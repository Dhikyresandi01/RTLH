<!-- Modal Universal Preview Foto & Download Langsung -->
<div class="modal fade" id="modalUniversalPreviewFoto" tabindex="-1" aria-labelledby="universalPreviewTitle" aria-hidden="true" style="z-index: 1065;">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg overflow-hidden">
            <div class="modal-header text-white py-2 px-3" style="background-color: #054735;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-image text-warning me-2 fs-5"></i>
                    <div>
                        <h6 class="modal-title fw-bold mb-0 text-white" id="universalPreviewTitle">Preview Foto Dokumentasi</h6>
                        <small class="text-white-50" style="font-size: 0.75rem;">Sistem E-RTLH Kota Kediri</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-2" style="background-color: #0b1320; min-height: 250px; display: flex; align-items: center; justify-content: center;">
                <img id="universalPreviewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 72vh; max-width: 100%; object-fit: contain; box-shadow: 0 4px 20px rgba(0,0,0,0.5);">
            </div>
            <div class="modal-footer bg-light py-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center text-muted small">
                    <i class="bi bi-info-circle text-primary me-1"></i>
                    <span>Klik tombol <strong>Unduh Foto</strong> untuk menyimpan foto asli ke komputer Anda.</span>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Tutup
                    </button>
                    <a id="universalPreviewNewTab" href="#" target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Buka Tab Baru
                    </a>
                    <a id="universalPreviewDownload" href="#" download class="btn btn-success btn-sm px-3 fw-semibold shadow-sm">
                        <i class="bi bi-download me-1"></i>Unduh Foto
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
if (typeof window.bukaPreviewFoto === 'undefined') {
    window.bukaPreviewFoto = function(url, title, downloadName) {
        if (!url) return;
        const modalEl = document.getElementById('modalUniversalPreviewFoto');
        if (!modalEl) return;

        const imgEl = document.getElementById('universalPreviewImg');
        const titleEl = document.getElementById('universalPreviewTitle');
        const downloadEl = document.getElementById('universalPreviewDownload');
        const newTabEl = document.getElementById('universalPreviewNewTab');

        imgEl.src = url;
        titleEl.textContent = title || 'Preview Foto Dokumentasi';
        newTabEl.href = url;
        downloadEl.href = url;

        if (downloadName) {
            downloadEl.setAttribute('download', downloadName);
        } else {
            const cleanTitle = (title ? title.replace(/[^a-zA-Z0-9_\- ]/g, '').trim().replace(/\s+/g, '_') : 'foto_dokumen');
            downloadEl.setAttribute('download', cleanTitle + '.jpg');
        }

        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    };
}
</script>
