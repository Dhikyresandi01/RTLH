

## 1.1 Latar belakang

Proses penentuan penerima bantuan RTLH membutuhkan pengelolaan data calon penerima, dokumen administrasi, kondisi fisik rumah, status sosial ekonomi, hasil survei lapangan, dan laporan pelaksanaan bantuan.

Berdasarkan Perwali Kota Kediri Nomor 18 Tahun 2026, tahapan kegiatan meliputi pengusulan calon penerima, verifikasi, validasi dan penetapan, sosialisasi, pelaksanaan bantuan, serta pertanggungjawaban.&#x20;

18 PETUNJUK PELAKSANAAN KEGIATAN BANTUAN SOSIAL UNTUK RUMAH TIDAK LAYAK HUNI.pdf



Sistem yang dikembangkan bertujuan untuk mendukung proses tersebut melalui pengelolaan data terpusat dan fitur SPK agar petugas dapat melihat kandidat yang memenuhi syarat, membandingkan tingkat kebutuhan perbaikan rumah, dan menyusun rekomendasi prioritas secara lebih terstruktur.

## 1.2 Visi produk

Mewujudkan sistem informasi yang membantu Pemerintah Kota Kediri mengelola bantuan RTLH secara terstruktur, transparan, terdokumentasi, dan dapat dipertanggungjawabkan.

## 1.3 Tujuan bisnis dan produk

- Memusatkan data calon penerima dan kondisi rumah.
- Mempermudah proses pengajuan dan pemeriksaan dokumen.
- Membantu petugas melakukan survei dan mencatat hasil verifikasi lapangan.
- Menghasilkan peringkat prioritas berdasarkan kriteria SPK yang disepakati.
- Mempermudah validasi dan pencatatan keputusan penerima.
- Memantau progres pembangunan atau rehabilitasi rumah.
- Mengelola dokumen pertanggungjawaban dan riwayat aktivitas.
- Menyediakan laporan untuk kebutuhan monitoring, evaluasi, dan audit.

## 1.4 Batasan penting

Sistem ini merupakan alat bantu pengambilan keputusan, bukan pengganti kewenangan pemerintah dalam memverifikasi dan menetapkan penerima bantuan. Peringkat SPK tidak otomatis membuat seseorang menjadi penerima bantuan.

Perwali menjadi dasar proses bisnis, sedangkan metode SPK, bobot kriteria, skala penilaian, dan aturan pemeringkatan harus ditetapkan melalui kesepakatan dengan pihak berwenang. Hal-hal tersebut tidak boleh dianggap sebagai ketentuan resmi apabila belum ditetapkan.

# 2. Ruang lingkup produk

## 2.1 Fitur yang termasuk dalam MVP

MVP (Minimum Viable Product) adalah versi awal yang berisi fitur inti agar sistem dapat diuji dan digunakan untuk proses utama.

### 01

Autentikasi dan manajemen pengguna

Login, logout, pengelolaan akun, peran, dan hak akses.

### 02

Manajemen calon penerima

Input data identitas, alamat, data keluarga, serta informasi sosial ekonomi.

### 03

Pengajuan dan dokumen

Pengunggahan KTP, KK, bukti status tanah, surat pernyataan, dan dokumen pendukung.

### 04

Survei dan verifikasi rumah

Pencatatan kondisi rumah, indikator RTLH, foto lapangan, dan hasil pemeriksaan.

### 05

SPK dan pemeringkatan

Penyaringan kelayakan, perhitungan skor, dan daftar rekomendasi prioritas.

### 06

Validasi dan penetapan

Pemeriksaan hasil rekomendasi, catatan koreksi, dan pencatatan keputusan resmi.

### 07

Pelaksanaan bantuan

Pencatatan pencairan, RAB, progres pekerjaan, dan dokumentasi 0%, 50%, serta 100%.

### 08

Pertanggungjawaban

Pengumpulan laporan penggunaan dana, kuitansi, bukti belanja, dan dokumen penyelesaian.

### 09

Dashboard dan laporan

Ringkasan jumlah kandidat, status proses, hasil penilaian, progres, serta ekspor laporan.

## 2.2 Fitur di luar ruang lingkup awal

