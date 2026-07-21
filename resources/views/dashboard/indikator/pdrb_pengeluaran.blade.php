@extends('layouts.main')

@section('title', 'PDRB Pengeluaran')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
@endpush

@section('content')
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>

<div class="container py-4">
  <h3 class="font-weight-bold mb-4">PDRB Pengeluaran</h3>
  
  <!-- Infinite Carousel for Summary Cards - All Data -->
  <div class="row mb-4">
    <div class="col-md-12" style="padding:0px;">
      <div class="card">
        <div class="card-body" style="padding: 25px;">
          <div class="indicator-carousel-wrapper" style="position: relative; overflow: hidden; padding: 0;">
            <div class="indicator-carousel-track" id="pdrbSheetCarousel" style="display: flex; gap: 15px; will-change: transform;">
              <!-- Cards will be populated by JavaScript -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Toggle Buttons for Tahunan and Triwulanan -->
  <div class="row mb-4">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body" style="padding: 15px;">
          <div style="display: flex; gap: 10px; align-items: center;">
            <button id="btnTahunan" class="btn btn-primary" style="padding: 10px 20px; font-weight: 500; border-radius: 8px;">
              <i class="fas fa-calendar-alt me-2"></i>PDRB Tahunan
            </button>
            <button id="btnTriwulanan" class="btn btn-outline-primary" style="padding: 10px 20px; font-weight: 500; border-radius: 8px;">
              <i class="fas fa-calendar-week me-2"></i>PDRB Triwulanan
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Cards for Tahunan -->
  <div id="cardsTahunan">
    <div class="row mb-4">
      <!-- ADHB Card -->
      <div class="col-md-6 mb-3">
        <div class="dashboard-card" style="position: relative;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
            <h5 class="mb-0">ADHB <span style="font-size: 14px; font-weight: normal; color: #666;">(Rupiah)</span></h5>
            <div class="chart-header-actions">
              <x-chart-share-button chartId="adhbChart" title="ADHB PDRB Pengeluaran" />
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadAdhbDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                  <i class="fas fa-download"></i> <span>Unduh</span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="downloadAdhbDropdown" style="border-radius: 8px; min-width: 100%;">
                  <li><a class="dropdown-item" href="#" id="downloadAdhbExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                  <li><a class="dropdown-item" href="#" id="downloadAdhbPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="chart-scroll-container">
            <div id="adhbChart" class="chart-responsive" style="width: 100%; height: 400px;"></div>
          </div>
        </div>
      </div>

      <!-- ADHK Card -->
      <div class="col-md-6 mb-3">
        <div class="dashboard-card" style="position: relative;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
            <h5 class="mb-0">ADHK <span style="font-size: 14px; font-weight: normal; color: #666;">(Rupiah)</span></h5>
            <div class="chart-header-actions">
              <x-chart-share-button chartId="adhkChart" title="ADHK PDRB Pengeluaran" />
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadAdhkDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                  <i class="fas fa-download"></i> <span>Unduh</span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="downloadAdhkDropdown" style="border-radius: 8px; min-width: 100%;">
                  <li><a class="dropdown-item" href="#" id="downloadAdhkExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                  <li><a class="dropdown-item" href="#" id="downloadAdhkPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="chart-scroll-container">
            <div id="adhkChart" class="chart-responsive" style="width: 100%; height: 400px;"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-4">
      <!-- Distribusi Card -->
      <div class="col-md-6 mb-3">
        <div class="dashboard-card" style="position: relative;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
            <h5 class="mb-0">Distribusi - Komponen Pengeluaran <span style="font-size: 14px; font-weight: normal; color: #666;">(Persen)</span></h5>
            <div class="chart-header-actions">
              <x-chart-share-button chartId="distribusiChart" title="Distribusi PDRB Komponen Pengeluaran" />
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadDistribusiDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                  <i class="fas fa-download"></i> <span>Unduh</span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="downloadDistribusiDropdown" style="border-radius: 8px; min-width: 100%;">
                  <li><a class="dropdown-item" href="#" id="downloadDistribusiExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                  <li><a class="dropdown-item" href="#" id="downloadDistribusiPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div style="margin-bottom: 10px;">
            <label for="yearFilterDistribusi" style="margin: 0; font-weight: 500; color: #333; font-size: 12px; margin-bottom: 5px; display: block;">Tahun:</label>
            <select id="yearFilterDistribusi" class="form-control" style="width: 50%; padding: 5px 10px; font-size: 12px;">
              <option value="">Semua Tahun</option>
              <!-- Options will be populated by JavaScript -->
            </select>
          </div>
          <div class="chart-scroll-container">
            <div id="distribusiChart" class="chart-responsive" style="width: 100%; height: 400px;"></div>
          </div>
        </div>
      </div>

      <!-- Laju PDRB Card -->
      <div class="col-md-6 mb-3">
        <div class="dashboard-card" style="position: relative;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
            <h5 class="mb-0">Laju PDRB <span style="font-size: 14px; font-weight: normal; color: #666;">(Persen)</span></h5>
            <div class="chart-header-actions">
              <x-chart-share-button chartId="lajuChart" title="Laju PDRB Pengeluaran" />
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadLajuDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                  <i class="fas fa-download"></i> <span>Unduh</span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="downloadLajuDropdown" style="border-radius: 8px; min-width: 100%;">
                  <li><a class="dropdown-item" href="#" id="downloadLajuExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                  <li><a class="dropdown-item" href="#" id="downloadLajuPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="chart-scroll-container">
            <div id="lajuChart" class="chart-responsive" style="width: 100%; height: 400px;"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter PDRB Pengeluaran -->
    <div class="row mb-4">
      <div class="col-md-12">
        <div class="dashboard-card filter-card" style="background-color: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); padding: 25px; min-height: auto; overflow: visible;">
          <h5 class="mb-4">
            <i class="fas fa-search me-2"></i>Filter PDRB Pengeluaran
          </h5>
          <p class="text-muted mb-4" style="font-size: 14px;">
            Pilih jenis PDRB dan jenis pengeluaran untuk melihat data sesuai kebutuhan. Pilih satu jenis PDRB dan satu atau lebih jenis pengeluaran.
          </p>
          
          <div class="row g-3">
            <!-- Filter Jenis PDRB (Single Selection) -->
            <div class="col-md-6">
              <label class="form-label" style="font-weight: 600; margin-bottom: 8px;">
                <span class="badge bg-primary me-2">1</span>Jenis PDRB
              </label>
              <div id="filterJenisPDRBWrapper" style="position: relative; overflow: visible; z-index: 9999;">
                <div id="filterJenisPDRBInput" class="form-control" style="padding: 6px 12px; border-radius: 6px; min-height: 40px; height: auto; font-size: 14px; cursor: pointer; display: flex; flex-wrap: wrap; align-items: center; gap: 6px; background-color: #fff;">
                  <span id="filterJenisPDRBPlaceholder" style="color: #6c757d;">Pilih Jenis PDRB</span>
                  <span id="filterJenisPDRBSelected" style="display: none; color: #333; flex: 1;"></span>
                  <i class="fas fa-chevron-down" style="color: #6c757d; margin-left: auto; flex-shrink: 0;"></i>
                </div>
                <div id="filterJenisPDRBDropdown" style="display: none; position: fixed; background: white; border: 1px solid #dee2e6; border-radius: 6px; margin-top: 0; max-height: 300px; overflow-y: auto; z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                  <div class="filter-option-pdrb" data-value="ADHB" style="padding: 10px 12px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #f0f0f0;">
                    ADHB (Atas Dasar Harga Berlaku)
                  </div>
                  <div class="filter-option-pdrb" data-value="ADHK" style="padding: 10px 12px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #f0f0f0;">
                    ADHK (Atas Dasar Harga Konstan)
                  </div>
                  <div class="filter-option-pdrb" data-value="Distribusi" style="padding: 10px 12px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #f0f0f0;">
                    Distribusi
                  </div>
                  <div class="filter-option-pdrb" data-value="Laju Pertumbuhan" style="padding: 10px 12px; cursor: pointer; font-size: 14px;">
                    Laju Pertumbuhan
                  </div>
                </div>
              </div>
            </div>

            <!-- Filter Jenis Pengeluaran (Multiple Selection with Tags) -->
            <div class="col-md-6">
              <label class="form-label" style="font-weight: 600; margin-bottom: 8px;">
                <span class="badge bg-success me-2">2</span>Jenis Pengeluaran
              </label>
              <div id="filterJenisPengeluaranWrapper" style="position: relative; overflow: visible; z-index: 9999;">
                <div id="filterJenisPengeluaranInput" class="form-control" style="padding: 6px 12px; border-radius: 6px; min-height: 40px; height: auto; font-size: 14px; cursor: pointer; display: flex; flex-wrap: wrap; align-items: center; gap: 6px; background-color: #fff;">
                  <span id="filterJenisPengeluaranPlaceholder" style="color: #6c757d;">Pilih Jenis Pengeluaran</span>
                  <div id="filterJenisPengeluaranTags" style="display: none; flex-wrap: wrap; gap: 6px; flex: 1;"></div>
                  <i class="fas fa-chevron-down" style="color: #6c757d; margin-left: auto; flex-shrink: 0;"></i>
                </div>
                <div id="filterJenisPengeluaranDropdown" style="display: none; position: fixed; background: white; border: 1px solid #dee2e6; border-radius: 6px; margin-top: 0; max-height: 300px; overflow-y: auto; z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                  <div class="filter-option" data-value="Pengeluaran Konsumsi Rumah Tangga" style="padding: 10px 12px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #f0f0f0;">
                    Pengeluaran Konsumsi Rumah Tangga
                  </div>
                  <div class="filter-option" data-value="Pengeluaran Konsumsi LNPRT" style="padding: 10px 12px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #f0f0f0;">
                    Pengeluaran Konsumsi LNPRT
                  </div>
                  <div class="filter-option" data-value="Pengeluaran Konsumsi Pemerintah" style="padding: 10px 12px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #f0f0f0;">
                    Pengeluaran Konsumsi Pemerintah
                  </div>
                  <div class="filter-option" data-value="Pembentukan Modal Tetap Bruto" style="padding: 10px 12px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #f0f0f0;">
                    Pembentukan Modal Tetap Bruto
                  </div>
                  <div class="filter-option" data-value="Perubahan Inventori" style="padding: 10px 12px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #f0f0f0;">
                    Perubahan Inventori
                  </div>
                  <div class="filter-option" data-value="Net Ekspor Barang dan Jasa" style="padding: 10px 12px; cursor: pointer; font-size: 14px;">
                    Net Ekspor Barang dan Jasa
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-md-12">
              <button id="btnTerapkanFilterPDRB" class="btn btn-primary btn-lg w-100" style="padding: 12px; border-radius: 8px;" disabled>
                <i class="fas fa-check me-2"></i>Terapkan
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Chart Section (Hidden by default, shown after applying filter) -->
    <div id="filteredChartSection" style="display: none;">
      <div class="row mb-4">
        <div class="col-md-12">
          <div class="dashboard-card" style="position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
              <h5 class="mb-0" id="filteredChartTitle">Grafik PDRB Pengeluaran</h5>
              <div class="chart-header-actions">
                <x-chart-share-button chartId="filteredChart" title="Grafik PDRB Pengeluaran" />
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadFilteredDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                    <i class="fas fa-download"></i> <span>Unduh</span>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="downloadFilteredDropdown" style="border-radius: 8px; min-width: 100%;">
                    <li><a class="dropdown-item" href="#" id="downloadFilteredExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                    <li><a class="dropdown-item" href="#" id="downloadFilteredPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="chart-scroll-container">
              <div id="filteredChart" class="chart-responsive" style="width: 100%; height: 400px;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Cards for Triwulanan -->
  <div id="cardsTriwulanan" style="display: none;">
    <div class="row mb-4">
      <!-- ADHB Triwulanan Card -->
      <div class="col-md-6 mb-3">
        <div class="dashboard-card" style="position: relative;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
            <h5 class="mb-0">ADHB Triwulanan <span style="font-size: 14px; font-weight: normal; color: #666;">(Rupiah)</span></h5>
            <div class="chart-header-actions">
              <x-chart-share-button chartId="adhbTriwulananChart" title="ADHB Triwulanan PDRB Pengeluaran" />
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadAdhbTriwulananDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                  <i class="fas fa-download"></i> <span>Unduh</span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="downloadAdhbTriwulananDropdown" style="border-radius: 8px; min-width: 100%;">
                  <li><a class="dropdown-item" href="#" id="downloadAdhbTriwulananExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                  <li><a class="dropdown-item" href="#" id="downloadAdhbTriwulananPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div style="margin-bottom: 10px;">
            <label for="yearFilterADHBTriwulanan" style="margin: 0; font-weight: 500; color: #333; font-size: 12px; margin-bottom: 5px; display: block;">Tahun:</label>
            <select id="yearFilterADHBTriwulanan" class="form-control" style="width: 50%; padding: 5px 10px; font-size: 12px;">
              <option value="">4 Triwulan Terakhir</option>
              <!-- Options will be populated by JavaScript -->
            </select>
          </div>
          <div class="chart-scroll-container">
            <div id="adhbTriwulananChart" class="chart-responsive" style="width: 100%; height: 400px;"></div>
          </div>
        </div>
      </div>

      <!-- ADHK Triwulanan Card -->
      <div class="col-md-6 mb-3">
        <div class="dashboard-card" style="position: relative;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
            <h5 class="mb-0">ADHK Triwulanan <span style="font-size: 14px; font-weight: normal; color: #666;">(Rupiah)</span></h5>
            <div class="chart-header-actions">
              <x-chart-share-button chartId="adhkTriwulananChart" title="ADHK Triwulanan PDRB Pengeluaran" />
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadAdhkTriwulananDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                  <i class="fas fa-download"></i> <span>Unduh</span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="downloadAdhkTriwulananDropdown" style="border-radius: 8px; min-width: 100%;">
                  <li><a class="dropdown-item" href="#" id="downloadAdhkTriwulananExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                  <li><a class="dropdown-item" href="#" id="downloadAdhkTriwulananPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div style="margin-bottom: 10px;">
            <label for="yearFilterADHKTriwulanan" style="margin: 0; font-weight: 500; color: #333; font-size: 12px; margin-bottom: 5px; display: block;">Tahun:</label>
            <select id="yearFilterADHKTriwulanan" class="form-control" style="width: 50%; padding: 5px 10px; font-size: 12px;">
              <option value="">4 Triwulan Terakhir</option>
              <!-- Options will be populated by JavaScript -->
            </select>
          </div>
          <div class="chart-scroll-container">
            <div id="adhkTriwulananChart" class="chart-responsive" style="width: 100%; height: 400px;"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-4">
      <!-- Distribusi Triwulanan Card -->
      <div class="col-md-6 mb-3">
        <div class="dashboard-card" style="position: relative;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
            <h5 class="mb-0">Distribusi Triwulanan - Komponen Pengeluaran <span style="font-size: 14px; font-weight: normal; color: #666;">(Persen)</span></h5>
            <div class="chart-header-actions">
              <x-chart-share-button chartId="distribusiTriwulananChart" title="Distribusi Triwulanan PDRB Pengeluaran" />
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadDistribusiTriwulananDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                  <i class="fas fa-download"></i> <span>Unduh</span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="downloadDistribusiTriwulananDropdown" style="border-radius: 8px; min-width: 100%;">
                  <li><a class="dropdown-item" href="#" id="downloadDistribusiTriwulananExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                  <li><a class="dropdown-item" href="#" id="downloadDistribusiTriwulananPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div style="margin-bottom: 10px; display: flex; gap: 10px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 150px;">
            <label for="yearFilterDistribusiTriwulanan" style="margin: 0; font-weight: 500; color: #333; font-size: 12px; margin-bottom: 5px; display: block;">Tahun:</label>
              <select id="yearFilterDistribusiTriwulanan" class="form-control" style="width: 100%; padding: 5px 10px; font-size: 12px;">
                <option value="">Pilih Tahun</option>
                <!-- Options will be populated by JavaScript -->
            </select>
            </div>
            <div style="flex: 1; min-width: 150px;">
              <label for="quarterFilterDistribusiTriwulanan" style="margin: 0; font-weight: 500; color: #333; font-size: 12px; margin-bottom: 5px; display: block;">Triwulan:</label>
              <select id="quarterFilterDistribusiTriwulanan" class="form-control" style="width: 100%; padding: 5px 10px; font-size: 12px;">
                <option value="">Pilih Triwulan</option>
                <option value="I">Triwulan I</option>
                <option value="II">Triwulan II</option>
                <option value="III">Triwulan III</option>
                <option value="IV">Triwulan IV</option>
              </select>
            </div>
          </div>
          <div class="chart-scroll-container">
            <div id="distribusiTriwulananChart" class="chart-responsive" style="width: 100%; height: 400px;"></div>
          </div>
        </div>
      </div>

      <!-- Laju Q-to-Q Card -->
      <div class="col-md-6 mb-3">
        <div class="dashboard-card" style="position: relative;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
            <h5 class="mb-0">Laju Q-to-Q <span style="font-size: 14px; font-weight: normal; color: #666;">(Persen)</span></h5>
            <div class="chart-header-actions">
              <x-chart-share-button chartId="lajuQtoQChart" title="Laju Q-to-Q PDRB Pengeluaran" />
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadLajuQtoQDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                  <i class="fas fa-download"></i> <span>Unduh</span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="downloadLajuQtoQDropdown" style="border-radius: 8px; min-width: 100%;">
                  <li><a class="dropdown-item" href="#" id="downloadLajuQtoQExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                  <li><a class="dropdown-item" href="#" id="downloadLajuQtoQPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div style="margin-bottom: 10px;">
            <label for="yearFilterLajuQtoQ" style="margin: 0; font-weight: 500; color: #333; font-size: 12px; margin-bottom: 5px; display: block;">Tahun:</label>
            <select id="yearFilterLajuQtoQ" class="form-control" style="width: 50%; padding: 5px 10px; font-size: 12px;">
              <option value="">4 Triwulan Terakhir</option>
              <!-- Options will be populated by JavaScript -->
            </select>
          </div>
          <div class="chart-scroll-container">
            <div id="lajuQtoQChart" class="chart-responsive" style="width: 100%; height: 400px;"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-4">
      <!-- Laju Y-to-Y Card -->
      <div class="col-md-6 mb-3">
        <div class="dashboard-card" style="position: relative;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
            <h5 class="mb-0">Laju Y-to-Y <span style="font-size: 14px; font-weight: normal; color: #666;">(Persen)</span></h5>
            <div class="chart-header-actions">
              <x-chart-share-button chartId="lajuYtoYChart" title="Laju Y-to-Y PDRB Pengeluaran" />
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadLajuYtoYDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                  <i class="fas fa-download"></i> <span>Unduh</span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="downloadLajuYtoYDropdown" style="border-radius: 8px; min-width: 100%;">
                  <li><a class="dropdown-item" href="#" id="downloadLajuYtoYExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                  <li><a class="dropdown-item" href="#" id="downloadLajuYtoYPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div style="margin-bottom: 10px;">
            <label for="yearFilterLajuYtoY" style="margin: 0; font-weight: 500; color: #333; font-size: 12px; margin-bottom: 5px; display: block;">Tahun:</label>
            <select id="yearFilterLajuYtoY" class="form-control" style="width: 50%; padding: 5px 10px; font-size: 12px;">
              <option value="">4 Triwulan Terakhir</option>
              <!-- Options will be populated by JavaScript -->
            </select>
          </div>
          <div class="chart-scroll-container">
            <div id="lajuYtoYChart" class="chart-responsive" style="width: 100%; height: 400px;"></div>
          </div>
        </div>
      </div>

      <!-- Laju C-to-C Card -->
      <div class="col-md-6 mb-3">
        <div class="dashboard-card" style="position: relative;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
            <h5 class="mb-0">Laju C-to-C <span style="font-size: 14px; font-weight: normal; color: #666;">(Persen)</span></h5>
            <div class="chart-header-actions">
              <x-chart-share-button chartId="lajuCtoCChart" title="Laju C-to-C PDRB Pengeluaran" />
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadLajuCtoCDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                  <i class="fas fa-download"></i> <span>Unduh</span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="downloadLajuCtoCDropdown" style="border-radius: 8px; min-width: 100%;">
                  <li><a class="dropdown-item" href="#" id="downloadLajuCtoCExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                  <li><a class="dropdown-item" href="#" id="downloadLajuCtoCPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div style="margin-bottom: 10px;">
            <label for="yearFilterLajuCtoC" style="margin: 0; font-weight: 500; color: #333; font-size: 12px; margin-bottom: 5px; display: block;">Tahun:</label>
            <select id="yearFilterLajuCtoC" class="form-control" style="width: 50%; padding: 5px 10px; font-size: 12px;">
              <option value="">4 Triwulan Terakhir</option>
              <!-- Options will be populated by JavaScript -->
            </select>
          </div>
          <div class="chart-scroll-container">
            <div id="lajuCtoCChart" class="chart-responsive" style="width: 100%; height: 400px;"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter PDRB Pengeluaran Triwulanan -->
    <div class="row mb-4">
      <div class="col-md-12">
        <div class="dashboard-card filter-card" style="background-color: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); padding: 25px; min-height: auto; overflow: visible;">
          <h5 class="mb-4">
            <i class="fas fa-search me-2"></i>Filter PDRB Pengeluaran Triwulanan
          </h5>
          <p class="text-muted mb-4" style="font-size: 14px;">
            Pilih jenis PDRB dan jenis pengeluaran untuk melihat data sesuai kebutuhan. Pilih satu jenis PDRB dan satu atau lebih jenis pengeluaran.
          </p>
          
          <div class="row g-3">
            <!-- Filter Jenis PDRB (Single Selection) -->
            <div class="col-md-6">
              <label class="form-label" style="font-weight: 600; margin-bottom: 8px;">
                <span class="badge bg-primary me-2">1</span>Jenis PDRB
              </label>
              <div id="filterJenisPDRBTriwulananWrapper" style="position: relative; overflow: visible; z-index: 9999;">
                <div id="filterJenisPDRBTriwulananInput" class="form-control" style="padding: 6px 12px; border-radius: 6px; min-height: 40px; height: auto; font-size: 14px; cursor: pointer; display: flex; flex-wrap: wrap; align-items: center; gap: 6px; background-color: #fff;">
                  <span id="filterJenisPDRBTriwulananPlaceholder" style="color: #6c757d;">Pilih Jenis PDRB</span>
                  <span id="filterJenisPDRBTriwulananSelected" style="display: none; color: #333; flex: 1;"></span>
                  <i class="fas fa-chevron-down" style="color: #6c757d; margin-left: auto; flex-shrink: 0;"></i>
                </div>
                <div id="filterJenisPDRBTriwulananDropdown" style="display: none; position: fixed; background: white; border: 1px solid #dee2e6; border-radius: 6px; margin-top: 0; max-height: 300px; overflow-y: auto; z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                  <div class="filter-option-pdrb-triwulanan" data-value="ADHB" style="padding: 10px 12px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #f0f0f0;">
                    ADHB (Atas Dasar Harga Berlaku)
                  </div>
                  <div class="filter-option-pdrb-triwulanan" data-value="ADHK" style="padding: 10px 12px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #f0f0f0;">
                    ADHK (Atas Dasar Harga Konstan)
                  </div>
                  <div class="filter-option-pdrb-triwulanan" data-value="Laju Q-to-Q" style="padding: 10px 12px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #f0f0f0;">
                    Laju Q-to-Q
                  </div>
                  <div class="filter-option-pdrb-triwulanan" data-value="Laju Y-to-Y" style="padding: 10px 12px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #f0f0f0;">
                    Laju Y-to-Y
                  </div>
                  <div class="filter-option-pdrb-triwulanan" data-value="Laju C-to-C" style="padding: 10px 12px; cursor: pointer; font-size: 14px;">
                    Laju C-to-C
                  </div>
                </div>
              </div>
            </div>

            <!-- Filter Jenis Pengeluaran (Multiple Selection with Tags) -->
            <div class="col-md-6">
              <label class="form-label" style="font-weight: 600; margin-bottom: 8px;">
                <span class="badge bg-success me-2">2</span>Jenis Pengeluaran
              </label>
              <div id="filterJenisPengeluaranTriwulananWrapper" style="position: relative; overflow: visible; z-index: 9999;">
                <div id="filterJenisPengeluaranTriwulananInput" class="form-control" style="padding: 6px 12px; border-radius: 6px; min-height: 40px; height: auto; font-size: 14px; cursor: pointer; display: flex; flex-wrap: wrap; align-items: center; gap: 6px; background-color: #fff;">
                  <span id="filterJenisPengeluaranTriwulananPlaceholder" style="color: #6c757d;">Pilih Jenis Pengeluaran</span>
                  <div id="filterJenisPengeluaranTriwulananTags" style="display: none; flex-wrap: wrap; gap: 6px; flex: 1;"></div>
                  <i class="fas fa-chevron-down" style="color: #6c757d; margin-left: auto; flex-shrink: 0;"></i>
                </div>
                <div id="filterJenisPengeluaranTriwulananDropdown" style="display: none; position: fixed; background: white; border: 1px solid #dee2e6; border-radius: 6px; margin-top: 0; max-height: 300px; overflow-y: auto; z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                  <div class="filter-option-triwulanan" data-value="Konsumsi Akhir Rumah Tangga" style="padding: 10px 12px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #f0f0f0;">
                    Konsumsi Akhir Rumah Tangga
                  </div>
                  <div class="filter-option-triwulanan" data-value="Konsumsi Akhir Pemerintah" style="padding: 10px 12px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #f0f0f0;">
                    Konsumsi Akhir Pemerintah
                  </div>
                  <div class="filter-option-triwulanan" data-value="Pembentukan Modal Tetap Bruto" style="padding: 10px 12px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #f0f0f0;">
                    Pembentukan Modal Tetap Bruto
                  </div>
                  <div class="filter-option-triwulanan" data-value="Lainnya" style="padding: 10px 12px; cursor: pointer; font-size: 14px;">
                    Lainnya
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-md-12">
              <button id="btnTerapkanFilterPDRBTriwulanan" class="btn btn-primary btn-lg w-100" style="padding: 12px; border-radius: 8px;" disabled>
                <i class="fas fa-check me-2"></i>Terapkan
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Chart Section (Hidden by default, shown after applying filter) -->
    <div id="filteredChartSectionTriwulanan" style="display: none;">
      <div class="row mb-4">
        <div class="col-md-12">
          <div class="dashboard-card" style="position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
              <h5 class="mb-0" id="filteredChartTitleTriwulanan">Grafik PDRB Pengeluaran Triwulanan</h5>
              <div class="chart-header-actions">
                <x-chart-share-button chartId="filteredChartTriwulanan" title="Grafik PDRB Pengeluaran Triwulanan" />
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadFilteredTriwulananDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                    <i class="fas fa-download"></i> <span>Unduh</span>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="downloadFilteredTriwulananDropdown" style="border-radius: 8px; min-width: 100%;">
                    <li><a class="dropdown-item" href="#" id="downloadFilteredTriwulananExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                    <li><a class="dropdown-item" href="#" id="downloadFilteredTriwulananPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="chart-scroll-container">
              <div id="filteredChartTriwulanan" class="chart-responsive" style="width: 100%; height: 400px;"></div>
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
        <h5 class="mb-3"><i class="fas fa-info-circle"></i> Tentang PDRB Pengeluaran</h5>
        <p style="margin-bottom: 0; line-height: 1.8;">
          <strong>Produk Domestik Regional Bruto (PDRB)</strong> adalah nilai tambah barang dan jasa yang dihasilkan oleh unit-unit produksi di suatu wilayah dalam jangka waktu tertentu. 
          PDRB Pengeluaran mengelompokkan kegiatan ekonomi berdasarkan komponen pengeluaran (expenditure approach). 
          Terdapat beberapa jenis PDRB yang dihitung:
        </p>
        <ul style="margin-top: 12px; margin-bottom: 0; line-height: 1.8;">
          <li><strong>ADHB (Atas Dasar Harga Berlaku)</strong>: PDRB yang dihitung menggunakan harga yang berlaku pada tahun berjalan. 
            Menggambarkan nilai tambah bruto berdasarkan harga pasar saat ini.</li>
          <li><strong>ADHK (Atas Dasar Harga Konstan)</strong>: PDRB yang dihitung menggunakan harga pada tahun dasar tertentu. 
            Digunakan untuk mengukur pertumbuhan ekonomi riil dengan menghilangkan pengaruh inflasi.</li>
          <li><strong>Distribusi</strong>: Kontribusi masing-masing komponen pengeluaran terhadap total PDRB, dinyatakan dalam persen. 
            Menunjukkan komponen pengeluaran mana yang dominan di suatu wilayah.</li>
          <li><strong>Laju Pertumbuhan</strong>: Perubahan PDRB dari periode ke periode, dinyatakan dalam persen. 
            Menggambarkan dinamika pertumbuhan ekonomi.</li>
        </ul>
        <p style="margin-top: 12px; margin-bottom: 16px; line-height: 1.8;">
          Komponen pengeluaran dalam PDRB meliputi: Pengeluaran Konsumsi Rumah Tangga, Pengeluaran Konsumsi LNPRT (Lembaga Non-Profit yang Melayani Rumah Tangga), 
          Pengeluaran Konsumsi Pemerintah, Pembentukan Modal Tetap Bruto, Perubahan Inventori, dan Net Ekspor Barang dan Jasa. 
          PDRB Pengeluaran memberikan gambaran tentang bagaimana output ekonomi digunakan atau dialokasikan dalam perekonomian regional.
        </p>
      </div>
    </div>
  </div>

</div>






@push('scripts')
@vite(['resources/css/dashboard/pdrb-pengeluaran.css', 'resources/js/dashboard/pdrb-pengeluaran.js'])
@endpush

@endsection
