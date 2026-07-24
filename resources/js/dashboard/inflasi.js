// Month mapping for display
const monthNames = {
  'JANUARI': 'Jan', 'FEBRUARI': 'Feb', 'MARET': 'Mar', 'APRIL': 'Apr',
  'MEI': 'Mei', 'JUNI': 'Jun', 'JULI': 'Jul', 'AGUSTUS': 'Agu',
  'SEPTEMBER': 'Sep', 'OKTOBER': 'Okt', 'NOPEMBER': 'Nov', 'DESEMBER': 'Des'
};

const monthNamesFull = {
  'JANUARI': 'Januari', 'FEBRUARI': 'Februari', 'MARET': 'Maret', 'APRIL': 'April',
  'MEI': 'Mei', 'JUNI': 'Juni', 'JULI': 'Juli', 'AGUSTUS': 'Agustus',
  'SEPTEMBER': 'September', 'OKTOBER': 'Oktober', 'NOPEMBER': 'November', 'DESEMBER': 'Desember'
};

// Month order for sorting
const monthOrder = ['JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 
                    'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOPEMBER', 'DESEMBER'];

// Helper function to get month display name
function getMonthDisplay(month) {
  const monthMap = {
    'JANUARI': 'Januari', 'FEBRUARI': 'Februari', 'MARET': 'Maret', 'APRIL': 'April',
    'MEI': 'Mei', 'JUNI': 'Juni', 'JULI': 'Juli', 'AGUSTUS': 'Agustus',
    'SEPTEMBER': 'September', 'OKTOBER': 'Oktober', 'NOPEMBER': 'November', 'DESEMBER': 'Desember'
  };
  return monthMap[month] || month;
}

// API Routes


// Global variables
let allInflasiData = [];
let selectedYear = null; // Default to latest year
let selectedKomoditas = {
  tahun: null,
  umum: null,
  sub: null,
  spesifik: null
};

// Initialize charts (will be initialized after DOM is ready)
let mtoMChart = null;
let yonYChart = null;
let perKomoditasChart = null;

// --- Dropdown Management JS ---
document.addEventListener('click', function(event) {
  const dropdowns = [
    { toggleId: 'filterYearToggle', menuId: 'filterYearDropdown' },
    { toggleId: 'filterKomoditasTahunInput', menuId: 'filterKomoditasTahunDropdown' },
    { toggleId: 'filterKomoditasInput', menuId: 'filterKomoditasDropdown' }
  ];

  const clickedEl = event.target;
  
  // Check if clicked on a toggle
  let activeToggle = null;
  dropdowns.forEach(d => {
    const toggle = document.getElementById(d.toggleId);
    if (toggle && toggle.contains(clickedEl)) {
      activeToggle = d;
    }
  });

  if (activeToggle) {
    // Close all other dropdowns
    dropdowns.forEach(d => {
      if (d.toggleId !== activeToggle.toggleId) {
        const menu = document.getElementById(d.menuId);
        if (menu) menu.classList.remove('show');
      }
    });
  } else {
    // If clicked outside, close all if not clicking inside a menu
    dropdowns.forEach(d => {
      const menu = document.getElementById(d.menuId);
      if (menu && !menu.contains(clickedEl)) {
        // Special case for filterYear which doesn't use the body-append logic
        if (d.toggleId === 'filterYearToggle') {
          menu.style.display = 'none';
          menu.classList.remove('show');
        }
      }
    });
  }
}, true); // Use capture phase to run before other listeners
// --- End Dropdown Management JS ---

// Load data on page load
document.addEventListener('DOMContentLoaded', function() {
  // Initialize charts only after DOM is ready
  const mtoMChartElement = document.getElementById('inflasiMtoMChart');
  const yonYChartElement = document.getElementById('inflasiYonYChart');
  const perKomoditasChartElement = document.getElementById('inflasiPerKomoditasChart');
  
  if (mtoMChartElement) {
    mtoMChart = echarts.init(mtoMChartElement);
  }
  if (yonYChartElement) {
    yonYChart = echarts.init(yonYChartElement);
  }
  if (perKomoditasChartElement) {
    perKomoditasChart = echarts.init(perKomoditasChartElement);
  }
  
  // Update chart instances reference for export functions
  updateChartInstances();
  
  // Auto-resize charts when window size changes
  // NOTE: Debounced resize handler is set up below (line ~615). No duplicate listener needed here.

  // Listen for sidebar toggle to resize charts
  const sidebarToggle = document.getElementById('sidebarToggle');
  if (sidebarToggle) {
    sidebarToggle.addEventListener('click', function() {
      // Wait for sidebar transition to complete (usually 300ms)
      setTimeout(function() {
        if (mtoMChart) {
          mtoMChart.resize();
        }
        if (yonYChart) {
          yonYChart.resize();
        }
        if (perKomoditasChart) {
          perKomoditasChart.resize();
        }
      }, 350);
    });
  }
  
  // Use ResizeObserver for more accurate container size detection
  // This will handle sidebar toggle, responsive changes, etc.
  if (typeof ResizeObserver !== 'undefined') {
    // Observe MtoM Chart
    if (mtoMChartElement) {
      const mtoMObserver = new ResizeObserver(function() {
        if (mtoMChart) {
          setTimeout(function() {
            mtoMChart.resize();
          }, 100);
        }
      });
      mtoMObserver.observe(mtoMChartElement.parentElement);
    }
    
    // Observe YonY Chart
    if (yonYChartElement) {
      const yonYObserver = new ResizeObserver(function() {
        if (yonYChart) {
          setTimeout(function() {
            yonYChart.resize();
          }, 100);
        }
      });
      yonYObserver.observe(yonYChartElement.parentElement);
    }
    
    // Observe Komoditas Chart
    if (perKomoditasChartElement) {
      const komoditasObserver = new ResizeObserver(function() {
        if (perKomoditasChart) {
          setTimeout(function() {
            perKomoditasChart.resize();
          }, 100);
        }
      });
      komoditasObserver.observe(perKomoditasChartElement.parentElement);
    }
  }
  
  // Load data first, then setup filters
  loadInflasiSummary();
  loadInflasiData();
  loadYears();
  setupYearFilter();
  setupKomoditasFilter();
  loadKomoditasExplanation();

  // Debounce function for resize events
  function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  // Handle window resize for all charts with debounce
  const debouncedResize = debounce(function() {
    if (mtoMChart) mtoMChart.resize();
    if (yonYChart) yonYChart.resize();
    if (perKomoditasChart) perKomoditasChart.resize();
  }, 250);

  window.addEventListener('resize', debouncedResize);
});

// Load inflasi summary data
function loadInflasiSummary() {
  window.fetchAPIWithCache(window.APP_CONFIG.routes.inflasiSummary)
    .then(data => {
      if (data.status === 'success' && data.data) {
        const summary = data.data;
        updateSummaryCards(summary);
      } else {
        console.warn('No inflasi summary data available');
      }
    })
    .catch(error => {
      console.error('Error loading inflasi summary:', error);
    });
}

