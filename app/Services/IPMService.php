<?php

namespace App\Services;

use App\Models\IPM_UHH_SP;
use App\Models\IPM_HLS;
use App\Models\IPM_RLS;
use App\Models\IPM_PengeluaranPerKapita;
use App\Models\IPM_IndeksKesehatan;
use App\Models\IPM_IndeksPendidikan;
use App\Models\IPM_IndeksHidupLayak;
use App\Models\HumanDevelopmentIndex;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * Service untuk sync data IPM (Indeks Pembangunan Manusia) dari Google Sheets
 */
class IPMService
{
    protected $spreadsheetService;

    public function __construct(SpreadsheetService $spreadsheetService)
    {
        $this->spreadsheetService = $spreadsheetService;
    }

    /**
     * Helper method to sync IPM data generically using transactions, bulk upserts, and chunking
     */
    protected function syncGeneric(string $modelClass, string $sheetName, string $valueField = 'value'): array
    {
        try {
            $startTime = microtime(true);
            $modelName = class_basename($modelClass);
            echo "📊 Syncing {$modelName} data from sheet: {$sheetName}\n";
            $rawData = $this->spreadsheetService->fetchWorksheetData($sheetName);
            $processedData = $this->spreadsheetService->processSheetData($rawData);
            
            if (empty($processedData)) {
                echo "⚠️ No data found in sheet: {$sheetName}\n";
                return ['created' => 0, 'updated' => 0];
            }

            // Transform from wide format to long format
            $transformedData = $this->transformWideToLong($processedData);
            
            if (empty($transformedData)) {
                echo "⚠️ No valid records found in sheet: {$sheetName}\n";
                return ['created' => 0, 'updated' => 0];
            }

            echo "📝 Processing " . count($processedData) . " wide rows -> " . count($transformedData) . " long rows...\n";

            $records = [];
            $now = now();
            $skippedCount = 0;
            foreach ($transformedData as $index => $row) {
                if (empty($row['location_name']) || empty($row['year']) || $row['value'] === null) {
                    $skippedCount++;
                    if ($skippedCount <= 5) {
                        echo "⚠️  Skipping row " . ($index + 1) . " - missing location_name, year, or value. Data: " . json_encode($row) . "\n";
                        Log::warning("{$modelName} - Skipping row without location_name, year, or value. Row: " . json_encode($row));
                    }
                    continue;
                }

                $records[] = [
                    'location_name' => $row['location_name'],
                    'location_type' => $row['location_type'] ?? 'REGENCY',
                    'year' => (int) $row['year'],
                    $valueField => $row['value'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            $totalProcessed = count($records);
            $chunkSize = 500;
            $chunks = array_chunk($records, $chunkSize);
            $totalChunks = count($chunks);

            // Count before upsert to calculate inserted vs updated
            $countBefore = $modelClass::count();

            DB::transaction(function () use ($chunks, $modelClass, $valueField) {
                foreach ($chunks as $chunk) {
                    $modelClass::upsert(
                        $chunk,
                        ['location_name', 'year'],
                        ['location_type', $valueField, 'updated_at']
                    );
                }
            });

            $countAfter = $modelClass::count();
            $createdCount = max(0, $countAfter - $countBefore);
            $updatedCount = $totalProcessed - $createdCount;
            $duration = round(microtime(true) - $startTime, 2);

            $logMessage = "Sync {$modelName}\n"
                . "  Processed : {$totalProcessed}\n"
                . "  Inserted  : {$createdCount}\n"
                . "  Updated   : {$updatedCount}\n"
                . "  Skipped   : {$skippedCount}\n"
                . "  Chunks    : {$totalChunks}\n"
                . "  Duration  : {$duration} sec";

            echo "✅ {$logMessage}\n";
            Log::info($logMessage);

            return ['created' => $createdCount, 'updated' => $updatedCount];
        } catch (\Exception $e) {
            $modelName = class_basename($modelClass);
            Log::error("Error syncing {$modelName}: " . $e->getMessage());
            echo "❌ Error syncing {$modelName}: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    /**
     * Sync IPM UHH SP (Usia Harapan Hidup saat Lahir)
     */
    public function syncUHHSP(string $sheetName = 'IPM_UHH SP_Y-to-Y '): array
    {
        return $this->syncGeneric(IPM_UHH_SP::class, $sheetName, 'value');
    }

    /**
     * Sync IPM HLS (Harapan Lama Sekolah)
     */
    public function syncHLS(string $sheetName = 'IPM_HLS_Y-to-Y'): array
    {
        return $this->syncGeneric(IPM_HLS::class, $sheetName, 'value');
    }

    /**
     * Sync IPM RLS (Rata-rata Lama Sekolah)
     */
    public function syncRLS(string $sheetName = 'IPM_RLS_Y-to-Y'): array
    {
        return $this->syncGeneric(IPM_RLS::class, $sheetName, 'value');
    }

    /**
     * Sync IPM Pengeluaran Per Kapita
     */
    public function syncPengeluaranPerKapita(string $sheetName = 'IPM_Pengeluaran per kapita_Y-to-Y'): array
    {
        return $this->syncGeneric(IPM_PengeluaranPerKapita::class, $sheetName, 'value');
    }

    /**
     * Sync IPM Indeks Kesehatan
     */
    public function syncIndeksKesehatan(string $sheetName = 'IPM_Indeks Kesehatan_Y-to-Y'): array
    {
        return $this->syncGeneric(IPM_IndeksKesehatan::class, $sheetName, 'value');
    }

    /**
     * Sync IPM Indeks Pendidikan
     */
    public function syncIndeksPendidikan(string $sheetName = 'IPM_Indeks Pendidikan_Y-to-Y'): array
    {
        return $this->syncGeneric(IPM_IndeksPendidikan::class, $sheetName, 'value');
    }

    /**
     * Sync IPM Indeks Hidup Layak
     */
    public function syncIndeksHidupLayak(string $sheetName = 'IPM_Indeks Hidup Layak_Y-to-Y'): array
    {
        return $this->syncGeneric(IPM_IndeksHidupLayak::class, $sheetName, 'value');
    }

    /**
     * Sync IPM main data (Human Development Index) to humandevelopmentindex table
     */
    public function syncMainIPM(string $sheetName = 'Indeks Pembangunan Manusia Menu_Y-to-Y'): array
    {
        return $this->syncGeneric(HumanDevelopmentIndex::class, $sheetName, 'ipm_value');
    }

    /**
     * Sync all IPM data
     * Each method uses its own default sheet name
     */
    public function syncAll(?string $sheetName = null): array
    {
        // If sheetName is provided, use it for all (backward compatibility)
        // Otherwise, each method uses its own default sheet name
        if ($sheetName !== null) {
            $results = [
                'main' => $this->syncMainIPM($sheetName),
                'uhh_sp' => $this->syncUHHSP($sheetName),
                'hls' => $this->syncHLS($sheetName),
                'rls' => $this->syncRLS($sheetName),
                'pengeluaran_per_kapita' => $this->syncPengeluaranPerKapita($sheetName),
                'indeks_kesehatan' => $this->syncIndeksKesehatan($sheetName),
                'indeks_pendidikan' => $this->syncIndeksPendidikan($sheetName),
                'indeks_hidup_layak' => $this->syncIndeksHidupLayak($sheetName),
            ];
        } else {
            // Use default sheet name for each method
            $results = [
                'main' => $this->syncMainIPM(), // Uses 'Indeks Pembangunan Manusia Menu_Y-to-Y'
                'uhh_sp' => $this->syncUHHSP(), // Uses 'IPM_UHH SP_Y-to-Y '
                'hls' => $this->syncHLS(), // Uses 'IPM_HLS_Y-to-Y'
                'rls' => $this->syncRLS(), // Uses 'IPM_RLS_Y-to-Y'
                'pengeluaran_per_kapita' => $this->syncPengeluaranPerKapita(), // Uses 'IPM_Pengeluaran per kapita_Y-to-Y'
                'indeks_kesehatan' => $this->syncIndeksKesehatan(), // Uses 'IPM_Indeks Kesehatan_Y-to-Y'
                'indeks_pendidikan' => $this->syncIndeksPendidikan(), // Uses 'IPM_Indeks Pendidikan_Y-to-Y'
                'indeks_hidup_layak' => $this->syncIndeksHidupLayak(), // Uses 'IPM_Indeks Hidup Layak_Y-to-Y'
            ];
        }

        return $results;
    }

    /**
     * Transform wide format data (years as columns) to long format (location + year + value)
     * 
     * @param array $processedData Data from Google Sheets in wide format
     * @param string $locationKey Key name for location (default: auto-detect)
     * @return array Transformed data in long format
     */
    protected function transformWideToLong(array $processedData, ?string $locationKey = null): array
    {
        $transformed = [];
        
        // Auto-detect location key if not provided
        if (!$locationKey && !empty($processedData)) {
            $firstRow = $processedData[0];
            foreach (array_keys($firstRow) as $key) {
                $keyLower = strtolower($key);
                if (stripos($key, 'kabupaten') !== false || 
                    stripos($key, 'regency') !== false || 
                    stripos($key, 'municipality') !== false ||
                    stripos($key, 'location') !== false ||
                    stripos($key, 'wilayah') !== false ||
                    stripos($key, 'provinsi') !== false ||
                    $keyLower === 'provinsi' ||
                    stripos($key, 'kota') !== false) {
                    $locationKey = $key;
                    echo "🔍 Detected location key: '{$key}'\n";
                    Log::info("IPM Service - Detected location key: {$key}");
                    break;
                }
            }
        }
        
        if (!$locationKey) {
            echo "⚠️  Could not detect location key in data. Available keys: " . json_encode(array_keys($processedData[0] ?? [])) . "\n";
            Log::warning('IPM Service - Could not detect location key in data. Available keys: ' . json_encode(array_keys($processedData[0] ?? [])));
            return [];
        }
        
        $totalRowsProcessed = 0;
        $rowsWithValidData = 0;
        $seenBaseNames = [];
        
        foreach ($processedData as $row) {
            $locationName = $row[$locationKey] ?? null;
            
            if (empty($locationName)) {
                continue;
            }
            
            // Clean location name (remove newlines, extra spaces)
            $locationName = trim(preg_replace('/\s+/', ' ', $locationName));
            
            // Normalize base name
            $baseName = trim(str_ireplace(['Kabupaten ', 'Kab. ', 'Kota '], '', $locationName));
            $baseNameLower = strtolower($baseName);
            $locationType = 'REGENCY';
            $finalLocationName = $locationName;

            if ($baseNameLower === 'jawa timur' || strcasecmp($locationName, 'jawa timur') === 0) {
                $locationType = 'REGENCY';
                $finalLocationName = 'Jawa Timur';
            } elseif (in_array($baseNameLower, ['surabaya', 'batu'])) {
                $locationType = 'MUNICIPALITY';
                $finalLocationName = 'Kota ' . ucwords($baseNameLower);
            } elseif (in_array($baseNameLower, ['kediri', 'blitar', 'malang', 'probolinggo', 'pasuruan', 'mojokerto', 'madiun'])) {
                if (stripos($locationName, 'Kota') !== false) {
                    $locationType = 'MUNICIPALITY';
                    $finalLocationName = 'Kota ' . ucwords($baseNameLower);
                } elseif (stripos($locationName, 'Kab') !== false) {
                    $locationType = 'REGENCY';
                    $finalLocationName = ucwords($baseNameLower);
                } elseif (isset($seenBaseNames[$baseNameLower])) {
                    // Second occurrence without prefix -> it's Municipality!
                    $locationType = 'MUNICIPALITY';
                    $finalLocationName = 'Kota ' . ucwords($baseNameLower);
                } else {
                    // First occurrence without prefix -> it's Regency!
                    $locationType = 'REGENCY';
                    $finalLocationName = ucwords($baseNameLower);
                }
            } elseif (stripos($locationName, 'Kota') !== false) {
                $locationType = 'MUNICIPALITY';
            } elseif (stripos($locationName, 'Kab') !== false) {
                $locationType = 'REGENCY';
                $finalLocationName = ucwords($baseNameLower);
            }

            $seenBaseNames[$baseNameLower] = true;
            
            // Loop through all keys to find year columns
            foreach ($row as $key => $value) {
                // Skip location key and empty keys
                if ($key === $locationKey || $key === '' || empty($key)) {
                    continue;
                }
                
                // Check if key is a year (numeric, 4 digits, between 1900-2100)
                $year = null;
                if (is_numeric($key) && strlen((string)$key) === 4) {
                    $yearInt = (int)$key;
                    if ($yearInt >= 1900 && $yearInt <= 2100) {
                        $year = $yearInt;
                    }
                } elseif (is_string($key) && preg_match('/^\d{4}$/', trim($key))) {
                    $yearInt = (int)trim($key);
                    if ($yearInt >= 1900 && $yearInt <= 2100) {
                        $year = $yearInt;
                    }
                }
                
                // If it's a year column and has a value
                if ($year !== null && $value !== null && $value !== '') {
                    $parsedValue = $this->parseDecimal($value);
                    
                    // Skip if parsed value is null (invalid data)
                    if ($parsedValue === null) {
                        continue;
                    }
                    
                    $transformed[] = [
                        'location_name' => $finalLocationName,
                        'location_type' => $locationType,
                        'year' => $year,
                        'value' => $parsedValue,
                    ];
                    $rowsWithValidData++;
                }
            }
            $totalRowsProcessed++;
        }
        
        if (count($transformed) === 0 && !empty($processedData)) {
            echo "⚠️  Warning: No valid data transformed. Processed {$totalRowsProcessed} rows, but no valid year+value combinations found.\n";
            echo "   Sample row keys: " . json_encode(array_keys($processedData[0] ?? [])) . "\n";
            echo "   Sample row data: " . json_encode($processedData[0] ?? []) . "\n";
            Log::warning("IPM Service - No valid data transformed. Processed {$totalRowsProcessed} rows.");
        }
        
        return $transformed;
    }

    /**
     * Parse decimal value from string
     */
    protected function parseDecimal($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Remove commas and spaces
        $value = str_replace([',', ' '], '', $value);
        
        // Convert to float
        $floatValue = (float) $value;
        
        return $floatValue > 0 ? $floatValue : null;
    }

    /**
     * Helper method to add debug logging for sync operations
     */
    protected function addDebugLogging(string $methodName, array $processedData, int &$skippedCount, int &$errorCount, int $index, array $row, array $data, \Exception $e = null): void
    {
        if ($skippedCount <= 5 && empty($row['location_name']) || empty($row['year'])) {
            echo "⚠️  Skipping row " . ($index + 1) . " - missing location_name or year. Keys: " . json_encode(array_keys($row)) . "\n";
            Log::warning("{$methodName} - Skipping row without location_name or year. Row: " . json_encode($row));
        }
        
        if ($e) {
            echo "❌ Error saving row " . ($index + 1) . " (location: {$data['location_name']}, year: {$data['year']}): " . $e->getMessage() . "\n";
            Log::error("{$methodName} - Error saving row: " . $e->getMessage());
            Log::error("{$methodName} - Stack trace: " . $e->getTraceAsString());
        }
    }
}


