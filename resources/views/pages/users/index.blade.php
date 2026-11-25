@extends('app')

@section('title', 'Kelola Pengguna - E-RTLH Kota Kediri')

@section('content')
<div class="page-heading">
    <!-- Header Halaman -->
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-12 col-md-7">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item text-muted">Master Data</li>
                        <li class="breadcrumb-item active" aria-current="page">Kelola Pengguna</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-primary">Kelola Pengguna Sistem</h3>
                <p class="text-muted small mb-0">Manajemen akun hak akses Super Admin, Admin Dinas, dan Admin Kelurahan se-Kota Kediri.</p>
            </div>
            <div class="col-12 col-md-5 text-md-end mt-3 mt-md-0">
                <button type="button" class="btn btn-primary d-inline-flex align-items-center shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                    <i class="bi bi-person-plus-fill me-2"></i> Tambah Pengguna Baru
                </button>
            </div>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
            <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-octagon-fill me-2"></i>Validasi Gagal:</h6>
            <ul class="mb-0 small ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistik Ringkasan Pengguna -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 mb-0 h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon blue me-3 d-flex align-items-center justify-content-center text-white rounded-3" style="width: 48px; height: 48px; background-color: #002b84;">
                            <i class="bi bi-people-fill fs-4 text-white"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold d-block">Total Pengguna</span>
                            <h4 class="fw-bold mb-0 text-dark">{{ $totalUsers }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 mb-0 h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon amber me-3 d-flex align-items-center justify-content-center rounded-3" style="width: 48px; height: 48px; background-color: #ffc000;">
                            <i class="bi bi-shield-lock-fill fs-4 text-dark"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold d-block">Super Admin</span>
                            <h4 class="fw-bold mb-0 text-dark">{{ $totalSuperadmin }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 mb-0 h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon green me-3 d-flex align-items-center justify-content-center text-white rounded-3" style="width: 48px; height: 48px; background-color: #054735;">
                            <i class="bi bi-building fs-4 text-white"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold d-block">Admin Dinas</span>
                            <h4 class="fw-bold mb-0 text-dark">{{ $totalDinas }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 mb-0 h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon info me-3 d-flex align-items-center justify-content-center text-white rounded-3" style="width: 48px; height: 48px; background-color: #1e3653;">
                            <i class="bi bi-geo-alt-fill fs-4 text-white"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-semibold d-block">Admin Kelurahan</span>
                            <h4 class="fw-bold mb-0 text-dark">
                                {{ $totalKelurahan }} 
                                <small class="text-muted fw-normal" style="font-size: 0.75rem;">({{ $totalKelurahanAktif }}/{{ $totalSemuaKelurahan }} Wilayah)</small>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Pencarian Pengguna -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('users.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama, email, jabatan, atau kelurahan..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <select name="role" class="form-select">
                        <option value="">-- Semua Role --</option>
                        <option value="superadmin" {{ request('role') === 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin_dinas" {{ request('role') === 'admin_dinas' ? 'selected' : '' }}>Admin Dinas</option>
                        <option value="admin_kelurahan" {{ request('role') === 'admin_kelurahan' ? 'selected' : '' }}>Admin Kelurahan</option>
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <select name="id_wilayah" class="form-select">
                        <option value="">-- Semua Wilayah --</option>
                        @foreach($wilayahs as $w)
                            <option value="{{ $w->id_wilayah }}" {{ request('id_wilayah') == $w->id_wilayah ? 'selected' : '' }}>
                                {{ $w->desa }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
                        <i class="bi bi-funnel-fill me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['search', 'role', 'id_wilayah']))
                        <a href="{{ route('users.index') }}" class="btn btn-light-secondary" title="Reset Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Pengguna -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h6 class="card-title fw-bold mb-0 text-dark">
                <i class="bi bi-people me-2 text-primary"></i> Daftar Akun Pengguna Terdaftar
            </h6>
            <small class="text-muted">Total: <strong>{{ $users->total() }}</strong> akun</small>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">No</th>
                            <th>Nama &amp; Email Pengguna</th>
                            <th>Peran / Role</th>
                            <th>Jabatan</th>
                            <th>Wilayah Penugasan</th>
                            <th>Terdaftar</th>
                            <th class="text-center" style="width: 170px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $u)
                            <tr>
                                <td class="text-center text-muted fw-bold">{{ $users->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar rounded-circle text-white d-flex align-items-center justify-content-center fw-bold me-2 shadow-sm"
                                             style="width: 38px; height: 38px; background-color: {{ $u->isSuperadmin() ? '#002b84' : ($u->isAdminDinas() ? '#054735' : '#1e3653') }}; font-size: 0.85rem;">
                                            {{ strtoupper(substr($u->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark d-flex align-items-center gap-1">
                                                <span>{{ $u->name }}</span>
                                                @if($u->id === Auth::id())
                                                    <span class="badge bg-light text-primary border" style="font-size: 0.65rem;">Akun Anda</span>
                                                @endif
                                            </div>
                                            <small class="text-muted">{{ $u->email }}</small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    @if($u->isSuperadmin())
                                        <span class="badge" style="background-color: #002b84; color: #ffffff;">
                                            <i class="bi bi-shield-lock-fill me-1 text-warning"></i> Super Admin
                                        </span>
                                    @elseif($u->isAdminDinas())
                                        <span class="badge text-white" style="background-color: #054735;">
                                            <i class="bi bi-building me-1"></i> Admin Dinas
                                        </span>
                                    @else
                                        <span class="badge" style="background-color: #1e3653; color: #ffffff;">
                                            <i class="bi bi-geo-alt-fill me-1 text-info"></i> Admin Kelurahan
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <span class="text-dark">{{ $u->jabatan ?: '-' }}</span>
                                </td>

                                <td>
                                    @if($u->isAdminKelurahan())
                                        @if($u->wilayah)
                                            <span class="badge bg-light-primary text-primary fw-semibold border border-primary-subtle px-2 py-1">
                                                <i class="bi bi-geo-alt me-1"></i>Kel. {{ $u->wilayah->desa }}
                                            </span>
                                        @else
                                            <span class="badge bg-light-danger text-danger border border-danger-subtle px-2 py-1">
                                                <i class="bi bi-exclamation-triangle me-1"></i>Belum Dipetakan
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge bg-light text-secondary border">
                                            <i class="bi bi-globe me-1"></i>Kota Kediri (Seluruh Wilayah)
                                        </span>
                                    @endif
                                </td>

                                <td class="text-muted small">
                                    {{ $u->created_at ? $u->created_at->translatedFormat('d M Y') : '-' }}
                                </td>

                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <!-- Tombol Edit -->
                                        <button type="button" class="btn btn-light-warning" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $u->id }}" title="Edit Data Pengguna">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <!-- Tombol Reset Password -->
                                        <button type="button" class="btn btn-light-info" data-bs-toggle="modal" data-bs-target="#modalResetPassword{{ $u->id }}" title="Reset Password">
                                            <i class="bi bi-key-fill"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        @if($u->id !== Auth::id())
                                            <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna {{ addslashes($u->name) }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-light-danger" title="Hapus Pengguna">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-light-secondary opacity-50" disabled title="Anda tidak dapat menghapus akun sendiri">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-person-x fs-1 d-block mb-2 text-muted opacity-50"></i>
                                    <h6 class="fw-bold mb-1">Tidak Ada Data Pengguna</h6>
                                    <p class="small mb-0">Tidak ditemukan pengguna yang sesuai dengan kriteria filter atau pencarian Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($users->hasPages())
            <div class="card-footer bg-white border-0 py-3 d-flex flex-wrap justify-content-between align-items-center">
                <small class="text-muted">
                    Menampilkan <strong>{{ $users->firstItem() }}</strong> - <strong>{{ $users->lastItem() }}</strong> dari <strong>{{ $users->total() }}</strong> pengguna
                </small>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL TAMBAH PENGGUNA BARU                  -->
<!-- ========================================== -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header text-white" style="background-color: #054735;">
                    <h5 class="modal-title fw-bold text-white d-flex align-items-center">
                        <i class="bi bi-person-plus-fill me-2 text-warning"></i> Tambah Pengguna Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso, S.STP" required value="{{ old('name') }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-dark">Alamat Email (Login) <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="Contoh: budi@kedirikota.go.id" required value="{{ old('email') }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-dark">Peran / Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select select-role-tambah" required onchange="toggleWilayahField(this, 'wrapperWilayahTambah')">
                                <option value="">-- Pilih Peran Pengguna --</option>
                                <option value="admin_kelurahan" {{ old('role') === 'admin_kelurahan' ? 'selected' : '' }}>Admin Kelurahan (Petugas Pengusul & Realisasi)</option>
                                <option value="admin_dinas" {{ old('role') === 'admin_dinas' ? 'selected' : '' }}>Admin Dinas (Verifikator Lapangan & SPK)</option>
                                <option value="superadmin" {{ old('role') === 'superadmin' ? 'selected' : '' }}>Super Admin (Pengendali Master Sistem)</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-dark">Jabatan / Unit Kerja</label>
                            <input type="text" name="jabatan" class="form-control" placeholder="Contoh: Kasi Kesos / Staf Perumahan" value="{{ old('jabatan') }}">
                        </div>

                        <!-- Pilihan Wilayah (Hanya tampil jika Admin Kelurahan) -->
                        <div class="col-12" id="wrapperWilayahTambah" style="{{ old('role') === 'admin_kelurahan' ? '' : 'display: none;' }}">
                            <div class="p-3 bg-light rounded-3 border border-primary-subtle">
                                <label class="form-label fw-bold text-primary d-flex align-items-center">
                                    <i class="bi bi-geo-alt-fill me-1"></i> Wilayah Kelurahan Penugasan <span class="text-danger">*</span>
                                </label>
                                <select name="id_wilayah" class="form-select" id="selectWilayahTambah">
                                    <option value="">-- Pilih Kelurahan Binaan --</option>
                                    @foreach($wilayahs as $w)
                                        <option value="{{ $w->id_wilayah }}" {{ old('id_wilayah') == $w->id_wilayah ? 'selected' : '' }}>
                                            Kelurahan {{ $w->desa }} (Kec. {{ $w->kecamatan ?? 'Kota Kediri' }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-1">Petugas hanya dapat menginput usulan warga pada wilayah kelurahan yang dipilih di atas.</small>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-dark">Kata Sandi <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-dark">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi kata sandi" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3 fw-semibold">
                        <i class="bi bi-save me-1"></i> Simpan Pengguna
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL EDIT & RESET PASSWORD (PER USER)     -->
<!-- ========================================== -->
@foreach($users as $u)
    <!-- Modal Edit User -->
    <div class="modal fade" id="modalEditUser{{ $u->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('users.update', $u->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content border-0 shadow">
                    <div class="modal-header text-white" style="background-color: #002b84;">
                        <h5 class="modal-title fw-bold text-white d-flex align-items-center">
                            <i class="bi bi-pencil-square me-2 text-warning"></i> Edit Data Pengguna: {{ $u->name }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required value="{{ old('name', $u->name) }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold text-dark">Alamat Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" required value="{{ old('email', $u->email) }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold text-dark">Peran / Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-select" required onchange="toggleWilayahField(this, 'wrapperWilayahEdit{{ $u->id }}')">
                                    <option value="admin_kelurahan" {{ old('role', $u->role) === 'admin_kelurahan' ? 'selected' : '' }}>Admin Kelurahan (Petugas Pengusul)</option>
                                    <option value="admin_dinas" {{ old('role', $u->role) === 'admin_dinas' ? 'selected' : '' }}>Admin Dinas (Verifikator Lapangan)</option>
                                    <option value="superadmin" {{ old('role', $u->role) === 'superadmin' ? 'selected' : '' }}>Super Admin (Pengendali Sistem)</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold text-dark">Jabatan / Unit Kerja</label>
                                <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $u->jabatan) }}">
                            </div>

                            <!-- Pilihan Wilayah Edit -->
                            <div class="col-12" id="wrapperWilayahEdit{{ $u->id }}" style="{{ $u->role === 'admin_kelurahan' ? '' : 'display: none;' }}">
                                <div class="p-3 bg-light rounded-3 border border-primary-subtle">
                                    <label class="form-label fw-bold text-primary d-flex align-items-center">
                                        <i class="bi bi-geo-alt-fill me-1"></i> Wilayah Kelurahan Penugasan <span class="text-danger">*</span>
                                    </label>
                                    <select name="id_wilayah" class="form-select">
                                        <option value="">-- Pilih Kelurahan Binaan --</option>
                                        @foreach($wilayahs as $w)
                                            <option value="{{ $w->id_wilayah }}" {{ old('id_wilayah', $u->id_wilayah) == $w->id_wilayah ? 'selected' : '' }}>
                                                Kelurahan {{ $w->desa }} (Kec. {{ $w->kecamatan ?? 'Kota Kediri' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted d-block mt-1">Petugas hanya dapat menginput dan mengelola usulan warga pada kelurahan ini.</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="alert alert-light border small mb-0">
                                    <i class="bi bi-info-circle text-primary me-1"></i>
                                    Kosongkan kolom kata sandi di bawah ini jika tidak ingin mengubah kata sandi akun pengguna ini.
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-dark">Kata Sandi Baru (Opsional)</label>
                                <input type="password" name="password" class="form-control" placeholder="Isi hanya jika ingin ganti password">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-dark">Ulangi Kata Sandi Baru</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi kata sandi baru">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm px-3 fw-semibold">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Reset Password -->
    <div class="modal fade" id="modalResetPassword{{ $u->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('users.reset-password', $u->id) }}" method="POST">
                @csrf
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title fw-bold d-flex align-items-center">
                            <i class="bi bi-key-fill me-2 text-dark"></i> Reset Kata Sandi
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-light border mb-3">
                            <div class="fw-bold text-dark">{{ $u->name }}</div>
                            <small class="text-muted d-block">{{ $u->email }} | {{ ucfirst($u->role) }}</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Kata Sandi Baru <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required autofocus>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold text-dark">Konfirmasi Kata Sandi Baru <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi kata sandi baru" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning btn-sm px-3 fw-semibold text-dark">
                            <i class="bi bi-check2-circle me-1"></i> Reset Password Sekarang
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

<script>
function toggleWilayahField(selectElem, wrapperId) {
    const wrapper = document.getElementById(wrapperId);
    if (!wrapper) return;
    if (selectElem.value === 'admin_kelurahan') {
        wrapper.style.display = 'block';
    } else {
        wrapper.style.display = 'none';
    }
}
</script>
@endsection
