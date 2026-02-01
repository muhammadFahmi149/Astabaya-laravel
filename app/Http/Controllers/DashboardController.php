<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Publication;
use App\Models\News;

class DashboardController extends Controller
{
    /**
     * Display dashboard page.
     * Note: All data is fetched via API endpoints, not directly from database.
     */
    public function dashboard()
    {
        // Data will be fetched from API via JavaScript/Axios on frontend
        return view('dashboard.dashboard');
    }

    /**
     * Display infographics page.
     */
    public function infographics()
    {
        return view('dashboard.infographics');
    }

    /**
     * Display publications page.
     */
    public function publications(Request $request)
    {
        $query = Publication::query();
        
        // Search filter
        $searchQuery = $request->get('search');
        if ($searchQuery) {
            $query->where(function($q) use ($searchQuery) {
                $q->where('title', 'like', '%' . $searchQuery . '%')
                  ->orWhere('abstract', 'like', '%' . $searchQuery . '%');
            });
        }
        
        // Year filter
        $selectedYear = $request->get('year');
        if ($selectedYear) {
            $query->whereYear('date', $selectedYear);
        }
        
        // Get total count before pagination
        $countPublication = Publication::count();
        $filteredCount = $query->count();
        
        // Get available years
        $availableYears = Publication::selectRaw('YEAR(date) as year')
            ->whereNotNull('date')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        
        // Paginate results
        $dataPublication = $query->orderBy('date', 'desc')
            ->paginate(10)
            ->withQueryString();
        
        return view('dashboard.publications', [
            'dataPublication' => $dataPublication,
            'available_years' => $availableYears,
            'selected_year' => $selectedYear,
            'search_query' => $searchQuery,
            'countPublication' => $countPublication,
            'filtered_count' => $filteredCount,
        ]);
    }

    /**
     * Display news page with server-side pagination.
     */
    public function news(Request $request)
    {
        $query = News::query();
        
        // Search filter
        $searchQuery = $request->get('search');
        if ($searchQuery) {
            $query->where(function($q) use ($searchQuery) {
                $q->where('title', 'like', '%' . $searchQuery . '%')
                  ->orWhere('content', 'like', '%' . $searchQuery . '%');
            });
        }
        
        // Category filter
        $categoryId = $request->get('category_id');
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        // Sort filter
        $sort = $request->get('sort', 'latest');
        if ($sort === 'oldest') {
            $query->orderBy('release_date', 'asc');
        } else {
            $query->orderBy('release_date', 'desc');
        }
        
        // Get total count before pagination
        $totalNews = News::count();
        $filteredCount = $query->count();
        
        // Get available categories
        $availableCategories = News::select('category_id', 'category_name')
            ->whereNotNull('category_id')
            ->whereNotNull('category_name')
            ->distinct()
            ->orderBy('category_name')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->category_id,
                    'name' => $item->category_name,
                ];
            });
        
        // Paginate results (20 per page as per NewsController)
        $dataNews = $query->paginate(20)
            ->withQueryString();
        
        return view('dashboard.news', [
            'dataNews' => $dataNews,
            'available_categories' => $availableCategories,
            'selected_category' => $categoryId,
            'search_query' => $searchQuery,
            'sort' => $sort,
            'totalNews' => $totalNews,
            'filtered_count' => $filteredCount,
        ]);
    }

    /**
     * Display Indeks Pembangunan Manusia page.
     */
    public function indeksPembangunanManusia()
    {
        return view('dashboard.indikator.indeks_pembangunan_manusia');
    }

    /**
     * Display Hotel Occupancy page.
     */
    public function hotelOccupancy()
    {
        return view('dashboard.indikator.hotel_occupancy');
    }

    /**
     * Display Gini Ratio page.
     */
    public function giniRatio()
    {
        return view('dashboard.indikator.gini_ratio');
    }

    /**
     * Display Kemiskinan page.
     */
    public function kemiskinan()
    {
        return view('dashboard.indikator.kemiskinan');
    }

    /**
     * Display Kependudukan page.
     */
    public function kependudukan()
    {
        return view('dashboard.indikator.kependudukan');
    }

    /**
     * Display Ketenagakerjaan page.
     * Note: All data is fetched via API endpoints, not directly from database.
     */
    public function ketenagakerjaan()
    {
        // Data will be fetched from API via JavaScript/Axios on frontend
        return view('dashboard.indikator.ketenagakerjaan');
    }

    // IPM Individual Indicator Pages
    public function ipmUhhSp()
    {
        return view('dashboard.indikator.ipm_uhh_sp');
    }

    public function ipmHls()
    {
        return view('dashboard.indikator.ipm_hls');
    }

    public function ipmRls()
    {
        return view('dashboard.indikator.ipm_rls');
    }

    public function ipmPengeluaranPerKapita()
    {
        return view('dashboard.indikator.ipm_pengeluaran_per_kapita');
    }

    public function ipmIndeksKesehatan()
    {
        return view('dashboard.indikator.ipm_indeks_kesehatan');
    }

    public function ipmIndeksHidupLayak()
    {
        return view('dashboard.indikator.ipm_indeks_hidup_layak');
    }

    public function ipmIndeksPendidikan()
    {
        return view('dashboard.indikator.ipm_indeks_pendidikan');
    }

    /**
     * Display PDRB Pengeluaran page.
     */
    public function pdrbPengeluaran()
    {
        return view('dashboard.indikator.pdrb_pengeluaran');
    }

    /**
     * Display PDRB Lapangan Usaha page.
     */
    public function pdrbLapanganUsaha()
    {
        return view('dashboard.indikator.pdrb_lapangan_usaha');
    }

    /**
     * Display Inflasi page.
     */
    public function inflasi()
    {
        return view('dashboard.indikator.inflasi');
    }

    /**
     * Download infographic.
     */
    public function downloadInfographic($id)
    {
        // Implementation will fetch from API
        // For now, just redirect to download URL
        return redirect()->back();
    }

    /**
     * Download publication.
     * Note: All data is fetched via API endpoints, not directly from database.
     */
    public function downloadPublication($pubId)
    {
        // Redirect to API endpoint which will handle the download
        return redirect()->route('api.publications.download', $pubId);
    }
}
