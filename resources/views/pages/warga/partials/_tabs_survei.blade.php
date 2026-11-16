<!-- ================= TAB: DETAIL KEPEMILIKAN HUNIAN ================= -->
<div class="tab-pane fade" id="content-hunian" role="tabpanel">
    <div class="table-responsive">
        <table class="table table-hover table-striped mb-0 align-middle table-terpadu">
            <thead>
                <tr>
                    <th class="ps-4" style="width: 50px;">No</th>
                    <th>Calon Penerima</th>
                    <th>Status Kepemilikan Rumah</th>
                    <th>Status Hak Atas Tanah</th>
                    <th>Luas Bangunan &amp; Lahan</th>
                    <th>Penghuni &amp; Kawasan</th>
                    <th>Titik Koordinat GPS</th>
                    <th class="text-end pe-4" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($wargas as $index => $warga)
                    @php $hunian = $warga->Hunian->first(); @endphp
                    <tr>
                        <td class="ps-4 fw-semibold text-muted">
                            {{ method_exists($wargas, 'firstItem') ? $wargas->firstItem() + $index : $index + 1 }}
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $warga->nama }}</div>
                            <small class="text-muted">NIK: {{ $warga->nik }}</small>
                        </td>
                        <td>
                            @if($hunian?->status_rumah == 1)
                                <span class="badge bg-success">Milik Sendiri</span>
                            @elseif($hunian?->status_rumah == 2)
                                <span class="badge bg-warning text-dark">Sewa / Kontrak</span>
                            @elseif($hunian?->status_rumah == 3)
                                <span class="badge bg-secondary">Menumpang</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($hunian?->status_tanah == 1)
                                <span class="badge bg-light-primary text-primary fw-semibold">SHM / Hak Milik</span>
                            @elseif($hunian?->status_tanah == 2)
                                <span class="badge bg-light-secondary text-dark fw-semibold">Girik / Letter C</span>
                            @elseif($hunian?->status_tanah == 3)
                                <span class="badge bg-light-danger text-danger fw-semibold">Tanah Negara</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="fw-semibold text-dark">Rumah: {{ $hunian?->luas_rumah ?? '-' }} m&sup2;</span><br>
                            <small class="text-muted">Tanah: {{ $hunian?->luas_tanah ?? '-' }} m&sup2;</small>
                        </td>
                        <td>
                            <div class="text-dark">{{ $hunian?->jml_kk ?? 1 }} KK / {{ $hunian?->jml_penghuni ?? 1 }} Jiwa</div>
                            <small class="text-muted">
                                @if($hunian?->kawasan == 1) Permukiman Kota
                                @elseif($hunian?->kawasan == 2) Bantaran Sungai
                                @elseif($hunian?->kawasan == 3) Kawasan Kumuh
                                @else - @endif
                            </small>
                        </td>
                        <td>
                            @if($hunian?->latitude && $hunian?->longitude)
                                <a href="https://www.google.com/maps?q={{ $hunian->latitude }},{{ $hunian->longitude }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.78rem;">
                                    <i class="bi bi-geo-alt-fill me-1"></i> Buka Maps
                                </a>
                            @else
                                <span class="text-muted small">Belum ada</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('warga.show', $warga->id_warga) }}" class="btn btn-sm btn-light-primary" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="py-3">
                                <div class="avatar bg-light-primary text-primary mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 54px; height: 54px;">
                                    <i class="bi bi-house-door fs-2"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Belum ada data kepemilikan hunian</h6>
                                <p class="text-muted small mb-0">Data kepemilikan hunian dan lahan belum tersedia untuk filter ini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ================= TAB: DETAIL FISIK & SANITASI ================= -->
