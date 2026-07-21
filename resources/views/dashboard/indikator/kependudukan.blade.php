@extends('layouts.main')

@section('title', 'Kependudukan - Aastabaya')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
@endpush

@section('content')
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="font-weight-bold mb-0">Kependudukan</h3>
    <select id="yearSelector" class="form-control" style="width: auto; max-width: 200px;">
      <option value="">Loading...</option>
    </select>
  </div>
  
  <!-- Summary Cards -->
  <div class="row mb-4">
    <!-- Total Penduduk -->
    <div class="col-6 col-md-4 col-lg-3 mb-3 summary-card-mobile">
      <div class="summary-card" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-radius: 12px; padding: 20px; min-height: 160px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); display: flex; flex-direction: column;">
        <div style="position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column;">
          <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">Total Penduduk</h6>
          <h3 style="font-size: 28px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;">
            <span class="total-population-value">-</span>
          </h3>
          <div style="display: flex; align-items: center; gap: 5px; margin-top: 8px;">
            <span class="total-population-change" style="display: none;">
              <span class="change-indicator"></span>
              <span class="change-value"></span>
            </span>
            <span class="previous-year-text" style="color: rgba(255, 255, 255, 0.8); font-size: 11px; display: none;"></span>
          </div>
          <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: auto;" class="year-text">
            Loading...
          </small>
        </div>
      </div>
    </div>

    <!-- Total Laki-laki -->
    <div class="col-6 col-md-4 col-lg-3 mb-3 summary-card-mobile">
      <div class="summary-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 12px; padding: 20px; min-height: 160px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); display: flex; flex-direction: column;">
        <div style="position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column;">
          <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">Total Laki-laki</h6>
          <h3 style="font-size: 28px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;">
            <span class="total-male-value">-</span>
          </h3>
          <div style="display: flex; align-items: center; gap: 5px; margin-top: 8px;">
            <span class="total-male-change" style="display: none;">
              <span class="change-indicator"></span>
              <span class="change-value"></span>
            </span>
            <span class="previous-year-text" style="color: rgba(255, 255, 255, 0.8); font-size: 11px; display: none;"></span>
          </div>
          <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: auto;" class="year-text">
            Loading...
          </small>
        </div>
      </div>
    </div>

    <!-- Total Perempuan -->
    <div class="col-6 col-md-4 col-lg-3 mb-3 summary-card-mobile">
      <div class="summary-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border-radius: 12px; padding: 20px; min-height: 160px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); display: flex; flex-direction: column;">
        <div style="position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column;">
          <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">Total Perempuan</h6>
          <h3 style="font-size: 28px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;">
            <span class="total-female-value">-</span>
          </h3>
          <div style="display: flex; align-items: center; gap: 5px; margin-top: 8px;">
            <span class="total-female-change" style="display: none;">
              <span class="change-indicator"></span>
              <span class="change-value"></span>
            </span>
            <span class="previous-year-text" style="color: rgba(255, 255, 255, 0.8); font-size: 11px; display: none;"></span>
          </div>
          <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: auto;" class="year-text">
            Loading...
          </small>
        </div>
      </div>
    </div>

    <!-- Rasio Penduduk -->
    <div class="col-6 col-md-4 col-lg-3 mb-3 summary-card-mobile">
      <div class="summary-card" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border-radius: 12px; padding: 20px; min-height: 160px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); display: flex; flex-direction: column;">
        <div style="position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column;">
          <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">Rasio Jenis Kelamin</h6>
          <h3 style="font-size: 28px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;">
            <span class="population-ratio-value">-</span>
          </h3>
          <div style="display: flex; align-items: center; gap: 5px; margin-top: 8px;">
            <span class="prev-ratio-text" style="color: rgba(255, 255, 255, 0.8); font-size: 11px; display: none;"></span>
          </div>
          <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: auto;" class="year-text">
            Loading...
          </small>
        </div>
      </div>
    </div>
  </div>

  <!-- Trend Chart: Tren Penduduk 5 Tahun Terakhir -->
  <div class="row mb-4">
    <div class="col-md-12">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">Tren Penduduk per Tahun (5 Tahun Terakhir)</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="trendChart" title="Tren Penduduk per Tahun" />
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
        <div class="chart-container-mobile">
        <div id="trendChart" style="width: 100%; height: 400px;"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Row 1: Distribution and Pie Chart -->
  <div class="row mb-4">
    <!-- Kolom 1.1: Distribusi Penduduk Berdasarkan Umur -->
    <div class="col-md-6 mb-3">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">Distribusi Penduduk Berdasarkan Umur</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="distributionChart" title="Distribusi Penduduk Berdasarkan Umur" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadDistributionDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadDistributionDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadDistributionExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadDistributionPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="chart-container-mobile">
          <div id="distributionChart" style="width: 100%; height: 100%; min-height: 400px;"></div>
        </div>
      </div>
    </div>

    <!-- Kolom 1.2: Proporsi Penduduk per Kelompok Umur -->
    <div class="col-md-6 mb-3">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">Proporsi Penduduk per Kelompok Umur</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="pieChart" title="Proporsi Penduduk per Kelompok Umur" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadPieDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadPieDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadPieExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadPiePNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="chart-container-mobile">
          <div id="pieChart" style="width: 100%; height: 100%; min-height: 400px;"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Additional Section: Gender Comparison by Age Group -->
  <div class="row mb-4">
    <div class="col-md-12">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">Perbandingan Laki-laki dan Perempuan per Kelompok Umur</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="genderComparisonChart" title="Perbandingan Laki-laki dan Perempuan per Kelompok Umur" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadGenderComparisonDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadGenderComparisonDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadGenderComparisonExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadGenderComparisonPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="chart-container-mobile">
        <div id="genderComparisonChart" style="width: 100%; height: 400px;"></div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Population Pyramid -->
  <div class="row mb-4">
    <div class="col-md-12">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">Piramida Penduduk (LK vs PR)</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="populationPyramid" title="Piramida Penduduk" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadPyramidDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadPyramidDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadPyramidExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadPyramidPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="chart-container-mobile">
        <div id="pyramidChart" style="width: 100%; height: 500px;"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Additional Info Card -->
  <div class="row">
    <div class="col-md-12">
      <div class="dashboard-card" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
        <h5 class="mb-3"><i class="fas fa-info-circle"></i> Tentang Kependudukan</h5>
        <p style="margin-bottom: 0; line-height: 1.8;">
          <strong>Kependudukan</strong> adalah studi tentang jumlah, komposisi, dan distribusi penduduk dalam suatu wilayah. 
          Data kependudukan mencakup berbagai aspek penting untuk perencanaan pembangunan:
        </p>
        <ul style="margin-top: 12px; margin-bottom: 0; line-height: 1.8;">
          <li><strong>Total Penduduk</strong>: Jumlah keseluruhan penduduk yang tinggal di suatu wilayah pada waktu tertentu</li>
          <li><strong>Komposisi Jenis Kelamin</strong>: Distribusi penduduk berdasarkan jenis kelamin (laki-laki dan perempuan)</li>
          <li><strong>Rasio Jenis Kelamin</strong>: Perbandingan jumlah penduduk laki-laki terhadap perempuan (biasanya dinyatakan per 100 perempuan)</li>
          <li><strong>Struktur Umur</strong>: Distribusi penduduk berdasarkan kelompok umur, yang dapat menggambarkan struktur piramida penduduk</li>
        </ul>
        <p style="margin-top: 12px; margin-bottom: 16px; line-height: 1.8;">
          Piramida penduduk menunjukkan struktur umur dan jenis kelamin penduduk, yang dapat mengindikasikan apakah suatu wilayah memiliki struktur penduduk muda (ekspansif), menua (konstriktif), atau stabil (stasioner). 
          Data kependudukan sangat penting untuk perencanaan pembangunan, alokasi sumber daya, dan kebijakan sosial.
        </p>
      </div>
    </div>
  </div>

</div>






@push('scripts')
@vite(['resources/css/dashboard/kependudukan.css', 'resources/js/dashboard/kependudukan.js'])
@endpush

@endsection
