@extends('app')
@section('title', 'Master Data Jenis Bantuan')

@section('content')
<div class="page-heading">
    <!-- Page Header Versi Stitch -->
    <div class="d-flex flex-column flex-md-row md:items-start justify-content-between gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1 font-headline" style="color: var(--stitch-primary);">
                <span class="material-symbols-outlined me-2 text-primary" style="font-size: 28px; vertical-align: middle;">card_giftcard</span>Master Data Jenis Bantuan
            </h3>
            <p class="text-muted mb-0 font-stitch" style="font-size: 13.5px;">Kelola daftar jenis program bantuan RTLH yang tersedia untuk calon penerima Kota Kediri.</p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2 shrink-0">
            <button type="button" class="btn btn-sm btn-primary shadow-sm px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1 font-stitch" data-bs-toggle="modal" data-bs-target="#modalTambah" style="font-size: 12.5px; border-radius: 8px;">
                <span class="material-symbols-outlined" style="font-size: 17px;">add_circle</span>
                <span>Tambah Jenis Bantuan</span>
            </button>
        </div>
    </div>

    {{-- Flash Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4 font-stitch" role="alert" style="font-size: 13.5px;">
            <div class="d-flex align-items-center">
                <span class="material-symbols-outlined fs-4 me-2 text-success">check_circle</span>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4 font-stitch" role="alert" style="font-size: 13.5px;">
            <div class="d-flex align-items-center">
                <span class="material-symbols-outlined fs-4 me-2 text-danger">warning</span>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Tabel Jenis Bantuan Versi Stitch --}}
    <div class="stitch-card">
        <!-- Header Tabel & Live Badge Counter -->
        <div class="p-3 bg-white border-bottom d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
            <div>
                <h5 class="fw-bold text-dark mb-0 font-headline">Daftar Jenis Bantuan RTLH</h5>
                <small class="text-muted font-stitch">Data master program bantuan acuan dalam pengusulan dan penanganan fisik warga.</small>
            </div>
            <div class="stitch-header-badge font-stitch">
                <span class="dot"></span>
                <span>Total: <strong class="font-mono-num">{{ $programs->count() }}</strong> Program</span>
            </div>
        </div>

        <div class="card-body p-0">
            @if($programs->isEmpty())
                <div class="text-center py-5">
                    <span class="material-symbols-outlined text-muted mb-2" style="font-size: 48px;">card_giftcard</span>
                    <h5 class="text-muted mt-2 fw-semibold font-headline">Belum ada jenis bantuan</h5>
                    <p class="text-muted small font-stitch mb-3">Silakan tambahkan jenis bantuan pertama dengan menekan tombol <strong>+ Tambah Jenis Bantuan</strong> di atas.</p>
                    <button type="button" class="btn btn-primary btn-sm font-stitch" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <span class="material-symbols-outlined me-1" style="font-size: 16px; vertical-align: middle;">add</span> Tambah Jenis Bantuan Pertama
                    </button>
                </div>
            @else
                <div class="table-responsive">
                    <table class="stitch-table">
                        <thead>
                            <tr>
                                <th class="ps-3" style="width: 60px;">No</th>
                                <th>Nama Jenis Bantuan</th>
                                <th class="text-center" style="width: 140px;">Status</th>
                                <th class="text-end pe-3" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($programs as $i => $p)
                            <tr>
                                <td class="ps-3 tabular-nums font-mono-num text-muted">{{ $i + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="d-flex align-items-center justify-content-center rounded" style="width: 32px; height: 32px; background: var(--stitch-info-bg); color: var(--stitch-info-text);">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">card_giftcard</span>
                                        </div>
                                        <div class="fw-semibold text-dark font-stitch" style="font-size: 13.5px;">{{ $p->nama_program }}</div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($p->is_aktif)
                                        <span class="micro-pill micro-pill-success mx-auto">
                                            <span class="dot"></span>Aktif
                                        </span>
                                    @else
                                        <span class="micro-pill mx-auto" style="background: #F1F5F9; color: #475569;">
                                            <span class="dot" style="background-color: #94A3B8;"></span>Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <div class="d-inline-flex align-items-center justify-content-end gap-1">
                                        <button type="button" class="stitch-btn-edit"
                                            onclick="openEditModal(this)"
                                            data-id="{{ $p->id_program }}"
                                            data-nama="{{ $p->nama_program }}"
                                            data-aktif="{{ $p->is_aktif ? '1' : '0' }}"
                                            title="Edit Program">
                                            <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>
                                        </button>
                                        <button type="button" class="stitch-btn-delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalHapus{{ $p->id_program }}"
                                            title="Hapus Program">
                                            <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Konfirmasi Hapus per baris --}}
                            <div class="modal fade" id="modalHapus{{ $p->id_program }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header border-bottom">
                                            <h5 class="modal-title fw-bold text-danger font-headline">
                                                <span class="material-symbols-outlined me-2 text-danger" style="font-size: 20px; vertical-align: middle;">warning</span>Konfirmasi Hapus
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4 font-stitch">
                                            <p class="mb-1">Apakah Anda yakin ingin menghapus jenis bantuan berikut?</p>
                                            <div class="bg-light rounded p-3 mt-2 border">
                                                <strong class="text-dark">{{ $p->nama_program }}</strong>
                                            </div>
                                            <p class="text-danger small mt-2 mb-0">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Jenis bantuan tidak dapat dihapus jika sudah digunakan pada data warga.
                                            </p>
                                        </div>
                                        <div class="modal-footer border-top">
                                            <button type="button" class="btn btn-secondary btn-sm font-stitch" data-bs-dismiss="modal">
                                                <i class="bi bi-x me-1"></i> Batal
                                            </button>
                                            <form action="{{ route('program-bantuan.destroy', $p->id_program) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm font-stitch">
                                                    <span class="material-symbols-outlined me-1" style="font-size: 16px; vertical-align: middle;">delete</span> Ya, Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- End Modal Hapus --}}

                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ===================== MODAL TAMBAH ===================== --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="labelModalTambah" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold font-headline" style="color: var(--stitch-primary);" id="labelModalTambah">
                    <span class="material-symbols-outlined me-2" style="font-size: 22px; vertical-align: middle;">add_circle</span>Tambah Jenis Bantuan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('program-bantuan.store') }}" method="POST">
                @csrf
                <div class="modal-body font-stitch">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Jenis Bantuan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_program"
                            class="form-control @error('nama_program') is-invalid @enderror"
                            placeholder="Contoh: Bantuan Stimulan Perumahan Swadaya (BSPS)"
                            maxlength="150" required value="{{ old('nama_program') }}" autofocus>
                        @error('nama_program') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-1">
                        <div class="form-check form-switch p-3 bg-light rounded border">
                            <input class="form-check-input ms-0 me-2" type="checkbox" name="is_aktif" value="1" id="isAktifTambah" checked>
                            <label class="form-check-label fw-semibold" for="isAktifTambah">Status Aktif</label>
                            <small class="text-muted d-block">Jenis bantuan aktif dapat dipilih saat pendataan warga</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top font-stitch">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <span class="material-symbols-outlined me-1" style="font-size: 16px; vertical-align: middle;">save</span> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===================== MODAL EDIT (Dinamis via JS) ===================== --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="labelModalEdit" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold text-dark font-headline" id="labelModalEdit">
                    <span class="material-symbols-outlined me-2 text-warning" style="font-size: 22px; vertical-align: middle;">edit</span>Edit Jenis Bantuan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body font-stitch">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Jenis Bantuan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_program" id="editNamaProgram" class="form-control"
                            placeholder="Nama jenis bantuan" maxlength="150" required>
                    </div>
                    <div class="mb-1">
                        <div class="form-check form-switch p-3 bg-light rounded border">
                            <input class="form-check-input ms-0 me-2" type="checkbox" name="is_aktif" value="1" id="editIsAktif">
                            <label class="form-check-label fw-semibold" for="editIsAktif">Status Aktif</label>
                            <small class="text-muted d-block">Jenis bantuan aktif dapat dipilih saat pendataan warga</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top font-stitch">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-warning text-dark fw-semibold btn-sm">
                        <span class="material-symbols-outlined me-1" style="font-size: 16px; vertical-align: middle;">save</span> Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(btn) {
    const id    = btn.dataset.id;
    const nama  = btn.dataset.nama;
    const aktif = btn.dataset.aktif;

    document.getElementById('formEdit').action = '/program-bantuan/' + id;
    document.getElementById('editNamaProgram').value = nama || '';
    document.getElementById('editIsAktif').checked = (aktif === '1');

    var modal = new bootstrap.Modal(document.getElementById('modalEdit'));
    modal.show();
}
</script>

@endsection
