<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiDataController;
use App\Http\Controllers\API\ApiSyncController;
use App\Http\Controllers\API\PublicationController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\InfographicController;
use App\Http\Controllers\API\IPMController;
use App\Http\Controllers\API\KetenagakerjaanController;
use App\Http\Controllers\API\KemiskinanController;
use App\Http\Controllers\API\GiniRatioController;
use App\Http\Controllers\API\PDRBController;
use App\Http\Controllers\API\HotelOccupancyController;
use App\Http\Controllers\API\InflasiController;
use App\Http\Controllers\API\KependudukanController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API endpoints
Route::get('/hotel-occupancy', [ApiDataController::class, 'getHotelOccupancy'])->name('api.hotel-occupancy');
Route::get('/hotel-occupancy-summary', [HotelOccupancyController::class, 'getSummary'])->name('api.hotel-occupancy-summary');
Route::get('/gini-ratio', [ApiDataController::class, 'getGiniRatio'])->name('api.gini-ratio');

// Inflasi API endpoints (optimized with separate controller)
Route::get('/inflasi', [InflasiController::class, 'getInflasi'])->name('api.inflasi');
Route::get('/inflasi-perkomoditas', [InflasiController::class, 'getInflasiPerKomoditas'])->name('api.inflasi-perkomoditas');
Route::get('/komoditas-by-flag', [InflasiController::class, 'getKomoditasByFlag'])->name('api.komoditas-by-flag');
Route::get('/inflasi-summary', [InflasiController::class, 'getSummary'])->name('api.inflasi-summary');
Route::get('/inflasi-years', [InflasiController::class, 'getYears'])->name('api.inflasi-years');
Route::get('/inflasi-komoditas-years', [InflasiController::class, 'getKomoditasYears'])->name('api.inflasi-komoditas-years');

// Ketenagakerjaan API endpoints (optimized with separate controller)
Route::get('/ketenagakerjaan-tpt', [KetenagakerjaanController::class, 'getTPT'])->name('api.ketenagakerjaan-tpt');
Route::get('/ketenagakerjaan-tpak', [KetenagakerjaanController::class, 'getTPAK'])->name('api.ketenagakerjaan-tpak');
Route::get('/ketenagakerjaan-summary', [KetenagakerjaanController::class, 'getSummary'])->name('api.ketenagakerjaan-summary');

// Kemiskinan API endpoints (optimized with separate controller)
Route::get('/kemiskinan-surabaya', [KemiskinanController::class, 'getSurabaya'])->name('api.kemiskinan-surabaya');
Route::get('/kemiskinan-jawa-timur', [KemiskinanController::class, 'getJawaTimur'])->name('api.kemiskinan-jawa-timur');
Route::get('/kemiskinan-summary', [KemiskinanController::class, 'getSummary'])->name('api.kemiskinan-summary');

// Gini Ratio API endpoints (optimized with separate controller)
Route::get('/gini-ratio-surabaya', [GiniRatioController::class, 'getSurabaya'])->name('api.gini-ratio-surabaya');
Route::get('/gini-ratio-jawa-timur', [GiniRatioController::class, 'getJawaTimur'])->name('api.gini-ratio-jawa-timur');
Route::get('/gini-ratio-summary', [GiniRatioController::class, 'getSummary'])->name('api.gini-ratio-summary');

// Kependudukan API endpoints (optimized with separate controller)
Route::get('/kependudukan-summary', [KependudukanController::class, 'getSummary'])->name('api.kependudukan-summary');
Route::get('/kependudukan-trend', [KependudukanController::class, 'getTrend'])->name('api.kependudukan-trend');
Route::get('/kependudukan-distribution', [KependudukanController::class, 'getDistribution'])->name('api.kependudukan-distribution');
Route::get('/kependudukan-piechart', [KependudukanController::class, 'getPieChart'])->name('api.kependudukan-piechart');
Route::get('/kependudukan-pyramid', [KependudukanController::class, 'getPyramid'])->name('api.kependudukan-pyramid');
Route::get('/kependudukan-years', [KependudukanController::class, 'getYears'])->name('api.kependudukan-years');

