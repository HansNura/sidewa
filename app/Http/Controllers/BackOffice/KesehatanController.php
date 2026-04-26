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
        $rw     = $request->input('rw');
        $rt     = $request->input('rt');
        $gizi   = $request->input('gizi');
        $jk     = $request->input('jk');
        $umur   = $request->input('umur');

        // Latest measurement per child (deduped)
        $query = PengukuranBalita::with('penduduk')
            ->latestPerChild()
            ->orderByDesc('tanggal_pengukuran');

        if ($search) {
            $query->search($search);
        }

        if ($dusun || $rw || $rt || $jk) {
            $query->whereHas('penduduk', function($q) use ($dusun, $rw, $rt, $jk) {
                if ($dusun) $q->where('dusun', $dusun);
                if ($rw) $q->where('rw', $rw);
                if ($rt) $q->where('rt', $rt);
                if ($jk) $q->where('jenis_kelamin', $jk);
            });
        }

        if ($gizi) {
            if ($gizi === 'stunting') {
                $query->stunting();
            } else {
                $query->where('status_gizi', $gizi);
            }
        }

        if ($umur) {
            if ($umur === '0-6') $query->whereBetween('umur_bulan', [0, 6]);
            elseif ($umur === '7-24') $query->whereBetween('umur_bulan', [7, 24]);
            elseif ($umur === '25-59') $query->whereBetween('umur_bulan', [25, 59]);
        }

        $pengukuran = $query->paginate(5)->withQueryString();

        // ─── KPI Statistics ─────────────────────────────────────
        $kpiQuery = PengukuranBalita::latestPerChild();
        if ($dusun) {
            $kpiQuery->whereHas('penduduk', fn($q) => $q->where('dusun', $dusun));
        }
        $latestIds = $kpiQuery->pluck('id');

        $totalBalita    = $latestIds->count();
        $stuntingAktif  = PengukuranBalita::whereIn('id', $latestIds)->stunting()->count();
        $sangatPendek   = PengukuranBalita::whereIn('id', $latestIds)->where('status_gizi', 'sangat_pendek')->count();
        $prevalensi     = $totalBalita > 0 ? round(($stuntingAktif / $totalBalita) * 100, 1) : 0;

        // Trend: compare current month stunting vs last month
        $qCurrent = PengukuranBalita::stunting()
            ->whereMonth('tanggal_pengukuran', now()->month)
            ->whereYear('tanggal_pengukuran', now()->year);
        if ($dusun) $qCurrent->whereHas('penduduk', fn($q) => $q->where('dusun', $dusun));
        $currentMonth = $qCurrent->count();

        $qLast = PengukuranBalita::stunting()
            ->whereMonth('tanggal_pengukuran', now()->subMonth()->month)
            ->whereYear('tanggal_pengukuran', now()->subMonth()->year);
        if ($dusun) $qLast->whereHas('penduduk', fn($q) => $q->where('dusun', $dusun));
        $lastMonth = $qLast->count();
        
        $trend = $currentMonth - $lastMonth;

        // ─── Chart Data ──────────────────────────────────────────
        // 12-month stunting trend
        $trendData = [];
        $trendLabels = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $trendLabels[] = $date->translatedFormat('M');
            $qTrend = PengukuranBalita::stunting()
                ->whereMonth('tanggal_pengukuran', $date->month)
                ->whereYear('tanggal_pengukuran', $date->year);
            if ($dusun) $qTrend->whereHas('penduduk', fn($q) => $q->where('dusun', $dusun));
            $trendData[] = $qTrend->count();
        }

        // Status gizi distribution (latest per child)
        $giziDistribution = [
            ['name' => 'Normal',       'y' => PengukuranBalita::whereIn('id', $latestIds)->where('status_gizi', 'normal')->count(),        'color' => '#22c55e'],
            ['name' => 'Pendek',       'y' => PengukuranBalita::whereIn('id', $latestIds)->where('status_gizi', 'pendek')->count(),        'color' => '#f59e0b'],
            ['name' => 'Sangat Pendek', 'y' => $sangatPendek, 'color' => '#ef4444'],
            ['name' => 'Tinggi',       'y' => PengukuranBalita::whereIn('id', $latestIds)->where('status_gizi', 'tinggi')->count(),        'color' => '#3b82f6'],
        ];

        // Intervention programs
        $statusOrder = "CASE status WHEN 'berjalan' THEN 1 WHEN 'terjadwal' THEN 2 WHEN 'selesai' THEN 3 ELSE 4 END";
        $intervensi = IntervensiStunting::orderByRaw($statusOrder)
            ->limit(5)
            ->get();

        // Dusun list for filter from Wilayah table
        $dusunList = \App\Models\Wilayah::where('tipe', 'dusun')->orderBy('nama')->pluck('nama');

        return view('pages.backoffice.kesehatan.index', [
            'pengukuran'      => $pengukuran,
            'totalBalita'     => $totalBalita,
            'stuntingAktif'   => $stuntingAktif,
            'sangatPendek'    => $sangatPendek,
            'prevalensi'      => $prevalensi,
            'trend'           => $trend,
            'trendLabels'     => $trendLabels,
            'trendData'       => $trendData,
            'giziDistribution' => $giziDistribution,
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
            ->map(fn($p) => [
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

        // Auto-fill nama_ortu if empty
        if (empty($validated['nama_ortu'])) {
            $validated['nama_ortu'] = $penduduk->getOrangTuaGabungan();
        }

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
        $mode = $request->input('mode', 'search');

        // Always restrict to 0-59 months old
        $query = Penduduk::where('status', 'hidup')
            ->where('tanggal_lahir', '>=', now()->subMonths(60));

        if ($q) {
            $query->where(function ($queryBuilder) use ($q) {
                $queryBuilder->where('nama', 'like', "%{$q}%")
                      ->orWhere('nik', 'like', "%{$q}%");
            });
        }

        if ($mode === 'browse') {
            // Apply extra filters for browse modal
            if ($jk = $request->input('jk')) {
                $query->where('jenis_kelamin', $jk);
            }
            if ($umur = $request->input('umur')) {
                $now = now();
                if ($umur === '0-6') {
                    $query->whereBetween('tanggal_lahir', [(clone $now)->subMonths(6), $now]);
                } elseif ($umur === '7-24') {
                    $query->whereBetween('tanggal_lahir', [(clone $now)->subMonths(24), (clone $now)->subMonths(7)]);
                } elseif ($umur === '25-59') {
                    $query->whereBetween('tanggal_lahir', [(clone $now)->subMonths(59), (clone $now)->subMonths(25)]);
                }
            }
            $limit = 50;
        } else {
            // Dropdown mode requires at least 2 chars
            if (strlen($q) < 2) {
                return response()->json([]);
            }
            $limit = 10;
        }

        $results = $query->limit($limit)
            ->with(['kartuKeluarga.anggota' => function($q) {
                // Hanya muat anggota yang berpotensi jadi orang tua untuk efisiensi
                $q->whereIn('status_hubungan', ['Kepala Keluarga', 'Istri', 'Suami', 'Ayah', 'Ibu']);
            }])
            ->orderBy('nama')
            ->get(['id', 'nik', 'nama', 'tanggal_lahir', 'jenis_kelamin', 'nama_ayah', 'nama_ibu', 'kartu_keluarga_id'])
            ->map(function($p) {
                return [
                    'id'                 => $p->id,
                    'nik'                => $p->nik,
                    'nama'               => $p->nama,
                    'tanggal_lahir'      => $p->tanggal_lahir,
                    'jenis_kelamin'      => $p->jenis_kelamin,
                    'nama_ayah'          => $p->nama_ayah,
                    'nama_ibu'           => $p->nama_ibu,
                    'nama_ortu_gabungan' => $p->getOrangTuaGabungan(),
                    'umur_bulan'         => (int) floor(Carbon::parse($p->tanggal_lahir)->diffInMonths(now())),
                ];
            });

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
