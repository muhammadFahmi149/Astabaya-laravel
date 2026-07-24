@extends('layouts.main')

@section('title', 'Infografis')

@push('styles')
@vite('resources/css/dashboard/infographics.css')
@endpush

@push('scripts')
<script>
    window.ASTABAYA = window.ASTABAYA || {};
    window.ASTABAYA.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
    window.ASTABAYA.baseUrl = '{{ url("/") }}';
    window.ASTABAYA.apiBase = '{{ url("/api/v1") }}';
</script>
@vite('resources/js/dashboard/infographics.js')
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

@include('components.infographic-modal')
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


@endsection