// IPM API endpoints (optimized with separate controller)
Route::get('/ipm-main', [IPMController::class, 'getMain'])->name('api.ipm-main');
Route::get('/ipm-uhh-sp', [IPMController::class, 'getUhhSp'])->name('api.ipm-uhh-sp');
Route::get('/ipm-uhh-sp-summary', [IPMController::class, 'getUhhSpSummary'])->name('api.ipm-uhh-sp-summary');
Route::get('/ipm-hls', [IPMController::class, 'getHls'])->name('api.ipm-hls');
Route::get('/ipm-hls-summary', [IPMController::class, 'getHlsSummary'])->name('api.ipm-hls-summary');
Route::get('/ipm-rls', [IPMController::class, 'getRls'])->name('api.ipm-rls');
Route::get('/ipm-rls-summary', [IPMController::class, 'getRlsSummary'])->name('api.ipm-rls-summary');
Route::get('/ipm-surabaya', [IPMController::class, 'getSurabaya'])->name('api.ipm-surabaya');
Route::get('/ipm-jatim', [IPMController::class, 'getJatim'])->name('api.ipm-jatim');
Route::get('/ipm-pengeluaran-per-kapita', [IPMController::class, 'getPengeluaranPerKapita'])->name('api.ipm-pengeluaran-per-kapita');
Route::get('/ipm-pengeluaran-per-kapita-summary', [IPMController::class, 'getPengeluaranPerKapitaSummary'])->name('api.ipm-pengeluaran-per-kapita-summary');
Route::get('/ipm-indeks-kesehatan', [IPMController::class, 'getIndeksKesehatan'])->name('api.ipm-indeks-kesehatan');
Route::get('/ipm-indeks-kesehatan-summary', [IPMController::class, 'getIndeksKesehatanSummary'])->name('api.ipm-indeks-kesehatan-summary');
Route::get('/ipm-indeks-pendidikan', [IPMController::class, 'getIndeksPendidikan'])->name('api.ipm-indeks-pendidikan');
Route::get('/ipm-indeks-pendidikan-summary', [IPMController::class, 'getIndeksPendidikanSummary'])->name('api.ipm-indeks-pendidikan-summary');
Route::get('/ipm-indeks-hidup-layak', [IPMController::class, 'getIndeksHidupLayak'])->name('api.ipm-indeks-hidup-layak');
Route::get('/ipm-indeks-hidup-layak-summary', [IPMController::class, 'getIndeksHidupLayakSummary'])->name('api.ipm-indeks-hidup-layak-summary');

// PDRB Pengeluaran API endpoints (optimized with separate controller)
Route::get('/pdrb-pengeluaran-adhb', [PDRBController::class, 'getPengeluaranAdhb'])->name('api.pdrb-pengeluaran-adhb');
Route::get('/pdrb-pengeluaran-adhk', [PDRBController::class, 'getPengeluaranAdhk'])->name('api.pdrb-pengeluaran-adhk');
Route::get('/pdrb-pengeluaran-distribusi', [PDRBController::class, 'getPengeluaranDistribusi'])->name('api.pdrb-pengeluaran-distribusi');
Route::get('/pdrb-pengeluaran-laju', [PDRBController::class, 'getPengeluaranLaju'])->name('api.pdrb-pengeluaran-laju');
Route::get('/pdrb-pengeluaran-adhb-triwulanan', [PDRBController::class, 'getPengeluaranAdhbTriwulanan'])->name('api.pdrb-pengeluaran-adhb-triwulanan');
Route::get('/pdrb-pengeluaran-adhk-triwulanan', [PDRBController::class, 'getPengeluaranAdhkTriwulanan'])->name('api.pdrb-pengeluaran-adhk-triwulanan');
Route::get('/pdrb-pengeluaran-distribusi-triwulanan', [PDRBController::class, 'getPengeluaranDistribusiTriwulanan'])->name('api.pdrb-pengeluaran-distribusi-triwulanan');
Route::get('/pdrb-pengeluaran-laju-qtoq', [PDRBController::class, 'getPengeluaranLajuQtoQ'])->name('api.pdrb-pengeluaran-laju-qtoq');
Route::get('/pdrb-pengeluaran-laju-ytoy', [PDRBController::class, 'getPengeluaranLajuYtoY'])->name('api.pdrb-pengeluaran-laju-ytoy');
Route::get('/pdrb-pengeluaran-laju-ctoc', [PDRBController::class, 'getPengeluaranLajuCtoC'])->name('api.pdrb-pengeluaran-laju-ctoc');
Route::get('/pdrb-pengeluaran-summary', [PDRBController::class, 'getSummaryBySheet'])->name('api.pdrb-pengeluaran-summary');
Route::get('/pdrb-pengeluaran-by-category', [PDRBController::class, 'getDataByCategory'])->name('api.pdrb-pengeluaran-by-category');
Route::get('/pdrb-pengeluaran-years', [PDRBController::class, 'getAllYears'])->name('api.pdrb-pengeluaran-years');

