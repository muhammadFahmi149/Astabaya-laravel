
function escapeHTML(str) {
    if (typeof str !== 'string') return str;
    return str.replace(/[&<>'"]/g, tag => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        "'": '&#39;',
        '"': '&quot;'
    }[tag] || tag));
}

let currentContentType = 'news'; // Default to news

  // Switch content type (news/publication/infographic)
  function switchContentType(type) {
    currentContentType = type;
    
    // Update active button
    document.querySelectorAll('.category-btn').forEach(btn => {
      btn.classList.remove('active');
    });
    document.querySelector(`.category-btn[data-type="${type}"]`).classList.add('active');
    
    // Update carousel based on type
    updateCarousel(type);
    
    // Update content cards
    document.getElementById('newsCards').style.display = type === 'news' ? 'contents' : 'none';
    document.getElementById('publicationCards').style.display = type === 'publication' ? 'contents' : 'none';
    document.getElementById('infographicCards').style.display = type === 'infographic' ? 'contents' : 'none';
    
    // Update section title and link
    const titles = {
      'news': 'Berita',
      'publication': 'Publikasi',
      'infographic': 'Infografis'
    };
    
    const links = {
      'news': window.DASHBOARD_CONFIG.routes.news,
      'publication': window.DASHBOARD_CONFIG.routes.publications,
      'infographic': window.DASHBOARD_CONFIG.routes.infographics
    };
    
    document.getElementById('contentSectionTitle').textContent = titles[type];
    document.getElementById('viewMoreLink').href = links[type];
  }
  
  // Make switchContentType available globally for inline onclick handlers
  window.switchContentType = switchContentType;

  // Update carousel based on content type
  function updateCarousel(type) {
    const carousel = document.getElementById('contentCarousel');
    if (!carousel) return;
    
    const carouselInner = carousel.querySelector('.carousel-inner');
    if (!carouselInner) return;
    
    // Get items based on type
    let items = [];
    try {
      if (type === 'news') {
        items = window.DASHBOARD_CONFIG.carouselData.news || [];
      } else if (type === 'publication') {
        items = window.DASHBOARD_CONFIG.carouselData.publications || [];
      } else if (type === 'infographic') {
        items = window.DASHBOARD_CONFIG.carouselData.infographics || [];
      }
    } catch (e) {
      console.error('Error getting carousel items:', e);
      items = [];
    }
    
    if (items && items.length > 0) {
      carouselInner.innerHTML = items.map((item, index) => {
        const dateStr = item.date ? new Date(item.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '';
        const isPublication = type === 'publication';
        const isInfographic = type === 'infographic';
        const imageClass = isPublication ? 'carousel-publication-image' : (isInfographic ? 'carousel-infographic-image' : '');
        
        // For publications and infographics, wrap image in container
        if (isPublication || isInfographic) {
          return `
          <div class="carousel-item ${index === 0 ? 'active' : ''}">
            <div class="carousel-image-wrapper">
              <img src="${item.image || '' + window.DASHBOARD_CONFIG.defaultPlaceholderImg + ''}" 
                   alt="${escapeHTML(item.title || 'Item')}"
                   class="${imageClass}"
                   onclick="${isPublication ? `showPublicationModal('${item.id}')` : `showInfographicDetail('${item.id}')`}"
                   style="cursor: pointer;"
                   onerror="this.onerror=null; this.src='${window.DASHBOARD_CONFIG.defaultPlaceholderImg}'">
            </div>
            <div class="carousel-overlay" onclick="${isPublication ? `showPublicationModal('${item.id}')` : `showInfographicDetail('${item.id}')`}" style="cursor: pointer;">
              <h5>${escapeHTML(item.title || 'Item')}</h5>
              <p>
                <span class="badge bg-primary">${escapeHTML((item.type || 'item').charAt(0).toUpperCase() + (item.type || 'item').slice(1))}</span>
                ${dateStr ? `<span class="ms-2">${dateStr}</span>` : ''}
              </p>
            </div>
          </div>
        `;
        } else {
          // For news, use original styling
          return `
          <div class="carousel-item ${index === 0 ? 'active' : ''}">
            <img src="${item.image || '' + window.DASHBOARD_CONFIG.defaultPlaceholderImg + ''}" 
                 alt="${escapeHTML(item.title || 'Item')}"
                 onclick="showNewsModal('${item.id}')"
                 style="cursor: pointer;"
                 onerror="this.onerror=null; this.src='${window.DASHBOARD_CONFIG.defaultPlaceholderImg}'">
            <div class="carousel-overlay" onclick="showNewsModal('${item.id}')" style="cursor: pointer;">
              <h5>${escapeHTML(item.title || 'Item')}</h5>
              <p>
                <span class="badge bg-primary">${escapeHTML((item.type || 'item').charAt(0).toUpperCase() + (item.type || 'item').slice(1))}</span>
                ${dateStr ? `<span class="ms-2">${dateStr}</span>` : ''}
              </p>
            </div>
          </div>
        `;
        }
      }).join('');
      
      // Dispose existing carousel instance if any
      const existingCarousel = bootstrap.Carousel.getInstance(carousel);
      if (existingCarousel) {
        existingCarousel.dispose();
      }
      
      // Reinitialize carousel
      new bootstrap.Carousel(carousel, {
        interval: 5000,
        wrap: true
      });
    } else {
      // Show empty state
      carouselInner.innerHTML = `
        <div class="carousel-item active">
          <div style="width: 100%; height: 400px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
            <div class="text-center text-muted">
              <i class="bi bi-image" style="font-size: 3rem;"></i>
              <p class="mt-3">Belum ada data untuk ditampilkan</p>
            </div>
          </div>
        </div>
      `;
    }
  }

  // Scroll indicator cards
  function scrollIndicators(direction) {
    const container = document.getElementById('indicatorCardsContainer');
    const scrollAmount = 200;
    
    if (direction === 'prev') {
      container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    } else {
      container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }
    
    // Update button states
    updateScrollButtons();
  }

  // Update scroll button states
  function updateScrollButtons() {
    const container = document.getElementById('indicatorCardsContainer');
    const prevBtn = document.querySelector('.indicator-scroll-btn.prev');
    const nextBtn = document.querySelector('.indicator-scroll-btn.next');
    
    prevBtn.disabled = container.scrollLeft <= 0;
    nextBtn.disabled = container.scrollLeft >= container.scrollWidth - container.clientWidth;
  }

  window.showNewsModal = async function(id) {
    const modalEl = document.getElementById('newsCardModal');
    if (!modalEl) return;
    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
    
    // Show loading state immediately
    const modalTitle = document.getElementById('newsModalTitle');
    const modalCategory = document.getElementById('newsModalCategory');
    const modalDate = document.getElementById('newsModalDate');
    const modalContent = document.getElementById('newsModalContent');
    const modalImageContainer = document.getElementById('newsModalImageContainer');
    
    if (modalTitle) modalTitle.textContent = 'Memuat Berita...';
    if (modalCategory) modalCategory.innerHTML = '';
    if (modalDate) modalDate.innerHTML = '';
    if (modalImageContainer) modalImageContainer.innerHTML = '';
    if (modalContent) modalContent.innerHTML = '<div class="text-center py-4"><span class="spinner-border spinner-border-sm text-primary me-2" role="status"></span><em>Mengambil data...</em></div>';
    
    modal.show();

    try {
      const response = await fetch(window.DASHBOARD_CONFIG.apiBase + `/news/${encodeURIComponent(id)}`);
      if (response.ok) {
        const data = await response.json();
        const news = data.data || data;
        
        if (modalTitle) modalTitle.textContent = news.title || '';
        if (modalCategory) modalCategory.innerHTML = `<i class="bi bi-tag-fill me-1"></i> ${news.category_name || news.category || 'Berita'}`;
        
        let formattedDate = 'N/A';
        if (news.release_date || news.date || news.created_at) {
          const dateVal = news.release_date || news.date || news.created_at;
          try { formattedDate = new Date(dateVal).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }); }
          catch(e) { formattedDate = dateVal; }
        }
        if (modalDate) modalDate.innerHTML = `<i class="bi bi-calendar-event me-1"></i> ${formattedDate}`;
        
        if (modalImageContainer) {
          const imageUrl = news.picture_url || news.image || window.DASHBOARD_CONFIG.defaultPlaceholderImg;
          modalImageContainer.innerHTML = `<img src="${imageUrl}" alt="${escapeHTML(news.title || '')}" class="img-fluid rounded shadow-sm w-100" style="max-height: 300px; object-fit: cover;">`;
        }
        
        if (modalContent) modalContent.innerHTML = news.content || news.fullContent || 'Tidak ada konten';
        
        const shareBtn = document.querySelector('.share-news-modal-btn');
        if (shareBtn) {
          shareBtn.dataset.pubTitle = news.title || '';
          shareBtn.dataset.pubUrl = window.location.origin + '/news?news=' + id;
        }
        
        const bookmarkBtn = document.getElementById('modalNewsBookmarkBtn');
        if (bookmarkBtn) {
          bookmarkBtn.dataset.objectId = id;
          if (typeof updateBookmarkButtonState === 'function') updateBookmarkButtonState('news', id, bookmarkBtn);
        }
      } else {
        window.location.href = window.DASHBOARD_CONFIG.routes.news + '?news=' + id;
      }
    } catch (e) {
      console.error(e);
      window.location.href = window.DASHBOARD_CONFIG.routes.news + '?news=' + id;
    }
  };

  window.showPublicationModal = async function(id) {
    const modalEl = document.getElementById('publicationModal');
    if (!modalEl) return;
    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
    
    // Show loading state immediately
    const modalTitle = document.getElementById('modalTitle');
    const modalImage = document.getElementById('modalImage');
    const modalDate = document.getElementById('modalDate');
    const modalSize = document.getElementById('modalSize');
    const modalPubId = document.getElementById('modalPubId');
    const modalAbstract = document.getElementById('modalAbstract');
    
    if (modalTitle) modalTitle.textContent = 'Memuat Publikasi...';
    if (modalImage) {
        modalImage.src = window.DASHBOARD_CONFIG.defaultPlaceholderImg;
        modalImage.alt = 'Loading...';
    }
    if (modalDate) modalDate.textContent = '...';
    if (modalSize) modalSize.textContent = '...';
    if (modalPubId) modalPubId.textContent = '...';
    if (modalAbstract) modalAbstract.innerHTML = '<div class="text-center py-4"><span class="spinner-border spinner-border-sm text-primary me-2" role="status"></span><em>Mengambil data...</em></div>';
    
    modal.show();

    try {
      const response = await fetch(window.DASHBOARD_CONFIG.apiBase + `/publications/${encodeURIComponent(id)}`);
      if (response.ok) {
        const data = await response.json();
        const pub = data.data || data;
        
        if (modalTitle) modalTitle.textContent = pub.title || '';
        if (modalImage) {
            modalImage.src = pub.image || window.DASHBOARD_CONFIG.defaultPlaceholderImg;
            modalImage.alt = pub.title || '';
        }
        
        let formattedDate = 'N/A';
        if (pub.date) {
            try { formattedDate = new Date(pub.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }); }
            catch(e) { formattedDate = pub.date; }
        }
        if (modalDate) modalDate.textContent = formattedDate;
        if (modalSize) modalSize.textContent = pub.size || 'N/A';
        if (modalPubId) modalPubId.textContent = pub.pub_id || pub.id || id;
        
        const subjCsa = document.getElementById('modalSubjectCsa');
        const subjCsaContainer = document.getElementById('modalSubjectCsaContainer');
        if (pub.subject_csa) {
            if (subjCsa) subjCsa.textContent = pub.subject_csa;
            if (subjCsaContainer) subjCsaContainer.style.display = 'block';
        } else {
            if (subjCsaContainer) subjCsaContainer.style.display = 'none';
        }
        
        if (modalAbstract) modalAbstract.innerHTML = pub.abstract || 'Tidak ada abstrak tersedia.';
        
        const modalDownloadBtn = document.getElementById('modalDownload');
        if (modalDownloadBtn) {
            modalDownloadBtn.setAttribute('data-pub-id', pub.id || id);
            modalDownloadBtn.setAttribute('data-pub-title', pub.title || '');
        }
        
        const shareBtn = document.querySelector('.share-publication-modal-btn');
        if (shareBtn) {
            shareBtn.dataset.pubTitle = pub.title || '';
            shareBtn.dataset.pubUrl = window.location.origin + '/publications?publication=' + (pub.id || id);
        }
        
        const bookmarkBtn = document.getElementById('modalBookmarkBtn');
        if (bookmarkBtn) {
            bookmarkBtn.dataset.objectId = pub.id || id;
            if (typeof updateBookmarkButtonState === 'function') updateBookmarkButtonState('publication', pub.id || id, bookmarkBtn);
        }
      } else {
        window.location.href = window.DASHBOARD_CONFIG.routes.publications + '?publication=' + id;
      }
    } catch (e) {
      console.error(e);
      window.location.href = window.DASHBOARD_CONFIG.routes.publications + '?publication=' + id;
    }
  };
  
  window.showInfographicDetail = async function(id) {
    const modalEl = document.getElementById('infographicModal');
    if (!modalEl) return;
    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
    
    // Show loading state immediately
    const modalTitle = document.getElementById('infographicModalTitle');
    const modalImage = document.getElementById('infographicModalImage');
    
    if (modalTitle) modalTitle.textContent = 'Memuat Infografis...';
    if (modalImage) {
        modalImage.src = window.DASHBOARD_CONFIG.defaultPlaceholderImg;
        modalImage.alt = 'Loading...';
    }
    
    modal.show();

    try {
      const response = await fetch(window.DASHBOARD_CONFIG.apiBase + `/infographics/${encodeURIComponent(id)}`);
      if (response.ok) {
        const data = await response.json();
        const info = data.data || data;
        
        if (modalTitle) modalTitle.textContent = info.title || '';
        
        if (modalImage) {
            modalImage.src = info.image || window.DASHBOARD_CONFIG.defaultPlaceholderImg;
            modalImage.alt = info.title || '';
        }
        
        const downloadBtn = document.getElementById('infographicModalDownload');
        if (downloadBtn) {
            downloadBtn.dataset.infographicId = info.id || id;
            downloadBtn.dataset.infographicTitle = info.title || '';
        }
        
        const shareBtns = document.querySelectorAll('.share-infographic-modal-btn, .share-infographic-btn');
        shareBtns.forEach(btn => {
            btn.dataset.pubTitle = info.title || '';
            btn.dataset.pubUrl = window.location.origin + '/infographics?infographic=' + (info.id || id);
        });
        
        const bookmarkBtn = document.getElementById('modalInfographicBookmarkBtn');
        if (bookmarkBtn) {
            bookmarkBtn.dataset.objectId = info.id || id;
            if (typeof updateBookmarkButtonState === 'function') updateBookmarkButtonState('infographic', info.id || id, bookmarkBtn);
        }
      } else {
        window.location.href = window.DASHBOARD_CONFIG.routes.infographics + '?infographic=' + id;
      }
    } catch (e) {
      console.error(e);
      window.location.href = window.DASHBOARD_CONFIG.routes.infographics + '?infographic=' + id;
    }
  };

  // Handle publication download
  function handlePublicationDownload(button) {
    
    if (!window.DASHBOARD_CONFIG.isAuthenticated) {
      const pubTitle = button.dataset.pubTitle || 'publikasi ini';
      if (typeof showLoginRequiredModal === 'function') {
        showLoginRequiredModal(pubTitle, 'Ingin mengunduh ' + pubTitle + '? Silakan login terlebih dahulu.');
      } else {
        window.location.href = window.DASHBOARD_CONFIG.routes.login;
      }
      return;
    }

    const pubId = button.dataset.pubId || '';
    if (pubId) {
      window.open(window.DASHBOARD_CONFIG.routes.downloadPublication.replace(':id', pubId), '_blank');
    }

  }

  // Handle infographic download
  function handleInfographicDownload(button) {
    
    if (!window.DASHBOARD_CONFIG.isAuthenticated) {
      const infographicTitle = button.dataset.infographicTitle || 'infografis ini';
      if (typeof showLoginRequiredModal === 'function') {
        showLoginRequiredModal(infographicTitle, 'Ingin mengunduh ' + infographicTitle + '? Silakan login terlebih dahulu.');
      } else {
        window.location.href = window.DASHBOARD_CONFIG.routes.login;
      }
      return;
    }

    const infographicId = button.dataset.infographicId || '';
    if (infographicId) {
      window.open(window.DASHBOARD_CONFIG.routes.downloadInfographic.replace(':id', infographicId), '_blank');
    }

  }

  // Make functions globally available
  window.handlePublicationDownload = handlePublicationDownload;
  window.handleInfographicDownload = handleInfographicDownload;

  // Initialize on page load
  document.addEventListener('DOMContentLoaded', function() {
    updateScrollButtons();
    
    // Update scroll buttons on scroll
    const container = document.getElementById('indicatorCardsContainer');
    container.addEventListener('scroll', updateScrollButtons);
    
    // Set default to news
    switchContentType('news');
    
    // Initialize summary cards carousel
    initSummaryCardsCarousel();
  });

  // ========== Summary Cards Carousel ==========
  async function initSummaryCardsCarousel() {
    const API_BASE = window.DASHBOARD_CONFIG.apiBase;
    const location = 'Kota Surabaya';
    const carousel = document.getElementById('summaryCardsCarousel');
    if (!carousel) return;

    try {
      // Fetch aggregated data from BFF endpoint
      const summaryResponse = await fetch(`${API_BASE}/dashboard-summary?location=${encodeURIComponent(location)}`)
        .then(r => r.json())
        .catch(() => ({ success: false, data: {} }));

      const aggregatedData = summaryResponse.success ? summaryResponse.data : {};

      // Normalize responses to match the expected { success: true, data: {...} } format
      const normalize = (res) => {
        if (!res) return { success: false, data: null };
        return { 
          success: res.success === true || res.status === 'success', 
          data: res.data 
        };
      };

      const inflasiRes = normalize(aggregatedData.inflasi);
      const kemiskinanRes = normalize(aggregatedData.kemiskinan);
      const kependudukanRes = normalize(aggregatedData.kependudukan);
      const ketenagakerjaanRes = normalize(aggregatedData.ketenagakerjaan);
      const pdrbPengeluaranRes = normalize(aggregatedData.pdrbPengeluaran);
      const pdrbLapanganUsahaRes = normalize(aggregatedData.pdrbLapanganUsaha);
      const hotelOccupancyRes = normalize(aggregatedData.hotelOccupancy);
      const giniRatioRes = normalize(aggregatedData.giniRatio);
      const ipmRes = normalize(aggregatedData.ipm);
      const uhhSpRes = normalize(aggregatedData.uhhSp);
      const hlsRes = normalize(aggregatedData.hls);
      const rlsRes = normalize(aggregatedData.rls);
      const pengeluaranRes = normalize(aggregatedData.pengeluaran);
      const indeksKesehatanRes = normalize(aggregatedData.indeksKesehatan);
      const indeksPendidikanRes = normalize(aggregatedData.indeksPendidikan);
      const indeksHidupLayakRes = normalize(aggregatedData.indeksHidupLayak);

      const cards = [];

      // Helper function to get latest data
      const getLatest = (data, valueKey = 'value') => {
        if (!data || !Array.isArray(data) || data.length === 0) return null;
        const sorted = [...data].sort((a, b) => (b.year || 0) - (a.year || 0));
        const latest = sorted[0];
        // Find previous year data
        const previousYear = latest.year - 1;
        let previous = sorted.find(d => d.year === previousYear);
        if (!previous && sorted.length > 1) {
          previous = sorted[1];
        }
        // Get value based on valueKey
        let value;
        if (valueKey === 'ipm_value') {
          value = latest.ipm_value !== undefined ? latest.ipm_value : latest.value;
        } else {
          value = latest[valueKey] !== undefined ? latest[valueKey] : latest.value;
        }
        return {
          value: value,
          year: latest.year,
          previous: previous,
          previousYear: previous ? previous.year : (latest.year - 1)
        };
      };

      // Helper function to format value
      const formatValue = (value, type = 'number') => {
        if (value === null || value === undefined || value === '') return '-';
        if (type === 'currency') {
          return `Rp ${Number(value).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        } else if (type === 'percent') {
          return `${Number(value).toFixed(2)}%`;
        } else if (type === 'population') {
          const numValue = parseFloat(value);
          if (numValue >= 1000000) {
            return (numValue / 1000000).toFixed(2).replace(/\.?0+$/, '') + ' juta';
          } else if (numValue >= 1000) {
            return (numValue / 1000).toFixed(2).replace(/\.?0+$/, '') + ' ribu';
          }
          return numValue.toLocaleString('id-ID');
        } else if (type === 'raw_locale') {
          return Number(value).toLocaleString('id-ID');
        } else if (type === 'number') {
          return Number(value).toFixed(2);
        }
        return value;
      };

      // Helper function to calculate comparison
      const getComparison = (latestData, previousData, valueKey = 'value') => {
        if (!latestData || !previousData || !latestData.previous) return null;
        
        const latestVal = latestData.value;
        const prevVal = previousData[valueKey] !== undefined ? previousData[valueKey] : previousData.value;
        
        if (latestVal === null || latestVal === undefined || prevVal === null || prevVal === undefined) return null;
        const diff = parseFloat(latestVal) - parseFloat(prevVal);
        return {
          diff: diff,
          arrow: diff > 0 ? '▲' : (diff < 0 ? '▼' : '─'),
          color: diff > 0 ? '#28a745' : (diff < 0 ? '#dc3545' : '#666')
        };
      };

      // Helper function to create card
      const createCard = (latest, title, valueType, link, valueKey = 'value', suffix = '') => {
        if (!latest || latest.value === null || latest.value === undefined) return null;
        
        let comparison = null;
        // Calculate comparison if previous data exists
        const prevValueRaw = latest.previous ? (latest.previous[valueKey] !== undefined ? latest.previous[valueKey] : latest.previous.value) : null;
        if (latest.previous && prevValueRaw !== null && prevValueRaw !== undefined) {
          const latestVal = parseFloat(latest.value);
          const prevVal = parseFloat(prevValueRaw);
          
          if (!isNaN(latestVal) && !isNaN(prevVal)) {
            const diff = latestVal - prevVal;
            comparison = {
              diff: diff,
              arrow: diff > 0 ? '▲' : (diff < 0 ? '▼' : '─'),
              color: diff > 0 ? '#28a745' : (diff < 0 ? '#dc3545' : '#666')
            };
          }
        }
        
        let value = formatValue(latest.value, valueType);
        if (suffix) value += suffix;
        
        return {
          title: title,
          value: value,
          year: latest.year,
          previousYear: latest.previousYear,
          comparison: comparison,
          link: link
        };
      };

      // ========== INFLASI ==========
      // Endpoint -summary returns: { status: 'success', data: { latest, previous_month, previous_year, m_to_m_change, y_on_y_change } }
      if (inflasiRes.success && inflasiRes.data) {
        const inflasiData = inflasiRes.data;
        const latest = inflasiData.latest;
        const previousMonth = inflasiData.previous_month;
        const previousYear = inflasiData.previous_year;
        
        if (latest) {
          // Helper to create card with specific previous data
          const createInflasiCard = (field, title, previousData, prevLabel) => {
            if (latest[field] !== null && latest[field] !== undefined) {
              const prevValue = previousData && previousData[field] !== null && previousData[field] !== undefined ? previousData[field] : null;
              return createCard({
                value: latest[field],
                year: latest.year,
                previous: prevValue !== null ? { value: prevValue, year: previousData.year } : null,
                previousYear: prevLabel || (previousData && previousData.year ? previousData.year : (latest.year - 1))
              }, title, 'percent', '{{ route("inflasi") }}');
            }
            return null;
          };
          
          // Inflasi Bulan ke Bulan (m-to-m) - field: bulanan
          // Compare with previous month
          const card1 = createInflasiCard('bulanan', 'Inflasi Bulan ke Bulan', previousMonth, previousMonth ? previousMonth.month + ' ' + previousMonth.year : '');
          if (card1) cards.push(card1);
          
          // Inflasi Tahun ke Tahun (y-on-y) - field: yoy
          // Compare with previous year
          const card2 = createInflasiCard('yoy', 'Inflasi Tahun ke Tahun', previousYear, previousYear ? previousYear.year : '');
          if (card2) cards.push(card2);
          
          // Inflasi Kumulatif
          // Compare with previous year
          const card3 = createInflasiCard('kumulatif', 'Inflasi Kumulatif', previousYear, previousYear ? previousYear.year : '');
          if (card3) cards.push(card3);
        }
      }

      // ========== KEMISKINAN ==========
      // Endpoint -summary returns: { success: true, data: { surabaya_latest, surabaya_previous, surabaya_changes } }
      if (kemiskinanRes.success && kemiskinanRes.data) {
        const kemiskinanData = kemiskinanRes.data;
        const latest = kemiskinanData.surabaya_latest;
        const previous = kemiskinanData.surabaya_previous;
        
        if (latest) {
          // Helper to create card with proper previous data
          const createKemiskinanCard = (field, title, valueType, suffix = '') => {
            if (latest[field] !== null && latest[field] !== undefined) {
              const prevValue = previous && previous[field] !== null && previous[field] !== undefined ? previous[field] : null;
              return createCard({
                value: latest[field],
                year: latest.year,
                previous: prevValue !== null ? { value: prevValue, year: previous.year } : null,
                previousYear: previous && previous.year ? previous.year : (latest.year - 1)
              }, title, valueType, '{{ route("kemiskinan") }}', 'value', suffix);
            }
            return null;
          };
          
          // Jumlah Penduduk Miskin
          const card1 = createKemiskinanCard('jumlah_penduduk_miskin', 'Jumlah Penduduk Miskin', 'number', ' ribu');
          if (card1) cards.push(card1);
          
          // Persentase Kemiskinan
          const card2 = createKemiskinanCard('persentase_penduduk_miskin', 'Persentase Kemiskinan', 'percent');
          if (card2) cards.push(card2);
          
          // Indeks Kedalaman (P1)
          const card3 = createKemiskinanCard('indeks_kedalaman_kemiskinan_p1', 'Indeks Kedalaman (P1)', 'number');
          if (card3) cards.push(card3);
          
          // Indeks Keparahan (P2)
          const card4 = createKemiskinanCard('indeks_keparahan_kemiskinan_p2', 'Indeks Keparahan (P2)', 'number');
          if (card4) cards.push(card4);
          
          // Garis Kemiskinan
          const card5 = createKemiskinanCard('garis_kemiskinan', 'Garis Kemiskinan', 'currency');
          if (card5) cards.push(card5);
        }
      }

      // ========== KEPENDUDUKAN ==========
      // Endpoint -summary returns: { success: true, data: { total_population, total_male, total_female, population_ratio, selected_year, previous_year, total_change, male_change, female_change, previous_year_data } }
      if (kependudukanRes.success && kependudukanRes.data) {
        const kependudukanData = kependudukanRes.data;
        const year = kependudukanData.selected_year || kependudukanData.year;
        const previousYear = kependudukanData.previous_year || (year ? year - 1 : null);
        const previousData = kependudukanData.previous_year_data || kependudukanData.previous;
        
        // Helper to create card with previous data
        const createKependudukanCard = (field, title, valueType, changeField = null) => {
          if (kependudukanData[field] !== null && kependudukanData[field] !== undefined) {
            // Try to get previous value from previousData or calculate from change
            let prevValue = null;
            if (previousData && previousData[field] !== null && previousData[field] !== undefined) {
              prevValue = previousData[field];
            } else if (changeField && kependudukanData[changeField] !== null && kependudukanData[changeField] !== undefined) {
              // Calculate previous from change
              const currentValue = parseFloat(kependudukanData[field]);
              const change = parseFloat(kependudukanData[changeField]);
              if (!isNaN(currentValue) && !isNaN(change)) {
                prevValue = currentValue - change;
              }
            }
            
            return createCard({
              value: kependudukanData[field],
              year: year,
              previous: prevValue !== null ? { value: prevValue, year: previousYear } : null,
              previousYear: previousYear
            }, title, valueType, '{{ route("kependudukan") }}');
          }
          return null;
        };
        
        // Total Penduduk
        const card1 = createKependudukanCard('total_population', 'Total Penduduk', 'population', 'total_change');
        if (card1) cards.push(card1);
        
        // Total Laki-laki
        const card2 = createKependudukanCard('total_male', 'Total Laki-laki', 'population', 'male_change');
        if (card2) cards.push(card2);
        
        // Total Perempuan
        const card3 = createKependudukanCard('total_female', 'Total Perempuan', 'population', 'female_change');
        if (card3) cards.push(card3);
        
        // Rasio Jenis Kelamin
        const ratioValue = kependudukanData.population_ratio || kependudukanData.population_ratio_display;
        if (ratioValue !== null && ratioValue !== undefined) {
          const prevRatio = previousData && (previousData.population_ratio || previousData.population_ratio_display);
          const card = createCard({
            value: ratioValue,
            year: year,
            previous: prevRatio !== null && prevRatio !== undefined ? { value: prevRatio, year: previousYear } : null,
            previousYear: previousYear
          }, 'Rasio Jenis Kelamin', 'number', '{{ route("kependudukan") }}');
          if (card) cards.push(card);
        }
      }

      // ========== KETENAGAKERJAAN ==========
      // Endpoint -summary returns: { success: true, data: { tpt_latest_data, tpak_latest_data, tpt_previous_data, tpak_previous_data } }
      if (ketenagakerjaanRes.success && ketenagakerjaanRes.data) {
        const ketenagakerjaanData = ketenagakerjaanRes.data;
        const tptLatest = ketenagakerjaanData.tpt_latest_data;
        const tptPrevious = ketenagakerjaanData.tpt_previous_data;
        const tpakLatest = ketenagakerjaanData.tpak_latest_data;
        const tpakPrevious = ketenagakerjaanData.tpak_previous_data;
        
        // TPT Total
        if (tptLatest && tptLatest.total !== null && tptLatest.total !== undefined) {
          const prevValue = tptPrevious && tptPrevious.total !== null && tptPrevious.total !== undefined ? tptPrevious.total : null;
          const card = createCard({
            value: tptLatest.total,
            year: tptLatest.year,
            previous: prevValue !== null ? { value: prevValue, year: tptPrevious.year } : null,
            previousYear: tptPrevious && tptPrevious.year ? tptPrevious.year : (tptLatest.year - 1)
          }, 'TPT Total', 'percent', '{{ route("ketenagakerjaan") }}');
          if (card) cards.push(card);
        }
        // TPAK Total
        if (tpakLatest && tpakLatest.total !== null && tpakLatest.total !== undefined) {
          const prevValue = tpakPrevious && tpakPrevious.total !== null && tpakPrevious.total !== undefined ? tpakPrevious.total : null;
          const card = createCard({
            value: tpakLatest.total,
            year: tpakLatest.year,
            previous: prevValue !== null ? { value: prevValue, year: tpakPrevious.year } : null,
            previousYear: tpakPrevious && tpakPrevious.year ? tpakPrevious.year : (tpakLatest.year - 1)
          }, 'TPAK Total', 'percent', '{{ route("ketenagakerjaan") }}');
          if (card) cards.push(card);
        }
      }

      // ========== GINI RATIO ==========
      // Endpoint -summary returns: { success: true, data: { surabaya_latest, jatim_latest, surabaya_previous, jatim_previous } }
      if (giniRatioRes.success && giniRatioRes.data) {
        const giniRatioData = giniRatioRes.data;
        const surabayaLatest = giniRatioData.surabaya_latest;
        const surabayaPrevious = giniRatioData.surabaya_previous;
        const jatimLatest = giniRatioData.jatim_latest;
        const jatimPrevious = giniRatioData.jatim_previous;
        
        // Gini Ratio Surabaya
        if (surabayaLatest && surabayaLatest.gini_ratio_value !== null && surabayaLatest.gini_ratio_value !== undefined) {
          const prevValue = surabayaPrevious && surabayaPrevious.gini_ratio_value !== null && surabayaPrevious.gini_ratio_value !== undefined ? surabayaPrevious.gini_ratio_value : null;
          const card = createCard({
            value: surabayaLatest.gini_ratio_value,
            year: surabayaLatest.year,
            previous: prevValue !== null ? { value: prevValue, year: surabayaPrevious.year } : null,
            previousYear: surabayaPrevious && surabayaPrevious.year ? surabayaPrevious.year : (surabayaLatest.year - 1)
          }, 'Gini Ratio Surabaya', 'number', '{{ route("gini-ratio") }}');
          if (card) cards.push(card);
        }
        // Gini Ratio Jawa Timur
        if (jatimLatest && jatimLatest.gini_ratio_value !== null && jatimLatest.gini_ratio_value !== undefined) {
          const prevValue = jatimPrevious && jatimPrevious.gini_ratio_value !== null && jatimPrevious.gini_ratio_value !== undefined ? jatimPrevious.gini_ratio_value : null;
          const card = createCard({
            value: jatimLatest.gini_ratio_value,
            year: jatimLatest.year,
            previous: prevValue !== null ? { value: prevValue, year: jatimPrevious.year } : null,
            previousYear: jatimPrevious && jatimPrevious.year ? jatimPrevious.year : (jatimLatest.year - 1)
          }, 'Gini Ratio Jawa Timur', 'number', '{{ route("gini-ratio") }}');
          if (card) cards.push(card);
        }
      }

      // ========== HOTEL OCCUPANCY ==========
      // Endpoint -summary returns: { success: true, data: { latest_month_data, previous_month_data, changes } }
      if (hotelOccupancyRes.success && hotelOccupancyRes.data) {
        const hotelOccupancyData = hotelOccupancyRes.data;
        const latest = hotelOccupancyData.latest_month_data;
        const previous = hotelOccupancyData.previous_month_data;
        
        if (latest) {
          // Helper to create card with previous data
          const createHotelCard = (field, title, valueType, suffix = '') => {
            if (latest[field] !== null && latest[field] !== undefined) {
              const prevValue = previous && previous[field] !== null && previous[field] !== undefined ? previous[field] : null;
              const year = latest.year || latest.date || latest.tanggal;
              
              const prevLabel = previous && previous.month ? 'bulan ' + previous.month.toLowerCase().substring(0, 3) : null;
              let yearLabel = year;
              if (latest.month) {
                const m = latest.month;
                yearLabel = m.substring(0, 1).toUpperCase() + m.substring(1, 3).toLowerCase() + ' ' + year;
              }
              
              return createCard({
                value: latest[field],
                year: yearLabel,
                previous: prevValue !== null ? { value: prevValue, year: previous ? previous.year : null } : null,
                previousYear: prevLabel || (previous ? previous.year : (year ? (typeof year === 'number' ? year - 1 : null) : null))
              }, title, valueType, '{{ route("hotel-occupancy") }}', 'value', suffix);
            }
            return null;
          };
          
          // TPK Total
          const card1 = createHotelCard('tpk', 'TPK Total', 'percent');
          if (card1) cards.push(card1);
          
          // MKTJ
          const card2 = createHotelCard('mktj', 'MKTJ', 'raw_locale', ' ribu');
          if (card2) cards.push(card2);
          
          // RLMT Gabungan
          const card3 = createHotelCard('rlmtgab', 'RLMT Gabungan', 'number', ' malam');
          if (card3) cards.push(card3);
          
          // GPR
          const card4 = createHotelCard('gpr', 'GPR', 'percent');
          if (card4) cards.push(card4);
        }
      }

      // ========== PDRB PENGELUARAN ==========
      // Endpoint -summary returns: { success: true, data: { latestBySheet: { sheet_name: { value, year } } } }
      // Note: PDRB might not have previous data in summary, so we'll skip comparison for now
      if (pdrbPengeluaranRes.success && pdrbPengeluaranRes.data) {
        const pdrbPengeluaranData = pdrbPengeluaranRes.data;
        const latestBySheet = pdrbPengeluaranData.latestBySheet || {};
        
        // Get all sheets from latestBySheet
        Object.keys(latestBySheet).forEach(sheetName => {
          const sheetData = latestBySheet[sheetName];
          if (sheetData && sheetData.value !== null && sheetData.value !== undefined) {
            // Try to get previous year data if available
            const previousYear = sheetData.year ? sheetData.year - 1 : null;
            const previousData = sheetData.previous_value !== null && sheetData.previous_value !== undefined ? 
              { value: sheetData.previous_value, year: previousYear } : null;
            
            const card = createCard({
              value: sheetData.value,
              year: sheetData.year,
              previous: previousData,
              previousYear: previousYear
            }, `PDRB Pengeluaran - ${sheetName}`, 'currency', '{{ route("pdrb-pengeluaran") }}');
            if (card) cards.push(card);
          }
        });
      }

      // ========== PDRB LAPANGAN USAHA ==========
      // Endpoint -summary returns: { success: true, data: { latestBySheet: { sheet_name: { value, year } } } }
      // Note: PDRB might not have previous data in summary, so we'll skip comparison for now
      if (pdrbLapanganUsahaRes.success && pdrbLapanganUsahaRes.data) {
        const pdrbLapanganUsahaData = pdrbLapanganUsahaRes.data;
        const latestBySheet = pdrbLapanganUsahaData.latestBySheet || {};
        
        // Get all sheets from latestBySheet
        Object.keys(latestBySheet).forEach(sheetName => {
          const sheetData = latestBySheet[sheetName];
          if (sheetData && sheetData.value !== null && sheetData.value !== undefined) {
            // Try to get previous year data if available
            const previousYear = sheetData.year ? sheetData.year - 1 : null;
            const previousData = sheetData.previous_value !== null && sheetData.previous_value !== undefined ? 
              { value: sheetData.previous_value, year: previousYear } : null;
            
            const card = createCard({
              value: sheetData.value,
              year: sheetData.year,
              previous: previousData,
              previousYear: previousYear
            }, `PDRB Lapangan Usaha - ${sheetName}`, 'currency', '{{ route("pdrb-lapangan-usaha") }}');
            if (card) cards.push(card);
          }
        });
      }

      // IPM
      const ipmData = ipmRes.success ? ipmRes.data : [];
      const ipmCard = createCard(getLatest(ipmData, 'ipm_value'), 'Indeks Pembangunan Manusia', 'number', '{{ route("indeks-pembangunan-manusia") }}', 'ipm_value');
      if (ipmCard) cards.push(ipmCard);

      // UHH SP
      const uhhSpData = uhhSpRes.success ? uhhSpRes.data : [];
      const uhhSpCard = createCard(getLatest(uhhSpData), 'Usia Harapan Hidup saat Lahir', 'number', '{{ route("ipm-uhh-sp") }}');
      if (uhhSpCard) cards.push(uhhSpCard);

      // HLS
      const hlsData = hlsRes.success ? hlsRes.data : [];
      const hlsCard = createCard(getLatest(hlsData), 'Harapan Lama Sekolah', 'number', '{{ route("ipm-hls") }}');
      if (hlsCard) cards.push(hlsCard);

      // RLS
      const rlsData = rlsRes.success ? rlsRes.data : [];
      const rlsCard = createCard(getLatest(rlsData), 'Rata-rata Lama Sekolah', 'number', '{{ route("ipm-rls") }}');
      if (rlsCard) cards.push(rlsCard);

      // Pengeluaran per Kapita
      const pengeluaranData = pengeluaranRes.success ? pengeluaranRes.data : [];
      const pengeluaranCard = createCard(getLatest(pengeluaranData), 'Pengeluaran per Kapita', 'currency', '{{ route("ipm-pengeluaran-per-kapita") }}');
      if (pengeluaranCard) cards.push(pengeluaranCard);

      // Indeks Kesehatan
      const indeksKesehatanData = indeksKesehatanRes.success ? indeksKesehatanRes.data : [];
      const indeksKesehatanCard = createCard(getLatest(indeksKesehatanData), 'Indeks Kesehatan', 'number', '{{ route("ipm-indeks-kesehatan") }}');
      if (indeksKesehatanCard) cards.push(indeksKesehatanCard);

      // Indeks Pendidikan
      const indeksPendidikanData = indeksPendidikanRes.success ? indeksPendidikanRes.data : [];
      const indeksPendidikanCard = createCard(getLatest(indeksPendidikanData), 'Indeks Pendidikan', 'number', '{{ route("ipm-indeks-pendidikan") }}');
      if (indeksPendidikanCard) cards.push(indeksPendidikanCard);

      // Indeks Hidup Layak
      const indeksHidupLayakData = indeksHidupLayakRes.success ? indeksHidupLayakRes.data : [];
      const indeksHidupLayakCard = createCard(getLatest(indeksHidupLayakData), 'Indeks Hidup Layak', 'number', '{{ route("ipm-indeks-hidup-layak") }}');
      if (indeksHidupLayakCard) cards.push(indeksHidupLayakCard);

      // Debug: Log cards data
      console.log('Summary cards loaded:', cards.length, cards);
      console.log('API Responses:', {
        inflasi: inflasiRes.success ? 'Loaded' : 'Failed',
        kemiskinan: kemiskinanRes.success ? 'Loaded' : 'Failed',
        kependudukan: kependudukanRes.success ? 'Loaded' : 'Failed',
        ketenagakerjaan: ketenagakerjaanRes.success ? 'Loaded' : 'Failed',
        giniRatio: giniRatioRes.success ? 'Loaded' : 'Failed',
        hotelOccupancy: hotelOccupancyRes.success ? 'Loaded' : 'Failed',
        ipm: ipmRes.success ? 'Loaded' : 'Failed'
      });

      // Render cards
      if (cards.length > 0) {
        renderSummaryCards(cards);
        startCarouselAnimation();
      } else {
        carousel.innerHTML = '<div class="text-center text-muted p-4">Data summary card sedang dimuat...</div>';
        console.warn('No cards to display. Check API responses above.');
      }
    } catch (error) {
      console.error('Error loading summary cards:', error);
      carousel.innerHTML = '<div class="text-center text-muted p-4">Gagal memuat data summary card</div>';
    }
  }

  function renderSummaryCards(cards) {
    const carousel = document.getElementById('summaryCardsCarousel');
    if (!carousel) return;

    // Color mapping based on card titles
    const getCardColor = (title) => {
      const titleLower = title.toLowerCase();
      
      // Kemiskinan
      if (titleLower.includes('jumlah penduduk miskin')) return 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)';
      if (titleLower.includes('persentase kemiskinan')) return 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
      if (titleLower.includes('indeks kedalaman') || titleLower.includes('p1')) return 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
      if (titleLower.includes('indeks keparahan') || titleLower.includes('p2')) return 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
      if (titleLower.includes('garis kemiskinan')) return 'linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)';
      
      // Kependudukan
      if (titleLower.includes('total penduduk') && !titleLower.includes('laki') && !titleLower.includes('perempuan')) return 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)';
      if (titleLower.includes('total laki') || titleLower.includes('laki-laki')) return 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
      if (titleLower.includes('total perempuan')) return 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
      if (titleLower.includes('rasio jenis kelamin') || titleLower.includes('rasio penduduk')) return 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
      
      // Inflasi
      if (titleLower.includes('inflasi bulan ke bulan') || titleLower.includes('inflasi m-to-m')) return 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)';
      if (titleLower.includes('inflasi tahun ke tahun') || titleLower.includes('inflasi y-on-y')) return 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
      if (titleLower.includes('inflasi kumulatif')) return 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
      
      // Ketenagakerjaan
      if (titleLower.includes('tpt')) return 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)';
      if (titleLower.includes('tpak')) return 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
      
      // Gini Ratio
      if (titleLower.includes('gini ratio surabaya')) return 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)';
      if (titleLower.includes('gini ratio jawa timur') || titleLower.includes('gini ratio jatim')) return 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
      
      // Hotel Occupancy
      if (titleLower.includes('tpk')) return 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)';
      if (titleLower.includes('mktj')) return 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
      if (titleLower.includes('rlmt')) return 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
      if (titleLower.includes('gpr')) return 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
      
      // IPM
      if (titleLower.includes('indeks pembangunan manusia') || titleLower.includes('ipm')) return 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)';
      if (titleLower.includes('usia harapan hidup') || titleLower.includes('uhh')) return 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
      if (titleLower.includes('harapan lama sekolah') || titleLower.includes('hls')) return 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
      if (titleLower.includes('rata-rata lama sekolah') || titleLower.includes('rls')) return 'linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)';
      if (titleLower.includes('pengeluaran per kapita')) return 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
      if (titleLower.includes('indeks kesehatan')) return 'linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)';
      if (titleLower.includes('indeks pendidikan')) return 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
      if (titleLower.includes('indeks hidup layak')) return 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
      
      // PDRB - default colors
      if (titleLower.includes('pdrb')) {
        // Rotate colors for PDRB cards
        const pdrbColors = [
          'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
          'linear-gradient(135deg, #10b981 0%, #059669 100%)',
          'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
          'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
          'linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)'
        ];
        // Use hash of title to get consistent color
        let hash = 0;
        for (let i = 0; i < title.length; i++) {
          hash = title.charCodeAt(i) + ((hash << 5) - hash);
        }
        return pdrbColors[Math.abs(hash) % pdrbColors.length];
      }
      
      // Default color
      return 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)';
    };

    const originalContent = document.createElement('div');
    originalContent.className = 'indicator-carousel-content';
    originalContent.style.display = 'flex';
    originalContent.style.gap = '15px';
    originalContent.style.flexShrink = '0';
    originalContent.style.minWidth = 'fit-content';

    cards.forEach(card => {
      const cardElement = document.createElement('div');
      cardElement.className = 'summary-card-carousel';
      const bgColor = getCardColor(card.title);
      cardElement.style.background = bgColor;
      cardElement.style.color = 'white';
      cardElement.style.cursor = 'pointer';
      cardElement.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
      cardElement.onclick = () => {
        if (card.link) window.location.href = card.link;
      };

      // Format comparison value based on card value type
      let comparisonHTML = '';
      if (card.comparison && card.previousYear) {
        let diffFormatted = '';
        const diff = card.comparison.diff;
        
        // Determine format based on card title or value
        if (card.title.includes('Persentase') || card.title.includes('Inflasi') || card.title.includes('TPT') || card.title.includes('TPAK') || card.title.includes('TPK') || card.title.includes('GPR')) {
          // Percentage format
          diffFormatted = `${diff >= 0 ? '+' : ''}${Math.abs(diff).toFixed(2)}%`;
        } else if (card.value.includes('Rp') || card.title.includes('Garis Kemiskinan') || card.title.includes('Pengeluaran') || card.title.includes('PDRB')) {
          // Currency format
          diffFormatted = `${diff >= 0 ? '+' : ''}Rp ${Math.abs(diff).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        } else if (card.title.includes('Jumlah Penduduk Miskin') || card.title.includes('Total Penduduk') || card.title.includes('Total Laki-laki') || card.title.includes('Total Perempuan')) {
          // Population format - convert to ribu/juta
          const absDiff = Math.abs(diff);
          if (absDiff >= 1000000) {
            diffFormatted = `${diff >= 0 ? '+' : ''}${(absDiff / 1000000).toFixed(2).replace(/\.?0+$/, '')} juta`;
          } else if (absDiff >= 1000) {
            diffFormatted = `${diff >= 0 ? '+' : ''}${(absDiff / 1000).toFixed(2).replace(/\.?0+$/, '')} ribu`;
          } else {
            diffFormatted = `${diff >= 0 ? '+' : ''}${absDiff.toFixed(2)}`;
          }
        } else if (card.title.includes('MKTJ')) {
          diffFormatted = `${diff >= 0 ? '+' : ''}${Math.abs(diff).toLocaleString('id-ID')} ribu`;
        } else {
          // Number format
          diffFormatted = `${diff >= 0 ? '+' : ''}${Math.abs(diff).toFixed(2)}`;
        }
        
        comparisonHTML = `<div class="comparison">
          <span style="color: rgba(255, 255, 255, 0.9); font-size: 14px;">${card.comparison.arrow}</span>
          <span style="color: rgba(255, 255, 255, 0.9); font-size: 14px; font-weight: 600;">${diffFormatted}</span>
          <span style="color: rgba(255, 255, 255, 0.8); font-size: 12px;">dari ${card.previousYear}</span>
        </div>`;
      }

      cardElement.innerHTML = `
        <h6 style="color: rgba(255, 255, 255, 0.9); font-size: 12px; font-weight: 500; margin: 0 0 8px 0;">${card.title}</h6>
        <h3 style="font-size: 22px; font-weight: 700; color: white; margin: 0 0 6px 0;">${card.value}</h3>
        ${comparisonHTML}
        <small style="color: rgba(255, 255, 255, 0.8); font-size: 11px;">Tahun ${card.year || 'Data tidak tersedia'}</small>
      `;

      originalContent.appendChild(cardElement);
    });

    // Create duplicate for seamless loop
    const duplicateContent = originalContent.cloneNode(true);
    duplicateContent.setAttribute('aria-hidden', 'true');

    carousel.innerHTML = '';
    carousel.appendChild(originalContent);
    carousel.appendChild(duplicateContent);
  }

  function startCarouselAnimation() {
    const carousel = document.getElementById('summaryCardsCarousel');
    if (!carousel) return;

    const contentSets = carousel.querySelectorAll('.indicator-carousel-content');
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

    const carouselWrapper = carousel.closest('.indicator-carousel-wrapper');
    if (carouselWrapper) {
      carouselWrapper.addEventListener('mouseenter', () => { isPaused = true; });
      carouselWrapper.addEventListener('mouseleave', () => { isPaused = false; });
    }

    animate();

    let resizeTimeout;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(() => {
        const contentSetWidth = getContentSetWidth();
        if (currentPosition >= contentSetWidth) {
          currentPosition = currentPosition % contentSetWidth;
        }
      }, 250);
    });
  }

  // Make inline onclick handlers available globally
  window.scrollIndicators = scrollIndicators;
  window.showPublicationModal = showPublicationModal;
  window.showInfographicDetail = showInfographicDetail;
  window.handlePublicationDownload = handlePublicationDownload;
  window.handleInfographicDownload = handleInfographicDownload;