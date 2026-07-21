  document.addEventListener("DOMContentLoaded", () => {
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

    // Handle option selection using event delegation
    if (filterJenisPengeluaranDropdown) {
      // Use event delegation to handle clicks on dynamically added options
      filterJenisPengeluaranDropdown.addEventListener('click', function(e) {
        // Check if clicked element is a filter option or inside one
        const option = e.target.closest('.filter-option');
        if (!option) {
          // If clicking on dropdown itself but not an option, don't close
          return;
        }
        
        e.stopPropagation();
        e.preventDefault();
        
        const value = option.getAttribute('data-value');
        
        if (!value) return;
        
        // Toggle selection
        if (selectedPengeluaran.includes(value)) {
          selectedPengeluaran = selectedPengeluaran.filter(v => v !== value);
          option.classList.remove('selected');
          option.style.backgroundColor = '';
        } else {
          selectedPengeluaran.push(value);
          option.classList.add('selected');
          option.style.backgroundColor = '#e7f3ff';
        }
        
        updateTagsDisplay();
        checkFilterValidity();
        
        // Don't close dropdown after selection - allow multiple selections
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
            <button type="button" class="tag-remove" data-value="${value}">Ã—</button>
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
          } else if (selectedJenisPDRB === 'Laju Implisit') {
            dataByCategory = lajuImplisitByCategory;
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

    // Handle option selection for triwulanan using event delegation
    if (filterJenisPengeluaranTriwulananDropdown) {
      // Use event delegation to handle clicks on dynamically added options
      filterJenisPengeluaranTriwulananDropdown.addEventListener('click', function(e) {
        // Check if clicked element is a filter option or inside one
        const option = e.target.closest('.filter-option-triwulanan');
        if (!option) {
          // If clicking on dropdown itself but not an option, don't close
          return;
        }
        
        e.stopPropagation();
        e.preventDefault();
        
        const value = option.getAttribute('data-value');
        
        if (!value) return;
        
        if (selectedPengeluaranTriwulanan.includes(value)) {
          selectedPengeluaranTriwulanan = selectedPengeluaranTriwulanan.filter(v => v !== value);
          option.classList.remove('selected');
          option.style.backgroundColor = '';
        } else {
          selectedPengeluaranTriwulanan.push(value);
          option.classList.add('selected');
          option.style.backgroundColor = '#e7f3ff';
        }
        
        updateTagsDisplayTriwulanan();
        checkFilterValidityTriwulanan();
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
            <button type="button" class="tag-remove" data-value="${value}">Ã—</button>
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

    // Carousel will be initialized in populateCarouselCards() after data is loaded

    // ========== Data Variables (will be populated from API) ==========
    let adhbByCategory = {};
    let adhkByCategory = {};
    let distribusiByCategory = {};
    let lajuByCategory = {};
    let lajuImplisitByCategory = {};
    let adhbTriwulananByCategory = {};
    let adhkTriwulananByCategory = {};
    let distribusiTriwulananByCategory = {};
    let lajuQtoQByCategory = {};
    let lajuYtoYByCategory = {};
    let lajuCtoCByCategory = {};
    let latestBySheet = {};
    let allYears = [];
    let latestYear = null;
    let selectedYearDistribusi = null;
    let selectedYearDistribusiTriwulanan = null;
    let selectedQuarterDistribusiTriwulanan = null;
    let selectedYearADHBTriwulanan = null;
    let selectedYearADHKTriwulanan = null;
    let selectedYearLajuQtoQ = null;
    let selectedYearLajuYtoY = null;
    let selectedYearLajuCtoC = null;

    // ========== API Base URL ==========
    const API_BASE_URL = '/api';

    // ========== Helper function to group data by category ==========
    function groupDataByCategory(dataArray) {
      const grouped = {};
      if (!dataArray || !Array.isArray(dataArray)) return grouped;
      
      dataArray.forEach(item => {
        const category = item.industry_category || item.expenditure_category;
        if (!category) return;
        
        if (!grouped[category]) {
          grouped[category] = [];
        }
        
        const dataItem = {
          year: item.year,
          value: parseFloat(item.value) || 0,
          preliminary_flag: item.preliminary_flag || ''
        };
        
        if (item.quarter) {
          dataItem.quarter = item.quarter;
        }
        
        grouped[category].push(dataItem);
      });
      
      // Sort each category's data by year and quarter
      Object.keys(grouped).forEach(category => {
        grouped[category].sort((a, b) => {
          if (a.year !== b.year) return a.year - b.year;
          if (a.quarter && b.quarter) {
            const quarterOrder = { 'I': 1, 'II': 2, 'III': 3, 'IV': 4 };
            return (quarterOrder[a.quarter] || 0) - (quarterOrder[b.quarter] || 0);
          }
          return 0;
        });
      });
      
      return grouped;
    }

    // ========== Load All Data from API ==========
    async function loadAllData() {
      try {
        // Load all data types in parallel
        const [
          adhbRes, adhkRes, distribusiRes, lajuRes, lajuImplisitRes,
          adhbTriRes, adhkTriRes, distribusiTriRes,
          lajuQtoQRes, lajuYtoYRes, lajuCtoCRes,
          summaryRes, yearsRes
        ] = await Promise.all([
          fetch(`${API_BASE_URL}/pdrb-lapangan-usaha-by-category?type=adhb`).then(r => r.json()),
          fetch(`${API_BASE_URL}/pdrb-lapangan-usaha-by-category?type=adhk`).then(r => r.json()),
          fetch(`${API_BASE_URL}/pdrb-lapangan-usaha-by-category?type=distribusi`).then(r => r.json()),
          fetch(`${API_BASE_URL}/pdrb-lapangan-usaha-by-category?type=laju`).then(r => r.json()),
          fetch(`${API_BASE_URL}/pdrb-lapangan-usaha-by-category?type=laju_implisit`).then(r => r.json()),
          fetch(`${API_BASE_URL}/pdrb-lapangan-usaha-by-category?type=adhb_triwulanan`).then(r => r.json()),
          fetch(`${API_BASE_URL}/pdrb-lapangan-usaha-by-category?type=adhk_triwulanan`).then(r => r.json()),
          fetch(`${API_BASE_URL}/pdrb-lapangan-usaha-by-category?type=distribusi_triwulanan`).then(r => r.json()),
          fetch(`${API_BASE_URL}/pdrb-lapangan-usaha-by-category?type=laju_qtoq`).then(r => r.json()),
          fetch(`${API_BASE_URL}/pdrb-lapangan-usaha-by-category?type=laju_ytoy`).then(r => r.json()),
          fetch(`${API_BASE_URL}/pdrb-lapangan-usaha-by-category?type=laju_ctoc`).then(r => r.json()),
          fetch(`${API_BASE_URL}/pdrb-lapangan-usaha-summary`).then(r => r.json()),
          fetch(`${API_BASE_URL}/pdrb-lapangan-usaha-years`).then(r => r.json())
        ]);

        // Process responses
        if (adhbRes.success) adhbByCategory = adhbRes.data || {};
        if (adhkRes.success) adhkByCategory = adhkRes.data || {};
        if (distribusiRes.success) distribusiByCategory = distribusiRes.data || {};
        if (lajuRes.success) lajuByCategory = lajuRes.data || {};
        if (lajuImplisitRes.success) lajuImplisitByCategory = lajuImplisitRes.data || {};
        if (adhbTriRes.success) adhbTriwulananByCategory = adhbTriRes.data || {};
        if (adhkTriRes.success) adhkTriwulananByCategory = adhkTriRes.data || {};
        if (distribusiTriRes.success) distribusiTriwulananByCategory = distribusiTriRes.data || {};
        if (lajuQtoQRes.success) lajuQtoQByCategory = lajuQtoQRes.data || {};
        if (lajuYtoYRes.success) lajuYtoYByCategory = lajuYtoYRes.data || {};
        if (lajuCtoCRes.success) lajuCtoCByCategory = lajuCtoCRes.data || {};
        if (summaryRes.success) latestBySheet = summaryRes.data || {};
        if (yearsRes.success) {
          allYears = yearsRes.data || [];
          if (allYears.length > 0) {
            latestYear = Math.max(...allYears);
            selectedYearDistribusi = latestYear;
            selectedYearDistribusiTriwulanan = latestYear;
          }
        }

        // Populate year filter dropdowns
        populateYearFilters();
        
        // Populate carousel cards
        populateCarouselCards();
        
        // Set default quarter for triwulanan
        setDefaultQuarter();
        
        // Populate filter dropdowns
        populateIndustryCategoryFilter();
        populateIndustryCategoryFilterTriwulanan();
        
        // Initialize charts
        setTimeout(() => {
          updateAllCharts();
        }, 100);
        
      } catch (error) {
        console.error('Error loading data from API:', error);
      }
    }

    // ========== Populate Year Filter Dropdowns ==========
    function populateYearFilters() {
      const yearFilterDistribusi = document.getElementById('yearFilterDistribusi');
      const yearFilterDistribusiTriwulanan = document.getElementById('yearFilterDistribusiTriwulanan');
      const yearFilterADHBTriwulanan = document.getElementById('yearFilterADHBTriwulanan');
      const yearFilterADHKTriwulanan = document.getElementById('yearFilterADHKTriwulanan');
      const yearFilterLajuQtoQ = document.getElementById('yearFilterLajuQtoQ');
      const yearFilterLajuYtoY = document.getElementById('yearFilterLajuYtoY');
      const yearFilterLajuCtoC = document.getElementById('yearFilterLajuCtoC');

      const yearOptions = allYears.map(year => `<option value="${year}" ${year === latestYear ? 'selected' : ''}>${year}</option>`).join('');

      if (yearFilterDistribusi) {
        yearFilterDistribusi.innerHTML = '<option value="">Semua Tahun</option>' + yearOptions;
        if (selectedYearDistribusi) {
          yearFilterDistribusi.value = selectedYearDistribusi;
        }
      }

      if (yearFilterDistribusiTriwulanan) {
        yearFilterDistribusiTriwulanan.innerHTML = '<option value="">Pilih Tahun</option>' + yearOptions;
        if (selectedYearDistribusiTriwulanan) {
          yearFilterDistribusiTriwulanan.value = selectedYearDistribusiTriwulanan;
        }
      }

      [yearFilterADHBTriwulanan, yearFilterADHKTriwulanan, yearFilterLajuQtoQ, yearFilterLajuYtoY, yearFilterLajuCtoC].forEach(select => {
        if (select) {
          select.innerHTML = '<option value="">4 Triwulan Terakhir</option>' + yearOptions;
        }
      });
    }

    // ========== Populate Carousel Cards ==========
    function populateCarouselCards() {
      const carousel = document.getElementById('pdrbSheetCarousel');
      if (!carousel) return;

      carousel.innerHTML = '';
      let cardIndex = 0;

      Object.keys(latestBySheet).forEach(sheetName => {
        const sheetData = latestBySheet[sheetName];
        if (!sheetData || !sheetData.data) return;

        const card = document.createElement('div');
        card.className = 'indicator-card';
        card.setAttribute('data-card-index', cardIndex);
        card.style.cssText = 'min-width: 240px; border-radius: 12px; padding: 15px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); position: relative; overflow: hidden;';

        const isPercentage = sheetName.includes('Distribusi') || sheetName.includes('Laju');
        const value = sheetData.data.value || 0;
        const valueDisplay = isPercentage 
          ? `${parseFloat(value).toFixed(2)}%`
          : `<span class="rupiah-value" data-value="${parseFloat(value).toFixed(0)}">Rp ${parseFloat(value).toFixed(0)}</span>`;

        card.innerHTML = `
          <div style="position: relative; z-index: 2;">
            <h6 class="card-title" style="font-size: 12px; margin-bottom: 8px; font-weight: 500;">${sheetName}</h6>
            <h6 class="card-subtitle" style="font-size: 11px; margin-bottom: 10px; font-weight: 400;">${(sheetData.category || '').substring(0, 50)}</h6>
            <h3 class="card-value" style="font-size: 22px; font-weight: 700; margin-bottom: 6px; word-break: break-word; overflow-wrap: break-word; white-space: normal;">${valueDisplay}</h3>
            <div id="sheet-${cardIndex}-comparison" style="display: flex; align-items: center; gap: 5px; margin-bottom: 4px;"></div>
            <small class="card-year" style="font-size: 11px;">Tahun ${sheetData.data.year}${sheetData.data.preliminary_flag ? ' ' + sheetData.data.preliminary_flag : ''}</small>
          </div>
        `;

        carousel.appendChild(card);
        cardIndex++;
      });

      // Apply colors and calculate comparisons
      setTimeout(() => {
        applyCardColors();
        calculateCarouselComparisons();
        initializeCarousel();
      }, 100);
    }

    // ========== Initialize Carousel Animation ==========
    function initializeCarousel() {
      const carousel = document.getElementById('pdrbSheetCarousel');
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
      const cardsArray = Array.from(cards);
      if (cardsArray.length > 0) {
        cardsArray.forEach(card => {
          originalContent.appendChild(card);
        });
      }

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

      let currentPosition = 0;
      let isPaused = false;
      let animationFrameId;
      const scrollSpeed = 1.5; // pixels per frame (adjust for speed)

      function animate() {
        if (!isPaused) {
          const contentSetWidth = getContentSetWidth();
          
          if (contentSetWidth > 0) {
            // Move to the right (negative translateX = content moves right)
            currentPosition += scrollSpeed;

            // When we've scrolled past one complete set, reset seamlessly
            if (currentPosition >= contentSetWidth) {
              // Reset position without transition for seamless loop
              currentPosition = currentPosition - contentSetWidth;
            }

            carousel.style.transition = 'none';
            carousel.style.transform = `translateX(-${currentPosition}px)`;
          }
        }

        animationFrameId = requestAnimationFrame(animate);
      }

      // Pause on hover
      const carouselWrapper = carousel.closest('.indicator-carousel-wrapper');
      if (carouselWrapper) {
        carouselWrapper.addEventListener('mouseenter', () => {
          isPaused = true;
        });

        carouselWrapper.addEventListener('mouseleave', () => {
          isPaused = false;
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
          if (contentSetWidth > 0 && currentPosition > 0) {
            currentPosition = currentPosition % contentSetWidth;
            carousel.style.transition = 'none';
            carousel.style.transform = `translateX(-${currentPosition}px)`;
          }
        }, 250);
      });
    }

    // ========== Populate Filter Dropdown with Industry Categories ==========
    function populateIndustryCategoryFilter() {
      const filterDropdown = document.getElementById('filterJenisPengeluaranDropdown');
      if (!filterDropdown) return;
      
      // Collect all unique industry categories from all data sources
      const allCategories = new Set();
      Object.keys(adhbByCategory).forEach(cat => allCategories.add(cat));
      Object.keys(adhkByCategory).forEach(cat => allCategories.add(cat));
      Object.keys(distribusiByCategory).forEach(cat => allCategories.add(cat));
      Object.keys(lajuByCategory).forEach(cat => allCategories.add(cat));
      Object.keys(lajuImplisitByCategory).forEach(cat => allCategories.add(cat));
      
      // Sort categories
      const sortedCategories = Array.from(allCategories).sort();
      
      // Populate dropdown
      filterDropdown.innerHTML = '';
      sortedCategories.forEach(category => {
        const option = document.createElement('div');
        option.className = 'filter-option';
        option.setAttribute('data-value', category);
        option.style.cssText = 'padding: 10px 12px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #f0f0f0; pointer-events: auto;';
        option.textContent = category;
        
        // Add hover effect
        option.addEventListener('mouseenter', function() {
          this.style.backgroundColor = '#f0f8ff';
        });
        option.addEventListener('mouseleave', function() {
          if (!this.classList.contains('selected')) {
            this.style.backgroundColor = '';
          }
        });
        
        filterDropdown.appendChild(option);
      });
      
      // Remove border from last item
      if (filterDropdown.lastElementChild) {
        filterDropdown.lastElementChild.style.borderBottom = 'none';
      }
    }
    
    // Populate filter on page load (after DOM is ready)
    setTimeout(() => {
      populateIndustryCategoryFilter();
    }, 100);
    
    // ========== Populate Triwulanan Filter Dropdown with Industry Categories ==========
    function populateIndustryCategoryFilterTriwulanan() {
      const filterDropdown = document.getElementById('filterJenisPengeluaranTriwulananDropdown');
      if (!filterDropdown) return;
      
      // Collect all unique industry categories from triwulanan data sources
      const allCategories = new Set();
      Object.keys(adhbTriwulananByCategory).forEach(cat => allCategories.add(cat));
      Object.keys(adhkTriwulananByCategory).forEach(cat => allCategories.add(cat));
      Object.keys(distribusiTriwulananByCategory).forEach(cat => allCategories.add(cat));
      Object.keys(lajuQtoQByCategory).forEach(cat => allCategories.add(cat));
      Object.keys(lajuYtoYByCategory).forEach(cat => allCategories.add(cat));
      Object.keys(lajuCtoCByCategory).forEach(cat => allCategories.add(cat));
      
      // Sort categories
      const sortedCategories = Array.from(allCategories).sort();
      
      // Populate dropdown
      filterDropdown.innerHTML = '';
      sortedCategories.forEach(category => {
        const option = document.createElement('div');
        option.className = 'filter-option-triwulanan';
        option.setAttribute('data-value', category);
        option.style.cssText = 'padding: 10px 12px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #f0f0f0; pointer-events: auto;';
        option.textContent = category;
        
        // Add hover effect
        option.addEventListener('mouseenter', function() {
          this.style.backgroundColor = '#f0f8ff';
        });
        option.addEventListener('mouseleave', function() {
          if (!this.classList.contains('selected')) {
            this.style.backgroundColor = '';
          }
        });
        
        filterDropdown.appendChild(option);
      });
      
      // Remove border from last item
      if (filterDropdown.lastElementChild) {
        filterDropdown.lastElementChild.style.borderBottom = 'none';
      }
    }
    
    // Populate triwulanan filter on page load (after DOM is ready)
    setTimeout(() => {
      populateIndustryCategoryFilterTriwulanan();
    }, 100);

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
            return item ? parseFloat(item.value) : null;
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
            return item ? parseFloat(item.value) : null;
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

      chart.setOption(option);
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
      
      // For PDRB Lapangan Usaha, show ALL industry categories (not just PDRB total)
      // This is different from PDRB Pengeluaran which only shows PDRB total
      let categories = Object.keys(dataByCategory);
      
      // Debug: log all available categories
      console.log(`[${canvasId}] All available categories:`, categories);
      
      // Filter out empty or invalid categories
      categories = categories.filter(cat => cat && cat.trim() !== '');
      
      // For PDRB Lapangan Usaha, we want to show all industry categories
      // But we can optionally prioritize showing PDRB total if it exists
      // For now, show all categories - user can filter if needed
      
      if (categories.length === 0) {
        console.warn(`[${canvasId}] No categories available. Data:`, dataByCategory);
        return null;
      }
      
      console.log(`[${canvasId}] Using categories:`, categories);
      
      // Prepare x-axis data and series
      let xAxisData = [];
      let series = [];
      
      // Get all quarters from data, excluding TOTAL and Jumlah
      const allQuarters = [];
      const validQuarters = ['I', 'II', 'III', 'IV']; // Only include these quarters
      
      // Collect all quarters from all categories
      categories.forEach(category => {
        if (!dataByCategory[category]) return;
        const categoryData = dataByCategory[category];
        categoryData.forEach(item => {
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
      
      if (allQuarters.length === 0) {
        console.warn(`[${canvasId}] No valid quarters found in data`);
        return null;
      }
      
      // Filter by selected year or get last 4 quarters
      let selectedQuarters = [];
      if (selectedYear) {
        // Get all quarters from selected year
        selectedQuarters = allQuarters.filter(q => q.year === selectedYear);
        if (selectedQuarters.length === 0) {
          console.warn(`[${canvasId}] No quarters found for year ${selectedYear}`);
          // Fallback to last 4 quarters
          selectedQuarters = allQuarters.slice(-4);
        }
      } else {
        // Get last 4 quarters
        selectedQuarters = allQuarters.slice(-4);
      }
      
      // Create x-axis labels
      xAxisData = selectedQuarters.map(q => `${q.year} Q${q.quarter}`);
      
      console.log(`[${canvasId}] Selected quarters:`, selectedQuarters);
      console.log(`[${canvasId}] X-axis data:`, xAxisData);
      
      // Prepare series data for each category
      series = categories.map((category, index) => {
        // Double-check: ensure category exists in dataByCategory
        if (!dataByCategory[category]) {
          console.warn(`[${canvasId}] Category not found in data:`, category);
          return null;
        }
        
        const categoryData = dataByCategory[category];
        const values = selectedQuarters.map(q => {
          const item = categoryData.find(d => d.year === q.year && d.quarter === q.quarter);
          if (!item) {
            console.log(`[${canvasId}] No data for ${category} at ${q.year} Q${q.quarter}`);
            return null;
          }
          return item.value;
        });
        
        // Check if all values are null
        const hasData = values.some(v => v !== null && v !== undefined);
        if (!hasData) {
          console.warn(`[${canvasId}] No data for category:`, category);
          return null;
        }
        
        console.log(`[${canvasId}] Category ${category} values:`, values);
        
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
      
      if (series.length === 0) {
        console.error(`[${canvasId}] No series data available after filtering`);
        return null;
      }
      
      console.log(`[${canvasId}] Final series count:`, series.length);

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

    // ========== Helper function to create bar chart for Distribusi (without PDRB/GRDP) ==========
    function createDistribusiBarChart(canvasId, dataByCategory, selectedYear, isQuarterly = false) {
      const chartDom = document.getElementById(canvasId);
      if (!chartDom) return null;
      
      const chart = echarts.init(chartDom);
      
      // Filter data by selected year and exclude PDRB/GRDP/PRODUK DOMESTIK REGIONAL BRUTO
      let filteredData = {};
      if (selectedYear) {
        Object.keys(dataByCategory).forEach(category => {
          // Exclude PDRB, GRDP, and PRODUK DOMESTIK REGIONAL BRUTO
          const categoryUpper = category.toUpperCase();
          if (categoryUpper.includes('PDRB') || categoryUpper.includes('GRDP') || 
              categoryUpper.includes('PRODUK DOMESTIK REGIONAL BRUTO')) {
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
        // Get latest year data for each category (excluding PDRB/GRDP/PRODUK DOMESTIK REGIONAL BRUTO)
        Object.keys(dataByCategory).forEach(category => {
          // Exclude PDRB, GRDP, and PRODUK DOMESTIK REGIONAL BRUTO
          const categoryUpper = category.toUpperCase();
          if (categoryUpper.includes('PDRB') || categoryUpper.includes('GRDP') || 
              categoryUpper.includes('PRODUK DOMESTIK REGIONAL BRUTO')) {
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
            return item ? parseFloat(item.value) : null;
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
          return item ? parseFloat(item.value) : null;
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
        const item = categoryData.find(d => d.year === targetYear && d.quarter === targetQuarter);
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
      
      const option = {
        tooltip: {
          trigger: 'item',
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
            
            // Hitung posisi y: di tengah vertikal chart area
            const y = viewHeight / 2 - tooltipHeight / 2;
            
            return [x, y];
          },
          formatter: '{a} <br/>{b}: {c}% ({d}%)',
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
          data: legendData,
          bottom: 0,
          orient: 'horizontal',
          itemGap: 15,
          itemWidth: 12,
          itemHeight: 12,
          textStyle: {
            fontSize: 11
          },
          type: 'scroll',
          width: '100%',
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
          radius: ['40%', '75%'],
          center: ['50%', '50%'],
          avoidLabelOverlap: true,
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
        chartInstances.distribusi = createDistribusiBarChart('distribusiChart', distribusiByCategory, selectedYearDistribusi, false);
        chartInstances.laju = createLineChart('lajuChart', lajuByCategory, true, false, []);
        chartInstances.lajuImplisit = createLineChart('lajuImplisitChart', lajuImplisitByCategory, true, false, []);
      } else {
        // Create charts for triwulanan - all line charts only show PDRB and last 4 quarters
        chartInstances.adhbTriwulanan = createTriwulananLineChart('adhbTriwulananChart', adhbTriwulananByCategory, false, selectedYearADHBTriwulanan);
        chartInstances.adhkTriwulanan = createTriwulananLineChart('adhkTriwulananChart', adhkTriwulananByCategory, false, selectedYearADHKTriwulanan);
        chartInstances.distribusiTriwulanan = createDistribusiPieChart('distribusiTriwulananChart', distribusiTriwulananByCategory, selectedYearDistribusiTriwulanan, selectedQuarterDistribusiTriwulanan);
        chartInstances.lajuQtoQ = createTriwulananLineChart('lajuQtoQChart', lajuQtoQByCategory, true, selectedYearLajuQtoQ);
        chartInstances.lajuYtoY = createTriwulananLineChart('lajuYtoYChart', lajuYtoYByCategory, true, selectedYearLajuYtoY);
        chartInstances.lajuCtoC = createTriwulananLineChart('lajuCtoCChart', lajuCtoCByCategory, true, selectedYearLajuCtoC);
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
        chartInstances.distribusi = createDistribusiBarChart('distribusiChart', distribusiByCategory, selectedYearDistribusi, false);
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
      const allYearQuarterPairs = [];
      Object.values(distribusiTriwulananByCategory).forEach(dataList => {
        dataList.forEach(item => {
          if (item.quarter && ['I', 'II', 'III', 'IV'].includes(item.quarter)) {
            const pair = `${item.year}-${item.quarter}`;
            if (!allYearQuarterPairs.includes(pair)) {
              allYearQuarterPairs.push(pair);
            }
          }
        });
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
    
    // Set default to latest quarter (called after data is loaded)
    function setDefaultQuarter() {
      const latestQuarterInfo = findLatestQuarter();
      if (latestQuarterInfo) {
        if (!selectedYearDistribusiTriwulanan) {
          selectedYearDistribusiTriwulanan = latestQuarterInfo.year;
          if (yearFilterDistribusiTriwulanan) {
            yearFilterDistribusiTriwulanan.value = latestQuarterInfo.year;
          }
        }
        selectedQuarterDistribusiTriwulanan = latestQuarterInfo.quarter;
        if (quarterFilterDistribusiTriwulanan) {
          quarterFilterDistribusiTriwulanan.value = latestQuarterInfo.quarter;
        }
      }
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

    // Year filter change handler for ADHB Triwulanan
    const yearFilterADHBTriwulanan = document.getElementById('yearFilterADHBTriwulanan');
    if (yearFilterADHBTriwulanan) {
      yearFilterADHBTriwulanan.addEventListener('change', function() {
        selectedYearADHBTriwulanan = this.value ? parseInt(this.value) : null;
        if (chartInstances.adhbTriwulanan) {
          chartInstances.adhbTriwulanan.dispose();
        }
        chartInstances.adhbTriwulanan = createTriwulananLineChart('adhbTriwulananChart', adhbTriwulananByCategory, false, selectedYearADHBTriwulanan);
        setTimeout(() => {
          if (chartInstances.adhbTriwulanan) chartInstances.adhbTriwulanan.resize();
        }, 100);
      });
    }

    // Year filter change handler for ADHK Triwulanan
    const yearFilterADHKTriwulanan = document.getElementById('yearFilterADHKTriwulanan');
    if (yearFilterADHKTriwulanan) {
      yearFilterADHKTriwulanan.addEventListener('change', function() {
        selectedYearADHKTriwulanan = this.value ? parseInt(this.value) : null;
        if (chartInstances.adhkTriwulanan) {
          chartInstances.adhkTriwulanan.dispose();
        }
        chartInstances.adhkTriwulanan = createTriwulananLineChart('adhkTriwulananChart', adhkTriwulananByCategory, false, selectedYearADHKTriwulanan);
        setTimeout(() => {
          if (chartInstances.adhkTriwulanan) chartInstances.adhkTriwulanan.resize();
        }, 100);
      });
    }

    // Year filter change handler for Laju Q-to-Q
    const yearFilterLajuQtoQ = document.getElementById('yearFilterLajuQtoQ');
    if (yearFilterLajuQtoQ) {
      yearFilterLajuQtoQ.addEventListener('change', function() {
        selectedYearLajuQtoQ = this.value ? parseInt(this.value) : null;
        if (chartInstances.lajuQtoQ) {
          chartInstances.lajuQtoQ.dispose();
        }
        chartInstances.lajuQtoQ = createTriwulananLineChart('lajuQtoQChart', lajuQtoQByCategory, true, selectedYearLajuQtoQ);
        setTimeout(() => {
          if (chartInstances.lajuQtoQ) chartInstances.lajuQtoQ.resize();
        }, 100);
      });
    }

    // Year filter change handler for Laju Y-to-Y
    const yearFilterLajuYtoY = document.getElementById('yearFilterLajuYtoY');
    if (yearFilterLajuYtoY) {
      yearFilterLajuYtoY.addEventListener('change', function() {
        selectedYearLajuYtoY = this.value ? parseInt(this.value) : null;
        if (chartInstances.lajuYtoY) {
          chartInstances.lajuYtoY.dispose();
        }
        chartInstances.lajuYtoY = createTriwulananLineChart('lajuYtoYChart', lajuYtoYByCategory, true, selectedYearLajuYtoY);
        setTimeout(() => {
          if (chartInstances.lajuYtoY) chartInstances.lajuYtoY.resize();
        }, 100);
      });
    }

    // Year filter change handler for Laju C-to-C
    const yearFilterLajuCtoC = document.getElementById('yearFilterLajuCtoC');
    if (yearFilterLajuCtoC) {
      yearFilterLajuCtoC.addEventListener('change', function() {
        selectedYearLajuCtoC = this.value ? parseInt(this.value) : null;
        if (chartInstances.lajuCtoC) {
          chartInstances.lajuCtoC.dispose();
        }
        chartInstances.lajuCtoC = createTriwulananLineChart('lajuCtoCChart', lajuCtoCByCategory, true, selectedYearLajuCtoC);
        setTimeout(() => {
          if (chartInstances.lajuCtoC) chartInstances.lajuCtoC.resize();
        }, 100);
      });
    }


    // Charts will be initialized in loadAllData() after data is loaded

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
    window.formatRupiah = function(value) {
      if (!value && value !== 0) return '';
      const numStr = value.toString().replace(/\D/g, '');
      return numStr.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    };
    
    function applyRupiahFormatting() {
      const rupiahElements = document.querySelectorAll('.rupiah-value');
      rupiahElements.forEach(element => {
        const value = element.getAttribute('data-value');
        if (value) {
          const formatted = window.formatRupiah(value);
          element.textContent = 'Rp ' + formatted;
        }
      });
      
      // Also format comparison values
      const comparisonContainers = document.querySelectorAll('[id^="sheet-"][id$="-comparison"]');
      comparisonContainers.forEach(container => {
        const spans = container.querySelectorAll('span');
        spans.forEach(span => {
          const text = span.textContent;
          // Check if it contains "Rp" and a number
          const rupiahMatch = text.match(/Rp\s*([\d,\.]+)/);
          if (rupiahMatch) {
            const numValue = rupiahMatch[1].replace(/\./g, '');
            const formatted = window.formatRupiah(numValue);
            span.textContent = text.replace(/Rp\s*[\d,\.]+/, 'Rp ' + formatted);
          }
        });
      });
    }
    
    // Apply formatting after DOM is ready
    setTimeout(applyRupiahFormatting, 100);
    setTimeout(applyRupiahFormatting, 600);

    // ========== Calculate Year-over-Year Comparisons for Carousel Cards ==========
    function calculateCarouselComparisons() {
      Object.keys(latestBySheet).forEach((sheetName, index) => {
        const sheetData = latestBySheet[sheetName];
        if (!sheetData || !sheetData.all_data || sheetData.all_data.length < 2) return;
        
        const sorted = [...sheetData.all_data].sort((a, b) => a.year - b.year);
        const latest = sorted[sorted.length - 1];
        const previous = sorted.find(d => d.year === latest.year - 1) || sorted[sorted.length - 2];
        
        if (!previous || !latest) return;
        
        const diff = latest.value - previous.value;
        
        const container = document.getElementById(`sheet-${index}-comparison`);
        if (!container) return;
        
        let arrow = 'â”€';
        let arrowColor = '#666';
        let valueColor = '#666';
        if (diff > 0) {
          arrow = 'â–²';
          arrowColor = '#28a745';
          valueColor = '#28a745';
        } else if (diff < 0) {
          arrow = 'â–¼';
          arrowColor = '#dc3545';
          valueColor = '#dc3545';
        }
        
        const isPercentage = sheetName.includes('Distribusi') || sheetName.includes('Laju');
        const diffFormatted = Math.abs(diff).toFixed(2);
        const diffFormattedRupiah = isPercentage ? diffFormatted : (window.formatRupiah ? window.formatRupiah(Math.abs(diff).toFixed(0)) : Math.abs(diff).toFixed(0));
        const comparisonHTML = isPercentage ? 
          `<span style="color: ${arrowColor}; font-size: 14px;">${arrow}</span>
           <span style="color: ${valueColor}; font-size: 14px; font-weight: 600;">${diff >= 0 ? '+' : ''}${diffFormatted}%</span>
           <span style="color: rgba(255, 255, 255, 0.8); font-size: 12px;">dari ${previous.year}</span>` :
          `<span style="color: ${arrowColor}; font-size: 14px;">${arrow}</span>
           <span style="color: ${valueColor}; font-size: 14px; font-weight: 600;">${diff >= 0 ? '+' : ''}Rp ${diffFormattedRupiah}</span>
           <span style="color: rgba(255, 255, 255, 0.8); font-size: 12px;">dari ${previous.year}</span>`;
        
        container.innerHTML = comparisonHTML;
        // Update comparison text color to white
        container.querySelectorAll('span').forEach(span => {
          if (span.style.color !== 'rgba(255, 255, 255, 0.8)') {
            span.style.color = 'rgba(255, 255, 255, 0.9)';
          }
        });
        
        // Re-apply Rupiah formatting after comparison is set
        setTimeout(applyRupiahFormatting, 50);
      });
    }

    // ========== Initialize on Page Load ==========
    // Load all data when page is ready
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', function() {
        loadAllData();
      });
    } else {
      loadAllData();
    }

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
        }, 350);
      });
    }
    
    // Use ResizeObserver for more accurate container size detection
    if (typeof ResizeObserver !== 'undefined') {
      // Get all chart containers
      const chartIds = [
        'adhbChart', 'adhkChart', 'lajuChart', 'lajuImplisitChart', 'distribusiChart',
        'adhbTriwulananChart', 'adhkTriwulananChart', 'distribusiTriwulananChart',
        'lajuQtoQChart', 'lajuYtoYChart', 'lajuCtoCChart', 'filteredChart'
      ];
      
      chartIds.forEach(chartId => {
        const chartElement = document.getElementById(chartId);
        if (chartElement && chartElement.parentElement) {
          const observer = new ResizeObserver(function() {
            setTimeout(function() {
              // Find the chart instance for this element
              const chartName = chartId.replace('Chart', '');
              const chart = chartInstances[chartName] || 
                           (chartId === 'filteredChart' && typeof filteredChartInstance !== 'undefined' ? filteredChartInstance : null);
              
              if (chart && chart.resize) {
                chart.resize();
              }
            }, 100);
          });
          observer.observe(chartElement.parentElement);
        }
      });
    }
  });

    // ========== Export Functionality ==========
    function exportToExcel(chartId, title) {
      if (typeof XLSX === 'undefined') {
        alert('Library XLSX belum dimuat. Mohon tunggu atau refresh halaman.');
        return;
      }
      
      const chartName = chartId.replace('Chart', '');
      let chartData = null;
      
      if (chartId === 'filteredChart' && filteredChartInstance) {
        chartData = filteredChartInstance.getOption();
      } else if (chartId === 'filteredChartTriwulanan' && filteredChartInstanceTriwulanan) {
        chartData = filteredChartInstanceTriwulanan.getOption();
      } else if (chartInstances[chartName]) {
        chartData = chartInstances[chartName].getOption();
      }
      
      if (!chartData || !chartData.series || !chartData.xAxis) return;
      
      let rows = [];
      const xAxisData = chartData.xAxis[0] && chartData.xAxis[0].data ? chartData.xAxis[0].data : [];
      
      // Header row
      let header = ['Kategori'];
      xAxisData.forEach(x => header.push(x));
      rows.push(header);
      
      // Data rows
      chartData.series.forEach(s => {
        let row = [s.name];
        if (s.data) {
          s.data.forEach(val => row.push(val));
        }
        rows.push(row);
      });
      
      const wb = XLSX.utils.book_new();
      const ws = XLSX.utils.aoa_to_sheet(rows);
      XLSX.utils.book_append_sheet(wb, ws, 'Data');
      XLSX.writeFile(wb, title + '.xlsx');
    }
    
    function exportToPNG(chartId, title) {
      const chartName = chartId.replace('Chart', '');
      let chartInstance = null;
      
      if (chartId === 'filteredChart' && filteredChartInstance) {
        chartInstance = filteredChartInstance;
      } else if (chartId === 'filteredChartTriwulanan' && filteredChartInstanceTriwulanan) {
        chartInstance = filteredChartInstanceTriwulanan;
      } else if (chartInstances[chartName]) {
        chartInstance = chartInstances[chartName];
      }
      
      if (chartInstance) {
        const url = chartInstance.getDataURL({
          type: 'png',
          pixelRatio: 2,
          backgroundColor: '#fff'
        });
        const a = document.createElement('a');
        a.download = title + '.png';
        a.href = url;
        a.click();
      }
    }
    
    function setupExportListeners() {
      // Mapping of button IDs to their chart IDs and titles
      const exportMapping = {
        'downloadAdhb': { chart: 'adhbChart', title: 'ADHB_PDRB_Lapangan_Usaha' },
        'downloadAdhk': { chart: 'adhkChart', title: 'ADHK_PDRB_Lapangan_Usaha' },
        'downloadLaju': { chart: 'lajuChart', title: 'Laju_PDRB' },
        'downloadLajuImplisit': { chart: 'lajuImplisitChart', title: 'Laju_Implisit_PDRB' },
        'downloadDistribusi': { chart: 'distribusiChart', title: 'Distribusi_PDRB_Lapangan_Usaha' },
        'downloadFiltered': { chart: 'filteredChart', title: 'PDRB_Lapangan_Usaha_Filter' },
        
        'downloadAdhbTriwulanan': { chart: 'adhbTriwulananChart', title: 'ADHB_Triwulanan_PDRB' },
        'downloadAdhkTriwulanan': { chart: 'adhkTriwulananChart', title: 'ADHK_Triwulanan_PDRB' },
        'downloadDistribusiTriwulanan': { chart: 'distribusiTriwulananChart', title: 'Distribusi_Triwulanan_PDRB' },
        'downloadLajuQtoQ': { chart: 'lajuQtoQChart', title: 'Laju_QtoQ_PDRB' },
        'downloadLajuYtoY': { chart: 'lajuYtoYChart', title: 'Laju_YtoY_PDRB' },
        'downloadLajuCtoC': { chart: 'lajuCtoCChart', title: 'Laju_CtoC_PDRB' },
        'downloadFilteredTriwulanan': { chart: 'filteredChartTriwulanan', title: 'PDRB_Triwulanan_Filter' }
      };
      
      Object.keys(exportMapping).forEach(prefix => {
        const excelBtn = document.getElementById(prefix + 'Excel');
        const pngBtn = document.getElementById(prefix + 'PNG');
        const config = exportMapping[prefix];
        
        if (excelBtn) {
          excelBtn.addEventListener('click', (e) => {
            e.preventDefault();
            exportToExcel(config.chart, config.title);
          });
        }
        
        if (pngBtn) {
          pngBtn.addEventListener('click', (e) => {
            e.preventDefault();
            exportToPNG(config.chart, config.title);
          });
        }
      });
    }
    
    // Call it after a short delay to ensure DOM and charts are ready
    setTimeout(setupExportListeners, 500);


