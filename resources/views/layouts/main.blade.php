<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Aastabaya')</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}" />
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/share-styles.css') }}" />
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('images/Aastabaya-favicon(2).png') }}" />
    @vite('resources/css/layout-main.css')
    @stack('styles')
  <body>
    <div class="container-scroller">
      <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-between">
          <a class="navbar-brand brand-logo" href="{{ route('dashboard') }}"><img src="{{ asset('images/logoastabayav3.png') }}" width="150" height="45" alt="logo" /></a>
          <button class="navbar-toggler" type="button" id="sidebarToggle" aria-label="Toggle sidebar">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
          <!-- Logo akan dipindahkan ke sini ketika sidebar collapsed -->
          <div class="navbar-brand-moved d-none">
            <a class="navbar-brand brand-logo-moved" href="{{ route('dashboard') }}"><img src="{{ asset('images/logoastabayav3.png') }}" width="150" height="45" alt="logo" /></a>
          </div>
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                <i class="bi bi-bookmark-star mx-0"></i>
                @php
                  $bookmarkedCount = isset($bookmarked_items) && is_array($bookmarked_items) ? count($bookmarked_items) : 0;
                @endphp
                @if($bookmarkedCount == 0)
                <span class="count" id="bookmarkCount" style="display: none;"></span>
                @else
                <span class="count" id="bookmarkCount"></span>
                @endif
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" id="bookmarkDropdown" aria-labelledby="notificationDropdown">
                <p class="mb-0 font-weight-normal float-left dropdown-header">Bookmark</p>
                <div id="bookmarkList">
                @forelse($bookmarked_items ?? [] as $item)
                <a class="dropdown-item preview-item" href="{{ $item->url }}">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-primary">
                        <i class="{{ $item->icon_class ?? 'bi bi-bookmark-fill' }} mx-0"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal">{{ $item->title }}</h6>
                  </div>
                </a>
                @empty
                  <p class="text-center p-3 text-muted" id="emptyBookmarkMessage">Tidak ada bookmark.</p>
                @endforelse
                </div>
              </div>
            </li>
            @auth
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                @if(strlen(auth()->user()->username) >= 2)
                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px; text-transform: uppercase;">
                  {{ strtoupper(substr(auth()->user()->username, 0, 2)) }}
                </div>
                @else
                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 14px; text-transform: uppercase;">
                  {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                </div>
                @endif
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                <button type="button" class="dropdown-item-button" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal" style="background: none; border: none; width: 100%; text-align: left; padding: 8px 16px; cursor: pointer;"><i class="ti-power-off text-primary"></i>Keluar</button>
              </div>
            </li>
            @else
            <li class="nav-item">
              <div class="d-flex gap-2 align-items-center">
                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary" style="white-space: nowrap;">Masuk</a>
                <a href="{{ route('signup') }}" class="btn btn-sm btn-primary" style="white-space: nowrap;">Daftar</a>
              </div>
            </li>
            @endauth
          </ul>
        </div>
      </nav>
      <div class="container-fluid page-body-wrapper">
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item">
              <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('inflasi') }}">
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
                  <li class="nav-item"><a class="nav-link" href="{{ route('pdrb-pengeluaran') }}">PDRB Pengeluaran</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{ route('pdrb-lapangan-usaha') }}">PDRB Lapangan Usaha</a></li>
                </ul>
              </div>

            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('kemiskinan') }}">
                <i class="bi bi-heart-pulse menu-icon"></i>
                <span class="menu-title">Kemiskinan</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('kependudukan') }}">
                <i class="bi bi-people menu-icon"></i>
                <span class="menu-title">Kependudukan</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('ketenagakerjaan') }}">
                <i class="bi bi-briefcase menu-icon"></i>
                <span class="menu-title">Ketenagakerjaan</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('hotel-occupancy') }}" >
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
                  <li class="nav-item"><a class="nav-link" href="{{ route('indeks-pembangunan-manusia') }}">IPM</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{ route('ipm-uhh-sp') }}">UHH SP</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{ route('ipm-hls') }}">HLS</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{ route('ipm-rls') }}">RLS</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{ route('ipm-pengeluaran-per-kapita') }}">Pengeluaran per Kapita</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{ route('ipm-indeks-kesehatan') }}">Indeks Kesehatan</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{ route('ipm-indeks-hidup-layak') }}">Indeks Hidup Layak</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{ route('ipm-indeks-pendidikan') }}">Indeks Pendidikan</a></li>
                </ul>
              </div>
            </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('gini-ratio') }}">
                  <i class="bi bi-percent menu-icon"></i>
                  <span class="menu-title">Gini ratio</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('publications') }}">
                  <i class="icon-book menu-icon"></i>
                  <span class="menu-title">Publikasi</span>
                </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('infographics') }}">
                <i class="bi bi-bar-chart-line menu-icon"></i>
                <span class="menu-title">Infografis</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('news') }}">
                <i class="bi bi-file-earmark-text menu-icon"></i>
                <span class="menu-title">Berita</span>
              </a>
            </li>
          </ul>
        </nav>
        <div class="main-panel">
          <div class="content-wrapper">
            @yield('content')
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
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- Plugin JS -->
    <script src="{{ asset('assets/vendors/chart.js/chart.umd.js') }}"></script>
    <!-- Custom JS for template -->
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <!-- External JS from CDN -->

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
    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: rgba(225, 224, 224, 0.08); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-radius: 24px; border: 1px solid rgba(255, 255, 255, 0.15); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.37);">
          <div class="modal-header border-0 pb-0" style="background: transparent;">
            <div class="w-100 text-center">
              <img src="{{ asset('images/logoastabayav2.png') }}" alt="Logo Astabaya" width="150" class="mb-2" />
              <h2 style="font-size: 25px; font-weight: 600; color: #fff; margin-bottom: 8px; letter-spacing: -0.5px;">Selamat Datang</h2>
              <h3 style="font-size: 15px; font-weight: 400; color: #fff; margin-bottom: 20px; margin-top: 7px; letter-spacing: -0.5px;">Masuk ke akun anda</h3>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; top: 15px; right: 15px;"></button>
          </div>
          <div class="modal-body pt-0">
            <p id="login-error" class="text-danger" style="display: none; text-align: center; padding: 8px; background: rgba(255, 107, 107, 0.1); border-radius: 8px; margin-bottom: 15px;"></p>
            <form id="login-form-modal" style="display: flex; flex-direction: column; gap: 15px;">
              @csrf
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
              <img src="{{ asset('images/logoastabayav2.png') }}" alt="Logo Astabaya" width="150" class="mb-2" />
              <h2 style="font-size: 25px; font-weight: 600; color: #fff; margin-bottom: 0px; letter-spacing: -0.5px;">Selamat Datang</h2>
              <h3 style="font-size: 15px; font-weight: 400; color: #fff; margin-bottom: 20px; margin-top: 7px; letter-spacing: -0.5px;">Buat akun anda</h3>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; top: 15px; right: 15px;"></button>
          </div>
          <div class="modal-body pt-0">
            <p id="register-error" class="text-danger" style="display: none; text-align: center; padding: 8px; background: rgba(255, 107, 107, 0.1); border-radius: 8px; margin-bottom: 15px;"></p>
            <form id="register-form-modal" style="display: flex; flex-direction: column; gap: 16px;">
              @csrf
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
            <form method="post" action="{{ route('logout') }}" id="logout-form">
              @csrf
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
            <p id="login-required-message">
              <span id="login-item-name"></span>
            </p>
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
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
          const response = await fetch('{{ route('api.login') }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
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
              'X-CSRF-TOKEN': csrfToken,
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
      function showLoginRequiredModal(itemName, customMessage) {
        const modal = document.getElementById('loginRequiredModal');
        if (!modal) {
          console.error('Login required modal not found');
          // Fallback: redirect to login page
          window.location.href = '{{ route("login") }}';
          return;
        }

        const messageElement = document.getElementById('login-required-message');
        const itemNameElement = document.getElementById('login-item-name');
        
        if (customMessage) {
          messageElement.innerHTML = customMessage;
        } else if (itemName && itemNameElement) {
          messageElement.innerHTML = 'Ingin mengakses <span id="login-item-name">' + itemName + '</span>? Silakan login terlebih dahulu.';
        } else {
          messageElement.textContent = 'Silakan login terlebih dahulu untuk mengakses fitur ini.';
        }

        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
      }

      // Make function globally available
      window.showLoginRequiredModal = showLoginRequiredModal;

      // Global function to check authentication before download
      function checkAuthBeforeDownload(callback, itemName = 'data') {
        @auth
        if (typeof callback === 'function') {
          callback();
        }
        @else
        // Redirect to login page instead of showing modal
        window.location.href = "{{ route('login') }}";
        @endauth
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

        // Get CSRF token (Laravel way - from meta tag)
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        const csrftoken = metaTag ? metaTag.getAttribute('content') : null;
        
        console.log("[Bookmark v3] CSRF Token:", csrftoken ? `Found (${csrftoken.substring(0, 10)}...)` : "NOT FOUND");
        console.log("[Bookmark v3] Meta tag exists:", !!metaTag);

        if (!csrftoken) {
          console.error("CSRF token not found! Meta tag:", metaTag);
          alert("Token CSRF tidak ditemukan. Silakan refresh halaman (Ctrl+F5).");
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
          const response = await fetch(`/bookmarks/${bookmarkId}`, {
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
            
          const response = await fetch(`/bookmarks/add`, {
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
            console.log("Add response headers:", Object.fromEntries(response.headers.entries()));
            const responseText = await response.text();
            console.log("Add response text:", responseText);
            let responseData = {};
            try {
              responseData = JSON.parse(responseText);
              console.log("Add response data (parsed):", responseData);
            } catch (e) {
              console.error("Failed to parse response as JSON:", e);
              console.log("Response was:", responseText);
            }

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
                  const existingBookmarks = await fetch(`/bookmarks`, {
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
                const errorMsg = responseData.error || responseData.detail || responseData.non_field_errors || responseData.message || "Terjadi kesalahan";
                console.error("Add bookmark error:", {
                  status: response.status,
                  statusText: response.statusText,
                  responseData: responseData,
                  errorMsg: errorMsg
                });
                alert("Gagal menambahkan bookmark: " + (Array.isArray(errorMsg) ? errorMsg.join(", ") : errorMsg));
              }
            }
          }
        } catch (error) {
          console.error("Error toggling bookmark:", error);
          console.error("Error stack:", error.stack);
          alert("Terjadi kesalahan: " + error.message);
        } finally {
          button.disabled = false;
        }
      }

      // Function to update bookmark list in header
      async function updateBookmarkList() {
        // Check if user is authenticated before syncing bookmarks
        @guest
        return;
        @endguest
        
        // Get CSRF token from meta tag (Laravel standard)
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        const csrftoken = metaTag ? metaTag.getAttribute('content') : null;
        
        console.log('[updateBookmarkList] CSRF Token:', csrftoken ? 'Found' : 'NOT FOUND');

        if (!csrftoken) {
          console.error("CSRF token not found for updating bookmark list");
          return;
        }

        try {
          const response = await fetch(`/bookmarks`, {
            headers: { 
              "X-CSRF-TOKEN": csrftoken,
              "X-Requested-With": "XMLHttpRequest"
            },
            credentials: "include",
          });

          console.log('[updateBookmarkList] Response status:', response.status);

          if (response.ok) {
            const bookmarks = await response.json();
            console.log('[updateBookmarkList] Bookmarks loaded:', bookmarks.length, bookmarks);
            
            const bookmarkList = document.getElementById("bookmarkList");
            const countIndicator = document.getElementById("bookmarkCount") || document.querySelector("#notificationDropdown .count");
            const emptyMessage = document.getElementById("emptyBookmarkMessage");

            if (!bookmarkList) {
              console.error('[updateBookmarkList] bookmarkList element not found!');
              return;
            }

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
                console.log('[updateBookmarkList] Processing bookmark:', {
                  id: bookmark.id,
                  type: bookmark.content_type_model,
                  has_object: !!item,
                  has_title: item?.title
                });
                
                if (!item || !item.title) {
                  console.warn('[updateBookmarkList] Skipping bookmark - no content_object or title:', bookmark);
                  return;
                }

                let itemUrl = "#";
                let iconClass = "bi bi-bookmark-fill"; // Default icon (fallback jika tidak ada gambar)
                let contentTypeLabel = ""; // Label untuk menampilkan asal bookmark
                let imageUrl = bookmark.image_url; // Ambil image URL dari API
                
                // Determine URL, icon, && label based on content type
                if (bookmark.content_type_model === "news") {
                  const newsId = item.news_id || bookmark.object_id;
                  itemUrl = `/news/#news-${newsId}`;
                  iconClass = "bi bi-file-earmark-text"; // Icon berita dari sidebar (fallback)
                  contentTypeLabel = "Berita";
                } else if (bookmark.content_type_model === "infographic") {
                  const infographicId = item.id || bookmark.object_id;
                  itemUrl = `/infographics/#infographic-${infographicId}`;
                  iconClass = "bi bi-bar-chart-line"; // Icon infografis dari sidebar (fallback)
                  contentTypeLabel = "Infografis";
                } else if (bookmark.content_type_model === "publication") {
                  const pubId = item.pub_id || bookmark.object_id;
                  itemUrl = `/publications/#publication-${pubId}`;
                  iconClass = "icon-book"; // Icon publikasi dari sidebar (fallback)
                  contentTypeLabel = "Publikasi";
                }

                const bookmarkItem = document.createElement("a");
                bookmarkItem.className = "dropdown-item preview-item";
                bookmarkItem.href = itemUrl;

                // Escape HTML untuk mencegah XSS
                const title = (item.title || "Untitled").replace(/</g, "&lt;").replace(/>/g, "&gt;");
                // Format: "Judul (Asal)"
                const formattedTitle = `${title} (${contentTypeLabel})`;

                // Gunakan gambar thumbnail jika tersedia, jika tidak gunakan icon
                let thumbnailHtml = '';
                if (imageUrl) {
                  thumbnailHtml = `
                    <div class="preview-thumbnail">
                      <img src="${imageUrl}" 
                           alt="${title}" 
                           style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px;"
                           onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<div class=&quot;preview-icon bg-primary&quot;><i class=&quot;${iconClass} mx-0&quot;></i></div>';">
                    </div>
                  `;
                } else {
                  thumbnailHtml = `
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-primary">
                        <i class="${iconClass} mx-0"></i>
                      </div>
                    </div>
                  `;
                }

                bookmarkItem.innerHTML = `
                  ${thumbnailHtml}
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
        @guest
        return;
        @endguest
        
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
        @guest
        return;
        @endguest
        
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
        @guest
        return;
        @endguest
        
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
          const response = await fetch(`/bookmarks`, {
            headers: { 
              "X-CSRF-TOKEN": csrftoken,
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
        @auth
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
          console.log('Initial bookmark status refresh and list update...');
          refreshBookmarkStatus();
          updateBookmarkList(); // Also update bookmark dropdown on page load
        }, 500);
        @endauth
        
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
    
    {{-- Global Share Functionality --}}
    <script>
      // Function to show share modal (defined outside IIFE for early access)
      // Make it available immediately on window object
      window.showShareModal = function(title, url) {
        console.log('showShareModal called:', { title, url }); // Debug
        const modalTitle = document.getElementById('shareModalTitle');
        const modalInput = document.getElementById('shareModalInput');
        const shareModal = document.getElementById('shareModal');
        
        if (!shareModal) {
          console.error('Share modal element not found!');
          alert('Modal share tidak ditemukan. Pastikan modal HTML sudah dimuat.');
          return;
        }
        
        if (modalTitle) modalTitle.textContent = 'Bagikan: ' + title;
        if (modalInput) modalInput.value = url;
        
        try {
          const modal = new bootstrap.Modal(shareModal);
          modal.show();
          
          // Select text in input when modal is shown
          shareModal.addEventListener('shown.bs.modal', function() {
            if (modalInput) {
              modalInput.select();
              modalInput.focus();
            }
          }, { once: true });
        } catch (err) {
          console.error('Error showing share modal:', err);
          alert('Error saat membuka modal share: ' + err.message);
        }
      };
      
      // Also create a direct reference for convenience
      var showShareModal = window.showShareModal;
      
      // Global share functionality for all pages
      (function() {
        // Initialize share buttons - using event delegation for dynamic content
        document.addEventListener('DOMContentLoaded', function() {
          console.log('Initializing global share buttons'); // Debug
          
          // Use event delegation to handle all share buttons (including dynamically added ones)
          document.addEventListener('click', async function(e) {
            console.log('Click event detected:', e.target, e.target.classList); // Debug
            
            // Check if clicked element or its parent is a share button
            const shareBtn = e.target.closest('.share-btn, .share-infographic-modal-btn, .share-infographic-btn, .share-publication-modal-btn, .share-publication-btn, .share-news-modal-btn, .share-news-btn');
            
            // Also check if the clicked element itself is a share button
            const isShareButton = e.target.classList.contains('share-btn') || 
                                  e.target.classList.contains('share-infographic-modal-btn') ||
                                  e.target.classList.contains('share-infographic-btn') ||
                                  e.target.classList.contains('share-publication-modal-btn') ||
                                  e.target.classList.contains('share-publication-btn') ||
                                  e.target.classList.contains('share-news-modal-btn') ||
                                  e.target.classList.contains('share-news-btn');
            
            // Check if parent is share button
            const parentIsShareBtn = e.target.parentElement && (
              e.target.parentElement.classList.contains('share-btn') ||
              e.target.parentElement.classList.contains('share-news-modal-btn') ||
              e.target.parentElement.classList.contains('share-infographic-modal-btn') ||
              e.target.parentElement.classList.contains('share-publication-modal-btn')
            );
            
            const targetBtn = shareBtn || (isShareButton ? e.target.closest('button') : null) || (parentIsShareBtn ? e.target.closest('button') : null);
            
            if (targetBtn) {
              // Skip if this button has an onclick handler (to avoid conflicts with page-specific handlers)
              if (targetBtn.hasAttribute('onclick')) {
                console.log('Skipping button with onclick handler:', targetBtn);
                return;
              }
              
              console.log('Share button detected:', targetBtn, 'Classes:', targetBtn.className); // Debug
              e.preventDefault();
              e.stopPropagation();
              
              // Get title and URL based on content type
              let title = '';
              let url = '';
              
              // Try different data attributes
              if (targetBtn.dataset.infographicTitle) {
                title = targetBtn.dataset.infographicTitle;
                url = targetBtn.dataset.infographicUrl || window.location.href;
              } else if (targetBtn.dataset.pubTitle) {
                title = targetBtn.dataset.pubTitle;
                url = targetBtn.dataset.pubUrl || window.location.href;
              } else if (targetBtn.dataset.newsTitle) {
                title = targetBtn.dataset.newsTitle;
                url = targetBtn.dataset.newsUrl || window.location.href;
              } else if (targetBtn.dataset.shareTitle) {
                title = targetBtn.dataset.shareTitle;
                url = targetBtn.dataset.shareUrl || window.location.href;
              } else {
                title = 'Konten';
                url = window.location.href;
              }
              
              console.log('Share data extracted:', { title, url, dataset: targetBtn.dataset }); // Debug
              
              // Ensure URL is complete (add origin if relative)
              if (url && !url.startsWith('http://') && !url.startsWith('https://')) {
                url = window.location.origin + (url.startsWith('/') ? url : '/' + url);
              }
              
              // Ensure URL is a string
              url = String(url);
              
              console.log('Share button clicked:', { title, url, button: targetBtn }); // Debug log
              
              // Directly copy to clipboard (no Web Share API or modal)
              await copyToClipboardGlobal(url, title, targetBtn);
            }
          });
        });
        
        // Global copy to clipboard function (maintains user interaction context)
        async function copyToClipboardGlobal(text, title, button) {
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
              
              // Visual feedback on button
              if (button) {
                const originalHTML = button.innerHTML;
                const originalClasses = button.className;
                button.innerHTML = '<i class="bi bi-check"></i> <span class="share-btn-text">Tersalin!</span>';
                button.classList.add('btn-success');
                button.classList.remove('btn-light', 'btn-outline-secondary', 'btn-outline-primary');
                
                setTimeout(() => {
                  button.innerHTML = originalHTML;
                  button.className = originalClasses;
                }, 2000);
              }
              
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
          fallbackCopyToClipboardGlobal(text, title, button);
        }
        
        // Global fallback copy function
        function fallbackCopyToClipboardGlobal(text, title, button) {
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
              
              // Visual feedback on button
              if (button) {
                const originalHTML = button.innerHTML;
                const originalClasses = button.className;
                button.innerHTML = '<i class="bi bi-check"></i> <span class="share-btn-text">Tersalin!</span>';
                button.classList.add('btn-success');
                button.classList.remove('btn-light', 'btn-outline-secondary', 'btn-outline-primary');
                
                setTimeout(() => {
                  button.innerHTML = originalHTML;
                  button.className = originalClasses;
                }, 2000);
              }
              
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
        
        // Simple copy function for share modal button
        function copyShareLink() {
          const input = document.getElementById('shareModalInput');
          const copyBtn = document.getElementById('shareModalCopyBtn');
          const copyBtnText = document.getElementById('shareModalCopyBtnText');
          
          if (!input) {
            console.error('Share modal input not found!');
            return;
          }
          
          const url = input.value;
          if (!url) {
            showShareToast('Tidak ada link untuk disalin');
            return;
          }
          
          // Select text first
          input.select();
          input.setSelectionRange(0, url.length);
          input.focus();
          
          // Try Clipboard API first
          if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(() => {
              showShareToast('Link telah disalin ke clipboard!');
              
              // Visual feedback on button
              if (copyBtn && copyBtnText) {
                const originalText = copyBtnText.textContent;
                const originalIcon = copyBtn.querySelector('i').className;
                
                copyBtn.querySelector('i').className = 'bi bi-check';
                copyBtnText.textContent = 'Tersalin!';
                copyBtn.classList.add('btn-success');
                copyBtn.classList.remove('btn-primary');
                
                setTimeout(() => {
                  copyBtn.querySelector('i').className = originalIcon;
                  copyBtnText.textContent = originalText;
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
        window.showShareModal = showShareModal; // Already defined globally above
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
            <div class="input-group mb-3">
              <input 
                type="text" 
                class="form-control" 
                id="shareModalInput" 
                readonly
                style="font-family: monospace; font-size: 0.9rem;"
              >
              <button 
                class="btn btn-primary" 
                type="button" 
                id="shareModalCopyBtn"
                onclick="copyShareLink()"
                style="border-top-left-radius: 0; border-bottom-left-radius: 0; white-space: nowrap;"
              >
                <i class="bi bi-clipboard"></i> <span id="shareModalCopyBtnText">Salin</span>
              </button>
            </div>
            <div class="alert alert-info mb-0" role="alert">
              <i class="bi bi-info-circle"></i> <small>Klik tombol "Salin" untuk menyalin link ke clipboard, atau pilih teks dan salin dengan Ctrl+C (Cmd+C di Mac)</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
    
    <script src="{{ asset('js/share-utils.js') }}"></script>
    @vite('resources/js/utilities.js')
    @stack('scripts')
  </body>
</html>
