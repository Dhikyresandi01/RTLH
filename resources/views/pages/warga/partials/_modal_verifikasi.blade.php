@php
    $warga = $warga ?? $bantuan?->warga;
    $rank = $rank ?? $bantuan?->spkRanking;
    $allowedStatuses = $allowedStatuses ?? [
        \App\Models\Bantuan::STATUS_DIVERIFIKASI,
        \App\Models\Bantuan::STATUS_DISETUJUI,
        \App\Models\Bantuan::STATUS_REVISI,
        \App\Models\Bantuan::STATUS_DITOLAK,
    ];
    $modalTitle = $modalTitle ?? 'Verifikasi Dinas';
    $showSawBox = $showSawBox ?? true;
    $statusOptions = [
        \App\Models\Bantuan::STATUS_DIVERIFIKASI => 'Diverifikasi Lapangan (Masuk Antrean SPK SAW)',
        \App\Models\Bantuan::STATUS_DISETUJUI => 'Disetujui Dinas (Lolos Prioritas & Penetapan Penerima)',
        \App\Models\Bantuan::STATUS_REVISI => 'Perlu Revisi / Perbaikan Data Lapangan',
        \App\Models\Bantuan::STATUS_DITOLAK => 'Ditolak (Tidak Memenuhi Syarat Lapangan)',
    ];
    $defaultSelected = in_array((int) ($bantuan?->status_bantuan ?? -1), $allowedStatuses, true)
        ? (int) $bantuan->status_bantuan
        : $allowedStatuses[0];
@endphp

@if($bantuan && $warga)
<div class="modal fade" id="modalVerifikasiWarga{{ $bantuan->id_bantuan }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('spk.verifikasi', $bantuan->id_bantuan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyimpan keputusan verifikasi untuk {{ addslashes($warga->nama) }}?');">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white fw-bold">
                        <i class="bi bi-shield-check me-2"></i>{{ $modalTitle }}: {{ $warga->nama }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-light border mb-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                @if($showSawBox)
                                    <div class="small text-muted">Hasil SPK SAW Terkini:</div>
                                    <div class="fw-bold text-dark">
                                        Peringkat: <span class="text-primary">#{{ $rank?->ranking ?? '-' }}</span> |
                                        Skor Preferensi (V<sub>i</sub>): <span class="text-primary">{{ number_format($rank?->skor_akhir ?? 0, 4) }}</span>
                                    </div>
                                @else
                                    <div class="small text-muted">Status Usulan Saat Ini:</div>
                                    <div class="fw-bold text-dark">{{ $warga->wilayah->desa ?? '-' }} | NIK: {{ $warga->nik }}</div>
                                @endif
                            </div>
                            <div>
                                @include('pages.warga.partials._status_badge', ['bantuan' => $bantuan])
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Keputusan Verifikasi <span class="text-danger">*</span></label>
                        <select name="status_bantuan" class="form-select" required>
                            @foreach($allowedStatuses as $code)
                                @if(isset($statusOptions[$code]))
                                    <option value="{{ $code }}" {{ $defaultSelected === $code ? 'selected' : '' }}>
                                        {{ $statusOptions[$code] }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold text-dark">Catatan Resmi Verifikator <span class="text-danger">*</span></label>
                        <textarea name="catatan_verifikasi" class="form-control" rows="4" maxlength="1000" placeholder="Tuliskan catatan verifikasi fisik, kelayakan faktual, atau alasan revisi/penolakan..." required>{{ $bantuan->catatan_verifikasi }}</textarea>
                        <small class="text-muted">Maksimal 1000 karakter. Catatan ini akan terlihat oleh petugas kelurahan.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-save me-1"></i> Simpan Keputusan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
