@extends('layouts.main')

@section('title', 'Indeks Pembangunan Manusia')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
@endpush

@push('scripts')
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
              <div class="indicator-card" style="min-width: 240px; background: white; border-radius: 12px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h6 style="font-size: 12px; color: #666; margin-bottom: 8px; font-weight: 500;">
                  Usia Harapan Hidup saat Lahir
                </h6>
                <h3 style="font-size: 22px; font-weight: 700; color: #333; margin-bottom: 6px;" id="uhh-sp-value">
                  -
                </h3>
                <div id="uhh-sp-comparison" style="display: flex; align-items: center; gap: 5px; margin-bottom: 4px;">
                  <!-- Will be populated by JavaScript -->
                </div>
                <small style="color: #999; font-size: 11px;" id="uhh-sp-year">
                  Data tidak tersedia
                </small>
              </div>

              <!-- HLS Card -->
              <div class="indicator-card" style="min-width: 240px; background: white; border-radius: 12px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h6 style="font-size: 13px; color: #666; margin-bottom: 10px; font-weight: 500;">
                  Harapan Lama Sekolah
                </h6>
                <h3 style="font-size: 24px; font-weight: 700; color: #333; margin-bottom: 8px;" id="hls-value">
                  -
                </h3>
                <div id="hls-comparison" style="display: flex; align-items: center; gap: 5px; margin-bottom: 5px;">
                  <!-- Will be populated by JavaScript -->
                </div>
                <small style="color: #999; font-size: 11px;" id="hls-year">
                  Data tidak tersedia
                </small>
              </div>

              <!-- RLS Card -->
              <div class="indicator-card" style="min-width: 240px; background: white; border-radius: 12px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h6 style="font-size: 13px; color: #666; margin-bottom: 10px; font-weight: 500;">
                  Rata-rata Lama Sekolah
                </h6>
                <h3 style="font-size: 24px; font-weight: 700; color: #333; margin-bottom: 8px;" id="rls-value">
                  -
                </h3>
                <div id="rls-comparison" style="display: flex; align-items: center; gap: 5px; margin-bottom: 5px;">
                  <!-- Will be populated by JavaScript -->
                </div>
                <small style="color: #999; font-size: 11px;" id="rls-year">
                  Data tidak tersedia
                </small>
              </div>

              <!-- Pengeluaran per Kapita Card -->
              <div class="indicator-card" style="min-width: 240px; background: white; border-radius: 12px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h6 style="font-size: 12px; color: #666; margin-bottom: 8px; font-weight: 500;">
                  Pengeluaran per Kapita
                </h6>
                <h3 style="font-size: 22px; font-weight: 700; color: #333; margin-bottom: 6px; word-break: break-word; overflow-wrap: break-word; white-space: normal;" id="pengeluaran-value">
                  -
                </h3>
                <div id="pengeluaran-comparison" style="display: flex; align-items: center; gap: 5px; margin-bottom: 5px;">
                  <!-- Will be populated by JavaScript -->
                </div>
                <small style="color: #999; font-size: 11px;" id="pengeluaran-year">
                  Data tidak tersedia
                </small>
              </div>

              <!-- Indeks Kesehatan Card -->
              <div class="indicator-card" style="min-width: 240px; background: white; border-radius: 12px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h6 style="font-size: 13px; color: #666; margin-bottom: 10px; font-weight: 500;">
                  Indeks Kesehatan
                </h6>
                <h3 style="font-size: 24px; font-weight: 700; color: #333; margin-bottom: 8px;" id="indeks-kesehatan-value">
                  -
                </h3>
                <div id="indeks-kesehatan-comparison" style="display: flex; align-items: center; gap: 5px; margin-bottom: 5px;">
                  <!-- Will be populated by JavaScript -->
                </div>
                <small style="color: #999; font-size: 11px;" id="indeks-kesehatan-year">
                  Data tidak tersedia
                </small>
              </div>

              <!-- Indeks Pendidikan Card -->
              <div class="indicator-card" style="min-width: 240px; background: white; border-radius: 12px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h6 style="font-size: 13px; color: #666; margin-bottom: 10px; font-weight: 500;">
                  Indeks Pendidikan
                </h6>
                <h3 style="font-size: 24px; font-weight: 700; color: #333; margin-bottom: 8px;" id="indeks-pendidikan-value">
                  -
                </h3>
                <div id="indeks-pendidikan-comparison" style="display: flex; align-items: center; gap: 5px; margin-bottom: 5px;">
                  <!-- Will be populated by JavaScript -->
                </div>
                <small style="color: #999; font-size: 11px;" id="indeks-pendidikan-year">
                  Data tidak tersedia
                </small>
              </div>

              <!-- Indeks Hidup Layak Card -->
              <div class="indicator-card" style="min-width: 240px; background: white; border-radius: 12px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h6 style="font-size: 13px; color: #666; margin-bottom: 10px; font-weight: 500;">
                  Indeks Hidup Layak
                </h6>
                <h3 style="font-size: 24px; font-weight: 700; color: #333; margin-bottom: 8px;" id="indeks-hidup-layak-value">
                  -
                </h3>
                <div id="indeks-hidup-layak-comparison" style="display: flex; align-items: center; gap: 5px; margin-bottom: 5px;">
                  <!-- Will be populated by JavaScript -->
                </div>
                <small style="color: #999; font-size: 11px;" id="indeks-hidup-layak-year">
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
<style>
  /* Infinite Scroll Animation for Indicator Carousel - Continuous Right Scroll */
  .indicator-carousel-wrapper {
    overflow: hidden !important;
  }

  .indicator-carousel-track {
    display: flex !important;
    gap: 15px;
    will-change: transform;
    /* Animation handled by JavaScript for seamless continuous scroll */
  }

  .indicator-carousel-content {
    display: flex;
    gap: 15px;
    flex-shrink: 0;
    min-width: fit-content;
  }

  .dashboard-card {
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    padding: 25px;
    margin-bottom: 20px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
    box-sizing: border-box;
  }

  .dashboard-card > div[id$="Chart"] {
    max-width: 100%;
    box-sizing: border-box;
    overflow: hidden;
  }
  
  .dashboard-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  }
  
  .summary-card {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 20px !important;
    box-sizing: border-box;
  }

  .ipm-cards-row {
    display: flex !important;
    flex-wrap: nowrap !important;
  }

  /* Responsive styles for IPM cards and carousel cards */
  @media (max-width: 768px) {
    .ipm-card {
      padding: 12px !important;
      height: 150px !important;
    }
    
    .ipm-card h6 {
      font-size: 10px !important;
      margin: 0 0 6px 0 !important;
    }
    
    .ipm-card h3 {
      font-size: 20px !important;
      margin: 0 0 6px 0 !important;
    }
    
    .ipm-card small {
      font-size: 9px !important;
    }
    
    .indicator-card {
      padding: 12px !important;
      width: 100% !important;
      min-width: 100% !important;
      max-width: 100% !important;
      flex-shrink: 0 !important;
      height: 150px !important;
      display: flex !important;
      flex-direction: column !important;
      justify-content: space-between !important;
    }
  }

  @media (max-width: 576px) {
    .ipm-card {
      padding: 10px !important;
      height: 130px !important;
    }
    
    .indicator-card {
      padding: 12px !important;
      min-width: 180px !important;
    }
  }
