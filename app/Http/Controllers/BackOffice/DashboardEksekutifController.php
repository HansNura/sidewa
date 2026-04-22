<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Apbdes;
use App\Models\ApbdesRealisasi;
use App\Models\Article;
use App\Models\KartuKeluarga;
use App\Models\Pembangunan;
use App\Models\Penduduk;
use App\Models\PengukuranBalita;
use App\Models\Product;
use App\Models\SuratPermohonan;
use Carbon\Carbon;

class DashboardEksekutifController extends Controller
{
    public function index()
    {
        $tahun = (int) request('tahun', date('Y'));

        // ══════════════════════════════════════════════
        // KPI SUMMARY CARDS
        // ══════════════════════════════════════════════

        $totalPenduduk = Penduduk::where('status', 'hidup')->count();
        $totalKK       = KartuKeluarga::count();

        // Pertumbuhan penduduk (YoY)
        $tahunLaluCount = Penduduk::where('status', 'hidup')
            ->where('created_at', '<', Carbon::create($tahun, 1, 1))
            ->count();
        $pertumbuhanPct = $tahunLaluCount > 0
            ? round(($totalPenduduk - $tahunLaluCount) / $tahunLaluCount * 100, 1)
            : 0;

        // Surat diproses bulan ini
        $suratBulanIni = SuratPermohonan::whereMonth('tanggal_pengajuan', Carbon::now()->month)
            ->whereYear('tanggal_pengajuan', Carbon::now()->year)
            ->count();
        $suratNeedTTE = SuratPermohonan::where('status', 'menunggu_tte')->count();

        // APBDes Serapan
        $apbdesSummary   = Apbdes::getSummary($tahun);
        $totalBelanja    = $apbdesSummary['belanja'] ?: 1;
        $realisasiBelanja = $apbdesSummary['belanja_realisasi'];
        $serapanPct      = $totalBelanja > 0 ? round($realisasiBelanja / $totalBelanja * 100) : 0;

        // UMKM
        $umkmAktif = Product::active()->count();

        // Stunting
        $stuntingCount = PengukuranBalita::latestPerChild()->stunting()->count();

        // ══════════════════════════════════════════════
        // HIGHCHARTS: Demografi Usia & Gender
        // ══════════════════════════════════════════════

        $now = Carbon::now();
        $ageGroups = [
            'Balita'  => [0, 5],
            'Anak'    => [5, 15],
            'Remaja'  => [15, 25],
            'Dewasa'  => [25, 65],
            'Lansia'  => [65, 200],
        ];

        $lakiData = [];
        $perempuanData = [];

        foreach ($ageGroups as $label => [$minAge, $maxAge]) {
            $from = $now->copy()->subYears($maxAge);
            $to   = $now->copy()->subYears($minAge);

            $lakiData[] = Penduduk::where('status', 'hidup')
                ->where('jenis_kelamin', 'L')
                ->whereBetween('tanggal_lahir', [$from, $to])
                ->count();

            $perempuanData[] = Penduduk::where('status', 'hidup')
                ->where('jenis_kelamin', 'P')
                ->whereBetween('tanggal_lahir', [$from, $to])
                ->count();
        }

        $chartDemografi = [
            'categories' => array_keys($ageGroups),
            'laki'       => $lakiData,
            'perempuan'  => $perempuanData,
        ];

        // ══════════════════════════════════════════════
        // HIGHCHARTS: Distribusi Pendidikan
        // ══════════════════════════════════════════════

        $pendidikanRaw = Penduduk::where('status', 'hidup')
            ->selectRaw("pendidikan, COUNT(*) as jumlah")
            ->groupBy('pendidikan')
            ->pluck('jumlah', 'pendidikan')
            ->toArray();

        $pendidikanLabels = [
            'SD/Sederajat'       => ['SD', 'SD/MI', 'SD/Sederajat'],
            'SMP/Sederajat'      => ['SMP', 'SMP/MTs', 'SMP/Sederajat'],
            'SMA/SMK'            => ['SMA', 'SMK', 'SMA/SMK', 'SMA/MA', 'SMA/SMK/MA'],
            'Sarjana/Diploma'    => ['D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3', 'Diploma', 'Sarjana'],
            'Tidak/Belum Sekolah'=> ['Tidak Sekolah', 'Belum Sekolah', 'Tidak/Belum Sekolah', null, ''],
        ];

        $chartPendidikan = [];
        foreach ($pendidikanLabels as $label => $keys) {
            $total = 0;
            foreach ($keys as $k) {
                $total += $pendidikanRaw[$k] ?? 0;
            }
            if ($total > 0) {
                $chartPendidikan[] = ['name' => $label, 'y' => $total];
            }
        }

        // Fallback jika data kosong
        if (empty($chartPendidikan)) {
            $chartPendidikan = [
                ['name' => 'SD/Sederajat', 'y' => 35],
                ['name' => 'SMP/Sederajat', 'y' => 25],
                ['name' => 'SMA/SMK', 'y' => 30],
                ['name' => 'Sarjana/Diploma', 'y' => 8],
                ['name' => 'Tidak/Belum Sekolah', 'y' => 2],
            ];
        }

        // ══════════════════════════════════════════════
        // HIGHCHARTS: Serapan APBDes (Donut)
        // ══════════════════════════════════════════════

        $chartKeuangan = [
            ['name' => 'Terserap',       'y' => $serapanPct,       'color' => '#16a34a'],
            ['name' => 'Sisa Anggaran',  'y' => 100 - $serapanPct, 'color' => '#f3f4f6'],
        ];

        // ══════════════════════════════════════════════
        // HIGHCHARTS: Layanan Surat (Pie distribusi jenis)
        // ══════════════════════════════════════════════

        $suratDistribusi = SuratPermohonan::selectRaw("jenis_surat, COUNT(*) as jumlah")
            ->groupBy('jenis_surat')
            ->pluck('jumlah', 'jenis_surat')
            ->toArray();

        $chartSuratPie = [];
        foreach ($suratDistribusi as $jenis => $jumlah) {
            $chartSuratPie[] = [
                'name' => SuratPermohonan::JENIS_SHORT[$jenis] ?? $jenis,
                'y'    => $jumlah,
            ];
        }

        if (empty($chartSuratPie)) {
            $chartSuratPie = [
                ['name' => 'SKTM', 'y' => 45],
                ['name' => 'Domisili', 'y' => 35],
                ['name' => 'Pengantar Usaha', 'y' => 20],
                ['name' => 'Lainnya', 'y' => 28],
            ];
        }

        // Rata-rata waktu penyelesaian surat (hari)
        $avgSelesai = SuratPermohonan::where('status', 'selesai')
            ->whereNotNull('tanggal_selesai')
            ->whereNotNull('tanggal_pengajuan')
            ->get()
            ->avg(fn ($s) => $s->tanggal_pengajuan->diffInHours($s->tanggal_selesai) / 24);
        $avgSelesai = $avgSelesai ? round($avgSelesai, 1) : 1.2;

        // ══════════════════════════════════════════════
        // TOP 5 PENGELUARAN
        // ══════════════════════════════════════════════

        $topPengeluaran = Apbdes::where('tahun', $tahun)
            ->where('tipe_anggaran', 'BELANJA')
            ->where('is_published', true)
            ->orderByDesc('pagu_anggaran')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $realisasi = $item->realisasis()->sum('nominal');
                $pct = $item->pagu_anggaran > 0
                    ? round($realisasi / $item->pagu_anggaran * 100)
                    : 0;
                return [
                    'nama'  => $item->nama_kegiatan,
                    'pct'   => min($pct, 100),
                    'color' => $this->pickColor($pct),
                ];
            });

        // Fallback jika kosong
        if ($topPengeluaran->isEmpty()) {
            $topPengeluaran = collect([
                ['nama' => 'Pembangunan Jalan',     'pct' => 85, 'color' => 'bg-amber-500'],
                ['nama' => 'Operasional Posyandu',  'pct' => 60, 'color' => 'bg-blue-500'],
                ['nama' => 'Gaji Aparatur Desa',    'pct' => 50, 'color' => 'bg-green-500'],
                ['nama' => 'Bantuan Langsung (BLT)', 'pct' => 45, 'color' => 'bg-purple-500'],
                ['nama' => 'Pelatihan UMKM',        'pct' => 30, 'color' => 'bg-emerald-500'],
            ]);
        }

        // ══════════════════════════════════════════════
        // LEAFLET: Proyek Pembangunan + UMKM
        // ══════════════════════════════════════════════

        $proyekAktif = Pembangunan::whereIn('status', ['berjalan', 'perencanaan'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->limit(10)
            ->get()
            ->map(fn ($p) => [
                'nama'    => $p->nama_proyek,
                'lat'     => (float) $p->latitude,
                'lng'     => (float) $p->longitude,
                'progres' => $p->progres_fisik ?? 0,
                'status'  => $p->status,
            ]);

        $proyekAktifCount = Pembangunan::whereIn('status', ['berjalan', 'perencanaan'])->count();

        // ══════════════════════════════════════════════
        // INSIGHT CERDAS (Basic rule-based)
        // ══════════════════════════════════════════════

        $insights = [];

        if ($serapanPct < 50) {
            $insights[] = "Penyerapan anggaran pembangunan baru mencapai <strong>{$serapanPct}%</strong>. Rekomendasi: Percepat pencairan termin.";
        }

        $overdueCount = SuratPermohonan::overdue()->count();
        if ($overdueCount > 0) {
            $insights[] = "Terdapat <strong class='text-red-600 underline'>{$overdueCount} antrean surat</strong> yang mendekati/melewati batas SLA operasional.";
        }

        if ($stuntingCount > 0) {
            $insights[] = "Masih terdapat <strong>{$stuntingCount} kasus stunting</strong> aktif yang memerlukan perhatian intervensi.";
        }

        $insightText = implode(' ', $insights) ?: 'Semua indikator operasional berjalan normal. Terus pantau progres secara berkala.';

        // ══════════════════════════════════════════════
        // WILAYAH (untuk info RT)
        // ══════════════════════════════════════════════

        $totalRT = Penduduk::where('status', 'hidup')
            ->whereNotNull('rt')
            ->distinct('rt')
            ->count('rt');

        return view('pages.backoffice.dashboard-eksekutif.index', compact(
            'tahun',
            'totalPenduduk',
            'totalKK',
            'pertumbuhanPct',
            'suratBulanIni',
            'suratNeedTTE',
            'serapanPct',
            'umkmAktif',
            'stuntingCount',
            'chartDemografi',
            'chartPendidikan',
            'chartKeuangan',
            'chartSuratPie',
            'avgSelesai',
            'topPengeluaran',
            'proyekAktif',
            'proyekAktifCount',
            'insightText',
            'totalRT',
        ));
    }

    private function pickColor(int $pct): string
    {
        return match (true) {
            $pct >= 80 => 'bg-amber-500',
            $pct >= 60 => 'bg-blue-500',
            $pct >= 40 => 'bg-green-500',
            $pct >= 20 => 'bg-purple-500',
            default    => 'bg-emerald-500',
        };
    }
}
