// resources/js/dashboard/news.js

document.addEventListener('turbo:load', function() {
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
    
    // Store news data for modal
    const newsData = [];
    const dataElements = document.querySelectorAll('.news-data');
    dataElements.forEach((el) => {
        newsData.push({
            id: el.dataset.id || '', // actually news_id from blade
            news_id: el.dataset.id || '',
            title: el.dataset.title || '',
            content: el.dataset.content || '',
            category: el.dataset.category || '',
            date: el.dataset.date || 'N/A',
            image: el.dataset.image || '',
        });
    });

    function showNewsModal(index) {
        const item = newsData[index];
        if (!item) {
            console.error('News not found at index:', index);
            return;
        }

        // Generate slug from title
        const slug = generateSlug(item.title);
        
        // Update URL with news ID and slug
        const url = new URL(window.location.href);
        url.searchParams.set('news', item.news_id || item.id);
        if (slug) {
            url.searchParams.set('slug', slug);
        }
        window.history.pushState({}, '', url);

        const modalTitle = document.getElementById('newsModalTitle');
        const modalCategory = document.getElementById('newsModalCategory');
        const modalDate = document.getElementById('newsModalDate');
        const modalContent = document.getElementById('newsModalContent');
        const modalImageContainer = document.getElementById('newsModalImageContainer');

        if (modalTitle) modalTitle.textContent = item.title;
        if (modalCategory) {
            modalCategory.innerHTML = `<i class="bi bi-tag-fill me-1"></i> ${item.category}`;
        }
        if (modalDate) {
            modalDate.innerHTML = `<i class="bi bi-calendar-event me-1"></i> ${item.date}`;
        }
        if (modalContent) {
            // Check if full content is already cached
            if (item.fullContent) {
                modalContent.innerHTML = item.fullContent;
            } else {
                modalContent.innerHTML = '<div class="text-center py-4"><span class="spinner-border spinner-border-sm text-primary me-2" role="status"></span><em>Mengambil data lengkap berita...</em></div>';
                
                // Fetch detailed news
                const newsIdToFetch = item.news_id || item.id;
                const baseUrl = window.ASTABAYA ? window.ASTABAYA.baseUrl : '';
                
                fetch(baseUrl + '/api/news/' + newsIdToFetch)
                    .then(res => res.json())
                    .then(res => {
                        if (res && res.data && res.data.content) {
                            item.fullContent = res.data.content; // Cache it
                            modalContent.innerHTML = res.data.content;
                        } else {
                            modalContent.innerHTML = item.content; // fallback
                        }
                    })
                    .catch(err => {
                        console.error('Failed to fetch detailed news', err);
                        modalContent.innerHTML = item.content; // fallback
                    });
            }
        }

        // Handle image
        if (modalImageContainer) {
            modalImageContainer.innerHTML = ''; // Clear previous image
            
            if (item.image) {
                const imgElement = document.createElement('img');
                imgElement.src = item.image;
                imgElement.alt = item.title;
                imgElement.id = 'modalNewsImage';
                imgElement.className = 'img-fluid rounded mb-3 shadow-sm';
                imgElement.style.width = '100%';
                imgElement.style.maxHeight = '400px';
                imgElement.style.objectFit = 'cover';
                imgElement.style.display = 'block';
                
                imgElement.onerror = function() {
                    modalImageContainer.innerHTML = `<div class="bg-light rounded d-flex align-items-center justify-content-center p-5 mb-3">
                        <div class="text-center">
                            <i class="bi bi-image" style="font-size: 4rem; color: #ccc;"></i>
                            <p class="text-muted mt-2 mb-0">Image !available</p>
                        </div>
                    </div>`;
                };
                
                imgElement.onload = function() {
                    this.style.display = 'block';
                };
                
                modalImageContainer.appendChild(imgElement);
            } else {
                modalImageContainer.innerHTML = `<div class="bg-light rounded d-flex align-items-center justify-content-center p-5 mb-3">
                    <div class="text-center">
                        <i class="bi bi-image" style="font-size: 4rem; color: #ccc;"></i>
                        <p class="text-muted mt-2 mb-0">Image !available</p>
                    </div>
                </div>`;
            }
        }

        // Update share button in modal with slug
        const modalShareBtn = document.getElementById('modalNewsShareBtn');
        if (modalShareBtn) {
            const newsTitle = item.title || 'Berita';
            const newsUrl = window.location.origin + '/news?news=' + (item.news_id || item.id) + (slug ? '&slug=' + slug : '');
            
            modalShareBtn.dataset.newsTitle = newsTitle;
            modalShareBtn.dataset.newsUrl = newsUrl;
        }

        // Update bookmark button in modal
        if (window.ASTABAYA && window.ASTABAYA.isAuthenticated) {
            const modalBookmarkBtn = document.getElementById('modalNewsBookmarkBtn');
            if (modalBookmarkBtn) {
                const newsId = item.news_id || item.id;
                const newsElement = document.querySelector(`.bookmark-btn[data-content-type="news"][data-object-id="` + newsId + `"]`);
                if (newsElement) {
                    const bookmarkId = newsElement.dataset.bookmarkId || '';
                    const isBookmarked = newsElement.classList.contains('bookmarked');
                    
                    modalBookmarkBtn.dataset.objectId = String(newsId);
                    modalBookmarkBtn.dataset.bookmarkId = bookmarkId;
                    
                    const icon = modalBookmarkBtn.querySelector('i');
                    const text = modalBookmarkBtn.querySelector('span');
                    
                    if (isBookmarked) {
                        modalBookmarkBtn.classList.add('bookmarked');
                        if (icon) {
                            icon.classList.remove('bi-bookmark');
                            icon.classList.add('bi-bookmark-fill');
                        }
                        if (text) text.textContent = 'Bookmark';
                    } else {
                        modalBookmarkBtn.classList.remove('bookmarked');
                        if (icon) {
                            icon.classList.remove('bi-bookmark-fill');
                            icon.classList.add('bi-bookmark');
                        }
                        if (text) text.textContent = 'Bookmark';
                    }
                } else {
                    modalBookmarkBtn.dataset.objectId = String(newsId);
                    modalBookmarkBtn.dataset.bookmarkId = '';
                    modalBookmarkBtn.classList.remove('bookmarked');
                    const icon = modalBookmarkBtn.querySelector('i');
                    const text = modalBookmarkBtn.querySelector('span');
                    if(icon) {
                        icon.classList.remove('bi-bookmark-fill');
                        icon.classList.add('bi-bookmark');
                    }
                    if (text) text.textContent = 'Bookmark';
                }
            }
        }

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('newsCardModal'));
        modal.show();
        
        // Update font size after modal is shown
        setTimeout(() => {
            updateResponsiveFontSizes();
        }, 100);
    }
    
    // Make showNewsModal globally available
    window.showNewsModal = showNewsModal;

    // Handle bookmark click
    window.handleNewsBookmark = function(button) {
        if (!window.ASTABAYA || !window.ASTABAYA.isAuthenticated) {
            const newsTitle = button.closest('.news-item')?.querySelector('.card-title')?.textContent || 
                             button.closest('.card-body')?.querySelector('h6')?.textContent || 
                             document.getElementById('newsModalTitle')?.textContent || 
                             'berita ini';
            
            const itemNameSpan = document.getElementById('bookmark-item-name');
            if (itemNameSpan) {
                itemNameSpan.textContent = newsTitle;
            }
            
            const bookmarkLoginModal = document.getElementById('bookmarkLoginRequiredModal');
            if (bookmarkLoginModal) {
                const modal = new bootstrap.Modal(bookmarkLoginModal);
                modal.show();
            } else {
                window.location.href = window.ASTABAYA ? window.ASTABAYA.loginRoute : '/login';
            }
            return;
        }
        
        if (typeof toggleBookmark === 'function') {
            toggleBookmark(button);
        } else {
            console.error('toggleBookmark function not found');
        }
    };

    function updateResponsiveFontSizes() {
        const isMobile = window.innerWidth <= 768;
        const isSmallMobile = window.innerWidth <= 576;
        const isTablet = window.innerWidth <= 992;
        
        const newsCardTexts = document.querySelectorAll('.news-item .card-text');
        newsCardTexts.forEach(el => {
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
        
        const modalContent = document.getElementById('newsModalContent');
        if (modalContent) {
            if (isSmallMobile) {
                modalContent.style.fontSize = '1.1rem';
                modalContent.style.lineHeight = '2';
            } else if (isMobile) {
                modalContent.style.fontSize = '1.05rem';
                modalContent.style.lineHeight = '1.9';
            } else if (isTablet) {
                modalContent.style.fontSize = '1rem';
                modalContent.style.lineHeight = '1.8';
            }
        }
    }

    updateResponsiveFontSizes();
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(updateResponsiveFontSizes, 100);
    });
    
    if (window.ASTABAYA && window.ASTABAYA.isAuthenticated) {
        if (typeof toggleBookmark === 'undefined') {
            const baseUrl = window.ASTABAYA.baseUrl || '';
            fetch(baseUrl + '/bookmarks')
                .then(response => response.json())
                .then(data => {
                    const bookmarks = data || [];
                    bookmarks.forEach(bookmark => {
                        if (bookmark.content_type_model === 'news') {
                            const buttons = document.querySelectorAll(`.bookmark-btn[data-content-type="news"][data-object-id="` + bookmark.object_id + `"]`);
                            buttons.forEach(btn => {
                                btn.classList.add('bookmarked');
                                const icon = btn.querySelector('i');
                                if (icon) {
                                    icon.classList.remove('bi-bookmark');
                                    icon.classList.add('bi-bookmark-fill');
                                }
                                btn.dataset.bookmarkId = String(bookmark.id);
                            });
                        }
                    });
                })
                .catch(err => {
                    console.error('Error loading bookmarks:', err);
                });
        }
    }
    
    const newsModal = document.getElementById('newsCardModal');
    if (newsModal) {
        newsModal.addEventListener('hidden.bs.modal', function() {
            const url = new URL(window.location.href);
            url.searchParams.delete('news');
            url.searchParams.delete('slug');
            window.history.pushState({}, '', url);
        });
    }
    
    // Check URL parameters for deep linking
    const urlParams = new URLSearchParams(window.location.search);
    const newsId = urlParams.get('news');
    if (newsId) {
        // Find by news_id
        const newsIndex = newsData.findIndex(n => String(n.news_id) === String(newsId) || String(n.id) === String(newsId));
        if (newsIndex !== -1) {
            setTimeout(() => {
                showNewsModal(newsIndex);
            }, 500);
        } else {
            // Fallback: fetch from API
            const baseUrl = window.ASTABAYA ? window.ASTABAYA.baseUrl : '';
            fetch(baseUrl + '/api/news/' + newsId)
                .then(response => {
                    if (!response.ok) throw new Error('News not found');
                    return response.json();
                })
                .then(result => {
                    if (result.success && result.data) {
                        const item = result.data;
                        const formattedItem = {
                            id: item.news_id,
                            news_id: item.news_id,
                            title: item.title,
                            content: item.content,
                            category: item.category_name,
                            date: item.release_date,
                            image: item.picture_url
                        };
                        newsData.push(formattedItem);
                        showNewsModal(newsData.length - 1);
                    }
                })
                .catch(err => console.error('Failed to fetch deep link news', err));
        }
    }
});
