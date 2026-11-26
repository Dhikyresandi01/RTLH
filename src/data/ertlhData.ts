export type Kecamatan = 'Mojoroto' | 'Kota' | 'Pesantren';

export interface HeroSlide {
  id: number;
  kicker: string;
  accentColor: 'khaki' | 'gold';
  title: string;
  description: string;
  imageUrl: string;
  imageAlt: string;
  primaryCtaLabel: string;
  primaryCtaHref: string;
  secondaryCtaLabel?: string;
  secondaryCtaHref?: string;
}

export interface StatIndicator {
  value: string;
  label: string;
  description: string;
  toneClass: string;
}

export interface RtlhRow {
  id: string;
  kec: Kecamatan;
  kel: string;
  usulan: number;
  verif: number;
  tetap: number;
  proses: number;
  selesai: number;
  programUtama: string;
  prioritasSanitasi: number;
}

export interface DistrictStat {
  name: Kecamatan;
  kel: string;
  usulan: string;
  proses: string;
  selesai: string;
  persentaseRealisasi: string;
  pinPosition: { top: string; left: string };
  pinVariant: 'primary' | 'secondary' | 'dark-blue';
}

export interface ProgramItem {
  id: string;
  kicker: string;
  kickerColor: 'secondary' | 'primary' | 'warning';
  title: string;
  shortTitleMobile: string;
  description: string;
  shortDescriptionMobile: string;
  ctaLabel: string;
  imageUrl: string;
  imageAlt: string;
  sumberDana: string;
  nilaiStimulan: string;
  sasaran: string;
  kriteria: string[];
  persyaratan: string[];
}

export interface ProcessStep {
  step: number;
  title: string;
  description: string;
  markerColor: 'primary' | 'secondary' | 'khaki';
  isFinal?: boolean;
  outputDokumen: string;
}

export interface RealisasiCase {
  id: string;
  lokasi: string;
  kecamatan: Kecamatan;
  jenisBantuan: string;
  tahun: string;
  beforeImageUrl: string;
  beforeAlt: string;
  beforeCaption: string;
  afterImageUrl: string;
  afterAlt: string;
  afterCaption: string;
  ringkasanTeknis: string[];
}

export interface PublicationItem {
  id: string;
  type: 'Berita' | 'Pengumuman' | 'Laporan Publik';
  meta: string;
  title: string;
  summary?: string;
  imageUrl?: string;
  imageAlt?: string;
  fullContent: string[];
  downloadFilename?: string;
}

export interface FaqItem {
  id: number;
  question: string;
  answer: string;
}

