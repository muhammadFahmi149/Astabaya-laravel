<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Models\PDRBPengeluaranADHB;
use App\Models\PDRBPengeluaranADHK;
use App\Models\PDRBPengeluaranDistribusi;
use App\Models\PDRBPengeluaranLajuPDRB;
use App\Models\PDRBPengeluaranADHBTriwulanan;
use App\Models\PDRBPengeluaranADHKTriwulanan;
use App\Models\PDRBPengeluaranDistribusiTriwulanan;
use App\Models\PDRBPengeluaranLajuQtoQ;
use App\Models\PDRBPengeluaranLajuYtoY;
use App\Models\PDRBPengeluaranLajuCtoC;
use App\Models\PDRBLapanganUsahaADHB;
use App\Models\PDRBLapanganUsahaADHK;
use App\Models\PDRBLapanganUsahaDistribusi;
use App\Models\PDRBLapanganUsahaLajuPDRB;
use App\Models\PDRBLapanganUsahaLajuImplisit;
use App\Models\PDRBLapanganUsahaADHBTriwulanan;
use App\Models\PDRBLapanganUsahaADHKTriwulanan;
use App\Models\PDRBLapanganUsahaDistribusiTriwulanan;
use App\Models\PDRBLapanganUsahaLajuQtoQ;
use App\Models\PDRBLapanganUsahaLajuYtoY;
use App\Models\PDRBLapanganUsahaLajuCtoC;
use Illuminate\Support\Facades\DB;

class PDRBController extends Controller
{
    /**
     * Cache duration in seconds (5 minutes)
     */
    private const CACHE_DURATION = 300;