// PDRB Lapangan Usaha API endpoints (optimized with separate controller)
Route::get('/pdrb-lapangan-usaha-adhb', [PDRBController::class, 'getLapanganUsahaAdhb'])->name('api.pdrb-lapangan-usaha-adhb');
Route::get('/pdrb-lapangan-usaha-adhk', [PDRBController::class, 'getLapanganUsahaAdhk'])->name('api.pdrb-lapangan-usaha-adhk');
Route::get('/pdrb-lapangan-usaha-distribusi', [PDRBController::class, 'getLapanganUsahaDistribusi'])->name('api.pdrb-lapangan-usaha-distribusi');
Route::get('/pdrb-lapangan-usaha-laju', [PDRBController::class, 'getLapanganUsahaLaju'])->name('api.pdrb-lapangan-usaha-laju');
Route::get('/pdrb-lapangan-usaha-laju-implisit', [PDRBController::class, 'getLapanganUsahaLajuImplisit'])->name('api.pdrb-lapangan-usaha-laju-implisit');
Route::get('/pdrb-lapangan-usaha-adhb-triwulanan', [PDRBController::class, 'getLapanganUsahaAdhbTriwulanan'])->name('api.pdrb-lapangan-usaha-adhb-triwulanan');
Route::get('/pdrb-lapangan-usaha-adhk-triwulanan', [PDRBController::class, 'getLapanganUsahaAdhkTriwulanan'])->name('api.pdrb-lapangan-usaha-adhk-triwulanan');
Route::get('/pdrb-lapangan-usaha-distribusi-triwulanan', [PDRBController::class, 'getLapanganUsahaDistribusiTriwulanan'])->name('api.pdrb-lapangan-usaha-distribusi-triwulanan');
Route::get('/pdrb-lapangan-usaha-laju-qtoq', [PDRBController::class, 'getLapanganUsahaLajuQtoQ'])->name('api.pdrb-lapangan-usaha-laju-qtoq');
Route::get('/pdrb-lapangan-usaha-laju-ytoy', [PDRBController::class, 'getLapanganUsahaLajuYtoY'])->name('api.pdrb-lapangan-usaha-laju-ytoy');
Route::get('/pdrb-lapangan-usaha-laju-ctoc', [PDRBController::class, 'getLapanganUsahaLajuCtoC'])->name('api.pdrb-lapangan-usaha-laju-ctoc');
Route::get('/pdrb-lapangan-usaha-summary', [PDRBController::class, 'getLapanganUsahaSummaryBySheet'])->name('api.pdrb-lapangan-usaha-summary');
Route::get('/pdrb-lapangan-usaha-by-category', [PDRBController::class, 'getLapanganUsahaDataByCategory'])->name('api.pdrb-lapangan-usaha-by-category');
Route::get('/pdrb-lapangan-usaha-years', [PDRBController::class, 'getLapanganUsahaAllYears'])->name('api.pdrb-lapangan-usaha-years');

// News, Infographics, Publications (public endpoints - optimized with separate controllers)
Route::get('/news', [NewsController::class, 'index'])->name('api.news');
Route::get('/news/latest', [NewsController::class, 'getLatest'])->name('api.news.latest');
Route::get('/news/categories', [NewsController::class, 'categories'])->name('api.news.categories');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('api.news.show');
Route::get('/infographics', [InfographicController::class, 'index'])->name('api.infographics');
Route::get('/infographics/latest', [InfographicController::class, 'getLatest'])->name('api.infographics.latest');
Route::get('/infographics/{id}', [InfographicController::class, 'show'])->name('api.infographics.show');
Route::get('/publications', [PublicationController::class, 'index'])->name('api.publications');
Route::get('/publications/latest', [PublicationController::class, 'getLatest'])->name('api.publications.latest');
Route::get('/publications/{pubId}', [PublicationController::class, 'show'])->name('api.publications.show');
Route::get('/publications/{pubId}/download', [PublicationController::class, 'getDownloadUrl'])->name('api.publications.download');