<div class="tab-pane fade" id="content-fisik" role="tabpanel">
    <div class="table-responsive">
        <table class="table table-hover table-striped mb-0 align-middle table-terpadu">
            <thead>
                <tr>
                    <th class="ps-4" style="width: 50px;">No</th>
                    <th>Calon Penerima</th>
                    <th>Kondisi Atap</th>
                    <th>Kondisi Dinding</th>
                    <th>Kondisi Lantai</th>
                    <th>Sanitasi &amp; MCK</th>
                    <th>Dokumentasi Foto</th>
                    <th class="text-end pe-4" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($wargas as $index => $warga)
                    @php
                        $hunian = $warga->Hunian->first();
                        $rumah = $hunian?->Rumah->first();
                        $utilitas = $rumah?->Utilitas->first();
                        $fotoCount = $rumah?->DokumenRumah->count() ?? 0;
                    @endphp
                    <tr>
                        <td class="ps-4 fw-semibold text-muted">
                            {{ method_exists($wargas, 'firstItem') ? $wargas->firstItem() + $index : $index + 1 }}
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $warga->nama }}</div>
                            <small class="text-muted">{{ $warga->wilayah->desa ?? '-' }}</small>
                        </td>
                        <td>
                            @if($rumah?->kondisi_atap == 3)
                                <span class="badge bg-danger">Rusak Berat</span>
                            @elseif($rumah?->kondisi_atap == 2)
                                <span class="badge bg-warning text-dark">Rusak Sedang</span>
                            @elseif($rumah?->kondisi_atap == 1)
                                <span class="badge bg-success">Baik</span>
                            @else - @endif
                        </td>
                        <td>
                            @if($rumah?->kondisi_dinding == 3)
                                <span class="badge bg-danger">Rusak Berat</span>
                            @elseif($rumah?->kondisi_dinding == 2)
                                <span class="badge bg-warning text-dark">Rusak Sedang</span>
                            @elseif($rumah?->kondisi_dinding == 1)
                                <span class="badge bg-success">Baik</span>
                            @else - @endif
                        </td>
                        <td>
                            @if($rumah?->kondisi_lantai == 3)
                                <span class="badge bg-danger">Rusak / Tanah</span>
                            @elseif($rumah?->kondisi_lantai == 2)
                                <span class="badge bg-warning text-dark">Rusak Sedang</span>
                            @elseif($rumah?->kondisi_lantai == 1)
                                <span class="badge bg-success">Baik</span>
                            @else - @endif
                        </td>
                        <td>
                            @if($utilitas)
                                <div class="small">
                                    <i class="bi bi-droplet text-primary"></i>
                                    @if($utilitas->sumber_air == 1) PDAM
                                    @elseif($utilitas->sumber_air == 2) Sumur Bor
                                    @else Sumur/Lainnya @endif
                                    <br>
                                    <i class="bi bi-badge-wc text-secondary"></i>
                                    @if($utilitas->mck == 1) MCK Sendiri
                                    @elseif($utilitas->mck == 3) Komunal
                                    @else Tidak Ada @endif
                                </div>
                            @else
                                <span class="text-muted small">Belum ada</span>
                            @endif
                        </td>
                        <td>
                            @if($fotoCount > 0)
                                <button type="button" class="btn btn-sm btn-outline-info py-0 px-2" data-bs-toggle="modal" data-bs-target="#modalFoto{{ $warga->id_warga }}" style="font-size: 0.78rem;">
                                    <i class="bi bi-camera-fill me-1"></i> {{ $fotoCount }} Foto
                                </button>
                            @else
                                <span class="badge bg-light text-muted">0 Foto</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('warga.show', $warga->id_warga) }}" class="btn btn-sm btn-light-primary" title="Detail Lengkap">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="py-3">
                                <div class="avatar bg-light-primary text-primary mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 54px; height: 54px;">
                                    <i class="bi bi-bricks fs-2"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Belum ada data survei fisik rumah</h6>
                                <p class="text-muted small mb-0">Data survei fisik dan sanitasi belum tersedia untuk filter ini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@foreach($wargas as $warga)
    @php $rumah = $warga->Hunian->first()?->Rumah->first(); @endphp
    @include('pages.warga.partials._modal_foto_rumah', ['warga' => $warga, 'rumah' => $rumah])
@endforeach
