<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingSetting extends Model
{
    use HasFactory;

    protected $table = 'landing_settings';

    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    /**
     * Ambil nilai setting berdasarkan key dengan fallback nilai default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        if (!$setting || $setting->value === null) {
            return $default ?? static::getDefault($key);
        }

        $decoded = json_decode($setting->value, true);
        return (json_last_error() === JSON_ERROR_NONE) ? $decoded : $setting->value;
    }

    /**
     * Simpan atau perbarui nilai setting.
     */
    public static function set(string $key, mixed $value, string $group = 'general'): static
    {
        $encoded = is_array($value) || is_object($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : (string) $value;

        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $encoded, 'group' => $group]
        );
    }

    /**
     * Nilai default awal jika belum ada data di database.
     */
    public static function getDefault(string $key): mixed
    {
        $defaults = [
            'hero_slides' => [
                [
                    'id' => 1,
                    'kicker' => 'Portal Resmi DPKP Kota Kediri',
                    'accent_color' => 'khaki',
                    'title' => 'Mewujudkan Hunian Layak bagi Masyarakat Kota Kediri',
                    'description' => 'Sistem informasi terpadu pendataan, verifikasi teknis, dan transparansi realisasi penanganan Rumah Tidak Layak Huni di tiga kecamatan Kota Kediri.',
                    'image_url' => 'https://lh3.googleusercontent.com/aida/ALJJyrBpy2vGxvnr6ORhcmxYl3R8cd8D7dtYv1Yww2rxPest_AIvb3ZCVIEU0zWNOAtp1vKRKBwqShCLeehO6IrEAVbvMnqrvAhIaSQbUjOUOVtppx5CvZqPbgob9wHExRXOkd2V6ubEQ8Kj7T9_gaXlmbowt8peWMjWVKeCJIRZ2UCXRnQQBLWTPfJlgbNWUNl-NPVNikA0lFr2xavU45N9yaxkQ6SerfpRV_-9mbSeZw_fCssjvFETGcETrp8Vi3Q',
                    'image_alt' => 'Kawasan hunian layak hasil program perbaikan RTLH di Kota Kediri',
                    'primary_cta_label' => 'Lihat Data Usulan',
                    'primary_cta_href' => '#data-rtlh',
                    'secondary_cta_label' => 'Program Bantuan',
                    'secondary_cta_href' => '#program',
                ],
                [
                    'id' => 2,
                    'kicker' => 'Transparansi Verifikasi Lapangan',
                    'accent_color' => 'gold',
                    'title' => 'Pendataan Terukur Berbasis Kondisi Teknis Bangunan',
                    'description' => 'Setiap usulan dari kelurahan diverifikasi langsung oleh tim teknis untuk memastikan bantuan tepat sasaran sesuai standar keselamatan hunian.',
                    'image_url' => 'https://lh3.googleusercontent.com/aida/ALJJyrBFXsDnZHtc_w5DTk_G9e9XlJxkW-gJAWtcY26mAdk3NCoqAvg_DpZgT109PuvggIgTODxJzJEICIXHu46bREesw9dam4LHxLoPsxMh-Qc_E9KIe9TtNAkqtv2Ie2Fi0feDxMVG2_sNcKhu9yn1qck2b5tudYMg7Mg7SF_yIXt8WemEZMB1Z_NbKDrWl0PWtYJLntoIaED2ILEwW1IJ_oRphGfmGr1X9qFERvEUbZthRrtVXsVfbdKUTTH-',
                    'image_alt' => 'Tim verifikasi teknis Dinas Perumahan dan Kawasan Permukiman survei lapangan',
                    'primary_cta_label' => 'Lihat Hasil Realisasi',
                    'primary_cta_href' => '#realisasi',
                    'secondary_cta_label' => null,
                    'secondary_cta_href' => null,
                ],
            ],

            'profil' => [
                'kicker' => 'Tentang E-RTLH Kota Kediri',
                'title' => 'Tata Kelola Penanganan Rumah Tidak Layak Huni yang Akuntabel',
                'p1' => 'E-RTLH (Elektronik Rumah Tidak Layak Huni) adalah sistem informasi resmi yang dikelola oleh Dinas Perumahan dan Kawasan Permukiman Kota Kediri untuk memetakan, memverifikasi, dan memantau peningkatan kualitas hunian masyarakat berpenghasilan rendah.',
                'p2' => 'Melalui portal ini, warga dan pemangku kepentingan dapat menelusuri rekapitulasi usulan per kelurahan, memverifikasi sumber pendanaan bantuan, serta melihat capaian fisik hunian yang telah memenuhi standar keselamatan bangunan, kecukupan ruang, dan sanitasi dasar.',
                'highlight_1_title' => '46 Kelurahan Terintegrasi',
                'highlight_1_desc' => 'Mencakup Kecamatan Mojoroto, Kota, dan Pesantren.',
                'highlight_2_title' => 'Data Terbuka & Terverifikasi',
                'highlight_2_desc' => 'Pembaruan berkala tanpa menampilkan data pribadi sensitif.',
                'image_url' => 'https://lh3.googleusercontent.com/aida/ALJJyrCvX-0BBEXaol7jlAHve-WGZKavRXCeQUMa-I_WQ0TMuxs1jjazIMHYRMBxTHBZg04_PM2d7ZyCfqZKU8nOPNYc7QWMgP4hO22MWKlbQSrtN33XPb16Fyzw94WqTH7ps4IeA0GCz7KGL2RdZir7Hp1RcZI1XTfG1wX0LSzwLHyRSsg4ZL65kWpc5uRPD5IrTcBPIqzYHdjPS0QqHA1MgDu8R59crsNVlAo4yS28xoQPeZjb7JB46OG3YlLECA',
                'image_caption' => 'Dokumentasi penerima manfaat peningkatan kualitas rumah swadaya — Kota Kediri.',
            ],

            'kontak' => [
                'nama_dinas' => 'Dinas Perumahan dan Kawasan Permukiman Kota Kediri',
                'alamat' => 'Jl. Mayor Bismo No. 4, Kota Kediri, Jawa Timur 64121',
                'telepon' => '(0354) 682-145',
                'email' => 'dpkp@kedirikota.go.id',
                'jam_layanan' => 'Senin–Jumat, 08.00–15.30 WIB',
            ],

            'faq_items' => [
                [
                    'id' => 1,
                    'tanya' => 'Bagaimana cara mengusulkan bantuan RTLH?',
                    'jawab' => 'Pengusulan dilakukan melalui kantor kelurahan setempat dengan melampirkan fotokopi KTP, KK, dan bukti kepemilikan lahan yang sah untuk diverifikasi tim DPKP.',
                ],
                [
                    'id' => 2,
                    'tanya' => 'Apa kriteria rumah yang masuk kategori RTLH?',
                    'jawab' => 'Meliputi ketidaksesuaian keselamatan struktur bangunan (atap, lantai, dinding), luas lantai per kapita tidak mencukupi, serta ketiadaan akses air minum dan sanitasi layak.',
                ],
                [
                    'id' => 3,
                    'tanya' => 'Apakah proses pengusulan dipungut biaya?',
                    'jawab' => 'Seluruh tahapan pendataan, verifikasi, hingga penetapan penerima bantuan tidak dipungut biaya apa pun (gratis).',
                ],
            ],

            'publikasi' => [
                'berita_utama' => [
                    'kategori' => 'Berita',
                    'tanggal' => '18 November 2026',
                    'judul' => 'DPKP Kota Kediri Rampungkan Verifikasi Usulan RTLH Tahap Akhir',
                    'ringkasan' => 'Tim fasilitator lapangan bersama perangkat kelurahan menyelesaikan verifikasi teknis untuk penetapan alokasi anggaran tahun berjalan.',
                    'image_url' => 'https://lh3.googleusercontent.com/aida/ALJJyrBmfOndPSII5U2bmNofQCocpQUZKZ82qRbhjYSg-KvzxOFUFYcley1HsL37Kj0eCTpU2uPny6y1pvk-6ICasuG_w2tuiNh0i_Yt-veQQyTq8jEbjjIoKOYXS9b_6p0lilFvUq4FJAsGFGDRvoh05hX80qqXqjr60HaB6nZFP5XYFF_-4Rg-OctIMUIHfghlTxE75YEPP6RN2hWcAb-xgAggW2EcTfpzMHH_nSaAyBDj5AzGDIrhlVTzriYsRw',
                ],
                'daftar_pengumuman' => [
                    [
                        'tipe' => 'Pengumuman',
                        'tanggal' => '10 November 2026',
                        'judul' => 'Jadwal Sosialisasi Mekanisme Pengusulan BSPS Tahun Anggaran 2027',
                        'link' => '#',
                    ],
                    [
                        'tipe' => 'Laporan Publik',
                        'tanggal' => 'PDF 2,4 MB',
                        'judul' => 'Laporan Realisasi Penanganan RTLH Kota Kediri Triwulan III 2026',
                        'link' => '#',
                    ],
                ],
            ],

            'realisasi_gallery' => [
                [
                    'id' => 1,
                    'lokasi' => 'Kelurahan Bandar Lor, Kec. Mojoroto',
                    'jenis_bantuan' => 'RTLH APBD Kota Kediri',
                    'tahun' => '2026',
                    'before_image' => 'https://lh3.googleusercontent.com/aida/ALJJyrD4m1ctd8SQwfrHCu9PgbII6efuaKtqjD4yt2WXVyLi63mZ01boAmUo9wQxn5Du6k2atJhW7XwK5R8qCqfIwWo8eZdwtEIaJWKDigrIxQ5UoNJGn9ZKRmv_lS2thTonPnm7grC6EQVp0ZIIQyHHiijcton9kThdls7NRbX6H1z0onXMCro8OK2A4pzqHSO1Nj1E8NlgjopNYzCIE7lV-2Q8RnY16r3S8FS4OW35-LCoRRH2FnAsIuMjHU21',
                    'before_caption' => 'Dinding bata belum terplester, rangka atap lapuk, dan pencahayaan alami terbatas.',
                    'after_image' => 'https://lh3.googleusercontent.com/aida/ALJJyrC8AHTJhbDWpNTV1wh3IA6XS7A8D9bhJ0Sujx4E_2O7TU7I9yAlCoQTywqR4Fg_p96s5V3_JN2iNbJYqCV64m21rzHSx1YjFFdratZF6ENgUK9t-UeCJqnjc8CTUZksOzuwmFtZieTeI8jNJZRqOZ38559SAsNvZHOXUzZ5PoymMQGkreTSV5uRfnKJwCWBBft_FOqZWIJ3KB3hYB1CE3QyyB8dF7h6j61mzGutKQU1MEB4aCrxDWzudg',
                    'after_caption' => 'Struktur dinding kokoh dan terplester rapi, atap genteng baru, serta ventilasi sehat.',
                ],
            ],

            'statistik_mode' => 'auto', // 'auto' (database riil) atau 'manual'
            'statistik_manual' => [
                'total_usulan' => 2846,
                'terverifikasi' => 2310,
                'ditetapkan' => 1645,
                'proses' => 318,
                'selesai' => 1327,
            ],
        ];

        return $defaults[$key] ?? null;
    }
}
