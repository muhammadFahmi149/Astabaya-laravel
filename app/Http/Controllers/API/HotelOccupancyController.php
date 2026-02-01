<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Models\HotelOccupancyCombined;
use App\Models\HotelOccupancyYearly;

class HotelOccupancyController extends Controller
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
     * Month order mapping for proper sorting
     */
    private const MONTH_ORDER = [
        'JANUARI' => 1, 'FEBRUARI' => 2, 'MARET' => 3, 'APRIL' => 4,
        'MEI' => 5, 'JUNI' => 6, 'JULI' => 7, 'AGUSTUS' => 8,
        'SEPTEMBER' => 9, 'OKTOBER' => 10, 'NOPEMBER' => 11, 'DESEMBER' => 12,
        'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
        'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
        'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12,
    ];

    /**
     * Get Hotel Occupancy summary data (latest, previous, changes, and all data).
     * This endpoint provides all data needed for the main hotel occupancy page.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSummary(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Build cache key for summary
            $cacheKey = "hotel_occupancy_summary_api";

            // Try to get from cache
            $summary = Cache::remember($cacheKey, self::CACHE_DURATION_SUMMARY, function () {
                // Get all combined data (monthly) - optimized query with only needed columns
                $combinedData = HotelOccupancyCombined::select([
                    'id',
                    'year',
                    'month',
                    'mktj',
                    'tpk',
                    'rlmtgab',
                    'gpr'
                ])
                ->orderBy('year', 'asc')
                ->orderByRaw("FIELD(month, 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOPEMBER', 'DESEMBER')")
                ->get();
                
                // Get all yearly data - optimized query
                $yearlyData = HotelOccupancyYearly::select([
                    'id',
                    'year',
                    'tpk'
                ])
                ->whereNotNull('tpk')
                ->orderBy('year', 'asc')
                ->get();
                
                // Get latest monthly data (optimized query)
                $latestMonthly = HotelOccupancyCombined::select([
                    'id',
                    'year',
                    'month',
                    'mktj',
                    'tpk',
                    'rlmtgab',
                    'gpr'
                ])
                ->orderBy('year', 'desc')
                ->orderByRaw("FIELD(month, 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOPEMBER', 'DESEMBER') DESC")
                ->first();
                
                // Get previous monthly data (second latest) - optimized query
                $previousMonthly = HotelOccupancyCombined::select([
                    'id',
                    'year',
                    'month',
                    'mktj',
                    'tpk',
                    'rlmtgab',
                    'gpr'
                ])
                ->orderBy('year', 'desc')
                ->orderByRaw("FIELD(month, 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOPEMBER', 'DESEMBER') DESC")
                ->skip(1)
                ->first();
                
                // Calculate changes
                $changes = [
                    'tpk' => null,
                    'mktj' => null,
                    'rlmtgab' => null,
                    'gpr' => null,
                ];
                
                if ($latestMonthly && $previousMonthly) {
                    if ($latestMonthly->tpk !== null && $previousMonthly->tpk !== null) {
                        $changes['tpk'] = round($latestMonthly->tpk - $previousMonthly->tpk, 2);
                    }
                    if ($latestMonthly->mktj !== null && $previousMonthly->mktj !== null) {
                        $changes['mktj'] = round($latestMonthly->mktj - $previousMonthly->mktj, 2);
                    }
                    if ($latestMonthly->rlmtgab !== null && $previousMonthly->rlmtgab !== null) {
                        $changes['rlmtgab'] = round($latestMonthly->rlmtgab - $previousMonthly->rlmtgab, 2);
                    }
                    if ($latestMonthly->gpr !== null && $previousMonthly->gpr !== null) {
                        $changes['gpr'] = round($latestMonthly->gpr - $previousMonthly->gpr, 2);
                    }
                }
                
                // Get distinct years for dropdown
                $distinctYears = HotelOccupancyCombined::select('year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year')
                    ->toArray();
                
                // Get latest year
                $latestYear = !empty($distinctYears) ? $distinctYears[0] : null;
                
                return [
                    'occupancy_data' => $combinedData,
                    'yearly_occupancy_data' => $yearlyData,
                    'latest_month_data' => $latestMonthly,
                    'previous_month_data' => $previousMonthly,
                    'changes' => $changes,
                    'distinct_years' => $distinctYears,
                    'latest_year' => $latestYear,
                ];
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            // Log successful request
            Log::info('Hotel Occupancy Summary API request successful', [
                'total_combined_records' => count($summary['occupancy_data']),
                'total_yearly_records' => count($summary['yearly_occupancy_data']),
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
            Log::error('Hotel Occupancy Summary API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching Hotel Occupancy summary data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}

