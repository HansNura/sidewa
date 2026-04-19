<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SuratPermohonan;
use App\Models\User;
use App\Models\VillageSetting;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerifikasiSuratController extends Controller
{
    /**
     * Verification queue listing with stats, search & filter.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->input('status');
        $search       = $request->input('search');
        $tanggal      = $request->input('tanggal');

        $query = SuratPermohonan::with(['penduduk', 'operator'])
            ->whereIn('status', ['verifikasi', 'menunggu_tte'])
            ->searchArsip($search);

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }
        if ($tanggal) {
            $query->whereDate('tanggal_pengajuan', $tanggal);
        }

        // Prioritize menunggu_tte first, then by date
        $queue = $query->orderByRaw("FIELD(status, 'menunggu_tte', 'verifikasi')")
            ->orderByDesc('tanggal_pengajuan')
            ->paginate(20)
            ->withQueryString();

        // Stats
        $countVerifikasi  = SuratPermohonan::where('status', 'verifikasi')->count();
        $countTTE         = SuratPermohonan::where('status', 'menunggu_tte')->count();
        $countSelesaiToday = SuratPermohonan::where('status', 'selesai')
            ->whereDate('tanggal_selesai', Carbon::today())
            ->count();

        return view('pages.backoffice.verifikasi-surat.index', [
            'pageTitle'         => 'Verifikasi & TTE Surat',
            'queue'             => $queue,
            'countVerifikasi'   => $countVerifikasi,
            'countTTE'          => $countTTE,
            'countSelesaiToday' => $countSelesaiToday,
            'search'            => $search,
            'selStatus'         => $statusFilter,
            'selTanggal'        => $tanggal,
        ]);
    }

    /**
     * Get full detail for the verification workspace (JSON).
     */
    public function show(SuratPermohonan $surat): JsonResponse
    {
        $surat->load(['penduduk', 'operator']);
        $village = VillageSetting::instance();

        return response()->json([
            'id'              => $surat->id,
            'nomor_tiket'     => $surat->nomor_tiket,
            'jenis_surat'     => $surat->jenis_surat,
            'jenis_label'     => $surat->jenisLabel(),
            'jenis_short'     => $surat->jenisShort(),
            'status'          => $surat->status,
            'status_badge'    => $surat->statusBadge(),
            'keperluan'       => $surat->keperluan,
            'berlaku_hingga'  => $surat->berlaku_hingga,
            'nama_usaha'      => $surat->nama_usaha,
            'catatan'         => $surat->catatan,
            'tanggal_pengajuan' => $surat->tanggal_pengajuan->translatedFormat('d M Y, H:i'),
            'penduduk' => $surat->penduduk ? [
                'id'        => $surat->penduduk->id,
                'nik'       => $surat->penduduk->nik,
                'nama'      => $surat->penduduk->nama,
                'ttl'       => $surat->penduduk->tempat_lahir . ', ' . $surat->penduduk->tanggal_lahir->translatedFormat('d F Y'),
                'pekerjaan' => $surat->penduduk->pekerjaan ?? '-',
                'alamat'    => $surat->penduduk->alamatLengkap(),
                'agama'     => $surat->penduduk->agama ?? '-',
                'jenis_kelamin' => $surat->penduduk->jenis_kelamin ?? '-',
            ] : null,
            'operator_name' => $surat->operator?->name ?? '-',
            'village' => [
                'nama_desa'     => $village->nama_desa,
                'kecamatan'     => $village->kecamatan,
                'kabupaten'     => $village->kabupaten,
                'alamat'        => $village->alamat,
                'kode_pos'      => $village->kode_pos,
                'nama_kades'    => $village->nama_kades,
                'nip_kades'     => $village->nip_kades,
                'jabatan_kades' => $village->jabatan_kades,
                'logo_url'      => $village->logoUrl(),
            ],
        ]);
    }

    /**
     * Approve & apply TTE (digital signature).
     */
    public function approve(Request $request, SuratPermohonan $surat): JsonResponse
    {
        $request->validate([
            'pin' => ['required', 'string', 'size:6'],
        ], [
            'pin.required' => 'PIN keamanan wajib diisi.',
            'pin.size'     => 'PIN harus 6 digit.',
        ]);

        // In a real system, this would verify against a stored hashed PIN.
        // For now, we accept any 6-digit PIN as valid.

        if (!in_array($surat->status, ['verifikasi', 'menunggu_tte'])) {
            return response()->json([
                'success' => false,
                'message' => 'Surat tidak dalam status yang dapat disetujui.',
            ], 422);
        }

        $surat->update([
            'status'          => 'selesai',
            'tanggal_selesai' => Carbon::now(),
        ]);

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record(
            $actor,
            'tte_surat',
            "Menandatangani (TTE) surat {$surat->nomor_tiket} — {$surat->jenisShort()} untuk {$surat->penduduk?->nama}",
            ['surat_id' => $surat->id, 'jenis' => $surat->jenis_surat]
        );

        return response()->json([
            'success' => true,
            'message' => "TTE berhasil! Surat {$surat->nomor_tiket} telah dipindahkan ke Arsip.",
        ]);
    }

    /**
     * Reject a letter application.
     */
    public function reject(Request $request, SuratPermohonan $surat): JsonResponse
    {
        $request->validate([
            'alasan_tolak' => ['required', 'string', 'max:1000'],
        ], [
            'alasan_tolak.required' => 'Alasan penolakan wajib diisi.',
        ]);

        if (!in_array($surat->status, ['verifikasi', 'menunggu_tte'])) {
            return response()->json([
                'success' => false,
                'message' => 'Surat tidak dalam status yang dapat ditolak.',
            ], 422);
        }

        $surat->update([
            'status'          => 'ditolak',
            'alasan_tolak'    => $request->input('alasan_tolak'),
            'tanggal_selesai' => Carbon::now(),
        ]);

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record(
            $actor,
            'tolak_surat',
            "Menolak surat {$surat->nomor_tiket} — {$surat->jenisShort()}: {$request->input('alasan_tolak')}",
            ['surat_id' => $surat->id]
        );

        return response()->json([
            'success' => true,
            'message' => "Surat {$surat->nomor_tiket} telah ditolak.",
        ]);
    }

    /**
     * Send back for revision (return to verifikasi status).
     */
    public function revisi(Request $request, SuratPermohonan $surat): JsonResponse
    {
        $request->validate([
            'catatan' => ['required', 'string', 'max:1000'],
        ], [
            'catatan.required' => 'Catatan revisi wajib diisi.',
        ]);

        if ($surat->status !== 'menunggu_tte') {
            return response()->json([
                'success' => false,
                'message' => 'Surat hanya bisa dikembalikan dari status Menunggu TTE.',
            ], 422);
        }

        $surat->update([
            'status'  => 'verifikasi',
            'catatan' => $request->input('catatan'),
        ]);

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record(
            $actor,
            'revisi_surat',
            "Mengembalikan surat {$surat->nomor_tiket} untuk revisi: {$request->input('catatan')}",
            ['surat_id' => $surat->id]
        );

        return response()->json([
            'success' => true,
            'message' => "Surat {$surat->nomor_tiket} dikembalikan ke operator untuk revisi.",
        ]);
    }

    /**
     * Advance from verifikasi → menunggu_tte (operator verifies).
     */
    public function verify(Request $request, SuratPermohonan $surat): JsonResponse
    {
        if ($surat->status !== 'verifikasi') {
            return response()->json([
                'success' => false,
                'message' => 'Surat tidak dalam status verifikasi.',
            ], 422);
        }

        $surat->update([
            'status' => 'menunggu_tte',
        ]);

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record(
            $actor,
            'verifikasi_surat',
            "Memverifikasi surat {$surat->nomor_tiket} — siap untuk TTE Kepala Desa.",
            ['surat_id' => $surat->id]
        );

        return response()->json([
            'success' => true,
            'message' => "Surat {$surat->nomor_tiket} telah diverifikasi. Menunggu TTE Kepala Desa.",
        ]);
    }
}
