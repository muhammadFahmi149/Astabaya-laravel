<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Models\Infographic;
use App\Http\Resources\InfographicResource;

class InfographicController extends Controller
{
    /**
     * Cache duration in seconds (1 minute - for fresher data)
     */
    private const CACHE_DURATION = 60;

    /**
     * Cache duration for summary data (2 minutes - less frequently updated)
     */
    private const CACHE_DURATION_SUMMARY = 120;

    /**
     * Default pagination per page
     */
    private const PER_PAGE = 20;

    /**
     * Default limit for latest items (for dashboard)
     */
    private const LATEST_LIMIT = 10;

    /**
     * Get all infographics with search filter.
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
            ]);

            if ($validator->fails()) {
                Log::warning('Infographic API validation failed', [
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

            // Build cache key
            $cacheKey = "infographic_api:page:{$page}:search:" . md5($search);

            // Try to get from cache
            $result = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($search, $page) {
                // Build query with only needed columns for better performance
                $query = Infographic::select([
                    'id',
                    'bps_id',
                    'title',
                    'image',
                    'dl',
                    'created_at',
                    'updated_at'
                ]);

                // Search filter - sanitized input
                if (!empty($search)) {
                    $searchTerm = trim($search);
                    $query->where('title', 'like', "%{$searchTerm}%");
                }

                // Order by bps_id desc (highest bps_id first) - cast to integer for proper numeric sorting
                // This ensures bps_id 1121 appears before 999, 99, etc.
                $query->orderByRaw('CAST(bps_id AS UNSIGNED) DESC');

                // Paginate with optimized query - use page parameter
                $paginated = $query->paginate(self::PER_PAGE, ['*'], 'page', $page);
                
                // Log for debugging - show first 5 items with bps_id
                $items = $paginated->items();
                $firstItems = [];
                for ($i = 0; $i < min(5, count($items)); $i++) {
                    $firstItems[] = [
                        'id' => $items[$i]->id ?? null,
                        'bps_id' => $items[$i]->bps_id ?? null,
                        'title' => $items[$i]->title ?? null,
                    ];
                }
                
                // Log with detailed bps_id information
                $bpsIds = array_map(function($item) {
                    return $item->bps_id ?? null;
                }, $items);
                
                Log::info('Infographic query result - First items with bps_id', [
                    'total' => $paginated->total(),
                    'current_page' => $paginated->currentPage(),
                    'per_page' => $paginated->perPage(),
                    'first_5_items' => $firstItems,
                    'all_bps_ids_in_page' => $bpsIds,
                    'first_bps_id' => $bpsIds[0] ?? null,
                    'last_bps_id' => end($bpsIds) ?: null,
                    'max_bps_id' => !empty($bpsIds) ? max(array_filter($bpsIds)) : null,
                    'min_bps_id' => !empty($bpsIds) ? min(array_filter($bpsIds)) : null,
                ]);
                
                return $paginated;
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log successful request with bps_id details
            $firstItem = $result->items()[0] ?? null;
            $allItems = $result->items();
            $firstFiveBpsIds = array_slice(array_map(function($item) {
                return $item->bps_id ?? null;
            }, $allItems), 0, 5);
            
            Log::info('Infographic API request successful - BPS_ID DEBUG', [
                'page' => $page,
                'search' => $search ? 'yes' : 'no',
                'total_results' => $result->total(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'first_item' => [
                    'id' => $firstItem->id ?? null,
                    'bps_id' => $firstItem->bps_id ?? null,
                    'title' => $firstItem->title ?? null,
                ],
                'first_5_bps_ids' => $firstFiveBpsIds,
            ]);

            // Return ResourceCollection response (includes data, meta, links)
            return InfographicResource::collection($result);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log error
            Log::error('Infographic API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching infographic data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get single infographic by ID.
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
                Log::warning('Infographic API invalid ID', [
                    'id' => $id,
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid infographic ID'
                ], 400);
            }

            $cacheKey = "infographic_api:single:{$id}";

            // Try to get from cache
            $infographic = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($id) {
                return Infographic::select([
                    'id',
                    'title',
                    'image',
                    'dl',
                    'created_at',
                    'updated_at'
                ])->find($id);
            });

            if (!$infographic) {
                Log::info('Infographic not found', [
                    'id' => $id,
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Infographic not found'
                ], 404);
            }

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('Infographic API single request successful', [
                'id' => $id,
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return new InfographicResource($infographic);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('Infographic API single request error', [
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching infographic data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get latest infographics for dashboard (optimized, limited results).
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
            
            $cacheKey = "infographic_api:latest:limit:{$limit}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION_SUMMARY, function () use ($limit) {
                return Infographic::select([
                    'id',
                    'bps_id',
                    'title',
                    'image',
                    'dl',
                    'created_at',
                    'updated_at'
                ])
                ->orderByRaw('CAST(bps_id AS UNSIGNED) DESC')
                ->limit($limit)
                ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('Infographic Latest API request successful', [
                'limit' => $limit,
                'total_results' => $data->count(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return InfographicResource::collection($data);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('Infographic Latest API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching latest infographics',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}