export const IMAGES = {
  heroKediriHousing:
    'https://lh3.googleusercontent.com/aida/ALJJyrBpy2vGxvnr6ORhcmxYl3R8cd8D7dtYv1Yww2rxPest_AIvb3ZCVIEU0zWNOAtp1vKRKBwqShCLeehO6IrEAVbvMnqrvAhIaSQbUjOUOVtppx5CvZqPbgob9wHExRXOkd2V6ubEQ8Kj7T9_gaXlmbowt8peWMjWVKeCJIRZ2UCXRnQQBLWTPfJlgbNWUNl-NPVNikA0lFr2xavU45N9yaxkQ6SerfpRV_-9mbSeZw_fCssjvFETGcETrp8Vi3Q',
  heroSurveyTeam:
    'https://lh3.googleusercontent.com/aida/ALJJyrBFXsDnZHtc_w5DTk_G9e9XlJxkW-gJAWtcY26mAdk3NCoqAvg_DpZgT109PuvggIgTODxJzJEICIXHu46bREesw9dam4LHxLoPsxMh-Qc_E9KIe9TtNAkqtv2Ie2Fi0feDxMVG2_sNcKhu9yn1qck2b5tudYMg7Mg7SF_yIXt8WemEZMB1Z_NbKDrWl0PWtYJLntoIaED2ILEwW1IJ_oRphGfmGr1X9qFERvEUbZthRrtVXsVfbdKUTTH-',
  aboutDocumentary:
    'https://lh3.googleusercontent.com/aida/ALJJyrCvX-0BBEXaol7jlAHve-WGZKavRXCeQUMa-I_WQ0TMuxs1jjazIMHYRMBxTHBZg04_PM2d7ZyCfqZKU8nOPNYc7QWMgP4hO22MWKlbQSrtN33XPb16Fyzw94WqTH7ps4IeA0GCz7KGL2RdZir7Hp1RcZI1XTfG1wX0LSzwLHyRSsg4ZL65kWpc5uRPD5IrTcBPIqzYHdjPS0QqHA1MgDu8R59crsNVlAo4yS28xoQPeZjb7JB46OG3YlLECA',
  kediriMapOsm:
    'https://lh3.googleusercontent.com/aida/ALJJyrCnz-JuM5liXyzhOhdYTqeJOwDRRHb7o6S8o-8_wdm8cWJyoOmo-0zJyl-iBBoaHCFylNVUE571G2ijQk-uvq2hTSSdpIXAnx69mFRypq4dsZoMFSNo_Wde25MzRDJrAEjP70eYGFqMm-RCPBTGFLLVVG8QZsN7PIP1uNTc1YMsLjeJo5JtcNPc00_5Lt7TC5yBQRkfR6GREglM_l1r4JGDCkFKbogNMWdDA51l8HQoUyhGEoU-EL4bPw',
  programBsps:
    'https://lh3.googleusercontent.com/aida/ALJJyrA9XfhqrcYvkxOtL-VrsH1ew_2pU0pB-JU6GPBn2I1_tkskc3Xmgy7HVcGPPU9qppee6wkbr-pp7OSeDr5vJb-g4Zae7TjiVYFl0zhFq1oYUgAtx2NteJVQQ7I4zsI2yBWP5MaSPM8JkEbBqWkNXCcEaFqvq131v6CqYPemMZ9uqF4KB0DoaWO99DtH76U0gHP4yX9COyHl9GEvRKrcWpjpUGd35dckD8-Fmc7w6LH8nPpZtUtWoQ',
  programApbd:
    'https://lh3.googleusercontent.com/aida/ALJJyrDvV5w_i9Ornem7HcBVWldMMn2-8kIsipV_jc8WrU_APTLHqMChoqLM9PfWa4s0ijnRtzKgVBmhnMdPDI-foAZBrHZtUx2e6SmuPx_KmNdsRhjl_V2EsmLvomVFZzXRA5g1S8YLnCHwSIlL6LCUV49R8cAIkwa_vaLnPgJWMyEM460mOYZcbLHwYkUpXAYd9lI5k22VxkgTrmOU4MsUSmSEiqTu-U-mS6k-2MZa7BiWawPQrhFoPw',
  programStimulan:
    'https://lh3.googleusercontent.com/aida/ALJJyrD6PgT4WbLbLBnyMzyhM5MmF8ZVSLMJ0N3uSunEmqULlh_Fpn1r37WbVVh-xWxQYsarDa5uCwfarZ6C6vDJrnAhfeqw1ffCWJgwetMImc-Hwj2yajcHn8lRyFv8c1n6JvXSClv_Ps25nWD0j8PO8gEDgCljTbJpavJWPxfJABRNaASNr15G7sr0zzfJJExPggFb0P1iOOB5LWz31oXu12rw7-5rHECzX_kWMfZk6sdZicAJtbwSbolLf0uG',
  realisasiBefore:
    'https://lh3.googleusercontent.com/aida/ALJJyrD4m1ctd8SQwfrHCu9PgbII6efuaKtqjD4yt2WXVyLi63mZ01boAmUo9wQxn5Du6k2atJhW7XwK5R8qCqfIwWo8eZdwtEIaJWKDigrIxQ5UoNJGn9ZKRmv_lS2thTonPnm7grC6EQVp0ZIIQyHHiijcton9kThdls7NRbX6H1z0onXMCro8OK2A4pzqHSO1Nj1E8NlgjopNYzCIE7lV-2Q8RnY16r3S8FS4OW35-LCoRRH2FnAsIuMjHU21',
  realisasiAfter:
    'https://lh3.googleusercontent.com/aida/ALJJyrC8AHTJhbDWpNTV1wh3IA6XS7A8D9bhJ0Sujx4E_2O7TU7I9yAlCoQTywqR4Fg_p96s5V3_JN2iNbJYqCV64m21rzHSx1YjFFdratZF6ENgUK9t-UeCJqnjc8CTUZksOzuwmFtZieTeI8jNJZRqOZ38559SAsNvZHOXUzZ5PoymMQGkreTSV5uRfnKJwCWBBft_FOqZWIJ3KB3hYB1CE3QyyB8dF7h6j61mzGutKQU1MEB4aCrxDWzudg',
  beritaPenyerahan:
    'https://lh3.googleusercontent.com/aida/ALJJyrBmfOndPSII5U2bmNofQCocpQUZKZ82qRbhjYSg-KvzxOFUFYcley1HsL37Kj0eCTpU2uPny6y1pvk-6ICasuG_w2tuiNh0i_Yt-veQQyTq8jEbjjIoKOYXS9b_6p0lilFvUq4FJAsGFGDRvoh05hX80qqXqjr60HaB6nZFP5XYFF_-4Rg-OctIMUIHfghlTxE75YEPP6RN2hWcAb-xgAggW2EcTfpzMHH_nSaAyBDj5AzGDIrhlVTzriYsRw',
};

