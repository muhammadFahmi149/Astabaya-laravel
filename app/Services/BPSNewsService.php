<?php

namespace App\Services;

use App\Models\News;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Service untuk fetch dan sync data News dari BPS API
 * Equivalent to BPSNewsService in Django
 */
class BPSNewsService
{
    protected $apiKey;
    protected $baseUrl = 'https://webapi.bps.go.id/v1/api/list/model/news/lang/ind/domain/3578/key/';

    public function __construct()
    {
        $this->apiKey = config('services.bps.api_key', env('BPS_API_KEY', ''));
        $this->baseUrl .= $this->apiKey . '/';
    }

    /**
     * Clean HTML content from text
     */
    public function cleanHtmlContent(?string $text): string
    {
        if (empty($text)) {
            return '';
        }

        // Handle Unicode escape sequences
        $text = preg_replace('/\\\\u003C/', '', $text);
        $text = preg_replace('/\\\\u003E/', '', $text);
        $text = preg_replace('/\\\\u0022/', '', $text);
        $text = preg_replace('/\\\\u0027/', '', $text);
        $text = preg_replace('/\\\\u0020/', ' ', $text);
        
        // Handle other escape sequences
        $text = preg_replace('/\\\\u000D\\\\u000A/', ' ', $text);
        $text = preg_replace('/\\\\u000D/', ' ', $text);
        $text = preg_replace('/\\\\u000A/', ' ', $text);
        $text = preg_replace('/\\\\u0009/', ' ', $text);
        
        // Decode HTML entities
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Remove style attributes
        $text = preg_replace('/style\s*=\s*["\'][^"\']*["\']/i', '', $text);
        
        // Remove HTML tags
        $text = strip_tags($text);
        
        // Normalize whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        
        return trim($text);
    }

    /**
     * Clean text field (title, category_name, etc.)
     */
    public function cleanTextField(?string $text, ?int $maxLength = null): string
    {
        if (empty($text)) {
            return '';
        }

        $cleaned = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $cleaned = strip_tags($cleaned);
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        $cleaned = trim($cleaned);

        if ($maxLength && mb_strlen($cleaned) > $maxLength) {
            $cleaned = mb_substr($cleaned, 0, $maxLength);
        }

        return $cleaned;
    }

