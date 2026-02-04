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
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }
  
  .summary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
  }

  /* Mobile responsive: 3 baris x 2 kolom */
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
    }
  }

  /* Chart container styles */
  .chart-container-wrapper {
    position: relative;
    width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
    margin-bottom: 10px;
  }

  .chart-container {
    position: relative;
    min-height: 350px;
    height: 400px;
  }

  /* Desktop: normal size */
  @media (min-width: 768px) {
    .chart-container {
      width: 100%;
      height: 450px;
    }
    
    .chart-container-wrapper {
      overflow-x: hidden;
    }
  }

  /* Mobile: wider container for scrolling */
  @media (max-width: 767px) {
    .chart-container {
      width: auto;
      min-width: 100%;
      max-width: none;
      height: 350px;
    }

    .chart-container-wrapper {
      border-radius: 8px;
      overflow-x: auto;
      overflow-y: hidden;
    }

    .chart-container-wrapper::-webkit-scrollbar {
      height: 6px;
    }

    .chart-container-wrapper::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }

    .chart-container-wrapper::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 10px;
    }

    .chart-container-wrapper::-webkit-scrollbar-thumb:hover {
      background: #555;
    }

    /* Adjust grid padding for mobile */
    .dashboard-card {
      padding: 15px;
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

  /* Year Dropdown Menu - Scrollable */
  #yearDropdownMenu {
    max-height: 300px;
    overflow-y: auto;
    overflow-x: hidden;
    border-radius: 0.5rem;
    /* Ensure scroll works on all browsers */
    -webkit-overflow-scrolling: touch;
    /* Firefox scrollbar */
    scrollbar-width: thin;
    scrollbar-color: #888 #f1f1f1;
  }

  /* Custom scrollbar for year dropdown menu - Webkit browsers (Chrome, Safari, Edge) */
  #yearDropdownMenu::-webkit-scrollbar {
    width: 8px;
  }

  #yearDropdownMenu::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
    margin: 4px 0;
  }

  #yearDropdownMenu::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
    min-height: 30px;
  }

  #yearDropdownMenu::-webkit-scrollbar-thumb:hover {
    background: #555;
  }

  /* Year dropdown item styling */
  #yearDropdownMenu .dropdown-item {
    padding: 0.5rem 1rem;
    cursor: pointer;
    white-space: nowrap;
  }

  #yearDropdownMenu .dropdown-item:hover {
    background-color: #f8f9fa;
  }

  #yearDropdownMenu .dropdown-item.active {
    background-color: #0d6efd;
    color: white;
  }

  #yearDropdownMenu .dropdown-item.active:hover {
    background-color: #0b5ed7;
  }
</style>

