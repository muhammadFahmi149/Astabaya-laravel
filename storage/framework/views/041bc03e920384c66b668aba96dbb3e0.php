

<?php $__env->startSection('title', 'Ketenagakerjaan'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
  <h3 class="font-weight-bold mb-4">Ketenagakerjaan</h3>
  
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
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;" class="chart-header">
          <h5 class="mb-0">Perbandingan TPT dan TPAK dari Tahun ke Tahun</h5>
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
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
              <h5 class="mb-0">Distribusi TPT Berdasarkan Gender (<?php if(isset($tpt_latest_data) && $tpt_latest_data): ?><?php echo e($tpt_latest_data->year ?? '-'); ?><?php else: ?>-<?php endif; ?>)</h5>
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
            <div class="chart-container-mobile">
              <div id="tptPieChart" style="width: 100%; height: 400px;"></div>
            </div>
          </div>
        </div>

        <!-- Line Chart TPT -->
        <div class="col-md-6 mb-3">
          <div class="dashboard-card" style="position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
              <h5 class="mb-0">Tren TPT dari Tahun ke Tahun</h5>
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
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
              <h5 class="mb-0">Distribusi TPAK Berdasarkan Gender (<span id="tpak-pie-year">-</span>)</h5>
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
            <div class="chart-container-mobile">
              <div id="tpakPieChart" style="width: 100%; height: 400px;"></div>
            </div>
          </div>
        </div>

        <!-- Line Chart TPAK -->
        <div class="col-md-6 mb-3">
          <div class="dashboard-card" style="position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
              <h5 class="mb-0">Tren TPAK dari Tahun ke Tahun</h5>
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

<style>
  .dashboard-card {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
  }
  
  .summary-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  
  .summary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
  }

  /* Mobile responsive styles for summary cards */
  @media (max-width: 767.98px) {
    .summary-card-mobile {
      padding: 10px 10px 0px 10px !important;
      min-height: auto !important;
      height: fit-content !important;
      border-radius: 8px !important;
    }
    
    .summary-card-mobile h6 {
      font-size: 11px !important;
      margin-bottom: 4px !important;
    }
    
    .summary-card-mobile h3 {
      font-size: 24px !important;
      margin-bottom: 2px !important;
      line-height: 1.1 !important;
    }
    
    .summary-card-mobile > div[style*="position: absolute"] {
      top: 10px !important;
      right: 10px !important;
    }
    
    .summary-card-mobile > div[style*="position: absolute"] .fas {
      font-size: 25px !important;
    }
    
    .summary-card-mobile small {
      font-size: 10px !important;
      margin-top: 2px !important;
      margin-bottom: 0 !important;
      line-height: 1 !important;
      padding-bottom: 0 !important;
      padding-top: 0 !important;
      display: block !important;
    }
    
    .summary-card-mobile span[style*="font-size: 12px"] {
      font-size: 10px !important;
    }
    
    .summary-card-mobile span[style*="font-size: 11px"] {
      font-size: 9px !important;
    }
    
    .summary-card-mobile > div[style*="position: relative"][style*="z-index: 2"] {
      margin: 0 !important;
      padding-bottom: 0 !important;
      flex: 0 0 auto !important;
    }
    
    .summary-card-mobile > div[style*="position: relative"][style*="z-index: 2"] > div[style*="display: flex"][style*="align-items: center"] {
      margin-top: 2px !important;
      margin-bottom: 0 !important;
    }
    
    .summary-card-mobile > div[style*="position: relative"][style*="z-index: 2"] > div[style*="display: flex"][style*="align-items: center"] span[style*="gap: 5px"] {
      gap: 3px !important;
    }
    
    /* Hilangkan semua space kosong di bawah */
    .summary-card-mobile > div[style*="position: relative"][style*="z-index: 2"] > *:last-child {
      margin-bottom: 0 !important;
      padding-bottom: 0 !important;
    }
    
    /* Override inline style padding dan margin-top auto */
    .summary-card-mobile[style*="padding: 20px"] {
      padding: 10px 10px 0px 10px !important;
    }
    
    /* Pastikan card tidak punya space kosong di bawah */
    .summary-card-mobile {
      padding-bottom: 0 !important;
    }
    
    .summary-card-mobile small[style*="margin-top: 8px"] {
      margin-top: 2px !important;
    }
    
    /* Pastikan tidak ada space dari flex */
    .summary-card-mobile[style*="display: flex"] {
      align-items: flex-start !important;
    }
    
    /* Hilangkan semua space kosong di bawah card */
    .summary-card-mobile > div[style*="position: relative"][style*="z-index: 2"] {
      margin-bottom: 0 !important;
      padding-bottom: 0 !important;
    }
    
    .summary-card-mobile > div[style*="position: relative"][style*="z-index: 2"] > small:last-child {
      margin-bottom: 0 !important;
      padding-bottom: 0 !important;
    }

    /* Override margin-top pada div yang berisi change indicator */
    .summary-card-mobile > div[style*="position: relative"][style*="z-index: 2"] > div[style*="margin-top: 8px"] {
      margin-top: 2px !important;
    }
  }

  /* Chart container for mobile horizontal scroll */
  .chart-container-mobile {
    overflow-x: auto;
    overflow-y: visible;
    -webkit-overflow-scrolling: touch;
    width: 100%;
    position: relative;
    margin: 0;
    padding: 0;
  }

  @media (max-width: 767.98px) {
    .chart-container-mobile {
      width: 100%;
      overflow-x: auto;
      overflow-y: visible;
      margin: 0;
      padding: 0;
      -webkit-overflow-scrolling: touch;
    }
    
    .chart-container-mobile > div {
      min-width: 400px;
      width: 100%;
      margin: 0;
      padding: 0;
    }
    
    .dashboard-card {
      overflow: hidden;
      padding: 12px 10px;
      margin-bottom: 15px;
    }
    
    .dashboard-card h5 {
      white-space: normal;
      word-wrap: break-word;
      overflow: visible;
      margin-bottom: 10px !important;
      font-size: 14px;
    }
    
    /* Reduce spacing in header with download button */
    .dashboard-card > div[style*="display: flex"][style*="justify-content: space-between"] {
      margin-bottom: 10px !important;
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
    
    /* Reduce chart height on mobile to prevent excessive scrolling */
    .chart-container-mobile > div[id*="Chart"] {
      height: 350px !important;
    }
    
    /* Specific styling for comparison chart */
    .comparison-chart-row {
      margin-bottom: 15px !important;
    }
    
    .comparison-chart-card {
      margin-bottom: 15px !important;
    }
    
    .comparison-chart-card .chart-header {
      margin-bottom: 10px !important;
    }
    
    /* Remove extra margins from container */
    .container {
      padding-left: 10px;
      padding-right: 10px;
    }
    
    /* Reduce row margins */
    .row {
      margin-left: -5px;
      margin-right: -5px;
    }
    
    .row > [class*="col-"] {
      padding-left: 5px;
      padding-right: 5px;
    }
    
    /* Remove extra padding from mb-4 class on mobile */
    .mb-4 {
      margin-bottom: 1rem !important;
    }
    
    /* Ensure no extra space after charts */
    .chart-container-mobile:after {
      content: '';
      display: none;
    }
    
    /* Fix column spacing on mobile */
    .row > [class*="col-"] {
      padding-left: 5px !important;
      padding-right: 5px !important;
    }
    
    /* Ensure cards don't overflow */
    .summary-card-mobile {
      overflow: hidden !important;
    }
    
    /* Better spacing for tab summary cards */
    #tpt .row.mb-4,
    #tpak .row.mb-4 {
      margin-bottom: 1rem !important;
    }
    
    /* Adjust tab content padding */
    .tab-content {
      padding-top: 10px;
    }
    
    /* Fix nav tabs on mobile */
    .nav-tabs {
      flex-wrap: nowrap !important;
      overflow-x: auto !important;
      -webkit-overflow-scrolling: touch;
      scrollbar-width: none; /* Firefox */
      -ms-overflow-style: none; /* IE and Edge */
    }
    
    .nav-tabs::-webkit-scrollbar {
      display: none; /* Chrome, Safari, Opera */
    }
    
    .nav-tabs .nav-item {
      flex-shrink: 0;
    }
    
    .nav-tabs .nav-link {
      white-space: nowrap;
      padding: 8px 15px;
      font-size: 14px;
    }
    
    /* Fix summary card text overflow */
    .summary-card-mobile h3 {
      word-break: break-word;
      overflow-wrap: break-word;
    }
    
    /* Better spacing for change indicators */
    .summary-card-mobile > div[style*="position: relative"][style*="z-index: 2"] > div[style*="display: flex"] {
      flex-wrap: wrap;
      align-items: center;
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

  @media (min-width: 768px) {
    .chart-container-mobile {
      overflow: visible;
    }
    
    .chart-container-mobile > div {
      min-width: auto !important;
      width: 100% !important;
    }
  }
</style>

<script>
  document.addEventListener("DOMContentLoaded", async () => {
    // API Base URL
    const API_BASE = '<?php echo e(url("/api")); ?>';
    
    // Initialize data variables
    let tptData = [];
    let tpakData = [];
    let tptLatestData = null;
    let tpakLatestData = null;
    let tptPreviousData = null;
    let tpakPreviousData = null;
    let tptTotalChange = null;
    let tpakTotalChange = null;
    let tptLakiLakiChange = null;
    let tpakLakiLakiChange = null;
    let tptPerempuanChange = null;
    let tpakPerempuanChange = null;

    // Load summary data from API
    try {
      console.log('Fetching ketenagakerjaan data from:', `${API_BASE}/ketenagakerjaan-summary`);
      const response = await fetch(`${API_BASE}/ketenagakerjaan-summary`);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const result = await response.json();
      console.log('Ketenagakerjaan API Response:', result);
      
      if (result.success && result.data) {
        const data = result.data;
        console.log('Ketenagakerjaan Data:', {
          tpt_data_count: Array.isArray(data.tpt_data) ? data.tpt_data.length : 0,
          tpak_data_count: Array.isArray(data.tpak_data) ? data.tpak_data.length : 0,
          tpt_latest_data: data.tpt_latest_data,
          tpak_latest_data: data.tpak_latest_data,
          tpt_previous_data: data.tpt_previous_data,
          tpak_previous_data: data.tpak_previous_data,
        });
        
        // Helper function to convert string to number
        const parseNumber = (value) => {
          if (value === null || value === undefined) return null;
          const num = typeof value === 'string' ? parseFloat(value) : Number(value);
          return isNaN(num) ? null : num;
        };
        
        // Process TPT data - convert strings to numbers
        tptData = Array.isArray(data.tpt_data) ? data.tpt_data.map(item => ({
          id: item.id,
          year: parseInt(item.year),
          laki_laki: parseNumber(item.laki_laki),
          perempuan: parseNumber(item.perempuan),
          total: parseNumber(item.total)
        })) : [];
        
        // Process TPAK data - convert strings to numbers
        tpakData = Array.isArray(data.tpak_data) ? data.tpak_data.map(item => ({
          id: item.id,
          year: parseInt(item.year),
          laki_laki: parseNumber(item.laki_laki),
          perempuan: parseNumber(item.perempuan),
          total: parseNumber(item.total)
        })) : [];
        
        // Process latest data - convert strings to numbers
        if (data.tpt_latest_data) {
          tptLatestData = {
            id: data.tpt_latest_data.id,
            year: parseInt(data.tpt_latest_data.year),
            laki_laki: parseNumber(data.tpt_latest_data.laki_laki),
            perempuan: parseNumber(data.tpt_latest_data.perempuan),
            total: parseNumber(data.tpt_latest_data.total)
          };
        }
        
        if (data.tpak_latest_data) {
          tpakLatestData = {
            id: data.tpak_latest_data.id,
            year: parseInt(data.tpak_latest_data.year),
            laki_laki: parseNumber(data.tpak_latest_data.laki_laki),
            perempuan: parseNumber(data.tpak_latest_data.perempuan),
            total: parseNumber(data.tpak_latest_data.total)
          };
        }
        
        // Process previous data - convert strings to numbers
        if (data.tpt_previous_data) {
          tptPreviousData = {
            id: data.tpt_previous_data.id,
            year: parseInt(data.tpt_previous_data.year),
            laki_laki: parseNumber(data.tpt_previous_data.laki_laki),
            perempuan: parseNumber(data.tpt_previous_data.perempuan),
            total: parseNumber(data.tpt_previous_data.total)
          };
        }
        
        if (data.tpak_previous_data) {
          tpakPreviousData = {
            id: data.tpak_previous_data.id,
            year: parseInt(data.tpak_previous_data.year),
            laki_laki: parseNumber(data.tpak_previous_data.laki_laki),
            perempuan: parseNumber(data.tpak_previous_data.perempuan),
            total: parseNumber(data.tpak_previous_data.total)
          };
        }
        
        // Process changes - already numbers but ensure they are
        tptTotalChange = parseNumber(data.tpt_total_change);
        tpakTotalChange = parseNumber(data.tpak_total_change);
        tptLakiLakiChange = parseNumber(data.tpt_laki_laki_change);
        tpakLakiLakiChange = parseNumber(data.tpak_laki_laki_change);
        tptPerempuanChange = parseNumber(data.tpt_perempuan_change);
        tpakPerempuanChange = parseNumber(data.tpak_perempuan_change);
        
        console.log('Processed ketenagakerjaan data:', {
          tptDataLength: tptData.length,
          tpakDataLength: tpakData.length,
          tptLatestData: tptLatestData,
          tpakLatestData: tpakLatestData,
          tptLatestDataTotal: tptLatestData ? tptLatestData.total : null,
          tpakLatestDataTotal: tpakLatestData ? tpakLatestData.total : null
        });
      } else {
        console.error('Failed to load ketenagakerjaan summary data:', result.message || 'Unknown error');
        console.error('Full result:', result);
      }
    } catch (error) {
      console.error('Error loading ketenagakerjaan summary data:', error);
    }

    // Sort data by year
    tptData.sort((a, b) => a.year - b.year);
    tpakData.sort((a, b) => a.year - b.year);

    // Function to update UI with loaded data
    function updateUI() {
      // Update TPT Summary Card
      if (tptLatestData) {
        const tptTotalEl = document.getElementById('tpt-total-value');
        const tptLakiEl = document.getElementById('tpt-laki-value');
        const tptPerempuanEl = document.getElementById('tpt-perempuan-value');
        const tptYearEl = document.getElementById('tpt-year-value');
        const tptChangeEl = document.getElementById('tpt-change-value');
        
        if (tptTotalEl) tptTotalEl.textContent = tptLatestData.total !== null ? tptLatestData.total.toFixed(2) + '%' : '-';
        if (tptLakiEl) tptLakiEl.textContent = tptLatestData.laki_laki !== null ? tptLatestData.laki_laki.toFixed(2) + '%' : '-';
        if (tptPerempuanEl) tptPerempuanEl.textContent = tptLatestData.perempuan !== null ? tptLatestData.perempuan.toFixed(2) + '%' : '-';
        if (tptYearEl) tptYearEl.textContent = tptLatestData.year ? `Tahun ${tptLatestData.year}` : 'Data tidak tersedia';
        
        if (tptChangeEl && tptTotalChange !== null) {
          let changeHtml = '';
          if (tptTotalChange > 0) {
            changeHtml = `<span style="color: #28a745; font-size: 12px;">▲</span>
              <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${tptTotalChange.toFixed(2)}%</span>`;
          } else if (tptTotalChange < 0) {
            changeHtml = `<span style="color: #dc3545; font-size: 12px;">▼</span>
              <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${tptTotalChange.toFixed(2)}%</span>`;
          } else {
            changeHtml = '<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>';
          }
          if (tptPreviousData && tptPreviousData.year) {
            changeHtml += `<span style="color: rgba(255, 255, 255, 0.7); font-size: 10px;"> dari ${tptPreviousData.year}</span>`;
          }
          tptChangeEl.innerHTML = changeHtml;
        }
      }
      
      // Update TPAK Summary Card
      if (tpakLatestData) {
        const tpakTotalEl = document.getElementById('tpak-total-value');
        const tpakLakiEl = document.getElementById('tpak-laki-value');
        const tpakPerempuanEl = document.getElementById('tpak-perempuan-value');
        const tpakYearEl = document.getElementById('tpak-year-value');
        const tpakChangeEl = document.getElementById('tpak-change-value');
        
        if (tpakTotalEl) tpakTotalEl.textContent = tpakLatestData.total !== null ? tpakLatestData.total.toFixed(2) + '%' : '-';
        if (tpakLakiEl) tpakLakiEl.textContent = tpakLatestData.laki_laki !== null ? tpakLatestData.laki_laki.toFixed(2) + '%' : '-';
        if (tpakPerempuanEl) tpakPerempuanEl.textContent = tpakLatestData.perempuan !== null ? tpakLatestData.perempuan.toFixed(2) + '%' : '-';
        if (tpakYearEl) tpakYearEl.textContent = tpakLatestData.year ? `Tahun ${tpakLatestData.year}` : 'Data tidak tersedia';
        
        if (tpakChangeEl && tpakTotalChange !== null) {
          let changeHtml = '';
          if (tpakTotalChange > 0) {
            changeHtml = `<span style="color: #28a745; font-size: 12px;">▲</span>
              <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${tpakTotalChange.toFixed(2)}%</span>`;
          } else if (tpakTotalChange < 0) {
            changeHtml = `<span style="color: #dc3545; font-size: 12px;">▼</span>
              <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${tpakTotalChange.toFixed(2)}%</span>`;
          } else {
            changeHtml = '<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>';
          }
          if (tpakPreviousData && tpakPreviousData.year) {
            changeHtml += `<span style="color: rgba(255, 255, 255, 0.7); font-size: 10px;"> dari ${tpakPreviousData.year}</span>`;
          }
          tpakChangeEl.innerHTML = changeHtml;
        }
      }
      
      // Update TPT Tab Summary Cards
      updateTPTTabCards();
      
      // Update TPAK Tab Summary Cards
      updateTPAKTabCards();
    }
    
    // Function to update TPT tab summary cards
    function updateTPTTabCards() {
      if (!tptLatestData) return;
      
      // Total TPT
      const tptTabTotalEl = document.getElementById('tpt-tab-total-value');
      const tptTabTotalChangeEl = document.getElementById('tpt-tab-total-change');
      const tptTabTotalYearEl = document.getElementById('tpt-tab-total-year');
      
      if (tptTabTotalEl) {
        tptTabTotalEl.textContent = tptLatestData.total !== null ? tptLatestData.total.toFixed(2) + '%' : '-';
      }
      
      if (tptTabTotalChangeEl && tptTotalChange !== null) {
        let changeHtml = '';
        if (tptTotalChange > 0) {
          changeHtml = `<span style="color: #28a745; font-size: 12px;">▲</span>
            <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${tptTotalChange.toFixed(2)}%</span>`;
        } else if (tptTotalChange < 0) {
          changeHtml = `<span style="color: #dc3545; font-size: 12px;">▼</span>
            <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${tptTotalChange.toFixed(2)}%</span>`;
        } else {
          changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>`;
        }
        if (tptPreviousData && tptPreviousData.year) {
          changeHtml += `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari ${tptPreviousData.year}</span>`;
        }
        tptTabTotalChangeEl.innerHTML = changeHtml;
      }
      
      if (tptTabTotalYearEl) {
        tptTabTotalYearEl.textContent = tptLatestData.year ? `Tahun ${tptLatestData.year}` : 'Data tidak tersedia';
      }
      
      // Laki-Laki TPT
      const tptTabLakiEl = document.getElementById('tpt-tab-laki-value');
      const tptTabLakiChangeEl = document.getElementById('tpt-tab-laki-change');
      const tptTabLakiYearEl = document.getElementById('tpt-tab-laki-year');
      
      if (tptTabLakiEl) {
        tptTabLakiEl.textContent = tptLatestData.laki_laki !== null ? tptLatestData.laki_laki.toFixed(2) + '%' : '-';
      }
      
      if (tptTabLakiChangeEl && tptLakiLakiChange !== null) {
        let changeHtml = '';
        if (tptLakiLakiChange > 0) {
          changeHtml = `<span style="color: #28a745; font-size: 12px;">▲</span>
            <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${tptLakiLakiChange.toFixed(2)}%</span>`;
        } else if (tptLakiLakiChange < 0) {
          changeHtml = `<span style="color: #dc3545; font-size: 12px;">▼</span>
            <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${tptLakiLakiChange.toFixed(2)}%</span>`;
        } else {
          changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>`;
        }
        if (tptPreviousData && tptPreviousData.year) {
          changeHtml += `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari ${tptPreviousData.year}</span>`;
        }
        tptTabLakiChangeEl.innerHTML = changeHtml;
      }
      
      if (tptTabLakiYearEl) {
        tptTabLakiYearEl.textContent = tptLatestData.year ? `Tahun ${tptLatestData.year}` : 'Data tidak tersedia';
      }
      
      // Perempuan TPT
      const tptTabPerempuanEl = document.getElementById('tpt-tab-perempuan-value');
      const tptTabPerempuanChangeEl = document.getElementById('tpt-tab-perempuan-change');
      const tptTabPerempuanYearEl = document.getElementById('tpt-tab-perempuan-year');
      
      if (tptTabPerempuanEl) {
        tptTabPerempuanEl.textContent = tptLatestData.perempuan !== null ? tptLatestData.perempuan.toFixed(2) + '%' : '-';
      }
      
      if (tptTabPerempuanChangeEl && tptPerempuanChange !== null) {
        let changeHtml = '';
        if (tptPerempuanChange > 0) {
          changeHtml = `<span style="color: #28a745; font-size: 12px;">▲</span>
            <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${tptPerempuanChange.toFixed(2)}%</span>`;
        } else if (tptPerempuanChange < 0) {
          changeHtml = `<span style="color: #dc3545; font-size: 12px;">▼</span>
            <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${tptPerempuanChange.toFixed(2)}%</span>`;
        } else {
          changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>`;
        }
        if (tptPreviousData && tptPreviousData.year) {
          changeHtml += `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari ${tptPreviousData.year}</span>`;
        }
        tptTabPerempuanChangeEl.innerHTML = changeHtml;
      }
      
      if (tptTabPerempuanYearEl) {
        tptTabPerempuanYearEl.textContent = tptLatestData.year ? `Tahun ${tptLatestData.year}` : 'Data tidak tersedia';
      }
      
      // Update pie chart title year
      const tptPieYearEl = document.getElementById('tpt-pie-year');
      if (tptPieYearEl) {
        tptPieYearEl.textContent = tptLatestData.year ? tptLatestData.year : '-';
      }
    }
    
    // Function to update TPAK tab summary cards
    function updateTPAKTabCards() {
      if (!tpakLatestData) return;
      
      // Total TPAK
      const tpakTabTotalEl = document.getElementById('tpak-tab-total-value');
      const tpakTabTotalChangeEl = document.getElementById('tpak-tab-total-change');
      const tpakTabTotalYearEl = document.getElementById('tpak-tab-total-year');
      
      if (tpakTabTotalEl) {
        tpakTabTotalEl.textContent = tpakLatestData.total !== null ? tpakLatestData.total.toFixed(2) + '%' : '-';
      }
      
      if (tpakTabTotalChangeEl && tpakTotalChange !== null) {
        let changeHtml = '';
        if (tpakTotalChange > 0) {
          changeHtml = `<span style="color: #28a745; font-size: 12px;">▲</span>
            <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${tpakTotalChange.toFixed(2)}%</span>`;
        } else if (tpakTotalChange < 0) {
          changeHtml = `<span style="color: #dc3545; font-size: 12px;">▼</span>
            <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${tpakTotalChange.toFixed(2)}%</span>`;
        } else {
          changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>`;
        }
        if (tpakPreviousData && tpakPreviousData.year) {
          changeHtml += `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari ${tpakPreviousData.year}</span>`;
        }
        tpakTabTotalChangeEl.innerHTML = changeHtml;
      }
      
      if (tpakTabTotalYearEl) {
        tpakTabTotalYearEl.textContent = tpakLatestData.year ? `Tahun ${tpakLatestData.year}` : 'Data tidak tersedia';
      }
      
      // Laki-Laki TPAK
      const tpakTabLakiEl = document.getElementById('tpak-tab-laki-value');
      const tpakTabLakiChangeEl = document.getElementById('tpak-tab-laki-change');
      const tpakTabLakiYearEl = document.getElementById('tpak-tab-laki-year');
      
      if (tpakTabLakiEl) {
        tpakTabLakiEl.textContent = tpakLatestData.laki_laki !== null ? tpakLatestData.laki_laki.toFixed(2) + '%' : '-';
      }
      
      if (tpakTabLakiChangeEl && tpakLakiLakiChange !== null) {
        let changeHtml = '';
        if (tpakLakiLakiChange > 0) {
          changeHtml = `<span style="color: #28a745; font-size: 12px;">▲</span>
            <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${tpakLakiLakiChange.toFixed(2)}%</span>`;
        } else if (tpakLakiLakiChange < 0) {
          changeHtml = `<span style="color: #dc3545; font-size: 12px;">▼</span>
            <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${tpakLakiLakiChange.toFixed(2)}%</span>`;
        } else {
          changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>`;
        }
        if (tpakPreviousData && tpakPreviousData.year) {
          changeHtml += `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari ${tpakPreviousData.year}</span>`;
        }
        tpakTabLakiChangeEl.innerHTML = changeHtml;
      }
      
      if (tpakTabLakiYearEl) {
        tpakTabLakiYearEl.textContent = tpakLatestData.year ? `Tahun ${tpakLatestData.year}` : 'Data tidak tersedia';
      }
      
      // Perempuan TPAK
      const tpakTabPerempuanEl = document.getElementById('tpak-tab-perempuan-value');
      const tpakTabPerempuanChangeEl = document.getElementById('tpak-tab-perempuan-change');
      const tpakTabPerempuanYearEl = document.getElementById('tpak-tab-perempuan-year');
      
      if (tpakTabPerempuanEl) {
        tpakTabPerempuanEl.textContent = tpakLatestData.perempuan !== null ? tpakLatestData.perempuan.toFixed(2) + '%' : '-';
      }
      
      if (tpakTabPerempuanChangeEl && tpakPerempuanChange !== null) {
        let changeHtml = '';
        if (tpakPerempuanChange > 0) {
          changeHtml = `<span style="color: #28a745; font-size: 12px;">▲</span>
            <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${tpakPerempuanChange.toFixed(2)}%</span>`;
        } else if (tpakPerempuanChange < 0) {
          changeHtml = `<span style="color: #dc3545; font-size: 12px;">▼</span>
            <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${tpakPerempuanChange.toFixed(2)}%</span>`;
        } else {
          changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>`;
        }
        if (tpakPreviousData && tpakPreviousData.year) {
          changeHtml += `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari ${tpakPreviousData.year}</span>`;
        }
        tpakTabPerempuanChangeEl.innerHTML = changeHtml;
      }
      
      if (tpakTabPerempuanYearEl) {
        tpakTabPerempuanYearEl.textContent = tpakLatestData.year ? `Tahun ${tpakLatestData.year}` : 'Data tidak tersedia';
      }
      
      // Update pie chart title year
      const tpakPieYearEl = document.getElementById('tpak-pie-year');
      if (tpakPieYearEl) {
        tpakPieYearEl.textContent = tpakLatestData.year ? tpakLatestData.year : '-';
      }
    }

    // Update UI after data is loaded
    updateUI();

    // Check if mobile
    const isMobile = window.innerWidth <= 767.98;
    
    // Adjust chart height for mobile
    let comparisonChartDom = document.getElementById('comparisonChart');
    if (isMobile && comparisonChartDom) {
      comparisonChartDom.style.height = '350px';
    }

    // TPT Pie Chart
    const tptPieChartDom = document.getElementById('tptPieChart');
    let tptPieChart = null;
    if (tptPieChartDom) {
      tptPieChart = echarts.init(tptPieChartDom);
    }
    
    // Use tptLatestData from API, fallback to last item in array if not available
    const tptLatestDataForChart = tptLatestData || (tptData.length > 0 ? tptData[tptData.length - 1] : null);
    const tptPieData = [];
    
    if (tptLatestDataForChart && tptLatestDataForChart.laki_laki !== null) {
      tptPieData.push({
        name: 'Laki-Laki',
        value: tptLatestDataForChart.laki_laki
      });
    }
    
    if (tptLatestDataForChart && tptLatestDataForChart.perempuan !== null) {
      tptPieData.push({
        name: 'Perempuan',
        value: tptLatestDataForChart.perempuan
      });
    }

    if (tptPieChart) {
    tptPieChart.setOption({
      tooltip: {
        trigger: 'item',
        formatter: '{a} <br/>{b}: {c}% ({d}%)',
        hideDelay: 50,
        enterable: false,
        confine: true
      },
      legend: {
        orient: 'horizontal',
        left: 'center',
        top: 'bottom',
        data: tptPieData.map(function(item) { return item.name; }),
        itemGap: isMobile ? 10 : 20,
        textStyle: {
          fontSize: isMobile ? 10 : 12
        }
      },
      series: [
        {
          name: 'TPT',
          type: 'pie',
          radius: ['40%', '70%'],
          center: ['50%', '45%'],
          avoidLabelOverlap: false,
          itemStyle: {
            borderRadius: 10,
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
            scale: true,
            scaleSize: 5,
            itemStyle: {
              shadowBlur: 10,
              shadowOffsetX: 0,
              shadowColor: 'rgba(0, 0, 0, 0.3)'
            }
          },
          select: {
            disabled: true
          },
          data: tptPieData,
          color: ['#3b82f6', '#f093fb']
        }
      ]
    });
    }

    // Reset chart state on mouse leave
    if (tptPieChartDom && tptPieChart) {
    tptPieChartDom.addEventListener('mouseleave', function() {
      setTimeout(function() {
        tptPieChart.dispatchAction({ type: 'downplay' });
      }, 100);
    });
    }

    // TPT Line Chart
    const tptLineChartDom = document.getElementById('tptLineChart');
    let tptLineChart = null;
    if (tptLineChartDom) {
      tptLineChart = echarts.init(tptLineChartDom);
    }
    
    // Filter TPT data starting from 2017
    const tptFilteredData = tptData.filter(d => d.year >= 2017);
    const tptYears = tptFilteredData.map(d => d.year.toString());
    const tptTotalValues = tptFilteredData.map(d => {
      if (d.total !== null && d.total !== undefined) {
        return typeof d.total === 'number' ? d.total : parseFloat(d.total);
      }
      return null;
    });
    const tptLakiLakiValues = tptFilteredData.map(d => {
      if (d.laki_laki !== null && d.laki_laki !== undefined) {
        return typeof d.laki_laki === 'number' ? d.laki_laki : parseFloat(d.laki_laki);
      }
      return null;
    });
    const tptPerempuanValues = tptFilteredData.map(d => {
      if (d.perempuan !== null && d.perempuan !== undefined) {
        return typeof d.perempuan === 'number' ? d.perempuan : parseFloat(d.perempuan);
      }
      return null;
    });

    if (tptLineChart) {
    tptLineChart.setOption({
      tooltip: {
        trigger: 'axis',
        axisPointer: { 
          type: 'line',
          snap: true,
          lineStyle: {
            type: 'dashed'
          },
          label: {
            show: false
          }
        },
        hideDelay: 50,
        enterable: false,
        confine: true,
        formatter: function(params) {
          let result = 'Tahun: ' + params[0].axisValue + '<br/>';
          params.forEach(function(item) {
            result += item.marker + item.seriesName + ': ' + 
              (item.value !== null ? item.value.toFixed(2) + '%' : 'Data tidak tersedia') + '<br/>';
          });
          return result;
        }
      },
      legend: {
        data: ['Total', 'Laki-Laki', 'Perempuan'],
        textStyle: {
          fontSize: isMobile ? 10 : 12
        },
        itemGap: isMobile ? 10 : 20
      },
      grid: {
        left: isMobile ? '15%' : '12%',
        right: isMobile ? '8%' : '4%',
        bottom: isMobile ? '12%' : '10%',
        top: isMobile ? '12%' : '20%',
        containLabel: false
      },
      xAxis: {
        type: 'category',
        data: tptYears,
        boundaryGap: false,
        axisLabel: {
          fontSize: isMobile ? 10 : 12,
          margin: isMobile ? 5 : 10
        }
      },
      yAxis: {
        type: 'value',
        name: 'TPT (%)',
        position: 'left',
        nameLocation: 'end',
        nameGap: isMobile ? 5 : 10,
        nameTextStyle: {
          padding: [0, 0, 0, 0],
          fontSize: isMobile ? 10 : 12
        },
        axisLabel: {
          formatter: '{value}%',
          fontSize: isMobile ? 10 : 12,
          margin: isMobile ? 8 : 10
        }
      },
      series: [
        {
          name: 'Total',
          type: 'line',
          data: tptTotalValues,
          itemStyle: { color: '#667eea' },
          lineStyle: { width: 3 },
          symbol: 'circle',
          symbolSize: 8,
          smooth: true
        },
        {
          name: 'Laki-Laki',
          type: 'line',
          data: tptLakiLakiValues,
          itemStyle: { color: '#3b82f6' },
          lineStyle: { width: 2 },
          symbol: 'circle',
          symbolSize: 6,
          smooth: true
        },
        {
          name: 'Perempuan',
          type: 'line',
          data: tptPerempuanValues,
          itemStyle: { color: '#f093fb' },
          lineStyle: { width: 2 },
          symbol: 'circle',
          symbolSize: 6,
          smooth: true
        }
      ]
    });
    }

    // TPAK Pie Chart
    const tpakPieChartDom = document.getElementById('tpakPieChart');
    let tpakPieChart = null;
    if (tpakPieChartDom) {
      tpakPieChart = echarts.init(tpakPieChartDom);
    }
    
    // Use tpakLatestData from API, fallback to last item in array if not available
    const tpakLatestDataForChart = tpakLatestData || (tpakData.length > 0 ? tpakData[tpakData.length - 1] : null);
    const tpakPieData = [];
    
    if (tpakLatestDataForChart && tpakLatestDataForChart.laki_laki !== null) {
      const lakiValue = typeof tpakLatestDataForChart.laki_laki === 'number' 
        ? tpakLatestDataForChart.laki_laki 
        : parseFloat(tpakLatestDataForChart.laki_laki);
      if (!isNaN(lakiValue)) {
        tpakPieData.push({
          name: 'Laki-Laki',
          value: lakiValue
        });
      }
    }
    
    if (tpakLatestDataForChart && tpakLatestDataForChart.perempuan !== null) {
      const perempuanValue = typeof tpakLatestDataForChart.perempuan === 'number' 
        ? tpakLatestDataForChart.perempuan 
        : parseFloat(tpakLatestDataForChart.perempuan);
      if (!isNaN(perempuanValue)) {
        tpakPieData.push({
          name: 'Perempuan',
          value: perempuanValue
        });
      }
    }

    if (tpakPieChart) {
    tpakPieChart.setOption({
      tooltip: {
        trigger: 'item',
        formatter: '{a} <br/>{b}: {c}% ({d}%)',
        hideDelay: 50,
        enterable: false,
        confine: true
      },
      legend: {
        orient: 'horizontal',
        left: 'center',
        top: 'bottom',
        data: tpakPieData.map(function(item) { return item.name; }),
        itemGap: isMobile ? 10 : 20,
        textStyle: {
          fontSize: isMobile ? 10 : 12
        }
      },
      series: [
        {
          name: 'TPAK',
          type: 'pie',
          radius: ['40%', '70%'],
          center: ['50%', '45%'],
          avoidLabelOverlap: false,
          itemStyle: {
            borderRadius: 10,
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
            scale: true,
            scaleSize: 5,
            itemStyle: {
              shadowBlur: 10,
              shadowOffsetX: 0,
              shadowColor: 'rgba(0, 0, 0, 0.3)'
            }
          },
          select: {
            disabled: true
          },
          data: tpakPieData,
          color: ['#43e97b', '#fa709a']
        }
      ]
    });
    }

    // Reset chart state on mouse leave
    if (tpakPieChartDom && tpakPieChart) {
    tpakPieChartDom.addEventListener('mouseleave', function() {
      setTimeout(function() {
        tpakPieChart.dispatchAction({ type: 'downplay' });
      }, 100);
    });
    }

    // TPAK Line Chart
    const tpakLineChartDom = document.getElementById('tpakLineChart');
    let tpakLineChart = null;
    if (tpakLineChartDom) {
      tpakLineChart = echarts.init(tpakLineChartDom);
    }
    
    // Filter TPAK data starting from 2017
    const tpakFilteredData = tpakData.filter(d => d.year >= 2017);
    const tpakYears = tpakFilteredData.map(d => d.year.toString());
    const tpakTotalValues = tpakFilteredData.map(d => {
      if (d.total !== null && d.total !== undefined) {
        return typeof d.total === 'number' ? d.total : parseFloat(d.total);
      }
      return null;
    });
    const tpakLakiLakiValues = tpakFilteredData.map(d => {
      if (d.laki_laki !== null && d.laki_laki !== undefined) {
        return typeof d.laki_laki === 'number' ? d.laki_laki : parseFloat(d.laki_laki);
      }
      return null;
    });
    const tpakPerempuanValues = tpakFilteredData.map(d => {
      if (d.perempuan !== null && d.perempuan !== undefined) {
        return typeof d.perempuan === 'number' ? d.perempuan : parseFloat(d.perempuan);
      }
      return null;
    });

    if (tpakLineChart) {
    tpakLineChart.setOption({
      tooltip: {
        trigger: 'axis',
        axisPointer: { 
          type: 'line',
          snap: true,
          lineStyle: {
            type: 'dashed'
          },
          label: {
            show: false
          }
        },
        hideDelay: 50,
        enterable: false,
        confine: true,
        formatter: function(params) {
          let result = 'Tahun: ' + params[0].axisValue + '<br/>';
          params.forEach(function(item) {
            result += item.marker + item.seriesName + ': ' + 
              (item.value !== null ? item.value.toFixed(2) + '%' : 'Data tidak tersedia') + '<br/>';
          });
          return result;
        }
      },
      legend: {
        data: ['Total', 'Laki-Laki', 'Perempuan'],
        textStyle: {
          fontSize: isMobile ? 10 : 12
        },
        itemGap: isMobile ? 10 : 20
      },
      grid: {
        left: isMobile ? '15%' : '12%',
        right: isMobile ? '8%' : '4%',
        bottom: isMobile ? '12%' : '10%',
        top: isMobile ? '12%' : '20%',
        containLabel: false
      },
      xAxis: {
        type: 'category',
        data: tpakYears,
        boundaryGap: false,
        axisLabel: {
          fontSize: isMobile ? 10 : 12,
          margin: isMobile ? 5 : 10
        }
      },
      yAxis: {
        type: 'value',
        name: 'TPAK (%)',
        position: 'left',
        nameLocation: 'end',
        nameGap: isMobile ? 5 : 10,
        nameTextStyle: {
          padding: [0, 0, 0, 0],
          fontSize: isMobile ? 10 : 12
        },
        axisLabel: {
          formatter: '{value}%',
          fontSize: isMobile ? 10 : 12,
          margin: isMobile ? 8 : 10
        }
      },
      series: [
        {
          name: 'Total',
          type: 'line',
          data: tpakTotalValues,
          itemStyle: { color: '#4facfe' },
          lineStyle: { width: 3 },
          symbol: 'circle',
          symbolSize: 8,
          smooth: true
        },
        {
          name: 'Laki-Laki',
          type: 'line',
          data: tpakLakiLakiValues,
          itemStyle: { color: '#43e97b' },
          lineStyle: { width: 2 },
          symbol: 'circle',
          symbolSize: 6,
          smooth: true
        },
        {
          name: 'Perempuan',
          type: 'line',
          data: tpakPerempuanValues,
          itemStyle: { color: '#fa709a' },
          lineStyle: { width: 2 },
          symbol: 'circle',
          symbolSize: 6,
          smooth: true
        }
      ]
    });
    }

    // Comparison Chart - TPT vs TPAK
    // comparisonChartDom already declared above, just initialize chart
    let comparisonChart = null;
    if (comparisonChartDom) {
      try {
        comparisonChart = echarts.init(comparisonChartDom);
        console.log('Comparison chart initialized');
      } catch (e) {
        console.error('Error initializing comparison chart:', e);
      }
    } else {
      console.error('Comparison chart element not found');
    }
    
    // Get all unique years from both datasets
    const allYearsSet = new Set([...tptData.map(d => d.year), ...tpakData.map(d => d.year)]);
    const allYears = Array.from(allYearsSet).sort((a, b) => a - b);
    
    // Filter years starting from 2017
    const filteredYears = allYears.filter(year => year >= 2017);
    const years = filteredYears.map(y => y.toString());
    
    // Get TPT total values for years from 2017
    const tptComparisonValues = filteredYears.map(year => {
      const data = tptData.find(d => d.year === year);
      if (data && data.total !== null && data.total !== undefined) {
        return typeof data.total === 'number' ? data.total : parseFloat(data.total);
      }
      return null;
    });
    
    // Get TPAK total values for years from 2017
    const tpakComparisonValues = filteredYears.map(year => {
      const data = tpakData.find(d => d.year === year);
      if (data && data.total !== null && data.total !== undefined) {
        return typeof data.total === 'number' ? data.total : parseFloat(data.total);
      }
      return null;
    });

    if (comparisonChart) {
    comparisonChart.setOption({
      tooltip: {
        trigger: 'axis',
        axisPointer: { 
          type: 'line',
          snap: true,
          lineStyle: {
            type: 'dashed'
          },
          label: {
            show: false
          }
        },
        hideDelay: 50,
        enterable: false,
        confine: true,
        formatter: function(params) {
          let result = 'Tahun: ' + params[0].axisValue + '<br/>';
          params.forEach(function(item) {
            result += item.marker + item.seriesName + ': ' + 
              (item.value !== null ? item.value.toFixed(2) + '%' : 'Data tidak tersedia') + '<br/>';
          });
          return result;
        }
      },
      legend: {
        data: ['TPT', 'TPAK'],
        top: 10,
        textStyle: {
          fontSize: isMobile ? 10 : 12
        },
        itemGap: isMobile ? 10 : 20
      },
      grid: {
        left: isMobile ? '12%' : '12%',
        right: isMobile ? '5%' : '4%',
        bottom: isMobile ? '8%' : '10%',
        top: isMobile ? '8%' : '20%',
        containLabel: false
      },
      xAxis: {
        type: 'category',
        data: years,
        boundaryGap: false,
        axisLabel: {
          fontSize: isMobile ? 10 : 12,
          margin: isMobile ? 5 : 10
        }
      },
      yAxis: {
        type: 'value',
        name: 'Persentase (%)',
        position: 'left',
        nameLocation: 'end',
        nameGap: isMobile ? 5 : 10,
        nameTextStyle: {
          padding: [0, 0, 0, 0],
          fontSize: isMobile ? 10 : 12
        },
        axisLabel: {
          formatter: '{value}%',
          fontSize: isMobile ? 10 : 12,
          margin: isMobile ? 8 : 10
        }
      },
      series: [
        {
          name: 'TPT',
          type: 'line',
          data: tptComparisonValues,
          itemStyle: { color: '#667eea' },
          lineStyle: { width: 3 },
          symbol: 'circle',
          symbolSize: 8,
          smooth: true,
          areaStyle: {
            color: {
              type: 'linear',
              x: 0,
              y: 0,
              x2: 0,
              y2: 1,
              colorStops: [
                { offset: 0, color: 'rgba(102, 126, 234, 0.3)' },
                { offset: 1, color: 'rgba(102, 126, 234, 0.05)' }
              ]
            }
          }
        },
        {
          name: 'TPAK',
          type: 'line',
          data: tpakComparisonValues,
          itemStyle: { color: '#4facfe' },
          lineStyle: { width: 3 },
          symbol: 'circle',
          symbolSize: 8,
          smooth: true,
          areaStyle: {
            color: {
              type: 'linear',
              x: 0,
              y: 0,
              x2: 0,
              y2: 1,
              colorStops: [
                { offset: 0, color: 'rgba(79, 172, 254, 0.3)' },
                { offset: 1, color: 'rgba(79, 172, 254, 0.05)' }
              ]
            }
          }
        }
      ]
    });
    }

    // Initialize charts when tabs are shown
    const tptTab = document.getElementById('tpt-tab');
    const tpakTab = document.getElementById('tpak-tab');
    const tptPane = document.getElementById('tpt');
    const tpakPane = document.getElementById('tpak');

    function initializeTPTCharts() {
      if (tptPane && tptPane.classList.contains('active')) {
        setTimeout(() => {
          if (tptPieChart && tptPieChartDom) {
          tptPieChart.resize();
          }
          if (tptLineChart && tptLineChartDom) {
          tptLineChart.resize();
          }
        }, 200);
      }
    }

    function initializeTPAKCharts() {
      if (tpakPane && tpakPane.classList.contains('active')) {
        setTimeout(() => {
          if (tpakPieChart && tpakPieChartDom) {
          tpakPieChart.resize();
          }
          if (tpakLineChart && tpakLineChartDom) {
          tpakLineChart.resize();
          }
        }, 200);
      }
    }

    // Initialize comparison chart immediately
    if (comparisonChart && comparisonChartDom) {
      setTimeout(() => {
        comparisonChart.resize();
        }, 100);
    }

    // Initialize on page load
    setTimeout(() => {
    initializeTPTCharts();
      if (comparisonChart && comparisonChartDom) {
        comparisonChart.resize();
      }
    }, 300);

    // Re-initialize when tabs are shown
    if (tptTab) {
    tptTab.addEventListener('shown.bs.tab', function () {
        setTimeout(() => {
      initializeTPTCharts();
        }, 150);
    });
    }

    if (tpakTab) {
    tpakTab.addEventListener('shown.bs.tab', function () {
        setTimeout(() => {
      initializeTPAKCharts();
        }, 150);
      });
    }
    
    // Also listen for click events on tabs
    if (tptTab) {
      tptTab.addEventListener('click', function () {
        setTimeout(() => {
          initializeTPTCharts();
        }, 200);
      });
    }
    
    if (tpakTab) {
      tpakTab.addEventListener('click', function () {
        setTimeout(() => {
          initializeTPAKCharts();
        }, 200);
    });
    }

    // Add event handlers to prevent stuck pointer on line charts
    function resetLineChart(chart, chartDom) {
      chart.dispatchAction({
        type: 'hideTip'
      });
      chart.dispatchAction({
        type: 'downplay'
      });
    }

    // TPT Line Chart event handlers
    if (tptLineChartDom && tptLineChart) {
    tptLineChartDom.addEventListener('mouseleave', function() {
      resetLineChart(tptLineChart, tptLineChartDom);
    });
    tptLineChartDom.addEventListener('click', function(e) {
      // Reset after a short delay to allow tooltip to show
      setTimeout(function() {
        resetLineChart(tptLineChart, tptLineChartDom);
      }, 200);
    });
    }

    // TPAK Line Chart event handlers
    if (tpakLineChartDom && tpakLineChart) {
    tpakLineChartDom.addEventListener('mouseleave', function() {
      resetLineChart(tpakLineChart, tpakLineChartDom);
    });
    tpakLineChartDom.addEventListener('click', function(e) {
      setTimeout(function() {
        resetLineChart(tpakLineChart, tpakLineChartDom);
      }, 200);
    });
    }

    // Comparison Chart event handlers
    if (comparisonChartDom && comparisonChart) {
      comparisonChartDom.addEventListener('mouseleave', function() {
        resetLineChart(comparisonChart, comparisonChartDom);
      });
      comparisonChartDom.addEventListener('click', function(e) {
        setTimeout(function() {
          resetLineChart(comparisonChart, comparisonChartDom);
        }, 200);
      });
    }

    // Resize all charts function
    function resizeAllCharts() {
      setTimeout(() => {
        try {
          if (typeof comparisonChart !== 'undefined' && comparisonChart && comparisonChartDom) {
            comparisonChart.resize();
          }
          if (typeof tptPieChart !== 'undefined' && tptPieChart && tptPieChartDom) {
            tptPieChart.resize();
          }
          if (typeof tptLineChart !== 'undefined' && tptLineChart && tptLineChartDom) {
            tptLineChart.resize();
          }
          if (typeof tpakPieChart !== 'undefined' && tpakPieChart && tpakPieChartDom) {
            tpakPieChart.resize();
          }
          if (typeof tpakLineChart !== 'undefined' && tpakLineChart && tpakLineChartDom) {
            tpakLineChart.resize();
          }
        } catch (e) {
          console.log('Chart resize error:', e);
        }
      }, 150);
    }

    // Handle window resize
    window.addEventListener('resize', function() {
      resizeAllCharts();
    });

    // Handle sidebar toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarCheck = document.getElementById('check');
    const sidebar = document.querySelector('.sidebar');
    const mainPanel = document.querySelector('.main-panel');

    if (sidebarToggle) {
      sidebarToggle.addEventListener('click', function() {
        setTimeout(() => {
          resizeAllCharts();
        }, 300);
      });
    }

    if (sidebarCheck) {
      sidebarCheck.addEventListener('change', function() {
        setTimeout(() => {
          resizeAllCharts();
        }, 300);
      });
    }

    // Use MutationObserver to detect sidebar changes
    if (sidebar) {
      const observer = new MutationObserver(function(mutations) {
        setTimeout(() => {
          resizeAllCharts();
        }, 300);
      });
      
      observer.observe(sidebar, {
        attributes: true,
        attributeFilter: ['class']
      });
    }

    // Helper function to check authentication before download
    function checkAuthBeforeDownload(callback, itemName = 'data') {
      <?php if(auth()->guard()->guest()): ?>
      // User not authenticated, show login modal
      if (typeof showLoginRequiredModal === 'function') {
        showLoginRequiredModal(itemName);
      } else {
        alert('Ingin mengunduh ' + itemName + ' ini? Silakan login terlebih dahulu.');
        const loginModal = document.getElementById('loginModal');
        if (loginModal) {
          const modal = new bootstrap.Modal(loginModal);
          modal.show();
        } else {
          window.location.href = '<?php echo e(route('login')); ?>';
        }
      }
      return false;
      <?php else: ?>
      // User authenticated, proceed with download
      callback();
      return true;
      <?php endif; ?>
    }

    // Export functions for Comparison Chart
    function exportComparisonToExcel() {
      const allYears = [...new Set([...tptData.map(d => d.year), ...tpakData.map(d => d.year)])].sort();
      // Filter years starting from 2017
      const filteredYears = allYears.filter(year => year >= 2017);
      const exportData = [];
      exportData.push(['Tahun', 'TPT (%)', 'TPAK (%)']);
      filteredYears.forEach(year => {
        const tpt = tptData.find(d => d.year === year);
        const tpak = tpakData.find(d => d.year === year);
        const tptVal = tpt && tpt.total !== null ? tpt.total.toFixed(2) : 'Data tidak tersedia';
        const tpakVal = tpak && tpak.total !== null ? tpak.total.toFixed(2) : 'Data tidak tersedia';
        exportData.push([year.toString(), tptVal, tpakVal]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 10 }, { wch: 15 }, { wch: 15 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Perbandingan');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Ketenagakerjaan_Perbandingan_${today}.xlsx`);
    }

    function exportComparisonToPNG() {
      const url = comparisonChart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
      const link = document.createElement('a');
      link.download = `Ketenagakerjaan_Perbandingan_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }

    document.getElementById('downloadComparisonExcel').addEventListener('click', function() {
      checkAuthBeforeDownload(exportComparisonToExcel, 'data perbandingan ketenagakerjaan');
    });
    document.getElementById('downloadComparisonPNG').addEventListener('click', function() {
      checkAuthBeforeDownload(exportComparisonToPNG, 'grafik perbandingan ketenagakerjaan');
    });

    // Export functions for TPT Pie Chart
    function exportTptPieToExcel() {
      const exportData = [];
      exportData.push(['Gender', 'TPT (%)']);
      if (tptLatestData) {
        if (tptLatestData.laki_laki !== null) {
          exportData.push(['Laki-Laki', tptLatestData.laki_laki.toFixed(2)]);
        }
        if (tptLatestData.perempuan !== null) {
          exportData.push(['Perempuan', tptLatestData.perempuan.toFixed(2)]);
        }
        if (tptLatestData.total !== null) {
          exportData.push(['Total', tptLatestData.total.toFixed(2)]);
        }
      }
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 15 }, { wch: 15 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data TPT Pie');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Ketenagakerjaan_TPT_Pie_${today}.xlsx`);
    }

    function exportTptPieToPNG() {
      const url = tptPieChart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
      const link = document.createElement('a');
      link.download = `Ketenagakerjaan_TPT_Pie_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }

    document.getElementById('downloadTptPieExcel').addEventListener('click', function() {
      checkAuthBeforeDownload(exportTptPieToExcel, 'data TPT pie chart');
    });
    document.getElementById('downloadTptPiePNG').addEventListener('click', function() {
      checkAuthBeforeDownload(exportTptPieToPNG, 'grafik TPT pie chart');
    });

    // Export functions for TPT Line Chart
    function exportTptLineToExcel() {
      const exportData = [];
      exportData.push(['Tahun', 'Total (%)', 'Laki-Laki (%)', 'Perempuan (%)']);
      tptData.forEach(data => {
        const total = data.total !== null ? data.total.toFixed(2) : 'Data tidak tersedia';
        const laki = data.laki_laki !== null ? data.laki_laki.toFixed(2) : 'Data tidak tersedia';
        const perempuan = data.perempuan !== null ? data.perempuan.toFixed(2) : 'Data tidak tersedia';
        exportData.push([data.year.toString(), total, laki, perempuan]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 10 }, { wch: 15 }, { wch: 15 }, { wch: 15 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data TPT Line');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Ketenagakerjaan_TPT_Line_${today}.xlsx`);
    }

    function exportTptLineToPNG() {
      const url = tptLineChart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
      const link = document.createElement('a');
      link.download = `Ketenagakerjaan_TPT_Line_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }

    document.getElementById('downloadTptLineExcel').addEventListener('click', function() {
      checkAuthBeforeDownload(exportTptLineToExcel, 'data TPT line chart');
    });
    document.getElementById('downloadTptLinePNG').addEventListener('click', function() {
      checkAuthBeforeDownload(exportTptLineToPNG, 'grafik TPT line chart');
    });

    // Export functions for TPAK Pie Chart
    function exportTpakPieToExcel() {
      const tpakLatestDataForExport = tpakLatestData || (tpakData.length > 0 ? tpakData[tpakData.length - 1] : null);
      const exportData = [];
      exportData.push(['Gender', 'TPAK (%)']);
      if (tpakLatestDataForExport) {
        if (tpakLatestDataForExport.laki_laki !== null) {
          exportData.push(['Laki-Laki', tpakLatestDataForExport.laki_laki.toFixed(2)]);
        }
        if (tpakLatestDataForExport.perempuan !== null) {
          exportData.push(['Perempuan', tpakLatestDataForExport.perempuan.toFixed(2)]);
        }
        if (tpakLatestDataForExport.total !== null) {
          exportData.push(['Total', tpakLatestDataForExport.total.toFixed(2)]);
        }
      }
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 15 }, { wch: 15 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data TPAK Pie');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Ketenagakerjaan_TPAK_Pie_${today}.xlsx`);
    }

    function exportTpakPieToPNG() {
      const url = tpakPieChart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
      const link = document.createElement('a');
      link.download = `Ketenagakerjaan_TPAK_Pie_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }

    document.getElementById('downloadTpakPieExcel').addEventListener('click', function() {
      checkAuthBeforeDownload(exportTpakPieToExcel, 'data TPAK pie chart');
    });
    document.getElementById('downloadTpakPiePNG').addEventListener('click', function() {
      checkAuthBeforeDownload(exportTpakPieToPNG, 'grafik TPAK pie chart');
    });

    // Export functions for TPAK Line Chart
    function exportTpakLineToExcel() {
      const exportData = [];
      exportData.push(['Tahun', 'Total (%)', 'Laki-Laki (%)', 'Perempuan (%)']);
      tpakData.forEach(data => {
        const total = data.total !== null ? data.total.toFixed(2) : 'Data tidak tersedia';
        const laki = data.laki_laki !== null ? data.laki_laki.toFixed(2) : 'Data tidak tersedia';
        const perempuan = data.perempuan !== null ? data.perempuan.toFixed(2) : 'Data tidak tersedia';
        exportData.push([data.year.toString(), total, laki, perempuan]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 10 }, { wch: 15 }, { wch: 15 }, { wch: 15 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data TPAK Line');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Ketenagakerjaan_TPAK_Line_${today}.xlsx`);
    }

    function exportTpakLineToPNG() {
      const url = tpakLineChart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
      const link = document.createElement('a');
      link.download = `Ketenagakerjaan_TPAK_Line_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }

    document.getElementById('downloadTpakLineExcel').addEventListener('click', function() {
      checkAuthBeforeDownload(exportTpakLineToExcel, 'data TPAK line chart');
    });
    document.getElementById('downloadTpakLinePNG').addEventListener('click', function() {
      checkAuthBeforeDownload(exportTpakLineToPNG, 'grafik TPAK line chart');
    });

    // Listen for transition end on main panel
    if (mainPanel) {
      mainPanel.addEventListener('transitionend', function() {
        resizeAllCharts();
      });
    }

    // Also listen for sidebar toggle buttons
    const sidebarToggleButtons = document.querySelectorAll('[data-toggle="sidebar"], .sidebar-toggle');
    sidebarToggleButtons.forEach(function(button) {
      button.addEventListener('click', function() {
        setTimeout(() => {
          resizeAllCharts();
        }, 300);
      });
    });
  });
</script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\astabaya\resources\views/dashboard/indikator/ketenagakerjaan.blade.php ENDPATH**/ ?>