@extends('layouts.main')

@section('title', 'IPM - Harapan Lama Sekolah (HLS)')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
@endpush

@section('content')
<div class="container py-4">
  <h3 class="font-weight-bold mb-4">IPM - Harapan Lama Sekolah (HLS)</h3>
  
  <!-- Summary Cards -->
  <div class="row mb-4" style="display: flex; flex-wrap: nowrap; gap: 15px; margin-left: 0; margin-right: 0;">
    <!-- Surabaya Summary Card -->
    <div class="col-6 mb-3" style="flex: 1; min-width: 0; padding-left: 0; padding-right: 0;">
      <div class="summary-card" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-radius: 12px; padding: 25px; min-height: 200px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
        <div style="position: relative; z-index: 2;">
          <h5 style="color: rgba(255, 255, 255, 0.95); font-size: 16px; font-weight: 600; margin: 0 0 15px 0;">
            <i class="fas fa-graduation-cap me-2"></i>Kota Surabaya
          </h5>
          <h2 style="font-size: 42px; font-weight: 700; line-height: 1.2; margin: 0 0 10px 0;">
            <span id="surabaya-value">-</span> <span style="font-size: 24px; font-weight: 400;">tahun</span>
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
          <i class="fas fa-graduation-cap" style="font-size: 80px;"></i>
        </div>
      </div>
    </div>

    <!-- Jawa Timur Summary Card -->
    <div class="col-6 mb-3" style="flex: 1; min-width: 0; padding-left: 0; padding-right: 0;">
      <div class="summary-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 12px; padding: 25px; min-height: 200px; position: relative; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
        <div style="position: relative; z-index: 2;">
          <h5 style="color: rgba(255, 255, 255, 0.95); font-size: 16px; font-weight: 600; margin: 0 0 15px 0;">
            <i class="fas fa-graduation-cap me-2"></i>Jawa Timur
          </h5>
          <h2 style="font-size: 42px; font-weight: 700; line-height: 1.2; margin: 0 0 10px 0;">
            <span id="jatim-value">-</span> <span style="font-size: 24px; font-weight: 400;">tahun</span>
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
          <i class="fas fa-graduation-cap" style="font-size: 80px;"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Comparison Chart -->
  <div class="row mb-4">
    <div class="col-md-12">
      <div class="dashboard-card" style="position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
          <h5 class="mb-0">Perbandingan HLS: Surabaya vs Jawa Timur</h5>
          <div style="display: flex; gap: 8px;">
            <button id="downloadChartHLS" class="btn btn-sm btn-outline-primary" style="padding: 5px 10px; border-radius: 5px;" title="Download Data Excel">
              <i class="fas fa-file-excel"></i> <span>Excel</span>
            </button>
            <button id="downloadImageHLS" class="btn btn-sm btn-outline-success" style="padding: 5px 10px; border-radius: 5px;" title="Download Grafik PNG">
              <i class="fas fa-image"></i> <span>PNG</span>
            </button>
          </div>
        </div>
        <div id="comparisonChart" style="width: 100%; height: 450px;"></div>
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

  /* Responsive styles for summary cards */
  @media (max-width: 768px) {
    .summary-card {
      padding: 15px !important;
      min-height: 150px !important;
    }
    
    .summary-card h5 {
      font-size: 12px !important;
      margin: 0 0 8px 0 !important;
    }
    
    .summary-card h2 {
      font-size: 24px !important;
      margin: 0 0 8px 0 !important;
    }
    
    .summary-card small {
      font-size: 9px !important;
    }
    
    .summary-card span {
      font-size: 10px !important;
    }
    
    .summary-card i[style*="font-size: 80px"] {
      font-size: 50px !important;
    }
    
    /* Download button responsive */
    #downloadChartHLS, #downloadImageHLS {
      padding: 3px 8px !important;
      font-size: 11px !important;
    }
    
    #downloadChartHLS i, #downloadImageHLS i {
      font-size: 10px !important;
    }
    
    #downloadChartHLS span, #downloadImageHLS span {
      display: none;
    }
  }
  
  @media (max-width: 576px) {
    #downloadChartHLS, #downloadImageHLS {
      padding: 4px 6px !important;
      font-size: 10px !important;
    }
    
    #downloadChartHLS i, #downloadImageHLS i {
      font-size: 12px !important;
      margin: 0 !important;
    }
  }