// Update summary cards
function updateSummaryCards(summary) {
  const latest = summary.latest;
  const previousMonth = summary.previous_month;
  const previousYear = summary.previous_year;
  const mToMChange = summary.m_to_m_change;
  const yOnYChange = summary.y_on_y_change;

  // Update M-to-M card
  if (latest) {
    const bulanan = latest.bulanan !== null ? parseFloat(latest.bulanan).toFixed(2) + '%' : '-';
    document.getElementById('m-to-m-value').textContent = bulanan;
    
    const monthDisplay = getMonthDisplay(latest.month);
    document.getElementById('m-to-m-date').textContent = `${monthDisplay} ${latest.year}`;
    
    // Update change indicator
    const changeEl = document.getElementById('m-to-m-change');
    changeEl.innerHTML = '';
    if (mToMChange !== null) {
      if (mToMChange > 0) {
        changeEl.innerHTML = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 13px;">▲</span>
          <span style="color: rgba(255, 255, 255, 0.9); font-size: 13px;">+${mToMChange.toFixed(2)}%</span>`;
        if (previousMonth) {
          const prevMonthDisplay = getMonthDisplay(previousMonth.month).toLowerCase();
          changeEl.innerHTML += `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari bulan ${prevMonthDisplay}</span>`;
        }
      } else if (mToMChange < 0) {
        changeEl.innerHTML = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 13px;">▼</span>
          <span style="color: rgba(255, 255, 255, 0.9); font-size: 13px;">${mToMChange.toFixed(2)}%</span>`;
        if (previousMonth) {
          const prevMonthDisplay = getMonthDisplay(previousMonth.month).toLowerCase();
          changeEl.innerHTML += `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari bulan ${prevMonthDisplay}</span>`;
        }
      } else {
        changeEl.innerHTML = '<span style="color: rgba(255, 255, 255, 0.9); font-size: 13px;">-</span>';
      }
    }
  } else {
    document.getElementById('m-to-m-value').textContent = '-';
    document.getElementById('m-to-m-date').textContent = 'Data tidak tersedia';
  }

  // Update Y-on-Y card
  if (latest) {
    const yoy = latest.yoy !== null ? parseFloat(latest.yoy).toFixed(2) + '%' : '-';
    document.getElementById('y-on-y-value').textContent = yoy;
    
    const monthDisplay = getMonthDisplay(latest.month);
    document.getElementById('y-on-y-date').textContent = `${monthDisplay} ${latest.year}`;
    
    // Update change indicator
    const changeEl = document.getElementById('y-on-y-change');
    changeEl.innerHTML = '';
    if (yOnYChange !== null) {
      if (yOnYChange > 0) {
        changeEl.innerHTML = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 13px;">▲</span>
          <span style="color: rgba(255, 255, 255, 0.9); font-size: 13px;">+${yOnYChange.toFixed(2)}%</span>`;
        if (previousYear) {
          const prevMonthDisplay = getMonthDisplay(previousYear.month).toLowerCase();
          changeEl.innerHTML += `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari bulan ${prevMonthDisplay} ${previousYear.year}</span>`;
        }
      } else if (yOnYChange < 0) {
        changeEl.innerHTML = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 13px;">▼</span>
          <span style="color: rgba(255, 255, 255, 0.9); font-size: 13px;">${yOnYChange.toFixed(2)}%</span>`;
        if (previousYear) {
          const prevMonthDisplay = getMonthDisplay(previousYear.month).toLowerCase();
          changeEl.innerHTML += `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari bulan ${prevMonthDisplay} ${previousYear.year}</span>`;
        }
      } else {
        changeEl.innerHTML = '<span style="color: rgba(255, 255, 255, 0.9); font-size: 13px;">-</span>';
      }
    }
  } else {
    document.getElementById('y-on-y-value').textContent = '-';
    document.getElementById('y-on-y-date').textContent = 'Data tidak tersedia';
  }

  // Update Kumulatif card
  if (latest) {
    const kumulatif = latest.kumulatif !== null ? parseFloat(latest.kumulatif).toFixed(2) + '%' : '-';
    document.getElementById('kumulatif-value').textContent = kumulatif;
    
    const monthDisplay = getMonthDisplay(latest.month);
    document.getElementById('kumulatif-date').textContent = `Januari - ${monthDisplay} ${latest.year}`;
  } else {
    document.getElementById('kumulatif-value').textContent = '-';
    document.getElementById('kumulatif-date').textContent = 'Data tidak tersedia';
  }
}

// Load years for filter
function loadYears() {
  window.fetchAPIWithCache(window.APP_CONFIG.routes.inflasiYears)
    .then(data => {
      if (data.status === 'success' && data.data && data.data.length > 0) {
        const years = data.data;
        const latestYear = years[0];
        selectedYear = latestYear;
        
        // Update filter year dropdown
        const dropdown = document.getElementById('filterYearDropdown');
        const select = document.getElementById('filterYear');
        
        // Clear existing options except Default
        const defaultItem = dropdown.querySelector('[data-value=""]');
        dropdown.innerHTML = '';
        if (defaultItem) {
          dropdown.appendChild(defaultItem);
        } else {
          const defaultDiv = document.createElement('div');
          defaultDiv.className = 'dropdown-item';
          defaultDiv.setAttribute('data-value', '');
          defaultDiv.style.cssText = 'padding: 8px 12px; cursor: pointer; background-color: #f0f0f0;';
          defaultDiv.setAttribute('data-selected', 'true');
          defaultDiv.textContent = 'Default';
          dropdown.appendChild(defaultDiv);
        }
        
        // Add year options
        years.slice(0, 5).forEach(year => {
          if (year != latestYear) {
            const div = document.createElement('div');
            div.className = 'dropdown-item';
            div.setAttribute('data-value', year);
            div.style.cssText = 'padding: 8px 12px; cursor: pointer;';
            div.textContent = year;
            dropdown.appendChild(div);
            
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            select.appendChild(option);
          }
        });
        
        if (years.length > 5) {
          years.slice(5).forEach(year => {
            const div = document.createElement('div');
            div.className = 'dropdown-item';
            div.setAttribute('data-value', year);
            div.style.cssText = 'padding: 8px 12px; cursor: pointer;';
            div.textContent = year;
            dropdown.appendChild(div);
            
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            select.appendChild(option);
          });
        }
        
        // Update info text
        document.getElementById('filterYearInfo').textContent = `Default menampilkan tahun terbaru (${latestYear})`;
      }
    })
    .catch(error => {
      console.error('Error loading years:', error);
    });
}

// Load inflasi general data
function loadInflasiData() {
  const url = selectedYear ? `${window.APP_CONFIG.routes.inflasi}?year=${selectedYear}` : window.APP_CONFIG.routes.inflasi;
  
  window.fetchAPIWithCache(url)
    .then(data => {
      if (data.status === 'success' && data.data && data.data.length > 0) {
        allInflasiData = data.data;
        updateChartData();
        // Render charts after data is loaded
        if (mtoMChart) {
          renderMtoMChart();
        }
        if (yonYChart) {
          renderYonYChart();
        }
      } else {
        console.warn('No inflasi data available');
        // Show empty charts
        if (mtoMChart) {
          mtoMChart.setOption({
            title: {
              text: 'Data tidak tersedia',
              left: 'center',
              top: 'center',
              textStyle: { color: '#999', fontSize: 14 }
            }
          });
        }
        if (yonYChart) {
          yonYChart.setOption({
            title: {
              text: 'Data tidak tersedia',
              left: 'center',
              top: 'center',
              textStyle: { color: '#999', fontSize: 14 }
            }
          });
        }
      }
    })
    .catch(error => {
      console.error('Error loading inflasi data:', error);
      // Show error message in charts
      if (mtoMChart) {
        mtoMChart.setOption({
          title: {
            text: 'Error memuat data',
            left: 'center',
            top: 'center',
            textStyle: { color: '#dc3545', fontSize: 14 }
          }
        });
      }
      if (yonYChart) {
        yonYChart.setOption({
          title: {
            text: 'Error memuat data',
            left: 'center',
            top: 'center',
            textStyle: { color: '#dc3545', fontSize: 14 }
          }
        });
      }
    });
}

// Setup year filter
function setupYearFilter() {
  const filterYear = document.getElementById('filterYear');
  const filterYearToggle = document.getElementById('filterYearToggle');
  const filterYearDropdown = document.getElementById('filterYearDropdown');
  const filterYearDisplay = document.getElementById('filterYearDisplay');
  const dropdownItems = filterYearDropdown.querySelectorAll('.dropdown-item');

  // Toggle dropdown
  filterYearToggle.addEventListener('click', function(e) {
    e.stopPropagation();
    const isOpen = filterYearDropdown.style.display === 'block';
    
    if (isOpen) {
      filterYearDropdown.style.display = 'none';
      filterYearDropdown.classList.remove('show');
    } else {
      filterYearDropdown.style.display = 'block';
      filterYearDropdown.classList.add('show');
    }
    const chevron = filterYearToggle.querySelector('i');
    if (chevron) {
      chevron.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
      chevron.style.transition = 'transform 0.3s ease';
    }
  });

  // Handle item selection using event delegation
  filterYearDropdown.addEventListener('click', function(e) {
    const item = e.target.closest('.dropdown-item');
    if (!item) return;
    
    e.stopPropagation();
    const value = item.getAttribute('data-value');
    const text = item.textContent.trim();
    
    // Update hidden select
    filterYear.value = value || '';
    selectedYear = value || null;
    
    // Update display
    filterYearDisplay.textContent = text;
    
    // Update selected state - remove selection from all items first
    filterYearDropdown.querySelectorAll('.dropdown-item').forEach(i => {
      const wasSelected = i.getAttribute('data-selected') === 'true';
      if (wasSelected) {
        i.style.setProperty('background-color', '', 'important');
        i.setAttribute('data-selected', 'false');
      }
    });
    // Set new selection
    item.style.setProperty('background-color', '#f0f0f0', 'important');
    item.setAttribute('data-selected', 'true');
    
    // Close dropdown
    filterYearDropdown.classList.remove('show');
    const chevron = filterYearToggle.querySelector('i');
    if (chevron) {
      chevron.style.transform = 'rotate(0deg)';
    }
    
    // Update charts
    loadInflasiData();
  });

  // Close dropdown when clicking outside
  document.addEventListener('click', function(e) {
    if (!filterYearToggle.contains(e.target) && !filterYearDropdown.contains(e.target)) {
      filterYearDropdown.style.display = 'none';
      filterYearDropdown.classList.remove('show');
      const chevron = filterYearToggle.querySelector('i');
      if (chevron) {
        chevron.style.transform = 'rotate(0deg)';
      }
    }
  });

  // Also handle change on hidden select for compatibility
  filterYear.addEventListener('change', function() {
    selectedYear = this.value || null;
    loadInflasiData();
  });
}