export const HERO_SLIDES: HeroSlide[] = [
  {
    id: 0,
    kicker: 'Portal Resmi DPKP Kota Kediri',
    accentColor: 'khaki',
    title: 'Mewujudkan Hunian Layak bagi Masyarakat Kota Kediri',
    description:
      'Sistem informasi terpadu pendataan, verifikasi teknis, dan transparansi realisasi penanganan Rumah Tidak Layak Huni di tiga kecamatan Kota Kediri.',
    imageUrl: IMAGES.heroKediriHousing,
    imageAlt: 'Kawasan hunian layak hasil program perbaikan RTLH di Kota Kediri',
    primaryCtaLabel: 'Lihat Data Usulan',
    primaryCtaHref: '#data-rtlh',
    secondaryCtaLabel: 'Program Bantuan',
    secondaryCtaHref: '#program',
  },
  {
    id: 1,
    kicker: 'Transparansi Verifikasi Lapangan',
    accentColor: 'gold',
    title: 'Pendataan Terukur Berbasis Kondisi Teknis Bangunan',
    description:
      'Setiap usulan dari kelurahan diverifikasi langsung oleh tim teknis untuk memastikan bantuan tepat sasaran sesuai standar keselamatan hunian.',
    imageUrl: IMAGES.heroSurveyTeam,
    imageAlt:
      'Tim verifikasi teknis Dinas Perumahan dan Kawasan Permukiman Kota Kediri melakukan survei lapangan',
    primaryCtaLabel: 'Lihat Hasil Realisasi',
    primaryCtaHref: '#realisasi',
  },
];

export const STAT_INDICATORS: StatIndicator[] = [
  {
    value: '2.846',
    label: 'Total Usulan',
    description: 'Usulan masuk kelurahan',
    toneClass: 'text-on-surface',
  },
  {
    value: '2.310',
    label: 'Terverifikasi',
    description: 'Lolos survei teknis lapangan',
    toneClass: 'text-on-surface',
  },
  {
    value: '1.645',
    label: 'Penerima Bantuan',
    description: 'Ditetapkan melalui SK Wali Kota',
    toneClass: 'text-secondary',
  },
  {
    value: '318',
    label: 'Dalam Pengerjaan',
    description: 'Tahap konstruksi berjalan',
    toneClass: 'text-warning',
  },
  {
    value: '1.327',
    label: 'Selesai',
    description: 'Hunian layak diserahterimakan',
    toneClass: 'text-primary',
  },
];

