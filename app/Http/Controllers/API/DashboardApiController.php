<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DashboardApiController extends Controller
{
    /**
     * Get aggregated summary for all dashboard indicators.
     * This replaces 16 individual API calls with a single backend-aggregated response.
     */
    public function getSummary(Request $request): JsonResponse
    {
        $startTime = microtime(true);
        $location = $request->query('location', 'Kota Surabaya');
        $requestWithLocation = Request::create('/dummy', 'GET', ['location' => $location]);
        
        $results = [
            'inflasi' => $this->getResponseData(app(InflasiController::class)->getSummary($request)),
            'kemiskinan' => $this->getResponseData(app(KemiskinanController::class)->getSummary($request)),
            'kependudukan' => $this->getResponseData(app(KependudukanController::class)->getSummary($request)),
            'ketenagakerjaan' => $this->getResponseData(app(KetenagakerjaanController::class)->getSummary($request)),
            'pdrbPengeluaran' => $this->getResponseData(app(PDRBController::class)->getSummaryBySheet($request)),
            'pdrbLapanganUsaha' => $this->getResponseData(app(PDRBController::class)->getLapanganUsahaSummaryBySheet($request)),
            'hotelOccupancy' => $this->getResponseData(app(HotelOccupancyController::class)->getSummary($request)),
            'giniRatio' => $this->getResponseData(app(GiniRatioController::class)->getSummary($request)),
            'ipm' => $this->getResponseData(app(IPMController::class)->getSurabaya($request)),
            'uhhSp' => $this->getResponseData(app(IPMController::class)->getUhhSp($requestWithLocation)),
            'hls' => $this->getResponseData(app(IPMController::class)->getHls($requestWithLocation)),
            'rls' => $this->getResponseData(app(IPMController::class)->getRls($requestWithLocation)),
            'pengeluaran' => $this->getResponseData(app(IPMController::class)->getPengeluaranPerKapita($requestWithLocation)),
            'indeksKesehatan' => $this->getResponseData(app(IPMController::class)->getIndeksKesehatan($requestWithLocation)),
            'indeksPendidikan' => $this->getResponseData(app(IPMController::class)->getIndeksPendidikan($requestWithLocation)),
            'indeksHidupLayak' => $this->getResponseData(app(IPMController::class)->getIndeksHidupLayak($requestWithLocation)),
        ];

        return response()->json([
            'success' => true,
            'data' => $results,
            'processing_time' => microtime(true) - $startTime
        ]);
    }

    /**
     * Extract data array from JsonResponse safely.
     */
    private function getResponseData($response)
    {
        if ($response instanceof JsonResponse) {
            return $response->getData(true);
        }
        return ['success' => false, 'data' => []];
    }
}
