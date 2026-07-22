<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startPush('styles'); ?>
<style>
  /* Welcome Section */
  .welcome-section {
    background: #234C6A;
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
  }

  .welcome-section h2 {
    margin: 0;
    font-size: 1.75rem;
    font-weight: 600;
  }

  /* Carousel Section */
  .carousel-section {
    margin-bottom: 2rem;
  }

  .carousel-container {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    height: 400px;
  }

  .carousel-item img {
    width: 100%;
    height: 400px;
    object-fit: cover;
  }

  /* Carousel image wrapper for publications and infographics */
  .carousel-item .carousel-image-wrapper {
    width: 100%;
    height: 400px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    overflow: hidden;
  }

  .carousel-item img.carousel-publication-image,
  .carousel-item img.carousel-infographic-image {
    width: auto;
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    object-position: center;
    display: block;
    border-radius: 0.375rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  }

  .carousel-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
    padding: 2rem;
    color: white;
  }

  .carousel-overlay h5 {
    margin: 0 0 0.5rem 0;
    font-weight: 600;
  }

  .carousel-overlay p {
    margin: 0;
    font-size: 0.9rem;
    opacity: 0.9;
  }

  /* Category Buttons */
  .category-buttons {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    height: 100%;
  }

  .category-btn {
    flex: 1;
    padding: 1.5rem;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    background: white;
    transition: all 0.3s ease;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
  }

  .category-btn:hover {
    border-color: #234C6A;
    background: #f8f9ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
  }

  .category-btn.active {
    border-color: #234C6A;
    background: #234C6A;
    color: white;
  }

  .category-btn i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
  }

  .category-btn span {
    font-weight: 600;
    font-size: 1rem;
  }

  /* Indicator Cards Section */
  .indicator-cards-section {
    margin-bottom: 2rem;
  }

  .indicator-cards-wrapper {
    position: relative;
  }

  .indicator-cards-container {
    display: flex;
    gap: 1rem;
    overflow-x: auto;
    scroll-behavior: smooth;
    padding: 1rem 0;
    scrollbar-width: none;
    -ms-overflow-style: none;
  }

  .indicator-cards-container::-webkit-scrollbar {
    display: none;
  }

  .indicator-card {
    min-width: 150px;
    width: 150px;
    height: 150px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    background: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
  }

  .indicator-card:hover {
    border-color: #667eea;
    background: #f8f9ff;
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.2);
    color: inherit;
    text-decoration: none;
  }

  .indicator-card i {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    color: #667eea;
  }

  .indicator-card span {
    font-weight: 600;
    font-size: 0.9rem;
    line-height: 1.2;
  }

  .indicator-scroll-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: white;
    border: 2px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }

  .indicator-scroll-btn:hover {
    background: #667eea;
    border-color: #667eea;
    color: white;
  }

  .indicator-scroll-btn.prev {
    left: -20px;
  }

  .indicator-scroll-btn.next {
    right: -20px;
  }

  .indicator-scroll-btn:disabled {
    opacity: 0.3;
    cursor: not-allowed;
  }

  /* Content Cards Section */
  .content-cards-section {
    margin-bottom: 2rem;
  }

  .content-cards-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    align-items: stretch;
  }

  .content-card {
    border: 1px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
    background: white;
    transition: all 0.3s ease;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    height: 100%;
  }

  .content-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    border-color: #667eea;
  }

  .content-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    flex-shrink: 0;
  }

  .content-card .publication-image-wrapper {
    width: 100%;
    min-height: 200px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    overflow: hidden;
  }

  .content-card img.publication-image {
    width: auto;
    max-width: 100%;
    max-height: 200px;
    object-fit: contain;
    object-position: center;
    display: block;
    border-radius: 0.375rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  }

  .content-card .infographic-image-wrapper {
    width: 100%;
    min-height: 200px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    overflow: hidden;
  }

  .content-card img.infographic-image {
    width: auto;
    max-width: 100%;
    max-height: 200px;
    object-fit: contain;
    object-position: center;
    display: block;
    border-radius: 0.375rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  }

  .content-card-body {
    padding: 1.25rem;
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .content-card-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .content-card-meta {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
    flex-wrap: wrap;
  }

  .content-card-meta .badge {
    font-size: 0.75rem;
  }

  .content-card-text {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
  }

  .content-card-footer {
    padding: 0.75rem 1.25rem;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    display: flex;
    gap: 0.5rem;
    margin-top: auto;
    flex-shrink: 0;
  }

  .content-card-footer .btn {
    flex: 1;
    font-size: 0.875rem;
  }

  /* Summary Cards Carousel Styles */
  .indicator-carousel-wrapper {
    overflow: hidden !important;
  }

  .indicator-carousel-track {
    display: flex !important;
    gap: 15px;
    will-change: transform;
  }

  .indicator-carousel-content {
    display: flex;
    gap: 15px;
    flex-shrink: 0;
    min-width: fit-content;
  }

  .summary-card-carousel {
    min-width: 240px;
    background: white;
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    min-height: 140px;
  }

  .summary-card-carousel h6 {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 8px;
    font-weight: 500;
  }

  .summary-card-carousel h3 {
    font-size: 22px;
    font-weight: 700;
    color: white;
    margin-bottom: 6px;
    line-height: 1.2;
  }

  .summary-card-carousel .comparison {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 4px;
    flex-wrap: wrap;
  }

  .summary-card-carousel small {
    color: rgba(255, 255, 255, 0.8);
    font-size: 11px;
    margin-top: auto;
  }

  @media (max-width: 768px) {
    .welcome-section {
      padding: 1.5rem;
    }

    .welcome-section h2 {
      font-size: 1.5rem;
    }

    .summary-card-carousel {
      min-width: 200px;
      padding: 12px;
    }

    .summary-card-carousel h3 {
      font-size: 20px;
    }

    .carousel-container {
      height: 300px;
    }

    .carousel-item img {
      height: 300px;
    }

    .carousel-item .carousel-image-wrapper {
      height: 300px;
    }

    .category-buttons {
      flex-direction: row;
      margin-top: 1rem;
    }

    .category-btn {
      flex: 1;
      padding: 1rem;
    }

    .category-btn i {
      font-size: 1.5rem;
    }

    .indicator-card {
      min-width: 120px;
      width: 120px;
      height: 120px;
    }

    .indicator-card i {
      font-size: 2rem;
    }

    .indicator-scroll-btn {
      width: 35px;
      height: 35px;
    }

    .indicator-scroll-btn.prev {
      left: -15px;
    }

    .indicator-scroll-btn.next {
      right: -15px;
    }

    .content-cards-container {
      grid-template-columns: 1fr;
    }
  }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-page">
  <!-- Row 0: Welcome Section -->
  <div class="welcome-section">
    <h2>
      <?php if(auth()->guard()->check()): ?>
        Selamat datang <?php echo e(auth()->user()->username); ?>!<br>
      <?php else: ?>
        Selamat Datang Pengguna!<br>
      <?php endif; ?>
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
              <?php $__empty_1 = true; $__currentLoopData = $carouselItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?>">
                <img src="<?php echo e($item['image'] ?? asset('images/default-placeholder.jpg')); ?>" 
                     alt="<?php echo e($item['title'] ?? 'Item'); ?>"
                     onerror="this.src='<?php echo e(asset('images/default-placeholder.jpg')); ?>'">
                <div class="carousel-overlay">
                  <h5><?php echo e($item['title'] ?? 'Item'); ?></h5>
                  <p>
                    <span class="badge bg-primary"><?php echo e(ucfirst($item['type'] ?? 'item')); ?></span>
                    <?php if(!empty($item['date'])): ?>
                      <span class="ms-2"><?php echo e(\Carbon\Carbon::parse($item['date'])->format('d M Y')); ?></span>
                    <?php endif; ?>
                  </p>
                </div>
              </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <div class="carousel-item active">
                <div style="width: 100%; height: 400px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                  <div class="text-center text-muted">
                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                    <p class="mt-3">Belum ada data untuk ditampilkan</p>
                  </div>
                </div>
              </div>
              <?php endif; ?>
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
        <a href="<?php echo e(route('dashboard')); ?>" class="indicator-card">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
        <a href="<?php echo e(route('inflasi')); ?>" class="indicator-card">
          <i class="bi bi-graph-up-arrow"></i>
          <span>Inflasi</span>
        </a>
        <a href="<?php echo e(route('pdrb-pengeluaran')); ?>" class="indicator-card">
          <i class="bi bi-cash-stack"></i>
          <span>PDRB Pengeluaran</span>
        </a>
        <a href="<?php echo e(route('pdrb-lapangan-usaha')); ?>" class="indicator-card">
          <i class="bi bi-cash-stack"></i>
          <span>PDRB Lapangan Usaha</span>
        </a>
        <a href="<?php echo e(route('kemiskinan')); ?>" class="indicator-card">
          <i class="bi bi-heart-pulse"></i>
          <span>Kemiskinan</span>
        </a>
        <a href="<?php echo e(route('kependudukan')); ?>" class="indicator-card">
          <i class="bi bi-people"></i>
          <span>Kependudukan</span>
        </a>
        <a href="<?php echo e(route('ketenagakerjaan')); ?>" class="indicator-card">
          <i class="bi bi-briefcase"></i>
          <span>Ketenagakerjaan</span>
        </a>
        <a href="<?php echo e(route('hotel-occupancy')); ?>" class="indicator-card">
          <i class="bi bi-luggage"></i>
          <span>Tingkat Hunian Hotel</span>
        </a>
        <a href="<?php echo e(route('indeks-pembangunan-manusia')); ?>" class="indicator-card">
          <i class="bi bi-buildings"></i>
          <span>IPM</span>
        </a>
        <a href="<?php echo e(route('ipm-uhh-sp')); ?>" class="indicator-card">
          <i class="bi bi-buildings"></i>
          <span>UHH SP</span>
        </a>
        <a href="<?php echo e(route('ipm-hls')); ?>" class="indicator-card">
          <i class="bi bi-buildings"></i>
          <span>HLS</span>
        </a>
        <a href="<?php echo e(route('ipm-rls')); ?>" class="indicator-card">
          <i class="bi bi-buildings"></i>
          <span>RLS</span>
        </a>
        <a href="<?php echo e(route('ipm-pengeluaran-per-kapita')); ?>" class="indicator-card">
          <i class="bi bi-buildings"></i>
          <span>Pengeluaran per Kapita</span>
        </a>
        <a href="<?php echo e(route('ipm-indeks-kesehatan')); ?>" class="indicator-card">
          <i class="bi bi-buildings"></i>
          <span>Indeks Kesehatan</span>
        </a>
        <a href="<?php echo e(route('ipm-indeks-hidup-layak')); ?>" class="indicator-card">
          <i class="bi bi-buildings"></i>
          <span>Indeks Hidup Layak</span>
        </a>
        <a href="<?php echo e(route('ipm-indeks-pendidikan')); ?>" class="indicator-card">
          <i class="bi bi-buildings"></i>
          <span>Indeks Pendidikan</span>
        </a>
        <a href="<?php echo e(route('gini-ratio')); ?>" class="indicator-card">
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
      <a href="<?php echo e(route('news')); ?>" id="viewMoreLink" class="btn btn-sm btn-outline-primary">
        Lihat Selengkapnya <i class="bi bi-arrow-right"></i>
      </a>
    </div>
    <div class="content-cards-container" id="contentCardsContainer">
      <!-- News Cards (Default) -->
      <div id="newsCards">
        <?php $__empty_1 = true; $__currentLoopData = $latestNews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $news): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="content-card" onclick="window.location.href='<?php echo e(route('news')); ?>'">
          <?php if($news->picture_url): ?>
          <img src="<?php echo e($news->picture_url); ?>" alt="<?php echo e($news->title); ?>" 
               onerror="this.src='<?php echo e(asset('images/default-placeholder.jpg')); ?>'">
          <?php else: ?>
          <div style="width: 100%; height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-file-earmark-text" style="font-size: 3rem; color: #ccc;"></i>
          </div>
          <?php endif; ?>
          <div class="content-card-body">
            <h6 class="content-card-title"><?php echo e($news->title); ?></h6>
            <div class="content-card-meta">
              <?php if($news->category_name): ?>
              <span class="badge bg-primary"><?php echo e($news->category_name); ?></span>
              <?php endif; ?>
              <?php if($news->release_date): ?>
              <span class="badge bg-info">
                <i class="bi bi-calendar"></i> <?php echo e(\Carbon\Carbon::parse($news->release_date)->format('d M Y')); ?>

              </span>
              <?php endif; ?>
            </div>
            <p class="content-card-text">
              <?php echo e(\Illuminate\Support\Str::words(strip_tags($news->content ?? ''), 20, '...')); ?>

            </p>
          </div>
          <div class="content-card-footer">
            <a href="<?php echo e(route('news')); ?>" class="btn btn-sm btn-primary">
              <i class="bi bi-book"></i> Baca Selengkapnya
            </a>
          </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
          <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> Belum ada data berita.
          </div>
        </div>
        <?php endif; ?>
      </div>

      <!-- Publication Cards (Hidden by default) -->
      <div id="publicationCards" style="display: none;">
        <?php $__empty_1 = true; $__currentLoopData = $latestPublications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $publication): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="content-card" onclick="showPublicationModal(<?php echo e($publication->id); ?>)">
          <?php if($publication->image): ?>
          <div class="publication-image-wrapper">
            <img src="<?php echo e($publication->image); ?>" alt="<?php echo e($publication->title); ?>"
                 class="publication-image"
                 onerror="this.src='<?php echo e(asset('images/default-placeholder.jpg')); ?>'">
          </div>
          <?php else: ?>
          <div style="width: 100%; height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-book" style="font-size: 3rem; color: #ccc;"></i>
          </div>
          <?php endif; ?>
          <div class="content-card-body">
            <h6 class="content-card-title"><?php echo e($publication->title); ?></h6>
            <div class="content-card-meta">
              <?php if($publication->date): ?>
              <span class="badge bg-info">
                <i class="bi bi-calendar"></i> <?php echo e(\Carbon\Carbon::parse($publication->date)->format('d M Y')); ?>

              </span>
              <?php endif; ?>
              <?php if($publication->size): ?>
              <span class="badge bg-secondary">
                <i class="bi bi-file-earmark-pdf"></i> <?php echo e($publication->size); ?>

              </span>
              <?php endif; ?>
            </div>
            <p class="content-card-text">
              <?php echo e(\Illuminate\Support\Str::words($publication->abstract ?? '', 20, '...')); ?>

            </p>
          </div>
          <div class="content-card-footer">
            <button class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation(); showPublicationModal(<?php echo e($publication->id); ?>)">
              <i class="bi bi-eye"></i> Detail
            </button>
            <button class="btn btn-sm btn-primary download-publication-btn" 
                    data-pub-id="<?php echo e($publication->pub_id ?? $publication->id); ?>"
                    data-pub-title="<?php echo e(e($publication->title ?? 'Publikasi')); ?>"
                    onclick="event.stopPropagation(); handlePublicationDownload(this)">
              <i class="bi bi-download"></i> Unduh PDF
            </button>
          </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
          <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> Belum ada data publikasi.
          </div>
        </div>
        <?php endif; ?>
      </div>

      <!-- Infographic Cards (Hidden by default) -->
      <div id="infographicCards" style="display: none;">
        <?php $__empty_1 = true; $__currentLoopData = $latestInfographics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $infographic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="content-card" onclick="showInfographicDetail(<?php echo e($infographic->id); ?>)">
          <?php if($infographic->image): ?>
          <div class="infographic-image-wrapper">
            <img src="<?php echo e($infographic->image); ?>" alt="<?php echo e($infographic->title); ?>"
                 class="infographic-image"
                 onerror="this.src='<?php echo e(asset('images/default-placeholder.jpg')); ?>'">
          </div>
          <?php else: ?>
          <div style="width: 100%; height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-bar-chart-line" style="font-size: 3rem; color: #ccc;"></i>
          </div>
          <?php endif; ?>
          <div class="content-card-body">
            <h6 class="content-card-title"><?php echo e($infographic->title); ?></h6>
            <div class="content-card-meta">
              <span class="badge bg-primary">Infografis</span>
            </div>
          </div>
          <div class="content-card-footer">
            <button class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation(); showInfographicDetail(<?php echo e($infographic->id); ?>)">
              <i class="bi bi-eye"></i> Lihat
            </button>
            <button class="btn btn-sm btn-primary download-infographic-btn" 
                    data-infographic-id="<?php echo e($infographic->id); ?>"
                    data-infographic-title="<?php echo e(e($infographic->title ?? 'Infografis')); ?>"
                    onclick="event.stopPropagation(); handleInfographicDownload(this)">
              <i class="bi bi-download"></i> Unduh
            </button>
          </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
          <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> Belum ada data infografis.
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
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
      'news': '<?php echo e(route("news")); ?>',
      'publication': '<?php echo e(route("publications")); ?>',
      'infographic': '<?php echo e(route("infographics")); ?>'
    };
    
    document.getElementById('contentSectionTitle').textContent = titles[type];
    document.getElementById('viewMoreLink').href = links[type];
  }

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
        items = <?php echo json_encode($carouselNews ?? collect(), 15, 512) ?>;
      } else if (type === 'publication') {
        items = <?php echo json_encode($carouselPublications ?? collect(), 15, 512) ?>;
      } else if (type === 'infographic') {
        items = <?php echo json_encode($carouselInfographics ?? collect(), 15, 512) ?>;
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
              <img src="${item.image || '<?php echo e(asset("images/default-placeholder.jpg")); ?>'}" 
                   alt="${item.title || 'Item'}"
                   class="${imageClass}"
                   onerror="this.src='<?php echo e(asset("images/default-placeholder.jpg")); ?>'">
            </div>
            <div class="carousel-overlay">
              <h5>${item.title || 'Item'}</h5>
              <p>
                <span class="badge bg-primary">${(item.type || 'item').charAt(0).toUpperCase() + (item.type || 'item').slice(1)}</span>
                ${dateStr ? `<span class="ms-2">${dateStr}</span>` : ''}
              </p>
            </div>
          </div>
        `;
        } else {
          // For news, use original styling
          return `
          <div class="carousel-item ${index === 0 ? 'active' : ''}">
            <img src="${item.image || '<?php echo e(asset("images/default-placeholder.jpg")); ?>'}" 
                 alt="${item.title || 'Item'}"
                 onerror="this.src='<?php echo e(asset("images/default-placeholder.jpg")); ?>'">
            <div class="carousel-overlay">
              <h5>${item.title || 'Item'}</h5>
              <p>
                <span class="badge bg-primary">${(item.type || 'item').charAt(0).toUpperCase() + (item.type || 'item').slice(1)}</span>
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

  // Show publication modal (placeholder - implement based on your modal structure)
  function showPublicationModal(id) {
    // Redirect to publications page or show modal
    window.location.href = '<?php echo e(route("publications")); ?>?publication=' + id;
  }

  // Show infographic detail (placeholder - implement based on your modal structure)
  function showInfographicDetail(id) {
    // Redirect to infographics page or show modal
    window.location.href = '<?php echo e(route("infographics")); ?>?infographic=' + id;
  }

  // Handle publication download
  function handlePublicationDownload(button) {
    <?php if(auth()->guard()->guest()): ?>
    // User not logged in, show login required modal
    const pubTitle = button.dataset.pubTitle || 'publikasi ini';
    if (typeof showLoginRequiredModal === 'function') {
      showLoginRequiredModal(pubTitle, 'Ingin mengunduh ' + pubTitle + '? Silakan login terlebih dahulu.');
    } else {
      // Fallback: redirect to login page
      window.location.href = '<?php echo e(route("login")); ?>';
    }
    return;
    <?php endif; ?>

    // User is authenticated, proceed with download
    const pubId = button.dataset.pubId || '';
    if (pubId) {
      window.open('<?php echo e(route("download-publication", ":id")); ?>'.replace(':id', pubId), '_blank');
    }
  }

  // Handle infographic download
  function handleInfographicDownload(button) {
    <?php if(auth()->guard()->guest()): ?>
    // User not logged in, show login required modal
    const infographicTitle = button.dataset.infographicTitle || 'infografis ini';
    if (typeof showLoginRequiredModal === 'function') {
      showLoginRequiredModal(infographicTitle, 'Ingin mengunduh ' + infographicTitle + '? Silakan login terlebih dahulu.');
    } else {
      // Fallback: redirect to login page
      window.location.href = '<?php echo e(route("login")); ?>';
    }
    return;
    <?php endif; ?>

    // User is authenticated, proceed with download
    const infographicId = button.dataset.infographicId || '';
    if (infographicId) {
      window.open('<?php echo e(route("download-infographic", ":id")); ?>'.replace(':id', infographicId), '_blank');
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
    const API_BASE = '<?php echo e(url("/api")); ?>';
    const location = 'Kota Surabaya';
    const carousel = document.getElementById('summaryCardsCarousel');
    if (!carousel) return;

    try {
      // Fetch data from all indicator APIs
      // Note: Some endpoints use -summary suffix, others use location parameter
      const [
        inflasiRes, kemiskinanRes, kependudukanRes, ketenagakerjaanRes,
        pdrbPengeluaranRes, pdrbLapanganUsahaRes, hotelOccupancyRes, giniRatioRes,
        ipmRes, uhhSpRes, hlsRes, rlsRes, pengeluaranRes,
        indeksKesehatanRes, indeksPendidikanRes, indeksHidupLayakRes
      ] = await Promise.all([
        fetch(`${API_BASE}/inflasi-summary`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
        fetch(`${API_BASE}/kemiskinan-summary`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
        fetch(`${API_BASE}/kependudukan-summary`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
        fetch(`${API_BASE}/ketenagakerjaan-summary`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
        fetch(`${API_BASE}/pdrb-pengeluaran-summary`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
        fetch(`${API_BASE}/pdrb-lapangan-usaha-summary`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
        fetch(`${API_BASE}/hotel-occupancy-summary`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
        fetch(`${API_BASE}/gini-ratio-summary`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
        fetch(`${API_BASE}/ipm-surabaya`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
        fetch(`${API_BASE}/ipm-uhh-sp?location=${encodeURIComponent(location)}`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
        fetch(`${API_BASE}/ipm-hls?location=${encodeURIComponent(location)}`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
        fetch(`${API_BASE}/ipm-rls?location=${encodeURIComponent(location)}`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
        fetch(`${API_BASE}/ipm-pengeluaran-per-kapita?location=${encodeURIComponent(location)}`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
        fetch(`${API_BASE}/ipm-indeks-kesehatan?location=${encodeURIComponent(location)}`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
        fetch(`${API_BASE}/ipm-indeks-pendidikan?location=${encodeURIComponent(location)}`).then(r => r.json()).catch(() => ({ success: false, data: [] })),
        fetch(`${API_BASE}/ipm-indeks-hidup-layak?location=${encodeURIComponent(location)}`).then(r => r.json()).catch(() => ({ success: false, data: [] }))
      ]);

      // Helper function to get latest from array with specific field
      const getLatestByField = (data, field = 'value') => {
        if (!data || !Array.isArray(data) || data.length === 0) return null;
        const sorted = [...data].sort((a, b) => {
          const yearA = a.year || a.date_year || a.tahun || 0;
          const yearB = b.year || b.date_year || b.tahun || 0;
          return yearB - yearA;
        });
        const latest = sorted[0];
        const previousYear = (latest.year || latest.date_year || latest.tahun) - 1;
        let previous = sorted.find(d => {
          const year = d.year || d.date_year || d.tahun;
          return year === previousYear;
        });
        if (!previous && sorted.length > 1) {
          previous = sorted[1];
        }
        const value = latest[field] !== undefined ? latest[field] : latest.value;
        return {
          value: value,
          year: latest.year || latest.date_year || latest.tahun,
          previous: previous,
          previousYear: previous ? (previous.year || previous.date_year || previous.tahun) : previousYear
        };
      };

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
        if (latest.previous && latest.previous.value !== null && latest.previous.value !== undefined) {
          const latestVal = parseFloat(latest.value);
          const prevVal = parseFloat(latest.previous.value);
          
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
      // Endpoint -summary returns: { success: true, data: { latest, previous, m_to_m_change, y_on_y_change } }
      if (inflasiRes.success && inflasiRes.data) {
        const inflasiData = inflasiRes.data;
        const latest = inflasiData.latest;
        const previous = inflasiData.previous;
        
        if (latest) {
          // Helper to create card with previous data
          const createInflasiCard = (field, title) => {
            if (latest[field] !== null && latest[field] !== undefined) {
              const prevValue = previous && previous[field] !== null && previous[field] !== undefined ? previous[field] : null;
              return createCard({
                value: latest[field],
                year: latest.year,
                previous: prevValue !== null ? { value: prevValue, year: previous.year } : null,
                previousYear: previous && previous.year ? previous.year : (latest.year - 1)
              }, title, 'percent', '<?php echo e(route("inflasi")); ?>');
            }
            return null;
          };
          
          // Inflasi Bulan ke Bulan (m-to-m) - field: bulanan
          const card1 = createInflasiCard('bulanan', 'Inflasi Bulan ke Bulan');
          if (card1) cards.push(card1);
          
          // Inflasi Tahun ke Tahun (y-on-y) - field: tahunan
          const card2 = createInflasiCard('tahunan', 'Inflasi Tahun ke Tahun');
          if (card2) cards.push(card2);
          
          // Inflasi Kumulatif
          const card3 = createInflasiCard('kumulatif', 'Inflasi Kumulatif');
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
              }, title, valueType, '<?php echo e(route("kemiskinan")); ?>', 'value', suffix);
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
            }, title, valueType, '<?php echo e(route("kependudukan")); ?>');
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
          }, 'Rasio Jenis Kelamin', 'number', '<?php echo e(route("kependudukan")); ?>');
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
          }, 'TPT Total', 'percent', '<?php echo e(route("ketenagakerjaan")); ?>');
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
          }, 'TPAK Total', 'percent', '<?php echo e(route("ketenagakerjaan")); ?>');
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
          }, 'Gini Ratio Surabaya', 'number', '<?php echo e(route("gini-ratio")); ?>');
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
          }, 'Gini Ratio Jawa Timur', 'number', '<?php echo e(route("gini-ratio")); ?>');
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
          const createHotelCard = (field, title, valueType) => {
            if (latest[field] !== null && latest[field] !== undefined) {
              const prevValue = previous && previous[field] !== null && previous[field] !== undefined ? previous[field] : null;
              const year = latest.year || latest.date || latest.tanggal;
              const prevYear = previous ? (previous.year || previous.date || previous.tanggal) : null;
              return createCard({
                value: latest[field],
                year: year,
                previous: prevValue !== null ? { value: prevValue, year: prevYear } : null,
                previousYear: prevYear || (year ? (typeof year === 'number' ? year - 1 : null) : null)
              }, title, valueType, '<?php echo e(route("hotel-occupancy")); ?>');
            }
            return null;
          };
          
          // TPK Total
          const card1 = createHotelCard('tpk', 'TPK Total', 'percent');
          if (card1) cards.push(card1);
          
          // MKTJ
          const card2 = createHotelCard('mktj', 'MKTJ', 'currency');
          if (card2) cards.push(card2);
          
          // RLMT Gabungan
          const card3 = createHotelCard('rlmtgab', 'RLMT Gabungan', 'number');
          if (card3) cards.push(card3);
          
          // GPR
          const card4 = createHotelCard('gpr', 'GPR', 'currency');
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
            }, `PDRB Pengeluaran - ${sheetName}`, 'currency', '<?php echo e(route("pdrb-pengeluaran")); ?>');
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
            }, `PDRB Lapangan Usaha - ${sheetName}`, 'currency', '<?php echo e(route("pdrb-lapangan-usaha")); ?>');
            if (card) cards.push(card);
          }
        });
      }

      // IPM
      const ipmData = ipmRes.success ? ipmRes.data : [];
      const ipmCard = createCard(getLatest(ipmData, 'ipm_value'), 'Indeks Pembangunan Manusia', 'number', '<?php echo e(route("indeks-pembangunan-manusia")); ?>', 'ipm_value');
      if (ipmCard) cards.push(ipmCard);

      // UHH SP
      const uhhSpData = uhhSpRes.success ? uhhSpRes.data : [];
      const uhhSpCard = createCard(getLatest(uhhSpData), 'Usia Harapan Hidup saat Lahir', 'number', '<?php echo e(route("ipm-uhh-sp")); ?>');
      if (uhhSpCard) cards.push(uhhSpCard);

      // HLS
      const hlsData = hlsRes.success ? hlsRes.data : [];
      const hlsCard = createCard(getLatest(hlsData), 'Harapan Lama Sekolah', 'number', '<?php echo e(route("ipm-hls")); ?>');
      if (hlsCard) cards.push(hlsCard);

      // RLS
      const rlsData = rlsRes.success ? rlsRes.data : [];
      const rlsCard = createCard(getLatest(rlsData), 'Rata-rata Lama Sekolah', 'number', '<?php echo e(route("ipm-rls")); ?>');
      if (rlsCard) cards.push(rlsCard);

      // Pengeluaran per Kapita
      const pengeluaranData = pengeluaranRes.success ? pengeluaranRes.data : [];
      const pengeluaranCard = createCard(getLatest(pengeluaranData), 'Pengeluaran per Kapita', 'currency', '<?php echo e(route("ipm-pengeluaran-per-kapita")); ?>');
      if (pengeluaranCard) cards.push(pengeluaranCard);

      // Indeks Kesehatan
      const indeksKesehatanData = indeksKesehatanRes.success ? indeksKesehatanRes.data : [];
      const indeksKesehatanCard = createCard(getLatest(indeksKesehatanData), 'Indeks Kesehatan', 'number', '<?php echo e(route("ipm-indeks-kesehatan")); ?>');
      if (indeksKesehatanCard) cards.push(indeksKesehatanCard);

      // Indeks Pendidikan
      const indeksPendidikanData = indeksPendidikanRes.success ? indeksPendidikanRes.data : [];
      const indeksPendidikanCard = createCard(getLatest(indeksPendidikanData), 'Indeks Pendidikan', 'number', '<?php echo e(route("ipm-indeks-pendidikan")); ?>');
      if (indeksPendidikanCard) cards.push(indeksPendidikanCard);

      // Indeks Hidup Layak
      const indeksHidupLayakData = indeksHidupLayakRes.success ? indeksHidupLayakRes.data : [];
      const indeksHidupLayakCard = createCard(getLatest(indeksHidupLayakData), 'Indeks Hidup Layak', 'number', '<?php echo e(route("ipm-indeks-hidup-layak")); ?>');
      if (indeksHidupLayakCard) cards.push(indeksHidupLayakCard);

      // Debug: Log cards data
      console.log('Summary cards loaded:', cards.length, cards);
      console.log('API Responses:', {
        inflasi: inflasiRes.success ? inflasiRes.data?.length : 0,
        kemiskinan: kemiskinanRes.success ? kemiskinanRes.data?.length : 0,
        kependudukan: kependudukanRes.success ? kependudukanRes.data?.length : 0,
        ketenagakerjaan: ketenagakerjaanRes.success ? ketenagakerjaanRes.data?.length : 0,
        giniRatio: giniRatioRes.success ? giniRatioRes.data?.length : 0,
        hotelOccupancy: hotelOccupancyRes.success ? hotelOccupancyRes.data?.length : 0,
        ipm: ipmRes.success ? ipmRes.data?.length : 0
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
        if (card.title.includes('Persentase') || card.title.includes('Inflasi') || card.title.includes('TPT') || card.title.includes('TPAK') || card.title.includes('TPK')) {
          // Percentage format
          diffFormatted = `${diff >= 0 ? '+' : ''}${Math.abs(diff).toFixed(2)}%`;
        } else if (card.value.includes('Rp') || card.title.includes('Garis Kemiskinan') || card.title.includes('MKTJ') || card.title.includes('GPR') || card.title.includes('Pengeluaran') || card.title.includes('PDRB')) {
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
</script>
<?php $__env->stopPush(); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\astabaya\resources\views/dashboard/dashboard.blade.php ENDPATH**/ ?>