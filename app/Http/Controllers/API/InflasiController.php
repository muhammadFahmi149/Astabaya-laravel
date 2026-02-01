<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Inflasi;
use App\Models\InflasiPerKomoditas;

class InflasiController extends Controller
{
    /**
     * Cache duration in seconds (5 minutes)
     */
    private const CACHE_DURATION = 300;

    /**
     * Get Inflasi data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInflasi(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('Inflasi API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $year = $request->input('year', '');

            // Build cache key
            $cacheKey = "inflasi_api:year:{$year}";

            // Try to get from cache
            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($year) {
                $query = Inflasi::select([
                    'id',
                    'year',
                    'month',
                    'bulanan',
                    'kumulatif',
                    'yoy',
                    'created_at',
                    'updated_at'
                ]);

                // Year filter
                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                // Order by year desc, then by month order
                return $query->orderBy('year', 'desc')
                    ->orderByRaw("FIELD(month, 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOPEMBER', 'DESEMBER')")
                    ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log successful request
            Log::info('Inflasi API request successful', [
                'year' => $year ?: 'all',
                'total_results' => $data->count(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log error
            Log::error('Inflasi API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching Inflasi data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get Inflasi Per Komoditas data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInflasiPerKomoditas(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'commodity_code' => 'sometimes|string|max:50',
                'flag' => 'sometimes|integer|in:1,2,3',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('Inflasi Per Komoditas API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $commodityCode = $request->input('commodity_code', '');
            $flag = $request->input('flag', '');
            $year = $request->input('year', '');

            // Build cache key
            $cacheKey = "inflasi_perkomoditas_api:code:" . md5($commodityCode) . ":flag:{$flag}:year:{$year}";

            // Try to get from cache
            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($commodityCode, $flag, $year) {
                $query = InflasiPerKomoditas::select([
                    'id',
                    'commodity_code',
                    'commodity_name',
                    'flag',
                    'year',
                    'month',
                    'value',
                    'created_at',
                    'updated_at'
                ]);

                // Commodity code filter
                if (!empty($commodityCode)) {
                    $query->where('commodity_code', trim($commodityCode));
                }

                // Flag filter
                if (!empty($flag) && is_numeric($flag)) {
                    $query->where('flag', (int)$flag);
                }

                // Year filter
                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                // Order by year desc, then by month order
                return $query->orderBy('year', 'desc')
                    ->orderByRaw("FIELD(month, 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOPEMBER', 'DESEMBER')")
                    ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log successful request
            Log::info('Inflasi Per Komoditas API request successful', [
                'commodity_code' => $commodityCode ?: 'all',
                'flag' => $flag ?: 'all',
                'year' => $year ?: 'all',
                'total_results' => $data->count(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log error
            Log::error('Inflasi Per Komoditas API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching Inflasi Per Komoditas data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get komoditas grouped by flag.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getKomoditasByFlag(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'flag' => 'sometimes|integer|in:1,2,3',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
                'parent_code' => 'sometimes|string|max:50',
            ]);

            if ($validator->fails()) {
                Log::warning('Komoditas By Flag API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $flag = $request->input('flag', '1');
            $year = $request->input('year', '');
            $parentCode = $request->input('parent_code', '');

            // Build cache key
            $cacheKey = "komoditas_by_flag_api:flag:{$flag}:year:{$year}:parent:" . md5($parentCode);

            // Try to get from cache
            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($flag, $year, $parentCode) {
                $query = InflasiPerKomoditas::select('commodity_code', 'commodity_name')
                    ->where('flag', (int)$flag)
                    ->distinct();

                // Year filter
                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                // Parent code filter (for sub komoditas)
                if (!empty($parentCode)) {
                    // Assuming parent_code is stored in commodity_code or needs special handling
                    // Adjust based on your actual database structure
                    $query->where('commodity_code', 'like', trim($parentCode) . '%');
                }

                return $query->orderBy('commodity_name')
                    ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log successful request
            Log::info('Komoditas By Flag API request successful', [
                'flag' => $flag,
                'year' => $year ?: 'all',
                'parent_code' => $parentCode ?: 'all',
                'total_results' => $data->count(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log error
            Log::error('Komoditas By Flag API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching Komoditas By Flag data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get summary data for inflasi (latest data and changes).
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSummary(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $cacheKey = "inflasi_summary_api";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () {
                // Get latest inflasi data
                $latest = Inflasi::orderBy('year', 'desc')
                    ->orderByRaw("FIELD(month, 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOPEMBER', 'DESEMBER')")
                    ->first();

                if (!$latest) {
                    return [
                        'latest' => null,
                        'previous_month' => null,
                        'previous_year' => null,
                        'm_to_m_change' => null,
                        'y_on_y_change' => null,
                    ];
                }

                // Get previous month data
                $previousMonth = Inflasi::where('year', $latest->year)
                    ->whereRaw("FIELD(month, 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOPEMBER', 'DESEMBER') < FIELD(?, 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOPEMBER', 'DESEMBER')", [$latest->month])
                    ->orderByRaw("FIELD(month, 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOPEMBER', 'DESEMBER') DESC")
                    ->first();

                // If no previous month in same year, get last month of previous year
                if (!$previousMonth) {
                    $previousMonth = Inflasi::where('year', $latest->year - 1)
                        ->orderByRaw("FIELD(month, 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOPEMBER', 'DESEMBER') DESC")
                        ->first();
                }

                // Get previous year same month data
                $previousYear = Inflasi::where('year', $latest->year - 1)
                    ->where('month', $latest->month)
                    ->first();

                // Calculate changes
                $mToMChange = null;
                if ($latest->bulanan !== null && $previousMonth && $previousMonth->bulanan !== null) {
                    $mToMChange = $latest->bulanan - $previousMonth->bulanan;
                }

                $yOnYChange = null;
                if ($latest->yoy !== null && $previousYear && $previousYear->yoy !== null) {
                    $yOnYChange = $latest->yoy - $previousYear->yoy;
                }

                return [
                    'latest' => $latest,
                    'previous_month' => $previousMonth,
                    'previous_year' => $previousYear,
                    'm_to_m_change' => $mToMChange,
                    'y_on_y_change' => $yOnYChange,
                ];
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('Inflasi Summary API request successful', [
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('Inflasi Summary API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching Inflasi Summary data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get all available years for inflasi data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getYears(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $cacheKey = "inflasi_years_api";

            $years = Cache::remember($cacheKey, self::CACHE_DURATION, function () {
                return Inflasi::select('year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year')
                    ->toArray();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('Inflasi Years API request successful', [
                'total_years' => count($years),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $years
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('Inflasi Years API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching Inflasi Years data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get all available years for komoditas data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getKomoditasYears(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $cacheKey = "inflasi_komoditas_years_api";

            $years = Cache::remember($cacheKey, self::CACHE_DURATION, function () {
                return InflasiPerKomoditas::select('year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year')
                    ->toArray();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('Inflasi Komoditas Years API request successful', [
                'total_years' => count($years),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $years
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('Inflasi Komoditas Years API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching Inflasi Komoditas Years data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}

