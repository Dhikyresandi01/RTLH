<!-- ================= TAB 2: DETAIL KEPEMILIKAN HUNIAN & LAHAN ================= -->
<div class="tab-pane fade" id="content-hunian" role="tabpanel">
    <div class="table-responsive">
        <table class="stitch-table">
            <thead>
                <tr>
                    <th class="ps-3" style="width: 48px;">No</th>
                    <th>Calon Penerima</th>
                    <th>Status Kepemilikan Rumah</th>
                    <th>Status Hak Atas Tanah</th>
                    <th>Luas Bangunan &amp; Lahan</th>
                    <th>Penghuni &amp; Kawasan</th>
                    <th>Titik Koordinat GPS</th>
                    <th class="text-end pe-3" style="width: 80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($wargas as $index => $warga)
                    @php $hunian = $warga->Hunian->first(); @endphp
                    <tr>
                        <td class="ps-3 tabular-nums font-mono-num text-muted">
                            {{ method_exists($wargas, 'firstItem') ? $wargas->firstItem() + $index : $index + 1 }}
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <span class="fw-bold text-dark font-stitch" style="font-size: 13.5px;">{{ $warga->nama }}</span>
                                <span class="font-mono-num text-muted">NIK: {{ $warga->nik }}</span>
                                @if($warga->no_kk)
                                    <span class="font-mono-num text-muted">KK: {{ $warga->no_kk }}</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($hunian?->status_rumah == 1)
                                <span class="badge rounded-pill d-inline-flex align-items-center gap-1" style="background: var(--stitch-success-bg); color: var(--stitch-success-text); font-weight: 600; font-size: 11.5px; padding: 4px 10px;">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">home</span>
                                    <span>Milik Sendiri</span>
                                </span>
                            @elseif($hunian?->status_rumah == 2)
                                <span class="badge rounded-pill d-inline-flex align-items-center gap-1" style="background: var(--stitch-warning-bg); color: var(--stitch-warning-text); font-weight: 600; font-size: 11.5px; padding: 4px 10px;">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">key</span>
                                    <span>Sewa / Kontrak</span>
                                </span>
                            @elseif($hunian?->status_rumah == 3)
                                <span class="badge rounded-pill d-inline-flex align-items-center gap-1" style="background: #F1F5F9; color: #475569; border: 1px solid #CBD5E1; font-weight: 600; font-size: 11.5px; padding: 4px 10px;">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">diversity_3</span>
                                    <span>Menumpang</span>
                                </span>
                            @else
                                <span class="badge bg-light text-muted border font-stitch" style="font-size: 11px;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($hunian?->status_tanah == 1)
                                <span class="badge rounded-pill d-inline-flex align-items-center gap-1" style="background: var(--stitch-info-bg); color: var(--stitch-info-text); font-weight: 600; font-size: 11.5px; padding: 4px 10px;">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">verified</span>
                                    <span>SHM / Hak Milik</span>
                                </span>
                            @elseif($hunian?->status_tanah == 2)
                                <span class="badge rounded-pill d-inline-flex align-items-center gap-1" style="background: #F1F5F9; color: #334155; border: 1px solid #CBD5E1; font-weight: 600; font-size: 11.5px; padding: 4px 10px;">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">description</span>
                                    <span>Girik / Letter C</span>
                                </span>
                            @elseif($hunian?->status_tanah == 3)
                                <span class="badge rounded-pill d-inline-flex align-items-center gap-1" style="background: var(--stitch-error-bg); color: var(--stitch-error-text); font-weight: 600; font-size: 11.5px; padding: 4px 10px;">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">warning</span>
                                    <span>Tanah Negara</span>
                                </span>
                            @else
                                <span class="badge bg-light text-muted border font-stitch" style="font-size: 11px;">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1 font-mono-num">
                                <span class="fw-semibold text-dark" style="font-size: 12px;">
                                    Rumah: {{ $hunian?->luas_rumah ?? '-' }} m&sup2;
                                </span>
                                <span class="text-muted" style="font-size: 11.5px;">
                                    Lahan: {{ $hunian?->luas_tanah ?? '-' }} m&sup2;
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <span class="fw-semibold text-dark font-stitch" style="font-size: 12.5px;">
                                    <span class="font-mono-num">{{ $hunian?->jml_kk ?? 1 }}</span> KK &middot; <span class="font-mono-num">{{ $hunian?->jml_penghuni ?? 1 }}</span> Jiwa
                                </span>
                                <span class="text-muted font-stitch" style="font-size: 11.5px;">
                                    <span class="material-symbols-outlined me-1" style="font-size: 13px; vertical-align: middle;">location_city</span>
                                    @if($hunian?->kawasan == 1) Permukiman Kota
                                    @elseif($hunian?->kawasan == 2) Bantaran Sungai
                                    @elseif($hunian?->kawasan == 3) Kawasan Kumuh
                                    @else - @endif
                                </span>
                            </div>
                        </td>
                        <td>
                            @if($hunian?->latitude && $hunian?->longitude)
                                <a href="https://www.google.com/maps?q={{ $hunian->latitude }},{{ $hunian->longitude }}" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 font-mono-num py-1 px-2" style="font-size: 11.5px; border-radius: 6px;" title="Buka di Google Maps">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">pin_drop</span>
                                    <span>{{ number_format($hunian->latitude, 4) }}, {{ number_format($hunian->longitude, 4) }}</span>
                                </a>
                            @else
                                <span class="badge bg-light text-muted border font-stitch" style="font-size: 11px;">Belum dipetakan</span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <a href="{{ route('warga.show', $warga->id_warga) }}" class="stitch-btn-icon" title="Lihat Detail {{ $warga->nama }}">
                                <span class="material-symbols-outlined" style="font-size: 17px;">visibility</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="py-4">
                                <span class="material-symbols-outlined text-muted mb-2" style="font-size: 44px;">home_work</span>
                                <h6 class="fw-bold text-dark mb-1 font-headline">Belum ada data kepemilikan hunian</h6>
                                <p class="text-muted small mb-0 font-stitch">Data kepemilikan hunian dan lahan belum tersedia untuk filter ini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ================= TAB 3: SURVEI KONSTRUKSI & SANITASI ================= -->
