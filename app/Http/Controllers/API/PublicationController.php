<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Models\Publication;
use App\Http\Resources\PublicationResource;

class PublicationController extends Controller
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
     * Get all publications with search and year filter.
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
                'year' => 'sometimes|integer|min:1900|max:' . date('Y'),
            ]);

            if ($validator->fails()) {
                Log::warning('Publication API validation failed', [
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
            $year = $request->input('year', '');

            // Build cache key
            $cacheKey = "publication_api:page:{$page}:search:" . md5($search) . ":year:{$year}";

            // Try to get from cache
            $result = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($search, $year) {
                // Build query with only needed columns for better performance
                $query = Publication::select([
                    'id',
                    'pub_id',
                    'title',
                    'image',
                    'dl',
                    'date',
                    'abstract',
                    'size',
                    'created_at',
                    'updated_at'
                ]);
                
                // Search filter - sanitized input
                if (!empty($search)) {
                    $searchTerm = trim($search);
                    $query->where(function($q) use ($searchTerm) {
                        $q->where('title', 'like', "%{$searchTerm}%")
                          ->orWhere('abstract', 'like', "%{$searchTerm}%");
                    });
                }
                
                // Year filter
                if (!empty($year) && is_numeric($year)) {
                    $query->whereYear('date', (int)$year);
                }
                
                // Order by date desc (newest first)
                $query->orderBy('date', 'desc');
                
                // Paginate with optimized query
                return $query->paginate(self::PER_PAGE);
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log successful request
            Log::info('Publication API request successful', [
                'page' => $page,
                'search' => $search ? 'yes' : 'no',
                'year' => $year ?: 'all',
                'total_results' => $result->total(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            // Return ResourceCollection response (includes data, meta, links)
            return PublicationResource::collection($result);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log error
            Log::error('Publication API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching publication data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get single publication by pub_id.
     * 
     * @param string $pubId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($pubId, Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Validate pub_id
            if (empty($pubId) || !is_string($pubId)) {
                Log::warning('Publication API invalid pub_id', [
                    'pub_id' => $pubId,
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid publication ID'
                ], 400);
            }

            // Sanitize pub_id to prevent injection
            $pubId = trim($pubId);
            
            $cacheKey = "publication_api:single:" . md5($pubId);

            // Try to get from cache
            $publication = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($pubId) {
                return Publication::select([
                    'id',
                    'pub_id',
                    'title',
                    'image',
                    'dl',
                    'date',
                    'abstract',
                    'size',
                    'created_at',
                    'updated_at'
                ])->where('pub_id', $pubId)->first();
            });
            
            if (!$publication) {
                Log::info('Publication not found', [
                    'pub_id' => $pubId,
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Publikasi tidak ditemukan.'
                ], 404);
            }

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('Publication API single request successful', [
                'pub_id' => $pubId,
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);
            
            return new PublicationResource($publication);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('Publication API single request error', [
                'pub_id' => $pubId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching publication data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get publication download URL by pub_id.
     * 
     * @param string $pubId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function getDownloadUrl($pubId, Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Validate pub_id
            if (empty($pubId) || !is_string($pubId)) {
                Log::warning('Publication download API invalid pub_id', [
                    'pub_id' => $pubId,
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid publication ID'
                ], 400);
            }

            // Sanitize pub_id
            $pubId = trim($pubId);
            
            $cacheKey = "publication_api:download:" . md5($pubId);

            // Try to get from cache
            $publication = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($pubId) {
                return Publication::select([
                    'id',
                    'pub_id',
                    'dl'
                ])->where('pub_id', $pubId)->first();
            });
            
            if (!$publication) {
                Log::info('Publication not found for download', [
                    'pub_id' => $pubId,
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Publikasi tidak ditemukan.'
                ], 404);
            }

            if (!$publication->dl) {
                Log::info('Publication download URL not available', [
                    'pub_id' => $pubId,
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Link download belum tersedia untuk publikasi ini.'
                ], 404);
            }

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('Publication download URL request successful', [
                'pub_id' => $pubId,
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);
            
            // If request wants JSON, return JSON. Otherwise redirect
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'download_url' => $publication->dl
                ]);
            }
            
            // Otherwise redirect to download URL
            return redirect($publication->dl);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('Publication download URL request error', [
                'pub_id' => $pubId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching publication download URL',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get latest publications for dashboard (optimized, limited results).
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
            
            $cacheKey = "publication_api:latest:limit:{$limit}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION_SUMMARY, function () use ($limit) {
                return Publication::select([
                    'id',
                    'pub_id',
                    'title',
                    'image',
                    'dl',
                    'date',
                    'abstract',
                    'size',
                    'created_at',
                    'updated_at'
                ])
                ->orderBy('date', 'desc')
                ->limit($limit)
                ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('Publication Latest API request successful', [
                'limit' => $limit,
                'total_results' => $data->count(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return PublicationResource::collection($data);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('Publication Latest API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching latest publications',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}

