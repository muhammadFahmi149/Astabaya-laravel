<!-- Modal for Infographic Detail -->
<div class="modal fade" id="infographicModal" tabindex="-1" aria-labelledby="infographicModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" id="infographicModalDialog">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title flex-grow-1" id="infographicModalTitle"></h5>
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    @include('components.share-button', [
                        'title' => '',
                        'url' => '',
                        'contentType' => 'infographic',
                        'size' => 'sm',
                        'variant' => 'outline-secondary',
                        'showText' => true,
                        'class' => 'share-infographic-modal-btn'
                    ])
                    <button type="button" class="btn btn-outline-secondary btn-sm bookmark-btn" id="modalInfographicBookmarkBtn" data-content-type="infographic" data-object-id="" data-bookmark-id="" onclick="handleInfographicBookmark(this)">
                        <i class="bi bi-bookmark"></i> <span class="d-none d-md-inline">Bookmark</span>
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0 h-100 m-0">
                    <!-- Main Infographic -->
                    <div class="col-lg-8 border-end infographic-modal-left">
                        <div class="infographic-modal-img-wrap">
                            <img id="infographicModalImage" src="" alt="" class="img-fluid" />
                        </div>
                        <div class="infographic-modal-actions d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="d-flex gap-2">
                                @include('components.share-button', [
                                    'title' => '',
                                    'url' => '',
                                    'contentType' => 'infographic',
                                    'size' => 'sm',
                                    'variant' => 'outline-secondary',
                                    'showText' => true,
                                    'class' => 'share-infographic-btn'
                                ])
                            </div>
                            <button type="button" id="infographicModalDownload" class="btn btn-primary btn-sm download-infographic-btn" data-infographic-id="" data-infographic-title="" data-infographic-url="" onclick="handleInfographicDownload(this)">
                                <i class="bi bi-download"></i> <span class="d-none d-md-inline">Unduh</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Related Infographics -->
                    <div class="col-lg-4 infographic-modal-right">
                        <div class="p-3">
                            <h6 class="fw-bold mb-3">Lainnya</h6>
                            <div id="relatedInfographics" class="d-flex flex-column gap-3">
                                <!-- Related items will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
