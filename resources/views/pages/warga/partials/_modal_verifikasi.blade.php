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
                        <select name="status_bantuan" class="form-select select-keputusan-verifikasi" id="selectStatus{{ $bantuan->id_bantuan }}" required onchange="handleVerifikasiChange(this, '{{ $bantuan->id_bantuan }}')">
                            @foreach($allowedStatuses as $code)
                                @if(isset($statusOptions[$code]))
                                    <option value="{{ $code }}" {{ $defaultSelected === $code ? 'selected' : '' }}>
                                        {{ $statusOptions[$code] }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Pilihan Program Bantuan (Hanya tampil jika pilihan status mencakup Disetujui/Validasi) -->
                    @if(in_array(\App\Models\Bantuan::STATUS_DISETUJUI, $allowedStatuses, true))
                    <div class="mb-3" id="wrapperProgramBantuan{{ $bantuan->id_bantuan }}">
                        <label class="form-label fw-bold text-dark d-flex justify-content-between align-items-center">
                            <span>Alokasi Program Bantuan</span>
                            <span class="badge bg-light text-primary border" style="font-size: 0.7rem;">Wewenang Dinas</span>
                        </label>
                        @php
                            $programsList = $programs ?? \App\Models\ProgramBantuan::orderBy('nama_program')->get();
                        @endphp
                        <select name="id_program" class="form-select" id="selectProgram{{ $bantuan->id_bantuan }}">
                            <option value="">-- Tetapkan Program Bantuan --</option>
                            @foreach($programsList as $prog)
                                <option value="{{ $prog->id_program }}" {{ (int)($bantuan->id_program ?? 0) === (int)$prog->id_program ? 'selected' : '' }}>
                                    {{ $prog->nama_program }} {{ $prog->sumber_dana ? '('.$prog->sumber_dana.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih alokasi bantuan resmi yang ditetapkan Dinas untuk usulan ini.</small>
                    </div>
                    @endif

                    <div class="mb-0">
                        <label class="form-label fw-bold text-dark">
                            Catatan Resmi Verifikator 
                            <span class="text-danger {{ in_array($defaultSelected, [\App\Models\Bantuan::STATUS_REVISI, \App\Models\Bantuan::STATUS_DITOLAK], true) ? '' : 'd-none' }}" id="reqStar{{ $bantuan->id_bantuan }}">*</span>
                            <span class="text-muted fw-normal small {{ in_array($defaultSelected, [\App\Models\Bantuan::STATUS_REVISI, \App\Models\Bantuan::STATUS_DITOLAK], true) ? 'd-none' : '' }}" id="optLabel{{ $bantuan->id_bantuan }}">(Opsional)</span>
                        </label>
                        <textarea name="catatan_verifikasi" 
                                  id="catatanVerifikasi{{ $bantuan->id_bantuan }}" 
                                  class="form-control" 
                                  rows="4" 
                                  maxlength="4000" 
                                  placeholder="Tuliskan catatan verifikasi fisik, kelayakan faktual, atau alasan revisi/penolakan..."
                                  {{ in_array($defaultSelected, [\App\Models\Bantuan::STATUS_REVISI, \App\Models\Bantuan::STATUS_DITOLAK], true) ? 'required' : '' }}>{{ $bantuan->catatan_verifikasi }}</textarea>
                        <small class="text-muted">Maksimal 4000 karakter. Wajib diisi jika usulan ditolak atau perlu revisi.</small>
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

<script>
if (typeof window.handleVerifikasiChange === 'undefined') {
    window.handleVerifikasiChange = function(selectElem, idBantuan) {
        const status = String(selectElem.value);
        const textarea = document.getElementById('catatanVerifikasi' + idBantuan);
        const reqStar = document.getElementById('reqStar' + idBantuan);
        const optLabel = document.getElementById('optLabel' + idBantuan);

        // Status 3 = Perlu Revisi, Status 4 = Ditolak
        const wajibCatatan = (status === '3' || status === '4');

        if (textarea) {
            textarea.required = wajibCatatan;
        }
        if (reqStar && optLabel) {
            if (wajibCatatan) {
                reqStar.classList.remove('d-none');
                optLabel.classList.add('d-none');
            } else {
                reqStar.classList.add('d-none');
                optLabel.classList.remove('d-none');
            }
        }
    };
}
</script>
@endif
