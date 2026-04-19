<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Tampilkan halaman Transparansi APBDes
     */
    public function transparansi()
    {
        $pageTitle = "Transparansi APBDes";
        $pageSubtitle = "Wujud nyata keterbukaan informasi publik. Kami menyajikan rincian Anggaran Pendapatan dan Belanja Desa secara jujur, akuntabel, dan transparan.";

        // Data APBDes (Idealnya dari database, dibuat array statis sesuai HTML untuk sekarang)
        $apbdesRingkasan = [
            'pendapatan_target' => 1850500000,
            'pendapatan_realisasi' => 1850500000,
            'belanja_target' => 1790000000,
            'belanja_realisasi' => 1718400000,
            'pembiayaan_netto' => 15000000, // Penerimaan - Pengeluaran
            'silpa' => 75500000
        ];

        $rincianPendapatan = [
            ['uraian' => 'Pendapatan Asli Desa (PADes)', 'anggaran' => 150000000],
            ['uraian' => 'Dana Desa (DD)', 'anggaran' => 950500000],
            ['uraian' => 'Alokasi Dana Desa (ADD)', 'anggaran' => 450000000],
            ['uraian' => 'Bagi Hasil Pajak & Retribusi', 'anggaran' => 85000000],
            ['uraian' => 'Bantuan Keuangan Provinsi', 'anggaran' => 215000000],
        ];

        $rincianBelanja = [
            ['bidang' => 'Penyelenggaraan Pemerintahan', 'anggaran' => 580000000],
            ['bidang' => 'Pelaksanaan Pembangunan', 'anggaran' => 850000000],
            ['bidang' => 'Pembinaan Kemasyarakatan', 'anggaran' => 120000000],
            ['bidang' => 'Pemberdayaan Masyarakat', 'anggaran' => 165000000],
            ['bidang' => 'Penanggulangan Bencana/Darurat', 'anggaran' => 75000000],
        ];

        $rincianPembiayaan = [
            'penerimaan' => [
                ['uraian' => 'SiLPA Tahun Sebelumnya', 'anggaran' => 65000000]
            ],
            'pengeluaran' => [
                ['uraian' => 'Penyertaan Modal Desa (BUMDes)', 'anggaran' => 50000000]
            ]
        ];

        $arsipTransparansi = [
            [
                'tahun' => 2023,
                'status' => 'Selesai',
                'pendapatan' => 1750000000,
                'belanja' => 1685000000,
                'silpa' => 65000000
            ],
            [
                'tahun' => 2022,
                'status' => 'Selesai',
                'pendapatan' => 1620000000,
                'belanja' => 1590000000,
                'silpa' => 30000000
            ],
            [
                'tahun' => 2021,
                'status' => 'Selesai',
                'pendapatan' => 1550000000,
                'belanja' => 1545000000,
                'silpa' => 5000000
            ]
        ];

        return view('pages.frontend.transparansi', compact(
            'pageTitle',
            'pageSubtitle',
            'apbdesRingkasan',
            'rincianPendapatan',
            'rincianBelanja',
            'rincianPembiayaan',
            'arsipTransparansi'
        ));
    }

    /**
     * Tampilkan halaman Berita & Artikel
     */
    public function beritaArtikel()
    {
        $pageTitle = "Berita & Artikel Desa";
        $pageSubtitle = "Dapatkan informasi terbaru seputar kegiatan, pembangunan, pemberdayaan masyarakat, dan pengumuman resmi Desa Sindangmukti.";

        $sorotanUtama = [
            'kategori' => 'Pemerintahan',
            'tanggal' => '24 Okt 2024',
            'judul' => 'Musyawarah Rencana Pembangunan (Musrenbang) Desa Sindangmukti Tahun 2025',
            'ringkasan' => 'Pemerintah Desa Sindangmukti menggelar Musrenbang untuk menyusun Rencana Kerja Pemerintah Desa (RKPDes) tahun 2025. Acara ini dihadiri oleh tokoh masyarakat, RT/RW, dan perwakilan BPD untuk memastikan aspirasi warga tersalurkan secara maksimal.',
            'penulis' => 'Admin Desa',
            'gambar' => 'https://images.unsplash.com/photo-1596395819057-e37f55a8516d?auto=format&fit=crop&q=80&w=1200'
        ];

        $daftarArtikel = [
            [
                'kategori' => 'Pertanian',
                'tanggal' => '20 Okt 2024',
                'judul' => 'Panen Raya Jagung Hibrida Tembus Target, Petani Semringah',
                'ringkasan' => 'Kelompok Tani Mekar Jaya Desa Sindangmukti melaksanakan panen raya jagung hibrida binaan desa dengan hasil yang memuaskan dan melebihi target tahun lalu.',
                'gambar' => 'https://images.unsplash.com/photo-1592838064575-70ed626d3a0e?auto=format&fit=crop&q=80&w=800'
            ],
            [
                'kategori' => 'Bantuan Sosial',
                'tanggal' => '18 Okt 2024',
                'judul' => 'Penyaluran BLT Dana Desa Tahap III Berjalan Lancar',
                'ringkasan' => 'Pemerintahan Desa Sindangmukti telah sukses menyalurkan Bantuan Langsung Tunai (BLT) Dana Desa Tahap III kepada 85 Keluarga Penerima Manfaat.',
                'gambar' => 'https://images.unsplash.com/photo-1577563908411-5077b6dc7624?auto=format&fit=crop&q=80&w=800'
            ],
            [
                'kategori' => 'Kesehatan',
                'tanggal' => '15 Okt 2024',
                'judul' => 'Posyandu Remaja & Edukasi Pencegahan Stunting Dini',
                'ringkasan' => 'Kader Posyandu bekerjasama dengan Puskesmas setempat mengadakan penyuluhan gizi bagi remaja putri untuk mencegah resiko stunting di masa depan.',
                'gambar' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&q=80&w=800'
            ],
            [
                'kategori' => 'Pembangunan',
                'tanggal' => '10 Okt 2024',
                'judul' => 'Pengaspalan Jalan Dusun II Sepanjang 1,2 KM Telah Rampung',
                'ringkasan' => 'Proyek pengaspalan jalan poros Dusun II yang bersumber dari Dana Desa tahun ini telah selesai 100%, akses perekonomian warga kini lebih mudah.',
                'gambar' => 'https://images.unsplash.com/photo-1621644782084-245781a812da?auto=format&fit=crop&q=80&w=800'
            ],
            [
                'kategori' => 'Pemberdayaan',
                'tanggal' => '05 Okt 2024',
                'judul' => 'Pelatihan Digital Marketing Bagi Pelaku UMKM Kerajinan Bambu',
                'ringkasan' => 'BUMDes memfasilitasi puluhan pengrajin bambu lokal untuk go-digital melalui pelatihan pemasaran online guna menjangkau pasar nasional.',
                'gambar' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&q=80&w=800'
            ],
            [
                'kategori' => 'Kegiatan',
                'tanggal' => '01 Okt 2024',
                'judul' => 'Budaya Gotong Royong Membersihkan Saluran Irigasi Jelang Musim Hujan',
                'ringkasan' => 'Warga Desa Sindangmukti serentak melakukan kerja bakti membersihkan irigasi persawahan untuk mengantisipasi banjir di musim penghujan.',
                'gambar' => 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&q=80&w=800'
            ],
        ];

        return view('pages.frontend.informasi.berita-artikel', compact('pageTitle', 'pageSubtitle', 'sorotanUtama', 'daftarArtikel'));
    }

    /**
     * Tampilkan halaman Pengumuman & Agenda
     */
    public function pengumumanAgenda()
    {
        $pageTitle = "Pengumuman & Agenda";
        $pageSubtitle = "Pusat informasi resmi terkini dan jadwal kegiatan mendatang di lingkungan Pemerintahan Desa Sindangmukti.";

        $pengumumanPenting = [
            'tanggal' => '24 Okt 2024',
            'judul' => 'Pemadaman Listrik Bergilir & Perbaikan Jaringan Air Bersih di Dusun I dan II',
            'ringkasan' => 'Sehubungan dengan adanya perbaikan gardu induk PLN dan pipanisasi saluran air bersih desa, akan dilakukan pemadaman listrik sementara dan penghentian aliran air pada hari Sabtu, 28 Oktober 2024 mulai pukul 08.00 - 15.00 WIB. Dimohon warga mempersiapkan cadangan air.'
        ];

        $daftarAgenda = [
            [
                'hari_tanggal' => '10',
                'bulan' => 'Okt',
                'tema_warna' => 'green',
                'judul' => 'Penyuluhan Stunting & Posyandu Serentak',
                'waktu' => '08.00 - Selesai',
                'lokasi' => 'Balai Desa Sindangmukti',
                'ringkasan' => 'Kegiatan rutin bulanan bagi balita dan ibu hamil yang akan dipantau langsung oleh tim medis dari Puskesmas kecamatan. Wajib membawa buku KIA.',
                'detail' => [
                    'tanggal_full' => 'Kamis, 10 Oktober 2024',
                    'peta_link' => '#',
                    'kontak' => 'Ibu Siti Aminah (Bidan Desa) - 0812 3456 7890',
                    'deskripsi' => 'Pemerintah Desa Sindangmukti bekerja sama dengan Puskesmas Kecamatan mengundang seluruh ibu hamil dan orang tua yang memiliki anak usia balita (0-5 tahun) untuk hadir dalam kegiatan Posyandu Serentak dan Penyuluhan Pencegahan Stunting.',
                    'poin' => [
                        'Penimbangan berat badan dan pengukuran tinggi badan anak.',
                        'Pemberian makanan tambahan (PMT) bergizi.',
                        'Imunisasi dasar lengkap (bagi yang belum).',
                        'Penyuluhan gizi bagi ibu hamil dan menyusui.'
                    ],
                    'catatan' => 'Harap membawa Buku KIA (Kesehatan Ibu dan Anak) sebagai syarat pendaftaran.',
                    'gambar' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&q=80&w=800'
                ]
            ],
            [
                'hari_tanggal' => '15',
                'bulan' => 'Okt',
                'tema_warna' => 'amber',
                'judul' => 'Rapat Koordinasi BPD & Perangkat Desa (Musdes)',
                'waktu' => '19.30 - 22.00',
                'lokasi' => 'Ruang Rapat Kantor Desa',
                'ringkasan' => 'Pembahasan evaluasi rancangan Anggaran Pendapatan dan Belanja Desa (APBDes) Perubahan untuk kuartal akhir tahun 2024 bersama anggota BPD.',
                'detail' => null
            ],
            [
                'hari_tanggal' => '25',
                'bulan' => 'Okt',
                'tema_warna' => 'blue',
                'judul' => 'Kerja Bakti Massal Normalisasi Saluran Irigasi',
                'waktu' => '07.00 - 11.00',
                'lokasi' => 'Sepanjang Jalur Irigasi Dusun II',
                'ringkasan' => 'Diimbau kepada seluruh warga Dusun II laki-laki untuk membawa peralatan seperti cangkul dan sabit guna membersihkan rumput dan lumpur di saluran air.',
                'detail' => null
            ]
        ];

        return view('pages.frontend.informasi.pengumuman-agenda', compact('pageTitle', 'pageSubtitle', 'pengumumanPenting', 'daftarAgenda'));
    }

    /**
     * Tampilkan halaman Produk Hukum Desa
     */
    public function produkHukum()
    {
        $pageTitle = "JDIH & Produk Hukum Desa";
        $pageSubtitle = "Wadah keterbukaan informasi produk hukum pemerintahan. Jelajahi Peraturan Desa, Keputusan Kepala Desa, dan dokumen regulasi resmi lainnya.";

        $statistikHukum = [
            'perdes' => 45,
            'sk_kades' => 112,
            'perkades' => 24,
            'kep_bpd' => 18
        ];

        $daftarHukum = [
            [
                'id' => 'perdes-04-2024',
                'tipe_icon' => 'fa-file-pdf',
                'tipe_warna' => 'red', // red untuk pdf
                'kategori' => 'Peraturan Desa',
                'kategori_warna' => 'blue',
                'status' => 'Berlaku',
                'status_ikon' => 'fa-check-circle',
                'status_warna' => 'green',
                'judul' => 'Peraturan Desa Sindangmukti Nomor 04 Tahun 2024',
                'tentang' => 'Rencana Kerja Pemerintah Desa (RKPDes) Tahun Anggaran 2025',
                'ditetapkan' => '15 Okt 2024',
                'unduhan' => 124,
                'detail' => [
                    'nomor' => '04',
                    'tahun' => '2024',
                    'diundangkan' => '17 Okt 2024',
                    'pemrakarsa' => 'Pemerintah Desa',
                    'penandatangan' => 'Kepala Desa Sindangmukti',
                    'file_nama' => 'Perdes_04_2024_RKPDes.pdf',
                    'file_ukuran' => '2.4 MB',
                    'file_tipe' => 'PDF',
                    'link_unduh' => '#'
                ]
            ],
            [
                'id' => 'sk-141-015-2024',
                'tipe_icon' => 'fa-file-pdf',
                'tipe_warna' => 'red',
                'kategori' => 'SK Kepala Desa',
                'kategori_warna' => 'emerald',
                'status' => 'Berlaku',
                'status_ikon' => 'fa-check-circle',
                'status_warna' => 'green',
                'judul' => 'Keputusan Kepala Desa Sindangmukti Nomor 141/015/SK/2024',
                'tentang' => 'Pembentukan Susunan Pengurus Karang Taruna "Bina Karya" Masa Bakti 2024-2027',
                'ditetapkan' => '02 Sep 2024',
                'unduhan' => 85,
                'detail' => [
                    'nomor' => '141/015/SK/2024',
                    'tahun' => '2024',
                    'diundangkan' => '03 Sep 2024',
                    'pemrakarsa' => 'Pemerintah Desa',
                    'penandatangan' => 'Kepala Desa Sindangmukti',
                    'file_nama' => 'SK_Karang_Taruna_2024.pdf',
                    'file_ukuran' => '1.2 MB',
                    'file_tipe' => 'PDF',
                    'link_unduh' => '#'
                ]
            ],
            [
                'id' => 'perdes-02-2020',
                'tipe_icon' => 'fa-file-pdf',
                'tipe_warna' => 'gray', // karena dicabut
                'kategori' => 'Peraturan Desa',
                'kategori_warna' => 'blue',
                'status' => 'Tidak Berlaku / Dicabut',
                'status_ikon' => 'fa-ban',
                'status_warna' => 'red',
                'judul' => 'Peraturan Desa Sindangmukti Nomor 02 Tahun 2020',
                'tentang' => 'Pembentukan Badan Usaha Milik Desa (BUMDes) "Sejahtera"',
                'keterangan_status' => '*Telah digantikan oleh Perdes No. 01 Tahun 2024',
                'ditetapkan' => '10 Feb 2020',
                'unduhan' => 0, // opsional untuk dicabut
                'detail' => [
                    'nomor' => '02',
                    'tahun' => '2020',
                    'diundangkan' => '12 Feb 2020',
                    'pemrakarsa' => 'Pemerintah Desa',
                    'penandatangan' => 'Kepala Desa Sindangmukti',
                    'file_nama' => 'Perdes_02_2020_BUMDes.pdf',
                    'file_ukuran' => '3.1 MB',
                    'file_tipe' => 'PDF',
                    'link_unduh' => '#'
                ]
            ]
        ];

        return view('pages.frontend.informasi.produk-hukum', compact('pageTitle', 'pageSubtitle', 'statistikHukum', 'daftarHukum'));
    }

    /**
     * Tampilkan halaman Informasi Publik
     */
    public function informasiPublik()
    {
        $pageTitle = "Keterbukaan Informasi Publik";
        $pageSubtitle = "Kami berkomitmen mewujudkan tata kelola pemerintahan yang baik (Good Governance) melalui layanan informasi publik yang transparan, akurat, dan mudah diakses.";

        $daftarInformasi = [
            [
                'id' => 'info-01',
                'format_icon' => 'fa-file-pdf',
                'format_warna' => 'gray', // gray class in design
                'kategori' => 'Informasi Berkala',
                'kategori_warna' => 'blue',
                'sektor' => 'Pemerintahan',
                'sektor_warna' => 'gray',
                'judul' => 'Laporan Penyelenggaraan Pemerintahan Desa (LPPD) Akhir Tahun 2023',
                'ringkasan' => 'Laporan realisasi pelaksanaan program dan kegiatan pemerintahan desa selama tahun anggaran 2023.',
                'diperbarui' => '15 Jan 2024',
                'ukuran' => '3.2 MB',
                'detail' => [
                    'penanggung_jawab' => 'Sekretaris Desa',
                    'waktu_pembuatan' => '10 Januari 2024',
                    'waktu_publikasi' => '15 Januari 2024',
                    'format_ukuran' => 'PDF Document (3.2 MB)',
                    'deskripsi' => 'Dokumen LPPD ini memuat laporan pertanggungjawaban Kepala Desa atas pelaksanaan program kerja, realisasi anggaran, serta capaian target pembangunan selama satu tahun penuh di tahun 2023. Dokumen ini juga telah diserahkan kepada BPD sebagai bentuk fungsi pengawasan.',
                    'link_unduh' => '#'
                ]
            ],
            [
                'id' => 'info-02',
                'format_icon' => 'fa-file-excel',
                'format_warna' => 'green',
                'kategori' => 'Informasi Setiap Saat',
                'kategori_warna' => 'emerald',
                'sektor' => 'Keuangan & Aset',
                'sektor_warna' => 'gray',
                'judul' => 'Buku Inventaris Kekayaan dan Aset Desa Tahun 2024',
                'ringkasan' => 'Daftar lengkap pencatatan aset desa baik berupa tanah, bangunan, peralatan mesin, hingga aset tak berwujud.',
                'diperbarui' => '02 Feb 2024',
                'ukuran' => '1.5 MB',
                'detail' => [
                    'penanggung_jawab' => 'Kaur Tata Usaha dan Umum',
                    'waktu_pembuatan' => '20 Januari 2024',
                    'waktu_publikasi' => '02 Februari 2024',
                    'format_ukuran' => 'Excel Document (1.5 MB)',
                    'deskripsi' => 'Dokumen ini merupakan rekapitulasi aset desa di tahun 2024.',
                    'link_unduh' => '#'
                ]
            ],
            [
                'id' => 'info-03',
                'format_icon' => 'fa-file-pdf',
                'format_warna' => 'gray',
                'kategori' => 'Informasi Berkala',
                'kategori_warna' => 'blue',
                'sektor' => 'Pembangunan',
                'sektor_warna' => 'gray',
                'judul' => 'Rencana Anggaran Biaya (RAB) Pembangunan Infrastruktur 2024',
                'ringkasan' => 'Dokumen rincian biaya proyek pembangunan fisik desa, termasuk pengaspalan jalan dusun dan renovasi posyandu.',
                'diperbarui' => '10 Mar 2024',
                'ukuran' => '4.8 MB',
                'detail' => [
                    'penanggung_jawab' => 'Kasi Kesejahteraan',
                    'waktu_pembuatan' => '01 Maret 2024',
                    'waktu_publikasi' => '10 Maret 2024',
                    'format_ukuran' => 'PDF Document (4.8 MB)',
                    'deskripsi' => 'RAB Pembangunan Infrastruktur Desa mencakup penjabaran biaya RAB per program fisik.',
                    'link_unduh' => '#'
                ]
            ]
        ];

        return view('pages.frontend.informasi.informasi-publik', compact('pageTitle', 'pageSubtitle', 'daftarInformasi'));
    }

    /**
     * Tampilkan halaman Galeri Desa
     */
    public function galeri()
    {
        $pageTitle = "Galeri Desa Sindangmukti";
        $pageSubtitle = "Kumpulan momen berharga, dokumentasi kegiatan, serta potret keindahan alam dan potensi masyarakat desa kami.";

        $daftarKategori = ['Semua Foto', 'Pembangunan', 'Kemasyarakatan', 'Pemerintahan', 'Potensi Alam'];

        $daftarFoto = [
            [
                'id' => 1,
                'kategori' => 'Pemerintahan',
                'kategori_warna' => 'green',
                'judul' => 'Musyawarah Rencana Pembangunan Desa 2024',
                'tanggal' => '15 Jan 2024',
                'gambar_url' => 'https://images.unsplash.com/photo-1596395819057-e37f55a8516d?auto=format&fit=crop&q=80&w=600',
                'gambar_besar' => 'https://images.unsplash.com/photo-1596395819057-e37f55a8516d?auto=format&fit=crop&q=80&w=1200'
            ],
            [
                'id' => 2,
                'kategori' => 'Pembangunan',
                'kategori_warna' => 'blue',
                'judul' => 'Pengaspalan Jalan Dusun II',
                'tanggal' => '02 Feb 2024',
                'gambar_url' => 'https://images.unsplash.com/photo-1621644782084-245781a812da?auto=format&fit=crop&q=80&w=600',
                'gambar_besar' => 'https://images.unsplash.com/photo-1621644782084-245781a812da?auto=format&fit=crop&q=80&w=1200'
            ],
            [
                'id' => 3,
                'kategori' => 'Kemasyarakatan',
                'kategori_warna' => 'amber',
                'judul' => 'Gotong Royong Membersihkan Saluran Irigasi',
                'tanggal' => '20 Feb 2024',
                'gambar_url' => 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&q=80&w=600',
                'gambar_besar' => 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&q=80&w=1200'
            ],
            [
                'id' => 4,
                'kategori' => 'Potensi Alam',
                'kategori_warna' => 'emerald',
                'judul' => 'Panen Raya Padi Petani Lokal',
                'tanggal' => '10 Mar 2024',
                'gambar_url' => 'https://images.unsplash.com/photo-1592838064575-70ed626d3a0e?auto=format&fit=crop&q=80&w=600',
                'gambar_besar' => 'https://images.unsplash.com/photo-1592838064575-70ed626d3a0e?auto=format&fit=crop&q=80&w=1200'
            ],
            [
                'id' => 5,
                'kategori' => 'Kemasyarakatan',
                'kategori_warna' => 'amber',
                'judul' => 'Kegiatan Posyandu dan Edukasi Stunting',
                'tanggal' => '25 Mar 2024',
                'gambar_url' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&q=80&w=600',
                'gambar_besar' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&q=80&w=1200'
            ],
            [
                'id' => 6,
                'kategori' => 'Pemerintahan',
                'kategori_warna' => 'green',
                'judul' => 'Pelatihan BUMDes untuk UMKM Kerajinan',
                'tanggal' => '10 Apr 2024',
                'gambar_url' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&q=80&w=600',
                'gambar_besar' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&q=80&w=1200'
            ]
        ];

        $daftarVideo = [
            [
                'id' => 1,
                'judul' => 'Profil Desa Sindangmukti Tahun 2024',
                'ringkasan' => 'Video dokumenter singkat yang menampilkan sejarah, struktur pemerintahan, dan potensi wisata alam desa.',
                'durasi' => '04:25',
                'thumbnail' => 'https://images.unsplash.com/photo-1596395819057-e37f55a8516d?auto=format&fit=crop&q=80&w=800',
                'link' => '#'
            ],
            [
                'id' => 2,
                'judul' => 'Liputan Panen Raya Jagung Hibrida',
                'ringkasan' => 'Antusiasme warga dan kelompok tani dalam menyambut panen raya jagung unggulan bersama perangkat desa.',
                'durasi' => '02:18',
                'thumbnail' => 'https://images.unsplash.com/photo-1592838064575-70ed626d3a0e?auto=format&fit=crop&q=80&w=800',
                'link' => '#'
            ]
        ];

        return view('pages.frontend.informasi.galeri', compact('pageTitle', 'pageSubtitle', 'daftarKategori', 'daftarFoto', 'daftarVideo'));
    }

    /**
     * Tampilkan halaman Layanan
     */
    public function layanan()
    {
        $pageTitle = "Pusat Layanan Digital Masyarakat Sindangmukti";
        $pageSubtitle = "Akses berbagai layanan administrasi dan pelaporan dengan mudah, cepat, dan transparan langsung dari genggaman Anda.";

        return view('pages.frontend.layanan.index', compact('pageTitle', 'pageSubtitle'));
    }

    /**
     * Tampilkan halaman Layanan Pengaduan Masyarakat
     */
    public function pengaduan()
    {
        $pageTitle = "Pengaduan Masyarakat";
        $pageSubtitle = "Sampaikan aspirasi, kritik, maupun laporan terkait layanan dan infrastruktur desa. Laporan Anda akan ditindaklanjuti oleh pihak berwenang dengan jaminan kerahasiaan.";

        return view('pages.frontend.layanan.pengaduan', compact('pageTitle', 'pageSubtitle'));
    }

    /**
     * Tampilkan antarmuka Lapak Desa
     */
    public function lapak()
    {
        $pageTitle = "Lapak Desa Sindangmukti";
        $pageSubtitle = "Dukung perekonomian lokal dengan berbelanja produk unggulan hasil karya warga dan UMKM Desa Sindangmukti. Langsung dari pembuatnya!";

        $kategoriLapak = [
            ['label' => 'Makanan', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.35 5.4c-.1.4-.45.6-.85.6H4m14-6v6A2 2 0 0116 21H8a2 2 0 01-2-2v-6'], // actually using simple string is fine but let's just make categories simple
        ];
        // For lapak index, I will simplify and directly pass standard categories array
        $categories = [
            ['name' => 'Semua', 'icon' => '', 'active' => true],
            ['name' => 'Makanan', 'icon' => '<path stroke-linecap="..." d="..." />'], // Wait, I will just use text or simple icons in blade
        ];

        // Daftar produk mock
        $daftarProduk = [
            [
                'slug' => 'keripik-pisang-kepok',
                'nama' => 'Keripik Pisang Kepok Manis Gurih (250gr)',
                'kategori' => 'Makanan',
                'harga' => 'Rp 15.000',
                'toko' => 'UMKM Mekar Jaya',
                'terverifikasi' => true,
                'gambar' => 'https://images.unsplash.com/photo-1621939514649-280e2ee25f60?auto=format&fit=crop&q=80&w=600'
            ],
            [
                'slug' => 'bakul-nasi-anyaman-bambu',
                'nama' => 'Bakul Nasi Anyaman Bambu Tradisional',
                'kategori' => 'Kerajinan',
                'harga' => 'Rp 45.000',
                'toko' => 'Pengrajin Dusun II',
                'terverifikasi' => true,
                'gambar' => 'https://images.unsplash.com/photo-1606760227091-3dd870d97f1d?auto=format&fit=crop&q=80&w=600'
            ],
            [
                'slug' => 'madu-hutan-liar-murni',
                'nama' => 'Madu Hutan Liar Murni Sukakerta (500ml)',
                'kategori' => 'Kesehatan',
                'harga' => 'Rp 85.000',
                'toko' => 'Kelompok Tani Lebah',
                'terverifikasi' => false,
                'gambar' => 'https://images.unsplash.com/photo-1587049352847-4d4b137fabe9?auto=format&fit=crop&q=80&w=600'
            ],
            [
                'slug' => 'kopi-bubuk-robusta',
                'nama' => 'Kopi Bubuk Robusta Asli Petani Lokal',
                'kategori' => 'Minuman',
                'harga' => 'Rp 25.000',
                'toko' => 'Kopi Mang Ujang',
                'terverifikasi' => true,
                'gambar' => 'https://images.unsplash.com/photo-1559525839-b184a4d698c7?auto=format&fit=crop&q=80&w=600'
            ],
            [
                'slug' => 'kain-batik-tulis',
                'nama' => 'Kain Batik Tulis Motif Khas Pedesaan',
                'kategori' => 'Fashion',
                'harga' => 'Rp 150.000',
                'toko' => 'Sanggar Batik Ibu',
                'terverifikasi' => false,
                'gambar' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?auto=format&fit=crop&q=80&w=600'
            ],
            [
                'slug' => 'bibit-tanaman-buah',
                'nama' => 'Bibit Tanaman Buah Alpukat Miki Unggul',
                'kategori' => 'Pertanian',
                'harga' => 'Rp 35.000',
                'toko' => 'Koperasi Tani Sejahtera',
                'terverifikasi' => true,
                'gambar' => 'https://images.unsplash.com/photo-1599940824399-b87987ceb72a?auto=format&fit=crop&q=80&w=600'
            ],
            [
                'slug' => 'sambal-bawang-pedas',
                'nama' => 'Sambal Bawang Pedas Botol Kaca (150gr)',
                'kategori' => 'Makanan',
                'harga' => 'Rp 22.000',
                'toko' => 'Dapur Ibu Titi',
                'terverifikasi' => false,
                'gambar' => 'https://images.unsplash.com/photo-1563805042-7684c8a9e9cb?auto=format&fit=crop&q=80&w=600'
            ],
            [
                'slug' => 'rengginang-ketan-asli',
                'nama' => 'Rengginang Ketan Asli Renyah Isi 20 Pcs',
                'kategori' => 'Makanan',
                'harga' => 'Rp 18.000',
                'toko' => 'UMKM Suka Rasa',
                'terverifikasi' => true,
                'gambar' => 'https://images.unsplash.com/photo-1544681280-d25a782adc9b?auto=format&fit=crop&q=80&w=600'
            ]
        ];

        return view('pages.frontend.lapak.index', compact('pageTitle', 'pageSubtitle', 'daftarProduk'));
    }

    /**
     * Tampilkan detail Lapak Desa
     */
    public function lapakDetail($slug)
    {
        // Dalam implementasi nyata, $slug digunakan untuk query ke Database (Produk::where('slug', $slug)->firstOrFail())
        
        $produk = [
            'slug' => 'keripik-pisang-kepok',
            'nama' => 'Keripik Pisang Kepok Manis Gurih (250gr)',
            'kategori' => 'Makanan & Camilan',
            'harga' => 'Rp 15.000',
            'rating' => 4.9,
            'ulasan_count' => 42,
            'stok' => 'Tersedia (50+ pcs)',
            'berat' => '250 Gram / Bungkus',
            'ketahanan' => '3 Bulan (Suhu Ruang)',
            'pengiriman' => 'Dari Desa Sindangmukti',
            'deskripsi_singkat' => 'Keripik pisang khas Sindangmukti yang terbuat dari pisang kepok pilihan hasil panen kebun sendiri. Diiris tipis, digoreng renyah dengan minyak berkualitas, dan dibalut bumbu manis gurih tanpa bahan pengawet buatan.',
            'toko' => [
                'nama' => 'UMKM Mekar Jaya',
                'terverifikasi' => true,
                'lokasi' => 'Dusun I RT 02 / RW 01',
                'bergabung' => 'Januari 2023',
                'total_produk' => 8,
                'kurir' => 'JNE, Ambil Sendiri'
            ],
            'gambar_utama' => 'https://images.unsplash.com/photo-1621939514649-280e2ee25f60?auto=format&fit=crop&q=80&w=800',
            'galeri' => [
                'https://images.unsplash.com/photo-1621939514649-280e2ee25f60?auto=format&fit=crop&q=80&w=800',
                'https://images.unsplash.com/photo-1599598425947-3300262174fc?auto=format&fit=crop&q=80&w=800',
                'https://images.unsplash.com/photo-1600271886742-f049cd451bba?auto=format&fit=crop&q=80&w=800'
            ]
        ];

        // Terkait (Ambil 4 produk acak)
        $terkait = [
            [
                'slug' => 'rengginang-ketan-asli',
                'nama' => 'Rengginang Ketan Asli Renyah Isi 20 Pcs',
                'kategori' => 'Makanan',
                'harga' => 'Rp 18.000',
                'toko' => 'UMKM Suka Rasa',
                'gambar' => 'https://images.unsplash.com/photo-1544681280-d25a782adc9b?auto=format&fit=crop&q=80&w=600'
            ],
            [
                'slug' => 'sambal-bawang-pedas',
                'nama' => 'Sambal Bawang Pedas Botol Kaca (150gr)',
                'kategori' => 'Makanan',
                'harga' => 'Rp 22.000',
                'toko' => 'Dapur Ibu Titi',
                'gambar' => 'https://images.unsplash.com/photo-1563805042-7684c8a9e9cb?auto=format&fit=crop&q=80&w=600'
            ],
            [
                'slug' => 'kopi-bubuk-robusta',
                'nama' => 'Kopi Bubuk Robusta Asli Petani Lokal',
                'kategori' => 'Minuman',
                'harga' => 'Rp 25.000',
                'toko' => 'Kopi Mang Ujang',
                'gambar' => 'https://images.unsplash.com/photo-1559525839-b184a4d698c7?auto=format&fit=crop&q=80&w=600'
            ],
            [
                'slug' => 'madu-hutan-liar-murni',
                'nama' => 'Madu Hutan Liar Murni Sukakerta (500ml)',
                'kategori' => 'Kesehatan',
                'harga' => 'Rp 85.000',
                'toko' => 'Kelompok Tani Lebah',
                'gambar' => 'https://images.unsplash.com/photo-1587049352847-4d4b137fabe9?auto=format&fit=crop&q=80&w=600'
            ]
        ];

        return view('pages.frontend.lapak.detail', compact('produk', 'terkait'));
    }
}
