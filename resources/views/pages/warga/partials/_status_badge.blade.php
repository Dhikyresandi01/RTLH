@php
    $showVerifikator = $showVerifikator ?? false;
    $showCatatan = $showCatatan ?? false;
@endphp

@if($bantuan)
    @php
        $statusStyle = match ((int) $bantuan->status_bantuan) {
            \App\Models\Bantuan::STATUS_DIAJUKAN => [
                'bg' => 'var(--stitch-warning-bg)',
                'color' => 'var(--stitch-warning-text)',
                'icon' => 'schedule',
            ],
            \App\Models\Bantuan::STATUS_DIVERIFIKASI => [
                'bg' => 'var(--stitch-info-bg)',
                'color' => 'var(--stitch-info-text)',
                'icon' => 'fact_check',
            ],
            \App\Models\Bantuan::STATUS_DISETUJUI => [
                'bg' => 'var(--stitch-success-bg)',
                'color' => 'var(--stitch-success-text)',
                'icon' => 'verified',
            ],
            \App\Models\Bantuan::STATUS_REVISI => [
                'bg' => 'var(--stitch-warning-bg)',
                'color' => 'var(--stitch-warning-text)',
                'icon' => 'schedule',
            ],
            \App\Models\Bantuan::STATUS_DITOLAK => [
                'bg' => 'var(--stitch-error-bg)',
                'color' => 'var(--stitch-error-text)',
                'icon' => 'cancel',
            ],
            \App\Models\Bantuan::STATUS_DIREALISASIKAN => [
                'bg' => 'var(--stitch-success-bg)',
                'color' => 'var(--stitch-success-text)',
                'icon' => 'engineering',
            ],
            default => [
                'bg' => '#F1F5F9',
                'color' => '#475569',
                'icon' => 'help',
            ],
        };
    @endphp

    <div class="d-flex flex-column align-items-start gap-1">
        <span class="badge rounded-pill d-inline-flex align-items-center gap-1 font-stitch" style="background: {{ $statusStyle['bg'] }}; color: {{ $statusStyle['color'] }}; font-weight: 600; font-size: 11.5px; padding: 4px 10px;">
            <span class="material-symbols-outlined" style="font-size: 14px;">{{ $statusStyle['icon'] }}</span>
            <span>{{ $bantuan->status_label }}</span>
        </span>

        @if($showVerifikator && $bantuan->verifikator)
            <span class="text-muted font-stitch" style="font-size: 11px;">
                Oleh: {{ $bantuan->verifikator->name }}
            </span>
        @endif

        @if($showCatatan && $bantuan->catatan_verifikasi)
            <span class="text-muted font-stitch text-truncate" style="max-width: 200px; font-size: 11px;" title="{{ $bantuan->catatan_verifikasi }}">
                {{ \Illuminate\Support\Str::limit($bantuan->catatan_verifikasi, 40) }}
            </span>
        @endif
    </div>
@else
    <span class="badge rounded-pill bg-light text-muted border font-stitch" style="font-weight: 500; font-size: 11.5px; padding: 4px 10px;">
        Belum Ada Usulan
    </span>
@endif
