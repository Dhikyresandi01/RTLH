@php
    $warga = $warga ?? $bantuan?->warga;
    $rank = $rank ?? $bantuan?->spkRanking;
    $programsList = $programs ?? \App\Models\ProgramBantuan::where('is_aktif', true)->orderBy('nama_program')->get();
@endphp

@if($bantuan && $warga)
<div class="modal fade" id="modalValidasiBantuan{{ $bantuan->id_bantuan }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('spk.verifikasi', $bantuan->id_bantuan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memvalidasi dan menetapkan bantuan untuk {{ addslashes($warga->nama) }}?');">
            @csrf
            <!-- Status otomatis disetujui/tervalidasi oleh Dinas -->
            <input type="hidden" name="status_bantuan" value="{{ \App\Models\Bantuan::STATUS_DISETUJUI }}">

            <div class="modal-content border-0 shadow">
                <div class="modal-header text-white" style="background-color: #054735;">
                    <h5 class="modal-title text-white fw-bold d-flex align-items-center">
                        <i class="bi bi-patch-check-fill text-warning me-2 fs-5"></i>Validasi & Penetapan Bantuan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Ringkasan Calon Penerima & Hasil Ranking SPK SAW -->
                    <div class="alert alert-light border mb-4 rounded-3 p-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                            <div>
                                <h6 class="fw-bold text-dark mb-0">{{ $warga->nama }}</h6>
                                <small class="text-muted">{{ $warga->wilayah->desa ?? '-' }} | NIK: {{ $warga->nik }}</small>
                            </div>
                            <span class="badge bg-success px-3 py-2">
                                <i class="bi bi-award me-1"></i>Peringkat #{{ $rank?->ranking ?? '-' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top small">
                            <span class="text-muted">Skor Preferensi SPK (V<sub>i</sub>):</span>
                            <span class="fw-bold text-primary fs-6">{{ number_format($rank?->skor_akhir ?? 0, 4) }}</span>
                        </div>
                    </div>

                    <!-- 1 FORM UTAMA: Alokasi Program Bantuan Resmi -->
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark d-flex justify-content-between align-items-center">
                            <span>Alokasi Program Bantuan <span class="text-danger">*</span></span>
                            <span class="badge bg-light text-primary border" style="font-size: 0.7rem;">Wewenang Dinas</span>
                        </label>
                        <select name="id_program" class="form-select form-select-lg border-primary shadow-sm" required autofocus>
                            <option value="">-- Pilih Program Bantuan yang Ditetapkan --</option>
                            @foreach($programsList as $prog)
                                <option value="{{ $prog->id_program }}" {{ (int)($bantuan->id_program ?? 0) === (int)$prog->id_program ? 'selected' : '' }}>
                                    {{ $prog->nama_program }} {{ $prog->sumber_dana ? '('.$prog->sumber_dana.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted mt-1 d-block">
                            Tetapkan jenis program bantuan resmi (APBD / BSPS / Provinsi) untuk usulan prioritas ini.
                        </small>
                    </div>

                    <!-- Catatan Verifikator / SK Penetapan (Opsional) -->
                    <div class="mb-0">
                        <label class="form-label fw-semibold text-dark small">Catatan / No. SK Penetapan (Opsional)</label>
                        <textarea name="catatan_verifikasi" class="form-control" rows="2" maxlength="1000" placeholder="Tuliskan catatan penetapan bantuan atau nomor SK jika ada (opsional)..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm px-3 fw-semibold">
                        <i class="bi bi-patch-check-fill me-1"></i> Simpan & Validasi Penerima
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
