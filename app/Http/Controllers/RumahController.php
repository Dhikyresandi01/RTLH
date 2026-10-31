<?php

namespace App\Http\Controllers;

use App\Models\DokumenRumah;
use App\Models\Hunian;
use App\Models\Rumah;
use App\Models\Utilitas;
use App\Models\Warga;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RumahController extends Controller
{
    /**
     * Menampilkan daftar data survei fisik rumah RTLH.
     */
    public function index(Request $request)
    {
        $query = Rumah::with(['hunian.warga.wilayah', 'Utilitas', 'DokumenRumah']);

        // Batasi wilayah jika pengguna adalah admin kelurahan
        if (Auth::check() && !Auth::user()->isSuperadmin() && !Auth::user()->isAdminDinas()) {
            $userWilayah = Auth::user()->id_wilayah;
            $query->whereHas('hunian.warga', function ($q) use ($userWilayah) {
                $q->where('id_wilayah', $userWilayah);
            });
        }

        // Pencarian (Nama Warga, NIK, atau Alamat)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('hunian.warga', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        // Filter Wilayah / Kelurahan
        if ($request->filled('id_wilayah')) {
            $idWilayah = $request->input('id_wilayah');
            $query->whereHas('hunian.warga', function ($q) use ($idWilayah) {
                $q->where('id_wilayah', $idWilayah);
            });
        }

        // Filter Kondisi Atap / Dinding / Lantai (Kategori 1=Baik, 2=Sedang, 3=Rusak)
        if ($request->filled('kondisi_atap')) {
            $query->where('kondisi_atap', $request->input('kondisi_atap'));
        }

        $rumahs = $query->latest('id_rumah')->paginate(10)->withQueryString();
        $wilayahs = Wilayah::orderBy('desa')->get();

        return view('pages.rumah.index', compact('rumahs', 'wilayahs'));
    }

    /**
     * Menampilkan formulir survei kondisi rumah baru.
     */
    public function create(Request $request)
    {
        $wargaQuery = Warga::with('wilayah');

        // Batasi pilihan warga sesuai wilayah jika admin kelurahan
        if (Auth::check()) {
            $wargaQuery->visibleTo(Auth::user());
        }

        $wargas = $wargaQuery->orderBy('nama')->get();

        // Jika form dipanggil dari profil warga tertentu (?id_warga=...)
        $selectedWarga = null;
        if ($request->filled('id_warga')) {
            $selectedWarga = Warga::find($request->input('id_warga'));
        }

        return view('pages.rumah.create', compact('wargas', 'selectedWarga'));
    }

    /**
     * Menyimpan hasil survei rumah, hunian, utilitas, dan dokumentasi foto ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Warga
            'id_warga'          => ['required', 'exists:warga,id_warga'],

            // Data Hunian & Lahan
            'status_rumah'      => ['nullable', 'integer'],
            'status_tanah'      => ['nullable', 'integer'],
            'rumah_lain'        => ['nullable', 'boolean'],
            'tanah_lain'        => ['nullable', 'boolean'],
            'luas_rumah'        => ['nullable', 'numeric', 'min:0'],
            'luas_tanah'        => ['nullable', 'numeric', 'min:0'],
            'kawasan'           => ['nullable', 'integer'],
            'jml_kk'            => ['nullable', 'integer', 'min:1'],
            'jml_penghuni'      => ['nullable', 'integer', 'min:1'],
            'latitude'          => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'         => ['nullable', 'numeric', 'between:-180,180'],

            // Data Struktur & Fisik Bangunan (1=Baik, 2=Sedang, 3=Rusak)
            'pondasi'           => ['nullable', 'integer', 'in:1,2,3'],
            'kolom'             => ['nullable', 'integer', 'in:1,2,3'],
            'balok'             => ['nullable', 'integer', 'in:1,2,3'],
            'sloof'             => ['nullable', 'integer', 'in:1,2,3'],
            'plafon'            => ['nullable', 'integer', 'in:1,2,3'],
            'kerangka_atap'     => ['nullable', 'integer', 'in:1,2,3'],
            'kondisi_lantai'    => ['nullable', 'integer', 'in:1,2,3'],
            'bahan_lantai'      => ['nullable', 'integer'],
            'kondisi_dinding'   => ['nullable', 'integer', 'in:1,2,3'],
            'bahan_dinding'     => ['nullable', 'integer'],
            'kondisi_atap'      => ['nullable', 'integer', 'in:1,2,3'],
            'bahan_atap'        => ['nullable', 'integer'],

            // Data Utilitas & Sanitasi
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
            'foto_depan'        => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'foto_dalam'        => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'foto_atap'         => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'foto_dinding'      => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'foto_lantai'       => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'foto_mck'          => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ], [
            'id_warga.required' => 'Calon penerima (warga) wajib dipilih.',
            'id_warga.exists'   => 'Data warga yang dipilih tidak valid.',
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan atau perbarui entitas Hunian untuk warga ini
            $hunian = Hunian::create([
                'id_warga'      => $validated['id_warga'],
                'status_rumah'  => $validated['status_rumah'] ?? null,
                'status_tanah'  => $validated['status_tanah'] ?? null,
                'rumah_lain'    => $request->boolean('rumah_lain'),
                'tanah_lain'    => $request->boolean('tanah_lain'),
                'luas_rumah'    => $validated['luas_rumah'] ?? null,
                'luas_tanah'    => $validated['luas_tanah'] ?? null,
                'kawasan'       => $validated['kawasan'] ?? null,
                'jml_kk'        => $validated['jml_kk'] ?? 1,
                'jml_penghuni'  => $validated['jml_penghuni'] ?? 1,
                'latitude'      => $validated['latitude'] ?? null,
                'longitude'     => $validated['longitude'] ?? null,
            ]);

            // 2. Simpan Kondisi Fisik Rumah
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

            // 3. Simpan Fasilitas Utilitas / Sanitasi
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

            // 4. Simpan Berkas Foto Fisik Rumah
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

            DB::commit();

            return redirect()->route('rumah.index')
                ->with('success', 'Data survei kondisi rumah berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail survei fisik rumah dan utilitasnya.
     */
    public function show(string $id)
    {
        $rumah = Rumah::with(['hunian.warga.wilayah', 'Utilitas', 'DokumenRumah'])
            ->findOrFail($id);

        return view('pages.rumah.show', compact('rumah'));
    }

    /**
     * Menampilkan formulir edit survei rumah.
     */
    public function edit(string $id)
    {
        $rumah = Rumah::with(['hunian.warga', 'Utilitas', 'DokumenRumah'])
            ->findOrFail($id);

        return view('pages.rumah.edit', compact('rumah'));
    }

    /**
     * Menyimpan pembaruan data survei rumah ke database.
     */
    public function update(Request $request, string $id)
    {
        $rumah = Rumah::with(['hunian', 'Utilitas', 'DokumenRumah'])->findOrFail($id);

        $validated = $request->validate([
            // Data Hunian & Lahan
            'status_rumah'      => ['nullable', 'integer'],
            'status_tanah'      => ['nullable', 'integer'],
            'rumah_lain'        => ['nullable', 'boolean'],
            'tanah_lain'        => ['nullable', 'boolean'],
            'luas_rumah'        => ['nullable', 'numeric', 'min:0'],
            'luas_tanah'        => ['nullable', 'numeric', 'min:0'],
            'kawasan'           => ['nullable', 'integer'],
            'jml_kk'            => ['nullable', 'integer', 'min:1'],
            'jml_penghuni'      => ['nullable', 'integer', 'min:1'],
            'latitude'          => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'         => ['nullable', 'numeric', 'between:-180,180'],

            // Data Struktur & Fisik Bangunan
            'pondasi'           => ['nullable', 'integer', 'in:1,2,3'],
            'kolom'             => ['nullable', 'integer', 'in:1,2,3'],
            'balok'             => ['nullable', 'integer', 'in:1,2,3'],
            'sloof'             => ['nullable', 'integer', 'in:1,2,3'],
            'plafon'            => ['nullable', 'integer', 'in:1,2,3'],
            'kerangka_atap'     => ['nullable', 'integer', 'in:1,2,3'],
            'kondisi_lantai'    => ['nullable', 'integer', 'in:1,2,3'],
            'bahan_lantai'      => ['nullable', 'integer'],
            'kondisi_dinding'   => ['nullable', 'integer', 'in:1,2,3'],
            'bahan_dinding'     => ['nullable', 'integer'],
            'kondisi_atap'      => ['nullable', 'integer', 'in:1,2,3'],
            'bahan_atap'        => ['nullable', 'integer'],

            // Data Utilitas & Sanitasi
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

            // Upload Foto Dokumentasi Tambahan
            'foto_depan'        => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'foto_dalam'        => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'foto_atap'         => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'foto_dinding'      => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'foto_lantai'       => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'foto_mck'          => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        DB::beginTransaction();
        try {
            // 1. Update Hunian
            if ($rumah->hunian) {
                $rumah->hunian->update([
                    'status_rumah'  => $validated['status_rumah'] ?? $rumah->hunian->status_rumah,
                    'status_tanah'  => $validated['status_tanah'] ?? $rumah->hunian->status_tanah,
                    'rumah_lain'    => $request->boolean('rumah_lain'),
                    'tanah_lain'    => $request->boolean('tanah_lain'),
                    'luas_rumah'    => $validated['luas_rumah'] ?? $rumah->hunian->luas_rumah,
                    'luas_tanah'    => $validated['luas_tanah'] ?? $rumah->hunian->luas_tanah,
                    'kawasan'       => $validated['kawasan'] ?? $rumah->hunian->kawasan,
                    'jml_kk'        => $validated['jml_kk'] ?? $rumah->hunian->jml_kk,
                    'jml_penghuni'  => $validated['jml_penghuni'] ?? $rumah->hunian->jml_penghuni,
                    'latitude'      => $validated['latitude'] ?? $rumah->hunian->latitude,
                    'longitude'     => $validated['longitude'] ?? $rumah->hunian->longitude,
                ]);
            }

            // 2. Update Rumah
            $rumah->update([
                'pondasi'         => $validated['pondasi'] ?? $rumah->pondasi,
                'kolom'           => $validated['kolom'] ?? $rumah->kolom,
                'balok'           => $validated['balok'] ?? $rumah->balok,
                'sloof'           => $validated['sloof'] ?? $rumah->sloof,
                'plafon'          => $validated['plafon'] ?? $rumah->plafon,
                'kerangka_atap'   => $validated['kerangka_atap'] ?? $rumah->kerangka_atap,
                'kondisi_lantai'  => $validated['kondisi_lantai'] ?? $rumah->kondisi_lantai,
                'bahan_lantai'    => $validated['bahan_lantai'] ?? $rumah->bahan_lantai,
                'kondisi_dinding' => $validated['kondisi_dinding'] ?? $rumah->kondisi_dinding,
                'bahan_dinding'   => $validated['bahan_dinding'] ?? $rumah->bahan_dinding,
                'kondisi_atap'    => $validated['kondisi_atap'] ?? $rumah->kondisi_atap,
                'bahan_atap'      => $validated['bahan_atap'] ?? $rumah->bahan_atap,
            ]);

            // 3. Update atau Create Utilitas
            $utilitasData = [
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
            ];

            if ($rumah->Utilitas()->exists()) {
                $rumah->Utilitas()->first()->update($utilitasData);
            } else {
                $rumah->Utilitas()->create($utilitasData);
            }

            // 4. Update Foto-foto jika ada yang diunggah ulang
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
                    // Cari foto lama dengan jenis yang sama untuk diganti
                    $oldFoto = DokumenRumah::where('id_rumah', $rumah->id_rumah)
                        ->where('jenis_foto', $jenisFoto)
                        ->first();

                    if ($oldFoto && Storage::disk('public')->exists($oldFoto->path_foto)) {
                        Storage::disk('public')->delete($oldFoto->path_foto);
                    }

                    $path = $request->file($inputName)->store('dokumen_rumah', 'public');

                    DokumenRumah::updateOrCreate(
                        ['id_rumah' => $rumah->id_rumah, 'jenis_foto' => $jenisFoto],
                        ['path_foto' => $path]
                    );
                }
            }

            DB::commit();

            return redirect()->route('rumah.index')
                ->with('success', 'Data survei rumah berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data survei rumah beserta utilitas dan foto dokumentasinya.
     */
    public function destroy(string $id)
    {
        $rumah = Rumah::with(['DokumenRumah', 'Utilitas', 'hunian'])->findOrFail($id);

        DB::beginTransaction();
        try {
            // Hapus berkas foto fisik dari storage
            foreach ($rumah->DokumenRumah as $doc) {
                if (Storage::disk('public')->exists($doc->path_foto)) {
                    Storage::disk('public')->delete($doc->path_foto);
                }
                $doc->delete();
            }

            // Hapus data utilitas
            $rumah->Utilitas()->delete();

            // Hapus data rumah
            $hunian = $rumah->hunian;
            $rumah->delete();

            // Jika hunian tidak memiliki data rumah lain, hapus hunian terkait
            if ($hunian && $hunian->Rumah()->count() === 0) {
                $hunian->delete();
            }

            DB::commit();

            return redirect()->route('rumah.index')
                ->with('success', 'Data survei kondisi rumah berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