export const RTLH_ROWS: RtlhRow[] = [
  {
    id: 'bandar-lor',
    kec: 'Mojoroto',
    kel: 'Bandar Lor',
    usulan: 142,
    verif: 120,
    tetap: 94,
    proses: 8,
    selesai: 86,
    programUtama: 'RTLH APBD Kota Kediri',
    prioritasSanitasi: 24,
  },
  {
    id: 'campurejo',
    kec: 'Mojoroto',
    kel: 'Campurejo',
    usulan: 118,
    verif: 96,
    tetap: 72,
    proses: 14,
    selesai: 58,
    programUtama: 'BSPS (APBN)',
    prioritasSanitasi: 19,
  },
  {
    id: 'balowerti',
    kec: 'Kota',
    kel: 'Balowerti',
    usulan: 95,
    verif: 82,
    tetap: 61,
    proses: 9,
    selesai: 52,
    programUtama: 'RTLH APBD Kota Kediri',
    prioritasSanitasi: 15,
  },
  {
    id: 'ngronggo',
    kec: 'Kota',
    kel: 'Ngronggo',
    usulan: 134,
    verif: 112,
    tetap: 88,
    proses: 16,
    selesai: 72,
    programUtama: 'BSPS (APBN)',
    prioritasSanitasi: 27,
  },
  {
    id: 'bangsal',
    kec: 'Pesantren',
    kel: 'Bangsal',
    usulan: 156,
    verif: 128,
    tetap: 95,
    proses: 18,
    selesai: 77,
    programUtama: 'RTLH APBD Kota Kediri',
    prioritasSanitasi: 31,
  },
  {
    id: 'banaran',
    kec: 'Pesantren',
    kel: 'Banaran',
    usulan: 109,
    verif: 89,
    tetap: 64,
    proses: 11,
    selesai: 53,
    programUtama: 'Bantuan Khusus Stimulan',
    prioritasSanitasi: 18,
  },
  {
    id: 'lirboyo',
    kec: 'Mojoroto',
    kel: 'Lirboyo',
    usulan: 124,
    verif: 105,
    tetap: 80,
    proses: 12,
    selesai: 68,
    programUtama: 'RTLH APBD Kota Kediri',
    prioritasSanitasi: 21,
  },
  {
    id: 'setonopande',
    kec: 'Kota',
    kel: 'Setonopande',
    usulan: 87,
    verif: 74,
    tetap: 55,
    proses: 7,
    selesai: 48,
    programUtama: 'Bantuan Khusus Stimulan',
    prioritasSanitasi: 14,
  },
  {
    id: 'bawang',
    kec: 'Pesantren',
    kel: 'Bawang',
    usulan: 131,
    verif: 108,
    tetap: 79,
    proses: 15,
    selesai: 64,
    programUtama: 'BSPS (APBN)',
    prioritasSanitasi: 22,
  },
  {
    id: 'sukorame',
    kec: 'Mojoroto',
    kel: 'Sukorame',
    usulan: 112,
    verif: 91,
    tetap: 68,
    proses: 10,
    selesai: 58,
    programUtama: 'BSPS (APBN)',
    prioritasSanitasi: 17,
  },
  {
    id: 'dandong',
    kec: 'Kota',
    kel: 'Dandong',
    usulan: 78,
    verif: 64,
    tetap: 48,
    proses: 6,
    selesai: 42,
    programUtama: 'RTLH APBD Kota Kediri',
    prioritasSanitasi: 11,
  },
  {
    id: 'tempurejo',
    kec: 'Pesantren',
    kel: 'Tempurejo',
    usulan: 120,
    verif: 98,
    tetap: 73,
    proses: 13,
    selesai: 60,
    programUtama: 'RTLH APBD Kota Kediri',
    prioritasSanitasi: 20,
  },
];

export const DISTRICT_DATA: Record<Kecamatan, DistrictStat> = {
  Mojoroto: {
    name: 'Mojoroto',
    kel: '14 Kelurahan',
    usulan: '1.084 Unit',
    proses: '124 Unit',
    selesai: '512 Unit',
    persentaseRealisasi: '80,5%',
    pinPosition: { top: '32%', left: '24%' },
    pinVariant: 'primary',
  },
  Kota: {
    name: 'Kota',
    kel: '17 Kelurahan',
    usulan: '842 Unit',
    proses: '92 Unit',
    selesai: '418 Unit',
    persentaseRealisasi: '82,0%',
    pinPosition: { top: '46%', left: '52%' },
    pinVariant: 'secondary',
  },
  Pesantren: {
    name: 'Pesantren',
    kel: '15 Kelurahan',
    usulan: '920 Unit',
    proses: '102 Unit',
    selesai: '397 Unit',
    persentaseRealisasi: '79,6%',
    pinPosition: { top: '58%', left: '72%' },
    pinVariant: 'dark-blue',
  },
};