Fitur berikut dapat dipertimbangkan setelah MVP berjalan:

- Integrasi otomatis dengan e-RTLH.
- Integrasi langsung dengan data DTSEN.
- Integrasi dengan sistem perbankan untuk konfirmasi pencairan.
- Notifikasi WhatsApp atau SMS.
- Aplikasi mobile khusus untuk survei lapangan.
- Peta geografis sebaran rumah dan prioritas wilayah.

Catatan: Ketersediaan API, mekanisme pertukaran data, dan izin akses dari sistem eksternal belum dapat dipastikan dari dokumen peraturan. Fitur integrasi tidak boleh dijanjikan sebelum dilakukan koordinasi teknis dan administratif.

# 3. Pengguna dan hak akses

Hak akses berikut merupakan rancangan awal dan perlu disesuaikan dengan struktur organisasi serta kewenangan resmi.

| Peran                                  | Tanggung jawab utama                                                                                          |
| -------------------------------------- | ------------------------------------------------------------------------------------------------------------- |
| Admin sistem                           | Mengelola akun, peran, konfigurasi sistem, dan data referensi.                                                |
| Petugas Kelurahan                      | Mengelola usulan, melengkapi data, dan memantau proses di wilayahnya.                                         |
| RT/RW                                  | Membantu identifikasi calon dan mendukung informasi awal kondisi rumah.                                       |
| Pendamping                             | Melakukan atau mendukung survei, mendokumentasikan progres, serta membantu penyusunan laporan.                |
| Tim Koordinasi dan Pengendali          | Melakukan verifikasi, pemeriksaan kelengkapan, validasi, serta monitoring dan evaluasi sesuai kewenangan.     |
| Dinas Perumahan dan Kawasan Permukiman | Mengelola proses tingkat Dinas, menyiapkan daftar calon dan laporan, serta mengoordinasikan tahapan kegiatan. |
| Pejabat berwenang                      | Mencatat atau mengesahkan keputusan penetapan sesuai kewenangan resmi.                                        |

Prinsip hak akses:

- Pengguna hanya dapat mengakses data sesuai peran dan wilayah kewenangannya.
- Dokumen pribadi hanya dapat dibuka oleh pengguna yang berwenang.
- Perubahan skor, hasil verifikasi, dan status penetapan harus tercatat.
- Keputusan resmi tidak boleh diubah oleh pengguna yang tidak berwenang.

# 4. Alur proses bisnis

Alur berikut mengacu pada enam tahapan kegiatan dalam Perwali.&#x20;

18 PETUNJUK PELAKSANAAN KEGIATAN BANTUAN SOSIAL UNTUK RUMAH TIDAK LAYAK HUNI.pdf



1\. Pengusulan calon penerima

Kelurahan mengelola usulan dan dokumen calon penerima.

2\. Verifikasi administrasi dan lapangan

Pemeriksaan persyaratan, dokumen, dan kondisi rumah.

3\. SPK dan pemeringkatan prioritas

Penyaringan kelayakan, perhitungan skor, dan rekomendasi kandidat.

Rancangan fitur sistem

4\. Validasi dan penetapan

Pemeriksaan rekomendasi, ketersediaan anggaran, dan keputusan pejabat berwenang.

5\. Sosialisasi dan pelaksanaan bantuan

Penyampaian informasi, pencairan, dan pemantauan pekerjaan.

6\. Pertanggungjawaban dan evaluasi

Pemeriksaan laporan penggunaan dana, dokumentasi, dan progres akhir.

## 4.1 Alur pengajuan

1. Petugas Kelurahan memasukkan identitas calon penerima.
2. Sistem memvalidasi format dan kelengkapan data.
3. Petugas mengunggah dokumen persyaratan.
4. Calon penerima dicatat dengan status `Diajukan`.
5. Tim yang berwenang memeriksa dokumen dan menjadwalkan atau mencatat survei.
6. Jika data tidak lengkap, sistem mengembalikan pengajuan untuk diperbaiki.

## 4.2 Alur verifikasi