// Authentication API
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'apiLogin'])->name('api.login');

// Protected API endpoints
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'apiLogout'])->name('api.logout');
    
    // Generic data CRUD
    Route::apiResource('data', ApiDataController::class);
    
    // Bookmarks
    Route::get('/bookmarks', [ApiDataController::class, 'viewBookmarks'])->name('api.bookmarks');
    Route::post('/bookmarks/add', [ApiDataController::class, 'addBookmark'])->name('api.bookmarks.add');
    Route::delete('/bookmarks/{id}', [ApiDataController::class, 'deleteBookmark'])->name('api.bookmarks.delete');
    
    // BPS Data Synchronization
    Route::post('/sync/news', [ApiSyncController::class, 'syncNews'])->name('api.sync.news');
    Route::post('/sync/infographics', [ApiSyncController::class, 'syncInfographic'])->name('api.sync.infographics');
    Route::post('/sync/publications', [ApiSyncController::class, 'syncPublication'])->name('api.sync.publications');
    
    // Spreadsheet Data Synchronization (will be implemented later)
    Route::post('/sync/human-development-index', [ApiDataController::class, 'syncHumanDevelopmentIndex'])->name('api.sync.human-development-index');
    Route::post('/sync/hotel-occupancy-combined', [ApiDataController::class, 'syncHotelOccupancyCombined'])->name('api.sync.hotel-occupancy-combined');
    Route::post('/sync/gini-ratio', [ApiDataController::class, 'syncGiniRatio'])->name('api.sync.gini-ratio');
    
    // IPM synchronization
    Route::post('/sync/ipm-uhh-sp', [ApiDataController::class, 'syncIpmUhhSp'])->name('api.sync.ipm-uhh-sp');
    Route::post('/sync/ipm-hls', [ApiDataController::class, 'syncIpmHls'])->name('api.sync.ipm-hls');
    Route::post('/sync/ipm-rls', [ApiDataController::class, 'syncIpmRls'])->name('api.sync.ipm-rls');
    Route::post('/sync/ipm-pengeluaran-per-kapita', [ApiDataController::class, 'syncIpmPengeluaranPerKapita'])->name('api.sync.ipm-pengeluaran-per-kapita');
    Route::post('/sync/ipm-indeks-kesehatan', [ApiDataController::class, 'syncIpmIndeksKesehatan'])->name('api.sync.ipm-indeks-kesehatan');
    Route::post('/sync/ipm-indeks-hidup-layak', [ApiDataController::class, 'syncIpmIndeksHidupLayak'])->name('api.sync.ipm-indeks-hidup-layak');
    Route::post('/sync/ipm-indeks-pendidikan', [ApiDataController::class, 'syncIpmIndeksPendidikan'])->name('api.sync.ipm-indeks-pendidikan');
    
    // Other synchronization endpoints (will be implemented later)
    Route::post('/sync/kependudukan', [ApiDataController::class, 'syncKependudukan'])->name('api.sync.kependudukan');
    Route::post('/sync/pdrb-pengeluaran', [ApiDataController::class, 'syncPdrbPengeluaran'])->name('api.sync.pdrb-pengeluaran');
    Route::post('/sync/pdrb-lapangan-usaha', [ApiDataController::class, 'syncPdrbLapanganUsaha'])->name('api.sync.pdrb-lapangan-usaha');
    Route::post('/sync/kemiskinan-surabaya', [ApiDataController::class, 'syncKemiskinanSurabaya'])->name('api.sync.kemiskinan-surabaya');
    Route::post('/sync/kemiskinan-jawa-timur', [ApiDataController::class, 'syncKemiskinanJawaTimur'])->name('api.sync.kemiskinan-jawa-timur');
    Route::post('/sync/inflasi', [ApiDataController::class, 'syncInflasi'])->name('api.sync.inflasi');
});

