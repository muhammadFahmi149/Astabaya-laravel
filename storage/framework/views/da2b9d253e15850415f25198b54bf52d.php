<?php $__env->startSection('title', 'Publikasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="publications-page">
  <!-- Page Header -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h3 class="font-weight-bold mb-2">Publikasi BPS Kota Surabaya</h3>
          <p class="text-muted mb-0">
            <?php if(isset($selected_year) || isset($search_query)): ?>
              Menampilkan: <span class="badge bg-primary"><?php echo e($filtered_count ?? 0); ?></span> dari <span class="badge bg-secondary"><?php echo e($countPublication ?? 0); ?></span> publikasi
              <?php if(isset($selected_year)): ?>(Tahun <?php echo e($selected_year); ?>)<?php endif; ?>
            <?php else: ?>
              Total: <span class="badge bg-primary"><?php echo e($countPublication ?? 0); ?></span> publikasi
            <?php endif; ?>
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
        <input type="text" class="form-control" id="searchInput" placeholder="Cari publikasi berdasarkan judul atau abstrak..." value="<?php echo e($search_query ?? ''); ?>" onkeypress="handleSearchKeyPress(event)" />
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
            <i class="bi bi-calendar"></i> <span id="yearFilterText"><?php if(isset($selected_year)): ?><?php echo e($selected_year); ?><?php else: ?> Semua Tahun <?php endif; ?></span>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="yearFilterButton" id="yearFilterMenu" style="max-height: calc(5 * 2.5rem); overflow-y: auto;">
            <a class="dropdown-item <?php if(!isset($selected_year)): ?> active <?php endif; ?>" href="#" data-year="">Semua Tahun</a>
            <?php if(isset($available_years)): ?>
              <?php $__currentLoopData = $available_years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <a class="dropdown-item <?php if(isset($selected_year) && $selected_year == strval($year)): ?> active <?php endif; ?>" href="#" data-year="<?php echo e($year); ?>"><?php echo e($year); ?></a>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Publications List -->
  <div class="row">
    <?php if(isset($dataPublication) && $dataPublication->count() > 0): ?>
      <?php $__currentLoopData = $dataPublication; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $publication): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="col-12 mb-4 publication-item" data-year="<?php echo e($publication->date ? \Carbon\Carbon::parse($publication->date)->format('Y') : ''); ?>">
        <div class="card hover-card">
          <div class="card-body">
            <!-- Mobile Layout (Book-like) -->
            <div class="d-md-none">
              <div class="row g-3 mb-3">
                <!-- Cover Image (Left) -->
                <div class="col-auto">
                  <img
                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 150 200'%3E%3Crect fill='%23f0f0f0' width='150' height='200'/%3E%3Ctext fill='%23999' x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-size='14' font-family='Arial'%3ELoading...%3C/text%3E%3C/svg%3E"
                    data-src="<?php echo e($publication->image ?? ''); ?>"
                    alt="<?php echo e($publication->title ?? ''); ?>"
                    class="rounded shadow-sm lazy-load publication-thumbnail-mobile"
                    style="width: 100px; height: 140px; object-fit: cover; cursor: pointer"
                    loading="lazy"
                    data-pub-id="<?php echo e(e($publication->pub_id ?? '')); ?>"
                    data-index="<?php echo e($index); ?>"
                    onclick="showModal(this.dataset.pubId, this.dataset.index)"
                  />
                </div>

                <!-- Publication Info (Right) -->
                <div class="col">
                  <h6 class="card-title mb-2 fw-bold" style="font-size: 0.9rem; line-height: 1.3"><?php echo e($publication->title ?? ''); ?></h6>
                  
                  <div class="mb-2">
                    <small class="text-muted d-flex align-items-center mb-1" style="font-size: 0.75rem">
                      <i class="bi bi-people me-1" style="font-size: 0.8rem"></i>
                      <span>BPS Kota Surabaya</span>
                    </small>
                  </div>

                  <p class="card-text text-muted mb-2" style="font-size: 0.8rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 3; line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden">
                    <?php echo e(\Illuminate\Support\Str::words($publication->abstract ?? '', 25, '...')); ?>

                  </p>

                  <div class="mb-2">
                    <small class="text-muted d-flex flex-wrap align-items-center gap-2" style="font-size: 0.7rem">
                      <span>PDF</span>
                      <span>·</span>
                      <span>bahasa Indonesia</span>
                      <?php if($publication->date): ?>
                      <span>·</span>
                      <span><?php echo e(\Carbon\Carbon::parse($publication->date)->format('Y')); ?></span>
                      <?php endif; ?>
                      <?php if($publication->size): ?>
                      <span>·</span>
                      <span><?php echo e($publication->size); ?></span>
                      <?php endif; ?>
                    </small>
                  </div>
                </div>
              </div>

              <!-- Action Buttons (Bottom) -->
              <div class="row g-2 publication-action-buttons">
                <div class="col-3">
                  <button class="btn btn-light w-100 publication-action-btn d-flex flex-column align-items-center justify-content-center" data-pub-id="<?php echo e(e($publication->pub_id ?? '')); ?>" data-index="<?php echo e($index); ?>" onclick="showModal(this.dataset.pubId, this.dataset.index)">
                    <i class="bi bi-book publication-action-icon"></i>
                    <span class="publication-action-text">Baca</span>
                  </button>
                </div>
                <div class="col-3">
                  <a href="<?php echo e(route('download-publication', $publication->pub_id ?? $publication->id)); ?>" target="_blank" class="btn btn-light w-100 publication-action-btn download-publication-btn d-flex flex-column align-items-center justify-content-center" style="text-decoration: none" data-pub-id="<?php echo e($publication->pub_id ?? $publication->id); ?>" data-pub-title="<?php echo e(e($publication->title ?? '')); ?>">
                    <i class="bi bi-download publication-action-icon"></i>
                    <span class="publication-action-text">Unduhan</span>
                  </a>
                </div>
                <div class="col-3">
                  <?php echo $__env->make('components.share-button', [
                      'title' => $publication->title ?? '',
                      'url' => route('publications') . '?publication=' . $publication->id,
                      'contentType' => 'publication',
                      'size' => 'sm',
                      'variant' => 'light',
                      'showText' => true,
                      'iconClass' => 'bi bi-share publication-action-icon',
                      'textClass' => 'publication-action-text',
                      'class' => 'w-100 publication-action-btn d-flex flex-column align-items-center justify-content-center'
                  ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                <div class="col-3">
                  <button class="btn btn-light w-100 publication-action-btn bookmark-btn d-flex flex-column align-items-center justify-content-center" data-content-type="publication" data-object-id="<?php echo e($publication->id); ?>" data-bookmark-id="" onclick="handlePublicationBookmark(this)">
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
                  data-src="<?php echo e($publication->image ?? ''); ?>"
                  alt="<?php echo e($publication->title ?? ''); ?>"
                  class="img-fluid rounded shadow-sm lazy-load publication-thumbnail"
                  style="max-height: 150px; width: auto; cursor: pointer"
                  loading="lazy"
                  data-pub-id="<?php echo e(e($publication->pub_id ?? '')); ?>"
                  data-index="<?php echo e($index); ?>"
                  onclick="showModal(this.dataset.pubId, this.dataset.index)"
                />
              </div>

              <!-- Publication Info -->
              <div class="col-md-6 col-lg-7">
                <h5 class="card-title mb-2"><?php echo e($publication->title ?? ''); ?></h5>

                <div class="mb-2 publication-badges">
                  <?php if($publication->date): ?>
                  <span class="badge bg-info me-2"> <i class="bi bi-calendar"></i> <?php echo e(\Carbon\Carbon::parse($publication->date)->format('d M Y')); ?> </span>
                  <?php endif; ?>
                  <?php if($publication->size): ?>
                  <span class="badge bg-secondary"> <i class="bi bi-file-earmark-pdf"></i> <?php echo e($publication->size); ?> </span>
                  <?php endif; ?>
                </div>

                <p class="card-text text-muted mb-0"><?php echo e(\Illuminate\Support\Str::words($publication->abstract ?? '', 30, '...')); ?></p>
              </div>

              <!-- Actions -->
              <div class="col-md-3 col-lg-3">
                <div class="d-flex flex-column gap-2">
                  <button class="btn btn-outline-primary btn-sm" data-pub-id="<?php echo e(e($publication->pub_id ?? '')); ?>" data-index="<?php echo e($index); ?>" onclick="showModal(this.dataset.pubId, this.dataset.index)"><i class="bi bi-eye"></i> Detail</button>
                  <a href="<?php echo e(route('download-publication', $publication->pub_id ?? $publication->id)); ?>" target="_blank" class="btn btn-primary btn-sm download-publication-btn" data-pub-id="<?php echo e($publication->pub_id ?? $publication->id); ?>" data-pub-title="<?php echo e(e($publication->title ?? '')); ?>"> <i class="bi bi-download"></i> Download PDF </a>
                  <button class="btn btn-outline-secondary btn-sm bookmark-btn" data-content-type="publication" data-object-id="<?php echo e($publication->id); ?>" data-bookmark-id="" onclick="handlePublicationBookmark(this)">
                    <i class="bi bi-bookmark"></i> <span>Bookmark</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
    <div class="col-12">
      <div class="alert alert-info text-center">
        <i class="bi bi-info-circle"></i> Belum ada data publikasi.
      </div>
    </div>
    <?php endif; ?>
  </div>

  <!-- Pagination -->
  <?php if(isset($dataPublication) && is_object($dataPublication) && method_exists($dataPublication, 'hasPages') && $dataPublication->hasPages()): ?>
  <nav aria-label="Page navigation" class="mt-4">
    <ul class="pagination justify-content-center">
      <?php if($dataPublication->onFirstPage()): ?>
      <li class="page-item disabled">
        <span class="page-link"><i class="bi bi-chevron-left"></i> Previous</span>
      </li>
      <?php else: ?>
      <li class="page-item">
        <a class="page-link" href="<?php echo e($dataPublication->appends(request()->query())->previousPageUrl()); ?>"> <i class="bi bi-chevron-left"></i> Previous </a>
      </li>
      <?php endif; ?>

      <?php $__currentLoopData = $dataPublication->getUrlRange(max(1, $dataPublication->currentPage() - 2), min($dataPublication->lastPage(), $dataPublication->currentPage() + 2)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($page == $dataPublication->currentPage()): ?>
        <li class="page-item active"><span class="page-link"><?php echo e($page); ?></span></li>
        <?php else: ?>
        <li class="page-item"><a class="page-link" href="<?php echo e($dataPublication->appends(request()->query())->url($page)); ?>"><?php echo e($page); ?></a></li>
        <?php endif; ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      <?php if($dataPublication->hasMorePages()): ?>
      <li class="page-item">
        <a class="page-link" href="<?php echo e($dataPublication->appends(request()->query())->nextPageUrl()); ?>"> Next <i class="bi bi-chevron-right"></i> </a>
      </li>
      <?php else: ?>
      <li class="page-item disabled">
        <span class="page-link">Next <i class="bi bi-chevron-right"></i></span>
      </li>
      <?php endif; ?>
    </ul>
    <p class="text-center text-muted small">Showing <?php echo e($dataPublication->firstItem() ?? 0); ?> to <?php echo e($dataPublication->lastItem() ?? 0); ?> of <?php echo e($filtered_count ?? $dataPublication->total()); ?> publications</p>
  </nav>
  <?php endif; ?>
</div>

<!-- Modal for Publication Detail -->
<div class="modal fade" id="publicationModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4 col-lg-4 text-center mb-3">
            <img id="modalImage" src="" alt="" class="img-fluid rounded shadow publication-modal-image" />
          </div>
          <div class="col-md-8 col-lg-8">
            <div class="mb-2">
              <strong>Tanggal Publikasi:</strong>
              <span id="modalDate"></span>
            </div>
            <div class="mb-2">
              <strong>Ukuran File:</strong>
              <span id="modalSize"></span>
            </div>
            <div class="mb-2">
              <strong>ID Publikasi:</strong>
              <span id="modalPubId"></span>
            </div>
            <hr />
            <div class="abstract-container">
              <strong>Abstrak:</strong>
              <p id="modalAbstract" class="mt-2 text-muted"></p>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="d-flex w-100 gap-2 flex-wrap modal-footer-buttons">
          <button
            id="modalBookmarkBtn"
            class="btn btn-outline-secondary btn-sm bookmark-btn modal-footer-btn-left"
            data-content-type="publication"
            data-object-id=""
            data-bookmark-id=""
            onclick="handlePublicationBookmark(this)"
          >
            <i class="bi bi-bookmark"></i>
            <span class="modal-btn-text">Bookmark</span>
          </button>
          <?php echo $__env->make('components.share-button', [
              'title' => '',
              'url' => '',
              'contentType' => 'publication',
              'size' => 'sm',
              'variant' => 'outline-secondary',
              'showText' => true,
              'class' => 'modal-footer-btn-left share-publication-modal-btn'
          ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
          <a id="modalDownload" href="" target="_blank" class="btn btn-primary download-publication-btn modal-footer-btn-right" data-pub-id="" data-pub-title=""> <i class="bi bi-download"></i> Unduh PDF </a>
        </div>
      </div>
    </div>
  </div>
</div>


<style>
  /* Search Input Styling */
  #searchInput {
    border-radius: 25rem !important;
    height: 38px;
  }

  /* Year Filter Dropdown - Button Dropdown Styling */
  #yearFilterButton {
    width: 100%;
    min-width: 160px;
    border-radius: 25rem !important;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 1px solid #ced4da;
    position: relative;
  }

  #yearFilterButton i {
    margin-right: 8px;
    flex-shrink: 0;
  }

  #yearFilterButton::after {
    flex-shrink: 0;
    margin-left: 8px;
  }

  #yearFilterButton #yearFilterText {
    flex: 1;
    text-align: left;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    min-width: 0;
  }

  #yearFilterMenu {
    max-height: calc(5 * 2.5rem) !important;
    overflow-y: auto !important;
    overflow-x: hidden;
    border-radius: 1rem;
    border: 1px solid #dee2e6;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
  }

  /* Custom scrollbar for dropdown menu */
  #yearFilterMenu::-webkit-scrollbar {
    width: 8px;
  }

  #yearFilterMenu::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
  }

  #yearFilterMenu::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
  }

  #yearFilterMenu::-webkit-scrollbar-thumb:hover {
    background: #555;
  }

  #yearFilterMenu .dropdown-item {
    padding: 0.5rem 1rem;
    cursor: pointer;
    white-space: nowrap;
    text-align: center;
  }

  #yearFilterMenu .dropdown-item:hover {
    background-color: #f8f9fa;
  }

  #yearFilterMenu .dropdown-item.active {
    background-color: #0d6efd;
    color: white;
  }

  #yearFilterMenu .dropdown-item.active:hover {
    background-color: #0b5ed7;
  }

  /* Rounded corners for first and last items */
  #yearFilterMenu .dropdown-item:first-child {
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
  }

  #yearFilterMenu .dropdown-item:last-child {
    border-bottom-left-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
  }

  /* Modal Publication Detail - Desktop/Normal Screen */
  #publicationModal .modal-dialog {
    max-width: 900px;
    margin: 1.75rem auto;
    display: flex;
    align-items: center;
    min-height: calc(100% - 3.5rem);
  }

  #publicationModal .modal-content {
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    width: 100%;
    overflow: hidden;
  }

  #publicationModal .modal-header {
    flex-shrink: 0;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #dee2e6;
  }

  #publicationModal .modal-header .modal-title {
    font-size: 1.1rem;
    font-weight: 600;
  }

  #publicationModal .modal-body {
    flex: 1 1 auto;
    overflow-y: visible !important;
    overflow-x: hidden;
    padding: 1.5rem;
    padding-bottom: 1.5rem;
    min-height: 0;
    max-height: none;
    overflow: visible !important;
  }

  #publicationModal .modal-body .row {
    margin-left: 0;
    margin-right: 0;
  }

  #publicationModal .modal-body .col-md-4,
  #publicationModal .modal-body .col-lg-4 {
    padding-right: 1rem;
    padding-left: 0;
    display: flex;
    align-items: flex-start;
    justify-content: center;
  }

  #publicationModal .modal-body .col-md-8,
  #publicationModal .modal-body .col-lg-8 {
    padding-left: 1.5rem;
    padding-right: 0;
  }

  /* Publication Modal Image - Larger size for desktop */
  .publication-modal-image {
    max-height: 500px !important;
    width: auto !important;
    max-width: 100% !important;
    object-fit: contain;
  }

  #publicationModal .modal-body .col-md-4 img,
  #publicationModal .modal-body .col-lg-4 img {
    margin-right: 0;
  }

  #publicationModal .modal-body .mb-2 {
    font-size: 0.9rem;
    margin-bottom: 0.75rem;
  }

  #publicationModal .modal-body strong {
    font-size: 0.9rem;
    font-weight: 600;
  }

  #publicationModal .modal-footer {
    flex-shrink: 0;
    padding: 1rem 1.5rem;
    border-top: 1px solid #dee2e6;
    margin-top: 0;
    position: relative;
    z-index: 1;
    background-color: #fff;
  }

  #publicationModal .modal-footer .btn {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
  }

  /* Desktop: Bookmark and Share on left, Unduh on right */
  #publicationModal .modal-footer-buttons {
    display: flex;
    width: 100%;
    justify-content: space-between;
    align-items: center;
  }

  #publicationModal .modal-footer-btn-left {
    margin-right: auto;
  }

  #publicationModal .modal-footer-btn-right {
    margin-left: auto;
  }

  #publicationModal .modal-btn-text {
    margin-left: 0.5rem;
  }

  /* Mobile: Stack buttons vertically, Unduh at bottom */
  @media (max-width: 767.98px) {
    #publicationModal .modal-footer {
      padding: 1rem;
      flex-direction: column;
    }

    #publicationModal .modal-footer-buttons {
      flex-direction: column !important;
      width: 100%;
      gap: 0.75rem !important;
    }

    #publicationModal .modal-footer-btn-left {
      width: 100% !important;
      margin-right: 0;
      margin-left: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    #publicationModal .modal-footer-btn-right {
      width: 100% !important;
      margin-left: 0;
      margin-top: 0.5rem;
      order: 999;
    }

    #publicationModal .modal-btn-text {
      display: inline !important;
      margin-left: 0.5rem;
    }
  }

  #publicationModal .abstract-container {
    margin-bottom: 1rem;
    padding-bottom: 0;
  }

  #publicationModal .abstract-container strong {
    font-size: 0.95rem;
    font-weight: 600;
  }

  #publicationModal #modalAbstract {
    word-wrap: break-word;
    white-space: normal;
    line-height: 1.6;
    font-size: 0.875rem;
    margin-bottom: 0;
    margin-top: 0.5rem;
    max-height: 300px;
    overflow-y: auto !important;
    overflow-x: hidden;
    padding-right: 0.5rem;
    padding-bottom: 0.5rem;
  }

  /* Custom scrollbar for abstract */
  #publicationModal #modalAbstract::-webkit-scrollbar {
    width: 6px;
  }

  #publicationModal #modalAbstract::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
  }

  #publicationModal #modalAbstract::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
  }

  #publicationModal #modalAbstract::-webkit-scrollbar-thumb:hover {
    background: #555;
  }

  /* Responsive adjustments - Tablet and below */
  @media (max-width: 992px) {
    #publicationModal .modal-dialog {
      max-width: 85%;
    }

    .publication-modal-image {
      max-height: 350px !important;
    }

    #publicationModal .modal-body {
      overflow: visible !important;
      overflow-y: visible !important;
    }

    #publicationModal .modal-body {
      overflow-y: visible;
      max-height: none;
    }

    #publicationModal .abstract-container {
      padding-bottom: 0.5rem;
    }

    #publicationModal #modalAbstract {
      font-size: 0.6875rem;
      line-height: 1.5;
      max-height: 180px;
    }
  }

  @media (max-width: 768px) {
    #publicationModal .modal-dialog {
      max-width: 95%;
      margin: 0.5rem auto;
    }

    #publicationModal .modal-header {
      padding: 0.75rem 1rem;
    }

    #publicationModal .modal-header .modal-title {
      font-size: 0.95rem;
    }

    #publicationModal .modal-body {
      padding: 1rem;
      padding-bottom: 1rem;
      overflow-y: visible !important;
      overflow: visible !important;
      max-height: none;
    }

    #publicationModal .abstract-container {
      margin-bottom: 0.75rem;
    }

    #publicationModal .modal-body .col-md-4,
    #publicationModal .modal-body .col-lg-4 {
      padding-right: 0;
      padding-left: 0;
      margin-bottom: 1rem;
    }

    #publicationModal .modal-body .col-md-8,
    #publicationModal .modal-body .col-lg-8 {
      padding-left: 0;
      padding-right: 0;
    }

    .publication-modal-image {
      max-height: 300px !important;
    }

    #publicationModal .modal-body .mb-2 {
      font-size: 0.8rem;
    }

    #publicationModal .modal-body strong {
      font-size: 0.8rem;
    }

    #publicationModal .abstract-container {
      padding-bottom: 0.5rem;
      margin-bottom: 0;
    }

    #publicationModal .abstract-container strong {
      font-size: 0.85rem;
    }

    #publicationModal #modalAbstract {
      font-size: 0.75rem;
      line-height: 1.5;
      max-height: 200px;
    }

    #publicationModal .modal-footer {
      flex-direction: column;
      padding: 0.75rem 1rem;
      margin-top: 0;
    }

    #publicationModal .modal-footer .btn {
      width: 100%;
      margin: 0;
      font-size: 0.8rem;
    }
  }

  @media (max-width: 576px) {
    #publicationModal .modal-body {
      padding-bottom: 1rem;
      overflow-y: visible !important;
      overflow: visible !important;
      max-height: none;
    }

    #publicationModal .abstract-container {
      margin-bottom: 0.75rem;
    }

    #publicationModal .abstract-container {
      padding-bottom: 0.5rem;
    }

    #publicationModal #modalAbstract {
      font-size: 0.7rem;
      line-height: 1.4;
      max-height: 150px;
    }

    #publicationModal .modal-footer {
      padding: 0.75rem 1rem;
    }

    .publication-modal-image {
      max-height: 250px !important;
    }
  }

  @media (max-width: 400px) {
    #publicationModal .modal-body {
      padding-bottom: 1rem;
      overflow-y: visible !important;
      overflow: visible !important;
      max-height: none;
    }

    #publicationModal .abstract-container {
      margin-bottom: 0.75rem;
    }

    #publicationModal .abstract-container {
      padding-bottom: 0.5rem;
    }

    #publicationModal #modalAbstract {
      font-size: 0.65rem;
      line-height: 1.35;
      max-height: 120px;
    }

    #publicationModal .modal-footer {
      padding: 0.75rem 1rem;
    }

    .publication-modal-image {
      max-height: 200px !important;
    }
  }

  .search-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    border-radius: 25rem !important;
    overflow: hidden;
  }

  .search-input-wrapper #searchInput {
    border-radius: 25rem !important;
    padding-left: 45px;
    padding-right: 90px;
    border: 1px solid #ced4da;
  }

  .search-input-wrapper .search-icon {
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

  .search-button {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    border: none;
    background: #234C6A;
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

  .search-input-wrapper #searchInput:focus {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
  }

  .hover-card {
    transition: all 0.3s ease;
    border-radius: 12px;
  }

  .hover-card:hover {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
  }

  .publication-item {
    margin-bottom: 1.5rem;
  }

  .publication-thumbnail {
    transition: transform 0.3s ease;
  }

  .publication-thumbnail:hover {
    transform: scale(1.05);
  }

  /* Mobile Layout (Book-like) */
  @media (max-width: 767.98px) {
    .publication-item .card-body {
      padding: 1rem;
    }

    .publication-item {
      margin-bottom: 1rem;
    }

    .publication-item .card {
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .publication-thumbnail-mobile {
      flex-shrink: 0;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    .publication-item .card-title {
      font-size: 0.9rem;
      line-height: 1.3;
      margin-bottom: 0.5rem !important;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .publication-item .card-text {
      font-size: 0.8rem;
      line-height: 1.4;
      margin-bottom: 0.5rem;
    }

    .share-publication-btn:hover,
    .share-publication-btn:active {
      background-color: #f8f9fa !important;
      transform: scale(0.98);
      transition: all 0.2s ease;
    }

    .download-publication-btn:hover,
    .download-publication-btn:active {
      background-color: #f8f9fa !important;
      transform: scale(0.98);
      transition: all 0.2s ease;
    }

    .bookmark-btn:hover,
    .bookmark-btn:active {
      background-color: #f8f9fa !important;
      transform: scale(0.98);
      transition: all 0.2s ease;
    }

    /* Bookmarked state styling */
    .bookmark-btn.bookmarked {
      background-color: #fff3cd !important;
      border-color: #ffc107 !important;
    }

    .bookmark-btn.bookmarked .publication-action-icon,
    .bookmark-btn.bookmarked i {
      color: #ffc107 !important;
    }

    .bookmark-btn.bookmarked .publication-action-text,
    .bookmark-btn.bookmarked span {
      color: #856404 !important;
      font-weight: 600 !important;
    }

    .bookmark-btn.bookmarked:hover {
      background-color: #ffe69c !important;
      border-color: #ffc107 !important;
    }

    .publication-item .btn-light:hover {
      background-color: #f8f9fa !important;
    }

    /* Publication Action Buttons - Same size for all */
    .publication-action-buttons {
      margin-top: 0.75rem;
    }

    .publication-action-btn {
      border-radius: 0.5rem !important;
      padding: 0.5rem !important;
      min-height: 60px !important;
      height: 60px !important;
      display: flex !important;
      flex-direction: column !important;
      align-items: center !important;
      justify-content: center !important;
      background-color: #f8f9fa !important;
      border: 1px solid #e9ecef !important;
      transition: all 0.2s ease !important;
    }

    .publication-action-icon {
      font-size: 1.2rem !important;
      color: #0d6efd !important;
      margin-bottom: 0.25rem !important;
      line-height: 1 !important;
    }

    .publication-action-text {
      color: #0d6efd !important;
      font-weight: 500 !important;
      font-size: 0.75rem !important;
      line-height: 1 !important;
    }

    .publication-action-btn:hover,
    .publication-action-btn:active,
    .publication-action-btn:focus {
      background-color: #e9ecef !important;
      transform: scale(0.98);
      border-color: #dee2e6 !important;
    }

    .publication-action-btn:active {
      transform: scale(0.96);
    }
  }

  @media (min-width: 576px) and (max-width: 767.98px) {
    .publication-thumbnail {
      max-height: 130px;
    }

    .publication-item .card-body {
      padding: 1.25rem;
    }

    .publication-item .publication-badges {
      display: flex;
      flex-wrap: nowrap;
      align-items: center;
      gap: 0.5rem;
    }

    .publication-item .badge {
      font-size: 0.65rem;
      padding: 0.15rem 0.35rem;
      margin-bottom: 0 !important;
      white-space: nowrap;
    }

    .publication-item .badge i {
      font-size: 0.7rem;
    }
  }

  @media (min-width: 768px) {
    .publication-item .row {
      align-items: center;
    }

    .publication-thumbnail {
      max-height: 150px;
    }

    .publication-item .col-md-3 {
      display: flex;
      align-items: center;
      justify-content: flex-end;
    }

    .publication-item .d-flex.flex-md-column {
      width: 100%;
      max-width: 200px;
    }
  }

  /* General styles for badges */
  .publication-item .publication-badges {
    display: flex;
    flex-wrap: nowrap;
    align-items: center;
    gap: 0.5rem;
  }

  .publication-item .badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    margin-bottom: 0 !important;
    white-space: nowrap;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
  }

  .publication-item .badge i {
    font-size: 0.8rem;
  }

  .publication-item .btn-sm {
    white-space: nowrap;
  }

  /* Bookmarked state styling for desktop */
  .bookmark-btn.bookmarked {
    background-color: #fff3cd;
    border-color: #ffc107;
    color: #856404;
  }

  .bookmark-btn.bookmarked i {
    color: #ffc107;
  }

  .bookmark-btn.bookmarked:hover {
    background-color: #ffe69c;
    border-color: #ffc107;
    color: #856404;
  }

  /* Modal bookmark button styling */
  #modalBookmarkBtn.bookmarked {
    background-color: #fff3cd;
    border-color: #ffc107;
    color: #856404;
  }

  #modalBookmarkBtn.bookmarked i {
    color: #ffc107;
  }

  #modalBookmarkBtn.bookmarked:hover {
    background-color: #ffe69c;
    border-color: #ffc107;
    color: #856404;
  }

  /* Better alignment for content area on desktop */
  @media (min-width: 768px) {
    .publication-item .col-md-6 {
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
  }
</style>

<!-- Hidden data container for publications -->
<div id="publicationsDataContainer" style="display: none">
  <?php if(isset($dataPublication) && $dataPublication->count() > 0): ?>
    <?php $__currentLoopData = $dataPublication; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $publication): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div
      class="publication-data"
      data-title="<?php echo e(e($publication->title ?? '')); ?>"
      data-image="<?php echo e(e($publication->image ?? '')); ?>"
      data-date="<?php echo e($publication->date ? \Carbon\Carbon::parse($publication->date)->format('d M Y') : 'N/A'); ?>"
      data-size="<?php echo e($publication->size ?? 'N/A'); ?>"
      data-pub-id="<?php echo e(e($publication->pub_id ?? '')); ?>"
      data-id="<?php echo e($publication->id); ?>"
      data-abstract="<?php echo e(e($publication->abstract ?? '')); ?>"
      data-download="<?php echo e(e($publication->download_url ?? $publication->dl ?? '')); ?>"
    ></div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php endif; ?>
