<?php

namespace App\Services;

use App\Models\Publication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk fetch dan sync data Publication dari BPS API
 * Equivalent to BPSPublicationService in Django
 */
class BPSPublicationService
{
    protected $apiKey;
    protected $baseUrl = 'https://webapi.bps.go.id/v1/api/list/model/publication/lang/ind/domain/3578/key/';

    public function __construct()
    {
        $this->apiKey = config('services.bps.api_key', env('BPS_API_KEY', ''));
        $this->baseUrl .= $this->apiKey . '/';
    }

    /**
     * Clean abstract text from special characters
     */
    protected function cleanAbstract(?string $abstract): string
    {
        if (empty($abstract)) {
            return '';
        }

        // Handle escape sequences
        $abstract = preg_replace('/\\\\u000D\\\\u000A/i', ' ', $abstract);
        $abstract = preg_replace('/\\\\u000D/i', ' ', $abstract);
        $abstract = preg_replace('/\\\\u000A/i', ' ', $abstract);
        $abstract = preg_replace('/\\\\u0009/i', ' ', $abstract);
        $abstract = preg_replace('/\\\\r\\\\n/i', ' ', $abstract);
        $abstract = preg_replace('/\\\\n/i', ' ', $abstract);
        $abstract = preg_replace('/\\\\r/i', ' ', $abstract);
        $abstract = preg_replace('/\\\\t/i', ' ', $abstract);

        // Decode Unicode escape sequences
        $abstract = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($matches) {
            return chr(hexdec($matches[1]));
        }, $abstract);

        // Remove control characters
        $abstract = preg_replace('/[\r\n]+/', ' ', $abstract);
        $abstract = preg_replace('/\p{Cc}+/u', ' ', $abstract);
        $abstract = preg_replace('/[\s\t]+/', ' ', $abstract);

