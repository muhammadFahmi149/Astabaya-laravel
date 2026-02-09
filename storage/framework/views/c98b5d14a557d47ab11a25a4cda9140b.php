<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startPush('styles'); ?>
<style>
  /* Welcome Section */
  .welcome-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    border-color: #667eea;
    background: #f8f9ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
  }

  .category-btn.active {
    border-color: #667eea;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
  }

  .content-card {
    border: 1px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
    background: white;
    transition: all 0.3s ease;
    cursor: pointer;
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
  }

  .content-card-body {
    padding: 1.25rem;
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
  }

  .content-card-footer {
    padding: 0.75rem 1.25rem;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    display: flex;
    gap: 0.5rem;
  }

  .content-card-footer .btn {
    flex: 1;
    font-size: 0.875rem;
  }

  @media (max-width: 768px) {
    .welcome-section {
      padding: 1.5rem;
    }

    .welcome-section h2 {
      font-size: 1.5rem;
    }

    .carousel-container {
      height: 300px;
    }

    .carousel-item img {
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
      <small style="font-size: 1rem; opacity: 0.9;">Aastabaya Website untuk melihat indikator strategis!</small>
    </h2>
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
          <img src="<?php echo e($publication->image); ?>" alt="<?php echo e($publication->title); ?>"
               onerror="this.src='<?php echo e(asset('images/default-placeholder.jpg')); ?>'">
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
            <a href="<?php echo e(route('download-publication', $publication->pub_id ?? $publication->id)); ?>" 
               class="btn btn-sm btn-primary" 
               onclick="event.stopPropagation();"
               target="_blank">
              <i class="bi bi-download"></i> Download PDF
            </a>
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
          <img src="<?php echo e($infographic->image); ?>" alt="<?php echo e($infographic->title); ?>"
               onerror="this.src='<?php echo e(asset('images/default-placeholder.jpg')); ?>'">
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
            <button class="btn btn-sm btn-outline-primary flex-fill" onclick="event.stopPropagation(); showInfographicDetail(<?php echo e($infographic->id); ?>)">
              <i class="bi bi-eye"></i> Lihat
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

  // Initialize on page load
  document.addEventListener('DOMContentLoaded', function() {
    updateScrollButtons();
    
    // Update scroll buttons on scroll
    const container = document.getElementById('indicatorCardsContainer');
    container.addEventListener('scroll', updateScrollButtons);
    
    // Set default to news
    switchContentType('news');
  });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Astabaya-laravel\resources\views/dashboard/dashboard.blade.php ENDPATH**/ ?>