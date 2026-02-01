<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Infographic;
use App\Models\Publication;
use App\Models\HotelOccupancyCombined;
use App\Models\HotelOccupancyYearly;
use App\Models\GiniRatio;
use App\Models\HumanDevelopmentIndex;
use App\Models\Inflasi;
use App\Models\InflasiPerKomoditas;
use App\Models\Bookmark;
use App\Models\KetenagakerjaanTPT;
use App\Models\KetenagakerjaanTPAK;
use App\Models\IPM_UHH_SP;
use App\Models\IPM_HLS;
use App\Models\IPM_RLS;
use App\Models\PDRBPengeluaranADHB;
use App\Models\PDRBPengeluaranADHK;
use App\Models\PDRBPengeluaranDistribusi;
use App\Http\Resources\NewsResource;
use App\Http\Resources\InfographicResource;
use App\Http\Resources\PublicationResource;
use App\Http\Resources\HotelOccupancyResource;
use App\Http\Resources\GiniRatioResource;
use Illuminate\Support\Facades\DB;

class ApiDataController extends Controller
{
    /**
     * Get hotel occupancy data.
     * All frontend requests for hotel occupancy should use this endpoint.
     */
    public function getHotelOccupancy(Request $request)
    {
        $type = $request->query('type', 'combined'); // 'combined' or 'yearly'
        
        if ($type === 'yearly') {
            $data = HotelOccupancyYearly::orderBy('year', 'desc')->get();
        } else {
            $data = HotelOccupancyCombined::orderBy('year', 'desc')
                ->orderByRaw("FIELD(month, 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOPEMBER', 'DESEMBER')")
                ->get();
        }

        return HotelOccupancyResource::collection($data);
    }

    /**
     * Get Gini Ratio data.
     */
    public function getGiniRatio(Request $request)
    {
        $location = $request->query('location');
        $locationType = $request->query('location_type');
        $year = $request->query('year');

        $query = GiniRatio::query();

        if ($location) {
            $query->where('location_name', $location);
        }

        if ($locationType) {
            $query->where('location_type', $locationType);
        }

        if ($year) {
            $query->where('year', $year);
        }

        $data = $query->orderBy('year', 'desc')
            ->orderBy('location_name')
            ->get();

        return GiniRatioResource::collection($data);
    }

    /**
     * Get Inflasi data.
     */
    public function getInflasi(Request $request)
    {
        $year = $request->query('year');

        $query = Inflasi::query();

        if ($year) {
            $query->where('year', $year);
        }

        $data = $query->orderBy('year', 'desc')
            ->orderByRaw("FIELD(month, 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOPEMBER', 'DESEMBER')")
            ->get();

        return response()->json($data);
    }

    /**
     * Get Inflasi Per Komoditas data.
     */
    public function getInflasiPerKomoditas(Request $request)
    {
        $commodityCode = $request->query('commodity_code');
        $flag = $request->query('flag');
        $year = $request->query('year');

        $query = InflasiPerKomoditas::query();

        if ($commodityCode) {
            $query->where('commodity_code', $commodityCode);
        }

        if ($flag) {
            $query->where('flag', $flag);
        }

        if ($year) {
            $query->where('year', $year);
        }

        $data = $query->orderBy('year', 'desc')
            ->orderByRaw("FIELD(month, 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOPEMBER', 'DESEMBER')")
            ->get();

        return response()->json($data);
    }

    /**
     * Get komoditas grouped by flag.
     */
    public function getKomoditasByFlag(Request $request)
    {
        $flag = $request->query('flag', '1');
        $year = $request->query('year');

        $query = InflasiPerKomoditas::where('flag', $flag);

        if ($year) {
            $query->where('year', $year);
        }

        $data = $query->select('commodity_code', 'commodity_name')
            ->distinct()
            ->orderBy('commodity_name')
            ->get();

        return response()->json($data);
    }

    /**
     * Get all news.
     */
    public function getNews(Request $request)
    {
        $data = News::orderBy('release_date', 'desc')->paginate(20);
        return NewsResource::collection($data);
    }

    /**
     * Get all infographics.
     */
    public function getInfographics(Request $request)
    {
        $data = Infographic::orderBy('created_at', 'desc')->paginate(20);
        return InfographicResource::collection($data);
    }


    /**
     * View bookmarks for authenticated user.
     */
    public function viewBookmarks(Request $request)
    {
        $bookmarks = Bookmark::where('user_id', $request->user()->id)
            ->with('bookmarkable')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($bookmarks);
    }

