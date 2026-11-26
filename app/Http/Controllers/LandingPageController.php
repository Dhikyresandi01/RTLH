<?php

namespace App\Http\Controllers;

use App\Models\Bantuan;
use App\Models\LandingSetting;
use App\Models\ProgramBantuan;
use App\Models\Warga;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LandingPageController extends Controller
{
    /**
     * 1. Menampilkan Landing Page Publik (welcome.blade.php)
     */
    public function index()
    {
        // Ambil konfigurasi konten dari database / fallback default
        $heroSlides       = LandingSetting::get('hero_slides');
        $profil           = LandingSetting::get('profil');
        $kontak           = LandingSetting::get('kontak');
        $faqItems         = LandingSetting::get('faq_items');
        $publikasi        = LandingSetting::get('publikasi');
        $beritaList       = LandingSetting::get('berita_list');
        if (empty($beritaList) || !is_array($beritaList)) {
            $beritaList = LandingSetting::getDefault('berita_list') ?: [];
        }
        $realisasiGallery = LandingSetting::get('realisasi_gallery');
        $statistikMode    = LandingSetting::get('statistik_mode', 'auto');
        $statistikManual  = LandingSetting::get('statistik_manual');

        // Program Bantuan (ambil dari DB jika ada, gabungkan dengan skema)
        $dbPrograms = ProgramBantuan::where('is_aktif', true)->get();

        // Rekapitulasi Data RTLH per Kelurahan (46 Kelurahan se-Kota Kediri)
        $wilayahList = Wilayah::orderBy('kecamatan')->orderBy('desa')->get();

        // Agregat per Wilayah dari data Bantuan riil
        $bantuanByWilayah = Bantuan::aktif()
            ->join('warga', 'bantuan.id_warga', '=', 'warga.id_warga')
            ->select(
                'warga.id_wilayah',
                DB::raw('COUNT(*) as total_usulan'),
                DB::raw('SUM(CASE WHEN bantuan.status_bantuan >= 1 AND bantuan.status_bantuan != 4 THEN 1 ELSE 0 END) as terverifikasi'),
                DB::raw('SUM(CASE WHEN bantuan.status_bantuan IN (2, 5) THEN 1 ELSE 0 END) as ditetapkan'),
                DB::raw('SUM(CASE WHEN bantuan.status_bantuan = 5 THEN 1 ELSE 0 END) as realisasi_count')
            )
            ->groupBy('warga.id_wilayah')
            ->get()
            ->keyBy('id_wilayah');

        // Buat koleksi data kelurahan lengkap
        $kelurahanData = $wilayahList->map(function ($w) use ($bantuanByWilayah) {
            $stat = $bantuanByWilayah->get($w->id_wilayah);
            $totalUsulan = $stat ? (int) $stat->total_usulan : 0;
            $verif       = $stat ? (int) $stat->terverifikasi : 0;
            $tetap       = $stat ? (int) $stat->ditetapkan : 0;
            $realisasi   = $stat ? (int) $stat->realisasi_count : 0;
            
            // Perkiraan pengerjaan vs selesai
            $selesai = $realisasi;
            $proses  = max(0, $tetap - $selesai);

            return [
                'id_wilayah' => $w->id_wilayah,
                'kec'        => $w->kecamatan,
                'kel'        => $w->desa,
                'usulan'     => $totalUsulan,
                'verif'      => $verif,
                'tetap'      => $tetap,
                'proses'     => $proses,
                'selesai'    => $selesai,
            ];
        });

        // Hitung Indikator Statistik Capaian
        if ($statistikMode === 'manual' && !empty($statistikManual)) {
            $stats = $statistikManual;
        } else {
            $totalUsulanDb = $kelurahanData->sum('usulan');
            // Jika DB masih memiliki data sampel sedikit, gunakan angka simulasi resmi/nyata proporsional
            if ($totalUsulanDb > 0) {
                $stats = [
                    'total_usulan'  => $kelurahanData->sum('usulan'),
                    'terverifikasi' => $kelurahanData->sum('verif'),
                    'ditetapkan'    => $kelurahanData->sum('tetap'),
                    'proses'        => $kelurahanData->sum('proses'),
                    'selesai'       => $kelurahanData->sum('selesai'),
                ];
            } else {
                $stats = LandingSetting::getDefault('statistik_manual');
            }
        }

        // Statistik Agregat per Kecamatan untuk Peta Spasial
        $districtStats = [
            'Mojoroto' => [
                'name'    => 'Mojoroto',
                'kel'     => $kelurahanData->where('kec', 'Mojoroto')->count() . ' Kelurahan',
                'usulan'  => number_format($kelurahanData->where('kec', 'Mojoroto')->sum('usulan') ?: 1084, 0, ',', '.') . ' Unit',
                'proses'  => number_format($kelurahanData->where('kec', 'Mojoroto')->sum('proses') ?: 124, 0, ',', '.') . ' Unit',
                'selesai' => number_format($kelurahanData->where('kec', 'Mojoroto')->sum('selesai') ?: 512, 0, ',', '.') . ' Unit',
            ],
            'Kota' => [
                'name'    => 'Kota',
                'kel'     => $kelurahanData->where('kec', 'Kota')->count() . ' Kelurahan',
                'usulan'  => number_format($kelurahanData->where('kec', 'Kota')->sum('usulan') ?: 842, 0, ',', '.') . ' Unit',
                'proses'  => number_format($kelurahanData->where('kec', 'Kota')->sum('proses') ?: 92, 0, ',', '.') . ' Unit',
                'selesai' => number_format($kelurahanData->where('kec', 'Kota')->sum('selesai') ?: 418, 0, ',', '.') . ' Unit',
            ],
            'Pesantren' => [
                'name'    => 'Pesantren',
                'kel'     => $kelurahanData->where('kec', 'Pesantren')->count() . ' Kelurahan',
                'usulan'  => number_format($kelurahanData->where('kec', 'Pesantren')->sum('usulan') ?: 920, 0, ',', '.') . ' Unit',
                'proses'  => number_format($kelurahanData->where('kec', 'Pesantren')->sum('proses') ?: 102, 0, ',', '.') . ' Unit',
                'selesai' => number_format($kelurahanData->where('kec', 'Pesantren')->sum('selesai') ?: 397, 0, ',', '.') . ' Unit',
            ],
        ];

        return view('welcome', compact(
            'heroSlides',
            'profil',
            'kontak',
            'faqItems',
            'publikasi',
            'beritaList',
            'realisasiGallery',
            'statistikMode',
            'stats',
            'districtStats',
            'kelurahanData',
            'dbPrograms'
        ));
    }

    /**
     * 2. Menampilkan Panel Kelola Landing Page (Khusus Super Admin)
     */
    public function manage()
    {
        $heroSlides       = LandingSetting::get('hero_slides');
        $profil           = LandingSetting::get('profil');
        $kontak           = LandingSetting::get('kontak');
        $faqItems         = LandingSetting::get('faq_items');
        $publikasi        = LandingSetting::get('publikasi');
        $beritaList       = LandingSetting::get('berita_list');
        if (empty($beritaList) || !is_array($beritaList)) {
            $beritaList = LandingSetting::getDefault('berita_list') ?: [];
        }
        $realisasiGallery = LandingSetting::get('realisasi_gallery');
        $statistikMode    = LandingSetting::get('statistik_mode', 'auto');
        $statistikManual  = LandingSetting::get('statistik_manual');

        return view('pages.landing.manage', compact(
            'heroSlides',
            'profil',
            'kontak',
            'faqItems',
            'publikasi',
            'beritaList',
            'realisasiGallery',
            'statistikMode',
            'statistikManual'
        ));
    }

    /**
     * 3. Update Hero Slider
     */
    public function updateHero(Request $request)
    {
        $request->validate([
            'slide_index'         => 'required|integer|in:0,1',
            'kicker'              => 'required|string|max:100',
            'accent_color'        => 'required|string|in:khaki,gold',
            'title'               => 'required|string|max:255',
            'description'         => 'required|string|max:1000',
            'primary_cta_label'   => 'required|string|max:100',
            'primary_cta_href'    => 'required|string|max:255',
            'secondary_cta_label' => 'nullable|string|max:100',
            'secondary_cta_href'  => 'nullable|string|max:255',
            'image_file'          => 'nullable|image|max:4096',
            'image_url'           => 'nullable|string|max:1000',
        ]);

        $slides = LandingSetting::get('hero_slides');
        $idx    = (int) $request->slide_index;

        if (!isset($slides[$idx])) {
            $slides[$idx] = [
                'id' => $idx + 1,
            ];
        }

        $imageUrl = $slides[$idx]['image_url'] ?? '';
        if ($request->hasFile('image_file')) {
            $path     = $request->file('image_file')->store('landing', 'public');
            $imageUrl = Storage::url($path);
        } elseif ($request->filled('image_url')) {
            $imageUrl = $request->image_url;
        }

        $slides[$idx] = [
            'id'                  => $idx + 1,
            'kicker'              => $request->kicker,
            'accent_color'        => $request->accent_color,
            'title'               => $request->title,
            'description'         => $request->description,
            'image_url'           => $imageUrl,
            'image_alt'           => $request->title,
            'primary_cta_label'   => $request->primary_cta_label,
            'primary_cta_href'    => $request->primary_cta_href,
            'secondary_cta_label' => $request->secondary_cta_label,
            'secondary_cta_href'  => $request->secondary_cta_href,
        ];

        LandingSetting::set('hero_slides', $slides, 'hero');

        return back()->with('success', "Slide Hero " . ($idx + 1) . " berhasil diperbarui.");
    }

    /**
     * 4. Update Profil & Kontak DPKP
     */
    public function updateProfilKontak(Request $request)
    {
        $request->validate([
            // Profil
            'profil_kicker'            => 'required|string|max:100',
            'profil_title'             => 'required|string|max:255',
            'profil_p1'                => 'required|string|max:2000',
            'profil_p2'                => 'required|string|max:2000',
            'highlight_1_title'        => 'required|string|max:150',
            'highlight_1_desc'         => 'required|string|max:255',
            'highlight_2_title'        => 'required|string|max:150',
            'highlight_2_desc'         => 'required|string|max:255',
            'profil_image_caption'     => 'nullable|string|max:255',
            'profil_image_file'        => 'nullable|image|max:4096',
            'profil_image_url'         => 'nullable|string|max:1000',

            // Kontak
            'nama_dinas'               => 'required|string|max:255',
            'alamat'                   => 'required|string|max:500',
            'telepon'                  => 'required|string|max:50',
            'email'                    => 'required|email|max:100',
            'jam_layanan'              => 'required|string|max:100',
        ]);

        $profil = LandingSetting::get('profil');

        $profilImageUrl = $profil['image_url'] ?? '';
        if ($request->hasFile('profil_image_file')) {
            $path           = $request->file('profil_image_file')->store('landing', 'public');
            $profilImageUrl = Storage::url($path);
        } elseif ($request->filled('profil_image_url')) {
            $profilImageUrl = $request->profil_image_url;
        }

        $newProfil = [
            'kicker'            => $request->profil_kicker,
            'title'             => $request->profil_title,
            'p1'                => $request->profil_p1,
            'p2'                => $request->profil_p2,
            'highlight_1_title' => $request->highlight_1_title,
            'highlight_1_desc'  => $request->highlight_1_desc,
            'highlight_2_title' => $request->highlight_2_title,
            'highlight_2_desc'  => $request->highlight_2_desc,
            'image_url'         => $profilImageUrl,
            'image_caption'     => $request->profil_image_caption ?: ($profil['image_caption'] ?? ''),
        ];

        $newKontak = [
            'nama_dinas'  => $request->nama_dinas,
            'alamat'      => $request->alamat,
            'telepon'     => $request->telepon,
            'email'       => $request->email,
            'jam_layanan' => $request->jam_layanan,
        ];

        LandingSetting::set('profil', $newProfil, 'profil');
        LandingSetting::set('kontak', $newKontak, 'kontak');

        return back()->with('success', 'Informasi Profil dan Kontak DPKP berhasil diperbarui.');
    }

    /**
     * 5. Update Pengaturan Statistik (Auto vs Manual)
     */
    public function updateStatistik(Request $request)
    {
        $request->validate([
            'statistik_mode' => 'required|in:auto,manual',
            'total_usulan'   => 'nullable|numeric|min:0',
            'terverifikasi'  => 'nullable|numeric|min:0',
            'ditetapkan'     => 'nullable|numeric|min:0',
            'proses'         => 'nullable|numeric|min:0',
            'selesai'        => 'nullable|numeric|min:0',
        ]);

        LandingSetting::set('statistik_mode', $request->statistik_mode, 'statistik');

        if ($request->statistik_mode === 'manual') {
            $manualData = [
                'total_usulan'  => (int) $request->total_usulan,
                'terverifikasi' => (int) $request->terverifikasi,
                'ditetapkan'    => (int) $request->ditetapkan,
                'proses'        => (int) $request->proses,
                'selesai'       => (int) $request->selesai,
            ];
            LandingSetting::set('statistik_manual', $manualData, 'statistik');
        }

        return back()->with('success', 'Pengaturan statistik penanganan RTLH berhasil diperbarui.');
    }

    /**
     * 6. Kelola FAQ: Simpan FAQ Baru
     */
    public function storeFaq(Request $request)
    {
        $request->validate([
            'tanya' => 'required|string|max:255',
            'jawab' => 'required|string|max:2000',
        ]);

        $faqList = LandingSetting::get('faq_items') ?: [];
        $newId   = count($faqList) > 0 ? (max(array_column($faqList, 'id')) + 1) : 1;

        $faqList[] = [
            'id'    => $newId,
            'tanya' => $request->tanya,
            'jawab' => $request->jawab,
        ];

        LandingSetting::set('faq_items', $faqList, 'faq');

        return back()->with('success', 'Pertanyaan FAQ baru berhasil ditambahkan.');
    }

    /**
     * Kelola FAQ: Hapus FAQ
     */
    public function deleteFaq($id)
    {
        $faqList = LandingSetting::get('faq_items') ?: [];
        $faqList = array_values(array_filter($faqList, fn($item) => (string) ($item['id'] ?? '') !== (string) $id));

        LandingSetting::set('faq_items', $faqList, 'faq');

        return back()->with('success', 'Pertanyaan FAQ berhasil dihapus.');
    }

    /**
     * 7. Kelola Berita & Publikasi
     */
    public function updatePublikasi(Request $request)
    {
        $request->validate([
            'berita_judul'     => 'required|string|max:255',
            'berita_tanggal'   => 'required|string|max:100',
            'berita_ringkasan' => 'required|string|max:1000',
            'berita_image'     => 'nullable|image|max:4096',
            'berita_image_url' => 'nullable|string|max:1000',
            'pengumuman_1_judul' => 'nullable|string|max:255',
            'pengumuman_1_tgl'   => 'nullable|string|max:100',
            'pengumuman_2_judul' => 'nullable|string|max:255',
            'pengumuman_2_tgl'   => 'nullable|string|max:100',
        ]);

        $publikasi = LandingSetting::get('publikasi');
        $imgUrl    = $publikasi['berita_utama']['image_url'] ?? '';

        if ($request->hasFile('berita_image')) {
            $path   = $request->file('berita_image')->store('landing', 'public');
            $imgUrl = Storage::url($path);
        } elseif ($request->filled('berita_image_url')) {
            $imgUrl = $request->berita_image_url;
        }

        $daftarPengumuman = [
            [
                'tipe'    => 'Pengumuman',
                'tanggal' => $request->pengumuman_1_tgl ?: '10 November 2026',
                'judul'   => $request->pengumuman_1_judul ?: 'Jadwal Sosialisasi Mekanisme Pengusulan BSPS Tahun Anggaran 2027',
                'link'    => '#',
            ],
            [
                'tipe'    => 'Laporan Publik',
                'tanggal' => $request->pengumuman_2_tgl ?: 'PDF 2,4 MB',
                'judul'   => $request->pengumuman_2_judul ?: 'Laporan Realisasi Penanganan RTLH Kota Kediri Triwulan III 2026',
                'link'    => '#',
            ],
        ];

        $newPublikasi = [
            'berita_utama' => [
                'kategori'  => 'Berita',
                'tanggal'   => $request->berita_tanggal,
                'judul'     => $request->berita_judul,
                'ringkasan' => $request->berita_ringkasan,
                'image_url' => $imgUrl,
            ],
            'daftar_pengumuman' => $daftarPengumuman,
        ];

        LandingSetting::set('publikasi', $newPublikasi, 'publikasi');

        return back()->with('success', 'Publikasi dan Berita berhasil diperbarui.');
    }

    /**
     * Tambah Artikel Berita Baru
     */
    public function storeBerita(Request $request)
    {
        $request->validate([
            'judul'      => 'required|string|max:255',
            'kategori'   => 'required|string|max:100',
            'tanggal'    => 'required|string|max:100',
            'ringkasan'  => 'required|string|max:1000',
            'isi'        => 'nullable|string|max:10000',
            'image_file' => 'nullable|image|max:4096',
            'image_url'  => 'nullable|string|max:1000',
        ]);

        $beritaList = LandingSetting::get('berita_list');
        if (empty($beritaList) || !is_array($beritaList)) {
            $beritaList = LandingSetting::getDefault('berita_list') ?: [];
        }

        $imgUrl = '/assets/images/samples/building.jpg';
        if ($request->hasFile('image_file')) {
            $path   = $request->file('image_file')->store('landing', 'public');
            $imgUrl = Storage::url($path);
        } elseif ($request->filled('image_url')) {
            $imgUrl = $request->image_url;
        }

        $newId = count($beritaList) > 0 ? (max(array_column($beritaList, 'id')) + 1) : 1;

        $newBerita = [
            'id'        => $newId,
            'judul'     => $request->judul,
            'kategori'  => $request->kategori,
            'tanggal'   => $request->tanggal,
            'ringkasan' => $request->ringkasan,
            'isi'       => $request->isi ?: $request->ringkasan,
            'image_url' => $imgUrl,
        ];

        // Tempatkan berita terbaru di urutan paling atas
        array_unshift($beritaList, $newBerita);

        LandingSetting::set('berita_list', $beritaList, 'berita');

        return back()->with('success', 'Berita baru "' . $request->judul . '" berhasil ditambahkan.');
    }

    /**
     * Perbarui Artikel Berita
     */
    public function updateBerita(Request $request, $id)
    {
        $request->validate([
            'judul'      => 'required|string|max:255',
            'kategori'   => 'required|string|max:100',
            'tanggal'    => 'required|string|max:100',
            'ringkasan'  => 'required|string|max:1000',
            'isi'        => 'nullable|string|max:10000',
            'image_file' => 'nullable|image|max:4096',
            'image_url'  => 'nullable|string|max:1000',
        ]);

        $beritaList = LandingSetting::get('berita_list');
        if (empty($beritaList) || !is_array($beritaList)) {
            $beritaList = LandingSetting::getDefault('berita_list') ?: [];
        }

        $found = false;
        foreach ($beritaList as &$b) {
            if ((string) ($b['id'] ?? '') === (string) $id) {
                $imgUrl = $b['image_url'] ?? '/assets/images/samples/building.jpg';
                if ($request->hasFile('image_file')) {
                    $path   = $request->file('image_file')->store('landing', 'public');
                    $imgUrl = Storage::url($path);
                } elseif ($request->filled('image_url')) {
                    $imgUrl = $request->image_url;
                }

                $b['judul']     = $request->judul;
                $b['kategori']  = $request->kategori;
                $b['tanggal']   = $request->tanggal;
                $b['ringkasan'] = $request->ringkasan;
                $b['isi']       = $request->isi ?: $request->ringkasan;
                $b['image_url'] = $imgUrl;
                $found = true;
                break;
            }
        }

        if ($found) {
            LandingSetting::set('berita_list', $beritaList, 'berita');
            return back()->with('success', 'Berita berhasil diperbarui.');
        }

        return back()->with('error', 'Berita tidak ditemukan.');
    }

    /**
     * Hapus Artikel Berita
     */
    public function deleteBerita($id)
    {
        $beritaList = LandingSetting::get('berita_list');
        if (empty($beritaList) || !is_array($beritaList)) {
            $beritaList = LandingSetting::getDefault('berita_list') ?: [];
        }

        $beritaList = array_values(array_filter($beritaList, fn($b) => (string) ($b['id'] ?? '') !== (string) $id));

        LandingSetting::set('berita_list', $beritaList, 'berita');

        return back()->with('success', 'Berita berhasil dihapus.');
    }

    /**
     * 8. Update Galeri Realisasi Before-After
     */
    public function updateRealisasi(Request $request)
    {
        $request->validate([
            'lokasi'            => 'required|string|max:255',
            'jenis_bantuan'     => 'required|string|max:255',
            'tahun'             => 'required|string|max:10',
            'before_caption'    => 'required|string|max:255',
            'after_caption'     => 'required|string|max:255',
            'before_image_file' => 'nullable|image|max:4096',
            'after_image_file'  => 'nullable|image|max:4096',
            'before_image_url'  => 'nullable|string|max:1000',
            'after_image_url'   => 'nullable|string|max:1000',
        ]);

        $gallery = LandingSetting::get('realisasi_gallery') ?: [];
        $item    = $gallery[0] ?? [];

        $beforeImg = $item['before_image'] ?? '';
        if ($request->hasFile('before_image_file')) {
            $path      = $request->file('before_image_file')->store('landing', 'public');
            $beforeImg = Storage::url($path);
        } elseif ($request->filled('before_image_url')) {
            $beforeImg = $request->before_image_url;
        }

        $afterImg = $item['after_image'] ?? '';
        if ($request->hasFile('after_image_file')) {
            $path     = $request->file('after_image_file')->store('landing', 'public');
            $afterImg = Storage::url($path);
        } elseif ($request->filled('after_image_url')) {
            $afterImg = $request->after_image_url;
        }

        $gallery[0] = [
            'id'             => 1,
            'lokasi'         => $request->lokasi,
            'jenis_bantuan'  => $request->jenis_bantuan,
            'tahun'          => $request->tahun,
            'before_image'   => $beforeImg,
            'before_caption' => $request->before_caption,
            'after_image'    => $afterImg,
            'after_caption'  => $request->after_caption,
        ];

        LandingSetting::set('realisasi_gallery', $gallery, 'realisasi');

        return back()->with('success', 'Galeri Realisasi Sebelum & Sesudah berhasil diperbarui.');
    }

    /**
     * 9. Reset Section Konten ke Default
     */
    public function resetSection(Request $request)
    {
        $section = $request->input('section');
        $validKeys = ['hero_slides', 'profil', 'kontak', 'faq_items', 'publikasi', 'berita_list', 'realisasi_gallery', 'statistik_manual'];

        if (in_array($section, $validKeys)) {
            $default = LandingSetting::getDefault($section);
            LandingSetting::set($section, $default, 'general');
            return back()->with('success', "Bagian {$section} berhasil dikembalikan ke pengaturan default.");
        }

        return back()->with('error', 'Bagian tidak valid.');
    }
}
