<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Models\IPM_UHH_SP;
use App\Models\IPM_HLS;
use App\Models\IPM_RLS;
use App\Models\HumanDevelopmentIndex;
use App\Models\IPM_PengeluaranPerKapita;
use App\Models\IPM_IndeksKesehatan;
use App\Models\IPM_IndeksPendidikan;
use App\Models\IPM_IndeksHidupLayak;

class IPMController extends Controller
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
     * Get IPM UHH SP (Usia Harapan Hidup saat Lahir) data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUhhSp(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'location' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('IPM UHH SP API validation failed', [
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

            $location = $request->input('location', '');
            $year = $request->input('year', '');

            // Build cache key
            $cacheKey = "ipm_uhh_sp_api:location:" . md5($location) . ":year:{$year}";

            // Try to get from cache
            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($location, $year) {
                // Build query with only needed columns for better performance
                $query = IPM_UHH_SP::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value',
                    'created_at',
                    'updated_at'
                ]);

                // Location filter - sanitized input with flexible matching
                if (!empty($location)) {
                    $locationTerm = trim($location);
                    // Support multiple location name formats
                    if (stripos($locationTerm, 'surabaya') !== false) {
                        $query->where(function($q) {
                            $q->where('location_name', 'Surabaya')
                              ->orWhere('location_name', 'Kota Surabaya')
                              ->orWhere('location_name', 'LIKE', '%Surabaya%');
                        });
                    } else {
                        $query->where('location_name', $locationTerm);
                    }
                }

                // Year filter
                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                // Order by year desc (newest first)
                $query->orderBy('year', 'desc')
                      ->orderBy('location_name', 'asc');

                return $query->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log successful request
            Log::info('IPM UHH SP API request successful', [
                'location' => $location ?: 'all',
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
            Log::error('IPM UHH SP API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching IPM UHH SP data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get IPM HLS (Harapan Lama Sekolah) data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHls(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'location' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('IPM HLS API validation failed', [
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

            $location = $request->input('location', '');
            $year = $request->input('year', '');

            // Build cache key
            $cacheKey = "ipm_hls_api:location:" . md5($location) . ":year:{$year}";

            // Try to get from cache
            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($location, $year) {
                // Build query with only needed columns for better performance
                $query = IPM_HLS::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value',
                    'created_at',
                    'updated_at'
                ]);

                // Location filter - sanitized input with flexible matching
                if (!empty($location)) {
                    $locationTerm = trim($location);
                    // Support multiple location name formats
                    if (stripos($locationTerm, 'surabaya') !== false) {
                        $query->where(function($q) {
                            $q->where('location_name', 'Surabaya')
                              ->orWhere('location_name', 'Kota Surabaya')
                              ->orWhere('location_name', 'LIKE', '%Surabaya%');
                        });
                    } else {
                        $query->where('location_name', $locationTerm);
                    }
                }

                // Year filter
                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                // Order by year desc (newest first)
                $query->orderBy('year', 'desc')
                      ->orderBy('location_name', 'asc');

                return $query->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log successful request
            Log::info('IPM HLS API request successful', [
                'location' => $location ?: 'all',
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
            Log::error('IPM HLS API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching IPM HLS data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get IPM RLS (Rata-rata Lama Sekolah) data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRls(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'location' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('IPM RLS API validation failed', [
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

            $location = $request->input('location', '');
            $year = $request->input('year', '');

            // Build cache key
            $cacheKey = "ipm_rls_api:location:" . md5($location) . ":year:{$year}";

            // Try to get from cache
            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($location, $year) {
                // Build query with only needed columns for better performance
                $query = IPM_RLS::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value',
                    'created_at',
                    'updated_at'
                ]);

                // Location filter - sanitized input with flexible matching
                if (!empty($location)) {
                    $locationTerm = trim($location);
                    // Support multiple location name formats
                    if (stripos($locationTerm, 'surabaya') !== false) {
                        $query->where(function($q) {
                            $q->where('location_name', 'Surabaya')
                              ->orWhere('location_name', 'Kota Surabaya')
                              ->orWhere('location_name', 'LIKE', '%Surabaya%');
                        });
                    } else {
                        $query->where('location_name', $locationTerm);
                    }
                }

                // Year filter
                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                // Order by year desc (newest first)
                $query->orderBy('year', 'desc')
                      ->orderBy('location_name', 'asc');

                return $query->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log successful request
            Log::info('IPM RLS API request successful', [
                'location' => $location ?: 'all',
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
            Log::error('IPM RLS API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching IPM RLS data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get IPM main data (Human Development Index).
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMain(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'location' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('IPM Main API validation failed', [
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

            $location = $request->input('location', '');
            $year = $request->input('year', '');

            // Build cache key
            $cacheKey = "ipm_main_api:location:" . md5($location) . ":year:{$year}";

            // Try to get from cache
            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($location, $year) {
                // Build query with only needed columns for better performance
                $query = HumanDevelopmentIndex::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'ipm_value',
                    'created_at',
                    'updated_at'
                ]);

                // Location filter - sanitized input with flexible matching
                if (!empty($location)) {
                    $locationTerm = trim($location);
                    // Support multiple location name formats
                    if (stripos($locationTerm, 'surabaya') !== false) {
                        $query->where(function($q) {
                            $q->where('location_name', 'Surabaya')
                              ->orWhere('location_name', 'Kota Surabaya')
                              ->orWhere('location_name', 'LIKE', '%Surabaya%');
                        });
                    } else {
                        $query->where('location_name', $locationTerm);
                    }
                }

                // Year filter
                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                // Order by year desc (newest first)
                $query->orderBy('year', 'desc')
                      ->orderBy('location_name', 'asc');

                return $query->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log successful request
            Log::info('IPM Main API request successful', [
                'location' => $location ?: 'all',
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
            Log::error('IPM Main API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching IPM Main data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get IPM data for Surabaya.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSurabaya(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('IPM Surabaya API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $year = $request->input('year', '');
            $cacheKey = "ipm_surabaya_api:year:{$year}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($year) {
                $query = HumanDevelopmentIndex::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'ipm_value',
                    'created_at',
                    'updated_at'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                });

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                return $query->orderBy('year', 'desc')->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('IPM Surabaya API request successful', [
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
            
            Log::error('IPM Surabaya API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching IPM Surabaya data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get IPM data for Jawa Timur.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJatim(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('IPM Jatim API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $year = $request->input('year', '');
            $cacheKey = "ipm_jatim_api:year:{$year}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($year) {
                $query = HumanDevelopmentIndex::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'ipm_value',
                    'created_at',
                    'updated_at'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                });

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                return $query->orderBy('year', 'desc')->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('IPM Jatim API request successful', [
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
            
            Log::error('IPM Jatim API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching IPM Jatim data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get IPM Pengeluaran Per Kapita data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPengeluaranPerKapita(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'location' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('IPM Pengeluaran Per Kapita API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $location = $request->input('location', 'Kota Surabaya');
            $year = $request->input('year', '');
            $cacheKey = "ipm_pengeluaran_api:location:" . md5($location) . ":year:{$year}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($location, $year) {
                $query = IPM_PengeluaranPerKapita::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value',
                    'created_at',
                    'updated_at'
                ]);

                if (!empty($location)) {
                    $locationTerm = trim($location);
                    // Support multiple location name formats
                    if (stripos($locationTerm, 'surabaya') !== false) {
                        $query->where(function($q) {
                            $q->where('location_name', 'Surabaya')
                              ->orWhere('location_name', 'Kota Surabaya')
                              ->orWhere('location_name', 'LIKE', '%Surabaya%');
                        });
                    } else {
                        $query->where('location_name', $locationTerm);
                    }
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                return $query->orderBy('year', 'desc')
                             ->orderBy('location_name', 'asc')
                             ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('IPM Pengeluaran Per Kapita API request successful', [
                'location' => $location ?: 'all',
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
            
            Log::error('IPM Pengeluaran Per Kapita API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching IPM Pengeluaran Per Kapita data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get IPM Indeks Kesehatan data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndeksKesehatan(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'location' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('IPM Indeks Kesehatan API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $location = $request->input('location', 'Kota Surabaya');
            $year = $request->input('year', '');
            $cacheKey = "ipm_indeks_kesehatan_api:location:" . md5($location) . ":year:{$year}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($location, $year) {
                $query = IPM_IndeksKesehatan::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value',
                    'created_at',
                    'updated_at'
                ]);

                if (!empty($location)) {
                    $locationTerm = trim($location);
                    // Support multiple location name formats
                    if (stripos($locationTerm, 'surabaya') !== false) {
                        $query->where(function($q) {
                            $q->where('location_name', 'Surabaya')
                              ->orWhere('location_name', 'Kota Surabaya')
                              ->orWhere('location_name', 'LIKE', '%Surabaya%');
                        });
                    } else {
                        $query->where('location_name', $locationTerm);
                    }
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                return $query->orderBy('year', 'desc')
                             ->orderBy('location_name', 'asc')
                             ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('IPM Indeks Kesehatan API request successful', [
                'location' => $location ?: 'all',
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
            
            Log::error('IPM Indeks Kesehatan API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching IPM Indeks Kesehatan data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get IPM Indeks Pendidikan data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndeksPendidikan(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'location' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('IPM Indeks Pendidikan API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $location = $request->input('location', 'Kota Surabaya');
            $year = $request->input('year', '');
            $cacheKey = "ipm_indeks_pendidikan_api:location:" . md5($location) . ":year:{$year}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($location, $year) {
                $query = IPM_IndeksPendidikan::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value',
                    'created_at',
                    'updated_at'
                ]);

                if (!empty($location)) {
                    $locationTerm = trim($location);
                    // Support multiple location name formats
                    if (stripos($locationTerm, 'surabaya') !== false) {
                        $query->where(function($q) {
                            $q->where('location_name', 'Surabaya')
                              ->orWhere('location_name', 'Kota Surabaya')
                              ->orWhere('location_name', 'LIKE', '%Surabaya%');
                        });
                    } else {
                        $query->where('location_name', $locationTerm);
                    }
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                return $query->orderBy('year', 'desc')
                             ->orderBy('location_name', 'asc')
                             ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('IPM Indeks Pendidikan API request successful', [
                'location' => $location ?: 'all',
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
            
            Log::error('IPM Indeks Pendidikan API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching IPM Indeks Pendidikan data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get IPM Indeks Hidup Layak data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndeksHidupLayak(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'location' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('IPM Indeks Hidup Layak API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $location = $request->input('location', 'Kota Surabaya');
            $year = $request->input('year', '');
            $cacheKey = "ipm_indeks_hidup_layak_api:location:" . md5($location) . ":year:{$year}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($location, $year) {
                $query = IPM_IndeksHidupLayak::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value',
                    'created_at',
                    'updated_at'
                ]);

                if (!empty($location)) {
                    $locationTerm = trim($location);
                    // Support multiple location name formats
                    if (stripos($locationTerm, 'surabaya') !== false) {
                        $query->where(function($q) {
                            $q->where('location_name', 'Surabaya')
                              ->orWhere('location_name', 'Kota Surabaya')
                              ->orWhere('location_name', 'LIKE', '%Surabaya%');
                        });
                    } else {
                        $query->where('location_name', $locationTerm);
                    }
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                return $query->orderBy('year', 'desc')
                             ->orderBy('location_name', 'asc')
                             ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('IPM Indeks Hidup Layak API request successful', [
                'location' => $location ?: 'all',
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
            
            Log::error('IPM Indeks Hidup Layak API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching IPM Indeks Hidup Layak data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get IPM HLS summary data (latest, previous, and changes for Surabaya and Jawa Timur).
     * This endpoint provides all data needed for the main HLS page.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHlsSummary(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Build cache key for summary
            $cacheKey = "ipm_hls_summary_api";

            // Try to get from cache
            $summary = Cache::remember($cacheKey, self::CACHE_DURATION_SUMMARY, function () {
                // Get all Surabaya HLS data ordered by year (optimized - only needed columns)
                $surabaya_data = IPM_HLS::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                })->orderBy('year', 'asc')->get();
                
                // Get all Jawa Timur HLS data ordered by year (optimized - only needed columns)
                $jatim_data = IPM_HLS::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                })->orderBy('year', 'asc')->get();
                
                // Get latest Surabaya data (optimized query)
                $surabaya_latest = IPM_HLS::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                })->orderBy('year', 'desc')->first();
                
                // Get latest Jawa Timur data (optimized query)
                $jatim_latest = IPM_HLS::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                })->orderBy('year', 'desc')->first();
                
                // Get previous Surabaya data (second latest) - optimized query
                $surabaya_previous = IPM_HLS::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                })->orderBy('year', 'desc')->skip(1)->first();
                
                // Get previous Jawa Timur data (second latest) - optimized query
                $jatim_previous = IPM_HLS::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                })->orderBy('year', 'desc')->skip(1)->first();
                
                // Calculate changes
                $surabaya_change = null;
                $jatim_change = null;
                
                if ($surabaya_latest && $surabaya_previous) {
                    if ($surabaya_latest->value !== null && $surabaya_previous->value !== null) {
                        $surabaya_change = round($surabaya_latest->value - $surabaya_previous->value, 2);
                    }
                }
                
                if ($jatim_latest && $jatim_previous) {
                    if ($jatim_latest->value !== null && $jatim_previous->value !== null) {
                        $jatim_change = round($jatim_latest->value - $jatim_previous->value, 2);
                    }
                }
                
                return [
                    'surabaya_data' => $surabaya_data,
                    'jatim_data' => $jatim_data,
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
            Log::info('IPM HLS Summary API request successful', [
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
            Log::error('IPM HLS Summary API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching IPM HLS summary data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get IPM RLS summary data (latest, previous, and changes for Surabaya and Jawa Timur).
     * This endpoint provides all data needed for the main RLS page.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRlsSummary(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Build cache key for summary
            $cacheKey = "ipm_rls_summary_api";

            // Try to get from cache
            $summary = Cache::remember($cacheKey, self::CACHE_DURATION_SUMMARY, function () {
                // Get all Surabaya RLS data ordered by year (optimized - only needed columns)
                $surabaya_data = IPM_RLS::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                })->orderBy('year', 'asc')->get();
                
                // Get all Jawa Timur RLS data ordered by year (optimized - only needed columns)
                $jatim_data = IPM_RLS::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                })->orderBy('year', 'asc')->get();
                
                // Get latest Surabaya data (optimized query)
                $surabaya_latest = IPM_RLS::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                })->orderBy('year', 'desc')->first();
                
                // Get latest Jawa Timur data (optimized query)
                $jatim_latest = IPM_RLS::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                })->orderBy('year', 'desc')->first();
                
                // Get previous Surabaya data (second latest) - optimized query
                $surabaya_previous = IPM_RLS::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                })->orderBy('year', 'desc')->skip(1)->first();
                
                // Get previous Jawa Timur data (second latest) - optimized query
                $jatim_previous = IPM_RLS::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                })->orderBy('year', 'desc')->skip(1)->first();
                
                // Calculate changes
                $surabaya_change = null;
                $jatim_change = null;
                
                if ($surabaya_latest && $surabaya_previous) {
                    if ($surabaya_latest->value !== null && $surabaya_previous->value !== null) {
                        $surabaya_change = round($surabaya_latest->value - $surabaya_previous->value, 2);
                    }
                }
                
                if ($jatim_latest && $jatim_previous) {
                    if ($jatim_latest->value !== null && $jatim_previous->value !== null) {
                        $jatim_change = round($jatim_latest->value - $jatim_previous->value, 2);
                    }
                }
                
                return [
                    'surabaya_data' => $surabaya_data,
                    'jatim_data' => $jatim_data,
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
            Log::info('IPM RLS Summary API request successful', [
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
            Log::error('IPM RLS Summary API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching IPM RLS summary data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get IPM Indeks Hidup Layak summary data (latest, previous, and changes for Surabaya and Jawa Timur).
     * This endpoint provides all data needed for the main Indeks Hidup Layak page.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndeksHidupLayakSummary(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Build cache key for summary
            $cacheKey = "ipm_indeks_hidup_layak_summary_api";

            // Try to get from cache
            $summary = Cache::remember($cacheKey, self::CACHE_DURATION_SUMMARY, function () {
                // Get all Surabaya Indeks Hidup Layak data ordered by year (optimized - only needed columns)
                $surabaya_data = IPM_IndeksHidupLayak::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                })->orderBy('year', 'asc')->get();
                
                // Get all Jawa Timur Indeks Hidup Layak data ordered by year (optimized - only needed columns)
                $jatim_data = IPM_IndeksHidupLayak::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                })->orderBy('year', 'asc')->get();
                
                // Get latest Surabaya data (optimized query)
                $surabaya_latest = IPM_IndeksHidupLayak::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                })->orderBy('year', 'desc')->first();
                
                // Get latest Jawa Timur data (optimized query)
                $jatim_latest = IPM_IndeksHidupLayak::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                })->orderBy('year', 'desc')->first();
                
                // Get previous Surabaya data (second latest) - optimized query
                $surabaya_previous = IPM_IndeksHidupLayak::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                })->orderBy('year', 'desc')->skip(1)->first();
                
                // Get previous Jawa Timur data (second latest) - optimized query
                $jatim_previous = IPM_IndeksHidupLayak::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                })->orderBy('year', 'desc')->skip(1)->first();
                
                // Calculate changes
                $surabaya_change = null;
                $jatim_change = null;
                
                if ($surabaya_latest && $surabaya_previous) {
                    if ($surabaya_latest->value !== null && $surabaya_previous->value !== null) {
                        $surabaya_change = round($surabaya_latest->value - $surabaya_previous->value, 2);
                    }
                }
                
                if ($jatim_latest && $jatim_previous) {
                    if ($jatim_latest->value !== null && $jatim_previous->value !== null) {
                        $jatim_change = round($jatim_latest->value - $jatim_previous->value, 2);
                    }
                }
                
                return [
                    'surabaya_data' => $surabaya_data,
                    'jatim_data' => $jatim_data,
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
            Log::info('IPM Indeks Hidup Layak Summary API request successful', [
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
            Log::error('IPM Indeks Hidup Layak Summary API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching IPM Indeks Hidup Layak summary data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get IPM Indeks Kesehatan summary data (latest, previous, and changes for Surabaya and Jawa Timur).
     * This endpoint provides all data needed for the main Indeks Kesehatan page.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndeksKesehatanSummary(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Build cache key for summary
            $cacheKey = "ipm_indeks_kesehatan_summary_api";

            // Try to get from cache
            $summary = Cache::remember($cacheKey, self::CACHE_DURATION_SUMMARY, function () {
                // Get all Surabaya Indeks Kesehatan data ordered by year (optimized - only needed columns)
                $surabaya_data = IPM_IndeksKesehatan::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                })->orderBy('year', 'asc')->get();
                
                // Get all Jawa Timur Indeks Kesehatan data ordered by year (optimized - only needed columns)
                $jatim_data = IPM_IndeksKesehatan::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                })->orderBy('year', 'asc')->get();
                
                // Get latest Surabaya data (optimized query)
                $surabaya_latest = IPM_IndeksKesehatan::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                })->orderBy('year', 'desc')->first();
                
                // Get latest Jawa Timur data (optimized query)
                $jatim_latest = IPM_IndeksKesehatan::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                })->orderBy('year', 'desc')->first();
                
                // Get previous Surabaya data (second latest) - optimized query
                $surabaya_previous = IPM_IndeksKesehatan::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                })->orderBy('year', 'desc')->skip(1)->first();
                
                // Get previous Jawa Timur data (second latest) - optimized query
                $jatim_previous = IPM_IndeksKesehatan::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                })->orderBy('year', 'desc')->skip(1)->first();
                
                // Calculate changes
                $surabaya_change = null;
                $jatim_change = null;
                
                if ($surabaya_latest && $surabaya_previous) {
                    if ($surabaya_latest->value !== null && $surabaya_previous->value !== null) {
                        $surabaya_change = round($surabaya_latest->value - $surabaya_previous->value, 2);
                    }
                }
                
                if ($jatim_latest && $jatim_previous) {
                    if ($jatim_latest->value !== null && $jatim_previous->value !== null) {
                        $jatim_change = round($jatim_latest->value - $jatim_previous->value, 2);
                    }
                }
                
                return [
                    'surabaya_data' => $surabaya_data,
                    'jatim_data' => $jatim_data,
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
            Log::info('IPM Indeks Kesehatan Summary API request successful', [
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
            Log::error('IPM Indeks Kesehatan Summary API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching IPM Indeks Kesehatan summary data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get IPM Indeks Pendidikan summary data (latest, previous, and changes for Surabaya and Jawa Timur).
     * This endpoint provides all data needed for the main Indeks Pendidikan page.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndeksPendidikanSummary(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Build cache key for summary
            $cacheKey = "ipm_indeks_pendidikan_summary_api";

            // Try to get from cache
            $summary = Cache::remember($cacheKey, self::CACHE_DURATION_SUMMARY, function () {
                // Get all Surabaya Indeks Pendidikan data ordered by year (optimized - only needed columns)
                $surabaya_data = IPM_IndeksPendidikan::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                })->orderBy('year', 'asc')->get();
                
                // Get all Jawa Timur Indeks Pendidikan data ordered by year (optimized - only needed columns)
                $jatim_data = IPM_IndeksPendidikan::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                })->orderBy('year', 'asc')->get();
                
                // Get latest Surabaya data (optimized query)
                $surabaya_latest = IPM_IndeksPendidikan::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                })->orderBy('year', 'desc')->first();
                
                // Get latest Jawa Timur data (optimized query)
                $jatim_latest = IPM_IndeksPendidikan::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                })->orderBy('year', 'desc')->first();
                
                // Get previous Surabaya data (second latest) - optimized query
                $surabaya_previous = IPM_IndeksPendidikan::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                })->orderBy('year', 'desc')->skip(1)->first();
                
                // Get previous Jawa Timur data (second latest) - optimized query
                $jatim_previous = IPM_IndeksPendidikan::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                })->orderBy('year', 'desc')->skip(1)->first();
                
                // Calculate changes
                $surabaya_change = null;
                $jatim_change = null;
                
                if ($surabaya_latest && $surabaya_previous) {
                    if ($surabaya_latest->value !== null && $surabaya_previous->value !== null) {
                        $surabaya_change = round($surabaya_latest->value - $surabaya_previous->value, 2);
                    }
                }
                
                if ($jatim_latest && $jatim_previous) {
                    if ($jatim_latest->value !== null && $jatim_previous->value !== null) {
                        $jatim_change = round($jatim_latest->value - $jatim_previous->value, 2);
                    }
                }
                
                return [
                    'surabaya_data' => $surabaya_data,
                    'jatim_data' => $jatim_data,
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
            Log::info('IPM Indeks Pendidikan Summary API request successful', [
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
            Log::error('IPM Indeks Pendidikan Summary API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching IPM Indeks Pendidikan summary data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get IPM Pengeluaran Per Kapita summary data (latest, previous, and changes for Surabaya and Jawa Timur).
     * This endpoint provides all data needed for the main Pengeluaran Per Kapita page.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPengeluaranPerKapitaSummary(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Build cache key for summary
            $cacheKey = "ipm_pengeluaran_per_kapita_summary_api";

            // Try to get from cache
            $summary = Cache::remember($cacheKey, self::CACHE_DURATION_SUMMARY, function () {
                // Get all Surabaya Pengeluaran Per Kapita data ordered by year (optimized - only needed columns)
                $surabaya_data = IPM_PengeluaranPerKapita::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                })->orderBy('year', 'asc')->get();
                
                // Get all Jawa Timur Pengeluaran Per Kapita data ordered by year (optimized - only needed columns)
                $jatim_data = IPM_PengeluaranPerKapita::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                })->orderBy('year', 'asc')->get();
                
                // Get latest Surabaya data (optimized query)
                $surabaya_latest = IPM_PengeluaranPerKapita::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                })->orderBy('year', 'desc')->first();
                
                // Get latest Jawa Timur data (optimized query)
                $jatim_latest = IPM_PengeluaranPerKapita::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                })->orderBy('year', 'desc')->first();
                
                // Get previous Surabaya data (second latest) - optimized query
                $surabaya_previous = IPM_PengeluaranPerKapita::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                })->orderBy('year', 'desc')->skip(1)->first();
                
                // Get previous Jawa Timur data (second latest) - optimized query
                $jatim_previous = IPM_PengeluaranPerKapita::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                })->orderBy('year', 'desc')->skip(1)->first();
                
                // Calculate changes
                $surabaya_change = null;
                $jatim_change = null;
                
                if ($surabaya_latest && $surabaya_previous) {
                    if ($surabaya_latest->value !== null && $surabaya_previous->value !== null) {
                        $surabaya_change = round($surabaya_latest->value - $surabaya_previous->value, 2);
                    }
                }
                
                if ($jatim_latest && $jatim_previous) {
                    if ($jatim_latest->value !== null && $jatim_previous->value !== null) {
                        $jatim_change = round($jatim_latest->value - $jatim_previous->value, 2);
                    }
                }
                
                return [
                    'surabaya_data' => $surabaya_data,
                    'jatim_data' => $jatim_data,
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
            Log::info('IPM Pengeluaran Per Kapita Summary API request successful', [
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
            Log::error('IPM Pengeluaran Per Kapita Summary API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching IPM Pengeluaran Per Kapita summary data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get IPM UHH SP summary data (latest, previous, and changes for Surabaya and Jawa Timur).
     * This endpoint provides all data needed for the main UHH SP page.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUhhSpSummary(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Build cache key for summary
            $cacheKey = "ipm_uhh_sp_summary_api";

            // Try to get from cache
            $summary = Cache::remember($cacheKey, self::CACHE_DURATION_SUMMARY, function () {
                // Get all Surabaya UHH SP data ordered by year (optimized - only needed columns)
                $surabaya_data = IPM_UHH_SP::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                })->orderBy('year', 'asc')->get();
                
                // Get all Jawa Timur UHH SP data ordered by year (optimized - only needed columns)
                $jatim_data = IPM_UHH_SP::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                })->orderBy('year', 'asc')->get();
                
                // Get latest Surabaya data (optimized query)
                $surabaya_latest = IPM_UHH_SP::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                })->orderBy('year', 'desc')->first();
                
                // Get latest Jawa Timur data (optimized query)
                $jatim_latest = IPM_UHH_SP::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                })->orderBy('year', 'desc')->first();
                
                // Get previous Surabaya data (second latest) - optimized query
                $surabaya_previous = IPM_UHH_SP::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Surabaya')
                      ->orWhere('location_name', 'Kota Surabaya');
                })->orderBy('year', 'desc')->skip(1)->first();
                
                // Get previous Jawa Timur data (second latest) - optimized query
                $jatim_previous = IPM_UHH_SP::select([
                    'id',
                    'location_name',
                    'location_type',
                    'year',
                    'value'
                ])->where(function($q) {
                    $q->where('location_name', 'Jawa Timur')
                      ->orWhere('location_name', 'Provinsi Jawa Timur');
                })->orderBy('year', 'desc')->skip(1)->first();
                
                // Calculate changes
                $surabaya_change = null;
                $jatim_change = null;
                
                if ($surabaya_latest && $surabaya_previous) {
                    if ($surabaya_latest->value !== null && $surabaya_previous->value !== null) {
                        $surabaya_change = round($surabaya_latest->value - $surabaya_previous->value, 2);
                    }
                }
                
                if ($jatim_latest && $jatim_previous) {
                    if ($jatim_latest->value !== null && $jatim_previous->value !== null) {
                        $jatim_change = round($jatim_latest->value - $jatim_previous->value, 2);
                    }
                }
                
                return [
                    'surabaya_data' => $surabaya_data,
                    'jatim_data' => $jatim_data,
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
            Log::info('IPM UHH SP Summary API request successful', [
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
            Log::error('IPM UHH SP Summary API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching IPM UHH SP summary data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}

