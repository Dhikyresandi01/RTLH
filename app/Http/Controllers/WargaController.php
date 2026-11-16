<?php

namespace App\Http\Controllers;

use App\Models\Bantuan;
use App\Models\DokumenRumah;
use App\Models\DokumenWarga;
use App\Models\Hunian;
use App\Models\ProgramBantuan;
use App\Models\Rumah;
use App\Models\SpkRanking;
use App\Models\Utilitas;
use App\Models\Warga;
use App\Models\Wilayah;
use App\Services\SawRankingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class WargaController extends Controller
{
    /**
     * Membangun query data terpadu warga berdasarkan filter dan hak akses user login.
     */
    protected function buildWargaQuery(Request $request)
    {
        $query = Warga::with([
            'wilayah',
            'Dokumen',
            'Hunian.Rumah.Utilitas',
            'Hunian.Rumah.DokumenRumah',
            'bantuanAktif.spkRanking',
            'bantuanAktif.verifikator',
            'bantuanAktif.program',
        ]);

        // Batasi akses jika user login memiliki cakupan wilayah (admin kelurahan)
        if (Auth::check()) {
            $query->visibleTo(Auth::user());
        }

        // Fitur Pencarian (NIK, Nama, atau No. KK)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('warga.nik', 'like', "%{$search}%")
                  ->orWhere('warga.nama', 'like', "%{$search}%")
                  ->orWhere('warga.no_kk', 'like', "%{$search}%");
            });
        }

        // Fitur Filter Wilayah / Kelurahan
        if ($request->filled('id_wilayah')) {
            $query->where('warga.id_wilayah', $request->input('id_wilayah'));
        }

        // Fitur Filter Status Verifikasi Profil
        if ($request->filled('status_update')) {
            $query->where('warga.status_update', $request->input('status_update'));
        }

        // Fitur Filter Status Bantuan / Verifikasi Dinas
        if ($request->filled('status_bantuan')) {
            $statusBantuan = $request->input('status_bantuan');
            $query->whereHas('bantuanAktif', function ($q) use ($statusBantuan) {
                $q->where('status_bantuan', $statusBantuan);
            });
        }

        // Fitur Filter Program / Jenis Bantuan
        if ($request->filled('id_program')) {
            $idProgram = $request->input('id_program');
            $query->whereHas('bantuanAktif', function ($q) use ($idProgram) {
                $q->where('id_program', $idProgram);
            });
        }

        // Fitur Filter Status Kepemilikan Rumah
        if ($request->filled('status_rumah')) {
            $statusRumah = $request->input('status_rumah');
            $query->whereHas('Hunian', function ($q) use ($statusRumah) {
                $q->where('status_rumah', $statusRumah);
            });
        }

        // Fitur Filter Kondisi Atap / Kerusakan
        if ($request->filled('kondisi_atap')) {
            $kondisiAtap = $request->input('kondisi_atap');
            $query->whereHas('Hunian.Rumah', function ($q) use ($kondisiAtap) {
                $q->where('kondisi_atap', $kondisiAtap);
            });
        }

        // Opsi Pengurutan: Peringkat Prioritas SAW (Khusus Dinas/Superadmin) atau Terbaru
        $isDinas = Auth::check() && (Auth::user()->isSuperadmin() || Auth::user()->isAdminDinas());
        if ($isDinas && $request->input('sort') === 'rank_asc') {
            $query->leftJoin('bantuan', function ($join) {
                $join->on('warga.id_warga', '=', 'bantuan.id_warga')
                     ->where('bantuan.is_aktif', true);
            })
            ->leftJoin('spk_ranking', 'bantuan.id_bantuan', '=', 'spk_ranking.id_bantuan')
            ->select('warga.*')
            ->orderByRaw('spk_ranking.ranking IS NULL, spk_ranking.ranking ASC');
        } else {
            $query->latest('warga.id_warga');
        }

        return $query;
    }

    /**
     * Menampilkan daftar data terpadu warga / calon penerima, hunian, dan fisik rumah.
     */
    public function index(Request $request)
    {
        $query = $this->buildWargaQuery($request);

        $wargas = $query->paginate(10)->withQueryString();
        $wilayahs = Wilayah::orderBy('desa')->get();
        $programs = ProgramBantuan::orderBy('nama_program')->get();

        // Cek nama view yang digunakan di project
        $viewName = view()->exists('pages.warga.viewwarga') ? 'pages.warga.viewwarga' : 'pages.warga.index';

        return view($viewName, compact('wargas', 'wilayahs', 'programs'));
    }

    /**
     * Mengekspor data calon penerima bantuan RTLH ke format spreadsheet Excel (.xls) native.
     */
    public function exportExcel(Request $request)
    {
        $query = $this->buildWargaQuery($request);
        $wargas = $query->get();

        $isDinas = Auth::check() && (Auth::user()->isSuperadmin() || Auth::user()->isAdminDinas());
        $desaName = (Auth::check() && Auth::user()->wilayah) ? Str::slug(Auth::user()->wilayah->desa, '_') : 'Kota_Kediri';
        $filename = 'Data_Calon_Penerima_RTLH_' . $desaName . '_' . date('Ymd_His') . '.xls';

        $headers = [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        return response()->streamDownload(function () use ($wargas, $isDinas) {
            echo view('pages.warga.export-excel', compact('wargas', 'isDinas'))->render();
        }, $filename, $headers);
    }

    /**
     * Menampilkan formulir terpadu tambah calon penerima baru (Warga, Hunian & Survei Rumah).
     */
    public function create()
    {
        $wilayahs = Wilayah::orderBy('desa')->get();
        $programs = ProgramBantuan::where('is_aktif', true)->orderBy('nama_program')->get();

        return view('pages.warga.create', compact('wilayahs', 'programs'));
    }

    /**
     * Menyimpan data terpadu warga, berkas, kepemilikan hunian, dan survei fisik rumah sekaligus ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // 1. Kependudukan Warga
            'nik'            => ['required', 'numeric', 'digits:16', 'unique:warga,nik'],
            'no_kk'          => ['required', 'numeric', 'digits:16'],
            'nama'           => ['required', 'string', 'max:100'],
            'jenis_kelamin'  => ['required', 'in:1,2'],
            'tempat_lahir'   => ['nullable', 'string', 'max:100'],
            'tgl_lahir'      => ['nullable', 'date'],
            
            // Alamat & Wilayah
            'alamat'         => ['nullable', 'string', 'max:150'],
            'rt'             => ['nullable', 'string', 'max:5'],
            'rw'             => ['nullable', 'string', 'max:5'],
            'id_wilayah'     => ['required', 'exists:wilayah,id_wilayah'],
            'no_hp'          => ['nullable', 'string', 'max:15'],

            // Sosio-Ekonomi
            'kawin'          => ['required', 'in:1,2,3'],
            'pendidikan'     => ['nullable', 'integer'],
            'pekerjaan'      => ['nullable', 'integer'],
            'no_npwp'        => ['nullable', 'string', 'max:20'],
            'penghasilan'    => ['nullable', 'integer'],
            'tabungan'       => ['nullable', 'integer'],
            'mampu_swadaya'  => ['nullable', 'boolean'],
            'dtsen'          => ['nullable', 'integer', 'in:1,2,3,4,0'],

            // Program Bantuan
            'id_program'     => ['nullable', 'exists:program_bantuan,id_program'],

            // Upload Dokumen Warga
            'foto_ktp'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,pdf', 'max:2048'],
            'foto_kk'        => ['nullable', 'image', 'mimes:jpeg,png,jpg,pdf', 'max:2048'],
            'foto_dtsen'     => ['nullable', 'image', 'mimes:jpeg,png,jpg,pdf', 'max:2048'],

            // 2. Data Hunian & Legalitas Tanah
            'status_rumah'   => ['nullable', 'integer', 'in:1,2,3'],
            'status_tanah'   => ['nullable', 'integer', 'in:1,2,3'],
            'rumah_lain'     => ['nullable', 'boolean'],
            'tanah_lain'     => ['nullable', 'boolean'],
            'luas_rumah'     => ['nullable', 'numeric', 'min:0'],
            'luas_tanah'     => ['nullable', 'numeric', 'min:0'],
            'kawasan'        => ['nullable', 'integer', 'in:1,2,3,4'],
            'jml_kk'         => ['nullable', 'integer', 'min:1'],
            'jml_penghuni'   => ['nullable', 'integer', 'min:1'],
            'latitude'       => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'      => ['nullable', 'numeric', 'between:-180,180'],

            // 3. Data Konstruksi Fisik Bangunan (1=Baik, 2=Sedang, 3=Rusak)
            'pondasi'        => ['nullable', 'integer', 'in:1,2,3'],
            'kolom'          => ['nullable', 'integer', 'in:1,2,3'],
            'balok'          => ['nullable', 'integer', 'in:1,2,3'],
            'sloof'          => ['nullable', 'integer', 'in:1,2,3'],
            'plafon'         => ['nullable', 'integer', 'in:1,2,3'],
            'kerangka_atap'  => ['nullable', 'integer', 'in:1,2,3'],
            'kondisi_lantai' => ['nullable', 'integer', 'in:1,2,3'],
            'bahan_lantai'   => ['nullable', 'integer'],
            'kondisi_dinding'=> ['nullable', 'integer', 'in:1,2,3'],
            'bahan_dinding'  => ['nullable', 'integer'],
            'kondisi_atap'   => ['nullable', 'integer', 'in:1,2,3'],
            'bahan_atap'     => ['nullable', 'integer'],

            // 4. Utilitas & Sanitasi
            'jendela'           => ['nullable', 'integer'],
            'ventilasi'         => ['nullable', 'integer'],
            'sumber_air'        => ['nullable', 'integer'],
            'mck'               => ['nullable', 'integer'],
            'jenis_jamban'      => ['nullable', 'integer'],
            'jenis_tpa'         => ['nullable', 'integer'],
            'jarak_septik_tank' => ['nullable', 'integer'],
            'sumber_penerangan' => ['nullable', 'integer'],
            'daya_listrik'      => ['nullable', 'integer'],
            'bahan_bakar_masak' => ['nullable', 'integer'],

            // 5. Upload Foto Dokumentasi Fisik Lapangan
            'foto_depan'     => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'foto_dalam'     => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'foto_atap'      => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'foto_dinding'   => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'foto_lantai'    => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'foto_mck'       => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ], [
            'nik.required'       => 'NIK wajib diisi.',
            'nik.digits'         => 'NIK harus berjumlah tepat 16 digit angka.',
            'nik.unique'         => 'NIK sudah terdaftar dalam sistem.',
            'no_kk.required'     => 'Nomor Kartu Keluarga wajib diisi.',
            'no_kk.digits'       => 'Nomor Kartu Keluarga harus berjumlah tepat 16 digit angka.',
            'nama.required'      => 'Nama lengkap wajib diisi.',
            'id_wilayah.required'=> 'Pilihan Kelurahan / Wilayah wajib dipilih.',
            'kawin.required'     => 'Status perkawinan wajib dipilih.',
        ]);

        DB::beginTransaction();
        try {
            // Pasang user input & status verifikasi default
            $validated['id_user_input'] = Auth::id() ?? 1;
            $validated['status_update'] = 0; // 0 = Draft / Belum diverifikasi
            $validated['mampu_swadaya'] = $request->boolean('mampu_swadaya');

            // 1. Simpan Data Utama Warga
            $warga = Warga::create($validated);

            // 2. Simpan Dokumen Warga (KTP, KK, DTSEN)
            $dokumenData = ['id_warga' => $warga->id_warga];
            $hasDokumen = false;

            if ($request->hasFile('foto_ktp')) {
                $dokumenData['foto_ktp'] = $request->file('foto_ktp')->store('dokumen_warga', 'public');
                $hasDokumen = true;
            }
            if ($request->hasFile('foto_kk')) {
                $dokumenData['foto_kk'] = $request->file('foto_kk')->store('dokumen_warga', 'public');
                $hasDokumen = true;
            }
            if ($request->hasFile('foto_dtsen')) {
                $dokumenData['foto_dtsen'] = $request->file('foto_dtsen')->store('dokumen_warga', 'public');
                $hasDokumen = true;
            }

            if ($hasDokumen) {
                DokumenWarga::create($dokumenData);
            }

            // 3. Simpan Data Hunian Warga
            $hunian = Hunian::create([
                'id_warga'     => $warga->id_warga,
                'status_rumah' => $validated['status_rumah'] ?? null,
                'status_tanah' => $validated['status_tanah'] ?? null,
                'rumah_lain'   => $request->boolean('rumah_lain'),
                'tanah_lain'   => $request->boolean('tanah_lain'),
                'luas_rumah'   => $validated['luas_rumah'] ?? null,
                'luas_tanah'   => $validated['luas_tanah'] ?? null,
                'kawasan'      => $validated['kawasan'] ?? null,
                'jml_kk'       => $validated['jml_kk'] ?? 1,
                'jml_penghuni' => $validated['jml_penghuni'] ?? 1,
                'latitude'     => $validated['latitude'] ?? null,
                'longitude'    => $validated['longitude'] ?? null,
            ]);

            // 4. Simpan Kondisi Konstruksi Fisik Rumah
            $rumah = Rumah::create([
                'id_hunian'       => $hunian->id_hunian,
                'pondasi'         => $validated['pondasi'] ?? null,
                'kolom'           => $validated['kolom'] ?? null,
                'balok'           => $validated['balok'] ?? null,
                'sloof'           => $validated['sloof'] ?? null,
                'plafon'          => $validated['plafon'] ?? null,
                'kerangka_atap'   => $validated['kerangka_atap'] ?? null,
                'kondisi_lantai'  => $validated['kondisi_lantai'] ?? null,
                'bahan_lantai'    => $validated['bahan_lantai'] ?? null,
                'kondisi_dinding' => $validated['kondisi_dinding'] ?? null,
                'bahan_dinding'   => $validated['bahan_dinding'] ?? null,
                'kondisi_atap'    => $validated['kondisi_atap'] ?? null,
                'bahan_atap'      => $validated['bahan_atap'] ?? null,
            ]);

            // 5. Simpan Fasilitas Utilitas & Sanitasi Rumah
            Utilitas::create([
                'id_rumah'          => $rumah->id_rumah,
                'jendela'           => $validated['jendela'] ?? null,
                'ventilasi'         => $validated['ventilasi'] ?? null,
                'sumber_air'        => $validated['sumber_air'] ?? null,
                'mck'               => $validated['mck'] ?? null,
                'jenis_jamban'      => $validated['jenis_jamban'] ?? null,
                'jenis_tpa'         => $validated['jenis_tpa'] ?? null,
                'jarak_septik_tank' => $validated['jarak_septik_tank'] ?? null,
                'sumber_penerangan' => $validated['sumber_penerangan'] ?? null,
                'daya_listrik'      => $validated['daya_listrik'] ?? null,
                'bahan_bakar_masak' => $validated['bahan_bakar_masak'] ?? null,
            ]);

            // 6. Simpan Foto Dokumentasi Fisik Rumah
            $fotoList = [
                'foto_depan'   => 'Tampak Depan',
                'foto_dalam'   => 'Ruang Dalam',
                'foto_atap'    => 'Kondisi Atap',
                'foto_dinding' => 'Kondisi Dinding',
                'foto_lantai'  => 'Kondisi Lantai',
                'foto_mck'     => 'Kondisi MCK / Sanitasi',
            ];

            foreach ($fotoList as $inputName => $jenisFoto) {
                if ($request->hasFile($inputName)) {
                    $path = $request->file($inputName)->store('dokumen_rumah', 'public');
                    DokumenRumah::create([
                        'id_rumah'   => $rumah->id_rumah,
                        'jenis_foto' => $jenisFoto,
                        'path_foto'  => $path,
                    ]);
                }
            }

            // 7. Otomatis buatkan usulan bantuan awal jika jenis bantuan dipilih
            if (!empty($validated['id_program'])) {
                $program = ProgramBantuan::findOrFail($validated['id_program']);
                Bantuan::create([
                    'id_warga' => $warga->id_warga,
                    'id_program' => $program->id_program,
                    'tahun_bantuan' => (int) date('Y'),
                    'id_user_pengajuan' => Auth::id() ?? 1,
                    'tgl_pengajuan' => now(),
                    'status_bantuan' => Bantuan::STATUS_DIAJUKAN,
                    'is_aktif' => true,
                ]);
            }

            DB::commit();

            // 8. Otomatis hitung ulang perankingan SAW untuk seluruh data terpadu
            try {
                app(SawRankingService::class)->hitungDanSimpan((int) date('Y'));
            } catch (\Exception $e) {
                Log::warning('Otomatisasi SAW pada tambah warga: ' . $e->getMessage());
            }

            return redirect()->route('warga.index')
                ->with('success', 'Data calon penerima, kepemilikan hunian, dan survei fisik berhasil disimpan. Peringkat SPK SAW telah otomatis diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail komprehensif profil warga, berkas, kepemilikan hunian, dan dokumentasi fisik.
     */
    public function show(string $id)
    {
        $warga = Warga::with([
            'wilayah',
            'Dokumen',
            'userInput',
            'userVerifikator',
            'Hunian.Rumah.Utilitas',
            'Hunian.Rumah.DokumenRumah',
            'Bantuan.program'
        ])->findOrFail($id);

        return view('pages.warga.show', compact('warga'));
    }

    /**
     * Menampilkan form untuk mengubah data terpadu warga, hunian, dan hasil survei.
     */
    public function edit(string $id)
    {
        $warga = Warga::with([
            'wilayah',
            'Dokumen',
            'Hunian.Rumah.Utilitas',
            'Hunian.Rumah.DokumenRumah'
        ])->findOrFail($id);

        $wilayahs = Wilayah::orderBy('desa')->get();

        return view('pages.warga.edit', compact('warga', 'wilayahs'));
    }

    /**
     * Menyimpan pembaruan data terpadu warga, hunian, fisik bangunan, dan berkas.
     */
    public function update(Request $request, string $id)
    {
        $warga = Warga::with(['Dokumen', 'Hunian.Rumah.Utilitas', 'Hunian.Rumah.DokumenRumah'])->findOrFail($id);

        $validated = $request->validate([
            // Kependudukan
            'nik'            => ['required', 'numeric', 'digits:16', Rule::unique('warga', 'nik')->ignore($warga->id_warga, 'id_warga')],
            'no_kk'          => ['required', 'numeric', 'digits:16'],
            'nama'           => ['required', 'string', 'max:100'],
            'jenis_kelamin'  => ['required', 'in:1,2'],
            'tempat_lahir'   => ['nullable', 'string', 'max:100'],
            'tgl_lahir'      => ['nullable', 'date'],

            // Alamat & Wilayah
            'alamat'         => ['nullable', 'string', 'max:150'],
            'rt'             => ['nullable', 'string', 'max:5'],
            'rw'             => ['nullable', 'string', 'max:5'],
            'id_wilayah'     => ['required', 'exists:wilayah,id_wilayah'],
            'no_hp'          => ['nullable', 'string', 'max:15'],

            // Sosio-Ekonomi
            'kawin'          => ['required', 'in:1,2,3'],
            'pendidikan'     => ['nullable', 'integer'],
            'pekerjaan'      => ['nullable', 'integer'],
            'no_npwp'        => ['nullable', 'string', 'max:20'],
            'penghasilan'    => ['nullable', 'integer'],
            'tabungan'       => ['nullable', 'integer'],
            'mampu_swadaya'  => ['nullable', 'boolean'],
            'dtsen'          => ['nullable', 'integer', 'in:1,2,3,4,0'],

            // Program Bantuan
            'id_program'     => ['nullable', 'exists:program_bantuan,id_program'],
            'status_update'  => ['nullable', 'integer', 'in:0,1'],

            // Upload Dokumen Warga
            'foto_ktp'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,pdf', 'max:2048'],
            'foto_kk'        => ['nullable', 'image', 'mimes:jpeg,png,jpg,pdf', 'max:2048'],
            'foto_dtsen'     => ['nullable', 'image', 'mimes:jpeg,png,jpg,pdf', 'max:2048'],

            // Data Hunian & Lahan
            'status_rumah'   => ['nullable', 'integer', 'in:1,2,3'],
            'status_tanah'   => ['nullable', 'integer', 'in:1,2,3'],
            'rumah_lain'     => ['nullable', 'boolean'],
            'tanah_lain'     => ['nullable', 'boolean'],
            'luas_rumah'     => ['nullable', 'numeric', 'min:0'],
            'luas_tanah'     => ['nullable', 'numeric', 'min:0'],
            'kawasan'        => ['nullable', 'integer', 'in:1,2,3,4'],
            'jml_kk'         => ['nullable', 'integer', 'min:1'],
            'jml_penghuni'   => ['nullable', 'integer', 'min:1'],
            'latitude'       => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'      => ['nullable', 'numeric', 'between:-180,180'],

            // Data Konstruksi Fisik Bangunan
            'pondasi'        => ['nullable', 'integer', 'in:1,2,3'],
            'kolom'          => ['nullable', 'integer', 'in:1,2,3'],
            'balok'          => ['nullable', 'integer', 'in:1,2,3'],
            'sloof'          => ['nullable', 'integer', 'in:1,2,3'],
            'plafon'         => ['nullable', 'integer', 'in:1,2,3'],
            'kerangka_atap'  => ['nullable', 'integer', 'in:1,2,3'],
            'kondisi_lantai' => ['nullable', 'integer', 'in:1,2,3'],
            'bahan_lantai'   => ['nullable', 'integer'],
            'kondisi_dinding'=> ['nullable', 'integer', 'in:1,2,3'],
            'bahan_dinding'  => ['nullable', 'integer'],
            'kondisi_atap'   => ['nullable', 'integer', 'in:1,2,3'],
            'bahan_atap'     => ['nullable', 'integer'],

            // Utilitas & Sanitasi
            'jendela'           => ['nullable', 'integer'],
            'ventilasi'         => ['nullable', 'integer'],
            'sumber_air'        => ['nullable', 'integer'],
            'mck'               => ['nullable', 'integer'],
            'jenis_jamban'      => ['nullable', 'integer'],
            'jenis_tpa'         => ['nullable', 'integer'],
            'jarak_septik_tank' => ['nullable', 'integer'],
            'sumber_penerangan' => ['nullable', 'integer'],
            'daya_listrik'      => ['nullable', 'integer'],
            'bahan_bakar_masak' => ['nullable', 'integer'],

            // Upload Foto Dokumentasi Fisik
            'foto_depan'     => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'foto_dalam'     => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'foto_atap'      => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'foto_dinding'   => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'foto_lantai'    => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'foto_mck'       => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        DB::beginTransaction();
        try {
            $validated['mampu_swadaya'] = $request->boolean('mampu_swadaya');
            if ($request->filled('status_update')) {
                $validated['status_update'] = $request->input('status_update');
            }

            // 1. Perbarui Data Warga
            $warga->update($validated);

            // 2. Perbarui Dokumen Warga
            $dokumen = $warga->Dokumen ?? new DokumenWarga(['id_warga' => $warga->id_warga]);

            if ($request->hasFile('foto_ktp')) {
                if ($dokumen->foto_ktp && Storage::disk('public')->exists($dokumen->foto_ktp)) {
                    Storage::disk('public')->delete($dokumen->foto_ktp);
                }
                $dokumen->foto_ktp = $request->file('foto_ktp')->store('dokumen_warga', 'public');
            }

            if ($request->hasFile('foto_kk')) {
                if ($dokumen->foto_kk && Storage::disk('public')->exists($dokumen->foto_kk)) {
                    Storage::disk('public')->delete($dokumen->foto_kk);
                }
                $dokumen->foto_kk = $request->file('foto_kk')->store('dokumen_warga', 'public');
            }

            if ($request->hasFile('foto_dtsen')) {
                if ($dokumen->foto_dtsen && Storage::disk('public')->exists($dokumen->foto_dtsen)) {
                    Storage::disk('public')->delete($dokumen->foto_dtsen);
                }
                $dokumen->foto_dtsen = $request->file('foto_dtsen')->store('dokumen_warga', 'public');
            }

            $dokumen->save();

            // 3. Perbarui atau Buat Data Hunian
            $hunian = $warga->Hunian->first();
            $hunianData = [
                'id_warga'     => $warga->id_warga,
                'status_rumah' => $validated['status_rumah'] ?? $hunian?->status_rumah,
                'status_tanah' => $validated['status_tanah'] ?? $hunian?->status_tanah,
                'rumah_lain'   => $request->boolean('rumah_lain'),
                'tanah_lain'   => $request->boolean('tanah_lain'),
                'luas_rumah'   => $validated['luas_rumah'] ?? $hunian?->luas_rumah,
                'luas_tanah'   => $validated['luas_tanah'] ?? $hunian?->luas_tanah,
                'kawasan'      => $validated['kawasan'] ?? $hunian?->kawasan,
                'jml_kk'       => $validated['jml_kk'] ?? ($hunian?->jml_kk ?? 1),
                'jml_penghuni' => $validated['jml_penghuni'] ?? ($hunian?->jml_penghuni ?? 1),
                'latitude'     => $validated['latitude'] ?? $hunian?->latitude,
                'longitude'    => $validated['longitude'] ?? $hunian?->longitude,
            ];

            if ($hunian) {
                $hunian->update($hunianData);
            } else {
                $hunian = Hunian::create($hunianData);
            }

            // 4. Perbarui atau Buat Data Fisik Rumah
            $rumah = $hunian->Rumah->first();
            $rumahData = [
                'id_hunian'       => $hunian->id_hunian,
                'pondasi'         => $validated['pondasi'] ?? $rumah?->pondasi,
                'kolom'           => $validated['kolom'] ?? $rumah?->kolom,
                'balok'           => $validated['balok'] ?? $rumah?->balok,
                'sloof'           => $validated['sloof'] ?? $rumah?->sloof,
                'plafon'          => $validated['plafon'] ?? $rumah?->plafon,
                'kerangka_atap'   => $validated['kerangka_atap'] ?? $rumah?->kerangka_atap,
                'kondisi_lantai'  => $validated['kondisi_lantai'] ?? $rumah?->kondisi_lantai,
                'bahan_lantai'    => $validated['bahan_lantai'] ?? $rumah?->bahan_lantai,
                'kondisi_dinding' => $validated['kondisi_dinding'] ?? $rumah?->kondisi_dinding,
                'bahan_dinding'   => $validated['bahan_dinding'] ?? $rumah?->bahan_dinding,
                'kondisi_atap'    => $validated['kondisi_atap'] ?? $rumah?->kondisi_atap,
                'bahan_atap'      => $validated['bahan_atap'] ?? $rumah?->bahan_atap,
            ];

            if ($rumah) {
                $rumah->update($rumahData);
            } else {
                $rumah = Rumah::create($rumahData);
            }

            // 5. Perbarui atau Buat Utilitas
            $utilitas = $rumah->Utilitas->first();
            $utilitasData = [
                'id_rumah'          => $rumah->id_rumah,
                'jendela'           => $validated['jendela'] ?? $utilitas?->jendela,
                'ventilasi'         => $validated['ventilasi'] ?? $utilitas?->ventilasi,
                'sumber_air'        => $validated['sumber_air'] ?? $utilitas?->sumber_air,
                'mck'               => $validated['mck'] ?? $utilitas?->mck,
                'jenis_jamban'      => $validated['jenis_jamban'] ?? $utilitas?->jenis_jamban,
                'jenis_tpa'         => $validated['jenis_tpa'] ?? $utilitas?->jenis_tpa,
                'jarak_septik_tank' => $validated['jarak_septik_tank'] ?? $utilitas?->jarak_septik_tank,
                'sumber_penerangan' => $validated['sumber_penerangan'] ?? $utilitas?->sumber_penerangan,
                'daya_listrik'      => $validated['daya_listrik'] ?? $utilitas?->daya_listrik,
                'bahan_bakar_masak' => $validated['bahan_bakar_masak'] ?? $utilitas?->bahan_bakar_masak,
            ];

            if ($utilitas) {
                $utilitas->update($utilitasData);
            } else {
                Utilitas::create($utilitasData);
            }

            // 6. Unggah Foto Baru jika ada
            $fotoList = [
                'foto_depan'   => 'Tampak Depan',
                'foto_dalam'   => 'Ruang Dalam',
                'foto_atap'    => 'Kondisi Atap',
                'foto_dinding' => 'Kondisi Dinding',
                'foto_lantai'  => 'Kondisi Lantai',
                'foto_mck'     => 'Kondisi MCK / Sanitasi',
            ];

            foreach ($fotoList as $inputName => $jenisFoto) {
                if ($request->hasFile($inputName)) {
                    $existingFoto = $rumah->DokumenRumah()->where('jenis_foto', $jenisFoto)->first();
                    if ($existingFoto && Storage::disk('public')->exists($existingFoto->path_foto)) {
                        Storage::disk('public')->delete($existingFoto->path_foto);
                        $existingFoto->delete();
                    }

                    $path = $request->file($inputName)->store('dokumen_rumah', 'public');
                    DokumenRumah::create([
                        'id_rumah'   => $rumah->id_rumah,
                        'jenis_foto' => $jenisFoto,
                        'path_foto'  => $path,
                    ]);
                }
            }

            DB::commit();

            // Otomatis hitung ulang perankingan SAW untuk seluruh data terpadu
            try {
                app(SawRankingService::class)->hitungDanSimpan((int) date('Y'));
            } catch (\Exception $e) {
                Log::warning('Otomatisasi SAW pada update warga: ' . $e->getMessage());
            }

            return redirect()->route('warga.index')
                ->with('success', 'Data terpadu calon penerima berhasil diperbarui dan ranking prioritas SPK telah disesuaikan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data terpadu warga beserta hunian, survei rumah, dan seluruh file fisik.
     */
    public function destroy(string $id)
    {
        $warga = Warga::with(['Dokumen', 'Hunian.Rumah.DokumenRumah'])->findOrFail($id);

        DB::beginTransaction();
        try {
            // Hapus berkas fisik kependudukan
            if ($warga->Dokumen) {
                foreach (['foto_ktp', 'foto_kk', 'foto_dtsen'] as $field) {
                    if ($warga->Dokumen->$field && Storage::disk('public')->exists($warga->Dokumen->$field)) {
                        Storage::disk('public')->delete($warga->Dokumen->$field);
                    }
                }
                $warga->Dokumen->delete();
            }

            // Hapus seluruh file fisik survei rumah
            foreach ($warga->Hunian as $hunian) {
                foreach ($hunian->Rumah as $rumah) {
                    foreach ($rumah->DokumenRumah as $dokRumah) {
                        if ($dokRumah->path_foto && Storage::disk('public')->exists($dokRumah->path_foto)) {
                            Storage::disk('public')->delete($dokRumah->path_foto);
                        }
                    }
                }
            }

            // Hapus record warga (foreign key cascadeOnDelete akan membersihkan hunian, rumah, utilitas, dokumen_rumah)
            $warga->delete();

            DB::commit();

            // Otomatis hitung ulang perankingan SAW untuk seluruh data terpadu
            try {
                app(SawRankingService::class)->hitungDanSimpan((int) date('Y'));
            } catch (\Exception $e) {
                Log::warning('Otomatisasi SAW pada delete warga: ' . $e->getMessage());
            }

            return redirect()->route('warga.index')
                ->with('success', 'Data warga beserta seluruh survei hunian dan fisik rumah berhasil dihapus. Peringkat SAW telah diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