</div>

<script>
  // Lazy loading for images
  document.addEventListener("DOMContentLoaded", function () {
    // Initialize year filter dropdown
    initYearFilter();
    
    // Initialize bookmark states
    <?php if(auth()->guard()->check()): ?>
    initializeBookmarkStates();
    <?php endif; ?>
    
    const lazyImages = document.querySelectorAll("img.lazy-load");

    if ("IntersectionObserver" in window) {
      const imageObserver = new IntersectionObserver(
        (entries, observer) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              const img = entry.target;
              const src = img.dataset.src;

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
    
    document.getElementById("modalAbstract").textContent = cleanAbstract;
    
    const modalDownloadBtn = document.getElementById("modalDownload");
    modalDownloadBtn.setAttribute('data-pub-id', pub.pubId || pub.id || '');
    modalDownloadBtn.setAttribute('data-pub-title', pub.title || 'Publikasi');
    modalDownloadBtn.href = "<?php echo e(route('download-publication', ':id')); ?>".replace(':id', pub.pubId || pub.id || '');

    const modal = new bootstrap.Modal(document.getElementById("publicationModal"));
    modal.show();

    // Update share button data
    const shareBtn = document.querySelector('.share-publication-modal-btn');
    if (shareBtn) {
      shareBtn.dataset.pubTitle = pub.title || 'Publikasi';
      // Use the publication ID (primary key) for the share URL
      const publicationId = pub.id || pub.pubId || '';
      const shareUrl = window.location.origin + '/publications?publication=' + publicationId;
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
  function refreshData() {
    const btn = event.target.closest("button");
    const originalContent = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memuat ulang...';

    // Reload halaman untuk mengambil data terbaru dari database
    location.reload();
  }

  // Fix all modal close buttons - Universal handler
  document.addEventListener('DOMContentLoaded', function() {
    // Handle all close buttons with data-bs-dismiss="modal"
    document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function(closeBtn) {
      closeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Find the closest modal
        const modalElement = this.closest('.modal');
        if (modalElement) {
          // Use Bootstrap Modal API to close
          const modalInstance = bootstrap.Modal.getInstance(modalElement);
          if (modalInstance) {
            modalInstance.hide();
          } else {
            // If no instance exists, create one and hide it
            const modal = new bootstrap.Modal(modalElement);
            modal.hide();
          }
        }
      });
    });
  });

  // Handle download clicks with login check
  document.addEventListener('DOMContentLoaded', function() {
    const downloadButtons = document.querySelectorAll('.download-publication-btn');
    downloadButtons.forEach(btn => {
      btn.addEventListener('click', async function(e) {
        <?php if(auth()->guard()->guest()): ?>
        e.preventDefault();
        const pubTitle = this.getAttribute('data-pub-title') || 'publikasi';
        // Redirect to login page
        if (typeof showLoginRequiredModal === 'function') {
          showLoginRequiredModal(pubTitle);
        } else {
          // Fallback: show alert and open login modal
          alert('Ingin mengunduh ' + pubTitle + ' ini? Silakan login terlebih dahulu.');
          const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
          loginModal.show();
        }
        <?php endif; ?>
      });
    });
  });

  // Handle bookmark click - check login first
  function handlePublicationBookmark(button) {
    const isAuthenticated = <?php if(auth()->guard()->check()): ?> true <?php else: ?> false <?php endif; ?>;
    
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
        window.location.href = '<?php echo e(route("login")); ?>';
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

    // Try to get CSRF token from cookie first, then from meta tag
    let csrftoken = getCookie("XSRF-TOKEN");
    if (!csrftoken) {
      const metaTag = document.querySelector('meta[name="csrf-token"]');
      if (metaTag) {
        csrftoken = metaTag.getAttribute("content");
      }
    }

    if (!csrftoken) {
      console.error("CSRF token not found");
      alert("Sesi Anda telah berakhir. Silakan refresh halaman dan login kembali.");
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
        const response = await fetch(`/api/bookmarks/delete/${bookmarkId}/`, {
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
        
        const response = await fetch(`/api/bookmarks/add/`, {
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
              const existingBookmarks = await fetch(`/api/bookmarks/`, {
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
  <?php if(auth()->guard()->check()): ?>
  window.addEventListener('storage', function(e) {
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
  });
  <?php endif; ?>

  // Initialize bookmark states on page load
  <?php if(auth()->guard()->check()): ?>
  async function initializeBookmarkStates() {
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
      const response = await fetch('/api/bookmarks/', {
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
  <?php endif; ?>

  // Make functions available globally
  window.broadcastBookmarkChange = broadcastBookmarkChange;
  window.syncBookmarkButtons = syncBookmarkButtons;

  // Initialize share buttons - using event delegation for dynamic content
  document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing share buttons for publications'); // Debug
    // Use event delegation to handle all share buttons (including dynamically added ones)
    document.addEventListener('click', async function(e) {
      const shareBtn = e.target.closest('.share-publication-modal-btn') || e.target.closest('.share-publication-btn');
      if (shareBtn) {
        e.preventDefault();
        e.stopPropagation();
        const title = shareBtn.dataset.pubTitle || 'Publikasi';
        let url = shareBtn.dataset.pubUrl || window.location.href;
        
        // Ensure URL is complete (add origin if relative)
        if (url && !url.startsWith('http://') && !url.startsWith('https://')) {
          url = window.location.origin + (url.startsWith('/') ? url : '/' + url);
        }
        
        console.log('Share button clicked:', { title, url, button: shareBtn, dataset: shareBtn.dataset }); // Debug log
        
        // Try Web Share API first
        if (navigator.share) {
          try {
            await navigator.share({
              title: title,
              text: 'Lihat publikasi ini: ' + title,
              url: url
            });
            console.log('Share successful');
            return;
          } catch (err) {
            if (err.name !== 'AbortError') {
              console.log('Error sharing or user cancelled:', err);
              // Fallback to copy to clipboard
              await copyToClipboardDirect(url, title, e);
            }
          }
        } else {
          // Fallback: copy to clipboard directly from event handler
          await copyToClipboardDirect(url, title, e);
        }
      }
    });
  });
  
  // Copy to clipboard directly from event handler (maintains user interaction context)
  async function copyToClipboardDirect(text, title, event) {
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
</script>

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
                <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">Login</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\astabaya\resources\views/dashboard/publications.blade.php ENDPATH**/ ?>