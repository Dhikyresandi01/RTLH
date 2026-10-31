<?php

namespace App\Http\Controllers;

use App\Models\Hunian;
use App\Models\Warga;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HunianController extends Controller
{
    /**
     * Menampilkan daftar data kepemilikan hunian & lahan calon penerima.
     */
    public function index(Request $request)
    {
        $query = Hunian::with(['warga.wilayah', 'Rumah']);

        // Batasi akses jika user adalah admin kelurahan
        if (Auth::check() && !Auth::user()->isSuperadmin() && !Auth::user()->isAdminDinas()) {
            $userWilayah = Auth::user()->id_wilayah;
            $query->whereHas('warga', function ($q) use ($userWilayah) {
                $q->where('id_wilayah', $userWilayah);
            });
        }

        // Pencarian (Nama Warga, NIK, atau Alamat)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('warga', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        // Filter Wilayah / Kelurahan
        if ($request->filled('id_wilayah')) {
            $idWilayah = $request->input('id_wilayah');
            $query->whereHas('warga', function ($q) use ($idWilayah) {
                $q->where('id_wilayah', $idWilayah);
            });
        }

        // Filter Status Kepemilikan Rumah (1=Milik Sendiri, 2=Sewa, 3=Menumpang)
        if ($request->filled('status_rumah')) {
            $query->where('status_rumah', $request->input('status_rumah'));
        }

        // Filter Status Hak Atas Tanah (1=Milik Sendiri/SHM, 2=Bukan Milik Sendiri/Girik/Letter C, 3=Tanah Negara)
        if ($request->filled('status_tanah')) {
            $query->where('status_tanah', $request->input('status_tanah'));
        }

        $hunians = $query->latest('id_hunian')->paginate(10)->withQueryString();
        $wilayahs = Wilayah::orderBy('desa')->get();

        return view('pages.hunian.index', compact('hunians', 'wilayahs'));
    }

    /**
     * Menampilkan formulir tambah data hunian.
     */
    public function create(Request $request)
    {
        $wargaQuery = Warga::with('wilayah');

        if (Auth::check()) {
            $wargaQuery->visibleTo(Auth::user());
        }

        $wargas = $wargaQuery->orderBy('nama')->get();

        $selectedWarga = null;
        if ($request->filled('id_warga')) {
            $selectedWarga = Warga::find($request->input('id_warga'));
        }

        return view('pages.hunian.create', compact('wargas', 'selectedWarga'));
    }

    /**
     * Menyimpan data hunian baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_warga'      => ['required', 'exists:warga,id_warga'],
            'status_rumah'  => ['required', 'integer', 'in:1,2,3'],
            'status_tanah'  => ['required', 'integer', 'in:1,2,3'],
            'rumah_lain'    => ['nullable', 'boolean'],
            'tanah_lain'    => ['nullable', 'boolean'],
            'luas_rumah'    => ['nullable', 'numeric', 'min:0'],
            'luas_tanah'    => ['nullable', 'numeric', 'min:0'],
            'kawasan'       => ['nullable', 'integer', 'in:1,2,3,4'],
            'jml_kk'        => ['nullable', 'integer', 'min:1'],
            'jml_penghuni'  => ['nullable', 'integer', 'min:1'],
            'latitude'      => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'     => ['nullable', 'numeric', 'between:-180,180'],
        ], [
            'id_warga.required'     => 'Calon penerima (warga) wajib dipilih.',
            'id_warga.exists'       => 'Data warga yang dipilih tidak ditemukan.',
            'status_rumah.required' => 'Status kepemilikan rumah wajib dipilih.',
            'status_tanah.required' => 'Status hak atas tanah wajib dipilih.',
        ]);

        $validated['rumah_lain'] = $request->boolean('rumah_lain');
        $validated['tanah_lain'] = $request->boolean('tanah_lain');
        $validated['jml_kk'] = $validated['jml_kk'] ?? 1;
        $validated['jml_penghuni'] = $validated['jml_penghuni'] ?? 1;

        Hunian::create($validated);

        return redirect()->route('hunian.index')
            ->with('success', 'Data hunian berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail hunian beserta data fisik rumah yang terkait.
     */
    public function show(string $id)
    {
        $hunian = Hunian::with(['warga.wilayah', 'Rumah.Utilitas', 'Rumah.DokumenRumah'])
            ->findOrFail($id);

        return view('pages.hunian.show', compact('hunian'));
    }

    /**
     * Menampilkan formulir edit data hunian.
     */
    public function edit(string $id)
    {
        $hunian = Hunian::with('warga')->findOrFail($id);

        $wargaQuery = Warga::with('wilayah');
        if (Auth::check()) {
            $wargaQuery->visibleTo(Auth::user());
        }
        $wargas = $wargaQuery->orderBy('nama')->get();

        return view('pages.hunian.edit', compact('hunian', 'wargas'));
    }

    /**
     * Menyimpan pembaruan data hunian ke database.
     */
    public function update(Request $request, string $id)
    {
        $hunian = Hunian::findOrFail($id);

        $validated = $request->validate([
            'id_warga'      => ['required', 'exists:warga,id_warga'],
            'status_rumah'  => ['required', 'integer', 'in:1,2,3'],
            'status_tanah'  => ['required', 'integer', 'in:1,2,3'],
            'rumah_lain'    => ['nullable', 'boolean'],
            'tanah_lain'    => ['nullable', 'boolean'],
            'luas_rumah'    => ['nullable', 'numeric', 'min:0'],
            'luas_tanah'    => ['nullable', 'numeric', 'min:0'],
            'kawasan'       => ['nullable', 'integer', 'in:1,2,3,4'],
            'jml_kk'        => ['nullable', 'integer', 'min:1'],
            'jml_penghuni'  => ['nullable', 'integer', 'min:1'],
            'latitude'      => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'     => ['nullable', 'numeric', 'between:-180,180'],
        ], [
            'id_warga.required'     => 'Calon penerima (warga) wajib dipilih.',
            'status_rumah.required' => 'Status kepemilikan rumah wajib dipilih.',
            'status_tanah.required' => 'Status hak atas tanah wajib dipilih.',
        ]);

        $validated['rumah_lain'] = $request->boolean('rumah_lain');
        $validated['tanah_lain'] = $request->boolean('tanah_lain');

        $hunian->update($validated);

        return redirect()->route('hunian.index')
            ->with('success', 'Data hunian berhasil diperbarui.');
    }

    /**
     * Menghapus data hunian dari database.
     */
    public function destroy(string $id)
    {
        $hunian = Hunian::with('Rumah.DokumenRumah')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Hapus berkas foto fisik jika hunian ini memiliki relasi ke rumah
            foreach ($hunian->Rumah as $rumah) {
                foreach ($rumah->DokumenRumah as $doc) {
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($doc->path_foto)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($doc->path_foto);
                    }
                }
            }

            $hunian->delete();

            DB::commit();

            return redirect()->route('hunian.index')
                ->with('success', 'Data hunian berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus data hunian: ' . $e->getMessage());
        }
    }
}
