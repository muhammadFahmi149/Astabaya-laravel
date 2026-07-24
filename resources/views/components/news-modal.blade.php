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