    /**
     * Get PDRB Pengeluaran ADHB (Atas Dasar Harga Berlaku) data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPengeluaranAdhb(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('PDRB Pengeluaran ADHB API validation failed', [
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

            $category = $request->input('category', '');
            $year = $request->input('year', '');

            // Build cache key
            $cacheKey = "pdrb_pengeluaran_adhb_api:category:" . md5($category) . ":year:{$year}";

            // Try to get from cache
            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category, $year) {
                // Build query with only needed columns for better performance
                $query = PDRBPengeluaranADHB::select([
                    'id',
                    'expenditure_category',
                    'year',
                    'preliminary_flag',
                    'value',
                    'created_at',
                    'updated_at'
                ]);

                // Category filter - sanitized input
                if (!empty($category)) {
                    $categoryTerm = trim($category);
                    $query->where('expenditure_category', $categoryTerm);
                }

                // Year filter
                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                // Order by year desc, then category
                $query->orderBy('year', 'desc')
                      ->orderBy('expenditure_category', 'asc');

                return $query->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log successful request
            Log::info('PDRB Pengeluaran ADHB API request successful', [
                'category' => $category ?: 'all',
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
            Log::error('PDRB Pengeluaran ADHB API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PDRB Pengeluaran ADHB data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get PDRB Pengeluaran ADHK (Atas Dasar Harga Konstan) data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPengeluaranAdhk(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('PDRB Pengeluaran ADHK API validation failed', [
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

            $category = $request->input('category', '');
            $year = $request->input('year', '');

            // Build cache key
            $cacheKey = "pdrb_pengeluaran_adhk_api:category:" . md5($category) . ":year:{$year}";

            // Try to get from cache
            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category, $year) {
                // Build query with only needed columns for better performance
                $query = PDRBPengeluaranADHK::select([
                    'id',
                    'expenditure_category',
                    'year',
                    'preliminary_flag',
                    'value',
                    'created_at',
                    'updated_at'
                ]);

                // Category filter - sanitized input
                if (!empty($category)) {
                    $categoryTerm = trim($category);
                    $query->where('expenditure_category', $categoryTerm);
                }

                // Year filter
                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                // Order by year desc, then category
                $query->orderBy('year', 'desc')
                      ->orderBy('expenditure_category', 'asc');

                return $query->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log successful request
            Log::info('PDRB Pengeluaran ADHK API request successful', [
                'category' => $category ?: 'all',
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
            Log::error('PDRB Pengeluaran ADHK API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PDRB Pengeluaran ADHK data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get PDRB Pengeluaran Distribusi data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPengeluaranDistribusi(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('PDRB Pengeluaran Distribusi API validation failed', [
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

            $category = $request->input('category', '');
            $year = $request->input('year', '');

            // Build cache key
            $cacheKey = "pdrb_pengeluaran_distribusi_api:category:" . md5($category) . ":year:{$year}";

            // Try to get from cache
            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category, $year) {
                // Build query with only needed columns for better performance
                $query = PDRBPengeluaranDistribusi::select([
                    'id',
                    'expenditure_category',
                    'year',
                    'preliminary_flag',
                    'value',
                    'created_at',
                    'updated_at'
                ]);

                // Category filter - sanitized input
                if (!empty($category)) {
                    $categoryTerm = trim($category);
                    $query->where('expenditure_category', $categoryTerm);
                }

                // Year filter
                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                // Order by year desc, then category
                $query->orderBy('year', 'desc')
                      ->orderBy('expenditure_category', 'asc');

                return $query->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log successful request
            Log::info('PDRB Pengeluaran Distribusi API request successful', [
                'category' => $category ?: 'all',
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
            Log::error('PDRB Pengeluaran Distribusi API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PDRB Pengeluaran Distribusi data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get PDRB Pengeluaran Laju PDRB (Growth Rate) data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPengeluaranLaju(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('PDRB Pengeluaran Laju API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $category = $request->input('category', '');
            $year = $request->input('year', '');

            $cacheKey = "pdrb_pengeluaran_laju_api:category:" . md5($category) . ":year:{$year}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category, $year) {
                $query = PDRBPengeluaranLajuPDRB::select([
                    'id',
                    'expenditure_category',
                    'year',
                    'preliminary_flag',
                    'value',
                ]);

                if (!empty($category)) {
                    $query->where('expenditure_category', trim($category));
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                return $query->orderBy('year', 'desc')
                      ->orderBy('expenditure_category', 'asc')
                      ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Pengeluaran Laju API request successful', [
                'category' => $category ?: 'all',
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
            
            Log::error('PDRB Pengeluaran Laju API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PDRB Pengeluaran Laju data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get PDRB Pengeluaran ADHB Triwulanan data.
     */
    public function getPengeluaranAdhbTriwulanan(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
                'quarter' => 'sometimes|string|in:I,II,III,IV',
            ]);

            if ($validator->fails()) {
                Log::warning('PDRB Pengeluaran ADHB Triwulanan API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $category = $request->input('category', '');
            $year = $request->input('year', '');
            $quarter = $request->input('quarter', '');

            $cacheKey = "pdrb_pengeluaran_adhb_triwulanan_api:category:" . md5($category) . ":year:{$year}:quarter:{$quarter}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category, $year, $quarter) {
                $query = PDRBPengeluaranADHBTriwulanan::select([
                    'id',
                    'expenditure_category',
                    'year',
                    'quarter',
                    'preliminary_flag',
                    'value',
                ]);

                if (!empty($category)) {
                    $query->where('expenditure_category', trim($category));
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                if (!empty($quarter)) {
                    $query->where('quarter', $quarter);
                }

                return $query->orderBy('year', 'desc')
                      ->orderByRaw("FIELD(quarter, 'I', 'II', 'III', 'IV')")
                      ->orderBy('expenditure_category', 'asc')
                      ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Pengeluaran ADHB Triwulanan API request successful', [
                'category' => $category ?: 'all',
                'year' => $year ?: 'all',
                'quarter' => $quarter ?: 'all',
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
            
            Log::error('PDRB Pengeluaran ADHB Triwulanan API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PDRB Pengeluaran ADHB Triwulanan data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get PDRB Pengeluaran ADHK Triwulanan data.
     */
    public function getPengeluaranAdhkTriwulanan(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
                'quarter' => 'sometimes|string|in:I,II,III,IV',
            ]);

            if ($validator->fails()) {
                Log::warning('PDRB Pengeluaran ADHK Triwulanan API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $category = $request->input('category', '');
            $year = $request->input('year', '');
            $quarter = $request->input('quarter', '');

            $cacheKey = "pdrb_pengeluaran_adhk_triwulanan_api:category:" . md5($category) . ":year:{$year}:quarter:{$quarter}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category, $year, $quarter) {
                $query = PDRBPengeluaranADHKTriwulanan::select([
                    'id',
                    'expenditure_category',
                    'year',
                    'quarter',
                    'preliminary_flag',
                    'value',
                ]);

                if (!empty($category)) {
                    $query->where('expenditure_category', trim($category));
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                if (!empty($quarter)) {
                    $query->where('quarter', $quarter);
                }

                return $query->orderBy('year', 'desc')
                      ->orderByRaw("FIELD(quarter, 'I', 'II', 'III', 'IV')")
                      ->orderBy('expenditure_category', 'asc')
                      ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Pengeluaran ADHK Triwulanan API request successful', [
                'category' => $category ?: 'all',
                'year' => $year ?: 'all',
                'quarter' => $quarter ?: 'all',
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
            
            Log::error('PDRB Pengeluaran ADHK Triwulanan API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PDRB Pengeluaran ADHK Triwulanan data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get PDRB Pengeluaran Distribusi Triwulanan data.
     */
    public function getPengeluaranDistribusiTriwulanan(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
                'quarter' => 'sometimes|string|in:I,II,III,IV',
            ]);

            if ($validator->fails()) {
                Log::warning('PDRB Pengeluaran Distribusi Triwulanan API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $category = $request->input('category', '');
            $year = $request->input('year', '');
            $quarter = $request->input('quarter', '');

            $cacheKey = "pdrb_pengeluaran_distribusi_triwulanan_api:category:" . md5($category) . ":year:{$year}:quarter:{$quarter}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category, $year, $quarter) {
                $query = PDRBPengeluaranDistribusiTriwulanan::select([
                    'id',
                    'expenditure_category',
                    'year',
                    'quarter',
                    'preliminary_flag',
                    'value',
                ]);

                if (!empty($category)) {
                    $query->where('expenditure_category', trim($category));
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                if (!empty($quarter)) {
                    $query->where('quarter', $quarter);
                }

                return $query->orderBy('year', 'desc')
                      ->orderByRaw("FIELD(quarter, 'I', 'II', 'III', 'IV')")
                      ->orderBy('expenditure_category', 'asc')
                      ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Pengeluaran Distribusi Triwulanan API request successful', [
                'category' => $category ?: 'all',
                'year' => $year ?: 'all',
                'quarter' => $quarter ?: 'all',
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
            
            Log::error('PDRB Pengeluaran Distribusi Triwulanan API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PDRB Pengeluaran Distribusi Triwulanan data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get PDRB Pengeluaran Laju Q-to-Q data.
     */
    public function getPengeluaranLajuQtoQ(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
                'quarter' => 'sometimes|string|in:I,II,III,IV',
            ]);

            if ($validator->fails()) {
                Log::warning('PDRB Pengeluaran Laju Q-to-Q API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $category = $request->input('category', '');
            $year = $request->input('year', '');
            $quarter = $request->input('quarter', '');

            $cacheKey = "pdrb_pengeluaran_laju_qtoq_api:category:" . md5($category) . ":year:{$year}:quarter:{$quarter}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category, $year, $quarter) {
                $query = PDRBPengeluaranLajuQtoQ::select([
                    'id',
                    'expenditure_category',
                    'year',
                    'quarter',
                    'preliminary_flag',
                    'value',
                ]);

                if (!empty($category)) {
                    $query->where('expenditure_category', trim($category));
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                if (!empty($quarter)) {
                    $query->where('quarter', $quarter);
                }

                return $query->orderBy('year', 'desc')
                      ->orderByRaw("FIELD(quarter, 'I', 'II', 'III', 'IV')")
                      ->orderBy('expenditure_category', 'asc')
                      ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Pengeluaran Laju Q-to-Q API request successful', [
                'category' => $category ?: 'all',
                'year' => $year ?: 'all',
                'quarter' => $quarter ?: 'all',
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
            
            Log::error('PDRB Pengeluaran Laju Q-to-Q API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PDRB Pengeluaran Laju Q-to-Q data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get PDRB Pengeluaran Laju Y-to-Y data.
     */
    public function getPengeluaranLajuYtoY(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
                'quarter' => 'sometimes|string|in:I,II,III,IV',
            ]);

            if ($validator->fails()) {
                Log::warning('PDRB Pengeluaran Laju Y-to-Y API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $category = $request->input('category', '');
            $year = $request->input('year', '');
            $quarter = $request->input('quarter', '');

            $cacheKey = "pdrb_pengeluaran_laju_ytoy_api:category:" . md5($category) . ":year:{$year}:quarter:{$quarter}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category, $year, $quarter) {
                $query = PDRBPengeluaranLajuYtoY::select([
                    'id',
                    'expenditure_category',
                    'year',
                    'quarter',
                    'preliminary_flag',
                    'value',
                ]);

                if (!empty($category)) {
                    $query->where('expenditure_category', trim($category));
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                if (!empty($quarter)) {
                    $query->where('quarter', $quarter);
                }

                return $query->orderBy('year', 'desc')
                      ->orderByRaw("FIELD(quarter, 'I', 'II', 'III', 'IV')")
                      ->orderBy('expenditure_category', 'asc')
                      ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Pengeluaran Laju Y-to-Y API request successful', [
                'category' => $category ?: 'all',
                'year' => $year ?: 'all',
                'quarter' => $quarter ?: 'all',
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
            
            Log::error('PDRB Pengeluaran Laju Y-to-Y API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PDRB Pengeluaran Laju Y-to-Y data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get PDRB Pengeluaran Laju C-to-C data.
     */
    public function getPengeluaranLajuCtoC(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
                'quarter' => 'sometimes|string|in:I,II,III,IV',
            ]);

            if ($validator->fails()) {
                Log::warning('PDRB Pengeluaran Laju C-to-C API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $category = $request->input('category', '');
            $year = $request->input('year', '');
            $quarter = $request->input('quarter', '');

            $cacheKey = "pdrb_pengeluaran_laju_ctoc_api:category:" . md5($category) . ":year:{$year}:quarter:{$quarter}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category, $year, $quarter) {
                $query = PDRBPengeluaranLajuCtoC::select([
                    'id',
                    'expenditure_category',
                    'year',
                    'quarter',
                    'preliminary_flag',
                    'value',
                ]);

                if (!empty($category)) {
                    $query->where('expenditure_category', trim($category));
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                if (!empty($quarter)) {
                    $query->where('quarter', $quarter);
                }

                return $query->orderBy('year', 'desc')
                      ->orderByRaw("FIELD(quarter, 'I', 'II', 'III', 'IV')")
                      ->orderBy('expenditure_category', 'asc')
                      ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Pengeluaran Laju C-to-C API request successful', [
                'category' => $category ?: 'all',
                'year' => $year ?: 'all',
                'quarter' => $quarter ?: 'all',
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
            
            Log::error('PDRB Pengeluaran Laju C-to-C API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PDRB Pengeluaran Laju C-to-C data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get summary data grouped by sheet name (for carousel).
     * Returns latest data for each sheet type.
     */
    public function getSummaryBySheet(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $cacheKey = "pdrb_pengeluaran_summary_by_sheet";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () {
                $summary = [];

                // ADHB
                $adhbLatest = PDRBPengeluaranADHB::select('expenditure_category', 'year', 'value', 'preliminary_flag')
                    ->where('expenditure_category', 'LIKE', '%PDRB%')
                    ->orWhere('expenditure_category', 'LIKE', '%Produk Domestik Regional Bruto%')
                    ->orderBy('year', 'desc')
                    ->orderBy('expenditure_category', 'asc')
                    ->first();
                
                if ($adhbLatest) {
                    $summary['ADHB'] = [
                        'category' => $adhbLatest->expenditure_category,
                        'data' => [
                            'year' => $adhbLatest->year,
                            'value' => $adhbLatest->value,
                            'preliminary_flag' => $adhbLatest->preliminary_flag ?? '',
                        ],
                        'all_data' => PDRBPengeluaranADHB::where('expenditure_category', $adhbLatest->expenditure_category)
                            ->orderBy('year', 'asc')
                            ->get(['year', 'value', 'preliminary_flag'])
                            ->toArray()
                    ];
                }

                // ADHK
                $adhkLatest = PDRBPengeluaranADHK::select('expenditure_category', 'year', 'value', 'preliminary_flag')
                    ->where('expenditure_category', 'LIKE', '%PDRB%')
                    ->orWhere('expenditure_category', 'LIKE', '%Produk Domestik Regional Bruto%')
                    ->orderBy('year', 'desc')
                    ->orderBy('expenditure_category', 'asc')
                    ->first();
                
                if ($adhkLatest) {
                    $summary['ADHK'] = [
                        'category' => $adhkLatest->expenditure_category,
                        'data' => [
                            'year' => $adhkLatest->year,
                            'value' => $adhkLatest->value,
                            'preliminary_flag' => $adhkLatest->preliminary_flag ?? '',
                        ],
                        'all_data' => PDRBPengeluaranADHK::where('expenditure_category', $adhkLatest->expenditure_category)
                            ->orderBy('year', 'asc')
                            ->get(['year', 'value', 'preliminary_flag'])
                            ->toArray()
                    ];
                }

                // Distribusi
                $distribusiLatest = PDRBPengeluaranDistribusi::select('expenditure_category', 'year', 'value', 'preliminary_flag')
                    ->where('expenditure_category', 'NOT LIKE', '%PDRB%')
                    ->where('expenditure_category', 'NOT LIKE', '%Produk Domestik Regional Bruto%')
                    ->orderBy('year', 'desc')
                    ->orderBy('value', 'desc')
                    ->first();
                
                if ($distribusiLatest) {
                    $summary['Distribusi'] = [
                        'category' => $distribusiLatest->expenditure_category,
                        'data' => [
                            'year' => $distribusiLatest->year,
                            'value' => $distribusiLatest->value,
                            'preliminary_flag' => $distribusiLatest->preliminary_flag ?? '',
                        ],
                        'all_data' => PDRBPengeluaranDistribusi::where('expenditure_category', $distribusiLatest->expenditure_category)
                            ->orderBy('year', 'asc')
                            ->get(['year', 'value', 'preliminary_flag'])
                            ->toArray()
                    ];
                }

                // Laju Pertumbuhan
                $lajuLatest = PDRBPengeluaranLajuPDRB::select('expenditure_category', 'year', 'value', 'preliminary_flag')
                    ->where('expenditure_category', 'LIKE', '%PDRB%')
                    ->orWhere('expenditure_category', 'LIKE', '%Produk Domestik Regional Bruto%')
                    ->orderBy('year', 'desc')
                    ->orderBy('expenditure_category', 'asc')
                    ->first();
                
                if ($lajuLatest) {
                    $summary['Laju Pertumbuhan'] = [
                        'category' => $lajuLatest->expenditure_category,
                        'data' => [
                            'year' => $lajuLatest->year,
                            'value' => $lajuLatest->value,
                            'preliminary_flag' => $lajuLatest->preliminary_flag ?? '',
                        ],
                        'all_data' => PDRBPengeluaranLajuPDRB::where('expenditure_category', $lajuLatest->expenditure_category)
                            ->orderBy('year', 'asc')
                            ->get(['year', 'value', 'preliminary_flag'])
                            ->toArray()
                    ];
                }

                return $summary;
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Pengeluaran Summary by Sheet API request successful', [
                'total_sheets' => count($data),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('PDRB Pengeluaran Summary by Sheet API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching summary data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get data grouped by category for chart rendering.
     * Returns data organized by expenditure_category.
     */
    public function getDataByCategory(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|string|in:adhb,adhk,distribusi,laju,adhb_triwulanan,adhk_triwulanan,distribusi_triwulanan,laju_qtoq,laju_ytoy,laju_ctoc',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid type parameter',
                    'errors' => $validator->errors()
                ], 400);
            }

            $type = $request->input('type');
            $cacheKey = "pdrb_pengeluaran_by_category:type:{$type}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($type) {
                $model = null;
                
                switch ($type) {
                    case 'adhb':
                        $model = PDRBPengeluaranADHB::class;
                        break;
                    case 'adhk':
                        $model = PDRBPengeluaranADHK::class;
                        break;
                    case 'distribusi':
                        $model = PDRBPengeluaranDistribusi::class;
                        break;
                    case 'laju':
                        $model = PDRBPengeluaranLajuPDRB::class;
                        break;
                    case 'adhb_triwulanan':
                        $model = PDRBPengeluaranADHBTriwulanan::class;
                        break;
                    case 'adhk_triwulanan':
                        $model = PDRBPengeluaranADHKTriwulanan::class;
                        break;
                    case 'distribusi_triwulanan':
                        $model = PDRBPengeluaranDistribusiTriwulanan::class;
                        break;
                    case 'laju_qtoq':
                        $model = PDRBPengeluaranLajuQtoQ::class;
                        break;
                    case 'laju_ytoy':
                        $model = PDRBPengeluaranLajuYtoY::class;
                        break;
                    case 'laju_ctoc':
                        $model = PDRBPengeluaranLajuCtoC::class;
                        break;
                }

                if (!$model) {
                    return [];
                }

                $isQuarterly = in_array($type, ['adhb_triwulanan', 'adhk_triwulanan', 'distribusi_triwulanan', 'laju_qtoq', 'laju_ytoy', 'laju_ctoc']);
                
                $query = $model::select([
                    'expenditure_category',
                    'year',
                    'value',
                    'preliminary_flag',
                ]);

                if ($isQuarterly) {
                    $query->addSelect('quarter');
                }

                $results = $query->orderBy('year', 'asc')
                    ->orderBy('expenditure_category', 'asc')
                    ->get();

                // Group by category
                $grouped = [];
                foreach ($results as $item) {
                    $category = $item->expenditure_category;
                    if (!isset($grouped[$category])) {
                        $grouped[$category] = [];
                    }
                    
                    $dataItem = [
                        'year' => $item->year,
                        'value' => $item->value,
                        'preliminary_flag' => $item->preliminary_flag ?? '',
                    ];
                    
                    if ($isQuarterly) {
                        $dataItem['quarter'] = $item->quarter;
                    }
                    
                    $grouped[$category][] = $dataItem;
                }

                return $grouped;
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Pengeluaran Data by Category API request successful', [
                'type' => $type,
                'total_categories' => count($data),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('PDRB Pengeluaran Data by Category API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching data by category',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get all available years from all PDRB tables.
     */
    public function getAllYears(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $cacheKey = "pdrb_pengeluaran_all_years";

            $years = Cache::remember($cacheKey, self::CACHE_DURATION, function () {
                $allYears = collect();
                
                // Get years from all annual tables
                $allYears = $allYears->merge(
                    PDRBPengeluaranADHB::distinct()->pluck('year')
                )->merge(
                    PDRBPengeluaranADHK::distinct()->pluck('year')
                )->merge(
                    PDRBPengeluaranDistribusi::distinct()->pluck('year')
                )->merge(
                    PDRBPengeluaranLajuPDRB::distinct()->pluck('year')
                )->merge(
                    PDRBPengeluaranADHBTriwulanan::distinct()->pluck('year')
                )->merge(
                    PDRBPengeluaranADHKTriwulanan::distinct()->pluck('year')
                )->merge(
                    PDRBPengeluaranDistribusiTriwulanan::distinct()->pluck('year')
                )->merge(
                    PDRBPengeluaranLajuQtoQ::distinct()->pluck('year')
                )->merge(
                    PDRBPengeluaranLajuYtoY::distinct()->pluck('year')
                )->merge(
                    PDRBPengeluaranLajuCtoC::distinct()->pluck('year')
                );

                return $allYears->unique()->sort()->values()->toArray();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Pengeluaran All Years API request successful', [
                'total_years' => count($years),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $years
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('PDRB Pengeluaran All Years API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching years',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    // ========== PDRB LAPANGAN USAHA API METHODS ==========

    /**
     * Get PDRB Lapangan Usaha ADHB data.
     */
    public function getLapanganUsahaAdhb(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('PDRB Lapangan Usaha ADHB API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $category = $request->input('category', '');
            $year = $request->input('year', '');

            $cacheKey = "pdrb_lapangan_usaha_adhb_api:category:" . md5($category) . ":year:{$year}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category, $year) {
                $query = PDRBLapanganUsahaADHB::select([
                    'id',
                    'industry_category',
                    'year',
                    'preliminary_flag',
                    'value',
                ]);

                if (!empty($category)) {
                    $query->where('industry_category', trim($category));
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                return $query->orderBy('year', 'desc')
                      ->orderBy('industry_category', 'asc')
                      ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Lapangan Usaha ADHB API request successful', [
                'category' => $category ?: 'all',
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
            
            Log::error('PDRB Lapangan Usaha ADHB API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PDRB Lapangan Usaha ADHB data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get PDRB Lapangan Usaha ADHK data.
     */
    public function getLapanganUsahaAdhk(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('PDRB Lapangan Usaha ADHK API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $category = $request->input('category', '');
            $year = $request->input('year', '');

            $cacheKey = "pdrb_lapangan_usaha_adhk_api:category:" . md5($category) . ":year:{$year}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category, $year) {
                $query = PDRBLapanganUsahaADHK::select([
                    'id',
                    'industry_category',
                    'year',
                    'preliminary_flag',
                    'value',
                ]);

                if (!empty($category)) {
                    $query->where('industry_category', trim($category));
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                return $query->orderBy('year', 'desc')
                      ->orderBy('industry_category', 'asc')
                      ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Lapangan Usaha ADHK API request successful', [
                'category' => $category ?: 'all',
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
            
            Log::error('PDRB Lapangan Usaha ADHK API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PDRB Lapangan Usaha ADHK data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get PDRB Lapangan Usaha Distribusi data.
     */
    public function getLapanganUsahaDistribusi(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('PDRB Lapangan Usaha Distribusi API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $category = $request->input('category', '');
            $year = $request->input('year', '');

            $cacheKey = "pdrb_lapangan_usaha_distribusi_api:category:" . md5($category) . ":year:{$year}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category, $year) {
                $query = PDRBLapanganUsahaDistribusi::select([
                    'id',
                    'industry_category',
                    'year',
                    'preliminary_flag',
                    'value',
                ]);

                if (!empty($category)) {
                    $query->where('industry_category', trim($category));
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                return $query->orderBy('year', 'desc')
                      ->orderBy('industry_category', 'asc')
                      ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Lapangan Usaha Distribusi API request successful', [
                'category' => $category ?: 'all',
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
            
            Log::error('PDRB Lapangan Usaha Distribusi API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PDRB Lapangan Usaha Distribusi data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get PDRB Lapangan Usaha Laju PDRB data.
     */
    public function getLapanganUsahaLaju(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('PDRB Lapangan Usaha Laju API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $category = $request->input('category', '');
            $year = $request->input('year', '');

            $cacheKey = "pdrb_lapangan_usaha_laju_api:category:" . md5($category) . ":year:{$year}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category, $year) {
                $query = PDRBLapanganUsahaLajuPDRB::select([
                    'id',
                    'industry_category',
                    'year',
                    'preliminary_flag',
                    'value',
                ]);

                if (!empty($category)) {
                    $query->where('industry_category', trim($category));
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                return $query->orderBy('year', 'desc')
                      ->orderBy('industry_category', 'asc')
                      ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Lapangan Usaha Laju API request successful', [
                'category' => $category ?: 'all',
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
            
            Log::error('PDRB Lapangan Usaha Laju API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PDRB Lapangan Usaha Laju data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get PDRB Lapangan Usaha Laju Implisit data.
     */
    public function getLapanganUsahaLajuImplisit(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('PDRB Lapangan Usaha Laju Implisit API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $category = $request->input('category', '');
            $year = $request->input('year', '');

            $cacheKey = "pdrb_lapangan_usaha_laju_implisit_api:category:" . md5($category) . ":year:{$year}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category, $year) {
                $query = PDRBLapanganUsahaLajuImplisit::select([
                    'id',
                    'industry_category',
                    'year',
                    'preliminary_flag',
                    'value',
                ]);

                if (!empty($category)) {
                    $query->where('industry_category', trim($category));
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                return $query->orderBy('year', 'desc')
                      ->orderBy('industry_category', 'asc')
                      ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Lapangan Usaha Laju Implisit API request successful', [
                'category' => $category ?: 'all',
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
            
            Log::error('PDRB Lapangan Usaha Laju Implisit API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PDRB Lapangan Usaha Laju Implisit data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get PDRB Lapangan Usaha ADHB Triwulanan data.
     */
    public function getLapanganUsahaAdhbTriwulanan(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
                'quarter' => 'sometimes|string|in:I,II,III,IV',
            ]);

            if ($validator->fails()) {
                Log::warning('PDRB Lapangan Usaha ADHB Triwulanan API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $category = $request->input('category', '');
            $year = $request->input('year', '');
            $quarter = $request->input('quarter', '');

            $cacheKey = "pdrb_lapangan_usaha_adhb_triwulanan_api:category:" . md5($category) . ":year:{$year}:quarter:{$quarter}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category, $year, $quarter) {
                $query = PDRBLapanganUsahaADHBTriwulanan::select([
                    'id',
                    'industry_category',
                    'year',
                    'quarter',
                    'preliminary_flag',
                    'value',
                ]);

                if (!empty($category)) {
                    $query->where('industry_category', trim($category));
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                if (!empty($quarter)) {
                    $query->where('quarter', $quarter);
                }

                return $query->orderBy('year', 'desc')
                      ->orderByRaw("FIELD(quarter, 'I', 'II', 'III', 'IV')")
                      ->orderBy('industry_category', 'asc')
                      ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Lapangan Usaha ADHB Triwulanan API request successful', [
                'category' => $category ?: 'all',
                'year' => $year ?: 'all',
                'quarter' => $quarter ?: 'all',
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
            
            Log::error('PDRB Lapangan Usaha ADHB Triwulanan API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PDRB Lapangan Usaha ADHB Triwulanan data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get PDRB Lapangan Usaha ADHK Triwulanan data.
     */
    public function getLapanganUsahaAdhkTriwulanan(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
                'quarter' => 'sometimes|string|in:I,II,III,IV',
            ]);

            if ($validator->fails()) {
                Log::warning('PDRB Lapangan Usaha ADHK Triwulanan API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $category = $request->input('category', '');
            $year = $request->input('year', '');
            $quarter = $request->input('quarter', '');

            $cacheKey = "pdrb_lapangan_usaha_adhk_triwulanan_api:category:" . md5($category) . ":year:{$year}:quarter:{$quarter}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category, $year, $quarter) {
                $query = PDRBLapanganUsahaADHKTriwulanan::select([
                    'id',
                    'industry_category',
                    'year',
                    'quarter',
                    'preliminary_flag',
                    'value',
                ]);

                if (!empty($category)) {
                    $query->where('industry_category', trim($category));
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                if (!empty($quarter)) {
                    $query->where('quarter', $quarter);
                }

                return $query->orderBy('year', 'desc')
                      ->orderByRaw("FIELD(quarter, 'I', 'II', 'III', 'IV')")
                      ->orderBy('industry_category', 'asc')
                      ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Lapangan Usaha ADHK Triwulanan API request successful', [
                'category' => $category ?: 'all',
                'year' => $year ?: 'all',
                'quarter' => $quarter ?: 'all',
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
            
            Log::error('PDRB Lapangan Usaha ADHK Triwulanan API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PDRB Lapangan Usaha ADHK Triwulanan data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get PDRB Lapangan Usaha Distribusi Triwulanan data.
     */
    public function getLapanganUsahaDistribusiTriwulanan(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
                'quarter' => 'sometimes|string|in:I,II,III,IV',
            ]);

            if ($validator->fails()) {
                Log::warning('PDRB Lapangan Usaha Distribusi Triwulanan API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $category = $request->input('category', '');
            $year = $request->input('year', '');
            $quarter = $request->input('quarter', '');

            $cacheKey = "pdrb_lapangan_usaha_distribusi_triwulanan_api:category:" . md5($category) . ":year:{$year}:quarter:{$quarter}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category, $year, $quarter) {
                $query = PDRBLapanganUsahaDistribusiTriwulanan::select([
                    'id',
                    'industry_category',
                    'year',
                    'quarter',
                    'preliminary_flag',
                    'value',
                ]);

                if (!empty($category)) {
                    $query->where('industry_category', trim($category));
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                if (!empty($quarter)) {
                    $query->where('quarter', $quarter);
                }

                return $query->orderBy('year', 'desc')
                      ->orderByRaw("FIELD(quarter, 'I', 'II', 'III', 'IV')")
                      ->orderBy('industry_category', 'asc')
                      ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Lapangan Usaha Distribusi Triwulanan API request successful', [
                'category' => $category ?: 'all',
                'year' => $year ?: 'all',
                'quarter' => $quarter ?: 'all',
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
            
            Log::error('PDRB Lapangan Usaha Distribusi Triwulanan API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PDRB Lapangan Usaha Distribusi Triwulanan data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get PDRB Lapangan Usaha Laju Q-to-Q data.
     */
    public function getLapanganUsahaLajuQtoQ(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
                'quarter' => 'sometimes|string|in:I,II,III,IV',
            ]);

            if ($validator->fails()) {
                Log::warning('PDRB Lapangan Usaha Laju Q-to-Q API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $category = $request->input('category', '');
            $year = $request->input('year', '');
            $quarter = $request->input('quarter', '');

            $cacheKey = "pdrb_lapangan_usaha_laju_qtoq_api:category:" . md5($category) . ":year:{$year}:quarter:{$quarter}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category, $year, $quarter) {
                $query = PDRBLapanganUsahaLajuQtoQ::select([
                    'id',
                    'industry_category',
                    'year',
                    'quarter',
                    'preliminary_flag',
                    'value',
                ]);

                if (!empty($category)) {
                    $query->where('industry_category', trim($category));
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                if (!empty($quarter)) {
                    $query->where('quarter', $quarter);
                }

                return $query->orderBy('year', 'desc')
                      ->orderByRaw("FIELD(quarter, 'I', 'II', 'III', 'IV')")
                      ->orderBy('industry_category', 'asc')
                      ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Lapangan Usaha Laju Q-to-Q API request successful', [
                'category' => $category ?: 'all',
                'year' => $year ?: 'all',
                'quarter' => $quarter ?: 'all',
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
            
            Log::error('PDRB Lapangan Usaha Laju Q-to-Q API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PDRB Lapangan Usaha Laju Q-to-Q data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get PDRB Lapangan Usaha Laju Y-to-Y data.
     */
    public function getLapanganUsahaLajuYtoY(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
                'quarter' => 'sometimes|string|in:I,II,III,IV',
            ]);

            if ($validator->fails()) {
                Log::warning('PDRB Lapangan Usaha Laju Y-to-Y API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $category = $request->input('category', '');
            $year = $request->input('year', '');
            $quarter = $request->input('quarter', '');

            $cacheKey = "pdrb_lapangan_usaha_laju_ytoy_api:category:" . md5($category) . ":year:{$year}:quarter:{$quarter}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category, $year, $quarter) {
                $query = PDRBLapanganUsahaLajuYtoY::select([
                    'id',
                    'industry_category',
                    'year',
                    'quarter',
                    'preliminary_flag',
                    'value',
                ]);

                if (!empty($category)) {
                    $query->where('industry_category', trim($category));
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                if (!empty($quarter)) {
                    $query->where('quarter', $quarter);
                }

                return $query->orderBy('year', 'desc')
                      ->orderByRaw("FIELD(quarter, 'I', 'II', 'III', 'IV')")
                      ->orderBy('industry_category', 'asc')
                      ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Lapangan Usaha Laju Y-to-Y API request successful', [
                'category' => $category ?: 'all',
                'year' => $year ?: 'all',
                'quarter' => $quarter ?: 'all',
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
            
            Log::error('PDRB Lapangan Usaha Laju Y-to-Y API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PDRB Lapangan Usaha Laju Y-to-Y data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get PDRB Lapangan Usaha Laju C-to-C data.
     */
    public function getLapanganUsahaLajuCtoC(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
                'quarter' => 'sometimes|string|in:I,II,III,IV',
            ]);

            if ($validator->fails()) {
                Log::warning('PDRB Lapangan Usaha Laju C-to-C API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $category = $request->input('category', '');
            $year = $request->input('year', '');
            $quarter = $request->input('quarter', '');

            $cacheKey = "pdrb_lapangan_usaha_laju_ctoc_api:category:" . md5($category) . ":year:{$year}:quarter:{$quarter}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($category, $year, $quarter) {
                $query = PDRBLapanganUsahaLajuCtoC::select([
                    'id',
                    'industry_category',
                    'year',
                    'quarter',
                    'preliminary_flag',
                    'value',
                ]);

                if (!empty($category)) {
                    $query->where('industry_category', trim($category));
                }

                if (!empty($year) && is_numeric($year)) {
                    $query->where('year', (int)$year);
                }

                if (!empty($quarter)) {
                    $query->where('quarter', $quarter);
                }

                return $query->orderBy('year', 'desc')
                      ->orderByRaw("FIELD(quarter, 'I', 'II', 'III', 'IV')")
                      ->orderBy('industry_category', 'asc')
                      ->get();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Lapangan Usaha Laju C-to-C API request successful', [
                'category' => $category ?: 'all',
                'year' => $year ?: 'all',
                'quarter' => $quarter ?: 'all',
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
            
            Log::error('PDRB Lapangan Usaha Laju C-to-C API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching PDRB Lapangan Usaha Laju C-to-C data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get summary data grouped by sheet name for Lapangan Usaha (for carousel).
     */
    public function getLapanganUsahaSummaryBySheet(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $cacheKey = "pdrb_lapangan_usaha_summary_by_sheet";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () {
                $summary = [];

                // ADHB
                $adhbLatest = PDRBLapanganUsahaADHB::select('industry_category', 'year', 'value', 'preliminary_flag')
                    ->where('industry_category', 'LIKE', '%PDRB%')
                    ->orWhere('industry_category', 'LIKE', '%Produk Domestik Regional Bruto%')
                    ->orderBy('year', 'desc')
                    ->orderBy('industry_category', 'asc')
                    ->first();
                
                if ($adhbLatest) {
                    $summary['ADHB'] = [
                        'category' => $adhbLatest->industry_category,
                        'data' => [
                            'year' => $adhbLatest->year,
                            'value' => $adhbLatest->value,
                            'preliminary_flag' => $adhbLatest->preliminary_flag ?? '',
                        ],
                        'all_data' => PDRBLapanganUsahaADHB::where('industry_category', $adhbLatest->industry_category)
                            ->orderBy('year', 'asc')
                            ->get(['year', 'value', 'preliminary_flag'])
                            ->toArray()
                    ];
                }

                // ADHK
                $adhkLatest = PDRBLapanganUsahaADHK::select('industry_category', 'year', 'value', 'preliminary_flag')
                    ->where('industry_category', 'LIKE', '%PDRB%')
                    ->orWhere('industry_category', 'LIKE', '%Produk Domestik Regional Bruto%')
                    ->orderBy('year', 'desc')
                    ->orderBy('industry_category', 'asc')
                    ->first();
                
                if ($adhkLatest) {
                    $summary['ADHK'] = [
                        'category' => $adhkLatest->industry_category,
                        'data' => [
                            'year' => $adhkLatest->year,
                            'value' => $adhkLatest->value,
                            'preliminary_flag' => $adhkLatest->preliminary_flag ?? '',
                        ],
                        'all_data' => PDRBLapanganUsahaADHK::where('industry_category', $adhkLatest->industry_category)
                            ->orderBy('year', 'asc')
                            ->get(['year', 'value', 'preliminary_flag'])
                            ->toArray()
                    ];
                }

                // Distribusi
                $distribusiLatest = PDRBLapanganUsahaDistribusi::select('industry_category', 'year', 'value', 'preliminary_flag')
                    ->where('industry_category', 'NOT LIKE', '%PDRB%')
                    ->where('industry_category', 'NOT LIKE', '%Produk Domestik Regional Bruto%')
                    ->orderBy('year', 'desc')
                    ->orderBy('value', 'desc')
                    ->first();
                
                if ($distribusiLatest) {
                    $summary['Distribusi'] = [
                        'category' => $distribusiLatest->industry_category,
                        'data' => [
                            'year' => $distribusiLatest->year,
                            'value' => $distribusiLatest->value,
                            'preliminary_flag' => $distribusiLatest->preliminary_flag ?? '',
                        ],
                        'all_data' => PDRBLapanganUsahaDistribusi::where('industry_category', $distribusiLatest->industry_category)
                            ->orderBy('year', 'asc')
                            ->get(['year', 'value', 'preliminary_flag'])
                            ->toArray()
                    ];
                }

                // Laju Pertumbuhan
                $lajuLatest = PDRBLapanganUsahaLajuPDRB::select('industry_category', 'year', 'value', 'preliminary_flag')
                    ->where('industry_category', 'LIKE', '%PDRB%')
                    ->orWhere('industry_category', 'LIKE', '%Produk Domestik Regional Bruto%')
                    ->orderBy('year', 'desc')
                    ->orderBy('industry_category', 'asc')
                    ->first();
                
                if ($lajuLatest) {
                    $summary['Laju Pertumbuhan'] = [
                        'category' => $lajuLatest->industry_category,
                        'data' => [
                            'year' => $lajuLatest->year,
                            'value' => $lajuLatest->value,
                            'preliminary_flag' => $lajuLatest->preliminary_flag ?? '',
                        ],
                        'all_data' => PDRBLapanganUsahaLajuPDRB::where('industry_category', $lajuLatest->industry_category)
                            ->orderBy('year', 'asc')
                            ->get(['year', 'value', 'preliminary_flag'])
                            ->toArray()
                    ];
                }

                // Laju Implisit
                $lajuImplisitLatest = PDRBLapanganUsahaLajuImplisit::select('industry_category', 'year', 'value', 'preliminary_flag')
                    ->where('industry_category', 'LIKE', '%PDRB%')
                    ->orWhere('industry_category', 'LIKE', '%Produk Domestik Regional Bruto%')
                    ->orderBy('year', 'desc')
                    ->orderBy('industry_category', 'asc')
                    ->first();
                
                if ($lajuImplisitLatest) {
                    $summary['Laju Implisit'] = [
                        'category' => $lajuImplisitLatest->industry_category,
                        'data' => [
                            'year' => $lajuImplisitLatest->year,
                            'value' => $lajuImplisitLatest->value,
                            'preliminary_flag' => $lajuImplisitLatest->preliminary_flag ?? '',
                        ],
                        'all_data' => PDRBLapanganUsahaLajuImplisit::where('industry_category', $lajuImplisitLatest->industry_category)
                            ->orderBy('year', 'asc')
                            ->get(['year', 'value', 'preliminary_flag'])
                            ->toArray()
                    ];
                }

                return $summary;
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Lapangan Usaha Summary by Sheet API request successful', [
                'total_sheets' => count($data),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('PDRB Lapangan Usaha Summary by Sheet API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching summary data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get data grouped by category for Lapangan Usaha chart rendering.
     */
    public function getLapanganUsahaDataByCategory(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|string|in:adhb,adhk,distribusi,laju,laju_implisit,adhb_triwulanan,adhk_triwulanan,distribusi_triwulanan,laju_qtoq,laju_ytoy,laju_ctoc',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid type parameter',
                    'errors' => $validator->errors()
                ], 400);
            }

            $type = $request->input('type');
            $cacheKey = "pdrb_lapangan_usaha_by_category:type:{$type}";

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($type) {
                $model = null;
                
                switch ($type) {
                    case 'adhb':
                        $model = PDRBLapanganUsahaADHB::class;
                        break;
                    case 'adhk':
                        $model = PDRBLapanganUsahaADHK::class;
                        break;
                    case 'distribusi':
                        $model = PDRBLapanganUsahaDistribusi::class;
                        break;
                    case 'laju':
                        $model = PDRBLapanganUsahaLajuPDRB::class;
                        break;
                    case 'laju_implisit':
                        $model = PDRBLapanganUsahaLajuImplisit::class;
                        break;
                    case 'adhb_triwulanan':
                        $model = PDRBLapanganUsahaADHBTriwulanan::class;
                        break;
                    case 'adhk_triwulanan':
                        $model = PDRBLapanganUsahaADHKTriwulanan::class;
                        break;
                    case 'distribusi_triwulanan':
                        $model = PDRBLapanganUsahaDistribusiTriwulanan::class;
                        break;
                    case 'laju_qtoq':
                        $model = PDRBLapanganUsahaLajuQtoQ::class;
                        break;
                    case 'laju_ytoy':
                        $model = PDRBLapanganUsahaLajuYtoY::class;
                        break;
                    case 'laju_ctoc':
                        $model = PDRBLapanganUsahaLajuCtoC::class;
                        break;
                }

                if (!$model) {
                    return [];
                }

                $isQuarterly = in_array($type, ['adhb_triwulanan', 'adhk_triwulanan', 'distribusi_triwulanan', 'laju_qtoq', 'laju_ytoy', 'laju_ctoc']);
                
                $query = $model::select([
                    'industry_category',
                    'year',
                    'value',
                    'preliminary_flag',
                ]);

                if ($isQuarterly) {
                    $query->addSelect('quarter');
                }

                $results = $query->orderBy('year', 'asc')
                    ->orderBy('industry_category', 'asc')
                    ->get();

                // Group by category
                $grouped = [];
                foreach ($results as $item) {
                    $category = $item->industry_category;
                    if (!isset($grouped[$category])) {
                        $grouped[$category] = [];
                    }
                    
                    $dataItem = [
                        'year' => $item->year,
                        'value' => $item->value,
                        'preliminary_flag' => $item->preliminary_flag ?? '',
                    ];
                    
                    if ($isQuarterly) {
                        $dataItem['quarter'] = $item->quarter;
                    }
                    
                    $grouped[$category][] = $dataItem;
                }

                return $grouped;
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Lapangan Usaha Data by Category API request successful', [
                'type' => $type,
                'total_categories' => count($data),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('PDRB Lapangan Usaha Data by Category API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching data by category',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get all available years from all PDRB Lapangan Usaha tables.
     */
    public function getLapanganUsahaAllYears(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $cacheKey = "pdrb_lapangan_usaha_all_years";

            $years = Cache::remember($cacheKey, self::CACHE_DURATION, function () {
                $allYears = collect();
                
                // Get years from all annual tables
                $allYears = $allYears->merge(
                    PDRBLapanganUsahaADHB::distinct()->pluck('year')
                )->merge(
                    PDRBLapanganUsahaADHK::distinct()->pluck('year')
                )->merge(
                    PDRBLapanganUsahaDistribusi::distinct()->pluck('year')
                )->merge(
                    PDRBLapanganUsahaLajuPDRB::distinct()->pluck('year')
                )->merge(
                    PDRBLapanganUsahaLajuImplisit::distinct()->pluck('year')
                )->merge(
                    PDRBLapanganUsahaADHBTriwulanan::distinct()->pluck('year')
                )->merge(
                    PDRBLapanganUsahaADHKTriwulanan::distinct()->pluck('year')
                )->merge(
                    PDRBLapanganUsahaDistribusiTriwulanan::distinct()->pluck('year')
                )->merge(
                    PDRBLapanganUsahaLajuQtoQ::distinct()->pluck('year')
                )->merge(
                    PDRBLapanganUsahaLajuYtoY::distinct()->pluck('year')
                )->merge(
                    PDRBLapanganUsahaLajuCtoC::distinct()->pluck('year')
                );

                return $allYears->unique()->sort()->values()->toArray();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('PDRB Lapangan Usaha All Years API request successful', [
                'total_years' => count($years),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $years
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('PDRB Lapangan Usaha All Years API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching years',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}

