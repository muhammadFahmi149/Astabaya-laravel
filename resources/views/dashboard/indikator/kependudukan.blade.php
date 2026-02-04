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

<style>
  .dashboard-card {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
  }
  
  /* Samakan tinggi card di row yang sama */
  .row.mb-4 {
    display: flex;
    flex-wrap: wrap;
  }
  
  .row.mb-4 > .col-md-6 {
    display: flex;
    flex-direction: column;
  }
  
  .row.mb-4 > .col-md-6 > .dashboard-card {
    flex: 1;
    display: flex;
    flex-direction: column;
  }
  
  .row.mb-4 > .col-md-6 > .dashboard-card > .chart-container-mobile {
    flex: 1;
    display: flex;
    flex-direction: column;
  }
  
  .row.mb-4 > .col-md-6 > .dashboard-card > .chart-container-mobile > div {
    flex: 1;
  }
  
  .summary-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }
  
  .summary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
  }

  /* Mobile responsive: 2 baris x 2 kolom */
  @media (max-width: 767.98px) {
    .summary-card-mobile {
      flex: 0 0 50% !important;
      max-width: 50% !important;
      padding-left: 8px !important;
      padding-right: 8px !important;
    }
    
    .summary-card-mobile .summary-card {
      padding: 12px !important;
      min-height: auto !important;
      height: auto !important;
    }
    
    .summary-card-mobile .summary-card[style*="min-height: 160px"] {
      min-height: auto !important;
    }
    
    .summary-card-mobile .summary-card > div[style*="position: relative"] {
      margin: 0 !important;
      padding: 0 !important;
      flex: 0 0 auto !important;
    }
    
    .summary-card-mobile .summary-card > div[style*="flex: 1"] {
      flex: 0 0 auto !important;
    }
    
    .summary-card-mobile h6 {
      font-size: 11px !important;
      margin-bottom: 6px !important;
    }
    
    .summary-card-mobile h3 {
      font-size: 20px !important;
      margin-bottom: 4px !important;
      line-height: 1.1 !important;
    }
    
    .summary-card-mobile small {
      font-size: 10px !important;
      margin-top: 4px !important;
      margin-bottom: 0 !important;
      line-height: 1.2 !important;
    }
    
    .summary-card-mobile span[style*="font-size: 12px"] {
      font-size: 10px !important;
    }
    
    .summary-card-mobile span[style*="font-size: 11px"] {
      font-size: 9px !important;
    }
    
    .summary-card-mobile > div[style*="display: flex"][style*="align-items: center"] {
      margin-top: 4px !important;
      margin-bottom: 0 !important;
    }
    
    /* Hilangkan space kosong di bawah */
    .summary-card-mobile .summary-card {
      padding-bottom: 10px !important;
    }
    
    .summary-card-mobile .summary-card > div[style*="position: relative"] > *:last-child {
      margin-bottom: 0 !important;
      padding-bottom: 0 !important;
    }
    
    .summary-card-mobile .summary-card > div[style*="position: relative"] > small[style*="margin-top: auto"] {
      margin-top: 4px !important;
    }
    
    /* Chart responsive dengan horizontal scroll */
    .chart-container-mobile {
      width: 100%;
      max-width: 100%;
      overflow-x: auto;
      overflow-y: hidden;
      -webkit-overflow-scrolling: touch;
      position: relative;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    .chart-container-mobile > div {
      min-width: 500px !important;
      max-width: 100% !important;
      width: 100% !important;
      box-sizing: border-box;
    }
    
    .dashboard-card {
      overflow: hidden;
      box-sizing: border-box;
    }
    
    .dashboard-card h5 {
      font-size: 16px !important;
      white-space: normal !important;
      word-wrap: break-word !important;
    }
    
    /* Download button responsive */
    .dashboard-card button[id*="download"] {
      padding: 3px 8px !important;
      font-size: 11px !important;
    }
    
    .dashboard-card button[id*="download"] i {
      font-size: 10px !important;
    }
    
    .dashboard-card button[id*="download"] span {
      display: none;
    }
  }
  
  /* Download button styles */
  .dashboard-card button[id*="download"] {
    white-space: nowrap;
  }
  
  @media (max-width: 576px) {
    .dashboard-card button[id*="download"] {
      padding: 4px 6px !important;
      font-size: 10px !important;
    }
    
    .dashboard-card button[id*="download"] i {
      font-size: 12px !important;
      margin: 0 !important;
    }
  }
  
  /* Desktop: chart container normal - tidak scroll */
  @media (min-width: 768px) {
    .chart-container-mobile {
      width: 100%;
      max-width: 100%;
      overflow: visible;
      margin: 0;
      padding: 0;
    }
    
    .chart-container-mobile > div {
      min-width: auto !important;
      width: 100% !important;
    }
    
    .dashboard-card {
      overflow: visible;
    }
  }
  
  /* Pastikan container tidak overflow saat sidebar dibuka */
  .container {
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    overflow-x: hidden;
  }
  
  .dashboard-card {
    max-width: 100%;
    box-sizing: border-box;
  }
</style>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const API_BASE_URL = '/api';
    const IS_AUTHENTICATED = {{ auth()->check() ? 'true' : 'false' }};
    const LOGIN_URL = '{{ route("login") }}';
    let currentYear = null;
    let trendData = [];
    let ageDistribution = [];
    let pieChartData = [];
    let pyramidData = [];

    // Format number with thousand separators
    function formatNumber(num) {
      if (num === null || num === undefined || isNaN(num)) return '0';
      return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Format number to thousands/millions
    function formatPopulation(num) {
      if (num === null || num === undefined || isNaN(num)) return '0';
      const numValue = parseFloat(num);
      if (numValue >= 1000000) {
        return (numValue / 1000000).toFixed(2).replace(/\.?0+$/, '') + ' juta';
      } else if (numValue >= 1000) {
        return (numValue / 1000).toFixed(2).replace(/\.?0+$/, '') + ' ribu';
      }
      return numValue.toString();
    }

    // Format change values
    function formatChangeValue(value) {
      if (value === null || value === undefined || isNaN(value)) return '';
      const absValue = Math.abs(value);
      if (absValue >= 1000000) {
        return (absValue / 1000000).toFixed(2).replace(/\.?0+$/, '') + ' juta';
      } else if (absValue >= 1000) {
        return (absValue / 1000).toFixed(2).replace(/\.?0+$/, '') + ' ribu';
      }
      return absValue.toString();
    }

    // Update summary cards
    function updateSummaryCards(summary) {
      // Total Population
      const totalPopEl = document.querySelector('.total-population-value');
      if (totalPopEl && summary.total_population !== null) {
        totalPopEl.textContent = formatPopulation(summary.total_population);
      } else if (totalPopEl) {
        totalPopEl.textContent = '-';
      }

      // Total Male
      const totalMaleEl = document.querySelector('.total-male-value');
      if (totalMaleEl && summary.total_male !== null) {
        totalMaleEl.textContent = formatPopulation(summary.total_male);
      } else if (totalMaleEl) {
        totalMaleEl.textContent = '-';
      }

      // Total Female
      const totalFemaleEl = document.querySelector('.total-female-value');
      if (totalFemaleEl && summary.total_female !== null) {
        totalFemaleEl.textContent = formatPopulation(summary.total_female);
      } else if (totalFemaleEl) {
        totalFemaleEl.textContent = '-';
      }

      // Population Ratio
      const ratioEl = document.querySelector('.population-ratio-value');
      if (ratioEl) {
        if (summary.population_ratio_display) {
          ratioEl.textContent = summary.population_ratio_display;
        } else {
          ratioEl.textContent = '-';
        }
      }

      // Update change indicators
      updateChangeIndicator('.total-population-change', summary.total_change, summary.previous_year);
      updateChangeIndicator('.total-male-change', summary.male_change, summary.previous_year);
      updateChangeIndicator('.total-female-change', summary.female_change, summary.previous_year);

      // Update previous ratio
      const prevRatioEl = document.querySelector('.prev-ratio-text');
      if (prevRatioEl && summary.prev_population_ratio_display && summary.previous_year) {
        prevRatioEl.textContent = `dari ${summary.previous_year}: ${summary.prev_population_ratio_display}`;
        prevRatioEl.style.display = 'inline';
      } else if (prevRatioEl) {
        prevRatioEl.style.display = 'none';
      }

      // Update year text
      const yearTexts = document.querySelectorAll('.year-text');
      yearTexts.forEach(el => {
        if (summary.selected_year) {
          el.textContent = `Tahun ${summary.selected_year}`;
        } else {
          el.textContent = 'Data tidak tersedia';
        }
      });
    }

    function updateChangeIndicator(selector, change, previousYear) {
      const container = document.querySelector(selector);
      if (!container) return;

      const indicator = container.querySelector('.change-indicator');
      const valueEl = container.querySelector('.change-value');
      const prevYearEl = container.parentElement.querySelector('.previous-year-text');

      if (change !== null && change !== undefined) {
        container.style.display = 'flex';
        container.style.alignItems = 'center';
        container.style.gap = '5px';
        container.style.color = 'rgba(255, 255, 255, 0.9)';
        container.style.fontSize = '12px';

        if (change > 0) {
          if (indicator) indicator.textContent = '▲';
        } else if (change < 0) {
          if (indicator) indicator.textContent = '▼';
        } else {
          if (indicator) indicator.textContent = '-';
        }

        const prefix = change > 0 ? '+' : '';
        if (valueEl) valueEl.textContent = prefix + formatChangeValue(change);

        if (prevYearEl && previousYear) {
          prevYearEl.textContent = `dari ${previousYear}`;
          prevYearEl.style.display = 'inline';
        }
      } else {
        container.style.display = 'none';
        if (prevYearEl) prevYearEl.style.display = 'none';
      }
    }

    // Load years for selector
    async function loadYears() {
      try {
        const response = await fetch(`${API_BASE_URL}/kependudukan-years`);
        const result = await response.json();
        
        if (result.success && result.data) {
          const yearSelector = document.getElementById('yearSelector');
          if (yearSelector) {
            yearSelector.innerHTML = '';
            result.data.forEach(year => {
              const option = document.createElement('option');
              option.value = year;
              option.textContent = year;
              if (year === result.data[0]) {
                option.selected = true;
                currentYear = year;
              }
              yearSelector.appendChild(option);
            });
          }
        }
      } catch (error) {
        console.error('Error loading years:', error);
      }
    }

    // Load summary data
    async function loadSummary(year) {
      try {
        const url = year ? `${API_BASE_URL}/kependudukan-summary?year=${year}` : `${API_BASE_URL}/kependudukan-summary`;
        const response = await fetch(url);
        const result = await response.json();
        
        if (result.success && result.data) {
          updateSummaryCards(result.data);
          currentYear = result.data.selected_year;
        }
      } catch (error) {
        console.error('Error loading summary:', error);
      }
    }

    // Load trend data
    async function loadTrend() {
      try {
        const response = await fetch(`${API_BASE_URL}/kependudukan-trend`);
        const result = await response.json();
        
        if (result.success && result.data) {
          trendData = result.data;
          renderTrendChart();
        }
      } catch (error) {
        console.error('Error loading trend:', error);
      }
    }

    // Load distribution data
    async function loadDistribution(year) {
      try {
        const url = year ? `${API_BASE_URL}/kependudukan-distribution?year=${year}` : `${API_BASE_URL}/kependudukan-distribution`;
        const response = await fetch(url);
        const result = await response.json();
        
        if (result.success && result.data) {
          ageDistribution = result.data;
          renderDistributionChart();
        }
      } catch (error) {
        console.error('Error loading distribution:', error);
      }
    }

    // Load pie chart data
    async function loadPieChart(year) {
      try {
        const url = year ? `${API_BASE_URL}/kependudukan-piechart?year=${year}` : `${API_BASE_URL}/kependudukan-piechart`;
        const response = await fetch(url);
        const result = await response.json();
        
        if (result.success && result.data) {
          pieChartData = result.data;
          renderPieChart();
        }
      } catch (error) {
        console.error('Error loading pie chart:', error);
      }
    }

    // Load pyramid data
    async function loadPyramid(year) {
      try {
        const url = year ? `${API_BASE_URL}/kependudukan-pyramid?year=${year}` : `${API_BASE_URL}/kependudukan-pyramid`;
        const response = await fetch(url);
        const result = await response.json();
        
        if (result.success && result.data) {
          pyramidData = result.data;
          renderPyramidChart();
          renderGenderComparisonChart();
        }
      } catch (error) {
        console.error('Error loading pyramid:', error);
      }
    }

    // Check if mobile
    const isMobile = window.innerWidth <= 767.98;

    // Chart 1: Trend Chart
    function renderTrendChart() {
      const trendChartDom = document.getElementById('trendChart');
      if (!trendChartDom || !trendData || trendData.length === 0) {
        if (trendChartDom) {
          trendChartDom.innerHTML = '<div style="text-align: center; padding: 50px; color: #999;">Data tidak tersedia. Silakan sinkronisasi data terlebih dahulu.</div>';
        }
        return;
      }

      const trendChart = echarts.init(trendChartDom);
      const trendYears = trendData.map(d => d.year.toString());
      const trendTotal = trendData.map(d => d.total);
      const trendMale = trendData.map(d => d.male);
      const trendFemale = trendData.map(d => d.female);

      trendChart.setOption({
        tooltip: {
          trigger: 'axis',
          axisPointer: { type: 'cross' },
          formatter: function(params) {
            let result = 'Tahun: ' + params[0].axisValue + '<br/>';
            params.forEach(function(item) {
              result += item.marker + item.seriesName + ': ' + formatNumber(item.value) + '<br/>';
            });
            return result;
          }
        },
        legend: {
          data: ['Total', 'Laki-laki (LK)', 'Perempuan (PR)'],
          top: isMobile ? 5 : 10,
          left: 'center',
          orient: 'horizontal',
          itemGap: isMobile ? 15 : 20,
          itemWidth: isMobile ? 10 : 12,
          itemHeight: isMobile ? 10 : 12,
          textStyle: {
            fontSize: isMobile ? 10 : 12
          }
        },
        grid: {
          left: isMobile ? '15%' : '12%',
          right: '4%',
          bottom: '10%',
          top: isMobile ? '18%' : '15%',
          containLabel: false
        },
        xAxis: {
          type: 'category',
          data: trendYears,
          axisLabel: {
            fontSize: isMobile ? 9 : 11
          }
        },
        yAxis: {
          type: 'value',
          name: 'Jumlah Penduduk',
          nameLocation: 'end',
          nameGap: isMobile ? 8 : 10,
          nameTextStyle: {
            fontSize: isMobile ? 10 : 12
          },
          axisLabel: {
            formatter: function(value) {
              return formatNumber(value);
            },
            fontSize: isMobile ? 9 : 11
          }
        },
        series: [
          {
            name: 'Total',
            type: 'line',
            data: trendTotal,
            itemStyle: { color: '#3b82f6' },
            lineStyle: { width: 3 },
            symbol: 'circle',
            symbolSize: 8
          },
          {
            name: 'Laki-laki (LK)',
            type: 'line',
            data: trendMale,
            itemStyle: { color: '#10b981' },
            lineStyle: { width: 3 },
            symbol: 'circle',
            symbolSize: 8
          },
          {
            name: 'Perempuan (PR)',
            type: 'line',
            data: trendFemale,
            itemStyle: { color: '#f59e0b' },
            lineStyle: { width: 3 },
            symbol: 'circle',
            symbolSize: 8
          }
        ]
      });
    }

    // Chart 2: Distribution Chart
    function renderDistributionChart() {
      const distributionChartDom = document.getElementById('distributionChart');
      if (!distributionChartDom || !ageDistribution || ageDistribution.length === 0) {
        if (distributionChartDom) {
          distributionChartDom.innerHTML = '<div style="text-align: center; padding: 50px; color: #999;">Data tidak tersedia. Silakan sinkronisasi data terlebih dahulu.</div>';
        }
        return;
      }

      const distributionChart = echarts.init(distributionChartDom);
      const ageGroups = ageDistribution.map(d => d.age_group);
      const populations = ageDistribution.map(d => d.population);

      distributionChart.setOption({
        tooltip: {
          trigger: 'axis',
          axisPointer: { type: 'shadow' },
          formatter: function(params) {
            return params[0].name + '<br/>' +
                   params[0].marker + 'Jumlah: ' + formatNumber(params[0].value);
          }
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
          data: ageGroups,
          axisLabel: {
            rotate: 45,
            interval: 0,
            fontSize: isMobile ? 9 : 11
          }
        },
        yAxis: {
          type: 'value',
          name: 'Jumlah Penduduk',
          nameLocation: 'end',
          nameGap: isMobile ? 8 : 10,
          nameTextStyle: {
            fontSize: isMobile ? 10 : 12
          },
          axisLabel: {
            formatter: function(value) {
              return formatNumber(value);
            },
            fontSize: isMobile ? 9 : 11
          }
        },
        series: [{
          name: 'Jumlah Penduduk',
          type: 'bar',
          data: populations,
          itemStyle: {
            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
              { offset: 0, color: '#3b82f6' },
              { offset: 1, color: '#60a5fa' }
            ])
          }
        }]
      });
    }

    // Chart 3: Pie Chart
    function renderPieChart() {
      const pieChartDom = document.getElementById('pieChart');
      if (!pieChartDom || !pieChartData || pieChartData.length === 0) {
        if (pieChartDom) {
          pieChartDom.innerHTML = '<div style="text-align: center; padding: 50px; color: #999;">Data tidak tersedia. Silakan sinkronisasi data terlebih dahulu.</div>';
        }
        return;
      }

      const pieChart = echarts.init(pieChartDom);
      
      // Function to extract age range from age group string (e.g., "0-4" -> [0, 4])
      function parseAgeGroup(ageGroup) {
        if (!ageGroup) return null;
        // Handle formats like "0-4", "5-9", "75+", etc.
        if (ageGroup.includes('+')) {
          const start = parseInt(ageGroup.replace('+', ''));
          return { start: start, end: 999 }; // 999 as max age
        }
        const parts = ageGroup.split('-');
        if (parts.length === 2) {
          return { start: parseInt(parts[0]), end: parseInt(parts[1]) };
        }
        return null;
      }
      
      // Function to categorize age group
      function categorizeAge(ageGroup) {
        const ageRange = parseAgeGroup(ageGroup);
        if (!ageRange) return null;
        
        const { start, end } = ageRange;
        
        // Bayi-balita (0-5 tahun): includes groups that fall within 0-5
        if (start >= 0 && start <= 5) {
          return 'Bayi-balita (0-5 tahun)';
        }
        // Anak-anak (5-11 tahun): includes groups that start at 5 or later but within 5-11
        if (start >= 5 && start <= 11) {
          return 'Anak-anak (5-11 tahun)';
        }
        // Remaja (12-25 tahun): includes groups that start at 12 or later but within 12-25
        if (start >= 12 && start <= 25) {
          return 'Remaja (12-25 tahun)';
        }
        // Dewasa (26-45 tahun): includes groups that start at 26 or later but within 26-45
        if (start >= 26 && start <= 45) {
          return 'Dewasa (26-45 tahun)';
        }
        // Lansia (46 tahun ke atas): includes groups that start at 46 or later
        if (start >= 46) {
          return 'Lansia (46+ tahun)';
        }
        
        return null;
      }
      
      // Group data by category
      const groupedData = {};
      pieChartData.forEach(d => {
        const category = categorizeAge(d.name);
        if (category) {
          if (!groupedData[category]) {
            groupedData[category] = 0;
          }
          groupedData[category] += d.value;
        }
      });
      
      // Convert to array format for ECharts
      const pieData = Object.keys(groupedData).map(category => ({
        name: category,
        value: groupedData[category]
      })).sort((a, b) => {
        // Sort by category order
        const order = {
          'Bayi-balita (0-5 tahun)': 1,
          'Anak-anak (5-11 tahun)': 2,
          'Remaja (12-25 tahun)': 3,
          'Dewasa (26-45 tahun)': 4,
          'Lansia (46+ tahun)': 5
        };
        return (order[a.name] || 999) - (order[b.name] || 999);
      });

      pieChart.setOption({
        tooltip: {
          trigger: 'item',
          formatter: function(params) {
            return params.name + '<br/>' + 
                   formatNumber(params.value) + ' (' + params.percent + '%)';
          }
        },
        legend: {
          show: true,
          orient: 'horizontal',
          top: isMobile ? 5 : 10,
          left: 'center',
          itemGap: isMobile ? 12 : 15,
          itemWidth: isMobile ? 10 : 12,
          itemHeight: isMobile ? 10 : 12,
          textStyle: {
            fontSize: isMobile ? 10 : 11,
            fontWeight: 'normal'
          },
          formatter: function(name) {
            return name;
          }
        },
        series: [{
          name: 'Proporsi Penduduk',
          type: 'pie',
          radius: ['30%', '65%'],
          center: ['50%', '55%'],
          avoidLabelOverlap: false,
          itemStyle: {
            borderRadius: 8,
            borderColor: '#fff',
            borderWidth: 2
          },
          label: {
            show: false
          },
          labelLine: {
            show: false
          },
          emphasis: {
            itemStyle: {
              shadowBlur: 10,
              shadowOffsetX: 0,
              shadowColor: 'rgba(0, 0, 0, 0.5)'
            },
            label: {
              show: false
            }
          },
          data: pieData
        }]
      });
    }

    // Chart 4: Pyramid Chart
    function renderPyramidChart() {
      const pyramidChartDom = document.getElementById('pyramidChart');
      if (!pyramidChartDom || !pyramidData || pyramidData.length === 0) {
        if (pyramidChartDom) {
          pyramidChartDom.innerHTML = '<div style="text-align: center; padding: 50px; color: #999;">Data tidak tersedia. Silakan sinkronisasi data terlebih dahulu.</div>';
        }
        return;
      }

      const pyramidChart = echarts.init(pyramidChartDom);
      const pyramidAgeGroups = pyramidData.map(d => d.age_group);
      const pyramidMale = pyramidData.map(d => -d.male);
      const pyramidFemale = pyramidData.map(d => d.female);

      pyramidChart.setOption({
        tooltip: {
          trigger: 'axis',
          axisPointer: { type: 'shadow' },
          formatter: function(params) {
            let result = params[0].name + '<br/>';
            params.forEach(function(item) {
              if (item.seriesName === 'Laki-laki (LK)') {
                result += item.marker + item.seriesName + ': ' + formatNumber(Math.abs(item.value)) + '<br/>';
              } else {
                result += item.marker + item.seriesName + ': ' + formatNumber(item.value) + '<br/>';
              }
            });
            return result;
          }
        },
        legend: {
          data: ['Laki-laki (LK)', 'Perempuan (PR)'],
          top: isMobile ? 5 : 10,
          left: 'center',
          orient: 'horizontal',
          itemGap: isMobile ? 15 : 20,
          itemWidth: isMobile ? 10 : 12,
          itemHeight: isMobile ? 10 : 12,
          textStyle: {
            fontSize: isMobile ? 10 : 12
          }
        },
        grid: {
          left: isMobile ? '18%' : '15%',
          right: isMobile ? '18%' : '15%',
          bottom: '10%',
          top: isMobile ? '18%' : '10%',
          containLabel: false
        },
        xAxis: [{
          type: 'value',
          position: 'bottom',
          name: 'Jumlah Penduduk',
          nameLocation: 'middle',
          nameGap: isMobile ? 25 : 30,
          nameTextStyle: {
            fontSize: isMobile ? 10 : 12
          },
          axisLabel: {
            formatter: function(value) {
              return formatNumber(Math.abs(value));
            },
            fontSize: isMobile ? 9 : 11
          }
        }],
        yAxis: {
          type: 'category',
          data: pyramidAgeGroups,
          axisLabel: {
            interval: 0,
            fontSize: isMobile ? 9 : 11
          }
        },
        series: [
          {
            name: 'Laki-laki (LK)',
            type: 'bar',
            data: pyramidMale,
            itemStyle: { color: '#3b82f6' }
          },
          {
            name: 'Perempuan (PR)',
            type: 'bar',
            data: pyramidFemale,
            itemStyle: { color: '#f59e0b' }
          }
        ]
      });
    }

    // Chart 5: Gender Comparison Chart
    function renderGenderComparisonChart() {
      const genderComparisonChartDom = document.getElementById('genderComparisonChart');
      if (!genderComparisonChartDom || !pyramidData || pyramidData.length === 0) {
        if (genderComparisonChartDom) {
          genderComparisonChartDom.innerHTML = '<div style="text-align: center; padding: 50px; color: #999;">Data tidak tersedia. Silakan sinkronisasi data terlebih dahulu.</div>';
        }
        return;
      }

      const genderComparisonChart = echarts.init(genderComparisonChartDom);
      const comparisonAgeGroups = pyramidData.map(d => d.age_group);
      const comparisonMale = pyramidData.map(d => d.male);
      const comparisonFemale = pyramidData.map(d => d.female);

      genderComparisonChart.setOption({
        tooltip: {
          trigger: 'axis',
          axisPointer: { type: 'shadow' },
          formatter: function(params) {
            let result = params[0].name + '<br/>';
            params.forEach(function(item) {
              result += item.marker + item.seriesName + ': ' + formatNumber(item.value) + '<br/>';
            });
            return result;
          }
        },
        legend: {
          data: ['Laki-laki (LK)', 'Perempuan (PR)'],
          top: isMobile ? 5 : 10,
          left: 'center',
          orient: 'horizontal',
          itemGap: isMobile ? 15 : 20,
          itemWidth: isMobile ? 10 : 12,
          itemHeight: isMobile ? 10 : 12,
          textStyle: {
            fontSize: isMobile ? 10 : 12
          }
        },
        grid: {
          left: isMobile ? '15%' : '12%',
          right: '4%',
          bottom: '15%',
          top: isMobile ? '18%' : '15%',
          containLabel: false
        },
        xAxis: {
          type: 'category',
          data: comparisonAgeGroups,
          axisLabel: {
            rotate: 45,
            interval: 0,
            fontSize: isMobile ? 9 : 11
          }
        },
        yAxis: {
          type: 'value',
          name: 'Jumlah Penduduk',
          nameLocation: 'end',
          nameGap: isMobile ? 8 : 10,
          nameTextStyle: {
            fontSize: isMobile ? 10 : 12
          },
          axisLabel: {
            formatter: function(value) {
              return formatNumber(value);
            },
            fontSize: isMobile ? 9 : 11
          }
        },
        series: [
          {
            name: 'Laki-laki (LK)',
            type: 'bar',
            data: comparisonMale,
            itemStyle: { color: '#3b82f6' }
          },
          {
            name: 'Perempuan (PR)',
            type: 'bar',
            data: comparisonFemale,
            itemStyle: { color: '#f59e0b' }
          }
        ]
      });
    }

    // Function to resize all charts
    function resizeAllCharts() {
      const charts = ['trendChart', 'distributionChart', 'pieChart', 'pyramidChart', 'genderComparisonChart'];
      charts.forEach(chartId => {
        const chartDom = document.getElementById(chartId);
        if (chartDom) {
          const chart = echarts.getInstanceByDom(chartDom);
          if (chart) {
            setTimeout(() => {
              chart.resize();
            }, 100);
          }
        }
      });
    }

    // Handle window resize
    window.addEventListener('resize', resizeAllCharts);

    // Handle sidebar toggle
    const sidebarToggle = document.querySelector('#sidebarToggle, #check, [data-toggle="sidebar"], .sidebar-toggle');
    if (sidebarToggle) {
      sidebarToggle.addEventListener('change', resizeAllCharts);
      sidebarToggle.addEventListener('click', function() {
        setTimeout(resizeAllCharts, 300);
      });
    }

    // Observe sidebar changes
    const sidebar = document.querySelector('.sidebar, #sidebar, .side-menu');
    if (sidebar) {
      const observer = new MutationObserver(function(mutations) {
        resizeAllCharts();
      });
      observer.observe(sidebar, {
        attributes: true,
        attributeFilter: ['class', 'style']
      });
    }

    // Year selector handler
    const yearSelector = document.getElementById('yearSelector');
    if (yearSelector) {
      yearSelector.addEventListener('change', async function() {
        const selectedYear = this.value;
        currentYear = selectedYear;
        await Promise.all([
          loadSummary(selectedYear),
          loadDistribution(selectedYear),
          loadPieChart(selectedYear),
          loadPyramid(selectedYear)
        ]);
      });
    }

    // Export functions
    function exportTrendToExcel() {
      if (!trendData || trendData.length === 0) return;
      const exportData = [];
      exportData.push(['Tahun', 'Total', 'Laki-laki (LK)', 'Perempuan (PR)']);
      trendData.forEach(data => {
        exportData.push([
          data.year.toString(),
          formatNumber(data.total),
          formatNumber(data.male),
          formatNumber(data.female)
        ]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 10 }, { wch: 20 }, { wch: 20 }, { wch: 20 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Trend');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Kependudukan_Trend_${today}.xlsx`);
    }

    function exportTrendToPNG() {
      const chart = echarts.getInstanceByDom(document.getElementById('trendChart'));
      if (chart) {
        const url = chart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
        const link = document.createElement('a');
        link.download = `Kependudukan_Trend_${new Date().toISOString().split('T')[0]}.png`;
        link.href = url;
        link.click();
      }
    }

    function exportDistributionToExcel() {
      if (!ageDistribution || ageDistribution.length === 0) return;
      const exportData = [];
      exportData.push(['Kelompok Umur', 'Jumlah Penduduk']);
      ageDistribution.forEach(data => {
        exportData.push([data.age_group, formatNumber(data.population)]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 25 }, { wch: 20 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Distribusi');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Kependudukan_Distribusi_${today}.xlsx`);
    }

    function exportDistributionToPNG() {
      const chart = echarts.getInstanceByDom(document.getElementById('distributionChart'));
      if (chart) {
        const url = chart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
        const link = document.createElement('a');
        link.download = `Kependudukan_Distribusi_${new Date().toISOString().split('T')[0]}.png`;
        link.href = url;
        link.click();
      }
    }

    function exportPieToExcel() {
      if (!pieChartData || pieChartData.length === 0) return;
      const exportData = [];
      exportData.push(['Kelompok Umur', 'Jumlah Penduduk', 'Persentase (%)']);
      const total = pieChartData.reduce((sum, d) => sum + d.value, 0);
      pieChartData.forEach(data => {
        const percentage = total > 0 ? ((data.value / total) * 100).toFixed(2) : '0';
        exportData.push([data.name, formatNumber(data.value), percentage]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 25 }, { wch: 20 }, { wch: 15 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Proporsi');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Kependudukan_Proporsi_${today}.xlsx`);
    }

    function exportPieToPNG() {
      const chart = echarts.getInstanceByDom(document.getElementById('pieChart'));
      if (chart) {
        const url = chart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
        const link = document.createElement('a');
        link.download = `Kependudukan_Proporsi_${new Date().toISOString().split('T')[0]}.png`;
        link.href = url;
        link.click();
      }
    }

    function exportGenderComparisonToExcel() {
      if (!pyramidData || pyramidData.length === 0) return;
      const exportData = [];
      exportData.push(['Kelompok Umur', 'Laki-laki (LK)', 'Perempuan (PR)']);
      pyramidData.forEach(data => {
        exportData.push([
          data.age_group,
          formatNumber(data.male),
          formatNumber(data.female)
        ]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 25 }, { wch: 20 }, { wch: 20 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Perbandingan Gender');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Kependudukan_Perbandingan_Gender_${today}.xlsx`);
    }

    function exportGenderComparisonToPNG() {
      const chart = echarts.getInstanceByDom(document.getElementById('genderComparisonChart'));
      if (chart) {
        const url = chart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
        const link = document.createElement('a');
        link.download = `Kependudukan_Perbandingan_Gender_${new Date().toISOString().split('T')[0]}.png`;
        link.href = url;
        link.click();
      }
    }

    function exportPyramidToExcel() {
      if (!pyramidData || pyramidData.length === 0) return;
      const exportData = [];
      exportData.push(['Kelompok Umur', 'Laki-laki (LK)', 'Perempuan (PR)']);
      pyramidData.forEach(data => {
        exportData.push([
          data.age_group,
          formatNumber(data.male),
          formatNumber(data.female)
        ]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 25 }, { wch: 20 }, { wch: 20 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Piramida');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Kependudukan_Piramida_${today}.xlsx`);
    }

    function exportPyramidToPNG() {
      const chart = echarts.getInstanceByDom(document.getElementById('pyramidChart'));
      if (chart) {
        const url = chart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
        const link = document.createElement('a');
        link.download = `Kependudukan_Piramida_${new Date().toISOString().split('T')[0]}.png`;
        link.href = url;
        link.click();
      }
    }

    // Helper function to check authentication before download
    function checkAuthBeforeDownload(callback, itemName = 'data') {
      if (IS_AUTHENTICATED) {
        callback();
        return true;
      } else {
        alert('Ingin mengunduh ' + itemName + ' ini? Silakan login terlebih dahulu.');
        const loginModal = document.getElementById('loginModal');
        if (loginModal) {
          const modal = new bootstrap.Modal(loginModal);
          modal.show();
        } else {
          window.location.href = LOGIN_URL;
        }
        return false;
      }
    }

    // Event listeners for download buttons
    document.getElementById('downloadTrendExcel')?.addEventListener('click', function(e) {
      e.preventDefault();
      checkAuthBeforeDownload(exportTrendToExcel, 'data trend kependudukan');
    });
    document.getElementById('downloadTrendPNG')?.addEventListener('click', function(e) {
      e.preventDefault();
      checkAuthBeforeDownload(exportTrendToPNG, 'grafik trend kependudukan');
    });
    document.getElementById('downloadDistributionExcel')?.addEventListener('click', function(e) {
      e.preventDefault();
      checkAuthBeforeDownload(exportDistributionToExcel, 'data distribusi kependudukan');
    });
    document.getElementById('downloadDistributionPNG')?.addEventListener('click', function(e) {
      e.preventDefault();
      checkAuthBeforeDownload(exportDistributionToPNG, 'grafik distribusi kependudukan');
    });
    document.getElementById('downloadPieExcel')?.addEventListener('click', function(e) {
      e.preventDefault();
      checkAuthBeforeDownload(exportPieToExcel, 'data proporsi kependudukan');
    });
    document.getElementById('downloadPiePNG')?.addEventListener('click', function(e) {
      e.preventDefault();
      checkAuthBeforeDownload(exportPieToPNG, 'grafik proporsi kependudukan');
    });
    document.getElementById('downloadGenderComparisonExcel')?.addEventListener('click', function(e) {
      e.preventDefault();
      checkAuthBeforeDownload(exportGenderComparisonToExcel, 'data perbandingan gender kependudukan');
    });
    document.getElementById('downloadGenderComparisonPNG')?.addEventListener('click', function(e) {
      e.preventDefault();
      checkAuthBeforeDownload(exportGenderComparisonToPNG, 'grafik perbandingan gender kependudukan');
    });
    document.getElementById('downloadPyramidExcel')?.addEventListener('click', function(e) {
      e.preventDefault();
      checkAuthBeforeDownload(exportPyramidToExcel, 'data piramida kependudukan');
    });
    document.getElementById('downloadPyramidPNG')?.addEventListener('click', function(e) {
      e.preventDefault();
      checkAuthBeforeDownload(exportPyramidToPNG, 'grafik piramida kependudukan');
    });

    // Initialize: Load all data
    async function initialize() {
      await loadYears();
      if (currentYear) {
        await Promise.all([
          loadSummary(currentYear),
          loadTrend(),
          loadDistribution(currentYear),
          loadPieChart(currentYear),
          loadPyramid(currentYear)
        ]);
      }
      
      // Resize charts after layout is complete
      setTimeout(() => {
        resizeAllCharts();
      }, 500);
    }

    initialize();
  });
</script>

@endsection
