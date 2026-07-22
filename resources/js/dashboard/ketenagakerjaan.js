document.addEventListener("DOMContentLoaded", async () => {
    const mainPanel = document.querySelector('.main-panel');
    // API Base URL
    const API_BASE = window.APP_CONFIG.apiUrl;
    
    // Initialize data variables
    let tptData = [];
    let originalTptData = [];
    let tpakData = [];
    let originalTpakData = [];
    let tptLatestData = null;
    let tpakLatestData = null;
    let tptPreviousData = null;
    let tpakPreviousData = null;
    let tptTotalChange = null;
    let tpakTotalChange = null;
    let tptLakiLakiChange = null;
    let tpakLakiLakiChange = null;
    let tptPerempuanChange = null;
    let tpakPerempuanChange = null;
    let tptPieChart = null;
    let tptLineChart = null;
    let tpakPieChart = null;
    let tpakLineChart = null;
    let comparisonChart = null;


    // Load summary data from API
    try {
      console.log('Fetching ketenagakerjaan data from:', `${API_BASE}/ketenagakerjaan-summary`);
      const response = await fetch(`${API_BASE}/ketenagakerjaan-summary`);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const result = await response.json();
      console.log('Ketenagakerjaan API Response:', result);
      
      if (result.success && result.data) {
        const data = result.data;
        console.log('Ketenagakerjaan Data:', {
          tpt_data_count: Array.isArray(data.tpt_data) ? data.tpt_data.length : 0,
          tpak_data_count: Array.isArray(data.tpak_data) ? data.tpak_data.length : 0,
          tpt_latest_data: data.tpt_latest_data,
          tpak_latest_data: data.tpak_latest_data,
          tpt_previous_data: data.tpt_previous_data,
          tpak_previous_data: data.tpak_previous_data,
        });
        
        // Helper function to convert string to number
        const parseNumber = (value) => {
          if (value === null || value === undefined) return null;
          const num = typeof value === 'string' ? parseFloat(value) : Number(value);
          return isNaN(num) ? null : num;
        };
        
        // Process TPT data - convert strings to numbers
        tptData = Array.isArray(data.tpt_data) ? data.tpt_data.map(item => ({
          id: item.id,
          year: parseInt(item.year),
          laki_laki: parseNumber(item.laki_laki),
          perempuan: parseNumber(item.perempuan),
          total: parseNumber(item.total)
        })) : [];
        
        // Process TPAK data - convert strings to numbers
        tpakData = Array.isArray(data.tpak_data) ? data.tpak_data.map(item => ({
          id: item.id,
          year: parseInt(item.year),
          laki_laki: parseNumber(item.laki_laki),
          perempuan: parseNumber(item.perempuan),
          total: parseNumber(item.total)
        })) : [];
        
        // Process latest data - convert strings to numbers
        if (data.tpt_latest_data) {
          tptLatestData = {
            id: data.tpt_latest_data.id,
            year: parseInt(data.tpt_latest_data.year),
            laki_laki: parseNumber(data.tpt_latest_data.laki_laki),
            perempuan: parseNumber(data.tpt_latest_data.perempuan),
            total: parseNumber(data.tpt_latest_data.total)
          };
        }
        
        if (data.tpak_latest_data) {
          tpakLatestData = {
            id: data.tpak_latest_data.id,
            year: parseInt(data.tpak_latest_data.year),
            laki_laki: parseNumber(data.tpak_latest_data.laki_laki),
            perempuan: parseNumber(data.tpak_latest_data.perempuan),
            total: parseNumber(data.tpak_latest_data.total)
          };
        }
        
        // Process previous data - convert strings to numbers
        if (data.tpt_previous_data) {
          tptPreviousData = {
            id: data.tpt_previous_data.id,
            year: parseInt(data.tpt_previous_data.year),
            laki_laki: parseNumber(data.tpt_previous_data.laki_laki),
            perempuan: parseNumber(data.tpt_previous_data.perempuan),
            total: parseNumber(data.tpt_previous_data.total)
          };
        }
        
        if (data.tpak_previous_data) {
          tpakPreviousData = {
            id: data.tpak_previous_data.id,
            year: parseInt(data.tpak_previous_data.year),
            laki_laki: parseNumber(data.tpak_previous_data.laki_laki),
            perempuan: parseNumber(data.tpak_previous_data.perempuan),
            total: parseNumber(data.tpak_previous_data.total)
          };
        }
        
        // Process changes - already numbers but ensure they are
        tptTotalChange = parseNumber(data.tpt_total_change);
        tpakTotalChange = parseNumber(data.tpak_total_change);
        tptLakiLakiChange = parseNumber(data.tpt_laki_laki_change);
        tpakLakiLakiChange = parseNumber(data.tpak_laki_laki_change);
        tptPerempuanChange = parseNumber(data.tpt_perempuan_change);
        tpakPerempuanChange = parseNumber(data.tpak_perempuan_change);
        
        console.log('Processed ketenagakerjaan data:', {
          tptDataLength: tptData.length,
          tpakDataLength: tpakData.length,
          tptLatestData: tptLatestData,
          tpakLatestData: tpakLatestData,
          tptLatestDataTotal: tptLatestData ? tptLatestData.total : null,
          tpakLatestDataTotal: tpakLatestData ? tpakLatestData.total : null
        });
      } else {
        console.error('Failed to load ketenagakerjaan summary data:', result.message || 'Unknown error');
        console.error('Full result:', result);
      }
    } catch (error) {
      console.error('Error loading ketenagakerjaan summary data:', error);
    }

    // Sort data by year
    originalTptData = [...tptData];
    originalTpakData = [...tpakData];
    
    // Populate year filter
    const allYears = [...new Set([...originalTptData.map(d => d.year), ...originalTpakData.map(d => d.year)])].sort((a, b) => b - a);
    const yearFilter = document.getElementById('yearFilter');
    if (yearFilter) {
      yearFilter.innerHTML = ''; // Remove "Loading..." option
      allYears.forEach(year => {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearFilter.appendChild(option);
      });
      if (allYears.length > 0) {
        yearFilter.value = allYears[0];
      }
      yearFilter.addEventListener('change', function() {
        applyYearFilter(this.value);
      });
    }
    
    function applyYearFilter(selectedYear) {
      if (selectedYear === 'all') {
        tptData = [...originalTptData];
        tpakData = [...originalTpakData];
      } else {
        const year = parseInt(selectedYear);
        tptData = originalTptData.filter(d => d.year <= year);
        tpakData = originalTpakData.filter(d => d.year <= year);
      }
      
      tptLatestData = tptData.length > 0 ? tptData[tptData.length - 1] : null;
      tpakLatestData = tpakData.length > 0 ? tpakData[tpakData.length - 1] : null;
      
      tptPreviousData = tptData.length > 1 ? tptData[tptData.length - 2] : null;
      tpakPreviousData = tpakData.length > 1 ? tpakData[tpakData.length - 2] : null;
      
      if (tptLatestData && tptPreviousData) {
        tptTotalChange = tptLatestData.total - tptPreviousData.total;
        tptLakiLakiChange = tptLatestData.laki_laki - tptPreviousData.laki_laki;
        tptPerempuanChange = tptLatestData.perempuan - tptPreviousData.perempuan;
      } else {
        tptTotalChange = tptLakiLakiChange = tptPerempuanChange = null;
      }
      
      if (tpakLatestData && tpakPreviousData) {
        tpakTotalChange = tpakLatestData.total - tpakPreviousData.total;
        tpakLakiLakiChange = tpakLatestData.laki_laki - tpakPreviousData.laki_laki;
        tpakPerempuanChange = tpakLatestData.perempuan - tpakPreviousData.perempuan;
      } else {
        tpakTotalChange = tpakLakiLakiChange = tpakPerempuanChange = null;
      }
      
      updateUI();
      initCharts();
    }
    
    tptData.sort((a, b) => a.year - b.year);
    tpakData.sort((a, b) => a.year - b.year);

    // Function to update UI with loaded data
    function updateUI() {
      // Update TPT Summary Card
      if (tptLatestData) {
        const tptTotalEl = document.getElementById('tpt-total-value');
        const tptLakiEl = document.getElementById('tpt-laki-value');
        const tptPerempuanEl = document.getElementById('tpt-perempuan-value');
        const tptYearEl = document.getElementById('tpt-year-value');
        const tptChangeEl = document.getElementById('tpt-change-value');
        
        if (tptTotalEl) tptTotalEl.textContent = tptLatestData.total !== null ? tptLatestData.total.toFixed(2) + '%' : '-';
        if (tptLakiEl) tptLakiEl.textContent = tptLatestData.laki_laki !== null ? tptLatestData.laki_laki.toFixed(2) + '%' : '-';
        if (tptPerempuanEl) tptPerempuanEl.textContent = tptLatestData.perempuan !== null ? tptLatestData.perempuan.toFixed(2) + '%' : '-';
        if (tptYearEl) tptYearEl.textContent = tptLatestData.year ? `Tahun ${tptLatestData.year}` : 'Data tidak tersedia';
        
        if (tptChangeEl) {
          if (tptTotalChange !== null) {
            let changeHtml = '';
            if (tptTotalChange > 0) {
              changeHtml = `<span style="color: #28a745; font-size: 12px;">▲</span>
                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${tptTotalChange.toFixed(2)}%</span>`;
            } else if (tptTotalChange < 0) {
              changeHtml = `<span style="color: #dc3545; font-size: 12px;">▼</span>
                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${tptTotalChange.toFixed(2)}%</span>`;
            } else {
              changeHtml = '<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>';
            }
            if (tptPreviousData && tptPreviousData.year) {
              changeHtml += `<span style="color: rgba(255, 255, 255, 0.7); font-size: 10px;"> dari ${tptPreviousData.year}</span>`;
            }
            tptChangeEl.innerHTML = changeHtml;
          } else {
            tptChangeEl.innerHTML = '';
          }
        }
      }
      
      // Update TPAK Summary Card
      if (tpakLatestData) {
        const tpakTotalEl = document.getElementById('tpak-total-value');
        const tpakLakiEl = document.getElementById('tpak-laki-value');
        const tpakPerempuanEl = document.getElementById('tpak-perempuan-value');
        const tpakYearEl = document.getElementById('tpak-year-value');
        const tpakChangeEl = document.getElementById('tpak-change-value');
        
        if (tpakTotalEl) tpakTotalEl.textContent = tpakLatestData.total !== null ? tpakLatestData.total.toFixed(2) + '%' : '-';
        if (tpakLakiEl) tpakLakiEl.textContent = tpakLatestData.laki_laki !== null ? tpakLatestData.laki_laki.toFixed(2) + '%' : '-';
        if (tpakPerempuanEl) tpakPerempuanEl.textContent = tpakLatestData.perempuan !== null ? tpakLatestData.perempuan.toFixed(2) + '%' : '-';
        if (tpakYearEl) tpakYearEl.textContent = tpakLatestData.year ? `Tahun ${tpakLatestData.year}` : 'Data tidak tersedia';
        
        if (tpakChangeEl) {
          if (tpakTotalChange !== null) {
            let changeHtml = '';
            if (tpakTotalChange > 0) {
              changeHtml = `<span style="color: #28a745; font-size: 12px;">▲</span>
                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${tpakTotalChange.toFixed(2)}%</span>`;
            } else if (tpakTotalChange < 0) {
              changeHtml = `<span style="color: #dc3545; font-size: 12px;">▼</span>
                <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${tpakTotalChange.toFixed(2)}%</span>`;
            } else {
              changeHtml = '<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>';
            }
            if (tpakPreviousData && tpakPreviousData.year) {
              changeHtml += `<span style="color: rgba(255, 255, 255, 0.7); font-size: 10px;"> dari ${tpakPreviousData.year}</span>`;
            }
            tpakChangeEl.innerHTML = changeHtml;
          } else {
            tpakChangeEl.innerHTML = '';
          }
        }
      }
      
      // Update TPT Tab Summary Cards
      updateTPTTabCards();
      
      // Update TPAK Tab Summary Cards
      updateTPAKTabCards();
    }
    
    // Function to update TPT tab summary cards
    function updateTPTTabCards() {
      if (!tptLatestData) return;
      
      // Total TPT
      const tptTabTotalEl = document.getElementById('tpt-tab-total-value');
      const tptTabTotalChangeEl = document.getElementById('tpt-tab-total-change');
      const tptTabTotalYearEl = document.getElementById('tpt-tab-total-year');
      
      if (tptTabTotalEl) {
        tptTabTotalEl.textContent = tptLatestData.total !== null ? tptLatestData.total.toFixed(2) + '%' : '-';
      }
      
      if (tptTabTotalChangeEl) {
        if (tptTotalChange !== null) {
          let changeHtml = '';
          if (tptTotalChange > 0) {
            changeHtml = `<span style="color: #28a745; font-size: 12px;">▲</span>
              <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${tptTotalChange.toFixed(2)}%</span>`;
          } else if (tptTotalChange < 0) {
            changeHtml = `<span style="color: #dc3545; font-size: 12px;">▼</span>
              <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${tptTotalChange.toFixed(2)}%</span>`;
          } else {
            changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>`;
          }
          if (tptPreviousData && tptPreviousData.year) {
            changeHtml += `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari ${tptPreviousData.year}</span>`;
          }
          tptTabTotalChangeEl.innerHTML = changeHtml;
        } else {
          tptTabTotalChangeEl.innerHTML = '';
        }
      }
      
      if (tptTabTotalYearEl) {
        tptTabTotalYearEl.textContent = tptLatestData.year ? `Tahun ${tptLatestData.year}` : 'Data tidak tersedia';
      }
      
      // Laki-Laki TPT
      const tptTabLakiEl = document.getElementById('tpt-tab-laki-value');
      const tptTabLakiChangeEl = document.getElementById('tpt-tab-laki-change');
      const tptTabLakiYearEl = document.getElementById('tpt-tab-laki-year');
      
      if (tptTabLakiEl) {
        tptTabLakiEl.textContent = tptLatestData.laki_laki !== null ? tptLatestData.laki_laki.toFixed(2) + '%' : '-';
      }
      
      if (tptTabLakiChangeEl) {
        if (tptLakiLakiChange !== null) {
          let changeHtml = '';
          if (tptLakiLakiChange > 0) {
            changeHtml = `<span style="color: #28a745; font-size: 12px;">▲</span>
              <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${tptLakiLakiChange.toFixed(2)}%</span>`;
          } else if (tptLakiLakiChange < 0) {
            changeHtml = `<span style="color: #dc3545; font-size: 12px;">▼</span>
              <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${tptLakiLakiChange.toFixed(2)}%</span>`;
          } else {
            changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>`;
          }
          if (tptPreviousData && tptPreviousData.year) {
            changeHtml += `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari ${tptPreviousData.year}</span>`;
          }
          tptTabLakiChangeEl.innerHTML = changeHtml;
        } else {
          tptTabLakiChangeEl.innerHTML = '';
        }
      }
      
      if (tptTabLakiYearEl) {
        tptTabLakiYearEl.textContent = tptLatestData.year ? `Tahun ${tptLatestData.year}` : 'Data tidak tersedia';
      }
      
      // Perempuan TPT
      const tptTabPerempuanEl = document.getElementById('tpt-tab-perempuan-value');
      const tptTabPerempuanChangeEl = document.getElementById('tpt-tab-perempuan-change');
      const tptTabPerempuanYearEl = document.getElementById('tpt-tab-perempuan-year');
      
      if (tptTabPerempuanEl) {
        tptTabPerempuanEl.textContent = tptLatestData.perempuan !== null ? tptLatestData.perempuan.toFixed(2) + '%' : '-';
      }
      
      if (tptTabPerempuanChangeEl) {
        if (tptPerempuanChange !== null) {
          let changeHtml = '';
          if (tptPerempuanChange > 0) {
            changeHtml = `<span style="color: #28a745; font-size: 12px;">▲</span>
              <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${tptPerempuanChange.toFixed(2)}%</span>`;
          } else if (tptPerempuanChange < 0) {
            changeHtml = `<span style="color: #dc3545; font-size: 12px;">▼</span>
              <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${tptPerempuanChange.toFixed(2)}%</span>`;
          } else {
            changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>`;
          }
          if (tptPreviousData && tptPreviousData.year) {
            changeHtml += `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari ${tptPreviousData.year}</span>`;
          }
          tptTabPerempuanChangeEl.innerHTML = changeHtml;
        } else {
          tptTabPerempuanChangeEl.innerHTML = '';
        }
      }
      
      if (tptTabPerempuanYearEl) {
        tptTabPerempuanYearEl.textContent = tptLatestData.year ? `Tahun ${tptLatestData.year}` : 'Data tidak tersedia';
      }
      
      // Update pie chart title year
      const tptPieYearEl = document.getElementById('tpt-pie-year');
      if (tptPieYearEl) {
        tptPieYearEl.textContent = tptLatestData.year ? tptLatestData.year : '-';
      }
    }
    
    // Function to update TPAK tab summary cards
    function updateTPAKTabCards() {
      if (!tpakLatestData) return;
      
      // Total TPAK
      const tpakTabTotalEl = document.getElementById('tpak-tab-total-value');
      const tpakTabTotalChangeEl = document.getElementById('tpak-tab-total-change');
      const tpakTabTotalYearEl = document.getElementById('tpak-tab-total-year');
      
      if (tpakTabTotalEl) {
        tpakTabTotalEl.textContent = tpakLatestData.total !== null ? tpakLatestData.total.toFixed(2) + '%' : '-';
      }
      
      if (tpakTabTotalChangeEl) {
        if (tpakTotalChange !== null) {
          let changeHtml = '';
          if (tpakTotalChange > 0) {
            changeHtml = `<span style="color: #28a745; font-size: 12px;">▲</span>
              <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${tpakTotalChange.toFixed(2)}%</span>`;
          } else if (tpakTotalChange < 0) {
            changeHtml = `<span style="color: #dc3545; font-size: 12px;">▼</span>
              <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${tpakTotalChange.toFixed(2)}%</span>`;
          } else {
            changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>`;
          }
          if (tpakPreviousData && tpakPreviousData.year) {
            changeHtml += `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari ${tpakPreviousData.year}</span>`;
          }
          tpakTabTotalChangeEl.innerHTML = changeHtml;
        } else {
          tpakTabTotalChangeEl.innerHTML = '';
        }
      }
      
      if (tpakTabTotalYearEl) {
        tpakTabTotalYearEl.textContent = tpakLatestData.year ? `Tahun ${tpakLatestData.year}` : 'Data tidak tersedia';
      }
      
      // Laki-Laki TPAK
      const tpakTabLakiEl = document.getElementById('tpak-tab-laki-value');
      const tpakTabLakiChangeEl = document.getElementById('tpak-tab-laki-change');
      const tpakTabLakiYearEl = document.getElementById('tpak-tab-laki-year');
      
      if (tpakTabLakiEl) {
        tpakTabLakiEl.textContent = tpakLatestData.laki_laki !== null ? tpakLatestData.laki_laki.toFixed(2) + '%' : '-';
      }
      
      if (tpakTabLakiChangeEl) {
        if (tpakLakiLakiChange !== null) {
          let changeHtml = '';
          if (tpakLakiLakiChange > 0) {
            changeHtml = `<span style="color: #28a745; font-size: 12px;">▲</span>
              <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${tpakLakiLakiChange.toFixed(2)}%</span>`;
          } else if (tpakLakiLakiChange < 0) {
            changeHtml = `<span style="color: #dc3545; font-size: 12px;">▼</span>
              <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${tpakLakiLakiChange.toFixed(2)}%</span>`;
          } else {
            changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>`;
          }
          if (tpakPreviousData && tpakPreviousData.year) {
            changeHtml += `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari ${tpakPreviousData.year}</span>`;
          }
          tpakTabLakiChangeEl.innerHTML = changeHtml;
        } else {
          tpakTabLakiChangeEl.innerHTML = '';
        }
      }
      
      if (tpakTabLakiYearEl) {
        tpakTabLakiYearEl.textContent = tpakLatestData.year ? `Tahun ${tpakLatestData.year}` : 'Data tidak tersedia';
      }
      
      // Perempuan TPAK
      const tpakTabPerempuanEl = document.getElementById('tpak-tab-perempuan-value');
      const tpakTabPerempuanChangeEl = document.getElementById('tpak-tab-perempuan-change');
      const tpakTabPerempuanYearEl = document.getElementById('tpak-tab-perempuan-year');
      
      if (tpakTabPerempuanEl) {
        tpakTabPerempuanEl.textContent = tpakLatestData.perempuan !== null ? tpakLatestData.perempuan.toFixed(2) + '%' : '-';
      }
      
      if (tpakTabPerempuanChangeEl) {
        if (tpakPerempuanChange !== null) {
          let changeHtml = '';
          if (tpakPerempuanChange > 0) {
            changeHtml = `<span style="color: #28a745; font-size: 12px;">▲</span>
              <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">+${tpakPerempuanChange.toFixed(2)}%</span>`;
          } else if (tpakPerempuanChange < 0) {
            changeHtml = `<span style="color: #dc3545; font-size: 12px;">▼</span>
              <span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">${tpakPerempuanChange.toFixed(2)}%</span>`;
          } else {
            changeHtml = `<span style="color: rgba(255, 255, 255, 0.9); font-size: 12px;">-</span>`;
          }
          if (tpakPreviousData && tpakPreviousData.year) {
            changeHtml += `<span style="color: rgba(255, 255, 255, 0.8); font-size: 11px;"> dari ${tpakPreviousData.year}</span>`;
          }
          tpakTabPerempuanChangeEl.innerHTML = changeHtml;
        } else {
          tpakTabPerempuanChangeEl.innerHTML = '';
        }
      }
      
      if (tpakTabPerempuanYearEl) {
        tpakTabPerempuanYearEl.textContent = tpakLatestData.year ? `Tahun ${tpakLatestData.year}` : 'Data tidak tersedia';
      }
      
      // Update pie chart title year
      const tpakPieYearEl = document.getElementById('tpak-pie-year');
      if (tpakPieYearEl) {
        tpakPieYearEl.textContent = tpakLatestData.year ? tpakLatestData.year : '-';
      }
    }

    // Update UI after data is loaded
    updateUI();

        // Resize all charts function
    function resizeAllCharts() {
      setTimeout(() => {
        try {
          if (typeof comparisonChart !== 'undefined' && comparisonChart) {
            comparisonChart.resize();
          }
          if (typeof tptPieChart !== 'undefined' && tptPieChart) {
            tptPieChart.resize();
          }
          if (typeof tptLineChart !== 'undefined' && tptLineChart) {
            tptLineChart.resize();
          }
          if (typeof tpakPieChart !== 'undefined' && tpakPieChart) {
            tpakPieChart.resize();
          }
          if (typeof tpakLineChart !== 'undefined' && tpakLineChart) {
            tpakLineChart.resize();
          }
        } catch (e) {
          console.log('Chart resize error:', e);
        }
      }, 150);
    }

    function initCharts() {
    // Check if mobile
    const isMobile = window.innerWidth <= 767.98;
    
    // Adjust chart height for mobile
    const comparisonChartDom = document.getElementById('comparisonChart');
    if (isMobile && comparisonChartDom) {
      comparisonChartDom.style.height = '350px';
    }

    // TPT Pie Chart
    const tptPieChartDom = document.getElementById('tptPieChart');
    tptPieChart = null;
    if (tptPieChartDom) {
      tptPieChart = echarts.getInstanceByDom(tptPieChartDom) || echarts.init(tptPieChartDom);
    }
    
    // Use tptLatestData from API, fallback to last item in array if not available
    const tptLatestDataForChart = tptLatestData || (tptData.length > 0 ? tptData[tptData.length - 1] : null);
    const tptPieData = [];
    
    if (tptLatestDataForChart && tptLatestDataForChart.laki_laki !== null) {
      tptPieData.push({
        name: 'Laki-Laki',
        value: tptLatestDataForChart.laki_laki
      });
    }
    
    if (tptLatestDataForChart && tptLatestDataForChart.perempuan !== null) {
      tptPieData.push({
        name: 'Perempuan',
        value: tptLatestDataForChart.perempuan
      });
    }

    if (tptPieChart) {
    tptPieChart.setOption({
      tooltip: {
        trigger: 'item',
        formatter: '{a} <br/>{b}: {c}% ({d}%)',
        hideDelay: 50,
        enterable: false,
        confine: true
      },
      legend: {
        orient: 'horizontal',
        left: 'center',
        top: 'bottom',
        data: tptPieData.map(function(item) { return item.name; }),
        itemGap: isMobile ? 10 : 20,
        textStyle: {
          fontSize: isMobile ? 10 : 12
        }
      },
      series: [
        {
          name: 'TPT',
          type: 'pie',
          radius: ['40%', '70%'],
          center: ['50%', '45%'],
          avoidLabelOverlap: false,
          itemStyle: {
            borderRadius: 10,
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
            scale: true,
            scaleSize: 5,
            itemStyle: {
              shadowBlur: 10,
              shadowOffsetX: 0,
              shadowColor: 'rgba(0, 0, 0, 0.3)'
            }
          },
          select: {
            disabled: true
          },
          data: tptPieData,
          color: ['#3b82f6', '#f093fb']
        }
      ]
    });
    }

    // Reset chart state on mouse leave
    if (tptPieChartDom && tptPieChart) {
    tptPieChartDom.addEventListener('mouseleave', function() {
      setTimeout(function() {
        tptPieChart.dispatchAction({ type: 'downplay' });
      }, 100);
    });
    }

    // TPT Line Chart
    const tptLineChartDom = document.getElementById('tptLineChart');
    tptLineChart = null;
    if (tptLineChartDom) {
      tptLineChart = echarts.getInstanceByDom(tptLineChartDom) || echarts.init(tptLineChartDom);
    }
    
    // Filter TPT data for the last 10 years
    const tptFilteredData = tptData.slice(-10);
    const tptYears = tptFilteredData.map(d => d.year.toString());
    const tptTotalValues = tptFilteredData.map(d => {
      if (d.total !== null && d.total !== undefined) {
        return typeof d.total === 'number' ? d.total : parseFloat(d.total);
      }
      return null;
    });
    const tptLakiLakiValues = tptFilteredData.map(d => {
      if (d.laki_laki !== null && d.laki_laki !== undefined) {
        return typeof d.laki_laki === 'number' ? d.laki_laki : parseFloat(d.laki_laki);
      }
      return null;
    });
    const tptPerempuanValues = tptFilteredData.map(d => {
      if (d.perempuan !== null && d.perempuan !== undefined) {
        return typeof d.perempuan === 'number' ? d.perempuan : parseFloat(d.perempuan);
      }
      return null;
    });

    if (tptLineChart) {
    tptLineChart.setOption({
      tooltip: {
        trigger: 'axis',
        axisPointer: { 
          type: 'line',
          snap: true,
          lineStyle: {
            type: 'dashed'
          },
          label: {
            show: false
          }
        },
        hideDelay: 50,
        enterable: false,
        confine: true,
        formatter: function(params) {
          let result = 'Tahun: ' + params[0].axisValue + '<br/>';
          params.forEach(function(item) {
            result += item.marker + item.seriesName + ': ' + 
              (item.value !== null ? item.value.toFixed(2) + '%' : 'Data tidak tersedia') + '<br/>';
          });
          return result;
        }
      },
      legend: {
        data: ['Total', 'Laki-Laki', 'Perempuan'],
        textStyle: {
          fontSize: isMobile ? 10 : 12
        },
        itemGap: isMobile ? 10 : 20
      },
      grid: {
        left: isMobile ? '15%' : '12%',
        right: isMobile ? '8%' : '4%',
        bottom: isMobile ? '12%' : '10%',
        top: isMobile ? '12%' : '20%',
        containLabel: false
      },
      xAxis: {
        type: 'category',
        data: tptYears,
        boundaryGap: false,
        axisLabel: {
          fontSize: isMobile ? 10 : 12,
          margin: isMobile ? 5 : 10
        }
      },
      yAxis: {
        type: 'value',
        name: 'TPT (%)',
        position: 'left',
        nameLocation: 'end',
        nameGap: isMobile ? 5 : 10,
        nameTextStyle: {
          padding: [0, 0, 0, 0],
          fontSize: isMobile ? 10 : 12
        },
        axisLabel: {
          formatter: '{value}%',
          fontSize: isMobile ? 10 : 12,
          margin: isMobile ? 8 : 10
        }
      },
      series: [
        {
          name: 'Total',
          type: 'line',
          data: tptTotalValues,
          itemStyle: { color: '#667eea' },
          lineStyle: { width: 3 },
          symbol: 'circle',
          symbolSize: 8,
          smooth: true
        },
        {
          name: 'Laki-Laki',
          type: 'line',
          data: tptLakiLakiValues,
          itemStyle: { color: '#3b82f6' },
          lineStyle: { width: 2 },
          symbol: 'circle',
          symbolSize: 6,
          smooth: true
        },
        {
          name: 'Perempuan',
          type: 'line',
          data: tptPerempuanValues,
          itemStyle: { color: '#f093fb' },
          lineStyle: { width: 2 },
          symbol: 'circle',
          symbolSize: 6,
          smooth: true
        }
      ]
    });
    }

    // TPAK Pie Chart
    const tpakPieChartDom = document.getElementById('tpakPieChart');
    tpakPieChart = null;
    if (tpakPieChartDom) {
      tpakPieChart = echarts.getInstanceByDom(tpakPieChartDom) || echarts.init(tpakPieChartDom);
    }
    
    // Use tpakLatestData from API, fallback to last item in array if not available
    const tpakLatestDataForChart = tpakLatestData || (tpakData.length > 0 ? tpakData[tpakData.length - 1] : null);
    const tpakPieData = [];
    
    if (tpakLatestDataForChart && tpakLatestDataForChart.laki_laki !== null) {
      const lakiValue = typeof tpakLatestDataForChart.laki_laki === 'number' 
        ? tpakLatestDataForChart.laki_laki 
        : parseFloat(tpakLatestDataForChart.laki_laki);
      if (!isNaN(lakiValue)) {
        tpakPieData.push({
          name: 'Laki-Laki',
          value: lakiValue
        });
      }
    }
    
    if (tpakLatestDataForChart && tpakLatestDataForChart.perempuan !== null) {
      const perempuanValue = typeof tpakLatestDataForChart.perempuan === 'number' 
        ? tpakLatestDataForChart.perempuan 
        : parseFloat(tpakLatestDataForChart.perempuan);
      if (!isNaN(perempuanValue)) {
        tpakPieData.push({
          name: 'Perempuan',
          value: perempuanValue
        });
      }
    }

    if (tpakPieChart) {
    tpakPieChart.setOption({
      tooltip: {
        trigger: 'item',
        formatter: '{a} <br/>{b}: {c}% ({d}%)',
        hideDelay: 50,
        enterable: false,
        confine: true
      },
      legend: {
        orient: 'horizontal',
        left: 'center',
        top: 'bottom',
        data: tpakPieData.map(function(item) { return item.name; }),
        itemGap: isMobile ? 10 : 20,
        textStyle: {
          fontSize: isMobile ? 10 : 12
        }
      },
      series: [
        {
          name: 'TPAK',
          type: 'pie',
          radius: ['40%', '70%'],
          center: ['50%', '45%'],
          avoidLabelOverlap: false,
          itemStyle: {
            borderRadius: 10,
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
            scale: true,
            scaleSize: 5,
            itemStyle: {
              shadowBlur: 10,
              shadowOffsetX: 0,
              shadowColor: 'rgba(0, 0, 0, 0.3)'
            }
          },
          select: {
            disabled: true
          },
          data: tpakPieData,
          color: ['#43e97b', '#fa709a']
        }
      ]
    });
    }

    // Reset chart state on mouse leave
    if (tpakPieChartDom && tpakPieChart) {
    tpakPieChartDom.addEventListener('mouseleave', function() {
      setTimeout(function() {
        tpakPieChart.dispatchAction({ type: 'downplay' });
      }, 100);
    });
    }

    // TPAK Line Chart
    const tpakLineChartDom = document.getElementById('tpakLineChart');
    tpakLineChart = null;
    if (tpakLineChartDom) {
      tpakLineChart = echarts.getInstanceByDom(tpakLineChartDom) || echarts.init(tpakLineChartDom);
    }
    
    // Filter TPAK data for the last 10 years
    const tpakFilteredData = tpakData.slice(-10);
    const tpakYears = tpakFilteredData.map(d => d.year.toString());
    const tpakTotalValues = tpakFilteredData.map(d => {
      if (d.total !== null && d.total !== undefined) {
        return typeof d.total === 'number' ? d.total : parseFloat(d.total);
      }
      return null;
    });
    const tpakLakiLakiValues = tpakFilteredData.map(d => {
      if (d.laki_laki !== null && d.laki_laki !== undefined) {
        return typeof d.laki_laki === 'number' ? d.laki_laki : parseFloat(d.laki_laki);
      }
      return null;
    });
    const tpakPerempuanValues = tpakFilteredData.map(d => {
      if (d.perempuan !== null && d.perempuan !== undefined) {
        return typeof d.perempuan === 'number' ? d.perempuan : parseFloat(d.perempuan);
      }
      return null;
    });

    if (tpakLineChart) {
    tpakLineChart.setOption({
      tooltip: {
        trigger: 'axis',
        axisPointer: { 
          type: 'line',
          snap: true,
          lineStyle: {
            type: 'dashed'
          },
          label: {
            show: false
          }
        },
        hideDelay: 50,
        enterable: false,
        confine: true,
        formatter: function(params) {
          let result = 'Tahun: ' + params[0].axisValue + '<br/>';
          params.forEach(function(item) {
            result += item.marker + item.seriesName + ': ' + 
              (item.value !== null ? item.value.toFixed(2) + '%' : 'Data tidak tersedia') + '<br/>';
          });
          return result;
        }
      },
      legend: {
        data: ['Total', 'Laki-Laki', 'Perempuan'],
        textStyle: {
          fontSize: isMobile ? 10 : 12
        },
        itemGap: isMobile ? 10 : 20
      },
      grid: {
        left: isMobile ? '15%' : '12%',
        right: isMobile ? '8%' : '4%',
        bottom: isMobile ? '12%' : '10%',
        top: isMobile ? '12%' : '20%',
        containLabel: false
      },
      xAxis: {
        type: 'category',
        data: tpakYears,
        boundaryGap: false,
        axisLabel: {
          fontSize: isMobile ? 10 : 12,
          margin: isMobile ? 5 : 10
        }
      },
      yAxis: {
        type: 'value',
        name: 'TPAK (%)',
        position: 'left',
        nameLocation: 'end',
        nameGap: isMobile ? 5 : 10,
        nameTextStyle: {
          padding: [0, 0, 0, 0],
          fontSize: isMobile ? 10 : 12
        },
        axisLabel: {
          formatter: '{value}%',
          fontSize: isMobile ? 10 : 12,
          margin: isMobile ? 8 : 10
        }
      },
      series: [
        {
          name: 'Total',
          type: 'line',
          data: tpakTotalValues,
          itemStyle: { color: '#4facfe' },
          lineStyle: { width: 3 },
          symbol: 'circle',
          symbolSize: 8,
          smooth: true
        },
        {
          name: 'Laki-Laki',
          type: 'line',
          data: tpakLakiLakiValues,
          itemStyle: { color: '#43e97b' },
          lineStyle: { width: 2 },
          symbol: 'circle',
          symbolSize: 6,
          smooth: true
        },
        {
          name: 'Perempuan',
          type: 'line',
          data: tpakPerempuanValues,
          itemStyle: { color: '#fa709a' },
          lineStyle: { width: 2 },
          symbol: 'circle',
          symbolSize: 6,
          smooth: true
        }
      ]
    });
    }

    // Comparison Chart - TPT vs TPAK
    // comparisonChartDom already declared above, just initialize chart
    comparisonChart = null;
    if (comparisonChartDom) {
      try {
        comparisonChart = echarts.getInstanceByDom(comparisonChartDom) || echarts.init(comparisonChartDom);
        console.log('Comparison chart initialized');
      } catch (e) {
        console.error('Error initializing comparison chart:', e);
      }
    } else {
      console.error('Comparison chart element not found');
    }
    
    // Get all unique years from both datasets
    const allYearsSet = new Set([...tptData.map(d => d.year), ...tpakData.map(d => d.year)]);
    const allYears = Array.from(allYearsSet).sort((a, b) => a - b);
    
    // Filter years for the last 10 years
    const filteredYears = allYears.slice(-10);
    const years = filteredYears.map(y => y.toString());
    
    // Get TPT total values for years from 2017
    const tptComparisonValues = filteredYears.map(year => {
      const data = tptData.find(d => d.year === year);
      if (data && data.total !== null && data.total !== undefined) {
        return typeof data.total === 'number' ? data.total : parseFloat(data.total);
      }
      return null;
    });
    
    // Get TPAK total values for years from 2017
    const tpakComparisonValues = filteredYears.map(year => {
      const data = tpakData.find(d => d.year === year);
      if (data && data.total !== null && data.total !== undefined) {
        return typeof data.total === 'number' ? data.total : parseFloat(data.total);
      }
      return null;
    });

    if (comparisonChart) {
    comparisonChart.setOption({
      tooltip: {
        trigger: 'axis',
        axisPointer: { 
          type: 'line',
          snap: true,
          lineStyle: {
            type: 'dashed'
          },
          label: {
            show: false
          }
        },
        hideDelay: 50,
        enterable: false,
        confine: true,
        formatter: function(params) {
          let result = 'Tahun: ' + params[0].axisValue + '<br/>';
          params.forEach(function(item) {
            result += item.marker + item.seriesName + ': ' + 
              (item.value !== null ? item.value.toFixed(2) + '%' : 'Data tidak tersedia') + '<br/>';
          });
          return result;
        }
      },
      legend: {
        data: ['TPT', 'TPAK'],
        top: 10,
        textStyle: {
          fontSize: isMobile ? 10 : 12
        },
        itemGap: isMobile ? 10 : 20
      },
      grid: {
        left: isMobile ? '12%' : '12%',
        right: isMobile ? '5%' : '4%',
        bottom: isMobile ? '8%' : '10%',
        top: isMobile ? '8%' : '20%',
        containLabel: false
      },
      xAxis: {
        type: 'category',
        data: years,
        boundaryGap: false,
        axisLabel: {
          fontSize: isMobile ? 10 : 12,
          margin: isMobile ? 5 : 10
        }
      },
      yAxis: {
        type: 'value',
        name: 'Persentase (%)',
        position: 'left',
        nameLocation: 'end',
        nameGap: isMobile ? 5 : 10,
        nameTextStyle: {
          padding: [0, 0, 0, 0],
          fontSize: isMobile ? 10 : 12
        },
        axisLabel: {
          formatter: '{value}%',
          fontSize: isMobile ? 10 : 12,
          margin: isMobile ? 8 : 10
        }
      },
      series: [
        {
          name: 'TPT',
          type: 'line',
          data: tptComparisonValues,
          itemStyle: { color: '#667eea' },
          lineStyle: { width: 3 },
          symbol: 'circle',
          symbolSize: 8,
          smooth: true,
          areaStyle: {
            color: {
              type: 'linear',
              x: 0,
              y: 0,
              x2: 0,
              y2: 1,
              colorStops: [
                { offset: 0, color: 'rgba(102, 126, 234, 0.3)' },
                { offset: 1, color: 'rgba(102, 126, 234, 0.05)' }
              ]
            }
          }
        },
        {
          name: 'TPAK',
          type: 'line',
          data: tpakComparisonValues,
          itemStyle: { color: '#4facfe' },
          lineStyle: { width: 3 },
          symbol: 'circle',
          symbolSize: 8,
          smooth: true,
          areaStyle: {
            color: {
              type: 'linear',
              x: 0,
              y: 0,
              x2: 0,
              y2: 1,
              colorStops: [
                { offset: 0, color: 'rgba(79, 172, 254, 0.3)' },
                { offset: 1, color: 'rgba(79, 172, 254, 0.05)' }
              ]
            }
          }
        }
      ]
    });
    }

    // Initialize charts when tabs are shown
    const tptTab = document.getElementById('tpt-tab');
    const tpakTab = document.getElementById('tpak-tab');
    const tptPane = document.getElementById('tpt');
    const tpakPane = document.getElementById('tpak');

    function initializeTPTCharts() {
      if (tptPane && tptPane.classList.contains('active')) {
        setTimeout(() => {
          if (tptPieChart && tptPieChartDom) {
          tptPieChart.resize();
          }
          if (tptLineChart && tptLineChartDom) {
          tptLineChart.resize();
          }
        }, 200);
      }
    }

    function initializeTPAKCharts() {
      if (tpakPane && tpakPane.classList.contains('active')) {
        setTimeout(() => {
          if (tpakPieChart && tpakPieChartDom) {
          tpakPieChart.resize();
          }
          if (tpakLineChart && tpakLineChartDom) {
          tpakLineChart.resize();
          }
        }, 200);
      }
    }

    // Initialize comparison chart immediately
    if (comparisonChart && comparisonChartDom) {
      setTimeout(() => {
        comparisonChart.resize();
        }, 100);
    }

    // Initialize on page load
    setTimeout(() => {
    initializeTPTCharts();
      if (comparisonChart && comparisonChartDom) {
        comparisonChart.resize();
      }
    }, 300);

    // Re-initialize when tabs are shown
    if (tptTab) {
    tptTab.addEventListener('shown.bs.tab', function () {
        setTimeout(() => {
      initializeTPTCharts();
        }, 150);
    });
    }

    if (tpakTab) {
    tpakTab.addEventListener('shown.bs.tab', function () {
        setTimeout(() => {
      initializeTPAKCharts();
        }, 150);
      });
    }
    
    // Also listen for click events on tabs
    if (tptTab) {
      tptTab.addEventListener('click', function () {
        setTimeout(() => {
          initializeTPTCharts();
        }, 200);
      });
    }
    
    if (tpakTab) {
      tpakTab.addEventListener('click', function () {
        setTimeout(() => {
          initializeTPAKCharts();
        }, 200);
    });
    }

    // Add event handlers to prevent stuck pointer on line charts
    function resetLineChart(chart, chartDom) {
      chart.dispatchAction({
        type: 'hideTip'
      });
      chart.dispatchAction({
        type: 'downplay'
      });
    }

    // TPT Line Chart event handlers
    if (tptLineChartDom && tptLineChart) {
    tptLineChartDom.addEventListener('mouseleave', function() {
      resetLineChart(tptLineChart, tptLineChartDom);
    });
    tptLineChartDom.addEventListener('click', function(e) {
      // Reset after a short delay to allow tooltip to show
      setTimeout(function() {
        resetLineChart(tptLineChart, tptLineChartDom);
      }, 200);
    });
    }

    // TPAK Line Chart event handlers
    if (tpakLineChartDom && tpakLineChart) {
    tpakLineChartDom.addEventListener('mouseleave', function() {
      resetLineChart(tpakLineChart, tpakLineChartDom);
    });
    tpakLineChartDom.addEventListener('click', function(e) {
      setTimeout(function() {
        resetLineChart(tpakLineChart, tpakLineChartDom);
      }, 200);
    });
    }

    // Comparison Chart event handlers
    if (comparisonChartDom && comparisonChart) {
      comparisonChartDom.addEventListener('mouseleave', function() {
        resetLineChart(comparisonChart, comparisonChartDom);
      });
      comparisonChartDom.addEventListener('click', function(e) {
        setTimeout(function() {
          resetLineChart(comparisonChart, comparisonChartDom);
        }, 200);
      });
    }



    // Handle window resize
    window.addEventListener('resize', function() {
      resizeAllCharts();
    });

    // Handle sidebar toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarCheck = document.getElementById('check');
    const sidebar = document.querySelector('.sidebar');


    if (sidebarToggle) {
      sidebarToggle.addEventListener('click', function() {
        setTimeout(() => {
          resizeAllCharts();
        }, 300);
      });
    }

    if (sidebarCheck) {
      sidebarCheck.addEventListener('change', function() {
        setTimeout(() => {
          resizeAllCharts();
        }, 300);
      });
    }

    // Use MutationObserver to detect sidebar changes
    if (sidebar) {
      const observer = new MutationObserver(function(mutations) {
        setTimeout(() => {
          resizeAllCharts();
        }, 300);
      });
      
      observer.observe(sidebar, {
        attributes: true,
        attributeFilter: ['class']
      });
    }



    }

    initCharts();

    // Export functions for Comparison Chart
    function exportComparisonToExcel() {
      const allYears = [...new Set([...tptData.map(d => d.year), ...tpakData.map(d => d.year)])].sort();
      // Filter years for the last 10 years
      const filteredYears = allYears.slice(-10);
      const exportData = [];
      exportData.push(['Tahun', 'TPT (%)', 'TPAK (%)']);
      filteredYears.forEach(year => {
        const tpt = tptData.find(d => d.year === year);
        const tpak = tpakData.find(d => d.year === year);
        const tptVal = tpt && tpt.total !== null ? tpt.total.toFixed(2) : 'Data tidak tersedia';
        const tpakVal = tpak && tpak.total !== null ? tpak.total.toFixed(2) : 'Data tidak tersedia';
        exportData.push([year.toString(), tptVal, tpakVal]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 10 }, { wch: 15 }, { wch: 15 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data Perbandingan');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Ketenagakerjaan_Perbandingan_${today}.xlsx`);
    }

    function exportComparisonToPNG() {
      const url = comparisonChart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
      const link = document.createElement('a');
      link.download = `Ketenagakerjaan_Perbandingan_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }

    document.getElementById('downloadComparisonExcel').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportComparisonToExcel, 'data perbandingan ketenagakerjaan');
    });
    document.getElementById('downloadComparisonPNG').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportComparisonToPNG, 'grafik perbandingan ketenagakerjaan');
    });

    // Export functions for TPT Pie Chart
    function exportTptPieToExcel() {
      const exportData = [];
      exportData.push(['Gender', 'TPT (%)']);
      if (tptLatestData) {
        if (tptLatestData.laki_laki !== null) {
          exportData.push(['Laki-Laki', tptLatestData.laki_laki.toFixed(2)]);
        }
        if (tptLatestData.perempuan !== null) {
          exportData.push(['Perempuan', tptLatestData.perempuan.toFixed(2)]);
        }
        if (tptLatestData.total !== null) {
          exportData.push(['Total', tptLatestData.total.toFixed(2)]);
        }
      }
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 15 }, { wch: 15 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data TPT Pie');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Ketenagakerjaan_TPT_Pie_${today}.xlsx`);
    }

    function exportTptPieToPNG() {
      const url = tptPieChart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
      const link = document.createElement('a');
      link.download = `Ketenagakerjaan_TPT_Pie_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }

    document.getElementById('downloadTptPieExcel').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportTptPieToExcel, 'data TPT pie chart');
    });
    document.getElementById('downloadTptPiePNG').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportTptPieToPNG, 'grafik TPT pie chart');
    });

    // Export functions for TPT Line Chart
    function exportTptLineToExcel() {
      const exportData = [];
      exportData.push(['Tahun', 'Total (%)', 'Laki-Laki (%)', 'Perempuan (%)']);
      tptData.forEach(data => {
        const total = data.total !== null ? data.total.toFixed(2) : 'Data tidak tersedia';
        const laki = data.laki_laki !== null ? data.laki_laki.toFixed(2) : 'Data tidak tersedia';
        const perempuan = data.perempuan !== null ? data.perempuan.toFixed(2) : 'Data tidak tersedia';
        exportData.push([data.year.toString(), total, laki, perempuan]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 10 }, { wch: 15 }, { wch: 15 }, { wch: 15 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data TPT Line');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Ketenagakerjaan_TPT_Line_${today}.xlsx`);
    }

    function exportTptLineToPNG() {
      const url = tptLineChart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
      const link = document.createElement('a');
      link.download = `Ketenagakerjaan_TPT_Line_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }

    document.getElementById('downloadTptLineExcel').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportTptLineToExcel, 'data TPT line chart');
    });
    document.getElementById('downloadTptLinePNG').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportTptLineToPNG, 'grafik TPT line chart');
    });

    // Export functions for TPAK Pie Chart
    function exportTpakPieToExcel() {
      const tpakLatestDataForExport = tpakLatestData || (tpakData.length > 0 ? tpakData[tpakData.length - 1] : null);
      const exportData = [];
      exportData.push(['Gender', 'TPAK (%)']);
      if (tpakLatestDataForExport) {
        if (tpakLatestDataForExport.laki_laki !== null) {
          exportData.push(['Laki-Laki', tpakLatestDataForExport.laki_laki.toFixed(2)]);
        }
        if (tpakLatestDataForExport.perempuan !== null) {
          exportData.push(['Perempuan', tpakLatestDataForExport.perempuan.toFixed(2)]);
        }
        if (tpakLatestDataForExport.total !== null) {
          exportData.push(['Total', tpakLatestDataForExport.total.toFixed(2)]);
        }
      }
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 15 }, { wch: 15 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data TPAK Pie');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Ketenagakerjaan_TPAK_Pie_${today}.xlsx`);
    }

    function exportTpakPieToPNG() {
      const url = tpakPieChart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
      const link = document.createElement('a');
      link.download = `Ketenagakerjaan_TPAK_Pie_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }

    document.getElementById('downloadTpakPieExcel').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportTpakPieToExcel, 'data TPAK pie chart');
    });
    document.getElementById('downloadTpakPiePNG').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportTpakPieToPNG, 'grafik TPAK pie chart');
    });

    // Export functions for TPAK Line Chart
    function exportTpakLineToExcel() {
      const exportData = [];
      exportData.push(['Tahun', 'Total (%)', 'Laki-Laki (%)', 'Perempuan (%)']);
      tpakData.forEach(data => {
        const total = data.total !== null ? data.total.toFixed(2) : 'Data tidak tersedia';
        const laki = data.laki_laki !== null ? data.laki_laki.toFixed(2) : 'Data tidak tersedia';
        const perempuan = data.perempuan !== null ? data.perempuan.toFixed(2) : 'Data tidak tersedia';
        exportData.push([data.year.toString(), total, laki, perempuan]);
      });
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(exportData);
      ws['!cols'] = [{ wch: 10 }, { wch: 15 }, { wch: 15 }, { wch: 15 }];
      XLSX.utils.book_append_sheet(wb, ws, 'Data TPAK Line');
      const today = new Date().toISOString().split('T')[0];
      XLSX.writeFile(wb, `Ketenagakerjaan_TPAK_Line_${today}.xlsx`);
    }

    function exportTpakLineToPNG() {
      const url = tpakLineChart.getDataURL({ type: 'png', pixelRatio: 2, backgroundColor: '#fff' });
      const link = document.createElement('a');
      link.download = `Ketenagakerjaan_TPAK_Line_${new Date().toISOString().split('T')[0]}.png`;
      link.href = url;
      link.click();
    }

    document.getElementById('downloadTpakLineExcel').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportTpakLineToExcel, 'data TPAK line chart');
    });
    document.getElementById('downloadTpakLinePNG').addEventListener('click', function() {
      window.checkAuthBeforeDownload(exportTpakLineToPNG, 'grafik TPAK line chart');
    });

    // Listen for transition end on main panel
    if (mainPanel) {
      mainPanel.addEventListener('transitionend', function() {
        resizeAllCharts();
      });
    }

    // Also listen for sidebar toggle buttons
    const sidebarToggleButtons = document.querySelectorAll('[data-toggle="sidebar"], .sidebar-toggle');
    sidebarToggleButtons.forEach(function(button) {
      button.addEventListener('click', function() {
        setTimeout(() => {
          resizeAllCharts();
        }, 300);
      });
    });
  });