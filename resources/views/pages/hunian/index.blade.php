@extends('app')

@section('title', 'Data Kepemilikan Hunian')

@section('content')
<div class="page-heading">
    <div class="page-title mb-3">
        <div class="row align-items-center">
            <div class="col-12 col-md-6">
                <h3 class="fw-bold mb-1 text-primary">Kepemilikan Hunian & Lahan</h3>
                <p class="text-muted mb-0">Pendataan status kepemilikan rumah, hak atas tanah, dan kawasan hunian warga</p>
            </div>
            <div class="col-12 col-md-6 text-md-end mt-3 mt-md-0">
                @if(Route::has('hunian.create'))
                    <a href="{{ route('hunian.create') }}" class="btn btn-primary shadow-sm">
                        <i class="bi bi-plus-circle-fill me-1"></i> Tambah Data Hunian
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible show fade">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filter & Pencarian -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('hunian.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold text-muted">Cari Nama / NIK</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Ketik NIK atau Nama..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-semibold text-muted">Filter Kelurahan</label>
                        <select name="id_wilayah" class="form-select">
                            <option value="">Semua Kelurahan</option>
                            @foreach($wilayahs as $wilayah)
                                <option value="{{ $wilayah->id_wilayah }}" {{ request('id_wilayah') == $wilayah->id_wilayah ? 'selected' : '' }}>
                                    {{ $wilayah->desa }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-semibold text-muted">Status Kepemilikan</label>
                        <select name="status_rumah" class="form-select">
                            <option value="">Semua Status Rumah</option>
                            <option value="1" {{ request('status_rumah') == '1' ? 'selected' : '' }}>Milik Sendiri</option>
                            <option value="2" {{ request('status_rumah') == '2' ? 'selected' : '' }}>Sewa / Kontrak</option>
                            <option value="3" {{ request('status_rumah') == '3' ? 'selected' : '' }}>Menumpang</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                        @if(request()->hasAny(['search', 'id_wilayah', 'status_rumah', 'status_tanah']))
                            <a href="{{ route('hunian.index') }}" class="btn btn-outline-secondary" title="Reset">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Hunian -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title fw-bold mb-0">Daftar Kepemilikan Hunian</h5>
            <span class="badge bg-light-primary text-primary">Total: {{ $hunians->total() }} Data</span>
        </div>
        <div class="card-body px-0 pt-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Pemilik / Calon Penerima</th>
                            <th>Wilayah & Alamat</th>
                            <th>Status Kepemilikan</th>
                            <th>Luas & Penghuni</th>
                            <th class="text-end pe-4" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hunians as $index => $hunian)
                            <tr>
                                <td class="ps-4 fw-semibold text-muted">
                                    {{ $hunians->firstItem() + $index }}
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $hunian->warga->nama ?? '-' }}</div>
                                    <small class="text-muted d-block">NIK: {{ $hunian->warga->nik ?? '-' }}</small>
                                </td>
                                <td>
                                    <div>{{ $hunian->warga->alamat ?? '-' }}</div>
                                    <small class="text-primary fw-semibold">
                                        {{ $hunian->warga->wilayah->desa ?? 'Kelurahan' }}
                                    </small>
                                </td>
                                <td>
                                    <div>
                                        @if($hunian->status_rumah == 1)
                                            <span class="badge bg-light-success text-success"><i class="bi bi-house-door-fill me-1"></i>Milik Sendiri</span>
                                        @elseif($hunian->status_rumah == 2)
                                            <span class="badge bg-light-warning text-dark"><i class="bi bi-key-fill me-1"></i>Sewa / Kontrak</span>
                                        @else
                                            <span class="badge bg-light-secondary text-secondary"><i class="bi bi-people-fill me-1"></i>Menumpang</span>
                                        @endif
                                    </div>
                                    <small class="text-muted">
                                        Tanah: 
                                        @if($hunian->status_tanah == 1) SHM / Milik Sendiri
                                        @elseif($hunian->status_tanah == 2) Girik / Letter C
                                        @else Tanah Negara / Lainnya @endif
                                    </small>
                                </td>
                                <td>
                                    <small class="d-block text-dark">Rumah: <strong>{{ $hunian->luas_rumah ?? '-' }} m²</strong> | Tanah: <strong>{{ $hunian->luas_tanah ?? '-' }} m²</strong></small>
                                    <small class="text-muted">{{ $hunian->jml_kk ?? 1 }} KK &bull; {{ $hunian->jml_penghuni ?? 1 }} Penghuni</small>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        @if(Route::has('hunian.show'))
                                            <a href="{{ route('hunian.show', $hunian->id_hunian) }}" class="btn btn-light-primary" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endif
                                        @if(Route::has('hunian.edit'))
                                            <a href="{{ route('hunian.edit', $hunian->id_hunian) }}" class="btn btn-light-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="py-4">
                                        <i class="bi bi-geo-alt fs-1 d-block mb-3 text-muted opacity-50"></i>
                                        <h6 class="fw-bold text-secondary">Belum ada data kepemilikan hunian</h6>
                                        <p class="text-muted small mb-3">Data hunian & lahan calon penerima belum ditambahkan.</p>
                                        @if(Route::has('hunian.create'))
                                            <a href="{{ route('hunian.create') }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-plus-lg me-1"></i> Tambah Data Hunian Baru
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($hunians->hasPages())
                <div class="card-footer border-0 py-3 d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Menampilkan {{ $hunians->firstItem() ?? 0 }} - {{ $hunians->lastItem() ?? 0 }} dari {{ $hunians->total() }} data
                    </small>
                    {{ $hunians->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
