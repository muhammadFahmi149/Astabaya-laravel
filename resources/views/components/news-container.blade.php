@push('styles')
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

    /* Ensure consistent card styling */
    .news-item .card-img-wrapper,
    .news-item img {
        transition: transform 0.3s ease;
    }

    .news-item .card:hover img {
        transform: scale(1.02);
    }
</style>
@endpush

@push('scripts')
<script>
    function renderNews() {
        const container = document.getElementById('newsContainer');
        if (!container) return;

        if (news.length === 0) {
            container.innerHTML = '<div class="col-12"><div class="alert alert-info text-center">Belum ada data berita.</div></div>';
            return;
        }

        // Note: News count will be updated in renderPagination with total from API

        container.innerHTML = news.map((item, index) => `
            <div class="col-12 mb-4 news-item">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row d-none d-md-flex align-items-center">
                            <div class="col-md-3">
                                ${item.picture_url ? `
                                    <img src="${item.picture_url}" alt="${item.title}" 
                                         class="img-fluid rounded shadow-sm" 
                                         style="width: 100%; height: 200px; min-height: 200px; object-fit: cover; cursor: pointer;"
                                         onclick="showNewsModal(${index})">
                                ` : `
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm" 
                                         style="width: 100%; height: 200px; min-height: 200px; cursor: pointer;"
                                         onclick="showNewsModal(${index})">
                                        <i class="bi bi-newspaper" style="font-size: 3rem; color: #ccc;"></i>
                                    </div>
                                `}
                            </div>
                            <div class="col-md-9">
                                <h6 class="card-title mb-2 fw-bold" style="cursor: pointer; min-height: 48px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" onclick="showNewsModal(${index})">${item.title}</h6>
                                <div class="mb-2">
                                    ${item.category_name ? `
                                        <span class="badge bg-primary me-2">${item.category_name}</span>
                                    ` : ''}
                                    ${item.release_date ? `
                                        <span class="badge bg-info">
                                            <i class="bi bi-calendar"></i> ${formatDate(item.release_date)}
                                        </span>
                                    ` : ''}
                                </div>
                                <p class="card-text text-muted mb-3" style="min-height: 60px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">${truncateText(item.content || '', 150)}</p>
                                <button class="btn btn-sm btn-primary" onclick="showNewsModal(${index})">
                                    <i class="bi bi-book"></i> Baca Selengkapnya
                                </button>
                            </div>
                        </div>
                        <!-- Mobile Layout -->
                        <div class="row d-md-none">
                            <div class="col-12">
                                <div class="d-flex gap-2 align-items-start">
                                    ${item.picture_url ? `
                                        <img src="${item.picture_url}" alt="${item.title}" 
                                             class="rounded shadow-sm" 
                                             style="width: 80px; height: 80px; min-width: 80px; min-height: 80px; object-fit: cover; cursor: pointer; flex-shrink: 0;"
                                             onclick="showNewsModal(${index})">
                                    ` : `
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm" 
                                             style="width: 80px; height: 80px; min-width: 80px; min-height: 80px; cursor: pointer; flex-shrink: 0;"
                                             onclick="showNewsModal(${index})">
                                            <i class="bi bi-newspaper" style="font-size: 1.5rem; color: #ccc;"></i>
                                        </div>
                                    `}
                                    <div class="flex-grow-1">
                                        <h6 class="card-title mb-1 fw-bold" style="font-size: 0.9rem; cursor: pointer; min-height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" onclick="showNewsModal(${index})">${item.title}</h6>
                                        <div class="mb-1">
                                            ${item.category_name ? `
                                                <span class="badge bg-primary me-1" style="font-size: 0.7rem;">${item.category_name}</span>
                                            ` : ''}
                                            ${item.release_date ? `
                                                <span class="badge bg-info" style="font-size: 0.7rem;">
                                                    <i class="bi bi-calendar"></i> ${formatDate(item.release_date)}
                                                </span>
                                            ` : ''}
                                        </div>
                                        <p class="card-text text-muted mb-2" style="font-size: 0.85rem; min-height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">${truncateText(item.content || '', 80)}</p>
                                        <button class="btn btn-sm btn-primary" onclick="showNewsModal(${index})" style="font-size: 0.8rem;">
                                            <i class="bi bi-book"></i> Baca
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function renderPagination(data) {
        const paginationContainer = document.getElementById('paginationContainer');
        if (!paginationContainer) return;

        // Check if pagination data exists - handle different response structures
        let meta, links;
        
        if (data.meta && data.links) {
            // Standard Laravel pagination response
            meta = data.meta;
            links = data.links;
        } else if (data.pagination) {
            // Alternative structure
            meta = data.pagination.meta || data.pagination;
            links = data.pagination.links || {};
        } else {
            // No pagination data
            paginationContainer.innerHTML = '';
            const paginationInfo = document.getElementById('paginationInfo');
            if (paginationInfo) {
                paginationInfo.textContent = '';
            }
            return;
        }

        if (!meta || !links) {
            paginationContainer.innerHTML = '';
            return;
        }
        const currentPage = meta.current_page || 1;
        const lastPage = meta.last_page || 1;
        const total = meta.total || 0;
        const perPage = meta.per_page || 20;
        const from = meta.from || 0;
        const to = meta.to || 0;

        // Update news count with total
        const newsCountElement = document.getElementById('newsCount');
        if (newsCountElement) {
            newsCountElement.textContent = total;
        }

        // Don't show pagination if there's only one page
        if (lastPage <= 1) {
            paginationContainer.innerHTML = '';
            return;
        }

        let paginationHTML = '';

        // Previous button
        if (links.prev) {
            paginationHTML += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="event.preventDefault(); goToPage(${currentPage - 1});">
                        <i class="bi bi-chevron-left"></i> Previous
                    </a>
                </li>
            `;
        } else {
            paginationHTML += `
                <li class="page-item disabled">
                    <span class="page-link"><i class="bi bi-chevron-left"></i> Previous</span>
                </li>
            `;
        }

        // Page numbers - show current page and 2 pages before/after
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(lastPage, currentPage + 2);

        // Show first page if not in range
        if (startPage > 1) {
            paginationHTML += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="event.preventDefault(); goToPage(1);">1</a>
                </li>
            `;
            if (startPage > 2) {
                paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }

        // Show page numbers
        for (let i = startPage; i <= endPage; i++) {
            if (i === currentPage) {
                paginationHTML += `
                    <li class="page-item active">
                        <span class="page-link">${i}</span>
                    </li>
                `;
            } else {
                paginationHTML += `
                    <li class="page-item">
                        <a class="page-link" href="#" onclick="event.preventDefault(); goToPage(${i});">${i}</a>
                    </li>
                `;
            }
        }

        // Show last page if not in range
        if (endPage < lastPage) {
            if (endPage < lastPage - 1) {
                paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
            paginationHTML += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="event.preventDefault(); goToPage(${lastPage});">${lastPage}</a>
                </li>
            `;
        }

        // Next button
        if (links.next) {
            paginationHTML += `
                <li class="page-item">
                    <a class="page-link" href="#" onclick="event.preventDefault(); goToPage(${currentPage + 1});">
                        Next <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            `;
        } else {
            paginationHTML += `
                <li class="page-item disabled">
                    <span class="page-link">Next <i class="bi bi-chevron-right"></i></span>
                </li>
            `;
        }

        paginationContainer.innerHTML = paginationHTML;

        // Add pagination info text
        const paginationInfo = document.getElementById('paginationInfo');
        if (paginationInfo) {
            paginationInfo.textContent = `Menampilkan ${from} sampai ${to} dari ${total} berita`;
        }
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
    }

    function truncateText(text, length) {
        return text.length > length ? text.substring(0, length) + '...' : text;
    }

    function showNewsModal(index) {
        const item = news[index];
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
        if (modalCategory) modalCategory.textContent = item.category_name || 'Umum';
        if (modalDate) {
            modalDate.innerHTML = `<i class="bi bi-calendar"></i> ${item.release_date ? formatDate(item.release_date) : 'N/A'}`;
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
            
            const imageToLoad = item.picture_url || '';
            
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

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('newsCardModal'));
        modal.show();
    }
</script>
@endpush

<!-- News Timeline -->
<div class="row" id="newsContainer">
    <div class="col-12">
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</div>

<!-- Pagination -->
<div class="mt-4">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center" id="paginationContainer">
            <!-- Pagination will be rendered here -->
        </ul>
    </nav>
    <p class="text-center text-muted small mt-2" id="paginationInfo">
        <!-- Pagination info will be rendered here -->
    </p>
</div>

