<?php

namespace App\Services;

use App\Models\Inflasi;
use App\Models\InflasiPerKomoditas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk sync data Inflasi dari Google Sheets
 */
class InflasiService
{
    protected $spreadsheetService;

    /**
     * Mapping bulan dari nama Indonesia ke format model
     */
    protected const MONTH_MAPPING = [
        'JANUARI' => 'JANUARI',
        'FEBRUARI' => 'FEBRUARI',
        'MARET' => 'MARET',
        'APRIL' => 'APRIL',
        'MEI' => 'MEI',
        'JUNI' => 'JUNI',
        'JULI' => 'JULI',
        'AGUSTUS' => 'AGUSTUS',
        'SEPTEMBER' => 'SEPTEMBER',
        'OKTOBER' => 'OKTOBER',
        'NOPEMBER' => 'NOPEMBER',
        'NOVEMBER' => 'NOPEMBER', // Konversi "NOVEMBER" dari spreadsheet ke 'NOPEMBER'
        'DESEMBER' => 'DESEMBER'
    ];

    public function __construct(SpreadsheetService $spreadsheetService)
    {
        $this->spreadsheetService = $spreadsheetService;
    }

    /**
     * Process Inflasi general data from raw sheet data
     * Format: 
     * - Row 0 = Tahun headers (2008, 2009, etc.)
     * - Row 1 = Sub-headers (Bulanan, Kumulatif, YoY)
     * - Row 2+ = Data bulan (Januari, Februari, etc.) dengan bulan di kolom pertama
     * 
     * @param array $rawData Raw data from Google Sheets
     * @return array Processed records grouped by year-month
     */
    protected function processInflasiData(array $rawData): array
    {
        if (empty($rawData) || count($rawData) < 3) {
            return [];
        }

        // Row 0 = Tahun headers (2008, 2009, etc.)
        // Row 1 = Sub-headers (Bulanan, Kumulatif, YoY)
        // Row 2+ = Data bulan
        $yearRow = $rawData[0];
        $subheaderRow = $rawData[1];
        $dataRows = array_slice($rawData, 2);

        // Parse struktur kolom: tahun dan sub-header
        $records = [];
        $currentYear = null;
        $currentType = null;

        for ($colIdx = 0; $colIdx < count($yearRow); $colIdx++) {
            $yearVal = trim($yearRow[$colIdx] ?? '');
            $subheaderVal = trim(strtoupper($subheaderRow[$colIdx] ?? ''));

            // Deteksi tahun
            if ($yearVal && ctype_digit($yearVal)) {
                $currentYear = (int) $yearVal;
            }

            // Deteksi tipe (Bulanan, Kumulatif, YoY)
            if ($subheaderVal) {
                if (stripos($subheaderVal, 'BULANAN') !== false || stripos($subheaderVal, 'MONTHLY') !== false) {
                    $currentType = 'bulanan';
                } elseif (stripos($subheaderVal, 'KUMULATIF') !== false || stripos($subheaderVal, 'CUMULATIVE') !== false) {
                    $currentType = 'kumulatif';
                } elseif (stripos($subheaderVal, 'YOY') !== false || stripos($subheaderVal, 'YEAR') !== false) {
                    $currentType = 'yoy';
                } else {
                    $currentType = null;
                }
            }

            // Jika ada tahun dan tipe yang valid, ambil data
            if ($currentYear && $currentType && !empty($dataRows)) {
                foreach ($dataRows as $row) {
                    if ($colIdx >= count($row)) {
                        continue;
                    }

                    $monthName = isset($row[0]) ? trim(strtoupper($row[0])) : '';
                    $valueStr = isset($row[$colIdx]) ? trim((string) $row[$colIdx]) : '';

                    // Skip jika bukan bulan yang valid
                    if (!isset(self::MONTH_MAPPING[$monthName])) {
                        continue;
                    }

                    // Convert value
                    if ($valueStr !== '') {
                        $value = $this->parseDecimal($valueStr);

                        if ($value !== null) {
                            $month = self::MONTH_MAPPING[$monthName];
                            
                            // Initialize record if not exists
                            $key = "{$currentYear}_{$month}";
                            if (!isset($records[$key])) {
                                $records[$key] = [
                                    'year' => $currentYear,
                                    'month' => $month,
                                    'bulanan' => null,
                                    'kumulatif' => null,
                                    'yoy' => null,
                                ];
                            }

                            // Set value based on type
                            $records[$key][$currentType] = $value;
                        }
                    }
                }
            }
        }

        return array_values($records);
    }

