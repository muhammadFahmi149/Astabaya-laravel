// Function to escape HTML to prevent XSS attacks
  function escapeHtml(text) {
    if (text === null || text === undefined) return '';
    const map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, m => map[m]);
  }

  // Function to validate and sanitize numeric value
  function sanitizeNumber(value) {
    if (value === null || value === undefined) return null;
    const num = Number(value);
    if (isNaN(num) || !isFinite(num)) return null;
    return num;
  }

  // Function to validate year value
  function sanitizeYear(value) {
    if (value === null || value === undefined) return null;
    const year = parseInt(value, 10);
    if (isNaN(year) || year < 1900 || year > 2100) return null;
    return year;
  }

  document.addEventListener("DOMContentLoaded", async () => {
    // API Base URL
    const API_BASE = window.APP_CONFIG.apiUrl;
    
    // Check if user is authenticated (set by server-side)
    const isAuthenticated = window.APP_CONFIG.isAuthenticated;
    
    // Initialize data variables
    let surabayaData = [];
    let jatimData = [];
    let surabayaLatest = null;
    let surabayaPrevious = null;
    let jatimLatest = null;
    let jatimPrevious = null;
    let surabayaChange = null;
    let jatimChange = null;

    // Cache Key
    const CACHE_KEY = 'astabaya_gini_ratio_summary';
    let result = null;

    // Load summary data from API or Cache
    try {
      const cachedData = sessionStorage.getItem(CACHE_KEY);
      if (cachedData) {
        result = JSON.parse(cachedData);
        console.log('Loaded gini ratio data from sessionStorage cache');
      } else {
        const response = await fetch(`${API_BASE}/gini-ratio-summary`);
        
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
      console.log('Gini Ratio API Response:', result);
      
      // Validate response structure
      if (result.success && result.data) {
        const data = result.data;
        console.log('Gini Ratio Data:', {
          surabaya_data_count: Array.isArray(data.surabaya_data) ? data.surabaya_data.length : 0,
          jatim_data_count: Array.isArray(data.jatim_data) ? data.jatim_data.length : 0,
          surabaya_latest: data.surabaya_latest,
          jatim_latest: data.jatim_latest,
          surabaya_previous: data.surabaya_previous,
          jatim_previous: data.jatim_previous,
        });
        
        // Validate and sanitize data arrays
        console.log('Processing surabaya_data:', data.surabaya_data);
        console.log('Processing jatim_data:', data.jatim_data);
        
        surabayaData = Array.isArray(data.surabaya_data) ? data.surabaya_data.map(item => ({
          year: sanitizeYear(item.year),
          value: sanitizeNumber(item.gini_ratio_value)
        })).filter(item => item.year !== null) : [];
        
        jatimData = Array.isArray(data.jatim_data) ? data.jatim_data.map(item => ({
          year: sanitizeYear(item.year),
          value: sanitizeNumber(item.gini_ratio_value)
        })).filter(item => item.year !== null) : [];
        
        console.log('Processed surabayaData:', surabayaData);
        console.log('Processed jatimData:', jatimData);
        
        // Validate and sanitize latest/previous data
        if (data.surabaya_latest) {
          surabayaLatest = {
            year: sanitizeYear(data.surabaya_latest.year),
            gini_ratio_value: sanitizeNumber(data.surabaya_latest.gini_ratio_value)
          };
        }
        
        if (data.surabaya_previous) {
          surabayaPrevious = {
            year: sanitizeYear(data.surabaya_previous.year),
            gini_ratio_value: sanitizeNumber(data.surabaya_previous.gini_ratio_value)
          };
        }
        
        if (data.jatim_latest) {
          jatimLatest = {
            year: sanitizeYear(data.jatim_latest.year),
            gini_ratio_value: sanitizeNumber(data.jatim_latest.gini_ratio_value)
          };
        }
        
        if (data.jatim_previous) {
          jatimPrevious = {
            year: sanitizeYear(data.jatim_previous.year),
            gini_ratio_value: sanitizeNumber(data.jatim_previous.gini_ratio_value)
          };
        }
        
        // Validate and sanitize changes
        surabayaChange = sanitizeNumber(data.surabaya_change);
        jatimChange = sanitizeNumber(data.jatim_change);
        
        console.log('Final processed data:', {
          surabayaData: surabayaData,
          jatimData: jatimData,
          surabayaLatest: surabayaLatest,
          jatimLatest: jatimLatest,
          surabayaChange: surabayaChange,
          jatimChange: jatimChange
        });
      } else {
        console.error('Failed to load gini ratio summary data:', result.message || 'Unknown error');
        console.error('Full result:', result);
      }
    } catch (error) {
      console.error('Error loading gini ratio summary data:', error);
      // Set empty data on error to prevent undefined errors
      surabayaData = [];
      jatimData = [];
    }

    // Function to update summary cards UI
    function updateSummaryCards() {
      console.log('Updating summary cards:', {
        surabayaLatest: surabayaLatest,
        jatimLatest: jatimLatest
      });
      // Update Surabaya card
      if (surabayaLatest && surabayaLatest.gini_ratio_value !== null && surabayaLatest.year !== null) {
        const value = sanitizeNumber(surabayaLatest.gini_ratio_value);
        if (value !== null) {
          document.getElementById('surabaya-value').textContent = value.toFixed(3);
          document.getElementById('surabaya-year').textContent = `Tahun ${escapeHtml(surabayaLatest.year)}`;
          
          const changeEl = document.getElementById('surabaya-change');
          const changeValue = sanitizeNumber(surabayaChange);
          if (changeValue !== null) {
            let changeHtml = '';
            if (changeValue > 0) {
              changeHtml = `<span style="font-size: 16px; font-weight: 600; margin-right: 8px;"><i class="fas fa-arrow-up" style="color: #f5576c;"></i></span>
                <span style="font-size: 14px; color: rgba(255, 255, 255, 0.9);">+${escapeHtml(changeValue.toFixed(3))}</span>`;
            } else if (changeValue < 0) {
              changeHtml = `<span style="font-size: 16px; font-weight: 600; margin-right: 8px;"><i class="fas fa-arrow-down" style="color: #34d399;"></i></span>
                <span style="font-size: 14px; color: rgba(255, 255, 255, 0.9);">${escapeHtml(changeValue.toFixed(3))}</span>`;
            } else {
              changeHtml = `<span style="font-size: 16px; font-weight: 600; margin-right: 8px;"><i class="fas fa-minus" style="color: rgba(255, 255, 255, 0.8);"></i></span>
                <span style="font-size: 14px; color: rgba(255, 255, 255, 0.9);">Tidak ada perubahan</span>`;
            }
            if (surabayaPrevious && surabayaPrevious.year !== null) {
              changeHtml += `<span style="font-size: 14px; color: rgba(255, 255, 255, 0.9);"> dari ${escapeHtml(surabayaPrevious.year)}</span>`;
            }
            changeEl.innerHTML = changeHtml;
          } else {
            changeEl.innerHTML = '';
          }
        }
      } else {
        document.getElementById('surabaya-value').textContent = '-';
        document.getElementById('surabaya-year').textContent = 'Data tidak tersedia';
        document.getElementById('surabaya-change').innerHTML = '';
      }

      // Update Jawa Timur card
      if (jatimLatest && jatimLatest.gini_ratio_value !== null && jatimLatest.year !== null) {
        const value = sanitizeNumber(jatimLatest.gini_ratio_value);
        if (value !== null) {
          document.getElementById('jatim-value').textContent = value.toFixed(3);
          document.getElementById('jatim-year').textContent = `Tahun ${escapeHtml(jatimLatest.year)}`;
          
          const changeEl = document.getElementById('jatim-change');
          const changeValue = sanitizeNumber(jatimChange);
          if (changeValue !== null) {
            let changeHtml = '';
            if (changeValue > 0) {
              changeHtml = `<span style="font-size: 16px; font-weight: 600; margin-right: 8px;"><i class="fas fa-arrow-up" style="color: #f5576c;"></i></span>
                <span style="font-size: 14px; color: rgba(255, 255, 255, 0.9);">+${escapeHtml(changeValue.toFixed(3))}</span>`;
            } else if (changeValue < 0) {
              changeHtml = `<span style="font-size: 16px; font-weight: 600; margin-right: 8px;"><i class="fas fa-arrow-down" style="color: #34d399;"></i></span>
                <span style="font-size: 14px; color: rgba(255, 255, 255, 0.9);">${escapeHtml(changeValue.toFixed(3))}</span>`;
            } else {
              changeHtml = `<span style="font-size: 16px; font-weight: 600; margin-right: 8px;"><i class="fas fa-minus" style="color: rgba(255, 255, 255, 0.8);"></i></span>
                <span style="font-size: 14px; color: rgba(255, 255, 255, 0.9);">Tidak ada perubahan</span>`;
            }
            if (jatimPrevious && jatimPrevious.year !== null) {
              changeHtml += `<span style="font-size: 14px; color: rgba(255, 255, 255, 0.9);"> dari ${escapeHtml(jatimPrevious.year)}</span>`;
            }
            changeEl.innerHTML = changeHtml;
          } else {
            changeEl.innerHTML = '';
          }
        }
      } else {
        document.getElementById('jatim-value').textContent = '-';
        document.getElementById('jatim-year').textContent = 'Data tidak tersedia';
        document.getElementById('jatim-change').innerHTML = '';
      }
    }

    // Update UI
    updateSummaryCards();

    // Sort data by year
    surabayaData.sort((a, b) => a.year - b.year);
    jatimData.sort((a, b) => a.year - b.year);

    // Get all unique years
    const allYears = [...new Set([...surabayaData.map(d => d.year), ...jatimData.map(d => d.year)])].sort();

    // Calculate min and max values from all data for dynamic scaling
    const allValues = [
      ...surabayaData.map(d => d.value).filter(v => v !== null),
      ...jatimData.map(d => d.value).filter(v => v !== null)
    ];
    
    const minValue = allValues.length > 0 ? Math.min(...allValues) : 0;
    const maxValue = allValues.length > 0 ? Math.max(...allValues) : 0.5;
    
    // Add padding: 10% below min and 15% above max
    const yMin = Math.max(0, minValue - (minValue * 0.1));
    const yMax = maxValue + (maxValue * 0.15);
    
    // Round to multiples of 0.05
    // Round min down to nearest 0.05
    const roundedYMin = Math.floor(yMin / 0.05) * 0.05;
    // Round max up to nearest 0.05
    const roundedYMax = Math.ceil(yMax / 0.05) * 0.05;

    // Check if mobile
    let isMobile = window.innerWidth <= 767.98;

    // Initialize charts after data is loaded
    console.log('Initializing charts with data:', {
      surabayaDataLength: surabayaData.length,
      jatimDataLength: jatimData.length,
      allYears: allYears,
      allYearsLength: allYears.length
    });
    
    // Wait a bit to ensure DOM is ready and ECharts is loaded
    if (typeof echarts === 'undefined') {
      console.error('ECharts library not loaded!');
    } else {
      setTimeout(() => {
        createComparisonLineChart();
        createComparisonBarChart();
      }, 100);
    }

    // Create comparison line chart
    function createComparisonLineChart() {
      const chartDom = document.getElementById('comparisonLineChart');
      
      // Dispose existing chart if it exists
      if (window.comparisonLineChartInstance) {
        window.comparisonLineChartInstance.dispose();
      }

      // Prepare data arrays aligned by year
      const labels = allYears.map(y => y.toString());
      const surabayaValues = allYears.map(year => {
        const data = surabayaData.find(d => d.year === year);
        return data && data.value !== null ? data.value : null;
      });
      const jatimValues = allYears.map(year => {
        const data = jatimData.find(d => d.year === year);
        return data && data.value !== null ? data.value : null;
      });

      window.comparisonLineChartInstance = echarts.init(chartDom);
      const option = {
        tooltip: {
          trigger: 'axis',
          formatter: function(params) {
            let result = 'Tahun: ' + params[0].axisValue + '<br/>';
            params.forEach(function(item) {
              if (item.value === null || item.value === undefined) {
                result += item.marker + item.seriesName + ': Data tidak tersedia<br/>';
              } else {
                result += item.marker + item.seriesName + ': ' + item.value.toFixed(3) + '<br/>';
              }
            });
            return result;
          }
        },
        legend: {
          data: ['Kota Surabaya', 'Jawa Timur'],
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
          left: isMobile ? '18%' : '10%',
          right: isMobile ? '10%' : '5%',
          bottom: isMobile ? '15%' : '12%',
          top: isMobile ? '18%' : '20%',
          containLabel: false
        },
        xAxis: {
          type: 'category',
          boundaryGap: false,
          data: labels,
          name: 'Tahun',
          nameLocation: 'middle',
          nameGap: isMobile ? 20 : 30,
          nameTextStyle: {
            fontSize: isMobile ? 10 : 12
          },
          axisLabel: {
            fontSize: isMobile ? 9 : 11,
            margin: isMobile ? 8 : 10
          }
        },
        yAxis: {
          type: 'value',
          min: roundedYMin,
          max: roundedYMax,
          interval: 0.05,
          axisLabel: {
            formatter: function(value) {
              return value.toFixed(3);
            },
            fontSize: isMobile ? 9 : 11,
            margin: isMobile ? 8 : 10
          },
          name: 'Gini Ratio',
          nameLocation: 'middle',
          nameGap: isMobile ? 35 : 50,
          nameTextStyle: {
            fontSize: isMobile ? 10 : 12
          }
        },
        series: [
          {
            name: 'Kota Surabaya',
            type: 'line',
            smooth: 0.4,
            data: surabayaValues,
            areaStyle: {
              color: {
                type: 'linear',
                x: 0,
                y: 0,
                x2: 0,
                y2: 1,
                colorStops: [
                  { offset: 0, color: 'rgba(59, 130, 246, 0.3)' },
                  { offset: 1, color: 'rgba(37, 99, 235, 0.1)' }
                ]
              }
            },
            lineStyle: {
              color: '#3b82f6',
              width: 2
            },
            itemStyle: {
              color: '#3b82f6',
              borderColor: '#fff',
              borderWidth: 2
            },
            symbol: 'circle',
            symbolSize: 8
          },
          {
            name: 'Jawa Timur',
            type: 'line',
            smooth: 0.4,
            data: jatimValues,
            areaStyle: {
              color: {
                type: 'linear',
                x: 0,
                y: 0,
                x2: 0,
                y2: 1,
                colorStops: [
                  { offset: 0, color: 'rgba(239, 68, 68, 0.3)' },
                  { offset: 1, color: 'rgba(220, 38, 38, 0.1)' }
                ]
              }
            },
            lineStyle: {
              color: '#ef4444',
              width: 2
            },
            itemStyle: {
              color: '#ef4444',
              borderColor: '#fff',
              borderWidth: 2
            },
            symbol: 'circle',
            symbolSize: 8
          }
        ]
      };
      window.comparisonLineChartInstance.setOption(option);
    }

    // Create comparison line chart for recent years
    function createComparisonBarChart() {
      const chartDom = document.getElementById('comparisonBarChart');
      
      // Dispose existing chart if it exists
      if (window.comparisonBarChartInstance) {
        window.comparisonBarChartInstance.dispose();
      }

      // Get last 5 years or all available years
      const recentYears = allYears.slice(-5);
      
      const labels = recentYears.map(y => y.toString());
      const surabayaValues = recentYears.map(year => {
        const data = surabayaData.find(d => d.year === year);
        return data && data.value !== null ? data.value : null;
      });
      const jatimValues = recentYears.map(year => {
        const data = jatimData.find(d => d.year === year);
        return data && data.value !== null ? data.value : null;
      });

      window.comparisonBarChartInstance = echarts.init(chartDom);
      const option = {
        tooltip: {
          trigger: 'axis',
          formatter: function(params) {
            let result = 'Tahun: ' + params[0].axisValue + '<br/>';
            params.forEach(function(item) {
              if (item.value === null || item.value === undefined) {
                result += item.marker + item.seriesName + ': Data tidak tersedia<br/>';
              } else {
                result += item.marker + item.seriesName + ': ' + item.value.toFixed(3) + '<br/>';
              }
            });
            return result;
          }
        },
        legend: {
          data: ['Kota Surabaya', 'Jawa Timur'],
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
          left: isMobile ? '18%' : '10%',
          right: isMobile ? '10%' : '5%',
          bottom: isMobile ? '15%' : '12%',
          top: isMobile ? '18%' : '20%',
          containLabel: false
        },
        xAxis: {
          type: 'category',
          boundaryGap: true,
          data: labels,
          name: 'Tahun',
          nameLocation: 'middle',
          nameGap: isMobile ? 20 : 30,
          nameTextStyle: {
            fontSize: isMobile ? 10 : 12
          },
          axisLabel: {
            fontSize: isMobile ? 9 : 11,
            margin: isMobile ? 8 : 10
          }
        },
        yAxis: {
          type: 'value',
          axisLabel: {
            formatter: function(value) {
              return value.toFixed(3);
            },
            fontSize: isMobile ? 9 : 11,
            margin: isMobile ? 8 : 10
          },
          name: 'Gini Ratio',
          nameLocation: 'middle',
          nameGap: isMobile ? 35 : 50,
          nameTextStyle: {
            fontSize: isMobile ? 10 : 12
          }
        },
        series: [
          {
            name: 'Kota Surabaya',
            type: 'bar',
            data: surabayaValues,
            
            
            
            },
          {
            name: 'Jawa Timur',
            type: 'bar',
            data: jatimValues,
            
            
            
            }
        ]
      };
      window.comparisonBarChartInstance.setOption(option);
    }

    // Function to resize all charts
    function resizeAllCharts() {
      // Update isMobile check
      const currentIsMobile = window.innerWidth <= 767.98;
      
      if (window.comparisonLineChartInstance) {
        setTimeout(() => {
          window.comparisonLineChartInstance.resize();
          // Update chart option if mobile state changed
          if (currentIsMobile !== isMobile) {
            const option = window.comparisonLineChartInstance.getOption();
            option.legend.top = currentIsMobile ? 5 : 10;
            option.legend.textStyle.fontSize = currentIsMobile ? 10 : 12;
            option.grid.left = currentIsMobile ? '18%' : '10%';
            option.grid.right = currentIsMobile ? '10%' : '5%';
            option.grid.top = currentIsMobile ? '18%' : '20%';
            window.comparisonLineChartInstance.setOption(option);
          }
        }, 100);
      }
      if (window.comparisonBarChartInstance) {
        setTimeout(() => {
          window.comparisonBarChartInstance.resize();
          // Update chart option if mobile state changed
          if (currentIsMobile !== isMobile) {
            const option = window.comparisonBarChartInstance.getOption();
            option.legend.top = currentIsMobile ? 5 : 10;
            option.legend.textStyle.fontSize = currentIsMobile ? 10 : 12;
            option.grid.left = currentIsMobile ? '18%' : '10%';
            option.grid.right = currentIsMobile ? '10%' : '5%';
            option.grid.top = currentIsMobile ? '18%' : '20%';
            window.comparisonBarChartInstance.setOption(option);
          }
        }, 100);
      }
    }

    // Handle window resize
    window.addEventListener('resize', resizeAllCharts);

    // Handle sidebar toggle (common sidebar toggle patterns)
    const sidebarToggle = document.querySelector('#sidebarToggle, #check, [data-toggle="sidebar"], .sidebar-toggle');
    if (sidebarToggle) {
      sidebarToggle.addEventListener('change', function() {
        setTimeout(resizeAllCharts, 300);
      });
      sidebarToggle.addEventListener('click', function() {
        setTimeout(resizeAllCharts, 300);
      });
    }

    // Observe sidebar changes using MutationObserver
    const sidebar = document.querySelector('.sidebar, #sidebar, .side-menu');
    if (sidebar) {
      const observer = new MutationObserver(function(mutations) {
        resizeAllCharts();
      });
      observer.observe(sidebar, {
        attributes: true,
        attributeFilter: ['class', 'style']
      });
    }

    // Also listen for transitionend events on main content area
    const mainContent = document.querySelector('.main-panel, .content-wrapper, .page-body-wrapper');
    if (mainContent) {
      mainContent.addEventListener('transitionend', resizeAllCharts);
    }

    // Resize charts after layout is complete
    setTimeout(() => {
      resizeAllCharts();
    }, 500);

    // Export functions for Line Chart
    function exportLineChartToExcel() {
      const labels = allYears.map(y => y.toString());
      const surabayaValues = allYears.map(year => {
        const data = surabayaData.find(d => d.year === year);
        return data && data.value !== null ? data.value : null;
      });
      const jatimValues = allYears.map(year => {
        const data = jatimData.find(d => d.year === year);
        return data && data.value !== null ? data.value : null;
      });
      const exportData = [];
      exportData.push(['Tahun', 'Kota Surabaya', 'Jawa Timur']);
      labels.forEach((year, index) => {
        const surabayaVal = surabayaValues[index] !== null ? surabayaValues[index].toFixed(3) : 'Data tidak tersedia';
        const jatimVal = jatimValues[index] !== null ? jatimValues[index].toFixed(3) : 'Data tidak tersedia';
        exportData.push([year, surabayaVal, jatimVal]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 10 }, { wch: 20 }, { wch: 20 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Line Chart');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Gini_Ratio_LineChart_${today}.xlsx`);
    }

    function exportLineChartToPNG() {
      const url = window.comparisonLineChartInstance.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
      const link = document.createElement('a');
      link.download = `Gini_Ratio_LineChart_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }

    // Helper function to check authentication before download


    document.getElementById('downloadLineChartExcel').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportLineChartToExcel, 'data line chart gini ratio');
    });
    document.getElementById('downloadLineChartPNG').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportLineChartToPNG, 'grafik line chart gini ratio');
    });

    // Export functions for Bar Chart
    function exportBarChartToExcel() {
      const last5Years = allYears.slice(-5);
      const labels = last5Years.map(y => y.toString());
      const surabayaValues = last5Years.map(year => {
        const data = surabayaData.find(d => d.year === year);
        return data && data.value !== null ? data.value : null;
      });
      const jatimValues = last5Years.map(year => {
        const data = jatimData.find(d => d.year === year);
        return data && data.value !== null ? data.value : null;
      });
      const exportData = [];
      exportData.push(['Tahun', 'Kota Surabaya', 'Jawa Timur']);
      labels.forEach((year, index) => {
        const surabayaVal = surabayaValues[index] !== null ? surabayaValues[index].toFixed(3) : 'Data tidak tersedia';
        const jatimVal = jatimValues[index] !== null ? jatimValues[index].toFixed(3) : 'Data tidak tersedia';
        exportData.push([year, surabayaVal, jatimVal]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 10 }, { wch: 20 }, { wch: 20 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Line Chart');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Gini_Ratio_LineChart_5Tahun_${today}.xlsx`);
    }

    function exportBarChartToPNG() {
      const url = window.comparisonBarChartInstance.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
      const link = document.createElement('a');
      link.download = `Gini_Ratio_LineChart_5Tahun_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }

    document.getElementById('downloadBarChartExcel').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportBarChartToExcel, 'data bar chart gini ratio');
    });
    document.getElementById('downloadBarChartPNG').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportBarChartToPNG, 'grafik bar chart gini ratio');
    });
  });