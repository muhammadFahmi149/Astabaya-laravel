@extends('layouts.main')

@section('title', 'Inflasi')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="{{ asset('js/share-utils.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/share-styles.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endpush

@section('content')
<div class="container py-4">
  <h3 class="font-weight-bold mb-4">Inflasi</h3>

  
  
  <!-- Summary Cards -->
  <div class="row mb-4" style="display: flex; flex-wrap: wrap; gap: 15px;">
    <!-- Inflasi Bulan ke Bulan (m-to-m) -->
    <div class="col-12 col-md-4 mb-3" style="flex: 1; min-width: 250px;">
      <div class="summary-card" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-radius: 12px; padding: 25px; min-height: 180px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
        <div style="position: relative; z-index: 2;">
          <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 13px; font-weight: 500; margin: 0 0 10px 0;">Inflasi Bulan ke Bulan</h6>
          <h3 style="font-size: 32px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;" id="m-to-m-value">
            -
          </h3>
          <div style="display: flex; align-items: center; gap: 5px; margin-top: 10px;" id="m-to-m-change">
            <!-- Will be populated by JavaScript -->
          </div>
          <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: 10px; display: block;" id="m-to-m-date">
            Memuat data...
          </small>
        </div>
      </div>
    </div>

    <!-- Inflasi Tahun ke Tahun (y-on-y) -->
    <div class="col-12 col-md-4 mb-3" style="flex: 1; min-width: 250px;">
      <div class="summary-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 12px; padding: 25px; min-height: 180px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
        <div style="position: relative; z-index: 2;">
          <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 13px; font-weight: 500; margin: 0 0 10px 0;">Inflasi Tahun ke Tahun</h6>
          <h3 style="font-size: 32px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;" id="y-on-y-value">
            -
          </h3>
          <div style="display: flex; align-items: center; gap: 5px; margin-top: 10px;" id="y-on-y-change">
            <!-- Will be populated by JavaScript -->
          </div>
          <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: 10px; display: block;" id="y-on-y-date">
            Memuat data...
          </small>
        </div>
      </div>
    </div>

    <!-- Inflasi Kumulatif -->
    <div class="col-12 col-md-4 mb-3" style="flex: 1; min-width: 250px;">
      <div class="summary-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border-radius: 12px; padding: 25px; min-height: 180px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
        <div style="position: relative; z-index: 2;">
          <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 13px; font-weight: 500; margin: 0 0 10px 0;">Inflasi Kumulatif</h6>
          <h3 style="font-size: 32px; font-weight: 700; line-height: 1.2; margin: 0 0 8px 0;" id="kumulatif-value">
            -
          </h3>
          <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px; margin-top: 10px; display: block;" id="kumulatif-date">
            Memuat data...
          </small>
        </div>
      </div>
    </div>
  </div>

  <div class="filter-layer" style="position: relative; z-index: 1001;"><!-- Filter Tahun untuk Grafik -->
  <div class="row mb-4">
    <div class="col-md-12">
      <div class="dashboard-card filter-card" style="background-color: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); padding: 20px; position: relative; z-index: 1001; overflow: visible;">
        <div class="row align-items-center">
          <div class="col-md-3">
            <label for="filterYear" class="form-label mb-2" style="font-weight: 600; color: #333;">
              <i class="fas fa-filter me-2"></i>Filter Tahun Grafik:
            </label>
          </div>
          <div class="col-md-12">
            <div class="custom-dropdown" style="position: relative; max-width: 300px; z-index: 500;">
              <div class="dropdown-toggle" id="filterYearToggle" style="padding: 10px; border-radius: 8px; border: 1px solid #ddd; background-color: white; cursor: pointer; display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 1;">
                <span id="filterYearDisplay">Default</span>
                
              </div>
              <div class="dropdown-menu" id="filterYearDropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background-color: white; border: 1px solid #ddd; border-radius: 8px; margin-top: 4px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); z-index: 900; max-height: 192px; overflow-y: auto; overflow-x: hidden;">
                <div class="dropdown-item" data-value="" style="padding: 8px 12px; cursor: pointer; background-color: #f0f0f0;" data-selected="true">Default</div>
                <!-- Will be populated by JavaScript -->
              </div>
              <select id="filterYear" style="display: none;">
                <option value="" selected>Default</option>
                <!-- Will be populated by JavaScript -->
              </select>
            </div>
            <small class="text-muted" style="font-size: 11px; display: block; margin-top: 5px;">
              <i class="fas fa-info-circle"></i> <span id="filterYearInfo">Default menampilkan tahun terbaru</span>
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Grafik Perkembangan Inflasi -->
  <div class="row mb-4">
    <!-- Grafik Inflasi Bulan ke Bulan -->
    <div class="col-md-6 mb-3">
      <div class="dashboard-card" style="background-color: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); padding: 25px; margin-bottom: 20px; position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">Perkembangan Inflasi Bulan ke Bulan (%)</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="inflasiMtoMChart" title="Perkembangan Inflasi Bulan ke Bulan" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadMtoMDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadMtoMDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadMtoMExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadMtoMPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="chart-scroll-container">
          <div id="inflasiMtoMChart" class="chart-responsive" style="width: 100%; height: 400px;"></div>
        </div>
      </div>
    </div>

    <!-- Grafik Inflasi Tahun ke Tahun -->
    <div class="col-md-6 mb-3">
      <div class="dashboard-card" style="background-color: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); padding: 25px; margin-bottom: 20px; position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0">Perkembangan Inflasi Tahun ke Tahun (%)</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="inflasiYonYChart" title="Perkembangan Inflasi Tahun ke Tahun" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadYonYDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadYonYDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadYonYExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadYonYPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="chart-scroll-container">
          <div id="inflasiYonYChart" class="chart-responsive" style="width: 100%; height: 400px;"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Filter Komoditas (Fleksibel) -->
  <div class="row mb-4" style="position: relative; z-index: 1000;">
    <div class="col-md-12">
      <div class="dashboard-card filter-card" style="background-color: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); padding: 25px; min-height: auto; overflow: visible; position: relative; z-index: 1000;">
        <h5 class="mb-4">
          <i class="fas fa-search me-2"></i>Filter Inflasi Per Komoditas
        </h5>
        <p class="text-muted mb-4" style="font-size: 14px;">
          Pilih filter untuk melihat inflasi per komoditas. Anda dapat memilih dari tahun terlebih dahulu atau langsung memilih komoditas umum, sub komoditas, atau komoditas spesifik.
        </p>
        
        <div class="row g-3">
          <!-- Filter Tahun -->
          <div class="col-md-6">
            <label class="form-label" style="font-weight: 600; margin-bottom: 8px;">
              <span class="badge bg-primary me-2">1</span>Tahun
            </label>
            <div id="filterKomoditasTahunWrapper" style="position: relative; overflow: visible; z-index: 900;">
              <div id="filterKomoditasTahunInput" class="form-control" style="padding: 6px 12px; border-radius: 6px; min-height: 40px; height: auto; font-size: 14px; cursor: pointer; display: flex; flex-wrap: wrap; align-items: center; gap: 6px; background-color: #fff;">
                <span id="filterKomoditasTahunPlaceholder" style="color: #6c757d;">Pilih Tahun</span>
                <span id="filterKomoditasTahunSelected" style="display: none; color: #333; flex: 1;"></span>
                <i class="fas fa-chevron-down" style="color: #6c757d; margin-left: auto; flex-shrink: 0;"></i>
              </div>
              <div id="filterKomoditasTahunDropdown" style="display: none; position: absolute; background: white; border: 1px solid #dee2e6; border-radius: 6px; margin-top: 4px; top: 100%; left: 0; right: 0; max-height: 300px; overflow-y: auto; z-index: 900; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                <!-- Will be populated by JavaScript -->
              </div>
            </div>
          </div>

          <!-- Filter Komoditas (Multiple Selection - Umum, Sub, Spesifik) -->
          <div class="col-md-6">
            <label class="form-label" style="font-weight: 600; margin-bottom: 8px;">
              <span class="badge bg-success me-2">2</span>Komoditas
            </label>
            <div id="filterKomoditasWrapper" style="position: relative; overflow: visible; z-index: 900;">
              <div id="filterKomoditasInput" class="form-control" style="padding: 6px 12px; border-radius: 6px; min-height: 40px; height: auto; font-size: 14px; cursor: text; display: flex; flex-wrap: wrap; align-items: center; gap: 6px; background-color: #fff;">
                <span id="filterKomoditasPlaceholder" style="color: #6c757d; display: inline;">Cari komoditas...</span>
                <div id="filterKomoditasTags" style="display: none; flex-wrap: wrap; gap: 6px; flex: 1; align-items: center;"></div>
                <input type="text" id="filterKomoditasSearch" style="flex: 1; min-width: 150px; border: none; outline: none; font-size: 14px; padding: 0; text-align: left; display: none;" autocomplete="off">
                <button type="button" id="filterKomoditasClear" style="display: none; background: none; border: none; color: #6c757d; cursor: pointer; padding: 4px 8px; font-size: 16px; line-height: 1;">×</button>
                <i class="fas fa-chevron-down" id="filterKomoditasChevron" style="color: #6c757d; margin-left: auto; flex-shrink: 0; cursor: pointer; padding: 4px;"></i>
              </div>
              <div id="filterKomoditasDropdown" style="display: none; position: absolute; background: white; border: 1px solid #dee2e6; border-radius: 6px; margin-top: 4px; top: 100%; left: 0; right: 0; max-height: 400px; overflow-y: auto; z-index: 900; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                <!-- Will be populated by JavaScript -->
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-4">
          <div class="col-md-12">
            <button id="btnCariKomoditas" class="btn btn-primary btn-lg w-100" style="padding: 12px; border-radius: 8px;" disabled>
              <i class="fas fa-check me-2"></i>Terapkan
            </button>
          </div>
        </div>

        <div id="selectedKomoditasInfo" style="display: none; margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #3b82f6;">
          <h6 class="mb-2">
            <i class="fas fa-info-circle me-2"></i>Komoditas Terpilih:
          </h6>
          <p id="selectedKomoditasText" class="mb-2" style="font-size: 14px;"></p>
          <button id="btnClearKomoditas" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-times me-1"></i>Hapus Pilihan
          </button>
        </div>
      </div>
    </div>
  </div>

  </div><!-- end filter-layer -->
