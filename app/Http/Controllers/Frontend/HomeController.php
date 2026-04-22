<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AlbumGaleri;
use App\Models\Apbdes;
use App\Models\Article;
use App\Models\KartuKeluarga;
use App\Models\MediaGaleri;
use App\Models\Penduduk;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\VillageSetting;
use App\Models\Wilayah;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman utama publik (frontend).
     * Semua data ditarik dari database — bukan hardcoded.
     */
    public function index()
    {
        $village = VillageSetting::instance();

        // ══════════════════════════════════════════════
        // STATISTIK PENDUDUK
        // ══════════════════════════════════════════════

        $pendudukStats = Penduduk::statistics();
        $totalKK       = KartuKeluarga::count();
        $totalRtRw     = Wilayah::count();

        // Data gender untuk Chart.js (Pie)
        $genderData = [
            'labels' => ['Laki-Laki', 'Perempuan'],
            'data'   => [$pendudukStats['laki'], $pendudukStats['perempuan']],
        ];

        // Data pekerjaan untuk Chart.js (Bar)
        $pekerjaanData = Penduduk::query()
            ->whereNotNull('pekerjaan')
            ->where('pekerjaan', '!=', '')
            ->selectRaw("pekerjaan, COUNT(*) as total")
            ->groupBy('pekerjaan')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $pekerjaanChart = [
            'labels' => $pekerjaanData->pluck('pekerjaan')->toArray(),
            'data'   => $pekerjaanData->pluck('total')->toArray(),
        ];

        // ══════════════════════════════════════════════
        // APBDes (Transparansi Keuangan)
        // ══════════════════════════════════════════════

        $tahunApbdes = date('Y');
        $apbdesSummary = Apbdes::getSummary($tahunApbdes);

        // Jika tahun ini belum ada data, coba tahun lalu
        if ($apbdesSummary['item_count'] === 0) {
            $tahunApbdes = (int)date('Y') - 1;
            $apbdesSummary = Apbdes::getSummary($tahunApbdes);
        }

        // Hitung persentase realisasi
        $pendapatanPersen = $apbdesSummary['pendapatan'] > 0
            ? round(($apbdesSummary['pendapatan_realisasi'] / $apbdesSummary['pendapatan']) * 100, 1)
            : 0;
        $belanjaPersen = $apbdesSummary['belanja'] > 0
            ? round(($apbdesSummary['belanja_realisasi'] / $apbdesSummary['belanja']) * 100, 1)
            : 0;
        $pembiayaanPersen = $apbdesSummary['pembiayaan'] > 0
            ? round(($apbdesSummary['pembiayaan_realisasi'] / $apbdesSummary['pembiayaan']) * 100, 1)
            : 0;

        // Data untuk Chart.js (Income & Expense horizontal bar)
        $incomeChartData = $this->buildApbdesChartData($tahunApbdes, 'PENDAPATAN');
        $expenseChartData = $this->buildApbdesChartData($tahunApbdes, 'BELANJA');

        // ══════════════════════════════════════════════
        // BERITA & ARTIKEL (dari Article model)
        // ══════════════════════════════════════════════

        $articles = Article::where('status', 'publish')
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->limit(6)
            ->get()
            ->map(fn($a) => [
                'id'      => $a->id,
                'imgSrc'  => $a->thumbnail_url,
                'tanggal' => $a->published_at->translatedFormat('d M Y'),
                'judul'   => $a->judul,
                'admin'   => $a->user?->name ?? 'Admin',
                'views'   => number_format($a->view_count ?? 0, 0, ',', '.'),
                'url'     => url('/informasi/berita-artikel'),
                'slug'    => $a->slug,
            ]);

        // ══════════════════════════════════════════════
        // LAPAK DESA (UMKM / Produk)
        // ══════════════════════════════════════════════

        $products = Product::active()
            ->with('category')
            ->limit(8)
            ->get()
            ->map(fn($p) => [
                'id'       => $p->id,
                'foto'     => $p->image_path
                    ? asset('storage/' . $p->image_path)
                    : asset('assets/img/galeri/galeri1.jpg'),
                'nama'     => $p->name,
                'harga'    => $p->price,
                'kategori' => $p->category?->name ?? 'Lainnya',
                'pelapak'  => $p->seller_name,
                'slug'     => $p->slug,
            ]);

        $productCategories = ProductCategory::pluck('name')->toArray();

        // ══════════════════════════════════════════════
        // GALERI
        // ══════════════════════════════════════════════

        $albums = AlbumGaleri::with(['medias' => fn($q) => $q->limit(1)])
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn($album) => [
                'id'       => $album->id,
                'title'    => $album->nama_album,
                'category' => 'kegiatan',
                'img'      => $album->cover_image
                    ? asset('storage/' . $album->cover_image)
                    : ($album->medias->first()?->url ?? asset('assets/img/galeri/galeri' . (($album->id % 2) + 1) . '.jpg')),
            ]);

        // ══════════════════════════════════════════════
        // BPD MEMBERS (hardcoded — data statis organisasi)
        // ══════════════════════════════════════════════

        $bpdMembers = [
            ['nama' => 'Tateng', 'jabatan' => 'Ketua', 'kontak' => '082118738256', 'alamat' => 'Dusun Cidoyang RT 3 RW 2'],
            ['nama' => 'Samsudin, S.Pd, M.M', 'jabatan' => 'Wakil Ketua', 'kontak' => '081323209666', 'alamat' => 'Dusun Sukamanah RT 3 RW 1'],
            ['nama' => 'Nonoh Sukanah, S.Pd, M.Pd', 'jabatan' => 'Sekretaris', 'kontak' => '082218923613', 'alamat' => 'Dusun Sukasari RT 2 RW 2'],
            ['nama' => 'Lili Sudirahayu', 'jabatan' => 'Anggota', 'kontak' => '081323175321', 'alamat' => 'Dusun Sukamanah RT 4 RW 2'],
            ['nama' => 'Arif Hidayat, S.Pd', 'jabatan' => 'Anggota', 'kontak' => '082126414546', 'alamat' => 'Dusun Sukasari RT 3 RW 1'],
            ['nama' => 'Heri Simri', 'jabatan' => 'Anggota', 'kontak' => '081320077590', 'alamat' => 'Dusun Sukasari RT 2 RW 2'],
            ['nama' => 'Norma Mustika, S.Pd.I', 'jabatan' => 'Anggota', 'kontak' => '087802837560', 'alamat' => 'Dusun Cidoyang RT 1 RW 1'],
            ['nama' => 'Nana', 'jabatan' => 'Anggota', 'kontak' => '-', 'alamat' => 'Dusun Cidoyang RT 1 RW 1'],
        ];

        // ══════════════════════════════════════════════
        // MAP COORDINATES
        // ══════════════════════════════════════════════

        $mapCenter = [
            'lat' => -7.2734, // Latitude Desa Sindangmukti (approx)
            'lng' => 108.3462, // Longitude
            'zoom' => 14,
        ];

        return view('pages.frontend.home', compact(
            'village',
            'pendudukStats',
            'totalKK',
            'totalRtRw',
            'genderData',
            'pekerjaanChart',
            'tahunApbdes',
            'apbdesSummary',
            'pendapatanPersen',
            'belanjaPersen',
            'pembiayaanPersen',
            'incomeChartData',
            'expenseChartData',
            'articles',
            'products',
            'productCategories',
            'albums',
            'bpdMembers',
            'mapCenter',
        ));
    }

    /**
     * Build Chart.js data for APBDes items by type.
     */
    private function buildApbdesChartData(int $tahun, string $tipe): array
    {
        $items = Apbdes::where('tahun', $tahun)
            ->where('is_published', true)
            ->where('tipe_anggaran', $tipe)
            ->orderByDesc('pagu_anggaran')
            ->limit(5)
            ->get();

        $labels    = [];
        $anggaran  = [];
        $realisasi = [];

        foreach ($items as $item) {
            $labels[]    = \Illuminate\Support\Str::limit($item->nama_kegiatan, 25);
            $anggaran[]  = $item->pagu_anggaran;
            $realisasi[] = $item->realisasis()->sum('nominal');
        }

        return [
            'labels'    => $labels,
            'anggaran'  => $anggaran,
            'realisasi' => $realisasi,
        ];
    }
}
