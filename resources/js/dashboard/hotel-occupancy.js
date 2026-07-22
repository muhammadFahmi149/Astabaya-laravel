document.addEventListener("DOMContentLoaded", async () => {
    // Wait for ECharts to be loaded
    if (typeof echarts === 'undefined') {
      console.error('ECharts library not loaded! Please check if the script is included.');
      return;
    }
    
    const API_BASE = window.APP_CONFIG ? window.APP_CONFIG.apiUrl : '/api';
    
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
    const isAuthenticated = window.APP_CONFIG && window.APP_CONFIG.isAuthenticated;

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
        } else {
          tpkChangeEl.innerHTML = '';
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
        } else {
          mktjChangeEl.innerHTML = '';
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
        } else {
          rlmtgabChangeEl.innerHTML = '';
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
        } else {
          gprChangeEl.innerHTML = '';
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
      
      const recentYears = distinctYears.slice(0, 3).reverse();
      
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
        const recentYears = distinctYears.slice(0, 3).reverse();
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


    document.getElementById('downloadTpkLineExcel').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportTpkLineToExcel, 'data TPK line hotel');
    });
    document.getElementById('downloadTpkLinePNG').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportTpkLineToPNG, 'grafik TPK line hotel');
    });

    // Export functions for TPK Comparison Chart
    function exportTpkComparisonToExcel() {
      const recentYears = distinctYears.slice(0, 3).reverse();
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
      window.checkAuthBeforeDownload(exportTpkComparisonToExcel, 'data perbandingan TPK hotel');
    });
    document.getElementById('downloadTpkComparisonPNG').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportTpkComparisonToPNG, 'grafik perbandingan TPK hotel');
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
      window.checkAuthBeforeDownload(exportTpkYearlyToExcel, 'data TPK tahunan hotel');
    });
    document.getElementById('downloadTpkYearlyPNG').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportTpkYearlyToPNG, 'grafik TPK tahunan hotel');
    });
  });