        return trim($abstract);
    }

    /**
     * Fetch publication data from BPS API
     */
    public function fetchPublicationData(): array
    {
        try {
            // Check if API key is set
            if (empty($this->apiKey)) {
                echo "❌ Error: BPS API key tidak ditemukan!\n";
                echo "   Pastikan BPS_API_KEY sudah di-set di file .env\n";
                Log::error('BPS API key is not set');
                return [];
            }

            echo "📡 Fetching publications from BPS API...\n";
            echo "   API URL: " . $this->baseUrl . "\n";
            Log::info("Fetching publications from BPS API", ['url' => $this->baseUrl]);

            $response = Http::get($this->baseUrl, ['page' => 1]);
            
            // Check if HTTP request was successful
            if (!$response->successful()) {
                echo "❌ Error: HTTP request gagal!\n";
                echo "   Status Code: " . $response->status() . "\n";
                echo "   Response: " . $response->body() . "\n";
                Log::error('BPS API HTTP request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return [];
            }

            $firstPage = $response->json();
            
            // Log the response structure for debugging
            Log::info('BPS API first page response', [
                'has_data' => isset($firstPage['data']),
                'data_keys' => isset($firstPage['data']) ? array_keys($firstPage['data']) : null,
                'full_response' => $firstPage
            ]);

            // Check if response has expected structure
            if (!isset($firstPage['data'])) {
                echo "❌ Error: Struktur response API tidak sesuai!\n";
                echo "   Response tidak memiliki key 'data'\n";
                echo "   Response keys: " . json_encode(array_keys($firstPage)) . "\n";
                Log::error('BPS API response missing data key', ['response' => $firstPage]);
                return [];
            }

            if (!isset($firstPage['data'][0]['pages'])) {
                echo "❌ Error: Struktur response API tidak sesuai!\n";
                echo "   Response tidak memiliki 'data[0][pages]'\n";
                echo "   Data structure: " . json_encode($firstPage['data'] ?? []) . "\n";
                Log::error('BPS API response missing pages info', [
                    'data_structure' => $firstPage['data'] ?? []
                ]);
                return [];
            }

            $totalPages = $firstPage['data'][0]['pages'];
            $allPublications = $firstPage['data'][1] ?? [];

            echo "📄 Total pages: {$totalPages}\n";
            echo "📚 Publications on page 1: " . count($allPublications) . "\n";
            Log::info("BPS API pagination info", [
                'total_pages' => $totalPages,
                'first_page_count' => count($allPublications)
            ]);

            for ($page = 2; $page <= $totalPages; $page++) {
                echo "📡 Fetching page {$page}/{$totalPages} ...\n";
                Log::info("Fetching publication page {$page}...");
                
                $response = Http::get($this->baseUrl, ['page' => $page]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['data'][1])) {
                        $allPublications = array_merge($allPublications, $data['data'][1]);
                    } else {
                        echo "⚠️  Warning: Page {$page} tidak memiliki data[1]\n";
                        Log::warning("Page {$page} missing data[1]", ['response' => $data]);
                    }
                } else {
                    echo "⚠️  Warning: Gagal mengambil page {$page} (Status: {$response->status()})\n";
                    Log::warning("Failed to fetch page {$page}", [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                }
            }

            echo "✅ Total publikasi diambil: " . count($allPublications) . "\n";
            Log::info("Total publications fetched: " . count($allPublications));
            return $allPublications;
        } catch (\Exception $e) {
            echo "❌ Exception: " . $e->getMessage() . "\n";
            echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n";
            Log::error('Error fetching publication data: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return [];
        }
    }

    public function savePublicationToDb(array $publicationList): array
    {
        $startTime = microtime(true);
        $skippedCount = 0;

        echo "📝 Processing " . count($publicationList) . " publications...\n";
        Log::info("Processing " . count($publicationList) . " publications for database save");

        // Log first item structure for debugging
        if (!empty($publicationList)) {
            $firstItem = $publicationList[0];
            echo "🔍 Sample item structure: " . json_encode(array_keys($firstItem)) . "\n";
            Log::info("Sample item keys: " . json_encode(array_keys($firstItem)));
            Log::info("Sample item data: " . json_encode($firstItem));
        }

        $records = [];
        $now = now();
        foreach ($publicationList as $index => $item) {
            $pubId = $item['pub_id'] ?? null;
            
            if (!$pubId) {
                $skippedCount++;
                if ($skippedCount <= 5) {
                    echo "⚠️  Skipping item " . ($index + 1) . " - no ID found. Keys: " . json_encode(array_keys($item)) . "\n";
                    Log::warning("Skipping publication without ID. Item keys: " . json_encode(array_keys($item)));
                }
                continue;
            }

            // Convert pubId to string if it's not already
            $pubId = (string) $pubId;

            $dlValue = $item['pdf'] ?? '';
            if (strlen($dlValue) > 500) {
                $dlValue = substr($dlValue, 0, 500);
            }

            $imageValue = $item['cover'] ?? '';
            if (strlen($imageValue) > 500) {
                $imageValue = substr($imageValue, 0, 500);
            }

            $records[] = [
                'pub_id' => $pubId,
                'title' => $item['title'] ?? '',
                'abstract' => $this->cleanAbstract($item['abstract'] ?? ''),
                'image' => $imageValue,
                'dl' => $dlValue,
                'date' => $item['rl_date'] ?? null,
                'size' => $item['size'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $totalProcessed = count($records);
        $chunkSize = 500;
        $chunks = array_chunk($records, $chunkSize);
        $totalChunks = count($chunks);

        // Count before upsert to calculate inserted vs updated
        $countBefore = Publication::count();

        DB::transaction(function () use ($chunks) {
            foreach ($chunks as $chunk) {
                Publication::upsert(
                    $chunk,
                    ['pub_id'],
                    ['title', 'abstract', 'image', 'dl', 'date', 'size', 'updated_at']
                );
            }
        });

        $countAfter = Publication::count();
        $createdCount = max(0, $countAfter - $countBefore);
        $updatedCount = $totalProcessed - $createdCount;
        $duration = round(microtime(true) - $startTime, 2);

        $logMessage = "Sync Publication\n"
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
     * Sync publications from BPS API
     */
    public function syncPublication(): array
    {
        $publicationList = $this->fetchPublicationData();
        return $this->savePublicationToDb($publicationList);
    }

    /**
     * Fetch publication detail data directly from BPS API (Proxy API)
     * This is used for on-the-fly fetching of complete details like abstract.
     * 
     * @param string $pubId
     * @return array|null
     */
    public function getDetailFromBps(string $pubId): ?array
    {
        try {
            // https://webapi.bps.go.id/v1/api/view/domain/3578/model/publication/lang/ind/id/{id}/key/{key}/
            $url = 'https://webapi.bps.go.id/v1/api/view/domain/3578/model/publication/lang/ind/id/' . urlencode($pubId) . '/key/' . $this->apiKey . '/';
            
            $response = Http::timeout(5)->get($url);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['status']) && $data['status'] === 'OK' && isset($data['data'])) {
                    $pubData = $data['data'];
                    
                    // Clean abstract if it exists
                    if (isset($pubData['abstract'])) {
                        $pubData['abstract_cleaned'] = $this->cleanAbstract($pubData['abstract']);
                    }
                    
                    return $pubData;
                }
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Failed to fetch publication detail from BPS API', [
                'pub_id' => $pubId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}