1. Petugas memeriksa identitas dan persyaratan calon penerima.
2. Petugas mencatat hasil survei kondisi rumah.
3. Petugas mengunggah foto kondisi awal atau 0%.
4. Sistem menyimpan hasil verifikasi beserta waktu dan identitas petugas.
5. Hasil verifikasi ditandai lengkap, perlu perbaikan, atau tidak memenuhi persyaratan.
6. Kandidat yang memenuhi persyaratan dapat masuk ke proses SPK.

## 4.3 Alur SPK

1. Sistem mengambil kandidat yang lolos pemeriksaan kelayakan.
2. Sistem memeriksa kelengkapan nilai pada setiap kriteria.
3. Sistem menghitung nilai normalisasi dan skor akhir sesuai metode yang telah ditetapkan.
4. Sistem menampilkan peringkat, rincian skor, dan alasan rekomendasi.
5. Petugas meninjau hasil pemeringkatan.
6. Hasil disampaikan kepada pihak berwenang untuk validasi.

Peringkat harus memiliki penjelasan yang dapat ditelusuri. Petugas juga perlu dapat melihat data dan nilai yang membentuk skor setiap kandidat.

## 4.4 Alur pelaksanaan dan pertanggungjawaban

Sistem harus mendukung pencatatan dokumen pencairan, RAB yang telah diverifikasi, foto progres, laporan penggunaan dana, kuitansi, dan bukti penyelesaian.

Perwali menyebutkan bahwa pekerjaan dimulai paling lambat 3 hari setelah dana diterima dan harus selesai dalam waktu 2 bulan. Sistem dapat menggunakan ketentuan tersebut sebagai dasar pengingat tenggat waktu, setelah aturan kalender dan kondisi pengecualian dikonfirmasi oleh Dinas.&#x20;

18 PETUNJUK PELAKSANAAN KEGIATAN BANTUAN SOSIAL UNTUK RUMAH TIDAK LAYAK HUNI.pdf



# 5. Kebutuhan fungsional

Daftar berikut merupakan kebutuhan yang harus dapat diuji saat pengembangan.

| ID    | Kebutuhan                                                                                   | Prioritas             |
| ----- | ------------------------------------------------------------------------------------------- | --------------------- |
| FR-01 | Sistem menyediakan login dan logout.                                                        | Must                  |
| FR-02 | Admin dapat mengatur akun dan hak akses pengguna.                                           | Must                  |
| FR-03 | Petugas dapat membuat, melihat, mengubah, dan mencari data calon penerima sesuai hak akses. | Must                  |
| FR-04 | Sistem dapat menyimpan data identitas, alamat, data keluarga, dan informasi sosial ekonomi. | Must                  |
| FR-05 | Sistem dapat mengunggah dan memeriksa kelengkapan dokumen pengajuan.                        | Must                  |
| FR-06 | Sistem dapat menyimpan hasil pemeriksaan persyaratan dan alasan ketidaklayakan.             | Must                  |
| FR-07 | Sistem menyediakan formulir survei kondisi rumah dan unggahan foto.                         | Must                  |
| FR-08 | Sistem dapat menyimpan hasil verifikasi beserta petugas dan waktu pemeriksaan.              | Must                  |
| FR-09 | Admin berwenang dapat mengatur kriteria, bobot, skala, dan metode SPK yang disetujui.       | Must                  |
| FR-10 | Sistem dapat menghitung skor SPK secara konsisten berdasarkan konfigurasi aktif.            | Must                  |
| FR-11 | Sistem menampilkan peringkat dan rincian perhitungan tiap kandidat.                         | Must                  |
| FR-12 | Sistem mencatat hasil validasi dan alasan perubahan dari rekomendasi SPK.                   | Must                  |
| FR-13 | Sistem dapat mencatat keputusan penetapan dan referensi dokumen keputusan.                  | Must                  |
| FR-14 | Sistem dapat mencatat RAB, pencairan, dan progres pekerjaan.                                | Must                  |
| FR-15 | Sistem dapat menyimpan dokumen pertanggungjawaban dan foto progres.                         | Must                  |
| FR-16 | Sistem menampilkan dashboard dan menghasilkan laporan yang dapat diekspor.                  | Must                  |
| FR-17 | Sistem mencatat riwayat perubahan data penting dan tindakan pengguna.                       | Must                  |
| FR-18 | Sistem menyediakan pengingat tenggat waktu pelaksanaan.                                     | Should                |
| FR-19 | Sistem menyediakan peta lokasi calon penerima.                                              | Could                 |
| FR-20 | Sistem terintegrasi otomatis dengan e-RTLH atau DTSEN.                                      | Future / perlu kajian |

