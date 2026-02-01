<?php

namespace App\Services;

use App\Models\GiniRatio;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk sync data Gini Ratio dari Google Sheets
 */
class GiniRatioService
{
    protected $spreadsheetService;

    public function __construct(SpreadsheetService $spreadsheetService)
    {
        $this->spreadsheetService = $spreadsheetService;
    }

    /**
     * Process Gini Ratio data from raw sheet data
     * Format: 
     * - Row 0 = Headers (Kabupaten/Kota, 2018, 2019, 2020, etc.)
     * - Row 1+ = Data rows (location name in column A, values in year columns)
     * 
     * @param array $rawData Raw data from Google Sheets
     * @return array Processed records
     */
    protected function processGiniRatioData(array $rawData): array
    {
        if (empty($rawData) || count($rawData) < 2) {
            return [];
        }

        // Row 0 = Headers
        $headers = array_map(function($h) {
            return trim($h);
        }, $rawData[0]);
        
        $dataRows = array_slice($rawData, 1);

        // Find location_name column index (usually column A)
        $locationNameIdx = 0; // Default to first column
        
        // Try to find location column by checking header
        foreach ($headers as $idx => $header) {
            $headerLower = strtolower($header);
            if (stripos($headerLower, 'kabupaten') !== false || 
                stripos($headerLower, 'kota') !== false || 
                stripos($headerLower, 'location') !== false ||
                stripos($headerLower, 'wilayah') !== false ||
                stripos($headerLower, 'nama') !== false) {
                $locationNameIdx = $idx;
                break;
            }
        }

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

        // Process data rows
        $records = [];
        foreach ($dataRows as $row) {
            if (count($row) <= $locationNameIdx) {
                continue;
            }

            $locationName = isset($row[$locationNameIdx]) ? trim((string) $row[$locationNameIdx]) : '';
            
            // Skip if location name is empty
            if (empty($locationName)) {
                continue;
            }

            // Process each year column
            foreach ($yearCols as $year => $colIdx) {
                if (isset($row[$colIdx])) {
                    $valueStr = trim((string) $row[$colIdx]);
                    
                    if ($valueStr !== '') {
                        $value = $this->parseDecimal($valueStr);
                        
                        if ($value !== null) {
                            // Detect location_type from location_name
                            $locationType = $this->detectLocationType($locationName);
                            
                            $records[] = [
                                'location_name' => $locationName,
                                'location_type' => $locationType,
                                'year' => $year,
                                'gini_ratio_value' => $value
                            ];
                        }
                    }
                }
            }
        }

        return $records;
    }

    /**
     * Sync Gini Ratio data from Google Sheets
     */
    public function sync(string $sheetName = 'Gini Ratio'): array
    {
        try {
            echo "📊 Syncing Gini Ratio data from sheet: {$sheetName}\n";
            $rawData = $this->spreadsheetService->fetchWorksheetData($sheetName);
            
            if (empty($rawData)) {
                echo "⚠️ No data found in sheet: {$sheetName}\n";
                return ['created' => 0, 'updated' => 0];
            }

            // Process data dengan format khusus Gini Ratio (matrix format)
            $processedRecords = $this->processGiniRatioData($rawData);
            
            if (empty($processedRecords)) {
                echo "⚠️ No valid records found in sheet: {$sheetName}\n";
                return ['created' => 0, 'updated' => 0];
            }

            echo "[OK] Data processed. Total records: " . count($processedRecords) . "\n";
            
            $createdCount = 0;
            $updatedCount = 0;

            foreach ($processedRecords as $record) {
                try {
                    $existing = GiniRatio::where('location_name', $record['location_name'])
                        ->where('year', $record['year'])
                        ->first();

                    if ($existing) {
                        $existing->update($record);
                        $updatedCount++;
                    } else {
                        GiniRatio::create($record);
                        $createdCount++;
                    }
                } catch (\Exception $e) {
                    echo "❌ Error saving record: " . $e->getMessage() . "\n";
                    Log::error("Error saving Gini Ratio record: " . $e->getMessage(), ['record' => $record]);
                    continue;
                }
            }

            echo "✅ Gini Ratio sync completed. Created: {$createdCount}, Updated: {$updatedCount}\n";
            Log::info("Gini Ratio sync completed. Created: {$createdCount}, Updated: {$updatedCount}");
            return ['created' => $createdCount, 'updated' => $updatedCount];
        } catch (\Exception $e) {
            Log::error('Error syncing Gini Ratio: ' . $e->getMessage());
            echo "❌ Error syncing Gini Ratio: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    /**
     * Detect location type from location name
     * 
     * @param string $locationName
     * @return string 'REGENCY' or 'MUNICIPALITY'
     */
    protected function detectLocationType(string $locationName): string
    {
        $locationNameUpper = strtoupper(trim($locationName));
        
        // If starts with "KOTA", it's a MUNICIPALITY
        if (strpos($locationNameUpper, 'KOTA') === 0) {
            return 'MUNICIPALITY';
        }
        
        // If starts with "KABUPATEN", it's a REGENCY
        if (strpos($locationNameUpper, 'KABUPATEN') === 0) {
            return 'REGENCY';
        }
        
        // Default to REGENCY for other cases (like "Jawa Timur")
        return 'REGENCY';
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

