@extends('layouts.main')

@section('title', 'Indeks Pembangunan Manusia')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script>
window.APP_CONFIG = {
  apiUrl: '{{ url("/api") }}',
  isAuthenticated: @auth true @else false @endauth,
  loginUrl: '{{ route("login") }}'
};
</script>

<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
@endpush

@section('content')
<div class="container py-4">
  <h3 class="font-weight-bold mb-4">Indeks Pembangunan Manusia</h3>
  
  <!-- Infinite Carousel for Summary Cards -->
  <div class="row mb-4">
    <div class="col-md-12" style="padding:0px;">
      <div class="card">
        <div class="card-body" style="padding: 25px;">
          <div class="indicator-carousel-wrapper" style="position: relative; overflow: hidden; padding: 0;">
            <div class="indicator-carousel-track" id="ipmIndicatorCarousel" style="display: flex; gap: 15px; will-change: transform;">
              <!-- UHH SP Card -->
              <div class="indicator-card" style="min-width: 240px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-radius: 12px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h6 style="font-size: 12px; color: rgba(255, 255, 255, 0.9); margin-bottom: 8px; font-weight: 500;">
                  Usia Harapan Hidup saat Lahir
                </h6>
                <h3 style="font-size: 22px; font-weight: 700; color: white; margin-bottom: 6px;" id="uhh-sp-value">
                  -
                </h3>
                <div id="uhh-sp-comparison" style="display: flex; align-items: center; gap: 5px; margin-bottom: 4px;">
                  <!-- Will be populated by JavaScript -->
                </div>
                <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px;" id="uhh-sp-year">
                  Data tidak tersedia
                </small>
              </div>

              <!-- HLS Card -->
              <div class="indicator-card" style="min-width: 240px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 12px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h6 style="font-size: 13px; color: rgba(255, 255, 255, 0.9); margin-bottom: 10px; font-weight: 500;">
                  Harapan Lama Sekolah
                </h6>
                <h3 style="font-size: 24px; font-weight: 700; color: white; margin-bottom: 8px;" id="hls-value">
                  -
                </h3>
                <div id="hls-comparison" style="display: flex; align-items: center; gap: 5px; margin-bottom: 5px;">
                  <!-- Will be populated by JavaScript -->
                </div>
                <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px;" id="hls-year">
                  Data tidak tersedia
                </small>
              </div>

              <!-- RLS Card -->
              <div class="indicator-card" style="min-width: 240px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border-radius: 12px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h6 style="font-size: 13px; color: rgba(255, 255, 255, 0.9); margin-bottom: 10px; font-weight: 500;">
                  Rata-rata Lama Sekolah
                </h6>
                <h3 style="font-size: 24px; font-weight: 700; color: white; margin-bottom: 8px;" id="rls-value">
                  -
                </h3>
                <div id="rls-comparison" style="display: flex; align-items: center; gap: 5px; margin-bottom: 5px;">
                  <!-- Will be populated by JavaScript -->
                </div>
                <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px;" id="rls-year">
                  Data tidak tersedia
                </small>
              </div>

              <!-- Pengeluaran per Kapita Card -->
              <div class="indicator-card" style="min-width: 240px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border-radius: 12px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h6 style="font-size: 12px; color: rgba(255, 255, 255, 0.9); margin-bottom: 8px; font-weight: 500;">
                  Pengeluaran per Kapita
                </h6>
                <h3 style="font-size: 22px; font-weight: 700; color: white; margin-bottom: 6px; word-break: break-word; overflow-wrap: break-word; white-space: normal;" id="pengeluaran-value">
                  -
                </h3>
                <div id="pengeluaran-comparison" style="display: flex; align-items: center; gap: 5px; margin-bottom: 5px;">
                  <!-- Will be populated by JavaScript -->
                </div>
                <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px;" id="pengeluaran-year">
                  Data tidak tersedia
                </small>
              </div>

              <!-- Indeks Kesehatan Card -->
              <div class="indicator-card" style="min-width: 240px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border-radius: 12px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h6 style="font-size: 13px; color: rgba(255, 255, 255, 0.9); margin-bottom: 10px; font-weight: 500;">
                  Indeks Kesehatan
                </h6>
                <h3 style="font-size: 24px; font-weight: 700; color: white; margin-bottom: 8px;" id="indeks-kesehatan-value">
                  -
                </h3>
                <div id="indeks-kesehatan-comparison" style="display: flex; align-items: center; gap: 5px; margin-bottom: 5px;">
                  <!-- Will be populated by JavaScript -->
                </div>
                <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px;" id="indeks-kesehatan-year">
                  Data tidak tersedia
                </small>
              </div>

              <!-- Indeks Pendidikan Card -->
              <div class="indicator-card" style="min-width: 240px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-radius: 12px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h6 style="font-size: 13px; color: rgba(255, 255, 255, 0.9); margin-bottom: 10px; font-weight: 500;">
                  Indeks Pendidikan
                </h6>
                <h3 style="font-size: 24px; font-weight: 700; color: white; margin-bottom: 8px;" id="indeks-pendidikan-value">
                  -
                </h3>
                <div id="indeks-pendidikan-comparison" style="display: flex; align-items: center; gap: 5px; margin-bottom: 5px;">
                  <!-- Will be populated by JavaScript -->
                </div>
                <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px;" id="indeks-pendidikan-year">
                  Data tidak tersedia
                </small>
              </div>

              <!-- Indeks Hidup Layak Card -->
              <div class="indicator-card" style="min-width: 240px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 12px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h6 style="font-size: 13px; color: rgba(255, 255, 255, 0.9); margin-bottom: 10px; font-weight: 500;">
                  Indeks Hidup Layak
                </h6>
                <h3 style="font-size: 24px; font-weight: 700; color: white; margin-bottom: 8px;" id="indeks-hidup-layak-value">
                  -
                </h3>
                <div id="indeks-hidup-layak-comparison" style="display: flex; align-items: center; gap: 5px; margin-bottom: 5px;">
                  <!-- Will be populated by JavaScript -->
                </div>
                <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px;" id="indeks-hidup-layak-year">
                  Data tidak tersedia
                </small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- IPM Cards: Surabaya and Jawa Timur -->
  <div class="row mb-4 ipm-cards-row" style="display: flex; flex-wrap: nowrap; gap: 15px; margin-left: 0; margin-right: 0;">
    <div class="col-6 mb-3" style="flex: 1; min-width: 0; padding-left: 0; padding-right: 0;">
      <div class="summary-card ipm-card" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-radius: 12px; padding: 20px; height: 200px; position: relative; overflow: visible;">
        <div style="position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column;">
          <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">
            Indeks Pembangunan Manusia (IPM)
          </h6>
          <h6 style="color: rgba(255, 255, 255, 0.85); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">Kota Surabaya</h6>
          <h3 style="font-size: 28px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;" id="surabaya-ipm-value">
            -
          </h3>
          <div id="surabaya-comparison" style="display: flex; align-items: center; gap: 5px; margin-top: 8px;">
            <!-- Will be populated by JavaScript -->
          </div>
          <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: auto;" id="surabaya-year">
            -
          </small>
        </div>
      </div>
    </div>
    <div class="col-6 mb-3" style="flex: 1; min-width: 0; padding-left: 0; padding-right: 0;">
      <div class="summary-card ipm-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 12px; padding: 20px; height: 200px; position: relative; overflow: visible;">
        <div style="position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column;">
          <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">
            Indeks Pembangunan Manusia (IPM)
          </h6>
          <h6 style="color: rgba(255, 255, 255, 0.85); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">Jawa Timur</h6>
          <h3 style="font-size: 28px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;" id="jatim-ipm-value">
            -
          </h3>
          <div id="jatim-comparison" style="display: flex; align-items: center; gap: 5px; margin-top: 8px;">
            <!-- Will be populated by JavaScript -->
          </div>
          <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: auto;" id="jatim-year">
            -
          </small>
        </div>
      </div>
    </div>
  </div>

  <!-- Trend Chart: Surabaya vs Jawa Timur -->
  <div class="row mb-4">
    <div class="col-md-12" style="padding:0px;">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">Tren IPM Kota Surabaya vs Jawa Timur</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="trendChart" title="Tren IPM Kota Surabaya vs Jawa Timur" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadTrendDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadTrendDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadTrendExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadTrendPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div id="trendChart" style="width: 100%; height: 450px;"></div>
      </div>
    </div>
  </div>

  <!-- Additional Visualizations - 2 Columns Layout -->
  <!-- Row 1: UHH SP and HLS -->
  <div class="row mb-4">
    <div class="col-md-6 mb-3">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">UHH SP - Kota Surabaya</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="uhhSpChart" title="UHH SP Kota Surabaya" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadUhhSpDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadUhhSpDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadUhhSpExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadUhhSpPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div id="uhhSpChart" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">HLS - Kota Surabaya</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="hlsChart" title="HLS Kota Surabaya" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadHlsDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadHlsDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadHlsExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadHlsPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div id="hlsChart" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
  </div>

  <!-- Row 2: RLS and Pengeluaran per Kapita -->
  <div class="row mb-4">
    <div class="col-md-6 mb-3">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">RLS - Kota Surabaya</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="rlsChart" title="RLS Kota Surabaya" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadRlsDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadRlsDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadRlsExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadRlsPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div id="rlsChart" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">Pengeluaran per Kapita - Kota Surabaya</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="pengeluaranChart" title="Pengeluaran per Kapita Kota Surabaya" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadPengeluaranDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadPengeluaranDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadPengeluaranExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadPengeluaranPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div id="pengeluaranChart" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
  </div>

  <!-- Row 3: Indeks Kesehatan and Indeks Pendidikan -->
  <div class="row mb-4">
    <div class="col-md-6 mb-3">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">Indeks Kesehatan - Kota Surabaya</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="indeksKesehatanChart" title="Indeks Kesehatan Kota Surabaya" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadIndeksKesehatanDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadIndeksKesehatanDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadIndeksKesehatanExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadIndeksKesehatanPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div id="indeksKesehatanChart" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">Indeks Pendidikan - Kota Surabaya</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="indeksPendidikanChart" title="Indeks Pendidikan Kota Surabaya" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadIndeksPendidikanDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadIndeksPendidikanDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadIndeksPendidikanExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadIndeksPendidikanPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div id="indeksPendidikanChart" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
  </div>

  <!-- Row 4: Indeks Hidup Layak -->
  <div class="row mb-4">
    <div class="col-md-6 mb-3">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">Indeks Hidup Layak - Kota Surabaya</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="indeksHidupLayakChart" title="Indeks Hidup Layak Kota Surabaya" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadIndeksHidupLayakDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadIndeksHidupLayakDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadIndeksHidupLayakExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadIndeksHidupLayakPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div id="indeksHidupLayakChart" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">Komposisi Indikator IPM - Tahun Terakhir</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="compositionChart" title="Komposisi Indikator IPM" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadCompositionDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadCompositionDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadCompositionExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadCompositionPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div id="compositionChart" style="width: 100%; height: 350px;"></div>
      </div>
    </div>
  </div>

  <!-- Additional Info Card -->
  <div class="row">
    <div class="col-md-12">
      <div class="dashboard-card" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
        <h5 class="mb-3"><i class="fas fa-info-circle"></i> Tentang Indeks Pembangunan Manusia</h5>
        <p style="margin-bottom: 0; line-height: 1.8;">
          <strong>Indeks Pembangunan Manusia (IPM)</strong> adalah indikator komposit yang mengukur pencapaian pembangunan manusia berdasarkan tiga dimensi utama: 
          kesehatan, pendidikan, dan standar hidup layak. IPM dihitung dengan menggabungkan:
        </p>
        <ul style="margin-top: 12px; margin-bottom: 0; line-height: 1.8;">
          <li><strong>Indeks Kesehatan</strong>: Diukur melalui Usia Harapan Hidup saat Lahir (UHH SP), 
            yang mencerminkan kondisi kesehatan dan kualitas hidup penduduk.</li>
          <li><strong>Indeks Pendidikan</strong>: Diukur melalui Harapan Lama Sekolah (HLS) dan Rata-rata Lama Sekolah (RLS), 
            yang mencerminkan akses dan kualitas pendidikan.</li>
          <li><strong>Indeks Hidup Layak</strong>: Diukur melalui Pengeluaran per Kapita yang disesuaikan, 
            yang mencerminkan kemampuan ekonomi untuk memenuhi kebutuhan dasar.</li>
        </ul>
        <p style="margin-top: 12px; margin-bottom: 16px; line-height: 1.8;">
          Nilai IPM berkisar antara 0 hingga 100, di mana nilai yang lebih tinggi menunjukkan tingkat pembangunan manusia yang lebih baik. 
          IPM digunakan untuk mengevaluasi kemajuan pembangunan manusia dan membantu dalam perencanaan kebijakan yang lebih fokus pada peningkatan kualitas hidup penduduk.
        </p>
      </div>
    </div>
  </div>
</div>

@push('styles')

@endpush

@push('scripts')
<script>
window.APP_CONFIG = {
  apiUrl: '{{ url("/api") }}',
  isAuthenticated: @auth true @else false @endauth,
  loginUrl: '{{ route("login") }}'
};
</script>


@endpush

@push('scripts')
<script>
window.APP_CONFIG = {
  apiUrl: '{{ url("/api") }}',
  isAuthenticated: @auth true @else false @endauth,
  loginUrl: '{{ route("login") }}'
};
</script>

@vite(['resources/css/dashboard/indeks-pembangunan-manusia.css', 'resources/js/dashboard/indeks-pembangunan-manusia.js'])
@endpush

@endsection
