<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar pengguna sistem E-RTLH beserta filter dan statistik ringkas.
     * Hanya dapat diakses oleh Superadmin.
     */
    public function index(Request $request)
    {
        $query = User::with('wilayah');

        // Filter Pencarian (Nama, Email, Jabatan)
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%")
                  ->orWhereHas('wilayah', function ($w) use ($search) {
                      $w->where('desa', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Berdasarkan Role
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // Filter Berdasarkan Wilayah Kelurahan
        if ($request->filled('id_wilayah')) {
            $query->where('id_wilayah', $request->input('id_wilayah'));
        }

        // Statistik Cepat Pengguna
        $totalUsers = User::count();
        $totalSuperadmin = User::where('role', 'superadmin')->count();
        $totalDinas = User::where('role', 'admin_dinas')->count();
        $totalKelurahan = User::where('role', 'admin_kelurahan')->count();
        $totalKelurahanAktif = User::where('role', 'admin_kelurahan')->whereNotNull('id_wilayah')->distinct('id_wilayah')->count('id_wilayah');
        $totalSemuaKelurahan = Wilayah::count();

        $users = $query->orderByRaw("FIELD(role, 'superadmin', 'admin_dinas', 'admin_kelurahan')")
                       ->orderBy('name')
                       ->paginate(15)
                       ->withQueryString();

        $wilayahs = Wilayah::orderBy('desa')->get();

        return view('pages.users.index', compact(
            'users',
            'wilayahs',
            'totalUsers',
            'totalSuperadmin',
            'totalDinas',
            'totalKelurahan',
            'totalKelurahanAktif',
            'totalSemuaKelurahan'
        ));
    }

    /**
     * Menyimpan data pengguna baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'   => ['required', 'string', 'min:6', 'confirmed'],
            'role'       => ['required', 'in:superadmin,admin_dinas,admin_kelurahan'],
            'jabatan'    => ['nullable', 'string', 'max:100'],
            'id_wilayah' => ['nullable', 'required_if:role,admin_kelurahan', 'exists:wilayah,id_wilayah'],
        ], [
            'name.required'             => 'Nama lengkap pengguna wajib diisi.',
            'email.required'            => 'Alamat email pengguna wajib diisi.',
            'email.email'               => 'Format email tidak valid.',
            'email.unique'              => 'Alamat email ini sudah terdaftar dalam sistem.',
            'password.required'         => 'Kata sandi wajib diisi.',
            'password.min'              => 'Kata sandi minimal 6 karakter.',
            'password.confirmed'        => 'Konfirmasi kata sandi tidak cocok.',
            'role.required'             => 'Peran / Role pengguna wajib dipilih.',
            'role.in'                   => 'Pilihan peran pengguna tidak sah.',
            'id_wilayah.required_if'    => 'Wilayah Kelurahan binaan wajib dipilih untuk Admin Kelurahan.',
            'id_wilayah.exists'         => 'Wilayah kelurahan yang dipilih tidak valid.',
        ]);

        // Jika bukan admin kelurahan, wilayah diset null
        $idWilayah = ($validated['role'] === 'admin_kelurahan') ? $validated['id_wilayah'] : null;

        User::create([
            'name'       => $validated['name'],
            'email'      => strtolower(trim($validated['email'])),
            'password'   => Hash::make($validated['password']),
            'role'       => $validated['role'],
            'jabatan'    => $validated['jabatan'] ?? null,
            'id_wilayah' => $idWilayah,
        ]);

        return redirect()->route('users.index')->with('success', "Pengguna {$validated['name']} berhasil ditambahkan.");
    }

    /**
     * Memperbarui informasi profil pengguna dan peran.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password'   => ['nullable', 'string', 'min:6', 'confirmed'],
            'role'       => ['required', 'in:superadmin,admin_dinas,admin_kelurahan'],
            'jabatan'    => ['nullable', 'string', 'max:100'],
            'id_wilayah' => ['nullable', 'required_if:role,admin_kelurahan', 'exists:wilayah,id_wilayah'],
        ], [
            'name.required'          => 'Nama lengkap pengguna wajib diisi.',
            'email.required'         => 'Alamat email pengguna wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Alamat email ini sudah digunakan oleh akun lain.',
            'password.min'           => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed'     => 'Konfirmasi kata sandi baru tidak cocok.',
            'role.required'          => 'Peran / Role pengguna wajib dipilih.',
            'id_wilayah.required_if' => 'Wilayah Kelurahan binaan wajib dipilih untuk Admin Kelurahan.',
            'id_wilayah.exists'      => 'Wilayah kelurahan yang dipilih tidak valid.',
        ]);

        // Proteksi: Superadmin terakhir tidak boleh mengubah rolenya sendiri menjadi non-superadmin
        if ($user->id === Auth::id() && $validated['role'] !== 'superadmin') {
            $superadminCount = User::where('role', 'superadmin')->count();
            if ($superadminCount <= 1) {
                return back()->with('error', 'Gagal mengubah peran: Anda adalah satu-satunya Super Admin aktif di sistem.');
            }
        }

        $idWilayah = ($validated['role'] === 'admin_kelurahan') ? $validated['id_wilayah'] : null;

        $updateData = [
            'name'       => $validated['name'],
            'email'      => strtolower(trim($validated['email'])),
            'role'       => $validated['role'],
            'jabatan'    => $validated['jabatan'] ?? null,
            'id_wilayah' => $idWilayah,
        ];

        // Jika password diisi, update password baru
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('users.index')->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    /**
     * Mereset kata sandi pengguna langsung oleh Superadmin.
     */
    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'password.required'  => 'Kata sandi baru wajib diisi.',
            'password.min'       => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('users.index')->with('success', "Kata sandi untuk pengguna {$user->name} berhasil direset.");
    }

    /**
     * Menghapus akun pengguna dari sistem.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Proteksi 1: Tidak boleh menghapus akun diri sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Akses Ditolak: Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Proteksi 2: Tidak boleh menghapus superadmin jika hanya tersisa 1
        if ($user->isSuperadmin()) {
            $superadminCount = User::where('role', 'superadmin')->count();
            if ($superadminCount <= 1) {
                return back()->with('error', 'Gagal: Tidak dapat menghapus Super Admin terakhir di sistem.');
            }
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')->with('success', "Akun pengguna {$userName} berhasil dihapus dari sistem.");
    }
}
