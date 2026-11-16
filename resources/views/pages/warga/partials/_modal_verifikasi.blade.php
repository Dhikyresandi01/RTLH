@php
    $warga = $warga ?? $bantuan?->warga;
    $rank = $rank ?? $bantuan?->spkRanking;
@endphp

@if($bantuan && $warga)
<div class="modal fade" id="modalVerifikasiWarga{{ $bantuan->id_bantuan }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('spk.verifikasi', $bantuan->id_bantuan) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white fw-bold">
                        <i class="bi bi-shield-check me-2"></i>Verifikasi Dinas: {{ $warga->nama }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-light border mb-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <div class="small text-muted">Hasil SPK SAW Terkini:</div>
                                <div class="fw-bold text-dark">
                                    Peringkat: <span class="text-primary">#{{ $rank?->ranking ?? '-' }}</span> |
                                    Skor Preferensi (V<sub>i</sub>): <span class="text-primary">{{ number_format($rank?->skor_akhir ?? 0, 4) }}</span>
                                </div>
                            </div>
                            <div>
                                @include('pages.warga.partials._status_badge', ['bantuan' => $bantuan])
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Keputusan Verifikasi Dinas <span class="text-danger">*</span></label>
                        <select name="status_bantuan" class="form-select" required>
                            <option value="{{ \App\Models\Bantuan::STATUS_DIVERIFIKASI }}" {{ (int) $bantuan->status_bantuan === \App\Models\Bantuan::STATUS_DIVERIFIKASI ? 'selected' : '' }}>
                                Diverifikasi Lapangan (Menunggu Penetapan)
                            </option>
                            <option value="{{ \App\Models\Bantuan::STATUS_DISETUJUI }}" {{ (int) $bantuan->status_bantuan === \App\Models\Bantuan::STATUS_DISETUJUI ? 'selected' : '' }}>
                                Disetujui Dinas (Lolos Prioritas &amp; Penetapan Penerima)
                            </option>
                            <option value="{{ \App\Models\Bantuan::STATUS_REVISI }}" {{ (int) $bantuan->status_bantuan === \App\Models\Bantuan::STATUS_REVISI ? 'selected' : '' }}>
                                Perlu Revisi / Perbaikan Data Lapangan
                            </option>
                            <option value="{{ \App\Models\Bantuan::STATUS_DITOLAK }}" {{ (int) $bantuan->status_bantuan === \App\Models\Bantuan::STATUS_DITOLAK ? 'selected' : '' }}>
                                Ditolak (Tidak Memenuhi Syarat Lapangan)
                            </option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold text-dark">Catatan Resmi Verifikator <span class="text-danger">*</span></label>
                        <textarea name="catatan_verifikasi" class="form-control" rows="4" maxlength="1000" placeholder="Tuliskan catatan verifikasi fisik, kelayakan faktual, atau pertimbangan kuota..." required>{{ $bantuan->catatan_verifikasi }}</textarea>
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
