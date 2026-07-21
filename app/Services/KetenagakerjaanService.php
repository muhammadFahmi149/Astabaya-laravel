<?php

namespace App\Services;

use App\Models\KetenagakerjaanTPT;
use App\Models\KetenagakerjaanTPAK;
use Illuminate\Support\Facades\DB;
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
            $startTime = microtime(true);
            echo "📊 Syncing Ketenagakerjaan TPT data from sheet: {$sheetName}\n";
            $rawData = $this->spreadsheetService->fetchWorksheetData($sheetName);
            $processedData = $this->spreadsheetService->processSheetData($rawData);
            
            if (empty($processedData)) {
                echo "⚠️ No data found in sheet: {$sheetName}\n";
                return ['created' => 0, 'updated' => 0];
            }

            echo "[OK] Data processed. Total records: " . count($processedData) . "\n";

            $records = [];
            $now = now();
            foreach ($processedData as $rowIndex => $row) {
                // Try to find year with various possible field names
                $year = $row['year'] ?? $row['tahun'] ?? null;
                
                if (empty($year)) {
                    if ($rowIndex < 3) { // Log first 3 skipped rows for debugging
                        echo "⚠️ Skipping row " . ($rowIndex + 1) . ": year=" . ($year ?? 'null') . "\n";
                    }
                    continue;
                }

                $records[] = [
                    'year' => (int) $year,
                    'laki_laki' => $this->parseDecimal($row['laki_laki'] ?? $row['laki-laki'] ?? $row['tpt_laki_laki'] ?? null),
                    'perempuan' => $this->parseDecimal($row['perempuan'] ?? $row['tpt_perempuan'] ?? null),
                    'total' => $this->parseDecimal($row['total'] ?? $row['tpt_total'] ?? null),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            $totalProcessed = count($records);
            $chunkSize = 500;
            $chunks = array_chunk($records, $chunkSize);
            $totalChunks = count($chunks);

            // Count before upsert to calculate inserted vs updated
            $countBefore = KetenagakerjaanTPT::count();

            DB::transaction(function () use ($chunks) {
                foreach ($chunks as $chunk) {
                    KetenagakerjaanTPT::upsert(
                        $chunk,
                        ['year'],
                        ['laki_laki', 'perempuan', 'total', 'updated_at']
                    );
                }
            });

            $countAfter = KetenagakerjaanTPT::count();
            $createdCount = max(0, $countAfter - $countBefore);
            $updatedCount = $totalProcessed - $createdCount;
            $skippedCount = count($processedData) - $totalProcessed;
            $duration = round(microtime(true) - $startTime, 2);

            $logMessage = "Sync Ketenagakerjaan TPT\n"
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
            Log::error('Error syncing Ketenagakerjaan TPT: ' . $e->getMessage());
            echo "❌ Error syncing Ketenagakerjaan TPT: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    /**
     * Sync Ketenagakerjaan TPAK (Tingkat Partisipasi Angkatan Kerja) data
     */
    public function syncTPAK(string $sheetName = 'Ketenagakerjaan_TPAK'): array
    {
        try {
            $startTime = microtime(true);
            echo "📊 Syncing Ketenagakerjaan TPAK data from sheet: {$sheetName}\n";
            $rawData = $this->spreadsheetService->fetchWorksheetData($sheetName);
            $processedData = $this->spreadsheetService->processSheetData($rawData);
            
            if (empty($processedData)) {
                echo "⚠️ No data found in sheet: {$sheetName}\n";
                return ['created' => 0, 'updated' => 0];
            }

            echo "[OK] Data processed. Total records: " . count($processedData) . "\n";

            $records = [];
            $now = now();
            foreach ($processedData as $rowIndex => $row) {
                // Try to find year with various possible field names
                $year = $row['year'] ?? $row['tahun'] ?? null;
                
                if (empty($year)) {
                    if ($rowIndex < 3) { // Log first 3 skipped rows for debugging
                        echo "⚠️ Skipping row " . ($rowIndex + 1) . ": year=" . ($year ?? 'null') . "\n";
                    }
                    continue;
                }

                $records[] = [
                    'year' => (int) $year,
                    'laki_laki' => $this->parseDecimal($row['laki_laki'] ?? $row['laki-laki'] ?? $row['tpak_laki_laki'] ?? null),
                    'perempuan' => $this->parseDecimal($row['perempuan'] ?? $row['tpak_perempuan'] ?? null),
                    'total' => $this->parseDecimal($row['total'] ?? $row['tpak_total'] ?? null),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            $totalProcessed = count($records);
            $chunkSize = 500;
            $chunks = array_chunk($records, $chunkSize);
            $totalChunks = count($chunks);

            // Count before upsert to calculate inserted vs updated
            $countBefore = KetenagakerjaanTPAK::count();

            DB::transaction(function () use ($chunks) {
                foreach ($chunks as $chunk) {
                    KetenagakerjaanTPAK::upsert(
                        $chunk,
                        ['year'],
                        ['laki_laki', 'perempuan', 'total', 'updated_at']
                    );
                }
            });

            $countAfter = KetenagakerjaanTPAK::count();
            $createdCount = max(0, $countAfter - $countBefore);
            $updatedCount = $totalProcessed - $createdCount;
            $skippedCount = count($processedData) - $totalProcessed;
            $duration = round(microtime(true) - $startTime, 2);

            $logMessage = "Sync Ketenagakerjaan TPAK\n"
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
            Log::error('Error syncing Ketenagakerjaan TPAK: ' . $e->getMessage());
            echo "❌ Error syncing Ketenagakerjaan TPAK: " . $e->getMessage() . "\n";
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

