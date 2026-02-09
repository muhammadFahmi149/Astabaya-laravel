@extends('layouts.main')

@section('title', 'IPM - Pengeluaran per Kapita')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
@endpush

@push('scripts')
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
            <span id="latest-value">-</span>
          </h2>
          <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255, 255, 255, 0.2);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
              <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px;" id="latest-year">
                Data tidak tersedia
              </small>
              <div style="display: flex; align-items: center; gap: 5px;" id="change-indicator">
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
            <span id="latest-jatim-value">-</span>
          </h2>
          <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255, 255, 255, 0.2);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
              <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px;" id="latest-jatim-year">
                Data tidak tersedia
              </small>
              <div style="display: flex; align-items: center; gap: 5px;" id="jatim-change-indicator">
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
            <button id="downloadChartPengeluaran" class="btn btn-sm btn-outline-primary" style="padding: 5px 10px; border-radius: 5px;" title="Download Data Excel">
              <i class="fas fa-file-excel"></i> <span>Excel</span>
            </button>
            <button id="downloadImagePengeluaran" class="btn btn-sm btn-outline-success" style="padding: 5px 10px; border-radius: 5px;" title="Download Grafik PNG">
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
  /* Ensure tooltip can appear - fix overflow issues */
  .dashboard-card, .row, .container, .col-md-12, .col-md-6 {
    overflow: visible !important;
  }
  
  .dashboard-card { background-color: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 20px; margin-bottom: 20px; }
  .summary-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
  .summary-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25); }
  
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
    #downloadChartPengeluaran, #downloadImagePengeluaran {
      padding: 3px 8px !important;
      font-size: 11px !important;
    }
    
    #downloadChartPengeluaran i, #downloadImagePengeluaran i {
      font-size: 10px !important;
    }
    
    #downloadChartPengeluaran span, #downloadImagePengeluaran span {
      display: none;
    }
  }
  
  @media (max-width: 576px) {
    #downloadChartPengeluaran, #downloadImagePengeluaran {
      padding: 4px 6px !important;
      font-size: 10px !important;
    }
    
    #downloadChartPengeluaran i, #downloadImagePengeluaran i {
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

    function formatNumber(num) {
      return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Load summary data for cards
    async function loadSummaryData() {
      try {
        const response = await fetch(`${API_BASE}/ipm-pengeluaran-per-kapita-summary`);
        const result = await response.json();
        
        if (result.success && result.data) {
          const data = result.data;
          
          // Update Surabaya card
          if (data.surabaya_latest) {
            const latestValueEl = document.getElementById('latest-value');
            if (latestValueEl && data.surabaya_latest.value !== null) {
              const value = Math.round(parseFloat(data.surabaya_latest.value));
              latestValueEl.textContent = 'Rp ' + formatNumber(value);
            } else if (latestValueEl) {
              latestValueEl.textContent = '-';
            }
            
            const latestYearEl = document.getElementById('latest-year');
            if (latestYearEl) {
              latestYearEl.textContent = `Tahun ${data.surabaya_latest.year}`;
            }
            
            // Update change indicator
            const changeIndicatorEl = document.getElementById('change-indicator');
            if (changeIndicatorEl) {
              if (data.surabaya_change !== null) {
                const changeValue = Math.round(Math.abs(data.surabaya_change));
                const changeText = data.surabaya_change > 0 
                  ? `+Rp ${formatNumber(changeValue)}` 
                  : `Rp ${formatNumber(changeValue)}`;
                const arrow = data.surabaya_change > 0 ? '▲' : '▼';
                const previousYear = data.surabaya_previous ? data.surabaya_previous.year : '';
                
                changeIndicatorEl.innerHTML = `
                  <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${arrow}</span>
                  <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${changeText}</span>
                  ${previousYear ? `<span style="color: rgba(255, 255, 255, 0.7); font-size: 10px;">dari ${previousYear}</span>` : ''}
                `;
              } else {
                changeIndicatorEl.innerHTML = '<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>';
              }
            }
          }
          
          // Update Jawa Timur card
          if (data.jatim_latest) {
            const latestJatimValueEl = document.getElementById('latest-jatim-value');
            if (latestJatimValueEl && data.jatim_latest.value !== null) {
              const value = Math.round(parseFloat(data.jatim_latest.value));
              latestJatimValueEl.textContent = 'Rp ' + formatNumber(value);
            } else if (latestJatimValueEl) {
              latestJatimValueEl.textContent = '-';
            }
            
            const latestJatimYearEl = document.getElementById('latest-jatim-year');
            if (latestJatimYearEl) {
              latestJatimYearEl.textContent = `Tahun ${data.jatim_latest.year}`;
            }
            
            // Update change indicator
            const jatimChangeIndicatorEl = document.getElementById('jatim-change-indicator');
            if (jatimChangeIndicatorEl) {
              if (data.jatim_change !== null) {
                const changeValue = Math.round(Math.abs(data.jatim_change));
                const changeText = data.jatim_change > 0 
                  ? `+Rp ${formatNumber(changeValue)}` 
                  : `Rp ${formatNumber(changeValue)}`;
                const arrow = data.jatim_change > 0 ? '▲' : '▼';
                const previousYear = data.jatim_previous ? data.jatim_previous.year : '';
                
                jatimChangeIndicatorEl.innerHTML = `
                  <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${arrow}</span>
                  <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${changeText}</span>
                  ${previousYear ? `<span style="color: rgba(255, 255, 255, 0.7); font-size: 10px;">dari ${previousYear}</span>` : ''}
                `;
              } else {
                jatimChangeIndicatorEl.innerHTML = '<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>';
              }
            }
          }
          
          // Store data for chart
          surabayaData = data.surabaya_data || [];
          jatimData = data.jatim_data || [];
          
          // Render chart
          renderChart();
        }
      } catch (error) {
        console.error('Error loading Pengeluaran Per Kapita summary data:', error);
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
      window.displayYearsPengeluaran = displayYears;

      const surabayaValues = displayYears.map(year => {
        const data = surabayaProcessed.find(d => d.year === year);
        return data && data.value !== null ? data.value : null;
      });

      const jatimValues = displayYears.map(year => {
        const data = jatimProcessed.find(d => d.year === year);
        return data && data.value !== null ? data.value : null;
      });
      
      // Store values in global scope for export function
      window.surabayaValuesPengeluaran = surabayaValues;
      window.jatimValuesPengeluaran = jatimValues;

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
                (item.value !== null ? 'Rp ' + item.value.toLocaleString('id-ID') : 'Data tidak tersedia') + '<br/>';
            });
            return result;
          }
        },
        legend: { data: ['Kota Surabaya', 'Jawa Timur'], top: 10 },
        grid: { left: '12%', right: '4%', bottom: '10%', top: '20%', containLabel: false },
        xAxis: { type: 'category', data: years, boundaryGap: false },
        yAxis: {
          type: 'value',
          name: 'Rupiah',
          position: 'left',
          nameLocation: 'end',
          nameGap: 10,
          axisLabel: {
            formatter: function(value) {
              if (value >= 1000000) return (value / 1000000).toFixed(1) + 'Jt';
              else if (value >= 1000) return (value / 1000).toFixed(0) + 'Rb';
              return value.toString();
            }
          }
        },
        series: [
          {
            name: 'Kota Surabaya',
            type: 'line',
            data: surabayaValues,
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
          },
          {
            name: 'Jawa Timur',
            type: 'line',
            data: jatimValues,
            itemStyle: { color: 'rgb(245, 158, 11)' },
            lineStyle: { width: 3 },
            symbol: 'circle',
            symbolSize: 8,
            smooth: true,
            areaStyle: {
              color: {
                type: 'linear',
                x: 0, y: 0, x2: 0, y2: 1,
                colorStops: [
                  { offset: 0, color: 'rgba(245, 158, 11, 0.3)' },
                  { offset: 1, color: 'rgba(245, 158, 11, 0.05)' }
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
    
    function exportToExcelPengeluaran() {
      const exportData = [['Tahun', 'Kota Surabaya (Rp)', 'Jawa Timur (Rp)']];
      if (window.displayYearsPengeluaran && window.surabayaValuesPengeluaran && window.jatimValuesPengeluaran) {
        window.displayYearsPengeluaran.forEach((year, index) => {
          const surabayaVal = window.surabayaValuesPengeluaran[index] !== null 
            ? window.surabayaValuesPengeluaran[index].toFixed(2) 
            : 'Data tidak tersedia';
          const jatimVal = window.jatimValuesPengeluaran[index] !== null 
            ? window.jatimValuesPengeluaran[index].toFixed(2) 
            : 'Data tidak tersedia';
          exportData.push([year.toString(), surabayaVal, jatimVal]);
        });
      }
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 10 }, { wch: 25 }, { wch: 25 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Pengeluaran');
      const today = new Date();
      XLSX.writeFile(wb, `Pengeluaran_Per_Kapita_Surabaya_vs_JawaTimur_${today.toISOString().split('T')[0]}.xlsx`);
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

    document.getElementById('downloadChartPengeluaran').addEventListener('click', function() {
      checkAuthBeforeDownload(exportToExcelPengeluaran, 'data pengeluaran per kapita');
    });
    
    function exportToPNGPengeluaran() {
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
      link.download = `Pengeluaran_Per_Kapita_Chart_Surabaya_vs_JawaTimur_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }
    
    document.getElementById('downloadImagePengeluaran').addEventListener('click', function() {
      checkAuthBeforeDownload(exportToPNGPengeluaran, 'grafik pengeluaran per kapita');
    });

    // Load data on page load
    loadSummaryData();
  });
</script>

@endsection
