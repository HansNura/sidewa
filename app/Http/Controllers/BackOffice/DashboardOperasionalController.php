<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BukuTamu;
use App\Models\PresensiPegawai;
use App\Models\SuratPermohonan;
use App\Models\User;
use Carbon\Carbon;

class DashboardOperasionalController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        /** @var \App\Models\User $user */
        $user  = auth()->user();

        // ══════════════════════════════════════════════
        // QUICK STATS (Hari Ini)
        // ══════════════════════════════════════════════

        $suratMasukHariIni = SuratPermohonan::whereDate('tanggal_pengajuan', $today)->count();
        $suratDiproses     = SuratPermohonan::aktif()->count();
        $tamuHariIni       = BukuTamu::whereDate('waktu_masuk', $today)->count();

        // Presensi: hadir vs total pegawai
        $totalPegawai  = User::where('is_active', true)
            ->whereIn('role', ['administrator', 'operator', 'kades', 'perangkat', 'resepsionis'])
            ->count();
        $hadirHariIni  = PresensiPegawai::where('tanggal', $today)
            ->whereIn('status', ['hadir', 'terlambat'])
            ->count();

        // ══════════════════════════════════════════════
        // STATUS LAYANAN KESELURUHAN
        // ══════════════════════════════════════════════

        $statusPending  = SuratPermohonan::where('status', 'pengajuan')->count();
        $statusDiproses = SuratPermohonan::whereIn('status', ['verifikasi', 'menunggu_tte'])->count();
        $statusSelesai  = SuratPermohonan::where('status', 'selesai')
            ->whereMonth('tanggal_selesai', $today->month)
            ->whereYear('tanggal_selesai', $today->year)
            ->count();

        // Bottleneck: surat overdue (melebihi SLA)
        $overdueCount = SuratPermohonan::overdue()->count();
        $overdueSurat = SuratPermohonan::overdue()
            ->with('penduduk')
            ->orderBy('tanggal_pengajuan')
            ->limit(3)
            ->get();

        // ══════════════════════════════════════════════
        // ANTREAN PEKERJAAN (Task Queue)
        // ══════════════════════════════════════════════

        $antreanSurat = SuratPermohonan::aktif()
            ->with(['penduduk', 'operator'])
            ->orderByRaw("CASE WHEN prioritas = 'tinggi' THEN 0 ELSE 1 END")
            ->orderBy('tanggal_pengajuan')
            ->limit(10)
            ->get();

        // ══════════════════════════════════════════════
        // ALERT OPERASIONAL
        // ══════════════════════════════════════════════

        $alerts = collect();

        if ($overdueCount > 0) {
            $alerts->push([
                'type'    => 'error',
                'title'   => 'SLA Terlampaui',
                'message' => "{$overdueCount} surat melebihi batas waktu penyelesaian 24 jam.",
                'time'    => now()->format('H:i'),
            ]);
        }

        // Cek presensi belum lengkap (setelah jam 09:00)
        $absentCount = $totalPegawai - $hadirHariIni;
        if ($absentCount > 0 && now()->hour >= 9) {
            $alerts->push([
                'type'    => 'warning',
                'title'   => 'Presensi Belum Lengkap',
                'message' => "{$absentCount} pegawai belum melakukan presensi hari ini.",
                'time'    => now()->format('H:i'),
            ]);
        }

        // ══════════════════════════════════════════════
        // LOG AKTIVITAS TERBARU
        // ══════════════════════════════════════════════

        $activityLogs = ActivityLog::with('user')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(fn($log) => [
                'time'        => $log->created_at->format('H:i'),
                'timeLabel'   => $log->created_at->isToday()
                    ? 'Hari ini, ' . $log->created_at->format('H:i')
                    : $log->created_at->translatedFormat('d M Y, H:i'),
                'title'       => $log->action,
                'description' => $log->description,
                'userName'    => $log->user?->name ?? 'Sistem',
                'isRecent'    => $log->created_at->diffInMinutes(now()) < 30,
            ]);

        // ══════════════════════════════════════════════
        // PRESENSI PEGAWAI SNAPSHOT
        // ══════════════════════════════════════════════

        $presensiHariIni = PresensiPegawai::with('pegawai')
            ->where('tanggal', $today)
            ->orderBy('waktu_masuk')
            ->get();

        // Pegawai yang belum presensi (alpha)
        $pegawaiHadirIds = $presensiHariIni->pluck('user_id')->toArray();
        $pegawaiAlpha = User::where('is_active', true)
            ->whereIn('role', ['administrator', 'operator', 'kades', 'perangkat', 'resepsionis'])
            ->whereNotIn('id', $pegawaiHadirIds)
            ->get();

        // ══════════════════════════════════════════════
        // BUKU TAMU HARI INI
        // ══════════════════════════════════════════════

        $tamuList = BukuTamu::whereDate('waktu_masuk', $today)
            ->orderByDesc('waktu_masuk')
            ->limit(5)
            ->get();

        // ══════════════════════════════════════════════
        // QUICK ACTIONS
        // ══════════════════════════════════════════════

        $quickActions = [
            [
                'title'       => 'Buat Surat',
                'description' => 'Layanan administrasi',
                'icon'        => 'fa-solid fa-envelope-open-text',
                'color'       => 'green',
                'route'       => route('admin.layanan-surat.create'),
            ],
            [
                'title'       => 'Input Penduduk',
                'description' => 'Data master demografi',
                'icon'        => 'fa-solid fa-users',
                'color'       => 'blue',
                'route'       => route('admin.penduduk.index'),
            ],
            [
                'title'       => 'Tambah UMKM',
                'description' => 'Katalog lapak desa',
                'icon'        => 'fa-solid fa-store',
                'color'       => 'purple',
                'route'       => route('admin.umkm.index'),
            ],
            [
                'title'       => 'Upload Berita',
                'description' => 'Konten publikasi',
                'icon'        => 'fa-solid fa-newspaper',
                'color'       => 'amber',
                'route'       => route('admin.artikel.index'),
            ],
        ];

        return view('pages.backoffice.dashboard-operasional.index', compact(
            'user',
            'suratMasukHariIni',
            'suratDiproses',
            'tamuHariIni',
            'totalPegawai',
            'hadirHariIni',
            'statusPending',
            'statusDiproses',
            'statusSelesai',
            'overdueCount',
            'overdueSurat',
            'antreanSurat',
            'alerts',
            'activityLogs',
            'presensiHariIni',
            'pegawaiAlpha',
            'tamuList',
            'quickActions',
        ));
    }
}
