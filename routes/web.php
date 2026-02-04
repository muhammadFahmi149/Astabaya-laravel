<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\API\ApiDataController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', [AppController::class, 'index'])->name('index');
Route::post('/contact-us', [AppController::class, 'contactUs'])->name('contact_us');

// Authentication routes (public)
Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login/form', [AuthController::class, 'login'])->name('login-form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Google OAuth routes
Route::get('/accounts/google/login', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/accounts/google/login/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

// Public pages (accessible without login)
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
Route::get('/news', [DashboardController::class, 'news'])->name('news');
Route::get('/indeks-pembangunan-manusia', [DashboardController::class, 'indeksPembangunanManusia'])->name('indeks-pembangunan-manusia');
Route::get('/hotel-occupancy', [DashboardController::class, 'hotelOccupancy'])->name('hotel-occupancy');
Route::get('/gini-ratio', [DashboardController::class, 'giniRatio'])->name('gini-ratio');
Route::get('/kemiskinan', [DashboardController::class, 'kemiskinan'])->name('kemiskinan');
Route::get('/kependudukan', [DashboardController::class, 'kependudukan'])->name('kependudukan');
Route::get('/ketenagakerjaan', [DashboardController::class, 'ketenagakerjaan'])->name('ketenagakerjaan');
Route::get('/ipm-uhh-sp', [DashboardController::class, 'ipmUhhSp'])->name('ipm-uhh-sp');
Route::get('/ipm-hls', [DashboardController::class, 'ipmHls'])->name('ipm-hls');
Route::get('/ipm-rls', [DashboardController::class, 'ipmRls'])->name('ipm-rls');
Route::get('/ipm-pengeluaran-per-kapita', [DashboardController::class, 'ipmPengeluaranPerKapita'])->name('ipm-pengeluaran-per-kapita');
Route::get('/ipm-indeks-kesehatan', [DashboardController::class, 'ipmIndeksKesehatan'])->name('ipm-indeks-kesehatan');
Route::get('/ipm-indeks-hidup-layak', [DashboardController::class, 'ipmIndeksHidupLayak'])->name('ipm-indeks-hidup-layak');
Route::get('/ipm-indeks-pendidikan', [DashboardController::class, 'ipmIndeksPendidikan'])->name('ipm-indeks-pendidikan');
Route::get('/pdrb-pengeluaran', [DashboardController::class, 'pdrbPengeluaran'])->name('pdrb-pengeluaran');
Route::get('/pdrb-lapangan-usaha', [DashboardController::class, 'pdrbLapanganUsaha'])->name('pdrb-lapangan-usaha');
Route::get('/inflasi', [DashboardController::class, 'inflasi'])->name('inflasi');
Route::get('/infographics', [DashboardController::class, 'infographics'])->name('infographics');
Route::get('/publications', [DashboardController::class, 'publications'])->name('publications');

// Protected routes (require authentication for bookmark and download features)
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Bookmark routes (accessible from web with session auth)
    // Changed from /api/bookmarks to /bookmarks to enable CSRF verification
    Route::get('/bookmarks', [ApiDataController::class, 'viewBookmarks'])->name('api.bookmarks');
    Route::post('/bookmarks/add', [ApiDataController::class, 'addBookmark'])->name('api.bookmarks.add');
    Route::delete('/bookmarks/{id}', [ApiDataController::class, 'deleteBookmark'])->name('api.bookmarks.delete');
    
    // Download routes (require login)
    Route::get('/infographics/download/{id}', [DashboardController::class, 'downloadInfographic'])->name('download-infographic');
    Route::get('/publications/download/{pub_id}', [DashboardController::class, 'downloadPublication'])->name('download-publication');
});