// Render Month-to-Month Chart
function renderMtoMChart() {
  if (!mtoMChart) {
    console.error('mtoMChart is not initialized');
    return;
  }
  
  // Check if data is available
  if (!allInflasiData || allInflasiData.length === 0) {
    console.warn('No data available for MtoM chart');
    return;
  }
  
  // Filter by selectedYear if set, otherwise use all data
  let filteredData = allInflasiData;
  if (selectedYear) {
    filteredData = allInflasiData.filter(d => d.year == selectedYear);
  } else {
    // If no year selected, use latest year
    const latestYear = Math.max(...allInflasiData.map(d => d.year));
    filteredData = allInflasiData.filter(d => d.year == latestYear);
  }
  
  if (filteredData.length === 0) {
    console.warn('No data available for selected year');
    mtoMChart.setOption({
      title: {
        text: 'Data tidak tersedia',
        left: 'center',
        top: 'center',
        textStyle: { color: '#999', fontSize: 14 }
      }
    });
    return;
  }

  const sortedData = filteredData.sort((a, b) => {
    if (a.year !== b.year) return a.year - b.year;
    return monthOrder.indexOf(a.month) - monthOrder.indexOf(b.month);
  });

  const labels = sortedData.map(d => `${monthNames[d.month]} ${d.year}`);
  const values = sortedData.map(d => (d.bulanan !== null && d.bulanan !== undefined) ? parseFloat(d.bulanan) : null);

  const option = {
    tooltip: {
      trigger: 'axis',
      formatter: function(params) {
        const param = params[0];
        return `${param.axisValue}<br/>${param.seriesName}: ${param.value !== null ? param.value.toFixed(2) + '%' : '-'}`;
      }
    },
    grid: {
      left: '3%',
      right: '8%',
      bottom: '15%',
      containLabel: true
    },
    xAxis: {
      type: 'category',
      boundaryGap: false,
      data: labels,
      axisLabel: {
        rotate: labels.length > 6 ? 45 : 0,
        interval: labels.length <= 12 ? 0 : Math.max(0, Math.floor(labels.length / 12)),
        margin: 8,
        formatter: function(value) {
          // Show only month abbreviation since we always filter by year
          return value.split(' ')[0]; // Only month
        }
      }
    },
    yAxis: {
      type: 'value',
      name: 'Inflasi (%)',
      axisLabel: {
        formatter: '{value}%'
      }
    },
    series: [{
      name: 'Inflasi Bulanan',
      type: 'line',
      smooth: false,
      data: values,
      itemStyle: {
        color: '#3b82f6'
      },
      areaStyle: {
        color: {
          type: 'linear',
          x: 0,
          y: 0,
          x2: 0,
          y2: 1,
          colorStops: [{
            offset: 0, color: 'rgba(59, 130, 246, 0.3)'
          }, {
            offset: 1, color: 'rgba(59, 130, 246, 0.05)'
          }]
        }
      }
    }]
  };

  mtoMChart.setOption(option, true); // Use notMerge=true to replace existing option
  
  // Resize chart to ensure proper rendering
  setTimeout(() => {
    if (mtoMChart) {
      mtoMChart.resize();
    }
  }, 100);
}

// Render Year-on-Year Chart
function renderYonYChart() {
  if (!yonYChart) {
    console.error('yonYChart is not initialized');
    return;
  }
  
  // Check if data is available
  if (!allInflasiData || allInflasiData.length === 0) {
    console.warn('No data available for YoY chart');
    return;
  }
  
  // Filter by selectedYear if set, otherwise use all data
  let filteredData = allInflasiData;
  if (selectedYear) {
    filteredData = allInflasiData.filter(d => d.year == selectedYear);
  } else {
    // If no year selected, use latest year
    const latestYear = Math.max(...allInflasiData.map(d => d.year));
    filteredData = allInflasiData.filter(d => d.year == latestYear);
  }
  
  if (filteredData.length === 0) {
    console.warn('No data available for selected year');
    yonYChart.setOption({
      title: {
        text: 'Data tidak tersedia',
        left: 'center',
        top: 'center',
        textStyle: { color: '#999', fontSize: 14 }
      }
    });
    return;
  }

  const sortedData = filteredData.sort((a, b) => {
    if (a.year !== b.year) return a.year - b.year;
    return monthOrder.indexOf(a.month) - monthOrder.indexOf(b.month);
  });

  const labels = sortedData.map(d => `${monthNames[d.month]} ${d.year}`);
  const values = sortedData.map(d => (d.yoy !== null && d.yoy !== undefined) ? parseFloat(d.yoy) : null);

  // If all values are null, show "Data tidak tersedia"
  if (values.every(v => v === null)) {
    yonYChart.clear();
    yonYChart.setOption({
      title: {
        text: 'Data YoY tidak tersedia',
        left: 'center',
        top: 'center',
        textStyle: { color: '#999', fontSize: 14 }
      }
    });
    return;
  }

  // Clear previous options to prevent overlay issues
  yonYChart.clear();
  
  const option = {
    tooltip: {
      trigger: 'axis',
      formatter: function(params) {
        const param = params[0];
        return `${param.axisValue}<br/>${param.seriesName}: ${param.value !== null ? param.value.toFixed(2) + '%' : '-'}`;
      }
    },
    grid: {
      left: '3%',
      right: '8%',
      bottom: '15%',
      containLabel: true
    },
    xAxis: {
      type: 'category',
      boundaryGap: false,
      data: labels,
      axisLabel: {
        rotate: labels.length > 6 ? 45 : 0,
        interval: labels.length <= 12 ? 0 : Math.max(0, Math.floor(labels.length / 12)),
        margin: 8,
        formatter: function(value) {
          // Show only month abbreviation since we always filter by year
          return value.split(' ')[0]; // Only month
        }
      }
    },
    yAxis: {
      type: 'value',
      name: 'Inflasi (%)',
      axisLabel: {
        formatter: '{value}%'
      }
    },
    series: [{
      name: 'Inflasi YoY',
      type: 'line',
      smooth: false,
      data: values,
      itemStyle: {
        color: '#10b981'
      },
      areaStyle: {
        color: {
          type: 'linear',
          x: 0,
          y: 0,
          x2: 0,
          y2: 1,
          colorStops: [{
            offset: 0, color: 'rgba(16, 185, 129, 0.3)'
          }, {
            offset: 1, color: 'rgba(16, 185, 129, 0.05)'
          }]
        }
      }
    }]
  };

  yonYChart.setOption(option, true); // Use notMerge=true to replace existing option
  
  // Resize chart to ensure proper rendering
  setTimeout(() => {
    if (yonYChart) {
      yonYChart.resize();
    }
  }, 100);
}

