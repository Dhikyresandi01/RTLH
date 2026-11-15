<?php

namespace App\Http\Controllers;

use App\Models\ProgramBantuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramBantuanController extends Controller
{
    /**
     * Tampilkan daftar semua Jenis Bantuan.
     * Hanya Superadmin yang dapat mengakses.
     */
    public function index()
    {
        if (!Auth::user()->isSuperadmin()) {
            abort(403, 'Akses ditolak. Hanya Superadmin yang dapat mengakses halaman ini.');
        }

        $programs = ProgramBantuan::orderBy('nama_program')->get();

        return view('pages.program-bantuan.index', compact('programs'));
    }

    /**
     * Simpan Jenis Bantuan baru ke database.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isSuperadmin()) {
            abort(403);
        }

        $request->validate([
            'nama_program' => ['required', 'string', 'max:150', 'unique:program_bantuan,nama_program'],
            'is_aktif'     => ['nullable', 'boolean'],
        ], [
            'nama_program.required' => 'Nama jenis bantuan wajib diisi.',
            'nama_program.unique'   => 'Nama jenis bantuan sudah terdaftar.',
            'nama_program.max'      => 'Nama jenis bantuan maksimal 150 karakter.',
        ]);

        try {
            ProgramBantuan::create([
                'nama_program' => $request->nama_program,
                'is_aktif'     => $request->boolean('is_aktif', true),
            ]);

            return back()->with('success', 'Jenis bantuan "' . $request->nama_program . '" berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan jenis bantuan: ' . $e->getMessage());
        }
    }

    /**
     * Perbarui data Jenis Bantuan yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->isSuperadmin()) {
            abort(403);
        }

        $program = ProgramBantuan::findOrFail($id);

        $request->validate([
            'nama_program' => ['required', 'string', 'max:150', 'unique:program_bantuan,nama_program,' . $id . ',id_program'],
            'is_aktif'     => ['nullable', 'boolean'],
        ], [
            'nama_program.required' => 'Nama jenis bantuan wajib diisi.',
            'nama_program.unique'   => 'Nama jenis bantuan sudah terdaftar.',
        ]);

        try {
            $program->update([
                'nama_program' => $request->nama_program,
                'is_aktif'     => $request->boolean('is_aktif', true),
            ]);

            return back()->with('success', 'Jenis bantuan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui jenis bantuan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus Jenis Bantuan.
     * Tidak dapat dihapus jika sudah digunakan pada data bantuan.
     */
    public function destroy($id)
    {
        if (!Auth::user()->isSuperadmin()) {
            abort(403);
        }

        $program = ProgramBantuan::findOrFail($id);

        if ($program->Bantuan()->count() > 0) {
            return back()->with('error', 'Jenis bantuan tidak dapat dihapus karena sudah digunakan pada ' . $program->Bantuan()->count() . ' data bantuan warga.');
        }

        try {
            $program->delete();
            return back()->with('success', 'Jenis bantuan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus jenis bantuan: ' . $e->getMessage());
        }
    }
}
