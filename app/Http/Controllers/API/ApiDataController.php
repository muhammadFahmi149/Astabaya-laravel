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
     * Map frontend content type names to Laravel model classes
     */
    private function mapContentTypeToModel($contentTypeName)
    {
        $mapping = [
            'news' => 'App\Models\News',
            'infographic' => 'App\Models\Infographic',
            'publication' => 'App\Models\Publication',
        ];

        return $mapping[$contentTypeName] ?? null;
    }

    /**
     * Map Laravel model class to frontend content type name
     */
    private function mapModelToContentType($modelClass)
    {
        $mapping = [
            'App\Models\News' => 'news',
            'App\Models\Infographic' => 'infographic',
            'App\Models\Publication' => 'publication',
        ];

        return $mapping[$modelClass] ?? null;
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

        // Transform bookmarks to match frontend expectations
        $transformedBookmarks = $bookmarks->map(function ($bookmark) {
            // Get image URL based on content type
            $imageUrl = null;
            if ($bookmark->bookmarkable) {
                if ($bookmark->bookmarkable instanceof News) {
                    $imageUrl = $bookmark->bookmarkable->picture_url;
                } elseif ($bookmark->bookmarkable instanceof Infographic) {
                    $imageUrl = $bookmark->bookmarkable->image;
                } elseif ($bookmark->bookmarkable instanceof Publication) {
                    $imageUrl = $bookmark->bookmarkable->image;
                }
            }

            return [
                'id' => $bookmark->id,
                'user_id' => $bookmark->user_id,
                'bookmarkable_type' => $bookmark->bookmarkable_type,
                'bookmarkable_id' => $bookmark->bookmarkable_id,
                'content_type_model' => $this->mapModelToContentType($bookmark->bookmarkable_type),
                'object_id' => $bookmark->bookmarkable_id,
                'content_object' => $bookmark->bookmarkable,
                'image_url' => $imageUrl,
                'created_at' => $bookmark->created_at,
            ];
        });

        return response()->json($transformedBookmarks);
    }

    /**
     * Add bookmark.
     */
    public function addBookmark(Request $request)
    {
        // Log request data for debugging
        \Log::info('Add Bookmark Request', [
            'all_input' => $request->all(),
            'content_type_name' => $request->input('content_type_name'),
            'object_id' => $request->input('object_id'),
            'user_id' => $request->user()->id ?? 'not authenticated'
        ]);

        // Accept both frontend field names (content_type_name, object_id) 
        // and backend field names (bookmarkable_type, bookmarkable_id)
        $contentType = $request->input('content_type_name') ?? $request->input('bookmarkable_type');
        $objectId = $request->input('object_id') ?? $request->input('bookmarkable_id');

        // Validate
        if (!$contentType || !$objectId) {
            \Log::error('Add Bookmark Validation Failed', [
                'content_type' => $contentType,
                'object_id' => $objectId
            ]);
            return response()->json([
                'error' => 'content_type_name dan object_id wajib diisi.'
            ], 422);
        }

        // Map content type to model class
        $modelClass = $this->mapContentTypeToModel($contentType);
        
        if (!$modelClass) {
            // If already a full class name, use it
            if (class_exists($contentType)) {
                $modelClass = $contentType;
            } else {
                return response()->json([
                    'error' => 'Tipe konten tidak valid.'
                ], 422);
            }
        }

        // Check if the object exists
        $model = new $modelClass;
        $primaryKey = $model->getKeyName();
        
        if (!$modelClass::where($primaryKey, $objectId)->exists()) {
            return response()->json([
                'error' => 'Item tidak ditemukan.'
            ], 404);
        }

        // Check if bookmark already exists
        $exists = Bookmark::where('user_id', $request->user()->id)
            ->where('bookmarkable_type', $modelClass)
            ->where('bookmarkable_id', $objectId)
            ->exists();

        if ($exists) {
            return response()->json([
                'error' => 'Item ini sudah ada di bookmark Anda.'
            ], 409);
        }

        $bookmark = Bookmark::create([
            'user_id' => $request->user()->id,
            'bookmarkable_type' => $modelClass,
            'bookmarkable_id' => $objectId,
        ]);

        // Load the related object
        $bookmark->load('bookmarkable');

        // Return in the format expected by frontend
        $response = [
            'id' => $bookmark->id,
            'user_id' => $bookmark->user_id,
            'bookmarkable_type' => $bookmark->bookmarkable_type,
            'bookmarkable_id' => $bookmark->bookmarkable_id,
            'content_type_model' => $this->mapModelToContentType($bookmark->bookmarkable_type),
            'object_id' => $bookmark->bookmarkable_id,
            'content_object' => $bookmark->bookmarkable,
            'created_at' => $bookmark->created_at,
        ];

        \Log::info('Add Bookmark Success', ['response' => $response]);

        return response()->json($response, 201);
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
