<?php

namespace App\Services;

use App\Models\Kependudukan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk sync data Kependudukan dari Google Sheets
 */
class KependudukanService
{
    protected $spreadsheetService;

    public function __construct(SpreadsheetService $spreadsheetService)
    {
        $this->spreadsheetService = $spreadsheetService;
    }

    /**
     * Process Kependudukan data from raw sheet data
     * Format: 
     * - Row 0 = Tahun headers (2015, 2016, 2017, etc.) - setiap tahun memakan 3 kolom
     * - Row 1 = Sub-headers (LK, PR, Total) untuk setiap tahun
     * - Row 2+ = Data umur (0-4, 5-9, etc.) dengan nilai untuk setiap tahun dan gender
     * 
     * @param array $rawData Raw data from Google Sheets
     * @return array Processed records
     */
    protected function processKependudukanData(array $rawData): array
    {
        if (empty($rawData) || count($rawData) < 3) {
            return [];
        }

        // Row 0 = Tahun headers
        // Row 1 = Sub-headers (LK, PR, Total)
        // Row 2+ = Data umur
        $yearRow = $rawData[0];
        $subheaderRow = $rawData[1];
        $dataRows = array_slice($rawData, 2);

        // Find age group column index (usually column A)
        $ageGroupIdx = 0;

        // Parse tahun dan gender columns
        $yearGenderCols = []; // ['year_gender' => column_index]
        $currentYear = null;
        
        for ($colIdx = 1; $colIdx < count($yearRow); $colIdx++) {
            $yearVal = trim($yearRow[$colIdx] ?? '');
            $subheaderVal = trim(strtoupper($subheaderRow[$colIdx] ?? ''));
            
            // Deteksi tahun
            if (preg_match('/^(\d{4})$/', $yearVal, $matches)) {
                $year = (int) $matches[1];
                if ($year >= 2000 && $year <= 2100) {
                    $currentYear = $year;
                }
            }
            
            // Deteksi gender (LK, PR, Total)
            if ($currentYear && $subheaderVal) {
                $gender = null;
                if (stripos($subheaderVal, 'LK') !== false || stripos($subheaderVal, 'LAKI') !== false || stripos($subheaderVal, 'MALE') !== false) {
                    $gender = 'LK';
                } elseif (stripos($subheaderVal, 'PR') !== false || stripos($subheaderVal, 'PEREMPUAN') !== false || stripos($subheaderVal, 'FEMALE') !== false) {
                    $gender = 'PR';
                } elseif (stripos($subheaderVal, 'TOTAL') !== false || stripos($subheaderVal, 'JUMLAH') !== false) {
                    $gender = 'TOTAL'; // Use uppercase to match enum
                }
                
                if ($gender && $currentYear) {
                    $key = "{$currentYear}_{$gender}";
                    $yearGenderCols[$key] = [
                        'year' => $currentYear,
                        'gender' => $gender,
                        'col_idx' => $colIdx
                    ];
                }
            }
        }

        if (empty($yearGenderCols)) {
            echo "⚠️ No year-gender columns found\n";
            return [];
        }

        // Process data rows
        $records = [];
        foreach ($dataRows as $row) {
            if (count($row) <= $ageGroupIdx) {
                continue;
            }

            $ageGroup = isset($row[$ageGroupIdx]) ? trim((string) $row[$ageGroupIdx]) : '';
            
            // Skip if age group is empty or is "JUMLAH" (total row)
            if (empty($ageGroup) || stripos($ageGroup, 'JUMLAH') !== false || stripos($ageGroup, 'TOTAL') !== false) {
                continue;
            }

            // Process each year-gender column
            foreach ($yearGenderCols as $yearGenderData) {
                $colIdx = $yearGenderData['col_idx'];
                
                if (isset($row[$colIdx])) {
                    $valueStr = trim((string) $row[$colIdx]);
                    
                    if ($valueStr !== '') {
                        $value = $this->parseInteger($valueStr);
                        
                        if ($value !== null) {
                            $records[] = [
                                'age_group' => $ageGroup,
                                'year' => $yearGenderData['year'],
                                'gender' => $yearGenderData['gender'],
                                'population' => $value
                            ];
                        }
                    }
                }
            }
        }

        return $records;
    }

    /**
     * Sync Kependudukan data
     */
    public function sync(string $sheetName = 'Kependudukan_gabungan'): array
    {
        try {
            $startTime = microtime(true);
            echo "📊 Syncing Kependudukan data from sheet: {$sheetName}\n";
            $rawData = $this->spreadsheetService->fetchWorksheetData($sheetName);
            
            if (empty($rawData)) {
                echo "⚠️ No data found in sheet: {$sheetName}\n";
                return ['created' => 0, 'updated' => 0];
            }

            // Process data dengan format khusus Kependudukan (matrix format)
            $processedRecords = $this->processKependudukanData($rawData);
            
            if (empty($processedRecords)) {
                echo "⚠️ No valid records found in sheet: {$sheetName}\n";
                return ['created' => 0, 'updated' => 0];
            }

            echo "[OK] Data processed. Total records: " . count($processedRecords) . "\n";
            
            $now = now();
            $records = [];
            foreach ($processedRecords as $record) {
                $records[] = $record + [
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            $totalProcessed = count($records);
            $chunkSize = 500;
            $chunks = array_chunk($records, $chunkSize);
            $totalChunks = count($chunks);

            // Count before upsert to calculate inserted vs updated
            $countBefore = Kependudukan::count();

            DB::transaction(function () use ($chunks) {
                foreach ($chunks as $chunk) {
                    Kependudukan::upsert(
                        $chunk,
                        ['age_group', 'year', 'gender'],
                        ['population', 'updated_at']
                    );
                }
            });

            $countAfter = Kependudukan::count();
            $createdCount = max(0, $countAfter - $countBefore);
            $updatedCount = $totalProcessed - $createdCount;
            $duration = round(microtime(true) - $startTime, 2);

            $logMessage = "Sync Kependudukan\n"
                . "  Processed : {$totalProcessed}\n"
                . "  Inserted  : {$createdCount}\n"
                . "  Updated   : {$updatedCount}\n"
                . "  Skipped   : 0\n"
                . "  Chunks    : {$totalChunks}\n"
                . "  Duration  : {$duration} sec";

            echo "✅ {$logMessage}\n";
            Log::info($logMessage);

            return ['created' => $createdCount, 'updated' => $updatedCount];
        } catch (\Exception $e) {
            Log::error('Error syncing Kependudukan: ' . $e->getMessage());
            echo "❌ Error syncing Kependudukan: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    /**
     * Parse integer value from string
     */
    protected function parseInteger($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Convert to string and trim
        $value = trim((string) $value);
        
        // Remove commas and spaces
        $value = str_replace([',', ' '], '', $value);
        
        try {
            $intValue = (int) $value;
            // Accept positive values and zero
            return $intValue >= 0 ? $intValue : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}