<div class="content-layer"><!-- Grafik Inflasi Per Komoditas -->
  <div class="row mb-4" id="komoditasChartSection" style="display: none;">
    <div class="col-md-12">
      <div class="dashboard-card" style="background-color: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); padding: 25px; position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 8px;">
          <h5 class="mb-0" id="komoditasChartTitle">Inflasi Per Komoditas</h5>
          <div class="chart-header-actions">
            <x-chart-share-button chartId="inflasiPerKomoditasChart" title="Inflasi Per Komoditas" />
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="downloadKomoditasDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                <i class="fas fa-download"></i> <span>Unduh</span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="downloadKomoditasDropdown" style="border-radius: 8px; min-width: 100%;">
                <li><a class="dropdown-item" href="#" id="downloadKomoditasExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" id="downloadKomoditasPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="chart-scroll-container">
          <div id="inflasiPerKomoditasChart" class="chart-responsive" style="width: 100%; height: 450px;"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Penjelasan Komoditas -->
  <div class="row mb-4">
    <div class="col-md-12">
      <div class="dashboard-card" style="background-color: #f8f9fa; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); padding: 25px;">
        <h5 class="mb-4">
          <i class="fas fa-book me-2"></i>Penjelasan Komoditas Umum
        </h5>
        <p class="text-muted mb-4" style="font-size: 14px;">
          Berikut adalah penjelasan mengenai komoditas umum dan sub komoditas yang digunakan dalam perhitungan inflasi. Informasi ini membantu Anda memahami struktur komoditas yang tersedia.
        </p>
        <div id="komoditasExplanation" style="font-size: 14px;">
          <!-- Will be populated by JavaScript -->
          <div class="text-center text-muted">
            <i class="fas fa-spinner fa-spin me-2"></i>Memuat penjelasan komoditas...
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Additional Info Card -->
  <div class="row mb-4">
    <div class="col-md-12">
      <div class="dashboard-card" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        <h5 class="mb-3"><i class="fas fa-info-circle"></i> Tentang Inflasi</h5>
        <p style="margin-bottom: 0; line-height: 1.8;">
          <strong>Inflasi</strong> adalah kenaikan harga barang dan jasa secara umum dan terus-menerus dalam jangka waktu tertentu. 
          Inflasi diukur menggunakan Indeks Harga Konsumen (IHK) yang mencakup berbagai komoditas yang dikonsumsi rumah tangga. 
          Terdapat beberapa jenis inflasi yang dihitung:
        </p>
        <ul style="margin-top: 12px; margin-bottom: 0; line-height: 1.8; padding-left: 20px;">
          <li style="margin-bottom: 8px;"><strong>Inflasi Bulan ke Bulan (Month-to-Month/MoM)</strong>: Perubahan IHK bulan berjalan dibandingkan dengan bulan sebelumnya, dinyatakan dalam persen. 
            Menggambarkan perubahan harga jangka pendek.</li>
          <li style="margin-bottom: 8px;"><strong>Inflasi Tahun ke Tahun (Year-on-Year/YoY)</strong>: Perubahan IHK bulan berjalan dibandingkan dengan bulan yang sama pada tahun sebelumnya, dinyatakan dalam persen. 
            Menggambarkan tren inflasi jangka menengah.</li>
          <li style="margin-bottom: 8px;"><strong>Inflasi Kumulatif</strong>: Akumulasi inflasi dari awal tahun (Januari) hingga bulan berjalan, dinyatakan dalam persen. 
            Menggambarkan total kenaikan harga sejak awal tahun.</li>
        </ul>
        <p style="margin-top: 12px; margin-bottom: 0; line-height: 1.8;">
          Inflasi yang terkendali (sekitar 2-4% per tahun) dianggap baik untuk perekonomian karena mendorong konsumsi dan investasi. 
          Namun, inflasi yang terlalu tinggi dapat mengurangi daya beli masyarakat dan stabilitas ekonomi. 
          Bank Indonesia menggunakan berbagai instrumen kebijakan moneter untuk menjaga inflasi dalam target yang ditetapkan.
        </p>
      </div>
    </div>
  </div>
</div>
</div><!-- end content-layer -->






@push('scripts')
<script>
window.APP_CONFIG = {
  routes: {
    inflasi: '{{ route("api.inflasi") }}',
    inflasiPerKomoditas: '{{ route("api.inflasi-perkomoditas") }}',
    komoditasByFlag: '{{ route("api.komoditas-by-flag") }}',
    inflasiSummary: '{{ route("api.inflasi-summary") }}',
    inflasiYears: '{{ route("api.inflasi-years") }}',
    komoditasYears: '{{ route("api.inflasi-komoditas-years") }}',
    inflasiKomoditasTree: '{{ route("api.inflasi-komoditas-tree") }}',
    login: '{{ route("login") }}'
  },
  isAuthenticated: @auth true @else false @endauth
};
</script>
@vite(['resources/css/dashboard/inflasi.css', 'resources/js/dashboard/inflasi.js'])
@endpush

@endsection
