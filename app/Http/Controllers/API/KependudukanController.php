<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Kependudukan;

class KependudukanController extends Controller
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
     * Get all available years for Kependudukan data.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getYears(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $cacheKey = "kependudukan_years_api";

            $years = Cache::remember($cacheKey, self::CACHE_DURATION_SUMMARY, function () {
                return Kependudukan::select('year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year')
                    ->toArray();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('Kependudukan Years API request successful', [
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
            
            Log::error('Kependudukan Years API error', [
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

    /**
     * Get Kependudukan summary data (total, male, female, ratio, changes).
     * This endpoint provides all data needed for the summary cards.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSummary(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                Log::warning('Kependudukan Summary API validation failed', [
                    'errors' => $validator->errors(),
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $selectedYear = $request->input('year');
            
            // Get latest year if not provided
            if (!$selectedYear) {
                $latestYear = Kependudukan::select('year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->value('year');
                $selectedYear = $latestYear ?: date('Y');
            }

            // Build cache key
            $cacheKey = "kependudukan_summary_api:year:{$selectedYear}";

            // Try to get from cache
            $summary = Cache::remember($cacheKey, self::CACHE_DURATION_SUMMARY, function () use ($selectedYear) {
                // Get total population for selected year
                $totalPopulation = Kependudukan::where('year', $selectedYear)
                    ->where('gender', 'TOTAL')
                    ->sum('population');

                // Get total male population
                $totalMale = Kependudukan::where('year', $selectedYear)
                    ->where('gender', 'LK')
                    ->sum('population');

                // Get total female population
                $totalFemale = Kependudukan::where('year', $selectedYear)
                    ->where('gender', 'PR')
                    ->sum('population');

                // Get previous year
                $previousYear = Kependudukan::select('year')
                    ->distinct()
                    ->where('year', '<', $selectedYear)
                    ->orderBy('year', 'desc')
                    ->value('year');

                // Get previous year data
                $prevTotalPopulation = null;
                $prevTotalMale = null;
                $prevTotalFemale = null;
                
                if ($previousYear) {
                    $prevTotalPopulation = Kependudukan::where('year', $previousYear)
                        ->where('gender', 'TOTAL')
                        ->sum('population');
                    
                    $prevTotalMale = Kependudukan::where('year', $previousYear)
                        ->where('gender', 'LK')
                        ->sum('population');
                    
                    $prevTotalFemale = Kependudukan::where('year', $previousYear)
                        ->where('gender', 'PR')
                        ->sum('population');
                }

                // Calculate changes
                $totalChange = ($prevTotalPopulation !== null && $totalPopulation !== null) 
                    ? $totalPopulation - $prevTotalPopulation 
                    : null;
                
                $maleChange = ($prevTotalMale !== null && $totalMale !== null) 
                    ? $totalMale - $prevTotalMale 
                    : null;
                
                $femaleChange = ($prevTotalFemale !== null && $totalFemale !== null) 
                    ? $totalFemale - $prevTotalFemale 
                    : null;

                // Calculate population ratio (male per 100 female)
                $populationRatio = null;
                $populationRatioDisplay = null;
                if ($totalFemale > 0 && $totalMale > 0) {
                    $populationRatio = ($totalMale / $totalFemale) * 100;
                    $populationRatioDisplay = number_format($populationRatio, 2) . ':100';
                }

                // Previous year ratio
                $prevPopulationRatio = null;
                $prevPopulationRatioDisplay = null;
                if ($prevTotalFemale > 0 && $prevTotalMale > 0) {
                    $prevPopulationRatio = ($prevTotalMale / $prevTotalFemale) * 100;
                    $prevPopulationRatioDisplay = number_format($prevPopulationRatio, 2) . ':100';
                }

                return [
                    'selected_year' => $selectedYear,
                    'previous_year' => $previousYear,
                    'total_population' => $totalPopulation,
                    'total_male' => $totalMale,
                    'total_female' => $totalFemale,
                    'total_change' => $totalChange,
                    'male_change' => $maleChange,
                    'female_change' => $femaleChange,
                    'population_ratio' => $populationRatio,
                    'population_ratio_display' => $populationRatioDisplay,
                    'prev_population_ratio_display' => $prevPopulationRatioDisplay,
                ];
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('Kependudukan Summary API request successful', [
                'year' => $selectedYear,
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('Kependudukan Summary API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => $request->all(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching Kependudukan summary data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get trend data (5 years latest).
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTrend(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $cacheKey = "kependudukan_trend_api";

            $trendData = Cache::remember($cacheKey, self::CACHE_DURATION, function () {
                // Get last 5 years
                $years = Kependudukan::select('year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->limit(5)
                    ->pluck('year')
                    ->sort()
                    ->values()
                    ->toArray();

                $result = [];
                foreach ($years as $year) {
                    $total = Kependudukan::where('year', $year)
                        ->where('gender', 'TOTAL')
                        ->sum('population');
                    
                    $male = Kependudukan::where('year', $year)
                        ->where('gender', 'LK')
                        ->sum('population');
                    
                    $female = Kependudukan::where('year', $year)
                        ->where('gender', 'PR')
                        ->sum('population');

                    $result[] = [
                        'year' => $year,
                        'total' => $total ?: 0,
                        'male' => $male ?: 0,
                        'female' => $female ?: 0,
                    ];
                }

                return $result;
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('Kependudukan Trend API request successful', [
                'total_years' => count($trendData),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $trendData
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('Kependudukan Trend API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching trend data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get age distribution data for selected year.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDistribution(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $selectedYear = $request->input('year');
            
            if (!$selectedYear) {
                $latestYear = Kependudukan::select('year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->value('year');
                $selectedYear = $latestYear ?: date('Y');
            }

            $cacheKey = "kependudukan_distribution_api:year:{$selectedYear}";

            $distribution = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($selectedYear) {
                return Kependudukan::select('age_group', DB::raw('SUM(population) as population'))
                    ->where('year', $selectedYear)
                    ->where('gender', 'TOTAL')
                    ->groupBy('age_group')
                    ->orderBy('age_group')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'age_group' => $item->age_group,
                            'population' => (int) $item->population
                        ];
                    })
                    ->toArray();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('Kependudukan Distribution API request successful', [
                'year' => $selectedYear,
                'total_groups' => count($distribution),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $distribution
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('Kependudukan Distribution API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching distribution data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get pie chart data (proportion by age group).
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPieChart(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $selectedYear = $request->input('year');
            
            if (!$selectedYear) {
                $latestYear = Kependudukan::select('year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->value('year');
                $selectedYear = $latestYear ?: date('Y');
            }

            $cacheKey = "kependudukan_piechart_api:year:{$selectedYear}";

            $pieData = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($selectedYear) {
                $distribution = Kependudukan::select('age_group', DB::raw('SUM(population) as population'))
                    ->where('year', $selectedYear)
                    ->where('gender', 'TOTAL')
                    ->groupBy('age_group')
                    ->orderBy('age_group')
                    ->get();

                return $distribution->map(function ($item) {
                    return [
                        'name' => $item->age_group,
                        'value' => (int) $item->population
                    ];
                })->toArray();
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('Kependudukan Pie Chart API request successful', [
                'year' => $selectedYear,
                'total_groups' => count($pieData),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $pieData
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('Kependudukan Pie Chart API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching pie chart data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get pyramid data (male and female by age group).
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPyramid(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            $validator = Validator::make($request->all(), [
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters',
                    'errors' => $validator->errors()
                ], 400);
            }

            $selectedYear = $request->input('year');
            
            if (!$selectedYear) {
                $latestYear = Kependudukan::select('year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->value('year');
                $selectedYear = $latestYear ?: date('Y');
            }

            $cacheKey = "kependudukan_pyramid_api:year:{$selectedYear}";

            $pyramidData = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($selectedYear) {
                // Get all age groups
                $ageGroups = Kependudukan::select('age_group')
                    ->where('year', $selectedYear)
                    ->distinct()
                    ->orderBy('age_group')
                    ->pluck('age_group')
                    ->toArray();

                $result = [];
                foreach ($ageGroups as $ageGroup) {
                    $male = Kependudukan::where('year', $selectedYear)
                        ->where('age_group', $ageGroup)
                        ->where('gender', 'LK')
                        ->sum('population');
                    
                    $female = Kependudukan::where('year', $selectedYear)
                        ->where('age_group', $ageGroup)
                        ->where('gender', 'PR')
                        ->sum('population');

                    $result[] = [
                        'age_group' => $ageGroup,
                        'male' => (int) ($male ?: 0),
                        'female' => (int) ($female ?: 0),
                    ];
                }

                return $result;
            });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::info('Kependudukan Pyramid API request successful', [
                'year' => $selectedYear,
                'total_groups' => count($pyramidData),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $pyramidData
            ]);

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::error('Kependudukan Pyramid API error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time_ms' => $executionTime,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching pyramid data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}

