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
                                <a href="javascript:void(0)" onclick="bukaPreviewFoto('{{ asset('storage/' . $foto->path_foto) }}', '{{ addslashes($foto->jenis_foto) }} - {{ addslashes($warga->nama) }}', '{{ Str::slug($foto->jenis_foto) }}_{{ $warga->nik }}.jpg')" title="Klik untuk memperbesar & unduh">
                                    <img src="{{ asset('storage/' . $foto->path_foto) }}" alt="{{ $foto->jenis_foto }}" class="img-fluid rounded mb-2" style="max-height: 180px; object-fit: cover; width: 100%; cursor: pointer;">
                                </a>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <span class="fw-semibold small text-dark">{{ $foto->jenis_foto }}</span>
                                    <button type="button" class="btn btn-outline-success btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="bukaPreviewFoto('{{ asset('storage/' . $foto->path_foto) }}', '{{ addslashes($foto->jenis_foto) }} - {{ addslashes($warga->nama) }}', '{{ Str::slug($foto->jenis_foto) }}_{{ $warga->nik }}.jpg')" title="Perbesar & Unduh">
                                        <i class="bi bi-download me-1"></i>Unduh
                                    </button>
                                </div>
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
