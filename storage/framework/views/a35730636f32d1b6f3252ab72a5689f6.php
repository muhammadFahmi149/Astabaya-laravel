<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title><?php echo $__env->yieldContent('title', 'Aastabaya'); ?></title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/vendors/feather/feather.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/vendors/ti-icons/css/themify-icons.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/vendors/css/vendor.bundle.base.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/vendors/font-awesome/css/font-awesome.min.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/vendors/mdi/css/materialdesignicons.min.css')); ?>" />
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/vendors/ti-icons/css/themify-icons.css')); ?>" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="https://code.highcharts.com/css/highcharts.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>" />
    <!-- endinject -->
    <link rel="shortcut icon" href="<?php echo e(asset('images/Aastabaya-favicon(2).png')); ?>" />
    <?php echo $__env->yieldPushContent('styles'); ?>
    <style>
      /* Default Modal Backdrop - Hitam Semi-transparan (untuk modal infografis, publikasi, dll) */
      .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.5) !important;
        backdrop-filter: none !important;
      }
      
      /* Default Modal Content - Putih (untuk modal infografis, publikasi, dll) */
      .modal-content {
        background-color: #ffffff !important;
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
      }
      
      /* Modal Background Overlay - Hanya untuk Login/Register Modal */
      body.login-register-modal-open .modal-backdrop {
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url("<?php echo e(asset('img/background-login-register.jpg')); ?>") no-repeat center center !important;
        background-size: cover !important;
        background-position: center !important;
        backdrop-filter: blur(5px);
      }
      
      /* Body background when login/register modal is open */
      body.login-register-modal-open {
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url("<?php echo e(asset('img/background-login-register.jpg')); ?>") no-repeat center center;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
      }
      
      /* Input Placeholder Color - Hanya untuk Login/Register Modal */
      #loginModal .modal-content input::placeholder,
      #registerModal .modal-content input::placeholder {
        color: rgba(255, 255, 255, 0.6) !important;
      }
      
      #loginModal .modal-content input:focus,
      #registerModal .modal-content input:focus {
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
        background: rgba(255, 255, 255, 0.15) !important;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.1) !important;
      }
      
      /* Google Sign-In Button Container */
      #g_id_onload, #g_id_onload_register {
        display: none;
      }
      
      /* Modal Content Background - Hanya untuk Login/Register Modal */
      #loginModal .modal-content,
      #registerModal .modal-content {
        background: rgba(225, 224, 224, 0.08) !important;
        backdrop-filter: blur(16px) !important;
        -webkit-backdrop-filter: blur(16px) !important;
      }
      
      /* Ensure modal backdrop shows background image - Hanya untuk Login/Register */
      #loginModal.show .modal-backdrop,
      #registerModal.show .modal-backdrop {
        opacity: 1;
      }
      
      @media (max-width: 767.98px) {
        .summary-card-responsive {
          padding: 12px !important;
          min-height: 140px !important;
          position: relative !important;
        }
        .summary-card-responsive > div {
          height: 100% !important;
          display: flex !important;
          flex-direction: column !important;
          justify-content: center !important;
        }
        .summary-card-responsive > div > div {
          display: flex !important;
          flex-direction: column !important;
          justify-content: center !important;
          align-items: flex-start !important;
        }
        .summary-card-responsive h6 {
          font-size: 10px !important;
          margin: 0 !important;
        }
        .summary-card-responsive h3 {
          font-size: 14px !important;
          margin: 0 !important;
        }
        .summary-card-responsive p {
          font-size: 10px !important;
          margin: 0 !important;
        }
        .summary-card-responsive .icon-mobile {
          font-size: 50px !important;
          height: 50px !important;
          line-height: 50px !important;
          position: absolute !important;
          top: 50% !important;
          right: 12px !important;
          transform: translateY(-50%) !important;
          z-index: 1 !important;
        }
        .summary-card-responsive .header-row {
          margin-bottom: 3px !important;
          position: relative !important;
        }
        .summary-card-responsive .change-indicator {
          margin-top: 3px !important;
        }
        .summary-card-responsive .change-indicator span {
          font-size: 10px !important;
        }
      }
      @media (min-width: 768px) {
        .summary-card-responsive {
          padding: 20px !important;
          min-height: 200px !important;
        }
        .summary-card-responsive h6 {
          font-size: 12px !important;
        }
        .summary-card-responsive h3 {
          font-size: 18px !important;
        }
        .summary-card-responsive p {
          font-size: 12px !important;
          margin-top: 5px !important;
        }
        .summary-card-responsive .header-row {
          margin-bottom: 10px !important;
        }
        .summary-card-responsive .change-indicator {
          margin-top: 8px !important;
        }
        .summary-card-responsive .change-indicator span {
          font-size: 12px !important;
        }
      }

      /* Responsive Bookmark Dropdown */
      /* Base styles for bookmark dropdown */
      #bookmarkDropdown {
        scrollbar-width: thin;
        scrollbar-color: rgba(0, 0, 0, 0.2) transparent;
        overflow-y: auto !important;
        overflow-x: hidden !important;
      }

      #bookmarkDropdown::-webkit-scrollbar {
        width: 6px;
      }

      #bookmarkDropdown::-webkit-scrollbar-track {
        background: transparent;
      }

      #bookmarkDropdown::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 3px;
      }

      #bookmarkDropdown::-webkit-scrollbar-thumb:hover {
        background-color: rgba(0, 0, 0, 0.3);
      }

      /* Ensure dropdown appears below icon, !to the side */
      #bookmarkDropdown.dropdown-menu {
        position: absolute !important;
        top: 100% !important;
        right: 0 !important;
        left: auto !important;
        transform: none !important;
        margin-top: 0.5rem !important;
        z-index: 1050 !important;
      }

      /* Ensure parent nav-item has relative positioning for dropdown */
      .navbar-nav .nav-item.dropdown {
        position: relative;
      }

      /* Prevent content from being cut off */
      #bookmarkDropdown .preview-item {
        white-space: normal !important;
        overflow: visible !important;
      }

      #bookmarkDropdown .preview-item-content {
        overflow: visible !important;
      }

      /* Mobile - Small screens (up to 575px) */
      @media (max-width: 575.98px) {
        #bookmarkDropdown {
          position: fixed !important;
          top: 60px !important;
          left: 10px !important;
          right: 10px !important;
          width: calc(100% - 20px) !important;
          max-width: none !important;
          max-height: calc(100vh - 80px) !important;
          box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
        }

        #bookmarkDropdown .preview-item {
          padding: 0.75rem 1rem !important;
          min-height: 60px !important;
        }

        #bookmarkDropdown .preview-thumbnail {
          min-width: 40px !important;
          width: 40px !important;
          height: 40px !important;
        }

        #bookmarkDropdown .preview-icon {
          width: 40px !important;
          height: 40px !important;
          font-size: 18px !important;
        }

        #bookmarkDropdown .preview-item-content {
          flex: 1 !important;
          min-width: 0 !important;
          padding-left: 0.75rem !important;
        }

        #bookmarkDropdown .preview-subject {
          font-size: 0.875rem !important;
          line-height: 1.4 !important;
          word-wrap: break-word !important;
          overflow-wrap: break-word !important;
          white-space: normal !important;
          display: -webkit-box !important;
          -webkit-line-clamp: 2 !important;
          -webkit-box-orient: vertical !important;
          overflow: hidden !important;
          text-overflow: ellipsis !important;
        }

        #bookmarkDropdown .dropdown-header {
          padding: 0.75rem 1rem !important;
          font-size: 0.9rem !important;
          font-weight: 600 !important;
        }

        #bookmarkDropdown #emptyBookmarkMessage {
          padding: 1.5rem 1rem !important;
          font-size: 0.875rem !important;
        }
      }

      /* Mobile - Medium screens (576px to 767px) */
      @media (min-width: 576px) and (max-width: 767.98px) {
        #bookmarkDropdown {
          min-width: 320px !important;
          max-width: 400px !important;
          max-height: calc(100vh - 100px) !important;
          position: absolute !important;
          right: 0 !important;
          left: auto !important;
          transform: none !important;
        }

        #bookmarkDropdown .preview-item {
          padding: 0.687rem 1.25rem !important;
        }

        #bookmarkDropdown .preview-subject {
          font-size: 0.9rem !important;
          line-height: 1.4 !important;
          white-space: normal !important;
          word-wrap: break-word !important;
          overflow-wrap: break-word !important;
          display: -webkit-box !important;
          -webkit-line-clamp: 2 !important;
          -webkit-box-orient: vertical !important;
          overflow: hidden !important;
          text-overflow: ellipsis !important;
        }
      }

      /* Tablet and Desktop (768px and up) */
      @media (min-width: 768px) {
        #bookmarkDropdown {
          min-width: 320px !important;
          max-width: 400px !important;
          max-height: calc(100vh - 120px) !important;
          position: absolute !important;
          right: 0 !important;
          left: auto !important;
        }

        #bookmarkDropdown .preview-item {
          padding: 0.687rem 1.562rem !important;
        }

        #bookmarkDropdown .preview-subject {
          font-size: 0.9rem !important;
          line-height: 1.4 !important;
          white-space: normal !important;
          word-wrap: break-word !important;
          overflow-wrap: break-word !important;
          display: -webkit-box !important;
          -webkit-line-clamp: 2 !important;
          -webkit-box-orient: vertical !important;
          overflow: hidden !important;
          text-overflow: ellipsis !important;
        }
      }

      /* Large Desktop (1200px && up) */
      @media (min-width: 1200px) {
        #bookmarkDropdown {
          min-width: 350px !important;
          max-width: 450px !important;
          max-height: calc(100vh - 120px) !important;
        }

        #bookmarkDropdown .preview-subject {
          -webkit-line-clamp: 2 !important;
        }
      }

      /* Extra Large Desktop (1400px && up) */
      @media (min-width: 1400px) {
        #bookmarkDropdown {
          min-width: 380px !important;
          max-width: 500px !important;
          max-height: calc(100vh - 120px) !important;
        }
      }

      /* Global Share Button Styles - Dapat digunakan di semua tab */
      .share-btn,
      .share-infographic-modal-btn,
      .share-infographic-btn,
      .share-publication-modal-btn,
      .share-publication-btn,
      .share-news-modal-btn,
      .share-news-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.375rem;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
        border: 1px solid;
        font-weight: 500;
      }

      .share-btn i,
      .share-infographic-modal-btn i,
      .share-infographic-btn i,
      .share-publication-modal-btn i,
      .share-publication-btn i,
      .share-news-modal-btn i,
      .share-news-btn i {
        font-size: 1rem;
      }

      .share-btn:hover,
      .share-infographic-modal-btn:hover,
      .share-infographic-btn:hover,
      .share-publication-modal-btn:hover,
      .share-publication-btn:hover,
      .share-news-modal-btn:hover,
      .share-news-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }

      .share-btn:active,
      .share-infographic-modal-btn:active,
      .share-infographic-btn:active,
      .share-publication-modal-btn:active,
      .share-publication-btn:active,
      .share-news-modal-btn:active,
      .share-news-btn:active {
        transform: translateY(0);
      }

      .share-btn-text {
        white-space: nowrap;
      }
      
      /* Share Modal Styles */
      #shareModal .modal-body textarea {
        cursor: text;
        user-select: all;
      }
      
      #shareModal .modal-body textarea:focus {
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
      }
      
      #shareModal .input-group {
        position: relative;
      }
      
      #shareModal .btn-primary {
        white-space: nowrap;
      }
      }

      /* Responsive: Hide text on small screens */
      @media (max-width: 575.98px) {
        .share-btn .share-btn-text,
        .share-infographic-modal-btn .share-btn-text,
        .share-infographic-btn .share-btn-text,
        .share-publication-modal-btn .share-btn-text,
        .share-publication-btn .share-btn-text,
        .share-news-modal-btn .share-btn-text,
        .share-news-btn .share-btn-text {
          display: none;
        }
      }

    </style>
  <body>
    <div class="container-scroller">
      <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-between">
          <a class="navbar-brand brand-logo" href="<?php echo e(route('dashboard')); ?>"><img src="<?php echo e(asset('images/logoastabayav3.png')); ?>" width="150" height="45" alt="logo" /></a>
          <button class="navbar-toggler" type="button" id="sidebarToggle" aria-label="Toggle sidebar">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
          <!-- Logo akan dipindahkan ke sini ketika sidebar collapsed -->
          <div class="navbar-brand-moved d-none">
            <a class="navbar-brand brand-logo-moved" href="<?php echo e(route('dashboard')); ?>"><img src="<?php echo e(asset('images/logoastabayav3.png')); ?>" width="150" height="45" alt="logo" /></a>
          </div>
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                <i class="bi bi-bookmark-star mx-0"></i>
                <?php
                  $bookmarkedCount = isset($bookmarked_items) && is_array($bookmarked_items) ? count($bookmarked_items) : 0;
                ?>
                <?php if($bookmarkedCount == 0): ?>
                <span class="count" id="bookmarkCount" style="display: none;"></span>
                <?php else: ?>
                <span class="count" id="bookmarkCount"></span>
                <?php endif; ?>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" id="bookmarkDropdown" aria-labelledby="notificationDropdown">
                <p class="mb-0 font-weight-normal float-left dropdown-header">Bookmark</p>
                <div id="bookmarkList">
                <?php $__empty_1 = true; $__currentLoopData = $bookmarked_items ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a class="dropdown-item preview-item" href="<?php echo e($item->url); ?>">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-primary">
                        <i class="<?php echo e($item->icon_class ?? 'bi bi-bookmark-fill'); ?> mx-0"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal"><?php echo e($item->title); ?></h6>
                  </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                  <p class="text-center p-3 text-muted" id="emptyBookmarkMessage">Tidak ada bookmark.</p>
                <?php endif; ?>
                </div>
              </div>
            </li>
            <?php if(auth()->guard()->check()): ?>
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                <?php if(strlen(auth()->user()->username) >= 2): ?>
                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px; text-transform: uppercase;">
                  <?php echo e(strtoupper(substr(auth()->user()->username, 0, 2))); ?>

                </div>
                <?php else: ?>
                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px; text-transform: uppercase;">
                  <?php echo e(strtoupper(substr(auth()->user()->username, 0, 1))); ?>

                </div>
                <?php endif; ?>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                <button type="button" class="dropdown-item-button" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal" style="background: none; border: none; width: 100%; text-align: left; padding: 8px 16px; cursor: pointer;"><i class="ti-power-off text-primary"></i>Keluar</button>
              </div>
            </li>
            <?php else: ?>
            <li class="nav-item">
              <div class="d-flex gap-2 align-items-center">
                <a href="<?php echo e(route('login')); ?>" class="btn btn-sm btn-outline-primary" style="white-space: nowrap;">Masuk</a>
                <a href="<?php echo e(route('signup')); ?>" class="btn btn-sm btn-primary" style="white-space: nowrap;">Daftar</a>
              </div>
            </li>
            <?php endif; ?>
          </ul>
        </div>
      </nav>
      <div class="container-fluid page-body-wrapper">
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('dashboard')); ?>">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('inflasi')); ?>">
                <i class="bi bi-graph-up-arrow menu-icon"></i>
                <span class="menu-title">Inflasi</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#PDRB" aria-expanded="false" aria-controls="PDRB">
                <i class="bi bi-cash-stack menu-icon"></i>
                <span class="menu-title">PDRB</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="PDRB">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"><a class="nav-link" href="<?php echo e(route('pdrb-pengeluaran')); ?>">PDRB Pengeluaran</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo e(route('pdrb-lapangan-usaha')); ?>">PDRB Lapangan Usaha</a></li>
                </ul>
              </div>

            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('kemiskinan')); ?>">
                <i class="bi bi-heart-pulse menu-icon"></i>
                <span class="menu-title">Kemiskinan</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('kependudukan')); ?>">
                <i class="bi bi-people menu-icon"></i>
                <span class="menu-title">Kependudukan</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('ketenagakerjaan')); ?>">
                <i class="bi bi-briefcase menu-icon"></i>
                <span class="menu-title">Ketenagakerjaan</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('hotel-occupancy')); ?>" >
                <i class="bi bi-luggage menu-icon"></i>
                <span class="menu-title">Tingkat Hunian Hotel</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#pembangunan-manusia" aria-expanded="false" aria-controls="pembangunan-manusia">
                <i class="bi bi-buildings menu-icon"></i>
                <span class="menu-title">Pembangunan Manusia</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="pembangunan-manusia">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"><a class="nav-link" href="<?php echo e(route('indeks-pembangunan-manusia')); ?>">IPM</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo e(route('ipm-uhh-sp')); ?>">UHH SP</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo e(route('ipm-hls')); ?>">HLS</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo e(route('ipm-rls')); ?>">RLS</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo e(route('ipm-pengeluaran-per-kapita')); ?>">Pengeluaran per Kapita</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo e(route('ipm-indeks-kesehatan')); ?>">Indeks Kesehatan</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo e(route('ipm-indeks-hidup-layak')); ?>">Indeks Hidup Layak</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo e(route('ipm-indeks-pendidikan')); ?>">Indeks Pendidikan</a></li>
                </ul>
              </div>
            </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('gini-ratio')); ?>">
                  <i class="bi bi-percent menu-icon"></i>
                  <span class="menu-title">Gini ratio</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('publications')); ?>">
                  <i class="icon-book menu-icon"></i>
                  <span class="menu-title">Publikasi</span>
                </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('infographics')); ?>">
                <i class="bi bi-bar-chart-line menu-icon"></i>
                <span class="menu-title">Infografis</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo e(route('news')); ?>">
                <i class="bi bi-file-earmark-text menu-icon"></i>
                <span class="menu-title">Berita</span>
              </a>
            </li>
          </ul>
        </nav>
        <div class="main-panel">
          <div class="content-wrapper">
            <?php echo $__env->yieldContent('content'); ?>
          </div>
          <input type="checkbox" id="check" /> <label class="chat-btn" for="check"> <i class="fa fa-commenting-o comment"></i> <i class="fa fa-close close"></i> </label>
          <div class="wrapper">
            <div class="header"><h6>Aastabaya Chat</h6></div>
            <div class="chat-area" id="chat-box"></div>
            <div class="typing-area">
              <form id="chat-form" class="d-flex">
                <input type="text" id="message-input" class="form-control" placeholder="Ketik pesan..." autocomplete="off" disabled />
                <button type="submit" class="btn btn-primary ms-2" disabled><i class="fa fa-paper-plane"></i></button>
              </form>
              <div style="text-align: center; padding: 10px; color: #666; font-size: 12px; font-style: italic;">
                Coming soon, tunggu pengembangan lebih lanjut
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted text-center d-block d-sm-inline-block w-100">Copyright © 2025 BPS Kota Surabaya. All rights reserved.</span>
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- Core JS from template -->
    <script src="<?php echo e(asset('assets/vendors/js/vendor.bundle.base.js')); ?>"></script>
    <!-- Plugin JS -->
    <script src="<?php echo e(asset('assets/vendors/chart.js/chart.umd.js')); ?>"></script>
    <!-- Custom JS for template -->
    <script src="<?php echo e(asset('assets/js/off-canvas.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/template.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/settings.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/todolist.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/jquery.cookie.js')); ?>" type="text/javascript"></script>
    <script src="<?php echo e(asset('assets/js/dashboard.js')); ?>"></script>
    <!-- External JS from CDN -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
      // WebSocket connection disabled - Chat feature coming soon
      // Chat feature is currently disabled, waiting for further development
      try {
        const chatBox = document.getElementById("chat-box");
        if (chatBox) {
          // Display coming soon message in chat area
          const comingSoonMsg = document.createElement("div");
          comingSoonMsg.className = "chat-message incoming";
          comingSoonMsg.style.textAlign = "center";
          comingSoonMsg.style.padding = "20px";
          comingSoonMsg.style.color = "#666";
          comingSoonMsg.innerHTML = `<span style="font-style: italic;">Fitur chat sedang dalam pengembangan. Mohon tunggu update selanjutnya.</span>`;
          chatBox.appendChild(comingSoonMsg);
        }
      } catch (error) {
        // Chat feature !available
        console.log("Chat feature is disabled - coming soon");
      }
      // Function to switch section
      function switchTable(type) {
        // Hide all sections
        const publicationsSection = document.getElementById("publicationsSection");
        const newsSection = document.getElementById("newsSection");
        const infographicsSection = document.getElementById("infographicsSection");

        if (publicationsSection) publicationsSection.style.display = "none";
        if (newsSection) newsSection.style.display = "none";
        if (infographicsSection) infographicsSection.style.display = "none";

        // Show selected section
        if (type === "news") {
          if (newsSection) {
            newsSection.style.display = "block";
            // Scroll to section
            setTimeout(() => {
              newsSection.scrollIntoView({ behavior: "smooth", block: "start" });
            }, 100);
          }
        } else if (type === "infographic") {
          if (infographicsSection) {
            infographicsSection.style.display = "block";
            // Scroll to section
            setTimeout(() => {
              infographicsSection.scrollIntoView({ behavior: "smooth", block: "start" });
            }, 100);
          }
        } else if (type === "publication") {
          if (publicationsSection) {
            publicationsSection.style.display = "block";
            // Scroll to section
            setTimeout(() => {
              publicationsSection.scrollIntoView({ behavior: "smooth", block: "start" });
            }, 100);
          }
        }
      }

      // Function to switch carousel && table
      function switchCarousel(type) {
        // Hide all carousels
        const carouselNewsEl = document.getElementById("carouselNews");
        const carouselInfographicEl = document.getElementById("carouselInfographic");
        const carouselPublicationEl = document.getElementById("carouselPublication");

        // Hide all carousels first
        if (carouselNewsEl) carouselNewsEl.style.display = "none";
        if (carouselInfographicEl) carouselInfographicEl.style.display = "none";
        if (carouselPublicationEl) carouselPublicationEl.style.display = "none";

        // Show selected carousel && initialize if needed
        if (type === "news") {
          if (carouselNewsEl) carouselNewsEl.style.display = "block";
          // Initialize or get carousel instance
          let carouselNews = bootstrap.Carousel.getInstance(carouselNewsEl);
          if (!carouselNews) {
            carouselNews = new bootstrap.Carousel(carouselNewsEl, { ride: "carousel" });
          }
          carouselNews.to(0);
        } else if (type === "infographic") {
          if (carouselInfographicEl) carouselInfographicEl.style.display = "block";
          // Initialize or get carousel instance
          let carouselInfographic = bootstrap.Carousel.getInstance(carouselInfographicEl);
          if (!carouselInfographic) {
            carouselInfographic = new bootstrap.Carousel(carouselInfographicEl);
          }
          carouselInfographic.to(0);
        } else if (type === "publication") {
          if (carouselPublicationEl) carouselPublicationEl.style.display = "block";
          // Initialize or get carousel instance
          let carouselPublication = bootstrap.Carousel.getInstance(carouselPublicationEl);
          if (!carouselPublication) {
            carouselPublication = new bootstrap.Carousel(carouselPublicationEl);
          }
          carouselPublication.to(0);
        }

        // Also switch table
        switchTable(type);
      }

      // Initialize on page load - show news section by default
      document.addEventListener("DOMContentLoaded", function () {
        const publicationsSection = document.getElementById("publicationsSection");
        const newsSection = document.getElementById("newsSection");
        const infographicsSection = document.getElementById("infographicsSection");

        // Hide other sections
        if (publicationsSection) publicationsSection.style.display = "none";
        if (infographicsSection) infographicsSection.style.display = "none";

        // News section is shown by default (display: block in HTML)
        // Ensure carousel news is visible
        const carouselNewsEl = document.getElementById("carouselNews");
        if (carouselNewsEl) {
          carouselNewsEl.style.display = "block";
        }
      });

      // Publication, Infographic & News Modal Functionality
      const publicationsData = [];
      const infographicCardsData = [];
      const newsCardsData = [];

      // Load publication data from HTML attributes
      document.addEventListener("DOMContentLoaded", function () {
        const publicationElements = document.querySelectorAll(".publication-data");
        publicationElements.forEach((el) => {
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
          
          publicationsData.push({
            title: el.dataset.title || '',
            image: imageUrl,
            date: el.dataset.date || 'N/A',
            size: el.dataset.size || 'N/A',
            pubId: el.dataset.pubId || '',
            abstract: el.dataset.abstract || '',
            download: el.dataset.download || '',
          });
          
          // Also create a map by pubId for faster lookup
          if (!window.publicationsDataMap) {
            window.publicationsDataMap = {};
          }
          if (el.dataset.pubId) {
            window.publicationsDataMap[el.dataset.pubId] = publicationsData[publicationsData.length - 1];
          }
        });

        const infographicElements = document.querySelectorAll(".infographic-card-data");
        infographicElements.forEach((el) => {
          // Get image URL directly from data attribute (don't use dataset.image as it may convert to camelCase)
          let imageUrl = el.getAttribute('data-image') || '';
          
          // Log for debugging
          console.log('Loading infographic data:', {
            title: el.dataset.title,
            image: imageUrl,
            download: el.dataset.download
          });
          
          infographicCardsData.push({
            title: el.dataset.title || '',
            image: imageUrl, // Use URL as-is
            download: el.dataset.download || '',
          });
        });
        
        console.log('Total infographics loaded:', infographicCardsData.length);

        const newsCardElements = document.querySelectorAll(".news-card-data");
        newsCardElements.forEach((el) => {
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
          
          newsCardsData.push({
            title: el.dataset.title || '',
            category: el.dataset.category || 'Umum',
            date: el.dataset.date || 'N/A',
            image: imageUrl,
            content: el.dataset.content || '',
          });
        });

        // Clean news content in listing
        function cleanNewsListingContent() {
          // Clean content in news cards on dashboard
          const newsContentElements = document.querySelectorAll('#newsSection .card-text');
          newsContentElements.forEach(function(el) {
            if (el.textContent) {
              let content = el.textContent;
              
              // Decode Unicode escape sequences first
              try {
                content = content.replace(/\\u([0-9a-fA-F]{4})/g, function(match, hex) {
                  return String.fromCharCode(parseInt(hex, 16));
                });
              } catch (e) {
                // Continue if decoding fails
              }
              
              // Remove HTML tags && style attributes
              const tempDiv = document.createElement('div');
              tempDiv.innerHTML = content;
              content = tempDiv.textContent || tempDiv.innerText || '';
              
              // Remove style attributes patterns that might remain
              content = content.replace(/style\s*=\s*["'][^"']*["']/gi, '');
              content = content.replace(/<[^>]+>/g, '');
              
              // Clean control characters && HTML entities
              content = content.replace(/[\u0000-\u001F\u007F-\u009F]/g, ' ');
              content = content.replace(/&nbsp;/gi, ' ').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>');
              content = content.replace(/&amp;/gi, '&').replace(/&quot;/gi, '"').replace(/&#39;/gi, "'");
              content = content.replace(/&apos;/gi, "'");
              
              // Normalize whitespace
              content = content.replace(/[\s\t]+/g, ' ').trim();
              
              // Update the element
              el.textContent = content;
            }
          });
        }

        // Clean news content on page load
        cleanNewsListingContent();

        // Lazy loading for publication images
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

      function showPublicationModal(pubId, index) {
        // Try to find publication by pubId first (more reliable)
        let pub = null;
        if (pubId && window.publicationsDataMap && window.publicationsDataMap[pubId]) {
          pub = window.publicationsDataMap[pubId];
        } else if (index !== undefined && publicationsData[index]) {
          // Fallback to index if pubId not found
          pub = publicationsData[index];
        } else {
          console.error('Publication not found', { pubId, index });
          alert('Error: Publication data not found');
          return;
        }

        document.getElementById("modalTitleDashboard").textContent = pub.title;
        
        // Set image with error handling
        const modalImage = document.getElementById("modalImageDashboard");
        const placeholderImg = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 150 200"%3E%3Crect fill="%23f0f0f0" width="150" height="200"/%3E%3Ctext fill="%23999" x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-size="14" font-family="Arial"%3ENo Image%3C/text%3E%3C/svg%3E';
        
        // Try to get image from already loaded thumbnail first (find by pubId)
        let thumbnailSrc = null;
        if (pubId) {
          const thumbnailImages = document.querySelectorAll('.lazy-load');
          thumbnailImages.forEach(function(thumbImg) {
            if (thumbImg.dataset.pubId === pubId) {
              // Check if thumbnail has been loaded (!the placeholder)
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
        
        document.getElementById("modalDateDashboard").textContent = pub.date;
        document.getElementById("modalSizeDashboard").textContent = pub.size;
        document.getElementById("modalPubIdDashboard").textContent = pub.pubId;
        
        // Clean abstract from special characters like \u000D\u000A (carriage return && line feed)
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
        
        // Remove actual carriage return (\r) && line feed (\n) characters
        cleanAbstract = cleanAbstract.replace(/\r\n/g, ' ').replace(/\n/g, ' ').replace(/\r/g, ' ');
        
        // Remove Unicode control characters (including \u000D && \u000A)
        cleanAbstract = cleanAbstract.replace(/[\u0000-\u001F\u007F-\u009F]/g, ' ');
        
        // Replace multiple spaces/tabs with single space
        cleanAbstract = cleanAbstract.replace(/[\s\t]+/g, ' ').trim();
        
        document.getElementById("modalAbstractDashboard").textContent = cleanAbstract;
        
        document.getElementById("modalDownloadDashboard").href = pub.download || '#';

        const modal = new bootstrap.Modal(document.getElementById("publicationModalDashboard"));
        modal.show();
      }

      function showNewsCardModal(index) {
        const news = newsCardsData[index];
        if (!news) return;

        const modalTitle = document.getElementById("newsModalTitle");
        const modalCategory = document.getElementById("newsModalCategory");
        const modalDate = document.getElementById("newsModalDate");
        const modalContent = document.getElementById("newsModalContent");
        const modalImageContainer = document.getElementById("newsModalImageContainer");

        modalTitle.textContent = news.title;
        modalCategory.textContent = news.category || "News";
        modalDate.innerHTML = `<i class="bi bi-calendar"></i> ${news.date}`;
        
        // Clean content from HTML tags && special characters
        let cleanContent = news.content || '';
        
        // First, decode all Unicode escape sequences (\uXXXX) to their actual characters
        try {
            cleanContent = cleanContent.replace(/\\u([0-9a-fA-F]{4})/g, function(match, hex) {
                return String.fromCharCode(parseInt(hex, 16));
            });
        } catch (e) {
            // If decoding fails, continue with original string
        }
        
        // Now that Unicode escapes are decoded, remove HTML tags && their attributes
        // Remove style attributes && their content first
        cleanContent = cleanContent.replace(/style\s*=\s*["'][^"']*["']/gi, '');
        cleanContent = cleanContent.replace(/style\s*=\s*[^\s>]*/gi, '');
        
        // Remove all HTML tags
        cleanContent = cleanContent.replace(/<[^>]+>/g, '');
        
        // Remove HTML tags but preserve text content (redundant but safe)
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
        
        modalContent.textContent = cleanContent;

        // Set image with error handling && try to get from thumbnail if available
        const placeholderImg = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300"%3E%3Crect fill="%23f0f0f0" width="400" height="300"/%3E%3Ctext fill="%23999" x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-size="16" font-family="Arial"%3EImage !available%3C/text%3E%3C/svg%3E';
        
        // Clear container first
        modalImageContainer.innerHTML = '';
        
        // Try to get image from already loaded thumbnail first
        const thumbnailImages = document.querySelectorAll('.lazy-load');
        let thumbnailSrc = null;
        if (thumbnailImages[index]) {
          const thumbImg = thumbnailImages[index];
          // Check if thumbnail has been loaded (!the placeholder)
          if (thumbImg.src && !thumbImg.src.includes('data:image/svg+xml') && thumbImg.src !== placeholderImg) {
            thumbnailSrc = thumbImg.src;
          }
          // Also check data-src if src is still placeholder
          else if (thumbImg.dataset && thumbImg.dataset.src) {
            thumbnailSrc = thumbImg.dataset.src;
          }
        }
        
        // Set image source - prefer thumbnail if available, otherwise use news.image
        const imageToLoad = thumbnailSrc || news.image;
        
        if (imageToLoad && imageToLoad.trim() !== '') {
          const imgElement = document.createElement('img');
          imgElement.src = imageToLoad;
          imgElement.alt = news.title || 'News image';
          imgElement.className = 'img-fluid';
          imgElement.style.width = '100%';
          imgElement.style.maxHeight = '300px';
          imgElement.style.objectFit = 'cover';
          imgElement.style.borderRadius = '8px';
          imgElement.style.display = 'block';
          
          imgElement.onerror = function() {
            // If image fails to load, try the other source or show placeholder
            if (this.src === thumbnailSrc && news.image && news.image.trim() !== '' && news.image !== imageToLoad) {
              // Try the original news.image if thumbnail failed
              this.src = news.image;
        } else {
              // Both failed, show placeholder
              modalImageContainer.innerHTML = `<div class="bg-light rounded d-flex align-items-center justify-content-center p-5 mb-3">
                  <div class="text-center">
                      <i class="bi bi-image" style="font-size: 4rem; color: #ccc;"></i>
                      <p class="text-muted mt-2 mb-0">Image !available</p>
                  </div>
              </div>`;
            }
          };
          
          imgElement.onload = function() {
            this.style.display = 'block';
          };
          
          modalImageContainer.appendChild(imgElement);
        } else {
          // If no image URL, show placeholder
          modalImageContainer.innerHTML = `<div class="bg-light rounded d-flex align-items-center justify-content-center p-5 mb-3">
              <div class="text-center">
                  <i class="bi bi-image" style="font-size: 4rem; color: #ccc;"></i>
                  <p class="text-muted mt-2 mb-0">Image !available</p>
              </div>
          </div>`;
        }

        const modal = new bootstrap.Modal(document.getElementById("newsCardModal"));
        modal.show();
      }

      function showInfographicModal(index) {
        const info = infographicCardsData[index];
        if (!info) {
          console.error('Infographic data not found for index:', index);
          return;
        }

        console.log('Showing infographic modal:', { index, title: info.title, image: info.image });

        document.getElementById("infographicModalTitle").textContent = info.title;
        
        // Set image with error handling - use info.image directly from infographicCardsData
        const modalImage = document.getElementById("infographicModalImage");
        const placeholderImg = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400"%3E%3Crect fill="%23f0f0f0" width="400" height="400"/%3E%3Ctext fill="%23999" x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-size="16" font-family="Arial"%3EImage !available%3C/text%3E%3C/svg%3E';
        
        // Use info.image directly from infographicCardsData to ensure correct image
        // Try to decode URL if it's encoded
        let imageToLoad = info.image || '';
        if (imageToLoad) {
          try {
            // Try decoding if it's URL encoded
            imageToLoad = decodeURIComponent(imageToLoad);
          } catch (e) {
            // If decoding fails, use original
            imageToLoad = info.image;
          }
        }
        
        console.log('Image URL to load:', imageToLoad);
        
        // Show loading state
        modalImage.src = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400"%3E%3Crect fill="%23f5f5f5" width="400" height="400"/%3E%3Ctext fill="%23999" x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-size="16" font-family="Arial"%3ELoading...%3C/text%3E%3C/svg%3E';
        modalImage.alt = info.title;
        modalImage.style.display = 'block';
        
        if (imageToLoad && imageToLoad.trim() !== '' && imageToLoad !== 'null' && imageToLoad !== 'undefined') {
          // Preload image to check if it's valid
          const tempImg = new Image();
          tempImg.onload = function() {
            console.log('Image loaded successfully:', imageToLoad);
            modalImage.src = imageToLoad;
            modalImage.alt = info.title || 'Infographic image';
          };
          tempImg.onerror = function() {
            console.error('Failed to load image:', imageToLoad);
            // If image fails to load, show placeholder
            modalImage.src = placeholderImg;
            modalImage.alt = 'Image !available';
          };
          tempImg.src = imageToLoad;
        } else {
          console.warn('Invalid || empty image URL:', imageToLoad);
          modalImage.src = placeholderImg;
          modalImage.alt = 'Image !available';
        }
        
        document.getElementById("infographicModalDownload").href = info.download || '#';
        document.getElementById("infographicModalDownload").setAttribute('data-infographic-id', '');
        document.getElementById("infographicModalDownload").setAttribute('data-infographic-title', info.title || '');

        // Populate related infographics (show other infographics from the same list)
        const relatedContainer = document.getElementById("relatedInfographics");
        if (relatedContainer) {
          relatedContainer.innerHTML = '';
          
          // Show up to 4 related infographics (excluding current one)
          const relatedCount = Math.min(4, infographicCardsData.length - 1);
          let shown = 0;
          
          for (let i = 0; i < infographicCardsData.length && shown < relatedCount; i++) {
            if (i !== index) {
              const relatedInfo = infographicCardsData[i];
              const relatedItem = document.createElement('a');
              relatedItem.className = 'related-infographic-item';
              relatedItem.href = '#';
              relatedItem.onclick = function(e) {
                e.preventDefault();
                showInfographicModal(i);
              };
              
              // Use image directly from infographicCardsData
              const relatedThumbnail = relatedInfo.image || placeholderImg;
              
              relatedItem.innerHTML = `
                <img src="${relatedThumbnail}" alt="${relatedInfo.title}" onerror="this.src='${placeholderImg}'" />
                <div class="content">
                  <div class="title">${relatedInfo.title}</div>
                </div>
              `;
              
              relatedContainer.appendChild(relatedItem);
              shown++;
            }
          }
          
          // If no related items, show message
          if (shown === 0) {
            relatedContainer.innerHTML = '<p class="text-muted small">Tidak ada infografis terkait</p>';
          }
        }

        const modal = new bootstrap.Modal(document.getElementById("infographicModal"));
        modal.show();
      }
      // Format numbers with Indonesian format (replace comma with dot)
      (function() {
        const formattedNumbers = document.querySelectorAll('.formatted-number');
        formattedNumbers.forEach(el => {
          let text = el.textContent.trim();
          // Replace comma with dot for Indonesian number format
          text = text.replace(/,/g, '.');
          el.textContent = text;
        });
        
        // Format currency values && add ribu/juta if needed
        const currencyValues = document.querySelectorAll('.currency-value[data-value]');
        currencyValues.forEach(el => {
          const value = parseFloat(el.dataset.value);
          if (!isNaN(value)) {
            const formattedSpan = el.querySelector('.formatted-number');
            if (formattedSpan) {
              let text = formattedSpan.textContent.trim().replace(/,/g, '.');
              let suffix = '';
              if (value >= 1000000) {
                suffix = ' juta';
              } else if (value >= 1000) {
                suffix = ' ribu';
              }
              formattedSpan.textContent = text + suffix;
            }
          }
        });
      })();

      // Indicat|| Carousel - Continuous Infinite Scroll to Right
      (function () {
        const carousel = document.getElementById("indicatorCarousel");
        if (!carousel) return;

        const contentSets = carousel.querySelectorAll(".indicator-carousel-content");
        if (contentSets.length < 2) return;

        // Get width of one content set
        function getContentSetWidth() {
          return contentSets[0] ? contentSets[0].offsetWidth + 20 : 0; // +20 for gap
        }

        let currentPosition = 0;
        let isPaused = false;
        let animationFrameId;
        const scrollSpeed = 1.5; // pixels per frame (adjust for speed)

        function animate() {
          if (!isPaused) {
            const contentSetWidth = getContentSetWidth();
            
            // Move to the right (negative translateX = content moves right)
            currentPosition += scrollSpeed;

            // When we've scrolled past one complete set, reset seamlessly
            if (currentPosition >= contentSetWidth) {
              // Reset position without transition for seamless loop
              currentPosition = currentPosition - contentSetWidth;
            }

            carousel.style.transform = `translateX(-${currentPosition}px)`;
          }

          animationFrameId = requestAnimationFrame(animate);
        }

        // Pause on hover
        const carouselWrapper = carousel.closest(".indicator-carousel-wrapper");
        if (carouselWrapper) {
          carouselWrapper.addEventListener("mouseenter", () => {
            isPaused = true;
          });

          carouselWrapper.addEventListener("mouseleave", () => {
            isPaused = false;
          });
        }

        // Start animation
        animate();

        // Handle window resize - maintain collapsed state when switching to mobile
        let resizeTimeout;
        window.addEventListener("resize", () => {
          clearTimeout(resizeTimeout);
          resizeTimeout = setTimeout(() => {
            // When switching to mobile view, ensure sidebar is collapsed
            if (window.innerWidth <= 991) {
              // If sidebar is !collapsed, collapse it
              if (!sidebar.classList.contains('collapsed')) {
                sidebar.classList.add('collapsed');
                document.body.classList.add('sidebar-icon-only');
                
                // Handle logo visibility for collapsed state
                const navbarBrandWrapper = document.querySelector('.navbar-brand-wrapper');
                const navbarMenuWrapper = document.querySelector('.navbar-menu-wrapper');
                const brandLogo = navbarBrandWrapper?.querySelector('.brand-logo');
                const brandLogoMini = navbarBrandWrapper?.querySelector('.brand-logo-mini');
                const navbarBrandMoved = navbarMenuWrapper?.querySelector('.navbar-brand-moved');
                const brandLogoMoved = navbarMenuWrapper?.querySelector('.brand-logo-moved');
                const brandLogoMiniMoved = navbarMenuWrapper?.querySelector('.brand-logo-mini-moved');
                
                if (brandLogo) brandLogo.style.display = 'none';
                if (brandLogoMini) brandLogoMini.style.display = 'none';
                if (navbarBrandMoved) {
                  navbarBrandMoved.classList.remove('d-none');
                  if (brandLogoMoved) brandLogoMoved.style.display = 'block';
                  if (brandLogoMiniMoved) brandLogoMiniMoved.style.display = 'none';
                }
                if (navbarMenuWrapper) {
                  navbarMenuWrapper.style.justifyContent = 'flex-start';
                }
              }
              // Remove active class on mobile resize if sidebar should be hidden
              sidebar.classList.remove('active');
            } else {
              // When switching to desktop view, maintain collapsed state
              // Don't automatically expand, keep current state
            }
            const contentSetWidth = getContentSetWidth();
            if (currentPosition >= contentSetWidth) {
              currentPosition = currentPosition % contentSetWidth;
            }
          }, 250);
        });
      })();

      // Sidebar toggle functionality
      document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const body = document.body;
        const navbarBrandWrapper = document.querySelector('.navbar-brand-wrapper');
        const navbarMenuWrapper = document.querySelector('.navbar-menu-wrapper');
        const brandLogo = navbarBrandWrapper?.querySelector('.brand-logo');
        const brandLogoMini = navbarBrandWrapper?.querySelector('.brand-logo-mini');
        const brandLogoMoved = navbarMenuWrapper?.querySelector('.brand-logo-moved');
        const brandLogoMiniMoved = navbarMenuWrapper?.querySelector('.brand-logo-mini-moved');
        const navbarBrandMoved = navbarMenuWrapper?.querySelector('.navbar-brand-moved');

        if (sidebarToggle && sidebar) {
          sidebarToggle.addEventListener('click', function() {
            const isCollapsed = sidebar.classList.contains('collapsed');
            const isMobile = window.innerWidth <= 991;
            
            if (isMobile) {
              // On mobile, toggle active class (show/hide sidebar overlay)
              // Sidebar always shows full width with text on mobile
              sidebar.classList.toggle('active');
              // Remove collapsed state on mobile - always show full sidebar
              sidebar.classList.remove('collapsed');
              body.classList.remove('sidebar-icon-only');
            } else {
              // On desktop, toggle collapsed state
              sidebar.classList.toggle('collapsed');
              body.classList.toggle('sidebar-icon-only');
              // Remove active class on desktop
              sidebar.classList.remove('active');
            }

            // Handle logo visibility based on collapsed state (only for desktop)
            if (!isMobile) {
              // Get current collapsed state after toggle
              const nowCollapsed = sidebar.classList.contains('collapsed');
              
              // Pindahkan logo ke navbar-menu-wrapper ketika sidebar collapsed
              if (nowCollapsed) {
                // Sidebar collapsed - sembunyikan logo di navbar-brand-wrapper
                if (brandLogo) brandLogo.style.display = 'none';
                if (brandLogoMini) brandLogoMini.style.display = 'none';
                
                // Tampilkan logo besar (A-removebg-preview.png) di navbar-menu-wrapper (paling kiri)
                if (navbarBrandMoved) {
                  navbarBrandMoved.classList.remove('d-none');
                  if (brandLogoMoved) brandLogoMoved.style.display = 'block';
                  if (brandLogoMiniMoved) brandLogoMiniMoved.style.display = 'none';
                }
                
                // Ubah justify-content navbar-menu-wrapper
                if (navbarMenuWrapper) {
                  navbarMenuWrapper.style.justifyContent = 'flex-start';
                }
              } else {
                // Sidebar expanded - kembalikan logo ke navbar-brand-wrapper
                if (brandLogo) brandLogo.style.display = 'block';
                if (brandLogoMini) brandLogoMini.style.display = 'none';
                
                // Sembunyikan logo di navbar-menu-wrapper
                if (navbarBrandMoved) {
                  navbarBrandMoved.classList.add('d-none');
                }
                
                // Kembalikan justify-content navbar-menu-wrapper
                if (navbarMenuWrapper) {
                  navbarMenuWrapper.style.justifyContent = 'flex-end';
                }
              }
            }
          });
        }

        // Handle window resize to maintain sidebar state consistency
        let resizeTimeout;
        let previousWidth = window.innerWidth;
        let sidebarStateBeforeMobile = null; // Store sidebar state before switching to mobile
        
        window.addEventListener('resize', function() {
          clearTimeout(resizeTimeout);
          resizeTimeout = setTimeout(function() {
            const currentWidth = window.innerWidth;
            const isMobile = currentWidth <= 991;
            const wasMobile = previousWidth <= 991;
            
            if (isMobile && !wasMobile) {
              // Switching from desktop to mobile - save current state
              sidebarStateBeforeMobile = sidebar.classList.contains('collapsed');
              
              // On mobile, sidebar always shows full width with text (!collapsed)
              sidebar.classList.remove('collapsed');
              body.classList.remove('sidebar-icon-only');
              
              // Handle logo visibility for expanded state on mobile
              if (brandLogo) brandLogo.style.display = 'block';
              if (brandLogoMini) brandLogoMini.style.display = 'none';
              if (navbarBrandMoved) {
                navbarBrandMoved.classList.add('d-none');
              }
              if (navbarMenuWrapper) {
                navbarMenuWrapper.style.justifyContent = 'flex-end';
              }
              
              // Remove active class on mobile resize to hide sidebar overlay initially
              sidebar.classList.remove('active');
            } else if (!isMobile && wasMobile) {
              // Switching from mobile to desktop - restore previous state
              sidebar.classList.remove('active');
              
              // Restore collapsed state if it was collapsed before
              if (sidebarStateBeforeMobile === true) {
                sidebar.classList.add('collapsed');
                body.classList.add('sidebar-icon-only');
                
                // Handle logo visibility for collapsed state
                if (brandLogo) brandLogo.style.display = 'none';
                if (brandLogoMini) brandLogoMini.style.display = 'none';
                if (navbarBrandMoved) {
                  navbarBrandMoved.classList.remove('d-none');
                  if (brandLogoMoved) brandLogoMoved.style.display = 'block';
                  if (brandLogoMiniMoved) brandLogoMiniMoved.style.display = 'none';
                }
                if (navbarMenuWrapper) {
                  navbarMenuWrapper.style.justifyContent = 'flex-start';
                }
              } else {
                // Sidebar was expanded before, keep it expanded
                sidebar.classList.remove('collapsed');
                body.classList.remove('sidebar-icon-only');
                
                // Handle logo visibility for expanded state
                if (brandLogo) brandLogo.style.display = 'block';
                if (brandLogoMini) brandLogoMini.style.display = 'none';
                if (navbarBrandMoved) {
                  navbarBrandMoved.classList.add('d-none');
                }
                if (navbarMenuWrapper) {
                  navbarMenuWrapper.style.justifyContent = 'flex-end';
                }
              }
            }
            
            previousWidth = currentWidth;
          }, 100);
        });
        
        // Initialize: check if on mobile on page load
        if (window.innerWidth <= 991) {
          sidebar.classList.remove('collapsed');
          body.classList.remove('sidebar-icon-only');
          sidebar.classList.remove('active');
        } else {
          // On desktop, save initial state
          sidebarStateBeforeMobile = sidebar.classList.contains('collapsed');
        }
        
        // Initialize previousWidth
        previousWidth = window.innerWidth;

        // Handle sidebar menu item clicks when sidebar is collapsed
        const sidebarNavLinks = sidebar.querySelectorAll('.nav-link');
        sidebarNavLinks.forEach(link => {
          link.addEventListener('click', function(e) {
            const isCollapsed = sidebar.classList.contains('collapsed');
            
            // Check if this link has a submenu (has data-bs-toggle="collapse")
            const hasSubmenu = this.hasAttribute('data-bs-toggle') && 
                              this.getAttribute('data-bs-toggle') === 'collapse';
            
            if (isCollapsed && hasSubmenu) {
              // Prevent default collapse behavior temporarily
              e.preventDefault();
              
              // Get the collapse target ID
              const collapseTargetId = this.getAttribute('href');
              const collapseTarget = document.querySelector(collapseTargetId);
              
              // Exp&& sidebar first
              sidebar.classList.remove('collapsed');
              body.classList.remove('sidebar-icon-only');
              
              // Handle logo visibility
              if (brandLogo) brandLogo.style.display = 'block';
              if (brandLogoMini) brandLogoMini.style.display = 'none';
              if (navbarBrandMoved) {
                navbarBrandMoved.classList.add('d-none');
              }
              if (navbarMenuWrapper) {
                navbarMenuWrapper.style.justifyContent = 'flex-end';
              }
              
              // Wait for sidebar animation to complete, then open the submenu
              setTimeout(() => {
                if (collapseTarget) {
                  // Use Bootstrap collapse API to show the submenu
                  const bsCollapse = new bootstrap.Collapse(collapseTarget, {
                    toggle: false
                  });
                  bsCollapse.show();
                  
                  // Set aria-expanded to true
                  this.setAttribute('aria-expanded', 'true');
                }
              }, 300); // Wait for sidebar animation (adjust timing if needed)
            }
            // If no submenu, let the default behavior happen (navigation)
          });
        });
      });
    </script>
    <style>
      /* Infinite Scroll Animation for Indicat|| Carousel - Continuous Right Scroll */
      .indicator-carousel-wrapper {
        overflow: hidden !important;
      }

      .indicator-carousel-track {
        display: flex !important;
        gap: 20px;
        will-change: transform;
        /* Animation handled by JavaScript for seamless continuous scroll */
      }

      .indicator-carousel-content {
        display: flex;
        gap: 20px;
        flex-shrink: 0;
        min-width: fit-content;
      }

      .hover-card {
        transition: all 0.3s ease;
      }

      .hover-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
      }

      .infographic-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(58, 87, 232, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
      }

      .infographic-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 35px rgba(58, 87, 232, 0.15);
      }

      .infographic-thumb {
        border-radius: 18px;
        overflow: hidden;
      }

      .infographic-thumb img {
        transition: transform 0.4s ease;
      }

      .infographic-card:hover .infographic-thumb img {
        transform: scale(1.03);
      }

      /* Card overlay && hover effects for infographic cards */
      .card-img-wrapper {
        position: relative;
        width: 100%;
        padding: 0;
        height: 280px;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      
      .card-img-wrapper img {
        width: 100%;
        max-width: 100%;
        max-height: 100%;
        height: auto;
        display: block;
        object-fit: contain;
        object-position: center;
        transition: transform 0.3s ease;
      }

      .infographic-card .card-body {
        flex-grow: 1;
      }

      .card-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.1) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
      }

      .infographic-card:hover .card-overlay {
        opacity: 1;
      }

      .infographic-card:hover .card-img-wrapper img {
        transform: scale(1.05);
      }

      .hover-shadow {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
      }

      .hover-shadow:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      }

      @media (max-width: 768px) {
        .card-img-wrapper {
          height: 240px;
        }
      }
      
      @media (max-width: 576px) {
        .card-img-wrapper {
          height: 200px;
        }
      }

      /* Carousel border radius */
      #carouselNewsContainer {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 60%;
        padding: 20px 0;
      }

      #carouselNews,
      #carouselInfographic,
      #carouselPublication {
        border-radius: 20px;
        overflow: hidden;
        width: 80%;
        max-width: 100%;
        max-height: 450px;
        height: auto;
        margin: 0 auto;
      }

      #carouselNews .carousel-inner,
      #carouselInfographic .carousel-inner,
      #carouselPublication .carousel-inner {
        border-radius: 20px;
        overflow: hidden;
        height: 100%;
        width: 100%;
      }

      #carouselNews .carousel-inner img,
      #carouselInfographic .carousel-inner img,
      #carouselPublication .carousel-inner img {
        border-radius: 20px;
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center;
        background-color: #f5f5f5;
      }

      /* Ensure carousel container card maintains proper width && height */
      .col-md-6.grid-margin.stretch-card.transparent .card {
        width: 100%;
        display: flex;
        flex-direction: column;
        min-height: 220px;
        height: auto;
      }

      /* Ensure carousel items maintain aspect ratio */
      #carouselNews .carousel-item,
      #carouselInfographic .carousel-item,
      #carouselPublication .carousel-item {
        height: 100%;
        width: 100%;
        position: relative;
      }

      /* Maintain aspect ratio for carousel images */
      #carouselNews .carousel-item img,
      #carouselInfographic .carousel-item img,
      #carouselPublication .carousel-item img {
        border-radius: 20px;
        object-fit: contain;
        object-position: center;
        background-color: #f5f5f5;
      }

      /* Ensure carousel maintains width && doesn't get squashed */
      .col-md-6.grid-margin.stretch-card.transparent {
        display: flex;
        flex-direction: column;
      }

      /* Ensure cards can be adjusted in height */
      .col-md-6.grid-margin.transparent .card {
        transition: height 0.3s ease;
        box-sizing: border-box;
      }

      /* Custom color for cards */
      .col-md-6.grid-margin.transparent .card.card-tale,
      .col-md-6.grid-margin.transparent .card.card-light-blue {
        background-color: #234C6A !important;
        color: #ffffff;
      }

      .col-md-6.grid-margin.transparent .card.card-tale:hover,
      .col-md-6.grid-margin.transparent .card.card-light-blue:hover {
        background-color: #2d5f82 !important;
      }

      /* Custom primary color - change all blue/primary colors to #234C6A */
      .text-primary,
      a.text-primary,
      .btn-link.text-primary {
        color: #234C6A !important;
      }

      .bg-primary,
      .badge.bg-primary {
        background-color: #234C6A !important;
        color: #ffffff !important;
      }

      .btn-primary,
      .btn-primary:hover,
      .btn-primary:focus,
      .btn-primary:active {
        background-color: #234C6A !important;
        border-color: #234C6A !important;
        color: #ffffff !important;
      }

      .btn-outline-primary {
        color: #234C6A !important;
        border-color: #234C6A !important;
      }

      .btn-outline-primary:hover,
      .btn-outline-primary:focus,
      .btn-outline-primary:active {
        background-color: #234C6A !important;
        border-color: #234C6A !important;
        color: #ffffff !important;
      }

      .col-md-6.grid-margin.transparent .card .card-body {
        height: 100%;
        display: flex;
        flex-direction: column;
        box-sizing: border-box;
      }

      /* Ensure rows don't add extra height */
      .col-md-6.grid-margin.transparent .row {
        margin-left: 0;
        margin-right: 0;
      }

      /* Ensure stretch-card doesn't interfere */
      .col-md-6.grid-margin.transparent .stretch-card {
        height: auto;
      }

      /* Responsive improvements */
      @media (max-width: 576px) {
        .card-body {
          padding: 1rem;
        }

        .card-title {
          font-size: 1.1rem;
        }

        .card-text {
          font-size: 0.9rem;
        }

        .btn-sm {
          font-size: 0.8rem;
          padding: 0.25rem 0.5rem;
        }

        .badge {
          font-size: 0.75rem;
        }

        #newsSection .col-12,
        #publicationsSection .col-12,
        #infographicsSection .col-12 {
          padding-left: 0.5rem;
          padding-right: 0.5rem;
        }

        /* Responsive styling untuk summary cards - 2 kolom */
        .summary-card {
          padding: 12px !important;
          min-height: 140px !important;
        }

        .summary-card h6 {
          font-size: 10px !important;
          margin-bottom: 6px !important;
        }

        .summary-card h3 {
          font-size: 20px !important;
        }

        .summary-card p {
          font-size: 10px !important;
          margin: 3px 0 0 0 !important;
        }

        .summary-card span {
          font-size: 10px !important;
        }

        .summary-card i {
          font-size: 40px !important;
        }

        .summary-card .d-flex {
          gap: 3px !important;
        }

        /* Spacing antar kartu di mobile */
        .row .col-6.mb-3 {
          padding-left: 8px;
          padding-right: 8px;
        }
      }

      @media (min-width: 577px) && (max-width: 768px) {
        .card-body {
          padding: 1.25rem;
        }
      }

      /* Ensure proper spacing on all devices */
      #newsSection,
      #publicationsSection,
      #infographicsSection {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
      }

      @media (min-width: 992px) {
        #newsSection,
        #publicationsSection,
        #infographicsSection {
          padding-left: 0;
          padding-right: 0;
        }
      }

      /* Modal Publication Detail Dashboard - Smaller size with proper spacing */
      #publicationModalDashboard .modal-dialog {
        max-width: 480px;
        margin: 1.75rem auto;
        display: flex;
        align-items: center;
        min-height: calc(100% - 3.5rem);
      }

      #publicationModalDashboard .modal-content {
        max-height: 80vh;
        display: flex;
        flex-direction: column;
        width: 100%;
      }

      #publicationModalDashboard .modal-header {
        flex-shrink: 0;
        padding: 0.5rem 1rem;
        border-bottom: 1px solid #dee2e6;
      }

      #publicationModalDashboard .modal-header .modal-title {
        font-size: 0.875rem;
        font-weight: 600;
      }

      #publicationModalDashboard .modal-body {
        flex: 1 1 auto;
        overflow-y: visible;
        overflow-x: hidden;
        padding: 0.75rem;
        padding-bottom: 0.5rem;
        min-height: 0;
        max-height: none;
      }

      #publicationModalDashboard .modal-body .row {
        margin-left: 0;
        margin-right: 0;
      }

      #publicationModalDashboard .modal-body .col-md-3 {
        padding-right: 0.5rem;
        padding-left: 0;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      #publicationModalDashboard .modal-body .col-md-9 {
        padding-left: 0.75rem;
        padding-right: 0;
      }

      #publicationModalDashboard .modal-body .col-md-3 img {
        margin-right: 0;
      }

      #publicationModalDashboard .modal-body .mb-2 {
        font-size: 0.75rem;
      }

      #publicationModalDashboard .modal-body strong {
        font-size: 0.75rem;
        font-weight: 600;
      }

      #publicationModalDashboard .modal-footer {
        flex-shrink: 0;
        padding: 0.5rem 1rem;
        border-top: 1px solid #dee2e6;
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
        margin-top: 0;
      }

      #publicationModalDashboard .modal-footer .btn {
        font-size: 0.8125rem;
        padding: 0.4rem 0.8rem;
      }

      #publicationModalDashboard .abstract-container {
        margin-bottom: 0;
        padding-bottom: 0.5rem;
      }

      #publicationModalDashboard .abstract-container strong {
        font-size: 0.75rem;
        font-weight: 600;
      }

      #publicationModalDashboard #modalAbstractDashboard {
        word-wrap: break-word;
        white-space: normal;
        line-height: 1.5;
        font-size: 0.6875rem;
        margin-bottom: 0;
        max-height: 200px;
        overflow-y: auto;
        padding-right: 0.5rem;
      }

      /* Custom scrollbar for abstract */
      #publicationModalDashboard #modalAbstractDashboard::-webkit-scrollbar {
        width: 6px;
      }

      #publicationModalDashboard #modalAbstractDashboard::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
      }

      #publicationModalDashboard #modalAbstractDashboard::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
      }

      #publicationModalDashboard #modalAbstractDashboard::-webkit-scrollbar-thumb:hover {
        background: #555;
      }

      /* Responsive adjustments */
      @media (max-width: 992px) {
        #publicationModalDashboard .modal-dialog {
          max-width: 80%;
        }

        #publicationModalDashboard #modalImageDashboard {
          max-height: 280px !important;
        }

        #publicationModalDashboard .modal-body {
          overflow-y: visible;
          max-height: none;
        }

        #publicationModalDashboard .abstract-container {
          padding-bottom: 0.5rem;
        }

        #publicationModalDashboard #modalAbstractDashboard {
          font-size: 0.6875rem;
          line-height: 1.5;
          max-height: 180px;
        }
      }

      @media (max-width: 768px) {
        #publicationModalDashboard .modal-dialog {
          max-width: 90%;
          margin: 0.5rem auto;
        }

        #publicationModalDashboard .modal-header {
          padding: 0.5rem 0.75rem;
        }

        #publicationModalDashboard .modal-header .modal-title {
          font-size: 0.8125rem;
        }

        #publicationModalDashboard .modal-body {
          padding: 0.5rem;
          padding-bottom: 0.5rem;
          overflow-y: visible;
          max-height: none;
        }

        #publicationModalDashboard .modal-body .col-md-3 {
          padding-right: 0.5rem;
          padding-left: 0;
        }

        #publicationModalDashboard .modal-body .col-md-9 {
          padding-left: 0.5rem;
          padding-right: 0;
        }

        #publicationModalDashboard .modal-body .mb-2 {
          font-size: 0.6875rem;
        }

        #publicationModalDashboard .modal-body strong {
          font-size: 0.6875rem;
        }

        #publicationModalDashboard .abstract-container {
          padding-bottom: 0.5rem;
          margin-bottom: 0;
        }

        #publicationModalDashboard .abstract-container strong {
          font-size: 0.6875rem;
        }

        #publicationModalDashboard #modalAbstractDashboard {
          font-size: 0.625rem;
          line-height: 1.45;
          max-height: 150px;
        }

        #publicationModalDashboard .modal-footer {
          flex-direction: column;
          padding: 0.5rem 0.75rem;
          margin-top: 0;
        }

        #publicationModalDashboard .modal-footer .btn {
          width: 100%;
          margin: 0;
          font-size: 0.6875rem;
        }

        #publicationModalDashboard #modalImageDashboard {
          max-height: 240px !important;
        }
      }

      @media (max-width: 576px) {
        #publicationModalDashboard .modal-body {
          padding-bottom: 0.5rem;
          overflow-y: visible;
          max-height: none;
        }

        #publicationModalDashboard .abstract-container {
          padding-bottom: 0.5rem;
        }

        #publicationModalDashboard #modalAbstractDashboard {
          font-size: 0.5625rem;
          line-height: 1.4;
          max-height: 120px;
        }

        #publicationModalDashboard .modal-footer {
          padding: 0.5rem 0.75rem;
        }

        #publicationModalDashboard #modalImageDashboard {
          max-height: 180px !important;
        }
      }

      @media (max-width: 400px) {
        #publicationModalDashboard .modal-body {
          padding-bottom: 0.5rem;
          overflow-y: visible;
          max-height: none;
        }

        #publicationModalDashboard .abstract-container {
          padding-bottom: 0.5rem;
        }

        #publicationModalDashboard #modalAbstractDashboard {
          font-size: 0.5rem;
          line-height: 1.35;
          max-height: 100px;
        }

        #publicationModalDashboard .modal-footer {
          padding: 0.5rem 0.75rem;
        }

        #publicationModalDashboard #modalImageDashboard {
          max-height: 150px !important;
        }
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

      /* Infographic Modal Styles */
      #infographicModal .modal-dialog {
        max-height: 95vh;
        margin: 5rem auto 1.75rem auto;
      }
      
      #infographicModal .modal-content {
        max-height: 95vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
      }
      
      #infographicModal .modal-header {
        flex-shrink: 0;
        border-bottom: 1px solid #dee2e6;
      }
      
      #infographicModal .modal-body {
        overflow: hidden;
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
        padding: 0;
      }
      
      #infographicModal .row.g-0 {
        height: 100%;
        overflow: hidden;
        margin: 0;
        flex: 1;
        min-height: 0;
      }
      
      #infographicModal .col-lg-8 {
        display: flex;
        flex-direction: column;
        overflow: hidden;
        min-height: 0;
        height: 100%;
      }
      
      #infographicModal .col-lg-8 > div:first-child {
        flex: 1;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 0;
        max-height: 100%;
      }
      
      #infographicModal .col-lg-8 > div:last-child {
        flex-shrink: 0;
        z-index: 10;
        position: relative;
        background: white;
      }
      
      #infographicModal .col-lg-4 {
        display: flex;
        flex-direction: column;
        overflow: visible;
        height: 100%;
      }
      
      #infographicModal .col-lg-4 > div {
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: visible;
        padding-right: 10px;
      }
      
      #infographicModal #relatedInfographics {
        overflow: visible !important;
      }

      /* Related Infographic Item */
      .related-infographic-item {
        display: flex;
        gap: 12px;
        padding: 12px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
      }

      .related-infographic-item:hover {
        background-color: #f8f9fa;
        border-color: #1F4068;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        text-decoration: none;
        color: inherit;
      }

      .related-infographic-item img {
        width: 80px;
        height: auto;
        max-height: 80px;
        object-fit: contain;
        border-radius: 4px;
        flex-shrink: 0;
      }

      .related-infographic-item .content {
        flex: 1;
        min-width: 0;
      }

      .related-infographic-item .title {
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.3;
      }

      @media (max-width: 991.98px) {
        #infographicModal .col-lg-8.border-end {
          border-right: none !important;
          border-bottom: 1px solid #dee2e6;
        }
      }

      /* Reorder carousel && cards on mobile */
      @media (max-width: 767.98px) {
        #carouselAndCardsRow {
          display: flex;
          flex-direction: column;
        }
        
        #cardsContainer {
          order: -1;
          margin-bottom: 1.5rem;
          background-color: #fff !important;
        }
        
        #cardsContainer.col-md-6.grid-margin.transparent {
          background-color: #fff !important;
        }
        
        #carouselContainer {
          order: 1;
        }
        
        /* Change cards layout to horizontal on mobile - 1 row 3 columns */
        #cardsContainer {
          display: flex !important;
          flex-direction: row !important;
          gap: 0.25rem;
          padding: 0.5rem 1.5rem;
          margin: 0;
          margin-bottom: 1.5rem;
          background-color: #fff !important;
        }
        
        #cardsContainer > .row {
          flex: 1;
          margin-bottom: 0 !important;
          display: flex;
          flex-direction: column;
          min-width: 0;
        }
        
        #cardsContainer > .row > div {
          flex: 1;
          margin-bottom: 0 !important;
          width: 100%;
        }
        
        #cardsContainer .card {
          background: #fff !important;
          border: none;
          box-shadow: none;
          height: auto !important;
          margin: 0;
        }
        
        #cardsContainer .card-body {
          display: flex;
          flex-direction: column;
          align-items: center;
          text-align: center;
          padding: 1rem 0.1rem;
          min-height: auto;
        }
        
        #cardsContainer .card-body > div {
          display: flex;
          flex-direction: column;
          align-items: center;
          width: 100%;
        }
        
        #cardsContainer .card-body > p:last-child {
          display: none;
        }
        
        /* Icon square styling */
        #cardsContainer .card-body i {
          width: 70px;
          height: 70px;
          min-width: 70px;
          min-height: 70px;
          display: flex;
          align-items: center;
          justify-content: center;
          border-radius: 12px;
          font-size: 1.75rem !important;
          margin-bottom: 0.5rem;
          margin-top: 0;
          flex-shrink: 0;
        }
        
        /* Different colors for each card icon - solid colors with white icon */
        #cardsContainer .row:nth-child(1) .card-body i {
          background-color: #234C6A;
          color: #fff;
        }
        
        #cardsContainer .row:nth-child(2) .card-body i {
          background-color: #234C6A;
          color: #fff;
        }
        
        #cardsContainer .row:nth-child(3) .card-body i {
          background-color: #234C6A;
          color: #fff;
        }
        
        /* Show title as label below icon */
        #cardsContainer .card-body p.mb-4 {
          display: block !important;
          margin-bottom: 0 !important;
          margin-top: 0;
          font-size: 0.8125rem;
          color: #000;
          font-weight: 500;
          text-align: center;
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
          width: 100%;
        }
        
        /* Ensure card body content doesn't overflow */
        #cardsContainer .card-body > div {
          width: 100%;
          display: flex;
          flex-direction: column;
          align-items: center;
        }
        
        /* Reorder elements: icon first, then title */
        #cardsContainer .card-body > div {
          display: flex;
          flex-direction: column;
          align-items: center;
        }
        
        #cardsContainer .card-body > div i {
          order: 1;
        }
        
        #cardsContainer .card-body > div p.mb-4 {
          order: 2;
        }
      }

      /* Sidebar toggle && logo visibility */
      .navbar-brand-wrapper .brand-logo-mini {
        display: none;
      }

      /* Hide logo in navbar-brand-wrapper when sidebar is collapsed */
      .sidebar-offcanvas.collapsed ~ .page-body-wrapper .navbar-brand-wrapper .brand-logo,
      body.sidebar-icon-only .navbar-brand-wrapper .brand-logo {
        display: none !important;
      }

      .sidebar-offcanvas.collapsed ~ .page-body-wrapper .navbar-brand-wrapper .brand-logo-mini,
      body.sidebar-icon-only .navbar-brand-wrapper .brand-logo-mini {
        display: none !important;
      }

      /* Logo yang dipindahkan ke navbar-menu-wrapper */
      .navbar-brand-moved {
        margin-right: auto;
        padding-right: 1rem;
        display: flex;
        align-items: center;
      }

      .navbar-brand-moved .brand-logo-moved {
        display: none;
      }

      .navbar-brand-moved .brand-logo-mini-moved {
        display: none;
      }

      /* Tampilkan logo besar ketika sidebar collapsed */
      body.sidebar-icon-only .navbar-brand-moved .brand-logo-moved,
      .sidebar-offcanvas.collapsed ~ .page-body-wrapper ~ * .navbar-brand-moved .brand-logo-moved {
        display: block !important;
      }

      /* Ketika sidebar collapsed, ubah justify-content navbar-menu-wrapper */
      body.sidebar-icon-only .navbar-menu-wrapper {
        justify-content: flex-start !important;
      }

      /* Pastikan navbar-menu-wrapper memiliki logo di kiri ketika collapsed */
      .navbar-menu-wrapper {
        transition: justify-content 0.3s ease;
      }

      /* Sidebar toggle button styling - centered vertically */
      #sidebarToggle {
        border: none;
        background: transparent;
        padding: 0.5rem;
        cursor: pointer;
        color: #27367f;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        margin: 0;
        outline: none !important;
        box-shadow: none !important;
      }

      #sidebarToggle:hover {
        color: #27367f;
        background-color: transparent;
        border-radius: 0;
      }

      #sidebarToggle:active,
      #sidebarToggle:focus,
      #sidebarToggle:focus-visible,
      #sidebarToggle:focus-within {
        outline: none !important;
        box-shadow: none !important;
        border: none !important;
        background-color: transparent !important;
      }

      /* Hilangkan efek pada navbar-toggler */
      .navbar-toggler:focus,
      .navbar-toggler:active,
      .navbar-toggler:focus-visible {
        outline: none !important;
        box-shadow: none !important;
        border: none !important;
        background-color: transparent !important;
      }

      #sidebarToggle .icon-menu {
        font-size: 1.5rem;
        line-height: 1;
      }

      /* Styling untuk dropdown logout */
      .navbar-dropdown .dropdown-item-form {
        display: block;
        margin: 0;
        padding: 0;
        width: 100%;
        border: 0;
      }

      .navbar-dropdown .dropdown-item-button {
        display: block;
        width: 100%;
        padding: 0.687rem 1.562rem;
        margin-bottom: 0;
        clear: both;
        font-weight: 400;
        font-size: 0.875rem;
        color: #1f1f1f;
        text-align: left;
        text-decoration: none;
        white-space: nowrap;
        background-color: transparent;
        border: 0;
        border-radius: 0;
        cursor: pointer;
        transition: background-color 0.15s ease-in-out;
      }

      .navbar-dropdown .dropdown-item-button:hover,
      .navbar-dropdown .dropdown-item-button:focus {
        color: #1f1f1f;
        background-color: #eaeaf1;
        outline: none;
      }

      .navbar-dropdown .dropdown-item-button:active {
        background: initial;
      }

      .navbar-dropdown .dropdown-item-button i {
        font-size: 17px;
        margin-right: 0.5rem;
        vertical-align: middle;
      }

      /* Hide scrollbar by default, show on hover - applies to all screen sizes */
      .sidebar-offcanvas {
        scrollbar-width: thin;
        scrollbar-color: transparent transparent;
        margin-top: -10px;
      }

      .sidebar-offcanvas::-webkit-scrollbar {
        width: 6px;
      }

      .sidebar-offcanvas::-webkit-scrollbar-track {
        background: transparent;
      }

      .sidebar-offcanvas::-webkit-scrollbar-thumb {
        background-color: transparent;
        border-radius: 3px;
        transition: background-color 0.3s ease;
      }

      .sidebar-offcanvas:hover {
        scrollbar-color: rgba(0, 0, 0, 0.3) transparent;
      }

      .sidebar-offcanvas:hover::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.3);
      }

      .sidebar-offcanvas:hover::-webkit-scrollbar-thumb:hover {
        background-color: rgba(0, 0, 0, 0.5);
      }

      /* Sidebar collapsed state - icon only */
      .sidebar-offcanvas.collapsed {
        width: 70px !important;
      }

      .sidebar-offcanvas.collapsed .menu-title,
      .sidebar-offcanvas.collapsed .menu-arrow {
        display: none !important;
      }

      .sidebar-offcanvas.collapsed .nav-link {
        justify-content: center;
        padding-left: 0.75rem;
        padding-right: 0.75rem;
      }

      .sidebar-offcanvas.collapsed .sub-menu {
        display: none !important;
      }

      .sidebar-offcanvas.collapsed .nav-item {
        position: relative;
      }

      /* Fixed && scrollable sidebar for desktop */
      @media (min-width: 992px) {
        .sidebar-offcanvas {
          position: fixed;
          top: 60px;
          left: 0;
          height: calc(100vh - 60px);
          min-height: calc(100vh - 60px);
          max-height: calc(100vh - 60px);
          overflow-y: auto;
          overflow-x: hidden;
          z-index: 1000;
          width: 235px;
          transition: width 0.3s ease;
        }

        .sidebar-offcanvas.active {
          height: calc(100vh - 60px);
          min-height: calc(100vh - 60px);
          max-height: calc(100vh - 60px);
        }

        /* Adjust page body wrapper to accommodate fixed sidebar */
        .page-body-wrapper {
          margin-left: 235px;
          transition: margin-left 0.3s ease;
        }

        body.sidebar-icon-only .page-body-wrapper,
        .sidebar-offcanvas.collapsed ~ .page-body-wrapper {
          margin-left: 70px;
        }
      }

      /* Sidebar animation from left to right for mobile */
      @media screen && (max-width: 991px) {
        .sidebar-offcanvas {
          position: fixed;
          height: calc(100vh - 60px);
          min-height: calc(100vh - 60px);
          max-height: calc(100vh - 60px);
          top: 60px;
          bottom: 0;
          overflow-y: auto;
          overflow-x: hidden;
          left: -235px !important;
          right: auto !important;
          -webkit-transition: left 0.25s ease-out;
          -o-transition: left 0.25s ease-out;
          transition: left 0.25s ease-out;
          z-index: 1045;
          width: 235px !important;
        }

        .sidebar-offcanvas.active {
          left: 0 !important;
          right: auto !important;
          height: calc(100vh - 60px);
          min-height: calc(100vh - 60px);
          max-height: calc(100vh - 60px);
        }

        /* On mobile, collapsed state still shows full sidebar when active */
        .sidebar-offcanvas.collapsed.active {
          width: 235px !important;
        }
      }

      #check {
        display: none;
      }

      .chat-btn {
        position: fixed;
        right: 25px;
        bottom: 25px;
        cursor: pointer;
        width: 60px;
        height: 60px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        background-color: #234C6A;
        color: white;
        font-size: 24px;
        z-index: 1000;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      }

      .chat-btn .close {
        display: none;
      }

      #check:checked ~ .chat-btn .comment {
        display: none;
      }

      #check:checked ~ .chat-btn .close {
        display: block;
      }

      .wrapper {
        position: fixed;
        right: 25px;
        bottom: 100px;
        width: 300px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        opacity: 0;
        transition: all 0.3s;
        z-index: 999;
        visibility: hidden;
      }

      #check:checked ~ .wrapper {
        opacity: 1;
        visibility: visible;
      }

      .wrapper .header {
        padding: 15px;
        background: #234C6A;
        border-radius: 10px 10px 0 0;
        text-align: center;
        color: white;
        font-size: 1rem;
      }

      .wrapper .chat-area {
        height: 250px;
        padding: 15px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
      }

      .chat-area .chat-message {
        max-width: 80%;
        padding: 8px 12px;
        border-radius: 18px;
        margin-bottom: 10px;
        word-wrap: break-word;
      }

      .chat-area .incoming {
        background: #f1f0f0;
        color: #333;
        align-self: flex-start;
        border-bottom-left-radius: 0;
      }

      .chat-area .outgoing {
        background: #234C6A;
        color: #fff;
        align-self: flex-end;
        border-bottom-right-radius: 0;
      }

      .wrapper .typing-area {
        padding: 15px;
        border-top: 1px solid #e0e0e0;
      }

      .typing-area .form-control {
        height: 45px;
        border-radius: 25px;
      }

      .wrapper .chat-form {
        padding: 15px;
      }
      .chat-form input,
      .chat-form textarea {
        margin-bottom: 10px;
      }

      /* Reduce sidebar font size to prevent text cutoff */
      .sidebar .nav .nav-item .nav-link .menu-title {
        font-size: 0.8125rem !important;
      }

      .sidebar .nav.sub-menu .nav-item .nav-link {
        font-size: 0.8125rem !important;
      }
    </style>
    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: rgba(225, 224, 224, 0.08); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-radius: 24px; border: 1px solid rgba(255, 255, 255, 0.15); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.37);">
          <div class="modal-header border-0 pb-0" style="background: transparent;">
            <div class="w-100 text-center">
              <img src="<?php echo e(asset('images/logoastabayav2.png')); ?>" alt="Logo Astabaya" width="150" class="mb-2" />
              <h2 style="font-size: 25px; font-weight: 600; color: #fff; margin-bottom: 8px; letter-spacing: -0.5px;">Selamat Datang</h2>
              <h3 style="font-size: 15px; font-weight: 400; color: #fff; margin-bottom: 20px; margin-top: 7px; letter-spacing: -0.5px;">Masuk ke akun anda</h3>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; top: 15px; right: 15px;"></button>
          </div>
          <div class="modal-body pt-0">
            <p id="login-error" class="text-danger" style="display: none; text-align: center; padding: 8px; background: rgba(255, 107, 107, 0.1); border-radius: 8px; margin-bottom: 15px;"></p>
            <form id="login-form-modal" style="display: flex; flex-direction: column; gap: 15px;">
              <?php echo csrf_field(); ?>
              <input type="text" name="username" placeholder="Username" required style="width: 100%; padding: 8px 16px; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 12px; background: rgba(255, 255, 255, 0.1); color: #fff; font-size: 15px; transition: all 0.3s ease; outline: none; box-sizing: border-box;" />
              <input type="password" name="password" placeholder="Password" required style="width: 100%; padding: 8px 16px; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 12px; background: rgba(255, 255, 255, 0.1); color: #fff; font-size: 15px; transition: all 0.3s ease; outline: none; box-sizing: border-box;" />
              <button type="submit" style="width: 100%; padding: 11px; background: linear-gradient(135deg, #258ffa 0%, #1c7dd8 100%); color: #fff; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);">Login</button>
            </form>
            <div class="text-center mb-3 mt-3">
              <p class="text-muted" style="color: rgba(255, 255, 255, 0.7); margin: 15px 0;">atau</p>
              <button type="button" onclick="signInWithGoogle()" class="btn btn-outline-danger w-100" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 10px; border: 1px solid #dadce0; background: #fff; color: #3c4043; font-size: 14px; font-weight: 500; border-radius: 4px; cursor: pointer;">
                <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg">
                  <g fill="#000" fill-rule="evenodd">
                    <path d="M9 3.48c1.69 0 2.83.73 3.48 1.34l2.54-2.48C13.46.89 11.43 0 9 0 5.48 0 2.44 2.02.96 4.96l2.91 2.26C4.6 5.05 6.62 3.48 9 3.48z" fill="#EA4335"/>
                    <path d="M17.64 9.2c0-.74-.06-1.28-.19-1.84H9v3.34h4.96c-.21 1.18-.84 2.18-1.79 2.85l2.75 2.13c1.66-1.52 2.72-3.76 2.72-6.48z" fill="#4285F4"/>
                    <path d="M3.88 10.78A5.54 5.54 0 0 1 3.58 9c0-.62.11-1.22.29-1.78L.96 4.96A9.008 9.008 0 0 0 0 9c0 1.45.35 2.82.96 4.04l2.92-2.26z" fill="#FBBC05"/>
                    <path d="M9 18c2.43 0 4.47-.8 5.96-2.18l-2.75-2.13c-.76.53-1.78.9-3.21.9-2.38 0-4.4-1.57-5.12-3.74L.96 13.04C2.45 15.98 5.48 18 9 18z" fill="#34A853"/>
                  </g>
                </svg>
                Masuk dengan Google
              </button>
            </div>
            <div class="text-center" style="margin-top: 24px; font-size: 14px; color: #d0e1f0;">
              <p class="mb-0">Belum memiliki akun? <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal" style="color: #ffffff; text-decoration: none; font-weight: 600; transition: color 0.3s ease;">Daftar</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: rgba(225, 224, 224, 0.08); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-radius: 24px; border: 1px solid rgba(255, 255, 255, 0.15); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.37);">
          <div class="modal-header border-0 pb-0" style="background: transparent;">
            <div class="w-100 text-center">
              <img src="<?php echo e(asset('images/logoastabayav2.png')); ?>" alt="Logo Astabaya" width="150" class="mb-2" />
              <h2 style="font-size: 25px; font-weight: 600; color: #fff; margin-bottom: 0px; letter-spacing: -0.5px;">Selamat Datang</h2>
              <h3 style="font-size: 15px; font-weight: 400; color: #fff; margin-bottom: 20px; margin-top: 7px; letter-spacing: -0.5px;">Buat akun anda</h3>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; top: 15px; right: 15px;"></button>
          </div>
          <div class="modal-body pt-0">
            <p id="register-error" class="text-danger" style="display: none; text-align: center; padding: 8px; background: rgba(255, 107, 107, 0.1); border-radius: 8px; margin-bottom: 15px;"></p>
            <form id="register-form-modal" style="display: flex; flex-direction: column; gap: 16px;">
              <?php echo csrf_field(); ?>
              <input type="text" name="username" placeholder="Username" required style="width: 100%; padding: 8px 16px; font-family: 'Poppins', system-ui, -apple-system, sans-serif; border: 1px solid rgba(255, 255, 255, 0.25); border-radius: 16px; background: rgba(255, 255, 255, 0.12); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); color: #fff; font-size: 15px; transition: all 0.3s ease; outline: none; box-sizing: border-box;" />
              <input type="email" name="email" placeholder="Email" required style="width: 100%; padding: 8px 16px; font-family: 'Poppins', system-ui, -apple-system, sans-serif; border: 1px solid rgba(255, 255, 255, 0.25); border-radius: 16px; background: rgba(255, 255, 255, 0.12); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); color: #fff; font-size: 15px; transition: all 0.3s ease; outline: none; box-sizing: border-box;" />
              <input type="password" name="password" placeholder="Password" required style="width: 100%; padding: 8px 16px; font-family: 'Poppins', system-ui, -apple-system, sans-serif; border: 1px solid rgba(255, 255, 255, 0.25); border-radius: 16px; background: rgba(255, 255, 255, 0.12); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); color: #fff; font-size: 15px; transition: all 0.3s ease; outline: none; box-sizing: border-box;" />
              <button type="submit" style="width: 100%; padding: 8px; background: linear-gradient(135deg, #258ffa 0%, #1c7dd8 100%); color: #fff; border: none; border-radius: 12px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);">Sign Up</button>
            </form>
            <div class="text-center mb-3 mt-3">
              <p class="text-muted" style="color: rgba(255, 255, 255, 0.7); margin: 15px 0;">atau</p>
              <button type="button" onclick="signInWithGoogle()" class="btn btn-outline-danger w-100" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 10px; border: 1px solid #dadce0; background: #fff; color: #3c4043; font-size: 14px; font-weight: 500; border-radius: 4px; cursor: pointer;">
                <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg">
                  <g fill="#000" fill-rule="evenodd">
                    <path d="M9 3.48c1.69 0 2.83.73 3.48 1.34l2.54-2.48C13.46.89 11.43 0 9 0 5.48 0 2.44 2.02.96 4.96l2.91 2.26C4.6 5.05 6.62 3.48 9 3.48z" fill="#EA4335"/>
                    <path d="M17.64 9.2c0-.74-.06-1.28-.19-1.84H9v3.34h4.96c-.21 1.18-.84 2.18-1.79 2.85l2.75 2.13c1.66-1.52 2.72-3.76 2.72-6.48z" fill="#4285F4"/>
                    <path d="M3.88 10.78A5.54 5.54 0 0 1 3.58 9c0-.62.11-1.22.29-1.78L.96 4.96A9.008 9.008 0 0 0 0 9c0 1.45.35 2.82.96 4.04l2.92-2.26z" fill="#FBBC05"/>
                    <path d="M9 18c2.43 0 4.47-.8 5.96-2.18l-2.75-2.13c-.76.53-1.78.9-3.21.9-2.38 0-4.4-1.57-5.12-3.74L.96 13.04C2.45 15.98 5.48 18 9 18z" fill="#34A853"/>
                  </g>
                </svg>
                Daftar dengan Google
              </button>
            </div>
            <div class="text-center" style="margin-top: 0px; padding-top: 0px;">
              <p style="font-size: 14px; color: rgba(255, 255, 255, 0.9); margin-bottom: 0;">Already have an account? <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal" style="background: linear-gradient(135deg, #06b6d4, #0891b2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-weight: 700; text-decoration: none; transition: all 0.3s ease;">Login</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutConfirmModal" tabindex="-1" aria-labelledby="logoutConfirmModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: #ffffff; border-radius: 24px; border: 1px solid rgba(0, 0, 0, 0.1); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.37);">
          <div class="modal-header border-0 pb-0" style="background: transparent;">
            <div class="w-100 text-center" style="padding: 20px;">
              <div style="width: 80px; height: 80px; margin: 0 auto 20px; background: rgba(255, 107, 107, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="ti-power-off" style="font-size: 40px; color: #ff6b6b;"></i>
              </div>
              <h2 style="font-size: 24px; font-weight: 600; color: #333; margin-bottom: 10px;">Konfirmasi Keluar</h2>
              <p style="font-size: 16px; font-weight: 500; color: #555; margin-bottom: 8px;">Apakah Anda yakin ingin keluar?</p>
              <p style="font-size: 14px; font-weight: 400; color: #777; margin-top: 8px; margin-bottom: 0;">Anda akan keluar dari akun Anda.</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; top: 15px; right: 15px;"></button>
          </div>
          <div class="modal-body pt-0">
            <form method="post" action="<?php echo e(route('logout')); ?>" id="logout-form">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="current_url" id="logout-current-url" value="">
              <div class="d-flex gap-2 justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 10px 24px; border-radius: 12px; font-weight: 500;">Batal</button>
                <button type="submit" class="btn btn-danger" style="padding: 10px 24px; border-radius: 12px; font-weight: 500; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); border: none;">Ya, Keluar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Login Required Modal for Downloads -->
    <div class="modal fade" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="loginRequiredModalLabel">Login Diperlukan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
            <p id="login-required-message">Ingin mengunduh <span id="download-item-name"></span> ini? Silakan login terlebih dahulu.</p>
            <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">Login</a>
          </div>
        </div>
      </div>
    </div>
    <script>
      // Login Form Handler
      document.getElementById('login-form-modal')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        const csrfToken = data.csrfmiddlewaretoken;
        const errorElement = document.getElementById('login-error');
        errorElement.style.display = 'none';

        try {
          const response = await fetch('<?php echo e(route('api.login')); ?>', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRFToken': csrfToken,
            },
            body: JSON.stringify(data),
          });

          const responseData = await response.json();
          if (response.ok) {
            // Reload page to update UI
            window.location.reload();
          } else {
            errorElement.textContent = responseData.error || 'Login gagal. Silakan coba lagi.';
            errorElement.style.display = 'block';
          }
        } catch (error) {
          errorElement.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
          errorElement.style.display = 'block';
        }
      });

      // Register Form Handler
      document.getElementById('register-form-modal')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        const csrfToken = data.csrfmiddlewaretoken;
        const errorElement = document.getElementById('register-error');
        errorElement.style.display = 'none';

        try {
          const response = await fetch('{% url "api-register")', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRFToken': csrfToken,
            },
            body: JSON.stringify(data),
          });

          if (response.ok) {
            alert('Registrasi berhasil! Silakan login.');
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            const registerModal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
            registerModal.hide();
            loginModal.show();
          } else {
            const errorData = await response.json();
            const errorMessage = Object.values(errorData).flat().join('\\n');
            errorElement.textContent = errorMessage;
            errorElement.style.display = 'block';
          }
        } catch (error) {
          errorElement.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
          errorElement.style.display = 'block';
        }
      });

      // Function to show login required modal
      function showLoginRequiredModal(itemName) {
        document.getElementById('download-item-name').textContent = itemName;
        const modal = new bootstrap.Modal(document.getElementById('loginRequiredModal'));
        modal.show();
      }

      // Global function to check authentication before download
      function checkAuthBeforeDownload(callback, itemName = 'data') {
        <?php if(auth()->guard()->check()): ?>
        if (typeof callback === 'function') {
          callback();
        }
        <?php else: ?>
        // Redirect to login page instead of showing modal
        window.location.href = "<?php echo e(route('login')); ?>";
        <?php endif; ?>
      }

      // Handle Google Sign-In with redirect (same tab, no popup)
      function signInWithGoogle() {
        // Close modal first
        const loginModal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
        const registerModal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
        if (loginModal) loginModal.hide();
        if (registerModal) registerModal.hide();
        
        // Redirect to Google OAuth in the same tab
        const clientId = '477948872524-8h48o7jmg3seadns5ddmb7hpi336e5a5.apps.googleusercontent.com';
        // Use the exact redirect URI that matches Google Cloud Console
        let redirectUri;
        if (window.location.hostname === 'localhost') {
          redirectUri = 'http://localhost:8000/accounts/google/login/callback/';
        } else {
          redirectUri = 'http://127.0.0.1:8000/accounts/google/login/callback/';
        }
        redirectUri = encodeURIComponent(redirectUri);
        const scope = encodeURIComponent('openid email profile');
        const responseType = 'code';
        const state = 'google_login'; // Can be used to track the login state
        
        const googleAuthUrl = `https://accounts.google.com/o/oauth2/v2/auth?` +
          `client_id=${clientId}&` +
          `redirect_uri=${redirectUri}&` +
          `response_type=${responseType}&` +
          `scope=${scope}&` +
          `state=${state}&` +
          `access_type=online&` +
          `prompt=select_account`;
        
        // Redirect in the same tab
        window.location.href = googleAuthUrl;
      }

      // --- Bookmark Functionality ---
      async function toggleBookmark(button) {
        // Prevent multiple clicks
        if (button.disabled) return;
        button.disabled = true;

        const contentType = button.dataset.contentType;
        const objectId = button.dataset.objectId;
        let bookmarkId = button.dataset.bookmarkId;
        const isBookmarked = button.classList.contains("bookmarked");

        // Validate required data
        if (!contentType || !objectId) {
          console.error("Missing required data:", { contentType, objectId });
          alert("Data tidak lengkap. Silakan refresh halaman.");
          button.disabled = false;
          return;
        }

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
        
        // Try to get CSRF token from cookie first, then from meta tag
        let csrftoken = getCookie("csrftoken");
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
                "X-CSRFToken": csrftoken,
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
              if (typeof syncBookmarkButtons === 'function') {
                syncBookmarkButtons(contentType, objectId, false, "");
              }
              
              // Broadcast change to other tabs
              if (typeof broadcastBookmarkChange === 'function') {
                broadcastBookmarkChange(contentType, objectId, false, "");
              }
              
              // Update bookmark list in header
              updateBookmarkList();
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
                "X-CSRFToken": csrftoken,
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
              if (typeof syncBookmarkButtons === 'function') {
                syncBookmarkButtons(contentType, objectId, true, String(responseData.id));
              }
              
              // Broadcast change to other tabs
              if (typeof broadcastBookmarkChange === 'function') {
                broadcastBookmarkChange(contentType, objectId, true, String(responseData.id));
              }
              
              // Update bookmark list in header
              updateBookmarkList();
            } else {
              if (response.status === 409) {
                // Bookmark already exists, fetch && update UI
                try {
                  const existingBookmarks = await fetch(`/api/bookmarks/`, {
                    headers: { 
                      "X-CSRFToken": csrftoken,
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
                    if (typeof syncBookmarkButtons === 'function') {
                      syncBookmarkButtons(contentType, objectId, true, String(bookmark.id));
                    }
                    
                    // Broadcast change to other tabs
                    if (typeof broadcastBookmarkChange === 'function') {
                      broadcastBookmarkChange(contentType, objectId, true, String(bookmark.id));
                    }
                    
                    // Update bookmark list in header
                    updateBookmarkList();
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

      // Function to update bookmark list in header
      async function updateBookmarkList() {
        // Check if user is authenticated before syncing bookmarks
        <?php if(auth()->guard()->guest()): ?>
        return;
        <?php endif; ?>
        
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
        
        let csrftoken = getCookie("csrftoken");
        if (!csrftoken) {
          const metaTag = document.querySelector('meta[name="csrf-token"]');
          if (metaTag) {
            csrftoken = metaTag.getAttribute("content");
          }
        }

        if (!csrftoken) {
          console.error("CSRF token not found for updating bookmark list");
          return;
        }

        try {
          const response = await fetch(`/api/bookmarks/`, {
            headers: { 
              "X-CSRFToken": csrftoken,
              "X-Requested-With": "XMLHttpRequest"
            },
            credentials: "include",
          });

          if (response.ok) {
            const bookmarks = await response.json();
            const bookmarkList = document.getElementById("bookmarkList");
            const countIndicator = document.getElementById("bookmarkCount") || document.querySelector("#notificationDropdown .count");
            const emptyMessage = document.getElementById("emptyBookmarkMessage");

            if (!bookmarkList) return;

            // Clear existing items
            bookmarkList.innerHTML = "";

            // Update badge - show circle only if there are bookmarks (no number, just circle)
            if (countIndicator) {
              if (bookmarks.length > 0) {
                countIndicator.textContent = ""; // No number, just circle
                countIndicator.style.display = "";
              } else {
                countIndicator.style.display = "none";
              }
            }

            if (bookmarks.length === 0) {
              // Show empty message
              const emptyMsg = document.createElement("p");
              emptyMsg.className = "text-center p-3 text-muted";
              emptyMsg.id = "emptyBookmarkMessage";
              emptyMsg.textContent = "Tidak ada bookmark.";
              bookmarkList.appendChild(emptyMsg);
            } else {
              // Remove empty message if exists
              if (emptyMessage) {
                emptyMessage.remove();
              }

              // Add bookmark items
              bookmarks.forEach(bookmark => {
                const item = bookmark.content_object;
                if (!item || !item.title) return;

                let itemUrl = "#";
                let iconClass = "bi bi-bookmark-fill"; // Default icon
                let contentTypeLabel = ""; // Label untuk menampilkan asal bookmark
                
                // Determine URL, icon, && label based on content type
                if (bookmark.content_type_model === "news") {
                  const newsId = item.news_id || bookmark.object_id;
                  itemUrl = `/news/#news-${newsId}`;
                  iconClass = "bi bi-file-earmark-text"; // Icon berita dari sidebar
                  contentTypeLabel = "Berita";
                } else if (bookmark.content_type_model === "infographic") {
                  const infographicId = item.id || bookmark.object_id;
                  itemUrl = `/infographics/#infographic-${infographicId}`;
                  iconClass = "bi bi-bar-chart-line"; // Icon infografis dari sidebar
                  contentTypeLabel = "Infografis";
                } else if (bookmark.content_type_model === "publication") {
                  const pubId = item.pub_id || bookmark.object_id;
                  itemUrl = `/publications/#publication-${pubId}`;
                  iconClass = "icon-book"; // Icon publikasi dari sidebar
                  contentTypeLabel = "Publikasi";
                }

                const bookmarkItem = document.createElement("a");
                bookmarkItem.className = "dropdown-item preview-item";
                bookmarkItem.href = itemUrl;

                // Escape HTML untuk mencegah XSS
                const title = (item.title || "Untitled").replace(/</g, "&lt;").replace(/>/g, "&gt;");
                // Format: "Judul (Asal)"
                const formattedTitle = `${title} (${contentTypeLabel})`;

                bookmarkItem.innerHTML = `
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-primary">
                      <i class="${iconClass} mx-0"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal" style="word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">${formattedTitle}</h6>
                  </div>
                `;

                bookmarkList.appendChild(bookmarkItem);
              });
            }
          } else {
            console.error("Failed to fetch bookmarks:", response.status);
          }
        } catch (error) {
          console.error("Error updating bookmark list:", error);
        }
      }

      // Make updateBookmarkList available globally
      window.updateBookmarkList = updateBookmarkList;

      // Sync bookmark state across all buttons for the same item
      function syncBookmarkButtons(contentType, objectId, isBookmarked, bookmarkId) {
        // Find all bookmark buttons for this item
        const selector = `.bookmark-btn[data-content-type="${contentType}"][data-object-id="${objectId}"]`;
        const allButtons = document.querySelectorAll(selector);
        
        console.log(`Syncing bookmark buttons: ${contentType}, ${objectId}, isBookmarked: ${isBookmarked}, found ${allButtons.length} buttons`);
        
        allButtons.forEach(btn => {
          const icon = btn.querySelector("i");
          const text = btn.querySelector("span");
          
          if (isBookmarked) {
            btn.classList.add("bookmarked");
            if (icon) {
              icon.classList.remove("bi-bookmark");
              icon.classList.add("bi-bookmark-fill");
            }
            btn.dataset.bookmarkId = bookmarkId;
            if (text) text.textContent = "Tersimpan";
          } else {
            btn.classList.remove("bookmarked");
            if (icon) {
              icon.classList.remove("bi-bookmark-fill");
              icon.classList.add("bi-bookmark");
            }
            btn.dataset.bookmarkId = "";
            if (text) text.textContent = "Bookmark";
          }
        });
      }

      // Make syncBookmarkButtons available globally
      window.syncBookmarkButtons = syncBookmarkButtons;


      // Listen for custom events (same-tab immediate sync)
      window.addEventListener('bookmarkChanged', function(e) {
        // Check if user is authenticated before syncing bookmarks
        <?php if(auth()->guard()->guest()): ?>
        return;
        <?php endif; ?>
        
        const { contentType, objectId, isBookmarked, bookmarkId } = e.detail;
        console.log('Bookmark change detected from custom event:', e.detail);
        
        if (typeof window.syncBookmarkButtons === 'function') {
          window.syncBookmarkButtons(contentType, objectId, isBookmarked, bookmarkId);
        }
        
        if (typeof window.updateBookmarkList === 'function') {
          window.updateBookmarkList();
        }
      });

      // Enhanced storage event listener - also refresh from server when receiving changes from other tabs
      window.addEventListener('storage', function(e) {
        // Check if user is authenticated before syncing bookmarks
        <?php if(auth()->guard()->guest()): ?>
        return;
        <?php endif; ?>
        
        if (e.key === 'bookmark_change' && e.newValue) {
          try {
            const bookmarkData = JSON.parse(e.newValue);
            const { contentType, objectId, isBookmarked, bookmarkId } = bookmarkData;
            
            console.log('Bookmark change detected from storage (other tab):', bookmarkData);
            
            // Refresh bookmark status from server to ensure accuracy
            if (typeof window.refreshBookmarkStatus === 'function') {
              window.refreshBookmarkStatus();
            }
            
            // Also sync specific buttons (immediate update)
            if (typeof window.syncBookmarkButtons === 'function') {
              window.syncBookmarkButtons(contentType, objectId, isBookmarked, bookmarkId);
            }
            
            // Update bookmark list in header
            if (typeof window.updateBookmarkList === 'function') {
              window.updateBookmarkList();
            }
          } catch (error) {
            console.error('Error parsing bookmark change:', error);
          }
        }
      });

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
        
        // Also dispatch custom event for immediate same-tab sync
        window.dispatchEvent(new CustomEvent('bookmarkChanged', {
          detail: bookmarkData
        }));
        
        // Trigger storage event manually for same-tab updates (fallback)
        try {
          window.dispatchEvent(new StorageEvent('storage', {
            key: 'bookmark_change',
            newValue: JSON.stringify(bookmarkData),
            oldValue: null,
            storageArea: localStorage
          }));
        } catch (e) {
          // StorageEvent might !work in all browsers, use custom event instead
          console.log('Using custom event for bookmark sync');
        }
      }

      // Make broadcastBookmarkChange available globally
      window.broadcastBookmarkChange = broadcastBookmarkChange;

      // Function to refresh bookmark status from server for all items on the page
      async function refreshBookmarkStatus() {
        // Check if user is authenticated before syncing bookmarks
        <?php if(auth()->guard()->guest()): ?>
        return;
        <?php endif; ?>
        
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
        
        let csrftoken = getCookie("csrftoken");
        if (!csrftoken) {
          const metaTag = document.querySelector('meta[name="csrf-token"]');
          if (metaTag) {
            csrftoken = metaTag.getAttribute("content");
          }
        }

        if (!csrftoken) {
          console.error("CSRF token not found for refreshing bookmark status");
          return;
        }

        try {
          const response = await fetch(`/api/bookmarks/`, {
            headers: { 
              "X-CSRFToken": csrftoken,
              "X-Requested-With": "XMLHttpRequest"
            },
            credentials: "include",
          });

          if (response.ok) {
            const bookmarks = await response.json();
            
            // Create a map of bookmarks by content_type && object_id for quick lookup
            const bookmarkMap = new Map();
            bookmarks.forEach(bookmark => {
              const key = `${bookmark.content_type_model}_${bookmark.object_id}`;
              bookmarkMap.set(key, bookmark);
            });

            // Update all bookmark buttons on the page
            const allBookmarkButtons = document.querySelectorAll('.bookmark-btn');
            allBookmarkButtons.forEach(btn => {
              const contentType = btn.dataset.contentType;
              const objectId = btn.dataset.objectId;
              
              if (!contentType || !objectId) return;

              const key = `${contentType}_${objectId}`;
              const bookmark = bookmarkMap.get(key);
              
              const icon = btn.querySelector("i");
              const text = btn.querySelector("span");
              
              if (bookmark) {
                // Item is bookmarked
                btn.classList.add("bookmarked");
                if (icon) {
                  icon.classList.remove("bi-bookmark");
                  icon.classList.add("bi-bookmark-fill");
                }
                btn.dataset.bookmarkId = String(bookmark.id);
                if (text) text.textContent = "Tersimpan";
              } else {
                // Item is !bookmarked
                btn.classList.remove("bookmarked");
                if (icon) {
                  icon.classList.remove("bi-bookmark-fill");
                  icon.classList.add("bi-bookmark");
                }
                btn.dataset.bookmarkId = "";
                if (text) text.textContent = "Bookmark";
              }
            });

            console.log('Bookmark status refreshed for', allBookmarkButtons.length, 'buttons');
          } else {
            console.error("Failed to fetch bookmarks for refresh:", response.status);
          }
        } catch (error) {
          console.error("Error refreshing bookmark status:", error);
        }
      }

      // Make refreshBookmarkStatus available globally
      window.refreshBookmarkStatus = refreshBookmarkStatus;

      // Initialize bookmark synchronization listeners on page load
      document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing bookmark synchronization...');
        
        // Hide bookmark badge when dropdown is opened (like notification read)
        const bookmarkDropdown = document.getElementById('bookmarkDropdown');
        const bookmarkButton = document.getElementById('notificationDropdown');
        
        if (bookmarkDropdown && bookmarkButton) {
          // Listen for Bootstrap dropdown show event
          bookmarkButton.addEventListener('shown.bs.dropdown', function() {
            const badge = document.getElementById('bookmarkCount');
            if (badge) {
              badge.style.display = 'none';
            }
          });
        }
        
        // Refresh bookmark status when page becomes visible (user switches back to this tab)
        <?php if(auth()->guard()->check()): ?>
        document.addEventListener('visibilitychange', function() {
          if (!document.hidden) {
            console.log('Page became visible, refreshing bookmark status...');
            refreshBookmarkStatus();
            updateBookmarkList();
          }
        });

        // Also refresh when window gains focus
        window.addEventListener('focus', function() {
          console.log('Window gained focus, refreshing bookmark status...');
          refreshBookmarkStatus();
          updateBookmarkList();
        });

        // Initial refresh after a short delay to ensure DOM is ready
        setTimeout(function() {
          console.log('Initial bookmark status refresh...');
          refreshBookmarkStatus();
        }, 500);
        <?php endif; ?>
        
        // Ensure event listeners are set up
        console.log('Bookmark sync functions available:', {
          syncBookmarkButtons: typeof window.syncBookmarkButtons,
          broadcastBookmarkChange: typeof window.broadcastBookmarkChange,
          updateBookmarkList: typeof window.updateBookmarkList,
          refreshBookmarkStatus: typeof window.refreshBookmarkStatus
        });
      });

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
                // If no instance exists, create one && hide it
                const modal = new bootstrap.Modal(modalElement);
                modal.hide();
              }
            }
          });
        });

        // Add/remove class to body for login/register modal styling
        const loginModal = document.getElementById('loginModal');
        const registerModal = document.getElementById('registerModal');
        
        if (loginModal) {
          loginModal.addEventListener('show.bs.modal', function() {
            document.body.classList.add('login-register-modal-open');
          });
          loginModal.addEventListener('hidden.bs.modal', function() {
            document.body.classList.remove('login-register-modal-open');
          });
        }
        
        if (registerModal) {
          registerModal.addEventListener('show.bs.modal', function() {
            document.body.classList.add('login-register-modal-open');
          });
          registerModal.addEventListener('hidden.bs.modal', function() {
            document.body.classList.remove('login-register-modal-open');
          });
        }

        // Logout Modal - Save current URL when modal opens
        const logoutModal = document.getElementById('logoutConfirmModal');
        if (logoutModal) {
          logoutModal.addEventListener('show.bs.modal', function() {
            // Save current URL to hidden input
            const currentUrl = window.location.href;
            const urlInput = document.getElementById('logout-current-url');
            if (urlInput) {
              urlInput.value = currentUrl;
            }
          });
        }
      });
    </script>
    
    
    <script>
      // Global share functionality for all pages
      (function() {
        // Initialize share buttons - using event delegation for dynamic content
        document.addEventListener('DOMContentLoaded', function() {
          console.log('Initializing global share buttons'); // Debug
          
          // Use event delegation to handle all share buttons (including dynamically added ones)
          document.addEventListener('click', async function(e) {
            const shareBtn = e.target.closest('.share-btn, .share-infographic-modal-btn, .share-infographic-btn, .share-publication-modal-btn, .share-publication-btn, .share-news-modal-btn, .share-news-btn');
            
            if (shareBtn) {
              e.preventDefault();
              e.stopPropagation();
              
              // Get title and URL based on content type
              let title = '';
              let url = '';
              
              // Try different data attributes
              if (shareBtn.dataset.infographicTitle) {
                title = shareBtn.dataset.infographicTitle;
                url = shareBtn.dataset.infographicUrl || window.location.href;
              } else if (shareBtn.dataset.pubTitle) {
                title = shareBtn.dataset.pubTitle;
                url = shareBtn.dataset.pubUrl || window.location.href;
              } else if (shareBtn.dataset.newsTitle) {
                title = shareBtn.dataset.newsTitle;
                url = shareBtn.dataset.newsUrl || window.location.href;
              } else if (shareBtn.dataset.shareTitle) {
                title = shareBtn.dataset.shareTitle;
                url = shareBtn.dataset.shareUrl || window.location.href;
              } else {
                title = 'Konten';
                url = window.location.href;
              }
              
              // Ensure URL is complete (add origin if relative)
              if (url && !url.startsWith('http://') && !url.startsWith('https://')) {
                url = window.location.origin + (url.startsWith('/') ? url : '/' + url);
              }
              
              // Ensure URL is a string
              url = String(url);
              
              console.log('Share button clicked:', { title, url, button: shareBtn }); // Debug log
              
              // Try Web Share API first (for mobile devices)
              if (navigator.share) {
                try {
                  await navigator.share({
                    title: title,
                    text: 'Lihat konten ini: ' + title,
                    url: url
                  });
                  console.log('Share successful');
                  return;
                } catch (err) {
                  if (err.name !== 'AbortError') {
                    console.log('Error sharing or user cancelled:', err);
                    // Fallback: show share modal
                    showShareModal(title, url);
                  }
                }
              } else {
                // Show share modal with textarea for manual copy
                showShareModal(title, url);
              }
            }
          });
        });
        
        // Global copy to clipboard function (maintains user interaction context)
        async function copyToClipboardGlobal(text, title) {
          text = String(text || '');
          
          if (!text) {
            console.error('No text to copy');
            showShareToast('Tidak ada link untuk disalin');
            return;
          }
          
          console.log('Copying to clipboard directly:', text, title); // Debug log
          
          // Try Clipboard API first (works best in modern browsers with HTTPS)
          // Note: Clipboard API requires HTTPS or localhost
          if (navigator.clipboard && navigator.clipboard.writeText) {
            try {
              await navigator.clipboard.writeText(text);
              console.log('Successfully copied to clipboard using Clipboard API'); // Debug log
              showShareToast('Link "' + title + '" telah disalin ke clipboard');
              return; // Success, exit early
            } catch (err) {
              console.error('Clipboard API failed:', err);
              // If Clipboard API fails (e.g., permission denied, not HTTPS), use fallback
              // Note: In HTTP environments, Clipboard API may not work, so we use fallback
            }
          }
          
          // Clipboard API not available or failed, use fallback immediately (synchronously)
          // Important: Must call synchronously to maintain user interaction context
          console.log('Using fallback copy method (Clipboard API not available or failed)'); // Debug log
          fallbackCopyToClipboardGlobal(text, title);
        }
        
        // Global fallback copy function
        function fallbackCopyToClipboardGlobal(text, title) {
          console.log('Using fallback copy method');
          
          text = String(text || '');
          
          if (!text) {
            console.error('No text to copy in fallback');
            showShareToast('Tidak ada link untuk disalin');
            return;
          }
          
          const textArea = document.createElement('textarea');
          textArea.value = text;
          // Make textarea visible but off-screen (some browsers require visibility)
          textArea.style.position = 'fixed';
          textArea.style.left = '-9999px';
          textArea.style.top = (window.pageYOffset || document.documentElement.scrollTop) + 'px';
          textArea.style.width = '1px';
          textArea.style.height = '1px';
          textArea.style.padding = '0';
          textArea.style.border = 'none';
          textArea.style.outline = 'none';
          textArea.style.boxShadow = 'none';
          textArea.style.background = 'transparent';
          textArea.style.opacity = '0';
          textArea.style.zIndex = '-9999';
          textArea.setAttribute('readonly', '');
          textArea.setAttribute('aria-hidden', 'true');
          
          document.body.appendChild(textArea);
          
          // For iOS devices
          if (navigator.userAgent.match(/ipad|iphone/i)) {
            textArea.contentEditable = true;
            textArea.readOnly = false;
            const range = document.createRange();
            range.selectNodeContents(textArea);
            const selection = window.getSelection();
            if (selection.rangeCount > 0) {
              selection.removeAllRanges();
            }
            selection.addRange(range);
            textArea.setSelectionRange(0, text.length);
          }
          
          // Focus and select - critical for copy to work
          textArea.focus();
          textArea.select();
          textArea.setSelectionRange(0, text.length);
          
          // Double-check selection
          if (textArea.selectionStart !== 0 || textArea.selectionEnd !== text.length) {
            textArea.setSelectionRange(0, text.length);
          }
          
          let copySuccess = false;
          
          try {
            // Try execCommand immediately (must be in user interaction context)
            // Ensure textarea is focused and selected before copy
            textArea.focus();
            textArea.select();
            textArea.setSelectionRange(0, text.length);
            
            // Verify selection
            const selectedText = textArea.value.substring(textArea.selectionStart, textArea.selectionEnd);
            console.log('Text to copy:', text);
            console.log('Selected text:', selectedText);
            console.log('Selection range:', textArea.selectionStart, '-', textArea.selectionEnd);
            
            copySuccess = document.execCommand('copy');
            console.log('execCommand result:', copySuccess);
            
            if (copySuccess) {
              console.log('Fallback copy successful via execCommand');
              showShareToast('Link "' + title + '" telah disalin ke clipboard');
              
              // Clean up
              setTimeout(() => {
                if (textArea && textArea.parentNode) {
                  textArea.parentNode.removeChild(textArea);
                }
              }, 100);
              return;
            } else {
              throw new Error('execCommand copy returned false');
            }
          } catch (err) {
            console.error('Fallback copy failed:', err);
            
            // Try alternative method: create a temporary input element
            try {
              const input = document.createElement('input');
              input.type = 'text';
              input.value = text;
              input.style.position = 'fixed';
              input.style.left = '0';
              input.style.top = '0';
              input.style.width = '2em';
              input.style.height = '2em';
              input.style.opacity = '0';
              input.style.pointerEvents = 'none';
              document.body.appendChild(input);
              
              input.focus();
              input.select();
              input.setSelectionRange(0, text.length);
              
              copySuccess = document.execCommand('copy');
              document.body.removeChild(input);
              
              if (copySuccess) {
                console.log('Fallback copy successful via input element');
                showShareToast('Link "' + title + '" telah disalin ke clipboard');
              } else {
                throw new Error('Alternative method also failed');
              }
            } catch (err2) {
              console.error('Alternative copy method failed:', err2);
              
              // Last resort: show the text in a prompt
              const userInput = prompt('Salin link berikut (Ctrl+C untuk menyalin):', text);
              if (userInput !== null) {
                showShareToast('Silakan salin link secara manual');
              }
            }
          }
          
          // Clean up textarea
          setTimeout(() => {
            if (textArea && textArea.parentNode) {
              textArea.parentNode.removeChild(textArea);
            }
          }, 100);
            
            if (copySuccess) {
              console.log('Fallback copy successful via execCommand');
              showShareToast('Link "' + title + '" telah disalin ke clipboard');
              
              // Clean up immediately
              setTimeout(() => {
                if (textArea && textArea.parentNode) {
                  textArea.parentNode.removeChild(textArea);
                }
              }, 0);
              return;
            } else {
              throw new Error('execCommand copy returned false');
            }
          } catch (err) {
            console.error('Fallback copy failed:', err);
            
            // Try alternative method: create a temporary input element
            try {
              const input = document.createElement('input');
              input.type = 'text';
              input.value = text;
              input.style.position = 'fixed';
              input.style.left = '0';
              input.style.top = '0';
              input.style.width = '2em';
              input.style.height = '2em';
              input.style.opacity = '0';
              input.style.pointerEvents = 'none';
              document.body.appendChild(input);
              
              input.focus();
              input.select();
              input.setSelectionRange(0, text.length);
              
              copySuccess = document.execCommand('copy');
              document.body.removeChild(input);
              
              if (copySuccess) {
                console.log('Fallback copy successful via input element');
                showShareToast('Link "' + title + '" telah disalin ke clipboard');
              } else {
                throw new Error('Alternative method also failed');
              }
            } catch (err2) {
              console.error('Alternative copy method failed:', err2);
              
              // Last resort: show the text in a prompt
              const userInput = prompt('Salin link berikut (Ctrl+C untuk menyalin):', text);
              if (userInput !== null) {
                showShareToast('Silakan salin link secara manual');
              }
            }
          }
          
          // Clean up textarea
          setTimeout(() => {
            if (textArea && textArea.parentNode) {
              textArea.parentNode.removeChild(textArea);
            }
          }, 100);
        }
        
        // Global toast notification function
        function showShareToast(message) {
          // Create toast element - consistent style
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
        
        // Function to show share modal
        function showShareModal(title, url) {
          const modalTitle = document.getElementById('shareModalTitle');
          const modalTextarea = document.getElementById('shareModalTextarea');
          const shareModal = document.getElementById('shareModal');
          
          if (modalTitle) modalTitle.textContent = 'Bagikan: ' + title;
          if (modalTextarea) modalTextarea.value = url;
          
          if (shareModal) {
            const modal = new bootstrap.Modal(shareModal);
            modal.show();
            
            // Select text in textarea when modal is shown
            shareModal.addEventListener('shown.bs.modal', function() {
              if (modalTextarea) {
                modalTextarea.select();
                modalTextarea.focus();
              }
            }, { once: true });
          }
        }
        
        // Simple copy function for share modal button
        function copyShareLink() {
          const textarea = document.getElementById('shareModalTextarea');
          const copyBtn = event?.target || document.querySelector('#shareModal .btn-primary');
          
          if (!textarea) return;
          
          const url = textarea.value;
          if (!url) {
            showShareToast('Tidak ada link untuk disalin');
            return;
          }
          
          // Select text first
          textarea.select();
          textarea.setSelectionRange(0, url.length);
          textarea.focus();
          
          // Try Clipboard API first
          if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(() => {
              showShareToast('Link telah disalin ke clipboard!');
              
              // Visual feedback on button
              if (copyBtn) {
                const originalText = copyBtn.innerHTML;
                copyBtn.innerHTML = '<i class="bi bi-check"></i> Tersalin!';
                copyBtn.classList.add('btn-success');
                copyBtn.classList.remove('btn-primary');
                
                setTimeout(() => {
                  copyBtn.innerHTML = originalText;
                  copyBtn.classList.remove('btn-success');
                  copyBtn.classList.add('btn-primary');
                }, 2000);
              }
            }).catch(() => {
              // Fallback: text is already selected, show instruction
              showShareToast('Pilih teks dan salin dengan Ctrl+C (atau Cmd+C di Mac)');
            });
          } else {
            // Fallback: text is already selected, show instruction
            showShareToast('Pilih teks dan salin dengan Ctrl+C (atau Cmd+C di Mac)');
          }
        }
        
        // Make functions globally available
        window.copyToClipboardGlobal = copyToClipboardGlobal;
        window.fallbackCopyToClipboardGlobal = fallbackCopyToClipboardGlobal;
        window.showShareToast = showShareToast;
        window.showShareModal = showShareModal;
        window.copyShareLink = copyShareLink;
      })();
    </script>
    
    <!-- Share Modal -->
    <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="shareModalLabel">
              <i class="bi bi-share"></i> <span id="shareModalTitle">Bagikan</span>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p class="mb-3">Salin link di bawah ini untuk membagikan:</p>
            <div class="input-group">
              <textarea 
                class="form-control" 
                id="shareModalTextarea" 
                rows="3" 
                readonly
                style="resize: none; font-family: monospace; font-size: 0.9rem;"
              ></textarea>
              <button 
                class="btn btn-primary" 
                type="button" 
                onclick="copyShareLink()"
                style="border-top-left-radius: 0; border-bottom-left-radius: 0;"
              >
                <i class="bi bi-clipboard"></i> Salin
              </button>
            </div>
            <small class="text-muted d-block mt-2">
              <i class="bi bi-info-circle"></i> Klik tombol "Salin" atau pilih teks dan salin dengan Ctrl+C
            </small>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
  </body>
</html>
<?php /**PATH D:\laragon\www\astabaya\resources\views/layouts/main.blade.php ENDPATH**/ ?>