Prioritas: Must = wajib untuk MVP; Should = diutamakan setelah fitur inti; Could = opsional; Future = memerlukan kajian lanjutan.

# 6. Persyaratan SPK

## 6.1 Prinsip penilaian

SPK menggunakan dua tahap berbeda:

- Tahap 1 — pemeriksaan kelayakan: memastikan kandidat memenuhi persyaratan yang berlaku.
- Tahap 2 — pemeringkatan: membandingkan tingkat prioritas di antara kandidat yang memenuhi syarat.

Persyaratan administratif dan kelayakan tidak sebaiknya langsung dijadikan skor yang dapat dikompensasi oleh kriteria lain. Contohnya, kandidat yang tidak memenuhi syarat kependudukan tidak otomatis menjadi layak hanya karena kondisi rumahnya sangat buruk.

## 6.2 Persyaratan kelayakan awal

Berdasarkan Pasal 5 Perwali, sistem perlu mendukung pemeriksaan berikut:&#x20;

18 PETUNJUK PELAKSANAAN KEGIATAN BANTUAN SOSIAL UNTUK RUMAH TIDAK LAYAK HUNI.pdf



- Penduduk Kota Kediri dengan KTP dan KK setempat.
- Termasuk kelompok masyarakat berpenghasilan rendah (MBR).
- Terdaftar pada DTSEN desil 1–4 dan sesuai dengan validasi kondisi faktual.
- Belum pernah menerima bantuan RTLH atau bantuan sejenis.
- Status tanah jelas dan didukung dokumen yang diterima sesuai ketentuan.
- Bersedia bekerja sama dalam pelaksanaan kegiatan.
- Menghuni satu rumah yang tidak layak, dengan surat pertanggungjawaban mutlak yang dipersyaratkan.

Sistem sebaiknya menyimpan status setiap persyaratan sebagai `Memenuhi`, `Tidak Memenuhi`, atau `Perlu Verifikasi`. Status terakhir diperlukan saat bukti belum cukup untuk membuat keputusan.

## 6.3 Usulan kriteria pemeringkatan

Indikator RTLH dalam Pasal 4 mencakup ketahanan bangunan, kecukupan luas hunian, akses air minum, dan sanitasi. Informasi sosial ekonomi juga relevan terhadap penentuan prioritas.&#x20;

18 PETUNJUK PELAKSANAAN KEGIATAN BANTUAN SOSIAL UNTUK RUMAH TIDAK LAYAK HUNI.pdf



| Kode | Kriteria usulan                               | Arah penilaian                                    |
| ---- | --------------------------------------------- | ------------------------------------------------- |
| C1   | Tingkat kerusakan atau ketidakamanan bangunan | Semakin berat, semakin prioritas                  |
| C2   | Kecukupan luas hunian                         | Semakin tidak mencukupi, semakin prioritas        |
| C3   | Akses air minum                               | Semakin tidak memadai, semakin prioritas          |
| C4   | Kondisi sanitasi                              | Semakin tidak memadai, semakin prioritas          |
| C5   | Kondisi sosial ekonomi                        | Berdasarkan indikator yang disetujui dan tersedia |

Tabel ini adalah usulan desain SPK, bukan daftar bobot atau rumus resmi dari Perwali. Untuk C5, tim perlu menentukan apakah nilai desil atau indikator sosial ekonomi lain benar-benar layak digunakan sebagai pembeda prioritas, mengingat DTSEN desil 1–4 sudah menjadi bagian dari pemeriksaan kelayakan.

### Skala penilaian

Sebagai rancangan awal, kriteria dapat memakai skala 1–5:

- 1 = kondisi relatif memadai.
- 2 = masalah ringan.
- 3 = masalah sedang.
- 4 = masalah berat.
- 5 = masalah sangat berat.

