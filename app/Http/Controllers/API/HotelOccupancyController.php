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
                // Get all combined data (monthly) - ordered properly for latest tracking
                $combinedDataQuery = HotelOccupancyCombined::select([
                    'id',
                    'year',
                    'month',
                    'mktj',
                    'tpk',
                    'rlmtgab',
                    'gpr'
                ])
                ->orderBy('year', 'desc')
                ->orderByRaw("FIELD(UPPER(month), 'DESEMBER', 'DES', 'NOPEMBER', 'NOVEMBER', 'NOV', 'OKTOBER', 'OKT', 'SEPTEMBER', 'SEPT', 'SEP', 'AGUSTUS', 'AGST', 'AGS', 'JULI', 'JUL', 'JUNI', 'JUN', 'MEI', 'APRIL', 'APR', 'MARET', 'MAR', 'FEBRUARI', 'FEB', 'JANUARI', 'JAN')")
                ->get();
                
                // Get all yearly data - optimized query
                $yearlyData = HotelOccupancyYearly::select([
                    'id',
                    'year',
                    'tpk'
                ])
                ->orderBy('year', 'asc')
                ->orderBy('id', 'desc') // in case of duplicates, take latest ID for that year
                ->get()
                ->unique('year')
                ->values();
                
                // Get latest and previous from the combined collection
                $latestMonthly = $combinedDataQuery->first();
                $previousMonthly = $combinedDataQuery->skip(1)->first();
                
                // We must sort combinedData back to ascending order for the chart (if needed by UI)
                // Actually, the UI parses and sorts it using its own `monthOrder`.
                $combinedData = $combinedDataQuery;
                
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
                
                // Get distinct years for dropdown from the collection
                $distinctYears = $combinedDataQuery->pluck('year')->unique()->values()->toArray();
                
                // Get latest year
                $latestYear = !empty($distinctYears) ? max($distinctYears) : null;
                
                // Add detailed logging
                Log::info('Hotel Occupancy Data Details', [
                    'latest_monthly_id' => $latestMonthly ? $latestMonthly->id : null,
                    'latest_monthly_year' => $latestMonthly ? $latestMonthly->year : null,
                    'latest_monthly_month' => $latestMonthly ? $latestMonthly->month : null,
                    'previous_monthly_id' => $previousMonthly ? $previousMonthly->id : null,
                    'previous_monthly_year' => $previousMonthly ? $previousMonthly->year : null,
                    'previous_monthly_month' => $previousMonthly ? $previousMonthly->month : null,
                    'yearly_data_count' => $yearlyData->count(),
                    'yearly_data_years' => $yearlyData->pluck('year')->toArray(),
                    'yearly_data_ids' => $yearlyData->pluck('id')->toArray(),
                    'combined_data_count' => $combinedData->count(),
                ]);
                
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
            
            // Log successful request with detailed data
            Log::info('Hotel Occupancy Summary API request successful', [
                'total_combined_records' => count($summary['occupancy_data']),
                'total_yearly_records' => count($summary['yearly_occupancy_data']),
                'latest_month_data' => $summary['latest_month_data'] ? [
                    'id' => $summary['latest_month_data']->id,
                    'year' => $summary['latest_month_data']->year,
                    'month' => $summary['latest_month_data']->month,
                ] : null,
                'yearly_data_sample' => collect($summary['yearly_occupancy_data'])->take(5)->map(function($item) {
                    return [
                        'id' => $item->id ?? $item['id'] ?? null,
                        'year' => $item->year ?? $item['year'] ?? null,
                        'tpk' => $item->tpk ?? $item['tpk'] ?? null,
                    ];
                })->toArray(),
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

