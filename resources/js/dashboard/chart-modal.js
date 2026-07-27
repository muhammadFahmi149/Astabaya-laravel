document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('globalChartModal');
    if (!modalEl) return;

    const modal = new bootstrap.Modal(modalEl);
    const modalTitle = document.getElementById('globalChartModalLabel');
    const modalCanvas = document.getElementById('modalChartCanvas');
    
    // Action buttons in modal
    let modalShareBtn = document.getElementById('modalShareBtn');
    const modalDownloadExcel = document.getElementById('modalDownloadExcel');
    const modalDownloadPNG = document.getElementById('modalDownloadPNG');
    
    let currentModalChart = null;
    let currentOriginalChartId = null;

    // Cleanup when modal hides
    modalEl.addEventListener('hidden.bs.modal', function () {
        if (currentModalChart) {
            currentModalChart.dispose();
            currentModalChart = null;
        }
        currentOriginalChartId = null;
        
        // Remove ?chart= parameter from URL without reloading
        const url = new URL(window.location);
        if (url.searchParams.has('chart')) {
            url.searchParams.delete('chart');
            window.history.replaceState({}, '', url);
        }
    });

    // Resize event when modal resizes
    window.addEventListener('resize', () => {
        if (currentModalChart && modalEl.classList.contains('show')) {
            currentModalChart.resize();
        }
    });
    
    // Copy link helper
    function copyToClipboard(url, btn) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(() => {
                showSuccessState(btn);
            }).catch(err => {
                console.error('Gagal menyalin:', err);
                alert('Gagal menyalin link');
            });
        } else {
            const input = document.createElement('input');
            input.value = url;
            input.style.position = 'fixed';
            input.style.opacity = '0';
            document.body.appendChild(input);
            input.select();
            try {
                document.execCommand('copy');
                showSuccessState(btn);
            } catch (err) {
                alert('Gagal menyalin link');
            }
            document.body.removeChild(input);
        }
    }
    
    // Slug generator
    function generateSlug(text) {
        return text.toString().toLowerCase().trim()
            .replace(/[\s_]+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-');
    }
    
    function showSuccessState(btn) {
        if (!btn) return;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class=\'fas fa-check\'></i> <span class="share-text">Tersalin!</span>';
        btn.classList.add('btn-success');
        btn.classList.remove('btn-outline-primary');
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-primary');
        }, 2000);
    }

    function openChartInModal(card) {
        // Find Echarts DOM inside card
        const originalCanvas = card.querySelector('div[_echarts_instance_]');
        if (!originalCanvas) return;
        
        const originalChartId = originalCanvas.id;
        if (!originalChartId) return;
        
        const originalChart = window.echarts && window.echarts.getInstanceByDom(originalCanvas);
        if (!originalChart) return;
        
        currentOriginalChartId = originalChartId;
        
        // Get chart title
        let chartTitle = 'Detail Grafik';
        const titleEl = card.querySelector('h4, h5, h6');
        if (titleEl) {
            chartTitle = titleEl.textContent;
        }
        modalTitle.textContent = chartTitle;

        // Setup share button logic
        if (modalShareBtn) {
            // Remove old listeners by cloning
            const newShareBtn = modalShareBtn.cloneNode(true);
            modalShareBtn.parentNode.replaceChild(newShareBtn, modalShareBtn);
            
            newShareBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const baseUrl = window.location.origin + window.location.pathname;
                const searchParams = new URLSearchParams(window.location.search);
                searchParams.set('chart', generateSlug(chartTitle));
                const url = baseUrl + '?' + searchParams.toString();
                copyToClipboard(url, this);
            });
            // Update reference
            modalShareBtn = newShareBtn;
        }
        
        // Setup download logic (triggering existing window.checkAuthBeforeDownload)
        if (modalDownloadExcel) {
            modalDownloadExcel.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (window.checkAuthBeforeDownload) {
                    window.checkAuthBeforeDownload(() => {
                        if (typeof window.exportToExcel === 'function') {
                            window.exportToExcel(currentOriginalChartId, chartTitle);
                        } else {
                            // Find and click the original button
                            const origExcelBtn = document.getElementById(`download${currentOriginalChartId}Excel`) || document.getElementById(`download${currentOriginalChartId.replace('Chart','')}Excel`);
                            if (origExcelBtn) origExcelBtn.click();
                        }
                    }, 'data ' + chartTitle);
                }
            };
        }
        
        if (modalDownloadPNG) {
            modalDownloadPNG.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (window.checkAuthBeforeDownload) {
                    window.checkAuthBeforeDownload(() => {
                        if (typeof window.exportToPNG === 'function') {
                            window.exportToPNG(currentOriginalChartId, chartTitle);
                        } else {
                            // Find and click the original button
                            const origPngBtn = document.getElementById(`download${currentOriginalChartId}PNG`) || document.getElementById(`download${currentOriginalChartId.replace('Chart','')}PNG`);
                            if (origPngBtn) origPngBtn.click();
                        }
                    }, 'grafik ' + chartTitle);
                }
            };
        }
        
        // Show modal first so canvas has dimensions
        modal.show();
        
        // Update URL to reflect the open chart (for shareability and history)
        const newUrl = new URL(window.location);
        newUrl.searchParams.set('chart', generateSlug(chartTitle));
        // Only pushState if the current URL doesn't already have this chart param
        if (window.location.search.indexOf('chart=' + generateSlug(chartTitle)) === -1) {
            window.history.pushState({}, '', newUrl);
        }
        
        // Render chart in modal
        setTimeout(() => {
            if (currentModalChart) {
                currentModalChart.dispose();
            }
            currentModalChart = window.echarts.init(modalCanvas);
            
            // Get original options
            let options = originalChart.getOption();
            
            // Set options to new chart
            currentModalChart.setOption(options);
            
        }, 300);
    }

    // Attach click listener to all dashboard cards that have charts
    function setupCardListeners() {
        const cards = document.querySelectorAll('.dashboard-card');
        cards.forEach(card => {
            // Find if it has a chart inside
            const hasChartCanvas = card.querySelector('div[id^="chart"], div[id*="Chart"], .chart-container, .chart-container-desktop, div[_echarts_instance_]');
            
            if (hasChartCanvas) {
                // Only setup if not already setup
                if (card.dataset.modalSetup) return;
                
                card.style.cursor = 'zoom-in';
                card.dataset.modalSetup = 'true';
                
                // Add hover effect
                card.addEventListener('mouseenter', () => {
                    if (document.body.classList.contains('modal-open')) return;
                    card.style.transform = 'translateY(-2px)';
                    card.style.transition = 'transform 0.2s';
                    card.style.boxShadow = '0 8px 20px rgba(0,0,0,0.1)';
                });
                card.addEventListener('mouseleave', () => {
                    card.style.transform = '';
                    card.style.boxShadow = '';
                });
                
                card.addEventListener('click', function(e) {
                    // Ignore clicks on interactive elements inside the card
                    const isInteractive = e.target.closest('button, a, input, select, .dropdown, .chart-header-actions, .filter-group, label');
                    if (isInteractive) return;
                    
                    openChartInModal(this);
                });
            }
        });
    }

    // Setup initially and also after a short delay to allow charts to render
    setupCardListeners();
    setTimeout(setupCardListeners, 1500);
    setTimeout(setupCardListeners, 3000); // Sometimes Echarts takes a bit longer

    // Check URL parameters for direct chart link
    const urlParams = new URLSearchParams(window.location.search);
    const chartIdParam = urlParams.get('chart');
    if (chartIdParam) {
        // Give time for charts to render first
        let attempts = 0;
        const tryOpen = setInterval(() => {
            attempts++;
            
            // Find card by slug matching the title
            let targetCard = null;
            const cards = document.querySelectorAll('.dashboard-card');
            for (let card of cards) {
                const titleEl = card.querySelector('h4, h5, h6');
                if (titleEl) {
                    if (generateSlug(titleEl.textContent) === chartIdParam) {
                        targetCard = card;
                        break;
                    }
                }
            }
            
            // If not found by slug, fallback to checking ID for backwards compatibility
            if (!targetCard) {
                const targetChart = document.getElementById(chartIdParam);
                if (targetChart) {
                    targetCard = targetChart.closest('.dashboard-card');
                }
            }
            
            if (targetCard) {
                // Ensure Echarts is ready inside this card
                const chartCanvas = targetCard.querySelector('div[_echarts_instance_]');
                if (chartCanvas && window.echarts && window.echarts.getInstanceByDom(chartCanvas)) {
                    clearInterval(tryOpen);
                    openChartInModal(targetCard);
                    return;
                }
            }
            
            if (attempts > 10) {
                clearInterval(tryOpen); // Stop trying after 5 seconds
            }
        }, 500);
    }
});
