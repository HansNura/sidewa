<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Penduduk;
use App\Models\PenerimaBansos;
use App\Models\ProgramBansos;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BansosController extends Controller
{
    /**
     * Display bansos dashboard with programs + recipients table.
     */
    public function index(Request $request): View
    {
        $search  = $request->input('search');
        $program = $request->input('program');
        $status  = $request->input('status');

        $programs = ProgramBansos::withCount('penerima')->orderByDesc('status')->get();

        $penerima = PenerimaBansos::with(['penduduk', 'program'])
            ->search($search)
            ->filterProgram($program)
            ->filterStatus($status)
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        // Audit: count duplicates
        $duplikatCount = PenerimaBansos::duplikat()->count();

        return view('pages.backoffice.bansos.index', [
            'programs'      => $programs,
            'penerima'      => $penerima,
            'duplikatCount' => $duplikatCount,
            'programList'   => ProgramBansos::orderBy('nama')->get(),
            'search'        => $search,
            'selectedProgram' => $program,
            'selectedStatus'  => $status,
            'pageTitle'     => 'Bantuan Sosial',
        ]);
    }

    /**
     * Show detail for a penerima (JSON for drawer).
     */
    public function show(PenerimaBansos $banso): JsonResponse
    {
        $banso->load(['penduduk', 'program']);

        // History: all bansos received by this penduduk
        $riwayat = PenerimaBansos::where('penduduk_id', $banso->penduduk_id)
            ->with('program')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($p) => [
                'id'                 => $p->id,
                'program_nama'       => $p->program?->nama,
                'tahap'              => $p->tahap,
                'status'             => $p->status_distribusi,
                'status_label'       => $p->statusBadge()['label'],
                'tanggal_distribusi' => $p->tanggal_distribusi?->format('d M Y'),
                'is_current'         => $p->id === $banso->id,
            ]);

        return response()->json([
            'id'                  => $banso->id,
            'penduduk_nama'       => $banso->penduduk?->nama,
            'penduduk_nik'        => $banso->penduduk?->nik,
            'penduduk_no_kk'      => $banso->penduduk?->no_kk,
            'penduduk_alamat'     => $banso->penduduk?->alamat,
            'penduduk_dusun'      => $banso->penduduk?->dusun,
            'penduduk_rt'         => $banso->penduduk?->rt,
            'penduduk_rw'         => $banso->penduduk?->rw,
            'penduduk_pekerjaan'  => $banso->penduduk?->pekerjaan,
            'program_nama'        => $banso->program?->nama,
            'tahap'               => $banso->tahap,
            'desil'               => $banso->desil,
            'status_distribusi'   => $banso->status_distribusi,
            'status_badge'        => $banso->statusBadge(),
            'tanggal_distribusi'  => $banso->tanggal_distribusi?->format('d M Y'),
            'is_duplikat'         => $banso->is_duplikat,
            'catatan_audit'       => $banso->catatan_audit,
            'riwayat'             => $riwayat,
        ]);
    }

    /**
     * Store new recipient.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'penduduk_id'       => ['required', 'exists:penduduk,id'],
            'program_bansos_id' => ['required', 'exists:program_bansos,id'],
            'tahap'             => ['nullable', 'string', 'max:100'],
            'desil'             => ['nullable', 'integer', 'min:1', 'max:4'],
        ], [
            'penduduk_id.required'       => 'Penerima manfaat wajib dipilih.',
            'program_bansos_id.required' => 'Program bantuan wajib dipilih.',
        ]);

        // Check for duplicate
        $exists = PenerimaBansos::where('penduduk_id', $validated['penduduk_id'])
            ->where('program_bansos_id', $validated['program_bansos_id'])
            ->where('tahap', $validated['tahap'] ?? null)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['penduduk_id' => 'Penerima sudah terdaftar pada program dan tahap yang sama.'])
                ->withInput();
        }

        // Auto-detect duplicates across programs
        $isDuplikat = PenerimaBansos::where('penduduk_id', $validated['penduduk_id'])
            ->where('program_bansos_id', $validated['program_bansos_id'])
            ->exists();

        $validated['status_distribusi'] = 'pending';
        $validated['is_duplikat'] = $isDuplikat;
        if ($isDuplikat) {
            $validated['status_distribusi'] = 'tertahan';
            $validated['catatan_audit'] = 'Auto-flagged: Penerima sudah ada di program yang sama pada tahap sebelumnya.';
        }

        $penerima = PenerimaBansos::create($validated);

        $penduduk = Penduduk::find($validated['penduduk_id']);

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record($actor, 'tambah_penerima_bansos', "Menambahkan penerima bansos: {$penduduk->nama}");

        return redirect()
            ->route('admin.bansos.index')
            ->with('success', "Penerima {$penduduk->nama} berhasil ditambahkan.");
    }

    /**
     * Update distribution status.
     */
    public function updateStatus(Request $request, PenerimaBansos $banso): RedirectResponse
    {
        $validated = $request->validate([
            'status_distribusi' => ['required', 'in:pending,siap_diambil,diterima,tertahan'],
        ]);

        if ($validated['status_distribusi'] === 'diterima') {
            $validated['tanggal_distribusi'] = now();
        }

        $banso->update($validated);

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record($actor, 'update_status_bansos', "Update status distribusi: {$banso->penduduk?->nama} → {$validated['status_distribusi']}");

        return redirect()
            ->route('admin.bansos.index')
            ->with('success', "Status distribusi berhasil diperbarui.");
    }

    /**
     * Delete recipient.
     */
    public function destroy(Request $request, PenerimaBansos $banso): RedirectResponse
    {
        $nama = $banso->penduduk?->nama ?? 'Unknown';
        $banso->delete();

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record($actor, 'hapus_penerima_bansos', "Menghapus penerima bansos: {$nama}");

        return redirect()
            ->route('admin.bansos.index')
            ->with('success', "Penerima {$nama} berhasil dihapus.");
    }

    /**
     * Search penduduk for the form (JSON API).
     */
    public function searchPenduduk(Request $request): JsonResponse
    {
        $q = $request->input('q', '');
        if (strlen($q) < 2) return response()->json([]);

        $results = Penduduk::where('status', 'hidup')
            ->where(fn ($query) =>
                $query->where('nama', 'like', "%{$q}%")
                      ->orWhere('nik', 'like', "%{$q}%")
                      ->orWhere('no_kk', 'like', "%{$q}%")
            )
            ->limit(10)
            ->get(['id', 'nik', 'nama', 'no_kk', 'dusun'])
            ->map(fn ($p) => [
                'id'    => $p->id,
                'nik'   => $p->nik,
                'nama'  => $p->nama,
                'no_kk' => $p->no_kk,
                'dusun' => $p->dusun,
            ]);

        return response()->json($results);
    }
}
