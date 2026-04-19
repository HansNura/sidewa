<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\IntervensiStunting;
use App\Models\Penduduk;
use App\Models\PengukuranBalita;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KesehatanController extends Controller
{
    /**
     * Display kesehatan & stunting dashboard with data table.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $dusun  = $request->input('dusun');

        // Latest measurement per child (deduped)
        $query = PengukuranBalita::with('penduduk')
            ->latestPerChild()
            ->orderByDesc('tanggal_pengukuran');

        if ($search) {
            $query->search($search);
        }

        if ($dusun) {
            $query->whereHas('penduduk', fn ($q) => $q->where('dusun', $dusun));
        }

        $pengukuran = $query->paginate(15)->withQueryString();

        // ─── KPI Statistics ─────────────────────────────────────
        $latestIds = PengukuranBalita::latestPerChild()->pluck('id');

        $totalBalita    = $latestIds->count();
        $stuntingAktif  = PengukuranBalita::whereIn('id', $latestIds)->stunting()->count();
        $sangatPendek   = PengukuranBalita::whereIn('id', $latestIds)->where('status_gizi', 'sangat_pendek')->count();
        $prevalensi     = $totalBalita > 0 ? round(($stuntingAktif / $totalBalita) * 100, 1) : 0;

        // Trend: compare current month stunting vs last month
        $currentMonth = PengukuranBalita::stunting()
            ->whereMonth('tanggal_pengukuran', now()->month)
            ->whereYear('tanggal_pengukuran', now()->year)
            ->count();
        $lastMonth = PengukuranBalita::stunting()
            ->whereMonth('tanggal_pengukuran', now()->subMonth()->month)
            ->whereYear('tanggal_pengukuran', now()->subMonth()->year)
            ->count();
        $trend = $currentMonth - $lastMonth;

        // ─── Chart Data ──────────────────────────────────────────
        // 12-month stunting trend
        $trendData = [];
        $trendLabels = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $trendLabels[] = $date->translatedFormat('M');
            $trendData[] = PengukuranBalita::stunting()
                ->whereMonth('tanggal_pengukuran', $date->month)
                ->whereYear('tanggal_pengukuran', $date->year)
                ->count();
        }

        // Status gizi distribution (latest per child)
        $giziDistribution = [
            ['name' => 'Normal',       'y' => PengukuranBalita::whereIn('id', $latestIds)->where('status_gizi', 'normal')->count(),        'color' => '#22c55e'],
            ['name' => 'Pendek',       'y' => PengukuranBalita::whereIn('id', $latestIds)->where('status_gizi', 'pendek')->count(),        'color' => '#f59e0b'],
            ['name' => 'Sangat Pendek','y' => $sangatPendek, 'color' => '#ef4444'],
            ['name' => 'Tinggi',       'y' => PengukuranBalita::whereIn('id', $latestIds)->where('status_gizi', 'tinggi')->count(),        'color' => '#3b82f6'],
        ];

        // Intervention programs
        $intervensi = IntervensiStunting::orderByRaw("FIELD(status, 'berjalan', 'terjadwal', 'selesai')")
            ->limit(5)
            ->get();

        // Dusun list for filter
        $dusunList = Penduduk::select('dusun')->distinct()->orderBy('dusun')->pluck('dusun');

        return view('pages.backoffice.kesehatan.index', [
            'pengukuran'      => $pengukuran,
            'totalBalita'     => $totalBalita,
            'stuntingAktif'   => $stuntingAktif,
            'sangatPendek'    => $sangatPendek,
            'prevalensi'      => $prevalensi,
            'trend'           => $trend,
            'trendLabels'     => $trendLabels,
            'trendData'       => $trendData,
            'giziDistribution'=> $giziDistribution,
            'intervensi'      => $intervensi,
            'dusunList'       => $dusunList,
            'search'          => $search,
            'dusun'           => $dusun,
            'pageTitle'       => 'Kesehatan & Stunting',
        ]);
    }

    /**
     * Show measurement history for a child (JSON for drawer).
     */
    public function show(Penduduk $penduduk): JsonResponse
    {
        $riwayat = PengukuranBalita::where('penduduk_id', $penduduk->id)
            ->orderByDesc('tanggal_pengukuran')
            ->get()
            ->map(fn ($p) => [
                'id'                  => $p->id,
                'tanggal_pengukuran'  => $p->tanggal_pengukuran->format('d M Y'),
                'umur_bulan'          => $p->umur_bulan,
                'tinggi_badan'        => $p->tinggi_badan,
                'berat_badan'         => $p->berat_badan,
                'status_gizi'         => $p->status_gizi,
                'status_label'        => $p->statusBadge()['label'],
                'is_stunting'         => $p->isStunting(),
                'nama_ortu'           => $p->nama_ortu,
            ]);

        $latest = $riwayat->first();

        return response()->json([
            'id'          => $penduduk->id,
            'nik'         => $penduduk->nik,
            'nama'        => $penduduk->nama,
            'nama_ortu'   => $latest['nama_ortu'] ?? null,
            'no_kk'       => $penduduk->no_kk,
            'status_gizi' => $latest['status_gizi'] ?? 'normal',
            'is_stunting'  => $latest['is_stunting'] ?? false,
            'riwayat'     => $riwayat,
        ]);
    }

    /**
     * Store a new measurement.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'penduduk_id'        => ['required', 'exists:penduduk,id'],
            'tanggal_pengukuran' => ['required', 'date'],
            'tinggi_badan'       => ['required', 'numeric', 'min:30', 'max:200'],
            'berat_badan'        => ['required', 'numeric', 'min:1', 'max:50'],
            'status_gizi'        => ['required', 'in:normal,pendek,sangat_pendek,tinggi'],
            'nama_ortu'          => ['nullable', 'string', 'max:255'],
            'catatan'            => ['nullable', 'string', 'max:1000'],
        ], [
            'penduduk_id.required'        => 'Data balita wajib dipilih.',
            'tanggal_pengukuran.required'  => 'Tanggal pengukuran wajib diisi.',
            'tinggi_badan.required'        => 'Tinggi badan wajib diisi.',
            'berat_badan.required'         => 'Berat badan wajib diisi.',
        ]);

        // Calculate age in months from penduduk birth date
        $penduduk = Penduduk::findOrFail($validated['penduduk_id']);
        $birthDate = Carbon::parse($penduduk->tanggal_lahir);
        $measureDate = Carbon::parse($validated['tanggal_pengukuran']);
        $validated['umur_bulan'] = $birthDate->diffInMonths($measureDate);

        PengukuranBalita::create($validated);

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record($actor, 'input_pengukuran', "Input pengukuran untuk: {$penduduk->nama}");

        return redirect()
            ->route('admin.kesehatan.index')
            ->with('success', "Pengukuran untuk {$penduduk->nama} berhasil dicatat.");
    }

    /**
     * Search children for the form modal (JSON API).
     */
    public function searchBalita(Request $request): JsonResponse
    {
        $q = $request->input('q', '');

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $results = Penduduk::where('status', 'hidup')
            ->where(function ($query) use ($q) {
                $query->where('nama', 'like', "%{$q}%")
                      ->orWhere('nik', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get(['id', 'nik', 'nama', 'tanggal_lahir', 'jenis_kelamin'])
            ->map(fn ($p) => [
                'id'             => $p->id,
                'nik'            => $p->nik,
                'nama'           => $p->nama,
                'tanggal_lahir'  => $p->tanggal_lahir,
                'jenis_kelamin'  => $p->jenis_kelamin,
                'umur_bulan'     => Carbon::parse($p->tanggal_lahir)->diffInMonths(now()),
            ]);

        return response()->json($results);
    }

    /**
     * Delete a measurement record.
     */
    public function destroy(Request $request, PengukuranBalita $pengukuran): RedirectResponse
    {
        $nama = $pengukuran->penduduk?->nama ?? 'Unknown';
        $pengukuran->delete();

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record($actor, 'delete_pengukuran', "Menghapus pengukuran untuk: {$nama}");

        return redirect()
            ->route('admin.kesehatan.index')
            ->with('success', "Data pengukuran berhasil dihapus.");
    }
}
