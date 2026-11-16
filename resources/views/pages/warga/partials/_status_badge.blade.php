@php
    $showVerifikator = $showVerifikator ?? false;
    $showCatatan = $showCatatan ?? false;
@endphp

@if($bantuan)
    @php
        $statusIcon = match ((int) $bantuan->status_bantuan) {
            \App\Models\Bantuan::STATUS_DIAJUKAN => 'bi-clock',
            \App\Models\Bantuan::STATUS_DIVERIFIKASI => 'bi-clipboard-check',
            \App\Models\Bantuan::STATUS_DISETUJUI => 'bi-check2-circle',
            \App\Models\Bantuan::STATUS_REVISI => 'bi-arrow-repeat',
            \App\Models\Bantuan::STATUS_DITOLAK => 'bi-x-circle',
            \App\Models\Bantuan::STATUS_DIREALISASIKAN => 'bi-house-check-fill',
            default => 'bi-question-circle',
        };
    @endphp
    <span class="badge {{ $bantuan->status_badge_class }}">
        <i class="bi {{ $statusIcon }} me-1"></i>{{ $bantuan->status_label }}
    </span>

    @if($showVerifikator && $bantuan->verifikator)
        <div class="small text-muted mt-1" style="font-size: 0.72rem;">
            Oleh: {{ $bantuan->verifikator->name }}
        </div>
    @endif

    @if($showCatatan && $bantuan->catatan_verifikasi)
        <div class="small text-muted mt-1 text-truncate" style="max-width: 220px; font-size: 0.72rem;" title="{{ $bantuan->catatan_verifikasi }}">
            <i class="bi bi-chat-left-text me-1"></i>{{ \Illuminate\Support\Str::limit($bantuan->catatan_verifikasi, 45) }}
        </div>
    @endif
@else
    <span class="badge bg-light text-muted border">Belum Ada Usulan</span>
@endif