Definisi operasional setiap angka harus dibuat per kriteria. Contohnya, tingkat kerusakan bangunan harus memiliki indikator yang dapat diamati, bukan hanya penilaian subjektif petugas.

## 6.4 Metode perhitungan

Metode yang dapat dipertimbangkan adalah SAW (Simple Additive Weighting) karena relatif mudah diterapkan, dijelaskan, dan diuji.

Rumus skor akhir:

\\[ V_i=\sum\_{j=1}^{n} w_j r\_{ij} \\]

Keterangan:

- \\(V_i\\) = skor akhir kandidat ke-\\(i\\).
- \\(w_j\\) = bobot kriteria ke-\\(j\\).
- \\(r\_{ij}\\) = nilai normalisasi kandidat ke-\\(i\\) pada kriteria ke-\\(j\\).
- \\(n\\) = jumlah kriteria.

Untuk indikator yang semakin tinggi nilainya berarti semakin mendesak, normalisasi dapat menggunakan pendekatan benefit. Namun, apabila data mentah berupa jumlah kekurangan, persentase kerusakan, atau ukuran fisik tertentu, rumus harus disesuaikan dengan definisi indikator tersebut.

Bobot final belum ditentukan. Bobot harus disepakati dengan Dinas dan pihak terkait berdasarkan kebijakan, validasi lapangan, serta pengujian hasil. Sistem tidak boleh mengarang bobot lalu memperlakukannya sebagai ketentuan pemerintah.

## 6.5 Aturan hasil SPK

- Hanya kandidat yang memenuhi persyaratan yang masuk pemeringkatan.
- Kandidat dengan data penilaian yang wajib tetapi belum lengkap tidak mendapatkan peringkat final.
- Sistem menampilkan skor, posisi peringkat, rincian nilai kriteria, dan versi konfigurasi perhitungan.
- Kandidat dengan skor yang sama mengikuti aturan pemecah seri yang telah disepakati.
- Perubahan kriteria atau bobot tidak boleh diam-diam mengubah hasil historis; hasil sebelumnya harus tetap dapat ditelusuri.
- Petugas berwenang dapat memberikan catatan atas hasil rekomendasi, tetapi setiap perubahan keputusan harus memiliki alasan dan riwayat audit.

# 7. Kebutuhan data dan rancangan entitas database

Berikut rancangan awal entitas. Struktur final perlu disesuaikan dengan framework, rancangan teknis, dan proses bisnis yang disetujui.

users

Akun, peran, status pengguna, dan cakupan wilayah.

applicants

Identitas calon penerima, alamat, data keluarga, dan status pengajuan.

documents

Dokumen persyaratan dan bukti administrasi.

inspections

Survei, kondisi rumah, hasil verifikasi, dan foto.

criteria_scores

Nilai kriteria dan hasil penilaian kandidat.

spk_runs

Versi metode, bobot, hasil skor, dan peringkat.

decisions

Hasil validasi, penetapan, dan alasan keputusan.

aid_progress

RAB, pencairan, progres fisik, dan pelaporan.

accountability_reports

Laporan penggunaan dana, kuitansi, bukti belanja, dan dokumentasi akhir.

audit_logs

Catatan aktivitas dan perubahan data penting oleh pengguna.

Diagram konseptual, bukan ERD final. Relasi dan kardinalitas perlu ditentukan saat desain database.

### Data penting yang perlu disimpan

| Entitas                | Contoh atribut                                                                           |
| ---------------------- | ---------------------------------------------------------------------------------------- |
| applicants             | ID, NIK, nama, alamat, kelurahan, nomor KK, status pengajuan                             |
| documents              | ID kandidat, jenis dokumen, lokasi file, tanggal unggah, status verifikasi               |
| inspections            | ID kandidat, petugas, tanggal survei, kondisi bangunan, air minum, sanitasi, luas hunian |
| criteria_scores        | ID kandidat, kode kriteria, nilai mentah, nilai normalisasi                              |
| spk_runs               | metode, versi kriteria, bobot, tanggal perhitungan, status hasil                         |
| decisions              | status keputusan, pejabat/petugas pencatat, tanggal, referensi dokumen keputusan, alasan |
| aid_progress           | RAB, tanggal pencairan, tanggal mulai, progres, foto, tanggal penyelesaian               |
| accountability_reports | jenis laporan, dokumen bukti, status pemeriksaan, catatan koreksi                        |
| audit_logs             | pengguna, aksi, entitas, waktu, perubahan sebelum dan sesudah                            |

