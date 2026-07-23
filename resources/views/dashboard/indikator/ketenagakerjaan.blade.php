@extends('layouts.main')

@section('title', 'Ketenagakerjaan')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
@endpush

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="font-weight-bold mb-0">Ketenagakerjaan</h3>
    <div style="display: flex; align-items: center; gap: 8px;">
      <label for="yearFilter" style="margin: 0; font-weight: 500; color: #333; font-size: 13px; white-space: nowrap;"><i class="fas fa-filter me-1"></i>Tahun:</label>
      <select id="yearFilter" class="form-control" style="width: auto; max-width: 200px;">
        <option value="">Loading...</option>
      </select>
    </div>
  </div>
  
  <!-- Rangkuman Data Ketenagakerjaan -->
  <div class="row mb-4">
    <!-- TPT Summary Card -->
    <div class="col-md-6 mb-3">
      <div class="summary-card" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);; color: white; border-radius: 12px; padding: 25px; min-height: 200px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
        <div style="position: relative; z-index: 2;">
          <h5 style="color: rgba(255, 255, 255, 0.95); font-size: 16px; font-weight: 600; margin: 0 0 15px 0;">
            <i class="fas fa-chart-line me-2"></i>Tingkat Pengangguran Terbuka (TPT)
          </h5>
          <div class="row">
            <div class="col-4">
              <h6 style="color: rgba(255, 255, 255, 0.8); font-size: 11px; font-weight: 500; margin: 0 0 5px 0;">Total</h6>
              <h3 id="tpt-total-value" style="font-size: 28px; font-weight: 700; line-height: 1.2; margin: 0;">-</h3>
            </div>
            <div class="col-4">
              <h6 style="color: rgba(255, 255, 255, 0.8); font-size: 11px; font-weight: 500; margin: 0 0 5px 0;">Laki-Laki</h6>
              <h3 id="tpt-laki-value" style="font-size: 28px; font-weight: 700; line-height: 1.2; margin: 0;">-</h3>
            </div>
            <div class="col-4">
              <h6 style="color: rgba(255, 255, 255, 0.8); font-size: 11px; font-weight: 500; margin: 0 0 5px 0;">Perempuan</h6>
              <h3 id="tpt-perempuan-value" style="font-size: 28px; font-weight: 700; line-height: 1.2; margin: 0;">-</h3>
            </div>
          </div>
          <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255, 255, 255, 0.2);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
              <small id="tpt-year-value" style="color: rgba(255, 255, 255, 0.8); font-size: 11px;">Data tidak tersedia</small>
              <div id="tpt-change-value" style="display: flex; align-items: center; gap: 5px;"></div>
            </div>
          </div>
        </div>
        <div style="position: absolute; top: 10px; right: 10px; opacity: 0.1; z-index: 1;">
          <i class="fas fa-chart-line" style="font-size: 80px;"></i>
        </div>
      </div>
    </div>

    <!-- TPAK Summary Card -->
    <div class="col-md-6 mb-3">
      <div class="summary-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);; color: white; border-radius: 12px; padding: 25px; min-height: 200px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
        <div style="position: relative; z-index: 2;">
          <h5 style="color: rgba(255, 255, 255, 0.95); font-size: 16px; font-weight: 600; margin: 0 0 15px 0;">
            <i class="fas fa-users me-2"></i>Tingkat Partisipasi Angkatan Kerja (TPAK)
          </h5>
          <div class="row">
            <div class="col-4">
              <h6 style="color: rgba(255, 255, 255, 0.8); font-size: 11px; font-weight: 500; margin: 0 0 5px 0;">Total</h6>
              <h3 id="tpak-total-value" style="font-size: 28px; font-weight: 700; line-height: 1.2; margin: 0;">-</h3>
            </div>
            <div class="col-4">
              <h6 style="color: rgba(255, 255, 255, 0.8); font-size: 11px; font-weight: 500; margin: 0 0 5px 0;">Laki-Laki</h6>
              <h3 id="tpak-laki-value" style="font-size: 28px; font-weight: 700; line-height: 1.2; margin: 0;">-</h3>
            </div>
            <div class="col-4">
              <h6 style="color: rgba(255, 255, 255, 0.8); font-size: 11px; font-weight: 500; margin: 0 0 5px 0;">Perempuan</h6>
              <h3 id="tpak-perempuan-value" style="font-size: 28px; font-weight: 700; line-height: 1.2; margin: 0;">-</h3>
            </div>
          </div>
          <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255, 255, 255, 0.2);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
              <small id="tpak-year-value" style="color: rgba(255, 255, 255, 0.8); font-size: 11px;">Data tidak tersedia</small>
              <div id="tpak-change-value" style="display: flex; align-items: center; gap: 5px;"></div>
            </div>
          </div>
        </div>
        <div style="position: absolute; top: 10px; right: 10px; opacity: 0.1; z-index: 1;">
          <i class="fas fa-users" style="font-size: 80px;"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Comparison Chart -->
  <div class="row mb-4 comparison-chart-row">
    <div class="col-md-12">
      <div class="dashboard-card comparison-chart-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;" class="chart-header">
          <h5 class="mb-0">Perbandingan TPT dan TPAK dari Tahun ke Tahun</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="comparisonChart" title="Perbandingan TPT dan TPAK" />
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
        <div class="chart-container-mobile">
          <div id="comparisonChart" style="width: 100%; height: 400px;"></div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Tab Navigation -->
  <ul class="nav nav-tabs mb-4" id="ketenagakerjaanTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="tpt-tab" data-bs-toggle="tab" data-bs-target="#tpt" type="button" role="tab" aria-controls="tpt" aria-selected="true">
        <i class="fas fa-chart-line me-2"></i>TPT
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="tpak-tab" data-bs-toggle="tab" data-bs-target="#tpak" type="button" role="tab" aria-controls="tpak" aria-selected="false">
        <i class="fas fa-users me-2"></i>TPAK
      </button>
    </li>
  </ul>

  <!-- Tab Content -->
  <div class="tab-content" id="ketenagakerjaanTabsContent">
    <!-- Tab 1: TPT -->
    <div class="tab-pane fade show active" id="tpt" role="tabpanel" aria-labelledby="tpt-tab">
      <!-- Summary Cards -->
      <div class="row mb-4">
        <!-- Total TPT -->
        <div class="col-6 col-md-4 mb-2 mb-md-3">
          <div class="summary-card summary-card-mobile" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);; color: white; border-radius: 12px; padding: 20px; min-height: 160px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
            <div style="position: relative; z-index: 2;">
              <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">Total TPT</h6>
              <h3 id="tpt-tab-total-value" style="font-size: 32px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;">-</h3>
              <div id="tpt-tab-total-change" style="display: flex; align-items: center; gap: 5px; margin-top: 8px;"></div>
              <small id="tpt-tab-total-year" style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: 8px; display: block;">Data tidak tersedia</small>
            </div>
            <div style="position: absolute; top: 10px; right: 10px; opacity: 0.15; z-index: 1;">
              <i class="fas fa-chart-line" style="font-size: 60px;"></i>
            </div>
          </div>
        </div>

        <!-- Laki-Laki TPT -->
        <div class="col-6 col-md-4 mb-2 mb-md-3">
          <div class="summary-card summary-card-mobile" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);; color: white; border-radius: 12px; padding: 20px; min-height: 160px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
            <div style="position: relative; z-index: 2;">
              <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">Laki-Laki</h6>
              <h3 id="tpt-tab-laki-value" style="font-size: 32px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;">-</h3>
              <div id="tpt-tab-laki-change" style="display: flex; align-items: center; gap: 5px; margin-top: 8px;"></div>
              <small id="tpt-tab-laki-year" style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: 8px; display: block;">Data tidak tersedia</small>
            </div>
            <div style="position: absolute; top: 10px; right: 10px; opacity: 0.15; z-index: 1;">
              <i class="fas fa-male" style="font-size: 60px;"></i>
            </div>
          </div>
        </div>

        <!-- Perempuan TPT -->
        <div class="col-6 col-md-4 mb-2 mb-md-3">
          <div class="summary-card summary-card-mobile" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);; color: white; border-radius: 12px; padding: 20px; min-height: 160px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
            <div style="position: relative; z-index: 2;">
              <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">Perempuan</h6>
              <h3 id="tpt-tab-perempuan-value" style="font-size: 32px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;">-</h3>
              <div id="tpt-tab-perempuan-change" style="display: flex; align-items: center; gap: 5px; margin-top: 8px;"></div>
              <small id="tpt-tab-perempuan-year" style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: 8px; display: block;">Data tidak tersedia</small>
            </div>
            <div style="position: absolute; top: 10px; right: 10px; opacity: 0.15; z-index: 1;">
              <i class="fas fa-female" style="font-size: 60px;"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts Row TPT -->
      <div class="row mb-4">
        <!-- Pie Chart for Demographics TPT -->
        <div class="col-md-6 mb-3">
          <div class="dashboard-card" style="position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
              <h5 class="mb-0">Distribusi TPT Berdasarkan Gender (@if(isset($tpt_latest_data) && $tpt_latest_data){{ $tpt_latest_data->year ?? '-' }}@else-@endif)</h5>
              <div class="chart-header-actions">
                <x-chart-share-button chartId="tptPieChart" title="Distribusi TPT Berdasarkan Gender" />
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadTptPieDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                    <i class="fas fa-download"></i> <span>Unduh</span>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="downloadTptPieDropdown" style="border-radius: 8px; min-width: 100%;">
                    <li><a class="dropdown-item" href="#" id="downloadTptPieExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                    <li><a class="dropdown-item" href="#" id="downloadTptPiePNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="chart-container-mobile">
              <div id="tptPieChart" style="width: 100%; height: 400px;"></div>
            </div>
          </div>
        </div>

        <!-- Line Chart TPT -->
        <div class="col-md-6 mb-3">
          <div class="dashboard-card" style="position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
              <h5 class="mb-0">Tren TPT dari Tahun ke Tahun</h5>
              <div class="chart-header-actions">
                <x-chart-share-button chartId="tptLineChart" title="Tren TPT dari Tahun ke Tahun" />
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadTptLineDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                    <i class="fas fa-download"></i> <span>Unduh</span>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="downloadTptLineDropdown" style="border-radius: 8px; min-width: 100%;">
                    <li><a class="dropdown-item" href="#" id="downloadTptLineExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                    <li><a class="dropdown-item" href="#" id="downloadTptLinePNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="chart-container-mobile">
              <div id="tptLineChart" style="width: 100%; height: 400px;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tab 2: TPAK -->
    <div class="tab-pane fade" id="tpak" role="tabpanel" aria-labelledby="tpak-tab">
      <!-- Summary Cards -->
      <div class="row mb-4">
        <!-- Total TPAK -->
        <div class="col-6 col-md-4 mb-2 mb-md-3">
          <div class="summary-card summary-card-mobile" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);; color: white; border-radius: 12px; padding: 20px; min-height: 160px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
            <div style="position: relative; z-index: 2;">
              <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">Total TPAK</h6>
              <h3 id="tpak-tab-total-value" style="font-size: 32px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;">-</h3>
              <div id="tpak-tab-total-change" style="display: flex; align-items: center; gap: 5px; margin-top: 8px;"></div>
              <small id="tpak-tab-total-year" style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: 8px; display: block;">Data tidak tersedia</small>
            </div>
            <div style="position: absolute; top: 10px; right: 10px; opacity: 0.15; z-index: 1;">
              <i class="fas fa-users" style="font-size: 60px;"></i>
            </div>
          </div>
        </div>

        <!-- Laki-Laki TPAK -->
        <div class="col-6 col-md-4 mb-2 mb-md-3">
          <div class="summary-card summary-card-mobile" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);; color: white; border-radius: 12px; padding: 20px; min-height: 160px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
            <div style="position: relative; z-index: 2;">
              <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">Laki-Laki</h6>
              <h3 id="tpak-tab-laki-value" style="font-size: 32px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;">-</h3>
              <div id="tpak-tab-laki-change" style="display: flex; align-items: center; gap: 5px; margin-top: 8px;"></div>
              <small id="tpak-tab-laki-year" style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: 8px; display: block;">Data tidak tersedia</small>
            </div>
            <div style="position: absolute; top: 10px; right: 10px; opacity: 0.15; z-index: 1;">
              <i class="fas fa-male" style="font-size: 60px;"></i>
            </div>
          </div>
        </div>

        <!-- Perempuan TPAK -->
        <div class="col-6 col-md-4 mb-2 mb-md-3">
          <div class="summary-card summary-card-mobile" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);; color: white; border-radius: 12px; padding: 20px; min-height: 160px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
            <div style="position: relative; z-index: 2;">
              <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 10px 0;">Perempuan</h6>
              <h3 id="tpak-tab-perempuan-value" style="font-size: 32px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;">-</h3>
              <div id="tpak-tab-perempuan-change" style="display: flex; align-items: center; gap: 5px; margin-top: 8px;"></div>
              <small id="tpak-tab-perempuan-year" style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: 8px; display: block;">Data tidak tersedia</small>
            </div>
            <div style="position: absolute; top: 10px; right: 10px; opacity: 0.15; z-index: 1;">
              <i class="fas fa-female" style="font-size: 60px;"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts Row TPAK -->
      <div class="row mb-4">
        <!-- Pie Chart for Demographics TPAK -->
        <div class="col-md-6 mb-3">
          <div class="dashboard-card" style="position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
              <h5 class="mb-0">Distribusi TPAK Berdasarkan Gender (<span id="tpak-pie-year">-</span>)</h5>
              <div class="chart-header-actions">
                <x-chart-share-button chartId="tpakPieChart" title="Distribusi TPAK Berdasarkan Gender" />
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadTpakPieDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                    <i class="fas fa-download"></i> <span>Unduh</span>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="downloadTpakPieDropdown" style="border-radius: 8px; min-width: 100%;">
                    <li><a class="dropdown-item" href="#" id="downloadTpakPieExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                    <li><a class="dropdown-item" href="#" id="downloadTpakPiePNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="chart-container-mobile">
              <div id="tpakPieChart" style="width: 100%; height: 400px;"></div>
            </div>
          </div>
        </div>

        <!-- Line Chart TPAK -->
        <div class="col-md-6 mb-3">
          <div class="dashboard-card" style="position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
              <h5 class="mb-0">Tren TPAK dari Tahun ke Tahun</h5>
              <div class="chart-header-actions">
                <x-chart-share-button chartId="tpakLineChart" title="Tren TPAK dari Tahun ke Tahun" />
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadTpakLineDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                    <i class="fas fa-download"></i> <span>Unduh</span>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="downloadTpakLineDropdown" style="border-radius: 8px; min-width: 100%;">
                    <li><a class="dropdown-item" href="#" id="downloadTpakLineExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                    <li><a class="dropdown-item" href="#" id="downloadTpakLinePNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="chart-container-mobile">
              <div id="tpakLineChart" style="width: 100%; height: 400px;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Additional Info Card -->
  <div class="row">
    <div class="col-md-12">
      <div class="dashboard-card" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
        <h5 class="mb-3"><i class="fas fa-info-circle"></i> Tentang Ketenagakerjaan</h5>
        <p style="margin-bottom: 0; line-height: 1.8;">
          <strong>Ketenagakerjaan</strong> mencakup berbagai indikator yang mengukur kondisi pasar tenaga kerja dan partisipasi penduduk dalam kegiatan ekonomi. 
          Dua indikator utama yang digunakan adalah:
        </p>
        <ul style="margin-top: 12px; margin-bottom: 0; line-height: 1.8;">
          <li><strong>Tingkat Pengangguran Terbuka (TPT)</strong>: Persentase angkatan kerja yang tidak bekerja dan sedang mencari pekerjaan terhadap total angkatan kerja. 
            TPT yang rendah menunjukkan kondisi pasar tenaga kerja yang baik.</li>
          <li><strong>Tingkat Partisipasi Angkatan Kerja (TPAK)</strong>: Persentase angkatan kerja (bekerja dan menganggur) terhadap penduduk usia kerja (15 tahun ke atas). 
            TPAK yang tinggi menunjukkan tingkat partisipasi ekonomi yang tinggi.</li>
        </ul>
        <p style="margin-top: 12px; margin-bottom: 16px; line-height: 1.8;">
          Angkatan kerja terdiri dari penduduk yang bekerja dan yang menganggur. 
          Penduduk yang bekerja adalah mereka yang melakukan pekerjaan dengan maksud memperoleh atau membantu memperoleh pendapatan atau keuntungan. 
          Data ketenagakerjaan penting untuk memahami kondisi ekonomi dan merancang kebijakan penciptaan lapangan kerja.
        </p>
      </div>
    </div>
  </div>
</div>






@push('scripts')
<script>
window.APP_CONFIG = {
  apiUrl: '{{ url("/api") }}',
  isAuthenticated: @auth true @else false @endauth,
  loginUrl: '{{ route("login") }}'
};
</script>
@vite(['resources/css/dashboard/ketenagakerjaan.css', 'resources/js/dashboard/ketenagakerjaan.js', 'resources/js/dashboard/chart-modal.js'])
@endpush

  <!-- Global Chart Modal Component -->
  <x-chart-modal />
@endsection

