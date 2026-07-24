@extends('layouts.main')

@section('title', 'Publikasi')

@section('content')
<div class="publications-page">
  <!-- Page Header -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h3 class="font-weight-bold mb-2">Publikasi BPS Kota Surabaya</h3>
          <p class="text-muted mb-0">
            @if(isset($selected_year) || isset($search_query))
              Menampilkan: <span class="badge bg-primary">{{ $filtered_count ?? 0 }}</span> dari <span class="badge bg-secondary">{{ $countPublication ?? 0 }}</span> publikasi
              @if(isset($selected_year))(Tahun {{ $selected_year }})@endif
            @else
              Total: <span class="badge bg-primary">{{ $countPublication ?? 0 }}</span> publikasi
            @endif
          </p>
        </div>
        <button class="btn btn-primary" onclick="refreshData()"><i class="bi bi-arrow-clockwise"></i> Refresh Data</button>
      </div>
    </div>
  </div>

  <!-- Filter & Search - Better Layout -->
  <div class="row mb-4">
    <div class="col-md-8 mb-3 mb-md-0">
      <div class="search-input-wrapper shadow-sm">
        <input type="text" class="form-control" id="searchInput" placeholder="Cari publikasi berdasarkan judul atau abstrak..." value="{{ $search_query ?? '' }}" onkeypress="handleSearchKeyPress(event)" />
        <span class="search-icon">
          <i class="bi bi-search text-muted"></i>
        </span>
        <button type="button" class="search-button" onclick="performSearch()">
          <i class="bi bi-search"></i> Cari
        </button>
      </div>
    </div>
    <div class="col-md-4">
      <div class="justify-content-end d-flex">
        <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
          <button class="btn btn-sm btn-light bg-white dropdown-toggle shadow-sm" type="button" id="yearFilterButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="bi bi-calendar"></i> <span id="yearFilterText">@if(isset($selected_year)){{ $selected_year }}@else Semua Tahun @endif</span>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="yearFilterButton" id="yearFilterMenu" style="max-height: calc(5 * 2.5rem); overflow-y: auto;">
            <a class="dropdown-item @if(!isset($selected_year)) active @endif" href="#" data-year="">Semua Tahun</a>
            @if(isset($available_years))
              @foreach($available_years as $year)
              <a class="dropdown-item @if(isset($selected_year) && $selected_year == strval($year)) active @endif" href="#" data-year="{{ $year }}">{{ $year }}</a>
              @endforeach
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Publications List -->
  <div class="row">
    @if(isset($dataPublication) && $dataPublication->count() > 0)
      @foreach($dataPublication as $index => $publication)
      <div class="col-12 mb-4 publication-item" data-year="{{ $publication->date ? \Carbon\Carbon::parse($publication->date)->format('Y') : '' }}">
        <div class="card hover-card">
          <div class="card-body">
            <!-- Mobile Layout (Book-like) -->
            <div class="d-md-none">
              <div class="row g-3 mb-3">
                <!-- Cover Image (Left) -->
                <div class="col-auto">
                  <img
                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 150 200'%3E%3Crect fill='%23f0f0f0' width='150' height='200'/%3E%3Ctext fill='%23999' x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-size='14' font-family='Arial'%3ELoading...%3C/text%3E%3C/svg%3E"
                    data-src="{{ $publication->image ?? '' }}"
                    alt="{{ $publication->title ?? '' }}"
                    class="rounded shadow-sm lazy-load publication-thumbnail-mobile"
                    style="width: 100px; height: 140px; object-fit: cover; cursor: pointer"
                    loading="lazy"
                    data-pub-id="{{ e($publication->pub_id ?? '') }}"
                    data-index="{{ $index }}"
                    onclick="showModal(this.dataset.pubId, this.dataset.index)"
                  />
                </div>

                <!-- Publication Info (Right) -->
                <div class="col">
                  <h6 class="card-title mb-2 fw-bold" style="font-size: 0.9rem; line-height: 1.3">{{ $publication->title ?? '' }}</h6>
                  
                  <div class="mb-2">
                    <small class="text-muted d-flex align-items-center mb-1" style="font-size: 0.75rem">
                      <i class="bi bi-people me-1" style="font-size: 0.8rem"></i>
                      <span>BPS Kota Surabaya</span>
                    </small>
                  </div>

                  <p class="card-text text-muted mb-2" style="font-size: 0.8rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 3; line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden">
                    {{ \Illuminate\Support\Str::words($publication->abstract ?? '', 25, '...') }}
                  </p>

                  <div class="mb-2">
                    <small class="text-muted d-flex flex-wrap align-items-center gap-2" style="font-size: 0.7rem">
                      <span>PDF</span>
                      <span>·</span>
                      <span>bahasa Indonesia</span>
                      @if($publication->date)
                      <span>·</span>
                      <span>{{ \Carbon\Carbon::parse($publication->date)->format('Y') }}</span>
                      @endif
                      @if($publication->size)
                      <span>·</span>
                      <span>{{ $publication->size }}</span>
                      @endif
                    </small>
                  </div>
                </div>
              </div>

              <!-- Action Buttons (Bottom) -->
              <div class="row g-2 publication-action-buttons">
                <div class="col-3">
                  <button class="btn btn-light w-100 publication-action-btn d-flex flex-column align-items-center justify-content-center" data-pub-id="{{ e($publication->pub_id ?? '') }}" data-index="{{ $index }}" onclick="showModal(this.dataset.pubId, this.dataset.index)">
                    <i class="bi bi-book publication-action-icon"></i>
                    <span class="publication-action-text">Baca</span>
                  </button>
                </div>
                <div class="col-3">
                  <a href="{{ route('api.publications.download', $publication->pub_id ?? $publication->id) }}" target="_blank" class="btn btn-light w-100 publication-action-btn download-publication-btn d-flex flex-column align-items-center justify-content-center" style="text-decoration: none" data-pub-id="{{ $publication->pub_id ?? $publication->id }}" data-pub-title="{{ e($publication->title ?? '') }}">
                    <i class="bi bi-download text-primary mb-1" style="font-size: 1.25rem"></i>
                    <span class="small">Unduh</span>
                  </a>
                </div>
                <div class="col-3">
                  @include('components.share-button', [
                      'title' => $publication->title ?? '',
                      'url' => route('publications') . '?publication=' . $publication->id,
                      'contentType' => 'publication',
                      'size' => 'sm',
                      'variant' => 'light',
                      'showText' => true,
                      'iconClass' => 'bi bi-share publication-action-icon',
                      'textClass' => 'publication-action-text',
                      'class' => 'w-100 publication-action-btn d-flex flex-column align-items-center justify-content-center'
                  ])
                </div>
                <div class="col-3">
                  <button class="btn btn-light w-100 publication-action-btn bookmark-btn d-flex flex-column align-items-center justify-content-center" data-content-type="publication" data-object-id="{{ $publication->id }}" data-bookmark-id="" onclick="handlePublicationBookmark(this)">
                    <i class="bi bi-bookmark publication-action-icon"></i>
                    <span class="publication-action-text">Bookmark</span>
                  </button>
                </div>
              </div>
            </div>

            <!-- Desktop Layout (Horizontal) -->
            <div class="row g-3 align-items-center d-none d-md-flex">
              <!-- Cover Image -->
              <div class="col-md-3 col-lg-2 text-center">
                <img
                  src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 150 200'%3E%3Crect fill='%23f0f0f0' width='150' height='200'/%3E%3Ctext fill='%23999' x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-size='14' font-family='Arial'%3ELoading...%3C/text%3E%3C/svg%3E"
                  data-src="{{ $publication->image ?? '' }}"
                  alt="{{ $publication->title ?? '' }}"
                  class="img-fluid rounded shadow-sm lazy-load publication-thumbnail"
                  style="max-height: 150px; width: auto; cursor: pointer"
                  loading="lazy"
                  data-pub-id="{{ e($publication->pub_id ?? '') }}"
                  data-index="{{ $index }}"
                  onclick="showModal(this.dataset.pubId, this.dataset.index)"
                />
              </div>

              <!-- Publication Info -->
              <div class="col-md-6 col-lg-7">
                <h5 class="card-title mb-2">{{ $publication->title ?? '' }}</h5>

                <div class="mb-2 publication-badges">
                  @if($publication->date)
                  <span class="badge bg-info me-2"> <i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($publication->date)->format('d M Y') }} </span>
                  @endif
                  @if($publication->size)
                  <span class="badge bg-secondary"> <i class="bi bi-file-earmark-pdf"></i> {{ $publication->size }} </span>
                  @endif
                </div>

                <p class="card-text text-muted mb-0">{{ \Illuminate\Support\Str::words($publication->abstract ?? '', 30, '...') }}</p>
              </div>

              <!-- Actions -->
              <div class="col-md-3 col-lg-3">
                <div class="d-flex flex-column gap-2">
                  <button class="btn btn-outline-primary btn-sm" data-pub-id="{{ e($publication->pub_id ?? '') }}" data-index="{{ $index }}" onclick="showModal(this.dataset.pubId, this.dataset.index)"><i class="bi bi-eye"></i> Rincian</button>
                  <a href="{{ route('api.publications.download', $publication->pub_id ?? $publication->id) }}" target="_blank" class="btn btn-primary btn-sm download-publication-btn" data-pub-id="{{ $publication->pub_id ?? $publication->id }}" data-pub-title="{{ e($publication->title ?? '') }}"> <i class="bi bi-download"></i> Unduh PDF </a>
                  <button class="btn btn-outline-secondary btn-sm bookmark-btn" data-content-type="publication" data-object-id="{{ $publication->id }}" data-bookmark-id="" onclick="handlePublicationBookmark(this)">
                    <i class="bi bi-bookmark"></i> <span>Bookmark</span>
                  </button>
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
        <i class="bi bi-info-circle"></i> Belum ada data publikasi.
      </div>
    </div>
    @endif
  </div>

  <!-- Pagination -->
  @if(isset($dataPublication) && is_object($dataPublication) && method_exists($dataPublication, 'hasPages') && $dataPublication->hasPages())
  <nav aria-label="Page navigation" class="mt-4">
    <ul class="pagination justify-content-center">
      @if($dataPublication->onFirstPage())
      <li class="page-item disabled">
        <span class="page-link"><i class="bi bi-chevron-left"></i> Previous</span>
      </li>
      @else
      <li class="page-item">
        <a class="page-link" href="{{ $dataPublication->appends(request()->query())->previousPageUrl() }}"> <i class="bi bi-chevron-left"></i> Previous </a>
      </li>
      @endif

      @foreach($dataPublication->getUrlRange(max(1, $dataPublication->currentPage() - 2), min($dataPublication->lastPage(), $dataPublication->currentPage() + 2)) as $page => $url)
        @if($page == $dataPublication->currentPage())
        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
        @else
        <li class="page-item"><a class="page-link" href="{{ $dataPublication->appends(request()->query())->url($page) }}">{{ $page }}</a></li>
        @endif
      @endforeach

      @if($dataPublication->hasMorePages())
      <li class="page-item">
        <a class="page-link" href="{{ $dataPublication->appends(request()->query())->nextPageUrl() }}"> Next <i class="bi bi-chevron-right"></i> </a>
      </li>
      @else
      <li class="page-item disabled">
        <span class="page-link">Next <i class="bi bi-chevron-right"></i></span>
      </li>
      @endif
    </ul>
    <p class="text-center text-muted small">Showing {{ $dataPublication->firstItem() ?? 0 }} to {{ $dataPublication->lastItem() ?? 0 }} of {{ $filtered_count ?? $dataPublication->total() }} publications</p>
  </nav>
  @endif
