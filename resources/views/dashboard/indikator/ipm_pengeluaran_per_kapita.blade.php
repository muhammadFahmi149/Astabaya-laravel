@extends('layouts.main')

@section('title', 'IPM - Pengeluaran per Kapita')

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
  <h3 class="font-weight-bold mb-4">IPM - Pengeluaran per Kapita</h3>
  
  <div class="row mb-4" style="display: flex; flex-wrap: nowrap; gap: 15px; margin-left: 0; margin-right: 0;">
    <div class="col-6 mb-3" style="flex: 1; min-width: 0; padding-left: 0; padding-right: 0;">
      <div class="summary-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 12px; padding: 25px; min-height: 200px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
        <div style="position: relative; z-index: 2;">
          <h5 style="color: rgba(255, 255, 255, 0.95); font-size: 16px; font-weight: 600; margin: 0 0 15px 0;">
            <i class="fas fa-money-bill-wave me-2"></i>Kota Surabaya
          </h5>
          <h2 style="font-size: 36px; font-weight: 700; line-height: 1.2; margin: 0 0 10px 0;">
            <span id="surabaya-value">-</span>
          </h2>
          <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255, 255, 255, 0.2);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
              <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px;" id="surabaya-year">
                Data tidak tersedia
              </small>
              <div style="display: flex; align-items: center; gap: 5px;" id="surabaya-change">
              </div>
            </div>
          </div>
        </div>
        <div style="position: absolute; top: 10px; right: 10px; opacity: 0.1; z-index: 1;">
          <i class="fas fa-money-bill-wave" style="font-size: 80px;"></i>
        </div>
      </div>
    </div>
    <div class="col-6 mb-3" style="flex: 1; min-width: 0; padding-left: 0; padding-right: 0;">
      <div class="summary-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border-radius: 12px; padding: 25px; min-height: 200px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
        <div style="position: relative; z-index: 2;">
          <h5 style="color: rgba(255, 255, 255, 0.95); font-size: 16px; font-weight: 600; margin: 0 0 15px 0;">
            <i class="fas fa-money-bill-wave me-2"></i>Jawa Timur
          </h5>
          <h2 style="font-size: 36px; font-weight: 700; line-height: 1.2; margin: 0 0 10px 0;">
            <span id="jatim-value">-</span>
          </h2>
          <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255, 255, 255, 0.2);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
              <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px;" id="jatim-year">
                Data tidak tersedia
              </small>
              <div style="display: flex; align-items: center; gap: 5px;" id="jatim-change">
              </div>
            </div>
          </div>
        </div>
        <div style="position: absolute; top: 10px; right: 10px; opacity: 0.1; z-index: 1;">
          <i class="fas fa-money-bill-wave" style="font-size: 80px;"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col-md-12">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">Perbandingan Pengeluaran per Kapita: Surabaya vs Jawa Timur</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="comparisonChart" title="Perbandingan Pengeluaran per Kapita Surabaya vs Jawa Timur" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadChartData" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadPengeluaranDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadChartData" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadImageData" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div id="comparisonChart" style="width: 100%; height: 450px;"></div>
      </div>
    </div>
  </div>
</div>

@push('styles')
    @vite('resources/css/dashboard/indikator-ipm.css')
@endpush

@push('scripts')
<script>
window.APP_CONFIG = {
  apiUrl: '{{ url("/api") }}',
  isAuthenticated: @auth true @else false @endauth,
  loginUrl: '{{ route("login") }}'
};
</script>

    <script>
        window.API_BASE = '{{ url("/api") }}';
        window.AUTH_CHECK = @json(auth()->check());
        window.LOGIN_ROUTE = '{{ route("login") }}';
        
        window.IPM_CONFIG = {
            apiEndpoint: '/ipm-pengeluaran-per-kapita-summary',
            chartTitle: 'Pengeluaran per Kapita',
            yAxisName: 'Juta',
            valuePrefix: 'Rp ',
            valueSuffix: ' Juta',
            exportPrefix: 'Pengeluaran',
            isCurrency: true
        };
    </script>
    @vite(['resources/js/dashboard/indikator-ipm.js', 'resources/js/dashboard/chart-modal.js'])
@endpush

  <!-- Global Chart Modal Component -->
  <x-chart-modal />
@endsection
