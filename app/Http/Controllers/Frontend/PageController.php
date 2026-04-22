<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Apbdes;
use App\Models\ApbdesPoster;

class PageController extends Controller
{
    /**
     * Tampilkan halaman Transparansi APBDes
     */
    public function transparansi(Request $request)
    {
        $pageTitle = "Transparansi APBDes";
        $pageSubtitle = "Wujud nyata keterbukaan informasi publik. Kami menyajikan rincian Anggaran Pendapatan dan Belanja Desa secara jujur, akuntabel, dan transparan.";

        // Daftar tahun yang tersedia di database
        $availableYears = Apbdes::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun')->toArray();

        // Tahun aktif: dari query param ?tahun= atau tahun terbaru yang published
        $tahunBerjalan = $request->query('tahun')
            ?? (Apbdes::where('is_published', true)->max('tahun') ?? date('Y'));
        $tahunBerjalan = (int) $tahunBerjalan;

        // Mendapatkan Config Poster & Dokumen
        $posterCurrent = ApbdesPoster::where('tahun', $tahunBerjalan)->first();

        // Data APBDes Ringkasan
        $summary = Apbdes::getSummary($tahunBerjalan);
        
        $apbdesRingkasan = [
            'pendapatan_target' => $summary['pendapatan'],
            'pendapatan_realisasi' => $summary['pendapatan'],
            'belanja_target' => $summary['belanja'],
            'belanja_realisasi' => $summary['belanja'],
            'pembiayaan_netto' => $summary['pembiayaan'],
            'silpa' => $summary['surplus']
        ];

        // Fetch rincian item berdasarkan tipe
        $baseQuery = Apbdes::where('tahun', $tahunBerjalan)->where('is_published', true);

        // Pendapatan
        $rincianPendapatan = (clone $baseQuery)->where('tipe_anggaran', 'PENDAPATAN')
            ->get()->map(fn($item) => ['uraian' => $item->nama_kegiatan, 'anggaran' => $item->pagu_anggaran])->toArray();
        
        // Belanja (bidang level)
        $rincianBelanja = (clone $baseQuery)->where('tipe_anggaran', 'BELANJA')
            ->whereRaw('LENGTH(kode_rekening) <= 3')
            ->get()->map(fn($item) => ['bidang' => $item->nama_kegiatan, 'anggaran' => $item->pagu_anggaran])->toArray();

        // Pembiayaan
        $pembiayaanItems = (clone $baseQuery)->where('tipe_anggaran', 'PEMBIAYAAN')->get();
        $rincianPembiayaan = [
            'penerimaan' => $pembiayaanItems->map(fn($item) => ['uraian' => $item->nama_kegiatan, 'anggaran' => $item->pagu_anggaran])->toArray(),
            'pengeluaran' => []
        ];

        // Arsip & Riwayat (past years excluding current)
        $arsipTransparansi = [];
        foreach ($availableYears as $year) {
            if ($year == $tahunBerjalan) continue;
            $sumPast = Apbdes::getSummary($year);
            if ($sumPast['item_count'] > 0) {
                $arsipTransparansi[] = [
                    'tahun' => $year,
                    'status' => 'Selesai',
                    'pendapatan' => $sumPast['pendapatan'],
                    'belanja' => $sumPast['belanja'],
                    'silpa' => $sumPast['surplus']
                ];
            }
        }

        return view('pages.frontend.transparansi', compact(
            'pageTitle',
            'pageSubtitle',
            'tahunBerjalan',
            'availableYears',
            'posterCurrent',
            'apbdesRingkasan',
            'rincianPendapatan',
            'rincianBelanja',
            'rincianPembiayaan',
            'arsipTransparansi'
        ));
    }

