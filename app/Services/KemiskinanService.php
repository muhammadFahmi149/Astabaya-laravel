<?php

namespace App\Services;

use App\Models\KemiskinanSurabaya;
use App\Models\KemiskinanJawaTimur;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk sync data Kemiskinan dari Google Sheets
 */
class KemiskinanService
{
    protected $spreadsheetService;

    public function __construct(SpreadsheetService $spreadsheetService)
    {
        $this->spreadsheetService = $spreadsheetService;
    }

    /**
     * Process Kemiskinan data from raw sheet data
     * Format: 
     * - Row 0 = Headers (Indikator, 2003, 2004, 2005, etc.)
     * - Row 1+ = Data rows (indicator name in column A, values in year columns)
     * 
     * @param array $rawData Raw data from Google Sheets
     * @return array Processed records
     */
    protected function processKemiskinanData(array $rawData): array
    {
        if (empty($rawData) || count($rawData) < 2) {
            return [];
        }

        // Row 0 = Headers
        $headers = array_map(function($h) {
            return trim($h);
        }, $rawData[0]);
        
        $dataRows = array_slice($rawData, 1);

        // Find indicator column index (usually column A)
        $indicatorIdx = 0; // Default to first column
        
        // Find year columns (columns with 4-digit year)
        $yearCols = [];
        foreach ($headers as $idx => $header) {
            $headerTrimmed = trim($header);
            // Check if header is a 4-digit year (2000-2100)
            if (preg_match('/^(\d{4})$/', $headerTrimmed, $matches)) {
                $year = (int) $matches[1];
                if ($year >= 2000 && $year <= 2100) {
                    $yearCols[$year] = $idx;
                }
            }
        }

        if (empty($yearCols)) {
            echo "⚠️ No year columns found in headers\n";
            return [];
        }

        // Map indicator names to field names
        $indicatorMapping = [
            'jumlah penduduk miskin' => 'jumlah_penduduk_miskin',
            'persentase penduduk miskin' => 'persentase_penduduk_miskin',
            'indeks kedalaman kemiskinan' => 'indeks_kedalaman_kemiskinan_p1',
            'indeks kedalaman kemiskinan (p1)' => 'indeks_kedalaman_kemiskinan_p1',
            'indeks keparahan kemiskinan' => 'indeks_keparahan_kemiskinan_p2',
            'indeks keparahan kemiskinan (p2)' => 'indeks_keparahan_kemiskinan_p2',
            'garis kemiskinan' => 'garis_kemiskinan',
        ];

        // Process data rows
        $records = [];
        foreach ($dataRows as $row) {
            if (count($row) <= $indicatorIdx) {
                continue;
            }

            $indicatorName = isset($row[$indicatorIdx]) ? trim(strtolower((string) $row[$indicatorIdx])) : '';
            
            // Skip if indicator name is empty or doesn't match
            if (empty($indicatorName)) {
                continue;
            }

            // Find matching field name
            $fieldName = null;
            foreach ($indicatorMapping as $key => $field) {
                if (stripos($indicatorName, $key) !== false) {
                    $fieldName = $field;
                    break;
                }
            }

            if (!$fieldName) {
                continue;
            }

            // Process each year column
            foreach ($yearCols as $year => $colIdx) {
                if (isset($row[$colIdx])) {
                    $valueStr = trim((string) $row[$colIdx]);
                    
                    if ($valueStr !== '') {
                        $value = $this->parseDecimal($valueStr);
                        
                        if ($value !== null) {
                            // Initialize record if not exists
                            $key = "{$year}";
                            if (!isset($records[$key])) {
                                $records[$key] = [
                                    'year' => $year,
                                    'jumlah_penduduk_miskin' => null,
                                    'persentase_penduduk_miskin' => null,
                                    'indeks_kedalaman_kemiskinan_p1' => null,
                                    'indeks_keparahan_kemiskinan_p2' => null,
                                    'garis_kemiskinan' => null,
                                ];
                            }

                            // Set value based on field name
                            $records[$key][$fieldName] = $value;
                        }
                    }
                }
            }
        }

        return array_values($records);
    }