<div class="tab-pane fade" id="content-fisik" role="tabpanel">
    <div class="table-responsive">
        <table class="stitch-table">
            <thead>
                <tr>
                    <th class="ps-3" style="width: 48px;">No</th>
                    <th>Calon Penerima</th>
                    <th>Kondisi Atap</th>
                    <th>Kondisi Dinding</th>
                    <th>Kondisi Lantai</th>
                    <th>Sanitasi &amp; MCK</th>
                    <th>Dokumentasi Foto</th>
                    <th class="text-end pe-3" style="width: 80px;">Aksi</th>
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
                        <td class="ps-3 tabular-nums font-mono-num text-muted">
                            {{ method_exists($wargas, 'firstItem') ? $wargas->firstItem() + $index : $index + 1 }}
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <span class="fw-bold text-dark font-stitch" style="font-size: 13.5px;">{{ $warga->nama }}</span>
                                <span class="text-muted font-stitch" style="font-size: 12px;">Kel. {{ $warga->wilayah->desa ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            @if($rumah?->kondisi_atap == 3)
                                <span class="micro-pill micro-pill-danger"><span class="dot"></span>Rusak Berat</span>
                            @elseif($rumah?->kondisi_atap == 2)
                                <span class="micro-pill micro-pill-warning"><span class="dot"></span>Rusak Sedang</span>
                            @elseif($rumah?->kondisi_atap == 1)
                                <span class="micro-pill micro-pill-success"><span class="dot"></span>Kondisi Baik</span>
                            @else
                                <span class="badge bg-light text-muted border font-stitch" style="font-size: 11px;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($rumah?->kondisi_dinding == 3)
                                <span class="micro-pill micro-pill-danger"><span class="dot"></span>Rusak Berat</span>
                            @elseif($rumah?->kondisi_dinding == 2)
                                <span class="micro-pill micro-pill-warning"><span class="dot"></span>Rusak Sedang</span>
                            @elseif($rumah?->kondisi_dinding == 1)
                                <span class="micro-pill micro-pill-success"><span class="dot"></span>Kondisi Baik</span>
                            @else
                                <span class="badge bg-light text-muted border font-stitch" style="font-size: 11px;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($rumah?->kondisi_lantai == 3)
                                <span class="micro-pill micro-pill-danger"><span class="dot"></span>Rusak / Tanah</span>
                            @elseif($rumah?->kondisi_lantai == 2)
                                <span class="micro-pill micro-pill-warning"><span class="dot"></span>Rusak Sedang</span>
                            @elseif($rumah?->kondisi_lantai == 1)
                                <span class="micro-pill micro-pill-success"><span class="dot"></span>Kondisi Baik</span>
                            @else
                                <span class="badge bg-light text-muted border font-stitch" style="font-size: 11px;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($utilitas)
                                <div class="d-flex flex-column gap-1 font-stitch" style="font-size: 11.5px;">
                                    <span class="text-dark d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-droplet-fill text-primary" style="font-size: 12px;"></i>
                                        Air: @if($utilitas->sumber_air == 1) PDAM @elseif($utilitas->sumber_air == 2) Sumur Bor @else Sumur/Lainnya @endif
                                    </span>
                                    <span class="text-muted d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-badge-wc-fill text-secondary" style="font-size: 12px;"></i>
                                        MCK: @if($utilitas->mck == 1) Sendiri @elseif($utilitas->mck == 3) Komunal @else Tidak Ada @endif
                                    </span>
                                    @if($utilitas->daya_listrik)
                                        <span class="text-muted d-inline-flex align-items-center gap-1 font-mono-num">
                                            <i class="bi bi-lightning-charge-fill text-warning" style="font-size: 12px;"></i>
                                            {{ $utilitas->daya_listrik }} VA
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="badge bg-light text-muted border font-stitch" style="font-size: 11px;">Belum ada utilitas</span>
                            @endif
                        </td>
                        <td>
                            @if($fotoCount > 0)
                                <button type="button" class="btn btn-sm btn-outline-info d-inline-flex align-items-center gap-1 py-1 px-2 font-mono-num" data-bs-toggle="modal" data-bs-target="#modalFoto{{ $warga->id_warga }}" style="font-size: 11.5px; border-radius: 6px;">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">photo_library</span>
                                    <span>{{ $fotoCount }} Foto</span>
                                </button>
                            @else
                                <span class="badge bg-light text-muted border font-mono-num" style="font-size: 11px;">0 Foto</span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <a href="{{ route('warga.show', $warga->id_warga) }}" class="stitch-btn-icon" title="Lihat Detail Lengkap {{ $warga->nama }}">
                                <span class="material-symbols-outlined" style="font-size: 17px;">visibility</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="py-4">
                                <span class="material-symbols-outlined text-muted mb-2" style="font-size: 44px;">construction</span>
                                <h6 class="fw-bold text-dark mb-1 font-headline">Belum ada data survei fisik rumah</h6>
                                <p class="text-muted small mb-0 font-stitch">Data survei fisik dan sanitasi belum tersedia untuk filter ini.</p>
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
