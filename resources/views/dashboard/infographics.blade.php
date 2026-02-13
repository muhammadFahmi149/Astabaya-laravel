@extends('layouts.main')

@section('title', 'Infografis')

@push('styles')
<style>
    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        border-radius: 25rem !important;
        overflow: hidden;
    }
    
    .search-input-wrapper .form-control {
        padding-left: 45px;
        padding-right: 90px;
        border-radius: 25rem !important;
        border: 1px solid #dee2e6;
        height: 47px;
    }
    
    .search-input-wrapper .form-control:focus {
        border-color: #ced4da !important;
        box-shadow: none !important;
        outline: none !important;
    }
    
    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        pointer-events: none;
        color: #6c757d;
    }
    
    .search-button {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        border: none;
        background: #1F4068;
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.2s;
    }
    
    .search-button:hover {
        background: #1a3554;
    }
    
    .search-button:active {
        background: #152a47;
    }

    /* Infographic Card Styles */
    .infographic-card {
        transition: all 0.3s ease;
        border: 5px solid #e9ecef;
        cursor: pointer;
    }

    .infographic-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
        border-color: #1F4068;
    }

    .infographic-card .card-img-wrapper {
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .infographic-card:hover .card-img-wrapper {
        background: #f8f9fa !important;
    }

    .infographic-card .card-img-wrapper img {
        transition: transform 0.3s ease;
    }

    .infographic-card:hover .card-img-wrapper img {
        transform: scale(1.05);
    }

    /* Ensure consistent card height */
    .infographic-item {
        display: flex;
    }

    .infographic-item .card {
        width: 100%;
    }

    .infographic-item .card-img-wrapper {
        height: 280px;
        min-height: 280px;
    }

    /* Infographic Card Title - Smaller font size */
    .infographic-card .card-title {
        font-size: 0.8rem !important;
        line-height: 1.3 !important;
        font-weight: 500;
    }

    /* List view title */
    .infographic-card table td strong {
        font-size: 0.85rem !important;
    }

    @media (max-width: 576px) {
        .infographic-card .card-title {
            font-size: 0.75rem !important;
        }
    }

    /* View Switcher Button Group */
    .btn-group {
        border-radius: 25rem !important;
        overflow: hidden;
    }

    .btn-group button:first-child {
        border-top-left-radius: 25rem !important;
        border-bottom-left-radius: 25rem !important;
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    .btn-group button:last-child {
        border-top-right-radius: 25rem !important;
        border-bottom-right-radius: 25rem !important;
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
    }

    /* View Switcher Button Active State */
    .btn-group button.active {
        background-color: #1F4068 !important;
        border-color: #1F4068 !important;
        color: white !important;
    }

    .btn-group button.active i {
        color: white !important;
    }

    .btn-group button.active:hover {
        background-color: #1a3554 !important;
        border-color: #1a3554 !important;
        color: white !important;
    }

    .btn-group button:not(.active) {
        background-color: white !important;
        border-color: #ced4da !important;
        color: #1F4068 !important;
    }

    .btn-group button:not(.active) i {
        color: #1F4068 !important;
    }

    .btn-group button:not(.active):hover {
        background-color: #f8f9fa !important;
        border-color: #ced4da !important;
        color: #1F4068 !important;
    }

    /* Pagination Styles */
    .pagination {
        margin-bottom: 0.5rem;
    }

    .pagination .page-link {
        color: #0d6efd;
        border: 1px solid #dee2e6;
        padding: 0.5rem 0.75rem;
        transition: all 0.2s ease;
        border-radius: 0.375rem;
        margin: 0 0.125rem;
    }

    .pagination .page-link:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
        color: #0a58ca;
        transform: translateY(-1px);
    }

    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
    }

    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #fff;
        border-color: #dee2e6;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .pagination .page-link i {
        font-size: 0.875rem;
        vertical-align: middle;
    }

    .pagination .page-item:first-child .page-link {
        border-top-left-radius: 0.5rem;
        border-bottom-left-radius: 0.5rem;
    }

    .pagination .page-item:last-child .page-link {
        border-top-right-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }

    /* Responsive pagination */
    @media (max-width: 576px) {
        .pagination .page-link {
            padding: 0.375rem 0.5rem;
            font-size: 0.875rem;
        }

        .pagination .page-link i {
            font-size: 0.75rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    const API_BASE = '{{ url("/api") }}';
    const isAuthenticated = @auth true @else false @endauth;
    let infographics = [];
    let allInfographics = []; // Store all infographics for modal
    let currentView = 'grid';
    let searchQuery = '';
    let currentPage = 1;
    let paginationMeta = null;
    let paginationLinks = null;
    let totalAllInfographics = 0; // Total all infographics without filter

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

    // Get page from URL
    function getPageFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        return parseInt(urlParams.get('page')) || 1;
    }

    // Update URL without reload
    function updateURL(page, search) {
        const url = new URL(window.location.href);
        if (page > 1) {
            url.searchParams.set('page', page);
        } else {
            url.searchParams.delete('page');
        }
        if (search) {
            url.searchParams.set('search', search);
        } else {
            url.searchParams.delete('search');
        }
        window.history.pushState({}, '', url);
    }

    async function loadInfographics(page = null) {
        try {
            const container = document.getElementById('infographicsContainer');
            const paginationContainer = document.getElementById('paginationContainer');
            
            if (container) {
                container.innerHTML = '<div class="col-12"><div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div></div>';
            }
            if (paginationContainer) {
                paginationContainer.innerHTML = '';
            }

            if (page === null) {
                page = getPageFromURL();
            }
            currentPage = page;

            const params = new URLSearchParams();
            if (searchQuery) params.append('search', searchQuery);
            if (page > 1) params.append('page', page);
            // Add cache busting to ensure fresh data
            params.append('_t', Date.now());

            const url = `${API_BASE}/infographics${params.toString() ? '?' + params.toString() : ''}`;

            const response = await fetch(url, {
                cache: 'no-cache',
                headers: {
                    'Cache-Control': 'no-cache',
                    'Pragma': 'no-cache'
                }
            });
            if (!response.ok) {
                throw new Error('Failed to load infographics');
            }
            const data = await response.json();
            
            console.log('API Response:', data); // Debug log
            
            // Handle paginated response - Laravel ResourceCollection returns data in 'data' key
            if (data.data && Array.isArray(data.data)) {
                infographics = data.data;
            } else if (Array.isArray(data)) {
                infographics = data;
            } else {
                infographics = [];
            }
            
            // Store pagination info - Laravel pagination uses 'meta' and 'links'
            if (data.meta) {
                paginationMeta = data.meta;
            } else {
                // If no meta, try to get from response structure
                paginationMeta = null;
            }
            
            // Update count display
            updateInfographicCount();
            
            if (data.links) {
                paginationLinks = data.links;
            } else {
                paginationLinks = null;
            }
            
            console.log('Infographics loaded:', infographics.length, 'Pagination meta:', paginationMeta); // Debug log
            
            // Update URL
            updateURL(page, searchQuery);
            
            // If there's a search query, get total all infographics
            if (searchQuery && searchQuery.trim() !== '' && totalAllInfographics === 0) {
                getTotalAllInfographics();
            }
            
            renderInfographics();
            renderPagination();
        } catch (error) {
            console.error('Error loading infographics:', error);
            const container = document.getElementById('infographicsContainer');
            if (container) {
                container.innerHTML = '<div class="col-12"><div class="alert alert-danger text-center">Gagal memuat data infografis. Silakan coba lagi.</div></div>';
            }
        }
    }

    function renderInfographics() {
        const container = document.getElementById('infographicsContainer');
        if (!container) return;

        if (infographics.length === 0) {
            container.innerHTML = '<div class="col-12"><div class="alert alert-info text-center">Belum ada data infografis.</div></div>';
            return;
        }

        // Count will be updated by updateInfographicCount() function

        if (currentView === 'grid') {
            container.innerHTML = infographics.map((item) => {
                const bookmarkBtn = `
                    <button class="btn btn-sm btn-outline-secondary bookmark-btn" 
                            data-content-type="infographic" 
                            data-object-id="${item.id}" 
                            data-bookmark-id="" 
                            onclick="event.stopPropagation(); handleInfographicBookmark(this)">
                        <i class="bi bi-bookmark"></i>
                    </button>
                `;
                return `
                <div class="col-6 col-md-4 col-lg-3 mb-4 infographic-item">
                    <div class="card infographic-card h-100 shadow-sm" onclick="console.log('[DEBUG] Klik infografis card:', ${item.id}); showInfographicDetail(${item.id})">
                        <div class="card-img-wrapper" style="background: #ffffff; height: 280px; min-height: 280px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <img src="${item.image || ''}" alt="${item.title}" 
                                 class="card-img-top" 
                                 loading="lazy"
                                 style="width: 100%; max-height: 100%; object-fit: contain; cursor: pointer;">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title mb-auto" style="font-size: 0.8rem; line-height: 1.3; min-height: 48px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">${item.title}</h6>
                        </div>
                        <div class="card-footer bg-transparent border-top">
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary flex-fill" onclick="event.stopPropagation(); showInfographicDetail(${item.id})">
                                    <i class="bi bi-eye"></i> Lihat
                                </button>
                                <button class="btn btn-sm btn-outline-secondary share-infographic-btn" data-infographic-title="${item.title || 'Infografis'}" data-infographic-url="${window.location.origin}/infographics?infographic=${item.id}&slug=${generateSlug(item.title)}" onclick="event.stopPropagation();">
                                    <i class="bi bi-share"></i>
                                </button>
                                <div onclick="event.stopPropagation();">${bookmarkBtn}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            }).join('');
        } else {
            // List view
            container.innerHTML = `
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 80px">Preview</th>
                                    <th>Judul</th>
                                    <th style="width: 150px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${infographics.map(item => {
                                    const bookmarkBtn = `
                                        <button class="btn btn-sm btn-outline-secondary bookmark-btn" 
                                                data-content-type="infographic" 
                                                data-object-id="${item.id}" 
                                                data-bookmark-id="" 
                                                onclick="handleInfographicBookmark(this)">
                                            <i class="bi bi-bookmark"></i>
                                        </button>
                                    `;
                                    return `
                                    <tr>
                                        <td><img src="${item.image}" loading="lazy" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;"></td>
                                        <td><strong style="font-size: 0.85rem;">${item.title}</strong></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-info" onclick="showInfographicDetail(${item.id})">
                                                    <i class="bi bi-eye"></i> View
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary share-infographic-btn" data-infographic-title="${item.title || 'Infografis'}" data-infographic-url="${window.location.origin}/infographics?infographic=${item.id}&slug=${generateSlug(item.title)}" onclick="event.stopPropagation();">
                                                    <i class="bi bi-share"></i> Bagikan
                                                </button>
                                                ${bookmarkBtn}
                                            </div>
                                        </td>
                                    </tr>
                                `;
                                }).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        }
    }

    function changeView(view, e) {
        currentView = view;
        const buttons = document.querySelectorAll('.btn-group button');
        buttons.forEach(btn => {
            btn.classList.remove('active');
        });
        if (e && e.target) {
            e.target.closest('button').classList.add('active');
        } else {
            // Fallback if event is not provided
            buttons[view === 'grid' ? 0 : 1].classList.add('active');
        }
        renderInfographics();
    }

    async function showInfographicDetail(id) {
        console.log('[DEBUG] showInfographicDetail dipanggil dengan id:', id);
        console.log('[DEBUG] Modal instance saat ini:', bootstrap.Modal.getInstance(document.getElementById('infographicModal')));
        console.log('[DEBUG] Backdrop saat ini:', document.querySelectorAll('.modal-backdrop').length);
        console.log('[DEBUG] Body modal-open saat ini:', document.body.classList.contains('modal-open'));
        
        let item = infographics.find(i => i.id === id);
        
        // If item not found in current page, fetch it from API
        if (!item) {
            try {
                const response = await fetch(`${API_BASE}/infographics/${id}`);
                if (response.ok) {
                    const data = await response.json();
                    item = data.data || data;
                } else {
                    console.error('Infographic not found:', id);
                    alert('Infografis tidak ditemukan');
                    return;
                }
            } catch (error) {
                console.error('Error fetching infographic:', error);
                alert('Gagal memuat detail infografis');
                return;
            }
        }
        
        if (!item) {
            console.error('Infographic not found:', id);
            return;
        }

        // Generate slug from title
        const slug = generateSlug(item.title);
        
        // Update URL with infographic ID and slug
        const url = new URL(window.location.href);
        url.searchParams.set('infographic', id);
        if (slug) {
            url.searchParams.set('slug', slug);
        }
        window.history.pushState({}, '', url);

        // Set modal title
        document.getElementById('infographicModalTitle').textContent = item.title || 'Infografis';
        
        // Set image with error handling
        const modalImage = document.getElementById('infographicModalImage');
        const placeholderImg = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400"%3E%3Crect fill="%23f0f0f0" width="400" height="400"/%3E%3Ctext fill="%23999" x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-size="16" font-family="Arial"%3EImage !available%3C/text%3E%3C/svg%3E';
        
        let imageToLoad = item.image || '';
        if (imageToLoad) {
            try {
                imageToLoad = decodeURIComponent(imageToLoad);
            } catch (e) {
                imageToLoad = item.image;
            }
        }
        
        // Show loading state
        modalImage.src = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400"%3E%3Crect fill="%23f5f5f5" width="400" height="400"/%3E%3Ctext fill="%23999" x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-size="16" font-family="Arial"%3ELoading...%3C/text%3E%3C/svg%3E';
        modalImage.alt = item.title;
        modalImage.style.display = 'block';
        
        if (imageToLoad && imageToLoad.trim() !== '' && imageToLoad !== 'null' && imageToLoad !== 'undefined') {
            const tempImg = new Image();
            tempImg.onload = function() {
                modalImage.src = imageToLoad;
                modalImage.alt = item.title || 'Infographic image';
            };
            tempImg.onerror = function() {
                modalImage.src = placeholderImg;
                modalImage.alt = 'Image !available';
            };
            tempImg.src = imageToLoad;
        } else {
            modalImage.src = placeholderImg;
            modalImage.alt = 'Image !available';
        }
        
        // Set download button data
        const downloadBtn = document.getElementById('infographicModalDownload');
        if (downloadBtn) {
            // Gunakan dl (download link) jika ada, jika tidak gunakan route download
            const downloadUrl = item.dl || `{{ url('/infographics/download') }}/${item.id}`;
            downloadBtn.setAttribute('data-infographic-id', item.id || '');
            downloadBtn.setAttribute('data-infographic-title', item.title || '');
            downloadBtn.setAttribute('data-infographic-url', downloadUrl);
        }
        
        // Set share button data with slug
        const shareUrl = window.location.origin + '/infographics?infographic=' + item.id + (slug ? '&slug=' + slug : '');
        const shareButtons = document.querySelectorAll('.share-infographic-modal-btn, .share-infographic-btn');
        shareButtons.forEach(btn => {
            if (btn) {
                btn.dataset.infographicTitle = item.title || 'Infografis';
                btn.dataset.infographicUrl = shareUrl;
            }
        });
        
        // Update bookmark button in modal (always show, but check login on click)
        const modalBookmarkBtn = document.getElementById('modalInfographicBookmarkBtn');
        if (modalBookmarkBtn) {
            // Find the infographic in the list to get bookmark_id
            const infographicElement = infographics.find(i => i.id === id);
            if (infographicElement) {
                // Check if there's a bookmark button in the grid/list view
                const listBookmarkBtn = document.querySelector(`.bookmark-btn[data-content-type="infographic"][data-object-id="${item.id}"]`);
                if (listBookmarkBtn && isAuthenticated) {
                    const bookmarkId = listBookmarkBtn.dataset.bookmarkId || '';
                    const isBookmarked = listBookmarkBtn.classList.contains('bookmarked');
                    
                    modalBookmarkBtn.dataset.objectId = String(item.id);
                    modalBookmarkBtn.dataset.bookmarkId = bookmarkId;
                    
                    const icon = modalBookmarkBtn.querySelector('i');
                    const text = modalBookmarkBtn.querySelector('span');
                    
                    if (isBookmarked) {
                        modalBookmarkBtn.classList.add('bookmarked');
                        icon.classList.remove('bi-bookmark');
                        icon.classList.add('bi-bookmark-fill');
                        if (text) text.textContent = 'Bookmark';
                    } else {
                        modalBookmarkBtn.classList.remove('bookmarked');
                        icon.classList.remove('bi-bookmark-fill');
                        icon.classList.add('bi-bookmark');
                        if (text) text.textContent = 'Bookmark';
                    }
                } else {
                    // Set default values (for non-authenticated users or if button not found)
                    modalBookmarkBtn.dataset.objectId = String(item.id);
                    modalBookmarkBtn.dataset.bookmarkId = '';
                    modalBookmarkBtn.classList.remove('bookmarked');
                    const icon = modalBookmarkBtn.querySelector('i');
                    const text = modalBookmarkBtn.querySelector('span');
                    icon.classList.remove('bi-bookmark-fill');
                    icon.classList.add('bi-bookmark');
                    if (text) text.textContent = 'Bookmark';
                }
            }
        }
        
        // Populate related infographics
        const relatedContainer = document.getElementById('relatedInfographics');
        if (relatedContainer) {
            relatedContainer.innerHTML = '';
            const relatedCount = Math.min(3, infographics.length - 1);
            let shown = 0;
            
            for (let i = 0; i < infographics.length && shown < relatedCount; i++) {
                if (infographics[i].id !== id) {
                    const relatedItem = infographics[i];
                    const relatedElement = document.createElement('a');
                    relatedElement.className = 'related-infographic-item';
                    relatedElement.href = '#';
                    relatedElement.onclick = function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        console.log('[DEBUG] Klik rekomendasi infografis:', relatedItem.id);
                        console.log('[DEBUG] Modal instance sebelum:', bootstrap.Modal.getInstance(document.getElementById('infographicModal')));
                        console.log('[DEBUG] Backdrop sebelum:', document.querySelectorAll('.modal-backdrop').length);
                        console.log('[DEBUG] Body modal-open sebelum:', document.body.classList.contains('modal-open'));
                        
                        // Simply call showInfographicDetail - it will handle updating the modal
                        showInfographicDetail(relatedItem.id);
                    };
                    
                    const relatedThumbnail = relatedItem.image || placeholderImg;
                    relatedElement.innerHTML = `
                        <img src="${relatedThumbnail}" alt="${relatedItem.title}" loading="lazy" onerror="this.src='${placeholderImg}'" />
                        <div class="content">
                            <div class="title">${relatedItem.title}</div>
                        </div>
                    `;
                    
                    relatedContainer.appendChild(relatedElement);
                    shown++;
                }
            }
            
            if (shown === 0) {
                relatedContainer.innerHTML = '<p class="text-muted small">Tidak ada infografis terkait</p>';
            }
        }
        
        // Show modal
        const modalElement = document.getElementById('infographicModal');
        
        // Check if modal is already open
        const existingModal = bootstrap.Modal.getInstance(modalElement);
        const isModalActuallyOpen = modalElement.classList.contains('show') && 
                                    existingModal && 
                                    existingModal._isShown === true;
        
        console.log('[DEBUG] Existing modal instance:', existingModal);
        console.log('[DEBUG] Modal element classList:', modalElement.classList.toString());
        console.log('[DEBUG] Modal _isShown:', existingModal ? existingModal._isShown : 'no instance');
        console.log('[DEBUG] isModalActuallyOpen:', isModalActuallyOpen);
        
        if (isModalActuallyOpen) {
            console.log('[DEBUG] Modal sudah terbuka, update konten saja');
            // Modal is already open, content is already updated above
            // Scroll to top of modal content to show new content
            const modalBody = modalElement.querySelector('.modal-body');
            if (modalBody) {
                modalBody.scrollTop = 0;
            }
            // Force a reflow to ensure content is visible
            modalElement.offsetHeight;
        } else {
            console.log('[DEBUG] Modal belum terbuka, buka modal baru');
            // If there's an existing instance but modal is closed, dispose it first
            if (existingModal) {
                console.log('[DEBUG] Dispose existing modal instance');
                existingModal.dispose();
            }
            
            // Clean up any lingering backdrops
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            
            // Create and show new modal
            const modal = new bootstrap.Modal(modalElement);
            console.log('[DEBUG] Membuka modal baru');
            modal.show();
        }
    }

    // Render pagination
    function renderPagination() {
        const paginationContainer = document.getElementById('paginationContainer');
        if (!paginationContainer || !paginationMeta || !paginationLinks) return;

        const currentPageNum = paginationMeta.current_page || currentPage;
        const lastPage = paginationMeta.last_page || 1;
        const total = paginationMeta.total || 0;
        const perPage = paginationMeta.per_page || 20;
        const from = paginationMeta.from || 0;
        const to = paginationMeta.to || 0;

        if (lastPage <= 1) {
            paginationContainer.innerHTML = '';
            return;
        }

        let paginationHTML = '<nav aria-label="Page navigation" class="mt-4"><ul class="pagination justify-content-center">';

        // Previous button
        if (paginationLinks.prev) {
            paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="event.preventDefault(); loadInfographics(${currentPageNum - 1});"><i class="bi bi-chevron-left"></i> Previous</a></li>`;
        } else {
            paginationHTML += `<li class="page-item disabled"><span class="page-link"><i class="bi bi-chevron-left"></i> Previous</span></li>`;
        }

        // Page numbers
        const startPage = Math.max(1, currentPageNum - 2);
        const endPage = Math.min(lastPage, currentPageNum + 2);

        if (startPage > 1) {
            paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="event.preventDefault(); loadInfographics(1);">1</a></li>`;
            if (startPage > 2) {
                paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            if (i === currentPageNum) {
                paginationHTML += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
            } else {
                paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="event.preventDefault(); loadInfographics(${i});">${i}</a></li>`;
            }
        }

        if (endPage < lastPage) {
            if (endPage < lastPage - 1) {
                paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
            paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="event.preventDefault(); loadInfographics(${lastPage});">${lastPage}</a></li>`;
        }

        // Next button
        if (paginationLinks.next) {
            paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="event.preventDefault(); loadInfographics(${currentPageNum + 1});">Next <i class="bi bi-chevron-right"></i></a></li>`;
        } else {
            paginationHTML += `<li class="page-item disabled"><span class="page-link">Next <i class="bi bi-chevron-right"></i></span></li>`;
        }

        paginationHTML += '</ul>';
        paginationHTML += `<p class="text-center text-muted small mt-2">Menampilkan ${from} sampai ${to} dari ${total} infografis</p>`;
        paginationHTML += '</nav>';

        paginationContainer.innerHTML = paginationHTML;
    }

    // Search function - only called when Enter is pressed or search button is clicked
    function performSearch() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchQuery = searchInput.value.trim();
            loadInfographics(1); // Reset to page 1 when searching
        }
    }

    // Handle Enter key press in search input
    function handleSearchKeyPress(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            performSearch();
        }
    }

    // Clear search when input is cleared
    document.getElementById('searchInput')?.addEventListener('input', function(e) {
        // Only clear search if input is empty, but don't search automatically
        if (e.target.value.trim() === '' && searchQuery !== '') {
            searchQuery = '';
            loadInfographics(1); // Reset to page 1 when clearing search
        }
    });

    // Refresh function - force reload without cache
    function refreshInfographics() {
        // Reset to page 1 and clear search
        currentPage = 1;
        searchQuery = '';
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.value = '';
        }
        // Force reload
        loadInfographics(1);
    }

    // Share infographic functionality
    function shareInfographic(title, url) {
        console.log('shareInfographic called:', { title, url }); // Debug
        
        // Ensure URL is valid
        if (!url || url === 'undefined' || url === 'null' || url === '') {
            url = window.location.href;
            console.warn('Invalid URL, using current page URL:', url);
        }
        
        // Ensure URL is a string
        url = String(url);
        
        // Check if Web Share API is available (mobile browsers)
        if (navigator.share) {
            navigator.share({
                title: title,
                text: 'Lihat infografis ini: ' + title,
                url: url
            }).then(() => {
                console.log('Share successful');
            }).catch(err => {
                console.log('Error sharing or user cancelled:', err);
                // Fallback to copy to clipboard
                copyInfographicToClipboard(url, title);
            });
        } else {
            // Fallback: copy to clipboard
            console.log('Web Share API not available, copying to clipboard');
            copyInfographicToClipboard(url, title);
        }
    }

    // Copy to clipboard directly from event handler (maintains user interaction context)
    async function copyInfographicToClipboardDirect(text, title, event, button) {
        text = String(text || '');
        
        if (!text) {
            console.error('No text to copy');
            showInfographicToast('Tidak ada link untuk disalin');
            return;
        }
        
        console.log('Copying to clipboard directly:', text, title); // Debug log
        
        if (navigator.clipboard && navigator.clipboard.writeText) {
            try {
                await navigator.clipboard.writeText(text);
                console.log('Successfully copied to clipboard using Clipboard API'); // Debug log
                showInfographicToast('Link infografis "' + title + '" telah disalin ke clipboard');
                
                // Visual feedback on button
                if (button) {
                    const originalHTML = button.innerHTML;
                    const originalClasses = button.className;
                    button.innerHTML = '<i class="bi bi-check"></i> <span>Tersalin!</span>';
                    button.classList.add('btn-success');
                    button.classList.remove('btn-outline-secondary', 'btn-light');
                    
                    setTimeout(() => {
                        button.innerHTML = originalHTML;
                        button.className = originalClasses;
                    }, 2000);
                }
            } catch (err) {
                console.error('Clipboard API failed:', err);
                // Fallback for older browsers or when API fails
                fallbackCopyInfographicToClipboard(text, title);
            }
        } else {
            console.log('Clipboard API not available, using fallback'); // Debug log
            fallbackCopyInfographicToClipboard(text, title);
        }
    }
    
    // Keep old function for backward compatibility
    function copyInfographicToClipboard(text, title) {
        return copyInfographicToClipboardDirect(text, title, null);
    }

    function fallbackCopyInfographicToClipboard(text, title) {
        console.log('Using fallback copy method');
        
        // Ensure text is a string
        text = String(text || '');
        
        if (!text) {
            console.error('No text to copy in fallback');
            showInfographicToast('Tidak ada link untuk disalin');
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
        textArea.setAttribute('aria-hidden', 'true');
        
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
                showInfographicToast('Link infografis "' + title + '" telah disalin ke clipboard');
            } else {
                throw new Error('execCommand copy returned false');
            }
        } catch (err) {
            console.error('Fallback copy failed:', err);
            // Last resort: show the text in a prompt
            prompt('Salin link berikut:', text);
            showInfographicToast('Silakan salin link secara manual');
        }
        
        // Clean up
        setTimeout(() => {
            if (textArea.parentNode) {
                textArea.parentNode.removeChild(textArea);
            }
        }, 100);
    }

    function showInfographicToast(message) {
        // Create toast element - same style as publications
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

    // Handle bookmark click - check login first
    function handleInfographicBookmark(button) {
        if (!isAuthenticated) {
            // User not logged in, show login required modal
            const infographicTitle = button.closest('.card')?.querySelector('.card-title')?.textContent || 
                                   button.closest('tr')?.querySelector('td strong')?.textContent || 
                                   document.getElementById('infographicModalTitle')?.textContent || 
                                   'infografis ini';
            
            // Update modal content
            const itemNameSpan = document.getElementById('bookmark-item-name');
            if (itemNameSpan) {
                itemNameSpan.textContent = infographicTitle;
            }
            
            // Show modal
            const bookmarkLoginModal = document.getElementById('bookmarkLoginRequiredModal');
            if (bookmarkLoginModal) {
                const modal = new bootstrap.Modal(bookmarkLoginModal);
                modal.show();
            } else {
                // Fallback: redirect to login page
                window.location.href = '{{ route("login") }}';
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

    // Handle download click - check login first
    function handleInfographicDownload(button) {
        if (!isAuthenticated) {
            // User not logged in, show login required modal
            const infographicTitle = button.dataset.infographicTitle || 
                                   document.getElementById('infographicModalTitle')?.textContent || 
                                   'infografis ini';
            
            // Update modal content
            const itemNameSpan = document.getElementById('download-item-name');
            if (itemNameSpan) {
                itemNameSpan.textContent = infographicTitle;
            }
            
            // Show modal
            const downloadLoginModal = document.getElementById('downloadLoginRequiredModal');
            if (downloadLoginModal) {
                const modal = new bootstrap.Modal(downloadLoginModal);
                modal.show();
            } else {
                // Fallback: redirect to login page
                window.location.href = '{{ route("login") }}';
            }
            return;
        }
        
        // User is authenticated, proceed with download
        // Langsung arahkan ke link dl jika ada
        const downloadUrl = button.dataset.infographicUrl || button.getAttribute('data-infographic-url');
        if (downloadUrl) {
            // Langsung redirect ke link download agar file langsung terunduh
            window.location.href = downloadUrl;
        } else {
            console.error('Download URL not found');
        }
    }

    // Initialize share buttons
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing share buttons for infographics'); // Debug
        // Share buttons in modal and actions - using event delegation
        document.addEventListener('click', async function(e) {
            const shareBtn = e.target.closest('.share-infographic-modal-btn') || e.target.closest('.share-infographic-btn') || e.target.closest('.share-btn');
            if (shareBtn) {
                e.preventDefault();
                e.stopPropagation();
                const title = shareBtn.dataset.infographicTitle || shareBtn.dataset.shareTitle || 'Infografis';
                let url = shareBtn.dataset.infographicUrl || shareBtn.dataset.shareUrl || window.location.href;
                
                // Ensure URL is complete (add origin if relative)
                if (url && !url.startsWith('http://') && !url.startsWith('https://')) {
                    url = window.location.origin + (url.startsWith('/') ? url : '/' + url);
                }
                
                console.log('Share button clicked:', { title, url, button: shareBtn, dataset: shareBtn.dataset }); // Debug log
                
                // Directly copy to clipboard (no Web Share API)
                await copyInfographicToClipboardDirect(url, title, e, shareBtn);
            }
        });

        // Load bookmarks for authenticated users
        if (isAuthenticated) {
            // Check if toggleBookmark function exists, if not, load it
            if (typeof toggleBookmark === 'undefined') {
                // Load bookmarks and sync bookmark buttons
                fetch('/bookmarks')
                    .then(response => response.json())
                    .then(data => {
                        const bookmarks = data.bookmarks || data || [];
                        bookmarks.forEach(bookmark => {
                            if (bookmark.content_type === 'infographic') {
                                const buttons = document.querySelectorAll(`.bookmark-btn[data-content-type="infographic"][data-object-id="${bookmark.object_id}"]`);
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
    });

    // Get total all infographics (without filter)
    async function getTotalAllInfographics() {
        try {
            const response = await fetch(`${API_BASE}/infographics?page=1&_t=${Date.now()}`, {
                cache: 'no-cache',
                headers: {
                    'Cache-Control': 'no-cache',
                    'Pragma': 'no-cache'
                }
            });
            if (response.ok) {
                const data = await response.json();
                if (data.meta && data.meta.total !== undefined) {
                    totalAllInfographics = data.meta.total;
                    // Update count display after getting total
                    updateInfographicCount();
                }
            }
        } catch (error) {
            console.error('Error getting total infographics:', error);
        }
    }

    // Update infographic count display
    function updateInfographicCount() {
        const countContainer = document.getElementById('infographicCountContainer');
        if (!countContainer) return;

        const filteredCount = paginationMeta?.total || 0;
        
        if (searchQuery && searchQuery.trim() !== '') {
            // Show filtered count and total
            // If totalAllInfographics is not yet loaded, use filteredCount as fallback
            const total = totalAllInfographics || filteredCount;
            countContainer.innerHTML = `
                Menampilkan: <span class="badge bg-primary">${filteredCount}</span> dari <span class="badge bg-secondary">${total}</span> infografis
            `;
        } else {
            // Show total only
            countContainer.innerHTML = `
                Total: <span class="badge bg-primary">${filteredCount}</span> infografis
            `;
            // Update totalAllInfographics when no search
            if (filteredCount > 0) {
                totalAllInfographics = filteredCount;
            }
        }
    }

    // Global cleanup for modal backdrop
    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('infographicModal');
        if (modalElement) {
            // Clean up backdrop when modal is hidden
            modalElement.addEventListener('hidden.bs.modal', function() {
                console.log('[DEBUG] Modal hidden event triggered');
                console.log('[DEBUG] Backdrop sebelum cleanup:', document.querySelectorAll('.modal-backdrop').length);
                console.log('[DEBUG] Body modal-open sebelum cleanup:', document.body.classList.contains('modal-open'));
                console.log('[DEBUG] Body overflow sebelum cleanup:', document.body.style.overflow);
                console.log('[DEBUG] Body paddingRight sebelum cleanup:', document.body.style.paddingRight);
                
                // Remove infographic and slug from URL when modal is closed
                const url = new URL(window.location.href);
                url.searchParams.delete('infographic');
                url.searchParams.delete('slug');
                window.history.pushState({}, '', url);
                
                // Clean up backdrop
                setTimeout(() => {
                    const backdrops = document.querySelectorAll('.modal-backdrop');
                    console.log('[DEBUG] Backdrop ditemukan untuk dihapus:', backdrops.length);
                    backdrops.forEach(backdrop => {
                        console.log('[DEBUG] Menghapus backdrop:', backdrop);
                        backdrop.remove();
                    });
                    
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                    
                    console.log('[DEBUG] Setelah cleanup:');
                    console.log('[DEBUG] - Backdrop tersisa:', document.querySelectorAll('.modal-backdrop').length);
                    console.log('[DEBUG] - Body modal-open:', document.body.classList.contains('modal-open'));
                    console.log('[DEBUG] - Body overflow:', document.body.style.overflow);
                    console.log('[DEBUG] - Body paddingRight:', document.body.style.paddingRight);
                    console.log('[DEBUG] - Body pointerEvents:', document.body.style.pointerEvents);
                    
                    // Cek apakah card bisa diklik
                    const cards = document.querySelectorAll('.infographic-card');
                    console.log('[DEBUG] Jumlah infographic-card ditemukan:', cards.length);
                    cards.forEach((card, index) => {
                        console.log(`[DEBUG] Card ${index}:`, {
                            pointerEvents: window.getComputedStyle(card).pointerEvents,
                            zIndex: window.getComputedStyle(card).zIndex,
                            onclick: card.onclick ? 'ada' : 'tidak ada',
                            hasOnclickAttr: card.getAttribute('onclick') ? 'ada' : 'tidak ada'
                        });
                    });
                }, 100);
            });
        }
    });

    // Initialize search query from URL
    document.addEventListener('DOMContentLoaded', async function() {
        const urlParams = new URLSearchParams(window.location.search);
        const urlSearch = urlParams.get('search');
        if (urlSearch) {
            searchQuery = urlSearch;
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.value = urlSearch;
            }
        }
        
        // Get total all infographics first (without search)
        await getTotalAllInfographics();
        
        // Then load infographics
        await loadInfographics();
        
        // Check if there's an infographic parameter in URL, open modal automatically
        const infographicId = urlParams.get('infographic');
        if (infographicId) {
            // Wait a bit for infographics to be loaded
            setTimeout(() => {
                showInfographicDetail(parseInt(infographicId));
            }, 500);
        }
    });
</script>
@endpush

@section('content')
<div class="infographics-page">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-2">Infografis BPS Kota Surabaya</h3>
                    <p class="text-muted mb-0" id="infographicCountContainer">
                        Total: <span class="badge bg-primary" id="infographicCount">-</span> infografis
                    </p>
                </div>
                <button class="btn btn-primary" onclick="refreshInfographics()">
                    <i class="bi bi-arrow-clockwise"></i> Refresh Data
                </button>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="row mb-4">
        <div class="col-md-8 mb-3 mb-md-0">
            <div class="search-input-wrapper shadow-sm">
                <input type="text" class="form-control" 
                       id="searchInput" placeholder="Cari infografis berdasarkan judul..."
                       onkeypress="handleSearchKeyPress(event)">
                <span class="search-icon">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <button type="button" class="search-button" onclick="performSearch()">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
        </div>
        <div class="col-md-4 d-flex justify-content-end gap-2">
            <div class="btn-group shadow-sm" role="group">
                <button type="button" class="btn btn-outline-primary active" onclick="changeView('grid', event)">
                    <i class="bi bi-grid-3x3"></i> <span class="d-none d-sm-inline">Grid</span>
                </button>
                <button type="button" class="btn btn-outline-primary" onclick="changeView('list', event)">
                    <i class="bi bi-list"></i> <span class="d-none d-sm-inline">List</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Grid View -->
    <div id="infographicsContainer" class="row">
        <div class="col-12">
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div id="paginationContainer"></div>
</div>

<!-- Modal for Infographic Detail -->
<div class="modal fade" id="infographicModal" tabindex="-1" aria-labelledby="infographicModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" id="infographicModalDialog">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title flex-grow-1" id="infographicModalTitle"></h5>
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    @include('components.share-button', [
                        'title' => '',
                        'url' => '',
                        'contentType' => 'infographic',
                        'size' => 'sm',
                        'variant' => 'outline-secondary',
                        'showText' => true,
                        'class' => 'share-infographic-modal-btn'
                    ])
                    <button type="button" class="btn btn-outline-secondary btn-sm bookmark-btn" id="modalInfographicBookmarkBtn" data-content-type="infographic" data-object-id="" data-bookmark-id="" onclick="handleInfographicBookmark(this)">
                        <i class="bi bi-bookmark"></i> <span class="d-none d-md-inline">Bookmark</span>
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0 h-100 m-0">
                    <!-- Main Infographic -->
                    <div class="col-lg-8 border-end infographic-modal-left">
                        <div class="infographic-modal-img-wrap">
                            <img id="infographicModalImage" src="" alt="" class="img-fluid" />
                        </div>
                        <div class="infographic-modal-actions d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="d-flex gap-2">
                                @include('components.share-button', [
                                    'title' => '',
                                    'url' => '',
                                    'contentType' => 'infographic',
                                    'size' => 'sm',
                                    'variant' => 'outline-secondary',
                                    'showText' => true,
                                    'class' => 'share-infographic-btn'
                                ])
                            </div>
                            <button type="button" id="infographicModalDownload" class="btn btn-primary btn-sm download-infographic-btn" data-infographic-id="" data-infographic-title="" data-infographic-url="" onclick="handleInfographicDownload(this)">
                                <i class="bi bi-download"></i> <span class="d-none d-md-inline">Unduh</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Related Infographics -->
                    <div class="col-lg-4 infographic-modal-right">
                        <div class="p-3">
                            <h6 class="fw-bold mb-3">Lainnya</h6>
                            <div id="relatedInfographics" class="d-flex flex-column gap-3">
                                <!-- Related items will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Login Required Modal for Bookmark -->
<div class="modal fade" id="bookmarkLoginRequiredModal" tabindex="-1" aria-labelledby="bookmarkLoginRequiredModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookmarkLoginRequiredModalLabel">Login Diperlukan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p id="bookmark-login-required-message">Ingin menambahkan <span id="bookmark-item-name"></span> ke bookmark? Silakan login terlebih dahulu.</p>
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            </div>
        </div>
    </div>
</div>

<!-- Login Required Modal for Download -->
<div class="modal fade" id="downloadLoginRequiredModal" tabindex="-1" aria-labelledby="downloadLoginRequiredModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="downloadLoginRequiredModalLabel">Login Diperlukan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p id="download-login-required-message">Ingin mengunduh <span id="download-item-name"></span>? Silakan login terlebih dahulu.</p>
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            </div>
        </div>
    </div>
</div>

<style>
    /* ===== MODAL FIXED LAYOUT ===== */

    /* Backdrop: cover full viewport, block scroll */
    #infographicModal {
        overflow: hidden !important;
    }
    
    /* Prevent body scroll when modal is open */
    body.modal-open {
        overflow: hidden !important;
        padding-right: 0 !important;
    }

    /* Dialog: centered box, fixed position, pas di layar */
    #infographicModalDialog {
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        width: 90% !important;
        max-width: 1400px !important;
        height: 90vh !important;
        max-height: 90vh !important;
        margin: 0 !important;
        display: flex;
        flex-direction: column;
        pointer-events: auto;
    }

    /* Content: flex column, fill dialog, clip overflow */
    #infographicModal .modal-content {
        display: flex;
        flex-direction: column;
        height: 100% !important;
        max-height: 100% !important;
        overflow: hidden !important;
        border-radius: 8px;
    }

    /* Header: never shrinks */
    #infographicModal .modal-header {
        flex-shrink: 0;
        z-index: 2;
    }

    /* Body: takes remaining space, no scroll itself */
    #infographicModal .modal-body {
        flex: 1;
        min-height: 0;              /* critical for flex child scroll */
        overflow: hidden !important;
        padding: 0;
        display: flex;
        flex-direction: column;
    }

    /* Row: fill body height */
    #infographicModal .modal-body .row {
        height: 100% !important;
        margin: 0 !important;
        display: flex;
        flex: 1;
        min-height: 0;
    }
    
    /* Column adjustments */
    #infographicModal .modal-body .row > div {
        display: flex;
        flex-direction: column;
    }

    /* --- LEFT COLUMN (image + download) --- */
    .infographic-modal-left {
        flex: 1;                    /* grow to fill */
        min-width: 0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border-right: 1px solid #dee2e6;
        height: 100%;
    }

    /* Image area: flex-grow, clips overflow */
    .infographic-modal-img-wrap {
        flex: 1;
        min-height: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f8f9fa;
        padding: 1rem;
    }

    .infographic-modal-img-wrap img {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    /* Download bar: never shrinks, pinned at bottom of left col */
    .infographic-modal-actions {
        flex-shrink: 0;
        padding: 0.75rem 1rem;
        border-top: 1px solid #dee2e6;
        background: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    /* Bookmark button styling */
    .bookmark-btn.bookmarked {
        color: #ffc107 !important;
        border-color: #ffc107 !important;
    }
    
    .bookmark-btn.bookmarked i {
        color: #ffc107 !important;
    }
    
    .bookmark-btn:hover {
        border-color: #ffc107;
    }
    
    /* Share button styling */
    .share-infographic-modal-btn,
    .share-infographic-btn {
        transition: all 0.2s ease;
    }
    
    .share-infographic-modal-btn:hover,
    .share-infographic-btn:hover {
        background-color: #f8f9fa;
        border-color: #6c757d;
    }
    
    /* Toast notification animations */
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    /* --- RIGHT COLUMN (related list) --- */
    .infographic-modal-right {
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        height: 100%;
    }
    
    .infographic-modal-right > div {
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
    }
    
    #infographicModal #relatedInfographics {
        overflow-y: auto !important;
        overflow-x: hidden !important;
        flex: 1;
        min-height: 0;
    }

    /* ===== RELATED ITEM CARDS ===== */
    .related-infographic-item {
        display: flex;
        gap: 12px;
        padding: 12px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .related-infographic-item:hover {
        background: #f8f9fa;
        border-color: #0d6efd;
        transform: translateX(4px);
    }

    .related-infographic-item img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
        flex-shrink: 0;
    }

    .related-infographic-item .content {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .related-infographic-item .title {
        font-size: 0.875rem;
        font-weight: 500;
        color: #333;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.3;
    }

    /* ===== RESPONSIVE ===== */
    
    /* Tablet (max-width: 992px) - Modal scrollable */
    @media (max-width: 992px) {
        #infographicModalDialog {
            width: 90% !important;
            max-width: 900px !important;
            height: auto !important;
            max-height: 90vh !important;
            overflow: visible !important;
        }

        #infographicModal .modal-content {
            height: auto !important;
            max-height: 90vh !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
        }

        #infographicModal .modal-body {
            overflow-y: visible !important;
            overflow: visible !important;
        }

        /* Tetap 2 kolom seperti desktop */
        #infographicModal .modal-body .row {
            flex-direction: row !important;
            height: auto !important;
        }

        .infographic-modal-left {
            border-right: 1px solid #dee2e6;
            border-bottom: none;
            height: auto;
        }

        .infographic-modal-img-wrap {
            padding: 0.75rem;
        }

        .infographic-modal-right {
            width: auto !important;
            height: auto;
            max-height: none;
        }

        #infographicModal .modal-header {
            flex-wrap: wrap;
            padding: 0.75rem 1rem;
        }

        #infographicModal .modal-header .modal-title {
            font-size: 1rem;
            flex: 1;
            min-width: 0;
            margin-right: 0.5rem;
        }

        #infographicModal .modal-header .d-flex {
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        #infographicModal .modal-header .btn {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        #infographicModal .modal-header .btn span {
            display: none;
        }

        .infographic-modal-actions {
            padding: 0.5rem 0.75rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .infographic-modal-actions .btn {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }
    }

    /* Mobile (max-width: 768px) - Modal scrollable */
    @media (max-width: 768px) {
        #infographicModalDialog {
            width: 95% !important;
            max-width: 600px !important;
            height: auto !important;
            max-height: 85vh !important;
            overflow: visible !important;
        }

        #infographicModal .modal-content {
            height: auto !important;
            max-height: 85vh !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
        }

        #infographicModal .modal-body {
            padding: 0;
            overflow-y: visible !important;
            overflow: visible !important;
        }

        /* Stack columns vertically */
        #infographicModal .modal-body .row {
            flex-direction: column !important;
            height: auto !important;
        }

        /* Image area mobile */
        .infographic-modal-left {
            height: auto;
            min-height: 300px;
            border-right: none;
            border-bottom: 1px solid #dee2e6;
        }

        .infographic-modal-img-wrap {
            padding: 0.5rem;
            min-height: 250px;
        }

        .infographic-modal-img-wrap img {
            max-height: 300px !important;
        }

        /* Actions mobile */
        .infographic-modal-actions {
            padding: 0.75rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .infographic-modal-actions .btn {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }

        /* Related infographics mobile */
        .infographic-modal-right {
            width: 100% !important;
            height: auto;
            max-height: 250px;
            border-top: 1px solid #dee2e6;
        }

        .infographic-modal-right > div {
            padding: 0.75rem;
        }

        .infographic-modal-right h6 {
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
        }

        #infographicModal .modal-header {
            padding: 0.75rem 1rem;
        }

        #infographicModal .modal-header .modal-title {
            font-size: 0.95rem;
        }

        .related-infographic-item {
            padding: 0.75rem;
            gap: 0.75rem;
        }

        .related-infographic-item img {
            width: 60px;
            height: 60px;
        }

        .related-infographic-item .title {
            font-size: 0.8rem;
        }
    }

    /* Small mobile (max-width: 576px) */
    @media (max-width: 576px) {
        #infographicModalDialog {
            width: 95% !important;
            max-width: 500px !important;
            max-height: 80vh !important;
        }

        #infographicModal .modal-content {
            max-height: 80vh !important;
        }

        #infographicModal .modal-body {
            overflow-y: visible !important;
            overflow: visible !important;
        }

        #infographicModal .modal-header .modal-title {
            font-size: 0.85rem;
        }

        #infographicModal .modal-header .btn {
            font-size: 0.65rem;
            padding: 0.2rem 0.4rem;
        }

        .infographic-modal-img-wrap {
            min-height: 200px;
            padding: 0.25rem;
        }

        .infographic-modal-img-wrap img {
            max-height: 250px !important;
        }

        .infographic-modal-actions {
            padding: 0.5rem;
        }

        .infographic-modal-actions .btn {
            font-size: 0.7rem;
            padding: 0.5rem;
        }

        .infographic-modal-right {
            max-height: 200px;
        }

        .related-infographic-item {
            padding: 0.5rem;
            gap: 0.5rem;
        }

        .related-infographic-item img {
            width: 50px;
            height: 50px;
        }

        .related-infographic-item .title {
            font-size: 0.75rem;
        }
    }

    /* Body scroll lock while modal is open */
    body.modal-open {
        overflow: hidden !important;
        padding-right: 0 !important;
    }
</style>
@endsection

