<?php

namespace App\Services;

use App\Models\KetenagakerjaanTPT;
use App\Models\KetenagakerjaanTPAK;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk sync data Ketenagakerjaan dari Google Sheets
 */
class KetenagakerjaanService
{
    protected $spreadsheetService;

    public function __construct(SpreadsheetService $spreadsheetService)
    {
        $this->spreadsheetService = $spreadsheetService;
    }

    /**
     * Sync Ketenagakerjaan TPT (Tingkat Pengangguran Terbuka) data
     */
    public function syncTPT(string $sheetName = 'Ketenagakerjaan_TPT'): array
    {
        try {
            echo "📊 Syncing Ketenagakerjaan TPT data from sheet: {$sheetName}\n";
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
                    'laki_laki' => $this->parseDecimal($row['laki_laki'] ?? $row['laki-laki'] ?? $row['tpt_laki_laki'] ?? null),
                    'perempuan' => $this->parseDecimal($row['perempuan'] ?? $row['tpt_perempuan'] ?? null),
                    'total' => $this->parseDecimal($row['total'] ?? $row['tpt_total'] ?? null),
                ];

                try {
                    $existing = KetenagakerjaanTPT::where('year', $data['year'])->first();

                    if ($existing) {
                        $existing->update($data);
                        $updatedCount++;
                    } else {
                        KetenagakerjaanTPT::create($data);
                        $createdCount++;
                    }
                } catch (\Exception $e) {
                    echo "❌ Error saving row " . ($rowIndex + 1) . ": " . $e->getMessage() . "\n";
                    Log::error("Error saving Ketenagakerjaan TPT row: " . $e->getMessage(), ['row' => $row, 'data' => $data]);
                    continue;
                }
            }

            echo "✅ Ketenagakerjaan TPT sync completed. Created: {$createdCount}, Updated: {$updatedCount}\n";
            Log::info("Ketenagakerjaan TPT sync completed. Created: {$createdCount}, Updated: {$updatedCount}");
            return ['created' => $createdCount, 'updated' => $updatedCount];
        } catch (\Exception $e) {
            Log::error('Error syncing Ketenagakerjaan TPT: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync Ketenagakerjaan TPAK (Tingkat Partisipasi Angkatan Kerja) data
     */
    public function syncTPAK(string $sheetName = 'Ketenagakerjaan_TPAK'): array
    {
        try {
            echo "📊 Syncing Ketenagakerjaan TPAK data from sheet: {$sheetName}\n";
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
                    'laki_laki' => $this->parseDecimal($row['laki_laki'] ?? $row['laki-laki'] ?? $row['tpak_laki_laki'] ?? null),
                    'perempuan' => $this->parseDecimal($row['perempuan'] ?? $row['tpak_perempuan'] ?? null),
                    'total' => $this->parseDecimal($row['total'] ?? $row['tpak_total'] ?? null),
                ];

                try {
                    $existing = KetenagakerjaanTPAK::where('year', $data['year'])->first();

                    if ($existing) {
                        $existing->update($data);
                        $updatedCount++;
                    } else {
                        KetenagakerjaanTPAK::create($data);
                        $createdCount++;
                    }
                } catch (\Exception $e) {
                    echo "❌ Error saving row " . ($rowIndex + 1) . ": " . $e->getMessage() . "\n";
                    Log::error("Error saving Ketenagakerjaan TPAK row: " . $e->getMessage(), ['row' => $row, 'data' => $data]);
                    continue;
                }
            }

            echo "✅ Ketenagakerjaan TPAK sync completed. Created: {$createdCount}, Updated: {$updatedCount}\n";
            Log::info("Ketenagakerjaan TPAK sync completed. Created: {$createdCount}, Updated: {$updatedCount}");
            return ['created' => $createdCount, 'updated' => $updatedCount];
        } catch (\Exception $e) {
            Log::error('Error syncing Ketenagakerjaan TPAK: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync all Ketenagakerjaan data
     * If sheetName is provided, it will be used for both TPT and TPAK
     * Otherwise, uses default sheet names for each
     */
    public function syncAll(?string $sheetName = null): array
    {
        if ($sheetName) {
            // If specific sheet name provided, use it for both
            return [
                'tpt' => $this->syncTPT($sheetName),
                'tpak' => $this->syncTPAK($sheetName),
            ];
        } else {
            // Use default sheet names
            return [
                'tpt' => $this->syncTPT('Ketenagakerjaan_TPT'),
                'tpak' => $this->syncTPAK('Ketenagakerjaan_TPAK'),
            ];
        }
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

