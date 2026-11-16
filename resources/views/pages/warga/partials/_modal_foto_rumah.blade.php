@php
    $rumah = $rumah ?? $warga?->Hunian?->first()?->Rumah?->first();
    $fotos = $rumah?->DokumenRumah ?? collect();
@endphp

@if($warga && $fotos->count() > 0)
<div class="modal fade" id="modalFoto{{ $warga->id_warga }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-images text-primary me-2"></i>Foto Dokumentasi: {{ $warga->nama }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    @foreach($fotos as $foto)
                        <div class="col-12 col-md-4 text-center">
                            <div class="border rounded p-2 bg-light">
                                <a href="{{ asset('storage/' . $foto->path_foto) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $foto->path_foto) }}" alt="{{ $foto->jenis_foto }}" class="img-fluid rounded mb-2" style="max-height: 180px; object-fit: cover; width: 100%;">
                                </a>
                                <div class="fw-semibold small text-dark">{{ $foto->jenis_foto }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endif