NIK dan informasi lain yang dapat mengidentifikasi seseorang harus dibatasi aksesnya, tidak ditampilkan secara terbuka, dan dilindungi dengan pengamanan yang sesuai.

# 8. Kebutuhan UI/UX

Halaman utama yang disarankan:

8.1 Dashboard

Ringkasan total calon penerima, pengajuan baru, menunggu verifikasi, memenuhi persyaratan, kandidat yang diperingkat, penerima ditetapkan, dan pekerjaan belum selesai.


8.2 Data calon penerima

Tabel dengan pencarian, filter kelurahan, filter status, detail kandidat, serta akses dokumen sesuai kewenangan.


8.3 Form survei

Form kondisi bangunan, luas hunian, akses air minum, sanitasi, bukti foto, catatan petugas, dan status verifikasi.


8.4 Hasil SPK

Tabel peringkat, skor akhir, rincian nilai kriteria, filter, versi perhitungan, dan catatan hasil evaluasi.


8.5 Pelaksanaan dan laporan

Informasi pencairan, tenggat waktu, progres fisik, foto 0%–50%–100%, dokumen pertanggungjawaban, dan status pemeriksaan.

Gambar di atas merupakan referensi visual untuk arah UI/UX, bukan tampilan final atau spesifikasi antarmuka yang mengikat.

# 9. Status dan transisi proses

Status berikut adalah rancangan untuk kebutuhan aplikasi, bukan daftar status resmi yang ditetapkan Perwali.

| Status                      | Makna                                                                          |
| --------------------------- | ------------------------------------------------------------------------------ |
| Draft                       | Data sedang disiapkan.                                                         |
| Diajukan                    | Usulan telah dikirim.                                                          |
| Verifikasi                  | Pemeriksaan dokumen dan/atau lapangan berlangsung.                             |
| Perlu Perbaikan Data        | Ada dokumen atau informasi yang harus dilengkapi.                              |
| Tidak Memenuhi Syarat       | Persyaratan wajib tidak terpenuhi, disertai alasan.                            |
| Terverifikasi               | Pemeriksaan persyaratan selesai.                                               |
| Menunggu Validasi           | Kandidat menunggu peninjauan dan validasi.                                     |
| Direkomendasikan            | Kandidat masuk rekomendasi berdasarkan SPK dan proses pemeriksaan.             |
| Ditetapkan                  | Penetapan resmi telah dicatat.                                                 |
| Pelaksanaan                 | Bantuan sedang dilaksanakan.                                                   |
| Menunggu Pertanggungjawaban | Pekerjaan selesai atau masuk tahap akhir dan dokumen masih perlu diselesaikan. |
| Selesai                     | Pekerjaan dan pertanggungjawaban telah diperiksa sesuai alur yang disetujui.   |

Sistem harus mencegah transisi status yang tidak sah. Sebagai contoh, kandidat tidak boleh langsung berstatus `Ditetapkan` hanya karena memperoleh peringkat pertama.

# 10. Non-functional requirements

| Area                 | Persyaratan                                                                                                   |
| -------------------- | ------------------------------------------------------------------------------------------------------------- |
| Keamanan             | Password disimpan menggunakan hashing yang aman; akses berbasis peran; perlindungan terhadap akses tidak sah. |
| Privasi              | Data identitas, data keluarga, dan dokumen dibatasi sesuai kebutuhan tugas dan kewenangan.                    |
| Auditabilitas        | Perubahan data, skor, konfigurasi SPK, dan keputusan dapat ditelusuri.                                        |
| Akurasi              | Perhitungan skor konsisten, memiliki pengujian, dan menggunakan versi konfigurasi yang tercatat.              |
| Kinerja              | Halaman utama dan pencarian harus responsif pada beban kerja yang disepakati saat pengujian.                  |
| Keandalan            | Pencadangan data dan prosedur pemulihan harus tersedia serta diuji.                                           |
| Kemudahan penggunaan | Formulir menggunakan label yang jelas, validasi input, dan pesan kesalahan yang mudah dipahami.               |
| Kompatibilitas       | Antarmuka responsif pada desktop, tablet, dan perangkat mobile.                                               |
| Dokumentasi          | Tersedia dokumentasi pengguna, administrasi, deployment, dan perhitungan SPK.                                 |

