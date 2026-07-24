

  document.addEventListener("DOMContentLoaded", async () => {
    // API Base URL
    const API_BASE = window.APP_CONFIG.apiBase;
    
    // Initialize data variables
    let surabayaData = [];
    let jatimData = [];
    let surabayaLatest = null;
    let surabayaPrevious = null;
    let surabayaChanges = {
      jumlah_penduduk_miskin: null,
      persentase_penduduk_miskin: null,
      indeks_kedalaman_kemiskinan_p1: null,
      indeks_keparahan_kemiskinan_p2: null,
      garis_kemiskinan: null,
    };

    // Cache Key
    const CACHE_KEY = 'astabaya_kemiskinan_summary';
    let result = null;

    // Load summary data from API or Cache
    try {
      const cachedData = sessionStorage.getItem(CACHE_KEY);
      if (cachedData) {
        result = JSON.parse(cachedData);
        console.log('Loaded kemiskinan data from sessionStorage cache');
      } else {
        const response = await fetch(`${API_BASE}/kemiskinan-summary`);
        
        // Validate response
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        result = await response.json();
        
        // Save to cache if successful
        if (result && result.success) {
          sessionStorage.setItem(CACHE_KEY, JSON.stringify(result));
        }
      }
      
      // Validate response structure
      if (result.success && result.data) {
        const data = result.data;
        
        // Validate and sanitize data arrays
        surabayaData = Array.isArray(data.surabaya_data) ? data.surabaya_data.map(item => ({
          year: window.sanitizeYear(item.year),
          jumlah_penduduk_miskin: window.sanitizeNumber(item.jumlah_penduduk_miskin),
          persentase_penduduk_miskin: window.sanitizeNumber(item.persentase_penduduk_miskin),
          indeks_kedalaman_kemiskinan_p1: window.sanitizeNumber(item.indeks_kedalaman_kemiskinan_p1),
          indeks_keparahan_kemiskinan_p2: window.sanitizeNumber(item.indeks_keparahan_kemiskinan_p2),
          garis_kemiskinan: window.sanitizeNumber(item.garis_kemiskinan)
        })).filter(item => item.year !== null) : [];
        
        jatimData = Array.isArray(data.jatim_data) ? data.jatim_data.map(item => ({
          year: window.sanitizeYear(item.year),
          jumlah_penduduk_miskin: window.sanitizeNumber(item.jumlah_penduduk_miskin),
          persentase_penduduk_miskin: window.sanitizeNumber(item.persentase_penduduk_miskin),
          indeks_kedalaman_kemiskinan_p1: window.sanitizeNumber(item.indeks_kedalaman_kemiskinan_p1),
          indeks_keparahan_kemiskinan_p2: window.sanitizeNumber(item.indeks_keparahan_kemiskinan_p2),
          garis_kemiskinan: window.sanitizeNumber(item.garis_kemiskinan)
        })).filter(item => item.year !== null) : [];
        
        // Validate and sanitize latest/previous data
        if (data.surabaya_latest) {
          surabayaLatest = {
            year: window.sanitizeYear(data.surabaya_latest.year),
            jumlah_penduduk_miskin: window.sanitizeNumber(data.surabaya_latest.jumlah_penduduk_miskin),
            persentase_penduduk_miskin: window.sanitizeNumber(data.surabaya_latest.persentase_penduduk_miskin),
            indeks_kedalaman_kemiskinan_p1: window.sanitizeNumber(data.surabaya_latest.indeks_kedalaman_kemiskinan_p1),
            indeks_keparahan_kemiskinan_p2: window.sanitizeNumber(data.surabaya_latest.indeks_keparahan_kemiskinan_p2),
            garis_kemiskinan: window.sanitizeNumber(data.surabaya_latest.garis_kemiskinan)
          };
        }
        
        if (data.surabaya_previous) {
          surabayaPrevious = {
            year: window.sanitizeYear(data.surabaya_previous.year),
            jumlah_penduduk_miskin: window.sanitizeNumber(data.surabaya_previous.jumlah_penduduk_miskin),
            persentase_penduduk_miskin: window.sanitizeNumber(data.surabaya_previous.persentase_penduduk_miskin),
            indeks_kedalaman_kemiskinan_p1: window.sanitizeNumber(data.surabaya_previous.indeks_kedalaman_kemiskinan_p1),
            indeks_keparahan_kemiskinan_p2: window.sanitizeNumber(data.surabaya_previous.indeks_keparahan_kemiskinan_p2),
            garis_kemiskinan: window.sanitizeNumber(data.surabaya_previous.garis_kemiskinan)
          };
        }
        
        // Validate and sanitize changes
        if (data.surabaya_changes) {
          surabayaChanges = {
            jumlah_penduduk_miskin: window.sanitizeNumber(data.surabaya_changes.jumlah_penduduk_miskin),
            persentase_penduduk_miskin: window.sanitizeNumber(data.surabaya_changes.persentase_penduduk_miskin),
            indeks_kedalaman_kemiskinan_p1: window.sanitizeNumber(data.surabaya_changes.indeks_kedalaman_kemiskinan_p1),
            indeks_keparahan_kemiskinan_p2: window.sanitizeNumber(data.surabaya_changes.indeks_keparahan_kemiskinan_p2),
            garis_kemiskinan: window.sanitizeNumber(data.surabaya_changes.garis_kemiskinan)
          };
        }
      } else {
        console.error('Failed to load kemiskinan summary data:', result.message || 'Unknown error');
      }
    } catch (error) {
      console.error('Error loading kemiskinan summary data:', error);
      // Set empty data on error to prevent undefined errors
      surabayaData = [];
      jatimData = [];
    }

    // Sort data by year
    surabayaData.sort((a, b) => a.year - b.year);
    jatimData.sort((a, b) => a.year - b.year);

    // Function to update summary cards UI
    function updateSummaryCards() {
      // Update Jumlah Penduduk Miskin
      if (surabayaLatest && surabayaLatest.jumlah_penduduk_miskin !== null && surabayaLatest.year !== null) {
        const value = window.sanitizeNumber(surabayaLatest.jumlah_penduduk_miskin);
        if (value !== null) {
          document.getElementById('jumlah-penduduk-miskin-value').textContent = value.toFixed(2);
          document.getElementById('jumlah-penduduk-miskin-year').textContent = `Tahun ${window.escapeHtml(surabayaLatest.year)}`;
          
          const changeEl = document.getElementById('jumlah-penduduk-miskin-change');
          const changeValue = window.sanitizeNumber(surabayaChanges.jumlah_penduduk_miskin);
          if (changeValue !== null) {
            let changeHtml = '';
            if (changeValue > 0) {
              changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">▲</span>
                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${window.escapeHtml(changeValue.toFixed(2))} <span style="font-size: 10px;">ribu</span></span>`;
            } else if (changeValue < 0) {
              changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">▼</span>
                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${window.escapeHtml(changeValue.toFixed(2))} <span style="font-size: 10px;">ribu</span></span>`;
            } else {
              changeHtml = '<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>';
            }
            if (surabayaPrevious && surabayaPrevious.year !== null) {
              changeHtml += `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari ${window.escapeHtml(surabayaPrevious.year)}</span>`;
            }
            changeEl.innerHTML = changeHtml;
          }
        }
      }

      // Update Persentase Kemiskinan
      if (surabayaLatest && surabayaLatest.persentase_penduduk_miskin !== null && surabayaLatest.year !== null) {
        const value = window.sanitizeNumber(surabayaLatest.persentase_penduduk_miskin);
        if (value !== null) {
          document.getElementById('persentase-kemiskinan-value').textContent = value.toFixed(2);
          document.getElementById('persentase-kemiskinan-year').textContent = `Tahun ${window.escapeHtml(surabayaLatest.year)}`;
          
          const changeEl = document.getElementById('persentase-kemiskinan-change');
          const changeValue = window.sanitizeNumber(surabayaChanges.persentase_penduduk_miskin);
          if (changeValue !== null) {
            let changeHtml = '';
            if (changeValue > 0) {
              changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">▲</span>
                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${window.escapeHtml(changeValue.toFixed(2))}%</span>`;
            } else if (changeValue < 0) {
              changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">▼</span>
                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${window.escapeHtml(changeValue.toFixed(2))}%</span>`;
            } else {
              changeHtml = '<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>';
            }
            if (surabayaPrevious && surabayaPrevious.year !== null) {
              changeHtml += `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari ${window.escapeHtml(surabayaPrevious.year)}</span>`;
            }
            changeEl.innerHTML = changeHtml;
          }
        }
      }

      // Update Indeks Kedalaman (P1)
      if (surabayaLatest && surabayaLatest.indeks_kedalaman_kemiskinan_p1 !== null && surabayaLatest.year !== null) {
        const value = window.sanitizeNumber(surabayaLatest.indeks_kedalaman_kemiskinan_p1);
        if (value !== null) {
          document.getElementById('indeks-kedalaman-p1-value').textContent = value.toFixed(2);
          document.getElementById('indeks-kedalaman-p1-year').textContent = `Tahun ${window.escapeHtml(surabayaLatest.year)}`;
          
          const changeEl = document.getElementById('indeks-kedalaman-p1-change');
          const changeValue = window.sanitizeNumber(surabayaChanges.indeks_kedalaman_kemiskinan_p1);
          if (changeValue !== null) {
            let changeHtml = '';
            if (changeValue > 0) {
              changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">▲</span>
                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${window.escapeHtml(changeValue.toFixed(2))}</span>`;
            } else if (changeValue < 0) {
              changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">▼</span>
                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${window.escapeHtml(changeValue.toFixed(2))}</span>`;
            } else {
              changeHtml = '<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>';
            }
            if (surabayaPrevious && surabayaPrevious.year !== null) {
              changeHtml += `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari ${window.escapeHtml(surabayaPrevious.year)}</span>`;
            }
            changeEl.innerHTML = changeHtml;
          }
        }
      }

      // Update Indeks Keparahan (P2)
      if (surabayaLatest && surabayaLatest.indeks_keparahan_kemiskinan_p2 !== null && surabayaLatest.year !== null) {
        const value = window.sanitizeNumber(surabayaLatest.indeks_keparahan_kemiskinan_p2);
        if (value !== null) {
          document.getElementById('indeks-keparahan-p2-value').textContent = value.toFixed(2);
          document.getElementById('indeks-keparahan-p2-year').textContent = `Tahun ${window.escapeHtml(surabayaLatest.year)}`;
          
          const changeEl = document.getElementById('indeks-keparahan-p2-change');
          const changeValue = window.sanitizeNumber(surabayaChanges.indeks_keparahan_kemiskinan_p2);
          if (changeValue !== null) {
            let changeHtml = '';
            if (changeValue > 0) {
              changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">▲</span>
                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${window.escapeHtml(changeValue.toFixed(2))}</span>`;
            } else if (changeValue < 0) {
              changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">▼</span>
                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${window.escapeHtml(changeValue.toFixed(2))}</span>`;
            } else {
              changeHtml = '<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>';
            }
            if (surabayaPrevious && surabayaPrevious.year !== null) {
              changeHtml += `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari ${window.escapeHtml(surabayaPrevious.year)}</span>`;
            }
            changeEl.innerHTML = changeHtml;
          }
        }
      }

      // Update Garis Kemiskinan
      if (surabayaLatest && surabayaLatest.garis_kemiskinan !== null && surabayaLatest.year !== null) {
        const garisKemiskinanValue = window.sanitizeNumber(surabayaLatest.garis_kemiskinan);
        if (garisKemiskinanValue !== null) {
          document.getElementById('garis-kemiskinan-value').textContent = 'Rp ' + window.formatRupiah(garisKemiskinanValue);
          document.getElementById('garis-kemiskinan-year').textContent = `Tahun ${window.escapeHtml(surabayaLatest.year)} (Rp/kap/bulan)`;
          
          const changeEl = document.getElementById('garis-kemiskinan-change');
          const changeValue = window.sanitizeNumber(surabayaChanges.garis_kemiskinan);
          if (changeValue !== null) {
            let changeHtml = '';
            if (changeValue > 0) {
              changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">▲</span>
                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;" class="garis-kemiskinan-change">+Rp ${window.escapeHtml(window.formatRupiah(changeValue))}</span>`;
            } else if (changeValue < 0) {
              changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">▼</span>
                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;" class="garis-kemiskinan-change">Rp ${window.escapeHtml(window.formatRupiah(changeValue))}</span>`;
            } else {
              changeHtml = '<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>';
            }
            if (surabayaPrevious && surabayaPrevious.year !== null) {
              changeHtml += `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari ${window.escapeHtml(surabayaPrevious.year)}</span>`;
            }
            changeEl.innerHTML = changeHtml;
          }
        }
      }
    }

    // Update UI
    updateSummaryCards();

    // Get last 10 years for comparison chart
    const allYears = [...new Set([...surabayaData.map(d => d.year), ...jatimData.map(d => d.year)])].sort();
    const last10Years = allYears.slice(-10);

    // Get last 5 years for chart 1
    const last5Years = allYears.slice(-5);

    // Filter data to last 10 years for comparison
    const surabayaDataLast10 = surabayaData.filter(d => last10Years.includes(d.year));
    const jatimDataLast10 = jatimData.filter(d => last10Years.includes(d.year));

    // Filter data to last 5 years for chart 1
    const surabayaDataLast5 = surabayaData.filter(d => last5Years.includes(d.year));

    // Check if mobile
    const isMobile = window.innerWidth <= 767.98;
    
    // Function to check if sidebar is open
    function isSidebarOpen() {
      const sidebar = document.querySelector('.sidebar, #sidebar, .side-menu');
      if (!sidebar) return false;
      
      // Check common sidebar open indicators
      const hasOpenClass = sidebar.classList.contains('active') || 
                          sidebar.classList.contains('open') || 
                          sidebar.classList.contains('show') ||
                          sidebar.classList.contains('sidebar-open');
      
      // Check if sidebar is visible (not hidden)
      const isVisible = window.getComputedStyle(sidebar).display !== 'none' &&
                       window.getComputedStyle(sidebar).visibility !== 'hidden' &&
                       window.getComputedStyle(sidebar).width !== '0px';
      
      // Check if sidebar has width (not collapsed)
      const sidebarWidth = sidebar.offsetWidth || parseInt(window.getComputedStyle(sidebar).width);
      const isWide = sidebarWidth > 50; // More than 50px means it's open
      
      return hasOpenClass || (isVisible && isWide);
    }
    
    // Function to update chart1 legend based on sidebar state
    function updateChart1Legend() {
      const sidebarOpen = isSidebarOpen();
      const chartWidth = chart1Dom.offsetWidth || chart1Dom.clientWidth;
      const isNarrow = chartWidth < 600; // Consider narrow if chart width is less than 600px
      
      // If sidebar is open or chart is narrow, use smaller spacing and font to keep legend in one line
      const useCompactLegend = sidebarOpen || isNarrow;
      
      chart1.setOption({
        legend: {
          data: ['Jumlah Penduduk Miskin (Ribu)', 'Persentase Penduduk Miskin'],
          top: isMobile ? 5 : 10,
          left: 'center',
          orient: 'horizontal',
          itemGap: useCompactLegend ? 8 : (isMobile ? 15 : 20),
          itemWidth: isMobile ? 10 : 12,
          itemHeight: isMobile ? 10 : 12,
          textStyle: {
            fontSize: useCompactLegend ? 9 : (isMobile ? 10 : 12)
          }
        }
      }, false); // notMerge: false to only update legend
    }

    // Chart 1: Jumlah dan Persentase Penduduk Miskin (Bar + Line) - 5 tahun terakhir
    const chart1Dom = document.getElementById('chart1');
    const chart1 = echarts.init(chart1Dom);
    
    const chart1Years = surabayaDataLast5.map(d => d.year.toString());
    const chart1Jumlah = surabayaDataLast5.map(d => d.jumlah_penduduk_miskin !== null ? d.jumlah_penduduk_miskin : null);
    const chart1Persentase = surabayaDataLast5.map(d => d.persentase_penduduk_miskin !== null ? d.persentase_penduduk_miskin : null);

    chart1.setOption({
      tooltip: {
        trigger: 'axis',
        axisPointer: { type: 'cross' },
        formatter: function(params) {
          let result = 'Tahun: ' + params[0].axisValue + '<br/>';
          params.forEach(function(item) {
            if (item.seriesName === 'Jumlah Penduduk Miskin (Ribu)') {
              result += item.marker + item.seriesName + ': ' + (item.value !== null && item.value !== undefined ? item.value.toFixed(2) + ' ribu' : 'Data tidak tersedia') + '<br/>';
            } else {
              result += item.marker + item.seriesName + ': ' + (item.value !== null && item.value !== undefined ? item.value.toFixed(2) + '%' : 'Data tidak tersedia') + '<br/>';
            }
          });
          return result;
        }
      },
      legend: {
        data: ['Jumlah Penduduk Miskin (Ribu)', 'Persentase Penduduk Miskin'],
        top: isMobile ? 5 : 10,
        left: 'center',
        orient: 'horizontal',
        itemGap: isMobile ? 15 : 20,
        itemWidth: isMobile ? 10 : 12,
        itemHeight: isMobile ? 10 : 12,
        textStyle: {
          fontSize: isMobile ? 10 : 12
        }
      },
      grid: {
        left: isMobile ? '15%' : '12%',
        right: isMobile ? '15%' : '12%',
        bottom: '10%',
        top: isMobile ? '18%' : '20%',
        containLabel: false
      },
      xAxis: {
        type: 'category',
        data: chart1Years,
        axisLabel: {
          fontSize: isMobile ? 9 : 11
        }
      },
      yAxis: [
        {
          type: 'value',
          max: function (value) {
            return Math.ceil(value.max + Math.abs(value.max * 0.2));
          },
          name: 'Jumlah (Ribu)',
          position: 'left',
          nameLocation: 'end',
          nameGap: 10,
          nameTextStyle: {
            padding: [0, 0, 0, 0],
            fontSize: isMobile ? 10 : 12
          },
          axisLabel: { 
            formatter: '{value} ribu',
            fontSize: isMobile ? 9 : 11
          }
        },
        {
          type: 'value',
          max: function (value) {
            return Math.ceil(value.max + Math.abs(value.max * 0.2));
          },
          name: 'Persentase (%)',
          position: 'right',
          nameLocation: 'end',
          nameGap: isMobile ? 8 : 10,
          nameTextStyle: {
            padding: [0, 0, 0, 0],
            fontSize: isMobile ? 10 : 12
          },
          axisLabel: { 
            formatter: '{value}%',
            fontSize: isMobile ? 9 : 11
          }
        }
      ],
      series: [
        {
          name: 'Jumlah Penduduk Miskin (Ribu)',
          type: 'bar',
          data: chart1Jumlah,
          itemStyle: { color: '#3b82f6' },
          yAxisIndex: 0
        },
        {
          name: 'Persentase Penduduk Miskin',
          type: 'line',
          data: chart1Persentase,
          itemStyle: { color: '#f59e0b' },
          lineStyle: { width: 3 },
          symbol: 'circle',
          symbolSize: 8,
          yAxisIndex: 1
        }
      ]
    });

    // Chart 2: Garis Kemiskinan (Area Chart)
    const chart2Dom = document.getElementById('chart2');
    const chart2 = echarts.init(chart2Dom);
    
    const chart2Years = surabayaDataLast10.map(d => d.year.toString());
    const chart2Garis = surabayaDataLast10.map(d => d.garis_kemiskinan !== null ? d.garis_kemiskinan : null);

    chart2.setOption({
      tooltip: {
        trigger: 'axis',
        formatter: function(params) {
          let result = 'Tahun: ' + params[0].axisValue + '<br/>';
          if (params[0].seriesName === 'Garis Kemiskinan') {
            result += params[0].marker + params[0].seriesName + ': ' + 
                 (params[0].value !== null && params[0].value !== undefined ? 'Rp ' + params[0].value.toLocaleString('id-ID') : 'Data tidak tersedia');
          }
          return result;
        }
      },
      legend: {
        data: ['Garis Kemiskinan'],
        top: isMobile ? 5 : 10,
        left: 'center',
        orient: 'horizontal',
        itemGap: isMobile ? 15 : 20,
        itemWidth: isMobile ? 10 : 12,
        itemHeight: isMobile ? 10 : 12,
        textStyle: {
          fontSize: isMobile ? 10 : 12
        }
      },
      grid: {
        left: isMobile ? '15%' : '12%',
        right: '4%',
        bottom: '10%',
        top: isMobile ? '18%' : '20%',
        containLabel: false
      },
      xAxis: {
        type: 'category',
        data: chart2Years,
        axisLabel: {
          fontSize: isMobile ? 9 : 11
        }
      },
      yAxis: {
        type: 'value',
        max: function (value) {
          return Math.ceil(value.max + Math.abs(value.max * 0.2));
        },
        name: 'Rupiah',
        position: 'left',
        nameLocation: 'end',
        nameGap: isMobile ? 8 : 10,
        nameTextStyle: {
          padding: [0, 0, 0, 0],
          fontSize: isMobile ? 10 : 12
        },
        axisLabel: {
          formatter: function(value) {
            return 'Rp ' + (value / 1000).toFixed(0) + 'k';
          },
          fontSize: isMobile ? 9 : 11
        }
      },
      series: [{
        name: 'Garis Kemiskinan',
        type: 'line',
        areaStyle: {
          color: {
            type: 'linear',
            x: 0,
            y: 0,
            x2: 0,
            y2: 1,
            colorStops: [
              { offset: 0, color: 'rgba(139, 92, 246, 0.6)' },
              { offset: 1, color: 'rgba(139, 92, 246, 0.1)' }
            ]
          }
        },
        data: chart2Garis,
        itemStyle: { color: '#8b5cf6' },
        lineStyle: { width: 3 },
        symbol: 'circle',
        symbolSize: 8
      }]
    });

    // Chart 3: Indeks Kedalaman (P1) - Line Chart
    const chart3Dom = document.getElementById('chart3');
    const chart3 = echarts.init(chart3Dom);
    
    const chart3Years = surabayaDataLast10.map(d => d.year.toString());
    const chart3P1 = surabayaDataLast10.map(d => d.indeks_kedalaman_kemiskinan_p1 !== null ? d.indeks_kedalaman_kemiskinan_p1 : null);

    chart3.setOption({
      tooltip: {
        trigger: 'axis',
        formatter: function(params) {
          let result = 'Tahun: ' + params[0].axisValue + '<br/>';
          if (params[0].seriesName === 'Indeks Kedalaman (P1)') {
            result += params[0].marker + params[0].seriesName + ': ' + 
                 (params[0].value !== null && params[0].value !== undefined ? params[0].value.toFixed(2) : 'Data tidak tersedia');
          }
          return result;
        }
      },
      legend: {
        data: ['Indeks Kedalaman (P1)'],
        top: isMobile ? 5 : 10,
        left: 'center',
        orient: 'horizontal',
        itemGap: isMobile ? 15 : 20,
        itemWidth: isMobile ? 10 : 12,
        itemHeight: isMobile ? 10 : 12,
        textStyle: {
          fontSize: isMobile ? 10 : 12
        }
      },
      grid: {
        left: isMobile ? '15%' : '12%',
        right: '4%',
        bottom: '10%',
        top: isMobile ? '18%' : '20%',
        containLabel: false
      },
      xAxis: {
        type: 'category',
        data: chart3Years,
        axisLabel: {
          fontSize: isMobile ? 9 : 11
        }
      },
      yAxis: {
        type: 'value',
        max: function (value) {
          return value.max + Math.abs(value.max * 0.2);
        },
        name: 'Indeks Kedalaman (P1)',
        position: 'left',
        nameLocation: 'end',
        nameGap: isMobile ? 8 : 10,
        nameTextStyle: {
          padding: [0, 0, 0, 0],
          fontSize: isMobile ? 10 : 12
        },
        axisLabel: {
          fontSize: isMobile ? 9 : 11
        }
      },
      series: [{
        name: 'Indeks Kedalaman (P1)',
        type: 'line',
        data: chart3P1,
        itemStyle: { color: '#f59e0b' },
        lineStyle: { width: 3 },
        symbol: 'circle',
        symbolSize: 8,
        smooth: true
      }]
    });

    // Chart 4: Indeks Keparahan (P2) - Line Chart
    const chart4Dom = document.getElementById('chart4');
    const chart4 = echarts.init(chart4Dom);
    
    const chart4Years = surabayaDataLast10.map(d => d.year.toString());
    const chart4P2 = surabayaDataLast10.map(d => d.indeks_keparahan_kemiskinan_p2 !== null ? d.indeks_keparahan_kemiskinan_p2 : null);

    chart4.setOption({
      tooltip: {
        trigger: 'axis',
        formatter: function(params) {
          let result = 'Tahun: ' + params[0].axisValue + '<br/>';
          if (params[0].seriesName === 'Indeks Keparahan (P2)') {
            result += params[0].marker + params[0].seriesName + ': ' + 
                 (params[0].value !== null && params[0].value !== undefined ? params[0].value.toFixed(2) : 'Data tidak tersedia');
          }
          return result;
        }
      },
      legend: {
        data: ['Indeks Keparahan (P2)'],
        top: isMobile ? 5 : 10,
        left: 'center',
        orient: 'horizontal',
        itemGap: isMobile ? 15 : 20,
        itemWidth: isMobile ? 10 : 12,
        itemHeight: isMobile ? 10 : 12,
        textStyle: {
          fontSize: isMobile ? 10 : 12
        }
      },
      grid: {
        left: isMobile ? '15%' : '12%',
        right: '4%',
        bottom: '10%',
        top: isMobile ? '18%' : '20%',
        containLabel: false
      },
      xAxis: {
        type: 'category',
        data: chart4Years,
        axisLabel: {
          fontSize: isMobile ? 9 : 11
        }
      },
      yAxis: {
        type: 'value',
        max: function (value) {
          return value.max + Math.abs(value.max * 0.2);
        },
        name: 'Indeks Keparahan (P2)',
        position: 'left',
        nameLocation: 'end',
        nameGap: isMobile ? 8 : 10,
        nameTextStyle: {
          padding: [0, 0, 0, 0],
          fontSize: isMobile ? 10 : 12
        },
        axisLabel: {
          fontSize: isMobile ? 9 : 11
        }
      },
      series: [{
        name: 'Indeks Keparahan (P2)',
        type: 'line',
        data: chart4P2,
        itemStyle: { color: '#ef4444' },
        lineStyle: { width: 3 },
        symbol: 'circle',
        symbolSize: 8,
        smooth: true
      }]
    });

    // Comparison Chart: Surabaya vs Jawa Timur (Dual-axis Line Chart like image 1)
    const comparisonChartDom = document.getElementById('comparisonChart');
    const comparisonChart = echarts.init(comparisonChartDom);

    function updateComparisonChart(indicator) {
      const fieldMap = {
        'jumlah_penduduk_miskin': { 
          name: 'Jumlah Penduduk Miskin', 
          unit: 'ribu', 
          surabayaColor: '#ff69b4', 
          jatimColor: '#3b82f6',
          surabayaLabel: 'Surabaya (Ribu)',
          jatimLabel: 'Jawa Timur (Juta)',
          surabayaDivisor: 1,
          jatimDivisor: 1000
        },
        'persentase_penduduk_miskin': { 
          name: 'Persentase Penduduk Miskin', 
          unit: '%', 
          surabayaColor: '#ff69b4', 
          jatimColor: '#3b82f6',
          surabayaLabel: 'Surabaya (%)',
          jatimLabel: 'Jawa Timur (%)',
          surabayaDivisor: 1,
          jatimDivisor: 1
        },
        'indeks_kedalaman_kemiskinan_p1': { 
          name: 'Indeks Kedalaman (P1)', 
          unit: '', 
          surabayaColor: '#ff69b4', 
          jatimColor: '#3b82f6',
          surabayaLabel: 'Surabaya',
          jatimLabel: 'Jawa Timur',
          surabayaDivisor: 1,
          jatimDivisor: 1
        },
        'indeks_keparahan_kemiskinan_p2': { 
          name: 'Indeks Keparahan (P2)', 
          unit: '', 
          surabayaColor: '#ff69b4', 
          jatimColor: '#3b82f6',
          surabayaLabel: 'Surabaya',
          jatimLabel: 'Jawa Timur',
          surabayaDivisor: 1,
          jatimDivisor: 1
        },
        'garis_kemiskinan': { 
          name: 'Garis Kemiskinan', 
          unit: 'Rp', 
          surabayaColor: '#ff69b4', 
          jatimColor: '#3b82f6',
          surabayaLabel: 'Surabaya (Rp)',
          jatimLabel: 'Jawa Timur (Rp)',
          surabayaDivisor: 1,
          jatimDivisor: 1
        }
      };

      const config = fieldMap[indicator];
      const years = last10Years.map(y => y.toString());
      
      const surabayaValues = last10Years.map(year => {
        const data = surabayaDataLast10.find(d => d.year === year);
        if (data && data[indicator] !== null && data[indicator] !== undefined) {
          // For jumlah_penduduk_miskin, keep in ribu (no conversion)
          // For others, use value as is
          return data[indicator];
        }
        return null;
      });

      const jatimValues = last10Years.map(year => {
        const data = jatimDataLast10.find(d => d.year === year);
        if (data && data[indicator] !== null && data[indicator] !== undefined) {
          // For jumlah_penduduk_miskin, convert to juta (divide by 1000)
          // For others, use value as is
          return indicator === 'jumlah_penduduk_miskin' ? data[indicator] / 1000 : data[indicator];
        }
        return null;
      });

      const formatter = (value, unit, divisor) => {
        if (value === null) return 'Data tidak tersedia';
        if (unit === 'Rp') {
          return 'Rp ' + (value * divisor).toLocaleString('id-ID');
        } else if (unit === 'ribu') {
          return (value * divisor).toFixed(2) + ' ribu';
        } else if (unit === '%') {
          return (value * divisor).toFixed(2) + '%';
        } else {
          return (value * divisor).toFixed(2);
        }
      };

      // Hanya indikator "Jumlah Penduduk Miskin" yang menggunakan 2 y-axis (dual-axis)
      // Indikator lain (Persentase, P1, P2, Garis Kemiskinan) menggunakan 1 y-axis saja
      if (indicator === 'jumlah_penduduk_miskin') {
        comparisonChart.setOption({
          tooltip: {
            trigger: 'axis',
            axisPointer: { type: 'cross' },
            formatter: function(params) {
              let result = 'Tahun: ' + params[0].axisValue + '<br/>';
              params.forEach(function(item) {
                if (item.seriesName === 'Jawa Timur') {
                  result += item.marker + item.seriesName + ': ' + 
                    (item.value !== null && item.value !== undefined ? item.value.toFixed(2) + ' Juta' : 'Data tidak tersedia') + '<br/>';
                } else {
                  result += item.marker + item.seriesName + ': ' + 
                    (item.value !== null && item.value !== undefined ? item.value.toFixed(2) + ' ribu' : 'Data tidak tersedia') + '<br/>';
                }
              });
              return result;
            }
          },
          legend: {
            data: ['Jawa Timur', 'Surabaya'],
            top: isMobile ? 5 : 10,
            left: 'center',
            orient: 'horizontal',
            itemGap: isMobile ? 15 : 20,
            itemWidth: isMobile ? 10 : 12,
            itemHeight: isMobile ? 10 : 12,
            textStyle: {
              fontSize: isMobile ? 10 : 12
            }
          },
          grid: {
            left: isMobile ? '15%' : '12%',
            right: isMobile ? '15%' : '12%',
            bottom: '10%',
            top: isMobile ? '18%' : '15%',
            containLabel: false
          },
          xAxis: {
            type: 'category',
            data: years,
            axisLabel: {
              fontSize: isMobile ? 9 : 11
            }
          },
          yAxis: [
            {
              type: 'value',
          max: function (value) {
            return Math.ceil(value.max + Math.abs(value.max * 0.2));
          },
              name: 'Jawa Timur (Juta)',
              position: 'left',
              nameLocation: 'start',
              nameGap: isMobile ? 35 : 40,
              nameTextStyle: {
                padding: [0, 0, 0, 0],
                fontSize: isMobile ? 10 : 12
              },
              axisLabel: {
                formatter: '{value}',
                fontSize: isMobile ? 9 : 11
              }
            },
            {
              type: 'value',
          max: function (value) {
            return Math.ceil(value.max + Math.abs(value.max * 0.2));
          },
              name: 'Surabaya (Ribu)',
              position: 'right',
              nameLocation: 'start',
              nameGap: isMobile ? 35 : 40,
              nameTextStyle: {
                padding: [0, 0, 0, 0],
                fontSize: isMobile ? 10 : 12
              },
              axisLabel: {
                formatter: '{value}',
                fontSize: isMobile ? 9 : 11
              }
            }
          ],
          series: [
            {
              name: 'Jawa Timur',
              type: 'line',
              data: jatimValues,
              itemStyle: { color: config.jatimColor },
              lineStyle: { width: 3 },
              symbol: 'circle',
              symbolSize: 8,
              yAxisIndex: 0
            },
            {
              name: 'Surabaya',
              type: 'line',
              data: surabayaValues,
              itemStyle: { color: config.surabayaColor },
              lineStyle: { width: 3 },
              symbol: 'circle',
              symbolSize: 8,
              yAxisIndex: 1
            }
          ]
        }, true); // notMerge: true untuk menggantikan semua option sebelumnya
      } else {
        // Untuk indikator selain "Jumlah Penduduk Miskin", gunakan 1 y-axis saja (single axis)
        // Clear chart dulu untuk memastikan tidak ada y-axis kedua yang tersisa
        comparisonChart.clear();
        comparisonChart.setOption({
          tooltip: {
            trigger: 'axis',
            axisPointer: { type: 'cross' },
            formatter: function(params) {
              let result = 'Tahun: ' + params[0].axisValue + '<br/>';
              params.forEach(function(item) {
                if (item.value === null || item.value === undefined) {
                  result += item.marker + item.seriesName + ': Data tidak tersedia<br/>';
                } else {
                  if (config.unit === 'Rp') {
                    result += item.marker + item.seriesName + ': Rp ' + item.value.toLocaleString('id-ID') + '<br/>';
                  } else if (config.unit === '%') {
                    result += item.marker + item.seriesName + ': ' + item.value.toFixed(2) + '%<br/>';
                  } else if (config.unit === 'ribu') {
                    result += item.marker + item.seriesName + ': ' + item.value.toFixed(2) + ' ribu<br/>';
                  } else {
                    result += item.marker + item.seriesName + ': ' + item.value.toFixed(2) + '<br/>';
                  }
                }
              });
              return result;
            }
          },
          legend: {
            data: ['Jawa Timur', 'Surabaya'],
            top: isMobile ? 5 : 10,
            left: 'center',
            orient: 'horizontal',
            itemGap: isMobile ? 15 : 20,
            itemWidth: isMobile ? 10 : 12,
            itemHeight: isMobile ? 10 : 12,
            textStyle: {
              fontSize: isMobile ? 10 : 12
            }
          },
          grid: {
            left: isMobile ? '15%' : '12%',
            right: '4%',
            bottom: '10%',
            top: isMobile ? '18%' : '15%',
            containLabel: false
          },
          xAxis: {
            type: 'category',
            data: years,
            axisLabel: {
              fontSize: isMobile ? 9 : 11
            }
          },
          // Hanya 1 y-axis (object, bukan array) - hanya y-axis kiri
          yAxis: {
            type: 'value',
          max: function (value) {
            return Math.ceil(value.max + Math.abs(value.max * 0.2));
          },
            name: config.name + (config.unit ? ' (' + config.unit + ')' : ''),
            position: 'left',
            nameLocation: 'start',
            nameGap: isMobile ? 35 : 40,
            nameTextStyle: {
              padding: [0, 0, 0, 0],
              fontSize: isMobile ? 10 : 12
            },
            axisLabel: {
              formatter: function(value) {
                if (config.unit === 'Rp') {
                  return 'Rp ' + (value / 1000).toFixed(0) + 'k';
                } else if (config.unit === 'ribu') {
                  return value.toFixed(0) + ' ribu';
                } else if (config.unit === '%') {
                  return value.toFixed(1) + '%';
                } else {
                  return value.toFixed(2);
                }
              },
              fontSize: isMobile ? 9 : 11
            }
          },
          series: [
            {
              name: 'Jawa Timur',
              type: 'line',
              data: jatimValues,
              itemStyle: { color: config.jatimColor },
              lineStyle: { width: 3 },
              symbol: 'circle',
              symbolSize: 8
              // Tidak menggunakan yAxisIndex - menggunakan y-axis default (kiri)
            },
            {
              name: 'Surabaya',
              type: 'line',
              data: surabayaValues,
              itemStyle: { color: config.surabayaColor },
              lineStyle: { width: 3 },
              symbol: 'circle',
              symbolSize: 8
              // Tidak menggunakan yAxisIndex - menggunakan y-axis default (kiri)
            }
          ]
        }, true); // notMerge: true untuk menggantikan semua option sebelumnya dan memastikan hanya 1 y-axis
      }
    }

    // Initialize comparison chart
    updateComparisonChart('jumlah_penduduk_miskin');

    // Handle indicator selector change
    document.getElementById('indicatorSelector').addEventListener('change', function() {
      updateComparisonChart(this.value);
    });

    // Function to resize all charts
    function resizeAllCharts() {
      const charts = [chart1, chart2, chart3, chart4, comparisonChart];
      charts.forEach(chart => {
        if (chart) {
          setTimeout(() => {
            chart.resize();
          }, 100);
        }
      });
    }


    // Function to handle sidebar changes and update chart1 legend
    function handleSidebarChange() {
      setTimeout(() => {
        resizeAllCharts();
        updateChart1Legend();
      }, 300);
    }

    // Handle sidebar toggle (common sidebar toggle patterns)
    const sidebarToggle = document.querySelector('#sidebarToggle, #check, [data-toggle="sidebar"], .sidebar-toggle');
    if (sidebarToggle) {
      sidebarToggle.addEventListener('change', handleSidebarChange);
      sidebarToggle.addEventListener('click', handleSidebarChange);
    }

    // Observe sidebar changes using MutationObserver
    const sidebar = document.querySelector('.sidebar, #sidebar, .side-menu');
    if (sidebar) {
      const observer = new MutationObserver(function(mutations) {
        handleSidebarChange();
      });
      observer.observe(sidebar, {
        attributes: true,
        attributeFilter: ['class', 'style']
      });
    }

    // Also listen for transitionend events on main content area
    const mainContent = document.querySelector('.main-panel, .content-wrapper, .page-body-wrapper');
    if (mainContent) {
      mainContent.addEventListener('transitionend', handleSidebarChange);
    }

    // Handle window resize to update legend
    window.addEventListener('resize', function() {
      resizeAllCharts();
      updateChart1Legend();
    });

    // Initial update of chart1 legend
    setTimeout(() => {
      updateChart1Legend();
      resizeAllCharts();
    }, 500);

    // Export functions for Chart 1
    function exportChart1ToExcel() {
      const exportData = [];
      exportData.push(['Tahun', 'Jumlah Penduduk Miskin (Ribu)', 'Persentase Penduduk Miskin (%)']);
      chart1Years.forEach((year, index) => {
        const jumlah = chart1Jumlah[index] !== null ? chart1Jumlah[index].toFixed(2) : 'Data tidak tersedia';
        const persentase = chart1Persentase[index] !== null ? chart1Persentase[index].toFixed(2) : 'Data tidak tersedia';
        exportData.push([year, jumlah, persentase]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 10 }, { wch: 30 }, { wch: 30 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Chart 1');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Tren_Jumlah_dan_Persentase_Penduduk_Miskin_${today}.xlsx`);
    }

    function exportChart1ToPNG() {
      const url = chart1.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
      const link = document.createElement('a');
      link.download = `Tren_Jumlah_dan_Persentase_Penduduk_Miskin_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }



    document.getElementById('downloadChart1Excel').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportChart1ToExcel, 'Tren Jumlah dan Persentase Penduduk Miskin');
    });
    document.getElementById('downloadChart1PNG').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportChart1ToPNG, 'Tren Jumlah dan Persentase Penduduk Miskin');
    });

    // Export functions for Chart 2
    function exportChart2ToExcel() {
      const chart2Years = surabayaDataLast10.map(d => d.year.toString());
      const chart2Garis = surabayaDataLast10.map(d => d.garis_kemiskinan !== null ? d.garis_kemiskinan : null);
      const exportData = [];
      exportData.push(['Tahun', 'Garis Kemiskinan (Rp)']);
      chart2Years.forEach((year, index) => {
        const garis = chart2Garis[index] !== null ? chart2Garis[index].toFixed(0) : 'Data tidak tersedia';
        exportData.push([year, garis]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 10 }, { wch: 25 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Chart 2');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Tren_Garis_Kemiskinan_${today}.xlsx`);
    }

    function exportChart2ToPNG() {
      const url = chart2.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
      const link = document.createElement('a');
      link.download = `Tren_Garis_Kemiskinan_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }

    document.getElementById('downloadChart2Excel').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportChart2ToExcel, 'Tren Garis Kemiskinan');
    });
    document.getElementById('downloadChart2PNG').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportChart2ToPNG, 'Tren Garis Kemiskinan');
    });

    // Export functions for Chart 3
    function exportChart3ToExcel() {
      const chart3Years = surabayaDataLast10.map(d => d.year.toString());
      const chart3P1 = surabayaDataLast10.map(d => d.indeks_kedalaman_kemiskinan_p1 !== null ? d.indeks_kedalaman_kemiskinan_p1 : null);
      const exportData = [];
      exportData.push(['Tahun', 'Indeks Kedalaman (P1)']);
      chart3Years.forEach((year, index) => {
        const p1 = chart3P1[index] !== null ? chart3P1[index].toFixed(2) : 'Data tidak tersedia';
        exportData.push([year, p1]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 10 }, { wch: 25 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Chart 3');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Tren_Indeks_Kedalaman_Kemiskinan_P1_${today}.xlsx`);
    }

    function exportChart3ToPNG() {
      const url = chart3.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
      const link = document.createElement('a');
      link.download = `Tren_Indeks_Kedalaman_Kemiskinan_P1_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }

    document.getElementById('downloadChart3Excel').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportChart3ToExcel, 'Tren Indeks Kedalaman Kemiskinan (P1)');
    });
    document.getElementById('downloadChart3PNG').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportChart3ToPNG, 'Tren Indeks Kedalaman Kemiskinan (P1)');
    });

    // Export functions for Chart 4
    function exportChart4ToExcel() {
      const chart4Years = surabayaDataLast10.map(d => d.year.toString());
      const chart4P2 = surabayaDataLast10.map(d => d.indeks_keparahan_kemiskinan_p2 !== null ? d.indeks_keparahan_kemiskinan_p2 : null);
      const exportData = [];
      exportData.push(['Tahun', 'Indeks Keparahan (P2)']);
      chart4Years.forEach((year, index) => {
        const p2 = chart4P2[index] !== null ? chart4P2[index].toFixed(2) : 'Data tidak tersedia';
        exportData.push([year, p2]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 10 }, { wch: 25 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Chart 4');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Tren_Indeks_Keparahan_Kemiskinan_P2_${today}.xlsx`);
    }

    function exportChart4ToPNG() {
      const url = chart4.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
      const link = document.createElement('a');
      link.download = `Tren_Indeks_Keparahan_Kemiskinan_P2_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }

    document.getElementById('downloadChart4Excel').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportChart4ToExcel, 'Tren Indeks Keparahan Kemiskinan (P2)');
    });
    document.getElementById('downloadChart4PNG').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportChart4ToPNG, 'Tren Indeks Keparahan Kemiskinan (P2)');
    });

    // Export functions for Comparison Chart
    function exportComparisonToExcel() {
      const selectedIndicator = document.getElementById('indicatorSelector')?.value;
      
      // Validate selected indicator to prevent injection
      const indicatorConfigs = {
        'jumlah_penduduk_miskin': { name: 'Jumlah Penduduk Miskin', unit: 'ribu' },
        'persentase_penduduk_miskin': { name: 'Persentase Penduduk Miskin', unit: '%' },
        'indeks_kedalaman_kemiskinan_p1': { name: 'Indeks Kedalaman (P1)', unit: '' },
        'indeks_keparahan_kemiskinan_p2': { name: 'Indeks Keparahan (P2)', unit: '' },
        'garis_kemiskinan': { name: 'Garis Kemiskinan', unit: 'Rp' }
      };
      
      // Validate that selected indicator exists in config
      if (!selectedIndicator || !indicatorConfigs.hasOwnProperty(selectedIndicator)) {
        console.error('Invalid indicator selected');
        return;
      }
      
      const config = indicatorConfigs[selectedIndicator];
      const years = last10Years.map(y => window.sanitizeYear(y)).filter(y => y !== null).map(y => y.toString());
      const surabayaValues = years.map(year => {
        const yearNum = window.sanitizeYear(year);
        if (yearNum === null) return null;
        const data = surabayaDataLast10.find(d => d.year === yearNum);
        return data && data[selectedIndicator] !== undefined ? window.sanitizeNumber(data[selectedIndicator]) : null;
      });
      const jatimValues = years.map(year => {
        const yearNum = window.sanitizeYear(year);
        if (yearNum === null) return null;
        const data = jatimDataLast10.find(d => d.year === yearNum);
        return data && data[selectedIndicator] !== undefined ? window.sanitizeNumber(data[selectedIndicator]) : null;
      });
      const exportData = [];
      exportData.push(['Tahun', `Surabaya ${config.unit ? '(' + config.unit + ')' : ''}`.trim(), `Jawa Timur ${config.unit ? '(' + config.unit + ')' : ''}`.trim()]);
      years.forEach((year, index) => {
        const surabayaVal = surabayaValues[index] !== null ? (config.unit === 'Rp' ? surabayaValues[index].toFixed(0) : surabayaValues[index].toFixed(2)) : 'Data tidak tersedia';
        const jatimVal = jatimValues[index] !== null ? (config.unit === 'Rp' ? jatimValues[index].toFixed(0) : jatimValues[index].toFixed(2)) : 'Data tidak tersedia';
        exportData.push([year, surabayaVal, jatimVal]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 10 }, { wch: 25 }, { wch: 25 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Perbandingan');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Kemiskinan_Perbandingan_${today}.xlsx`);
    }

    function exportComparisonToPNG() {
      const url = comparisonChart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
      const link = document.createElement('a');
      link.download = `Kemiskinan_Perbandingan_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }

    document.getElementById('downloadComparisonExcel').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportComparisonToExcel, 'data perbandingan kemiskinan');
    });
    document.getElementById('downloadComparisonPNG').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportComparisonToPNG, 'grafik perbandingan kemiskinan');
    });
  });