</style>
@endpush

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const API_BASE = '{{ url("/api") }}';
    
    // ========== Load all IPM data from APIs ==========
    async function loadAllIPMData() {
      try {
        const location = 'Kota Surabaya';
        
        // Load all data in parallel with location filter
        const [
          uhhSpRes, hlsRes, rlsRes, 
          surabayaRes, jatimRes,
          pengeluaranRes, indeksKesehatanRes, indeksPendidikanRes, indeksHidupLayakRes
        ] = await Promise.all([
          fetch(`${API_BASE}/ipm-uhh-sp?location=${encodeURIComponent(location)}`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
          fetch(`${API_BASE}/ipm-hls?location=${encodeURIComponent(location)}`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
          fetch(`${API_BASE}/ipm-rls?location=${encodeURIComponent(location)}`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
          fetch(`${API_BASE}/ipm-surabaya`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
          fetch(`${API_BASE}/ipm-jatim`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
          fetch(`${API_BASE}/ipm-pengeluaran-per-kapita?location=${encodeURIComponent(location)}`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
          fetch(`${API_BASE}/ipm-indeks-kesehatan?location=${encodeURIComponent(location)}`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
          fetch(`${API_BASE}/ipm-indeks-pendidikan?location=${encodeURIComponent(location)}`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
          fetch(`${API_BASE}/ipm-indeks-hidup-layak?location=${encodeURIComponent(location)}`).then(r => r.json()).catch(() => ({ success: false, data: [] }))
        ]);

        const uhhSpData = uhhSpRes.success ? uhhSpRes.data : [];
        const hlsData = hlsRes.success ? hlsRes.data : [];
        const rlsData = rlsRes.success ? rlsRes.data : [];
        const surabayaData = surabayaRes.success ? surabayaRes.data : [];
        const jatimData = jatimRes.success ? jatimRes.data : [];
        const pengeluaranData = pengeluaranRes.success ? pengeluaranRes.data : [];
        const indeksKesehatanData = indeksKesehatanRes.success ? indeksKesehatanRes.data : [];
        const indeksPendidikanData = indeksPendidikanRes.success ? indeksPendidikanRes.data : [];
        const indeksHidupLayakData = indeksHidupLayakRes.success ? indeksHidupLayakRes.data : [];

        // Debug logging
        console.log('IPM Data loaded:', {
          uhhSp: { count: uhhSpData.length, sample: uhhSpData[0] },
          hls: { count: hlsData.length, sample: hlsData[0] },
          rls: { count: rlsData.length, sample: rlsData[0] },
          surabaya: { count: surabayaData.length, sample: surabayaData[0] },
          jatim: { count: jatimData.length, sample: jatimData[0] },
          pengeluaran: { count: pengeluaranData.length, sample: pengeluaranData[0] },
          indeksKesehatan: { count: indeksKesehatanData.length, sample: indeksKesehatanData[0] },
          indeksPendidikan: { count: indeksPendidikanData.length, sample: indeksPendidikanData[0] },
          indeksHidupLayak: { count: indeksHidupLayakData.length, sample: indeksHidupLayakData[0] }
        });

        // Update summary cards
        updateSummaryCards(uhhSpData, hlsData, rlsData, surabayaData, jatimData, pengeluaranData, indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData);
        
        // Initialize carousel
        initCarousel();
        
        // Initialize charts
        initCharts(uhhSpData, hlsData, rlsData, surabayaData, jatimData, pengeluaranData, indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData);
      } catch (error) {
        console.error('Error loading IPM data:', error);
      }
    }

    function updateSummaryCards(uhhSpData, hlsData, rlsData, surabayaData, jatimData, pengeluaranData, indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData) {
      // Sort data by year to get latest
      const sortByYear = (a, b) => (b.year || 0) - (a.year || 0);
      
      // Update UHH SP
      if (uhhSpData && uhhSpData.length > 0) {
        const sorted = [...uhhSpData].sort(sortByYear);
        const latest = sorted[0];
        const el = document.getElementById('uhh-sp-value');
        const yearEl = document.getElementById('uhh-sp-year');
        if (el) el.textContent = latest.value ? Number(latest.value).toFixed(2) : '-';
        if (yearEl) yearEl.textContent = latest.year ? `Tahun ${latest.year}` : 'Data tidak tersedia';
      }

      // Update HLS
      if (hlsData && hlsData.length > 0) {
        const sorted = [...hlsData].sort(sortByYear);
        const latest = sorted[0];
        const el = document.getElementById('hls-value');
        const yearEl = document.getElementById('hls-year');
        if (el) el.textContent = latest.value ? Number(latest.value).toFixed(2) : '-';
        if (yearEl) yearEl.textContent = latest.year ? `Tahun ${latest.year}` : 'Data tidak tersedia';
      }

      // Update RLS
      if (rlsData && rlsData.length > 0) {
        const sorted = [...rlsData].sort(sortByYear);
        const latest = sorted[0];
        const el = document.getElementById('rls-value');
        const yearEl = document.getElementById('rls-year');
        if (el) el.textContent = latest.value ? Number(latest.value).toFixed(2) : '-';
        if (yearEl) yearEl.textContent = latest.year ? `Tahun ${latest.year}` : 'Data tidak tersedia';
      }

      // Update Pengeluaran per Kapita
      if (pengeluaranData && pengeluaranData.length > 0) {
        const sorted = [...pengeluaranData].sort(sortByYear);
        const latest = sorted[0];
        const el = document.getElementById('pengeluaran-value');
        const yearEl = document.getElementById('pengeluaran-year');
        if (el) el.textContent = latest.value ? `Rp ${Number(latest.value).toFixed(2)}` : '-';
        if (yearEl) yearEl.textContent = latest.year ? `Tahun ${latest.year}` : 'Data tidak tersedia';
      }

      // Update Indeks Kesehatan
      if (indeksKesehatanData && indeksKesehatanData.length > 0) {
        const sorted = [...indeksKesehatanData].sort(sortByYear);
        const latest = sorted[0];
        const el = document.getElementById('indeks-kesehatan-value');
        const yearEl = document.getElementById('indeks-kesehatan-year');
        if (el) el.textContent = latest.value ? Number(latest.value).toFixed(2) : '-';
        if (yearEl) yearEl.textContent = latest.year ? `Tahun ${latest.year}` : 'Data tidak tersedia';
      }

      // Update Indeks Pendidikan
      if (indeksPendidikanData && indeksPendidikanData.length > 0) {
        const sorted = [...indeksPendidikanData].sort(sortByYear);
        const latest = sorted[0];
        const el = document.getElementById('indeks-pendidikan-value');
        const yearEl = document.getElementById('indeks-pendidikan-year');
        if (el) el.textContent = latest.value ? Number(latest.value).toFixed(2) : '-';
        if (yearEl) yearEl.textContent = latest.year ? `Tahun ${latest.year}` : 'Data tidak tersedia';
      }

      // Update Indeks Hidup Layak
      if (indeksHidupLayakData && indeksHidupLayakData.length > 0) {
        const sorted = [...indeksHidupLayakData].sort(sortByYear);
        const latest = sorted[0];
        const el = document.getElementById('indeks-hidup-layak-value');
        const yearEl = document.getElementById('indeks-hidup-layak-year');
        if (el) el.textContent = latest.value ? Number(latest.value).toFixed(2) : '-';
        if (yearEl) yearEl.textContent = latest.year ? `Tahun ${latest.year}` : 'Data tidak tersedia';
      }

      // Update Surabaya IPM
      if (surabayaData && surabayaData.length > 0) {
        const sorted = [...surabayaData].sort(sortByYear);
        const latest = sorted[0];
        const el = document.getElementById('surabaya-ipm-value');
        const yearEl = document.getElementById('surabaya-year');
        if (el) el.textContent = latest.ipm_value ? Number(latest.ipm_value).toFixed(2) : '-';
        if (yearEl) yearEl.textContent = latest.year ? `Tahun ${latest.year}` : '-';
      }

      // Update Jatim IPM
      if (jatimData && jatimData.length > 0) {
        const sorted = [...jatimData].sort(sortByYear);
        const latest = sorted[0];
        const el = document.getElementById('jatim-ipm-value');
        const yearEl = document.getElementById('jatim-year');
        if (el) el.textContent = latest.ipm_value ? Number(latest.ipm_value).toFixed(2) : '-';
        if (yearEl) yearEl.textContent = latest.year ? `Tahun ${latest.year}` : '-';
      }
    }

    // ========== IPM Indicator Carousel - Continuous Infinite Scroll to Right ==========
    function initCarousel() {
      const carousel = document.getElementById("ipmIndicatorCarousel");
      if (!carousel) return;

      const cards = carousel.querySelectorAll('.indicator-card');
      if (cards.length === 0) return;

      // Wrap existing cards in content set
      const originalContent = document.createElement('div');
      originalContent.className = 'indicator-carousel-content';
      originalContent.style.display = 'flex';
      originalContent.style.gap = '15px';
      originalContent.style.flexShrink = '0';
      originalContent.style.minWidth = 'fit-content';
      
      // Move existing cards to originalContent
      Array.from(cards).forEach(card => {
        originalContent.appendChild(card);
      });

      // Create duplicate content set for seamless loop
      const duplicateContent = originalContent.cloneNode(true);
      duplicateContent.setAttribute('aria-hidden', 'true');

      // Clear carousel and add both content sets
      carousel.innerHTML = '';
      carousel.appendChild(originalContent);
      carousel.appendChild(duplicateContent);

      const contentSets = carousel.querySelectorAll(".indicator-carousel-content");
      if (contentSets.length < 2) return;

      function getContentSetWidth() {
        return contentSets[0] ? contentSets[0].offsetWidth + 15 : 0;
      }

      let currentPosition = 0;
      let isPaused = false;
      let animationFrameId;
      const scrollSpeed = 1.5;

      function animate() {
        if (!isPaused) {
          const contentSetWidth = getContentSetWidth();
          currentPosition += scrollSpeed;
          if (currentPosition >= contentSetWidth) {
            currentPosition = currentPosition - contentSetWidth;
          }
          carousel.style.transform = `translateX(-${currentPosition}px)`;
        }
        animationFrameId = requestAnimationFrame(animate);
      }

      const carouselWrapper = carousel.closest(".indicator-carousel-wrapper");
      if (carouselWrapper) {
        carouselWrapper.addEventListener("mouseenter", () => { isPaused = true; });
        carouselWrapper.addEventListener("mouseleave", () => { isPaused = false; });
      }

      animate();

      let resizeTimeout;
      window.addEventListener("resize", () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
          const contentSetWidth = getContentSetWidth();
          if (currentPosition >= contentSetWidth) {
            currentPosition = currentPosition % contentSetWidth;
          }
        }, 250);
      });
    }

    // ========== Initialize Charts ==========
    function initCharts(uhhSpData, hlsData, rlsData, surabayaData, jatimData, pengeluaranData, indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData) {
      // Sort data by year
      const sortByYear = (a, b) => (a.year || 0) - (b.year || 0);
      surabayaData.sort(sortByYear);
      jatimData.sort(sortByYear);
      uhhSpData.sort(sortByYear);
      hlsData.sort(sortByYear);
      rlsData.sort(sortByYear);
      pengeluaranData.sort(sortByYear);
      indeksKesehatanData.sort(sortByYear);
      indeksPendidikanData.sort(sortByYear);
      indeksHidupLayakData.sort(sortByYear);

      // Calculate comparisons for IPM cards
      calculateComparison(surabayaData, 'surabaya-comparison', 'rgba(255, 255, 255, 0.9)');
      calculateComparison(jatimData, 'jatim-comparison', 'rgba(255, 255, 255, 0.9)');

      // Get all unique years
      const allYears = [...new Set([...surabayaData.map(d => d.year), ...jatimData.map(d => d.year)])].sort((a, b) => a - b);

      // Create trend chart
      createTrendChart(surabayaData, jatimData, allYears);

      // Create sub-indicator charts
      createSubIndicatorCharts(uhhSpData, hlsData, rlsData, pengeluaranData, indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData);

      // Create composition chart
      createCompositionChart(indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData, allYears);
    }

    function calculateComparison(data, containerId, textColor = 'rgba(255, 255, 255, 0.9)') {
      if (!data || data.length < 2) return;
      
      // Sort by year ascending
      const sorted = [...data].sort((a, b) => (a.year || 0) - (b.year || 0));
      const latest = sorted[sorted.length - 1];
      const previous = sorted[sorted.length - 2];
      
      if (!latest || latest.ipm_value === null && latest.value === null || !previous || previous.ipm_value === null && previous.value === null) return;
      
      const latestValue = latest.ipm_value !== undefined ? latest.ipm_value : latest.value;
      const previousValue = previous.ipm_value !== undefined ? previous.ipm_value : previous.value;
      
      if (latestValue === null || previousValue === null) return;
      
      const diff = latestValue - previousValue;
      const diffFormatted = Math.abs(diff).toFixed(2);
      
      const container = document.getElementById(containerId);
      if (!container) return;
      
      let arrow = '─';
      if (diff > 0) arrow = '▲';
      else if (diff < 0) arrow = '▼';
      
      container.innerHTML = `
        <span style="color: ${textColor}; font-size: 12px;">${arrow}</span>
        <span style="color: ${textColor}; font-size: 12px;">${diff >= 0 ? '+' : ''}${diffFormatted}</span>
        <span style="color: ${textColor.replace('0.9', '0.8')}; font-size: 11px;">dari ${previous.year}</span>
      `;
    }

    function createTrendChart(surabayaData, jatimData, allYears) {
      const trendChartDom = document.getElementById('trendChart');
      if (!trendChartDom) return;
      
      const trendChart = echarts.init(trendChartDom);
      
      const labels = allYears.map(y => y.toString());
      const surabayaValues = allYears.map(year => {
        const data = surabayaData.find(d => d.year === year);
        return data && data.ipm_value !== null ? data.ipm_value : null;
      });
      const jatimValues = allYears.map(year => {
        const data = jatimData.find(d => d.year === year);
        return data && data.ipm_value !== null ? data.ipm_value : null;
      });

      const allValues = [
        ...surabayaValues.filter(v => v !== null),
        ...jatimValues.filter(v => v !== null)
      ];
      
      const minValue = allValues.length > 0 ? Math.min(...allValues) : 0;
      const maxValue = allValues.length > 0 ? Math.max(...allValues) : 100;
      const yMin = Math.max(0, minValue - (minValue * 0.05));
      const yMax = maxValue + (maxValue * 0.10);
      const roundedYMin = Math.floor(yMin / 5) * 5;
      const roundedYMax = Math.ceil(yMax / 5) * 5;

      trendChart.setOption({
        tooltip: {
          trigger: 'axis',
          formatter: function(params) {
            let result = 'Tahun: ' + params[0].axisValue + '<br/>';
            params.forEach(function(item) {
              if (item.value === null || item.value === undefined) {
                result += item.marker + item.seriesName + ': Data tidak tersedia<br/>';
              } else {
                result += item.marker + item.seriesName + ': ' + item.value.toFixed(2) + '<br/>';
              }
            });
            return result;
          }
        },
        legend: {
          data: ['Kota Surabaya', 'Jawa Timur'],
          bottom: 0,
          orient: 'horizontal'
        },
        grid: {
          left: '12%',
          right: '4%',
          bottom: '15%',
          top: '10%',
          containLabel: false
        },
        xAxis: {
          type: 'category',
          boundaryGap: false,
          data: labels,
          name: 'Tahun',
          nameLocation: 'middle',
          nameGap: 30,
          axisLabel: {
            show: true,
            margin: 12,
            rotate: 0,
            interval: 0
          }
        },
        yAxis: {
          type: 'value',
          min: roundedYMin,
          max: roundedYMax,
          interval: 5,
          axisLabel: {
            show: true,
            formatter: function(value) {
              return value.toFixed(1);
            },
            margin: 12
          },
          name: 'Nilai IPM',
          nameLocation: 'middle',
          nameGap: 60
        },
        series: [
          {
            name: 'Kota Surabaya',
            type: 'line',
            smooth: 0.4,
            data: surabayaValues,
            areaStyle: {
              color: {
                type: 'linear',
                x: 0, y: 0, x2: 0, y2: 1,
                colorStops: [
                  { offset: 0, color: 'rgba(59, 130, 246, 0.3)' },
                  { offset: 1, color: 'rgba(59, 130, 246, 0.05)' }
                ]
              }
            },
            lineStyle: { color: 'rgb(59, 130, 246)', width: 3 },
            itemStyle: { color: 'rgb(59, 130, 246)', borderColor: '#fff', borderWidth: 2 },
            symbol: 'circle',
            symbolSize: 8
          },
          {
            name: 'Jawa Timur',
            type: 'line',
            smooth: 0.4,
            data: jatimValues,
            areaStyle: {
              color: {
                type: 'linear',
                x: 0, y: 0, x2: 0, y2: 1,
                colorStops: [
                  { offset: 0, color: 'rgba(239, 68, 68, 0.3)' },
                  { offset: 1, color: 'rgba(239, 68, 68, 0.05)' }
                ]
              }
            },
            lineStyle: { color: 'rgb(239, 68, 68)', width: 3 },
            itemStyle: { color: 'rgb(239, 68, 68)', borderColor: '#fff', borderWidth: 2 },
            symbol: 'circle',
            symbolSize: 8
          }
        ]
      });

      window.chartInstances = window.chartInstances || {};
      window.chartInstances.trend = trendChart;
    }

    function createLineChart(canvasId, data, title, color, gridOptions = {}) {
      const chartDom = document.getElementById(canvasId);
      if (!chartDom) return;
      
      const sortedData = [...data].sort((a, b) => a.year - b.year);
      const last5YearsData = sortedData.slice(-5);
      
      const chart = echarts.init(chartDom);
      const labels = last5YearsData.map(d => d.year.toString());
      const values = last5YearsData.map(d => d.value !== null ? d.value : null);

      const validValues = values.filter(v => v !== null);
      const minValue = validValues.length > 0 ? Math.min(...validValues) : 0;
      const maxValue = validValues.length > 0 ? Math.max(...validValues) : 100;
      const yMin = Math.max(0, minValue - (minValue * 0.05));
      const yMax = gridOptions.yMax !== undefined ? gridOptions.yMax : (maxValue + (maxValue * 0.10));

      const rgbaColor = color.replace('rgb', 'rgba').replace(')', ', 0.1)');

      chart.setOption({
        tooltip: {
          trigger: 'axis',
          formatter: function(params) {
            if (params[0].value === null || params[0].value === undefined) {
              return params[0].name + '<br/>' + params[0].marker + params[0].seriesName + ': Data tidak tersedia';
            }
            return params[0].name + '<br/>' + params[0].marker + params[0].seriesName + ': ' + params[0].value.toFixed(2);
          }
        },
        grid: {
          left: gridOptions.left || '3%',
          right: gridOptions.right || '4%',
          bottom: gridOptions.bottom || '10%',
          top: gridOptions.top || '10%',
          containLabel: true
        },
        xAxis: {
          type: 'category',
          boundaryGap: false,
          data: labels,
          name: 'Tahun',
          nameLocation: 'middle',
          nameGap: 30,
          axisLabel: { rotate: 0, margin: 12 }
        },
        yAxis: {
          type: 'value',
          min: yMin,
          max: yMax,
          name: 'Nilai',
          nameLocation: 'middle',
          nameGap: 40
        },
        series: [{
          name: title,
          type: 'line',
          smooth: 0.4,
          data: values,
          areaStyle: { color: rgbaColor },
          lineStyle: { color: color, width: 3 },
          itemStyle: { color: color, borderColor: '#fff', borderWidth: 2 },
          symbol: 'circle',
          symbolSize: 8
        }]
      });

      return chart;
    }

    function createSubIndicatorCharts(uhhSpData, hlsData, rlsData, pengeluaranData, indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData) {
      const charts = {
        uhhSp: createLineChart('uhhSpChart', uhhSpData, 'UHH SP', 'rgb(59, 130, 246)', { bottom: '10%' }),
        hls: createLineChart('hlsChart', hlsData, 'HLS', 'rgb(16, 185, 129)', { bottom: '10%', yMax: 16.37 }),
        rls: createLineChart('rlsChart', rlsData, 'RLS', 'rgb(245, 158, 11)', { bottom: '10%' }),
        pengeluaran: createLineChart('pengeluaranChart', pengeluaranData, 'Pengeluaran per Kapita', 'rgb(239, 68, 68)', { bottom: '10%' }),
        indeksKesehatan: createLineChart('indeksKesehatanChart', indeksKesehatanData, 'Indeks Kesehatan', 'rgb(139, 92, 246)', { bottom: '10%' }),
        indeksPendidikan: createLineChart('indeksPendidikanChart', indeksPendidikanData, 'Indeks Pendidikan', 'rgb(59, 130, 246)', { bottom: '10%' }),
        indeksHidupLayak: createLineChart('indeksHidupLayakChart', indeksHidupLayakData, 'Indeks Hidup Layak', 'rgb(16, 185, 129)', { bottom: '10%' })
      };

      window.chartInstances = window.chartInstances || {};
      Object.assign(window.chartInstances, charts);
    }

    function createCompositionChart(indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData, allYears) {
      const compositionChartDom = document.getElementById('compositionChart');
      if (!compositionChartDom || allYears.length === 0) return;
      
      const compositionChart = echarts.init(compositionChartDom);
      const latestYear = allYears[allYears.length - 1];
      
      // Get latest year data for each index
      const latestIndeksKesehatan = indeksKesehatanData.find(d => d.year === latestYear);
      const latestIndeksPendidikan = indeksPendidikanData.find(d => d.year === latestYear);
      const latestIndeksHidupLayak = indeksHidupLayakData.find(d => d.year === latestYear);

      const pieData = [];
      if (latestIndeksKesehatan && latestIndeksKesehatan.value !== null) {
        pieData.push({ name: 'Indeks Kesehatan', value: latestIndeksKesehatan.value });
      }
      if (latestIndeksHidupLayak && latestIndeksHidupLayak.value !== null) {
        pieData.push({ name: 'Indeks Hidup Layak', value: latestIndeksHidupLayak.value });
      }
      if (latestIndeksPendidikan && latestIndeksPendidikan.value !== null) {
        pieData.push({ name: 'Indeks Pendidikan', value: latestIndeksPendidikan.value });
      }
      
      if (pieData.length > 0) {
        window.chartData = window.chartData || {};
        window.chartData.composition = pieData;
        
        compositionChart.setOption({
          tooltip: {
            trigger: 'item',
            formatter: '{a} <br/>{b}: {c} ({d}%)'
          },
          legend: {
            data: pieData.map(item => item.name),
            bottom: 0,
            orient: 'horizontal',
            itemGap: 15,
            itemWidth: 12,
            itemHeight: 12,
            textStyle: { fontSize: 11 },
            type: 'scroll',
            width: '100%'
          },
          series: [{
            name: 'Komposisi IPM',
            type: 'pie',
            radius: ['40%', '75%'],
            center: ['50%', '45%'],
            avoidLabelOverlap: true,
            itemStyle: {
              borderRadius: 10,
              borderColor: '#fff',
              borderWidth: 2
            },
            label: { show: false },
            labelLine: { show: false },
            emphasis: { label: { show: false } },
            data: pieData,
            color: ['#8b5cf6', '#3b82f6', '#10b981']
          }]
        });
      }

      window.chartInstances = window.chartInstances || {};
      window.chartInstances.composition = compositionChart;
    }

    // ========== Calculate Carousel Comparisons ==========
    function calculateCarouselComparison(allData, containerId, isCurrency = false) {
      const containers = document.querySelectorAll(`#${containerId}`);
      if (!containers || containers.length === 0 || !allData || allData.length < 2) return;
      
      const validData = allData.filter(d => {
        if (!d || !d.year) return false;
        const val = d.value;
        if (val === null || val === undefined || val === '') return false;
        const numVal = parseFloat(val);
        return !isNaN(numVal) && isFinite(numVal);
      });
      
      if (validData.length < 2) return;
      
      const sortedData = [...validData].sort((a, b) => (a.year || 0) - (b.year || 0));
      const latest = sortedData[sortedData.length - 1];
      const previousYear = latest.year - 1;
      let previous = sortedData.find(d => d.year === previousYear);
      
      if (!previous && sortedData.length > 1) {
        const previousYears = sortedData.filter(d => d.year < latest.year);
        if (previousYears.length > 0) {
          previous = previousYears[previousYears.length - 1];
        }
      }
      
      if (!previous || previous.value === null || previous.value === undefined) return;
      
      const diff = parseFloat(latest.value) - parseFloat(previous.value);
      let arrow = '─';
      let arrowColor = '#666';
      let valueColor = '#666';
      if (diff > 0) {
        arrow = '▲';
        arrowColor = '#28a745';
        valueColor = '#28a745';
      } else if (diff < 0) {
        arrow = '▼';
        arrowColor = '#dc3545';
        valueColor = '#dc3545';
      }
      
      const diffFormatted = Math.abs(diff).toFixed(2);
      const comparisonHTML = isCurrency ? 
        `<span style="color: ${arrowColor}; font-size: 14px;">${arrow}</span>
         <span style="color: ${valueColor}; font-size: 14px; font-weight: 600;">${diff >= 0 ? '+' : ''}Rp ${diffFormatted}</span>
         <span style="color: #666; font-size: 12px;">dari ${previous.year}</span>` :
        `<span style="color: ${arrowColor}; font-size: 14px;">${arrow}</span>
         <span style="color: ${valueColor}; font-size: 14px; font-weight: 600;">${diff >= 0 ? '+' : ''}${diffFormatted}</span>
         <span style="color: #666; font-size: 12px;">dari ${previous.year}</span>`;
      
      containers.forEach(container => {
        container.innerHTML = comparisonHTML;
      });
    }

    function calculateAllCarouselComparisons(uhhSpData, hlsData, rlsData, pengeluaranData, indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData) {
      const filteredUhhSp = uhhSpData.filter(d => d && d.year && d.value !== null && d.value !== undefined);
      const filteredHls = hlsData.filter(d => d && d.year && d.value !== null && d.value !== undefined);
      const filteredRls = rlsData.filter(d => d && d.year && d.value !== null && d.value !== undefined);
      const filteredPengeluaran = pengeluaranData.filter(d => d && d.year && d.value !== null && d.value !== undefined);
      const filteredIndeksKesehatan = indeksKesehatanData.filter(d => d && d.year && d.value !== null && d.value !== undefined);
      const filteredIndeksPendidikan = indeksPendidikanData.filter(d => d && d.year && d.value !== null && d.value !== undefined);
      const filteredIndeksHidupLayak = indeksHidupLayakData.filter(d => d && d.year && d.value !== null && d.value !== undefined);

      filteredUhhSp.sort((a, b) => (a.year || 0) - (b.year || 0));
      filteredHls.sort((a, b) => (a.year || 0) - (b.year || 0));
      filteredRls.sort((a, b) => (a.year || 0) - (b.year || 0));
      filteredPengeluaran.sort((a, b) => (a.year || 0) - (b.year || 0));
      filteredIndeksKesehatan.sort((a, b) => (a.year || 0) - (b.year || 0));
      filteredIndeksPendidikan.sort((a, b) => (a.year || 0) - (b.year || 0));
      filteredIndeksHidupLayak.sort((a, b) => (a.year || 0) - (b.year || 0));

      calculateCarouselComparison(filteredUhhSp, 'uhh-sp-comparison');
      calculateCarouselComparison(filteredHls, 'hls-comparison');
      calculateCarouselComparison(filteredRls, 'rls-comparison');
      calculateCarouselComparison(filteredPengeluaran, 'pengeluaran-comparison', true);
      calculateCarouselComparison(filteredIndeksKesehatan, 'indeks-kesehatan-comparison');
      calculateCarouselComparison(filteredIndeksPendidikan, 'indeks-pendidikan-comparison');
      calculateCarouselComparison(filteredIndeksHidupLayak, 'indeks-hidup-layak-comparison');
      
      setTimeout(() => {
        calculateCarouselComparison(filteredUhhSp, 'uhh-sp-comparison');
        calculateCarouselComparison(filteredHls, 'hls-comparison');
        calculateCarouselComparison(filteredRls, 'rls-comparison');
        calculateCarouselComparison(filteredPengeluaran, 'pengeluaran-comparison', true);
        calculateCarouselComparison(filteredIndeksKesehatan, 'indeks-kesehatan-comparison');
        calculateCarouselComparison(filteredIndeksPendidikan, 'indeks-pendidikan-comparison');
        calculateCarouselComparison(filteredIndeksHidupLayak, 'indeks-hidup-layak-comparison');
      }, 600);
    }

    // ========== Export Functions ==========
    function exportTrendToExcel() {
      if (!window.chartData || !window.chartData.surabaya || !window.chartData.jatim) return;
      
      const surabayaData = window.chartData.surabaya;
      const jatimData = window.chartData.jatim;
      const allYears = [...new Set([...surabayaData.map(d => d.year), ...jatimData.map(d => d.year)])].sort((a, b) => a - b);
      
      const exportData = [['Tahun', 'Kota Surabaya', 'Jawa Timur']];
      allYears.forEach(year => {
        const surabayaVal = surabayaData.find(d => d.year === year);
        const jatimVal = jatimData.find(d => d.year === year);
        const surabayaValue = surabayaVal && surabayaVal.ipm_value !== null && surabayaVal.ipm_value !== undefined ? Number(surabayaVal.ipm_value).toFixed(2) : 'Data tidak tersedia';
        const jatimValue = jatimVal && jatimVal.ipm_value !== null && jatimVal.ipm_value !== undefined ? Number(jatimVal.ipm_value).toFixed(2) : 'Data tidak tersedia';
        exportData.push([year.toString(), surabayaValue, jatimValue]);
      });
      
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 10 }, { wch: 25 }, { wch: 25 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Trend IPM');
      XLSX.writeFile(wb, `Trend_IPM_Surabaya_vs_JawaTimur_${new Date().toISOString().split('T')[0]}.xlsx`);
    }

    function exportSingleSeriesToExcel(data, chartName, unit = '') {
      const sortedData = [...data].sort((a, b) => a.year - b.year);
      const last5Years = sortedData.slice(-5);
      const exportData = [['Tahun', chartName + (unit ? ` (${unit})` : '')]];
      
      last5Years.forEach(item => {
        const value = item.value !== null ? item.value.toFixed(2) : 'Data tidak tersedia';
        exportData.push([item.year.toString(), value]);
      });
      
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 10 }, { wch: 25 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data');
      const filename = chartName.replace(/\s+/g, '_') + '_' + new Date().toISOString().split('T')[0] + '.xlsx';
      XLSX.writeFile(wb, filename);
    }

    function exportChartToPNG(chartInstance, filename) {
      if (!chartInstance) return;
      const url = chartInstance.getDataURL({
        type: 'png',
        pixelRatio: 2,
        backgroundColor: '#fff'
      });
      const link = document.createElement('a');
      link.download = filename;
      link.href = url;
      link.click();
    }

    function checkAuthBeforeDownload(callback, itemName = 'data') {
      @if(auth()->check())
      callback();
      return true;
      @else
      alert('Ingin mengunduh ' + itemName + ' ini? Silakan login terlebih dahulu.');
      const loginModal = document.getElementById('loginModal');
      if (loginModal) {
        const modal = new bootstrap.Modal(loginModal);
        modal.show();
      } else {
        window.location.href = '{{ route("login") }}';
      }
      return false;
      @endif
    }

    // ========== Update initCharts to include carousel comparisons ==========
    const originalInitCharts = initCharts;
    initCharts = function(uhhSpData, hlsData, rlsData, surabayaData, jatimData, pengeluaranData, indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData) {
      originalInitCharts(uhhSpData, hlsData, rlsData, surabayaData, jatimData, pengeluaranData, indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData);
      calculateAllCarouselComparisons(uhhSpData, hlsData, rlsData, pengeluaranData, indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData);
      
      // Store data globally for export
      window.chartData = {
        surabaya: surabayaData,
        jatim: jatimData,
        uhhSp: uhhSpData,
        hls: hlsData,
        rls: rlsData,
        pengeluaran: pengeluaranData,
        indeksKesehatan: indeksKesehatanData,
        indeksPendidikan: indeksPendidikanData,
        indeksHidupLayak: indeksHidupLayakData
      };
    };

    // ========== Setup Download Event Listeners ==========
    function setupDownloadListeners() {
      document.getElementById('downloadTrendExcel')?.addEventListener('click', function(e) {
        e.preventDefault();
        checkAuthBeforeDownload(() => {
          exportTrendToExcel();
        }, 'data trend IPM');
      });

      document.getElementById('downloadTrendPNG')?.addEventListener('click', function(e) {
        e.preventDefault();
        checkAuthBeforeDownload(() => {
          if (window.chartInstances && window.chartInstances.trend) {
            exportChartToPNG(window.chartInstances.trend, `Trend_IPM_Chart_${new Date().toISOString().split('T')[0]}.png`);
          }
        }, 'grafik trend IPM');
      });

      // Add more download listeners as needed
      const downloadMappings = [
        { excel: 'downloadUhhSpExcel', png: 'downloadUhhSpPNG', data: 'uhhSp', chart: 'uhhSp', name: 'UHH SP', unit: 'tahun' },
        { excel: 'downloadHlsExcel', png: 'downloadHlsPNG', data: 'hls', chart: 'hls', name: 'HLS', unit: 'tahun' },
        { excel: 'downloadRlsExcel', png: 'downloadRlsPNG', data: 'rls', chart: 'rls', name: 'RLS', unit: 'tahun' },
        { excel: 'downloadPengeluaranExcel', png: 'downloadPengeluaranPNG', data: 'pengeluaran', chart: 'pengeluaran', name: 'Pengeluaran per Kapita', unit: 'Rp' },
        { excel: 'downloadIndeksKesehatanExcel', png: 'downloadIndeksKesehatanPNG', data: 'indeksKesehatan', chart: 'indeksKesehatan', name: 'Indeks Kesehatan', unit: '' },
        { excel: 'downloadIndeksPendidikanExcel', png: 'downloadIndeksPendidikanPNG', data: 'indeksPendidikan', chart: 'indeksPendidikan', name: 'Indeks Pendidikan', unit: '' },
        { excel: 'downloadIndeksHidupLayakExcel', png: 'downloadIndeksHidupLayakPNG', data: 'indeksHidupLayak', chart: 'indeksHidupLayak', name: 'Indeks Hidup Layak', unit: '' }
      ];

      downloadMappings.forEach(mapping => {
        document.getElementById(mapping.excel)?.addEventListener('click', function(e) {
          e.preventDefault();
          if (window.chartData && window.chartData[mapping.data]) {
            checkAuthBeforeDownload(() => {
              exportSingleSeriesToExcel(window.chartData[mapping.data], mapping.name, mapping.unit);
            }, `data ${mapping.name}`);
          }
        });

        document.getElementById(mapping.png)?.addEventListener('click', function(e) {
          e.preventDefault();
          checkAuthBeforeDownload(() => {
            if (window.chartInstances && window.chartInstances[mapping.chart]) {
              exportChartToPNG(window.chartInstances[mapping.chart], `${mapping.name.replace(/\s+/g, '_')}_Chart_${new Date().toISOString().split('T')[0]}.png`);
            }
          }, `grafik ${mapping.name}`);
        });
      });

      // Composition chart download
      document.getElementById('downloadCompositionExcel')?.addEventListener('click', function(e) {
        e.preventDefault();
        checkAuthBeforeDownload(() => {
          if (window.chartData && window.chartData.composition && window.chartData.composition.length > 0) {
            const exportData = [['Indikator', 'Nilai']];
            window.chartData.composition.forEach(item => {
              exportData.push([item.name, Number(item.value).toFixed(2)]);
            });
            
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet(exportData);
            ws['!cols'] = [{ wch: 25 }, { wch: 15 }];
            XLSX.utils.book_append_sheet(wb, ws, 'Data Komposisi');
            XLSX.writeFile(wb, `Komposisi_IPM_${new Date().toISOString().split('T')[0]}.xlsx`);
          } else {
            alert('Data komposisi belum tersedia');
          }
        }, 'data komposisi IPM');
      });

      document.getElementById('downloadCompositionPNG')?.addEventListener('click', function(e) {
        e.preventDefault();
        checkAuthBeforeDownload(() => {
          if (window.chartInstances && window.chartInstances.composition) {
            exportChartToPNG(window.chartInstances.composition, `Komposisi_IPM_Chart_${new Date().toISOString().split('T')[0]}.png`);
          }
        }, 'grafik komposisi IPM');
      });
    }

    // Handle window resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(function() {
        if (window.chartInstances) {
          Object.values(window.chartInstances).forEach(chart => {
            if (chart && typeof chart.resize === 'function') {
              chart.resize();
            }
          });
        }
      }, 150);
    });

    // Initialize
    loadAllIPMData();
    setupDownloadListeners();
  });
</script>
@endpush
@endsection
