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
            <div class="mb-2" id="modalSubjectCsaContainer">
              <strong>Subjek CSA:</strong>
              <span id="modalSubjectCsa"></span>
            </div>
            <hr />
            <div class="abstract-container">
              <strong>Abstrak:</strong>
              <div id="modalAbstract" class="mt-2 text-muted" style="text-align: justify;"></div>
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
          @include('components.share-button', [
              'title' => '',
              'url' => '',
              'contentType' => 'publication',
              'size' => 'sm',
              'variant' => 'outline-secondary',
              'showText' => true,
              'class' => 'modal-footer-btn-left share-publication-modal-btn'
          ])
          <a id="modalDownload" href="" target="_blank" class="btn btn-primary download-publication-btn modal-footer-btn-right" data-pub-id="" data-pub-title=""> <i class="bi bi-download"></i> Unduh PDF </a>
        </div>
      </div>
    </div>
  </div>
</div>
