<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Penduduk;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WilayahController extends Controller
{
    /**
     * Display wilayah tree + table + map.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $tree = Wilayah::tree();
        $flatList = Wilayah::flatList($search);

        // Collect GeoJSON features for the map
        $mapFeatures = Wilayah::whereNotNull('geojson')->get()
            ->map(fn (Wilayah $w) => [
                'label'   => $w->label(),
                'tipe'    => $w->tipe,
                'geojson' => $w->geojson,
            ]);

        // All dusun for the parent dropdown in the form
        $dusunList = Wilayah::where('tipe', 'dusun')->orderBy('nama')->get();
        $rwList = Wilayah::where('tipe', 'rw')->with('parent')->orderBy('nama')->get();

        return view('pages.backoffice.wilayah.index', [
            'tree'        => $tree,
            'flatList'    => $flatList,
            'mapFeatures' => $mapFeatures,
            'dusunList'   => $dusunList,
            'rwList'      => $rwList,
            'search'      => $search,
            'pageTitle'   => 'Wilayah Administratif',
        ]);
    }

    /**
     * Show detail for a wilayah (JSON for drawer).
     */
    public function show(Wilayah $wilayah): JsonResponse
    {
        $wilayah->load('parent', 'children');

        $pendudukList = $wilayah->pendudukQuery()
            ->limit(10)
            ->get(['id', 'nik', 'nama', 'rt', 'rw']);

        return response()->json([
            'id'              => $wilayah->id,
            'tipe'            => strtoupper($wilayah->tipe),
            'nama'            => $wilayah->nama,
            'label'           => $wilayah->label(),
            'parent_path'     => $wilayah->parentPath(),
            'kepala_nama'     => $wilayah->kepala_nama,
            'kepala_jabatan'  => $wilayah->kepala_jabatan,
            'kepala_telepon'  => $wilayah->kepala_telepon,
            'populasi'        => $wilayah->populasi(),
            'jumlah_kk'       => $wilayah->jumlahKK(),
            'penduduk'        => $pendudukList->map(fn ($p) => [
                'id'   => $p->id,
                'nik'  => $p->nik,
                'nama' => $p->nama,
                'rt'   => $p->rt,
            ]),
        ]);
    }

    /**
     * Store a new wilayah.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tipe'            => ['required', Rule::in(['dusun', 'rw', 'rt'])],
            'nama'            => ['required', 'string', 'max:100'],
            'parent_id'       => ['nullable', 'exists:wilayah,id'],
            'kepala_nama'     => ['nullable', 'string', 'max:255'],
            'kepala_jabatan'  => ['nullable', 'string', 'max:100'],
            'kepala_telepon'  => ['nullable', 'string', 'max:20'],
            'geojson'         => ['nullable', 'json'],
        ], [
            'nama.required' => 'Nama wilayah wajib diisi.',
            'tipe.required' => 'Tipe wilayah wajib dipilih.',
        ]);

        // Parse geojson string to array if provided
        if (!empty($validated['geojson'])) {
            $validated['geojson'] = json_decode($validated['geojson'], true);
        }

        // Validate hierarchy: RW needs dusun parent, RT needs RW parent
        if ($validated['tipe'] !== 'dusun' && empty($validated['parent_id'])) {
            return back()->withErrors(['parent_id' => 'Wilayah induk wajib dipilih untuk tipe ' . strtoupper($validated['tipe'])])->withInput();
        }

        $wilayah = Wilayah::create($validated);

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record($actor, 'create_wilayah', "Menambahkan wilayah: {$wilayah->label()}");

        return redirect()
            ->route('admin.wilayah.index')
            ->with('success', "Wilayah {$wilayah->label()} berhasil ditambahkan.");
    }

    /**
     * Update a wilayah.
     */
    public function update(Request $request, Wilayah $wilayah): RedirectResponse
    {
        $validated = $request->validate([
            'nama'            => ['required', 'string', 'max:100'],
            'kepala_nama'     => ['nullable', 'string', 'max:255'],
            'kepala_jabatan'  => ['nullable', 'string', 'max:100'],
            'kepala_telepon'  => ['nullable', 'string', 'max:20'],
            'geojson'         => ['nullable', 'json'],
        ]);

        if (!empty($validated['geojson'])) {
            $validated['geojson'] = json_decode($validated['geojson'], true);
        }

        $wilayah->update($validated);

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record($actor, 'update_wilayah', "Memperbarui wilayah: {$wilayah->label()}");

        return redirect()
            ->route('admin.wilayah.index')
            ->with('success', "Wilayah {$wilayah->label()} berhasil diperbarui.");
    }

    /**
     * Delete a wilayah (cascade deletes children via FK).
     */
    public function destroy(Request $request, Wilayah $wilayah): RedirectResponse
    {
        $label = $wilayah->label();
        $wilayah->delete();

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record($actor, 'delete_wilayah', "Menghapus wilayah: {$label}");

        return redirect()
            ->route('admin.wilayah.index')
            ->with('success', "Wilayah {$label} berhasil dihapus.");
    }
}
