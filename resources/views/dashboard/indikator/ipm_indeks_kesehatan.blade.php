@extends('layouts.main')

@section('title', 'IPM - Indeks Kesehatan')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
@endpush

@section('content')
<div class="container py-4">
  <h3 class="font-weight-bold mb-4">IPM - Indeks Kesehatan</h3>
  
  <div class="row mb-4" style="display: flex; flex-wrap: nowrap; gap: 15px; margin-left: 0; margin-right: 0;">
    <div class="col-6 mb-3" style="flex: 1; min-width: 0; padding-left: 0; padding-right: 0;">
      <div class="summary-card" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border-radius: 12px; padding: 25px; min-height: 200px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
        <div style="position: relative; z-index: 2;">
          <h5 style="color: rgba(255, 255, 255, 0.95); font-size: 16px; font-weight: 600; margin: 0 0 15px 0;">
            <i class="fas fa-heartbeat me-2"></i>Kota Surabaya
          </h5>
          <h2 style="font-size: 42px; font-weight: 700; line-height: 1.2; margin: 0 0 10px 0;">
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
          <i class="fas fa-heartbeat" style="font-size: 80px;"></i>
        </div>
      </div>
    </div>
    <div class="col-6 mb-3" style="flex: 1; min-width: 0; padding-left: 0; padding-right: 0;">
      <div class="summary-card" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border-radius: 12px; padding: 25px; min-height: 200px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
        <div style="position: relative; z-index: 2;">
          <h5 style="color: rgba(255, 255, 255, 0.95); font-size: 16px; font-weight: 600; margin: 0 0 15px 0;">
            <i class="fas fa-heartbeat me-2"></i>Jawa Timur
          </h5>
          <h2 style="font-size: 42px; font-weight: 700; line-height: 1.2; margin: 0 0 10px 0;">
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
          <i class="fas fa-heartbeat" style="font-size: 80px;"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col-md-12">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">Perbandingan Indeks Kesehatan: Surabaya vs Jawa Timur</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="comparisonChart" title="Perbandingan Indeks Kesehatan Surabaya vs Jawa Timur" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadChartData" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadKesehatanDropdown" style="border-radius: 8px; min-width: 100%;">
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
        window.API_BASE = '{{ url("/api") }}';
        window.AUTH_CHECK = @json(auth()->check());
        window.LOGIN_ROUTE = '{{ route("login") }}';
        
        window.IPM_CONFIG = {
            apiEndpoint: '/ipm-indeks-kesehatan-summary',
            chartTitle: 'Indeks Kesehatan',
            yAxisName: 'Indeks',
            valuePrefix: '',
            valueSuffix: '',
            exportPrefix: 'Indeks Kesehatan',
            isCurrency: false
        };
    </script>
    @vite('resources/js/dashboard/indikator-ipm.js')
@endpush

@endsection