    /**
     * Add bookmark.
     */
    public function addBookmark(Request $request)
    {
        $request->validate([
            'bookmarkable_type' => 'required|string',
            'bookmarkable_id' => 'required|integer',
        ]);

        // Check if bookmark already exists
        $exists = Bookmark::where('user_id', $request->user()->id)
            ->where('bookmarkable_type', $request->bookmarkable_type)
            ->where('bookmarkable_id', $request->bookmarkable_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'error' => 'Item ini sudah ada di bookmark Anda.'
            ], 409);
        }

        $bookmark = Bookmark::create([
            'user_id' => $request->user()->id,
            'bookmarkable_type' => $request->bookmarkable_type,
            'bookmarkable_id' => $request->bookmarkable_id,
        ]);

        return response()->json($bookmark, 201);
    }

    /**
     * Delete bookmark.
     */
    public function deleteBookmark($id)
    {
        $bookmark = Bookmark::findOrFail($id);
        
        if ($bookmark->user_id !== request()->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $bookmark->delete();
        return response()->json(null, 204);
    }

    // Sync methods will be implemented later when Services are converted
    public function syncBpsNews() {
        return response()->json(['message' => 'Sync method not implemented yet'], 501);
    }

    public function syncBpsInfographic() {
        return response()->json(['message' => 'Sync method not implemented yet'], 501);
    }

    public function syncBpsPublication() {
        return response()->json(['message' => 'Sync method not implemented yet'], 501);
    }

    public function syncHumanDevelopmentIndex() {
        return response()->json(['message' => 'Sync method not implemented yet'], 501);
    }

    public function syncHotelOccupancyCombined() {
        return response()->json(['message' => 'Sync method not implemented yet'], 501);
    }

    public function syncHotelOccupancyYearly() {
        return response()->json(['message' => 'Sync method not implemented yet'], 501);
    }

    public function syncGiniRatio() {
        return response()->json(['message' => 'Sync method not implemented yet'], 501);
    }

    // IPM sync methods
    public function syncIpmUhhSp() {
        return response()->json(['message' => 'Sync method not implemented yet'], 501);
    }

    public function syncIpmHls() {
        return response()->json(['message' => 'Sync method not implemented yet'], 501);
    }

    public function syncIpmRls() {
        return response()->json(['message' => 'Sync method not implemented yet'], 501);
    }

    public function syncIpmPengeluaranPerKapita() {
        return response()->json(['message' => 'Sync method not implemented yet'], 501);
    }

    public function syncIpmIndeksKesehatan() {
        return response()->json(['message' => 'Sync method not implemented yet'], 501);
    }

    public function syncIpmIndeksHidupLayak() {
        return response()->json(['message' => 'Sync method not implemented yet'], 501);
    }

    public function syncIpmIndeksPendidikan() {
        return response()->json(['message' => 'Sync method not implemented yet'], 501);
    }

    public function syncKependudukan() {
        return response()->json(['message' => 'Sync method not implemented yet'], 501);
    }

    public function syncPdrbPengeluaran() {
        return response()->json(['message' => 'Sync method not implemented yet'], 501);
    }

    public function syncPdrbLapanganUsaha() {
        return response()->json(['message' => 'Sync method not implemented yet'], 501);
    }

    public function syncKemiskinanSurabaya() {
        return response()->json(['message' => 'Sync method not implemented yet'], 501);
    }

    public function syncKemiskinanJawaTimur() {
        return response()->json(['message' => 'Sync method not implemented yet'], 501);
    }

    /**
     * Get Ketenagakerjaan TPT data.
     */
    public function getKetenagakerjaanTPT(Request $request)
    {
        $year = $request->query('year');

        $query = KetenagakerjaanTPT::query();

        if ($year) {
            $query->where('year', $year);
        }

        $data = $query->orderBy('year', 'desc')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Get Ketenagakerjaan TPAK data.
     */
    public function getKetenagakerjaanTPAK(Request $request)
    {
        $year = $request->query('year');

        $query = KetenagakerjaanTPAK::query();

        if ($year) {
            $query->where('year', $year);
        }

        $data = $query->orderBy('year', 'desc')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Get IPM UHH SP data.
     */
    public function getIpmUhhSp(Request $request)
    {
        $location = $request->query('location');
        $year = $request->query('year');

        $query = IPM_UHH_SP::query();

        if ($location) {
            $query->where('location_name', $location);
        }

        if ($year) {
            $query->where('year', $year);
        }

        $data = $query->orderBy('year', 'desc')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Get IPM HLS data.
     */
    public function getIpmHls(Request $request)
    {
        $location = $request->query('location');
        $year = $request->query('year');

        $query = IPM_HLS::query();

        if ($location) {
            $query->where('location_name', $location);
        }

        if ($year) {
            $query->where('year', $year);
        }

        $data = $query->orderBy('year', 'desc')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Get IPM RLS data.
     */
    public function getIpmRls(Request $request)
    {
        $location = $request->query('location');
        $year = $request->query('year');

        $query = IPM_RLS::query();

        if ($location) {
            $query->where('location_name', $location);
        }

        if ($year) {
            $query->where('year', $year);
        }

        $data = $query->orderBy('year', 'desc')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Get PDRB Pengeluaran ADHB data.
     */
    public function getPdrbPengeluaranAdhb(Request $request)
    {
        $category = $request->query('category');
        $year = $request->query('year');

        $query = PDRBPengeluaranADHB::query();

        if ($category) {
            $query->where('expenditure_category', $category);
        }

        if ($year) {
            $query->where('year', $year);
        }

        $data = $query->orderBy('year', 'desc')
            ->orderBy('expenditure_category')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Get PDRB Pengeluaran ADHK data.
     */
    public function getPdrbPengeluaranAdhk(Request $request)
    {
        $category = $request->query('category');
        $year = $request->query('year');

        $query = PDRBPengeluaranADHK::query();

        if ($category) {
            $query->where('expenditure_category', $category);
        }

        if ($year) {
            $query->where('year', $year);
        }

        $data = $query->orderBy('year', 'desc')
            ->orderBy('expenditure_category')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Get PDRB Pengeluaran Distribusi data.
     */
    public function getPdrbPengeluaranDistribusi(Request $request)
    {
        $category = $request->query('category');
        $year = $request->query('year');

        $query = PDRBPengeluaranDistribusi::query();

        if ($category) {
            $query->where('expenditure_category', $category);
        }

        if ($year) {
            $query->where('year', $year);
        }

        $data = $query->orderBy('year', 'desc')
            ->orderBy('expenditure_category')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function syncInflasi() {
        return response()->json(['message' => 'Sync method not implemented yet'], 501);
    }
}
