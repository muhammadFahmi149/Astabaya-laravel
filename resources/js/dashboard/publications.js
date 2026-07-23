
  // Generate slug from title
  function generateSlug(title) {
    if (!title) return '';
    return title
      .toLowerCase()
      .trim()
      .replace(/[^\w\s-]/g, '') // Remove special characters
      .replace(/[\s_-]+/g, '-') // Replace spaces and underscores with hyphens
      .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
  }

  // Function to update font sizes for responsive
  function updateResponsiveFontSizes() {
    const isMobile = window.innerWidth <= 768;
    const isSmallMobile = window.innerWidth <= 576;
    const isTablet = window.innerWidth <= 992;
    
    // Update publication card text
    const publicationCardTexts = document.querySelectorAll('.publication-item .card-text');
    publicationCardTexts.forEach(el => {
      if (isSmallMobile) {
        el.style.fontSize = '1.15rem';
        el.style.lineHeight = '1.8';
      } else if (isMobile) {
        el.style.fontSize = '1.1rem';
        el.style.lineHeight = '1.7';
      } else if (isTablet) {
        el.style.fontSize = '1rem';
        el.style.lineHeight = '1.6';
      }
    });
    
    // Update publication modal abstract
    const modalAbstract = document.getElementById('modalAbstract');
    if (modalAbstract) {
      if (isSmallMobile) {
        modalAbstract.style.fontSize = '1.1rem';
        modalAbstract.style.lineHeight = '1.9';
      } else if (isMobile) {
        modalAbstract.style.fontSize = '1.05rem';
        modalAbstract.style.lineHeight = '1.8';
      } else if (isTablet) {
        modalAbstract.style.fontSize = '1rem';
        modalAbstract.style.lineHeight = '1.7';
      }
    }
  }

  // Lazy loading for images
  document.addEventListener("DOMContentLoaded", function () {
    // Initialize year filter dropdown
    initYearFilter();
    
    // Update font sizes on load
    updateResponsiveFontSizes();
    
    // Update font sizes on resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(updateResponsiveFontSizes, 100);
    });
    
    // Initialize bookmark states
    if(window.ASTABAYA.isAuthenticated) initializeBookmarkStates();
    
    const lazyImages = document.querySelectorAll("img.lazy-load");

    if ("IntersectionObserver" in window) {
      const imageObserver = new IntersectionObserver(
        (entries, observer) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              const img = entry.target;
const src = img.dataset.src;
if (!src || src === 'undefined') return;
const tempImg = new Image();
              tempImg.onload = function () {
                img.src = src;
                img.classList.remove("lazy-load");
              };
              tempImg.onerror = function () {
                img.src =
                  'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 150 200"%3E%3Crect fill="%23f0f0f0" width="150" height="200"/%3E%3Ctext fill="%23999" x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-size="14" font-family="Arial"%3ENo Image%3C/text%3E%3C/svg%3E';
              };
              tempImg.src = src;

              imageObserver.unobserve(img);
            }
          });
        },
        { rootMargin: "50px" }
      );

      lazyImages.forEach((img) => imageObserver.observe(img));
    } else {
      lazyImages.forEach((img) => {
        img.src = img.dataset.src;
      });
    }
  });

  // Store publication data for modal
  const publications = [];

  // Load data from HTML attributes
  const dataElements = document.querySelectorAll(".publication-data");
  dataElements.forEach((el) => {
    // Get image URL - try to decode if needed
    let imageUrl = el.dataset.image || '';
    // Ensure image URL is properly decoded
    if (imageUrl) {
      try {
        imageUrl = decodeURIComponent(imageUrl);
      } catch (e) {
        // If decoding fails, use original
        imageUrl = el.dataset.image;
      }
    }
    
    // Get the primary key (id) from data attribute
    const publicationId = el.dataset.id || '';
    
    publications.push({
      title: el.dataset.title || '',
      image: imageUrl,
      date: el.dataset.date || 'N/A',
      size: el.dataset.size || 'N/A',
      pubId: el.dataset.pubId || '',
      id: publicationId, // Primary key for bookmark
      abstract: el.dataset.abstract || '',
      download: el.dataset.download || '',
    });
    
    // Also create a map by pubId for faster lookup
    if (!window.publicationsMap) {
      window.publicationsMap = {};
    }
    if (el.dataset.pubId) {
      window.publicationsMap[el.dataset.pubId] = publications[publications.length - 1];
    }
  });

  // Rest of your existing script...

  // Search function - only called when Enter is pressed or search button is clicked
  function performSearch() {
    const searchInput = document.getElementById("searchInput");
    if (searchInput) {
      const searchTerm = searchInput.value.trim();
      const url = new URL(window.location.href);
      
      // Update or remove search parameter
      if (searchTerm) {
        url.searchParams.set('search', searchTerm);
      } else {
        url.searchParams.delete('search');
      }
      
      // Reset to page 1 when searching
      url.searchParams.set('page', '1');
      
      // Reload page with search query
      window.location.href = url.toString();
    }
  }

  // Handle Enter key press in search input
  function handleSearchKeyPress(event) {
    if (event.key === 'Enter') {
      event.preventDefault();
      performSearch();
    }
  }

  // Clear search when input is cleared (optional - only if you want auto-clear on empty)
  document.getElementById("searchInput")?.addEventListener("input", function(e) {
    // Only clear search if input is empty, but don't search automatically
    if (e.target.value.trim() === '' && new URL(window.location.href).searchParams.get('search')) {
      const url = new URL(window.location.href);
      url.searchParams.delete('search');
      url.searchParams.set('page', '1');
      window.location.href = url.toString();
    }
  });

  // Year filter - handle dropdown item clicks
  function initYearFilter() {
    const yearFilterButton = document.getElementById("yearFilterButton");
    const yearFilterMenu = document.getElementById("yearFilterMenu");
    const yearFilterItems = document.querySelectorAll("#yearFilterMenu .dropdown-item");
    if (yearFilterItems.length === 0) return;
    
    // Sync dropdown menu width with button width
    function syncDropdownWidth() {
      if (yearFilterButton && yearFilterMenu) {
        // Get button width
        const buttonWidth = yearFilterButton.offsetWidth;
        
        // Set dropdown menu width to match button width
        yearFilterMenu.style.width = buttonWidth + 'px';
        yearFilterMenu.style.minWidth = buttonWidth + 'px';
        yearFilterMenu.style.maxWidth = buttonWidth + 'px';
      }
    }
    
    // Sync width on dropdown show
    if (yearFilterButton && yearFilterMenu) {
      // Use Bootstrap dropdown events
      yearFilterButton.addEventListener('show.bs.dropdown', function() {
        // Small delay to ensure button width is calculated
        setTimeout(syncDropdownWidth, 10);
      });
      
      // Sync on window resize
      let resizeTimeout;
      window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(syncDropdownWidth, 100);
      });
      
      // Initial sync after a small delay to ensure DOM is ready
      setTimeout(syncDropdownWidth, 100);
    }
    
    yearFilterItems.forEach(function(item) {
      item.addEventListener("click", function(e) {
        e.preventDefault();
        
        // Remove active class from all items
        yearFilterItems.forEach(function(i) {
          i.classList.remove("active");
        });
        
        // Add active class to clicked item
        item.classList.add("active");
        
        const selectedYear = item.dataset.year || '';
        const yearFilterText = document.getElementById("yearFilterText");
        
        // Update button text
        if (selectedYear) {
          yearFilterText.textContent = selectedYear;
        } else {
          yearFilterText.textContent = "Semua Tahun";
        }
        
        const url = new URL(window.location.href);
        
        // Update or remove year parameter
        if (selectedYear) {
          url.searchParams.set('year', selectedYear);
        } else {
          url.searchParams.delete('year');
        }
        
        // Reset to page 1 when filtering
        url.searchParams.set('page', '1');
        
        // Preserve search query if exists
        const searchInput = document.getElementById("searchInput");
        if (searchInput && searchInput.value.trim()) {
          url.searchParams.set('search', searchInput.value.trim());
        }
        
        // Reload page with new filter
        window.location.href = url.toString();
      });
    });
  }

  // Modal functionality
  function showModal(pubId, index) {
    // Try to find publication by pubId first (more reliable)
    let pub = null;
    if (pubId && window.publicationsMap && window.publicationsMap[pubId]) {
      pub = window.publicationsMap[pubId];
    } else if (index !== undefined && publications[index]) {
      // Fallback to index if pubId not found
      pub = publications[index];
    } else {
      console.error('Publication not found', { pubId, index });
      alert('Error: Publication data not found');
      return;
    }

    document.getElementById("modalTitle").textContent = pub.title;
    
    // Generate slug from title
    const slug = generateSlug(pub.title);
    
    // Update URL with publication ID and slug
    const url = new URL(window.location.href);
    url.searchParams.set('publication', pub.pubId || pub.id || '');
    if (slug) {
      url.searchParams.set('slug', slug);
    }
    window.history.pushState({}, '', url);
    
    // Set image with error handling
    const modalImage = document.getElementById("modalImage");
    const placeholderImg = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 150 200"%3E%3Crect fill="%23f0f0f0" width="150" height="200"/%3E%3Ctext fill="%23999" x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-size="14" font-family="Arial"%3ENo Image%3C/text%3E%3C/svg%3E';
    
    // Try to get image from already loaded thumbnail first (find by pubId)
    let thumbnailSrc = null;
    if (pubId) {
      const thumbnailImages = document.querySelectorAll('.publication-thumbnail, .publication-thumbnail-mobile');
      thumbnailImages.forEach(function(thumbImg) {
        if (thumbImg.dataset.pubId === pubId) {
          // Check if thumbnail has been loaded (not the placeholder)
          if (thumbImg.src && !thumbImg.src.includes('data:image/svg+xml') && thumbImg.src !== placeholderImg) {
            thumbnailSrc = thumbImg.src;
          }
          // Also check data-src if src is still placeholder
          else if (thumbImg.dataset.src) {
            thumbnailSrc = thumbImg.dataset.src;
          }
        }
      });
    }
    
    // Set image source - prefer thumbnail if available, otherwise use pub.image
    const imageToLoad = thumbnailSrc || pub.image;
    
    if (imageToLoad && imageToLoad.trim() !== '') {
      modalImage.src = imageToLoad;
      modalImage.alt = pub.title || 'Publication image';
      modalImage.style.display = 'block';
      modalImage.onerror = function() {
        // If image fails to load, try the other source or show placeholder
        if (this.src === thumbnailSrc && pub.image && pub.image.trim() !== '' && pub.image !== imageToLoad) {
          // Try the original pub.image if thumbnail failed
          this.src = pub.image;
        } else {
          // Both failed, show placeholder
          this.src = placeholderImg;
        }
      };
      modalImage.onload = function() {
        this.style.display = 'block';
      };
    } else {
      // If no image URL, show placeholder
      modalImage.src = placeholderImg;
      modalImage.alt = 'No image available';
      modalImage.style.display = 'block';
    }
    
    document.getElementById("modalDate").textContent = pub.date;
    document.getElementById("modalSize").textContent = pub.size;
    document.getElementById("modalPubId").textContent = pub.pubId;
    
    // Update bookmark button in modal
    const modalBookmarkBtn = document.getElementById("modalBookmarkBtn");
    if (modalBookmarkBtn) {
      // Find the publication in the list to get bookmark_id
      const pubElement = document.querySelector(`[data-pub-id="${pub.pubId}"]`);
      if (pubElement) {
        const listBookmarkBtn = pubElement.closest('.publication-item')?.querySelector('.bookmark-btn');
        if (listBookmarkBtn) {
          const bookmarkId = listBookmarkBtn.dataset.bookmarkId || '';
          const isBookmarked = listBookmarkBtn.classList.contains('bookmarked');
          
          // Use the publication's primary key (id) for bookmark, not pub_id
          const publicationId = pub.id || '';
          modalBookmarkBtn.dataset.objectId = String(publicationId);
          modalBookmarkBtn.dataset.bookmarkId = bookmarkId;
          
          const icon = modalBookmarkBtn.querySelector('i');
          const text = modalBookmarkBtn.querySelector('span');
          
          if (isBookmarked) {
            modalBookmarkBtn.classList.add('bookmarked');
            icon.classList.remove('bi-bookmark');
            icon.classList.add('bi-bookmark-fill');
            if (text) text.textContent = 'Tersimpan';
          } else {
            modalBookmarkBtn.classList.remove('bookmarked');
            icon.classList.remove('bi-bookmark-fill');
            icon.classList.add('bi-bookmark');
            if (text) text.textContent = 'Bookmark';
          }
        } else {
          // If no list bookmark button found, just set the object ID
          const publicationId = pub.id || '';
          modalBookmarkBtn.dataset.objectId = String(publicationId);
          modalBookmarkBtn.dataset.bookmarkId = '';
          
          const icon = modalBookmarkBtn.querySelector('i');
          const text = modalBookmarkBtn.querySelector('span');
          
          modalBookmarkBtn.classList.remove('bookmarked');
          icon.classList.remove('bi-bookmark-fill');
          icon.classList.add('bi-bookmark');
          if (text) text.textContent = 'Bookmark';
        }
      } else {
        // If no publication element found, just set the object ID
        const publicationId = pub.id || '';
        modalBookmarkBtn.dataset.objectId = String(publicationId);
        modalBookmarkBtn.dataset.bookmarkId = '';
        
        const icon = modalBookmarkBtn.querySelector('i');
        const text = modalBookmarkBtn.querySelector('span');
        
        modalBookmarkBtn.classList.remove('bookmarked');
        icon.classList.remove('bi-bookmark-fill');
        icon.classList.add('bi-bookmark');
        if (text) text.textContent = 'Bookmark';
      }
    }
    
    // Clean abstract from special characters like \u000D\u000A (carriage return and line feed)
    let cleanAbstract = pub.abstract || '';
    
    // First, handle literal escape sequences like "\u000D\u000A" (backslash-u-000D-000A)
    // These are Unicode escape sequences stored as literal strings
    cleanAbstract = cleanAbstract.replace(/\\u000D\\u000A/gi, ' ');
    cleanAbstract = cleanAbstract.replace(/\\u000D/gi, ' ');
    cleanAbstract = cleanAbstract.replace(/\\u000A/gi, ' ');
    cleanAbstract = cleanAbstract.replace(/\\u0009/gi, ' '); // tab
    cleanAbstract = cleanAbstract.replace(/\\u000B/gi, ' '); // vertical tab
    cleanAbstract = cleanAbstract.replace(/\\u000C/gi, ' '); // form feed
    
    // Handle other common escape sequences
    cleanAbstract = cleanAbstract.replace(/\\r\\n/gi, ' ');
    cleanAbstract = cleanAbstract.replace(/\\n/gi, ' ');
    cleanAbstract = cleanAbstract.replace(/\\r/gi, ' ');
    cleanAbstract = cleanAbstract.replace(/\\t/gi, ' ');
    
    // Try to decode Unicode escape sequences if they exist as literal strings
    try {
      // Replace literal \uXXXX patterns with actual characters, then clean them
      cleanAbstract = cleanAbstract.replace(/\\u([0-9a-fA-F]{4})/g, function(match, hex) {
        return String.fromCharCode(parseInt(hex, 16));
      });
    } catch (e) {
      // If decoding fails, continue with original string
    }
    
    // Remove actual carriage return (\r) and line feed (\n) characters
    cleanAbstract = cleanAbstract.replace(/\r\n/g, ' ').replace(/\n/g, ' ').replace(/\r/g, ' ');
    
    // Remove Unicode control characters (including \u000D and \u000A)
    cleanAbstract = cleanAbstract.replace(/[\u0000-\u001F\u007F-\u009F]/g, ' ');
    
    // Replace multiple spaces/tabs with single space
    cleanAbstract = cleanAbstract.replace(/[\s\t]+/g, ' ').trim();
    
    // Reset subject_csa container initially
    const modalSubjectCsaContainer = document.getElementById("modalSubjectCsaContainer");
    if (modalSubjectCsaContainer) {
        document.getElementById("modalSubjectCsa").innerHTML = '<span class="spinner-border spinner-border-sm text-primary" role="status" style="width: 1rem; height: 1rem;"></span>';
    }

    // Check if full details are already cached in the publication object
    if (pub.fullAbstract !== undefined) {
        document.getElementById("modalAbstract").innerHTML = pub.fullAbstract;
        if (modalSubjectCsaContainer) {
            document.getElementById("modalSubjectCsa").textContent = pub.fullSubjectCsa || "-";
        }
    } else {
        // Add loading spinner for dynamic abstract fetch
        const cleanAbstractFallback = cleanAbstract;
        document.getElementById("modalAbstract").innerHTML = '<div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div> <em>Mengambil rincian data...</em>';
        
        // Fetch detailed data dynamically
        const pubIdToFetch = pub.pubId || pub.id;
        if (pubIdToFetch) {
            fetch(window.ASTABAYA.baseUrl + `/api/publications/${encodeURIComponent(pubIdToFetch)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                credentials: 'include'
            })
            .then(res => res.json())
            .then(res => {
                let finalAbstract = cleanAbstractFallback;
                let finalSubjectCsa = "-";

                if (res && res.data) {
                    if (res.data.abstract) {
                        // Process the fetched abstract text
                        let detailedAbstract = res.data.abstract;
                        
                        // Decode HTML entities safely
                        const txt = document.createElement("textarea");
                        txt.innerHTML = detailedAbstract;
                        detailedAbstract = txt.value;
                        
                        // Add paragraphs if missing
                        if (!detailedAbstract.includes('<p>') && !detailedAbstract.includes('<br>')) {
                            // Split by common sentence endings and wrap in paragraphs
                            // This is a simple heuristic to make long text readable
                            const sentences = detailedAbstract.match(/[^.!?]+[.!?]+/g) || [detailedAbstract];
                            let formattedHtml = '';
                            let currentPara = '';
                            
                            sentences.forEach(sentence => {
                                currentPara += sentence + ' ';
                                if (currentPara.length > 200) { // arbitrary threshold for paragraph length
                                    formattedHtml += `<p class="mb-3">${currentPara.trim()}</p>`;
                                    currentPara = '';
                                }
                            });
                            
                            if (currentPara.trim().length > 0) {
                                formattedHtml += `<p class="mb-3">${currentPara.trim()}</p>`;
                            }
                            
                            detailedAbstract = formattedHtml || detailedAbstract;
                        }
                        finalAbstract = detailedAbstract;
                    }
                    
                    if (res.data.subject_csa || res.data.subject) {
                        finalSubjectCsa = res.data.subject_csa || res.data.subject;
                    }
                }
                
                // Cache the processed data
                pub.fullAbstract = finalAbstract;
                pub.fullSubjectCsa = finalSubjectCsa;
                
                // Update UI
                document.getElementById("modalAbstract").innerHTML = finalAbstract;
                if (modalSubjectCsaContainer) document.getElementById("modalSubjectCsa").textContent = finalSubjectCsa;
            })
            .catch(err => {
                console.error('Failed to fetch detailed publication data', err);
                pub.fullAbstract = cleanAbstractFallback;
                pub.fullSubjectCsa = "-";
                document.getElementById("modalAbstract").innerHTML = cleanAbstractFallback;
                if (modalSubjectCsaContainer) document.getElementById("modalSubjectCsa").textContent = "-";
            });
        } else {
            pub.fullAbstract = cleanAbstractFallback;
            pub.fullSubjectCsa = "-";
            document.getElementById("modalAbstract").innerHTML = cleanAbstractFallback;
            if (modalSubjectCsaContainer) document.getElementById("modalSubjectCsa").textContent = "-";
        }
    }
    
    const modalDownloadBtn = document.getElementById("modalDownload");
    modalDownloadBtn.setAttribute('data-pub-id', pub.pubId || pub.id || '');
    modalDownloadBtn.setAttribute('data-pub-title', pub.title || 'Publikasi');
    modalDownloadBtn.href = window.ASTABAYA.baseUrl + "/api/publications/" + encodeURIComponent(pub.pubId || pub.id || "") + "/download";

    const modal = new bootstrap.Modal(document.getElementById("publicationModal"));
    modal.show();
    
    // Update font size after modal is shown
    setTimeout(() => {
      updateResponsiveFontSizes();
    }, 100);

    // Update share button data with slug
    const shareBtn = document.querySelector('.share-publication-modal-btn');
    if (shareBtn) {
      shareBtn.dataset.pubTitle = pub.title || 'Publikasi';
      // Use the publication ID (primary key) for the share URL
      const publicationId = pub.id || pub.pubId || '';
      const shareUrl = window.location.origin + '/publications?publication=' + publicationId + (slug ? '&slug=' + slug : '');
      shareBtn.dataset.pubUrl = shareUrl;
      console.log('Share button updated:', { title: shareBtn.dataset.pubTitle, url: shareBtn.dataset.pubUrl }); // Debug log
    }
  }

  // Keep old function for backward compatibility (but now clipboard is called directly from event handler)
  function sharePublication(title, url) {
    // This function is kept for backward compatibility
    // But clipboard is now called directly from event handler to maintain user interaction context
    console.log('sharePublication called (legacy):', { title, url });
  }

  // Refresh data - Reload dari database
  window.refreshData = function(e) {
const event = e || window.event;
const btn = event.target.closest("button");
    const originalContent = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memuat ulang...';

    // Reload halaman untuk mengambil data terbaru dari database
    location.reload();
  }

  // Fix all modal close buttons - Universal handler
  document.addEventListener('DOMContentLoaded', function() {
    // Clean up URL when modal is closed
    const publicationModal = document.getElementById('publicationModal');
    if (publicationModal) {
      publicationModal.addEventListener('hidden.bs.modal', function() {
        // Remove publication and slug from URL when modal is closed
        const url = new URL(window.location.href);
        url.searchParams.delete('publication');
        url.searchParams.delete('slug');
        window.history.pushState({}, '', url);
      });
    }
    
    // Function to fetch publication by ID from API
    async function fetchPublicationById(pubId) {
      try {
        const response = await fetch(window.ASTABAYA.baseUrl + `/api/publications/${encodeURIComponent(pubId)}`, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          },
          credentials: 'include'
        });
        
        if (response.ok) {
          const data = await response.json();
          // Handle both direct data and wrapped data format
          const pub = data.data || data;
          if (pub && (pub.title || pub.pub_id)) {
            // Format date properly
            let formattedDate = 'N/A';
            if (pub.date) {
              try {
                const dateObj = new Date(pub.date);
                formattedDate = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
              } catch (e) {
                formattedDate = pub.date;
              }
            }
            
            return {
              title: pub.title || '',
              image: pub.image || '',
              date: formattedDate,
              size: pub.size || 'N/A',
              pubId: pub.pub_id || pubId,
              id: pub.id || pubId,
              abstract: pub.abstract || '',
              download: pub.download_url || pub.dl || ''
            };
          }
        } else {
          console.warn('Publication API returned error:', response.status, response.statusText);
        }
      } catch (error) {
        console.error('Error fetching publication from API:', error);
      }
      return null;
    }

    // Function to show modal by publication ID (with fallback to fetch from API)
    async function showModalByPubId(pubId) {
      // First try to find in loaded data - try both pubId and id
      let pub = null;
      let index = -1;
      
      // Try to find by pubId in map
      if (window.publicationsMap && window.publicationsMap[pubId]) {
        pub = window.publicationsMap[pubId];
        index = publications.findIndex(p => (p.pubId === pub.pubId && p.id === pub.id));
      }
      
      // If not found, try to find in publications array by pubId or id
      if (!pub) {
        pub = publications.find(p => {
          return String(p.pubId) === String(pubId) || 
                 String(p.id) === String(pubId) ||
                 (p.pubId && String(p.pubId) === String(pubId)) ||
                 (p.id && String(p.id) === String(pubId));
        });
        if (pub) {
          index = publications.findIndex(p => (p.pubId === pub.pubId && p.id === pub.id));
        }
      }
      
      // If still not found, try to fetch from API
      if (!pub) {
        console.log('Publication not found in loaded data, fetching from API...', pubId);
        pub = await fetchPublicationById(pubId);
        if (pub) {
          // Add to publications array and map for future use
          publications.push(pub);
          if (pub.pubId && !window.publicationsMap) {
            window.publicationsMap = {};
          }
          if (pub.pubId && window.publicationsMap) {
            window.publicationsMap[pub.pubId] = pub;
          }
          if (pub.id && window.publicationsMap) {
            window.publicationsMap[pub.id] = pub;
          }
          index = publications.length - 1;
        }
      }
      
      if (pub) {
        // Use showModal with the found publication
        showModal(pub.pubId || pub.id, index >= 0 ? index : publications.length - 1);
      } else {
        console.error('Publication not found:', pubId);
        alert('Publikasi tidak ditemukan. Mungkin publikasi sudah dihapus atau tidak tersedia.');
      }
    }

    // Check if there's a publication parameter in URL, open modal automatically
    const urlParams = new URLSearchParams(window.location.search);
    const publicationId = urlParams.get('publication');
    if (publicationId) {
      // Wait a bit for page to be fully loaded, then try to open modal
      setTimeout(() => {
        showModalByPubId(publicationId);
      }, 500);
    }
  });

  // Handle download clicks with login check
  document.addEventListener('DOMContentLoaded', function() {
    const downloadButtons = document.querySelectorAll('.download-publication-btn');
    downloadButtons.forEach(btn => {
      btn.addEventListener('click', async function(e) {
        if (!window.ASTABAYA.isAuthenticated) { e.preventDefault(); const pubTitle = this.getAttribute('data-pub-title') || 'publikasi'; if (typeof showLoginRequiredModal === 'function') { showLoginRequiredModal(pubTitle); } else { alert('Ingin mengunduh ' + pubTitle + ' ini? Silakan login terlebih dahulu.'); const loginModal = new bootstrap.Modal(document.getElementById('loginModal')); loginModal.show(); } return; }
      });
    });
  });

  // Handle bookmark click - check login first
  function handlePublicationBookmark(button) {
    const isAuthenticated = window.ASTABAYA.isAuthenticated;
    
    if (!isAuthenticated) {
      // User not logged in, show login required modal
      const publicationTitle = button.closest('.publication-item')?.querySelector('.card-title')?.textContent || 
                             button.closest('.card-body')?.querySelector('h5, h6')?.textContent || 
                             document.getElementById('modalTitle')?.textContent || 
                             'publikasi ini';
      
      // Update modal content
      const itemNameSpan = document.getElementById('bookmark-item-name');
      if (itemNameSpan) {
        itemNameSpan.textContent = publicationTitle;
      }
      
      // Show modal
      const bookmarkLoginModal = document.getElementById('bookmarkLoginRequiredModal');
      if (bookmarkLoginModal) {
        const modal = new bootstrap.Modal(bookmarkLoginModal);
        modal.show();
      } else {
        // Fallback: redirect to login page
        window.location.href = window.ASTABAYA.routes.login;
      }
      return;
    }
    
    // User is authenticated, proceed with bookmark
    if (typeof toggleBookmark === 'function') {
      toggleBookmark(button);
    } else {
      console.error('toggleBookmark function not found');
    }
  }

  // --- Bookmark Functionality ---
  async function toggleBookmark(button) {
    // Prevent multiple clicks
    if (button.disabled) return;
    button.disabled = true;

    const contentType = button.dataset.contentType;
    // Ensure objectId is a string, handle array case
    let objectId = button.dataset.objectId;
    if (Array.isArray(objectId)) {
      objectId = String(objectId[0]);
    } else if (objectId && typeof objectId === 'object') {
      objectId = String(objectId);
    } else {
      objectId = String(objectId || '');
    }
    let bookmarkId = button.dataset.bookmarkId;
    const isBookmarked = button.classList.contains("bookmarked");

    const icon = button.querySelector("i");
    const text = button.querySelector("span");

    function getCookie(name) {
      let cookieValue = null;
      if (document.cookie && document.cookie !== "") {
        const cookies = document.cookie.split(";");
        for (let i = 0; i < cookies.length; i++) {
          const cookie = cookies[i].trim();
          if (cookie.substring(0, name.length + 1) === name + "=") {
            cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
            break;
          }
        }
      }
      return cookieValue;
    }
    // Validate required data
    if (!contentType || !objectId) {
      console.error("Missing required data:", { contentType, objectId });
      alert("Data tidak lengkap. Silakan refresh halaman.");
      button.disabled = false;
      return;
    }

    // Get CSRF token from meta tag (Laravel standard)
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    const csrftoken = metaTag ? metaTag.getAttribute('content') : null;
    
    console.log("[Publications Bookmark] CSRF Token:", csrftoken ? "Found" : "NOT FOUND");

    if (!csrftoken) {
      console.error("CSRF token not found! Silakan refresh halaman (Ctrl+F5).");
      alert("Token CSRF tidak ditemukan. Silakan refresh halaman (Ctrl+F5).");
      button.disabled = false;
      return;
    }

    try {
      if (isBookmarked) {
        // --- Hapus Bookmark ---
        if (!bookmarkId) {
          console.error("Bookmark ID tidak ditemukan untuk penghapusan");
          button.disabled = false;
          return;
        }

        console.log("Deleting bookmark:", { bookmarkId, contentType, objectId });
        const response = await fetch(window.ASTABAYA.baseUrl + `/bookmarks/${bookmarkId}`, {
          method: "DELETE",
          headers: { 
            "X-CSRF-TOKEN": csrftoken,
            "X-Requested-With": "XMLHttpRequest"
          },
          credentials: "include",
        });

        console.log("Delete response status:", response.status);
        
        if (response.ok || response.status === 204) {
          button.classList.remove("bookmarked");
          icon.classList.remove("bi-bookmark-fill");
          icon.classList.add("bi-bookmark");
          if (text) text.textContent = "Bookmark";
          button.dataset.bookmarkId = "";
          
          // Sync with other bookmark buttons for the same item
          syncBookmarkButtons(contentType, objectId, false, "");
          
          // Broadcast change to other tabs
          if (typeof broadcastBookmarkChange === 'function') {
            broadcastBookmarkChange(contentType, objectId, false, "");
          }
          
          // Update bookmark list in header
          if (typeof updateBookmarkList === 'function') {
            updateBookmarkList();
          }
        } else {
          const errorData = await response.json().catch(() => ({}));
          console.error("Delete bookmark error:", errorData);
          alert("Gagal menghapus bookmark: " + (errorData.error || errorData.detail || "Terjadi kesalahan"));
        }
      } else {
        // --- Tambah Bookmark ---
        const requestBody = { 
          content_type_name: contentType, 
          object_id: objectId 
        };
        
        console.log("Adding bookmark:", requestBody);
        
        const response = await fetch(window.ASTABAYA.baseUrl + `/bookmarks/add`, {
          method: "POST",
          headers: { 
            "Content-Type": "application/json", 
            "X-CSRF-TOKEN": csrftoken,
            "X-Requested-With": "XMLHttpRequest"
          },
          credentials: "include",
          body: JSON.stringify(requestBody),
        });

        console.log("Add response status:", response.status);
        const responseData = await response.json().catch(() => ({}));
        console.log("Add response data:", responseData);

        if (response.ok) {
          button.classList.add("bookmarked");
          icon.classList.remove("bi-bookmark");
          icon.classList.add("bi-bookmark-fill");
          if (text) text.textContent = "Tersimpan";
          button.dataset.bookmarkId = String(responseData.id);
          
          // Sync with other bookmark buttons for the same item
          syncBookmarkButtons(contentType, objectId, true, String(responseData.id));
          
          // Broadcast change to other tabs
          if (typeof broadcastBookmarkChange === 'function') {
            broadcastBookmarkChange(contentType, objectId, true, String(responseData.id));
          }
          
          // Update bookmark list in header
          if (typeof updateBookmarkList === 'function') {
            updateBookmarkList();
          }
        } else {
          if (response.status === 409) {
            // Bookmark already exists, fetch and update UI
            try {
              const existingBookmarks = await fetch(window.ASTABAYA.baseUrl + `/bookmarks`, {
                headers: { 
                  "X-CSRF-TOKEN": csrftoken,
                  "X-Requested-With": "XMLHttpRequest"
                },
                credentials: "include",
              }).then(r => r.json()).catch(() => []);
              
              const bookmark = existingBookmarks.find(b => 
                b.content_type_model === contentType && 
                String(b.object_id) === String(objectId)
              );
              
              if (bookmark) {
                button.classList.add("bookmarked");
                icon.classList.remove("bi-bookmark");
                icon.classList.add("bi-bookmark-fill");
                if (text) text.textContent = "Tersimpan";
                button.dataset.bookmarkId = String(bookmark.id);
                syncBookmarkButtons(contentType, objectId, true, String(bookmark.id));
                
                // Broadcast change to other tabs
                if (typeof broadcastBookmarkChange === 'function') {
                  broadcastBookmarkChange(contentType, objectId, true, String(bookmark.id));
                }
                
                // Update bookmark list in header
                if (typeof updateBookmarkList === 'function') {
                  updateBookmarkList();
                }
              } else {
                alert("Bookmark sudah ada tetapi tidak dapat ditemukan.");
              }
            } catch (fetchError) {
              console.error("Error fetching existing bookmarks:", fetchError);
              alert("Bookmark sudah ada di daftar Anda.");
            }
          } else {
            const errorMsg = responseData.error || responseData.detail || responseData.non_field_errors || "Terjadi kesalahan";
            console.error("Add bookmark error:", responseData);
            alert("Gagal menambahkan bookmark: " + (Array.isArray(errorMsg) ? errorMsg.join(", ") : errorMsg));
          }
        }
      }
    } catch (error) {
      console.error("Error toggling bookmark:", error);
      alert("Terjadi kesalahan: " + error.message);
    } finally {
      button.disabled = false;
    }
  }

  // Sync bookmark state across all buttons for the same item
  function syncBookmarkButtons(contentType, objectId, isBookmarked, bookmarkId) {
    // Find all bookmark buttons for this item
    const allButtons = document.querySelectorAll(`.bookmark-btn[data-content-type="${contentType}"][data-object-id="${objectId}"]`);
    
    allButtons.forEach(btn => {
      const icon = btn.querySelector("i");
      const text = btn.querySelector("span");
      
      if (isBookmarked) {
        btn.classList.add("bookmarked");
        icon.classList.remove("bi-bookmark");
        icon.classList.add("bi-bookmark-fill");
        btn.dataset.bookmarkId = bookmarkId;
        if (text) text.textContent = "Tersimpan";
      } else {
        btn.classList.remove("bookmarked");
        icon.classList.remove("bi-bookmark-fill");
        icon.classList.add("bi-bookmark");
        btn.dataset.bookmarkId = "";
        if (text) text.textContent = "Bookmark";
      }
    });
  }

  // Cross-tab bookmark synchronization using localStorage
  function broadcastBookmarkChange(contentType, objectId, isBookmarked, bookmarkId) {
    const bookmarkData = {
      contentType,
      objectId: String(objectId),
      isBookmarked,
      bookmarkId: String(bookmarkId || ''),
      timestamp: Date.now()
    };
    
    console.log('Broadcasting bookmark change:', bookmarkData);
    
    // Save to localStorage (triggers storage event in other tabs)
    localStorage.setItem('bookmark_change', JSON.stringify(bookmarkData));
    
    // Dispatch custom event for immediate same-tab sync
    window.dispatchEvent(new CustomEvent('bookmarkChanged', {
      detail: bookmarkData
    }));
  }

  // Listen for bookmark changes from other tabs
  if (window.ASTABAYA.isAuthenticated) { window.addEventListener('storage', function(e) {
    if (e.key === 'bookmark_change' && e.newValue) {
      try {
        const bookmarkData = JSON.parse(e.newValue);
        const { contentType, objectId, isBookmarked, bookmarkId } = bookmarkData;
        
        console.log('Bookmark change from storage:', bookmarkData);
        
        // Sync bookmark buttons in current tab
        if (typeof syncBookmarkButtons === 'function') {
          syncBookmarkButtons(contentType, objectId, isBookmarked, bookmarkId);
        }
        
        // Update bookmark list in header
        if (typeof updateBookmarkList === 'function') {
          updateBookmarkList();
        }
      } catch (error) {
        console.error('Error parsing bookmark change:', error);
      }
    }
  });

  // Listen for custom events (same-tab immediate sync)
  window.addEventListener('bookmarkChanged', function(e) {
    const { contentType, objectId, isBookmarked, bookmarkId } = e.detail;
    console.log('Bookmark change from custom event:', e.detail);
    
    if (typeof syncBookmarkButtons === 'function') {
      syncBookmarkButtons(contentType, objectId, isBookmarked, bookmarkId);
    }
    
    if (typeof updateBookmarkList === 'function') {
      updateBookmarkList();
    }
  }); }

  // Initialize bookmark states on page load
  async function initializeBookmarkStates() { if (!window.ASTABAYA.isAuthenticated) return;
    try {
      // Get CSRF token
      function getCookie(name) {
        let cookieValue = null;
        if (document.cookie && document.cookie !== "") {
          const cookies = document.cookie.split(";");
          for (let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i].trim();
            if (cookie.substring(0, name.length + 1) === name + "=") {
              cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
              break;
            }
          }
        }
        return cookieValue;
      }
      
      let csrftoken = getCookie("XSRF-TOKEN");
      if (!csrftoken) {
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) {
          csrftoken = metaTag.getAttribute("content");
        }
      }
      
      if (!csrftoken) {
        console.error("CSRF token not found for bookmark initialization");
        return;
      }
      
      // Fetch user's bookmarks
      const response = await fetch(window.ASTABAYA.baseUrl + '/bookmarks', {
        headers: { 
          "X-CSRF-TOKEN": csrftoken,
          "X-Requested-With": "XMLHttpRequest"
        },
        credentials: "include",
      });
      
      if (!response.ok) {
        console.error("Failed to fetch bookmarks:", response.status);
        return;
      }
      
      const bookmarks = await response.json().catch(() => []);
      
      // Update bookmark buttons based on fetched bookmarks
      const bookmarkButtons = document.querySelectorAll('.bookmark-btn[data-content-type="publication"]');
      
      bookmarks.forEach(bookmark => {
        if (bookmark.content_type_model === 'publication') {
          const objectId = String(bookmark.object_id);
          
          // Find all bookmark buttons for this publication
          bookmarkButtons.forEach(btn => {
            if (String(btn.dataset.objectId) === objectId) {
              // Update button state
              btn.classList.add('bookmarked');
              btn.dataset.bookmarkId = String(bookmark.id);
              
              const icon = btn.querySelector('i');
              const text = btn.querySelector('span');
              
              if (icon) {
                icon.classList.remove('bi-bookmark');
                icon.classList.add('bi-bookmark-fill');
              }
              
              if (text) {
                text.textContent = 'Tersimpan';
              }
            }
          });
        }
      });
      
      console.log('Bookmark states initialized');
    } catch (error) {
      console.error('Error initializing bookmark states:', error);
    }
  }

  // Make functions available globally
  window.broadcastBookmarkChange = broadcastBookmarkChange;
  window.syncBookmarkButtons = syncBookmarkButtons;

  // Initialize share buttons - using event delegation for dynamic content
  document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing share buttons for publications'); // Debug
    // Use event delegation to handle all share buttons (including dynamically added ones)
    document.addEventListener('click', async function(e) {
      const shareBtn = e.target.closest('.share-publication-modal-btn') || e.target.closest('.share-publication-btn') || e.target.closest('.share-btn');
      if (shareBtn) {
        e.preventDefault();
        e.stopPropagation();
        const title = shareBtn.dataset.pubTitle || shareBtn.dataset.shareTitle || 'Publikasi';
        let url = shareBtn.dataset.pubUrl || shareBtn.dataset.shareUrl || window.location.href;
        
        // Ensure URL is complete (add origin if relative)
        if (url && !url.startsWith('http://') && !url.startsWith('https://')) {
          url = window.location.origin + (url.startsWith('/') ? url : '/' + url);
        }
        
        console.log('Share button clicked:', { title, url, button: shareBtn, dataset: shareBtn.dataset }); // Debug log
        
        // Directly copy to clipboard (no Web Share API)
        await copyToClipboardDirect(url, title, e, shareBtn);
      }
    });
  });
  
  // Copy to clipboard directly from event handler (maintains user interaction context)
  async function copyToClipboardDirect(text, title, event, button) {
    text = String(text || '');
    
    if (!text) {
      console.error('No text to copy');
      showToast('Tidak ada link untuk disalin');
      return;
    }
    
    console.log('Copying to clipboard directly:', text, title); // Debug log
    
    if (navigator.clipboard && navigator.clipboard.writeText) {
      try {
        await navigator.clipboard.writeText(text);
        console.log('Successfully copied to clipboard using Clipboard API'); // Debug log
        showToast('Link publikasi "' + title + '" telah disalin ke clipboard');
        
        // Visual feedback on button
        if (button) {
          const originalHTML = button.innerHTML;
          const originalClasses = button.className;
          button.innerHTML = '<i class="bi bi-check"></i> <span>Tersalin!</span>';
          button.classList.add('btn-success');
          button.classList.remove('btn-light', 'btn-outline-secondary');
          
          setTimeout(() => {
            button.innerHTML = originalHTML;
            button.className = originalClasses;
          }, 2000);
        }
      } catch (err) {
        console.error('Clipboard API failed:', err);
        // Fallback for older browsers or when API fails
        fallbackCopyToClipboard(text, title);
      }
    } else {
      console.log('Clipboard API not available, using fallback'); // Debug log
      fallbackCopyToClipboard(text, title);
    }
  }

  function copyToClipboard(text, title) {
    console.log('Copying to clipboard:', text, title); // Debug log
    
    // Ensure text is a string
    text = String(text || '');
    
    if (!text) {
      console.error('No text to copy');
      showToast('Tidak ada link untuk disalin');
      return;
    }
    
    if (navigator.clipboard && navigator.clipboard.writeText) {
      // Use Clipboard API
      navigator.clipboard.writeText(text).then(() => {
        console.log('Successfully copied to clipboard using Clipboard API'); // Debug log
        // Show toast notification
        showToast('Link publikasi "' + title + '" telah disalin ke clipboard');
      }).catch(err => {
        console.error('Clipboard API failed:', err);
        // Fallback for older browsers or when API fails
        fallbackCopyToClipboard(text, title);
      });
    } else {
      console.log('Clipboard API not available, using fallback'); // Debug log
      fallbackCopyToClipboard(text, title);
    }
  }

  function fallbackCopyToClipboard(text, title) {
    console.log('Using fallback copy method');
    
    // Ensure text is a string
    text = String(text || '');
    
    if (!text) {
      console.error('No text to copy in fallback');
      showToast('Tidak ada link untuk disalin');
      return;
    }
    
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '0';
    textArea.style.top = '0';
    textArea.style.width = '2em';
    textArea.style.height = '2em';
    textArea.style.padding = '0';
    textArea.style.border = 'none';
    textArea.style.outline = 'none';
    textArea.style.boxShadow = 'none';
    textArea.style.background = 'transparent';
    textArea.style.opacity = '0';
    textArea.setAttribute('readonly', '');
    // Do not set aria-hidden on focused element to prevent accessibility warnings
    
    document.body.appendChild(textArea);
    
    // For iOS
    if (navigator.userAgent.match(/ipad|iphone/i)) {
      const range = document.createRange();
      range.selectNodeContents(textArea);
      const selection = window.getSelection();
      selection.removeAllRanges();
      selection.addRange(range);
      textArea.setSelectionRange(0, 999999);
    } else {
      textArea.select();
      textArea.setSelectionRange(0, 99999); // For mobile devices
    }
    
    try {
      const successful = document.execCommand('copy');
      if (successful) {
        console.log('Fallback copy successful');
        showToast('Link publikasi "' + title + '" telah disalin ke clipboard');
      } else {
        throw new Error('execCommand copy returned false');
      }
    } catch (err) {
      console.error('Fallback copy failed:', err);
      // Last resort: show the text in a prompt
      prompt('Salin link berikut:', text);
      showToast('Silakan salin link secara manual');
    }
    
    // Clean up
    setTimeout(() => {
      if (textArea.parentNode) {
        textArea.parentNode.removeChild(textArea);
      }
    }, 100);
  }

  function showToast(message) {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.textContent = message;
    toast.style.cssText = 'position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); background: #333; color: white; padding: 12px 24px; border-radius: 8px; z-index: 10000; font-size: 0.875rem; box-shadow: 0 4px 12px rgba(0,0,0,0.3);';
    
    document.body.appendChild(toast);
    
    // Remove after 3 seconds
    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transition = 'opacity 0.3s';
      setTimeout(() => {
        if (toast.parentNode) {
          toast.parentNode.removeChild(toast);
        }
      }, 300);
    }, 3000);
  }


// Expose functions to global window object for inline event handlers
window.handleSearchKeyPress = handleSearchKeyPress;
window.performSearch = performSearch;
window.showModal = showModal;
window.handlePublicationBookmark = handlePublicationBookmark;
