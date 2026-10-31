@extends('app')

@section('title', 'Data Survei Fisik Rumah')

@section('content')
<div class="page-heading">
    <div class="page-title mb-3">
        <div class="row align-items-center">
            <div class="col-12 col-md-6">
                <h3 class="fw-bold mb-1 text-primary">Survei Fisik Rumah RTLH</h3>
                <p class="text-muted mb-0">Hasil survei konstruksi fisik (atap, lantai, dinding) dan utilitas rumah calon penerima</p>
            </div>
            <div class="col-12 col-md-6 text-md-end mt-3 mt-md-0">
                @if(Route::has('rumah.create'))
                    <a href="{{ route('rumah.create') }}" class="btn btn-primary shadow-sm">
                        <i class="bi bi-clipboard2-plus-fill me-1"></i> Tambah Hasil Survei Rumah
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
            <form method="GET" action="{{ route('rumah.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold text-muted">Cari Nama / NIK</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Ketik NIK atau Nama warga..." value="{{ request('search') }}">
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
                        <label class="form-label small fw-semibold text-muted">Kondisi Atap</label>
                        <select name="kondisi_atap" class="form-select">
                            <option value="">Semua Kondisi Atap</option>
                            <option value="3" {{ request('kondisi_atap') == '3' ? 'selected' : '' }}>Rusak Berat</option>
                            <option value="2" {{ request('kondisi_atap') == '2' ? 'selected' : '' }}>Rusak Sedang</option>
                            <option value="1" {{ request('kondisi_atap') == '1' ? 'selected' : '' }}>Baik</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                        @if(request()->hasAny(['search', 'id_wilayah', 'kondisi_atap']))
                            <a href="{{ route('rumah.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Rumah -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title fw-bold mb-0">Daftar Hasil Survei Fisik Rumah</h5>
            <span class="badge bg-light-primary text-primary">Total: {{ $rumahs->total() }} Rumah</span>
        </div>
        <div class="card-body px-0 pt-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Warga / Kepala Keluarga</th>
                            <th>Wilayah & Alamat</th>
                            <th>Kondisi Konstruksi (Atap / Dinding / Lantai)</th>
                            <th>Dokumentasi Foto</th>
                            <th class="text-end pe-4" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rumahs as $index => $rumah)
                            <tr>
                                <td class="ps-4 fw-semibold text-muted">
                                    {{ $rumahs->firstItem() + $index }}
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $rumah->hunian->warga->nama ?? '-' }}</div>
                                    <small class="text-muted d-block">NIK: {{ $rumah->hunian->warga->nik ?? '-' }}</small>
                                </td>
                                <td>
                                    <div>{{ $rumah->hunian->warga->alamat ?? '-' }}</div>
                                    <small class="text-primary fw-semibold">
                                        {{ $rumah->hunian->warga->wilayah->desa ?? 'Kelurahan' }}
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <!-- Atap -->
                                        @if($rumah->kondisi_atap == 3)
                                            <span class="badge bg-danger">Atap: Rusak</span>
                                        @elseif($rumah->kondisi_atap == 2)
                                            <span class="badge bg-warning text-dark">Atap: Sedang</span>
                                        @else
                                            <span class="badge bg-success">Atap: Baik</span>
                                        @endif

                                        <!-- Dinding -->
                                        @if($rumah->kondisi_dinding == 3)
                                            <span class="badge bg-danger">Dinding: Rusak</span>
                                        @elseif($rumah->kondisi_dinding == 2)
                                            <span class="badge bg-warning text-dark">Dinding: Sedang</span>
                                        @else
                                            <span class="badge bg-success">Dinding: Baik</span>
                                        @endif

                                        <!-- Lantai -->
                                        @if($rumah->kondisi_lantai == 3)
                                            <span class="badge bg-danger">Lantai: Rusak</span>
                                        @elseif($rumah->kondisi_lantai == 2)
                                            <span class="badge bg-warning text-dark">Lantai: Sedang</span>
                                        @else
                                            <span class="badge bg-success">Lantai: Baik</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light-primary text-primary">
                                        <i class="bi bi-camera me-1"></i> {{ $rumah->dokumenRumah->count() }} Foto
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        @if(Route::has('rumah.show'))
                                            <a href="{{ route('rumah.show', $rumah->id_rumah) }}" class="btn btn-light-primary" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endif
                                        @if(Route::has('rumah.edit'))
                                            <a href="{{ route('rumah.edit', $rumah->id_rumah) }}" class="btn btn-light-warning" title="Edit">
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
                                        <i class="bi bi-house-door fs-1 d-block mb-3 text-muted opacity-50"></i>
                                        <h6 class="fw-bold text-secondary">Belum ada data survei fisik rumah</h6>
                                        <p class="text-muted small mb-3">Hasil survei konstruksi atap, lantai, dan dinding rumah belum ditambahkan.</p>
                                        @if(Route::has('rumah.create'))
                                            <a href="{{ route('rumah.create') }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-plus-lg me-1"></i> Input Hasil Survei Rumah Baru
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($rumahs->hasPages())
                <div class="card-footer border-0 py-3 d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Menampilkan {{ $rumahs->firstItem() ?? 0 }} - {{ $rumahs->lastItem() ?? 0 }} dari {{ $rumahs->total() }} data
                    </small>
                    {{ $rumahs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
