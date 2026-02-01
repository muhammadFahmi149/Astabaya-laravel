<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Models\GiniRatio;

class GiniRatioController extends Controller
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
     * Get Gini Ratio Surabaya data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSurabaya(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('Gini Ratio Surabaya API validation failed', [
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

            $year = $request->input('year', '');

            // Build cache key
            $cacheKey = "gini_ratio_surabaya_api:year:{$year}";

            // Try to get from cache
            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($year) {
                // Build query with only needed columns for better performance
                $query = GiniRatio::select([
                    'id',
                    'year',
                    'gini_ratio_value',
                    'created_at',
                    'updated_at'
                ])
                ->where('location_name', 'Kota Surabaya')
                ->where('location_type', 'Kota');

                // Year filter
                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                // Order by year asc (oldest first) for consistency with frontend
                $query->orderBy('year', 'asc');

                return $query->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log successful request
            Log::info('Gini Ratio Surabaya API request successful', [
                'year' => $year ?: 'all',
                'total_results' => $data->count(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log error
            Log::error('Gini Ratio Surabaya API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching Gini Ratio Surabaya data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get Gini Ratio Jawa Timur data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJawaTimur(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('Gini Ratio Jawa Timur API validation failed', [
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

            $year = $request->input('year', '');

            // Build cache key
            $cacheKey = "gini_ratio_jatim_api:year:{$year}";

            // Try to get from cache
            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($year) {
                // Build query with only needed columns for better performance
                $query = GiniRatio::select([
                    'id',
                    'year',
                    'gini_ratio_value',
                    'created_at',
                    'updated_at'
                ])
                ->where('location_name', 'Jawa Timur')
                ->where('location_type', 'Provinsi');

                // Year filter
                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                // Order by year asc (oldest first) for consistency with frontend
                $query->orderBy('year', 'asc');

                return $query->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log successful request
            Log::info('Gini Ratio Jawa Timur API request successful', [
                'year' => $year ?: 'all',
                'total_results' => $data->count(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log error
            Log::error('Gini Ratio Jawa Timur API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching Gini Ratio Jawa Timur data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get Gini Ratio summary data (latest, previous, and changes).
     * This endpoint provides all data needed for the main gini ratio page.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSummary(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Build cache key for summary
            $cacheKey = "gini_ratio_summary_api";

            // Clear cache first to ensure fresh data
            Cache::forget($cacheKey);

            // Try to get from cache
            $summary = Cache::remember($cacheKey, self::CACHE_DURATION_SUMMARY, function () {
                // First, get ALL data from database to see what we have
                $allRecords = GiniRatio::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'gini_ratio_value'
                ])
                ->orderBy('id', 'desc')
                ->get();
                
                Log::info('Gini Ratio - All records in database', [
                    'total_count' => $allRecords->count(),
                    'sample_records' => $allRecords->take(10)->map(function($r) {
                        return [
                            'id' => $r->id,
                            'location_name' => $r->location_name,
                            'location_type' => $r->location_type,
                            'year' => $r->year,
                            'gini_ratio_value' => $r->gini_ratio_value
                        ];
                    })->toArray()
                ]);
                
                // Filter data manually (case-insensitive)
                $surabaya_data = $allRecords->filter(function($item) {
                    $name = strtolower($item->location_name ?? '');
                    return strpos($name, 'surabaya') !== false;
                })->map(function($item) {
                    return (object)[
                        'id' => $item->id,
                        'year' => $item->year,
                        'gini_ratio_value' => $item->gini_ratio_value
                    ];
                })->sortBy('year')->values();
                
                $jatim_data = $allRecords->filter(function($item) {
                    $name = strtolower($item->location_name ?? '');
                    return strpos($name, 'jawa timur') !== false || 
                           strpos($name, 'jatim') !== false;
                })->map(function($item) {
                    return (object)[
                        'id' => $item->id,
                        'year' => $item->year,
                        'gini_ratio_value' => $item->gini_ratio_value
                    ];
                })->sortBy('year')->values();
                
                Log::info('Gini Ratio - Filtered data', [
                    'surabaya_count' => $surabaya_data->count(),
                    'jatim_count' => $jatim_data->count(),
                    'surabaya_sample' => $surabaya_data->take(3)->toArray(),
                    'jatim_sample' => $jatim_data->take(3)->toArray(),
                ]);
                
                // Get latest data (by ID desc)
                $surabaya_latest = $surabaya_data->sortByDesc('id')->first();
                $jatim_latest = $jatim_data->sortByDesc('id')->first();
                
                // Get previous data (second latest by ID)
                $surabaya_previous = $surabaya_data->sortByDesc('id')->skip(1)->first();
                $jatim_previous = $jatim_data->sortByDesc('id')->skip(1)->first();
                
                // Calculate changes
                $surabaya_change = null;
                if ($surabaya_latest && $surabaya_previous) {
                    if ($surabaya_latest->gini_ratio_value !== null && $surabaya_previous->gini_ratio_value !== null) {
                        $surabaya_change = round($surabaya_latest->gini_ratio_value - $surabaya_previous->gini_ratio_value, 3);
                    }
                }
                
                $jatim_change = null;
                if ($jatim_latest && $jatim_previous) {
                    if ($jatim_latest->gini_ratio_value !== null && $jatim_previous->gini_ratio_value !== null) {
                        $jatim_change = round($jatim_latest->gini_ratio_value - $jatim_previous->gini_ratio_value, 3);
                    }
                }
                
                Log::info('Gini Ratio - Final summary', [
                    'surabaya_latest' => $surabaya_latest ? [
                        'id' => $surabaya_latest->id,
                        'year' => $surabaya_latest->year,
                        'value' => $surabaya_latest->gini_ratio_value
                    ] : null,
                    'jatim_latest' => $jatim_latest ? [
                        'id' => $jatim_latest->id,
                        'year' => $jatim_latest->year,
                        'value' => $jatim_latest->gini_ratio_value
                    ] : null,
                ]);
                
                return [
                    'surabaya_data' => $surabaya_data->toArray(),
                    'jatim_data' => $jatim_data->toArray(),
                    'surabaya_latest' => $surabaya_latest,
                    'jatim_latest' => $jatim_latest,
                    'surabaya_previous' => $surabaya_previous,
                    'jatim_previous' => $jatim_previous,
                    'surabaya_change' => $surabaya_change,
                    'jatim_change' => $jatim_change,
                ];
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log successful request
            Log::info('Gini Ratio Summary API request successful', [
                'total_results_surabaya' => count($summary['surabaya_data']),
                'total_results_jatim' => count($summary['jatim_data']),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log error
            Log::error('Gini Ratio Summary API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching Gini Ratio summary data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}