Target numerik untuk waktu respons, ketersediaan layanan, kapasitas pengguna, dan periode penyimpanan data perlu ditentukan setelah kebutuhan operasional dikonfirmasi.

# 11. Acceptance criteria

Fitur dinyatakan selesai apabila memenuhi kriteria penerimaan berikut.

### Checklist pengujian

0 dari 11

Autentikasi

Pengguna dapat login dan logout, dan tidak dapat membuka halaman yang tidak diizinkan.

Pengajuan

Petugas dapat menyimpan data kandidat dan sistem memvalidasi kelengkapan dokumen wajib.

Verifikasi

Hasil pemeriksaan dapat disimpan dengan petugas, tanggal, catatan, dan bukti yang relevan.

Kelayakan

Kandidat yang gagal memenuhi persyaratan wajib tidak masuk pemeringkatan final.

SPK

Skor dan peringkat sesuai perhitungan yang telah diuji menggunakan data uji dan konfigurasi yang disepakati.

Transparansi

Rincian skor, bobot, versi konfigurasi, dan alasan keputusan dapat ditinjau oleh pihak berwenang.

Penetapan

Status penetapan hanya dapat dicatat oleh peran yang diizinkan dan memiliki referensi keputusan.

Pelaksanaan

RAB, tanggal pencairan, progres, foto, dan tenggat pekerjaan dapat dicatat dan ditelusuri.

Pertanggungjawaban

Dokumen wajib dapat diperiksa, dikembalikan untuk koreksi, dan ditandai selesai oleh pihak berwenang.

Laporan

Laporan yang dihasilkan konsisten dengan data dan filter yang dipilih.

Audit

Perubahan penting tercatat dan tidak dapat dihapus oleh pengguna biasa melalui antarmuka aplikasi.

Reset checklistSalin checklist

# 12. Indikator keberhasilan produk

Keberhasilan sistem dapat dievaluasi menggunakan indikator berikut.

- Seluruh pengajuan tercatat dengan identitas dan status proses yang jelas.
- Persyaratan wajib dapat diperiksa dan hasil pemeriksaannya terdokumentasi.
- Hasil SPK dapat dihitung ulang dan diverifikasi dengan data uji.
- Petugas dapat menelusuri alasan peringkat dan keputusan kandidat.
- Data pelaksanaan dan pertanggungjawaban dapat dihubungkan dengan kandidat yang ditetapkan.
- Laporan yang dihasilkan konsisten dengan data operasional.
- Tidak ada akses atau perubahan keputusan tanpa kewenangan yang sesuai.

Target kuantitatif seperti persentase pengurangan waktu verifikasi atau tingkat kelengkapan data perlu ditetapkan setelah tersedia pengukuran proses saat ini sebagai baseline.

# 13. Rencana pengembangan

Berikut estimasi awal untuk perencanaan tim kecil. Durasi sebenarnya bergantung pada jumlah developer, kompleksitas integrasi, kesiapan kebutuhan, dan proses persetujuan.

| Fase                       | Aktivitas                                                                         | Estimasi   |
| -------------------------- | --------------------------------------------------------------------------------- | ---------- |
| 1. Discovery               | Wawancara Dinas, Kelurahan, pendamping, dan pemangku kepentingan; validasi proses | 1–2 minggu |
| 2. Desain                  | UI/UX, arsitektur, rancangan database, definisi kriteria dan hak akses            | 1–2 minggu |
| 3. Pengembangan inti       | Autentikasi, pengajuan, dokumen, survei, verifikasi                               | 2–3 minggu |
| 4. Pengembangan SPK        | Konfigurasi kriteria, perhitungan, peringkat, penjelasan skor                     | 1–2 minggu |
| 5. Pelaksanaan dan laporan | Pencairan, progres, pertanggungjawaban, dashboard, ekspor                         | 2–3 minggu |
| 6. Pengujian dan pilot     | UAT, perbaikan, pelatihan, uji coba terbatas                                      | 1–2 minggu |

