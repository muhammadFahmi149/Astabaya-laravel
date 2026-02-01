<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Models\KetenagakerjaanTPT;
use App\Models\KetenagakerjaanTPAK;

class KetenagakerjaanController extends Controller
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
     * Get Ketenagakerjaan TPT (Tingkat Pengangguran Terbuka) data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTPT(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('Ketenagakerjaan TPT API validation failed', [
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
            $cacheKey = "ketenagakerjaan_tpt_api:year:{$year}";

            // Try to get from cache
            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($year) {
                // Build query with only needed columns for better performance
                $query = KetenagakerjaanTPT::select([
                    'id',
                    'year',
                    'laki_laki',
                    'perempuan',
                    'total',
                    'created_at',
                    'updated_at'
                ]);

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
            Log::info('Ketenagakerjaan TPT API request successful', [
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
            Log::error('Ketenagakerjaan TPT API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching Ketenagakerjaan TPT data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get Ketenagakerjaan TPAK (Tingkat Partisipasi Angkatan Kerja) data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTPAK(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('Ketenagakerjaan TPAK API validation failed', [
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
            $cacheKey = "ketenagakerjaan_tpak_api:year:{$year}";

            // Try to get from cache
            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($year) {
                // Build query with only needed columns for better performance
                $query = KetenagakerjaanTPAK::select([
                    'id',
                    'year',
                    'laki_laki',
                    'perempuan',
                    'total',
                    'created_at',
                    'updated_at'
                ]);

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
            Log::info('Ketenagakerjaan TPAK API request successful', [
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
            Log::error('Ketenagakerjaan TPAK API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching Ketenagakerjaan TPAK data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get Ketenagakerjaan summary data (latest, previous, and changes).
     * This endpoint provides all data needed for the main ketenagakerjaan page.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSummary(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Build cache key for summary
            $cacheKey = "ketenagakerjaan_summary_api";

            // Try to get from cache
            $summary = Cache::remember($cacheKey, self::CACHE_DURATION_SUMMARY, function () {
                // Get all TPT data ordered by year (optimized - only needed columns)
                $tpt_data = KetenagakerjaanTPT::select([
                    'id',
                    'year',
                    'laki_laki',
                    'perempuan',
                    'total'
                ])->orderBy('year', 'asc')->get();
                
                // Get all TPAK data ordered by year (optimized - only needed columns)
                $tpak_data = KetenagakerjaanTPAK::select([
                    'id',
                    'year',
                    'laki_laki',
                    'perempuan',
                    'total'
                ])->orderBy('year', 'asc')->get();
                
                // Get latest TPT data - order by ID desc to get the most recent entry
                $tpt_latest_data = KetenagakerjaanTPT::select([
                    'id',
                    'year',
                    'laki_laki',
                    'perempuan',
                    'total'
                ])->orderBy('id', 'desc')->first();
                
                // Get latest TPAK data - order by ID desc to get the most recent entry
                $tpak_latest_data = KetenagakerjaanTPAK::select([
                    'id',
                    'year',
                    'laki_laki',
                    'perempuan',
                    'total'
                ])->orderBy('id', 'desc')->first();
                
                // Get previous TPT data (second latest by ID) - optimized query
                $tpt_previous_data = KetenagakerjaanTPT::select([
                    'id',
                    'year',
                    'laki_laki',
                    'perempuan',
                    'total'
                ])->orderBy('id', 'desc')->skip(1)->first();
                
                // Get previous TPAK data (second latest by ID) - optimized query
                $tpak_previous_data = KetenagakerjaanTPAK::select([
                    'id',
                    'year',
                    'laki_laki',
                    'perempuan',
                    'total'
                ])->orderBy('id', 'desc')->skip(1)->first();
                
                // Add detailed logging
                Log::info('Ketenagakerjaan Data Details', [
                    'tpt_latest_id' => $tpt_latest_data ? $tpt_latest_data->id : null,
                    'tpt_latest_year' => $tpt_latest_data ? $tpt_latest_data->year : null,
                    'tpak_latest_id' => $tpak_latest_data ? $tpak_latest_data->id : null,
                    'tpak_latest_year' => $tpak_latest_data ? $tpak_latest_data->year : null,
                    'tpt_data_count' => $tpt_data->count(),
                    'tpak_data_count' => $tpak_data->count(),
                ]);
                
                // Calculate changes for TPT
                $tpt_total_change = null;
                $tpt_laki_laki_change = null;
                $tpt_perempuan_change = null;
                
                if ($tpt_latest_data && $tpt_previous_data) {
                    if ($tpt_latest_data->total !== null && $tpt_previous_data->total !== null) {
                        $tpt_total_change = round($tpt_latest_data->total - $tpt_previous_data->total, 2);
                    }
                    if ($tpt_latest_data->laki_laki !== null && $tpt_previous_data->laki_laki !== null) {
                        $tpt_laki_laki_change = round($tpt_latest_data->laki_laki - $tpt_previous_data->laki_laki, 2);
                    }
                    if ($tpt_latest_data->perempuan !== null && $tpt_previous_data->perempuan !== null) {
                        $tpt_perempuan_change = round($tpt_latest_data->perempuan - $tpt_previous_data->perempuan, 2);
                    }
                }
                
                // Calculate changes for TPAK
                $tpak_total_change = null;
                $tpak_laki_laki_change = null;
                $tpak_perempuan_change = null;
                
                if ($tpak_latest_data && $tpak_previous_data) {
                    if ($tpak_latest_data->total !== null && $tpak_previous_data->total !== null) {
                        $tpak_total_change = round($tpak_latest_data->total - $tpak_previous_data->total, 2);
                    }
                    if ($tpak_latest_data->laki_laki !== null && $tpak_previous_data->laki_laki !== null) {
                        $tpak_laki_laki_change = round($tpak_latest_data->laki_laki - $tpak_previous_data->laki_laki, 2);
                    }
                    if ($tpak_latest_data->perempuan !== null && $tpak_previous_data->perempuan !== null) {
                        $tpak_perempuan_change = round($tpak_latest_data->perempuan - $tpak_previous_data->perempuan, 2);
                    }
                }
                
                return [
                    'tpt_data' => $tpt_data,
                    'tpak_data' => $tpak_data,
                    'tpt_latest_data' => $tpt_latest_data,
                    'tpak_latest_data' => $tpak_latest_data,
                    'tpt_previous_data' => $tpt_previous_data,
                    'tpak_previous_data' => $tpak_previous_data,
                    'tpt_total_change' => $tpt_total_change,
                    'tpak_total_change' => $tpak_total_change,
                    'tpt_laki_laki_change' => $tpt_laki_laki_change,
                    'tpak_laki_laki_change' => $tpak_laki_laki_change,
                    'tpt_perempuan_change' => $tpt_perempuan_change,
                    'tpak_perempuan_change' => $tpak_perempuan_change,
                ];
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log successful request
            Log::info('Ketenagakerjaan Summary API request successful', [
                'total_results_tpt' => count($summary['tpt_data']),
                'total_results_tpak' => count($summary['tpak_data']),
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
            Log::error('Ketenagakerjaan Summary API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching Ketenagakerjaan summary data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}

