<div class="modal fade" id="globalChartModal" tabindex="-1" aria-labelledby="globalChartModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content" style="background-color: #ffffff; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1); border-radius: 12px;">
      
      <div class="modal-header position-relative" style="border-bottom: none; padding: 1.5rem 1.5rem 1rem 1.5rem; flex-wrap: wrap; gap: 10px;">
        <div style="padding-right: 2rem; width: 100%; display: flex; flex-direction: column; gap: 10px;">
            <h5 class="modal-title m-0" id="globalChartModalLabel" style="color: #333; font-weight: 600; font-size: 1.15rem; line-height: 1.4;">Judul Grafik</h5>
            
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Modal Actions (Share & Download) -->
                <button class="btn btn-sm btn-outline-primary" id="modalShareBtn" title="Salin link grafik" style="padding: 5px 10px; border-radius: 5px;">
                    <i class="fas fa-share-alt"></i> <span class="share-text d-none d-sm-inline">Bagikan</span>
                </button>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="modalDownloadDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px 10px; border-radius: 5px;">
                    <i class="fas fa-download"></i> <span class="d-none d-sm-inline">Unduh</span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="modalDownloadDropdown" style="border-radius: 8px;">
                    <li><a class="dropdown-item" href="#" id="modalDownloadExcel" style="border-radius: 4px;"><i class="fas fa-file-excel"></i> Excel</a></li>
                    <li><a class="dropdown-item" href="#" id="modalDownloadPNG" style="border-radius: 4px;"><i class="fas fa-image"></i> PNG</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <button type="button" class="btn-close position-absolute" style="top: 1.5rem; right: 1.5rem;" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body" style="padding: 0 1.5rem 1.5rem 1.5rem; overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <!-- Canvas for the cloned chart -->
        <div style="min-width: 550px;">
            <div id="modalChartCanvas" style="width: 100%; height: 70vh; min-height: 400px;"></div>
        </div>
      </div>
    </div>
  </div>
</div>
