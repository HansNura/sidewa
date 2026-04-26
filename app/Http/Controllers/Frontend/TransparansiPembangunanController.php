<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Pembangunan;
use App\Models\PembangunanFoto;
use Illuminate\Http\Request;

class TransparansiPembangunanController extends Controller
{
    /**
     * Halaman List Transparansi Pembangunan (Publik)
     * Menampilkan semua proyek pembangunan dengan filter & statistik.
     */
    public function index(Request $request)
    {
        $pageTitle = "Transparansi Pembangunan Desa";
        $pageSubtitle = "Pantau transparansi alokasi anggaran dan progres realisasi fisik pembangunan di wilayah Desa Sindangmukti secara real-time.";

        // ── Filters ──
        $tahun    = $request->query('tahun');
        $kategori = $request->query('kategori');
        $status   = $request->query('status');
        $search   = $request->query('q');

        // ── Available years for filter ──
        $availableYears = Pembangunan::whereNotNull('tanggal_mulai')
            ->pluck('tanggal_mulai')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->year)
            ->unique()
            ->sortDesc()
            ->values();

        if ($availableYears->isEmpty()) {
            $availableYears = collect([date('Y')]);
        }

        // ── Available categories ──
        $availableKategori = Pembangunan::select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori')
            ->filter()
            ->values();

        // ── Build query ──
        $query = Pembangunan::with('apbdes');

        if ($tahun) {
            $query->where(function ($q) use ($tahun) {
                $q->whereYear('tanggal_mulai', $tahun)
                    ->orWhereYear('target_selesai', $tahun);
            });
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_proyek', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('lokasi_dusun', 'like', "%{$search}%");
            });
        }

        // ── Statistics (from filtered scope, excluding search for broader stats) ──
        $statsQuery = Pembangunan::query();
        if ($tahun) {
            $statsQuery->where(function ($q) use ($tahun) {
                $q->whereYear('tanggal_mulai', $tahun)
                    ->orWhereYear('target_selesai', $tahun);
            });
        }

        $totalProyek      = $statsQuery->count();
        $totalAnggaran    = (clone $statsQuery)->whereHas('apbdes')->with('apbdes')
            ->get()->sum(fn($p) => $p->apbdes?->pagu_anggaran ?? 0);
        $proyekBerjalan   = (clone $statsQuery)->where('status', 'berjalan')->count();
        $proyekSelesai    = (clone $statsQuery)->where('status', 'selesai')->count();

        // ── Paginate results ──
        $proyeks = $query->orderByDesc('created_at')->paginate(9)->withQueryString();

        return view('pages.frontend.transparansi-pembangunan.index', compact(
            'pageTitle',
            'pageSubtitle',
            'proyeks',
            'availableYears',
            'availableKategori',
            'tahun',
            'kategori',
            'status',
            'search',
            'totalProyek',
            'totalAnggaran',
            'proyekBerjalan',
            'proyekSelesai'
        ));
    }

    /**
     * Halaman Detail Proyek (Publik - Drill Down)
     * Menampilkan informasi lengkap proyek: progres, anggaran, timeline, dan dokumentasi.
     */
    public function show($id)
    {
        $proyek = Pembangunan::with(['apbdes', 'historis', 'fotos'])->findOrFail($id);

        $pageTitle = $proyek->nama_proyek . ' — Transparansi Pembangunan';

        return view('pages.frontend.transparansi-pembangunan.detail', compact(
            'pageTitle',
            'proyek'
        ));
    }
}
