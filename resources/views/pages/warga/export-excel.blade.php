<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--[if gte mso 9]>
    @php
    echo '<xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Data Calon Penerima RTLH</x:Name>
                    <x:WorksheetOptions>
                        <x:DisplayGridlines/>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>';
    @endphp
    <![endif]-->
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            color: #000000;
        }
        .title {
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
        }
        .subtitle {
            font-size: 11pt;
            text-align: center;
            color: #555555;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th {
            background-color: #002b84;
            color: #ffffff;
            font-weight: bold;
            border: 0.5pt solid #2c3e50;
            text-align: center;
            vertical-align: middle;
            padding: 8px 5px;
            font-size: 10pt;
        }
        td {
            border: 0.5pt solid #dddddd;
            padding: 6px 5px;
            vertical-align: middle;
            font-size: 10pt;
        }
        .str {
            mso-number-format: "\@";
        }
        .num {
            mso-number-format: "#,##0";
            text-align: right;
        }
        .currency {
            mso-number-format: '"Rp "\#,##0';
            text-align: right;
        }
        .dec {
            mso-number-format: "0.0000";
            text-align: right;
        }
        .date {
            mso-number-format: "dd/mm/yyyy";
            text-align: center;
        }
        .center {
            text-align: center;
        }
        .bg-rank-1 {
            background-color: #fef3c7;
            font-weight: bold;
        }
        .bg-rank-2 {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        .bg-rank-3 {
            background-color: #fee2e2;
            font-weight: bold;
        }
    </style>
</head>
<body>
    @php
        $totalWarga = $wargas->count();
        $desaUser = Auth::user()->wilayah->desa ?? 'Seluruh Kelurahan Kota Kediri';
    @endphp

    <table>
        <tr>
            <td colspan="{{ $isDinas ? '33' : '31' }}" class="title">
                PEMERINTAH KOTA KEDIRI - DINAS PERUMAHAN DAN KAWASAN PERMUKIMAN
            </td>
        </tr>
        <tr>
            <td colspan="{{ $isDinas ? '33' : '31' }}" class="title">
                DATA TERPADU CALON PENERIMA BANTUAN RUMAH TIDAK LAYAK HUNI (RTLH)
            </td>
        </tr>
        <tr>
            <td colspan="{{ $isDinas ? '33' : '31' }}" class="subtitle">
                Wilayah: {{ $desaUser }} | Tanggal Unduh: {{ date('d F Y, H:i') }} WIB | Oleh: {{ Auth::user()->name }} ({{ Auth::user()->role }})
            </td>
        </tr>
        <tr>
            <td colspan="{{ $isDinas ? '33' : '31' }}">&nbsp;</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                @if($isDinas)
                    <th style="width: 70px;">Rank SPK</th>
                    <th style="width: 80px;">Skor SAW ($V_i$)</th>
                @endif
                <th style="width: 140px;">NIK</th>
                <th style="width: 140px;">Nomor KK</th>
                <th style="width: 180px;">Nama Lengkap</th>
                <th style="width: 80px;">JK</th>
                <th style="width: 130px;">Tempat Lahir</th>
                <th style="width: 90px;">Tgl Lahir</th>
                <th style="width: 110px;">No. HP</th>
                <th style="width: 120px;">Kelurahan</th>
                <th style="width: 50px;">RT</th>
                <th style="width: 50px;">RW</th>
                <th style="width: 220px;">Alamat Lengkap</th>
                <th style="width: 90px;">Desil DTKS</th>
                <th style="width: 120px;">Pekerjaan</th>
                <th style="width: 120px;">Penghasilan/Bln</th>
                <th style="width: 110px;">Status Rumah</th>
                <th style="width: 120px;">Status Hak Tanah</th>
                <th style="width: 90px;">Luas Rumah (m²)</th>
                <th style="width: 90px;">Luas Tanah (m²)</th>
                <th style="width: 80px;">Jml Penghuni</th>
                <th style="width: 100px;">Kondisi Atap</th>
                <th style="width: 100px;">Kondisi Dinding</th>
                <th style="width: 100px;">Kondisi Lantai</th>
                <th style="width: 120px;">Sumber Air</th>
                <th style="width: 130px;">Fasilitas MCK</th>
                <th style="width: 160px;">Program Bantuan</th>
                <th style="width: 110px;">Nominal Bantuan</th>
                <th style="width: 60px;">Tahun</th>
                <th style="width: 130px;">Status Verifikasi</th>
                <th style="width: 130px;">Verifikator Dinas</th>
                <th style="width: 200px;">Catatan Verifikasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($wargas as $index => $warga)
                @php
                    $hunian = $warga->Hunian->first();
                    $rumah = $hunian?->Rumah->first();
                    $utilitas = $rumah?->Utilitas->first();
                    $bantuan = $warga->bantuanAktif;
                    $ranking = $bantuan?->spkRanking;

                    // Rank row background
                    $rowClass = '';
                    if ($ranking) {
                        if ($ranking->ranking == 1) $rowClass = 'bg-rank-1';
                        elseif ($ranking->ranking == 2) $rowClass = 'bg-rank-2';
                        elseif ($ranking->ranking == 3) $rowClass = 'bg-rank-3';
                    }

                    // Mapping Status Rumah
                    $statusRumahText = match ((int) ($hunian?->status_rumah ?? 0)) {
                        1 => 'Milik Sendiri',
                        2 => 'Sewa / Kontrak',
                        3 => 'Menumpang',
                        default => 'Belum Disurvei',
                    };

                    // Mapping Status Tanah
                    $statusTanahText = match ((int) ($hunian?->status_tanah ?? 0)) {
                        1 => 'Milik Sendiri (SHM)',
                        2 => 'Bukan Milik Sendiri',
                        3 => 'Tanah Negara/Kas Desa',
                        default => '-',
                    };

                    // Mapping Kondisi Kerusakan
                    $kondisiMap = [1 => 'Baik', 2 => 'Rusak Sedang', 3 => 'Rusak Berat'];
                    $atapText = $kondisiMap[$rumah?->kondisi_atap] ?? '-';
                    $dindingText = $kondisiMap[$rumah?->kondisi_dinding] ?? '-';
                    $lantaiText = $kondisiMap[$rumah?->kondisi_lantai] ?? '-';

                    // Mapping Sumber Air
                    $airMap = [1 => 'PDAM', 2 => 'Sumur Terlindung', 3 => 'Sumur Tak Terlindung', 4 => 'Mata Air / Lainnya'];
                    $airText = $airMap[$utilitas?->sumber_air] ?? '-';

                    // Mapping MCK
                    $mckMap = [1 => 'Sendiri di Dalam', 2 => 'Sendiri di Luar', 3 => 'Bersama/Umum', 4 => 'Tidak Ada'];
                    $mckText = $mckMap[$utilitas?->mck] ?? '-';

                    // Mapping Status Bantuan
                    $statusBantuanText = match ((int) ($bantuan?->status_bantuan ?? -1)) {
                        \App\Models\Bantuan::STATUS_DIAJUKAN => 'Diajukan',
                        \App\Models\Bantuan::STATUS_DIVERIFIKASI => 'Diverifikasi Lapangan',
                        \App\Models\Bantuan::STATUS_DISETUJUI => 'Disetujui Dinas',
                        \App\Models\Bantuan::STATUS_REVISI => 'Perlu Revisi',
                        \App\Models\Bantuan::STATUS_DITOLAK => 'Ditolak',
                        \App\Models\Bantuan::STATUS_DIREALISASIKAN => 'Direalisasikan (100%)',
                        default => 'Belum Ada Usulan',
                    };

                    // Desil DTSEN
                    $desilText = $warga->dtsen ? 'Desil ' . $warga->dtsen : '-';
                @endphp
                <tr class="{{ $rowClass }}">
                    <td class="center">{{ $index + 1 }}</td>

                    @if($isDinas)
                        <td class="center fw-bold">
                            {{ $ranking ? '#' . $ranking->ranking : '-' }}
                        </td>
                        <td class="dec">
                            {{ $ranking ? number_format($ranking->skor_akhir, 4) : '-' }}
                        </td>
                    @endif

                    <td class="str">{{ $warga->nik }}</td>
                    <td class="str">{{ $warga->no_kk }}</td>
                    <td>{{ $warga->nama }}</td>
                    <td class="center">{{ $warga->jenis_kelamin == 1 ? 'L' : ($warga->jenis_kelamin == 2 ? 'P' : '-') }}</td>
                    <td>{{ $warga->tempat_lahir ?? '-' }}</td>
                    <td class="date">{{ $warga->tgl_lahir ? $warga->tgl_lahir->format('d/m/Y') : '-' }}</td>
                    <td class="str">{{ $warga->no_hp ?? '-' }}</td>
                    <td>{{ $warga->wilayah->desa ?? '-' }}</td>
                    <td class="center str">{{ $warga->rt ?? '-' }}</td>
                    <td class="center str">{{ $warga->rw ?? '-' }}</td>
                    <td>{{ $warga->alamat ?? '-' }}</td>
                    <td class="center">{{ $desilText }}</td>
                    <td>{{ $warga->pekerjaan_text ?? '-' }}</td>
                    <td class="currency">{{ $warga->penghasilan ? $warga->penghasilan : 0 }}</td>
                    <td>{{ $statusRumahText }}</td>
                    <td>{{ $statusTanahText }}</td>
                    <td class="num">{{ $hunian?->luas_rumah ?? '-' }}</td>
                    <td class="num">{{ $hunian?->luas_tanah ?? '-' }}</td>
                    <td class="center num">{{ $hunian?->jml_penghuni ?? '-' }}</td>
                    <td class="center">{{ $atapText }}</td>
                    <td class="center">{{ $dindingText }}</td>
                    <td class="center">{{ $lantaiText }}</td>
                    <td>{{ $airText }}</td>
                    <td>{{ $mckText }}</td>
                    <td>{{ $bantuan?->program?->nama_program ?? '-' }}</td>
                    <td class="currency">{{ $bantuan?->program?->nominal_bantuan ?? 0 }}</td>
                    <td class="center">{{ $bantuan?->tahun_bantuan ?? '-' }}</td>
                    <td class="center fw-bold">{{ $statusBantuanText }}</td>
                    <td>{{ $bantuan?->verifikator?->name ?? '-' }}</td>
                    <td>{{ $bantuan?->catatan_verifikasi ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $isDinas ? '33' : '31' }}" class="center" style="padding: 20px;">
                        Tidak ada data calon penerima bantuan yang sesuai dengan filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
