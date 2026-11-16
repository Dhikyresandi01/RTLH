@php
    $rank = $rank ?? $warga?->bantuanAktif?->spkRanking;
@endphp

@if($rank && $warga)
@php
    $rincian = $rank->rincian_kriteria ?? [];
@endphp
<div class="modal fade" id="modalDetailSAW{{ $warga->id_warga }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-calculator me-2 text-primary"></i>Rincian Skor SAW #{{ $rank->ranking }} - {{ $warga->nama }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm text-center align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th class="text-start">Kriteria</th>
                                <th>Sifat</th>
                                <th>Bobot (W)</th>
                                <th>Nilai Mentah (X)</th>
                                <th>Normalisasi (r<sub>ij</sub>)</th>
                                <th>Skor Terbobot</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(['C1', 'C2', 'C3', 'C4', 'C5'] as $kode)
                                @php $k = $rincian[$kode] ?? null; @endphp
                                <tr>
                                    <td class="fw-bold">{{ $kode }}</td>
                                    <td class="text-start">{{ $k['nama'] ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ ($k['jenis'] ?? '') === 'benefit' ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ strtoupper($k['jenis'] ?? '-') }}
                                        </span>
                                    </td>
                                    <td>{{ $k['bobot'] ?? '-' }}</td>
                                    <td class="fw-semibold">{{ $k['mentah'] ?? '-' }}</td>
                                    <td>{{ $k['normalisasi'] ?? '-' }}</td>
                                    <td class="fw-bold text-primary">{{ $k['terbobot'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="6" class="text-end">Total Skor Akhir Preferensi (V<sub>i</sub>):</th>
                                <th class="text-primary fs-6">{{ number_format($rank->skor_akhir, 4) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endif