    /**
     * Fetch news data from BPS API
     */
    public function fetchNewsData(): array
    {
        try {
            echo "📡 Fetching page 1...\n";
            $response = Http::get($this->baseUrl, ['page' => 1]);
            
            if (!$response->successful()) {
                echo "❌ Failed to fetch page 1. Status: " . $response->status() . "\n";
                Log::error('Failed to fetch first page. Status: ' . $response->status());
                return [];
            }

            $firstPage = $response->json();
            
            // Debug: Log response structure
            if (config('app.debug')) {
                Log::info('First page response keys: ' . json_encode(array_keys($firstPage ?? [])));
                if (isset($firstPage['data'])) {
                    Log::info('Data structure: ' . json_encode([
                        'data_type' => gettype($firstPage['data']),
                        'data_count' => is_array($firstPage['data']) ? count($firstPage['data']) : 'N/A',
                        'data_keys' => is_array($firstPage['data']) ? array_keys($firstPage['data']) : 'N/A'
                    ]));
                }
            }
            
            // Check response structure
            if (!isset($firstPage['data']) || !is_array($firstPage['data'])) {
                echo "❌ Invalid API response structure: 'data' key not found or not an array\n";
                Log::error('Invalid API response structure: ' . json_encode($firstPage));
                return [];
            }

            // Get pagination info (first element of data array)
            $paginationInfo = $firstPage['data'][0] ?? null;
            if (!$paginationInfo || !isset($paginationInfo['pages'])) {
                echo "❌ Invalid API response: pagination info not found\n";
                Log::error('Pagination info not found: ' . json_encode($firstPage['data'][0] ?? null));
                return [];
            }

            $totalPages = (int) $paginationInfo['pages'];
            echo "📄 Total pages: {$totalPages}\n";
            
            // Get news data (second element of data array)
            $allNews = $firstPage['data'][1] ?? [];
            
            if (!is_array($allNews)) {
                echo "⚠️  News data is not an array, converting...\n";
                $allNews = [];
            }
            
            echo "📰 News from page 1: " . count($allNews) . " items\n";

            // Fetch remaining pages
            for ($page = 2; $page <= $totalPages; $page++) {
                echo "📡 Fetching page {$page}...\n";
                Log::info("Fetching page {$page}...");
                
                $response = Http::get($this->baseUrl, ['page' => $page]);
                
                if (!$response->successful()) {
                    echo "⚠️  Failed to fetch page {$page}. Status: " . $response->status() . "\n";
                    Log::warning("Failed to fetch page {$page}. Status: " . $response->status());
                    continue;
                }

                $data = $response->json();
                if (isset($data['data'][1]) && is_array($data['data'][1])) {
                    $pageNews = $data['data'][1];
                    $allNews = array_merge($allNews, $pageNews);
                    echo "   ✓ Got " . count($pageNews) . " news items from page {$page}\n";
                } else {
                    echo "   ⚠️  No news data found in page {$page}\n";
                    Log::warning("No news data found in page {$page}");
                }
            }

            echo "✅ Total berita diambil: " . count($allNews) . "\n";
            Log::info("Total news fetched: " . count($allNews));
            return $allNews;
        } catch (\Exception $e) {
            echo "❌ Error fetching news data: " . $e->getMessage() . "\n";
            Log::error('Error fetching news data: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return [];
        }
    }

    /**
     * Save news data to database
     */
    public function saveNewsToDb(array $newsList): array
    {
        $createdCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;
        $errorCount = 0;

        echo "📝 Processing " . count($newsList) . " news...\n";
        Log::info("Processing " . count($newsList) . " news for database save");

        // Log first item structure for debugging
        if (!empty($newsList)) {
            $firstItem = $newsList[0];
            echo "🔍 Sample item structure: " . json_encode(array_keys($firstItem)) . "\n";
            Log::info("Sample item keys: " . json_encode(array_keys($firstItem)));
            Log::info("Sample item data: " . json_encode($firstItem));
        }

        foreach ($newsList as $index => $item) {
            $newsId = $item['news_id'] ?? null;
            
            if (!$newsId) {
                $skippedCount++;
                if ($skippedCount <= 5) {
                    echo "⚠️  Skipping item " . ($index + 1) . " - no ID found. Keys: " . json_encode(array_keys($item)) . "\n";
                    Log::warning("Skipping news without ID. Item keys: " . json_encode(array_keys($item)));
                }
                continue;
            }

            // Convert newsId to string if it's not already
            $newsId = (string) $newsId;

            $data = [
                'news_id' => $newsId,
                'title' => $this->cleanTextField($item['title'] ?? '', 255),
                'content' => $this->cleanHtmlContent($item['news'] ?? $item['content'] ?? ''),
                'category_id' => $item['newscat_id'] ?? $item['cat_id'] ?? null,
                'category_name' => $this->cleanTextField($item['newscat_name'] ?? $item['cat_name'] ?? '', 100),
                'release_date' => $item['rl_date'] ?? null,
                'picture_url' => $item['picture'] ?? $item['img'] ?? null,
            ];

            try {
                $news = News::where('news_id', $newsId)->first();
                
                if ($news) {
                    $news->update($data);
                    $updatedCount++;
                } else {
                    News::create($data);
                    $createdCount++;
                }
            } catch (\Exception $e) {
                $errorCount++;
                echo "❌ Error saving news {$newsId}: " . $e->getMessage() . "\n";
                Log::error("Error saving news {$newsId}: " . $e->getMessage());
                Log::error("Stack trace: " . $e->getTraceAsString());
            }

            // Progress indicator every 100 items
            if (($index + 1) % 100 == 0) {
                echo "📊 Progress: " . ($index + 1) . "/" . count($newsList) . " (Created: {$createdCount}, Updated: {$updatedCount}, Errors: {$errorCount})\n";
            }
        }

        echo "✅ Processing completed!\n";
        echo "   Created: {$createdCount} records\n";
        echo "   Updated: {$updatedCount} records\n";
        echo "   Skipped: {$skippedCount} records (no ID)\n";
        echo "   Errors: {$errorCount} records\n";
        
        Log::info("News sync completed. Created: {$createdCount}, Updated: {$updatedCount}, Skipped: {$skippedCount}, Errors: {$errorCount}");
        return ['created' => $createdCount, 'updated' => $updatedCount];
    }

    /**
     * Sync news from BPS API
     */
    public function syncNews(): array
    {
        $newsList = $this->fetchNewsData();
        return $this->saveNewsToDb($newsList);
    }
}

