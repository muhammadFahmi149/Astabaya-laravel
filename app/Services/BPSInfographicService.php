<?php

namespace App\Services;

use App\Models\Infographic;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk fetch dan sync data Infographic dari BPS API
 * Equivalent to BPSInfographicService in Django
 */
class BPSInfographicService
{
    protected $apiKey;
    protected $baseUrl = 'https://webapi.bps.go.id/v1/api/list/model/infographic/lang/ind/domain/3578/key/';

    public function __construct()
    {
        $this->apiKey = config('services.bps.api_key', env('BPS_API_KEY', ''));
        $this->baseUrl .= $this->apiKey . '/';
    }

    /**
     * Fetch infographic data from BPS API
     */
    public function fetchInfographicData(): array
    {
        try {
            $firstPage = Http::get($this->baseUrl, ['page' => 1])->json();
            
            if (!isset($firstPage['data'][0]['pages'])) {
                return [];
            }

            $totalPages = $firstPage['data'][0]['pages'];
            $allInfographics = $firstPage['data'][1] ?? [];

            for ($page = 2; $page <= $totalPages; $page++) {
                echo "📡 Fetching page {$page} ...\n";
                Log::info("Fetching infographic page {$page}...");
                
                $response = Http::get($this->baseUrl, ['page' => $page]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['data'][1])) {
                        $allInfographics = array_merge($allInfographics, $data['data'][1]);
                    }
                }
            }

            echo "✅ Total infografis diambil: " . count($allInfographics) . "\n";
            Log::info("Total infographics fetched: " . count($allInfographics));
            return $allInfographics;
        } catch (\Exception $e) {
            Log::error('Error fetching infographic data: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Save infographic data to database
     */
    public function saveInfographicToDb(array $infographicList): array
    {
        $createdCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;
        $errorCount = 0;

        echo "📝 Processing " . count($infographicList) . " infographics...\n";
        Log::info("Processing " . count($infographicList) . " infographics for database save");

        // Log first item structure for debugging
        if (!empty($infographicList)) {
            $firstItem = $infographicList[0];
            echo "🔍 Sample item structure: " . json_encode(array_keys($firstItem)) . "\n";
            Log::info("Sample item keys: " . json_encode(array_keys($firstItem)));
            Log::info("Sample item data: " . json_encode($firstItem));
        }

        foreach ($infographicList as $index => $item) {
            // API menggunakan 'inf_id' bukan 'id'
            $bpsId = $item['inf_id'] ?? null;
            
            if (!$bpsId) {
                $skippedCount++;
                if ($skippedCount <= 5) {
                    echo "⚠️  Skipping item " . ($index + 1) . " - no ID found. Keys: " . json_encode(array_keys($item)) . "\n";
                    Log::warning("Skipping item without ID. Item keys: " . json_encode(array_keys($item)));
                }
                continue;
            }

            // Convert bpsId to string if it's not already
            $bpsId = (string) $bpsId;

            $data = [
                'bps_id' => $bpsId,
                'title' => $item['title'] ?? '',
                'image' => $item['img'] ?? '',
                'dl' => $item['dl'] ?? '', // API menggunakan 'dl' bukan 'pdf'
            ];

            try {
                $infographic = Infographic::where('bps_id', $bpsId)->first();
                
                if ($infographic) {
                    $infographic->update($data);
                    $updatedCount++;
                } else {
                    Infographic::create($data);
                    $createdCount++;
                }
            } catch (\Exception $e) {
                $errorCount++;
                echo "❌ Error saving infographic {$bpsId}: " . $e->getMessage() . "\n";
                Log::error("Error saving infographic {$bpsId}: " . $e->getMessage());
                Log::error("Stack trace: " . $e->getTraceAsString());
            }

            // Progress indicator every 100 items
            if (($index + 1) % 100 == 0) {
                echo "📊 Progress: " . ($index + 1) . "/" . count($infographicList) . " (Created: {$createdCount}, Updated: {$updatedCount}, Errors: {$errorCount})\n";
            }
        }

        echo "✅ Processing completed!\n";
        echo "   Created: {$createdCount} records\n";
        echo "   Updated: {$updatedCount} records\n";
        echo "   Skipped: {$skippedCount} records (no ID)\n";
        echo "   Errors: {$errorCount} records\n";
        
        Log::info("Infographic sync completed. Created: {$createdCount}, Updated: {$updatedCount}, Skipped: {$skippedCount}, Errors: {$errorCount}");
        return ['created' => $createdCount, 'updated' => $updatedCount];
    }

    /**
     * Sync infographics from BPS API
     */
    public function syncInfographic(): array
    {
        $infographicList = $this->fetchInfographicData();
        return $this->saveInfographicToDb($infographicList);
    }
}