// Setup komoditas filter (multiple selection - semua komoditas dalam satu filter)
function setupKomoditasFilter() {
  // Get all filter elements
  const filterTahunInput = document.getElementById('filterKomoditasTahunInput');
  const filterTahunPlaceholder = document.getElementById('filterKomoditasTahunPlaceholder');
  const filterTahunSelected = document.getElementById('filterKomoditasTahunSelected');
  const filterTahunDropdown = document.getElementById('filterKomoditasTahunDropdown');
  const filterTahunWrapper = document.getElementById('filterKomoditasTahunWrapper');
  
  const filterKomoditasInput = document.getElementById('filterKomoditasInput');
  const filterKomoditasPlaceholder = document.getElementById('filterKomoditasPlaceholder');
  const filterKomoditasSearch = document.getElementById('filterKomoditasSearch');
  const filterKomoditasTags = document.getElementById('filterKomoditasTags');
  const filterKomoditasDropdown = document.getElementById('filterKomoditasDropdown');
  const filterKomoditasWrapper = document.getElementById('filterKomoditasWrapper');
  const filterKomoditasClear = document.getElementById('filterKomoditasClear');
  const filterKomoditasChevron = document.getElementById('filterKomoditasChevron');
  
  const btnCari = document.getElementById('btnCariKomoditas');
  const btnClear = document.getElementById('btnClearKomoditas');

  // Check if all required elements exist
  if (!filterTahunInput || !filterTahunDropdown || !filterKomoditasInput || !filterKomoditasDropdown || !btnCari || !btnClear || !filterKomoditasSearch || !filterKomoditasPlaceholder) {
    console.error('Required filter elements not found');
    return;
  }

  // Store selected commodities (array of {code, name, flag})
  let selectedKomoditasList = [];
  let allCommoditiesList = []; // Store all commodities for search

  // Load komoditas years
  window.fetchAPIWithCache(window.APP_CONFIG.routes.komoditasYears)
    .then(data => {
      if (data.status === 'success' && data.data && data.data.length > 0) {
        const years = data.data;
        filterTahunDropdown.innerHTML = '';
        years.forEach(year => {
          const div = document.createElement('div');
          div.className = 'filter-option-tahun';
          div.setAttribute('data-value', year);
          div.style.cssText = 'padding: 10px 12px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #f0f0f0;';
          div.textContent = year;
          filterTahunDropdown.appendChild(div);
        });
      }
    })
    .catch(error => {
      console.error('Error loading komoditas years:', error);
    });

  // Setup toggle for tahun dropdown
  function initTahunDropdownToggle() {
    filterTahunInput.addEventListener('click', function(e) {
      e.stopPropagation();
      e.preventDefault();
      
      const isOpen = filterTahunDropdown.classList.contains('show');
      if (isOpen) {
        filterTahunDropdown.style.display = 'none';
        filterTahunDropdown.classList.remove('show');
      } else {
        if (filterTahunDropdown.parentNode !== filterTahunWrapper) {
          filterTahunWrapper.appendChild(filterTahunDropdown);
        }
        
        filterTahunDropdown.style.position = 'absolute';
        filterTahunDropdown.style.top = '100%';
        filterTahunDropdown.style.left = '0';
        filterTahunDropdown.style.right = '0';
        filterTahunDropdown.style.marginTop = '4px';
        
        filterTahunDropdown.style.display = 'block';
        filterTahunDropdown.classList.add('show');
        filterTahunDropdown.style.zIndex = '900';
      }
    });
  }

  // Setup toggle for komoditas dropdown
  function initKomoditasDropdownToggle() {
    filterKomoditasInput.addEventListener('click', function(e) {
      if (e.target === filterKomoditasSearch || e.target.closest('#filterKomoditasTags') || e.target === filterKomoditasClear || e.target === filterKomoditasChevron) {
        return;
      }
      
      e.stopPropagation();
      e.preventDefault();
      
      const isVisible = filterKomoditasDropdown.classList.contains('show');
      
      if (!isVisible) {
        showKomoditasDropdown();
      } else {
        hideKomoditasDropdown();
      }
    });
  }

  // Show komoditas dropdown
  function showKomoditasDropdown() {
    if (filterKomoditasDropdown.parentNode !== filterKomoditasWrapper) {
      filterKomoditasWrapper.appendChild(filterKomoditasDropdown);
    }
    
    filterKomoditasDropdown.style.position = 'absolute';
    filterKomoditasDropdown.style.top = '100%';
    filterKomoditasDropdown.style.left = '0';
    filterKomoditasDropdown.style.right = '0';
    filterKomoditasDropdown.style.marginTop = '4px';
    filterKomoditasDropdown.style.zIndex = '900';
    filterKomoditasDropdown.style.maxHeight = '300px';
    filterKomoditasDropdown.style.overflowY = 'auto';
    
    filterKomoditasDropdown.style.display = 'block';
    filterKomoditasDropdown.classList.add('show');
    
    showSearchInput();
    
    const searchTerm = filterKomoditasSearch.value.trim();
    if (allCommoditiesList.length > 0) {
      renderKomoditasDropdown(allCommoditiesList, searchTerm);
    }
  }

  // Hide komoditas dropdown
  function hideKomoditasDropdown() {
    filterKomoditasDropdown.style.display = 'none';
    filterKomoditasDropdown.classList.remove('show');
  }

  // Setup click outside handler for tahun dropdown
  function setupTahunClickOutside() {
    document.addEventListener('click', function(e) {
      if (filterTahunDropdown && filterTahunWrapper && filterTahunDropdown.classList.contains('show')) {
        const clickedInsideWrapper = filterTahunWrapper.contains(e.target);
        const clickedInsideDropdown = filterTahunDropdown.contains(e.target);
        
        if (!clickedInsideWrapper && !clickedInsideDropdown) {
          filterTahunDropdown.style.display = 'none';
          filterTahunDropdown.classList.remove('show');
        }
      }
    });
  }

  // Setup click outside handler for komoditas dropdown - FIXED VERSION
  function setupKomoditasClickOutside() {
    document.addEventListener('click', function(e) {
      if (filterKomoditasDropdown && filterKomoditasWrapper && filterKomoditasDropdown.classList.contains('show')) {
        const clickedInsideWrapper = filterKomoditasWrapper.contains(e.target);
        const clickedInsideDropdown = filterKomoditasDropdown.contains(e.target);
        
        if (!clickedInsideWrapper && !clickedInsideDropdown) {
          hideKomoditasDropdown();
        }
      }
    });
  }

  // Setup search input handler
  function setupSearchInput() {
    // Search on input
    filterKomoditasSearch.addEventListener('input', function(e) {
      const searchTerm = e.target.value.trim();
      updateClearButton();
      
      // Show dropdown if not visible
      if (!filterKomoditasDropdown.classList.contains('show')) {
        showKomoditasDropdown();
      }
      
      // Filter and render dropdown
      if (allCommoditiesList.length > 0) {
        renderKomoditasDropdown(allCommoditiesList, searchTerm);
      }
    });

    // Focus on search input
    filterKomoditasSearch.addEventListener('focus', function() {
      if (allCommoditiesList.length > 0) {
        showKomoditasDropdown();
      }
    });

    // Prevent input click from closing dropdown
    filterKomoditasSearch.addEventListener('click', function(e) {
      e.stopPropagation();
      if (allCommoditiesList.length > 0 && !filterKomoditasDropdown.classList.contains('show')) {
        showKomoditasDropdown();
      }
    });

    // Hide search input when blur if empty
    filterKomoditasSearch.addEventListener('blur', function() {
      setTimeout(() => {
        if (filterKomoditasSearch.value.trim() === '' && selectedKomoditasList.length === 0) {
          hideSearchInput();
        }
      }, 200);
    });

    // Handle keyboard navigation
    filterKomoditasSearch.addEventListener('keydown', function(e) {
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        const firstOption = filterKomoditasDropdown.querySelector('.filter-option-komoditas');
        if (firstOption) {
          firstOption.focus();
        }
      } else if (e.key === 'Escape') {
        hideKomoditasDropdown();
        filterKomoditasSearch.blur();
      }
    });

    // Clear button handler
    filterKomoditasClear.addEventListener('click', function(e) {
      e.stopPropagation();
      filterKomoditasSearch.value = '';
      updateClearButton();
      
      // Re-render dropdown without filter
      if (allCommoditiesList.length > 0) {
        renderKomoditasDropdown(allCommoditiesList, '');
      }
      
      // Hide search input if no tags
      if (selectedKomoditasList.length === 0) {
        hideSearchInput();
      } else {
        filterKomoditasSearch.focus();
      }
    });

    // Chevron click handler
    filterKomoditasChevron.addEventListener('click', function(e) {
      e.stopPropagation();
      const isVisible = filterKomoditasDropdown.classList.contains('show');
      if (isVisible) {
        hideKomoditasDropdown();
      } else {
        showKomoditasDropdown();
      }
    });
  }

  // Function to load all komoditas (umum, sub, spesifik) in one dropdown
  function loadAllKomoditas(year = null) {
    filterKomoditasDropdown.innerHTML = '<div style="padding: 10px 12px; color: #6c757d;">Memuat...</div>';
    
    // Load all flags in parallel
    const promises = [];
    
    // Load Flag 1 (Umum)
    let url1 = `${window.APP_CONFIG.routes.komoditasByFlag}?flag=1`;
    if (year) url1 += `&year=${year}`;
    promises.push(window.fetchAPIWithCache(url1).then(d => ({ flag: '1', data: d })).catch(e => ({ flag: '1', data: { status: 'error', data: [] } })));
    
    // Load Flag 2 (Sub) - load all sub komoditas
    let url2 = `${window.APP_CONFIG.routes.komoditasByFlag}?flag=2`;
    if (year) url2 += `&year=${year}`;
    promises.push(window.fetchAPIWithCache(url2).then(d => ({ flag: '2', data: d })).catch(e => ({ flag: '2', data: { status: 'error', data: [] } })));
    
    // Load Flag 3 (Spesifik)
    let url3 = `${window.APP_CONFIG.routes.komoditasByFlag}?flag=3`;
    if (year) url3 += `&year=${year}`;
    promises.push(window.fetchAPIWithCache(url3).then(d => ({ flag: '3', data: d })).catch(e => ({ flag: '3', data: { status: 'error', data: [] } })));
    
    Promise.all(promises)
      .then(results => {
        filterKomoditasDropdown.innerHTML = '';
        
        const allCommodities = [];
        
        // Process Flag 1 (Umum)
        if (results[0].data.status === 'success' && results[0].data.data && results[0].data.data.length > 0) {
          const uniqueUmum = {};
          results[0].data.data.forEach(k => {
            if (!uniqueUmum[k.commodity_code]) {
              uniqueUmum[k.commodity_code] = k.commodity_name;
            }
          });
          Object.entries(uniqueUmum).forEach(([code, name]) => {
            allCommodities.push({ code, name, flag: '1', type: 'Umum' });
          });
        }
        
        // Process Flag 2 (Sub)
        if (results[1].data.status === 'success' && results[1].data.data && results[1].data.data.length > 0) {
          results[1].data.data.forEach(k => {
            allCommodities.push({ code: k.commodity_code, name: k.commodity_name, flag: '2', type: 'Sub' });
          });
        }
        
        // Process Flag 3 (Spesifik)
        if (results[2].data.status === 'success' && results[2].data.data && results[2].data.data.length > 0) {
          results[2].data.data.forEach(k => {
            allCommodities.push({ code: k.commodity_code, name: k.commodity_name, flag: '3', type: 'Spesifik' });
          });
        }
        
        // Store all commodities for search
        allCommoditiesList = allCommodities;
        
        // Render dropdown with current search term
        renderKomoditasDropdown(allCommodities, filterKomoditasSearch.value.trim());
      })
      .catch(error => {
        console.error('Error loading komoditas:', error);
        filterKomoditasDropdown.innerHTML = '<div style="padding: 10px 12px; color: #dc3545;">Error memuat data</div>';
        allCommoditiesList = [];
      });
  }

  // Function to render dropdown with search filter
  function renderKomoditasDropdown(commodities, searchTerm = '') {
    filterKomoditasDropdown.innerHTML = '';
    
    // Filter commodities based on search term
    let filteredCommodities = commodities;
    if (searchTerm) {
      const searchLower = searchTerm.toLowerCase();
      filteredCommodities = commodities.filter(c => 
        c.code.toLowerCase().includes(searchLower) || 
        c.name.toLowerCase().includes(searchLower)
      );
    }
    
    if (filteredCommodities.length === 0) {
      filterKomoditasDropdown.innerHTML = '<div style="padding: 10px 12px; color: #6c757d;">Tidak ada hasil ditemukan</div>';
      return;
    }
    
    // Sort by code
    filteredCommodities.sort((a, b) => a.code.localeCompare(b.code));
    
    // Group by type for better organization
    const grouped = {
      'Umum': filteredCommodities.filter(c => c.type === 'Umum'),
      'Sub': filteredCommodities.filter(c => c.type === 'Sub'),
      'Spesifik': filteredCommodities.filter(c => c.type === 'Spesifik')
    };
    
    // Add section headers and options
    ['Umum', 'Sub', 'Spesifik'].forEach(type => {
      if (grouped[type].length > 0) {
        // Add section header
        const header = document.createElement('div');
        header.className = 'komoditas-section-header';
        header.style.cssText = 'padding: 8px 12px; background-color: #f8f9fa; font-weight: 600; font-size: 12px; color: #666; border-bottom: 1px solid #e0e0e0; position: sticky; top: 0; z-index: 10;';
        header.textContent = `Komoditas ${type}`;
        filterKomoditasDropdown.appendChild(header);
        
        // Add options
        grouped[type].forEach(commodity => {
          const option = document.createElement('div');
          option.className = 'filter-option-komoditas';
          option.setAttribute('data-value', commodity.code);
          option.setAttribute('data-name', commodity.name);
          option.setAttribute('data-flag', commodity.flag);
          option.setAttribute('data-type', commodity.type);
          option.style.cssText = 'padding: 10px 12px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #f0f0f0; padding-left: 24px;';
          
          // Format: [CODE] Name
          option.innerHTML = `<span style="color: #666; font-weight: 500;">[${commodity.code}]</span> <span>${commodity.name}</span>`;
          
          // Check if already selected
          if (selectedKomoditasList.find(c => c.code === commodity.code)) {
            option.classList.add('selected');
          }
          
          filterKomoditasDropdown.appendChild(option);
        });
      }
    });
  }

  // Update tags display
  function updateKomoditasTags() {
    filterKomoditasTags.innerHTML = '';
    
    // Show/hide placeholder and tags based on selection
    if (selectedKomoditasList.length > 0) {
      filterKomoditasPlaceholder.classList.remove('show');
      filterKomoditasTags.style.display = 'flex';
    } else {
      // Only show placeholder if search input is hidden
      if (filterKomoditasSearch.style.display === 'none' || filterKomoditasSearch.value.trim() === '') {
        filterKomoditasPlaceholder.style.display = 'inline';
      } else {
        filterKomoditasPlaceholder.classList.remove('show');
      }
      filterKomoditasTags.classList.remove('show');
    }
    
    selectedKomoditasList.forEach(commodity => {
      const tag = document.createElement('span');
      tag.className = 'filter-tag';
      tag.style.cssText = 'display: inline-flex; align-items: center; background-color: #e7f3ff; color: #0066cc; padding: 4px 8px; border-radius: 4px; font-size: 12px; gap: 6px; white-space: nowrap;';
      tag.innerHTML = `
        <span>[${commodity.code}] ${commodity.name}</span>
        <button type="button" class="tag-remove" data-value="${commodity.code}" style="background: none; border: none; color: #0066cc; cursor: pointer; font-size: 16px; line-height: 1; padding: 0; margin-left: 4px;">×</button>
      `;
      
      // Handle tag removal
      const removeBtn = tag.querySelector('.tag-remove');
      removeBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        const code = this.getAttribute('data-value');
        selectedKomoditasList = selectedKomoditasList.filter(c => c.code !== code);
        
        // Update option selection state
        const option = filterKomoditasDropdown.querySelector(`[data-value="${code}"]`);
        if (option) {
          option.classList.remove('selected');
        }
        
        updateKomoditasTags();
        updateClearButton();
        checkFilterValidity();
      });
      
      filterKomoditasTags.appendChild(tag);
    });
  }

  // Update clear button visibility
  function updateClearButton() {
    if (selectedKomoditasList.length > 0 || filterKomoditasSearch.value.trim() !== '') {
      filterKomoditasClear.style.display = 'block';
      filterKomoditasClear.classList.add('show');
    } else {
      filterKomoditasClear.style.display = 'none';
      filterKomoditasClear.classList.remove('show');
    }
  }

  // Show search input and hide placeholder
  function showSearchInput() {
    if (selectedKomoditasList.length === 0) {
      filterKomoditasPlaceholder.style.display = 'none';
      filterKomoditasPlaceholder.classList.remove('show');
    }
    filterKomoditasSearch.style.display = 'block';
    filterKomoditasSearch.classList.add('show');
    setTimeout(() => {
      filterKomoditasSearch.focus();
    }, 10);
  }

  // Hide search input and show placeholder if no tags
  function hideSearchInput() {
    if (selectedKomoditasList.length === 0 && filterKomoditasSearch.value.trim() === '') {
      filterKomoditasPlaceholder.style.display = 'inline';
    }
    filterKomoditasSearch.style.display = 'none';
    filterKomoditasSearch.classList.remove('show');
    filterKomoditasSearch.value = '';
  }

  // Check if filter is valid and enable/disable button
  function checkFilterValidity() {
    const hasCommodity = selectedKomoditasList.length > 0;
    btnCari.disabled = !hasCommodity;
  }

  // Initialize dropdown toggles (only once)
  initTahunDropdownToggle();
  initKomoditasDropdownToggle();

  // Setup click outside handlers
  setupTahunClickOutside();
  setupKomoditasClickOutside();

  // Setup search input
  setupSearchInput();

  // Setup Tahun dropdown options (inline event delegation)
  filterTahunDropdown.addEventListener('click', function(e) {
    e.stopPropagation();
    const option = e.target.closest('.filter-option-tahun');
    if (!option) return;

    const value = option.getAttribute('data-value');
    const text = option.textContent.trim();

    // Remove selected state from all options
    filterTahunDropdown.querySelectorAll('.filter-option-tahun').forEach(opt => opt.classList.remove('selected'));
    // Set selected state
    option.classList.add('selected');

    // Update display
    filterTahunPlaceholder.style.display = 'none';
    filterTahunSelected.style.display = 'inline';
    filterTahunSelected.textContent = option.textContent;

    // Close dropdown
    filterTahunDropdown.style.display = 'none';
    filterTahunDropdown.classList.remove('show');

    // Update state and reload komoditas
    selectedKomoditas.tahun = value;
    loadAllKomoditas(value);
    checkFilterValidity();
  });

  // Setup Komoditas dropdown options (multiple selection)
  filterKomoditasDropdown.addEventListener('click', function(e) {
    e.stopPropagation();
    const option = e.target.closest('.filter-option-komoditas');
    if (!option) return;
    
    e.stopPropagation();
    const code = option.getAttribute('data-value');
    const name = option.getAttribute('data-name');
    const flag = option.getAttribute('data-flag');
    const type = option.getAttribute('data-type');
    
    // Toggle selection
    const existingIndex = selectedKomoditasList.findIndex(c => c.code === code);
    if (existingIndex >= 0) {
      // Remove from selection
      selectedKomoditasList.splice(existingIndex, 1);
      option.classList.remove('selected');
    } else {
      // Add to selection
      selectedKomoditasList.push({ code, name, flag, type });
      option.classList.add('selected');
    }
    
    updateKomoditasTags();
    updateClearButton();
    checkFilterValidity();
    
    // Clear search and keep dropdown open
    filterKomoditasSearch.value = '';
    updateClearButton();
    renderKomoditasDropdown(allCommoditiesList, '');
  });

  // Initially load all komoditas (all years) - use setTimeout to ensure DOM is ready
  setTimeout(function() {
    loadAllKomoditas();
  }, 100);

  // Apply button
  btnCari.addEventListener('click', function() {
    if (selectedKomoditasList.length > 0) {
      loadMultipleKomoditasChart(selectedKomoditasList, selectedKomoditas.tahun);
      showSelectedKomoditasInfo();
    }
  });

  // Clear button
  btnClear.addEventListener('click', function() {
    // Reset all selections
    selectedKomoditas.tahun = null;
    selectedKomoditasList = [];
    
    // Reset UI
    filterTahunPlaceholder.style.display = 'inline';
    filterTahunSelected.style.display = 'none';
    filterTahunSelected.textContent = '';
    filterTahunDropdown.querySelectorAll('.filter-option-tahun').forEach(opt => opt.classList.remove('selected'));
    
    filterKomoditasTags.innerHTML = '';
    filterKomoditasSearch.value = '';
    filterKomoditasDropdown.querySelectorAll('.filter-option-komoditas').forEach(opt => opt.classList.remove('selected'));
    updateClearButton();
    hideKomoditasDropdown();
    hideSearchInput();
    
    btnCari.disabled = true;
    document.getElementById('selectedKomoditasInfo').style.display = 'none';
    document.getElementById('komoditasChartSection').style.display = 'none';
    
    // Reload all komoditas (all years)
    loadAllKomoditas();
    
    // Pastikan dropdown tetap di wrapper
    if (filterTahunDropdown.parentNode !== filterTahunWrapper) {
      filterTahunWrapper.appendChild(filterTahunDropdown);
    }
    if (filterKomoditasDropdown.parentNode !== filterKomoditasWrapper) {
      filterKomoditasWrapper.appendChild(filterKomoditasDropdown);
    }
  });

  function showSelectedKomoditasInfo() {
    let text = '';
    if (selectedKomoditas.tahun) text += `Tahun: ${selectedKomoditas.tahun}`;
    if (selectedKomoditasList.length > 0) {
      text += ` | Komoditas: ${selectedKomoditasList.map(c => c.name).join(', ')}`;
    }
    
    document.getElementById('selectedKomoditasText').textContent = text;
    document.getElementById('selectedKomoditasInfo').style.display = 'block';
  }
}

