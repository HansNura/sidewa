<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SuratPermohonan;
use App\Models\User;
use App\Models\VillageSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArsipSuratController extends Controller
{
    /**
     * Archive listing with search, filter, and pagination.
     */
    public function index(Request $request): View
    {
        $query = SuratPermohonan::with(['penduduk', 'operator'])
            ->arsip()
            ->searchArsip($request->input('search'))
            ->filterJenis($request->input('jenis'))
            ->filterStatus($request->input('status'));

        // Advanced filters
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal_pengajuan', '>=', $request->input('dari_tanggal'));
        }
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal_pengajuan', '<=', $request->input('sampai_tanggal'));
        }
        if ($request->filled('operator')) {
            $query->where('operator_id', $request->input('operator'));
        }

        $arsip = $query->orderByDesc('tanggal_selesai')
            ->orderByDesc('updated_at')
            ->paginate(15)
            ->withQueryString();

        // Stats for the page
        $totalArsip = SuratPermohonan::arsip()->count();
        $totalSelesai = SuratPermohonan::selesai()->count();
        $totalDitolak = SuratPermohonan::where('status', 'ditolak')->count();

        // Operator list for advanced filter
        $operators = User::whereIn('id', SuratPermohonan::arsip()->pluck('operator_id')->filter())
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('pages.backoffice.arsip-surat.index', [
            'pageTitle'     => 'Arsip Surat',
            'arsip'         => $arsip,
            'totalArsip'    => $totalArsip,
            'totalSelesai'  => $totalSelesai,
            'totalDitolak'  => $totalDitolak,
            'operators'     => $operators,
            'jenisLabels'   => SuratPermohonan::JENIS_LABELS,
            'search'        => $request->input('search'),
            'selJenis'      => $request->input('jenis'),
            'selStatus'     => $request->input('status'),
            'dariTanggal'   => $request->input('dari_tanggal'),
            'sampaiTanggal' => $request->input('sampai_tanggal'),
            'selOperator'   => $request->input('operator'),
        ]);
    }

    /**
     * Get detail data for the drawer (JSON).
     */
    public function show(SuratPermohonan $surat): JsonResponse
    {
        $surat->load(['penduduk', 'operator']);
        $village = VillageSetting::instance();

        $badge = $surat->statusBadge();

        // Build tracking timeline from activity logs
        $timeline = ActivityLog::where('description', 'like', "%{$surat->nomor_tiket}%")
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(fn (ActivityLog $log) => [
                'action'      => $log->action,
                'description' => $log->description,
                'by'          => $log->user?->name ?? 'Sistem',
                'at'          => $log->created_at->translatedFormat('d M Y, H:i') . ' WIB',
            ]);

        // If no logs found, build a minimal timeline from the surat data itself
        if ($timeline->isEmpty()) {
            $timeline = collect();
            if ($surat->tanggal_selesai) {
                $timeline->push([
                    'action'      => $surat->status === 'selesai' ? 'selesai' : 'ditolak',
                    'description' => $surat->status === 'selesai' ? 'Surat Selesai (Diarsipkan)' : 'Surat Ditolak / Dibatalkan',
                    'by'          => $surat->operator?->name ?? 'Sistem',
                    'at'          => $surat->tanggal_selesai->translatedFormat('d M Y, H:i') . ' WIB',
                ]);
            }
            $timeline->push([
                'action'      => 'pengajuan',
                'description' => 'Pengajuan Surat Dibuat',
                'by'          => $surat->operator?->name ?? 'Layanan Mandiri',
                'at'          => $surat->tanggal_pengajuan->translatedFormat('d M Y, H:i') . ' WIB',
            ]);
        }

        return response()->json([
            'id'              => $surat->id,
            'nomor_tiket'     => $surat->nomor_tiket,
            'jenis_surat'     => $surat->jenis_surat,
            'jenis_label'     => $surat->jenisLabel(),
            'jenis_short'     => $surat->jenisShort(),
            'status'          => $surat->status,
            'status_badge'    => $badge,
            'keperluan'       => $surat->keperluan,
            'berlaku_hingga'  => $surat->berlaku_hingga,
            'nama_usaha'      => $surat->nama_usaha,
            'catatan'         => $surat->catatan,
            'alasan_tolak'    => $surat->alasan_tolak,
            'tanggal_pengajuan' => $surat->tanggal_pengajuan->translatedFormat('d M Y'),
            'tanggal_selesai'   => $surat->tanggal_selesai?->translatedFormat('d M Y, H:i'),
            'penduduk' => $surat->penduduk ? [
                'id'        => $surat->penduduk->id,
                'nik'       => $surat->penduduk->nik,
                'nama'      => $surat->penduduk->nama,
                'ttl'       => $surat->penduduk->tempat_lahir . ', ' . $surat->penduduk->tanggal_lahir->translatedFormat('d F Y'),
                'pekerjaan' => $surat->penduduk->pekerjaan ?? '-',
                'alamat'    => $surat->penduduk->alamatLengkap(),
            ] : null,
            'operator_name' => $surat->operator?->name ?? '-',
            'timeline'      => $timeline,
            'village'       => [
                'nama_desa'     => $village->nama_desa,
                'kecamatan'     => $village->kecamatan,
                'kabupaten'     => $village->kabupaten,
                'alamat'        => $village->alamat,
                'kode_pos'      => $village->kode_pos,
                'nama_kades'    => $village->nama_kades,
                'jabatan_kades' => $village->jabatan_kades,
                'logo_url'      => $village->logoUrl(),
            ],
        ]);
    }

    /**
     * Bulk delete archived letters.
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ids'   => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:surat_permohonan,id'],
        ]);

        $count = SuratPermohonan::arsip()
            ->whereIn('id', $validated['ids'])
            ->delete();

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record($actor, 'bulk_hapus_arsip', "Menghapus {$count} arsip surat secara massal.");

        return response()->json([
            'success' => true,
            'message' => "{$count} arsip surat berhasil dihapus.",
        ]);
    }
}
