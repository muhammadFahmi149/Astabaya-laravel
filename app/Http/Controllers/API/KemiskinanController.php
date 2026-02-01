<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Models\KemiskinanSurabaya;
use App\Models\KemiskinanJawaTimur;

class KemiskinanController extends Controller
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
     * Get Kemiskinan Surabaya data.
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
                Log::warning('Kemiskinan Surabaya API validation failed', [
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
            $cacheKey = "kemiskinan_surabaya_api:year:{$year}";

            // Try to get from cache
            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($year) {
                // Build query with only needed columns for better performance
                $query = KemiskinanSurabaya::select([
                    'id',
                    'year',
                    'jumlah_penduduk_miskin',
                    'persentase_penduduk_miskin',
                    'indeks_kedalaman_kemiskinan_p1',
                    'indeks_keparahan_kemiskinan_p2',
                    'garis_kemiskinan',
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
            Log::info('Kemiskinan Surabaya API request successful', [
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
            Log::error('Kemiskinan Surabaya API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching Kemiskinan Surabaya data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get Kemiskinan Jawa Timur data.
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
                Log::warning('Kemiskinan Jawa Timur API validation failed', [
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
            $cacheKey = "kemiskinan_jatim_api:year:{$year}";

            // Try to get from cache
            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($year) {
                // Build query with only needed columns for better performance
                $query = KemiskinanJawaTimur::select([
                    'id',
                    'year',
                    'jumlah_penduduk_miskin',
                    'persentase_penduduk_miskin',
                    'indeks_kedalaman_kemiskinan_p1',
                    'indeks_keparahan_kemiskinan_p2',
                    'garis_kemiskinan',
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
            Log::info('Kemiskinan Jawa Timur API request successful', [
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
            Log::error('Kemiskinan Jawa Timur API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching Kemiskinan Jawa Timur data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get Kemiskinan summary data (latest, previous, and changes).
     * This endpoint provides all data needed for the main kemiskinan page.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSummary(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Build cache key for summary
            $cacheKey = "kemiskinan_summary_api";

            // Try to get from cache
            $summary = Cache::remember($cacheKey, self::CACHE_DURATION_SUMMARY, function () {
                // Get all Surabaya data ordered by year (optimized - only needed columns)
                $surabaya_data = KemiskinanSurabaya::select([
                    'id',
                    'year',
                    'jumlah_penduduk_miskin',
                    'persentase_penduduk_miskin',
                    'indeks_kedalaman_kemiskinan_p1',
                    'indeks_keparahan_kemiskinan_p2',
                    'garis_kemiskinan'
                ])->orderBy('year', 'asc')->get();
                
                // Get all Jawa Timur data ordered by year (optimized - only needed columns)
                $jatim_data = KemiskinanJawaTimur::select([
                    'id',
                    'year',
                    'jumlah_penduduk_miskin',
                    'persentase_penduduk_miskin',
                    'indeks_kedalaman_kemiskinan_p1',
                    'indeks_keparahan_kemiskinan_p2',
                    'garis_kemiskinan'
                ])->orderBy('year', 'asc')->get();
                
                // Get latest Surabaya data (optimized query)
                $surabaya_latest = KemiskinanSurabaya::select([
                    'id',
                    'year',
                    'jumlah_penduduk_miskin',
                    'persentase_penduduk_miskin',
                    'indeks_kedalaman_kemiskinan_p1',
                    'indeks_keparahan_kemiskinan_p2',
                    'garis_kemiskinan'
                ])->orderBy('year', 'desc')->first();
                
                // Get latest Jawa Timur data (optimized query)
                $jatim_latest = KemiskinanJawaTimur::select([
                    'id',
                    'year',
                    'jumlah_penduduk_miskin',
                    'persentase_penduduk_miskin',
                    'indeks_kedalaman_kemiskinan_p1',
                    'indeks_keparahan_kemiskinan_p2',
                    'garis_kemiskinan'
                ])->orderBy('year', 'desc')->first();
                
                // Get previous Surabaya data (second latest) - optimized query
                $surabaya_previous = KemiskinanSurabaya::select([
                    'id',
                    'year',
                    'jumlah_penduduk_miskin',
                    'persentase_penduduk_miskin',
                    'indeks_kedalaman_kemiskinan_p1',
                    'indeks_keparahan_kemiskinan_p2',
                    'garis_kemiskinan'
                ])->orderBy('year', 'desc')->skip(1)->first();
                
                // Get previous Jawa Timur data (second latest) - optimized query
                $jatim_previous = KemiskinanJawaTimur::select([
                    'id',
                    'year',
                    'jumlah_penduduk_miskin',
                    'persentase_penduduk_miskin',
                    'indeks_kedalaman_kemiskinan_p1',
                    'indeks_keparahan_kemiskinan_p2',
                    'garis_kemiskinan'
                ])->orderBy('year', 'desc')->skip(1)->first();
                
                // Calculate changes for Surabaya
                $surabaya_changes = [
                    'jumlah_penduduk_miskin' => null,
                    'persentase_penduduk_miskin' => null,
                    'indeks_kedalaman_kemiskinan_p1' => null,
                    'indeks_keparahan_kemiskinan_p2' => null,
                    'garis_kemiskinan' => null,
                ];
                
                if ($surabaya_latest && $surabaya_previous) {
                    if ($surabaya_latest->jumlah_penduduk_miskin !== null && $surabaya_previous->jumlah_penduduk_miskin !== null) {
                        $surabaya_changes['jumlah_penduduk_miskin'] = round($surabaya_latest->jumlah_penduduk_miskin - $surabaya_previous->jumlah_penduduk_miskin, 2);
                    }
                    if ($surabaya_latest->persentase_penduduk_miskin !== null && $surabaya_previous->persentase_penduduk_miskin !== null) {
                        $surabaya_changes['persentase_penduduk_miskin'] = round($surabaya_latest->persentase_penduduk_miskin - $surabaya_previous->persentase_penduduk_miskin, 2);
                    }
                    if ($surabaya_latest->indeks_kedalaman_kemiskinan_p1 !== null && $surabaya_previous->indeks_kedalaman_kemiskinan_p1 !== null) {
                        $surabaya_changes['indeks_kedalaman_kemiskinan_p1'] = round($surabaya_latest->indeks_kedalaman_kemiskinan_p1 - $surabaya_previous->indeks_kedalaman_kemiskinan_p1, 2);
                    }
                    if ($surabaya_latest->indeks_keparahan_kemiskinan_p2 !== null && $surabaya_previous->indeks_keparahan_kemiskinan_p2 !== null) {
                        $surabaya_changes['indeks_keparahan_kemiskinan_p2'] = round($surabaya_latest->indeks_keparahan_kemiskinan_p2 - $surabaya_previous->indeks_keparahan_kemiskinan_p2, 2);
                    }
                    if ($surabaya_latest->garis_kemiskinan !== null && $surabaya_previous->garis_kemiskinan !== null) {
                        $surabaya_changes['garis_kemiskinan'] = round($surabaya_latest->garis_kemiskinan - $surabaya_previous->garis_kemiskinan, 2);
                    }
                }
                
                return [
                    'surabaya_data' => $surabaya_data,
                    'jatim_data' => $jatim_data,
                    'surabaya_latest' => $surabaya_latest,
                    'jatim_latest' => $jatim_latest,
                    'surabaya_previous' => $surabaya_previous,
                    'jatim_previous' => $jatim_previous,
                    'surabaya_changes' => $surabaya_changes,
                ];
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log successful request
            Log::info('Kemiskinan Summary API request successful', [
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
            Log::error('Kemiskinan Summary API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching Kemiskinan summary data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}