    /**
     * Sync Kemiskinan Surabaya data
     */
    public function syncSurabaya(string $sheetName = 'Kemiskinan(Surabaya)_YtoY'): array
    {
        try {
            echo "📊 Syncing Kemiskinan Surabaya data from sheet: {$sheetName}\n";
            $rawData = $this->spreadsheetService->fetchWorksheetData($sheetName);
            
            if (empty($rawData)) {
                echo "⚠️ No data found in sheet: {$sheetName}\n";
                return ['created' => 0, 'updated' => 0];
            }

            // Process data dengan format khusus Kemiskinan (matrix format)
            $processedRecords = $this->processKemiskinanData($rawData);
            
            if (empty($processedRecords)) {
                echo "⚠️ No valid records found in sheet: {$sheetName}\n";
                return ['created' => 0, 'updated' => 0];
            }

            echo "[OK] Data processed. Total records: " . count($processedRecords) . "\n";
            
            $createdCount = 0;
            $updatedCount = 0;

            foreach ($processedRecords as $record) {
                try {
                    $existing = KemiskinanSurabaya::where('year', $record['year'])->first();

                    if ($existing) {
                        $existing->update($record);
                        $updatedCount++;
                    } else {
                        KemiskinanSurabaya::create($record);
                        $createdCount++;
                    }
                } catch (\Exception $e) {
                    echo "❌ Error saving record: " . $e->getMessage() . "\n";
                    Log::error("Error saving Kemiskinan Surabaya record: " . $e->getMessage(), ['record' => $record]);
                    continue;
                }
            }

            echo "✅ Kemiskinan Surabaya sync completed. Created: {$createdCount}, Updated: {$updatedCount}\n";
            Log::info("Kemiskinan Surabaya sync completed. Created: {$createdCount}, Updated: {$updatedCount}");
            return ['created' => $createdCount, 'updated' => $updatedCount];
        } catch (\Exception $e) {
            Log::error('Error syncing Kemiskinan Surabaya: ' . $e->getMessage());
            echo "❌ Error syncing Kemiskinan Surabaya: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    /**
     * Sync Kemiskinan Jawa Timur data
     */
    public function syncJawaTimur(string $sheetName = 'Kemiskinan(JawaTimur)_YtoY_'): array
    {
        try {
            echo "📊 Syncing Kemiskinan Jawa Timur data from sheet: {$sheetName}\n";
            $rawData = $this->spreadsheetService->fetchWorksheetData($sheetName);
            
            if (empty($rawData)) {
                echo "⚠️ No data found in sheet: {$sheetName}\n";
                return ['created' => 0, 'updated' => 0];
            }

            // Process data dengan format khusus Kemiskinan (matrix format)
            $processedRecords = $this->processKemiskinanData($rawData);
            
            if (empty($processedRecords)) {
                echo "⚠️ No valid records found in sheet: {$sheetName}\n";
                return ['created' => 0, 'updated' => 0];
            }

            echo "[OK] Data processed. Total records: " . count($processedRecords) . "\n";
            
            $createdCount = 0;
            $updatedCount = 0;

            foreach ($processedRecords as $record) {
                try {
                    $existing = KemiskinanJawaTimur::where('year', $record['year'])->first();

                    if ($existing) {
                        $existing->update($record);
                        $updatedCount++;
                    } else {
                        KemiskinanJawaTimur::create($record);
                        $createdCount++;
                    }
                } catch (\Exception $e) {
                    echo "❌ Error saving record: " . $e->getMessage() . "\n";
                    Log::error("Error saving Kemiskinan Jawa Timur record: " . $e->getMessage(), ['record' => $record]);
                    continue;
                }
            }

            echo "✅ Kemiskinan Jawa Timur sync completed. Created: {$createdCount}, Updated: {$updatedCount}\n";
            Log::info("Kemiskinan Jawa Timur sync completed. Created: {$createdCount}, Updated: {$updatedCount}");
            return ['created' => $createdCount, 'updated' => $updatedCount];
        } catch (\Exception $e) {
            Log::error('Error syncing Kemiskinan Jawa Timur: ' . $e->getMessage());
            echo "❌ Error syncing Kemiskinan Jawa Timur: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    /**
     * Sync all Kemiskinan data
     */
    public function syncAll(): array
    {
        return [
            'surabaya' => $this->syncSurabaya(),
            'jawa_timur' => $this->syncJawaTimur(),
        ];
    }

    /**
     * Parse decimal value from string
     * Handles both comma and dot as decimal separator
     */
    protected function parseDecimal($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Convert to string and trim
        $value = trim((string) $value);
        
        // Replace comma with dot for decimal
        $value = str_replace(',', '.', $value);
        
        // Remove spaces
        $value = str_replace(' ', '', $value);
        
        try {
            $floatValue = (float) $value;
            // Accept both positive and negative values, and zero
            return is_numeric($floatValue) ? $floatValue : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}