    /**
     * Sync Inflasi data
     */
    public function sync(string $sheetName = 'Inflasi'): array
    {
        try {
            $startTime = microtime(true);
            echo "📊 Syncing Inflasi data from sheet: {$sheetName}\n";
            $rawData = $this->spreadsheetService->fetchWorksheetData($sheetName);
            
            if (empty($rawData)) {
                echo "⚠️ No data found in sheet: {$sheetName}\n";
                return ['created' => 0, 'updated' => 0];
            }

            // Process data dengan format khusus Inflasi
            $processedData = $this->processInflasiData($rawData);
            
            if (empty($processedData)) {
                echo "⚠️ No valid records found in sheet: {$sheetName}\n";
                return ['created' => 0, 'updated' => 0];
            }

            // Prepare records for bulk upsert
            $now = now();
            $records = [];

            foreach ($processedData as $row) {
                if (empty($row['year']) || empty($row['month'])) {
                    continue;
                }

                $records[] = [
                    'year' => (int) $row['year'],
                    'month' => $row['month'], // Keep as string (JANUARI, FEBRUARI, etc.)
                    'bulanan' => $this->parseDecimal($row['bulanan'] ?? null),
                    'kumulatif' => $this->parseDecimal($row['kumulatif'] ?? null),
                    'yoy' => $this->parseDecimal($row['yoy'] ?? null),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            $totalProcessed = count($records);
            $chunkSize = 500;
            $chunks = array_chunk($records, $chunkSize);
            $totalChunks = count($chunks);

            // Count before upsert to calculate inserted vs updated
            $countBefore = Inflasi::count();

            DB::transaction(function () use ($chunks) {
                foreach ($chunks as $chunk) {
                    Inflasi::upsert(
                        $chunk,
                        ['year', 'month'],
                        ['bulanan', 'kumulatif', 'yoy', 'updated_at']
                    );
                }
            });

            $countAfter = Inflasi::count();
            $createdCount = $countAfter - $countBefore;
            $updatedCount = $totalProcessed - $createdCount;
            $duration = round(microtime(true) - $startTime, 2);

            $logMessage = "Sync Inflasi\n"
                . "  Processed : {$totalProcessed}\n"
                . "  Inserted  : {$createdCount}\n"
                . "  Updated   : {$updatedCount}\n"
                . "  Chunks    : {$totalChunks}\n"
                . "  Duration  : {$duration} sec";

            echo "✅ {$logMessage}\n";
            Log::info($logMessage);

            return ['created' => $createdCount, 'updated' => $updatedCount];
        } catch (\Exception $e) {
            Log::error('Error syncing Inflasi: ' . $e->getMessage());
            echo "❌ Error syncing Inflasi: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    /**
     * Find all sheets with pattern 'Inflasi_perkom_YYYY'
     * 
     * @return array Array of sheet names matching the pattern
     */
    public function findPerKomSheets(): array
    {
        try {
            $allSheets = $this->spreadsheetService->getAllWorksheets();
            $perkomSheets = [];
            
            $pattern = '/Inflasi_perkom[_\s]*(\d{4})/i';
            
            foreach ($allSheets as $sheetName) {
                if (preg_match($pattern, $sheetName, $matches)) {
                    $year = (int) $matches[1];
                    // Validasi tahun yang masuk akal (2000-2100)
                    if ($year >= 2000 && $year <= 2100) {
                        $perkomSheets[] = $sheetName;
                    }
                }
            }
            
            sort($perkomSheets);
            echo "📋 Found " . count($perkomSheets) . " perkom sheets: " . implode(', ', $perkomSheets) . "\n";
            Log::info("Found " . count($perkomSheets) . " perkom sheets: " . implode(', ', $perkomSheets));
            
            return $perkomSheets;
        } catch (\Exception $e) {
            Log::error('Error finding perkom sheets: ' . $e->getMessage());
            echo "⚠️ Error finding perkom sheets: " . $e->getMessage() . "\n";
            return [];
        }
    }

    /**
     * Extract year from sheet name (e.g., "Inflasi_perkom_2025" -> 2025)
     * 
     * @param string $sheetName
     * @return int|null
     */
    protected function extractYearFromSheetName(string $sheetName): ?int
    {
        // Pattern yang lebih spesifik untuk mencari tahun 4 digit setelah "perkom"
        if (preg_match('/perkom[_\s]*(\d{4})/i', $sheetName, $matches)) {
            $year = (int) $matches[1];
            // Validasi tahun yang masuk akal (2000-2100)
            if ($year >= 2000 && $year <= 2100) {
                return $year;
            }
        }
        
        // Fallback: cari tahun 4 digit pertama yang ditemukan
        if (preg_match('/(\d{4})/', $sheetName, $matches)) {
            $year = (int) $matches[1];
            if ($year >= 2000 && $year <= 2100) {
                return $year;
            }
        }
        
        return null;
    }

    /**
     * Process Inflasi Per Komoditas data from raw sheet data
     * Format: 
     * - Column A = Kode Komoditas
     * - Column B = Nama Komoditas
     * - Column C = Flag
     * - Column D+ = Bulan (JANUARI, FEBRUARI, etc.)
     * 
     * @param array $rawData Raw data from Google Sheets
     * @param int $year Year extracted from sheet name
     * @return array Processed records
     */
    protected function processPerKomData(array $rawData, int $year): array
    {
        if (empty($rawData) || count($rawData) < 2) {
            return [];
        }

        // Row 0 = Headers (Kode Komoditas, Nama Komoditas, Flag, JANUARI, FEBRUARI, etc.)
        $headers = array_map(function($h) {
            return trim(strtoupper($h));
        }, $rawData[0]);
        
        $dataRows = array_slice($rawData, 1);

        // Find column indices
        $kodeIdx = null;
        $namaIdx = null;
        $flagIdx = null;
        $monthCols = []; // month_name => column_index

        foreach ($headers as $idx => $header) {
            if (stripos($header, 'KODE') !== false || stripos($header, 'CODE') !== false) {
                $kodeIdx = $idx;
            } elseif (stripos($header, 'NAMA') !== false || stripos($header, 'NAME') !== false || stripos($header, 'KOMODITAS') !== false) {
                $namaIdx = $idx;
            } elseif (stripos($header, 'FLAG') !== false) {
                $flagIdx = $idx;
            } elseif (isset(self::MONTH_MAPPING[$header])) {
                $monthCols[self::MONTH_MAPPING[$header]] = $idx;
            }
        }

        if ($kodeIdx === null || $namaIdx === null) {
            echo "⚠️ Could not find required columns (Kode or Nama) in sheet\n";
            return [];
        }

        // Process data rows
        $records = [];
        foreach ($dataRows as $row) {
            if (count($row) <= max($kodeIdx, $namaIdx)) {
                continue;
            }

            $kode = isset($row[$kodeIdx]) ? trim((string) $row[$kodeIdx]) : '';
            $nama = isset($row[$namaIdx]) ? trim((string) $row[$namaIdx]) : '';
            $flag = ($flagIdx !== null && isset($row[$flagIdx])) ? trim((string) $row[$flagIdx]) : '';

            // Skip jika kode atau nama kosong
            if (empty($kode) || empty($nama)) {
                continue;
            }

            // Process monthly values
            foreach ($monthCols as $month => $colIdx) {
                if (isset($row[$colIdx])) {
                    $valueStr = trim((string) $row[$colIdx]);
                    
                    if ($valueStr !== '') {
                        $value = $this->parseDecimal($valueStr);
                        
                        if ($value !== null) {
                            $records[] = [
                                'commodity_code' => $kode,
                                'commodity_name' => $nama,
                                'flag' => $flag ?: null,
                                'year' => $year,
                                'month' => $month,
                                'value' => $value
                            ];
                        }
                    }
                }
            }
        }

        return $records;
    }

    /**
     * Sync Inflasi Per Komoditas data from a specific sheet
     * 
     * @param string $sheetName Sheet name (e.g., 'Inflasi_perkom_2025')
     * @return array
     */
    public function syncPerKomoditasFromSheet(string $sheetName): array
    {
        try {
            $startTime = microtime(true);
            echo "📊 Syncing Inflasi Per Komoditas data from sheet: {$sheetName}\n";
            
            // Extract year from sheet name
            $year = $this->extractYearFromSheetName($sheetName);
            if ($year === null) {
                echo "⚠️ Could not extract year from sheet name: {$sheetName}\n";
                Log::warning("Could not extract year from sheet name: {$sheetName}");
                return ['created' => 0, 'updated' => 0];
            }

            // Fetch raw data
            $rawData = $this->spreadsheetService->fetchWorksheetData($sheetName);
            if (empty($rawData)) {
                echo "⚠️ No data found in sheet: {$sheetName}\n";
                return ['created' => 0, 'updated' => 0];
            }

            // Process data
            $processedRecords = $this->processPerKomData($rawData, $year);
            
            if (empty($processedRecords)) {
                echo "⚠️ No valid records found in sheet: {$sheetName}\n";
                return ['created' => 0, 'updated' => 0];
            }

            // Prepare records for bulk upsert with timestamps
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
            $countBefore = InflasiPerKomoditas::count();

            DB::transaction(function () use ($chunks) {
                foreach ($chunks as $chunk) {
                    // IMPORTANT: Include 'flag' in unique key because same commodity_code
                    // can exist with different flags (e.g., code "11" can be Flag 1 or Flag 2)
                    InflasiPerKomoditas::upsert(
                        $chunk,
                        ['commodity_code', 'flag', 'year', 'month'],
                        ['commodity_name', 'value', 'updated_at']
                    );
                }
            });

            $countAfter = InflasiPerKomoditas::count();
            $createdCount = $countAfter - $countBefore;
            $updatedCount = $totalProcessed - $createdCount;
            $duration = round(microtime(true) - $startTime, 2);

            $logMessage = "Sync Inflasi Per Komoditas [{$sheetName}]\n"
                . "  Processed : {$totalProcessed}\n"
                . "  Inserted  : {$createdCount}\n"
                . "  Updated   : {$updatedCount}\n"
                . "  Chunks    : {$totalChunks}\n"
                . "  Duration  : {$duration} sec";

            echo "✅ {$logMessage}\n";
            Log::info($logMessage);
            
            return ['created' => $createdCount, 'updated' => $updatedCount];
        } catch (\Exception $e) {
            Log::error("Error syncing Inflasi Per Komoditas from sheet '{$sheetName}': " . $e->getMessage());
            echo "❌ Error syncing sheet '{$sheetName}': " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    /**
     * Sync Inflasi Per Komoditas data from all matching sheets
     * Automatically finds all sheets with pattern 'Inflasi_perkom_YYYY'
     * 
     * @return array Summary of all sync operations
     */
    public function syncPerKomoditas(): array
    {
        try {
            echo "\n" . str_repeat("=", 60) . "\n";
            echo "SYNCING INFLASI PER KOMODITAS DATA\n";
            echo str_repeat("=", 60) . "\n\n";

            $perkomSheets = $this->findPerKomSheets();
            
            if (empty($perkomSheets)) {
                echo "⚠️ No perkom sheets found\n";
                Log::warning("No perkom sheets found");
                return ['total_created' => 0, 'total_updated' => 0, 'sheets' => []];
            }

            $results = [];
            $totalCreated = 0;
            $totalUpdated = 0;

            foreach ($perkomSheets as $sheetName) {
                echo "\n[PROCESSING] Sheet '{$sheetName}'...\n";
                $result = $this->syncPerKomoditasFromSheet($sheetName);
                $results[$sheetName] = $result;
                $totalCreated += $result['created'];
                $totalUpdated += $result['updated'];
            }

            echo "\n" . str_repeat("=", 60) . "\n";
            echo "INFLASI PER KOMODITAS SYNC COMPLETE - SUMMARY\n";
            echo str_repeat("=", 60) . "\n";
            foreach ($results as $sheetName => $counts) {
                echo "{$sheetName}: {$counts['created']} created, {$counts['updated']} updated\n";
            }
            echo "TOTAL: {$totalCreated} created, {$totalUpdated} updated\n";
            echo str_repeat("=", 60) . "\n\n";

            Log::info("Inflasi Per Komoditas sync completed. Total: {$totalCreated} created, {$totalUpdated} updated");

            return [
                'total_created' => $totalCreated,
                'total_updated' => $totalUpdated,
                'sheets' => $results
            ];
        } catch (\Exception $e) {
            Log::error('Error syncing Inflasi Per Komoditas: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync all Inflasi data
     */
    public function syncAll(): array
    {
        return [
            'inflasi' => $this->sync(),
            'inflasi_per_komoditas' => $this->syncPerKomoditas(),
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

