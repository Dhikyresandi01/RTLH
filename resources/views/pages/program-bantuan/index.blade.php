@extends('app')
@section('title', 'Master Data Jenis Bantuan')

@section('content')
<style>
.table-program thead th {
    background: #f8f9fa;
    font-weight: 700;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #495057;
}
</style>

<div class="page-heading">
    {{-- Page Header --}}
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-12 col-md-7">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Master Data</li>
                        <li class="breadcrumb-item active" aria-current="page">Jenis Bantuan</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-1 text-primary">Master Data Jenis Bantuan</h3>
                <p class="text-muted small mb-0">Kelola daftar jenis bantuan yang tersedia untuk calon penerima RTLH Kota Kediri.</p>
            </div>
            <div class="col-12 col-md-5 text-md-end mt-3 mt-md-0">
                <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Jenis Bantuan
                </button>
            </div>
        </div>
    </div>

    {{-- Flash Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-4 me-2 text-success"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-octagon-fill fs-4 me-2 text-danger"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Tabel Jenis Bantuan --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <div class="d-flex align-items-center">
                <i class="bi bi-gift-fill text-primary me-2 fs-5"></i>
                <h5 class="card-title mb-0 fw-bold text-dark">Daftar Jenis Bantuan</h5>
                <span class="badge bg-primary ms-2 rounded-pill">{{ $programs->count() }}</span>
            </div>
        </div>
        <div class="card-body p-0">
            @if($programs->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-gift text-muted" style="font-size: 3.5rem;"></i>
                    <h5 class="text-muted mt-3 fw-semibold">Belum ada jenis bantuan</h5>
                    <p class="text-muted small">Silakan tambahkan jenis bantuan pertama dengan menekan tombol <strong>+ Tambah Jenis Bantuan</strong> di atas.</p>
                    <button type="button" class="btn btn-primary btn-sm mt-1" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Jenis Bantuan Pertama
                    </button>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-program">
                        <thead>
                            <tr>
                                <th class="ps-4" style="width:60px;">No</th>
                                <th>Nama Jenis Bantuan</th>
                                <th class="text-center" style="width:140px;">Status</th>
                                <th class="text-center pe-4" style="width:140px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($programs as $i => $p)
                            <tr>
                                <td class="ps-4 text-muted small fw-semibold">{{ $i + 1 }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $p->nama_program }}</div>
                                </td>
                                <td class="text-center">
                                    @if($p->is_aktif)
                                        <span class="badge bg-success px-3 py-1">
                                            <i class="bi bi-check-circle me-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-1">
                                            <i class="bi bi-dash-circle me-1"></i>Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            onclick="openEditModal(this)"
                                            data-id="{{ $p->id_program }}"
                                            data-nama="{{ $p->nama_program }}"
                                            data-aktif="{{ $p->is_aktif ? '1' : '0' }}"
                                            title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalHapus{{ $p->id_program }}"
                                            title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Konfirmasi Hapus per baris --}}
                            <div class="modal fade" id="modalHapus{{ $p->id_program }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold text-danger">
                                                <i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Hapus
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body pt-2">
                                            <p class="mb-1">Apakah Anda yakin ingin menghapus jenis bantuan berikut?</p>
                                            <div class="bg-light rounded p-3 mt-2">
                                                <strong class="text-dark">{{ $p->nama_program }}</strong>
                                            </div>
                                            <p class="text-danger small mt-2 mb-0">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Jenis bantuan tidak dapat dihapus jika sudah digunakan pada data warga.
                                            </p>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                                <i class="bi bi-x me-1"></i> Batal
                                            </button>
                                            <form action="{{ route('program-bantuan.destroy', $p->id_program) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash me-1"></i> Ya, Hapus
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
                <h5 class="modal-title fw-bold text-primary" id="labelModalTambah">
                    <i class="bi bi-plus-circle-fill me-2"></i> Tambah Jenis Bantuan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('program-bantuan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Jenis Bantuan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_program"
                            class="form-control @error('nama_program') is-invalid @enderror"
                            placeholder="Contoh: Bantuan Stimulan Perumahan Swadaya (BSPS)"
                            maxlength="150" required value="{{ old('nama_program') }}" autofocus>
                        @error('nama_program') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-1">
                        <div class="form-check form-switch p-3 bg-light rounded">
                            <input class="form-check-input ms-0 me-2" type="checkbox" name="is_aktif" value="1" id="isAktifTambah" checked>
                            <label class="form-check-label fw-semibold" for="isAktifTambah">Status Aktif</label>
                            <small class="text-muted d-block">Jenis bantuan aktif dapat dipilih saat pendataan warga</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan
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
                <h5 class="modal-title fw-bold text-warning" id="labelModalEdit">
                    <i class="bi bi-pencil-fill me-2"></i> Edit Jenis Bantuan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Jenis Bantuan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_program" id="editNamaProgram" class="form-control"
                            placeholder="Nama jenis bantuan" maxlength="150" required>
                    </div>
                    <div class="mb-1">
                        <div class="form-check form-switch p-3 bg-light rounded">
                            <input class="form-check-input ms-0 me-2" type="checkbox" name="is_aktif" value="1" id="editIsAktif">
                            <label class="form-check-label fw-semibold" for="editIsAktif">Status Aktif</label>
                            <small class="text-muted d-block">Jenis bantuan aktif dapat dipilih saat pendataan warga</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-warning text-white">
                        <i class="bi bi-save me-1"></i> Perbarui
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