</style>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const API_BASE = '{{ url("/api") }}';
    let surabayaData = [];
    let jatimData = [];
    let comparisonChart = null;

    // Load summary data for cards
    async function loadSummaryData() {
      try {
        const response = await fetch(`${API_BASE}/ipm-hls-summary`);
        const result = await response.json();
        
        if (result.success && result.data) {
          const data = result.data;
          
          // Update Surabaya card
          if (data.surabaya_latest) {
            document.getElementById('surabaya-value').textContent = data.surabaya_latest.value !== null 
              ? parseFloat(data.surabaya_latest.value).toFixed(2) 
              : '-';
            document.getElementById('surabaya-year').textContent = `Tahun ${data.surabaya_latest.year}`;
            
            // Update change indicator
            const surabayaChangeEl = document.getElementById('surabaya-change');
            if (data.surabaya_change !== null) {
              const changeText = data.surabaya_change > 0 
                ? `+${data.surabaya_change.toFixed(2)} tahun` 
                : `${data.surabaya_change.toFixed(2)} tahun`;
              const arrow = data.surabaya_change > 0 ? '▲' : '▼';
              const previousYear = data.surabaya_previous ? data.surabaya_previous.year : '';
              
              surabayaChangeEl.innerHTML = `
                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${arrow}</span>
                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${changeText}</span>
                ${previousYear ? `<span style="color: rgba(255, 255, 255, 0.7); font-size: 10px;">dari ${previousYear}</span>` : ''}
              `;
            } else {
              surabayaChangeEl.innerHTML = '<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>';
            }
          }
          
          // Update Jawa Timur card
          if (data.jatim_latest) {
            document.getElementById('jatim-value').textContent = data.jatim_latest.value !== null 
              ? parseFloat(data.jatim_latest.value).toFixed(2) 
              : '-';
            document.getElementById('jatim-year').textContent = `Tahun ${data.jatim_latest.year}`;
            
            // Update change indicator
            const jatimChangeEl = document.getElementById('jatim-change');
            if (data.jatim_change !== null) {
              const changeText = data.jatim_change > 0 
                ? `+${data.jatim_change.toFixed(2)} tahun` 
                : `${data.jatim_change.toFixed(2)} tahun`;
              const arrow = data.jatim_change > 0 ? '▲' : '▼';
              const previousYear = data.jatim_previous ? data.jatim_previous.year : '';
              
              jatimChangeEl.innerHTML = `
                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${arrow}</span>
                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${changeText}</span>
                ${previousYear ? `<span style="color: rgba(255, 255, 255, 0.7); font-size: 10px;">dari ${previousYear}</span>` : ''}
              `;
            } else {
              jatimChangeEl.innerHTML = '<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>';
            }
          }
          
          // Store data for chart
          surabayaData = data.surabaya_data || [];
          jatimData = data.jatim_data || [];
          
          // Render chart
          renderChart();
        }
      } catch (error) {
        console.error('Error loading HLS summary data:', error);
      }
    }

    function renderChart() {
      // Process data
      const surabayaProcessed = surabayaData.map(d => ({
        year: d.year,
        value: d.value !== null ? parseFloat(d.value) : null
      }));
      
      const jatimProcessed = jatimData.map(d => ({
        year: d.year,
        value: d.value !== null ? parseFloat(d.value) : null
      }));

      surabayaProcessed.sort((a, b) => a.year - b.year);
      jatimProcessed.sort((a, b) => a.year - b.year);

      const allYearsSet = new Set([
        ...surabayaProcessed.map(d => d.year),
        ...jatimProcessed.map(d => d.year)
      ]);
      const allYears = Array.from(allYearsSet).sort((a, b) => a - b);
      
      // Only show last 10 years if more than 10 years
      const displayYears = allYears.length > 10 ? allYears.slice(-10) : allYears;
      const years = displayYears.map(y => y.toString());
      
      // Store displayYears in global scope for export function
      window.displayYearsHLS = displayYears;

      const surabayaValues = displayYears.map(year => {
        const data = surabayaProcessed.find(d => d.year === year);
        return data && data.value !== null ? data.value : null;
      });

      const jatimValues = displayYears.map(year => {
        const data = jatimProcessed.find(d => d.year === year);
        return data && data.value !== null ? data.value : null;
      });
      
      // Store values in global scope for export function
      window.surabayaValuesHLS = surabayaValues;
      window.jatimValuesHLS = jatimValues;

      const comparisonChartDom = document.getElementById('comparisonChart');
      comparisonChart = echarts.init(comparisonChartDom);
      
      comparisonChart.setOption({
        tooltip: {
          trigger: 'axis',
          axisPointer: { type: 'line', snap: true, lineStyle: { type: 'dashed' } },
          formatter: function(params) {
            let result = 'Tahun: ' + params[0].axisValue + '<br/>';
            params.forEach(function(item) {
              result += item.marker + item.seriesName + ': ' + 
                (item.value !== null ? item.value.toFixed(2) + ' tahun' : 'Data tidak tersedia') + '<br/>';
            });
            return result;
          }
        },
        legend: { data: ['Kota Surabaya', 'Jawa Timur'], top: 10 },
        grid: { left: '12%', right: '4%', bottom: '10%', top: '20%', containLabel: false },
        xAxis: { type: 'category', data: years, boundaryGap: false },
        yAxis: {
          type: 'value',
          name: 'Tahun',
          position: 'left',
          nameLocation: 'end',
          nameGap: 10,
          axisLabel: { formatter: '{value}' }
        },
        series: [
          {
            name: 'Kota Surabaya',
            type: 'line',
            data: surabayaValues,
            itemStyle: { color: 'rgb(59, 130, 246)' },
            lineStyle: { width: 3 },
            symbol: 'circle',
            symbolSize: 8,
            smooth: true,
            areaStyle: {
              color: {
                type: 'linear',
                x: 0, y: 0, x2: 0, y2: 1,
                colorStops: [
                  { offset: 0, color: 'rgba(59, 130, 246, 0.3)' },
                  { offset: 1, color: 'rgba(59, 130, 246, 0.05)' }
                ]
              }
            }
          },
          {
            name: 'Jawa Timur',
            type: 'line',
            data: jatimValues,
            itemStyle: { color: 'rgb(16, 185, 129)' },
            lineStyle: { width: 3 },
            symbol: 'circle',
            symbolSize: 8,
            smooth: true,
            areaStyle: {
              color: {
                type: 'linear',
                x: 0, y: 0, x2: 0, y2: 1,
                colorStops: [
                  { offset: 0, color: 'rgba(16, 185, 129, 0.3)' },
                  { offset: 1, color: 'rgba(16, 185, 129, 0.05)' }
                ]
              }
            }
          }
        ]
      });

      window.addEventListener('resize', function() {
        comparisonChart.resize();
      });
    }

    // Export to Excel function
    function exportToExcel() {
      // Prepare data for export
      const exportData = [];
      
      // Header row
      exportData.push(['Tahun', 'Kota Surabaya (tahun)', 'Jawa Timur (tahun)']);
      
      // Data rows
      if (window.displayYearsHLS && window.surabayaValuesHLS && window.jatimValuesHLS) {
        window.displayYearsHLS.forEach((year, index) => {
          const surabayaVal = window.surabayaValuesHLS[index] !== null 
            ? window.surabayaValuesHLS[index].toFixed(2) 
            : 'Data tidak tersedia';
          const jatimVal = window.jatimValuesHLS[index] !== null 
            ? window.jatimValuesHLS[index].toFixed(2) 
            : 'Data tidak tersedia';
          exportData.push([year.toString(), surabayaVal, jatimVal]);
        });
      }
      
      // Create workbook and worksheet
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      
      // Set column widths
      ws['!cols'] = [
        { wch: 10 }, // Tahun
        { wch: 25 }, // Kota Surabaya
        { wch: 25 }  // Jawa Timur
      ];
      
      // Add worksheet to workbook
      XLSX.utils.book_append_sheet(wb, ws, 'Data HLS');
      
      // Generate filename with current date
      const today = new Date();
      const dateStr = today.toISOString().split('T')[0];
      const filename = `HLS_Surabaya_vs_JawaTimur_${dateStr}.xlsx`;
      
      // Save file
      XLSX.writeFile(wb, filename);
    }
    
    // Helper function to check authentication before download
    function checkAuthBeforeDownload(callback, itemName = 'data') {
      @auth
      // User authenticated, proceed with download
      callback();
      return true;
      @else
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
      @endauth
    }

    // Add click event to download button
    document.getElementById('downloadChartHLS').addEventListener('click', function() {
      checkAuthBeforeDownload(exportToExcel, 'data HLS');
    });
    
    // Export chart as PNG
    function exportToPNGHLS() {
      if (!comparisonChart) {
        alert('Grafik belum dimuat. Silakan tunggu sebentar.');
        return;
      }
      
      const url = comparisonChart.getDataURL({
        type: 'png',
        pixelRatio: 2,
        backgroundColor: '#fff'
      });
      const link = document.createElement('a');
      link.download = `HLS_Chart_Surabaya_vs_JawaTimur_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }
    
    document.getElementById('downloadImageHLS').addEventListener('click', function() {
      checkAuthBeforeDownload(exportToPNGHLS, 'grafik HLS');
    });

    // Load data on page load
    loadSummaryData();
  });
</script>

@endsection
