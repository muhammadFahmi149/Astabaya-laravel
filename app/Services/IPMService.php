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
     * Sync IPM UHH SP (Usia Harapan Hidup saat Lahir)
     */
    public function syncUHHSP(string $sheetName = 'IPM_UHH SP_Y-to-Y '): array
    {
        try {
            echo "📊 Syncing IPM UHH SP data from sheet: {$sheetName}\n";
            $rawData = $this->spreadsheetService->fetchWorksheetData($sheetName);
            $processedData = $this->spreadsheetService->processSheetData($rawData);
            
            // Transform from wide format to long format
            $transformedData = $this->transformWideToLong($processedData);
            
            $createdCount = 0;
            $updatedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;

            echo "📝 Processing " . count($processedData) . " wide rows -> " . count($transformedData) . " long rows...\n";
            
            // Log first row structure for debugging
            if (!empty($processedData)) {
                $firstRow = $processedData[0];
                echo "🔍 Sample wide row structure: " . json_encode(array_keys($firstRow)) . "\n";
                Log::info("IPM UHH SP - Sample wide row keys: " . json_encode(array_keys($firstRow)));
            }
            
            if (!empty($transformedData)) {
                $firstTransformed = $transformedData[0];
                echo "🔍 Sample transformed row: " . json_encode($firstTransformed) . "\n";
                Log::info("IPM UHH SP - Sample transformed row: " . json_encode($firstTransformed));
            }

            foreach ($transformedData as $index => $row) {
                if (empty($row['location_name']) || empty($row['year']) || $row['value'] === null) {
                    $skippedCount++;
                    if ($skippedCount <= 5) {
                        echo "⚠️  Skipping row " . ($index + 1) . " - missing location_name, year, or value. Data: " . json_encode($row) . "\n";
                        Log::warning("IPM UHH SP - Skipping row without location_name, year, or value. Row: " . json_encode($row));
                    }
                    continue;
                }

                $data = [
                    'location_name' => $row['location_name'],
                    'location_type' => $row['location_type'] ?? 'REGENCY',
                    'year' => (int) $row['year'],
                    'value' => $row['value'],
                ];

                try {
                    $existing = IPM_UHH_SP::where('location_name', $data['location_name'])
                        ->where('year', $data['year'])
                        ->first();

                    if ($existing) {
                        $existing->update($data);
                        $updatedCount++;
                    } else {
                        IPM_UHH_SP::create($data);
                        $createdCount++;
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    echo "❌ Error saving row " . ($index + 1) . " (location: {$data['location_name']}, year: {$data['year']}): " . $e->getMessage() . "\n";
                    Log::error("IPM UHH SP - Error saving row: " . $e->getMessage());
                    Log::error("IPM UHH SP - Stack trace: " . $e->getTraceAsString());
                }
            }

            $totalProcessed = $createdCount + $updatedCount;
            echo "✅ IPM UHH SP sync completed!\n";
            echo "   Created: {$createdCount} records\n";
            echo "   Updated: {$updatedCount} records\n";
            echo "   Total processed: {$totalProcessed} records\n";
            echo "   Skipped: {$skippedCount} records (missing location_name or year)\n";
            echo "   Errors: {$errorCount} records\n";
            Log::info("IPM UHH SP sync completed. Created: {$createdCount}, Updated: {$updatedCount}, Total: {$totalProcessed}, Skipped: {$skippedCount}, Errors: {$errorCount}");
            return ['created' => $createdCount, 'updated' => $updatedCount];
        } catch (\Exception $e) {
            Log::error('Error syncing IPM UHH SP: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync IPM HLS (Harapan Lama Sekolah)
     */
    public function syncHLS(string $sheetName = 'IPM_HLS_Y-to-Y'): array
    {
        try {
            echo "📊 Syncing IPM HLS data from sheet: {$sheetName}\n";
            $rawData = $this->spreadsheetService->fetchWorksheetData($sheetName);
            $processedData = $this->spreadsheetService->processSheetData($rawData);
            
            // Transform from wide format to long format
            $transformedData = $this->transformWideToLong($processedData);
            
            $createdCount = 0;
            $updatedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;

            echo "📝 Processing " . count($processedData) . " wide rows -> " . count($transformedData) . " long rows...\n";

            foreach ($transformedData as $index => $row) {
                if (empty($row['location_name']) || empty($row['year']) || $row['value'] === null) {
                    $skippedCount++;
                    if ($skippedCount <= 5) {
                        echo "⚠️  Skipping row " . ($index + 1) . " - missing location_name, year, or value. Data: " . json_encode($row) . "\n";
                        Log::warning("IPM HLS - Skipping row without location_name, year, or value. Row: " . json_encode($row));
                    }
                    continue;
                }

                $data = [
                    'location_name' => $row['location_name'],
                    'location_type' => $row['location_type'] ?? 'REGENCY',
                    'year' => (int) $row['year'],
                    'value' => $row['value'],
                ];

                try {
                    $existing = IPM_HLS::where('location_name', $data['location_name'])
                        ->where('year', $data['year'])
                        ->first();

                    if ($existing) {
                        $existing->update($data);
                        $updatedCount++;
                    } else {
                        IPM_HLS::create($data);
                        $createdCount++;
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    echo "❌ Error saving row " . ($index + 1) . " (location: {$data['location_name']}, year: {$data['year']}): " . $e->getMessage() . "\n";
                    Log::error("IPM HLS - Error saving row: " . $e->getMessage());
                    Log::error("IPM HLS - Stack trace: " . $e->getTraceAsString());
                }
            }

            $totalProcessed = $createdCount + $updatedCount;
            echo "✅ IPM HLS sync completed!\n";
            echo "   Created: {$createdCount} records\n";
            echo "   Updated: {$updatedCount} records\n";
            echo "   Total processed: {$totalProcessed} records\n";
            echo "   Skipped: {$skippedCount} records (missing location_name or year)\n";
            echo "   Errors: {$errorCount} records\n";
            Log::info("IPM HLS sync completed. Created: {$createdCount}, Updated: {$updatedCount}, Total: {$totalProcessed}, Skipped: {$skippedCount}, Errors: {$errorCount}");
            return ['created' => $createdCount, 'updated' => $updatedCount];
        } catch (\Exception $e) {
            Log::error('Error syncing IPM HLS: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync IPM RLS (Rata-rata Lama Sekolah)
     */
    public function syncRLS(string $sheetName = 'IPM_RLS_Y-to-Y'): array
    {
        try {
            echo "📊 Syncing IPM RLS data from sheet: {$sheetName}\n";
            $rawData = $this->spreadsheetService->fetchWorksheetData($sheetName);
            $processedData = $this->spreadsheetService->processSheetData($rawData);
            
            // Transform from wide format to long format
            $transformedData = $this->transformWideToLong($processedData);
            
            $createdCount = 0;
            $updatedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;

            echo "📝 Processing " . count($processedData) . " wide rows -> " . count($transformedData) . " long rows...\n";

            foreach ($transformedData as $index => $row) {
                if (empty($row['location_name']) || empty($row['year']) || $row['value'] === null) {
                    $skippedCount++;
                    if ($skippedCount <= 5) {
                        echo "⚠️  Skipping row " . ($index + 1) . " - missing location_name, year, or value. Data: " . json_encode($row) . "\n";
                        Log::warning("IPM RLS - Skipping row without location_name, year, or value. Row: " . json_encode($row));
                    }
                    continue;
                }

                $data = [
                    'location_name' => $row['location_name'],
                    'location_type' => $row['location_type'] ?? 'REGENCY',
                    'year' => (int) $row['year'],
                    'value' => $row['value'],
                ];

                try {
                    $existing = IPM_RLS::where('location_name', $data['location_name'])
                        ->where('year', $data['year'])
                        ->first();

                    if ($existing) {
                        $existing->update($data);
                        $updatedCount++;
                    } else {
                        IPM_RLS::create($data);
                        $createdCount++;
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    echo "❌ Error saving row " . ($index + 1) . " (location: {$data['location_name']}, year: {$data['year']}): " . $e->getMessage() . "\n";
                    Log::error("IPM RLS - Error saving row: " . $e->getMessage());
                    Log::error("IPM RLS - Stack trace: " . $e->getTraceAsString());
                }
            }

            $totalProcessed = $createdCount + $updatedCount;
            echo "✅ IPM RLS sync completed!\n";
            echo "   Created: {$createdCount} records\n";
            echo "   Updated: {$updatedCount} records\n";
            echo "   Total processed: {$totalProcessed} records\n";
            echo "   Skipped: {$skippedCount} records (missing location_name or year)\n";
            echo "   Errors: {$errorCount} records\n";
            Log::info("IPM RLS sync completed. Created: {$createdCount}, Updated: {$updatedCount}, Total: {$totalProcessed}, Skipped: {$skippedCount}, Errors: {$errorCount}");
            return ['created' => $createdCount, 'updated' => $updatedCount];
        } catch (\Exception $e) {
            Log::error('Error syncing IPM RLS: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync IPM Pengeluaran Per Kapita
     */
    public function syncPengeluaranPerKapita(string $sheetName = 'IPM_Pengeluaran per kapita_Y-to-Y'): array
    {
        try {
            echo "📊 Syncing IPM Pengeluaran Per Kapita data from sheet: {$sheetName}\n";
            $rawData = $this->spreadsheetService->fetchWorksheetData($sheetName);
            $processedData = $this->spreadsheetService->processSheetData($rawData);
            
            // Transform from wide format to long format
            $transformedData = $this->transformWideToLong($processedData);
            
            $createdCount = 0;
            $updatedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;

            echo "📝 Processing " . count($processedData) . " wide rows -> " . count($transformedData) . " long rows...\n";

            foreach ($transformedData as $index => $row) {
                if (empty($row['location_name']) || empty($row['year']) || $row['value'] === null) {
                    $skippedCount++;
                    if ($skippedCount <= 5) {
                        echo "⚠️  Skipping row " . ($index + 1) . " - missing location_name, year, or value. Data: " . json_encode($row) . "\n";
                        Log::warning("IPM Pengeluaran Per Kapita - Skipping row without location_name, year, or value. Row: " . json_encode($row));
                    }
                    continue;
                }

                $data = [
                    'location_name' => $row['location_name'],
                    'location_type' => $row['location_type'] ?? 'REGENCY',
                    'year' => (int) $row['year'],
                    'value' => $row['value'],
                ];
                
                try {
                    $existing = IPM_PengeluaranPerKapita::where('location_name', $data['location_name'])
                        ->where('year', $data['year'])
                        ->first();

                    if ($existing) {
                        $existing->update($data);
                        $updatedCount++;
                    } else {
                        IPM_PengeluaranPerKapita::create($data);
                        $createdCount++;
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    echo "❌ Error saving row " . ($index + 1) . ": " . $e->getMessage() . "\n";
                    Log::error("IPM Pengeluaran Per Kapita - Error: " . $e->getMessage());
                }
            }

            $totalProcessed = $createdCount + $updatedCount;
            echo "✅ IPM Pengeluaran Per Kapita sync completed!\n";
            echo "   Created: {$createdCount} records\n";
            echo "   Updated: {$updatedCount} records\n";
            echo "   Total processed: {$totalProcessed} records\n";
            echo "   Skipped: {$skippedCount} records\n";
            echo "   Errors: {$errorCount} records\n";
            Log::info("IPM Pengeluaran Per Kapita sync completed. Created: {$createdCount}, Updated: {$updatedCount}, Total: {$totalProcessed}, Skipped: {$skippedCount}, Errors: {$errorCount}");
            return ['created' => $createdCount, 'updated' => $updatedCount];
        } catch (\Exception $e) {
            Log::error('Error syncing IPM Pengeluaran Per Kapita: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync IPM Indeks Kesehatan
     */
    public function syncIndeksKesehatan(string $sheetName = 'IPM_Indeks Kesehatan_Y-to-Y'): array
    {
        try {
            echo "📊 Syncing IPM Indeks Kesehatan data from sheet: {$sheetName}\n";
            $rawData = $this->spreadsheetService->fetchWorksheetData($sheetName);
            $processedData = $this->spreadsheetService->processSheetData($rawData);
            
            // Transform from wide format to long format
            $transformedData = $this->transformWideToLong($processedData);
            
            $createdCount = 0;
            $updatedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;

            echo "📝 Processing " . count($processedData) . " wide rows -> " . count($transformedData) . " long rows...\n";

            foreach ($transformedData as $index => $row) {
                if (empty($row['location_name']) || empty($row['year']) || $row['value'] === null) {
                    $skippedCount++;
                    if ($skippedCount <= 5) {
                        echo "⚠️  Skipping row " . ($index + 1) . " - missing location_name, year, or value. Data: " . json_encode($row) . "\n";
                        Log::warning("IPM Indeks Kesehatan - Skipping row without location_name, year, or value. Row: " . json_encode($row));
                    }
                    continue;
                }

                $data = [
                    'location_name' => $row['location_name'],
                    'location_type' => $row['location_type'] ?? 'REGENCY',
                    'year' => (int) $row['year'],
                    'value' => $row['value'],
                ];
                
                try {
                    $existing = IPM_IndeksKesehatan::where('location_name', $data['location_name'])
                        ->where('year', $data['year'])
                        ->first();

                    if ($existing) {
                        $existing->update($data);
                        $updatedCount++;
                    } else {
                        IPM_IndeksKesehatan::create($data);
                        $createdCount++;
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    echo "❌ Error saving row " . ($index + 1) . ": " . $e->getMessage() . "\n";
                    Log::error("IPM Indeks Kesehatan - Error: " . $e->getMessage());
                }
            }

            $totalProcessed = $createdCount + $updatedCount;
            echo "✅ IPM Indeks Kesehatan sync completed!\n";
            echo "   Created: {$createdCount} records\n";
            echo "   Updated: {$updatedCount} records\n";
            echo "   Total processed: {$totalProcessed} records\n";
            echo "   Skipped: {$skippedCount} records\n";
            echo "   Errors: {$errorCount} records\n";
            Log::info("IPM Indeks Kesehatan sync completed. Created: {$createdCount}, Updated: {$updatedCount}, Total: {$totalProcessed}, Skipped: {$skippedCount}, Errors: {$errorCount}");
            return ['created' => $createdCount, 'updated' => $updatedCount];
        } catch (\Exception $e) {
            Log::error('Error syncing IPM Indeks Kesehatan: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync IPM Indeks Pendidikan
     */
    public function syncIndeksPendidikan(string $sheetName = 'IPM_Indeks Pendidikan_Y-to-Y'): array
    {
        try {
            echo "📊 Syncing IPM Indeks Pendidikan data from sheet: {$sheetName}\n";
            $rawData = $this->spreadsheetService->fetchWorksheetData($sheetName);
            $processedData = $this->spreadsheetService->processSheetData($rawData);
            
            // Transform from wide format to long format
            $transformedData = $this->transformWideToLong($processedData);
            
            $createdCount = 0;
            $updatedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;

            echo "📝 Processing " . count($processedData) . " wide rows -> " . count($transformedData) . " long rows...\n";

            foreach ($transformedData as $index => $row) {
                if (empty($row['location_name']) || empty($row['year']) || $row['value'] === null) {
                    $skippedCount++;
                    if ($skippedCount <= 5) {
                        echo "⚠️  Skipping row " . ($index + 1) . " - missing location_name, year, or value. Data: " . json_encode($row) . "\n";
                        Log::warning("IPM Indeks Pendidikan - Skipping row without location_name, year, or value. Row: " . json_encode($row));
                    }
                    continue;
                }

                $data = [
                    'location_name' => $row['location_name'],
                    'location_type' => $row['location_type'] ?? 'REGENCY',
                    'year' => (int) $row['year'],
                    'value' => $row['value'],
                ];
                
                try {
                    $existing = IPM_IndeksPendidikan::where('location_name', $data['location_name'])
                        ->where('year', $data['year'])
                        ->first();

                    if ($existing) {
                        $existing->update($data);
                        $updatedCount++;
                    } else {
                        IPM_IndeksPendidikan::create($data);
                        $createdCount++;
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    echo "❌ Error saving row " . ($index + 1) . ": " . $e->getMessage() . "\n";
                    Log::error("IPM Indeks Pendidikan - Error: " . $e->getMessage());
                }
            }

            $totalProcessed = $createdCount + $updatedCount;
            echo "✅ IPM Indeks Pendidikan sync completed!\n";
            echo "   Created: {$createdCount} records\n";
            echo "   Updated: {$updatedCount} records\n";
            echo "   Total processed: {$totalProcessed} records\n";
            echo "   Skipped: {$skippedCount} records\n";
            echo "   Errors: {$errorCount} records\n";
            Log::info("IPM Indeks Pendidikan sync completed. Created: {$createdCount}, Updated: {$updatedCount}, Total: {$totalProcessed}, Skipped: {$skippedCount}, Errors: {$errorCount}");
            return ['created' => $createdCount, 'updated' => $updatedCount];
        } catch (\Exception $e) {
            Log::error('Error syncing IPM Indeks Pendidikan: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync IPM Indeks Hidup Layak
     */
    public function syncIndeksHidupLayak(string $sheetName = 'IPM_Indeks Hidup Layak_Y-to-Y'): array
    {
        try {
            echo "📊 Syncing IPM Indeks Hidup Layak data from sheet: {$sheetName}\n";
            $rawData = $this->spreadsheetService->fetchWorksheetData($sheetName);
            $processedData = $this->spreadsheetService->processSheetData($rawData);
            
            // Transform from wide format to long format
            $transformedData = $this->transformWideToLong($processedData);
            
            $createdCount = 0;
            $updatedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;

            echo "📝 Processing " . count($processedData) . " wide rows -> " . count($transformedData) . " long rows...\n";

            foreach ($transformedData as $index => $row) {
                if (empty($row['location_name']) || empty($row['year']) || $row['value'] === null) {
                    $skippedCount++;
                    if ($skippedCount <= 5) {
                        echo "⚠️  Skipping row " . ($index + 1) . " - missing location_name, year, or value. Data: " . json_encode($row) . "\n";
                        Log::warning("IPM Indeks Hidup Layak - Skipping row without location_name, year, or value. Row: " . json_encode($row));
                    }
                    continue;
                }

                $data = [
                    'location_name' => $row['location_name'],
                    'location_type' => $row['location_type'] ?? 'REGENCY',
                    'year' => (int) $row['year'],
                    'value' => $row['value'],
                ];
                
                try {
                    $existing = IPM_IndeksHidupLayak::where('location_name', $data['location_name'])
                        ->where('year', $data['year'])
                        ->first();

                    if ($existing) {
                        $existing->update($data);
                        $updatedCount++;
                    } else {
                        IPM_IndeksHidupLayak::create($data);
                        $createdCount++;
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    echo "❌ Error saving row " . ($index + 1) . ": " . $e->getMessage() . "\n";
                    Log::error("IPM Indeks Hidup Layak - Error: " . $e->getMessage());
                }
            }

            $totalProcessed = $createdCount + $updatedCount;
            echo "✅ IPM Indeks Hidup Layak sync completed!\n";
            echo "   Created: {$createdCount} records\n";
            echo "   Updated: {$updatedCount} records\n";
            echo "   Total processed: {$totalProcessed} records\n";
            echo "   Skipped: {$skippedCount} records\n";
            echo "   Errors: {$errorCount} records\n";
            Log::info("IPM Indeks Hidup Layak sync completed. Created: {$createdCount}, Updated: {$updatedCount}, Total: {$totalProcessed}, Skipped: {$skippedCount}, Errors: {$errorCount}");
            return ['created' => $createdCount, 'updated' => $updatedCount];
        } catch (\Exception $e) {
            Log::error('Error syncing IPM Indeks Hidup Layak: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync IPM main data (Human Development Index) to humandevelopmentindex table
     */
    public function syncMainIPM(string $sheetName = 'Indeks Pembangunan Manusia Menu_Y-to-Y'): array
    {
        try {
            echo "📊 Syncing IPM main data (Human Development Index) from sheet: {$sheetName}\n";
            $rawData = $this->spreadsheetService->fetchWorksheetData($sheetName);
            $processedData = $this->spreadsheetService->processSheetData($rawData);
            
            // Transform from wide format to long format
            $transformedData = $this->transformWideToLong($processedData);
            
            $createdCount = 0;
            $updatedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;

            echo "📝 Processing " . count($processedData) . " wide rows -> " . count($transformedData) . " long rows...\n";
            
            // Log first row structure for debugging
            if (!empty($transformedData)) {
                $firstRow = $transformedData[0];
                echo "🔍 Sample transformed row: " . json_encode($firstRow) . "\n";
                Log::info("IPM Main - Sample transformed row: " . json_encode($firstRow));
            }

            foreach ($transformedData as $index => $row) {
                if (empty($row['location_name']) || empty($row['year']) || $row['value'] === null) {
                    $skippedCount++;
                    if ($skippedCount <= 5) {
                        echo "⚠️  Skipping row " . ($index + 1) . " - missing location_name, year, or value. Row: " . json_encode($row) . "\n";
                        Log::warning("IPM Main - Skipping row without location_name, year, or value. Row: " . json_encode($row));
                    }
                    continue;
                }

                $data = [
                    'location_name' => $row['location_name'],
                    'location_type' => $row['location_type'] ?? 'REGENCY',
                    'year' => (int) $row['year'],
                    'ipm_value' => $row['value'],
                ];

                try {
                    $existing = HumanDevelopmentIndex::where('location_name', $data['location_name'])
                        ->where('year', $data['year'])
                        ->first();

                    if ($existing) {
                        $existing->update($data);
                        $updatedCount++;
                    } else {
                        HumanDevelopmentIndex::create($data);
                        $createdCount++;
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    echo "❌ Error saving row " . ($index + 1) . " (location: {$data['location_name']}, year: {$data['year']}): " . $e->getMessage() . "\n";
                    Log::error("IPM Main - Error saving row: " . $e->getMessage());
                    Log::error("IPM Main - Stack trace: " . $e->getTraceAsString());
                }
            }

            $totalProcessed = $createdCount + $updatedCount;
            echo "✅ IPM Main (Human Development Index) sync completed!\n";
            echo "   Created: {$createdCount} records\n";
            echo "   Updated: {$updatedCount} records\n";
            echo "   Total processed: {$totalProcessed} records\n";
            echo "   Skipped: {$skippedCount} records (missing location_name, year, or value)\n";
            echo "   Errors: {$errorCount} records\n";
            Log::info("IPM Main sync completed. Created: {$createdCount}, Updated: {$updatedCount}, Total: {$totalProcessed}, Skipped: {$skippedCount}, Errors: {$errorCount}");
            return ['created' => $createdCount, 'updated' => $updatedCount];
        } catch (\Exception $e) {
            Log::error('Error syncing IPM Main: ' . $e->getMessage());
            throw $e;
        }
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
        
        foreach ($processedData as $row) {
            $locationName = $row[$locationKey] ?? null;
            
            if (empty($locationName)) {
                continue;
            }
            
            // Clean location name (remove newlines, extra spaces)
            $locationName = trim(preg_replace('/\s+/', ' ', $locationName));
            
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
                    
                    // Determine location type based on location name
                    // If location name contains "Kota" or is a known city, use MUNICIPALITY, otherwise REGENCY
                    $locationType = 'REGENCY'; // Default
                    $locationLower = strtolower($locationName);
                    if (stripos($locationName, 'Kota') !== false || 
                        in_array($locationName, ['Surabaya', 'Malang', 'Kediri', 'Blitar', 'Probolinggo', 'Pasuruan', 'Mojokerto', 'Madiun', 'Batu'])) {
                        $locationType = 'MUNICIPALITY';
                    }
                    
                    $transformed[] = [
                        'location_name' => $locationName,
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


