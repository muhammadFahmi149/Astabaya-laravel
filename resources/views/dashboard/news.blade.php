@extends('layouts.main')

@section('title', 'Berita')

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
        border: 1px solid #ced4da;
        height: 38px;
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
    
    .search-input-wrapper .form-control:focus {
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        pointer-events: none;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background-color: #f8f9fa;
        border-radius: 50%;
        color: #6c757d;
    }

    /* Filter Select Styling */
    #categoryFilter,
    #sortFilter {
        border-radius: 25rem !important;
        height: 38px;
        border: 1px solid #ced4da;
    }

    #categoryFilter:focus,
    #sortFilter:focus {
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
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

@section('content')
<div class="news-page">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-2">Berita BPS Kota Surabaya</h3>
                    <p class="text-muted mb-0">
                        @if(isset($search_query) || isset($selected_category))
                            Menampilkan: <span class="badge bg-primary">{{ $filtered_count ?? 0 }}</span> dari <span class="badge bg-secondary">{{ $totalNews ?? 0 }}</span> berita
                        @else
                            Total: <span class="badge bg-primary">{{ $totalNews ?? 0 }}</span> berita
                        @endif
                    </p>
                </div>
                <a href="{{ route('news') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-clockwise"></i> Refresh Data
                </a>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <form method="GET" action="{{ route('news') }}" id="filterForm">
        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="search-input-wrapper shadow-sm">
                    <input type="text" class="form-control" id="searchInput" name="search"
                           placeholder="Cari berita berdasarkan judul atau konten..."
                           value="{{ $search_query ?? '' }}"
                           onkeypress="if(event.key === 'Enter') { event.preventDefault(); document.getElementById('filterForm').submit(); }">
                    <span class="search-icon">
                        <i class="bi bi-search"></i>
                    </span>
                    <button type="submit" class="search-button">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </div>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <select class="form-select shadow-sm" id="categoryFilter" name="category_id" onchange="document.getElementById('filterForm').submit();">
                    <option value="">Semua Kategori</option>
                    @if(isset($available_categories))
                        @foreach($available_categories as $category)
                            <option value="{{ $category['id'] }}" {{ (isset($selected_category) && $selected_category == $category['id']) ? 'selected' : '' }}>
                                {{ $category['name'] }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select shadow-sm" id="sortFilter" name="sort" onchange="document.getElementById('filterForm').submit();">
                    <option value="latest" {{ (isset($sort) && $sort == 'latest') ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ (isset($sort) && $sort == 'oldest') ? 'selected' : '' }}>Terlama</option>
                </select>
            </div>
        </div>
    </form>

    <!-- News List -->
    <div class="row" id="newsContainer">
        @if(isset($dataNews) && $dataNews->count() > 0)
            @foreach($dataNews as $index => $item)
            <div class="col-12 mb-4 news-item">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row d-none d-md-flex align-items-center">
                            <div class="col-md-3">
                                @if($item->picture_url)
                                    <img src="{{ $item->picture_url }}" alt="{{ $item->title }}" 
                                         class="img-fluid rounded shadow-sm" 
                                         style="width: 100%; height: 200px; min-height: 200px; object-fit: cover; cursor: pointer;"
                                         onclick="showNewsModal({{ $index }})">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm" 
                                         style="width: 100%; height: 200px; min-height: 200px; cursor: pointer;"
                                         onclick="showNewsModal({{ $index }})">
                                        <i class="bi bi-newspaper" style="font-size: 3rem; color: #ccc;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-9">
                                <h6 class="card-title mb-2 fw-bold" style="cursor: pointer; min-height: 48px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" onclick="showNewsModal({{ $index }})">{{ $item->title }}</h6>
                                <div class="mb-2">
                                    @if($item->category_name)
                                        <span class="badge bg-primary me-2">{{ $item->category_name }}</span>
                                    @endif
                                    @if($item->release_date)
                                        <span class="badge bg-info">
                                            <i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($item->release_date)->format('d F Y') }}
                                        </span>
                                    @endif
                                </div>
                                <p class="card-text text-muted mb-3" style="min-height: 60px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ \Illuminate\Support\Str::words(strip_tags($item->content ?? ''), 30, '...') }}
                                </p>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-primary" onclick="showNewsModal({{ $index }})">
                                        <i class="bi bi-book"></i> Baca Selengkapnya
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary bookmark-btn" data-content-type="news" data-object-id="{{ $item->news_id }}" data-bookmark-id="" onclick="event.stopPropagation(); handleNewsBookmark(this)">
                                        <i class="bi bi-bookmark"></i> Bookmark
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Mobile Layout -->
                        <div class="row d-md-none">
                            <div class="col-12">
                                <div class="d-flex gap-2 align-items-start">
                                    @if($item->picture_url)
                                        <img src="{{ $item->picture_url }}" alt="{{ $item->title }}" 
                                             class="rounded shadow-sm" 
                                             style="width: 80px; height: 80px; min-width: 80px; min-height: 80px; object-fit: cover; cursor: pointer; flex-shrink: 0;"
                                             onclick="showNewsModal({{ $index }})">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm" 
                                             style="width: 80px; height: 80px; min-width: 80px; min-height: 80px; cursor: pointer; flex-shrink: 0;"
                                             onclick="showNewsModal({{ $index }})">
                                            <i class="bi bi-newspaper" style="font-size: 1.5rem; color: #ccc;"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <h6 class="card-title mb-1 fw-bold" style="font-size: 0.9rem; cursor: pointer; min-height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" onclick="showNewsModal({{ $index }})">{{ $item->title }}</h6>
                                        <div class="mb-1">
                                            @if($item->category_name)
                                                <span class="badge bg-primary me-1" style="font-size: 0.7rem;">{{ $item->category_name }}</span>
                                            @endif
                                            @if($item->release_date)
                                                <span class="badge bg-info" style="font-size: 0.7rem;">
                                                    <i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($item->release_date)->format('d M Y') }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="card-text text-muted mb-2" style="font-size: 0.85rem; min-height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ \Illuminate\Support\Str::words(strip_tags($item->content ?? ''), 20, '...') }}
                                        </p>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <button class="btn btn-sm btn-primary" onclick="showNewsModal({{ $index }})" style="font-size: 0.8rem;">
                                                <i class="bi bi-book"></i> Baca
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary bookmark-btn" data-content-type="news" data-object-id="{{ $item->news_id }}" data-bookmark-id="" onclick="event.stopPropagation(); handleNewsBookmark(this)" style="font-size: 0.8rem;">
                                                <i class="bi bi-bookmark"></i> Bookmark
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> Belum ada data berita.
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if(isset($dataNews) && is_object($dataNews) && method_exists($dataNews, 'hasPages') && $dataNews->hasPages())
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            @if($dataNews->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link"><i class="bi bi-chevron-left"></i> Previous</span>
            </li>
            @else
            <li class="page-item">
                <a class="page-link" href="{{ $dataNews->appends(request()->query())->previousPageUrl() }}">
                    <i class="bi bi-chevron-left"></i> Previous
                </a>
            </li>
            @endif

            @foreach($dataNews->getUrlRange(max(1, $dataNews->currentPage() - 2), min($dataNews->lastPage(), $dataNews->currentPage() + 2)) as $page => $url)
                @if($page == $dataNews->currentPage())
                <li class="page-item active">
                    <span class="page-link">{{ $page }}</span>
                </li>
                @else
                <li class="page-item">
                    <a class="page-link" href="{{ $dataNews->appends(request()->query())->url($page) }}">{{ $page }}</a>
                </li>
                @endif
            @endforeach

            @if($dataNews->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $dataNews->appends(request()->query())->nextPageUrl() }}">
                    Next <i class="bi bi-chevron-right"></i>
                </a>
            </li>
            @else
            <li class="page-item disabled">
                <span class="page-link">Next <i class="bi bi-chevron-right"></i></span>
            </li>
            @endif
        </ul>
        <p class="text-center text-muted small mt-2">
            Menampilkan {{ $dataNews->firstItem() ?? 0 }} sampai {{ $dataNews->lastItem() ?? 0 }} dari {{ $filtered_count ?? $dataNews->total() }} berita
        </p>
    </nav>
    @endif
