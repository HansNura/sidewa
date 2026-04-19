<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KartuKeluargaController extends Controller
{
    /**
     * Display KK list with search/filter and pagination.
     */
    public function index(Request $request): View
    {
        $kkList = KartuKeluarga::query()
            ->with(['kepalaKeluarga:id,kartu_keluarga_id,nik,nama,jenis_kelamin,status_hubungan'])
            ->withCount('anggota')
            ->search($request->input('search'))
            ->filterDusun($request->input('dusun'))
            ->orderByAnggota($request->input('sort_anggota'))
            ->paginate(15)
            ->appends($request->query());

        // Distinct dusun for filter
        $dusunList = KartuKeluarga::whereNotNull('dusun')
            ->distinct()
            ->orderBy('dusun')
            ->pluck('dusun');

        // Count unlinked penduduk (have no KK assigned)
        $unlinkedCount = Penduduk::whereNull('kartu_keluarga_id')
            ->where('status', 'hidup')
            ->count();

        return view('pages.backoffice.kartu-keluarga.index', [
            'kkList'        => $kkList,
            'dusunList'     => $dusunList,
            'unlinkedCount' => $unlinkedCount,
            'filters'       => $request->only(['search', 'dusun', 'sort_anggota']),
            'pageTitle'     => 'Data Keluarga (KK)',
        ]);
    }

    /**
     * Show KK detail with all members (JSON for drawer).
     */
    public function show(KartuKeluarga $kartuKeluarga): JsonResponse
    {
        $kartuKeluarga->load(['anggota' => function ($q) {
            $q->orderByRaw("FIELD(status_hubungan, 'Kepala Keluarga', 'Istri', 'Anak', 'Famili Lain')");
        }]);

        return response()->json([
            'id'                   => $kartuKeluarga->id,
            'no_kk'                => $kartuKeluarga->no_kk,
            'alamat'               => $kartuKeluarga->alamat,
            'dusun'                => $kartuKeluarga->dusun,
            'rt'                   => $kartuKeluarga->rt,
            'rw'                   => $kartuKeluarga->rw,
            'wilayah'              => $kartuKeluarga->wilayah(),
            'tanggal_dikeluarkan'  => $kartuKeluarga->tanggal_dikeluarkan?->format('d F Y'),
            'jumlah_anggota'       => $kartuKeluarga->anggota->count(),
            'anggota'              => $kartuKeluarga->anggota->map(fn (Penduduk $p) => [
                'id'              => $p->id,
                'nik'             => $p->nik,
                'nama'            => $p->nama,
                'jenis_kelamin'   => $p->jenis_kelamin,
                'status_hubungan' => $p->status_hubungan,
                'pendidikan'      => $p->pendidikan,
                'pekerjaan'       => $p->pekerjaan,
                'umur'            => $p->umur(),
            ]),
            'kepala' => $kartuKeluarga->anggota
                ->firstWhere('status_hubungan', 'Kepala Keluarga')?->nama,
        ]);
    }

    /**
     * Store a new KK.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'no_kk'                => ['required', 'string', 'size:16', 'unique:kartu_keluarga,no_kk'],
            'kepala_keluarga_id'   => ['required', 'exists:penduduk,id'],
            'alamat'               => ['required', 'string', 'max:1000'],
            'dusun'                => ['required', 'string', 'max:100'],
            'rt'                   => ['required', 'string', 'max:5'],
            'rw'                   => ['required', 'string', 'max:5'],
            'tanggal_dikeluarkan'  => ['nullable', 'date'],
        ], [
            'no_kk.required' => 'Nomor KK wajib diisi.',
            'no_kk.size'     => 'Nomor KK harus 16 digit.',
            'no_kk.unique'   => 'Nomor KK sudah terdaftar.',
            'kepala_keluarga_id.required' => 'Kepala keluarga wajib dipilih.',
            'kepala_keluarga_id.exists'   => 'Penduduk tidak ditemukan.',
        ]);

        $kk = KartuKeluarga::create([
            'no_kk'               => $validated['no_kk'],
            'alamat'              => $validated['alamat'],
            'dusun'               => $validated['dusun'],
            'rt'                  => $validated['rt'],
            'rw'                  => $validated['rw'],
            'tanggal_dikeluarkan' => $validated['tanggal_dikeluarkan'] ?? now(),
        ]);

        // Assign the head of family
        $kepala = Penduduk::findOrFail($validated['kepala_keluarga_id']);
        $kepala->update([
            'kartu_keluarga_id' => $kk->id,
            'no_kk'             => $kk->no_kk,
            'status_hubungan'   => 'Kepala Keluarga',
            'dusun'             => $kk->dusun,
            'rt'                => $kk->rt,
            'rw'                => $kk->rw,
        ]);

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record($actor, 'create_kk', "Membuat KK baru: {$kk->no_kk} (Kepala: {$kepala->nama})");

        return redirect()
            ->route('admin.kartu-keluarga.index')
            ->with('success', "Kartu Keluarga {$kk->no_kk} berhasil dibuat.");
    }

    /**
     * Add a member to a KK.
     */
    public function addMember(Request $request, KartuKeluarga $kartuKeluarga): RedirectResponse
    {
        $validated = $request->validate([
            'penduduk_id'     => ['required', 'exists:penduduk,id'],
            'status_hubungan' => ['required', 'string', 'max:50'],
        ]);

        $penduduk = Penduduk::findOrFail($validated['penduduk_id']);
        $penduduk->update([
            'kartu_keluarga_id' => $kartuKeluarga->id,
            'no_kk'             => $kartuKeluarga->no_kk,
            'status_hubungan'   => $validated['status_hubungan'],
            'dusun'             => $kartuKeluarga->dusun,
            'rt'                => $kartuKeluarga->rt,
            'rw'                => $kartuKeluarga->rw,
        ]);

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record($actor, 'add_kk_member', "Menambahkan {$penduduk->nama} ke KK {$kartuKeluarga->no_kk}");

        return redirect()
            ->route('admin.kartu-keluarga.index')
            ->with('success', "{$penduduk->nama} berhasil ditambahkan ke KK {$kartuKeluarga->no_kk}.");
    }

    /**
     * Remove a member from a KK.
     */
    public function removeMember(Request $request, KartuKeluarga $kartuKeluarga, Penduduk $penduduk): RedirectResponse
    {
        $penduduk->update([
            'kartu_keluarga_id' => null,
        ]);

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record($actor, 'remove_kk_member', "Mengeluarkan {$penduduk->nama} dari KK {$kartuKeluarga->no_kk}");

        return redirect()
            ->route('admin.kartu-keluarga.index')
            ->with('success', "{$penduduk->nama} berhasil dikeluarkan dari KK.");
    }

    /**
     * Delete entire KK.
     */
    public function destroy(Request $request, KartuKeluarga $kartuKeluarga): RedirectResponse
    {
        // Unlink all members first
        Penduduk::where('kartu_keluarga_id', $kartuKeluarga->id)
            ->update(['kartu_keluarga_id' => null]);

        $noKk = $kartuKeluarga->no_kk;
        $kartuKeluarga->delete();

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record($actor, 'delete_kk', "Menghapus KK: {$noKk}");

        return redirect()
            ->route('admin.kartu-keluarga.index')
            ->with('success', "Kartu Keluarga {$noKk} berhasil dihapus.");
    }

    /**
     * API: Search unlinked penduduk for "add member" modal.
     */
    public function searchPenduduk(Request $request): JsonResponse
    {
        $search = $request->input('q', '');

        $results = Penduduk::whereNull('kartu_keluarga_id')
            ->where('status', 'hidup')
            ->where(function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get(['id', 'nik', 'nama', 'jenis_kelamin']);

        return response()->json($results);
    }
}