</div>

@include('components.publication-modal')



<!-- Hidden data container for publications -->
<div id="publicationsDataContainer" style="display: none">
  @if(isset($dataPublication) && $dataPublication->count() > 0)
    @foreach($dataPublication as $publication)
    <div
      class="publication-data"
      data-title="{{ e($publication->title ?? '') }}"
      data-image="{{ e($publication->image ?? '') }}"
      data-date="{{ $publication->date ? \Carbon\Carbon::parse($publication->date)->format('d M Y') : 'N/A' }}"
      data-size="{{ $publication->size ?? 'N/A' }}"
      data-pub-id="{{ e($publication->pub_id ?? '') }}"
      data-id="{{ $publication->id }}"
      data-abstract="{{ e($publication->abstract ?? '') }}"
      data-download="{{ e($publication->download_url ?? $publication->dl ?? '') }}"
    ></div>
    @endforeach
  @endif
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
@endsection


@push('styles')
    @vite(['resources/css/dashboard/publications.css'])
@endpush

@push('scripts')
    <script>
        window.ASTABAYA = {
            isAuthenticated: {{ auth()->check() ? 'true' : 'false' }},
            routes: {
                login: '{{ route("login") }}'
            },
            csrfToken: '{{ csrf_token() }}',
            baseUrl: '{{ url("/") }}'
        };
    </script>
    @vite(['resources/js/dashboard/publications.js'])
@endpush

