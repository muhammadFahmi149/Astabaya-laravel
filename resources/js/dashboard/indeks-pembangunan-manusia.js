document.addEventListener("turbo:load", () => {
    const API_BASE = window.APP_CONFIG ? window.APP_CONFIG.apiUrl : '/api';
    
    // ========== Load all IPM data from APIs ==========
    async function loadAllIPMData() {
      try {
        const location = 'Kota Surabaya';
        
        // Load all data in parallel with location filter
        const [
          uhhSpRes, hlsRes, rlsRes, 
          surabayaRes, jatimRes,
          pengeluaranRes, indeksKesehatanRes, indeksPendidikanRes, indeksHidupLayakRes
        ] = await Promise.all([
          fetch(`${API_BASE}/ipm-uhh-sp?location=${encodeURIComponent(location)}`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
          fetch(`${API_BASE}/ipm-hls?location=${encodeURIComponent(location)}`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
          fetch(`${API_BASE}/ipm-rls?location=${encodeURIComponent(location)}`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
          fetch(`${API_BASE}/ipm-surabaya`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
          fetch(`${API_BASE}/ipm-jatim`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
          fetch(`${API_BASE}/ipm-pengeluaran-per-kapita?location=${encodeURIComponent(location)}`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
          fetch(`${API_BASE}/ipm-indeks-kesehatan?location=${encodeURIComponent(location)}`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
          fetch(`${API_BASE}/ipm-indeks-pendidikan?location=${encodeURIComponent(location)}`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
          fetch(`${API_BASE}/ipm-indeks-hidup-layak?location=${encodeURIComponent(location)}`).then(r => r.json()).catch(() => ({ success: false, data: [] }))
        ]);

        const uhhSpData = uhhSpRes.success ? uhhSpRes.data : [];
        const hlsData = hlsRes.success ? hlsRes.data : [];
        const rlsData = rlsRes.success ? rlsRes.data : [];
        const surabayaData = surabayaRes.success ? surabayaRes.data : [];
        const jatimData = jatimRes.success ? jatimRes.data : [];
        const pengeluaranData = pengeluaranRes.success ? pengeluaranRes.data : [];
        const indeksKesehatanData = indeksKesehatanRes.success ? indeksKesehatanRes.data : [];
        const indeksPendidikanData = indeksPendidikanRes.success ? indeksPendidikanRes.data : [];
        const indeksHidupLayakData = indeksHidupLayakRes.success ? indeksHidupLayakRes.data : [];

        // Debug logging
        console.log('IPM Data loaded:', {
          uhhSp: { count: uhhSpData.length, sample: uhhSpData[0] },
          hls: { count: hlsData.length, sample: hlsData[0] },
          rls: { count: rlsData.length, sample: rlsData[0] },
          surabaya: { count: surabayaData.length, sample: surabayaData[0] },
          jatim: { count: jatimData.length, sample: jatimData[0] },
          pengeluaran: { count: pengeluaranData.length, sample: pengeluaranData[0] },
          indeksKesehatan: { count: indeksKesehatanData.length, sample: indeksKesehatanData[0] },
          indeksPendidikan: { count: indeksPendidikanData.length, sample: indeksPendidikanData[0] },
          indeksHidupLayak: { count: indeksHidupLayakData.length, sample: indeksHidupLayakData[0] }
        });

        // Update summary cards
        updateSummaryCards(uhhSpData, hlsData, rlsData, surabayaData, jatimData, pengeluaranData, indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData);
        
        // Initialize carousel
        initCarousel();
        
        // Initialize charts
        initCharts(uhhSpData, hlsData, rlsData, surabayaData, jatimData, pengeluaranData, indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData);
      } catch (error) {
        console.error('Error loading IPM data:', error);
      }
    }

    function updateSummaryCards(uhhSpData, hlsData, rlsData, surabayaData, jatimData, pengeluaranData, indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData) {
      // Sort data by year to get latest
      const sortByYear = (a, b) => (b.year || 0) - (a.year || 0);
      
      // Update UHH SP
      if (uhhSpData && uhhSpData.length > 0) {
        const sorted = [...uhhSpData].sort(sortByYear);
        const latest = sorted[0];
        const el = document.getElementById('uhh-sp-value');
        const yearEl = document.getElementById('uhh-sp-year');
        if (el) el.textContent = latest.value != null ? Number(latest.value).toFixed(2) : '-';
        if (yearEl) yearEl.textContent = latest.year != null ? `Tahun ${latest.year}` : 'Data tidak tersedia';
      } else {
        const el = document.getElementById('uhh-sp-value');
        const yearEl = document.getElementById('uhh-sp-year');
        if (el) el.textContent = '-';
        if (yearEl) yearEl.textContent = 'Data tidak tersedia';
      }

      // Update HLS
      if (hlsData && hlsData.length > 0) {
        const sorted = [...hlsData].sort(sortByYear);
        const latest = sorted[0];
        const el = document.getElementById('hls-value');
        const yearEl = document.getElementById('hls-year');
        if (el) el.textContent = latest.value != null ? Number(latest.value).toFixed(2) : '-';
        if (yearEl) yearEl.textContent = latest.year != null ? `Tahun ${latest.year}` : 'Data tidak tersedia';
      } else {
        const el = document.getElementById('hls-value');
        const yearEl = document.getElementById('hls-year');
        if (el) el.textContent = '-';
        if (yearEl) yearEl.textContent = 'Data tidak tersedia';
      }

      // Update RLS
      if (rlsData && rlsData.length > 0) {
        const sorted = [...rlsData].sort(sortByYear);
        const latest = sorted[0];
        const el = document.getElementById('rls-value');
        const yearEl = document.getElementById('rls-year');
        if (el) el.textContent = latest.value != null ? Number(latest.value).toFixed(2) : '-';
        if (yearEl) yearEl.textContent = latest.year != null ? `Tahun ${latest.year}` : 'Data tidak tersedia';
      } else {
        const el = document.getElementById('rls-value');
        const yearEl = document.getElementById('rls-year');
        if (el) el.textContent = '-';
        if (yearEl) yearEl.textContent = 'Data tidak tersedia';
      }

      // Update Pengeluaran per Kapita
      if (pengeluaranData && pengeluaranData.length > 0) {
        const sorted = [...pengeluaranData].sort(sortByYear);
        const latest = sorted[0];
        const el = document.getElementById('pengeluaran-value');
        const yearEl = document.getElementById('pengeluaran-year');
        if (el) el.textContent = latest.value != null ? `Rp ${Number(latest.value).toFixed(2)} Juta` : '-';
        if (yearEl) yearEl.textContent = latest.year != null ? `Tahun ${latest.year}` : 'Data tidak tersedia';
      } else {
        const el = document.getElementById('pengeluaran-value');
        const yearEl = document.getElementById('pengeluaran-year');
        if (el) el.textContent = '-';
        if (yearEl) yearEl.textContent = 'Data tidak tersedia';
      }

      // Update Indeks Kesehatan
      if (indeksKesehatanData && indeksKesehatanData.length > 0) {
        const sorted = [...indeksKesehatanData].sort(sortByYear);
        const latest = sorted[0];
        const el = document.getElementById('indeks-kesehatan-value');
        const yearEl = document.getElementById('indeks-kesehatan-year');
        if (el) el.textContent = latest.value != null ? Number(latest.value).toFixed(2) : '-';
        if (yearEl) yearEl.textContent = latest.year != null ? `Tahun ${latest.year}` : 'Data tidak tersedia';
      } else {
        const el = document.getElementById('indeks-kesehatan-value');
        const yearEl = document.getElementById('indeks-kesehatan-year');
        if (el) el.textContent = '-';
        if (yearEl) yearEl.textContent = 'Data tidak tersedia';
      }

      // Update Indeks Pendidikan
      if (indeksPendidikanData && indeksPendidikanData.length > 0) {
        const sorted = [...indeksPendidikanData].sort(sortByYear);
        const latest = sorted[0];
        const el = document.getElementById('indeks-pendidikan-value');
        const yearEl = document.getElementById('indeks-pendidikan-year');
        if (el) el.textContent = latest.value != null ? Number(latest.value).toFixed(2) : '-';
        if (yearEl) yearEl.textContent = latest.year != null ? `Tahun ${latest.year}` : 'Data tidak tersedia';
      } else {
        const el = document.getElementById('indeks-pendidikan-value');
        const yearEl = document.getElementById('indeks-pendidikan-year');
        if (el) el.textContent = '-';
        if (yearEl) yearEl.textContent = 'Data tidak tersedia';
      }

      // Update Indeks Hidup Layak
      if (indeksHidupLayakData && indeksHidupLayakData.length > 0) {
        const sorted = [...indeksHidupLayakData].sort(sortByYear);
        const latest = sorted[0];
        const el = document.getElementById('indeks-hidup-layak-value');
        const yearEl = document.getElementById('indeks-hidup-layak-year');
        if (el) el.textContent = latest.value != null ? Number(latest.value).toFixed(2) : '-';
        if (yearEl) yearEl.textContent = latest.year != null ? `Tahun ${latest.year}` : 'Data tidak tersedia';
      } else {
        const el = document.getElementById('indeks-hidup-layak-value');
        const yearEl = document.getElementById('indeks-hidup-layak-year');
        if (el) el.textContent = '-';
        if (yearEl) yearEl.textContent = 'Data tidak tersedia';
      }

      // Update Surabaya IPM
      if (surabayaData && surabayaData.length > 0) {
        const sorted = [...surabayaData].sort(sortByYear);
        const latest = sorted[0];
        const el = document.getElementById('surabaya-ipm-value');
        const yearEl = document.getElementById('surabaya-year');
        if (el) el.textContent = latest.ipm_value ? Number(latest.ipm_value).toFixed(2) : '-';
        if (yearEl) yearEl.textContent = latest.year != null ? `Tahun ${latest.year}` : '-';
      } else {
        const el = document.getElementById('surabaya-ipm-value');
        const yearEl = document.getElementById('surabaya-year');
        if (el) el.textContent = '-';
        if (yearEl) yearEl.textContent = 'Data tidak tersedia';
      }

      // Update Jatim IPM
      if (jatimData && jatimData.length > 0) {
        const sorted = [...jatimData].sort(sortByYear);
        const latest = sorted[0];
        const el = document.getElementById('jatim-ipm-value');
        const yearEl = document.getElementById('jatim-year');
        if (el) el.textContent = latest.ipm_value ? Number(latest.ipm_value).toFixed(2) : '-';
        if (yearEl) yearEl.textContent = latest.year != null ? `Tahun ${latest.year}` : '-';
      } else {
        const el = document.getElementById('jatim-ipm-value');
        const yearEl = document.getElementById('jatim-year');
        if (el) el.textContent = '-';
        if (yearEl) yearEl.textContent = 'Data tidak tersedia';
      }
    }

    // ========== IPM Indicator Carousel - Continuous Infinite Scroll to Right ==========
    function initCarousel() {
      const carousel = document.getElementById("ipmIndicatorCarousel");
      if (!carousel) return;

      const cards = carousel.querySelectorAll('.indicator-card');
      if (cards.length === 0) return;

      // Wrap existing cards in content set
      const originalContent = document.createElement('div');
      originalContent.className = 'indicator-carousel-content';
      originalContent.style.display = 'flex';
      originalContent.style.gap = '15px';
      originalContent.style.flexShrink = '0';
      originalContent.style.minWidth = 'fit-content';
      
      // Move existing cards to originalContent
      Array.from(cards).forEach(card => {
        originalContent.appendChild(card);
      });

      // Create duplicate content set for seamless loop
      const duplicateContent = originalContent.cloneNode(true);
      duplicateContent.setAttribute('aria-hidden', 'true');

      // Clear carousel and add both content sets
      carousel.innerHTML = '';
      carousel.appendChild(originalContent);
      carousel.appendChild(duplicateContent);

      const contentSets = carousel.querySelectorAll(".indicator-carousel-content");
      if (contentSets.length < 2) return;

      function getContentSetWidth() {
        return contentSets[0] ? contentSets[0].offsetWidth + 15 : 0;
      }

      let currentPosition = 0;
      let isPaused = false;
      let animationFrameId;
      const scrollSpeed = 1.5;

      function animate() {
        if (!isPaused) {
          const contentSetWidth = getContentSetWidth();
          currentPosition += scrollSpeed;
          if (currentPosition >= contentSetWidth) {
            currentPosition = currentPosition - contentSetWidth;
          }
          carousel.style.transform = `translateX(-${currentPosition}px)`;
        }
        animationFrameId = requestAnimationFrame(animate);
      }

      const carouselWrapper = carousel.closest(".indicator-carousel-wrapper");
      if (carouselWrapper) {
        carouselWrapper.addEventListener("mouseenter", () => { isPaused = true; });
        carouselWrapper.addEventListener("mouseleave", () => { isPaused = false; });
      }

      animate();

      let resizeTimeout;
      window.addEventListener("resize", () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
          const contentSetWidth = getContentSetWidth();
          if (currentPosition >= contentSetWidth) {
            currentPosition = currentPosition % contentSetWidth;
          }
        }, 250);
      });
    }

    // ========== Initialize Charts ==========
    function initCharts(uhhSpData, hlsData, rlsData, surabayaData, jatimData, pengeluaranData, indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData) {
      // Sort data by year
      const sortByYear = (a, b) => (a.year || 0) - (b.year || 0);
      surabayaData.sort(sortByYear);
      jatimData.sort(sortByYear);
      uhhSpData.sort(sortByYear);
      hlsData.sort(sortByYear);
      rlsData.sort(sortByYear);
      pengeluaranData.sort(sortByYear);
      indeksKesehatanData.sort(sortByYear);
      indeksPendidikanData.sort(sortByYear);
      indeksHidupLayakData.sort(sortByYear);

      // Calculate comparisons for IPM cards
      calculateComparison(surabayaData, 'surabaya-comparison', 'rgba(255, 255, 255, 0.9)');
      calculateComparison(jatimData, 'jatim-comparison', 'rgba(255, 255, 255, 0.9)');

      // Get all unique years
      const allYears = [...new Set([...surabayaData.map(d => d.year), ...jatimData.map(d => d.year)])].sort((a, b) => a - b);

      // Create trend chart
      createTrendChart(surabayaData, jatimData, allYears);

      // Create sub-indicator charts
      createSubIndicatorCharts(uhhSpData, hlsData, rlsData, pengeluaranData, indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData);

      // Create composition chart
      createCompositionChart(indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData, allYears);
      
      // Setup Year Filter for Composition Chart
      const filterYearComp = document.getElementById('filterYearComposition');
      if (filterYearComp && filterYearComp.options.length === 0) {
        // Sort descending
        const yearsDesc = [...allYears].sort((a, b) => b - a);
        yearsDesc.forEach(year => {
          const option = document.createElement('option');
          option.value = year;
          option.textContent = year;
          filterYearComp.appendChild(option);
        });
        
        // Add event listener
        filterYearComp.addEventListener('change', function(e) {
          createCompositionChart(indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData, allYears, parseInt(e.target.value));
        });
      }
    }

    function calculateComparison(data, containerId, textColor = 'rgba(255, 255, 255, 0.9)') {
      if (!data || data.length < 2) return;
      
      // Sort by year ascending
      const sorted = [...data].sort((a, b) => (a.year || 0) - (b.year || 0));
      const latest = sorted[sorted.length - 1];
      const previous = sorted[sorted.length - 2];
      
      if (!latest || latest.ipm_value === null && latest.value === null || !previous || previous.ipm_value === null && previous.value === null) return;
      
      const latestValue = latest.ipm_value !== undefined ? latest.ipm_value : latest.value;
      const previousValue = previous.ipm_value !== undefined ? previous.ipm_value : previous.value;
      
      if (latestValue === null || previousValue === null) return;
      
      const diff = latestValue - previousValue;
      const diffFormatted = Math.abs(diff).toFixed(2);
      
      const container = document.getElementById(containerId);
      if (!container) return;
      
      let arrow = '─';
      if (diff > 0) arrow = '▲';
      else if (diff < 0) arrow = '▼';
      
      container.innerHTML = `
        <span style="color: ${textColor}; font-size: 12px;">${arrow}</span>
        <span style="color: ${textColor}; font-size: 12px;">${diff >= 0 ? '+' : ''}${diffFormatted}</span>
        <span style="color: ${textColor.replace('0.9', '0.8')}; font-size: 11px;">dari ${previous.year}</span>
      `;
    }

    function createTrendChart(surabayaData, jatimData, allYears) {
      const trendChartDom = document.getElementById('trendChart');
      if (!trendChartDom) return;
      
      const trendChart = echarts.init(trendChartDom);
      
      const labels = allYears.map(y => y.toString());
      const surabayaValues = allYears.map(year => {
        const data = surabayaData.find(d => d.year === year);
        return data && data.ipm_value !== null ? data.ipm_value : null;
      });
      const jatimValues = allYears.map(year => {
        const data = jatimData.find(d => d.year === year);
        return data && data.ipm_value !== null ? data.ipm_value : null;
      });

      const allValues = [
        ...surabayaValues.filter(v => v !== null),
        ...jatimValues.filter(v => v !== null)
      ];
      
      const minValue = allValues.length > 0 ? Math.min(...allValues) : 0;
      const maxValue = allValues.length > 0 ? Math.max(...allValues) : 100;
      const yMin = Math.max(0, minValue - (minValue * 0.05));
      const yMax = maxValue + (maxValue * 0.10);
      const roundedYMin = Math.floor(yMin / 5) * 5;
      const roundedYMax = Math.ceil(yMax / 5) * 5;

      trendChart.setOption({
        tooltip: {
          trigger: 'axis',
          appendToBody: true,
          formatter: function(params) {
            let result = 'Tahun: ' + params[0].axisValue + '<br/>';
            params.forEach(function(item) {
              if (item.value === null || item.value === undefined) {
                result += item.marker + item.seriesName + ': Data tidak tersedia<br/>';
              } else {
                result += item.marker + item.seriesName + ': ' + Number(item.value).toFixed(2) + '<br/>';
              }
            });
            return result;
          }
        },
        legend: {
          data: ['Kota Surabaya', 'Jawa Timur'],
          bottom: 0,
          orient: 'horizontal'
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
          boundaryGap: false,
          data: labels,
          name: 'Tahun',
          nameLocation: 'middle',
          nameGap: 30,
          axisLabel: {
            show: true,
            margin: 12,
            rotate: 0,
            interval: 0
          }
        },
        yAxis: {
          type: 'value',
          scale: true,
          boundaryGap: ['10%', '10%'],
          axisLabel: {
            show: true,
            formatter: function(value) {
              return value.toFixed(1);
            },
            margin: 12
          },
          name: 'Nilai IPM',
          nameLocation: 'end',
          nameGap: 15
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
                x: 0, y: 0, x2: 0, y2: 1,
                colorStops: [
                  { offset: 0, color: 'rgba(59, 130, 246, 0.3)' },
                  { offset: 1, color: 'rgba(59, 130, 246, 0.05)' }
                ]
              }
            },
            lineStyle: { color: 'rgb(59, 130, 246)', width: 3 },
            itemStyle: { color: 'rgb(59, 130, 246)', borderColor: '#fff', borderWidth: 2 },
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
                x: 0, y: 0, x2: 0, y2: 1,
                colorStops: [
                  { offset: 0, color: 'rgba(239, 68, 68, 0.3)' },
                  { offset: 1, color: 'rgba(239, 68, 68, 0.05)' }
                ]
              }
            },
            lineStyle: { color: 'rgb(239, 68, 68)', width: 3 },
            itemStyle: { color: 'rgb(239, 68, 68)', borderColor: '#fff', borderWidth: 2 },
            symbol: 'circle',
            symbolSize: 8
          }
        ]
      });

      window.chartInstances = window.chartInstances || {};
      window.chartInstances.trend = trendChart;
    }

    function createLineChart(canvasId, data, title, color, gridOptions = {}) {
      const chartDom = document.getElementById(canvasId);
      if (!chartDom) return;
      
      const sortedData = [...data].sort((a, b) => a.year - b.year);
      const last5YearsData = sortedData.slice(-5);
      
      const chart = echarts.init(chartDom);
      const labels = last5YearsData.map(d => d.year.toString());
      const values = last5YearsData.map(d => d.value !== null ? d.value : null);

      const validValues = values.filter(v => v !== null);
      const minValue = validValues.length > 0 ? Math.min(...validValues) : 0;
      const maxValue = validValues.length > 0 ? Math.max(...validValues) : 100;
      const yMin = Math.max(0, minValue - (minValue * 0.05));
      const yMax = gridOptions.yMax !== undefined ? gridOptions.yMax : (maxValue + (maxValue * 0.10));

      const rgbaColor = color.replace('rgb', 'rgba').replace(')', ', 0.1)');

      chart.setOption({
        tooltip: {
          trigger: 'axis',
          appendToBody: true,
          formatter: function(params) {
            if (params[0].value === null || params[0].value === undefined) {
              return params[0].name + '<br/>' + params[0].marker + params[0].seriesName + ': Data tidak tersedia';
            }
            return params[0].name + '<br/>' + params[0].marker + params[0].seriesName + ': ' + Number(params[0].value).toFixed(2);
          }
        },
        grid: {
          left: gridOptions.left || '3%',
          right: gridOptions.right || '4%',
          bottom: gridOptions.bottom || '10%',
          top: gridOptions.top || '10%',
          containLabel: true
        },
        xAxis: {
          type: 'category',
          boundaryGap: false,
          data: labels,
          name: 'Tahun',
          nameLocation: 'middle',
          nameGap: 30,
          axisLabel: { rotate: 0, margin: 12 }
        },
        yAxis: {
          type: 'value',
          scale: true,
          boundaryGap: ['10%', '10%'],
          name: 'Nilai',
          nameLocation: 'end',
          nameGap: 15
        },
        series: [{
          name: title,
          type: 'line',
          smooth: 0.4,
          data: values,
          areaStyle: { color: rgbaColor },
          lineStyle: { color: color, width: 3 },
          itemStyle: { color: color, borderColor: '#fff', borderWidth: 2 },
          symbol: 'circle',
          symbolSize: 8
        }]
      });

      return chart;
    }

    function createSubIndicatorCharts(uhhSpData, hlsData, rlsData, pengeluaranData, indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData) {
      const charts = {
        uhhSp: createLineChart('uhhSpChart', uhhSpData, 'UHH SP', 'rgb(59, 130, 246)', { bottom: '10%' }),
        hls: createLineChart('hlsChart', hlsData, 'HLS', 'rgb(16, 185, 129)', { bottom: '10%', yMax: undefined }),
        rls: createLineChart('rlsChart', rlsData, 'RLS', 'rgb(245, 158, 11)', { bottom: '10%' }),
        pengeluaran: createLineChart('pengeluaranChart', pengeluaranData, 'Pengeluaran per Kapita', 'rgb(239, 68, 68)', { bottom: '10%' }),
        indeksKesehatan: createLineChart('indeksKesehatanChart', indeksKesehatanData, 'Indeks Kesehatan', 'rgb(139, 92, 246)', { bottom: '10%' }),
        indeksPendidikan: createLineChart('indeksPendidikanChart', indeksPendidikanData, 'Indeks Pendidikan', 'rgb(59, 130, 246)', { bottom: '10%' }),
        indeksHidupLayak: createLineChart('indeksHidupLayakChart', indeksHidupLayakData, 'Indeks Hidup Layak', 'rgb(16, 185, 129)', { bottom: '10%' })
      };

      window.chartInstances = window.chartInstances || {};
      Object.assign(window.chartInstances, charts);
    }

    function createCompositionChart(indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData, allYears, selectedYear) {
      const compositionChartDom = document.getElementById('compositionChart');
      if (!compositionChartDom || allYears.length === 0) return;
      
      const compositionChart = echarts.init(compositionChartDom);
      const latestYear = selectedYear || allYears[allYears.length - 1];
      
      // Get latest year data for each index
      const latestIndeksKesehatan = indeksKesehatanData.find(d => d.year === latestYear);
      const latestIndeksPendidikan = indeksPendidikanData.find(d => d.year === latestYear);
      const latestIndeksHidupLayak = indeksHidupLayakData.find(d => d.year === latestYear);

      const pieData = [];
      if (latestIndeksKesehatan && latestIndeksKesehatan.value !== null) {
        pieData.push({ name: 'Indeks Kesehatan', value: latestIndeksKesehatan.value });
      }
      if (latestIndeksHidupLayak && latestIndeksHidupLayak.value !== null) {
        pieData.push({ name: 'Indeks Hidup Layak', value: latestIndeksHidupLayak.value });
      }
      if (latestIndeksPendidikan && latestIndeksPendidikan.value !== null) {
        pieData.push({ name: 'Indeks Pendidikan', value: latestIndeksPendidikan.value });
      }
      
      if (pieData.length > 0) {
        window.chartData = window.chartData || {};
        window.chartData.composition = pieData;
        
        compositionChart.setOption({
          tooltip: {
            trigger: 'item',
            appendToBody: true,
            formatter: '{a} <br/>{b}: {c} ({d}%)'
          },
          legend: {
            data: pieData.map(item => item.name),
            bottom: 0,
            orient: 'horizontal',
            itemGap: 15,
            itemWidth: 12,
            itemHeight: 12,
            textStyle: { fontSize: 11 },
            type: 'scroll',
            width: '100%'
          },
          series: [{
            name: 'Komposisi IPM',
            type: 'pie',
            radius: ['40%', '75%'],
            center: ['50%', '45%'],
            avoidLabelOverlap: true,
            itemStyle: {
              borderRadius: 10,
              borderColor: '#fff',
              borderWidth: 2
            },
            label: { show: false },
            labelLine: { show: false },
            emphasis: { label: { show: false } },
            data: pieData,
            color: ['#8b5cf6', '#3b82f6', '#10b981']
          }]
        });
      } else {
        compositionChart.clear();
      }

      window.chartInstances = window.chartInstances || {};
      window.chartInstances.composition = compositionChart;
    }

    // ========== Calculate Carousel Comparisons ==========
    function calculateCarouselComparison(allData, containerId, isCurrency = false) {
      const containers = document.querySelectorAll(`#${containerId}`);
      if (!containers || containers.length === 0 || !allData || allData.length < 2) return;
      
      const validData = allData.filter(d => {
        if (!d || !d.year) return false;
        const val = d.value;
        if (val === null || val === undefined || val === '') return false;
        const numVal = parseFloat(val);
        return !isNaN(numVal) && isFinite(numVal);
      });
      
      if (validData.length < 2) return;
      
      const sortedData = [...validData].sort((a, b) => (a.year || 0) - (b.year || 0));
      const latest = sortedData[sortedData.length - 1];
      const previousYear = latest.year - 1;
      let previous = sortedData.find(d => d.year === previousYear);
      
      if (!previous && sortedData.length > 1) {
        const previousYears = sortedData.filter(d => d.year < latest.year);
        if (previousYears.length > 0) {
          previous = previousYears[previousYears.length - 1];
        }
      }
      
      if (!previous || previous.value === null || previous.value === undefined) return;
      
      const diff = parseFloat(latest.value) - parseFloat(previous.value);
      const arrow = diff > 0 ? '▲' : (diff < 0 ? '▼' : '-');
      const arrowColor = 'rgba(255, 255, 255, 0.9)';
      const valueColor = 'rgba(255, 255, 255, 0.9)';
      
      const diffFormatted = Math.abs(diff).toFixed(2);
      const comparisonHTML = isCurrency ? 
        `<span style="color: ${arrowColor}; font-size: 12px;">${arrow}</span>
         <span style="color: ${valueColor}; font-size: 12px; font-weight: 500;">${diff >= 0 ? '+' : ''}Rp ${diffFormatted} Juta</span>
         <span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari ${previous.year}</span>` :
        `<span style="color: ${arrowColor}; font-size: 12px;">${arrow}</span>
         <span style="color: ${valueColor}; font-size: 12px; font-weight: 500;">${diff >= 0 ? '+' : ''}${diffFormatted}</span>
         <span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari ${previous.year}</span>`;
      
      containers.forEach(container => {
        container.innerHTML = comparisonHTML;
      });
    }

    function calculateAllCarouselComparisons(uhhSpData, hlsData, rlsData, pengeluaranData, indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData) {
      const filteredUhhSp = uhhSpData.filter(d => d && d.year && d.value !== null && d.value !== undefined);
      const filteredHls = hlsData.filter(d => d && d.year && d.value !== null && d.value !== undefined);
      const filteredRls = rlsData.filter(d => d && d.year && d.value !== null && d.value !== undefined);
      const filteredPengeluaran = pengeluaranData.filter(d => d && d.year && d.value !== null && d.value !== undefined);
      const filteredIndeksKesehatan = indeksKesehatanData.filter(d => d && d.year && d.value !== null && d.value !== undefined);
      const filteredIndeksPendidikan = indeksPendidikanData.filter(d => d && d.year && d.value !== null && d.value !== undefined);
      const filteredIndeksHidupLayak = indeksHidupLayakData.filter(d => d && d.year && d.value !== null && d.value !== undefined);

      filteredUhhSp.sort((a, b) => (a.year || 0) - (b.year || 0));
      filteredHls.sort((a, b) => (a.year || 0) - (b.year || 0));
      filteredRls.sort((a, b) => (a.year || 0) - (b.year || 0));
      filteredPengeluaran.sort((a, b) => (a.year || 0) - (b.year || 0));
      filteredIndeksKesehatan.sort((a, b) => (a.year || 0) - (b.year || 0));
      filteredIndeksPendidikan.sort((a, b) => (a.year || 0) - (b.year || 0));
      filteredIndeksHidupLayak.sort((a, b) => (a.year || 0) - (b.year || 0));

      calculateCarouselComparison(filteredUhhSp, 'uhh-sp-comparison');
      calculateCarouselComparison(filteredHls, 'hls-comparison');
      calculateCarouselComparison(filteredRls, 'rls-comparison');
      calculateCarouselComparison(filteredPengeluaran, 'pengeluaran-comparison', true);
      calculateCarouselComparison(filteredIndeksKesehatan, 'indeks-kesehatan-comparison');
      calculateCarouselComparison(filteredIndeksPendidikan, 'indeks-pendidikan-comparison');
      calculateCarouselComparison(filteredIndeksHidupLayak, 'indeks-hidup-layak-comparison');
      
      setTimeout(() => {
        calculateCarouselComparison(filteredUhhSp, 'uhh-sp-comparison');
        calculateCarouselComparison(filteredHls, 'hls-comparison');
        calculateCarouselComparison(filteredRls, 'rls-comparison');
        calculateCarouselComparison(filteredPengeluaran, 'pengeluaran-comparison', true);
        calculateCarouselComparison(filteredIndeksKesehatan, 'indeks-kesehatan-comparison');
        calculateCarouselComparison(filteredIndeksPendidikan, 'indeks-pendidikan-comparison');
        calculateCarouselComparison(filteredIndeksHidupLayak, 'indeks-hidup-layak-comparison');
      }, 600);
    }

    // ========== Export Functions ==========
    function exportTrendToExcel() {
      if (!window.chartData || !window.chartData.surabaya || !window.chartData.jatim) return;
      
      const surabayaData = window.chartData.surabaya;
      const jatimData = window.chartData.jatim;
      const allYears = [...new Set([...surabayaData.map(d => d.year), ...jatimData.map(d => d.year)])].sort((a, b) => a - b);
      
      const exportData = [['Tahun', 'Kota Surabaya', 'Jawa Timur']];
      allYears.forEach(year => {
        const surabayaVal = surabayaData.find(d => d.year === year);
        const jatimVal = jatimData.find(d => d.year === year);
        const surabayaValue = surabayaVal && surabayaVal.ipm_value !== null && surabayaVal.ipm_value !== undefined ? Number(surabayaVal.ipm_value).toFixed(2) : 'Data tidak tersedia';
        const jatimValue = jatimVal && jatimVal.ipm_value !== null && jatimVal.ipm_value !== undefined ? Number(jatimVal.ipm_value).toFixed(2) : 'Data tidak tersedia';
        exportData.push([year.toString(), surabayaValue, jatimValue]);
      });
      
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 10 }, { wch: 25 }, { wch: 25 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Trend IPM');
      XLSX.writeFile(wb, `Trend_IPM_Surabaya_vs_JawaTimur_${new Date().toISOString().split('T')[0]}.xlsx`);
    }

    function exportSingleSeriesToExcel(data, chartName, unit = '') {
      const sortedData = [...data].sort((a, b) => a.year - b.year);
      const last5Years = sortedData.slice(-5);
      const exportData = [['Tahun', chartName + (unit ? ` (${unit})` : '')]];
      
      last5Years.forEach(item => {
        const value = item.value !== null ? item.value.toFixed(2) : 'Data tidak tersedia';
        exportData.push([item.year.toString(), value]);
      });
      
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 10 }, { wch: 25 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data');
      const filename = chartName.replace(/\s+/g, '_') + '_' + new Date().toISOString().split('T')[0] + '.xlsx';
      XLSX.writeFile(wb, filename);
    }

    function exportChartToPNG(chartInstance, filename) {
      if (!chartInstance) return;
      const url = chartInstance.getDataURL({
        type: 'png',
        pixelRatio: 2,
        backgroundColor: '#fff'
      });
      const link = document.createElement('a');
      link.download = filename;
      link.href = url;
      link.click();
    }

    // ========== Update initCharts to include carousel comparisons ==========
    const originalInitCharts = initCharts;
    initCharts = function(uhhSpData, hlsData, rlsData, surabayaData, jatimData, pengeluaranData, indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData) {
      originalInitCharts(uhhSpData, hlsData, rlsData, surabayaData, jatimData, pengeluaranData, indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData);
      calculateAllCarouselComparisons(uhhSpData, hlsData, rlsData, pengeluaranData, indeksKesehatanData, indeksPendidikanData, indeksHidupLayakData);
      
      // Store data globally for export
      window.chartData = {
        surabaya: surabayaData,
        jatim: jatimData,
        uhhSp: uhhSpData,
        hls: hlsData,
        rls: rlsData,
        pengeluaran: pengeluaranData,
        indeksKesehatan: indeksKesehatanData,
        indeksPendidikan: indeksPendidikanData,
        indeksHidupLayak: indeksHidupLayakData
      };
    };

    // ========== Setup Download Event Listeners ==========
    function setupDownloadListeners() {
      document.getElementById('downloadTrendExcel')?.addEventListener('click', function(e) {
        e.preventDefault();
        window.checkAuthBeforeDownload(() => {
          exportTrendToExcel();
        }, 'Tren Indeks Pembangunan Manusia');
      });

      document.getElementById('downloadTrendPNG')?.addEventListener('click', function(e) {
        e.preventDefault();
        window.checkAuthBeforeDownload(() => {
          if (window.chartInstances && window.chartInstances.trend) {
            exportChartToPNG(window.chartInstances.trend, `Tren_IPM_${new Date().toISOString().split('T')[0]}.png`);
          }
        }, 'Tren Indeks Pembangunan Manusia');
      });

      // Add more download listeners as needed
      const downloadMappings = [
        { excel: 'downloadUhhSpExcel', png: 'downloadUhhSpPNG', data: 'uhhSp', chart: 'uhhSp', name: 'UHH SP', unit: 'tahun' },
        { excel: 'downloadHlsExcel', png: 'downloadHlsPNG', data: 'hls', chart: 'hls', name: 'HLS', unit: 'tahun' },
        { excel: 'downloadRlsExcel', png: 'downloadRlsPNG', data: 'rls', chart: 'rls', name: 'RLS', unit: 'tahun' },
        { excel: 'downloadPengeluaranExcel', png: 'downloadPengeluaranPNG', data: 'pengeluaran', chart: 'pengeluaran', name: 'Pengeluaran per Kapita', unit: 'Rp' },
        { excel: 'downloadIndeksKesehatanExcel', png: 'downloadIndeksKesehatanPNG', data: 'indeksKesehatan', chart: 'indeksKesehatan', name: 'Indeks Kesehatan', unit: '' },
        { excel: 'downloadIndeksPendidikanExcel', png: 'downloadIndeksPendidikanPNG', data: 'indeksPendidikan', chart: 'indeksPendidikan', name: 'Indeks Pendidikan', unit: '' },
        { excel: 'downloadIndeksHidupLayakExcel', png: 'downloadIndeksHidupLayakPNG', data: 'indeksHidupLayak', chart: 'indeksHidupLayak', name: 'Indeks Hidup Layak', unit: '' }
      ];

      downloadMappings.forEach(mapping => {
        document.getElementById(mapping.excel)?.addEventListener('click', function(e) {
          e.preventDefault();
          window.checkAuthBeforeDownload(() => {
            exportSingleSeriesToExcel(window.chartData[mapping.data], mapping.name, mapping.unit);
          }, mapping.name);
        });

        document.getElementById(mapping.png)?.addEventListener('click', function(e) {
          e.preventDefault();
          window.checkAuthBeforeDownload(() => {
            if (window.chartInstances && window.chartInstances[mapping.chart]) {
              exportChartToPNG(window.chartInstances[mapping.chart], `${mapping.name.replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.png`);
            }
          }, mapping.name);
        });
      });

      // Composition chart download
      document.getElementById('downloadCompositionExcel')?.addEventListener('click', function(e) {
        e.preventDefault();
        window.checkAuthBeforeDownload(() => {
          if (window.chartData && window.chartData.composition && window.chartData.composition.length > 0) {
            const exportData = [['Indikator', 'Nilai']];
            window.chartData.composition.forEach(item => {
              exportData.push([item.name, Number(item.value).toFixed(2)]);
            });
            
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet(exportData);
            ws['!cols'] = [{ wch: 25 }, { wch: 15 }];
            XLSX.utils.book_append_sheet(wb, ws, 'Data Komposisi');
            XLSX.writeFile(wb, `Komposisi_IPM_${new Date().toISOString().split('T')[0]}.xlsx`);
          } else {
            alert('Data komposisi belum tersedia');
          }
        }, 'Komposisi IPM');
      });

      document.getElementById('downloadCompositionPNG')?.addEventListener('click', function(e) {
        e.preventDefault();
        window.checkAuthBeforeDownload(() => {
          if (window.chartInstances && window.chartInstances.composition) {
            exportChartToPNG(window.chartInstances.composition, `Komposisi_IPM_${new Date().toISOString().split('T')[0]}.png`);
          }
        }, 'Komposisi IPM');
      });
    }

    // Handle window resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(function() {
        if (window.chartInstances) {
          Object.values(window.chartInstances).forEach(chart => {
            if (chart && typeof chart.resize === 'function') {
              chart.resize();
            }
          });
        }
      }, 150);
    });

    // Initialize
    loadAllIPMData();
    setupDownloadListeners();
  });