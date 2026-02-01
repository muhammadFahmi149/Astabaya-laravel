<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\BPSNewsService;
use App\Services\BPSPublicationService;
use App\Services\BPSInfographicService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Controller untuk API endpoints sync data dari BPS API dan Google Sheets
 */
class ApiSyncController extends Controller
{
    protected $newsService;
    protected $publicationService;
    protected $infographicService;

    public function __construct(
        BPSNewsService $newsService,
        BPSPublicationService $publicationService,
        BPSInfographicService $infographicService
    ) {
        $this->newsService = $newsService;
        $this->publicationService = $publicationService;
        $this->infographicService = $infographicService;
    }

    /**
     * Sync news from BPS API
     */
    public function syncNews(): JsonResponse
    {
        try {
            $result = $this->newsService->syncNews();
            return response()->json([
                'status' => 'success',
                'message' => "Sinkronisasi berita selesai. Data baru: {$result['created']}, data diperbarui: {$result['updated']}.",
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync publications from BPS API
     */
    public function syncPublication(): JsonResponse
    {
        try {
            $result = $this->publicationService->syncPublication();
            return response()->json([
                'status' => 'success',
                'message' => "Sinkronisasi publikasi selesai. Data baru: {$result['created']}, data diperbarui: {$result['updated']}.",
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync infographics from BPS API
     */
    public function syncInfographic(): JsonResponse
    {
        try {
            $result = $this->infographicService->syncInfographic();
            return response()->json([
                'status' => 'success',
                'message' => "Sinkronisasi infografis selesai. Data baru: {$result['created']}, data diperbarui: {$result['updated']}.",
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

