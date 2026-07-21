document.addEventListener("DOMContentLoaded", () => {
    const API_BASE_URL = '/api';
            let currentYear = null;
    let trendData = [];
    let ageDistribution = [];
    let pieChartData = [];
    let pyramidData = [];

    // Format number with thousand separators
    
    // Format number to thousands/millions
    
    // Format change values
    
    // Update summary cards
    function updateSummaryCards(summary) {
      // Total Population
      const totalPopEl = document.querySelector('.total-population-value');
      if (totalPopEl && summary.total_population !== null) {
        totalPopEl.textContent = window.formatPopulation(summary.total_population);
      } else if (totalPopEl) {
        totalPopEl.textContent = '-';
      }

      // Total Male
      const totalMaleEl = document.querySelector('.total-male-value');
      if (totalMaleEl && summary.total_male !== null) {
        totalMaleEl.textContent = window.formatPopulation(summary.total_male);
      } else if (totalMaleEl) {
        totalMaleEl.textContent = '-';
      }

      // Total Female
      const totalFemaleEl = document.querySelector('.total-female-value');
      if (totalFemaleEl && summary.total_female !== null) {
        totalFemaleEl.textContent = window.formatPopulation(summary.total_female);
      } else if (totalFemaleEl) {
        totalFemaleEl.textContent = '-';
      }

      // Population Ratio
      const ratioEl = document.querySelector('.population-ratio-value');
      if (ratioEl) {
        if (summary.population_ratio_display) {
          ratioEl.textContent = summary.population_ratio_display;
        } else {
          ratioEl.textContent = '-';
        }
      }

      // Update change indicators
      updateChangeIndicator('.total-population-change', summary.total_change, summary.previous_year);
      updateChangeIndicator('.total-male-change', summary.male_change, summary.previous_year);
      updateChangeIndicator('.total-female-change', summary.female_change, summary.previous_year);

      // Update previous ratio
      const prevRatioEl = document.querySelector('.prev-ratio-text');
      if (prevRatioEl && summary.prev_population_ratio_display && summary.previous_year) {
        prevRatioEl.textContent = `dari ${summary.previous_year}: ${summary.prev_population_ratio_display}`;
        prevRatioEl.style.display = 'inline';
      } else if (prevRatioEl) {
        prevRatioEl.style.display = 'none';
      }

      // Update year text
      const yearTexts = document.querySelectorAll('.year-text');
      yearTexts.forEach(el => {
        if (summary.selected_year) {
          el.textContent = `Tahun ${summary.selected_year}`;
        } else {
          el.textContent = 'Data tidak tersedia';
        }
      });
    }

    function updateChangeIndicator(selector, change, previousYear) {
      const container = document.querySelector(selector);
      if (!container) return;

      const indicator = container.querySelector('.change-indicator');
      const valueEl = container.querySelector('.change-value');
      const prevYearEl = container.parentElement.querySelector('.previous-year-text');

      if (change !== null && change !== undefined) {
        container.style.display = 'flex';
        container.style.alignItems = 'center';
        container.style.gap = '5px';
        container.style.color = 'rgba(255, 255, 255, 0.9)';
        container.style.fontSize = '12px';

        if (change > 0) {
          if (indicator) indicator.textContent = '▲';
        } else if (change < 0) {
          if (indicator) indicator.textContent = '▼';
        } else {
          if (indicator) indicator.textContent = '-';
        }

        const prefix = change > 0 ? '+' : '';
        if (valueEl) valueEl.textContent = prefix + window.formatChangeValue(change);

        if (prevYearEl && previousYear) {
          prevYearEl.textContent = `dari ${previousYear}`;
          prevYearEl.style.display = 'inline';
        }
      } else {
        container.style.display = 'none';
        if (prevYearEl) prevYearEl.style.display = 'none';
      }
    }

    // Load years for selector
    async function loadYears() {
      try {
        const response = await fetch(`${API_BASE_URL}/kependudukan-years`);
        const result = await response.json();
        
        if (result.success && result.data) {
          const yearSelector = document.getElementById('yearSelector');
          if (yearSelector) {
            yearSelector.innerHTML = '';
            result.data.forEach(year => {
              const option = document.createElement('option');
              option.value = year;
              option.textContent = year;
              if (year === result.data[0]) {
                option.selected = true;
                currentYear = year;
              }
              yearSelector.appendChild(option);
            });
          }
        }
      } catch (error) {
        console.error('Error loading years:', error);
      }
    }

    // Load summary data
    async function loadSummary(year) {
      try {
        const url = year ? `${API_BASE_URL}/kependudukan-summary?year=${year}` : `${API_BASE_URL}/kependudukan-summary`;
        const response = await fetch(url);
        const result = await response.json();
        
        if (result.success && result.data) {
          updateSummaryCards(result.data);
          currentYear = result.data.selected_year;
        }
      } catch (error) {
        console.error('Error loading summary:', error);
      }
    }

    // Load trend data
    async function loadTrend() {
      try {
        const response = await fetch(`${API_BASE_URL}/kependudukan-trend`);
        const result = await response.json();
        
        if (result.success && result.data) {
          trendData = result.data;
          renderTrendChart();
        }
      } catch (error) {
        console.error('Error loading trend:', error);
      }
    }

    // Load distribution data
    async function loadDistribution(year) {
      try {
        const url = year ? `${API_BASE_URL}/kependudukan-distribution?year=${year}` : `${API_BASE_URL}/kependudukan-distribution`;
        const response = await fetch(url);
        const result = await response.json();
        
        if (result.success && result.data) {
          ageDistribution = result.data;
          renderDistributionChart();
        }
      } catch (error) {
        console.error('Error loading distribution:', error);
      }
    }

    // Load pie chart data
    async function loadPieChart(year) {
      try {
        const url = year ? `${API_BASE_URL}/kependudukan-piechart?year=${year}` : `${API_BASE_URL}/kependudukan-piechart`;
        const response = await fetch(url);
        const result = await response.json();
        
        if (result.success && result.data) {
          pieChartData = result.data;
          renderPieChart();
        }
      } catch (error) {
        console.error('Error loading pie chart:', error);
      }
    }

    // Load pyramid data
    async function loadPyramid(year) {
      try {
        const url = year ? `${API_BASE_URL}/kependudukan-pyramid?year=${year}` : `${API_BASE_URL}/kependudukan-pyramid`;
        const response = await fetch(url);
        const result = await response.json();
        
        if (result.success && result.data) {
          pyramidData = result.data;
          renderPyramidChart();
          renderGenderComparisonChart();
        }
      } catch (error) {
        console.error('Error loading pyramid:', error);
      }
    }

    // Check if mobile
    const isMobile = window.innerWidth <= 767.98;

    // Chart 1: Trend Chart
    function renderTrendChart() {
      const trendChartDom = document.getElementById('trendChart');
      if (!trendChartDom || !trendData || trendData.length === 0) {
        if (trendChartDom) {
          trendChartDom.innerHTML = '<div style="text-align: center; padding: 50px; color: #999;">Data tidak tersedia. Silakan sinkronisasi data terlebih dahulu.</div>';
        }
        return;
      }

      const trendChart = echarts.init(trendChartDom);
      const trendYears = trendData.map(d => d.year.toString());
      const trendMale = trendData.map(d => d.male);
      const trendFemale = trendData.map(d => d.female);

      trendChart.setOption({
        tooltip: {
          trigger: 'axis',
          axisPointer: { type: 'cross' },
          formatter: function(params) {
            let result = 'Tahun: ' + params[0].axisValue + '<br/>';
            params.forEach(function(item) {
              result += item.marker + item.seriesName + ': ' + window.formatRupiah(item.value) + '<br/>';
            });
            return result;
          }
        },
        legend: {
          data: ['Laki-laki (LK)', 'Perempuan (PR)'],
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
          data: trendYears,
          axisLabel: {
            fontSize: isMobile ? 9 : 11
          }
        },
        yAxis: {
          type: 'value',
          name: 'Jumlah Penduduk',
          nameLocation: 'end',
          nameGap: isMobile ? 8 : 10,
          nameTextStyle: {
            fontSize: isMobile ? 10 : 12
          },
          axisLabel: {
            formatter: function(value) {
              return window.formatRupiah(value);
            },
            fontSize: isMobile ? 9 : 11
          }
        },
        series: [
          
          {
            name: 'Laki-laki (LK)',
            type: 'line',
            data: trendMale,
            itemStyle: { color: '#3b82f6' },
            lineStyle: { width: 3 },
            symbol: 'circle',
            symbolSize: 8
          },
          {
            name: 'Perempuan (PR)',
            type: 'line',
            data: trendFemale,
            itemStyle: { color: '#f59e0b' },
            lineStyle: { width: 3 },
            symbol: 'circle',
            symbolSize: 8
          }
        ]
      });
    }

    // Chart 2: Distribution Chart
    function renderDistributionChart() {
      const distributionChartDom = document.getElementById('distributionChart');
      if (!distributionChartDom || !ageDistribution || ageDistribution.length === 0) {
        if (distributionChartDom) {
          distributionChartDom.innerHTML = '<div style="text-align: center; padding: 50px; color: #999;">Data tidak tersedia. Silakan sinkronisasi data terlebih dahulu.</div>';
        }
        return;
      }

      const distributionChart = echarts.init(distributionChartDom);
      const ageGroups = ageDistribution.map(d => d.age_group);
      const populations = ageDistribution.map(d => d.population);

      distributionChart.setOption({
        tooltip: {
          trigger: 'axis',
          axisPointer: { type: 'shadow' },
          formatter: function(params) {
            return params[0].name + '<br/>' +
                   params[0].marker + 'Jumlah: ' + window.formatRupiah(params[0].value);
          }
        },
        grid: {
          left: '12%',
          right: '4%',
          bottom: '15%',
          top: '10%',
          containLabel: false
        },
        xAxis: {
          type: 'category',
          data: ageGroups,
          axisLabel: {
            rotate: 45,
            interval: 0,
            fontSize: isMobile ? 9 : 11
          }
        },
        yAxis: {
          type: 'value',
          name: 'Jumlah Penduduk',
          nameLocation: 'end',
          nameGap: isMobile ? 8 : 10,
          nameTextStyle: {
            fontSize: isMobile ? 10 : 12
          },
          axisLabel: {
            formatter: function(value) {
              return window.formatRupiah(value);
            },
            fontSize: isMobile ? 9 : 11
          }
        },
        series: [{
          name: 'Jumlah Penduduk',
          type: 'bar',
          data: populations,
          itemStyle: {
            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
              { offset: 0, color: '#3b82f6' },
              { offset: 1, color: '#60a5fa' }
            ])
          }
        }]
      });
    }

    // Chart 3: Pie Chart
    function renderPieChart() {
      const pieChartDom = document.getElementById('pieChart');
      if (!pieChartDom || !pieChartData || pieChartData.length === 0) {
        if (pieChartDom) {
          pieChartDom.innerHTML = '<div style="text-align: center; padding: 50px; color: #999;">Data tidak tersedia. Silakan sinkronisasi data terlebih dahulu.</div>';
        }
        return;
      }

      const pieChart = echarts.init(pieChartDom);
      
      // Function to extract age range from age group string (e.g., "0-4" -> [0, 4])
      function parseAgeGroup(ageGroup) {
        if (!ageGroup) return null;
        // Handle formats like "0-4", "5-9", "75+", etc.
        if (ageGroup.includes('+')) {
          const start = parseInt(ageGroup.replace('+', ''));
          return { start: start, end: 999 }; // 999 as max age
        }
        const parts = ageGroup.split('-');
        if (parts.length === 2) {
          return { start: parseInt(parts[0]), end: parseInt(parts[1]) };
        }
        return null;
      }
      
      // Function to categorize age group
      function categorizeAge(ageGroup) {
        const ageRange = parseAgeGroup(ageGroup);
        if (!ageRange) return null;
        
        const { start, end } = ageRange;
        
        // Bayi-balita (0-5 tahun): includes groups that fall within 0-5
        if (start >= 0 && start <= 5) {
          return 'Bayi-balita (0-5 tahun)';
        }
        // Anak-anak (5-11 tahun): includes groups that start at 5 or later but within 5-11
        if (start >= 5 && start <= 11) {
          return 'Anak-anak (5-11 tahun)';
        }
        // Remaja (12-25 tahun): includes groups that start at 12 or later but within 12-25
        if (start >= 12 && start <= 25) {
          return 'Remaja (12-25 tahun)';
        }
        // Dewasa (26-45 tahun): includes groups that start at 26 or later but within 26-45
        if (start >= 26 && start <= 45) {
          return 'Dewasa (26-45 tahun)';
        }
        // Lansia (46 tahun ke atas): includes groups that start at 46 or later
        if (start >= 46) {
          return 'Lansia (46+ tahun)';
        }
        
        return null;
      }
      
      // Group data by category
      const groupedData = {};
      pieChartData.forEach(d => {
        const category = categorizeAge(d.name);
        if (category) {
          if (!groupedData[category]) {
            groupedData[category] = 0;
          }
          groupedData[category] += d.value;
        }
      });
      
      // Convert to array format for ECharts
      const pieData = Object.keys(groupedData).map(category => ({
        name: category,
        value: groupedData[category]
      })).sort((a, b) => {
        // Sort by category order
        const order = {
          'Bayi-balita (0-5 tahun)': 1,
          'Anak-anak (5-11 tahun)': 2,
          'Remaja (12-25 tahun)': 3,
          'Dewasa (26-45 tahun)': 4,
          'Lansia (46+ tahun)': 5
        };
        return (order[a.name] || 999) - (order[b.name] || 999);
      });

      pieChart.setOption({
        tooltip: {
          trigger: 'item',
          formatter: function(params) {
            return params.name + '<br/>' + 
                   window.formatRupiah(params.value) + ' (' + params.percent + '%)';
          }
        },
        legend: {
          show: true,
          orient: 'horizontal',
          top: isMobile ? 5 : 10,
          left: 'center',
          itemGap: isMobile ? 12 : 15,
          itemWidth: isMobile ? 10 : 12,
          itemHeight: isMobile ? 10 : 12,
          textStyle: {
            fontSize: isMobile ? 10 : 11,
            fontWeight: 'normal'
          },
          formatter: function(name) {
            return name;
          }
        },
        series: [{
          name: 'Proporsi Penduduk',
          type: 'pie',
          radius: ['30%', '65%'],
          center: ['50%', '55%'],
          avoidLabelOverlap: false,
          itemStyle: {
            borderRadius: 8,
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
            itemStyle: {
              shadowBlur: 10,
              shadowOffsetX: 0,
              shadowColor: 'rgba(0, 0, 0, 0.5)'
            },
            label: {
              show: false
            }
          },
          data: pieData
        }]
      });
    }

    // Chart 4: Pyramid Chart
    function renderPyramidChart() {
      const pyramidChartDom = document.getElementById('pyramidChart');
      if (!pyramidChartDom || !pyramidData || pyramidData.length === 0) {
        if (pyramidChartDom) {
          pyramidChartDom.innerHTML = '<div style="text-align: center; padding: 50px; color: #999;">Data tidak tersedia. Silakan sinkronisasi data terlebih dahulu.</div>';
        }
        return;
      }

      const pyramidChart = echarts.init(pyramidChartDom);
      const pyramidAgeGroups = pyramidData.map(d => d.age_group);
      const pyramidMale = pyramidData.map(d => -d.male);
      const pyramidFemale = pyramidData.map(d => d.female);

      pyramidChart.setOption({
        tooltip: {
          trigger: 'axis',
          axisPointer: { type: 'shadow' },
          formatter: function(params) {
            let result = params[0].name + '<br/>';
            params.forEach(function(item) {
              if (item.seriesName === 'Laki-laki (LK)') {
                result += item.marker + item.seriesName + ': ' + window.formatRupiah(Math.abs(item.value)) + '<br/>';
              } else {
                result += item.marker + item.seriesName + ': ' + window.formatRupiah(item.value) + '<br/>';
              }
            });
            return result;
          }
        },
        legend: {
          data: ['Laki-laki (LK)', 'Perempuan (PR)'],
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
          left: isMobile ? '18%' : '15%',
          right: isMobile ? '18%' : '15%',
          bottom: '10%',
          top: isMobile ? '18%' : '10%',
          containLabel: false
        },
        xAxis: [{
          type: 'value',
          position: 'bottom',
          name: 'Jumlah Penduduk',
          nameLocation: 'middle',
          nameGap: isMobile ? 25 : 30,
          nameTextStyle: {
            fontSize: isMobile ? 10 : 12
          },
          axisLabel: {
            formatter: function(value) {
              return window.formatRupiah(Math.abs(value));
            },
            fontSize: isMobile ? 9 : 11
          }
        }],
        yAxis: {
          type: 'category',
          data: pyramidAgeGroups,
          axisLabel: {
            interval: 0,
            fontSize: isMobile ? 9 : 11
          }
        },
        series: [
          {
            name: 'Laki-laki (LK)',
            type: 'bar',
            data: pyramidMale,
            itemStyle: { color: '#3b82f6' }
          },
          {
            name: 'Perempuan (PR)',
            type: 'bar',
            data: pyramidFemale,
            itemStyle: { color: '#f59e0b' }
          }
        ]
      });
    }

    // Chart 5: Gender Comparison Chart
    function renderGenderComparisonChart() {
      const genderComparisonChartDom = document.getElementById('genderComparisonChart');
      if (!genderComparisonChartDom || !pyramidData || pyramidData.length === 0) {
        if (genderComparisonChartDom) {
          genderComparisonChartDom.innerHTML = '<div style="text-align: center; padding: 50px; color: #999;">Data tidak tersedia. Silakan sinkronisasi data terlebih dahulu.</div>';
        }
        return;
      }

      const genderComparisonChart = echarts.init(genderComparisonChartDom);
      const comparisonAgeGroups = pyramidData.map(d => d.age_group);
      const comparisonMale = pyramidData.map(d => d.male);
      const comparisonFemale = pyramidData.map(d => d.female);

      genderComparisonChart.setOption({
        tooltip: {
          trigger: 'axis',
          axisPointer: { type: 'shadow' },
          formatter: function(params) {
            let result = params[0].name + '<br/>';
            params.forEach(function(item) {
              result += item.marker + item.seriesName + ': ' + window.formatRupiah(item.value) + '<br/>';
            });
            return result;
          }
        },
        legend: {
          data: ['Laki-laki (LK)', 'Perempuan (PR)'],
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
          bottom: '15%',
          top: isMobile ? '18%' : '15%',
          containLabel: false
        },
        xAxis: {
          type: 'category',
          data: comparisonAgeGroups,
          axisLabel: {
            rotate: 45,
            interval: 0,
            fontSize: isMobile ? 9 : 11
          }
        },
        yAxis: {
          type: 'value',
          name: 'Jumlah Penduduk',
          nameLocation: 'end',
          nameGap: isMobile ? 8 : 10,
          nameTextStyle: {
            fontSize: isMobile ? 10 : 12
          },
          axisLabel: {
            formatter: function(value) {
              return window.formatRupiah(value);
            },
            fontSize: isMobile ? 9 : 11
          }
        },
        series: [
          {
            name: 'Laki-laki (LK)',
            type: 'bar',
            data: comparisonMale,
            itemStyle: { color: '#3b82f6' }
          },
          {
            name: 'Perempuan (PR)',
            type: 'bar',
            data: comparisonFemale,
            itemStyle: { color: '#f59e0b' }
          }
        ]
      });
    }

    // Function to resize all charts
    function resizeAllCharts() {
      const charts = ['trendChart', 'distributionChart', 'pieChart', 'pyramidChart', 'genderComparisonChart'];
      charts.forEach(chartId => {
        const chartDom = document.getElementById(chartId);
        if (chartDom) {
          const chart = echarts.getInstanceByDom(chartDom);
          if (chart) {
            setTimeout(() => {
              chart.resize();
            }, 100);
          }
        }
      });
    }

    // Handle window resize
    window.addEventListener('resize', resizeAllCharts);

    // Handle sidebar toggle
    const sidebarToggle = document.querySelector('#sidebarToggle, #check, [data-toggle="sidebar"], .sidebar-toggle');
    if (sidebarToggle) {
      sidebarToggle.addEventListener('change', resizeAllCharts);
      sidebarToggle.addEventListener('click', function() {
        setTimeout(resizeAllCharts, 300);
      });
    }

    // Observe sidebar changes
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

    // Year selector handler
    const yearSelector = document.getElementById('yearSelector');
    if (yearSelector) {
      yearSelector.addEventListener('change', async function() {
        const selectedYear = this.value;
        currentYear = selectedYear;

        const chartsToLoad = ['distributionChart', 'pieChart', 'pyramidChart', 'genderComparisonChart'];
        chartsToLoad.forEach(id => {
          const dom = document.getElementById(id);
          if (dom) {
            const chart = echarts.getInstanceByDom(dom) || echarts.init(dom);
            chart.showLoading({ text: 'Memuat data...', color: '#3b82f6' });
          }
        });
        
        // Show loading in summary cards
        const summaryEls = ['.total-population-value', '.total-male-value', '.total-female-value', '.total-ratio-value'];
        summaryEls.forEach(selector => {
            const el = document.querySelector(selector);
            if (el) el.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        });
        await Promise.all([
          loadSummary(selectedYear),
          loadDistribution(selectedYear),
          loadPieChart(selectedYear),
          loadPyramid(selectedYear)
        ]);
        chartsToLoad.forEach(id => {
          const dom = document.getElementById(id);
          if (dom) {
            const chart = echarts.getInstanceByDom(dom);
            if (chart) chart.hideLoading();
          }
        });

      });
    }

    // Export functions
    function exportTrendToExcel() {
      if (!trendData || trendData.length === 0) return;
      const exportData = [];
      exportData.push(['Tahun', 'Laki-laki (LK)', 'Perempuan (PR)']);
      trendData.forEach(data => {
        exportData.push([
          data.year.toString(),
          window.formatRupiah(data.male),
          window.formatRupiah(data.female)
        ]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 10 }, { wch: 20 }, { wch: 20 }, { wch: 20 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Trend');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Kependudukan_Trend_${today}.xlsx`);
    }

    function exportTrendToPNG() {
      const chart = echarts.getInstanceByDom(document.getElementById('trendChart'));
      if (chart) {
        const url = chart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
        const link = document.createElement('a');
        link.download = `Kependudukan_Trend_${new Date().toISOString().split('T')[0]}.png`;
        link.href = url;
        link.click();
      }
    }

    function exportDistributionToExcel() {
      if (!ageDistribution || ageDistribution.length === 0) return;
      const exportData = [];
      exportData.push(['Kelompok Umur', 'Jumlah Penduduk']);
      ageDistribution.forEach(data => {
        exportData.push([data.age_group, window.formatRupiah(data.population)]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 25 }, { wch: 20 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Distribusi');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Kependudukan_Distribusi_${today}.xlsx`);
    }

    function exportDistributionToPNG() {
      const chart = echarts.getInstanceByDom(document.getElementById('distributionChart'));
      if (chart) {
        const url = chart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
        const link = document.createElement('a');
        link.download = `Kependudukan_Distribusi_${new Date().toISOString().split('T')[0]}.png`;
        link.href = url;
        link.click();
      }
    }

    function exportPieToExcel() {
      if (!pieChartData || pieChartData.length === 0) return;
      const exportData = [];
      exportData.push(['Kelompok Umur', 'Jumlah Penduduk', 'Persentase (%)']);
      const total = pieChartData.reduce((sum, d) => sum + d.value, 0);
      pieChartData.forEach(data => {
        const percentage = total > 0 ? ((data.value / total) * 100).toFixed(2) : '0';
        exportData.push([data.name, window.formatRupiah(data.value), percentage]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 25 }, { wch: 20 }, { wch: 15 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Proporsi');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Kependudukan_Proporsi_${today}.xlsx`);
    }

    function exportPieToPNG() {
      const chart = echarts.getInstanceByDom(document.getElementById('pieChart'));
      if (chart) {
        const url = chart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
        const link = document.createElement('a');
        link.download = `Kependudukan_Proporsi_${new Date().toISOString().split('T')[0]}.png`;
        link.href = url;
        link.click();
      }
    }

    function exportGenderComparisonToExcel() {
      if (!pyramidData || pyramidData.length === 0) return;
      const exportData = [];
      exportData.push(['Kelompok Umur', 'Laki-laki (LK)', 'Perempuan (PR)']);
      pyramidData.forEach(data => {
        exportData.push([
          data.age_group,
          window.formatRupiah(data.male),
          window.formatRupiah(data.female)
        ]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 25 }, { wch: 20 }, { wch: 20 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Perbandingan Gender');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Kependudukan_Perbandingan_Gender_${today}.xlsx`);
    }

    function exportGenderComparisonToPNG() {
      const chart = echarts.getInstanceByDom(document.getElementById('genderComparisonChart'));
      if (chart) {
        const url = chart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
        const link = document.createElement('a');
        link.download = `Kependudukan_Perbandingan_Gender_${new Date().toISOString().split('T')[0]}.png`;
        link.href = url;
        link.click();
      }
    }

    function exportPyramidToExcel() {
      if (!pyramidData || pyramidData.length === 0) return;
      const exportData = [];
      exportData.push(['Kelompok Umur', 'Laki-laki (LK)', 'Perempuan (PR)']);
      pyramidData.forEach(data => {
        exportData.push([
          data.age_group,
          window.formatRupiah(data.male),
          window.formatRupiah(data.female)
        ]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 25 }, { wch: 20 }, { wch: 20 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Piramida');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Kependudukan_Piramida_${today}.xlsx`);
    }

    function exportPyramidToPNG() {
      const chart = echarts.getInstanceByDom(document.getElementById('pyramidChart'));
      if (chart) {
        const url = chart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
        const link = document.createElement('a');
        link.download = `Kependudukan_Piramida_${new Date().toISOString().split('T')[0]}.png`;
        link.href = url;
        link.click();
      }
    }

    // Helper function to check authentication before download
    
    // Event listeners for download buttons
    document.getElementById('downloadTrendExcel')?.addEventListener('click', function(e) {
      e.preventDefault();
      window.checkAuthBeforeDownload(exportTrendToExcel, 'data trend kependudukan');
    });
    document.getElementById('downloadTrendPNG')?.addEventListener('click', function(e) {
      e.preventDefault();
      window.checkAuthBeforeDownload(exportTrendToPNG, 'grafik trend kependudukan');
    });
    document.getElementById('downloadDistributionExcel')?.addEventListener('click', function(e) {
      e.preventDefault();
      window.checkAuthBeforeDownload(exportDistributionToExcel, 'data distribusi kependudukan');
    });
    document.getElementById('downloadDistributionPNG')?.addEventListener('click', function(e) {
      e.preventDefault();
      window.checkAuthBeforeDownload(exportDistributionToPNG, 'grafik distribusi kependudukan');
    });
    document.getElementById('downloadPieExcel')?.addEventListener('click', function(e) {
      e.preventDefault();
      window.checkAuthBeforeDownload(exportPieToExcel, 'data proporsi kependudukan');
    });
    document.getElementById('downloadPiePNG')?.addEventListener('click', function(e) {
      e.preventDefault();
      window.checkAuthBeforeDownload(exportPieToPNG, 'grafik proporsi kependudukan');
    });
    document.getElementById('downloadGenderComparisonExcel')?.addEventListener('click', function(e) {
      e.preventDefault();
      window.checkAuthBeforeDownload(exportGenderComparisonToExcel, 'data perbandingan gender kependudukan');
    });
    document.getElementById('downloadGenderComparisonPNG')?.addEventListener('click', function(e) {
      e.preventDefault();
      window.checkAuthBeforeDownload(exportGenderComparisonToPNG, 'grafik perbandingan gender kependudukan');
    });
    document.getElementById('downloadPyramidExcel')?.addEventListener('click', function(e) {
      e.preventDefault();
      window.checkAuthBeforeDownload(exportPyramidToExcel, 'data piramida kependudukan');
    });
    document.getElementById('downloadPyramidPNG')?.addEventListener('click', function(e) {
      e.preventDefault();
      window.checkAuthBeforeDownload(exportPyramidToPNG, 'grafik piramida kependudukan');
    });

    // Initialize: Load all data
    async function initialize() {
      await loadYears();
      if (currentYear) {
        const chartsToLoad = ['trendChart', 'distributionChart', 'pieChart', 'pyramidChart', 'genderComparisonChart'];
        chartsToLoad.forEach(id => {
          const dom = document.getElementById(id);
          if (dom) {
            const chart = echarts.getInstanceByDom(dom) || echarts.init(dom);
            chart.showLoading({ text: 'Memuat data...', color: '#3b82f6' });
          }
        });
        
        // Show loading in summary cards
        const summaryEls = ['.total-population-value', '.total-male-value', '.total-female-value', '.total-ratio-value'];
        summaryEls.forEach(selector => {
            const el = document.querySelector(selector);
            if (el) el.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        });

        await Promise.all([
          loadSummary(currentYear),
          loadTrend(),
          loadDistribution(currentYear),
          loadPieChart(currentYear),
          loadPyramid(currentYear)
        ]);

        chartsToLoad.forEach(id => {
          const dom = document.getElementById(id);
          if (dom) {
            const chart = echarts.getInstanceByDom(dom);
            if (chart) chart.hideLoading();
          }
        });
      }
      
      // Resize charts after layout is complete
      setTimeout(() => {
        resizeAllCharts();
      }, 500);
    }

    initialize();
  });