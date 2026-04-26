<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Penduduk;
use App\Models\Pertanahan;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PertanahanController extends Controller
{
    /**
     * Display pertanahan dashboard with stats, map, and table.
     */
    public function index(Request $request): View
    {
        $search       = $request->input('search');
        $kepemilikan  = $request->input('kepemilikan');
        $legalitas    = $request->input('legalitas');

        $query = Pertanahan::with('penduduk')
            ->search($search)
            ->filterKepemilikan($kepemilikan)
            ->filterLegalitas($legalitas)
            ->orderByDesc('created_at');

        $lahan = $query->paginate(5)->withQueryString();

        // ─── KPI Statistics ──────────────────────────────────────
        $totalLuas    = Pertanahan::sum('luas');
        $luasDesa     = Pertanahan::where('kepemilikan', 'desa')->sum('luas');
        $luasWarga    = Pertanahan::where('kepemilikan', 'warga')->sum('luas');
        $luasFasum    = Pertanahan::where('kepemilikan', 'fasum')->sum('luas');

        $pctDesa  = $totalLuas > 0 ? round(($luasDesa / $totalLuas) * 100, 1) : 0;
        $pctWarga = $totalLuas > 0 ? round(($luasWarga / $totalLuas) * 100, 1) : 0;
        $pctFasum = $totalLuas > 0 ? round(($luasFasum / $totalLuas) * 100, 1) : 0;

        // Convert m² to Ha for display
        $totalHa = round($totalLuas / 10000, 1);
        $desaHa  = round($luasDesa / 10000, 1);
        $wargaHa = round($luasWarga / 10000, 1);
        $fasumHa = round($luasFasum / 10000, 1);

        // ─── GeoJSON for map ─────────────────────────────────────
        $mapData = Pertanahan::whereNotNull('geojson')
            ->get(['id', 'kode_lahan', 'kepemilikan', 'nama_pemilik', 'penduduk_id', 'luas', 'lokasi_blok', 'geojson'])
            ->map(fn($p) => [
                'id'           => $p->id,
                'kode_lahan'   => $p->kode_lahan,
                'kepemilikan'  => $p->kepemilikan,
                'pemilik'      => $p->displayPemilik(),
                'luas'         => number_format($p->luas),
                'lokasi_blok'  => $p->lokasi_blok,
                'color'        => $p->mapColor(),
                'borderColor'  => $p->mapBorderColor(),
                'geojson'      => $p->geojson,
            ]);

        $wilayahTree = \App\Models\Wilayah::tree();

        return view('pages.backoffice.pertanahan.index', [
            'lahan'          => $lahan,
            'totalHa'        => $totalHa,
            'desaHa'         => $desaHa,
            'wargaHa'        => $wargaHa,
            'fasumHa'        => $fasumHa,
            'pctDesa'        => $pctDesa,
            'pctWarga'       => $pctWarga,
            'pctFasum'       => $pctFasum,
            'mapData'        => $mapData,
            'wilayahTree'    => $wilayahTree,
            'search'         => $search,
            'kepemilikan'    => $kepemilikan,
            'legalitas'      => $legalitas,
            'pageTitle'      => 'Data Pertanahan Desa',
        ]);
    }

    /**
     * Show detail for a land parcel (JSON for drawer).
     */
    public function show(Pertanahan $pertanahan): JsonResponse
    {
        $pertanahan->load('penduduk');

        return response()->json([
            'id'                => $pertanahan->id,
            'kode_lahan'        => $pertanahan->kode_lahan,
            'kepemilikan'       => $pertanahan->kepemilikan,
            'kepemilikan_label' => $pertanahan->kepemilikanBadge()['label'],
            'pemilik'           => $pertanahan->displayPemilik(),
            'penduduk_nik'      => $pertanahan->penduduk?->nik,
            'luas'              => $pertanahan->luas,
            'lokasi_blok'       => $pertanahan->lokasi_blok,
            'dusun'             => $pertanahan->dusun,
            'rt'                => $pertanahan->rt,
            'rw'                => $pertanahan->rw,
            'legalitas'         => $pertanahan->legalitas,
            'legalitas_label'   => $pertanahan->legalitasBadge()['label'],
            'nomor_sertifikat'  => $pertanahan->nomor_sertifikat,
            'geojson'           => $pertanahan->geojson,
            'catatan'           => $pertanahan->catatan,
            'color'             => $pertanahan->mapColor(),
            'borderColor'       => $pertanahan->mapBorderColor(),
            'created_at'        => $pertanahan->created_at?->format('d M Y'),
        ]);
    }

    /**
     * Store new land parcel.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kepemilikan'      => ['required', 'in:desa,warga,fasum'],
            'penduduk_id'      => ['nullable', 'exists:penduduk,id'],
            'luas'             => ['required', 'integer', 'min:1'],
            'lokasi_blok'      => ['required', 'string', 'max:255'],
            'dusun'            => ['nullable', 'string', 'max:100'],
            'rt'               => ['nullable', 'string', 'max:10'],
            'rw'               => ['nullable', 'string', 'max:10'],
            'legalitas'        => ['required', 'in:shm,shgb,girik,ajb,belum_sertifikat'],
            'nomor_sertifikat' => ['nullable', 'string', 'max:100'],
            'geojson'          => ['nullable', 'string'],
            'catatan'          => ['nullable', 'string', 'max:1000'],
        ], [
            'kepemilikan.required' => 'Jenis kepemilikan wajib dipilih.',
            'luas.required'        => 'Luas tanah wajib diisi.',
            'lokasi_blok.required' => 'Lokasi/blok tanah wajib diisi.',
            'legalitas.required'   => 'Status legalitas wajib dipilih.',
        ]);

        // Auto-generate kode lahan
        $validated['kode_lahan'] = Pertanahan::generateKode($validated['kepemilikan']);

        // Set nama_pemilik for desa/fasum
        if ($validated['kepemilikan'] === 'desa') {
            $validated['nama_pemilik'] = 'Pemerintah Desa';
        } elseif ($validated['kepemilikan'] === 'fasum') {
            $validated['nama_pemilik'] = 'Fasilitas Umum';
        }

        // Parse GeoJSON string to array
        if (!empty($validated['geojson'])) {
            $decoded = json_decode($validated['geojson'], true);
            $validated['geojson'] = $decoded ?: null;
        }

        Pertanahan::create($validated);

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record($actor, 'tambah_lahan', "Menambahkan lahan: {$validated['kode_lahan']} ({$validated['lokasi_blok']})");

        return redirect()
            ->route('admin.pertanahan.index')
            ->with('success', "Data lahan {$validated['kode_lahan']} berhasil disimpan.");
    }

    /**
     * Delete land parcel.
     */
    public function destroy(Request $request, Pertanahan $pertanahan): RedirectResponse
    {
        $kode = $pertanahan->kode_lahan;
        $pertanahan->delete();

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record($actor, 'hapus_lahan', "Menghapus lahan: {$kode}");

        return redirect()
            ->route('admin.pertanahan.index')
            ->with('success', "Data lahan {$kode} berhasil dihapus.");
    }

    /**
     * Search penduduk for owner field (JSON API).
     */
    public function searchPenduduk(Request $request): JsonResponse
    {
        $q = $request->input('q', '');

        $query = Penduduk::where('status', 'hidup');

        if (!empty($q)) {
            $query->where(
                fn($qBuilder) =>
                $qBuilder->where('nama', 'like', "%{$q}%")
                    ->orWhere('nik', 'like', "%{$q}%")
            );
        }

        $results = $query->limit(30)
            ->get(['id', 'nik', 'nama', 'dusun', 'rt', 'rw']);

        return response()->json($results);
    }
}