    /**
     * Tampilkan halaman Berita & Artikel — full DB-driven.
     */
    public function beritaArtikel(Request $request)
    {
        $pageTitle = "Berita & Artikel Desa";
        $pageSubtitle = "Dapatkan informasi terbaru seputar kegiatan, pembangunan, pemberdayaan masyarakat, dan pengumuman resmi Desa Sindangmukti.";

        // ── Kategori untuk filter pills ──
        $categories = \App\Models\ArticleCategory::orderBy('nama_kategori')->get();

        // ── Sorotan utama: artikel published terbaru ──
        $sorotanUtama = \App\Models\Article::published()
            ->with(['category', 'user'])
            ->orderByDesc('published_at')
            ->first();

        // ── Query utama (exclude featured) ──
        $query = \App\Models\Article::published()->with(['category', 'user']);

        if ($sorotanUtama) {
            $query->where('id', '!=', $sorotanUtama->id);
        }

        // Filter by category
        $activeCategory = $request->query('kategori');
        if ($activeCategory && $activeCategory !== 'semua') {
            $query->whereHas('category', fn($q) => $q->where('slug', $activeCategory));
        }

        // Search
        $search = $request->query('q');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('ringkasan', 'like', "%{$search}%")
                  ->orWhere('konten_html', 'like', "%{$search}%");
            });
        }

        // Pagination (6 per page)
        $daftarArtikel = $query->orderByDesc('published_at')->paginate(6)->withQueryString();

        return view('pages.frontend.informasi.berita-artikel', compact(
            'pageTitle', 'pageSubtitle', 'categories', 'activeCategory',
            'search', 'sorotanUtama', 'daftarArtikel'
        ));
    }

    /**
     * Tampilkan detail artikel Berita Desa.
     */
    public function beritaDetail($slug)
    {
        $article = \App\Models\Article::where('slug', $slug)
            ->published()
            ->with(['category', 'user'])
            ->firstOrFail();

        // Increment view count
        $article->increment('view_count');

        // Related articles (same category, max 3, exclude current)
        $related = \App\Models\Article::published()
            ->where('id', '!=', $article->id)
            ->where('kategori_id', $article->kategori_id)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('pages.frontend.informasi.berita-detail', compact('article', 'related'));
    }

    /**
     * Tampilkan halaman Pengumuman & Agenda — full DB-driven.
     */
    public function pengumumanAgenda(Request $request)
    {
        $pageTitle = "Pengumuman & Agenda";
        $pageSubtitle = "Pusat informasi resmi terkini dan jadwal kegiatan mendatang di lingkungan Pemerintahan Desa Sindangmukti.";

        // ── Filter bulan & tahun ──
        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        // ── Pengumuman penting terbaru (is_important = true, published, not expired) ──
        $pengumumanPenting = \App\Models\InformasiPublik::active()
            ->pengumuman()
            ->where('is_important', true)
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            })
            ->orderByDesc('start_date')
            ->first();

        // ── Daftar agenda bulan yang dipilih ──
        $daftarAgenda = \App\Models\InformasiPublik::active()
            ->agenda()
            ->whereMonth('start_date', $bulan)
            ->whereYear('start_date', $tahun)
            ->orderBy('start_date')
            ->paginate(10)
            ->withQueryString();

        // ── Data kalender: tanggal-tanggal yang punya event ──
        $calendarEvents = \App\Models\InformasiPublik::active()
            ->whereMonth('start_date', $bulan)
            ->whereYear('start_date', $tahun)
            ->get()
            ->map(fn($item) => [
                'day'  => (int) $item->start_date->format('d'),
                'type' => $item->type,
                'title' => $item->title,
            ]);

        // ── Tahun yang tersedia untuk dropdown filter ──
        $availableYears = \App\Models\InformasiPublik::whereNotNull('start_date')
            ->pluck('start_date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->year)
            ->unique()
            ->sortDesc()
            ->values();
        if ($availableYears->isEmpty()) {
            $availableYears = collect([now()->year]);
        }

        return view('pages.frontend.informasi.pengumuman-agenda', compact(
            'pageTitle', 'pageSubtitle', 'pengumumanPenting', 'daftarAgenda',
            'calendarEvents', 'bulan', 'tahun', 'availableYears'
        ));
    }

    /**
     * Tampilkan halaman Produk Hukum Desa — full DB-driven.
     */
    public function produkHukum(Request $request)
    {
        $pageTitle = "JDIH & Produk Hukum Desa";
        $pageSubtitle = "Wadah keterbukaan informasi produk hukum pemerintahan. Jelajahi Peraturan Desa, Keputusan Kepala Desa, dan dokumen regulasi resmi lainnya.";

        // ── Filters ──
        $search   = $request->query('q');
        $kategori = $request->query('kategori');
        $tahun    = $request->query('tahun');

        // ── Category stats (count per category) ──
        $categories = \App\Models\JdihCategory::withCount('documents')->orderBy('name')->get();

        // ── Build query ──
        $query = \App\Models\JdihDocument::with('category')
            ->orderByRaw("CASE WHEN status = 'berlaku' THEN 0 ELSE 1 END")
            ->orderByDesc('established_date');

        if ($search) {
            $query->search($search);
        }
        if ($kategori) {
            $query->byCategory($kategori);
        }
        if ($tahun) {
            $query->byYear($tahun);
        }

        $daftarHukum = $query->paginate(10)->withQueryString();

        // ── Available years for filter dropdown ──
        $availableYears = \App\Models\JdihDocument::whereNotNull('established_date')
            ->pluck('established_date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->year)
            ->unique()
            ->sortDesc()
            ->values();

        return view('pages.frontend.informasi.produk-hukum', compact(
            'pageTitle', 'pageSubtitle', 'categories', 'daftarHukum',
            'search', 'kategori', 'tahun', 'availableYears'
        ));
    }

    /**
     * Download JDIH document and increment counter.
     */
    public function downloadJdih(\App\Models\JdihDocument $document)
    {
        $document->increment('download_count');

        if ($document->file_path && \Storage::disk('public')->exists($document->file_path)) {
            return \Storage::disk('public')->download($document->file_path, $document->file_name);
        }

        return back()->with('error', 'File dokumen tidak tersedia.');
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
     * Tampilkan antarmuka Lapak Desa (Index) — full DB-driven.
     */
    public function lapak(Request $request)
    {
        $pageTitle    = "Lapak Desa Sindangmukti";
        $pageSubtitle = "Dukung perekonomian lokal dengan berbelanja produk unggulan hasil karya warga dan UMKM Desa Sindangmukti. Langsung dari pembuatnya!";

        // ── Kategori untuk filter tabs ──
        $categories = \App\Models\ProductCategory::orderBy('name')->get();

        // ── Query utama ──
        $query = \App\Models\Product::active()->with('category');

        // Filter by category
        $activeCategory = $request->query('kategori');
        if ($activeCategory && $activeCategory !== 'semua') {
            $query->whereHas('category', fn($q) => $q->where('slug', $activeCategory));
        }

        // Search
        $search = $request->query('q');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('seller_name', 'like', "%{$search}%")
                  ->orWhere('description_html', 'like', "%{$search}%");
            });
        }

        // Sort
        $sort = $request->query('sort', 'terbaru');
        $query = match ($sort) {
            'harga_rendah' => $query->orderBy('price', 'asc'),
            'harga_tinggi' => $query->orderBy('price', 'desc'),
            'nama'         => $query->orderBy('name', 'asc'),
            default        => $query->orderByDesc('created_at'), // terbaru
        };

        // Paginate (8 per page)
        $products = $query->paginate(8)->withQueryString();

        // Stats
        $totalProducts = \App\Models\Product::active()->count();

        return view('pages.frontend.lapak.index', compact(
            'pageTitle',
            'pageSubtitle',
            'categories',
            'products',
            'activeCategory',
            'search',
            'sort',
            'totalProducts',
        ));
    }

    /**
     * Tampilkan detail Produk Lapak Desa — full DB-driven.
     */
    public function lapakDetail($slug)
    {
        $product = \App\Models\Product::where('slug', $slug)
            ->where('status', 'aktif')
            ->with('category')
            ->firstOrFail();

        // Produk terkait (kategori sama, max 4, exclude current)
        $related = \App\Models\Product::active()
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->limit(4)
            ->get();

        // Jika related kurang dari 4, tambahkan produk random
        if ($related->count() < 4) {
            $moreNeeded = 4 - $related->count();
            $excludeIds = $related->pluck('id')->push($product->id)->toArray();

            $extras = \App\Models\Product::active()
                ->whereNotIn('id', $excludeIds)
                ->inRandomOrder()
                ->limit($moreNeeded)
                ->get();

            $related = $related->concat($extras);
        }

        return view('pages.frontend.lapak.detail', compact('product', 'related'));
    }
}
