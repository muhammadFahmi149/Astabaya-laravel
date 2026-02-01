<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Models\News;
use App\Http\Resources\NewsResource;

class NewsController extends Controller
{
    /**
     * Cache duration in seconds (5 minutes)
     */
    private const CACHE_DURATION = 300;

    /**
     * Cache duration for summary data (10 minutes - less frequently updated)
     */
    private const CACHE_DURATION_SUMMARY = 600;

    /**
     * Default pagination per page
     */
    private const PER_PAGE = 20;

    /**
     * Default limit for latest items (for dashboard)
     */
    private const LATEST_LIMIT = 10;

    /**
     * Get all news with search, category filter, and sorting.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'page' => 'sometimes|integer|min:1',
                'search' => 'sometimes|string|max:255',
                'category_id' => 'sometimes|integer|min:1',
                'sort' => 'sometimes|string|in:latest,oldest',
            ]);

            if ($validator->fails()) {
                Log::warning('News API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $page = $request->input('page', 1);
            $search = $request->input('search', '');
            $categoryId = $request->input('category_id', '');
            $sort = $request->input('sort', 'latest');

            // Build cache key
            $cacheKey = "news_api:page:{$page}:search:" . md5($search) . ":category:{$categoryId}:sort:{$sort}";

            // Try to get from cache
            $result = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($search, $categoryId, $sort) {
                // Build query with only needed columns for better performance
                $query = News::select([
                    'news_id',
                    'title',
                    'content',
                    'category_id',
                    'category_name',
                    'release_date',
                    'picture_url',
                    'created_at',
                    'updated_at'
                ]);

                // Search filter - sanitized input
                if (!empty($search)) {
                    $searchTerm = trim($search);
                    $query->where(function($q) use ($searchTerm) {
                        $q->where('title', 'like', "%{$searchTerm}%")
                          ->orWhere('content', 'like', "%{$searchTerm}%");
                    });
                }

                // Category filter
                if (!empty($categoryId)) {
                    $query->where('category_id', (int)$categoryId);
                }

                // Sorting
                if ($sort === 'oldest') {
                    $query->orderBy('release_date', 'asc');
                } else {
                    $query->orderBy('release_date', 'desc');
                }

                // Paginate with optimized query
                return $query->paginate(self::PER_PAGE);
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log successful request
            Log::info('News API request successful', [
                'page' => $page,
                'search' => $search ? 'yes' : 'no',
                'category_id' => $categoryId ?: 'all',
                'sort' => $sort,
                'total_results' => $result->total(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            // Return ResourceCollection response (includes data, meta, links)
            return NewsResource::collection($result);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log error
            Log::error('News API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching news data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get single news by ID.
     * 
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Validate ID
            if (!is_numeric($id) || $id < 1) {
                Log::warning('News API invalid ID', [
                    'id' => $id,
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid news ID'
                ], 400);
            }

            $cacheKey = "news_api:single:{$id}";

            // Try to get from cache
            $news = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($id) {
                return News::select([
                    'news_id',
                    'title',
                    'content',
                    'category_id',
                    'category_name',
                    'release_date',
                    'picture_url',
                    'created_at',
                    'updated_at'
                ])->find($id);
            });

            if (!$news) {
                Log::info('News not found', [
                    'id' => $id,
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'News not found'
                ], 404);
            }

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('News API single request successful', [
                'id' => $id,
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return new NewsResource($news);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('News API single request error', [
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching news data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get news categories (if needed for frontend).
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function categories(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $cacheKey = 'news_api:categories';

            $categories = Cache::remember($cacheKey, self::CACHE_DURATION * 2, function () {
                return News::select('category_id', 'category_name')
                    ->whereNotNull('category_id')
                    ->whereNotNull('category_name')
                    ->distinct()
                    ->orderBy('category_name')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id' => $item->category_id,
                            'name' => $item->category_name,
                        ];
                    });
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('News categories API request successful', [
                'count' => $categories->count(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('News categories API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching categories',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get latest news for dashboard (optimized, limited results).
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLatest(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $limit = $request->input('limit', self::LATEST_LIMIT);
            $limit = min(max((int)$limit, 1), 50); // Limit between 1 and 50
            
            $cacheKey = "news_api:latest:limit:{$limit}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION_SUMMARY, function () use ($limit) {
                return News::select([
                    'news_id',
                    'title',
                    'content',
                    'category_id',
                    'category_name',
                    'release_date',
                    'picture_url',
                    'created_at',
                    'updated_at'
                ])
                ->orderBy('release_date', 'desc')
                ->limit($limit)
                ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('News Latest API request successful', [
                'limit' => $limit,
                'total_results' => $data->count(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return NewsResource::collection($data);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('News Latest API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching latest news',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}

