@extends('layouts.main')

@section('title', 'Gini Ratio')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
@endpush

@section('content')
<div class="container py-4">
  <h3 class="font-weight-bold mb-4">Gini Ratio</h3>
  
  <!-- Summary Cards -->
  <div class="row mb-4">
    <!-- Kota Surabaya Card -->
    <div class="col-6 col-md-6 mb-2 mb-md-3">
      <div class="summary-card summary-card-mobile" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-radius: 12px; padding: 24px; min-height: 180px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); display: flex; flex-direction: column;">
        <div style="position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column;">
          <h6 style="color: rgba(255, 255, 255, 0.8); font-size: 14px; font-weight: 500; margin: 0 0 12px 0;">Kota Surabaya</h6>
          <h2 style="font-size: 42px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;">
            <span id="surabaya-value">-</span>
          </h2>
          <div style="display: flex; align-items: center; margin-top: 8px;" id="surabaya-change">
          </div>
          <small style="color: rgba(255, 255, 255, 0.8); font-size: 13px; margin-top: auto;" id="surabaya-year">
            Data tidak tersedia
          </small>
        </div>
        <div style="position: absolute; top: 20px; right: 20px; opacity: 0.15; z-index: 1;">
          <i class="fas fa-city" style="font-size: 80px;"></i>
        </div>
      </div>
    </div>

    <!-- Jawa Timur Card -->
    <div class="col-6 col-md-6 mb-2 mb-md-3">
      <div class="summary-card summary-card-mobile" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border-radius: 12px; padding: 24px; min-height: 180px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); display: flex; flex-direction: column;">
        <div style="position: relative; z-index: 2; flex: 1; display: flex; flex-direction: column;">
          <h6 style="color: rgba(255, 255, 255, 0.8); font-size: 14px; font-weight: 500; margin: 0 0 12px 0;">Jawa Timur</h6>
          <h2 style="font-size: 42px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;">
            <span id="jatim-value">-</span>
          </h2>
          <div style="display: flex; align-items: center; margin-top: 8px;" id="jatim-change">
          </div>
          <small style="color: rgba(255, 255, 255, 0.8); font-size: 13px; margin-top: auto;" id="jatim-year">
            Data tidak tersedia
          </small>
        </div>
        <div style="position: absolute; top: 20px; right: 20px; opacity: 0.15; z-index: 1;">
          <i class="fas fa-map-marked-alt" style="font-size: 80px;"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Comparison Line Chart -->
  <div class="row mb-4">
    <div class="col-md-12">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h4 class="mb-0">Trend Gini Ratio Kota Surabaya dan Jawa Timur</h4>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="comparisonLineChart" title="Trend Gini Ratio Kota Surabaya dan Jawa Timur" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadLineChartDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadLineChartDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadLineChartExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadLineChartPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="chart-container-mobile">
          <div id="comparisonLineChart" class="chart-container-desktop" style="width: 100%; height: 400px;"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Line Chart for Recent Years -->
  <div class="row mb-4">
    <div class="col-md-12">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h4 class="mb-0">Perbandingan Gini Ratio 5 Tahun Terakhir</h4>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="comparisonBarChart" title="Perbandingan Gini Ratio 5 Tahun Terakhir" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadBarChartDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadBarChartDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadBarChartExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadBarChartPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="chart-container-mobile">
          <div id="comparisonBarChart" class="chart-container-desktop" style="width: 100%; height: 400px;"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Additional Info Card -->
  <div class="row">
    <div class="col-md-12">
      <div class="dashboard-card" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
        <h5 class="mb-3"><i class="fas fa-info-circle"></i> Tentang Gini Ratio</h5>
        <p style="margin-bottom: 0; line-height: 1.8;">
          <strong>Gini Ratio</strong> adalah indikator yang mengukur tingkat ketimpangan distribusi pendapatan dalam suatu wilayah. 
          Nilai Gini Ratio berkisar antara 0 hingga 1, di mana:
        </p>
        <ul style="margin-top: 12px; margin-bottom: 0; line-height: 1.8;">
          <li><strong>0</strong> = Distribusi pendapatan sempurna (sangat merata)</li>
          <li><strong>1</strong> = Distribusi pendapatan sangat tidak merata (satu orang memiliki semua pendapatan)</li>
        </ul>
        <p style="margin-top: 12px; margin-bottom: 16px; line-height: 1.8;">
          Semakin rendah nilai Gini Ratio, semakin merata distribusi pendapatan di wilayah tersebut.
        </p>
        
        <h6 class="mb-3" style="font-weight: 600;">Interpretasi Ketimpangan</h6>
        <div class="table-responsive">
          <table class="table table-bordered" style="margin-bottom: 0;">
            <thead style="background-color: #f8f9fa;">
              <tr>
                <th style="padding: 12px; text-align: center; font-weight: 600;">Nilai Gini</th>
                <th style="padding: 12px; text-align: center; font-weight: 600;">Interpretasi Ketimpangan</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="padding: 12px; text-align: center;">0,00 – 0,29</td>
                <td style="padding: 12px; text-align: center;"><strong>Rendah (merata)</strong> / Low (even)</td>
              </tr>
              <tr>
                <td style="padding: 12px; text-align: center;">0,30 – 0,49</td>
                <td style="padding: 12px; text-align: center;"><strong>Sedang</strong> / Moderate</td>
              </tr>
              <tr>
                <td style="padding: 12px; text-align: center;">0,50 – 1,00</td>
                <td style="padding: 12px; text-align: center;"><strong>Tinggi (tidak merata)</strong> / High (uneven)</td>
              </tr>
            </tbody>
          </table>
        </div>
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
@vite(['resources/css/dashboard/gini-ratio.css', 'resources/js/dashboard/gini-ratio.js', 'resources/js/dashboard/chart-modal.js'])
@endpush

  <!-- Global Chart Modal Component -->
  <x-chart-modal />
@endsection

