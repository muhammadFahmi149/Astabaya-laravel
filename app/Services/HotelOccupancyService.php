<?php

namespace App\Services;

use App\Models\HotelOccupancyYearly;
use App\Models\HotelOccupancyCombined;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk sync data Hotel Occupancy dari Google Sheets
 */
class HotelOccupancyService
{
    protected $spreadsheetService;

    public function __construct(SpreadsheetService $spreadsheetService)
    {
        $this->spreadsheetService = $spreadsheetService;
    }

    /**
     * Sync Hotel Occupancy Yearly data
     */
    public function syncYearly(string $sheetName = 'Tingkat Hunian Hotel_Y-to-Y'): array
    {
        try {
            echo "📊 Syncing Hotel Occupancy Yearly data from sheet: {$sheetName}\n";
            $rawData = $this->spreadsheetService->fetchWorksheetData($sheetName);
            $processedData = $this->spreadsheetService->processSheetData($rawData);
            
            $createdCount = 0;
            $updatedCount = 0;

            if (empty($processedData)) {
                echo "⚠️ No data found in sheet: {$sheetName}\n";
                return ['created' => 0, 'updated' => 0];
            }

            echo "[OK] Data processed. Total records: " . count($processedData) . "\n";

            foreach ($processedData as $rowIndex => $row) {
                // Try to find year with various possible field names
                $year = $row['year'] ?? $row['tahun'] ?? null;
                
                if (empty($year)) {
                    if ($rowIndex < 3) { // Log first 3 skipped rows for debugging
                        echo "⚠️ Skipping row " . ($rowIndex + 1) . ": year=" . ($year ?? 'null') . "\n";
                    }
                    continue;
                }

                $data = [
                    'year' => (int) $year,
                    'mktj' => $this->parseDecimal($row['mktj'] ?? null),
                    'tpk' => $this->parseDecimal($row['tpk'] ?? null),
                    'rlmta' => $this->parseDecimal($row['rlmta'] ?? null),
                    'rlmtnus' => $this->parseDecimal($row['rlmtnus'] ?? null),
                    'rlmtgab' => $this->parseDecimal($row['rlmtgab'] ?? null),
                    'gpr' => $this->parseDecimal($row['gpr'] ?? null),
                ];

                try {
                    $existing = HotelOccupancyYearly::where('year', $data['year'])->first();

                    if ($existing) {
                        $existing->update($data);
                        $updatedCount++;
                    } else {
                        HotelOccupancyYearly::create($data);
                        $createdCount++;
                    }
                } catch (\Exception $e) {
                    echo "❌ Error saving row " . ($rowIndex + 1) . ": " . $e->getMessage() . "\n";
                    Log::error("Error saving Hotel Occupancy Yearly row: " . $e->getMessage(), ['row' => $row, 'data' => $data]);
                    continue;
                }
            }

            echo "✅ Hotel Occupancy Yearly sync completed. Created: {$createdCount}, Updated: {$updatedCount}\n";
            Log::info("Hotel Occupancy Yearly sync completed. Created: {$createdCount}, Updated: {$updatedCount}");
            return ['created' => $createdCount, 'updated' => $updatedCount];
        } catch (\Exception $e) {
            Log::error('Error syncing Hotel Occupancy Yearly: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync Hotel Occupancy Combined data
     */
    public function syncCombined(string $sheetName = 'Tingkat Hunian Hotel_M-to-M'): array
    {
        try {
            echo "📊 Syncing Hotel Occupancy Combined data from sheet: {$sheetName}\n";
            $rawData = $this->spreadsheetService->fetchWorksheetData($sheetName);
            $processedData = $this->spreadsheetService->processSheetData($rawData);
            
            $createdCount = 0;
            $updatedCount = 0;

            if (empty($processedData)) {
                echo "⚠️ No data found in sheet: {$sheetName}\n";
                return ['created' => 0, 'updated' => 0];
            }

            echo "[OK] Data processed. Total records: " . count($processedData) . "\n";

            foreach ($processedData as $rowIndex => $row) {
                // Try to find year and month with various possible field names
                $year = $row['year'] ?? $row['tahun'] ?? null;
                $month = $row['month'] ?? $row['bulan'] ?? null;
                
                if (empty($year) || empty($month)) {
                    if ($rowIndex < 3) { // Log first 3 skipped rows for debugging
                        echo "⚠️ Skipping row " . ($rowIndex + 1) . ": year=" . ($year ?? 'null') . ", month=" . ($month ?? 'null') . "\n";
                    }
                    continue;
                }

                $data = [
                    'year' => (int) $year,
                    'month' => $this->normalizeMonth($month),
                    'mktj' => $this->parseDecimal($row['mktj'] ?? null),
                    'tpk' => $this->parseDecimal($row['tpk'] ?? null),
                    'rlmta' => $this->parseDecimal($row['rlmta'] ?? null),
                    'rlmtnus' => $this->parseDecimal($row['rlmtnus'] ?? null),
                    'rlmtgab' => $this->parseDecimal($row['rlmtgab'] ?? null),
                    'gpr' => $this->parseDecimal($row['gpr'] ?? null),
                ];

                try {
                    $existing = HotelOccupancyCombined::where('year', $data['year'])
                        ->where('month', $data['month'])
                        ->first();

                    if ($existing) {
                        $existing->update($data);
                        $updatedCount++;
                    } else {
                        HotelOccupancyCombined::create($data);
                        $createdCount++;
                    }
                } catch (\Exception $e) {
                    echo "❌ Error saving row " . ($rowIndex + 1) . ": " . $e->getMessage() . "\n";
                    Log::error("Error saving Hotel Occupancy Combined row: " . $e->getMessage(), ['row' => $row, 'data' => $data]);
                    continue;
                }
            }

            echo "✅ Hotel Occupancy Combined sync completed. Created: {$createdCount}, Updated: {$updatedCount}\n";
            Log::info("Hotel Occupancy Combined sync completed. Created: {$createdCount}, Updated: {$updatedCount}");
            return ['created' => $createdCount, 'updated' => $updatedCount];
        } catch (\Exception $e) {
            Log::error('Error syncing Hotel Occupancy Combined: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync all Hotel Occupancy data
     * If sheetName is provided, it will be used for both yearly and combined
     * Otherwise, uses default sheet names for each
     */
    public function syncAll(?string $sheetName = null): array
    {
        if ($sheetName) {
            // If specific sheet name provided, use it for both
            return [
                'yearly' => $this->syncYearly($sheetName),
                'combined' => $this->syncCombined($sheetName),
            ];
        } else {
            // Use default sheet names
            return [
                'yearly' => $this->syncYearly('Tingkat Hunian Hotel_Y-to-Y'),
                'combined' => $this->syncCombined('Tingkat Hunian Hotel_M-to-M'),
            ];
        }
    }

    /**
     * Normalize month value (convert to string if needed)
     */
    protected function normalizeMonth($month)
    {
        if (is_numeric($month)) {
            $monthInt = (int) $month;
            $monthNames = [
                1 => 'JANUARI', 2 => 'FEBRUARI', 3 => 'MARET', 4 => 'APRIL',
                5 => 'MEI', 6 => 'JUNI', 7 => 'JULI', 8 => 'AGUSTUS',
                9 => 'SEPTEMBER', 10 => 'OKTOBER', 11 => 'NOPEMBER', 12 => 'DESEMBER'
            ];
            return $monthNames[$monthInt] ?? strtoupper(trim((string) $month));
        }
        return strtoupper(trim((string) $month));
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

