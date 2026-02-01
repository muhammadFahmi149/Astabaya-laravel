<?php

namespace App\Console\Commands;

use App\Services\IPMService;
use App\Services\GiniRatioService;
use App\Services\HotelOccupancyService;
use App\Services\InflasiService;
use App\Services\KemiskinanService;
use App\Services\KependudukanService;
use App\Services\KetenagakerjaanService;
use App\Services\PDRBService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Command untuk sync semua data dari Google Sheets ke database
 */
class SyncGoogleSheetsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:google-sheets 
                            {--service= : Sync specific service only (ipm, gini, hotel, inflasi, kemiskinan, kependudukan, ketenagakerjaan, pdrb)}
                            {--sheet= : Specific sheet name to sync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all data from Google Sheets to database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Google Sheets data synchronization...');

        $service = $this->option('service');
        $sheetName = $this->option('sheet');

        try {
            // Jika hanya sheet yang diberikan, detect service dari sheet name
            if ($sheetName && !$service) {
                $detectedService = $this->detectServiceFromSheetName($sheetName);
                if ($detectedService) {
                    $this->info("Detected service: {$detectedService} from sheet name: {$sheetName}");
                    $this->syncSpecificService($detectedService, $sheetName);
                } else {
                    $this->error("Could not detect service from sheet name: {$sheetName}");
                    $this->info('');
                    $this->info('Please specify --service option or use a recognizable sheet name.');
                    $this->info('');
                    $this->info('Available sheet patterns:');
                    $this->info('  - Inflasi: "Inflasi", "Inflasi_perkom_YYYY"');
                    $this->info('  - IPM: sheets containing "ipm"');
                    $this->info('  - Gini: sheets containing "gini"');
                    $this->info('  - Hotel: sheets containing "hotel" or "occupancy"');
                    $this->info('  - Kemiskinan: sheets containing "kemiskinan" or "poverty"');
                    $this->info('  - Kependudukan: sheets containing "kependudukan" or "population"');
                    $this->info('  - Ketenagakerjaan: sheets containing "ketenagakerjaan", "employment", or "labor"');
                    $this->info('  - PDRB: sheets containing "pdrb" or "gdp"');
                    $this->info('');
                    $this->info('Example: php artisan sync:google-sheets --sheet=Inflasi_perkom_2025');
                    $this->info('         php artisan sync:google-sheets --service=inflasi --sheet=Inflasi_perkom_2025');
                    return Command::FAILURE;
                }
            } elseif ($service) {
                $this->syncSpecificService($service, $sheetName);
            } else {
                $this->syncAllServices();
            }

            $this->info('Data synchronization completed successfully!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error during synchronization: ' . $e->getMessage());
            Log::error('Google Sheets sync error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Detect service from sheet name
     * 
     * @param string $sheetName
     * @return string|null
     */
    protected function detectServiceFromSheetName(string $sheetName): ?string
    {
        $sheetNameLower = strtolower($sheetName);

        // Inflasi patterns
        if (preg_match('/inflasi[_\s]*perkom[_\s]*\d{4}/i', $sheetName)) {
            return 'inflasi';
        }
        if (stripos($sheetName, 'inflasi') !== false) {
            return 'inflasi';
        }

        // IPM patterns
        if (stripos($sheetName, 'ipm') !== false) {
            return 'ipm';
        }

        // Gini Ratio patterns
        if (stripos($sheetName, 'gini') !== false) {
            return 'gini';
        }

        // Hotel Occupancy patterns
        if (stripos($sheetName, 'hotel') !== false || stripos($sheetName, 'occupancy') !== false) {
            return 'hotel';
        }

        // Kemiskinan patterns
        if (stripos($sheetName, 'kemiskinan') !== false || stripos($sheetName, 'poverty') !== false) {
            return 'kemiskinan';
        }

        // Kependudukan patterns
        if (stripos($sheetName, 'kependudukan') !== false || stripos($sheetName, 'population') !== false) {
            return 'kependudukan';
        }

        // Ketenagakerjaan patterns
        if (stripos($sheetName, 'ketenagakerjaan') !== false || stripos($sheetName, 'employment') !== false || stripos($sheetName, 'labor') !== false) {
            return 'ketenagakerjaan';
        }

        // PDRB patterns
        if (stripos($sheetName, 'pdrb') !== false || stripos($sheetName, 'gdp') !== false || stripos($sheetName, 'gross domestic product') !== false) {
            return 'pdrb';
        }

        return null;
    }

    /**
     * Sync all services
     */
    protected function syncAllServices(): void
    {
        $this->info('Syncing all services...');

        // IPM Service
        $this->info('Syncing IPM data...');
        $ipmService = app(IPMService::class);
        $ipmResults = $ipmService->syncAll();
        $this->displayResults('IPM', $ipmResults);

        // Gini Ratio Service
        $this->info('Syncing Gini Ratio data...');
        $giniService = app(GiniRatioService::class);
        $giniResults = $giniService->sync();
        $this->displayResults('Gini Ratio', $giniResults);

        // Hotel Occupancy Service
        $this->info('Syncing Hotel Occupancy data...');
        $hotelService = app(HotelOccupancyService::class);
        $hotelResults = $hotelService->syncAll();
        $this->displayResults('Hotel Occupancy', $hotelResults);

        // Inflasi Service
        $this->info('Syncing Inflasi data...');
        $inflasiService = app(InflasiService::class);
        $inflasiResults = $inflasiService->syncAll();
        $this->displayResults('Inflasi', $inflasiResults);

        // Kemiskinan Service
        $this->info('Syncing Kemiskinan data...');
        $kemiskinanService = app(KemiskinanService::class);
        $kemiskinanResults = $kemiskinanService->syncAll();
        $this->displayResults('Kemiskinan', $kemiskinanResults);

        // Kependudukan Service
        $this->info('Syncing Kependudukan data...');
        $kependudukanService = app(KependudukanService::class);
        $kependudukanResults = $kependudukanService->sync();
        $this->displayResults('Kependudukan', $kependudukanResults);

        // Ketenagakerjaan Service
        $this->info('Syncing Ketenagakerjaan data...');
        $ketenagakerjaanService = app(KetenagakerjaanService::class);
        $ketenagakerjaanResults = $ketenagakerjaanService->syncAll();
        $this->displayResults('Ketenagakerjaan', $ketenagakerjaanResults);

        // PDRB Service
        $this->info('Syncing PDRB data...');
        $pdrbService = app(PDRBService::class);
        $pdrbResults = $pdrbService->syncAll();
        $this->displayResults('PDRB', $pdrbResults);
    }

    /**
     * Sync specific service
     */
    protected function syncSpecificService(string $service, ?string $sheetName = null): void
    {
        switch (strtolower($service)) {
            case 'ipm':
                $this->info('Syncing IPM data...');
                $ipmService = app(IPMService::class);
                $results = $sheetName 
                    ? $ipmService->syncAll($sheetName)
                    : $ipmService->syncAll();
                $this->displayResults('IPM', $results);
                break;

            case 'gini':
                $this->info('Syncing Gini Ratio data...');
                $giniService = app(GiniRatioService::class);
                $results = $giniService->sync($sheetName ?? 'Gini Ratio');
                $this->displayResults('Gini Ratio', $results);
                break;

            case 'hotel':
                $this->info('Syncing Hotel Occupancy data...');
                $hotelService = app(HotelOccupancyService::class);
                // If sheetName provided, use it; otherwise syncAll will use default sheet names
                $results = $hotelService->syncAll($sheetName);
                $this->displayResults('Hotel Occupancy', $results);
                break;

            case 'inflasi':
                $this->info('Syncing Inflasi data...');
                $inflasiService = app(InflasiService::class);
                
                if ($sheetName) {
                    // Sync specific sheet
                    if (preg_match('/perkom[_\s]*\d{4}/i', $sheetName)) {
                        // Sync specific perkom sheet (e.g., Inflasi_perkom_2025)
                        $this->info("Syncing specific perkom sheet: {$sheetName}");
                        $results = $inflasiService->syncPerKomoditasFromSheet($sheetName);
                        $this->displayResults('Inflasi Per Komoditas', $results);
                    } else {
                        // Sync general Inflasi sheet (default: 'Inflasi' or custom name)
                        $this->info("Syncing Inflasi sheet: {$sheetName}");
                        $results = $inflasiService->sync($sheetName);
                        $this->displayResults('Inflasi', $results);
                    }
                } else {
                    // Sync all Inflasi data (general + all perkom sheets)
                    $results = $inflasiService->syncAll();
                    $this->displayResults('Inflasi', $results);
                }
                break;

            case 'kemiskinan':
                $this->info('Syncing Kemiskinan data...');
                $kemiskinanService = app(KemiskinanService::class);
                $results = $kemiskinanService->syncAll();
                $this->displayResults('Kemiskinan', $results);
                break;

            case 'kependudukan':
                $this->info('Syncing Kependudukan data...');
                $kependudukanService = app(KependudukanService::class);
                $results = $kependudukanService->sync($sheetName ?? 'Kependudukan_gabungan');
                $this->displayResults('Kependudukan', $results);
                break;

            case 'ketenagakerjaan':
                $this->info('Syncing Ketenagakerjaan data...');
                $ketenagakerjaanService = app(KetenagakerjaanService::class);
                // If sheetName provided, use it; otherwise syncAll will use default sheet names
                $results = $ketenagakerjaanService->syncAll($sheetName);
                $this->displayResults('Ketenagakerjaan', $results);
                break;

            case 'pdrb':
                $this->info('Syncing PDRB data...');
                $pdrbService = app(PDRBService::class);
                $results = $pdrbService->syncAll();
                $this->displayResults('PDRB', $results);
                break;

            default:
                $this->error("Unknown service: {$service}");
                $this->info('Available services: ipm, gini, hotel, inflasi, kemiskinan, kependudukan, ketenagakerjaan, pdrb');
                break;
        }
    }

    /**
     * Display sync results
     */
    protected function displayResults(string $serviceName, $results): void
    {
        if (is_array($results) && isset($results['created']) && isset($results['updated'])) {
            $this->line("  {$serviceName}: Created {$results['created']}, Updated {$results['updated']}");
        } elseif (is_array($results)) {
            foreach ($results as $key => $value) {
                if (is_array($value) && isset($value['created']) && isset($value['updated'])) {
                    $this->line("  {$serviceName} - {$key}: Created {$value['created']}, Updated {$value['updated']}");
                }
            }
        }
    }
}