// Load multiple komoditas chart
function loadMultipleKomoditasChart(commodities, year) {
  if (!commodities || commodities.length === 0) {
    alert('Pilih komoditas terlebih dahulu.');
    return;
  }

  // Load data for all commodities in parallel
  const promises = commodities.map(commodity => {
    let url = `${window.APP_CONFIG.routes.inflasiPerKomoditas}?commodity_code=${commodity.code}`;
    if (year) {
      url += `&year=${year}`;
    }
    return window.fetchAPIWithCache(url)
      .then(data => ({
        commodity: commodity,
        data: data.status === 'success' ? data.data : []
      }))
      .catch(error => {
        console.error(`Error loading data for ${commodity.name}:`, error);
        return { commodity: commodity, data: [] };
      });
  });

  Promise.all(promises)
    .then(results => {
      // Filter out commodities with no data
      const validResults = results.filter(r => r.data.length > 0);
      
      if (validResults.length === 0) {
        alert('Data untuk komoditas yang dipilih tidak tersedia.');
        return;
      }

      renderMultipleKomoditasChart(validResults, year);
      document.getElementById('komoditasChartSection').style.display = 'block';
    })
    .catch(error => {
      console.error('Error loading multiple komoditas chart:', error);
      alert('Terjadi kesalahan saat memuat data.');
    });
}