</div>

<!-- Hidden data container for modal -->
<div id="newsDataContainer" style="display: none">
    @if(isset($dataNews) && $dataNews->count() > 0)
        @foreach($dataNews as $item)
        <div class="news-data"
             data-id="{{ $item->news_id }}"
             data-title="{{ e($item->title ?? '') }}"
             data-content="{{ e($item->content ?? '') }}"
             data-category="{{ e($item->category_name ?? '') }}"
             data-date="{{ $item->release_date ? \Carbon\Carbon::parse($item->release_date)->format('d F Y') : 'N/A' }}"
             data-image="{{ e($item->picture_url ?? '') }}">
        </div>
        @endforeach
    @endif
</div>

<!-- Modal for News Detail -->
<div class="modal fade" id="newsCardModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newsModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- News Image (Left) -->
                    <div class="col-md-3">
                        <div id="newsModalImageContainer" class="mb-3"></div>
                    </div>
                    
                    <!-- News Content (Right) -->
                    <div class="col-md-9">
                        <!-- Category & Date (Top) -->
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="badge bg-primary" id="newsModalCategory"></span>
                            <span class="text-muted small" id="newsModalDate"></span>
                        </div>
                        
                        <!-- Description (Below) -->
                        <div id="newsModalContent" style="line-height: 1.8; text-align: justify;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex gap-2 w-100 flex-wrap">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm share-news-modal-btn" id="modalNewsShareBtn" data-news-title="" data-news-url="" onclick="handleNewsShareClick(this); return false;">
                            <i class="bi bi-share"></i> <span class="d-none d-md-inline share-btn-text">Bagikan</span>
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm bookmark-btn" id="modalNewsBookmarkBtn" data-content-type="news" data-object-id="" data-bookmark-id="" onclick="handleNewsBookmark(this)">
                            <i class="bi bi-bookmark"></i> <span class="d-none d-md-inline">Bookmark</span>
                        </button>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* News Card Styles */
    .news-item .card {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .news-item .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15) !important;
        border-color: #0d6efd;
    }

    .news-item .card-body {
        padding: 1.25rem;
    }

    .news-item .card-img-wrapper,
    .news-item img {
        transition: transform 0.3s ease;
    }

    .news-item .card:hover img {
        transform: scale(1.02);
    }

    /* News Detail Modal Styles */
    #newsCardModal .modal-dialog {
        max-width: 900px;
    }

    #newsCardModal .modal-body {
        padding: 1.5rem 0.25rem;
    }

    #newsCardModal #newsModalImageContainer {
        width: 100%;
    }

    #newsCardModal #newsModalImageContainer img {
        width: 100%;
        max-height: 300px;
        object-fit: cover;
        border-radius: 8px;
    }

    #newsCardModal #newsModalContent {
        margin-bottom: 1rem;
    }

    #newsCardModal .badge {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
    }
