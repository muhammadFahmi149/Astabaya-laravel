@extends('layouts.main')

@section('title', 'Tingkat Hunian Hotel')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.3/echarts.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
@endpush

@section('content')
<div class="container py-4">
  <h3 class="font-weight-bold mb-4">Tingkat Hunian Hotel</h3>
  
  <!-- Summary Cards -->
  <div class="row mb-4 summary-cards-row">
    <!-- TPK Total Card -->
    <div class="col-6 col-md-4 col-lg mb-3 summary-card-mobile">
      <div class="summary-card" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-radius: 12px; padding: 20px; min-height: 160px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); display: flex; flex-direction: column;">
        <div style="position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column;">
          <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">TPK Total</h6>
          <h3 style="font-size: 28px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;">
            <span id="tpk-value">-</span>
          </h3>
          <div class="tpk-change-container" style="display: flex; align-items: center; gap: 5px; margin-top: 8px; flex-wrap: wrap;" id="tpk-change">
          </div>
          <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: auto;" id="tpk-date">
            Data tidak tersedia
          </small>
        </div>
      </div>
    </div>

    <!-- MKTJ Card -->
    <div class="col-6 col-md-4 col-lg mb-3 summary-card-mobile">
      <div class="summary-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 12px; padding: 20px; min-height: 160px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); display: flex; flex-direction: column;">
        <div style="position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column;">
          <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">MKTJ</h6>
          <h3 style="font-size: 28px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;">
            <span class="mktj-value" id="mktj-value">-</span>
          </h3>
          <div class="mktj-change-container" style="display: flex; align-items: center; gap: 5px; margin-top: 8px; flex-wrap: wrap;" id="mktj-change">
          </div>
          <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: auto;" id="mktj-date">
            Data tidak tersedia
          </small>
        </div>
      </div>
    </div>

    <!-- RLMT Gabungan Card -->
    <div class="col-6 col-md-4 col-lg mb-3 summary-card-mobile">
      <div class="summary-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border-radius: 12px; padding: 20px; min-height: 160px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); display: flex; flex-direction: column;">
        <div style="position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column;">
          <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">RLMT Gabungan</h6>
          <h3 style="font-size: 28px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;">
            <span id="rlmtgab-value">-</span>
          </h3>
          <div class="rlmtgab-change-container" style="display: flex; align-items: center; gap: 5px; margin-top: 8px; flex-wrap: wrap;" id="rlmtgab-change">
          </div>
          <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: auto;" id="rlmtgab-date">
            Data tidak tersedia
          </small>
        </div>
      </div>
    </div>

    <!-- GPR Card -->
    <div class="col-6 col-md-4 col-lg mb-3 summary-card-mobile">
      <div class="summary-card" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border-radius: 12px; padding: 20px; min-height: 160px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); display: flex; flex-direction: column;">
        <div style="position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column;">
          <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">GPR</h6>
          <h3 style="font-size: 28px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;">
            <span id="gpr-value">-</span>
          </h3>
          <div class="gpr-change-container" style="display: flex; align-items: center; gap: 5px; margin-top: 8px; flex-wrap: wrap;" id="gpr-change">
          </div>
          <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: auto;" id="gpr-date">
            Data tidak tersedia
          </small>
        </div>
      </div>
    </div>
  </div>

  <!-- Year Selector and Chart -->
  <div class="row">
    <div class="col-md-12">
      <div class="dashboard-card" style="position: relative;">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
          <h4 class="mb-2 mb-md-0" style="font-size: clamp(18px, 3vw, 24px);">Perkembangan Tingkat Penghunian Kamar (TPK)</h4>
          <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="yearDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Tahun: <span id="selectedYear">-</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="yearDropdown" id="yearDropdownMenu">
              </ul>
            </div>
            <div class="chart-header-actions">
              <x-chart-share-button chartId="tpkLineChart" title="Perkembangan TPK" />
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadTpkLineDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                  <i class="fas fa-download"></i> <span>Unduh</span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="downloadTpkLineDropdown" style="border-radius: 8px; min-width: 100%;">
                  <li><a class="dropdown-item" href="#" id="downloadTpkLineExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                  <li><a class="dropdown-item" href="#" id="downloadTpkLinePNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="chart-container-wrapper">
          <div id="tpkLineChart" class="chart-container"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Multi-Year Comparison Chart -->
  <div class="row mt-4">
    <div class="col-md-12">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h4 class="mb-0" style="font-size: clamp(18px, 3vw, 24px);">Perbandingan TPK Beberapa Tahun</h4>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="tpkComparisonChart" title="Perbandingan TPK Beberapa Tahun" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadTpkComparisonDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadTpkComparisonDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadTpkComparisonExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadTpkComparisonPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="chart-container-wrapper">
          <div id="tpkComparisonChart" class="chart-container"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Annual TPK Chart -->
  <div class="row mt-4">
    <div class="col-md-12">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h4 class="mb-0" style="font-size: clamp(18px, 3vw, 24px);">TPK Tahunan</h4>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="tpkYearlyChart" title="TPK Tahunan" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadTpkYearlyDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadTpkYearlyDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadTpkYearlyExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadTpkYearlyPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="chart-container-wrapper">
          <div id="tpkYearlyChart" class="chart-container"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Additional Info Card -->
  <div class="row">
    <div class="col-md-12">
      <div class="dashboard-card" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
        <h5 class="mb-3"><i class="fas fa-info-circle"></i> Tentang Tingkat Hunian Hotel</h5>
        <p style="margin-bottom: 0; line-height: 1.8;">
          <strong>Tingkat Hunian Hotel</strong> mengukur kinerja industri perhotelan melalui berbagai indikator yang mencerminkan tingkat pemanfaatan fasilitas hotel. 
          Indikator-indikator utama meliputi:
        </p>
        <ul style="margin-top: 12px; margin-bottom: 0; line-height: 1.8;">
          <li><strong>Tingkat Penghunian Kamar (TPK)</strong>: Persentase kamar yang terisi dari total kamar yang tersedia. 
            TPK yang tinggi menunjukkan permintaan yang baik terhadap akomodasi hotel.</li>
          <li><strong>Jumlah Malam Tamu (MKTJ)</strong>: Total jumlah malam yang dihabiskan oleh tamu di hotel. 
            Indikator ini mencerminkan durasi rata-rata kunjungan tamu.</li>
          <li><strong>Rata-rata Lama Menginap Tamu Gabungan (RLMT Gabungan)</strong>: Rata-rata jumlah malam menginap per tamu, 
            yang mengindikasikan pola kunjungan wisatawan atau pelaku bisnis.</li>
          <li><strong>Gross Profit Ratio (GPR)</strong>: Rasio keuntungan kotor terhadap pendapatan, 
            yang menunjukkan efisiensi operasional hotel.</li>
        </ul>
        <p style="margin-top: 12px; margin-bottom: 16px; line-height: 1.8;">
          Indikator-indikator ini penting untuk menilai kesehatan sektor pariwisata dan perhotelan, 
          serta dapat menjadi acuan dalam pengembangan kebijakan pariwisata dan investasi di sektor perhotelan.
        </p>
      </div>
    </div>
  </div>
</div>





@push('scripts')
<script>
window.APP_CONFIG = {
  apiUrl: '{{ url("/api") }}',
  isAuthenticated: @auth true @else false @endauth
};
</script>
@vite(['resources/css/dashboard/hotel-occupancy.css', 'resources/js/dashboard/hotel-occupancy.js'])
@endpush

@endsection