export const PROGRAM_ITEMS: ProgramItem[] = [
  {
    id: 'bsps',
    kicker: 'APBN • Kementerian PKP',
    kickerColor: 'secondary',
    title: 'BSPS',
    shortTitleMobile: 'BSPS (APBN)',
    description:
      'Bantuan Stimulan Perumahan Swadaya bagi masyarakat berpenghasilan rendah untuk mendorong keswadayaan peningkatan struktur atap, lantai, dan dinding.',
    shortDescriptionMobile: 'Bantuan Stimulan Perumahan Swadaya peningkatan struktur hunian.',
    ctaLabel: 'Pelajari Kriteria BSPS',
    imageUrl: IMAGES.programBsps,
    imageAlt: 'Pembangunan rumah swadaya program BSPS di Kota Kediri',
    sumberDana: 'APBN — Kementerian Perumahan dan Kawasan Permukiman (PKP)',
    nilaiStimulan: 'Rp 20.000.000 / unit (Material & Upah Kerja)',
    sasaran: 'Masyarakat Berpenghasilan Rendah (MBR) dengan keswadayaan kelompok',
    kriteria: [
      'Warga Negara Indonesia yang berdomisili dan memiliki KTP Kota Kediri.',
      'Sudah berkeluarga dan memiliki penghasilan di bawah upah minimum daerah.',
      'Menguasai atau memiliki tanah dengan alas hak yang sah dan tidak dalam sengketa.',
      'Rumah satu-satunya mengalami kerusakan komponen struktur (atap, lantai, atau dinding).',
      'Belum pernah menerima bantuan perumahan sejenis dalam 10 tahun terakhir.',
    ],
    persyaratan: [
      'Fotokopi KTP dan Kartu Keluarga (KK) pemohon.',
      'Fotokopi bukti kepemilikan lahan (SHM / Akta Jual Beli / Letter C sah).',
      'Surat usulan dari kelurahan dan berita acara verifikasi tim teknis DPKP.',
      'Kesediaan membentuk Kelompok Penerima Bantuan (KPB) secara swadaya.',
    ],
  },
  {
    id: 'apbd',
    kicker: 'APBD • Pemerintah Kota Kediri',
    kickerColor: 'primary',
    title: 'RTLH APBD Kota',
    shortTitleMobile: 'RTLH APBD Kota Kediri',
    description:
      'Program perbaikan rumah tidak layak huni yang bersumber dari APBD Kota Kediri untuk mempercepat pengentasan kawasan permukiman kumuh perkotaan.',
    shortDescriptionMobile: 'Perbaikan rumah tidak layak huni melalui APBD daerah.',
    ctaLabel: 'Pelajari Kriteria APBD Kota',
    imageUrl: IMAGES.programApbd,
    imageAlt: 'Rumah warga hasil perbaikan program RTLH APBD Kota Kediri',
    sumberDana: 'APBD Pemerintah Kota Kediri (Dinas Perumahan & Kawasan Permukiman)',
    nilaiStimulan: 'Rp 17.500.000 – Rp 25.000.000 / unit sesuai tingkat kerusakan',
    sasaran: 'Rumah tangga miskin/rentan pada basis data kemiskinan daerah di 46 kelurahan',
    kriteria: [
      'Tercatat dalam basis data kemiskinan daerah Kota Kediri atau usulan resmi Musrenbang Kelurahan.',
      'Memiliki kondisi lantai tanah/rusak, dinding tidak permanen/rapuh, atau atap bocor berat.',
      'Memiliki luas lantai per kapita kurang dari 7,2 m² atau ventilasi/pencahayaan tidak memenuhi syarat sehat.',
      'Berada di lahan permukiman yang sesuai Rencana Tata Ruang Wilayah (RTRW) Kota Kediri.',
    ],
    persyaratan: [
      'Fotokopi KTP dan KK Kota Kediri minimal berdomisili 3 tahun.',
      'Bukti penguasaan lahan yang sah dan diketahui Lurah setempat.',
      'Foto kondisi eksisting bangunan (tampak depan, samping, dalam, atap, lantai, sanitasi).',
      'Penetapan melalui Keputusan Wali Kota Kediri setelah lolos verifikasi lapangan.',
    ],
  },
  {
    id: 'stimulan',
    kicker: 'Penanganan Khusus & Prioritas',
    kickerColor: 'warning',
    title: 'Bantuan Khusus Stimulan',
    shortTitleMobile: 'Bantuan Khusus Stimulan',
    description:
      'Dukungan stimulan material dan pendampingan teknis bagi hunian terdampak kondisi darurat, lansia tunggal, serta keluarga rentan di Kota Kediri.',
    shortDescriptionMobile: 'Dukungan darurat dan prioritas bagi lansia tunggal serta keluarga rentan.',
    ctaLabel: 'Pelajari Bantuan Khusus',
    imageUrl: IMAGES.programStimulan,
    imageAlt: 'Peningkatan sanitasi dan struktur hunian melalui Bantuan Khusus Stimulan',
    sumberDana: 'Belanja Prioritas Daerah & Sinergi Penanganan Darurat DPKP Kota Kediri',
    nilaiStimulan: 'Stimulan material bangunan & perbaikan sanitasi dasar terpadu',
    sasaran: 'Lansia tunggal, penyandang disabilitas, serta hunian terdampak bencana ringan/kerusakan mendesak',
    kriteria: [
      'Hunian mengalami kerusakan mendadak akibat cuaca ekstrem, pohon tumbang, atau pelapukan struktural kritis.',
      'Penghuni merupakan kategori rentan (lansia tunggal, kepala keluarga disabilitas, atau tanpa penopang ekonomi).',
      'Memerlukan perbaikan cepat pada struktur keselamatan utama dan akses jamban/sanitasi sehat.',
    ],
    persyaratan: [
      'Surat Keterangan Kondisi Darurat / Rekomendasi Resmi dari Kelurahan dan Kecamatan.',
      'Identitas kependudukan (KTP/KK) penghuni dan surat pernyataan kepemilikan tempat tinggal.',
      'Hasil kaji cepat tim teknis DPKP dan pendampingan swadaya lingkungan RT/RW.',
    ],
  },
];