</style>

@push('scripts')
<script>
    // Authentication status - global scope
    const isAuthenticated = @auth true @else false @endauth;
    
    // Store news data for modal
    const newsData = [];
    const dataElements = document.querySelectorAll('.news-data');
    dataElements.forEach((el) => {
        newsData.push({
            id: el.dataset.id || '',
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

        const modalTitle = document.getElementById('newsModalTitle');
        const modalCategory = document.getElementById('newsModalCategory');
        const modalDate = document.getElementById('newsModalDate');
        const modalContent = document.getElementById('newsModalContent');
        const modalImageContainer = document.getElementById('newsModalImageContainer');

        if (modalTitle) modalTitle.textContent = item.title || 'Berita';
        if (modalCategory) modalCategory.textContent = item.category || 'Umum';
        if (modalDate) {
            modalDate.innerHTML = `<i class="bi bi-calendar"></i> ${item.date}`;
        }
        
        // Clean content from HTML tags and special characters
        let cleanContent = item.content || '';
        
        // First, decode all Unicode escape sequences (\uXXXX) to their actual characters
        try {
            cleanContent = cleanContent.replace(/\\u([0-9a-fA-F]{4})/g, function(match, hex) {
                return String.fromCharCode(parseInt(hex, 16));
            });
        } catch (e) {
            // If decoding fails, continue with original string
        }
        
        // Remove style attributes and their content first
        cleanContent = cleanContent.replace(/style\s*=\s*["'][^"']*["']/gi, '');
        cleanContent = cleanContent.replace(/style\s*=\s*[^\s>]*/gi, '');
        
        // Remove all HTML tags
        cleanContent = cleanContent.replace(/<[^>]+>/g, '');
        
        // Remove HTML tags but preserve text content
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = cleanContent;
        cleanContent = tempDiv.textContent || tempDiv.innerText || '';
        
        // Remove actual control characters
        cleanContent = cleanContent.replace(/[\r\n]+/g, ' ');
        cleanContent = cleanContent.replace(/[\u0000-\u001F\u007F-\u009F]/g, ' ');
        
        // Remove HTML entities if any remain
        cleanContent = cleanContent.replace(/&nbsp;/gi, ' ');
        cleanContent = cleanContent.replace(/&lt;/gi, '<');
        cleanContent = cleanContent.replace(/&gt;/gi, '>');
        cleanContent = cleanContent.replace(/&amp;/gi, '&');
        cleanContent = cleanContent.replace(/&quot;/gi, '"');
        cleanContent = cleanContent.replace(/&#39;/gi, "'");
        cleanContent = cleanContent.replace(/&apos;/gi, "'");
        
        // Replace multiple spaces/tabs with single space
        cleanContent = cleanContent.replace(/[\s\t]+/g, ' ').trim();
        
        if (modalContent) modalContent.textContent = cleanContent;

        // Set image with error handling
        const placeholderImg = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300"%3E%3Crect fill="%23f0f0f0" width="400" height="300"/%3E%3Ctext fill="%23999" x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-size="16" font-family="Arial"%3EImage !available%3C/text%3E%3C/svg%3E';
        
        if (modalImageContainer) {
            modalImageContainer.innerHTML = '';
            
            const imageToLoad = item.image || '';
            
            if (imageToLoad && imageToLoad.trim() !== '') {
                const imgElement = document.createElement('img');
                imgElement.src = imageToLoad;
                imgElement.alt = item.title || 'News image';
                imgElement.className = 'img-fluid';
                imgElement.style.width = '100%';
                imgElement.style.maxHeight = '300px';
                imgElement.style.objectFit = 'cover';
                imgElement.style.borderRadius = '8px';
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

        // Update share button in modal
        const modalShareBtn = document.getElementById('modalNewsShareBtn');
        if (modalShareBtn) {
            const newsTitle = item.title || 'Berita';
            const newsUrl = window.location.href;
            
            modalShareBtn.dataset.newsTitle = newsTitle;
            modalShareBtn.dataset.newsUrl = newsUrl;
            
            console.log('Share button updated:', {
                title: newsTitle,
                url: newsUrl,
                button: modalShareBtn,
                hasShowShareModal: typeof showShareModal !== 'undefined',
                hasWindowShowShareModal: typeof window.showShareModal !== 'undefined'
            });
        } else {
            console.error('Share button not found in modal!');
        }

        // Update bookmark button in modal
        @auth
        const modalBookmarkBtn = document.getElementById('modalNewsBookmarkBtn');
        if (modalBookmarkBtn) {
            // Use news_id for News model (custom primary key)
            const newsId = item.news_id || item.id;
            // Find the news in the list to get bookmark_id
            const newsElement = document.querySelector(`.bookmark-btn[data-content-type="news"][data-object-id="${newsId}"]`);
            if (newsElement) {
                const bookmarkId = newsElement.dataset.bookmarkId || '';
                const isBookmarked = newsElement.classList.contains('bookmarked');
                
                modalBookmarkBtn.dataset.objectId = String(newsId);
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
                // Set default values
                const newsId = item.news_id || item.id;
                modalBookmarkBtn.dataset.objectId = String(newsId);
                modalBookmarkBtn.dataset.bookmarkId = '';
                modalBookmarkBtn.classList.remove('bookmarked');
                const icon = modalBookmarkBtn.querySelector('i');
                const text = modalBookmarkBtn.querySelector('span');
                icon.classList.remove('bi-bookmark-fill');
                icon.classList.add('bi-bookmark');
                if (text) text.textContent = 'Bookmark';
            }
        }
        @endauth

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('newsCardModal'));
        modal.show();
    }
    
    // Make showNewsModal globally available
    window.showNewsModal = showNewsModal;

    // Handle share button click
    function handleNewsShareClick(button) {
        console.log('handleNewsShareClick called:', button);
        console.log('Button dataset:', {
            newsTitle: button.dataset.newsTitle,
            newsUrl: button.dataset.newsUrl
        });
        
        const title = button.dataset.newsTitle || 'Berita';
        const url = button.dataset.newsUrl || window.location.href;
        
        console.log('Calling showShareModal with:', { title, url });
        console.log('window.showShareModal available?', typeof window.showShareModal === 'function');
        
        // Always use window.showShareModal for consistency
        if (typeof window.showShareModal === 'function') {
            window.showShareModal(title, url);
        } else {
            // Wait a bit and try again (in case script is still loading)
            console.log('showShareModal not found, waiting...');
            setTimeout(() => {
                if (typeof window.showShareModal === 'function') {
                    console.log('showShareModal found on retry, calling...');
                    window.showShareModal(title, url);
                } else {
                    console.error('showShareModal function not found after retry!');
                    // Fallback: try to show modal manually
                    const shareModal = document.getElementById('shareModal');
                    if (shareModal) {
                        const modalTitle = document.getElementById('shareModalTitle');
                        const modalInput = document.getElementById('shareModalInput');
                        if (modalTitle) modalTitle.textContent = 'Bagikan: ' + title;
                        if (modalInput) modalInput.value = url;
                        const modal = new bootstrap.Modal(shareModal);
                        modal.show();
                        // Select text when shown
                        shareModal.addEventListener('shown.bs.modal', function() {
                            if (modalInput) {
                                modalInput.select();
                                modalInput.focus();
                            }
                        }, { once: true });
                    } else {
                        alert('Modal share tidak ditemukan. Silakan refresh halaman.');
                    }
                }
            }, 200);
        }
    }
    
    // Make handleNewsShareClick globally available
    window.handleNewsShareClick = handleNewsShareClick;

    // Share news functionality - menggunakan fungsi global dari main.blade.php
    // Fungsi shareNews, copyNewsToClipboard, fallbackCopyNewsToClipboard, dan showNewsToast
    // sudah tersedia secara global di main.blade.php, jadi tidak perlu didefinisikan ulang di sini

    // Handle bookmark click - check login first (sama dengan infografis)
    function handleNewsBookmark(button) {
        if (!isAuthenticated) {
            // User not logged in, show login required modal
            const newsTitle = button.closest('.news-item')?.querySelector('.card-title')?.textContent || 
                             button.closest('.card-body')?.querySelector('h6')?.textContent || 
                             document.getElementById('newsModalTitle')?.textContent || 
                             'berita ini';
            
            // Update modal content
            const itemNameSpan = document.getElementById('bookmark-item-name');
            if (itemNameSpan) {
                itemNameSpan.textContent = newsTitle;
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

    // Initialize share buttons
    // Event handler untuk share button sudah ditangani oleh fungsi global di main.blade.php
    // Tidak perlu event handler tambahan di sini karena sudah menggunakan event delegation global

    // Load bookmarks for authenticated users
    document.addEventListener('DOMContentLoaded', function() {
        @auth
        if (isAuthenticated) {
            // Check if toggleBookmark function exists, if not, load it
            if (typeof toggleBookmark === 'undefined') {
                // Load bookmarks and sync bookmark buttons
                fetch('/bookmarks')
                    .then(response => response.json())
                    .then(data => {
                        const bookmarks = data || [];
                        console.log('[News] Loaded bookmarks:', bookmarks.length);
                        bookmarks.forEach(bookmark => {
                            // Use content_type_model (new field name from backend)
                            if (bookmark.content_type_model === 'news') {
                                const buttons = document.querySelectorAll(`.bookmark-btn[data-content-type="news"][data-object-id="${bookmark.object_id}"]`);
                                console.log('[News] Found', buttons.length, 'buttons for bookmark', bookmark.id);
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
        @endauth
    });

    // Toast notification animations
    const style = document.createElement('style');
    style.textContent = `
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
    `;
    document.head.appendChild(style);
</script>
@endpush

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
@endsection