Total perkiraan: 8–14 minggu, dengan kemungkinan beberapa fase dikerjakan secara paralel.

# 14. Risiko dan mitigasi

| Risiko                                                    | Mitigasi                                                                                           |
| --------------------------------------------------------- | -------------------------------------------------------------------------------------------------- |
| Bobot SPK tidak memiliki dasar yang disepakati            | Lakukan lokakarya dengan Dinas dan dokumentasikan keputusan bobot serta indikator.                 |
| Data DTSEN tidak tersedia atau belum dapat diintegrasikan | Sediakan input atau pemeriksaan manual oleh petugas berwenang sesuai prosedur yang disetujui.      |
| Data kondisi rumah tidak akurat                           | Gunakan indikator survei terstandar, bukti foto, identitas petugas, dan mekanisme verifikasi.      |
| Peringkat SPK dianggap sebagai keputusan otomatis         | Tampilkan keterangan bahwa hasil merupakan rekomendasi dan wajib melalui validasi serta penetapan. |
| Data pribadi bocor                                        | Terapkan hak akses, pencatatan audit, pengamanan file, dan pembatasan ekspor.                      |
| Dokumen pertanggungjawaban tidak lengkap                  | Gunakan checklist dokumen wajib dan status pemeriksaan yang jelas.                                 |
| Perubahan aturan atau konfigurasi                         | Simpan versi aturan, kriteria, bobot, dan riwayat perhitungan.                                     |

# 15. Keputusan yang harus diselesaikan sebelum development

Bagian ini penting agar developer tidak membangun fitur berdasarkan asumsi yang belum disetujui.

1\. Metode SPK

Apakah akan menggunakan SAW, TOPSIS, atau metode lain?

2\. Kriteria dan bobot

Siapa yang menetapkan kriteria, definisi indikator, skala, bobot, dan aturan pemecah seri?

3\. Sumber data

Bagaimana data DTSEN, status bantuan sebelumnya, dan status tanah diverifikasi?

4\. Alur persetujuan

Siapa yang berwenang mengembalikan usulan, memvalidasi, dan mencatat penetapan resmi?

5\. Integrasi e-RTLH

Apakah aplikasi menjadi sistem mandiri, atau harus terhubung ke e-RTLH? Apakah API tersedia?

6\. Pengguna dan cakupan

Kelurahan mana yang terlibat, berapa perkiraan pengguna, dan bagaimana pembagian akses wilayah?

7\. Laporan dan arsip

Format laporan, penamaan dokumen, retensi data, dan kebutuhan audit apa yang diwajibkan?

8\. Hosting dan keamanan

Di mana sistem di-host, siapa pengelola infrastruktur, dan bagaimana backup serta pemulihan dilakukan?

Salin hasil pembahasan

# 16. Kesimpulan

PRD ini menetapkan arah pengembangan sistem sebagai aplikasi pengelolaan bantuan RTLH yang mencakup proses administrasi hingga pertanggungjawaban, dengan SPK sebagai alat bantu untuk menyusun prioritas kandidat.

Ada tiga prinsip utama yang perlu dipertahankan selama pengembangan:

1. Persyaratan kelayakan dipisahkan dari skor prioritas.
2. Hasil SPK dapat dijelaskan dan ditelusuri, serta tidak menggantikan penetapan resmi.
3. Alur aplikasi mengikuti proses bisnis dan dokumen yang dipersyaratkan oleh regulasi, sementara fitur tambahan dan metode SPK ditetapkan melalui keputusan yang terdokumentasi.

Langkah paling tepat setelah PRD ini adalah menyepakati metode SPK, indikator operasional, skala penilaian, dan bobot kriteria bersama pihak terkait. Setelah itu, PRD dapat diturunkan menjadi dokumen SRS, ERD/database schema, user flow, wireframe UI/UX, dan backlog sprint untuk mulai dikembangkan.