export const PROCESS_STEPS: ProcessStep[] = [
  {
    step: 1,
    title: 'Pendataan',
    description: 'Pengusulan awal melalui kelurahan dan basis data kemiskinan daerah.',
    markerColor: 'primary',
    outputDokumen: 'Daftar Usulan Kelurahan',
  },
  {
    step: 2,
    title: 'Verifikasi',
    description: 'Peninjauan kelayakan teknis bangunan dan status kepemilikan lahan.',
    markerColor: 'primary',
    outputDokumen: 'Berita Acara Survei Lapangan',
  },
  {
    step: 3,
    title: 'Seleksi Prioritas',
    description: 'Penilaian tingkat kerusakan struktur atap, lantai, dinding, dan sanitasi.',
    markerColor: 'primary',
    outputDokumen: 'Skoring Kelayakan Teknis',
  },
  {
    step: 4,
    title: 'Penetapan',
    description: 'Pengesahan daftar penerima bantuan melalui Keputusan Wali Kota Kediri.',
    markerColor: 'secondary',
    outputDokumen: 'SK Wali Kota Kediri',
  },
  {
    step: 5,
    title: 'Realisasi',
    description: 'Penyaluran bahan bangunan dan pelaksanaan konstruksi didampingi fasilitator.',
    markerColor: 'khaki',
    outputDokumen: 'Laporan Progres Konstruksi',
  },
  {
    step: 6,
    title: 'Selesai',
    description: 'Pemeriksaan hasil akhir 100% dan pencatatan sebagai hunian layak.',
    markerColor: 'primary',
    isFinal: true,
    outputDokumen: 'Berita Acara Serah Terima',
  },
];

