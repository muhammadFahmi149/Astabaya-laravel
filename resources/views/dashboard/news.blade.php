@extends('layouts.main')

@section('title', 'Berita')

@push('styles')
    @vite(['resources/css/dashboard/news.css'])
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
                                         onclick="showNewsModal({{ $index }})"
                                         onerror="this.onerror=null; this.outerHTML='<div class=\'bg-light rounded d-flex align-items-center justify-content-center shadow-sm\' style=\'width: 100%; height: 200px; min-height: 200px; cursor: pointer;\' onclick=\'showNewsModal({{ $index }})\'><i class=\'bi bi-newspaper\' style=\'font-size: 3rem; color: #ccc;\'></i></div>'">
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
                                <div class="d-flex gap-3 align-items-start mb-3">
                                    @if($item->picture_url)
                                        <img src="{{ $item->picture_url }}" alt="{{ $item->title }}" 
                                             class="rounded shadow-sm" 
                                             style="width: 100px; height: 140px; min-width: 100px; min-height: 140px; object-fit: cover; cursor: pointer; flex-shrink: 0;"
                                             onclick="showNewsModal({{ $index }})"
                                             onerror="this.onerror=null; this.outerHTML='<div class=\'bg-light rounded d-flex align-items-center justify-content-center shadow-sm\' style=\'width: 100px; height: 140px; min-width: 100px; min-height: 140px; cursor: pointer; flex-shrink: 0;\' onclick=\'showNewsModal({{ $index }})\'><i class=\'bi bi-newspaper\' style=\'font-size: 2rem; color: #ccc;\'></i></div>'">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm" 
                                             style="width: 100px; height: 140px; min-width: 100px; min-height: 140px; cursor: pointer; flex-shrink: 0;"
                                             onclick="showNewsModal({{ $index }})">
                                            <i class="bi bi-newspaper" style="font-size: 2rem; color: #ccc;"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <h6 class="card-title mb-1 fw-bold" style="font-size: 0.85rem; line-height: 1.3; cursor: pointer; min-height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" onclick="showNewsModal({{ $index }})">{{ $item->title }}</h6>
                                        <div class="mb-1">
                                            @if($item->category_name)
                                                <span class="badge bg-primary me-1" style="font-size: 0.65rem; padding: 0.2rem 0.4rem;">{{ $item->category_name }}</span>
                                            @endif
                                            @if($item->release_date)
                                                <span class="badge bg-info" style="font-size: 0.65rem; padding: 0.2rem 0.4rem;">
                                                    <i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($item->release_date)->format('d M Y') }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="card-text text-muted mb-2" style="font-size: 0.75rem; line-height: 1.4; min-height: 40px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ \Illuminate\Support\Str::words(strip_tags($item->content ?? ''), 25, '...') }}
                                        </p>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <button class="btn btn-sm btn-primary" onclick="showNewsModal({{ $index }})" style="font-size: 0.75rem; padding: 0.4rem 0.6rem;">
                                                <i class="bi bi-book"></i> Baca
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary bookmark-btn" data-content-type="news" data-object-id="{{ $item->news_id }}" data-bookmark-id="" onclick="event.stopPropagation(); handleNewsBookmark(this)" style="font-size: 0.75rem; padding: 0.4rem 0.6rem;">
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
                    <button type="button" class="btn btn-outline-secondary btn-sm bookmark-btn" id="modalNewsBookmarkBtn" data-content-type="news" data-object-id="" data-bookmark-id="" onclick="handleNewsBookmark(this)">
                        <i class="bi bi-bookmark"></i> <span>Bookmark</span>
                    </button>
                    @include('components.share-button', [
                        'title' => '',
                        'url' => '',
                        'contentType' => 'news',
                        'size' => 'sm',
                        'variant' => 'outline-secondary',
                        'showText' => true,
                        'class' => 'share-news-modal-btn',
                        'id' => 'modalNewsShareBtn'
                    ])
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.ASTABAYA = {
            baseUrl: "{{ url('/') }}",
            isAuthenticated: {{ auth()->check() ? 'true' : 'false' }},
            loginRoute: "{{ route('login') }}"
        };
    </script>
    @vite(['resources/js/dashboard/news.js'])
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
