@extends('app')

@section('title', 'Kelola Pengguna - E-RTLH Kota Kediri')

@section('content')
<div class="page-heading">
    <!-- Header Halaman Versi Stitch -->
    <div class="d-flex flex-column flex-md-row md:items-start justify-content-between gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1 font-headline" style="color: var(--stitch-primary);">
                <span class="material-symbols-outlined me-2 text-primary" style="font-size: 28px; vertical-align: middle;">manage_accounts</span>Kelola Pengguna Sistem
            </h3>
            <p class="text-muted mb-0 font-stitch" style="font-size: 13.5px;">Manajemen akun hak akses Super Admin, Admin Dinas, dan Admin Kelurahan se-Kota Kediri.</p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2 shrink-0">
            <button type="button" class="btn btn-sm btn-primary shadow-sm px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1 font-stitch" data-bs-toggle="modal" data-bs-target="#modalTambahUser" style="font-size: 12.5px; border-radius: 8px;">
                <span class="material-symbols-outlined" style="font-size: 17px;">person_add</span>
                <span>Tambah Pengguna Baru</span>
            </button>
        </div>
    </div>

    <!-- Alert Notifikasi -->
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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4 font-stitch" role="alert" style="font-size: 13.5px;">
            <h6 class="fw-bold mb-1 font-headline"><i class="bi bi-exclamation-octagon-fill me-2"></i>Validasi Gagal:</h6>
            <ul class="mb-0 small ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- 4 KPI Summary Cards Pengguna (Telah Dirapikan) -->
    <div class="row g-3 mb-4">
        <!-- KPI 1: Total Pengguna -->
        <div class="col-6 col-lg-3">
            <div class="stitch-kpi-card">
                <div class="stitch-kpi-icon" style="background: var(--stitch-info-bg); color: var(--stitch-info-text);">
                    <span class="material-symbols-outlined">group</span>
                </div>
                <div class="min-w-0">
                    <div class="stitch-kpi-label">Total Pengguna</div>
                    <div class="stitch-kpi-val font-mono-num" style="font-size: 22px;">{{ $totalUsers }}</div>
                    <small class="text-muted font-stitch" style="font-size: 11px;">Akun terdaftar</small>
                </div>
            </div>
        </div>

        <!-- KPI 2: Super Admin -->
        <div class="col-6 col-lg-3">
            <div class="stitch-kpi-card">
                <div class="stitch-kpi-icon" style="background: var(--stitch-warning-bg); color: var(--stitch-warning-text);">
                    <span class="material-symbols-outlined">shield_person</span>
                </div>
                <div class="min-w-0">
                    <div class="stitch-kpi-label">Super Admin</div>
                    <div class="stitch-kpi-val font-mono-num" style="font-size: 22px; color: var(--stitch-warning-text);">{{ $totalSuperadmin }}</div>
                    <small class="text-muted font-stitch" style="font-size: 11px;">Hak akses penuh</small>
                </div>
            </div>
        </div>

        <!-- KPI 3: Admin Dinas -->
        <div class="col-6 col-lg-3">
            <div class="stitch-kpi-card">
                <div class="stitch-kpi-icon" style="background: var(--stitch-success-bg); color: var(--stitch-success-text);">
                    <span class="material-symbols-outlined">apartment</span>
                </div>
                <div class="min-w-0">
                    <div class="stitch-kpi-label">Admin Dinas</div>
                    <div class="stitch-kpi-val font-mono-num" style="font-size: 22px; color: var(--stitch-success-text);">{{ $totalDinas }}</div>
                    <small class="text-muted font-stitch" style="font-size: 11px;">Verifikator teknis</small>
                </div>
            </div>
        </div>

        <!-- KPI 4: Admin Kelurahan -->
        <div class="col-6 col-lg-3">
            <div class="stitch-kpi-card">
                <div class="stitch-kpi-icon" style="background: #F1F5F9; color: var(--stitch-primary);">
                    <span class="material-symbols-outlined">location_city</span>
                </div>
                <div class="min-w-0">
                    <div class="stitch-kpi-label">Admin Kelurahan</div>
                    <div class="stitch-kpi-val font-mono-num" style="font-size: 20px;">
                        {{ $totalKelurahan }} 
                    </div>
                    <small class="text-muted font-stitch" style="font-size: 11px;">({{ $totalKelurahanAktif }}/{{ $totalSemuaKelurahan }} Wilayah)</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Pencarian Pengguna Toolbar Stitch -->
    <div class="stitch-card mb-4">
        <div class="stitch-toolbar">
            <form action="{{ route('users.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <span class="material-symbols-outlined" style="font-size: 16px;">search</span>
                        </span>
                        <input type="text" name="search" class="form-control font-stitch" style="font-size: 12.5px;" placeholder="Cari nama, email, jabatan, kelurahan..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <select name="role" class="form-select form-select-sm font-stitch" style="font-size: 12.5px;" onchange="this.form.submit()">
                        <option value="">Semua Peran / Role</option>
                        <option value="superadmin" {{ request('role') === 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin_dinas" {{ request('role') === 'admin_dinas' ? 'selected' : '' }}>Admin Dinas</option>
                        <option value="admin_kelurahan" {{ request('role') === 'admin_kelurahan' ? 'selected' : '' }}>Admin Kelurahan</option>
                    </select>
                </div>

                <div class="col-6 col-md-3">
                    <select name="id_wilayah" class="form-select form-select-sm font-stitch" style="font-size: 12.5px;" onchange="this.form.submit()">
                        <option value="">Semua Wilayah</option>
                        @foreach($wilayahs as $w)
                            <option value="{{ $w->id_wilayah }}" {{ request('id_wilayah') == $w->id_wilayah ? 'selected' : '' }}>
                                Kel. {{ $w->desa }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-1 d-flex gap-1 justify-content-end">
                    <button type="submit" class="btn btn-sm btn-primary px-3 fw-semibold d-inline-flex align-items-center gap-1 font-stitch" style="font-size: 12.5px;" title="Terapkan Filter">
                        <span class="material-symbols-outlined" style="font-size: 15px;">filter_list</span>
                    </button>
                    @if(request()->hasAny(['search', 'role', 'id_wilayah']))
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center" title="Reset Filter">
                            <span class="material-symbols-outlined" style="font-size: 15px;">restart_alt</span>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Pengguna Versi Stitch -->
    <div class="stitch-card">
        <!-- Header Tabel & Live Badge Counter -->
        <div class="p-3 bg-white border-bottom d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
            <div>
                <h5 class="fw-bold text-dark mb-0 font-headline">Daftar Akun Pengguna Terdaftar</h5>
                <small class="text-muted font-stitch">Pengelolaan akun login petugas operasional dan verifikator sistem E-RTLH Kota Kediri.</small>
            </div>
            <div class="stitch-header-badge font-stitch">
                <span class="dot"></span>
                <span>Total: <strong class="font-mono-num">{{ $users->total() }}</strong> Akun</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="stitch-table">
                <thead>
                    <tr>
                        <th class="ps-3 text-center" style="width: 50px;">No</th>
                        <th>Nama &amp; Email Pengguna</th>
                        <th>Peran / Role</th>
                        <th>Jabatan</th>
                        <th>Wilayah Penugasan</th>
                        <th>Terdaftar</th>
                        <th class="text-end pe-3" style="width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $u)
                        <tr>
                            <td class="ps-3 text-center tabular-nums font-mono-num text-muted">
                                {{ $users->firstItem() + $index }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold me-2 font-headline shrink-0"
                                         style="width: 36px; height: 36px; background-color: {{ $u->isSuperadmin() ? 'var(--stitch-primary)' : ($u->isAdminDinas() ? '#054735' : '#1E40AF') }}; font-size: 12.5px;">
                                        {{ strtoupper(substr($u->name, 0, 2)) }}
                                    </div>
                                    <div class="d-flex flex-column gap-1">
                                        <div class="fw-bold text-dark d-flex align-items-center gap-1 font-stitch" style="font-size: 13.5px;">
                                            <span>{{ $u->name }}</span>
                                            @if($u->id === Auth::id())
                                                <span class="badge bg-light text-primary border" style="font-size: 10px;">Anda</span>
                                            @endif
                                        </div>
                                        <span class="text-muted font-mono-num" style="font-size: 11px;">{{ $u->email }}</span>
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if($u->isSuperadmin())
                                    <span class="badge rounded-pill font-stitch" style="background: var(--stitch-info-bg); color: var(--stitch-info-text); font-weight: 600; font-size: 11.5px;">
                                        <span class="material-symbols-outlined me-1" style="font-size: 13px; vertical-align: middle;">shield_person</span>Super Admin
                                    </span>
                                @elseif($u->isAdminDinas())
                                    <span class="badge rounded-pill font-stitch" style="background: var(--stitch-success-bg); color: var(--stitch-success-text); font-weight: 600; font-size: 11.5px;">
                                        <span class="material-symbols-outlined me-1" style="font-size: 13px; vertical-align: middle;">apartment</span>Admin Dinas
                                    </span>
                                @else
                                    <span class="badge rounded-pill font-stitch" style="background: var(--stitch-warning-bg); color: var(--stitch-warning-text); font-weight: 600; font-size: 11.5px;">
                                        <span class="material-symbols-outlined me-1" style="font-size: 13px; vertical-align: middle;">location_city</span>Admin Kelurahan
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="text-dark font-stitch" style="font-size: 12.5px;">{{ $u->jabatan ?: '-' }}</span>
                            </td>

                            <td>
                                @if($u->isAdminKelurahan())
                                    @if($u->wilayah)
                                        <span class="badge rounded-pill font-stitch" style="background: #F1F5F9; color: #334155; border: 1px solid #CBD5E1; font-weight: 600; font-size: 11px;">
                                            <span class="material-symbols-outlined me-1" style="font-size: 12px; vertical-align: middle;">location_on</span>Kel. {{ $u->wilayah->desa }}
                                        </span>
                                    @else
                                        <span class="badge rounded-pill font-stitch" style="background: var(--stitch-error-bg); color: var(--stitch-error-text); font-weight: 600; font-size: 11px;">
                                            Belum Dipetakan
                                        </span>
                                    @endif
                                @else
                                    <span class="text-muted font-stitch" style="font-size: 12px;">
                                        Kota Kediri (Seluruh Wilayah)
                                    </span>
                                @endif
                            </td>

                            <td class="text-muted font-mono-num" style="font-size: 11px;">
                                {{ $u->created_at ? $u->created_at->translatedFormat('d M Y') : '-' }}
                            </td>

                            <td class="text-end pe-3">
                                <div class="d-inline-flex align-items-center justify-content-end gap-1">
                                    <!-- Tombol Edit -->
                                    <button type="button" class="stitch-btn-edit" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $u->id }}" title="Edit Data Pengguna">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>
                                    </button>

                                    <!-- Tombol Reset Password -->
                                    <button type="button" class="stitch-btn-icon" data-bs-toggle="modal" data-bs-target="#modalResetPassword{{ $u->id }}" title="Reset Password">
                                        <span class="material-symbols-outlined" style="font-size: 16px; color: #B45309;">key</span>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    @if($u->id !== Auth::id())
                                        <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna {{ addslashes($u->name) }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="stitch-btn-delete" title="Hapus Pengguna">
                                                <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="stitch-btn-icon opacity-50" disabled title="Anda tidak dapat menghapus akun sendiri">
                                            <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <span class="material-symbols-outlined text-muted mb-2" style="font-size: 44px;">person_search</span>
                                    <h6 class="fw-bold mb-1 font-headline">Tidak Ada Data Pengguna</h6>
                                    <p class="small mb-0 font-stitch">Tidak ditemukan pengguna yang sesuai dengan kriteria filter atau pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer Versi Stitch -->
        @if($users->hasPages())
            <div class="p-3 bg-light border-top d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                <span class="font-stitch text-muted font-mono-num" style="font-size: 12.5px;">
                    Menampilkan <strong>{{ $users->firstItem() }}</strong>–<strong>{{ $users->lastItem() }}</strong> dari <strong>{{ $users->total() }}</strong> pengguna
                </span>
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
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold font-headline" style="color: var(--stitch-primary);">
                        <span class="material-symbols-outlined me-2 text-primary" style="font-size: 22px; vertical-align: middle;">person_add</span>Tambah Pengguna Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 font-stitch">
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
                                <option value="admin_kelurahan" {{ old('role') === 'admin_kelurahan' ? 'selected' : '' }}>Admin Kelurahan (Petugas Pengusul &amp; Realisasi)</option>
                                <option value="admin_dinas" {{ old('role') === 'admin_dinas' ? 'selected' : '' }}>Admin Dinas (Verifikator Lapangan &amp; SPK)</option>
                                <option value="superadmin" {{ old('role') === 'superadmin' ? 'selected' : '' }}>Super Admin (Pengendali Master Sistem)</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-dark">Jabatan / Unit Kerja</label>
                            <input type="text" name="jabatan" class="form-control" placeholder="Contoh: Kasi Kesos / Staf Perumahan" value="{{ old('jabatan') }}">
                        </div>

                        <!-- Pilihan Wilayah (Hanya tampil jika Admin Kelurahan) -->
                        <div class="col-12" id="wrapperWilayahTambah" style="{{ old('role') === 'admin_kelurahan' ? '' : 'display: none;' }}">
                            <div class="p-3 bg-light rounded-3 border">
                                <label class="form-label fw-bold text-primary d-flex align-items-center">
                                    <span class="material-symbols-outlined me-1" style="font-size: 16px;">location_on</span> Wilayah Kelurahan Penugasan <span class="text-danger">*</span>
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
                <div class="modal-footer border-top font-stitch">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3 fw-semibold">
                        <span class="material-symbols-outlined me-1" style="font-size: 16px; vertical-align: middle;">save</span> Simpan Pengguna
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
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form action="{{ route('users.update', $u->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title fw-bold font-headline" style="color: var(--stitch-primary);">
                            <span class="material-symbols-outlined me-2 text-warning" style="font-size: 22px; vertical-align: middle;">edit</span>Edit Data Pengguna: {{ $u->name }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 font-stitch">
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
                                <div class="p-3 bg-light rounded-3 border">
                                    <label class="form-label fw-bold text-primary d-flex align-items-center">
                                        <span class="material-symbols-outlined me-1" style="font-size: 16px;">location_on</span> Wilayah Kelurahan Penugasan <span class="text-danger">*</span>
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
                    <div class="modal-footer border-top font-stitch">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm px-3 fw-semibold">
                            <span class="material-symbols-outlined me-1" style="font-size: 16px; vertical-align: middle;">save</span> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Reset Password -->
    <div class="modal fade" id="modalResetPassword{{ $u->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('users.reset-password', $u->id) }}" method="POST">
                @csrf
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title fw-bold font-headline text-dark">
                            <span class="material-symbols-outlined me-2 text-warning" style="font-size: 20px; vertical-align: middle;">key</span>Reset Kata Sandi
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 font-stitch">
                        <div class="bg-light border rounded p-3 mb-3">
                            <div class="fw-bold text-dark">{{ $u->name }}</div>
                            <small class="text-muted d-block font-mono-num">{{ $u->email }} | {{ ucfirst($u->role) }}</small>
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
                    <div class="modal-footer border-top font-stitch">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning btn-sm px-3 fw-semibold text-dark">
                            <span class="material-symbols-outlined me-1" style="font-size: 16px; vertical-align: middle;">check_circle</span> Reset Password Sekarang
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