export const REALISASI_CASES: RealisasiCase[] = [
  {
    id: 'bandar-lor-2026',
    lokasi: 'Kelurahan Bandar Lor, Kec. Mojoroto',
    kecamatan: 'Mojoroto',
    jenisBantuan: 'RTLH APBD Kota Kediri',
    tahun: '2026',
    beforeImageUrl: IMAGES.realisasiBefore,
    beforeAlt: 'Kondisi rumah sebelum perbaikan RTLH di Kelurahan Bandar Lor',
    beforeCaption:
      'Dinding bata belum terplester, rangka atap lapuk, dan pencahayaan alami terbatas.',
    afterImageUrl: IMAGES.realisasiAfter,
    afterAlt: 'Kondisi rumah sesudah perbaikan RTLH di Kelurahan Bandar Lor',
    afterCaption:
      'Struktur dinding kokoh dan terplester rapi, atap genteng baru, serta ventilasi sehat.',
    ringkasanTeknis: [
      'Penggantian rangka atap kayu lapuk menjadi baja ringan & genteng baru',
      'Plesteran, acian dinding bata, serta pengecatan ulang eksterior dan interior',
      'Pemasangan jendela ventilasi silang dan perbaikan lantai keramik',
    ],
  },
  {
    id: 'ngronggo-2026',
    lokasi: 'Kelurahan Ngronggo, Kec. Kota',
    kecamatan: 'Kota',
    jenisBantuan: 'BSPS (APBN)',
    tahun: '2026',
    beforeImageUrl: IMAGES.realisasiBefore,
    beforeAlt: 'Kondisi awal bangunan sebelum perbaikan BSPS di Kelurahan Ngronggo',
    beforeCaption:
      'Bangunan berdinding rapuh dan atap bocor tanpa bukaan ventilasi udara yang memadai.',
    afterImageUrl: IMAGES.programBsps,
    afterAlt: 'Hasil peningkatan struktur hunian program BSPS di Kelurahan Ngronggo',
    afterCaption:
      'Struktur sloof dan kolom diperkuat, dinding permanen rapi, dan sirkulasi udara optimal.',
    ringkasanTeknis: [
      'Perkuatan struktur kolom beton bertulang dan balok ring',
      'Peningkatan tinggi plafon untuk pencahayaan serta sirkulasi udara sehat',
      'Penambahan saluran pembuangan air limbah rumah tangga tertutup',
    ],
  },
  {
    id: 'bangsal-2026',
    lokasi: 'Kelurahan Bangsal, Kec. Pesantren',
    kecamatan: 'Pesantren',
    jenisBantuan: 'Bantuan Khusus Stimulan',
    tahun: '2026',
    beforeImageUrl: IMAGES.realisasiBefore,
    beforeAlt: 'Kondisi awal hunian keluarga rentan di Kelurahan Bangsal',
    beforeCaption:
      'Kerusakan atap dan dinding pada hunian warga lansia dengan akses sanitasi terbatas.',
    afterImageUrl: IMAGES.programStimulan,
    afterAlt: 'Hasil perbaikan struktur dan sanitasi di Kelurahan Bangsal',
    afterCaption:
      'Hunian aman dari kebocoran, dilengkapi fasilitas MCK sehat dan pencahayaan cukup.',
    ringkasanTeknis: [
      'Rehabilitasi menyeluruh penutup atap dan talang air hujan',
      'Pembangunan bilik sanitasi sehat dan tangki septik komunal',
      'Pendampingan swadaya tenaga oleh fasilitator dan warga sekitar',
    ],
  },
];