// Load single komoditas chart (kept for backward compatibility)
function loadKomoditasChart(commodityCode, commodityName, year) {
  let url = `${window.APP_CONFIG.routes.inflasiPerKomoditas}?commodity_code=${commodityCode}`;
  if (year) {
    url += `&year=${year}`;
  }

  window.fetchAPIWithCache(url)
    .then(data => {
      if (data.status === 'success' && data.data.length > 0) {
        renderKomoditasChart(data.data, commodityName);
        document.getElementById('komoditasChartSection').style.display = 'block';
      } else {
        alert('Data untuk komoditas ini tidak tersedia.');
      }
    })
    .catch(error => {
      console.error('Error loading komoditas chart:', error);
      alert('Terjadi kesalahan saat memuat data.');
    });
}

// Render Multiple Komoditas Chart
function renderMultipleKomoditasChart(results, year) {
  if (!perKomoditasChart) {
    console.error('perKomoditasChart is not initialized');
    return;
  }

  // Process all data
  const allData = {};
  const commodityNames = [];
  const colors = ['#8b5cf6', '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#06b6d4', '#ec4899', '#84cc16', '#6366f1', '#14b8a6'];
  
  results.forEach((result, index) => {
    const commodity = result.commodity;
    const data = result.data;
    
    if (data.length > 0) {
      commodityNames.push(commodity.name);
      
      // Sort data
      const sortedData = data.sort((a, b) => {
        if (a.year !== b.year) return a.year - b.year;
        return monthOrder.indexOf(a.month) - monthOrder.indexOf(b.month);
      });
      
      // Create key for each month-year combination
      sortedData.forEach(d => {
        const key = `${d.year}-${d.month}`;
        if (!allData[key]) {
          allData[key] = { year: d.year, month: d.month, monthName: monthNames[d.month] };
        }
        if (!allData[key][commodity.code]) {
          allData[key][commodity.code] = (d.value !== null && d.value !== undefined) ? parseFloat(d.value) : null;
        }
      });
    }
  });

  if (commodityNames.length === 0) {
    perKomoditasChart.clear();
    perKomoditasChart.setOption({
      title: {
        text: 'Data tidak tersedia',
        left: 'center',
        top: 'center',
        textStyle: { color: '#6c757d', fontSize: 14, fontWeight: 'normal' }
      }
    });
    return;
  }

  // Get all unique month-year combinations and sort
  const labels = Object.keys(allData)
    .sort((a, b) => {
      const [yearA, monthA] = a.split('-');
      const [yearB, monthB] = b.split('-');
      if (yearA !== yearB) return yearA - yearB;
      return monthOrder.indexOf(monthA) - monthOrder.indexOf(monthB);
    })
    .map(key => {
      const data = allData[key];
      return `${data.monthName} ${data.year}`;
    });

  // Create series for each commodity
  const series = results.map((result, index) => {
    const commodity = result.commodity;
    const values = labels.map((label, labelIndex) => {
      const key = Object.keys(allData).sort((a, b) => {
        const [yearA, monthA] = a.split('-');
        const [yearB, monthB] = b.split('-');
        if (yearA !== yearB) return yearA - yearB;
        return monthOrder.indexOf(monthA) - monthOrder.indexOf(monthB);
      })[labelIndex];
      return allData[key] && allData[key][commodity.code] !== undefined ? allData[key][commodity.code] : null;
    });

    return {
      name: commodity.name,
      type: 'line',
      smooth: false,
      data: values,
      symbol: 'circle',
      symbolSize: 6,
      lineStyle: {
        width: 2,
        color: colors[index % colors.length]
      },
      itemStyle: {
        color: colors[index % colors.length],
        borderWidth: 2,
        borderColor: '#fff'
      },
      emphasis: {
        itemStyle: {
          borderWidth: 3,
          shadowBlur: 10,
          shadowColor: 'rgba(0, 0, 0, 0.3)'
        },
        lineStyle: {
          width: 3
        }
      }
    };
  });

  // Store data for export
  const exportData = [];
  labels.forEach((label, index) => {
    const key = Object.keys(allData).sort((a, b) => {
      const [yearA, monthA] = a.split('-');
      const [yearB, monthB] = b.split('-');
      if (yearA !== yearB) return yearA - yearB;
      return monthOrder.indexOf(monthA) - monthOrder.indexOf(monthB);
    })[index];
    const row = { label, year: allData[key].year, month: allData[key].month };
    results.forEach(result => {
      row[result.commodity.name] = allData[key][result.commodity.code];
    });
    exportData.push(row);
  });

  window.chartData.komoditas = {
    data: exportData,
    name: commodityNames.join(', '),
    year: year,
    commodities: results.map(r => r.commodity)
  };

  const chartTitle = commodityNames.length === 1 
    ? `Inflasi ${commodityNames[0]}` 
    : `Inflasi Multiple Komoditas (${commodityNames.length})`;
  document.getElementById('komoditasChartTitle').textContent = chartTitle;

  const needsRotation = labels.length > 6;
  const bottomPadding = needsRotation ? '15%' : '10%';

  const option = {
    tooltip: {
      trigger: 'axis',
      backgroundColor: 'rgba(50, 50, 50, 0.9)',
      borderColor: '#8b5cf6',
      borderWidth: 1,
      textStyle: {
        color: '#fff',
        fontSize: 12
      }
    },
    legend: {
      data: commodityNames,
      top: '5%',
      textStyle: {
        fontSize: 11
      }
    },
    grid: {
      left: '8%',
      right: '4%',
      top: '15%',
      bottom: bottomPadding,
      containLabel: true
    },
    xAxis: {
      type: 'category',
      boundaryGap: false,
      data: labels,
      axisLine: {
        lineStyle: {
          color: '#e0e0e0'
        }
      },
      axisLabel: {
        rotate: needsRotation ? 45 : 0,
        fontSize: 11,
        color: '#666',
        margin: needsRotation ? 15 : 8
      },
      splitLine: {
        show: false
      }
    },
    yAxis: {
      type: 'value',
      name: 'Inflasi (%)',
      nameLocation: 'middle',
      nameGap: 50,
      nameTextStyle: {
        fontSize: 12,
        color: '#666'
      },
      axisLine: {
        lineStyle: {
          color: '#e0e0e0'
        }
      },
      axisLabel: {
        formatter: '{value}%',
        fontSize: 11,
        color: '#666'
      },
      splitLine: {
        lineStyle: {
          color: '#f0f0f0',
          type: 'dashed'
        }
      }
    },
    series: series
  };

  setTimeout(() => {
    if (perKomoditasChart) {
      perKomoditasChart.resize();
      perKomoditasChart.setOption(option, true);
    }
  }, 100);
}