<script>
  document.addEventListener("DOMContentLoaded", async () => {
    // Wait for ECharts to be loaded
    if (typeof echarts === 'undefined') {
      console.error('ECharts library not loaded! Please check if the script is included.');
      return;
    }
    
    const API_BASE = '{{ url("/api") }}';
    
    // Initialize data variables
    let occupancyData = [];
    let yearlyOccupancyData = [];
    let latestMonthData = null;
    let previousMonthData = null;
    let changes = {
      tpk: null,
      mktj: null,
      rlmtgab: null,
      gpr: null,
    };
    let distinctYears = [];
    let selectedYear = null;
    
    // Check if user is authenticated (set from server side)
    const isAuthenticated = @auth true @else false @endauth;

    // Month order for sorting (chronological order) - using full names
    const monthOrder = ['JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 
                        'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOPEMBER', 'DESEMBER'];
    
    // Mapping from database month names to standard month names
    const monthNameMap = {
      'JANUARI': 'JANUARI', 'FEBRUARI': 'FEBRUARI', 'MARET': 'MARET', 
      'APRIL': 'APRIL', 'MEI': 'MEI', 'JUNI': 'JUNI',
      'JULI': 'JULI', 'AGUSTUS': 'AGUSTUS', 'SEPTEMBER': 'SEPTEMBER',
      'OKTOBER': 'OKTOBER', 'NOPEMBER': 'NOPEMBER', 'DESEMBER': 'DESEMBER',
      'Januari': 'JANUARI', 'Februari': 'FEBRUARI', 'Maret': 'MARET',
      'April': 'APRIL', 'Mei': 'MEI', 'Juni': 'JUNI',
      'Juli': 'JULI', 'Agustus': 'AGUSTUS', 'September': 'SEPTEMBER',
      'Oktober': 'OKTOBER', 'November': 'NOPEMBER', 'Desember': 'DESEMBER',
      'Jan': 'JANUARI', 'Feb': 'FEBRUARI', 'Mar': 'MARET',
      'Apr': 'APRIL', 'Jun': 'JUNI', 'Jul': 'JULI',
      'Ags': 'AGUSTUS', 'Agst': 'AGUSTUS', 'Sep': 'SEPTEMBER',
      'Sept': 'SEPTEMBER', 'Okt': 'OKTOBER', 'Nov': 'NOPEMBER', 'Des': 'DESEMBER'
    };
    
    // Month abbreviations for display
    const monthAbbr = {
      'JANUARI': 'Jan', 'FEBRUARI': 'Feb', 'MARET': 'Mar', 'APRIL': 'Apr',
      'MEI': 'Mei', 'JUNI': 'Jun', 'JULI': 'Jul', 'AGUSTUS': 'Ags',
      'SEPTEMBER': 'Sep', 'OKTOBER': 'Okt', 'NOPEMBER': 'Nov', 'DESEMBER': 'Des'
    };
    
    // Helper function to normalize month name
    function normalizeMonth(monthName) {
      if (!monthName) return null;
      const trimmed = monthName.trim().toUpperCase();
      if (monthNameMap[trimmed]) {
        return monthNameMap[trimmed];
      }
      // Try direct mapping
      for (const [key, value] of Object.entries(monthNameMap)) {
        if (key.toUpperCase() === trimmed) {
          return value;
        }
      }
      console.warn('Unknown month name:', monthName);
      return trimmed;
    }

    // Function to format number with thousand separators (Indonesian format using dot)
    function formatNumberWithSeparator(num) {
      if (num === null || num === undefined || isNaN(num)) return '0';
      const numStr = Math.abs(num).toString().split('.')[0];
      const formatted = numStr.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      return num < 0 ? '-' + formatted : formatted;
    }

    // Function to format number with thousand separators and add "ribu" suffix if >= 1000
    function formatNumberWithUnit(num, addRibu = false) {
      if (num === null || num === undefined || isNaN(num)) return '0';
      const numValue = parseFloat(num);
      let formatted = formatNumberWithSeparator(Math.abs(numValue));
      if (addRibu && numValue >= 1000) {
        formatted += ' ribu';
      }
      return numValue < 0 ? '-' + formatted : formatted;
    }

    // Function to format month name for display
    function formatMonthName(month) {
      if (!month) return '';
      const normalized = normalizeMonth(month);
      return monthAbbr[normalized] || normalized || month;
    }

    // Function to format date display
    function formatDateDisplay(month, year) {
      if (!month || !year) return 'Data tidak tersedia';
      const monthAbbr = formatMonthName(month);
      return `${monthAbbr} ${year}`;
    }

    // Load summary data from API
    try {
      console.log('Fetching data from:', `${API_BASE}/hotel-occupancy-summary`);
      const response = await fetch(`${API_BASE}/hotel-occupancy-summary`);
      
      // Validate response
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const result = await response.json();
      console.log('API Response:', result);
      
      // Validate response structure
      if (result.success && result.data) {
        const data = result.data;
        
        // Store data
        occupancyData = Array.isArray(data.occupancy_data) ? data.occupancy_data.map(item => ({
          year: parseInt(item.year),
          month: item.month,
          normalizedMonth: normalizeMonth(item.month),
          tpk: item.tpk !== null ? parseFloat(item.tpk) : null,
          mktj: item.mktj !== null ? parseFloat(item.mktj) : null,
          rlmtgab: item.rlmtgab !== null ? parseFloat(item.rlmtgab) : null,
          gpr: item.gpr !== null ? parseFloat(item.gpr) : null
        })) : [];
        
        // Process yearly occupancy data - handle both object and array formats
        const rawYearlyData = data.yearly_occupancy_data || [];
        console.log('Raw yearly data from API:', rawYearlyData);
        
        yearlyOccupancyData = Array.isArray(rawYearlyData) ? rawYearlyData.map(item => {
          // Handle both object and array formats
          const year = item.year || item.year;
          const tpk = item.tpk !== null && item.tpk !== undefined ? parseFloat(item.tpk) : null;
          return {
            year: parseInt(year),
            tpk: tpk
          };
        }).filter(item => item.year && !isNaN(item.year)) : [];
        
        console.log('Processed yearly data:', yearlyOccupancyData);
        
        latestMonthData = data.latest_month_data;
        previousMonthData = data.previous_month_data;
        changes = data.changes || changes;
        distinctYears = Array.isArray(data.distinct_years) ? data.distinct_years.sort((a, b) => b - a) : [];
        selectedYear = data.latest_year || (distinctYears.length > 0 ? distinctYears[0] : null);
        
        console.log('Data loaded:', {
          occupancyDataCount: occupancyData.length,
          yearlyOccupancyDataCount: yearlyOccupancyData.length,
          selectedYear: selectedYear,
          distinctYears: distinctYears,
          latestMonthData: latestMonthData ? {
            id: latestMonthData.id,
            year: latestMonthData.year,
            month: latestMonthData.month,
            tpk: latestMonthData.tpk
          } : null,
          previousMonthData: previousMonthData ? {
            id: previousMonthData.id,
            year: previousMonthData.year,
            month: previousMonthData.month
          } : null,
          yearlyOccupancyDataRaw: data.yearly_occupancy_data,
          yearlyOccupancyDataProcessed: yearlyOccupancyData
        });
        
        // Update summary cards
        updateSummaryCards();
        
        // Populate year dropdown
        populateYearDropdown();
        
        // Wait a bit to ensure DOM is ready
        setTimeout(() => {
          // Initialize chart instances first
          initChartInstances();
          
          // Initialize charts
          initializeCharts();
          
          console.log('Charts initialized:', {
            lineChart: !!lineChart,
            comparisonChart: !!comparisonChart,
            yearlyChart: !!yearlyChart
          });
        }, 100);
      } else {
        console.error('Invalid API response structure:', result);
      }
    } catch (error) {
      console.error('Error loading hotel occupancy data:', error);
    }

    // Update summary cards with data
    function updateSummaryCards() {
      // TPK Card
      const tpkValueEl = document.getElementById('tpk-value');
      const tpkChangeEl = document.getElementById('tpk-change');
      const tpkDateEl = document.getElementById('tpk-date');
      
      if (latestMonthData && latestMonthData.tpk !== null) {
        tpkValueEl.textContent = parseFloat(latestMonthData.tpk).toFixed(2) + '%';
        tpkDateEl.textContent = formatDateDisplay(latestMonthData.month, latestMonthData.year);
        
        if (changes.tpk !== null) {
          const changeHtml = changes.tpk > 0 
            ? `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">▲</span>
               <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${Math.abs(changes.tpk).toFixed(2)}%</span>`
            : changes.tpk < 0
            ? `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">▼</span>
               <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${changes.tpk.toFixed(2)}%</span>`
            : `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>`;
          
          const monthText = previousMonthData 
            ? `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;">dari bulan ${previousMonthData.month.toLowerCase()}</span>`
            : '';
          
          tpkChangeEl.innerHTML = changeHtml + monthText;
        }
      } else {
        tpkValueEl.textContent = '-';
        tpkDateEl.textContent = 'Data tidak tersedia';
      }

      // MKTJ Card
      const mktjValueEl = document.getElementById('mktj-value');
      const mktjChangeEl = document.getElementById('mktj-change');
      const mktjDateEl = document.getElementById('mktj-date');
      
      if (latestMonthData && latestMonthData.mktj !== null) {
        const mktjValue = parseFloat(latestMonthData.mktj);
        mktjValueEl.textContent = formatNumberWithUnit(mktjValue, true);
        mktjDateEl.textContent = formatDateDisplay(latestMonthData.month, latestMonthData.year);
        
        if (changes.mktj !== null) {
          const absChange = Math.abs(changes.mktj);
          let changeText = formatNumberWithSeparator(absChange);
          if (absChange >= 1000) {
            changeText += ' ribu';
          }
          
          const changeHtml = changes.mktj > 0 
            ? `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">▲</span>
               <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${changeText}</span>`
            : changes.mktj < 0
            ? `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">▼</span>
               <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${changeText}</span>`
            : `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>`;
          
          const monthText = previousMonthData 
            ? `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;">dari bulan ${previousMonthData.month.toLowerCase()}</span>`
            : '';
          
          mktjChangeEl.innerHTML = changeHtml + monthText;
        }
      } else {
        mktjValueEl.textContent = '-';
        mktjDateEl.textContent = 'Data tidak tersedia';
      }

      // RLMT Gabungan Card
      const rlmtgabValueEl = document.getElementById('rlmtgab-value');
      const rlmtgabChangeEl = document.getElementById('rlmtgab-change');
      const rlmtgabDateEl = document.getElementById('rlmtgab-date');
      
      if (latestMonthData && latestMonthData.rlmtgab !== null) {
        rlmtgabValueEl.innerHTML = parseFloat(latestMonthData.rlmtgab).toFixed(2) + ' <span style="font-size: 16px; font-weight: 400;">malam</span>';
        rlmtgabDateEl.textContent = formatDateDisplay(latestMonthData.month, latestMonthData.year);
        
        if (changes.rlmtgab !== null) {
          const changeHtml = changes.rlmtgab > 0 
            ? `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">▲</span>
               <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${Math.abs(changes.rlmtgab).toFixed(2)}</span>`
            : changes.rlmtgab < 0
            ? `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">▼</span>
               <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${changes.rlmtgab.toFixed(2)}</span>`
            : `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>`;
          
          const monthText = previousMonthData 
            ? `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;">dari bulan ${previousMonthData.month.toLowerCase()}</span>`
            : '';
          
          rlmtgabChangeEl.innerHTML = changeHtml + monthText;
        }
      } else {
        rlmtgabValueEl.textContent = '-';
        rlmtgabDateEl.textContent = 'Data tidak tersedia';
      }

      // GPR Card
      const gprValueEl = document.getElementById('gpr-value');
      const gprChangeEl = document.getElementById('gpr-change');
      const gprDateEl = document.getElementById('gpr-date');
      
      if (latestMonthData && latestMonthData.gpr !== null) {
        gprValueEl.textContent = parseFloat(latestMonthData.gpr).toFixed(2) + '%';
        gprDateEl.textContent = formatDateDisplay(latestMonthData.month, latestMonthData.year);
        
        if (changes.gpr !== null) {
          const changeHtml = changes.gpr > 0 
            ? `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">▲</span>
               <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${Math.abs(changes.gpr).toFixed(2)}</span>`
            : changes.gpr < 0
            ? `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">▼</span>
               <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${changes.gpr.toFixed(2)}</span>`
            : `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>`;
          
          const monthText = previousMonthData 
            ? `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;">dari bulan ${previousMonthData.month.toLowerCase()}</span>`
            : '';
          
          gprChangeEl.innerHTML = changeHtml + monthText;
        }
      } else {
        gprValueEl.textContent = '-';
        gprDateEl.textContent = 'Data tidak tersedia';
      }
    }

    // Populate year dropdown
    function populateYearDropdown() {
      const dropdownMenu = document.getElementById('yearDropdownMenu');
      const selectedYearEl = document.getElementById('selectedYear');
      
      if (!dropdownMenu) return;
      
      dropdownMenu.innerHTML = '';
      
      distinctYears.forEach(year => {
        const li = document.createElement('li');
        const a = document.createElement('a');
        a.className = 'dropdown-item year-option';
        a.href = '#';
        a.setAttribute('data-year', year);
        a.textContent = year;
        if (year === selectedYear) {
          a.classList.add('active');
        }
        li.appendChild(a);
        dropdownMenu.appendChild(li);
      });
      
      if (selectedYear) {
        selectedYearEl.textContent = selectedYear;
      }
      
      // Add event listeners to year options
      document.querySelectorAll('.year-option').forEach(option => {
        option.addEventListener('click', function(e) {
          e.preventDefault();
          selectedYear = parseInt(this.getAttribute('data-year'));
          document.getElementById('selectedYear').textContent = selectedYear;
          
          // Update active state
          document.querySelectorAll('.year-option').forEach(opt => opt.classList.remove('active'));
          this.classList.add('active');
          
          createLineChart(selectedYear);
          setTimeout(() => {
            adjustChartContainerWidth();
            lineChart.resize();
          }, 100);
        });
      });
    }

    // Initialize ECharts instances
    let lineChart = null;
    let comparisonChart = null;
    let yearlyChart = null;
    
    // Function to initialize chart instances
    function initChartInstances() {
      const lineChartEl = document.getElementById('tpkLineChart');
      const comparisonChartEl = document.getElementById('tpkComparisonChart');
      const yearlyChartEl = document.getElementById('tpkYearlyChart');
      
      console.log('Initializing chart instances:', {
        lineChartEl: !!lineChartEl,
        comparisonChartEl: !!comparisonChartEl,
        yearlyChartEl: !!yearlyChartEl,
        echartsAvailable: typeof echarts !== 'undefined'
      });
      
      if (!echarts) {
        console.error('ECharts library not loaded!');
        return;
      }
      
      if (lineChartEl && !lineChart) {
        try {
          lineChart = echarts.init(lineChartEl);
          console.log('Line chart initialized');
        } catch (e) {
          console.error('Error initializing line chart:', e);
        }
      }
      if (comparisonChartEl && !comparisonChart) {
        try {
          comparisonChart = echarts.init(comparisonChartEl);
          console.log('Comparison chart initialized');
        } catch (e) {
          console.error('Error initializing comparison chart:', e);
        }
      }
      if (yearlyChartEl && !yearlyChart) {
        try {
          yearlyChart = echarts.init(yearlyChartEl);
          console.log('Yearly chart initialized');
        } catch (e) {
          console.error('Error initializing yearly chart:', e);
        }
      }
    }

    // Filter data by selected year and sort by month order
    function getDataByYear(year) {
      const allYearData = occupancyData.filter(d => d.year === year);
      const yearData = allYearData.filter(d => d.tpk !== null && d.tpk !== undefined);
      
      const dataMap = {};
      yearData.forEach(d => {
        const normalizedMonth = d.normalizedMonth || normalizeMonth(d.month);
        if (normalizedMonth) {
          dataMap[normalizedMonth] = d;
        }
      });
      
      const sortedData = [];
      monthOrder.forEach(month => {
        if (dataMap[month]) {
          sortedData.push(dataMap[month]);
        }
      });
      
      return sortedData;
    }

    // Create line chart for selected year using ECharts
    function createLineChart(year) {
      if (!lineChart) {
        initChartInstances();
        if (!lineChart) {
          console.error('Line chart element not found');
          return;
        }
      }
      
      const sortedData = getDataByYear(year);
      
      const labels = [];
      const dataValues = [];
      
      sortedData.forEach(d => {
        const normalizedMonth = d.normalizedMonth || normalizeMonth(d.month);
        labels.push(monthAbbr[normalizedMonth] || normalizedMonth || d.month);
        dataValues.push(d.tpk ? d.tpk.toFixed(2) : null);
      });

      console.log('createLineChart data:', {
        year: year,
        sortedDataLength: sortedData.length,
        labels: labels,
        dataValues: dataValues
      });
      
      if (labels.length === 0 || dataValues.length === 0) {
        console.error('No data to display for year', year, {
          sortedData: sortedData,
          occupancyDataLength: occupancyData.length
        });
        return;
      }

      const option = {
        title: {
          show: false
        },
        tooltip: {
          trigger: 'axis',
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          borderColor: 'transparent',
          textStyle: {
            color: '#fff',
            fontSize: 13
          },
          formatter: function(params) {
            const param = params[0];
            return `Bulan: ${param.name}<br/>TPK: ${param.value}%`;
          }
        },
        legend: {
          data: [`TPK ${year}`],
          top: 10,
          textStyle: {
            fontSize: 14
          }
        },
        grid: {
          left: 60,
          right: 40,
          bottom: 60,
          top: 60,
          containLabel: true
        },
        xAxis: {
          type: 'category',
          boundaryGap: false,
          data: labels,
          name: 'Bulan',
          nameLocation: 'middle',
          nameGap: 30,
          nameTextStyle: {
            fontSize: 13,
            fontWeight: 'bold'
          },
          axisLabel: {
            fontSize: 12
          }
        },
        yAxis: {
          type: 'value',
          name: 'Tingkat Penghunian Kamar (%)',
          nameLocation: 'middle',
          nameGap: 50,
          nameTextStyle: {
            fontSize: 13,
            fontWeight: 'bold'
          },
          min: 30,
          max: 70,
          axisLabel: {
            formatter: '{value}%',
            fontSize: 12
          },
          splitLine: {
            lineStyle: {
              color: 'rgba(0, 0, 0, 0.05)'
            }
          }
        },
        series: [
          {
            name: `TPK ${year}`,
            type: 'line',
            data: dataValues,
            smooth: true,
            symbol: 'circle',
            symbolSize: 8,
            itemStyle: {
              color: 'rgb(220, 38, 38)',
              borderColor: '#fff',
              borderWidth: 2
            },
            lineStyle: {
              color: 'rgb(220, 38, 38)',
              width: 2
            },
            areaStyle: {
              color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                { offset: 0, color: 'rgba(220, 38, 38, 0.3)' },
                { offset: 1, color: 'rgba(220, 38, 38, 0.05)' }
              ])
            },
            emphasis: {
              focus: 'series',
              itemStyle: {
                shadowBlur: 10,
                shadowColor: 'rgba(220, 38, 38, 0.5)'
              }
            }
          }
        ]
      };

      try {
        lineChart.setOption(option, true);
        // Resize chart to ensure it renders properly
        setTimeout(() => {
          if (lineChart) {
            lineChart.resize();
          }
        }, 100);
        console.log('Line chart rendered successfully for year:', year);
      } catch (e) {
        console.error('Error setting line chart option:', e);
      }
    }

    // Create comparison chart for multiple years using ECharts
    function createComparisonChart() {
      if (!comparisonChart) {
        initChartInstances();
        if (!comparisonChart) {
          console.error('Comparison chart element not found');
          return;
        }
      }
      
      const recentYears = distinctYears.slice(-3);
      
      const colorPalette = [
        'rgb(220, 38, 38)',   // Red
        'rgb(37, 99, 235)',   // Blue
        'rgb(234, 179, 8)',   // Yellow
        'rgb(34, 197, 94)',   // Green
        'rgb(168, 85, 247)',  // Purple
        'rgb(236, 72, 153)'   // Pink
      ];
      
      const allMonths = new Set();
      recentYears.forEach(year => {
        const yearData = getDataByYear(year);
        yearData.forEach(d => {
          const normalizedMonth = d.normalizedMonth || normalizeMonth(d.month);
          if (normalizedMonth) {
            allMonths.add(normalizedMonth);
          }
        });
      });
      
      const sortedMonths = [];
      monthOrder.forEach(month => {
        if (allMonths.has(month)) {
          sortedMonths.push(month);
        }
      });
      
      const labels = sortedMonths.map(month => monthAbbr[month] || month);
      
      const seriesData = recentYears.map((year, yearIndex) => {
        const yearData = getDataByYear(year);
        const color = colorPalette[yearIndex % colorPalette.length];
        
        const yearDataMap = {};
        yearData.forEach(d => {
          const normalizedMonth = d.normalizedMonth || normalizeMonth(d.month);
          if (normalizedMonth) {
            yearDataMap[normalizedMonth] = d.tpk;
          }
        });
        
        const alignedData = sortedMonths.map(month => {
          const val = yearDataMap[month];
          return val !== undefined ? val.toFixed(2) : null;
        });
        
        return {
          name: `${year}`,
          type: 'line',
          data: alignedData,
          smooth: true,
          symbol: 'circle',
          symbolSize: 8,
          itemStyle: {
            color: color,
            borderColor: '#fff',
            borderWidth: 2
          },
          lineStyle: {
            color: color,
            width: 2
          },
          connectNulls: true,
          emphasis: {
            focus: 'series',
            itemStyle: {
              shadowBlur: 10,
              shadowColor: color
            }
          }
        };
      });

      const option = {
        title: {
          show: false
        },
        tooltip: {
          trigger: 'axis',
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          borderColor: 'transparent',
          textStyle: {
            color: '#fff',
            fontSize: 13
          },
          formatter: function(params) {
            let result = `Bulan: ${params[0].name}<br/>`;
            params.forEach(param => {
              if (param.value !== null) {
                result += `${param.seriesName}: ${param.value}%<br/>`;
              }
            });
            return result;
          }
        },
        legend: {
          data: recentYears.map(y => `${y}`),
          top: 10,
          textStyle: {
            fontSize: 14
          }
        },
        grid: {
          left: 60,
          right: 40,
          bottom: 60,
          top: 60,
          containLabel: true
        },
        xAxis: {
          type: 'category',
          boundaryGap: false,
          data: labels,
          name: 'Bulan',
          nameLocation: 'middle',
          nameGap: 30,
          nameTextStyle: {
            fontSize: 13,
            fontWeight: 'bold'
          },
          axisLabel: {
            fontSize: 12,
            rotate: 0
          }
        },
        yAxis: {
          type: 'value',
          name: 'Tingkat Penghunian Kamar (%)',
          nameLocation: 'middle',
          nameGap: 50,
          nameTextStyle: {
            fontSize: 13,
            fontWeight: 'bold'
          },
          min: 30,
          max: 70,
          axisLabel: {
            formatter: '{value}%',
            fontSize: 12
          },
          splitLine: {
            lineStyle: {
              color: 'rgba(0, 0, 0, 0.05)'
            }
          }
        },
        series: seriesData
      };

      try {
        comparisonChart.setOption(option, true);
        setTimeout(() => {
          if (comparisonChart) {
            comparisonChart.resize();
          }
        }, 100);
        console.log('Comparison chart rendered successfully');
      } catch (e) {
        console.error('Error setting comparison chart option:', e);
      }
    }

    // Create annual TPK chart using yearly data
    function createYearlyTpkChart() {
      if (!yearlyChart) {
        initChartInstances();
        if (!yearlyChart) {
          console.error('Yearly chart element not found');
          return;
        }
      }
      
      const validData = yearlyOccupancyData.filter(d => d.tpk !== null && d.tpk !== undefined);
      validData.sort((a, b) => a.year - b.year);

      if (validData.length === 0) {
        console.warn('No yearly TPK data available');
        return;
      }

      const years = validData.map(d => d.year.toString());
      const tpkValues = validData.map(d => parseFloat(d.tpk));

      // Calculate min and max with 10% gap
      const minValue = Math.min(...tpkValues);
      const maxValue = Math.max(...tpkValues);
      const range = maxValue - minValue;
      const yMin = Math.max(0, minValue - (range * 0.1)); // 10% gap below
      const yMax = maxValue + (range * 0.1); // 10% gap above

      const option = {
        title: {
          show: false
        },
        tooltip: {
          trigger: 'axis',
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          borderColor: 'transparent',
          textStyle: {
            color: '#fff',
            fontSize: 13
          },
          formatter: function(params) {
            const param = params[0];
            return `Tahun: ${param.name}<br/>TPK: ${param.value}%`;
          }
        },
        legend: {
          show: false
        },
        grid: {
          left: 60,
          right: 40,
          bottom: 60,
          top: 40,
          containLabel: true
        },
        xAxis: {
          type: 'category',
          boundaryGap: false,
          data: years,
          name: 'Tahun',
          nameLocation: 'middle',
          nameGap: 30,
          nameTextStyle: {
            fontSize: 13,
            fontWeight: 'bold'
          },
          axisLabel: {
            fontSize: 12
          }
        },
        yAxis: {
          type: 'value',
          name: 'Tingkat Penghunian Kamar (%)',
          nameLocation: 'middle',
          nameGap: 50,
          nameTextStyle: {
            fontSize: 13,
            fontWeight: 'bold'
          },
          min: yMin,
          max: yMax,
          axisLabel: {
            formatter: '{value}%',
            fontSize: 12
          },
          splitLine: {
            lineStyle: {
              color: 'rgba(0, 0, 0, 0.05)'
            }
          }
        },
        series: [
          {
            name: 'TPK Tahunan',
            type: 'line',
            data: tpkValues,
            smooth: true,
            symbol: 'circle',
            symbolSize: 10,
            itemStyle: {
              color: 'rgb(59, 130, 246)',
              borderColor: '#fff',
              borderWidth: 2
            },
            lineStyle: {
              color: 'rgb(59, 130, 246)',
              width: 3
            },
            areaStyle: {
              color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                { offset: 0, color: 'rgba(59, 130, 246, 0.3)' },
                { offset: 1, color: 'rgba(59, 130, 246, 0.05)' }
              ])
            },
            emphasis: {
              focus: 'series',
              itemStyle: {
                shadowBlur: 10,
                shadowColor: 'rgba(59, 130, 246, 0.5)'
              }
            }
          }
        ]
      };

      try {
        yearlyChart.setOption(option, true);
        setTimeout(() => {
          if (yearlyChart) {
            yearlyChart.resize();
          }
        }, 100);
        console.log('Yearly chart rendered successfully');
      } catch (e) {
        console.error('Error setting yearly chart option:', e);
      }
    }

    // Initialize charts
    function initializeCharts() {
      console.log('initializeCharts called', {
        selectedYear: selectedYear,
        lineChart: !!lineChart,
        comparisonChart: !!comparisonChart,
        yearlyChart: !!yearlyChart
      });
      
      // Ensure chart instances are initialized
      initChartInstances();
      
      if (selectedYear && lineChart) {
        console.log('Creating line chart for year:', selectedYear);
        createLineChart(selectedYear);
      } else {
        console.warn('Cannot create line chart:', { selectedYear, lineChart: !!lineChart });
      }
      
      if (comparisonChart) {
        console.log('Creating comparison chart');
        createComparisonChart();
      } else {
        console.warn('Cannot create comparison chart: chart not initialized');
      }
      
      if (yearlyChart) {
        console.log('Creating yearly chart');
        createYearlyTpkChart();
      } else {
        console.warn('Cannot create yearly chart: chart not initialized');
      }
    }

    // Handle window resize for responsive charts
    window.addEventListener('resize', function() {
      if (lineChart) lineChart.resize();
      if (comparisonChart) comparisonChart.resize();
      if (yearlyChart) yearlyChart.resize();
    });

    // Adjust grid for mobile
    function isMobile() {
      return window.innerWidth <= 767;
    }

    function adjustChartForMobile() {
      if (!lineChart || !comparisonChart || !yearlyChart) {
        return;
      }
      
      try {
        if (isMobile()) {
          // Update line chart grid for mobile
          const lineOption = lineChart.getOption();
          if (lineOption && lineOption.grid && lineOption.grid[0]) {
            lineOption.grid[0].left = 50;
            lineOption.grid[0].right = 20;
            lineOption.grid[0].bottom = 50;
            lineChart.setOption(lineOption);
          }

          // Update comparison chart grid for mobile
          const compOption = comparisonChart.getOption();
          if (compOption && compOption.grid && compOption.grid[0]) {
            compOption.grid[0].left = 50;
            compOption.grid[0].right = 20;
            compOption.grid[0].bottom = 50;
            comparisonChart.setOption(compOption);
          }

          // Update yearly chart grid for mobile
          const yearlyOption = yearlyChart.getOption();
          if (yearlyOption && yearlyOption.grid && yearlyOption.grid[0]) {
            yearlyOption.grid[0].left = 50;
            yearlyOption.grid[0].right = 20;
            yearlyOption.grid[0].bottom = 50;
            yearlyChart.setOption(yearlyOption);
          }
        } else {
          // Reset to desktop grid settings
          const lineOption = lineChart.getOption();
          if (lineOption && lineOption.grid && lineOption.grid[0]) {
            lineOption.grid[0].left = 60;
            lineOption.grid[0].right = 40;
            lineOption.grid[0].bottom = 60;
            lineChart.setOption(lineOption);
          }

          const compOption = comparisonChart.getOption();
          if (compOption && compOption.grid && compOption.grid[0]) {
            compOption.grid[0].left = 60;
            compOption.grid[0].right = 40;
            compOption.grid[0].bottom = 60;
            comparisonChart.setOption(compOption);
          }

          const yearlyOption = yearlyChart.getOption();
          if (yearlyOption && yearlyOption.grid && yearlyOption.grid[0]) {
            yearlyOption.grid[0].left = 60;
            yearlyOption.grid[0].right = 40;
            yearlyOption.grid[0].bottom = 60;
            yearlyChart.setOption(yearlyOption);
          }
        }
      } catch (e) {
        console.warn('Error adjusting chart for mobile:', e);
      }
    }

    // Function to adjust chart container width based on actual data
    function adjustChartContainerWidth() {
      if (!isMobile()) {
        // Reset to 100% width on desktop
        document.querySelectorAll('.chart-container').forEach(el => {
          el.style.width = '';
          el.style.minWidth = '';
          el.style.maxWidth = '';
          const wrapper = el.parentElement;
          if (wrapper && wrapper.classList.contains('chart-container-wrapper')) {
            wrapper.style.overflowX = 'hidden';
            // Reset scroll position
            wrapper.scrollLeft = 0;
          }
        });
        
        // Resize all charts after resetting styles using requestAnimationFrame for better timing
        requestAnimationFrame(() => {
          setTimeout(() => {
            if (lineChart) lineChart.resize();
            if (comparisonChart) comparisonChart.resize();
            if (yearlyChart) yearlyChart.resize();
          }, 50);
        });
        return;
      }

      // Helper function to calculate and set width for a chart container
      function setContainerWidth(containerElement, chartInstance, dataLength) {
        if (!containerElement || !chartInstance) return;

        const wrapper = containerElement.parentElement;
        if (!wrapper || !wrapper.classList.contains('chart-container-wrapper')) return;

        // Calculate optimal width based on data points
        // Use approximately 65px per data point for better readability on mobile
        const viewportWidth = wrapper.clientWidth || (window.innerWidth - 60);
        const minWidthPerPoint = 65;
        const calculatedWidth = Math.max(viewportWidth, dataLength * minWidthPerPoint);


        // Set the container width to exactly match content
        containerElement.style.width = calculatedWidth + 'px';
        containerElement.style.minWidth = viewportWidth + 'px';
        containerElement.style.maxWidth = calculatedWidth + 'px';

        // Resize chart after width change
        setTimeout(() => {
          chartInstance.resize();
          
          // Ensure scroll behavior is correct
          const scrollWidth = containerElement.scrollWidth;
          const clientWidth = wrapper.clientWidth;
          
          // If content fits in viewport, disable scroll
          if (scrollWidth <= clientWidth) {
            wrapper.style.overflowX = 'hidden';
          } else {
            wrapper.style.overflowX = 'auto';
          }
          
          // Store data attribute to prevent duplicate handlers
          if (!wrapper.dataset.scrollHandlerAdded) {
            wrapper.dataset.scrollHandlerAdded = 'true';
            
            // Prevent scrolling beyond content bounds
            wrapper.addEventListener('scroll', function() {
              const maxScroll = this.scrollWidth - this.clientWidth;
              if (this.scrollLeft > maxScroll) {
                this.scrollLeft = maxScroll;
              }
              if (this.scrollLeft < 0) {
                this.scrollLeft = 0;
              }
            }, { passive: true });
          }
        }, 150);
      }

      // Adjust line chart container
      const lineChartEl = document.getElementById('tpkLineChart');
      if (lineChartEl) {
        const yearData = getDataByYear(selectedYear);
        setContainerWidth(lineChartEl, lineChart, yearData.length);
      }

      // Adjust comparison chart container
      const comparisonChartEl = document.getElementById('tpkComparisonChart');
      if (comparisonChartEl) {
        const recentYears = years.slice(-3);
        const allMonths = new Set();
        recentYears.forEach(year => {
          const yearData = getDataByYear(year);
          yearData.forEach(d => {
            const normalizedMonth = d.normalizedMonth || normalizeMonth(d.month);
            if (normalizedMonth) {
              allMonths.add(normalizedMonth);
            }
          });
        });
        if (comparisonChart) {
          setContainerWidth(comparisonChartEl, comparisonChart, allMonths.size);
        }
      }

      // Adjust yearly chart container
      const yearlyChartEl = document.getElementById('tpkYearlyChart');
      if (yearlyChartEl && yearlyOccupancyData && yearlyChart) {
        const validData = yearlyOccupancyData.filter(d => d.tpk !== null && d.tpk !== undefined);
        setContainerWidth(yearlyChartEl, yearlyChart, validData.length);
      }
    }

    // Call on initial load - wait for charts to be rendered first
    setTimeout(() => {
      // Only adjust if charts have been initialized and rendered
      if (lineChart && comparisonChart && yearlyChart) {
        adjustChartForMobile();
        adjustChartContainerWidth();
      }
    }, 500);

    // Call on window resize with debounce
    let resizeTimeout;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(() => {
        adjustChartForMobile();
        adjustChartContainerWidth();
      }, 100);
    });

    // Export functions for TPK Line Chart
    function exportTpkLineToExcel() {
      const yearData = getDataByYear(selectedYear);
      const exportData = [];
      exportData.push(['Bulan', 'TPK (%)']);
      yearData.forEach(data => {
        const month = data.normalizedMonth || normalizeMonth(data.month);
        const monthName = monthAbbr[month] || month || data.month;
        const tpk = data.tpk !== null ? data.tpk.toFixed(2) : 'Data tidak tersedia';
        exportData.push([monthName, tpk]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 15 }, { wch: 15 }];
      XLSX.utils.book_append_sheet(wb, ws, `Data TPK ${selectedYear}`);
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Hotel_TPK_Line_${selectedYear}_${today}.xlsx`);
    }

    function exportTpkLineToPNG() {
      const url = lineChart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
      const link = document.createElement('a');
      link.download = `Hotel_TPK_Line_${selectedYear}_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }

    // Helper function to check authentication before download
    function checkAuthBeforeDownload(callback, itemName = 'data') {
      if (isAuthenticated) {
        // User authenticated, proceed with download
        callback();
        return true;
      } else {
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
            window.location.href = '{{ route("login") }}';
          }
        }
        return false;
      }
    }

    document.getElementById('downloadTpkLineExcel').addEventListener('click', function() {
      checkAuthBeforeDownload(exportTpkLineToExcel, 'data TPK line hotel');
    });
    document.getElementById('downloadTpkLinePNG').addEventListener('click', function() {
      checkAuthBeforeDownload(exportTpkLineToPNG, 'grafik TPK line hotel');
    });

    // Export functions for TPK Comparison Chart
    function exportTpkComparisonToExcel() {
      const recentYears = distinctYears.slice(-3);
      const allMonths = new Set();
      recentYears.forEach(year => {
        const yearData = getDataByYear(year);
        yearData.forEach(d => {
          const normalizedMonth = d.normalizedMonth || normalizeMonth(d.month);
          if (normalizedMonth) {
            allMonths.add(normalizedMonth);
          }
        });
      });
      
      const sortedMonths = [];
      monthOrder.forEach(month => {
        if (allMonths.has(month)) {
          sortedMonths.push(month);
        }
      });
      
      const labels = sortedMonths.map(month => monthAbbr[month] || month);
      const exportData = [];
      const header = ['Bulan', ...recentYears.map(y => `${y} (%)`)];
      exportData.push(header);
      
      labels.forEach((label, monthIndex) => {
        const row = [label];
        recentYears.forEach(year => {
          const yearData = getDataByYear(year);
          const month = sortedMonths[monthIndex];
          const dataPoint = yearData.find(d => {
            const normalizedMonth = d.normalizedMonth || normalizeMonth(d.month);
            return normalizedMonth === month;
          });
          const value = dataPoint && dataPoint.tpk !== null ? dataPoint.tpk.toFixed(2) : 'Data tidak tersedia';
          row.push(value);
        });
        exportData.push(row);
      });
      
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 15 }, ...recentYears.map(() => ({ wch: 15 }))];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Perbandingan');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Hotel_TPK_Perbandingan_${today}.xlsx`);
    }

    function exportTpkComparisonToPNG() {
      const url = comparisonChart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
      const link = document.createElement('a');
      link.download = `Hotel_TPK_Perbandingan_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }

    document.getElementById('downloadTpkComparisonExcel').addEventListener('click', function() {
      checkAuthBeforeDownload(exportTpkComparisonToExcel, 'data perbandingan TPK hotel');
    });
    document.getElementById('downloadTpkComparisonPNG').addEventListener('click', function() {
      checkAuthBeforeDownload(exportTpkComparisonToPNG, 'grafik perbandingan TPK hotel');
    });

    // Export functions for TPK Yearly Chart
    function exportTpkYearlyToExcel() {
      // Use data from API (yearlyOccupancyData)
      const validData = yearlyOccupancyData.filter(d => d.tpk !== null && d.tpk !== undefined);
      validData.sort((a, b) => a.year - b.year);

      const exportData = [];
      exportData.push(['Tahun', 'TPK (%)']);
      validData.forEach(data => {
        const tpk = data.tpk !== null ? parseFloat(data.tpk).toFixed(2) : 'Data tidak tersedia';
        exportData.push([data.year.toString(), tpk]);
      });

      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 15 }, { wch: 15 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data TPK Tahunan');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Hotel_TPK_Tahunan_${today}.xlsx`);
    }

    function exportTpkYearlyToPNG() {
      const url = yearlyChart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
      const link = document.createElement('a');
      link.download = `Hotel_TPK_Tahunan_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }

    document.getElementById('downloadTpkYearlyExcel').addEventListener('click', function() {
      checkAuthBeforeDownload(exportTpkYearlyToExcel, 'data TPK tahunan hotel');
    });
    document.getElementById('downloadTpkYearlyPNG').addEventListener('click', function() {
      checkAuthBeforeDownload(exportTpkYearlyToPNG, 'grafik TPK tahunan hotel');
    });
  });
</script>
@endsection