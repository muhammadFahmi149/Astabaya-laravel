@extends('layouts.main')

@section('title', 'Dashboard')

@push('styles')
@vite([
    'resources/css/dashboard/dashboard.css',
    'resources/css/dashboard/news.css',
    'resources/css/dashboard/publications.css',
    'resources/css/dashboard/infographics.css'
])
@endpush

@section('content')
<div class="dashboard-page">
  <!-- Row 0: Welcome Section -->
  <div class="welcome-section">
    <h2>
      @auth
        Selamat datang {{ explode('@', auth()->user()->username)[0] }}!<br>
      @else
        Selamat Datang Pengguna!<br>
      @endauth
      <small style="font-size: 1rem; opacity: 0.9;">Astabaya Website untuk melihat indikator strategis!</small>
    </h2>
  </div>

  <!-- Summary Cards Carousel -->
  <div class="row mb-4">
    <div class="col-md-12" style="padding:0px;">
      <div class="card">
        <div class="card-body" style="padding: 25px;">
          <div class="indicator-carousel-wrapper" style="position: relative; overflow: hidden; padding: 0;">
            <div class="indicator-carousel-track" id="summaryCardsCarousel" style="display: flex; gap: 15px; will-change: transform;">
              <!-- Cards will be populated by JavaScript -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Row 1: Carousel and Category Buttons -->
  <div class="carousel-section">
    <div class="row">
      <div class="col-lg-8 mb-3 mb-lg-0">
        <div class="carousel-container">
          <div id="contentCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
              @forelse($carouselItems as $index => $item)
              <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <img src="{{ $item['image'] ?? asset('images/default-placeholder.jpg') }}" 
                     alt="{{ $item['title'] ?? 'Item' }}"
                     onerror="this.onerror=null; this.src='{{ asset('images/default-placeholder.jpg') }}';">
                <div class="carousel-overlay">
                  <h5>{{ $item['title'] ?? 'Item' }}</h5>
                  <p>
                    <span class="badge bg-primary">{{ ucfirst($item['type'] ?? 'item') }}</span>
                    @if(!empty($item['date']))
                      <span class="ms-2">{{ \Carbon\Carbon::parse($item['date'])->format('d M Y') }}</span>
                    @endif
                  </p>
                </div>
              </div>
              @empty
              <div class="carousel-item active">
                <div style="width: 100%; height: 400px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                  <div class="text-center text-muted">
                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                    <p class="mt-3">Belum ada data untuk ditampilkan</p>
                  </div>
                </div>
              </div>
              @endforelse
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#contentCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#contentCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="category-buttons">
          <button class="category-btn active" data-type="news" onclick="switchContentType('news')">
            <i class="bi bi-file-earmark-text"></i>
            <span>Berita</span>
          </button>
          <button class="category-btn" data-type="publication" onclick="switchContentType('publication')">
            <i class="bi bi-book"></i>
            <span>Publikasi</span>
          </button>
          <button class="category-btn" data-type="infographic" onclick="switchContentType('infographic')">
            <i class="bi bi-bar-chart-line"></i>
            <span>Infografis</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Row 2: Indicator Cards -->
  <div class="indicator-cards-section">
    <h4 class="mb-3">Indikator Strategis</h4>
    <div class="indicator-cards-wrapper">
      <button class="indicator-scroll-btn prev" onclick="scrollIndicators('prev')">
        <i class="bi bi-chevron-left"></i>
      </button>
      <div class="indicator-cards-container" id="indicatorCardsContainer">
        <a href="{{ route('dashboard') }}" class="indicator-card">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
        <a href="{{ route('inflasi') }}" class="indicator-card">
          <i class="bi bi-graph-up-arrow"></i>
          <span>Inflasi</span>
        </a>
        <a href="{{ route('pdrb-pengeluaran') }}" class="indicator-card">
          <i class="bi bi-cash-stack"></i>
          <span>PDRB Pengeluaran</span>
        </a>
        <a href="{{ route('pdrb-lapangan-usaha') }}" class="indicator-card">
          <i class="bi bi-cash-stack"></i>
          <span>PDRB Lapangan Usaha</span>
        </a>
        <a href="{{ route('kemiskinan') }}" class="indicator-card">
          <i class="bi bi-heart-pulse"></i>
          <span>Kemiskinan</span>
        </a>
        <a href="{{ route('kependudukan') }}" class="indicator-card">
          <i class="bi bi-people"></i>
          <span>Kependudukan</span>
        </a>
        <a href="{{ route('ketenagakerjaan') }}" class="indicator-card">
          <i class="bi bi-briefcase"></i>
          <span>Ketenagakerjaan</span>
        </a>
        <a href="{{ route('hotel-occupancy') }}" class="indicator-card">
          <i class="bi bi-luggage"></i>
          <span>Tingkat Hunian Hotel</span>
        </a>
        <a href="{{ route('indeks-pembangunan-manusia') }}" class="indicator-card">
          <i class="bi bi-buildings"></i>
          <span>IPM</span>
        </a>
        <a href="{{ route('ipm-uhh-sp') }}" class="indicator-card">
          <i class="bi bi-buildings"></i>
          <span>UHH SP</span>
        </a>
        <a href="{{ route('ipm-hls') }}" class="indicator-card">
          <i class="bi bi-buildings"></i>
          <span>HLS</span>
        </a>
        <a href="{{ route('ipm-rls') }}" class="indicator-card">
          <i class="bi bi-buildings"></i>
          <span>RLS</span>
        </a>
        <a href="{{ route('ipm-pengeluaran-per-kapita') }}" class="indicator-card">
          <i class="bi bi-buildings"></i>
          <span>Pengeluaran per Kapita</span>
        </a>
        <a href="{{ route('ipm-indeks-kesehatan') }}" class="indicator-card">
          <i class="bi bi-buildings"></i>
          <span>Indeks Kesehatan</span>
        </a>
        <a href="{{ route('ipm-indeks-hidup-layak') }}" class="indicator-card">
          <i class="bi bi-buildings"></i>
          <span>Indeks Hidup Layak</span>
        </a>
        <a href="{{ route('ipm-indeks-pendidikan') }}" class="indicator-card">
          <i class="bi bi-buildings"></i>
          <span>Indeks Pendidikan</span>
        </a>
        <a href="{{ route('gini-ratio') }}" class="indicator-card">
          <i class="bi bi-percent"></i>
          <span>Gini ratio</span>
        </a>
      </div>
      <button class="indicator-scroll-btn next" onclick="scrollIndicators('next')">
        <i class="bi bi-chevron-right"></i>
      </button>
    </div>
  </div>

  <!-- Row 3: Content Cards (News/Publications/Infographics) -->
  <div class="content-cards-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 id="contentSectionTitle">Berita</h4>
      <a href="{{ route('news') }}" id="viewMoreLink" class="btn btn-sm btn-outline-primary">
        Lihat Selengkapnya <i class="bi bi-arrow-right"></i>
      </a>
    </div>
    <div class="content-cards-container" id="contentCardsContainer">
      <!-- News Cards (Default) -->
      <div id="newsCards">
        @forelse($latestNews as $news)
        <div class="content-card" onclick="showNewsModal({{ $news->news_id ?? $news->id }})">
          @if($news->picture_url)
          <div class="news-image-wrapper">
            <img src="{{ $news->picture_url }}" alt="{{ $news->title }}"
                 class="news-image"
                 onerror="this.onerror=null; this.src='{{ asset('images/default-placeholder.jpg') }}';">
          </div>
          @else
          <div style="width: 100%; height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-file-earmark-text" style="font-size: 3rem; color: #ccc;"></i>
          </div>
          @endif
          <div class="content-card-body">
            <h6 class="content-card-title">{{ $news->title }}</h6>
            <div class="content-card-meta">
              @if($news->category_name)
              <span class="badge bg-primary">{{ $news->category_name }}</span>
              @endif
              @if($news->release_date)
              <span class="badge bg-info">
                <i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($news->release_date)->format('d M Y') }}
              </span>
              @endif
            </div>
            <p class="content-card-text">
              {{ \Illuminate\Support\Str::words(strip_tags($news->content ?? ''), 20, '...') }}
            </p>
          </div>
          <div class="content-card-footer">
            <button class="btn btn-sm btn-primary" onclick="event.stopPropagation(); showNewsModal({{ $news->news_id ?? $news->id }})">
              <i class="bi bi-book"></i> Baca Selengkapnya
            </button>
          </div>
        </div>
        @empty
        <div class="col-12">
          <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> Belum ada data berita.
          </div>
        </div>
        @endforelse
      </div>

      <!-- Publication Cards (Hidden by default) -->
      <div id="publicationCards" style="display: none;">
        @forelse($latestPublications as $publication)
        <div class="content-card" onclick="showPublicationModal({{ $publication->id }})">
          @if($publication->image)
          <div class="publication-image-wrapper">
            <img src="{{ $publication->image }}" alt="{{ $publication->title }}"
                 class="publication-image"
                 onerror="this.onerror=null; this.src='{{ asset('images/default-placeholder.jpg') }}';">
          </div>
          @else
          <div style="width: 100%; height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-book" style="font-size: 3rem; color: #ccc;"></i>
          </div>
          @endif
          <div class="content-card-body">
            <h6 class="content-card-title">{{ $publication->title }}</h6>
            <div class="content-card-meta">
              @if($publication->date)
              <span class="badge bg-info">
                <i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($publication->date)->format('d M Y') }}
              </span>
              @endif
              @if($publication->size)
              <span class="badge bg-secondary">
                <i class="bi bi-file-earmark-pdf"></i> {{ $publication->size }}
              </span>
              @endif
            </div>
            <p class="content-card-text">
              {{ \Illuminate\Support\Str::words($publication->abstract ?? '', 20, '...') }}
            </p>
          </div>
          <div class="content-card-footer">
            <button class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation(); showPublicationModal({{ $publication->id }})">
              <i class="bi bi-eye"></i> Detail
            </button>
            <button class="btn btn-sm btn-primary download-publication-btn" 
                    data-pub-id="{{ $publication->pub_id ?? $publication->id }}"
                    data-pub-title="{{ e($publication->title ?? 'Publikasi') }}"
                    onclick="event.stopPropagation(); handlePublicationDownload(this)">
              <i class="bi bi-download"></i> Unduh PDF
            </button>
          </div>
        </div>
        @empty
        <div class="col-12">
          <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> Belum ada data publikasi.
          </div>
        </div>
        @endforelse
      </div>

      <!-- Infographic Cards (Hidden by default) -->
      <div id="infographicCards" style="display: none;">
        @forelse($latestInfographics as $infographic)
        <div class="content-card" onclick="showInfographicDetail({{ $infographic->id }})">
          @if($infographic->image)
          <div class="infographic-image-wrapper">
            <img src="{{ $infographic->image }}" alt="{{ $infographic->title }}"
                 class="infographic-image"
                 onerror="this.onerror=null; this.src='{{ asset('images/default-placeholder.jpg') }}';">
          </div>
          @else
          <div style="width: 100%; height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-bar-chart-line" style="font-size: 3rem; color: #ccc;"></i>
          </div>
          @endif
          <div class="content-card-body">
            <h6 class="content-card-title">{{ $infographic->title }}</h6>
            <div class="content-card-meta">
              <span class="badge bg-primary">Infografis</span>
            </div>
          </div>
          <div class="content-card-footer">
            <button class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation(); showInfographicDetail({{ $infographic->id }})">
              <i class="bi bi-eye"></i> Lihat
            </button>
            <button class="btn btn-sm btn-primary download-infographic-btn" 
                    data-infographic-id="{{ $infographic->id }}"
                    data-infographic-title="{{ e($infographic->title ?? 'Infografis') }}"
                    onclick="event.stopPropagation(); handleInfographicDownload(this)">
              <i class="bi bi-download"></i> Unduh
            </button>
          </div>
        </div>
        @empty
        <div class="col-12">
          <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> Belum ada data infografis.
          </div>
        </div>
        @endforelse
      </div>
    </div>
  </div>
</div>

@include('components.news-modal')
@include('components.publication-modal')
@include('components.infographic-modal')

@push('scripts')
<script>
window.DASHBOARD_CONFIG = {
    routes: {
        news: '{{ route("news") }}',
        publications: '{{ route("publications") }}',
        infographics: '{{ route("infographics") }}',
        login: '{{ route("login") }}',
        downloadPublication: '{{ route("download-publication", ":id") }}',
        downloadInfographic: '{{ route("download-infographic", ":id") }}'
    },
    carouselData: {
        news: @json($carouselNews ?? []),
        publications: @json($carouselPublications ?? []),
        infographics: @json($carouselInfographics ?? [])
    },
    apiBase: '{{ url("/api/v1") }}',
    isAuthenticated: @auth true @else false @endauth,
    defaultPlaceholderImg: '{{ asset("images/default-placeholder.jpg") }}'
};
</script>
@vite('resources/js/dashboard/dashboard.js')
@endpush


@endsection