// Render Komoditas Chart (single)
function renderKomoditasChart(data, komoditasName) {
  if (!perKomoditasChart) {
    console.error('perKomoditasChart is not initialized');
    return;
  }
  
  if (!data || data.length === 0) {
    perKomoditasChart.clear();
    perKomoditasChart.setOption({
      title: {
        text: 'Data tidak tersedia',
        left: 'center',
        top: 'center',
        textStyle: { color: '#6c757d', fontSize: 14, fontWeight: 'normal' }
      }
    });
    return;
  }
  
  const sortedData = data.sort((a, b) => {
    if (a.year !== b.year) return a.year - b.year;
    return monthOrder.indexOf(a.month) - monthOrder.indexOf(b.month);
  });

  // Check if data is from single year
  const uniqueYears = [...new Set(sortedData.map(d => d.year))];
  const isSingleYear = uniqueYears.length === 1;

  const labels = sortedData.map(d => `${monthNames[d.month]} ${d.year}`);
  const values = sortedData.map(d => (d.value !== null && d.value !== undefined) ? parseFloat(d.value) : null);

  // Store data for export
  window.chartData.komoditas = {
    data: sortedData,
    name: komoditasName,
    year: uniqueYears.length > 0 ? uniqueYears[0] : null
  };

  document.getElementById('komoditasChartTitle').textContent = `Inflasi ${komoditasName}`;

  // Calculate dynamic bottom padding based on label rotation
  const needsRotation = labels.length > 6;
  const bottomPadding = needsRotation ? '15%' : '10%';

  const option = {
    tooltip: {
      trigger: 'axis',
      backgroundColor: 'rgba(50, 50, 50, 0.9)',
      borderColor: '#8b5cf6',
      borderWidth: 1,
      textStyle: {
        color: '#fff',
        fontSize: 12
      },
      formatter: function(params) {
        const param = params[0];
        const monthFull = sortedData[param.dataIndex] ? sortedData[param.dataIndex].month : '';
        const year = sortedData[param.dataIndex] ? sortedData[param.dataIndex].year : '';
        return `<strong>${monthFull} ${year}</strong><br/>${param.seriesName}: ${param.value !== null ? param.value.toFixed(2) + '%' : '-'}`;
      }
    },
    grid: {
      left: '8%',
      right: '4%',
      top: '10%',
      bottom: bottomPadding,
      containLabel: true
    },
    xAxis: {
      type: 'category',
      boundaryGap: false,
      data: labels,
      axisLine: {
        lineStyle: {
          color: '#e0e0e0'
        }
      },
      axisLabel: {
        rotate: needsRotation ? 45 : 0,
        fontSize: 11,
        color: '#666',
        formatter: function(value) {
          // Show only month abbreviation if single year, or month + year if multiple years
          if (isSingleYear) {
            return value.split(' ')[0]; // Only month
          }
          return value;
        },
        margin: needsRotation ? 15 : 8
      },
      splitLine: {
        show: false
      }
    },
    yAxis: {
      type: 'value',
      name: 'Inflasi (%)',
      nameLocation: 'middle',
      nameGap: 50,
      nameTextStyle: {
        fontSize: 12,
        color: '#666'
      },
      axisLine: {
        lineStyle: {
          color: '#e0e0e0'
        }
      },
      axisLabel: {
        formatter: '{value}%',
        fontSize: 11,
        color: '#666'
      },
      splitLine: {
        lineStyle: {
          color: '#f0f0f0',
          type: 'dashed'
        }
      }
    },
    series: [{
      name: `Inflasi ${komoditasName}`,
      type: 'line',
      smooth: false,
      data: values,
      symbol: 'circle',
      symbolSize: 6,
      lineStyle: {
        width: 2,
        color: '#8b5cf6'
      },
      itemStyle: {
        color: '#8b5cf6',
        borderWidth: 2,
        borderColor: '#fff'
      },
      emphasis: {
        itemStyle: {
          color: '#7c3aed',
          borderColor: '#8b5cf6',
          borderWidth: 3,
          shadowBlur: 10,
          shadowColor: 'rgba(139, 92, 246, 0.5)'
        }
      }
    }]
  };

  // Resize chart to ensure proper rendering
  setTimeout(() => {
    if (perKomoditasChart) {
      perKomoditasChart.resize();
      perKomoditasChart.setOption(option, true);
    }
  }, 100);
}

// Load komoditas explanation
function loadKomoditasExplanation() {
  window.fetchAPIWithCache(window.APP_CONFIG.routes.inflasiKomoditasTree)
    .then(data => {
      if (data.status === 'success' && data.data && data.data.length > 0) {
        let html = '<div class="row">';
        
        data.data.forEach((result, index) => {
          const colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899', '#84cc16'];
          const colorIndex = index % colors.length;
          const borderColor = colors[colorIndex];
          
          html += `
            <div class="col-md-6 col-lg-4 mb-4">
              <div class="card h-100 shadow-sm" style="border-left: 4px solid ${borderColor}; border-radius: 8px; transition: transform 0.2s, box-shadow 0.2s;" 
                   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(0,0,0,0.15)';" 
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)';">
                <div class="card-body" style="padding: 20px;">
                  <h6 style="color: ${borderColor}; font-weight: 600; margin-bottom: 15px; font-size: 15px; line-height: 1.4;">
                    <i class="fas fa-box me-2" style="font-size: 14px;"></i>${result.umum.name}
                  </h6>
                  ${result.sub.length > 0 ? `
                    <div style="margin-bottom: 12px;">
                      <span style="font-size: 12px; color: #666; font-weight: 500; background: #f8f9fa; padding: 4px 10px; border-radius: 12px; display: inline-block;">
                        <i class="fas fa-list me-1"></i>${result.sub.length} Sub Komoditas
                      </span>
                    </div>
                    <div style="max-height: 200px; overflow-y: auto; padding-right: 5px;">
                      <ul style="font-size: 13px; color: #555; margin-left: 20px; margin-bottom: 0; line-height: 1.8;">
                        ${result.sub.map(s => `<li style="margin-bottom: 4px;">${s}</li>`).join('')}
                      </ul>
                    </div>
                  ` : `
                    <p style="font-size: 12px; color: #999; margin-bottom: 0; font-style: italic;">
                      <i class="fas fa-info-circle me-1"></i>Tidak ada sub komoditas
                    </p>
                  `}
                </div>
              </div>
            </div>
          `;
        });
        
        html += '</div>';
        document.getElementById('komoditasExplanation').innerHTML = html;
      } else {
        document.getElementById('komoditasExplanation').innerHTML = 
          '<div class="alert alert-info"><i class="fas fa-info-circle me-2"></i>Data komoditas belum tersedia. Silakan sinkronisasi data terlebih dahulu.</div>';
      }
    })
    .catch(error => {
      console.error('Error loading sub komoditas:', error);
      document.getElementById('komoditasExplanation').innerHTML = 
        '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i>Terjadi kesalahan saat memuat penjelasan komoditas.</div>';
    });
}

// ========== Export Functions ==========

// Store chart instances and data globally for export functions
window.chartInstances = {
  mtoM: null,
  yonY: null,
  komoditas: null
};

window.chartData = {
  mtoM: [],
  yonY: [],
  komoditas: { data: [], name: '', year: null }
};

// Update chart data when charts are rendered
function updateChartData() {
  if (allInflasiData && allInflasiData.length > 0) {
    // If no year selected, use latest year from data
    let yearToFilter = selectedYear;
    if (!yearToFilter && allInflasiData.length > 0) {
      yearToFilter = Math.max(...allInflasiData.map(d => d.year));
    }
    const filteredData = yearToFilter ? allInflasiData.filter(d => d.year == yearToFilter) : allInflasiData;
    const sortedData = filteredData.sort((a, b) => {
      if (a.year !== b.year) return a.year - b.year;
      return monthOrder.indexOf(a.month) - monthOrder.indexOf(b.month);
    });
    
    window.chartData.mtoM = sortedData.map(d => ({
      year: d.year,
      month: d.month,
      value: (d.bulanan !== null && d.bulanan !== undefined) ? parseFloat(d.bulanan) : null
    }));
    
    window.chartData.yonY = sortedData.map(d => ({
      year: d.year,
      month: d.month,
      value: (d.yoy !== null && d.yoy !== undefined) ? parseFloat(d.yoy) : null
    }));
  }
}

