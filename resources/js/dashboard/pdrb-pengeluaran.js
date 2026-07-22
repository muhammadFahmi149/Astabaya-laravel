document.addEventListener("DOMContentLoaded", () => {
    // API Base URL
    const API_BASE_URL = '/api';
    
    // CSRF Token for Laravel
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Helper function to fetch API data
    async function fetchAPI(url) {
      try {
        const response = await fetch(url, {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {})
          }
        });
        
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        return data.success ? data.data : null;
      } catch (error) {
        console.error('API fetch error:', error);
        return null;
      }
    }

    // ========== Load Initial Data ==========
    let latestBySheet = {};
    let allYears = [];
    let adhbByCategory = {};
    let adhkByCategory = {};
    let distribusiByCategory = {};
    let lajuByCategory = {};
    let adhbTriwulananByCategory = {};
    let adhkTriwulananByCategory = {};
    let distribusiTriwulananByCategory = {};
    let lajuQtoQByCategory = {};
    let lajuYtoYByCategory = {};
    let lajuCtoCByCategory = {};

    // Load all data on page load
    async function loadAllData() {
      try {
        // Load summary data for carousel
        latestBySheet = await fetchAPI(`${API_BASE_URL}/pdrb-pengeluaran-summary`) || {};
        
        // Load all years
        allYears = await fetchAPI(`${API_BASE_URL}/pdrb-pengeluaran-years`) || [];
        
        // Load data by category for charts
        adhbByCategory = await fetchAPI(`${API_BASE_URL}/pdrb-pengeluaran-by-category?type=adhb`) || {};
        adhkByCategory = await fetchAPI(`${API_BASE_URL}/pdrb-pengeluaran-by-category?type=adhk`) || {};
        distribusiByCategory = await fetchAPI(`${API_BASE_URL}/pdrb-pengeluaran-by-category?type=distribusi`) || {};
        lajuByCategory = await fetchAPI(`${API_BASE_URL}/pdrb-pengeluaran-by-category?type=laju`) || {};
        adhbTriwulananByCategory = await fetchAPI(`${API_BASE_URL}/pdrb-pengeluaran-by-category?type=adhb_triwulanan`) || {};
        adhkTriwulananByCategory = await fetchAPI(`${API_BASE_URL}/pdrb-pengeluaran-by-category?type=adhk_triwulanan`) || {};
        distribusiTriwulananByCategory = await fetchAPI(`${API_BASE_URL}/pdrb-pengeluaran-by-category?type=distribusi_triwulanan`) || {};
        lajuQtoQByCategory = await fetchAPI(`${API_BASE_URL}/pdrb-pengeluaran-by-category?type=laju_qtoq`) || {};
        lajuYtoYByCategory = await fetchAPI(`${API_BASE_URL}/pdrb-pengeluaran-by-category?type=laju_ytoy`) || {};
        lajuCtoCByCategory = await fetchAPI(`${API_BASE_URL}/pdrb-pengeluaran-by-category?type=laju_ctoc`) || {};
        
        // Populate year filters
        populateYearFilters();
        
        // Render carousel
        renderCarousel();
        
        // Set default selected years to latest year
        if (allYears && allYears.length > 0) {
          const latestYear = Math.max(...allYears);
          selectedYearDistribusi = latestYear;
          selectedYearDistribusiTriwulanan = latestYear;
          
          // Set default year in dropdowns
          const yearFilterDistribusi = document.getElementById('yearFilterDistribusi');
          if (yearFilterDistribusi) {
            yearFilterDistribusi.value = latestYear;
          }
          const yearFilterDistribusiTriwulanan = document.getElementById('yearFilterDistribusiTriwulanan');
          if (yearFilterDistribusiTriwulanan) {
            yearFilterDistribusiTriwulanan.value = latestYear;
          }
        }
        
        // Set default to latest quarter for Distribusi Triwulanan
        const latestQuarterInfo = findLatestQuarter();
        if (latestQuarterInfo) {
          if (!selectedYearDistribusiTriwulanan) {
            selectedYearDistribusiTriwulanan = latestQuarterInfo.year;
          }
          const yearFilterDistribusiTriwulanan = document.getElementById('yearFilterDistribusiTriwulanan');
          if (yearFilterDistribusiTriwulanan) {
            yearFilterDistribusiTriwulanan.value = latestQuarterInfo.year;
          }
          selectedQuarterDistribusiTriwulanan = latestQuarterInfo.quarter;
          const quarterFilterDistribusiTriwulanan = document.getElementById('quarterFilterDistribusiTriwulanan');
          if (quarterFilterDistribusiTriwulanan) {
            quarterFilterDistribusiTriwulanan.value = latestQuarterInfo.quarter;
          }
        }
        
        // Initialize charts after data is loaded
        setTimeout(() => {
          updateAllCharts();
        }, 200);
      } catch (error) {
        console.error('Error loading data:', error);
      }
    }

    // Populate year filter dropdowns
    function populateYearFilters() {
      // Calculate triwulananYears dynamically from ALL triwulanan data sources
      let triwulanYearSet = new Set();
      const triwulanSources = [adhbTriwulananByCategory, adhkTriwulananByCategory, lajuQtoQByCategory, lajuYtoYByCategory, lajuCtoCByCategory];
      triwulanSources.forEach(source => {
        if (source) {
          Object.values(source).forEach(list => list.forEach(item => {
            if (item.year) triwulanYearSet.add(item.year);
          }));
        }
      });
      const triwulananYears = Array.from(triwulanYearSet).sort((a,b) => b - a);
      const sortedAllYears = [...allYears].sort((a, b) => b - a);

      const filterConfigs = [
        { id: 'yearFilterDistribusi', years: sortedAllYears },
        { id: 'yearFilterDistribusiTriwulanan', years: triwulananYears },
        { id: 'globalYearFilterTriwulanan', years: triwulananYears }
      ];
      
      filterConfigs.forEach(config => {
        const select = document.getElementById(config.id);
        if (select) {
          // Clear existing options except first
          const firstOption = select.querySelector('option[value=""]');
          select.innerHTML = '';
          if (firstOption) {
            select.appendChild(firstOption);
          }
          
          // Add year options
          config.years.forEach(year => {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            select.appendChild(option);
          });
        }
      });
    }

    // Render carousel cards
    function renderCarousel() {
      const carousel = document.getElementById('pdrbSheetCarousel');
      if (!carousel || !latestBySheet) return;
      
      carousel.innerHTML = '';
      
      let cardIndex = 0;
      Object.keys(latestBySheet).forEach(sheetName => {
        const sheetData = latestBySheet[sheetName];
        const card = document.createElement('div');
        card.className = 'indicator-card';
        card.setAttribute('data-card-index', cardIndex);
        card.style.cssText = 'min-width: 240px; border-radius: 12px; padding: 15px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); position: relative; overflow: hidden;';
        
        const isPercentage = sheetName.includes('Distribusi') || sheetName.includes('Laju');
        const value = sheetData.data?.value || 0;
        const valueDisplay = isPercentage 
          ? `${parseFloat(value).toFixed(2)}%`
          : `<span class="rupiah-value" data-value="${parseFloat(value).toFixed(2)}">Rp ${formatRupiah(parseFloat(value).toFixed(2))}</span>`;
        
        card.innerHTML = `
          <div style="position: relative; z-index: 2;">
            <h6 class="card-title" style="font-size: 12px; margin-bottom: 8px; font-weight: 500;">${sheetName}</h6>
            <h6 class="card-subtitle" style="font-size: 11px; margin-bottom: 10px; font-weight: 400;">${truncateWords(sheetData.category || '', 5)}</h6>
            <h3 class="card-value" style="font-size: 22px; font-weight: 700; margin-bottom: 6px; word-break: break-word; overflow-wrap: break-word; white-space: normal;">${valueDisplay}</h3>
            <div id="sheet-${cardIndex}-comparison" style="display: flex; align-items: center; gap: 5px; margin-bottom: 4px;"></div>
            <small class="card-year" style="font-size: 11px;">
              ${sheetData.data ? `Tahun ${sheetData.data.year}${sheetData.data.preliminary_flag ? ' ' + sheetData.data.preliminary_flag : ''}` : 'Data tidak tersedia'}
            </small>
          </div>
        `;
        
        carousel.appendChild(card);
        cardIndex++;
      });
      
      // Apply colors and start carousel animation
      setTimeout(() => {
        applyCardColors();
        initCarousel();
        calculateCarouselComparisons();
      }, 100);
    }

    // Helper functions
    function truncateWords(str, num) {
      const words = str.split(' ');
      return words.slice(0, num).join(' ') + (words.length > num ? '...' : '');
    }



    // ========== Toggle Button Functionality ==========
    const btnTahunan = document.getElementById('btnTahunan');
    const btnTriwulanan = document.getElementById('btnTriwulanan');
    const cardsTahunan = document.getElementById('cardsTahunan');
    const cardsTriwulanan = document.getElementById('cardsTriwulanan');

    let currentView = 'tahunan';

    function switchView(view) {
      if (view === 'tahunan') {
        btnTahunan.classList.remove('btn-outline-primary');
        btnTahunan.classList.add('btn-primary');
        btnTriwulanan.classList.remove('btn-primary');
        btnTriwulanan.classList.add('btn-outline-primary');
        
        cardsTahunan.style.display = 'block';
        cardsTriwulanan.style.display = 'none';
        
        const globalContainer = document.getElementById('globalTriwulananFilterContainer');
        if (globalContainer) globalContainer.style.display = 'none';
        
        currentView = 'tahunan';
        
        setTimeout(() => {
          updateAllCharts();
        }, 100);
      } else {
        btnTriwulanan.classList.remove('btn-outline-primary');
        btnTriwulanan.classList.add('btn-primary');
        btnTahunan.classList.remove('btn-primary');
        btnTahunan.classList.add('btn-outline-primary');
        
        cardsTahunan.style.display = 'none';
        cardsTriwulanan.style.display = 'block';
        
        const globalContainer = document.getElementById('globalTriwulananFilterContainer');
        if (globalContainer) globalContainer.style.display = 'flex';
        
        currentView = 'triwulanan';
        
        setTimeout(() => {
          updateAllCharts();
        }, 100);
      }
    }

    if (btnTahunan) {
      btnTahunan.addEventListener('click', () => switchView('tahunan'));
    }
    if (btnTriwulanan) {
      btnTriwulanan.addEventListener('click', () => switchView('triwulanan'));
    }

    // ========== Filter PDRB Pengeluaran Functionality ==========
    // Filter Jenis PDRB (Tahunan) - Custom Dropdown
    const filterJenisPDRBInput = document.getElementById('filterJenisPDRBInput');
    const filterJenisPDRBPlaceholder = document.getElementById('filterJenisPDRBPlaceholder');
    const filterJenisPDRBSelected = document.getElementById('filterJenisPDRBSelected');
    const filterJenisPDRBDropdown = document.getElementById('filterJenisPDRBDropdown');
    const filterJenisPDRBWrapper = document.getElementById('filterJenisPDRBWrapper');
    const filterJenisPengeluaranInput = document.getElementById('filterJenisPengeluaranInput');
    const filterJenisPengeluaranPlaceholder = document.getElementById('filterJenisPengeluaranPlaceholder');
    const filterJenisPengeluaranTags = document.getElementById('filterJenisPengeluaranTags');
    const filterJenisPengeluaranDropdown = document.getElementById('filterJenisPengeluaranDropdown');
    const filterJenisPengeluaranWrapper = document.getElementById('filterJenisPengeluaranWrapper');
    const btnTerapkanFilterPDRB = document.getElementById('btnTerapkanFilterPDRB');
    const filteredChartSection = document.getElementById('filteredChartSection');

    let selectedJenisPDRB = '';
    let selectedPengeluaran = [];
    
    // ========== Filter Jenis PDRB (Tahunan) Dropdown Functionality ==========
    // Toggle dropdown with fixed positioning for PDRB
    if (filterJenisPDRBInput && filterJenisPDRBDropdown && filterJenisPDRBWrapper) {
      try {
        filterJenisPDRBInput.addEventListener('click', function(e) {
          try {
            e.stopPropagation();
            e.preventDefault();
            
            const computedStyle = window.getComputedStyle(filterJenisPDRBDropdown);
            const currentDisplay = filterJenisPDRBDropdown.style.display || computedStyle.display;
            const isVisible = currentDisplay === 'block';
            
            if (!isVisible) {
              // Force reflow to ensure accurate measurements
              void filterJenisPDRBInput.offsetWidth;
              
              const inputRect = filterJenisPDRBInput.getBoundingClientRect();
              
              if (!filterJenisPDRBDropdown.dataset.originalParent) {
                filterJenisPDRBDropdown.dataset.originalParent = 'true';
              }
              
              if (filterJenisPDRBDropdown.parentNode !== document.body) {
                document.body.appendChild(filterJenisPDRBDropdown);
              }
              
              // Calculate position
              const top = inputRect.bottom + 4;
              const left = inputRect.left;
              const width = inputRect.width;
              
              // Ensure dropdown doesn't go off screen
              const viewportWidth = window.innerWidth;
              const dropdownMaxWidth = viewportWidth - left - 20; // 20px margin from right edge
              
              filterJenisPDRBDropdown.style.position = 'fixed';
              filterJenisPDRBDropdown.style.top = top + 'px';
              filterJenisPDRBDropdown.style.left = left + 'px';
              filterJenisPDRBDropdown.style.right = 'auto';
              filterJenisPDRBDropdown.style.bottom = 'auto';
              filterJenisPDRBDropdown.style.width = Math.min(width, dropdownMaxWidth) + 'px';
              filterJenisPDRBDropdown.style.transform = 'none';
              filterJenisPDRBDropdown.style.display = 'block';
              filterJenisPDRBDropdown.style.zIndex = '1000';
            } else {
              filterJenisPDRBDropdown.style.display = 'none';
              if (filterJenisPDRBDropdown.parentNode === document.body && filterJenisPDRBWrapper) {
                filterJenisPDRBWrapper.appendChild(filterJenisPDRBDropdown);
              }
            }
          } catch (err) {
            console.error('Error in PDRB dropdown toggle:', err);
          }
        });
      } catch (err) {
        console.error('Error setting up PDRB dropdown listener:', err);
      }
      
      function updatePDRBDropdownPosition() {
        if (filterJenisPDRBDropdown && filterJenisPDRBDropdown.style.display === 'block' && filterJenisPDRBInput) {
          // Force reflow to ensure accurate measurements
          void filterJenisPDRBInput.offsetWidth;
          
          const inputRect = filterJenisPDRBInput.getBoundingClientRect();
          
          if (filterJenisPDRBDropdown.parentNode !== document.body) {
            document.body.appendChild(filterJenisPDRBDropdown);
          }
          
          // Calculate position
          const top = inputRect.bottom + 4;
          const left = inputRect.left;
          const width = inputRect.width;
          
          // Ensure dropdown doesn't go off screen
          const viewportWidth = window.innerWidth;
          const dropdownMaxWidth = viewportWidth - left - 20; // 20px margin from right edge
          
          filterJenisPDRBDropdown.style.position = 'fixed';
          filterJenisPDRBDropdown.style.top = top + 'px';
          filterJenisPDRBDropdown.style.left = left + 'px';
          filterJenisPDRBDropdown.style.right = 'auto';
          filterJenisPDRBDropdown.style.bottom = 'auto';
          filterJenisPDRBDropdown.style.width = Math.min(width, dropdownMaxWidth) + 'px';
          filterJenisPDRBDropdown.style.transform = 'none';
          filterJenisPDRBDropdown.style.zIndex = '1000';
        }
      }
      
      // Debounce resize handler
      let resizeTimeoutPDRB;
      window.addEventListener('resize', function() {
        clearTimeout(resizeTimeoutPDRB);
        resizeTimeoutPDRB = setTimeout(function() {
          updatePDRBDropdownPosition();
        }, 100);
      });
      
      window.addEventListener('scroll', function() {
        updatePDRBDropdownPosition();
      }, true);
    }

    // Close PDRB dropdown when clicking outside
    document.addEventListener('click', function(e) {
      if (filterJenisPDRBDropdown && filterJenisPDRBWrapper && 
          filterJenisPDRBDropdown.style.display === 'block') {
        const clickedInsideWrapper = filterJenisPDRBWrapper.contains(e.target);
        const clickedInsideDropdown = filterJenisPDRBDropdown.contains(e.target);
        
        if (!clickedInsideWrapper && !clickedInsideDropdown) {
          filterJenisPDRBDropdown.style.display = 'none';
          if (filterJenisPDRBDropdown.parentNode === document.body && filterJenisPDRBWrapper) {
            filterJenisPDRBWrapper.appendChild(filterJenisPDRBDropdown);
          }
        }
      }
    });

    // Handle PDRB option selection (single selection)
    if (filterJenisPDRBDropdown) {
      const filterOptionsPDRB = filterJenisPDRBDropdown.querySelectorAll('.filter-option-pdrb');
      filterOptionsPDRB.forEach(option => {
        option.addEventListener('click', function(e) {
          e.stopPropagation();
          e.preventDefault();
          const value = this.getAttribute('data-value');
          
          // Single selection - replace previous selection
          selectedJenisPDRB = value;
          
          // Update UI
          filterOptionsPDRB.forEach(opt => opt.classList.remove('selected'));
          this.classList.add('selected');
          
          // Update display
          if (filterJenisPDRBPlaceholder && filterJenisPDRBSelected) {
            filterJenisPDRBPlaceholder.style.display = 'none';
            filterJenisPDRBSelected.style.display = 'inline';
            filterJenisPDRBSelected.textContent = this.textContent.trim();
          }
          
          // Close dropdown after selection
          filterJenisPDRBDropdown.style.display = 'none';
          if (filterJenisPDRBDropdown.parentNode === document.body && filterJenisPDRBWrapper) {
            filterJenisPDRBWrapper.appendChild(filterJenisPDRBDropdown);
          }
          
          checkFilterValidity();
        });
      });
      
      filterJenisPDRBDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
      });
    }

    // Toggle dropdown with fixed positioning for Pengeluaran
    if (filterJenisPengeluaranInput && filterJenisPengeluaranDropdown && filterJenisPengeluaranWrapper) {
      try {
        filterJenisPengeluaranInput.addEventListener('click', function(e) {
          try {
            e.stopPropagation();
            e.preventDefault();
            
            const computedStyle = window.getComputedStyle(filterJenisPengeluaranDropdown);
            const currentDisplay = filterJenisPengeluaranDropdown.style.display || computedStyle.display;
            const isVisible = currentDisplay === 'block';
            
            if (!isVisible) {
              // Force reflow to ensure accurate measurements
              void filterJenisPengeluaranInput.offsetWidth;
              
              // Get position relative to viewport
              const inputRect = filterJenisPengeluaranInput.getBoundingClientRect();
              
              // Store original parent for later restoration
              if (!filterJenisPengeluaranDropdown.dataset.originalParent) {
                filterJenisPengeluaranDropdown.dataset.originalParent = 'true';
              }
              
              // Move to body temporarily to escape any parent clipping
              if (filterJenisPengeluaranDropdown.parentNode !== document.body) {
                document.body.appendChild(filterJenisPengeluaranDropdown);
              }
              
              // Calculate position
              const top = inputRect.bottom + 4;
              const left = inputRect.left;
              const width = inputRect.width;
              
              // Ensure dropdown doesn't go off screen
              const viewportWidth = window.innerWidth;
              const dropdownMaxWidth = viewportWidth - left - 20; // 20px margin from right edge
              
              // Use fixed positioning - getBoundingClientRect is already relative to viewport
              filterJenisPengeluaranDropdown.style.position = 'fixed';
              filterJenisPengeluaranDropdown.style.top = top + 'px';
              filterJenisPengeluaranDropdown.style.left = left + 'px';
              filterJenisPengeluaranDropdown.style.right = 'auto';
              filterJenisPengeluaranDropdown.style.bottom = 'auto';
              filterJenisPengeluaranDropdown.style.width = Math.min(width, dropdownMaxWidth) + 'px';
              filterJenisPengeluaranDropdown.style.transform = 'none';
              filterJenisPengeluaranDropdown.style.display = 'block';
              filterJenisPengeluaranDropdown.style.zIndex = '1000';
            } else {
              filterJenisPengeluaranDropdown.style.display = 'none';
              // Return to original parent when closing
              if (filterJenisPengeluaranDropdown.parentNode === document.body && filterJenisPengeluaranWrapper) {
                filterJenisPengeluaranWrapper.appendChild(filterJenisPengeluaranDropdown);
              }
            }
          } catch (err) {
            console.error('Error in dropdown toggle:', err);
          }
        });
      } catch (err) {
        console.error('Error setting up dropdown listener:', err);
      }
      
      // Update position on scroll/resize when dropdown is open
      function updateDropdownPosition() {
        if (filterJenisPengeluaranDropdown && filterJenisPengeluaranDropdown.style.display === 'block' && filterJenisPengeluaranInput) {
          // Force reflow to ensure accurate measurements
          void filterJenisPengeluaranInput.offsetWidth;
          
          const inputRect = filterJenisPengeluaranInput.getBoundingClientRect();
          
          // Ensure dropdown is in body
          if (filterJenisPengeluaranDropdown.parentNode !== document.body) {
            document.body.appendChild(filterJenisPengeluaranDropdown);
          }
          
          // Calculate position
          const top = inputRect.bottom + 4;
          const left = inputRect.left;
          const width = inputRect.width;
          
          // Ensure dropdown doesn't go off screen
          const viewportWidth = window.innerWidth;
          const dropdownMaxWidth = viewportWidth - left - 20; // 20px margin from right edge
          
          filterJenisPengeluaranDropdown.style.position = 'fixed';
          filterJenisPengeluaranDropdown.style.top = top + 'px';
          filterJenisPengeluaranDropdown.style.left = left + 'px';
          filterJenisPengeluaranDropdown.style.right = 'auto';
          filterJenisPengeluaranDropdown.style.bottom = 'auto';
          filterJenisPengeluaranDropdown.style.width = Math.min(width, dropdownMaxWidth) + 'px';
          filterJenisPengeluaranDropdown.style.transform = 'none';
          filterJenisPengeluaranDropdown.style.zIndex = '1000';
        }
      }
      
      // Debounce resize handler
      let resizeTimeoutPengeluaran;
      window.addEventListener('resize', function() {
        clearTimeout(resizeTimeoutPengeluaran);
        resizeTimeoutPengeluaran = setTimeout(function() {
          updateDropdownPosition();
        }, 100);
      });
      
      window.addEventListener('scroll', function() {
        updateDropdownPosition();
      }, true);
    }

    // Close dropdown when clicking outside (but not when clicking on dropdown itself)
    document.addEventListener('click', function(e) {
      if (filterJenisPengeluaranDropdown && filterJenisPengeluaranWrapper && 
          filterJenisPengeluaranDropdown.style.display === 'block') {
        // Check if click is outside both wrapper and dropdown
        const clickedInsideWrapper = filterJenisPengeluaranWrapper.contains(e.target);
        const clickedInsideDropdown = filterJenisPengeluaranDropdown.contains(e.target);
        
        if (!clickedInsideWrapper && !clickedInsideDropdown) {
          filterJenisPengeluaranDropdown.style.display = 'none';
          // Return to original parent when closing
          if (filterJenisPengeluaranDropdown.parentNode === document.body && filterJenisPengeluaranWrapper) {
            filterJenisPengeluaranWrapper.appendChild(filterJenisPengeluaranDropdown);
          }
        }
      }
    });

    // Handle option selection
    if (filterJenisPengeluaranDropdown) {
      const filterOptions = filterJenisPengeluaranDropdown.querySelectorAll('.filter-option');
      filterOptions.forEach(option => {
        option.addEventListener('click', function(e) {
          e.stopPropagation();
          e.preventDefault();
          const value = this.getAttribute('data-value');
          
          // Toggle selection
          if (selectedPengeluaran.includes(value)) {
            selectedPengeluaran = selectedPengeluaran.filter(v => v !== value);
            this.classList.remove('selected');
          } else {
            selectedPengeluaran.push(value);
            this.classList.add('selected');
          }
          
          updateTagsDisplay();
          checkFilterValidity();
          
          // Don't close dropdown after selection - allow multiple selections
        });
      });
      
      // Prevent dropdown from closing when clicking inside it
      filterJenisPengeluaranDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
      });
    }

    // Update tags display
    function updateTagsDisplay() {
      filterJenisPengeluaranTags.innerHTML = '';
      
      if (selectedPengeluaran.length === 0) {
        filterJenisPengeluaranPlaceholder.style.display = 'inline';
        filterJenisPengeluaranTags.style.display = 'none';
      } else {
        filterJenisPengeluaranPlaceholder.style.display = 'none';
        filterJenisPengeluaranTags.style.display = 'flex';
        
        selectedPengeluaran.forEach(value => {
          const tag = document.createElement('span');
          tag.className = 'filter-tag';
          tag.innerHTML = `
            <span>${value}</span>
            <button type="button" class="tag-remove" data-value="${value}">&times;</button>
          `;
          
          // Handle tag removal
          const removeBtn = tag.querySelector('.tag-remove');
          removeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const val = this.getAttribute('data-value');
            selectedPengeluaran = selectedPengeluaran.filter(v => v !== val);
            
            // Update option selection state
            const option = filterJenisPengeluaranDropdown.querySelector(`[data-value="${val}"]`);
            if (option) {
              option.classList.remove('selected');
            }
            
            updateTagsDisplay();
            checkFilterValidity();
          });
          
          filterJenisPengeluaranTags.appendChild(tag);
        });
      }
    }

    // Check validity
    function checkFilterValidity() {
      const jenisPDRBSelected = selectedJenisPDRB !== '';
      const atLeastOnePengeluaranSelected = selectedPengeluaran.length > 0;
      
      if (btnTerapkanFilterPDRB) {
        btnTerapkanFilterPDRB.disabled = !(jenisPDRBSelected && atLeastOnePengeluaranSelected);
      }
    }

    // Store filtered chart instance
    let filteredChartInstance = null;

    // Handle apply button click
    if (btnTerapkanFilterPDRB) {
      btnTerapkanFilterPDRB.addEventListener('click', function() {
        console.log('Jenis PDRB:', selectedJenisPDRB);
        console.log('Jenis Pengeluaran:', selectedPengeluaran);
        
        // Show chart section
        if (filteredChartSection) {
          filteredChartSection.style.display = 'block';
          
          // Select data based on jenis PDRB and determine unit
          let dataByCategory = {};
          let isPercentage = false;
          let isQuarterly = false;
          let unit = '';
          
          if (selectedJenisPDRB === 'ADHB') {
            dataByCategory = adhbByCategory;
            isPercentage = false;
            isQuarterly = false;
            unit = 'Rupiah';
          } else if (selectedJenisPDRB === 'ADHK') {
            dataByCategory = adhkByCategory;
            isPercentage = false;
            isQuarterly = false;
            unit = 'Rupiah';
          } else if (selectedJenisPDRB === 'Distribusi') {
            dataByCategory = distribusiByCategory;
            isPercentage = true;
            isQuarterly = false;
            unit = 'Persen';
          } else if (selectedJenisPDRB === 'Laju Pertumbuhan') {
            dataByCategory = lajuByCategory;
            isPercentage = true;
            isQuarterly = false;
            unit = 'Persen';
          }
          
          // Update chart title with unit
          const chartTitle = document.getElementById('filteredChartTitle');
          if (chartTitle) {
            chartTitle.innerHTML = `Grafik ${selectedJenisPDRB} - ${selectedPengeluaran.join(', ')} <span style="font-size: 14px; font-weight: normal; color: #666;">(${unit})</span>`;
          }
          
          // Dispose existing chart
          if (filteredChartInstance) {
            filteredChartInstance.dispose();
            filteredChartInstance = null;
          }
          
          // Create new chart with filtered categories
          setTimeout(() => {
            console.log('Data categories available:', Object.keys(dataByCategory));
            console.log('Selected pengeluaran:', selectedPengeluaran);
            filteredChartInstance = createLineChart('filteredChart', dataByCategory, isPercentage, isQuarterly, selectedPengeluaran);
            if (filteredChartInstance) {
              setTimeout(() => {
                filteredChartInstance.resize();
              }, 100);
            } else {
              console.error('Failed to create filtered chart');
            }
          }, 100);
          
          // Scroll to chart section
          setTimeout(() => {
            filteredChartSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }, 200);
        }
      });
    }

    // ========== Filter PDRB Pengeluaran Triwulanan Functionality ==========
    // Filter Jenis PDRB (Triwulanan) - Custom Dropdown
    const filterJenisPDRBTriwulananInput = document.getElementById('filterJenisPDRBTriwulananInput');
    const filterJenisPDRBTriwulananPlaceholder = document.getElementById('filterJenisPDRBTriwulananPlaceholder');
    const filterJenisPDRBTriwulananSelected = document.getElementById('filterJenisPDRBTriwulananSelected');
    const filterJenisPDRBTriwulananDropdown = document.getElementById('filterJenisPDRBTriwulananDropdown');
    const filterJenisPDRBTriwulananWrapper = document.getElementById('filterJenisPDRBTriwulananWrapper');
    const filterJenisPengeluaranTriwulananInput = document.getElementById('filterJenisPengeluaranTriwulananInput');
    const filterJenisPengeluaranTriwulananPlaceholder = document.getElementById('filterJenisPengeluaranTriwulananPlaceholder');
    const filterJenisPengeluaranTriwulananTags = document.getElementById('filterJenisPengeluaranTriwulananTags');
    const filterJenisPengeluaranTriwulananDropdown = document.getElementById('filterJenisPengeluaranTriwulananDropdown');
    const filterJenisPengeluaranTriwulananWrapper = document.getElementById('filterJenisPengeluaranTriwulananWrapper');
    const btnTerapkanFilterPDRBTriwulanan = document.getElementById('btnTerapkanFilterPDRBTriwulanan');
    const filteredChartSectionTriwulanan = document.getElementById('filteredChartSectionTriwulanan');

    let selectedJenisPDRBTriwulanan = '';
    let selectedPengeluaranTriwulanan = [];

    // ========== Filter Jenis PDRB (Triwulanan) Dropdown Functionality ==========
    // Toggle dropdown with fixed positioning for PDRB Triwulanan
    if (filterJenisPDRBTriwulananInput && filterJenisPDRBTriwulananDropdown && filterJenisPDRBTriwulananWrapper) {
      try {
        filterJenisPDRBTriwulananInput.addEventListener('click', function(e) {
          try {
            e.stopPropagation();
            e.preventDefault();
            
            const computedStyle = window.getComputedStyle(filterJenisPDRBTriwulananDropdown);
            const currentDisplay = filterJenisPDRBTriwulananDropdown.style.display || computedStyle.display;
            const isVisible = currentDisplay === 'block';
            
            if (!isVisible) {
              // Force reflow to ensure accurate measurements
              void filterJenisPDRBTriwulananInput.offsetWidth;
              
              const inputRect = filterJenisPDRBTriwulananInput.getBoundingClientRect();
              
              if (!filterJenisPDRBTriwulananDropdown.dataset.originalParent) {
                filterJenisPDRBTriwulananDropdown.dataset.originalParent = 'true';
              }
              
              if (filterJenisPDRBTriwulananDropdown.parentNode !== document.body) {
                document.body.appendChild(filterJenisPDRBTriwulananDropdown);
              }
              
              // Calculate position
              const top = inputRect.bottom + 4;
              const left = inputRect.left;
              const width = inputRect.width;
              
              // Ensure dropdown doesn't go off screen
              const viewportWidth = window.innerWidth;
              const dropdownMaxWidth = viewportWidth - left - 20; // 20px margin from right edge
              
              filterJenisPDRBTriwulananDropdown.style.position = 'fixed';
              filterJenisPDRBTriwulananDropdown.style.top = top + 'px';
              filterJenisPDRBTriwulananDropdown.style.left = left + 'px';
              filterJenisPDRBTriwulananDropdown.style.right = 'auto';
              filterJenisPDRBTriwulananDropdown.style.bottom = 'auto';
              filterJenisPDRBTriwulananDropdown.style.width = Math.min(width, dropdownMaxWidth) + 'px';
              filterJenisPDRBTriwulananDropdown.style.transform = 'none';
              filterJenisPDRBTriwulananDropdown.style.display = 'block';
              filterJenisPDRBTriwulananDropdown.style.zIndex = '1000';
            } else {
              filterJenisPDRBTriwulananDropdown.style.display = 'none';
              if (filterJenisPDRBTriwulananDropdown.parentNode === document.body && filterJenisPDRBTriwulananWrapper) {
                filterJenisPDRBTriwulananWrapper.appendChild(filterJenisPDRBTriwulananDropdown);
              }
            }
          } catch (err) {
            console.error('Error in PDRB Triwulanan dropdown toggle:', err);
          }
        });
      } catch (err) {
        console.error('Error setting up PDRB Triwulanan dropdown listener:', err);
      }
      
      function updatePDRBTriwulananDropdownPosition() {
        if (filterJenisPDRBTriwulananDropdown && filterJenisPDRBTriwulananDropdown.style.display === 'block' && filterJenisPDRBTriwulananInput) {
          // Force reflow to ensure accurate measurements
          void filterJenisPDRBTriwulananInput.offsetWidth;
          
          const inputRect = filterJenisPDRBTriwulananInput.getBoundingClientRect();
          
          if (filterJenisPDRBTriwulananDropdown.parentNode !== document.body) {
            document.body.appendChild(filterJenisPDRBTriwulananDropdown);
          }
          
          // Calculate position
          const top = inputRect.bottom + 4;
          const left = inputRect.left;
          const width = inputRect.width;
          
          // Ensure dropdown doesn't go off screen
          const viewportWidth = window.innerWidth;
          const dropdownMaxWidth = viewportWidth - left - 20; // 20px margin from right edge
          
          filterJenisPDRBTriwulananDropdown.style.position = 'fixed';
          filterJenisPDRBTriwulananDropdown.style.top = top + 'px';
          filterJenisPDRBTriwulananDropdown.style.left = left + 'px';
          filterJenisPDRBTriwulananDropdown.style.right = 'auto';
          filterJenisPDRBTriwulananDropdown.style.bottom = 'auto';
          filterJenisPDRBTriwulananDropdown.style.width = Math.min(width, dropdownMaxWidth) + 'px';
          filterJenisPDRBTriwulananDropdown.style.transform = 'none';
          filterJenisPDRBTriwulananDropdown.style.zIndex = '1000';
        }
      }
      
      // Debounce resize handler
      let resizeTimeoutPDRBTriwulanan;
      window.addEventListener('resize', function() {
        clearTimeout(resizeTimeoutPDRBTriwulanan);
        resizeTimeoutPDRBTriwulanan = setTimeout(function() {
          updatePDRBTriwulananDropdownPosition();
        }, 100);
      });
      
      window.addEventListener('scroll', function() {
        updatePDRBTriwulananDropdownPosition();
      }, true);
    }

    // Close PDRB Triwulanan dropdown when clicking outside
    document.addEventListener('click', function(e) {
      if (filterJenisPDRBTriwulananDropdown && filterJenisPDRBTriwulananWrapper && 
          filterJenisPDRBTriwulananDropdown.style.display === 'block') {
        const clickedInsideWrapper = filterJenisPDRBTriwulananWrapper.contains(e.target);
        const clickedInsideDropdown = filterJenisPDRBTriwulananDropdown.contains(e.target);
        
        if (!clickedInsideWrapper && !clickedInsideDropdown) {
          filterJenisPDRBTriwulananDropdown.style.display = 'none';
          if (filterJenisPDRBTriwulananDropdown.parentNode === document.body && filterJenisPDRBTriwulananWrapper) {
            filterJenisPDRBTriwulananWrapper.appendChild(filterJenisPDRBTriwulananDropdown);
          }
        }
      }
    });

    // Handle PDRB Triwulanan option selection (single selection)
    if (filterJenisPDRBTriwulananDropdown) {
      const filterOptionsPDRBTriwulanan = filterJenisPDRBTriwulananDropdown.querySelectorAll('.filter-option-pdrb-triwulanan');
      filterOptionsPDRBTriwulanan.forEach(option => {
        option.addEventListener('click', function(e) {
          e.stopPropagation();
          e.preventDefault();
          const value = this.getAttribute('data-value');
          
          // Single selection - replace previous selection
          selectedJenisPDRBTriwulanan = value;
          
          // Update UI
          filterOptionsPDRBTriwulanan.forEach(opt => opt.classList.remove('selected'));
          this.classList.add('selected');
          
          // Update display
          if (filterJenisPDRBTriwulananPlaceholder && filterJenisPDRBTriwulananSelected) {
            filterJenisPDRBTriwulananPlaceholder.style.display = 'none';
            filterJenisPDRBTriwulananSelected.style.display = 'inline';
            filterJenisPDRBTriwulananSelected.textContent = this.textContent.trim();
          }
          
          // Close dropdown after selection
          filterJenisPDRBTriwulananDropdown.style.display = 'none';
          if (filterJenisPDRBTriwulananDropdown.parentNode === document.body && filterJenisPDRBTriwulananWrapper) {
            filterJenisPDRBTriwulananWrapper.appendChild(filterJenisPDRBTriwulananDropdown);
          }
          
          checkFilterValidityTriwulanan();
        });
      });
      
      filterJenisPDRBTriwulananDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
      });
    }

    // Toggle dropdown with fixed positioning for Pengeluaran Triwulanan
    if (filterJenisPengeluaranTriwulananInput && filterJenisPengeluaranTriwulananDropdown && filterJenisPengeluaranTriwulananWrapper) {
      try {
        filterJenisPengeluaranTriwulananInput.addEventListener('click', function(e) {
          try {
            e.stopPropagation();
            e.preventDefault();
            
            const computedStyle = window.getComputedStyle(filterJenisPengeluaranTriwulananDropdown);
            const currentDisplay = filterJenisPengeluaranTriwulananDropdown.style.display || computedStyle.display;
            const isVisible = currentDisplay === 'block';
            
            if (!isVisible) {
              // Force reflow to ensure accurate measurements
              void filterJenisPengeluaranTriwulananInput.offsetWidth;
              
              const inputRect = filterJenisPengeluaranTriwulananInput.getBoundingClientRect();
              
              if (!filterJenisPengeluaranTriwulananDropdown.dataset.originalParent) {
                filterJenisPengeluaranTriwulananDropdown.dataset.originalParent = 'true';
              }
              
              if (filterJenisPengeluaranTriwulananDropdown.parentNode !== document.body) {
                document.body.appendChild(filterJenisPengeluaranTriwulananDropdown);
              }
              
              // Calculate position
              const top = inputRect.bottom + 4;
              const left = inputRect.left;
              const width = inputRect.width;
              
              // Ensure dropdown doesn't go off screen
              const viewportWidth = window.innerWidth;
              const dropdownMaxWidth = viewportWidth - left - 20; // 20px margin from right edge
              
              filterJenisPengeluaranTriwulananDropdown.style.position = 'fixed';
              filterJenisPengeluaranTriwulananDropdown.style.top = top + 'px';
              filterJenisPengeluaranTriwulananDropdown.style.left = left + 'px';
              filterJenisPengeluaranTriwulananDropdown.style.right = 'auto';
              filterJenisPengeluaranTriwulananDropdown.style.bottom = 'auto';
              filterJenisPengeluaranTriwulananDropdown.style.width = Math.min(width, dropdownMaxWidth) + 'px';
              filterJenisPengeluaranTriwulananDropdown.style.transform = 'none';
              filterJenisPengeluaranTriwulananDropdown.style.display = 'block';
              filterJenisPengeluaranTriwulananDropdown.style.zIndex = '1000';
            } else {
              filterJenisPengeluaranTriwulananDropdown.style.display = 'none';
              if (filterJenisPengeluaranTriwulananDropdown.parentNode === document.body && filterJenisPengeluaranTriwulananWrapper) {
                filterJenisPengeluaranTriwulananWrapper.appendChild(filterJenisPengeluaranTriwulananDropdown);
              }
            }
          } catch (err) {
            console.error('Error in dropdown toggle:', err);
          }
        });
      } catch (err) {
        console.error('Error setting up dropdown listener:', err);
      }
      
      function updateDropdownPositionTriwulanan() {
        if (filterJenisPengeluaranTriwulananDropdown && filterJenisPengeluaranTriwulananDropdown.style.display === 'block' && filterJenisPengeluaranTriwulananInput) {
          // Force reflow to ensure accurate measurements
          void filterJenisPengeluaranTriwulananInput.offsetWidth;
          
          const inputRect = filterJenisPengeluaranTriwulananInput.getBoundingClientRect();
          
          if (filterJenisPengeluaranTriwulananDropdown.parentNode !== document.body) {
            document.body.appendChild(filterJenisPengeluaranTriwulananDropdown);
          }
          
          // Calculate position
          const top = inputRect.bottom + 4;
          const left = inputRect.left;
          const width = inputRect.width;
          
          // Ensure dropdown doesn't go off screen
          const viewportWidth = window.innerWidth;
          const dropdownMaxWidth = viewportWidth - left - 20; // 20px margin from right edge
          
          filterJenisPengeluaranTriwulananDropdown.style.position = 'fixed';
          filterJenisPengeluaranTriwulananDropdown.style.top = top + 'px';
          filterJenisPengeluaranTriwulananDropdown.style.left = left + 'px';
          filterJenisPengeluaranTriwulananDropdown.style.right = 'auto';
          filterJenisPengeluaranTriwulananDropdown.style.bottom = 'auto';
          filterJenisPengeluaranTriwulananDropdown.style.width = Math.min(width, dropdownMaxWidth) + 'px';
          filterJenisPengeluaranTriwulananDropdown.style.transform = 'none';
          filterJenisPengeluaranTriwulananDropdown.style.zIndex = '1000';
        }
      }
      
      // Debounce resize handler
      let resizeTimeoutPengeluaranTriwulanan;
      window.addEventListener('resize', function() {
        clearTimeout(resizeTimeoutPengeluaranTriwulanan);
        resizeTimeoutPengeluaranTriwulanan = setTimeout(function() {
          updateDropdownPositionTriwulanan();
        }, 100);
      });
      
      window.addEventListener('scroll', function() {
        updateDropdownPositionTriwulanan();
      }, true);
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
      if (filterJenisPengeluaranTriwulananDropdown && filterJenisPengeluaranTriwulananWrapper && 
          filterJenisPengeluaranTriwulananDropdown.style.display === 'block') {
        const clickedInsideWrapper = filterJenisPengeluaranTriwulananWrapper.contains(e.target);
        const clickedInsideDropdown = filterJenisPengeluaranTriwulananDropdown.contains(e.target);
        
        if (!clickedInsideWrapper && !clickedInsideDropdown) {
          filterJenisPengeluaranTriwulananDropdown.style.display = 'none';
          if (filterJenisPengeluaranTriwulananDropdown.parentNode === document.body && filterJenisPengeluaranTriwulananWrapper) {
            filterJenisPengeluaranTriwulananWrapper.appendChild(filterJenisPengeluaranTriwulananDropdown);
          }
        }
      }
    });

    // Handle option selection for triwulanan
    if (filterJenisPengeluaranTriwulananDropdown) {
      const filterOptionsTriwulanan = filterJenisPengeluaranTriwulananDropdown.querySelectorAll('.filter-option-triwulanan');
      filterOptionsTriwulanan.forEach(option => {
        option.addEventListener('click', function(e) {
          e.stopPropagation();
          e.preventDefault();
          const value = this.getAttribute('data-value');
          
          if (selectedPengeluaranTriwulanan.includes(value)) {
            selectedPengeluaranTriwulanan = selectedPengeluaranTriwulanan.filter(v => v !== value);
            this.classList.remove('selected');
          } else {
            selectedPengeluaranTriwulanan.push(value);
            this.classList.add('selected');
          }
          
          updateTagsDisplayTriwulanan();
          checkFilterValidityTriwulanan();
        });
      });
      
      filterJenisPengeluaranTriwulananDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
      });
    }

    // Update tags display for triwulanan
    function updateTagsDisplayTriwulanan() {
      filterJenisPengeluaranTriwulananTags.innerHTML = '';
      
      if (selectedPengeluaranTriwulanan.length === 0) {
        filterJenisPengeluaranTriwulananPlaceholder.style.display = 'inline';
        filterJenisPengeluaranTriwulananTags.style.display = 'none';
      } else {
        filterJenisPengeluaranTriwulananPlaceholder.style.display = 'none';
        filterJenisPengeluaranTriwulananTags.style.display = 'flex';
        
        selectedPengeluaranTriwulanan.forEach(value => {
          const tag = document.createElement('span');
          tag.className = 'filter-tag';
          tag.innerHTML = `
            <span>${value}</span>
            <button type="button" class="tag-remove" data-value="${value}">&times;</button>
          `;
          
          const removeBtn = tag.querySelector('.tag-remove');
          removeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const val = this.getAttribute('data-value');
            selectedPengeluaranTriwulanan = selectedPengeluaranTriwulanan.filter(v => v !== val);
            
            const option = filterJenisPengeluaranTriwulananDropdown.querySelector(`[data-value="${val}"]`);
            if (option) {
              option.classList.remove('selected');
            }
            
            updateTagsDisplayTriwulanan();
            checkFilterValidityTriwulanan();
          });
          
          filterJenisPengeluaranTriwulananTags.appendChild(tag);
        });
      }
    }

    // Check validity for triwulanan
    function checkFilterValidityTriwulanan() {
      const jenisPDRBSelected = selectedJenisPDRBTriwulanan !== '';
      const atLeastOnePengeluaranSelected = selectedPengeluaranTriwulanan.length > 0;
      
      if (btnTerapkanFilterPDRBTriwulanan) {
        btnTerapkanFilterPDRBTriwulanan.disabled = !(jenisPDRBSelected && atLeastOnePengeluaranSelected);
      }
    }

    // Store filtered chart instance for triwulanan
    let filteredChartInstanceTriwulanan = null;

    // Handle apply button click for triwulanan
    if (btnTerapkanFilterPDRBTriwulanan) {
      btnTerapkanFilterPDRBTriwulanan.addEventListener('click', function() {
        console.log('Jenis PDRB Triwulanan:', selectedJenisPDRBTriwulanan);
        console.log('Jenis Pengeluaran Triwulanan:', selectedPengeluaranTriwulanan);
        
        if (filteredChartSectionTriwulanan) {
          filteredChartSectionTriwulanan.style.display = 'block';
          
          let dataByCategory = {};
          let isPercentage = false;
          let unit = '';
          
          if (selectedJenisPDRBTriwulanan === 'ADHB') {
            dataByCategory = adhbTriwulananByCategory;
            isPercentage = false;
            unit = 'Rupiah';
          } else if (selectedJenisPDRBTriwulanan === 'ADHK') {
            dataByCategory = adhkTriwulananByCategory;
            isPercentage = false;
            unit = 'Rupiah';
          } else if (selectedJenisPDRBTriwulanan === 'Laju Q-to-Q') {
            dataByCategory = lajuQtoQByCategory;
            isPercentage = true;
            unit = 'Persen';
          } else if (selectedJenisPDRBTriwulanan === 'Laju Y-to-Y') {
            dataByCategory = lajuYtoYByCategory;
            isPercentage = true;
            unit = 'Persen';
          } else if (selectedJenisPDRBTriwulanan === 'Laju C-to-C') {
            dataByCategory = lajuCtoCByCategory;
            isPercentage = true;
            unit = 'Persen';
          }
          
          const chartTitle = document.getElementById('filteredChartTitleTriwulanan');
          if (chartTitle) {
            chartTitle.innerHTML = `Grafik ${selectedJenisPDRBTriwulanan} Triwulanan - ${selectedPengeluaranTriwulanan.join(', ')} <span style="font-size: 14px; font-weight: normal; color: #666;">(${unit})</span>`;
          }
          
          if (filteredChartInstanceTriwulanan) {
            filteredChartInstanceTriwulanan.dispose();
            filteredChartInstanceTriwulanan = null;
          }
          
          setTimeout(() => {
            console.log('Data categories available:', Object.keys(dataByCategory));
            console.log('Selected pengeluaran:', selectedPengeluaranTriwulanan);
            // Use createLineChart with isQuarterly=true and selectedCategories for triwulanan filtered chart
            filteredChartInstanceTriwulanan = createLineChart('filteredChartTriwulanan', dataByCategory, isPercentage, true, selectedPengeluaranTriwulanan);
            if (filteredChartInstanceTriwulanan) {
              setTimeout(() => {
                filteredChartInstanceTriwulanan.resize();
              }, 100);
            } else {
              console.error('Failed to create filtered chart triwulanan');
            }
          }, 100);
          
          setTimeout(() => {
            filteredChartSectionTriwulanan.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }, 200);
        }
      });
    }

    // ========== Apply Colors to Carousel Cards ==========
    function applyCardColors() {
      const cardColors = [
        'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)', // Blue
        'linear-gradient(135deg, #10b981 0%, #059669 100%)', // Green
        'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)', // Orange
        'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)', // Red
        'linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)'  // Purple
      ];
      
      const cards = document.querySelectorAll('.indicator-card');
      cards.forEach((card, index) => {
        const colorIndex = index % cardColors.length;
        card.style.background = cardColors[colorIndex];
        card.style.color = 'white';
        
        // Update text colors
        const title = card.querySelector('.card-title');
        const subtitle = card.querySelector('.card-subtitle');
        const value = card.querySelector('.card-value');
        const year = card.querySelector('.card-year');
        const comparison = card.querySelector('#sheet-' + card.getAttribute('data-card-index') + '-comparison');
        
        if (title) title.style.color = 'rgba(255, 255, 255, 0.9)';
        if (subtitle) subtitle.style.color = 'rgba(255, 255, 255, 0.8)';
        if (value) value.style.color = 'white';
        if (year) year.style.color = 'rgba(255, 255, 255, 0.8)';
        if (comparison) {
          comparison.querySelectorAll('span').forEach(span => {
            span.style.color = 'rgba(255, 255, 255, 0.9)';
          });
        }
      });
    }
    
    // Apply colors after DOM is ready
    setTimeout(applyCardColors, 100);
    setTimeout(applyCardColors, 600);

    // ========== PDRB Sheet Carousel - Continuous Infinite Scroll to Right ==========
    let carouselAnimationId = null;
    let carouselCurrentPosition = 0;
    let carouselIsPaused = false;
    
    function initCarousel() {
      const carousel = document.getElementById('pdrbSheetCarousel');
      if (!carousel) return;

      const cards = carousel.querySelectorAll('.indicator-card');
      if (cards.length === 0) return;

      // Stop existing animation if any
      if (carouselAnimationId !== null) {
        cancelAnimationFrame(carouselAnimationId);
      }

      // Apply colors after cards are wrapped
      setTimeout(applyCardColors, 200);

      // Wrap existing cards in content set
      const originalContent = document.createElement('div');
      originalContent.className = 'indicator-carousel-content';
      originalContent.style.display = 'flex';
      originalContent.style.gap = '15px';
      originalContent.style.flexShrink = '0';
      originalContent.style.minWidth = 'fit-content';
      
      // Move existing cards to originalContent
      const cardsArray = Array.from(cards);
      cardsArray.forEach(card => {
        originalContent.appendChild(card);
      });

      // Create duplicate content set for seamless loop
      const duplicateContent = originalContent.cloneNode(true);
      duplicateContent.setAttribute('aria-hidden', 'true');

      // Clear carousel and add both content sets
      carousel.innerHTML = '';
      carousel.appendChild(originalContent);
      carousel.appendChild(duplicateContent);
      
      // Apply colors after content sets are added
      setTimeout(applyCardColors, 100);

      const contentSets = carousel.querySelectorAll(".indicator-carousel-content");
      if (contentSets.length < 2) return;

      // Get width of one content set
      function getContentSetWidth() {
        return contentSets[0] ? contentSets[0].offsetWidth + 15 : 0; // +15 for gap
      }

      // Reset position
      carouselCurrentPosition = 0;
      const scrollSpeed = 1.5; // pixels per frame (adjust for speed)

      function animate() {
        if (!carouselIsPaused) {
          const contentSetWidth = getContentSetWidth();
          
          if (contentSetWidth > 0) {
            // Move to the right (negative translateX = content moves right)
            carouselCurrentPosition += scrollSpeed;

            // When we've scrolled past one complete set, reset seamlessly
            if (carouselCurrentPosition >= contentSetWidth) {
              // Reset position without transition for seamless loop
              carouselCurrentPosition = carouselCurrentPosition - contentSetWidth;
            }

            carousel.style.transition = 'none';
            carousel.style.transform = `translateX(-${carouselCurrentPosition}px)`;
          }
        }

        carouselAnimationId = requestAnimationFrame(animate);
      }

      // Pause on hover
      const carouselWrapper = carousel.closest('.indicator-carousel-wrapper');
      if (carouselWrapper && !carouselWrapper.hasAttribute('data-carousel-init')) {
        carouselWrapper.setAttribute('data-carousel-init', 'true');
        carouselWrapper.addEventListener('mouseenter', () => {
          carouselIsPaused = true;
        });
        carouselWrapper.addEventListener('mouseleave', () => {
          carouselIsPaused = false;
        });
      }

      // Start animation
      animate();

      // Handle window resize
      let resizeTimeout;
      window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
          const contentSetWidth = getContentSetWidth();
          if (contentSetWidth > 0 && carouselCurrentPosition > 0) {
            carouselCurrentPosition = carouselCurrentPosition % contentSetWidth;
            carousel.style.transition = 'none';
            carousel.style.transform = `translateX(-${carouselCurrentPosition}px)`;
          }
        }, 250);
      });
    }

    // ========== Data Variables (loaded from API) ==========
    // All data is loaded via API in loadAllData() function
    // Variables are declared at the top and populated asynchronously
    
    let selectedYearDistribusi = null;
    let selectedYearDistribusiTriwulanan = null;
    let selectedQuarterDistribusiTriwulanan = null;
    let globalSelectedYearTriwulanan = null;

    // ========== Helper function to create line chart (7 years) ==========
    function createLineChart(canvasId, dataByCategory, isPercentage = false, isQuarterly = false, selectedCategories = []) {
      const chartDom = document.getElementById(canvasId);
      if (!chartDom) {
        console.error(`Chart element not found: ${canvasId}`);
        return null;
      }
      
      // Check if data is available
      if (!dataByCategory || Object.keys(dataByCategory).length === 0) {
        console.warn(`No data available for chart: ${canvasId}`);
        return null;
      }
      
      const chart = echarts.init(chartDom);
      
      // Filter categories
      let categories = Object.keys(dataByCategory);
      
      // If selectedCategories is provided and has items, use them directly (skip PDRB filtering)
      if (selectedCategories && Array.isArray(selectedCategories) && selectedCategories.length > 0) {
        categories = categories.filter(cat => {
          if (!cat) return false;
          // Try exact match first
          if (selectedCategories.includes(cat)) return true;
          // Try case-insensitive match
          const catUpper = cat.toUpperCase().trim();
          return selectedCategories.some(selected => {
            const selectedUpper = selected.toUpperCase().trim();
            // Exact match (case-insensitive)
            if (catUpper === selectedUpper) return true;
            // Partial match - check if category contains selected text or vice versa
            if (catUpper.includes(selectedUpper) || selectedUpper.includes(catUpper)) return true;
            return false;
          });
        });
      } else {
        // If no selectedCategories, filter categories - only show PDRB/Produk Domestik Regional Bruto
        const filteredCategories = categories.filter(cat => {
          if (!cat) return false;
          const catUpper = cat.toUpperCase().trim();
          return catUpper.includes('PDRB') || 
                 catUpper.includes('PRODUK DOMESTIK REGIONAL BRUTO') || 
                 catUpper.includes('GRDP') || 
                 catUpper === 'PDRB' ||
                 catUpper.startsWith('PDRB') ||
                 (catUpper.includes('PRODUK') && catUpper.includes('DOMESTIK') && catUpper.includes('REGIONAL')) ||
                 catUpper.includes('PRODUK DOMESTIK');
        });
        
        // If PDRB categories found, use them; otherwise show all (fallback)
        if (filteredCategories.length > 0) {
          categories = filteredCategories;
        }
      }
      
      // If no categories after filtering, show all categories (fallback)
      if (categories.length === 0) {
        categories = Object.keys(dataByCategory);
      }
      
      // Prepare x-axis data and series
      let xAxisData = [];
      let series = [];
      
      if (isQuarterly) {
        // For quarterly data, get quarters only (exclude TOTAL and Jumlah)
        const allQuarters = [];
        const validQuarters = ['I', 'II', 'III', 'IV']; // Only include these quarters
        Object.values(dataByCategory).forEach(dataList => {
          dataList.forEach(item => {
            // Exclude TOTAL, Jumlah, and any other non-standard quarters
            const quarterUpper = (item.quarter || '').toUpperCase().trim();
            if (quarterUpper === 'TOTAL' || quarterUpper === 'JUMLAH' || 
                quarterUpper === 'QTOTAL' || !validQuarters.includes(item.quarter)) {
              return; // Skip this quarter
            }
            
            const quarterKey = `${item.year}-${item.quarter}`;
            if (!allQuarters.find(q => q.year === item.year && q.quarter === item.quarter)) {
              allQuarters.push({ year: item.year, quarter: item.quarter });
            }
          });
        });
        
        // Sort quarters by year and quarter
        allQuarters.sort((a, b) => {
          if (a.year !== b.year) return a.year - b.year;
          const quarterOrder = { 'I': 1, 'II': 2, 'III': 3, 'IV': 4 };
          return (quarterOrder[a.quarter] || 0) - (quarterOrder[b.quarter] || 0);
        });
        
        // For filtered chart triwulanan, use last 8 quarters; otherwise use last 4 quarters
        const isFilteredChart = canvasId === 'filteredChartTriwulanan';
        const quarterCount = isFilteredChart ? 8 : 4;
        const selectedQuarters = allQuarters.slice(-quarterCount);
        
        // Create x-axis labels
        xAxisData = selectedQuarters.map(q => `${q.year} Q${q.quarter}`);
        
        // Prepare series data for each category
        series = categories.map((category, index) => {
          const categoryData = dataByCategory[category];
          const values = selectedQuarters.map(q => {
            const item = categoryData.find(d => d.year === q.year && d.quarter === q.quarter);
            return item ? item.value : null;
          });
          
          return {
            name: category.length > 30 ? category.substring(0, 30) + '...' : category,
            type: 'line',
            smooth: 0.4,
            data: values,
            lineStyle: {
              width: 2
            },
            symbol: 'circle',
            symbolSize: 6,
            itemStyle: {
              color: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#3b82f6', '#10b981', '#f59e0b'][index % 8]
            }
          };
        });
      } else {
        // For annual data, get last 7 years
        const allYearsSet = new Set();
        Object.values(dataByCategory).forEach(dataList => {
          dataList.forEach(item => allYearsSet.add(item.year));
        });
        const sortedYears = Array.from(allYearsSet).sort();
        const last7Years = sortedYears.slice(-7);
        xAxisData = last7Years.map(y => y.toString());
        
        // Prepare series data for each category
        series = categories.map((category, index) => {
          const categoryData = dataByCategory[category];
          const values = last7Years.map(year => {
            const item = categoryData.find(d => d.year === year);
            return item ? item.value : null;
          });

          return {
            name: category.length > 30 ? category.substring(0, 30) + '...' : category,
            type: 'line',
            smooth: 0.4,
            data: values,
            lineStyle: {
              width: 2
            },
            symbol: 'circle',
            symbolSize: 6,
            itemStyle: {
              color: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#3b82f6', '#10b981', '#f59e0b'][index % 8]
            }
          };
        });
      }


      const option = {
        tooltip: {
          trigger: 'axis',
          confine: true,
          position: function (point, params, dom, rect, size) {
            // size: {contentSize: [width, height], viewSize: [width, height]}
            // point: [x, y] - koordinat mouse
            // params: data array
            // dom: tooltip DOM element
            // rect: {x, y, width, height} - area chart
            
            const tooltipWidth = size.contentSize[0];
            const tooltipHeight = size.contentSize[1];
            const viewWidth = size.viewSize[0];
            const viewHeight = size.viewSize[1];
            
            // Hitung posisi x: selalu di tengah chart area
            const x = viewWidth / 2 - tooltipWidth / 2;
            
            // Hitung posisi y: di atas titik atau di bawah jika terlalu dekat ke atas
            let y = point[1] - tooltipHeight - 10; // 10px offset di atas titik
            
            // Jika tooltip terlalu dekat ke atas, letakkan di bawah titik
            if (y < 10) {
              y = point[1] + 20; // 20px offset di bawah titik
            }
            
            // Pastikan tooltip tidak keluar dari viewport
            if (y + tooltipHeight > viewHeight - 10) {
              y = viewHeight - tooltipHeight - 10;
            }
            
            return [x, y];
          },
          formatter: function(params) {
            const axisLabel = isQuarterly ? 'Periode: ' : 'Tahun: ';
            let result = axisLabel + params[0].axisValue + '<br/>';
            params.forEach(function(item) {
              if (item.value === null || item.value === undefined) {
                result += item.marker + item.seriesName + ': Data tidak tersedia<br/>';
              } else {
                const suffix = isPercentage ? '%' : (item.seriesName.includes('ADHB') || item.seriesName.includes('ADHK') ? ' (Rp)' : '');
                result += item.marker + item.seriesName + ': ' + parseFloat(item.value).toFixed(2) + suffix + '<br/>';
              }
            });
            return result;
          },
          backgroundColor: 'rgba(50, 50, 50, 0.9)',
          borderColor: '#333',
          borderWidth: 1,
          textStyle: {
            color: '#fff',
            fontSize: 12
          },
          padding: [8, 12],
          extraCssText: 'box-shadow: 0 2px 8px rgba(0,0,0,0.3); border-radius: 4px;'
        },
        legend: isQuarterly ? {
          data: series.map(s => s.name),
          top: 10,
          textStyle: {
            fontSize: 10
          },
          type: 'scroll'
        } : {
          show: false
        },
        grid: (function() {
          const isMobile = window.innerWidth <= 768;
          const isSmallMobile = window.innerWidth <= 480;
          const isFilteredChart = canvasId === 'filteredChart' || canvasId === 'filteredChartTriwulanan';
          
          // Increase bottom padding to prevent label cutoff
          // Filtered charts need extra space for rotated labels
          if (isQuarterly) {
            return {
              left: isMobile ? '18%' : '12%',
          right: '4%',
              bottom: isMobile ? '35%' : (isFilteredChart ? '28%' : '20%'),
              top: '20%',
          containLabel: false
            };
          }
          // For annual data with rotated labels - filtered charts need more space
          return {
            left: isMobile ? '18%' : '12%',
            right: '4%',
            bottom: isMobile ? (isSmallMobile ? '28%' : '25%') : (isFilteredChart ? '25%' : '18%'),
            top: '10%',
            containLabel: false
          };
        })(),
        xAxis: {
          type: 'category',
          boundaryGap: false,
          data: xAxisData,
          name: isQuarterly ? 'Triwulan' : 'Tahun',
          nameLocation: 'middle',
          nameGap: (function() {
            const isFilteredChart = canvasId === 'filteredChart' || canvasId === 'filteredChartTriwulanan';
            return isFilteredChart ? 40 : 35;
          })(),
          axisLabel: (function() {
            const isMobile = window.innerWidth <= 768;
            const isSmallMobile = window.innerWidth <= 480;
            
            if (isQuarterly) {
              return {
            rotate: 45,
                fontSize: 9,
                interval: 0,
                margin: 12
              };
            }
            
            // For annual data, always show all years
            if (isMobile) {
              return {
                rotate: isSmallMobile ? 45 : 0,
                fontSize: isSmallMobile ? 8 : 9,
                interval: 0, // Show all labels
                margin: isSmallMobile ? 12 : 8,
                formatter: function(value) {
                  // For very small screens, show abbreviated year
                  if (isSmallMobile && value && value.length === 4) {
                    return "'" + value.substring(2); // Show '18 for 2018
                  }
                  return value;
                }
              };
            }
            
            // Desktop: show all labels with rotation if needed
            const isFilteredChart = canvasId === 'filteredChart' || canvasId === 'filteredChartTriwulanan';
            return {
              interval: 0,
              rotate: 45,
              margin: isFilteredChart ? 15 : 12
            };
          })()
        },
        yAxis: {
          type: 'value',
          name: isPercentage ? 'Nilai (%)' : 'Nilai (Rp)',
          nameLocation: 'middle',
          nameGap: 60,
          axisLabel: {
            formatter: function(value) {
              if (isPercentage) {
                return value.toFixed(1) + '%';
              }
              if (value >= 1000000000000) {
                return (value / 1000000000000).toFixed(1) + 'T';
              } else if (value >= 1000000000) {
                return (value / 1000000000).toFixed(1) + 'M';
              } else if (value >= 1000000) {
                return (value / 1000000).toFixed(1) + 'J';
              }
              return value;
            }
          }
        },
        series: series
      };

      let hasData = false;
      if (option.series && option.series.length > 0) {
          hasData = option.series.some(s => {
              if (!s.data || s.data.length === 0) return false;
              return s.data.some(d => d !== null && d !== undefined && (typeof d === 'object' ? d.value !== null : true));
          });
      }
      
      if (!hasData) {
          chart.clear();
          chart.setOption({
              title: {
                  text: 'Data tidak tersedia',
                  left: 'center',
                  top: 'center',
                  textStyle: { color: '#999', fontSize: 16, fontWeight: 'normal' }
              }
          });
      } else {
          chart.setOption(option);
      }
      return chart;
    }

    // ========== Helper function to create triwulanan line chart (4 quarters, PDRB only) ==========
    function createTriwulananLineChart(canvasId, dataByCategory, isPercentage = false, selectedYear = null) {
      const chartDom = document.getElementById(canvasId);
      if (!chartDom) {
        console.error(`Chart element not found: ${canvasId}`);
        return null;
      }
      
      // Check if data is available
      if (!dataByCategory || Object.keys(dataByCategory).length === 0) {
        console.warn(`No data available for chart: ${canvasId}`);
        return null;
      }
      
      const chart = echarts.init(chartDom);
      
      // Filter categories - only show PDRB/Produk Domestik Regional Bruto
      // Exclude all expenditure components
      let categories = Object.keys(dataByCategory);
      
      // Debug: log all available categories
      console.log(`[${canvasId}] All available categories:`, categories);
      
      // List of specific expenditure component patterns to exclude (exact matches from console log)
      const expenditurePatterns = [
        'KONSUMSI AKHIR RUMAH TANGGA',
        'KONSUMSI AKHIR PEMERINTAH',
        'KONSUMSI AKHIR',
        'PENGELUARAN KONSUMSI RUMAH TANGGA',
        'PENGELUARAN KONSUMSI LNPRT',
        'PENGELUARAN KONSUMSI PEMERINTAH',
        'PEMBENTUKAN MODAL TETAP BRUTO',
        'PERUBAHAN INVENTORI',
        'NET EKSPOR BARANG DAN JASA',
        'LAINNYA'
      ];
      
      // First, try to find PDRB category
      let pdrbCategory = null;
      
      // Look for exact match first
      for (const cat of categories) {
        if (!cat || cat.trim() === '') continue;
        const catUpper = cat.toUpperCase().trim();
        
        // Check if it's an expenditure component
        const isExpenditure = expenditurePatterns.some(pattern => {
          return catUpper === pattern || catUpper.includes(pattern);
        });
        
        if (isExpenditure) {
          continue; // Skip expenditure components
        }
        
        // Check if it's PDRB - be very flexible
        const isPDRB = catUpper === 'PDRB' || 
                       catUpper === 'PRODUK DOMESTIK REGIONAL BRUTO' ||
                       catUpper.includes('PRODUK DOMESTIK REGIONAL BRUTO') ||
                       (catUpper.includes('PRODUK') && 
                        catUpper.includes('DOMESTIK') && 
                        catUpper.includes('REGIONAL') && 
                        catUpper.includes('BRUTO'));
        
        if (isPDRB) {
          pdrbCategory = cat;
          console.log(`[${canvasId}] Found PDRB category:`, cat);
          break;
        }
      }
      
      // If PDRB found, use it; otherwise calculate from expenditure components
      if (pdrbCategory) {
        categories = [pdrbCategory];
        console.log(`[${canvasId}] Using PDRB category:`, categories);
      } else {
        console.warn(`[${canvasId}] No PDRB category found. Available categories:`, categories);
        console.log(`[${canvasId}] Calculating PDRB from expenditure components...`);
        
        // Calculate PDRB as sum of all expenditure components
        const calculatedPDRB = {};
        const allQuartersSet = new Set();
        const validQuarters = ['I', 'II', 'III', 'IV']; // Only include these quarters
        
        // Get all quarters from all categories (exclude TOTAL and Jumlah)
        Object.values(dataByCategory).forEach(dataList => {
          dataList.forEach(item => {
            // Exclude TOTAL, Jumlah, and any other non-standard quarters
            const quarterUpper = (item.quarter || '').toUpperCase().trim();
            if (quarterUpper === 'TOTAL' || quarterUpper === 'JUMLAH' || 
                quarterUpper === 'QTOTAL' || !validQuarters.includes(item.quarter)) {
              return; // Skip this quarter
            }
            
            const quarterKey = `${item.year}-${item.quarter}`;
            allQuartersSet.add(quarterKey);
          });
        });
        
        // Calculate PDRB for each quarter
        allQuartersSet.forEach(quarterKey => {
          const [year, quarter] = quarterKey.split('-');
          let total = 0;
          
          // Sum all expenditure components for this quarter
          Object.keys(dataByCategory).forEach(cat => {
            const catUpper = cat.toUpperCase().trim();
            const isExpenditure = expenditurePatterns.some(pattern => {
              return catUpper === pattern || catUpper.includes(pattern);
            });
            
            if (isExpenditure) {
              const categoryData = dataByCategory[cat];
              const item = categoryData.find(d => d.year === parseInt(year) && d.quarter === quarter);
              if (item && item.value !== null && item.value !== undefined) {
                total += item.value;
              }
            }
          });
          
          if (!calculatedPDRB[quarterKey]) {
            calculatedPDRB[quarterKey] = [];
          }
          calculatedPDRB[quarterKey].push({
            year: parseInt(year),
            quarter: quarter,
            value: total,
            preliminary_flag: ''
          });
        });
        
        // Convert to array format
        const calculatedPDRBArray = [];
        Object.keys(calculatedPDRB).sort().forEach(quarterKey => {
          calculatedPDRB[quarterKey].forEach(item => {
            calculatedPDRBArray.push(item);
          });
        });
        
        // Add calculated PDRB to dataByCategory
        if (calculatedPDRBArray.length > 0) {
          dataByCategory['Produk Domestik Regional Bruto'] = calculatedPDRBArray;
          categories = ['Produk Domestik Regional Bruto'];
          console.log(`[${canvasId}] Calculated PDRB from expenditure components:`, calculatedPDRBArray);
        } else {
          categories = [];
          console.warn(`[${canvasId}] Could not calculate PDRB. No data available.`);
        }
      }
      
      // Prepare x-axis data and series
      let xAxisData = [];
      let series = [];
      
      // Get all quarters from data, excluding TOTAL and Jumlah
      const allQuarters = [];
      const validQuarters = ['I', 'II', 'III', 'IV']; // Only include these quarters
      Object.values(dataByCategory).forEach(dataList => {
        dataList.forEach(item => {
          // Exclude TOTAL, Jumlah, and any other non-standard quarters
          const quarterUpper = (item.quarter || '').toUpperCase().trim();
          if (quarterUpper === 'TOTAL' || quarterUpper === 'JUMLAH' || 
              quarterUpper === 'QTOTAL' || !validQuarters.includes(item.quarter)) {
            return; // Skip this quarter
          }
          
          const quarterKey = `${item.year}-${item.quarter}`;
          if (!allQuarters.find(q => q.year === item.year && q.quarter === item.quarter)) {
            allQuarters.push({ year: item.year, quarter: item.quarter });
          }
        });
      });
      
      // Sort quarters by year and quarter
      allQuarters.sort((a, b) => {
        if (a.year !== b.year) return a.year - b.year;
        const quarterOrder = { 'I': 1, 'II': 2, 'III': 3, 'IV': 4 };
        return (quarterOrder[a.quarter] || 0) - (quarterOrder[b.quarter] || 0);
      });
      
      // Filter by selected year or get last 4 quarters
      let selectedQuarters = [];
      if (selectedYear) {
        // Get all quarters from selected year
        selectedQuarters = allQuarters.filter(q => q.year === selectedYear);
      } else {
        // Get last 4 quarters
        selectedQuarters = allQuarters.slice(-4);
      }
      
      // Create x-axis labels
      xAxisData = selectedQuarters.map(q => `${q.year} Q${q.quarter}`);
      
      // Prepare series data for each category (only PDRB categories)
      // Ensure we only process categories that passed the filter
      series = categories.map((category, index) => {
        // Double-check: ensure category exists in dataByCategory
        if (!dataByCategory[category]) {
          return null;
        }
        
        const categoryData = dataByCategory[category];
        const values = selectedQuarters.map(q => {
          const item = categoryData.find(d => d.year === q.year && d.quarter === q.quarter);
          return item ? item.value : null;
        });
        
        return {
          name: category.length > 30 ? category.substring(0, 30) + '...' : category,
          type: 'line',
          smooth: 0.4,
          data: values,
          lineStyle: {
            width: 2
          },
          symbol: 'circle',
          symbolSize: 6,
          itemStyle: {
              color: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#3b82f6', '#10b981', '#f59e0b'][index % 8]
          }
        };
      }).filter(s => s !== null); // Remove any null entries

      const option = {
        tooltip: {
          trigger: 'axis',
          confine: true,
          position: function (point, params, dom, rect, size) {
            const tooltipWidth = size.contentSize[0];
            const tooltipHeight = size.contentSize[1];
            const viewWidth = size.viewSize[0];
            const viewHeight = size.viewSize[1];
            
            const x = viewWidth / 2 - tooltipWidth / 2;
            let y = point[1] - tooltipHeight - 10;
            
            if (y < 10) {
              y = point[1] + 20;
            }
            
            if (y + tooltipHeight > viewHeight - 10) {
              y = viewHeight - tooltipHeight - 10;
            }
            
            return [x, y];
          },
          formatter: function(params) {
            let result = 'Periode: ' + params[0].axisValue + '<br/>';
            params.forEach(function(item) {
              if (item.value === null || item.value === undefined) {
                result += item.marker + item.seriesName + ': Data tidak tersedia<br/>';
              } else {
                const suffix = isPercentage ? '%' : ' (Rp)';
                result += item.marker + item.seriesName + ': ' + parseFloat(item.value).toFixed(2) + suffix + '<br/>';
              }
            });
            return result;
          },
          backgroundColor: 'rgba(50, 50, 50, 0.9)',
          borderColor: '#333',
          borderWidth: 1,
          textStyle: {
            color: '#fff',
            fontSize: 12
          },
          padding: [8, 12],
          extraCssText: 'box-shadow: 0 2px 8px rgba(0,0,0,0.3); border-radius: 4px;'
        },
        legend: {
          show: false
        },
        grid: (function() {
          const isMobile = window.innerWidth <= 768;
          return {
            left: isMobile ? '18%' : '12%',
          right: '4%',
            bottom: isMobile ? '30%' : '22%',
          top: '10%',
          containLabel: false
          };
        })(),
        xAxis: {
          type: 'category',
          boundaryGap: false,
          data: xAxisData,
          name: 'Triwulan',
          nameLocation: 'middle',
          nameGap: 35,
          axisLabel: {
            rotate: 45,
            fontSize: 9,
            interval: 0,
            margin: 12
          }
        },
        yAxis: {
          type: 'value',
          name: isPercentage ? 'Nilai (%)' : 'Nilai (Rp)',
          nameLocation: 'middle',
          nameGap: 60,
          axisLabel: {
            formatter: function(value) {
              if (isPercentage) {
                return value.toFixed(1) + '%';
              }
              if (value >= 1000000000000) {
                return (value / 1000000000000).toFixed(1) + 'T';
              } else if (value >= 1000000000) {
                return (value / 1000000000).toFixed(1) + 'M';
              } else if (value >= 1000000) {
                return (value / 1000000).toFixed(1) + 'J';
              }
              return value;
            }
          }
        },
        series: series
      };

      chart.setOption(option);
      return chart;
    }

    // ========== Helper function to create horizontal bar chart for Distribusi Tahunan (without PDRB/GRDP) ==========
    function createDistribusiPieChartTahunan(canvasId, dataByCategory, selectedYear) {
      const chartDom = document.getElementById(canvasId);
      if (!chartDom) return null;
      
      const chart = echarts.init(chartDom);
      
      let filteredData = {};
      
      const allYears = [];
      Object.values(dataByCategory).forEach(dataList => {
        dataList.forEach(item => {
          if (!allYears.includes(item.year)) {
            allYears.push(item.year);
          }
        });
      });
      
      allYears.sort((a, b) => b - a);
      
      let targetYear = selectedYear;
      if (!targetYear) {
        if (allYears.length > 0) {
          targetYear = allYears[0];
        } else {
          console.warn('No data available for bar chart');
          return null;
        }
      }
      
      Object.keys(dataByCategory).forEach(category => {
        if (category.toUpperCase().includes('PDRB') || category.toUpperCase().includes('GRDP')) {
          return;
        }
        
        const yearData = dataByCategory[category].filter(d => parseInt(d.year) === parseInt(targetYear));
        if (yearData.length > 0) {
          filteredData[category] = yearData;
        }
      });
      
      const categories = Object.keys(filteredData);
      let barData = [];
      let barLabels = [];
      
      categories.forEach(category => {
        const item = filteredData[category][0];
        if (item && item.value !== null && item.value !== undefined) {
          barData.push(parseFloat(item.value));
          barLabels.push(category);
        }
      });
      
      if (barData.length === 0) {
        return null;
      }

      // Sort by value descending for better visualization
      const combined = barLabels.map((label, i) => ({ label, value: barData[i] }));
      combined.sort((a, b) => b.value - a.value);
      barLabels = combined.map(c => c.label);
      barData = combined.map(c => c.value);

      // Use full labels and let echarts wrap them
      const truncatedLabels = barLabels;
      
      const isMobile = window.innerWidth < 768;
      const barColors = ['#003f5c', '#2f4b7c', '#665191', '#a05195', '#d45087', '#f95d6a', '#ff7c43', '#ffa600'];
      
      const option = {
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'shadow'
          },
          confine: true,
          formatter: function(params) {
            const idx = params[0].dataIndex;
            const fullName = barLabels[idx];
            return fullName + '<br/>' + 
                   params[0].marker + 'Distribusi ' + targetYear + ': ' + 
                   parseFloat(params[0].value).toFixed(2) + '%';
          },
          backgroundColor: 'rgba(50, 50, 50, 0.9)',
          borderColor: '#333',
          borderWidth: 1,
          textStyle: {
            color: '#fff',
            fontSize: 12
          },
          padding: [8, 12],
          extraCssText: 'box-shadow: 0 2px 8px rgba(0,0,0,0.3); border-radius: 4px;'
        },
        grid: {
          left: '3%',
          right: '5%',
          bottom: '5%',
          top: '15%',
          containLabel: true
        },
        xAxis: {
          type: 'category',
          data: truncatedLabels,
          axisLabel: {
            fontSize: isMobile ? 9 : 10,
            color: '#333',
            rotate: 45,
            interval: 0,
            width: isMobile ? 90 : 140,
            overflow: 'break'
          },
          axisTick: {
            show: false
          },
          axisLine: {
            show: true,
            lineStyle: {
              color: '#ccc'
            }
          }
        },
        yAxis: {
          type: 'value',
          axisLabel: {
            formatter: function(value) {
              return value.toFixed(0) + '%';
            },
            fontSize: isMobile ? 9 : 11
          },
          splitLine: {
            lineStyle: {
              type: 'dashed',
              color: '#e8e8e8'
            }
          }
        },
        series: [{
          name: 'Distribusi ' + targetYear,
          type: 'bar',
          data: barData.map((value, index) => {
            return {
              value: value,
              itemStyle: {
                color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                  { offset: 0, color: barColors[index % barColors.length] },
                  { offset: 1, color: barColors[index % barColors.length] + 'cc' }
                ]),
                borderRadius: [4, 4, 0, 0]
              }
            };
          }),
          barWidth: isMobile ? '40%' : '50%',
          label: {
            show: true,
            position: 'top',
            formatter: function(params) {
              return parseFloat(params.value).toFixed(2) + '%';
            },
            fontSize: isMobile ? 8 : 10,
            color: '#333',
            fontWeight: 500
          },
          emphasis: {
            itemStyle: {
              shadowBlur: 10,
              shadowOffsetX: 0,
              shadowColor: 'rgba(0, 0, 0, 0.3)'
            }
          }
        }]
      };
      
      chart.setOption(option);
      return chart;
    }
    function createDistribusiBarChart(canvasId, dataByCategory, selectedYear, isQuarterly = false) {
      const chartDom = document.getElementById(canvasId);
      if (!chartDom) return null;
      
      const chart = echarts.init(chartDom);
      
      // Filter data by selected year and exclude PDRB/GRDP
      let filteredData = {};
      if (selectedYear) {
        Object.keys(dataByCategory).forEach(category => {
          // Exclude PDRB and GRDP
          const categoryUpper = category.toUpperCase();
          if (categoryUpper.includes('PDRB') || categoryUpper.includes('GRDP')) {
            return;
          }
          
          const categoryData = dataByCategory[category];
          if (isQuarterly) {
            const yearData = categoryData.filter(d => d.year === selectedYear);
            if (yearData.length > 0) {
              filteredData[category] = yearData;
            }
          } else {
            const yearData = categoryData.find(d => d.year === selectedYear);
            if (yearData) {
              filteredData[category] = [yearData];
            }
          }
        });
      } else {
        // Get latest year data for each category (excluding PDRB/GRDP)
        Object.keys(dataByCategory).forEach(category => {
          // Exclude PDRB and GRDP
          const categoryUpper = category.toUpperCase();
          if (categoryUpper.includes('PDRB') || categoryUpper.includes('GRDP')) {
            return;
          }
          
          const categoryData = dataByCategory[category];
          if (categoryData.length > 0) {
            if (isQuarterly) {
              const latestYear = Math.max(...categoryData.map(d => d.year));
              filteredData[category] = categoryData.filter(d => d.year === latestYear);
            } else {
              filteredData[category] = [categoryData[categoryData.length - 1]];
            }
          }
        });
      }

      // Prepare chart data
      const categories = Object.keys(filteredData);
      let chartData = [];
      let labels = [];

      if (isQuarterly) {
        // For quarterly data, show by quarter
        const quarters = ['I', 'II', 'III', 'IV'];
        labels = quarters;
        categories.forEach(category => {
          const values = quarters.map(q => {
            const item = filteredData[category].find(d => d.quarter === q);
            return item ? item.value : null;
          });
          chartData.push({
            name: category.length > 30 ? category.substring(0, 30) + '...' : category,
            data: values
          });
        });
      } else {
        // For annual data, show by category
        labels = categories.map(cat => cat.length > 40 ? cat.substring(0, 40) + '...' : cat);
        chartData = categories.map(category => {
          const item = filteredData[category][0];
          return item ? item.value : null;
        });
      }

      // If no categories or chartData, return null
      if (categories.length === 0 || chartData.length === 0) {
        console.warn(`No data to display for chart: ${canvasId}`);
        return null;
      }

      const option = {
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'shadow'
          },
          confine: true,
          position: function (point, params, dom, rect, size) {
            // Always center tooltip horizontally
            const tooltipWidth = size.contentSize[0];
            const tooltipHeight = size.contentSize[1];
            const viewWidth = size.viewSize[0];
            const viewHeight = size.viewSize[1];
            
            // Hitung posisi x: selalu di tengah chart area
            const x = viewWidth / 2 - tooltipWidth / 2;
            
            // Hitung posisi y: di atas titik atau di bawah jika terlalu dekat ke atas
            let y = point[1] - tooltipHeight - 10; // 10px offset di atas titik
            
            // Jika tooltip terlalu dekat ke atas, letakkan di bawah titik
            if (y < 10) {
              y = point[1] + 20; // 20px offset di bawah titik
            }
            
            // Pastikan tooltip tidak keluar dari viewport
            if (y + tooltipHeight > viewHeight - 10) {
              y = viewHeight - tooltipHeight - 10;
            }
            
            return [x, y];
          },
          formatter: function(params) {
            if (isQuarterly) {
              let result = params[0].name + '<br/>';
              params.forEach(function(item) {
                if (item.value === null || item.value === undefined) {
                  result += item.marker + item.seriesName + ': Data tidak tersedia<br/>';
                } else {
                  result += item.marker + item.seriesName + ': ' + parseFloat(item.value).toFixed(2) + '%<br/>';
                }
              });
              return result;
            } else {
              return params[0].name + '<br/>' + 
                     params[0].marker + params[0].seriesName + ': ' + 
                     parseFloat(params[0].value).toFixed(2) + '%';
            }
          },
          backgroundColor: 'rgba(50, 50, 50, 0.9)',
          borderColor: '#333',
          borderWidth: 1,
          textStyle: {
            color: '#fff',
            fontSize: 12
          },
          padding: [8, 12],
          extraCssText: 'box-shadow: 0 2px 8px rgba(0,0,0,0.3); border-radius: 4px;'
        },
        grid: {
          left: '15%',
          right: '4%',
          bottom: isQuarterly ? '20%' : '28%',
          top: '10%',
          containLabel: false
        },
        xAxis: {
          type: isQuarterly ? 'category' : 'category',
          data: isQuarterly ? labels : labels,
          name: isQuarterly ? 'Triwulan' : null,
          nameLocation: 'middle',
          nameGap: 35,
          axisLabel: {
            fontSize: isQuarterly ? 9 : 9,
            interval: 0,
            rotate: isQuarterly ? 0 : 45,
            margin: isQuarterly ? 8 : 12,
            color: '#333',
            fontWeight: 'normal',
            formatter: function(value) {
              if (isQuarterly) {
                return value;
              }
              if (value && value.length > 30) {
                return value.substring(0, 30) + '...';
              }
              return value || '';
            }
          },
          axisLine: {
            show: true,
            lineStyle: {
              color: '#666',
              width: 1
            }
          },
          axisTick: {
            show: true,
            alignWithLabel: true
          }
        },
        yAxis: {
          type: 'value',
          name: isQuarterly ? 'Nilai (%)' : 'Nilai (%)',
          nameLocation: 'middle',
          nameGap: 60,
          axisLabel: {
            formatter: function(value) {
              return value.toFixed(1) + '%';
            }
          }
        },
        series: isQuarterly ? chartData.map((item, index) => ({
          name: item.name,
          type: 'bar',
          data: item.data,
          itemStyle: {
            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
              { offset: 0, color: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'][index % 4] },
              { offset: 1, color: ['#2563eb', '#059669', '#d97706', '#dc2626'][index % 4] }
            ])
          }
        })) : [{
          name: 'Nilai (%)',
          type: 'bar',
          data: chartData,
          itemStyle: {
            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
              { offset: 0, color: '#3b82f6' },
              { offset: 1, color: '#2563eb' }
            ])
          },
          label: {
            show: true,
            position: 'top',
            formatter: function(params) {
              return parseFloat(params.value).toFixed(2) + '%';
            },
            fontSize: 8
          }
        }]
      };

      chart.setOption(option);
      return chart;
    }

    // ========== Helper function to create pie chart for Distribusi Triwulanan ==========
    function createDistribusiPieChart(canvasId, dataByCategory, selectedYear, selectedQuarter) {
      const chartDom = document.getElementById(canvasId);
      if (!chartDom) return null;
      
      const chart = echarts.init(chartDom);
      
      // Filter data by selected year and quarter, exclude PDRB/GRDP
      let filteredData = {};
      
      // Get all available years and quarters to find latest
      const allYearQuarterPairs = [];
      Object.values(dataByCategory).forEach(dataList => {
        dataList.forEach(item => {
          if (item.quarter && ['I', 'II', 'III', 'IV'].includes(item.quarter)) {
            const pair = `${item.year}-${item.quarter}`;
            if (!allYearQuarterPairs.includes(pair)) {
              allYearQuarterPairs.push(pair);
            }
          }
        });
      });
      
      // Sort to find latest year-quarter
      allYearQuarterPairs.sort((a, b) => {
        const [yearA, quarterA] = a.split('-');
        const [yearB, quarterB] = b.split('-');
        if (yearA !== yearB) return parseInt(yearB) - parseInt(yearA);
        const quarterOrder = { 'I': 1, 'II': 2, 'III': 3, 'IV': 4 };
        return quarterOrder[quarterB] - quarterOrder[quarterA];
      });
      
      // Determine which year and quarter to use
      let targetYear = selectedYear;
      let targetQuarter = selectedQuarter;
      
      if (!targetYear || !targetQuarter) {
        // Use latest available year-quarter
        if (allYearQuarterPairs.length > 0) {
          const [latestYear, latestQuarter] = allYearQuarterPairs[0].split('-');
          targetYear = targetYear || parseInt(latestYear);
          targetQuarter = targetQuarter || latestQuarter;
        } else {
          console.warn('No data available for pie chart');
          return null;
        }
      }
      
      // Filter data by year and quarter, exclude PDRB/GRDP
      Object.keys(dataByCategory).forEach(category => {
        // Exclude PDRB and GRDP
        const categoryUpper = category.toUpperCase();
        if (categoryUpper.includes('PDRB') || categoryUpper.includes('GRDP')) {
          return;
        }
        
        const categoryData = dataByCategory[category];
        const item = categoryData.find(d => d.year === parseInt(targetYear) && d.quarter === targetQuarter);
        if (item && item.value !== null && item.value !== undefined) {
          filteredData[category] = item.value;
        }
      });
      
      // Prepare pie chart data
      const pieData = Object.keys(filteredData).map((category, index) => {
        return {
          name: category.length > 40 ? category.substring(0, 40) + '...' : category,
          value: filteredData[category]
        };
      });
      
      if (pieData.length === 0) {
        console.warn(`No data to display for pie chart: ${canvasId}`);
        return null;
      }
      
      // Color palette - matching card colors
      const colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
      
      // Extract legend data from pieData
      const legendData = pieData.map(item => item.name);
      const isMobile = window.innerWidth < 768;
      const option = {
        tooltip: {
          trigger: 'item',
          formatter: function(params) {
            return params.name + '<br/>' + 
                   params.marker + params.seriesName + ': ' + 
                   parseFloat(params.value).toFixed(2) + '%';
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
            if (name.length > 40) {
              return name.substring(0, 40) + '...';
            }
            return name;
          }
        },
        series: [{
          name: 'Distribusi',
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
            label: {
              show: false
            },
            itemStyle: {
              shadowBlur: 10,
              shadowOffsetX: 0,
              shadowColor: 'rgba(0, 0, 0, 0.5)'
            }
          },
          data: pieData.map((item, index) => ({
            ...item,
            itemStyle: {
              color: colors[index % colors.length]
            }
          }))
        }]
      };

      chart.setOption(option);
      return chart;
    }

    // Store chart instances
    let chartInstances = {};

    // Function to update all charts based on current view
    function updateAllCharts() {
      // Destroy existing charts
      Object.values(chartInstances).forEach(chart => {
        if (chart && chart.dispose) chart.dispose();
      });
      chartInstances = {};

      if (typeof currentView === 'undefined' || currentView === 'tahunan') {
        // Create charts for tahunan
        // Other charts use line chart (7 years)
        chartInstances.adhb = createLineChart('adhbChart', adhbByCategory, false, false, []);
        chartInstances.adhk = createLineChart('adhkChart', adhkByCategory, false, false, []);
        chartInstances.distribusi = createDistribusiPieChartTahunan('distribusiChart', distribusiByCategory, selectedYearDistribusi, false);
        chartInstances.laju = createLineChart('lajuChart', lajuByCategory, true, false, []);
      } else {
        // Create charts for triwulanan - all line charts only show PDRB and last 4 quarters
        chartInstances.adhbTriwulanan = createTriwulananLineChart('adhbTriwulananChart', adhbTriwulananByCategory, false, globalSelectedYearTriwulanan);
        chartInstances.adhkTriwulanan = createTriwulananLineChart('adhkTriwulananChart', adhkTriwulananByCategory, false, globalSelectedYearTriwulanan);
        chartInstances.distribusiTriwulanan = createDistribusiPieChart('distribusiTriwulananChart', distribusiTriwulananByCategory, selectedYearDistribusiTriwulanan, selectedQuarterDistribusiTriwulanan);
        chartInstances.lajuQtoQ = createTriwulananLineChart('lajuQtoQChart', lajuQtoQByCategory, true, globalSelectedYearTriwulanan);
        chartInstances.lajuYtoY = createTriwulananLineChart('lajuYtoYChart', lajuYtoYByCategory, true, globalSelectedYearTriwulanan);
        chartInstances.lajuCtoC = createTriwulananLineChart('lajuCtoCChart', lajuCtoCByCategory, true, globalSelectedYearTriwulanan);
      }

      // Resize all charts
      setTimeout(() => {
        Object.values(chartInstances).forEach(chart => {
          if (chart && chart.resize) chart.resize();
        });
      }, 100);
    }

    // Year filter change handler for Distribusi Tahunan
    const yearFilterDistribusi = document.getElementById('yearFilterDistribusi');
    if (yearFilterDistribusi) {
      yearFilterDistribusi.addEventListener('change', function() {
        selectedYearDistribusi = this.value ? parseInt(this.value) : null;
        // Only update Distribusi chart
        if (chartInstances.distribusi) {
          chartInstances.distribusi.dispose();
        }
        chartInstances.distribusi = createDistribusiPieChartTahunan('distribusiChart', distribusiByCategory, selectedYearDistribusi, false);
        setTimeout(() => {
          if (chartInstances.distribusi) chartInstances.distribusi.resize();
        }, 100);
      });
    }

    // Year and Quarter filter change handler for Distribusi Triwulanan
    const yearFilterDistribusiTriwulanan = document.getElementById('yearFilterDistribusiTriwulanan');
    const quarterFilterDistribusiTriwulanan = document.getElementById('quarterFilterDistribusiTriwulanan');
    
    // Find latest quarter from data
    function findLatestQuarter() {
      if (!distribusiTriwulananByCategory || Object.keys(distribusiTriwulananByCategory).length === 0) {
        return null;
      }
      
      const allYearQuarterPairs = [];
      Object.values(distribusiTriwulananByCategory).forEach(dataList => {
        if (Array.isArray(dataList)) {
          dataList.forEach(item => {
            if (item.quarter && ['I', 'II', 'III', 'IV'].includes(item.quarter)) {
              const pair = `${item.year}-${item.quarter}`;
              if (!allYearQuarterPairs.includes(pair)) {
                allYearQuarterPairs.push(pair);
              }
            }
          });
        }
      });
      
      if (allYearQuarterPairs.length > 0) {
        allYearQuarterPairs.sort((a, b) => {
          const [yearA, quarterA] = a.split('-');
          const [yearB, quarterB] = b.split('-');
          if (yearA !== yearB) return parseInt(yearB) - parseInt(yearA);
          const quarterOrder = { 'I': 1, 'II': 2, 'III': 3, 'IV': 4 };
          return quarterOrder[quarterB] - quarterOrder[quarterA];
        });
        
        const [latestYear, latestQuarter] = allYearQuarterPairs[0].split('-');
        return { year: parseInt(latestYear), quarter: latestQuarter };
      }
      return null;
    }
    
    function updateDistribusiTriwulananChart() {
        if (chartInstances.distribusiTriwulanan) {
          chartInstances.distribusiTriwulanan.dispose();
        }
      chartInstances.distribusiTriwulanan = createDistribusiPieChart('distribusiTriwulananChart', distribusiTriwulananByCategory, selectedYearDistribusiTriwulanan, selectedQuarterDistribusiTriwulanan);
        setTimeout(() => {
          if (chartInstances.distribusiTriwulanan) chartInstances.distribusiTriwulanan.resize();
        }, 100);
    }
    
    if (yearFilterDistribusiTriwulanan) {
      yearFilterDistribusiTriwulanan.addEventListener('change', function() {
        selectedYearDistribusiTriwulanan = this.value ? parseInt(this.value) : null;
        updateDistribusiTriwulananChart();
      });
    }
    
    if (quarterFilterDistribusiTriwulanan) {
      quarterFilterDistribusiTriwulanan.addEventListener('change', function() {
        selectedQuarterDistribusiTriwulanan = this.value || null;
        updateDistribusiTriwulananChart();
      });
    }

        // Global Year filter change handler for Triwulanan
    const globalYearFilterTriwulanan = document.getElementById('globalYearFilterTriwulanan');
    if (globalYearFilterTriwulanan) {
      globalYearFilterTriwulanan.addEventListener('change', function() {
        globalSelectedYearTriwulanan = this.value ? parseInt(this.value) : null;
        
        const chartsToUpdate = [
          { instance: 'adhbTriwulanan', canvas: 'adhbTriwulananChart', data: adhbTriwulananByCategory, isPercentage: false },
          { instance: 'adhkTriwulanan', canvas: 'adhkTriwulananChart', data: adhkTriwulananByCategory, isPercentage: false },
          { instance: 'lajuQtoQ', canvas: 'lajuQtoQChart', data: lajuQtoQByCategory, isPercentage: true },
          { instance: 'lajuYtoY', canvas: 'lajuYtoYChart', data: lajuYtoYByCategory, isPercentage: true },
          { instance: 'lajuCtoC', canvas: 'lajuCtoCChart', data: lajuCtoCByCategory, isPercentage: true }
        ];
        
        chartsToUpdate.forEach(item => {
          if (chartInstances[item.instance]) {
            chartInstances[item.instance].dispose();
          }
          chartInstances[item.instance] = createTriwulananLineChart(item.canvas, item.data, item.isPercentage, globalSelectedYearTriwulanan);
        });
        
        setTimeout(() => {
          chartsToUpdate.forEach(item => {
            if (chartInstances[item.instance]) chartInstances[item.instance].resize();
          });
        }, 100);
      });
    }

    // Charts will be initialized after data is loaded in loadAllData()

    // Handle window resize
    let resizeTimeout;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(() => {
        // Resize all chart instances
      Object.values(chartInstances).forEach(chart => {
        if (chart && chart.resize) chart.resize();
      });
        
        // Resize filtered charts if they exist
        if (filteredChartInstance && filteredChartInstance.resize) {
          filteredChartInstance.resize();
        }
        if (filteredChartInstanceTriwulanan && filteredChartInstanceTriwulanan.resize) {
          filteredChartInstanceTriwulanan.resize();
        }
      }, 150);
    });

    // ========== Format Rupiah with Thousand Separator ==========

    


    // ========== Calculate Year-over-Year Comparisons for Carousel Cards ==========
    function calculateCarouselComparisons() {
      if (!latestBySheet || Object.keys(latestBySheet).length === 0) return;
      
      Object.keys(latestBySheet).forEach((sheetName, index) => {
        const sheetData = latestBySheet[sheetName];
        if (!sheetData || !sheetData.all_data || sheetData.all_data.length < 2) return;
        
        const sorted = [...sheetData.all_data].sort((a, b) => a.year - b.year);
        const latest = sorted[sorted.length - 1];
        const previous = sorted.find(d => d.year === latest.year - 1) || sorted[sorted.length - 2];
        
        if (!previous || !latest) return;
        
        const diff = latest.value - previous.value;
        
        const containers = document.querySelectorAll(`#sheet-${index}-comparison`);
        if (!containers || containers.length === 0) return;
        
        let arrow = '<i class="fas fa-minus"></i>';
        let arrowColor = '#666';
        let valueColor = '#666';
        if (diff > 0) {
          arrow = '<i class="fas fa-arrow-up"></i>';
          arrowColor = '#28a745';
          valueColor = '#28a745';
        } else if (diff < 0) {
          arrow = '<i class="fas fa-arrow-down"></i>';
          arrowColor = '#dc3545';
          valueColor = '#dc3545';
        }
        
        const isPercentage = sheetName.includes('Distribusi') || sheetName.includes('Laju');
        const diffFormatted = Math.abs(diff).toFixed(2);
        const diffFormattedRupiah = isPercentage ? diffFormatted : (window.formatRupiah ? window.formatRupiah(Math.abs(diff).toFixed(2)) : Math.abs(diff).toFixed(2));
        const comparisonHTML = isPercentage ? 
          `<span style="color: ${arrowColor}; font-size: 14px;">${arrow}</span>
           <span style="color: ${valueColor}; font-size: 14px; font-weight: 600;">${diff >= 0 ? '+' : ''}${diffFormatted}%</span>
           <span style="color: rgba(255, 255, 255, 0.8); font-size: 12px;">dari ${previous.year}</span>` :
          `<span style="color: ${arrowColor}; font-size: 14px;">${arrow}</span>
           <span style="color: ${valueColor}; font-size: 14px; font-weight: 600;">${diff >= 0 ? '+' : ''}Rp ${diffFormattedRupiah}</span>
           <span style="color: rgba(255, 255, 255, 0.8); font-size: 12px;">dari ${previous.year}</span>`;
        
        containers.forEach(container => {
          container.innerHTML = comparisonHTML;
          // Update comparison text color to white
          container.querySelectorAll('span').forEach(span => {
            if (span.style.color !== 'rgba(255, 255, 255, 0.8)') {
              span.style.color = 'rgba(255, 255, 255, 0.9)';
            }
          });
        });
        
        // Re-apply Rupiah formatting after comparison is set
        
      });
    }

    // ========== Initialize on Page Load ==========
    // Load all data when DOM is ready
    loadAllData();

    // ========== Auto-Resize Charts on Window/Sidebar Changes ==========
    // Auto-resize charts when window size changes
    window.addEventListener('resize', function() {
      Object.values(chartInstances).forEach(chart => {
        if (chart && chart.resize) {
          chart.resize();
        }
      });
      
      // Also resize filtered charts if they exist
      if (typeof filteredChartInstance !== 'undefined' && filteredChartInstance && filteredChartInstance.resize) {
        filteredChartInstance.resize();
      }
      if (typeof filteredChartInstanceTriwulanan !== 'undefined' && filteredChartInstanceTriwulanan && filteredChartInstanceTriwulanan.resize) {
        filteredChartInstanceTriwulanan.resize();
      }
    });

    // Listen for sidebar toggle to resize charts
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
      sidebarToggle.addEventListener('click', function() {
        // Wait for sidebar transition to complete (usually 300ms)
        setTimeout(function() {
          Object.values(chartInstances).forEach(chart => {
            if (chart && chart.resize) {
              chart.resize();
            }
          });
          
          // Also resize filtered charts if they exist
          if (typeof filteredChartInstance !== 'undefined' && filteredChartInstance && filteredChartInstance.resize) {
            filteredChartInstance.resize();
          }
          if (typeof filteredChartInstanceTriwulanan !== 'undefined' && filteredChartInstanceTriwulanan && filteredChartInstanceTriwulanan.resize) {
            filteredChartInstanceTriwulanan.resize();
          }
        }, 350);
      });
    }
    
    // Use ResizeObserver for more accurate container size detection
    if (typeof ResizeObserver !== 'undefined') {
      // Get all chart containers
      const chartIds = [
        'adhbChart', 'adhkChart', 'distribusiChart', 'lajuChart',
        'adhbTriwulananChart', 'adhkTriwulananChart', 'distribusiTriwulananChart',
        'lajuQtoQChart', 'lajuYtoYChart', 'lajuCtoCChart',
        'filteredChart', 'filteredChartTriwulanan'
      ];
      
      chartIds.forEach(chartId => {
        const chartElement = document.getElementById(chartId);
        if (chartElement && chartElement.parentElement) {
          const observer = new ResizeObserver(function() {
            setTimeout(function() {
              // Find the chart instance for this element
              const chartName = chartId.replace('Chart', '');
              const chart = chartInstances[chartName] || 
                           (chartId === 'filteredChart' && typeof filteredChartInstance !== 'undefined' ? filteredChartInstance : null) ||
                           (chartId === 'filteredChartTriwulanan' && typeof filteredChartInstanceTriwulanan !== 'undefined' ? filteredChartInstanceTriwulanan : null);
              
              if (chart && chart.resize) {
                chart.resize();
              }
            }, 100);
          });
          observer.observe(chartElement.parentElement);
        }
      });
    }
    // ========== Export to Excel and PNG ==========
    // Generic Export to PNG Function
    function exportToPNG(chartInstance, filename) {
      if (!chartInstance) return;
      const url = chartInstance.getDataURL({
        type: 'png',
        pixelRatio: 2,
        backgroundColor: '#fff'
      });
      const link = document.createElement('a');
      link.href = url;
      link.download = filename + '.png';
      link.click();
    }

    // Generic Export to Excel Function
    function exportToExcel(dataObj, filename, title) {
      if (!dataObj || Object.keys(dataObj).length === 0) return;
      
      const exportData = [];
      
      // Determine if data is quarterly by checking the first item
      let isQuarterly = false;
      const firstCategory = Object.keys(dataObj)[0];
      if (dataObj[firstCategory] && dataObj[firstCategory].length > 0) {
        if (dataObj[firstCategory][0].hasOwnProperty('quarter')) {
          isQuarterly = true;
        }
      }

      // Collect all years (and quarters)
      const timePoints = new Set();
      Object.values(dataObj).forEach(items => {
        items.forEach(item => {
          if (isQuarterly) {
            timePoints.add(item.year + ' Q' + item.quarter);
          } else {
            timePoints.add(item.year);
          }
        });
      });

      const sortedTimePoints = Array.from(timePoints).sort();
      const header = ['Kategori', ...sortedTimePoints];
      exportData.push(header);

      Object.keys(dataObj).forEach(category => {
        const row = [category];
        const items = dataObj[category];
        
        sortedTimePoints.forEach(tp => {
          let match;
          if (isQuarterly) {
            const [year, q] = tp.split(' Q');
            match = items.find(item => item.year == year && item.quarter == q);
          } else {
            match = items.find(item => item.year == tp);
          }
          row.push(match ? match.value : '');
        });
        
        exportData.push(row);
      });

      const ws = XLSX.utils.aoa_to_sheet(exportData);
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, "Data");
      XLSX.writeFile(wb, filename + '.xlsx');
    }

    // Setup Export Listeners
    const exportConfig = [
      { id: 'Adhb', chart: 'adhb', data: () => adhbByCategory, name: 'PDRB_Pengeluaran_ADHB' },
      { id: 'Adhk', chart: 'adhk', data: () => adhkByCategory, name: 'PDRB_Pengeluaran_ADHK' },
      { id: 'Distribusi', chart: 'distribusi', data: () => distribusiByCategory, name: 'Distribusi_PDRB_Pengeluaran' },
      { id: 'Laju', chart: 'laju', data: () => lajuByCategory, name: 'Laju_Pertumbuhan_PDRB_Pengeluaran' },
      { id: 'AdhbTriwulanan', chart: 'adhbTriwulanan', data: () => adhbTriwulananByCategory, name: 'PDRB_Pengeluaran_ADHB_Triwulanan' },
      { id: 'AdhkTriwulanan', chart: 'adhkTriwulanan', data: () => adhkTriwulananByCategory, name: 'PDRB_Pengeluaran_ADHK_Triwulanan' },
      { id: 'DistribusiTriwulanan', chart: 'distribusiTriwulanan', data: () => distribusiTriwulananByCategory, name: 'Distribusi_PDRB_Pengeluaran_Triwulanan' },
      { id: 'LajuQtoQ', chart: 'lajuQtoQ', data: () => lajuQtoQByCategory, name: 'Laju_QtoQ_PDRB_Pengeluaran' },
      { id: 'LajuYtoY', chart: 'lajuYtoY', data: () => lajuYtoYByCategory, name: 'Laju_YtoY_PDRB_Pengeluaran' },
      { id: 'LajuCtoC', chart: 'lajuCtoC', data: () => lajuCtoCByCategory, name: 'Laju_CtoC_PDRB_Pengeluaran' }
    ];

    exportConfig.forEach(config => {
      const btnExcel = document.getElementById('download' + config.id + 'Excel');
      if (btnExcel) {
        btnExcel.addEventListener('click', function(e) {
          e.preventDefault();
          exportToExcel(config.data(), config.name, config.name);
        });
      }
      
      const btnPNG = document.getElementById('download' + config.id + 'PNG');
      if (btnPNG) {
        btnPNG.addEventListener('click', function(e) {
          e.preventDefault();
          exportToPNG(chartInstances[config.chart], config.name);
        });
      }
    });
    
    // For Filtered Charts (Tahunan)
    const btnFilteredExcel = document.getElementById('downloadFilteredExcel');
    if (btnFilteredExcel) {
      btnFilteredExcel.addEventListener('click', function(e) {
        e.preventDefault();
        // Get currently displayed data
        let dataSource = {};
        if (selectedJenisPDRB === 'adhb') dataSource = adhbByCategory;
        else if (selectedJenisPDRB === 'adhk') dataSource = adhkByCategory;
        else if (selectedJenisPDRB === 'distribusi') dataSource = distribusiByCategory;
        else if (selectedJenisPDRB === 'laju') dataSource = lajuByCategory;
        
        const filteredData = {};
        selectedPengeluaran.forEach(cat => {
          if (dataSource[cat]) filteredData[cat] = dataSource[cat];
        });
        
        exportToExcel(filteredData, 'PDRB_Pengeluaran_Filtered', 'Data Filter');
      });
    }
    
    const btnFilteredPNG = document.getElementById('downloadFilteredPNG');
    if (btnFilteredPNG) {
      btnFilteredPNG.addEventListener('click', function(e) {
        e.preventDefault();
        exportToPNG(filteredChartInstance, 'PDRB_Pengeluaran_Filtered');
      });
    }

    // For Filtered Charts (Triwulanan)
    const btnFilteredTriwulananExcel = document.getElementById('downloadFilteredTriwulananExcel');
    if (btnFilteredTriwulananExcel) {
      btnFilteredTriwulananExcel.addEventListener('click', function(e) {
        e.preventDefault();
        // Get currently displayed data
        let dataSource = {};
        if (selectedJenisPDRBTriwulanan === 'adhb_triwulanan') dataSource = adhbTriwulananByCategory;
        else if (selectedJenisPDRBTriwulanan === 'adhk_triwulanan') dataSource = adhkTriwulananByCategory;
        else if (selectedJenisPDRBTriwulanan === 'distribusi_triwulanan') dataSource = distribusiTriwulananByCategory;
        else if (selectedJenisPDRBTriwulanan === 'laju_qtoq') dataSource = lajuQtoQByCategory;
        else if (selectedJenisPDRBTriwulanan === 'laju_ytoy') dataSource = lajuYtoYByCategory;
        else if (selectedJenisPDRBTriwulanan === 'laju_ctoc') dataSource = lajuCtoCByCategory;
        
        const filteredData = {};
        selectedPengeluaranTriwulanan.forEach(cat => {
          if (dataSource[cat]) filteredData[cat] = dataSource[cat];
        });
        
        exportToExcel(filteredData, 'PDRB_Pengeluaran_Triwulanan_Filtered', 'Data Filter');
      });
    }
    
    const btnFilteredTriwulananPNG = document.getElementById('downloadFilteredTriwulananPNG');
    if (btnFilteredTriwulananPNG) {
      btnFilteredTriwulananPNG.addEventListener('click', function(e) {
        e.preventDefault();
        exportToPNG(filteredChartInstanceTriwulanan, 'PDRB_Pengeluaran_Triwulanan_Filtered');
      });
    }
  });






