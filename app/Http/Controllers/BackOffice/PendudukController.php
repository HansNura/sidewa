<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PendudukController extends Controller
{
    /**
     * Display data penduduk list with search/filter, statistics, and pagination.
     */
    public function index(Request $request): View
    {
        $penduduk = Penduduk::query()
            ->search($request->input('search'))
            ->filterDusun($request->input('dusun'))
            ->filterGender($request->input('jenis_kelamin'))
            ->filterStatus($request->input('status'))
            ->filterPerkawinan($request->input('perkawinan'))
            ->filterUsia($request->input('usia'))
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        $stats = Penduduk::statistics();

        // Get distinct dusun values for filter dropdown
        $dusunList = Penduduk::whereNotNull('dusun')
            ->distinct()
            ->orderBy('dusun')
            ->pluck('dusun');

        return view('pages.backoffice.penduduk.index', [
            'penduduk'   => $penduduk,
            'stats'      => $stats,
            'dusunList'  => $dusunList,
            'filters'    => $request->only(['search', 'dusun', 'jenis_kelamin', 'status', 'perkawinan', 'usia']),
            'pageTitle'  => 'Data Penduduk',
        ]);
    }

    /**
     * Show a single penduduk detail (JSON for drawer).
     */
    public function show(Penduduk $penduduk): JsonResponse
    {
        return response()->json([
            'id'                => $penduduk->id,
            'nik'               => $penduduk->nik,
            'nama'              => $penduduk->nama,
            'tempat_lahir'      => $penduduk->tempat_lahir,
            'tanggal_lahir'     => $penduduk->tanggal_lahir->format('d-m-Y'),
            'jenis_kelamin'     => $penduduk->jenis_kelamin,
            'jenis_kelamin_label' => $penduduk->jenisKelaminLabel(),
            'agama'             => $penduduk->agama,
            'golongan_darah'    => $penduduk->golongan_darah,
            'no_kk'             => $penduduk->no_kk,
            'status_hubungan'   => $penduduk->status_hubungan,
            'status_perkawinan' => $penduduk->status_perkawinan,
            'nama_ayah'         => $penduduk->nama_ayah,
            'nama_ibu'          => $penduduk->nama_ibu,
            'pendidikan'        => $penduduk->pendidikan,
            'pekerjaan'         => $penduduk->pekerjaan,
            'alamat'            => $penduduk->alamat,
            'alamat_lengkap'    => $penduduk->alamatLengkap(),
            'dusun'             => $penduduk->dusun,
            'rt'                => $penduduk->rt,
            'rw'                => $penduduk->rw,
            'umur'              => $penduduk->umur(),
            'status'            => $penduduk->status,
            'status_color'      => $penduduk->statusColor(),
        ]);
    }

    /**
     * Store a new penduduk record.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nik'               => ['required', 'string', 'size:16', 'unique:penduduk,nik'],
            'nama'              => ['required', 'string', 'max:255'],
            'tempat_lahir'      => ['required', 'string', 'max:255'],
            'tanggal_lahir'     => ['required', 'date', 'before:today'],
            'jenis_kelamin'     => ['required', Rule::in(['L', 'P'])],
            'agama'             => ['required', 'string', 'max:50'],
            'golongan_darah'    => ['nullable', 'string', 'max:5'],
            'no_kk'             => ['required', 'string', 'size:16'],
            'status_hubungan'   => ['required', 'string', 'max:50'],
            'status_perkawinan' => ['required', 'string', 'max:50'],
            'nama_ayah'         => ['nullable', 'string', 'max:255'],
            'nama_ibu'          => ['nullable', 'string', 'max:255'],
            'pendidikan'        => ['nullable', 'string', 'max:100'],
            'pekerjaan'         => ['nullable', 'string', 'max:255'],
            'alamat'            => ['nullable', 'string', 'max:1000'],
            'dusun'             => ['nullable', 'string', 'max:100'],
            'rt'                => ['nullable', 'string', 'max:5'],
            'rw'                => ['nullable', 'string', 'max:5'],
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.size'     => 'NIK harus 16 digit.',
            'nik.unique'   => 'NIK sudah terdaftar di database.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'no_kk.size'   => 'No. KK harus 16 digit.',
        ]);

        $validated['nama'] = strtoupper($validated['nama']);

        $penduduk = Penduduk::create($validated);

        /** @var User $actor */
        $actor = $request->user();

        ActivityLog::record(
            $actor,
            'create_penduduk',
            "Menambahkan data penduduk: {$penduduk->nama} (NIK: {$penduduk->nik})",
            ['penduduk_id' => $penduduk->id]
        );

        return redirect()
            ->route('admin.penduduk.index')
            ->with('success', "Data penduduk {$penduduk->nama} berhasil ditambahkan.");
    }

    /**
     * Update an existing penduduk record.
     */
    public function update(Request $request, Penduduk $penduduk): RedirectResponse
    {
        $validated = $request->validate([
            'nik'               => ['required', 'string', 'size:16', Rule::unique('penduduk')->ignore($penduduk->id)],
            'nama'              => ['required', 'string', 'max:255'],
            'tempat_lahir'      => ['required', 'string', 'max:255'],
            'tanggal_lahir'     => ['required', 'date', 'before:today'],
            'jenis_kelamin'     => ['required', Rule::in(['L', 'P'])],
            'agama'             => ['required', 'string', 'max:50'],
            'golongan_darah'    => ['nullable', 'string', 'max:5'],
            'no_kk'             => ['required', 'string', 'size:16'],
            'status_hubungan'   => ['required', 'string', 'max:50'],
            'status_perkawinan' => ['required', 'string', 'max:50'],
            'nama_ayah'         => ['nullable', 'string', 'max:255'],
            'nama_ibu'          => ['nullable', 'string', 'max:255'],
            'pendidikan'        => ['nullable', 'string', 'max:100'],
            'pekerjaan'         => ['nullable', 'string', 'max:255'],
            'alamat'            => ['nullable', 'string', 'max:1000'],
            'dusun'             => ['nullable', 'string', 'max:100'],
            'rt'                => ['nullable', 'string', 'max:5'],
            'rw'                => ['nullable', 'string', 'max:5'],
            'status'            => ['required', Rule::in(['hidup', 'pindah', 'meninggal'])],
        ]);

        $validated['nama'] = strtoupper($validated['nama']);

        $penduduk->update($validated);

        /** @var User $actor */
        $actor = $request->user();

        ActivityLog::record(
            $actor,
            'update_penduduk',
            "Memperbarui data penduduk: {$penduduk->nama} (NIK: {$penduduk->nik})",
            ['penduduk_id' => $penduduk->id]
        );

        return redirect()
            ->route('admin.penduduk.index')
            ->with('success', "Data penduduk {$penduduk->nama} berhasil diperbarui.");
    }

    /**
     * Delete a penduduk record.
     */
    public function destroy(Request $request, Penduduk $penduduk): RedirectResponse
    {
        $nama = $penduduk->nama;
        $nik = $penduduk->nik;

        $penduduk->delete();

        /** @var User $actor */
        $actor = $request->user();

        ActivityLog::record(
            $actor,
            'delete_penduduk',
            "Menghapus data penduduk: {$nama} (NIK: {$nik})"
        );

        return redirect()
            ->route('admin.penduduk.index')
            ->with('success', "Data penduduk {$nama} berhasil dihapus.");
    }
}