// Update chart instances reference after initialization
function updateChartInstances() {
  if (window.chartInstances) {
    window.chartInstances.mtoM = mtoMChart;
    window.chartInstances.yonY = yonYChart;
    window.chartInstances.komoditas = perKomoditasChart;
  }
}

// Export MtoM Chart to Excel
function exportMtoMToExcel() {
  if (!window.chartData.mtoM || window.chartData.mtoM.length === 0) {
    alert('Data belum tersedia. Silakan tunggu hingga grafik dimuat.');
    return;
  }
  
  const exportData = [['Bulan', 'Tahun', 'Inflasi Bulan ke Bulan (%)']];
  const monthNamesFull = {
    'JANUARI': 'Januari', 'FEBRUARI': 'Februari', 'MARET': 'Maret', 'APRIL': 'April',
    'MEI': 'Mei', 'JUNI': 'Juni', 'JULI': 'Juli', 'AGUSTUS': 'Agustus',
    'SEPTEMBER': 'September', 'OKTOBER': 'Oktober', 'NOPEMBER': 'November', 'DESEMBER': 'Desember'
  };
  
  window.chartData.mtoM.forEach(item => {
    const monthName = monthNamesFull[item.month] || item.month;
    const value = item.value !== null ? item.value.toFixed(2) : 'Data tidak tersedia';
    exportData.push([monthName, item.year.toString(), value]);
  });
  
  const wb = XLSX.utils.book_new();
  const ws = XLSX.utils.aoa_to_sheet(exportData);
  ws['!cols'] = [{ wch: 15 }, { wch: 10 }, { wch: 25 }];
  XLSX.utils.book_append_sheet(wb, ws, 'Data Inflasi MtoM');
  XLSX.writeFile(wb, `Tren_Inflasi_Bulan_ke_Bulan_MoM_ke_Bulan_${selectedYear || 'All'}_${new Date().toISOString().split('T')[0]}.xlsx`);
}

// Export YoY Chart to Excel
function exportYonYToExcel() {
  if (!window.chartData.yonY || window.chartData.yonY.length === 0) {
    alert('Data belum tersedia. Silakan tunggu hingga grafik dimuat.');
    return;
  }
  
  const exportData = [['Bulan', 'Tahun', 'Inflasi Tahun ke Tahun (%)']];
  const monthNamesFull = {
    'JANUARI': 'Januari', 'FEBRUARI': 'Februari', 'MARET': 'Maret', 'APRIL': 'April',
    'MEI': 'Mei', 'JUNI': 'Juni', 'JULI': 'Juli', 'AGUSTUS': 'Agustus',
    'SEPTEMBER': 'September', 'OKTOBER': 'Oktober', 'NOPEMBER': 'November', 'DESEMBER': 'Desember'
  };
  
  window.chartData.yonY.forEach(item => {
    const monthName = monthNamesFull[item.month] || item.month;
    const value = item.value !== null ? item.value.toFixed(2) : 'Data tidak tersedia';
    exportData.push([monthName, item.year.toString(), value]);
  });
  
  const wb = XLSX.utils.book_new();
  const ws = XLSX.utils.aoa_to_sheet(exportData);
  ws['!cols'] = [{ wch: 15 }, { wch: 10 }, { wch: 25 }];
  XLSX.utils.book_append_sheet(wb, ws, 'Data Inflasi YoY');
  XLSX.writeFile(wb, `Tren_Inflasi_Tahun_ke_Tahun_YoY_ke_Tahun_${selectedYear || 'All'}_${new Date().toISOString().split('T')[0]}.xlsx`);
}

// Export Komoditas Chart to Excel
function exportKomoditasToExcel() {
  if (!window.chartData.komoditas || !window.chartData.komoditas.data || window.chartData.komoditas.data.length === 0) {
    alert('Data belum tersedia. Silakan pilih komoditas terlebih dahulu.');
    return;
  }
  
  const monthNamesFull = {
    'JANUARI': 'Januari', 'FEBRUARI': 'Februari', 'MARET': 'Maret', 'APRIL': 'April',
    'MEI': 'Mei', 'JUNI': 'Juni', 'JULI': 'Juli', 'AGUSTUS': 'Agustus',
    'SEPTEMBER': 'September', 'OKTOBER': 'Oktober', 'NOPEMBER': 'November', 'DESEMBER': 'Desember'
  };
  
  // Check if multiple commodities
  if (window.chartData.komoditas.commodities && window.chartData.komoditas.commodities.length > 1) {
    // Multiple commodities export
    const headers = ['Bulan', 'Tahun'];
    window.chartData.komoditas.commodities.forEach(c => {
      headers.push(`Inflasi ${c.name} (%)`);
    });
    const exportData = [headers];
    
    window.chartData.komoditas.data.forEach(item => {
      const monthName = monthNamesFull[item.month] || item.month;
      const row = [monthName, item.year.toString()];
      window.chartData.komoditas.commodities.forEach(c => {
        const value = item[c.name] !== null && item[c.name] !== undefined 
          ? parseFloat(item[c.name]).toFixed(2) 
          : 'Data tidak tersedia';
        row.push(value);
      });
      exportData.push(row);
    });
    
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.aoa_to_sheet(exportData);
    ws['!cols'] = [{ wch: 15 }, { wch: 10 }, ...window.chartData.komoditas.commodities.map(() => ({ wch: 30 }))];
    const safeName = 'Multiple_Komoditas';
    XLSX.utils.book_append_sheet(wb, ws, 'Data Inflasi Komoditas');
    XLSX.writeFile(wb, `Inflasi_${safeName}_${window.chartData.komoditas.year || ''}_${new Date().toISOString().split('T')[0]}.xlsx`);
  } else {
    // Single commodity export (backward compatibility)
    const exportData = [['Bulan', 'Tahun', `Inflasi ${window.chartData.komoditas.name} (%)`]];
    
    window.chartData.komoditas.data.forEach(item => {
      const monthName = monthNamesFull[item.month] || item.month;
      const value = item.value !== null ? parseFloat(item.value).toFixed(2) : 'Data tidak tersedia';
      exportData.push([monthName, item.year.toString(), value]);
    });
    
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.aoa_to_sheet(exportData);
    ws['!cols'] = [{ wch: 15 }, { wch: 10 }, { wch: 30 }];
    const safeName = window.chartData.komoditas.name.replace(/[^a-zA-Z0-9]/g, '_');
    XLSX.utils.book_append_sheet(wb, ws, 'Data Inflasi Komoditas');
    XLSX.writeFile(wb, `Inflasi_${safeName}_${window.chartData.komoditas.year || ''}_${new Date().toISOString().split('T')[0]}.xlsx`);
  }
}

// Export chart to PNG
function exportChartToPNG(chartInstance, filename) {
  if (!chartInstance) {
    alert('Grafik belum tersedia. Silakan tunggu hingga grafik dimuat.');
    return;
  }
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

// Helper function to check authentication before download

// Add event listeners for download buttons
document.addEventListener('DOMContentLoaded', function() {
  // MtoM Chart
  document.getElementById('downloadMtoMExcel')?.addEventListener('click', function() {
    window.checkAuthBeforeDownload(exportMtoMToExcel, 'Tren Inflasi Bulan ke Bulan (MoM)');
  });
  document.getElementById('downloadMtoMPNG')?.addEventListener('click', function() {
    window.checkAuthBeforeDownload(() => {
      exportChartToPNG(window.chartInstances.mtoM, `Tren_Inflasi_Bulan_ke_Bulan_MoM_ke_Bulan_${selectedYear || 'All'}_${new Date().toISOString().split('T')[0]}.png`);
    }, 'grafik inflasi bulan ke bulan');
  });
  
  // YoY Chart
  document.getElementById('downloadYonYExcel')?.addEventListener('click', function() {
    window.checkAuthBeforeDownload(exportYonYToExcel, 'Tren Inflasi Tahun ke Tahun (YoY)');
  });
  document.getElementById('downloadYonYPNG')?.addEventListener('click', function() {
    window.checkAuthBeforeDownload(() => {
      exportChartToPNG(window.chartInstances.yonY, `Tren_Inflasi_Tahun_ke_Tahun_YoY_ke_Tahun_${selectedYear || 'All'}_${new Date().toISOString().split('T')[0]}.png`);
    }, 'grafik inflasi tahun ke tahun');
  });
  
  // Komoditas Chart
  document.getElementById('downloadKomoditasExcel')?.addEventListener('click', function() {
    window.checkAuthBeforeDownload(exportKomoditasToExcel, 'Andil Inflasi Kelompok Pengeluaran');
  });
  document.getElementById('downloadKomoditasPNG')?.addEventListener('click', function() {
    window.checkAuthBeforeDownload(() => {
      const safeName = window.chartData.komoditas.name ? window.chartData.komoditas.name.replace(/[^a-zA-Z0-9]/g, '_') : 'Komoditas';
      exportChartToPNG(window.chartInstances.komoditas, `Inflasi_${safeName}_${window.chartData.komoditas.year || ''}_${new Date().toISOString().split('T')[0]}.png`);
    }, 'grafik inflasi komoditas');
  });
});


