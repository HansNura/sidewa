<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Penduduk;
use App\Models\SuratPermohonan;
use App\Models\User;
use App\Models\VillageSetting;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LayananSuratController extends Controller
{
    /**
     * Dashboard with KPI, pipeline, queue, chart, and timeline.
     */
    public function index(Request $request): View
    {
        $periode = $request->input('periode', 'bulan');
        $jenis   = $request->input('jenis');
        $status  = $request->input('status');

        // ─── KPI Statistics ──────────────────────────────────────
        $baseQuery = SuratPermohonan::filterPeriode($periode);

        $totalPermohonan = (clone $baseQuery)->count();
        $sedangDiproses  = (clone $baseQuery)->aktif()->count();
        $suratSelesai    = (clone $baseQuery)->selesai()->count();
        $overdueCount    = SuratPermohonan::overdue()->count();

        // Average processing time (for completed letters)
        $isSqlite = \DB::getDriverName() === 'sqlite';
        $avgExpression = $isSqlite
            ? 'AVG((julianday(tanggal_selesai) - julianday(tanggal_pengajuan)) * 24)'
            : 'AVG(TIMESTAMPDIFF(HOUR, tanggal_pengajuan, tanggal_selesai))';

        $avgJam = SuratPermohonan::filterPeriode($periode)
            ->selesai()
            ->whereNotNull('tanggal_selesai')
            ->selectRaw($avgExpression . ' as avg_hours')
            ->value('avg_hours');
        $avgJam = $avgJam ? round($avgJam, 1) : 0;

        // ─── Pipeline counts ─────────────────────────────────────
        $pipeline = [
            'pengajuan'    => SuratPermohonan::where('status', 'pengajuan')->count(),
            'verifikasi'   => SuratPermohonan::where('status', 'verifikasi')->count(),
            'menunggu_tte' => SuratPermohonan::where('status', 'menunggu_tte')->count(),
            'selesai'      => (clone $baseQuery)->selesai()->count(),
        ];

        // ─── Active Queue ────────────────────────────────────────
        $priorityOrder = "CASE prioritas WHEN 'tinggi' THEN 1 WHEN 'normal' THEN 2 ELSE 3 END";
        
        $antrian = SuratPermohonan::with('penduduk')
            ->aktif()
            ->filterJenis($jenis)
            ->filterStatus($status)
            ->orderByRaw($priorityOrder)
            ->orderBy('tanggal_pengajuan', 'asc')
            ->limit(10)
            ->get();

        // ─── Chart Data (letters by type this period) ────────────
        $chartData = [];
        foreach (SuratPermohonan::JENIS_SHORT as $key => $label) {
            $chartData[] = [
                'name'  => $label,
                'count' => SuratPermohonan::filterPeriode($periode)->where('jenis_surat', $key)->count(),
            ];
        }
        // Sort by count desc
        usort($chartData, fn ($a, $b) => $b['count'] - $a['count']);

        // ─── Recent Activity ─────────────────────────────────────
        $activities = SuratPermohonan::with('penduduk', 'operator')
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get();

        return view('pages.backoffice.layanan-surat.index', [
            'totalPermohonan' => $totalPermohonan,
            'sedangDiproses'  => $sedangDiproses,
            'suratSelesai'    => $suratSelesai,
            'overdueCount'    => $overdueCount,
            'avgJam'          => $avgJam,
            'pipeline'        => $pipeline,
            'antrian'         => $antrian,
            'chartData'       => $chartData,
            'activities'      => $activities,
            'periode'         => $periode,
            'selectedJenis'   => $jenis,
            'selectedStatus'  => $status,
            'pageTitle'       => 'Dashboard Layanan Surat',
        ]);
    }

    // ═══════════════════════════════════════════════════════════════
    //  WIZARD: Buat Surat Baru
    // ═══════════════════════════════════════════════════════════════

    /**
     * Show the multi-step wizard form.
     */
    public function create(): View
    {
        $village = VillageSetting::instance();

        // Build template list from model constants for the view
        $templates = collect(SuratPermohonan::JENIS_LABELS)->map(function ($label, $key) {
            $meta = self::templateMeta($key);
            return [
                'key'         => $key,
                'label'       => $label,
                'short'       => SuratPermohonan::JENIS_SHORT[$key] ?? $label,
                'icon'        => $meta['icon'],
                'iconBg'      => $meta['iconBg'],
                'iconColor'   => $meta['iconColor'],
                'description' => $meta['description'],
            ];
        })->values();

        return view('pages.backoffice.buat-surat.index', [
            'pageTitle' => 'Buat Surat Baru',
            'templates' => $templates,
            'village'   => $village,
        ]);
    }

    /**
     * Store a newly created surat from the wizard.
     * Returns JSON for Alpine.js consumption.
     */
    public function storeWizard(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'penduduk_id'    => ['required', 'exists:penduduk,id'],
            'jenis_surat'    => ['required', 'in:' . implode(',', array_keys(SuratPermohonan::JENIS_LABELS))],
            'keperluan'      => ['required', 'string', 'max:500'],
            'berlaku_hingga' => ['required', 'string', 'max:100'],
            'nama_usaha'     => ['nullable', 'required_if:jenis_surat,pengantar_usaha', 'string', 'max:255'],
            'keterangan_lain'=> ['nullable', 'string', 'max:500'],
            'submit_action'  => ['required', 'in:proses,draft,assign'],
        ], [
            'penduduk_id.required'    => 'Data pemohon wajib dipilih.',
            'jenis_surat.required'    => 'Template surat wajib dipilih.',
            'keperluan.required'      => 'Keperluan / tujuan surat wajib diisi.',
            'nama_usaha.required_if'  => 'Nama usaha wajib diisi untuk Surat Pengantar Usaha.',
        ]);

        $status = match ($validated['submit_action']) {
            'proses' => 'pengajuan',
            'draft'  => 'draft',
            'assign' => 'verifikasi',
        };

        $surat = SuratPermohonan::create([
            'nomor_tiket'       => SuratPermohonan::generateTiket(),
            'penduduk_id'       => $validated['penduduk_id'],
            'operator_id'       => $request->user()->id,
            'jenis_surat'       => $validated['jenis_surat'],
            'prioritas'         => 'normal',
            'status'            => $status,
            'keperluan'         => $validated['keperluan'],
            'berlaku_hingga'    => $validated['berlaku_hingga'],
            'nama_usaha'        => $validated['nama_usaha'] ?? null,
            'catatan'           => $validated['keterangan_lain'] ?? null,
            'tanggal_pengajuan' => now(),
        ]);

        /** @var User $actor */
        $actor = $request->user();
        $actionLabel = match ($validated['submit_action']) {
            'proses' => 'dikirim ke antrian TTE',
            'draft'  => 'disimpan sebagai draft',
            'assign' => 'diteruskan ke petugas',
        };
        ActivityLog::record(
            $actor,
            'buat_surat_wizard',
            "Membuat surat {$surat->jenisShort()} untuk penduduk ID {$surat->penduduk_id} — {$actionLabel} ({$surat->nomor_tiket})"
        );

        return response()->json([
            'success'      => true,
            'nomor_tiket'  => $surat->nomor_tiket,
            'action'       => $validated['submit_action'],
            'action_label' => $actionLabel,
        ]);
    }

    /**
     * List drafts belonging to the current operator (JSON).
     */
    public function drafts(Request $request): JsonResponse
    {
        $drafts = SuratPermohonan::with('penduduk:id,nama,nik')
            ->draft()
            ->where('operator_id', $request->user()->id)
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get()
            ->map(fn (SuratPermohonan $s) => [
                'id'           => $s->id,
                'jenis_surat'  => $s->jenis_surat,
                'jenis_short'  => $s->jenisShort(),
                'jenis_label'  => $s->jenisLabel(),
                'nama_pemohon' => $s->penduduk?->nama ?? '-',
                'updated_at'   => $s->updated_at->translatedFormat('d M Y, H:i'),
            ]);

        return response()->json($drafts);
    }

    /**
     * Load a draft's full data for resuming the wizard (JSON).
     */
    public function editWizard(SuratPermohonan $surat): JsonResponse
    {
        // Only allow loading drafts
        if ($surat->status !== 'draft') {
            return response()->json(['error' => 'Hanya draft yang dapat di-edit.'], 422);
        }

        $surat->load('penduduk');

        return response()->json([
            'id'              => $surat->id,
            'jenis_surat'     => $surat->jenis_surat,
            'jenis_label'     => $surat->jenisLabel(),
            'keperluan'       => $surat->keperluan,
            'berlaku_hingga'  => $surat->berlaku_hingga,
            'nama_usaha'      => $surat->nama_usaha,
            'catatan'         => $surat->catatan,
            'penduduk'        => $surat->penduduk ? [
                'id'        => $surat->penduduk->id,
                'nik'       => $surat->penduduk->nik,
                'nama'      => $surat->penduduk->nama,
                'ttl'       => $surat->penduduk->tempat_lahir . ', ' . $surat->penduduk->tanggal_lahir->translatedFormat('d F Y'),
                'pekerjaan' => $surat->penduduk->pekerjaan ?? '-',
                'alamat'    => $surat->penduduk->alamatLengkap(),
            ] : null,
        ]);
    }

    // ═══════════════════════════════════════════════════════════════
    //  END WIZARD
    // ═══════════════════════════════════════════════════════════════

    /**
     * Store new surat permohonan.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'penduduk_id' => ['required', 'exists:penduduk,id'],
            'jenis_surat' => ['required', 'in:sktm,domisili,pengantar_usaha,kematian,pengantar_nikah,pindah'],
            'prioritas'   => ['required', 'in:normal,tinggi'],
            'catatan'     => ['nullable', 'string', 'max:1000'],
        ], [
            'penduduk_id.required' => 'Pemohon wajib dipilih.',
            'jenis_surat.required' => 'Jenis surat wajib dipilih.',
        ]);

        $validated['nomor_tiket']       = SuratPermohonan::generateTiket();
        $validated['status']            = 'pengajuan';
        $validated['tanggal_pengajuan'] = now();

        $surat = SuratPermohonan::create($validated);

        $penduduk = Penduduk::find($validated['penduduk_id']);

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record($actor, 'buat_surat', "Membuat permohonan surat {$surat->jenisShort()} untuk {$penduduk->nama} ({$surat->nomor_tiket})");

        return redirect()
            ->route('admin.layanan-surat.index')
            ->with('success', "Permohonan {$surat->nomor_tiket} berhasil dibuat.");
    }

    /**
     * Update status workflow (advance or reject).
     */
    public function updateStatus(Request $request, SuratPermohonan $surat): RedirectResponse
    {
        $validated = $request->validate([
            'status'       => ['required', 'in:pengajuan,verifikasi,menunggu_tte,selesai,ditolak'],
            'alasan_tolak' => ['nullable', 'required_if:status,ditolak', 'string', 'max:500'],
        ]);

        $oldStatus = $surat->status;

        $surat->status = $validated['status'];

        if ($validated['status'] === 'selesai') {
            $surat->tanggal_selesai = now();
        }

        if ($validated['status'] === 'ditolak') {
            $surat->alasan_tolak = $validated['alasan_tolak'] ?? null;
        }

        // Assign operator on first processing
        if (!$surat->operator_id && in_array($validated['status'], ['verifikasi', 'menunggu_tte'])) {
            $surat->operator_id = $request->user()->id;
        }

        $surat->save();

        /** @var User $actor */
        $actor = $request->user();
        $statusLabel = $surat->statusBadge()['label'];
        ActivityLog::record($actor, 'update_status_surat', "Update status {$surat->nomor_tiket}: {$oldStatus} → {$statusLabel}");

        return redirect()
            ->route('admin.layanan-surat.index')
            ->with('success', "Status {$surat->nomor_tiket} diperbarui ke: {$statusLabel}");
    }

    /**
     * Delete surat.
     */
    public function destroy(Request $request, SuratPermohonan $surat): RedirectResponse
    {
        $tiket = $surat->nomor_tiket;
        $surat->delete();

        /** @var User $actor */
        $actor = $request->user();
        ActivityLog::record($actor, 'hapus_surat', "Menghapus permohonan surat: {$tiket}");

        return redirect()
            ->route('admin.layanan-surat.index')
            ->with('success', "Permohonan {$tiket} berhasil dihapus.");
    }

    /**
     * Search penduduk for form (JSON API).
     */
    public function searchPenduduk(Request $request): JsonResponse
    {
        $q = $request->input('q', '');
        if (strlen($q) < 2) return response()->json([]);

        $results = Penduduk::where('status', 'hidup')
            ->where(fn ($query) =>
                $query->where('nama', 'like', "%{$q}%")
                      ->orWhere('nik', 'like', "%{$q}%")
            )
            ->limit(10)
            ->get(['id', 'nik', 'nama', 'tempat_lahir', 'tanggal_lahir', 'pekerjaan', 'dusun', 'rt', 'rw']);

        // Format the response with computed fields
        $results->transform(function (Penduduk $p) {
            return [
                'id'        => $p->id,
                'nik'       => $p->nik,
                'nama'      => $p->nama,
                'ttl'       => $p->tempat_lahir . ', ' . $p->tanggal_lahir->translatedFormat('d F Y'),
                'pekerjaan' => $p->pekerjaan ?? '-',
                'alamat'    => $p->alamatLengkap(),
            ];
        });

        return response()->json($results);
    }

    // ─── Private Helpers ──────────────────────────────────────────

    /**
     * Template visual metadata (icon, color, description).
     */
    private static function templateMeta(string $key): array
    {
        return match ($key) {
            'sktm' => [
                'icon'        => 'fa-solid fa-hand-holding-dollar',
                'iconBg'      => 'bg-blue-100',
                'iconColor'   => 'text-blue-600',
                'description' => 'Untuk keperluan pendaftaran sekolah, bantuan kesehatan, dll.',
            ],
            'domisili' => [
                'icon'        => 'fa-solid fa-house-user',
                'iconBg'      => 'bg-amber-100',
                'iconColor'   => 'text-amber-600',
                'description' => 'Keterangan tempat tinggal warga di wilayah pemerintahan desa.',
            ],
            'pengantar_usaha' => [
                'icon'        => 'fa-solid fa-store',
                'iconBg'      => 'bg-purple-100',
                'iconColor'   => 'text-purple-600',
                'description' => 'Dokumen untuk pengajuan KUR atau legalitas UMKM dasar.',
            ],
            'kematian' => [
                'icon'        => 'fa-solid fa-cross',
                'iconBg'      => 'bg-gray-100',
                'iconColor'   => 'text-gray-600',
                'description' => 'Keterangan meninggal dunia untuk keperluan administrasi.',
            ],
            'pengantar_nikah' => [
                'icon'        => 'fa-solid fa-ring',
                'iconBg'      => 'bg-pink-100',
                'iconColor'   => 'text-pink-600',
                'description' => 'Pengantar pernikahan untuk mengurus ke KUA setempat.',
            ],
            'pindah' => [
                'icon'        => 'fa-solid fa-truck-moving',
                'iconBg'      => 'bg-teal-100',
                'iconColor'   => 'text-teal-600',
                'description' => 'Keterangan pindah domisili ke luar wilayah desa.',
            ],
            default => [
                'icon'        => 'fa-solid fa-file-lines',
                'iconBg'      => 'bg-gray-100',
                'iconColor'   => 'text-gray-600',
                'description' => 'Template surat desa.',
            ],
        };
    }
}
