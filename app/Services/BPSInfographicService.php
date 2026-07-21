<?php

namespace App\Services;

use App\Models\Infographic;
use Illuminate\Support\Facades\DB;
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

    public function saveInfographicToDb(array $infographicList): array
    {
        $startTime = microtime(true);
        $skippedCount = 0;

        echo "📝 Processing " . count($infographicList) . " infographics...\n";
        Log::info("Processing " . count($infographicList) . " infographics for database save");

        // Log first item structure for debugging
        if (!empty($infographicList)) {
            $firstItem = $infographicList[0];
            echo "🔍 Sample item structure: " . json_encode(array_keys($firstItem)) . "\n";
            Log::info("Sample item keys: " . json_encode(array_keys($firstItem)));
            Log::info("Sample item data: " . json_encode($firstItem));
        }

        $records = [];
        $now = now();
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

            $records[] = [
                'bps_id' => $bpsId,
                'title' => $item['title'] ?? '',
                'image' => $item['img'] ?? '',
                'dl' => $item['dl'] ?? '', // API menggunakan 'dl' bukan 'pdf'
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $totalProcessed = count($records);
        $chunkSize = 500;
        $chunks = array_chunk($records, $chunkSize);
        $totalChunks = count($chunks);

        // Count before upsert to calculate inserted vs updated
        $countBefore = Infographic::count();

        DB::transaction(function () use ($chunks) {
            foreach ($chunks as $chunk) {
                Infographic::upsert(
                    $chunk,
                    ['bps_id'],
                    ['title', 'image', 'dl', 'updated_at']
                );
            }
        });

        $countAfter = Infographic::count();
        $createdCount = max(0, $countAfter - $countBefore);
        $updatedCount = $totalProcessed - $createdCount;
        $duration = round(microtime(true) - $startTime, 2);

        $logMessage = "Sync Infographic\n"
            . "  Processed : {$totalProcessed}\n"
            . "  Inserted  : {$createdCount}\n"
            . "  Updated   : {$updatedCount}\n"
            . "  Skipped   : {$skippedCount}\n"
            . "  Chunks    : {$totalChunks}\n"
            . "  Duration  : {$duration} sec";

        echo "✅ {$logMessage}\n";
        Log::info($logMessage);

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

