@extends('layouts.main')

@section('title', 'Kemiskinan')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
@endpush

@section('content')
<div class="container py-4">
  <h3 class="font-weight-bold mb-4">Kemiskinan</h3>
  
  <!-- Summary Cards -->
  <div class="row mb-4 summary-cards-row">
    <!-- Jumlah Penduduk Miskin -->
    <div class="col-6 col-md-4 col-lg mb-3 summary-card-mobile">
      <div class="summary-card" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-radius: 12px; padding: 20px; min-height: 160px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); display: flex; flex-direction: column;">
        <div style="position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column;">
          <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">Jumlah Penduduk Miskin</h6>
          <h3 style="font-size: 28px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;">
            <span id="jumlah-penduduk-miskin-value">-</span> <span style="font-size: 16px; font-weight: 400;">ribu</span>
          </h3>
          <div style="display: flex; align-items: center; gap: 5px; margin-top: 8px;" id="jumlah-penduduk-miskin-change">
          </div>
          <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: auto;" id="jumlah-penduduk-miskin-year">
            Data tidak tersedia
          </small>
        </div>
      </div>
    </div>

    <!-- Persentase Kemiskinan -->
    <div class="col-6 col-md-4 col-lg mb-3 summary-card-mobile">
      <div class="summary-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 12px; padding: 20px; min-height: 160px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); display: flex; flex-direction: column;">
        <div style="position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column;">
          <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">Persentase Kemiskinan</h6>
          <h3 style="font-size: 28px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;">
            <span id="persentase-kemiskinan-value">-</span>%
          </h3>
          <div style="display: flex; align-items: center; gap: 5px; margin-top: 8px;" id="persentase-kemiskinan-change">
          </div>
          <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: auto;" id="persentase-kemiskinan-year">
            Data tidak tersedia
          </small>
        </div>
      </div>
    </div>

    <!-- Indeks Kedalaman (P1) -->
    <div class="col-6 col-md-4 col-lg mb-3 summary-card-mobile">
      <div class="summary-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border-radius: 12px; padding: 20px; min-height: 160px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); display: flex; flex-direction: column;">
        <div style="position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column;">
          <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">Indeks Kedalaman (P1)</h6>
          <h3 style="font-size: 28px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;">
            <span id="indeks-kedalaman-p1-value">-</span>
          </h3>
          <div style="display: flex; align-items: center; gap: 5px; margin-top: 8px;" id="indeks-kedalaman-p1-change">
          </div>
          <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: auto;" id="indeks-kedalaman-p1-year">
            Data tidak tersedia
          </small>
        </div>
      </div>
    </div>

    <!-- Indeks Keparahan (P2) -->
    <div class="col-6 col-md-4 col-lg mb-3 summary-card-mobile">
      <div class="summary-card" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border-radius: 12px; padding: 20px; min-height: 160px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); display: flex; flex-direction: column;">
        <div style="position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column;">
          <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">Indeks Keparahan (P2)</h6>
          <h3 style="font-size: 28px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;">
            <span id="indeks-keparahan-p2-value">-</span>
          </h3>
          <div style="display: flex; align-items: center; gap: 5px; margin-top: 8px;" id="indeks-keparahan-p2-change">
          </div>
          <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: auto;" id="indeks-keparahan-p2-year">
            Data tidak tersedia
          </small>
        </div>
      </div>
    </div>

    <!-- Garis Kemiskinan -->
    <div class="col-6 col-md-4 col-lg mb-3 summary-card-mobile">
      <div class="summary-card" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border-radius: 12px; padding: 20px; min-height: 160px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); display: flex; flex-direction: column;">
        <div style="position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column;">
          <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">Garis Kemiskinan</h6>
          <h3 style="font-size: 28px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;">
            <span class="garis-kemiskinan-value" id="garis-kemiskinan-value" style="font-size: 28px; font-weight: 700;">-</span>
          </h3>
          <div style="display: flex; align-items: center; gap: 5px; margin-top: 8px;" id="garis-kemiskinan-change">
          </div>
          <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: auto;" id="garis-kemiskinan-year">
            Data tidak tersedia
          </small>
        </div>
      </div>
    </div>
  </div>

  <!-- 4 Visualisasi Data Surabaya -->
  <div class="row mb-4">
    <!-- Kolom 1.1: Jumlah dan Persentase Penduduk Miskin -->
    <div class="col-md-6 mb-3">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">Tren Jumlah dan Persentase Penduduk Miskin</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="chart1" title="Tren Jumlah dan Persentase Penduduk Miskin" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadChart1Dropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadChart1Dropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadChart1Excel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadChart1PNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="chart-container-mobile">
        <div id="chart1" style="width: 100%; height: 350px;"></div>
        </div>
      </div>
    </div>

    <!-- Kolom 1.2: Garis Kemiskinan -->
    <div class="col-md-6 mb-3">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">Tren Garis Kemiskinan</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="chart2" title="Tren Garis Kemiskinan" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadChart2Dropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadChart2Dropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadChart2Excel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadChart2PNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="chart-container-mobile">
        <div id="chart2" style="width: 100%; height: 350px;"></div>
        </div>
      </div>
    </div>

    <!-- Kolom 2.1: Indeks Kedalaman (P1) -->
    <div class="col-md-6 mb-3">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">Tren Indeks Kedalaman (P1)</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="chart3" title="Tren Indeks Kedalaman (P1)" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadChart3Dropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadChart3Dropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadChart3Excel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadChart3PNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="chart-container-mobile">
        <div id="chart3" style="width: 100%; height: 350px;"></div>
        </div>
      </div>
    </div>

    <!-- Kolom 2.2: Indeks Keparahan (P2) -->
    <div class="col-md-6 mb-3">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">Tren Indeks Keparahan (P2)</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="chart4" title="Tren Indeks Keparahan (P2)" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadChart4Dropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadChart4Dropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadChart4Excel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadChart4PNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="chart-container-mobile">
        <div id="chart4" style="width: 100%; height: 350px;"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Visualisasi Perbandingan Surabaya vs Jawa Timur -->
  <div class="row mb-4">
    <div class="col-md-12">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">Perbandingan Surabaya vs Jawa Timur</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="comparisonChart" title="Perbandingan Kemiskinan Surabaya vs Jawa Timur" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadComparisonDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadComparisonDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadComparisonExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadComparisonPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="mb-3">
          <select id="indicatorSelector" class="form-control" style="width: auto; max-width: 300px;">
            <option value="jumlah_penduduk_miskin">Jumlah Penduduk Miskin</option>
            <option value="persentase_penduduk_miskin">Persentase Penduduk Miskin</option>
            <option value="indeks_kedalaman_kemiskinan_p1">Indeks Kedalaman (P1)</option>
            <option value="indeks_keparahan_kemiskinan_p2">Indeks Keparahan (P2)</option>
            <option value="garis_kemiskinan">Garis Kemiskinan</option>
          </select>
        </div>
        <div class="chart-container-mobile">
        <div id="comparisonChart" style="width: 100%; height: 400px;"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Additional Info Card -->
  <div class="row">
    <div class="col-md-12">
      <div class="dashboard-card" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
        <h5 class="mb-3"><i class="fas fa-info-circle"></i> Tentang Kemiskinan</h5>
        <p style="margin-bottom: 0; line-height: 1.8;">
          <strong>Kemiskinan</strong> adalah kondisi ketidakmampuan seseorang atau rumah tangga untuk memenuhi kebutuhan dasar hidupnya. 
          Di Indonesia, kemiskinan diukur menggunakan pendekatan kebutuhan dasar (basic needs approach) yang mencakup:
        </p>
        <ul style="margin-top: 12px; margin-bottom: 0; line-height: 1.8;">
          <li><strong>Garis Kemiskinan</strong>: Batas minimum pengeluaran per kapita per bulan untuk memenuhi kebutuhan dasar makanan dan non-makanan</li>
          <li><strong>Jumlah Penduduk Miskin</strong>: Jumlah penduduk yang pengeluarannya berada di bawah garis kemiskinan</li>
          <li><strong>Persentase Penduduk Miskin</strong>: Proporsi penduduk miskin terhadap total penduduk</li>
          <li><strong>Indeks Kedalaman Kemiskinan (P1)</strong>: Mengukur rata-rata kesenjangan pengeluaran penduduk miskin terhadap garis kemiskinan</li>
          <li><strong>Indeks Keparahan Kemiskinan (P2)</strong>: Mengukur ketimpangan pengeluaran di antara penduduk miskin</li>
        </ul>
        <p style="margin-top: 12px; margin-bottom: 16px; line-height: 1.8;">
          Semakin tinggi nilai P1 dan P2, semakin dalam dan parah kondisi kemiskinan di suatu wilayah. 
          Indikator-indikator ini membantu pemerintah dalam merancang program pengentasan kemiskinan yang lebih tepat sasaran.
        </p>
      </div>
    </div>
  </div>
</div>






@push('scripts')
<script>
window.APP_CONFIG = {
  apiBase: '{{ url("/api") }}'
};
</script>
@vite(['resources/css/dashboard/kemiskinan.css', 'resources/js/dashboard/kemiskinan.js'])
@endpush

@endsection