export const PUBLICATIONS: PublicationItem[] = [
  {
    id: 'verifikasi-420-unit',
    type: 'Berita',
    meta: 'Berita • 18 November 2026',
    title: 'DPKP Kota Kediri Rampungkan Verifikasi 420 Unit Usulan RTLH Tahap Akhir',
    summary:
      'Tim fasilitator lapangan bersama perangkat kelurahan menyelesaikan verifikasi teknis untuk penetapan alokasi anggaran tahun berjalan.',
    imageUrl: IMAGES.beritaPenyerahan,
    imageAlt: 'Rapat koordinasi penyaluran bantuan RTLH Kota Kediri',
    fullContent: [
      'Dinas Perumahan dan Kawasan Permukiman (DPKP) Kota Kediri resmi merampungkan proses verifikasi teknis lapangan terhadap 420 unit rumah tidak layak huni pada tahap akhir tahun anggaran 2026.',
      'Kegiatan verifikasi melibatkan tim fasilitator teknis lapangan bersama perangkat kelurahan di tiga kecamatan — Mojoroto, Kota, dan Pesantren — guna memastikan kesesuaian kondisi fisik bangunan dengan kriteria teknis keselamatan struktur, kecukupan luas lantai, serta ketersediaan sanitasi dasar.',
      'Hasil verifikasi ini selanjutnya menjadi dasar penyusunan daftar calon penerima bantuan yang akan ditetapkan melalui Keputusan Wali Kota Kediri sebelum memasuki tahap penyaluran bahan bangunan.',
    ],
  },
  {
    id: 'jadwal-sosialisasi-bsps-2027',
    type: 'Pengumuman',
    meta: 'Pengumuman • 10 November 2026',
    title: 'Jadwal Sosialisasi Mekanisme Pengusulan BSPS Tahun Anggaran 2027',
    fullContent: [
      'DPKP Kota Kediri akan menyelenggarakan sosialisasi mekanisme pengusulan Bantuan Stimulan Perumahan Swadaya (BSPS) untuk tahun anggaran 2027 kepada seluruh operator kelurahan dan tim pendamping wilayah.',
      'Sosialisasi dilaksanakan secara bertahap per kecamatan: Kecamatan Mojoroto (Senin, 1 Desember 2026), Kecamatan Kota (Rabu, 3 Desember 2026), dan Kecamatan Pesantren (Jumat, 5 Desember 2026) di Aula DPKP Kota Kediri, Jl. Mayor Bismo No. 4.',
      'Perangkat kelurahan diharapkan membawa rekap usulan awal serta kelengkapan verifikasi alas hak tanah warga.',
    ],
  },
  {
    id: 'laporan-triwulan-iii-2026',
    type: 'Laporan Publik',
    meta: 'Laporan Publik • PDF 2,4 MB',
    title: 'Laporan Realisasi Penanganan RTLH Kota Kediri Triwulan III 2026',
    downloadFilename: 'Laporan-Realisasi-RTLH-Kediri-TW3-2026.txt',
    fullContent: [
      'Dokumen ringkasan capaian realisasi penanganan Rumah Tidak Layak Huni (RTLH) Pemerintah Kota Kediri periode Triwulan III Tahun 2026.',
      'Total usulan tercatat: 2.846 unit | Terverifikasi lapangan: 2.310 unit | Ditetapkan penerima bantuan: 1.645 unit | Dalam pengerjaan: 318 unit | Selesai 100%: 1.327 unit.',
      'Sebaran realisasi selesai per kecamatan: Mojoroto (512 unit), Kota (418 unit), Pesantren (397 unit).',
    ],
  },
];

export const FAQ_ITEMS: FaqItem[] = [
  {
    id: 0,
    question: 'Bagaimana cara mengusulkan bantuan RTLH?',
    answer:
      'Pengusulan dilakukan melalui kantor kelurahan setempat dengan melampirkan fotokopi KTP, KK, dan bukti kepemilikan lahan yang sah untuk diverifikasi tim DPKP.',
  },
  {
    id: 1,
    question: 'Apa kriteria rumah yang masuk kategori RTLH?',
    answer:
      'Meliputi ketidaksesuaian keselamatan struktur bangunan, luas lantai per kapita tidak mencukupi, serta ketiadaan akses air minum dan sanitasi layak.',
  },
  {
    id: 2,
    question: 'Apakah proses pengusulan dipungut biaya?',
    answer:
      'Seluruh tahapan pendataan, verifikasi, hingga penetapan penerima bantuan tidak dipungut biaya apa pun (gratis).',
  },
];
