<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Penduduk;
use App\Models\SuratPermohonan;
use Illuminate\Support\Facades\Auth;

class WargaDashboardController extends Controller
{
    /**
     * Display the warga self-service dashboard.
     */
    public function index()
    {
        $warga = Auth::guard('warga')->user();

        // ── Cari data penduduk yang berelasi lewat NIK ──
        $penduduk = Penduduk::where('nik', $warga->nik)->first();

        // ── Permohonan surat milik warga ini ──
        $suratPermohonan = collect();
        if ($penduduk) {
            $suratPermohonan = SuratPermohonan::where('penduduk_id', $penduduk->id)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();
        }

        // ── Hitung notifikasi (surat yang statusnya berubah = belum "dibaca") ──
        $notifications = collect();
        if ($penduduk) {
            // Surat yang baru selesai / ditolak / menunggu TTE (update terbaru)
            $recentUpdates = SuratPermohonan::where('penduduk_id', $penduduk->id)
                ->whereIn('status', ['selesai', 'ditolak', 'menunggu_tte', 'verifikasi'])
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get();

            foreach ($recentUpdates as $surat) {
                $notifications->push([
                    'icon'    => $this->notifIcon($surat->status),
                    'iconBg'  => $this->notifIconBg($surat->status),
                    'message' => $this->notifMessage($surat),
                    'time'    => $surat->updated_at->diffForHumans(),
                    'isNew'   => $surat->updated_at->isToday() || $surat->updated_at->isYesterday(),
                ]);
            }
        }

        // ── Berita terbaru untuk sidebar Info Desa ──
        $berita = Article::where('status', 'published')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        // ── Layanan Cepat (Quick Actions) ──
        $quickActions = [
            [
                'title'       => 'Ajukan Surat',
                'description' => 'Buat permohonan baru',
                'icon'        => 'fa-solid fa-file-signature',
                'color'       => 'blue',
                'route'       => route('warga.surat.ajukan'),
            ],
            [
                'title'       => 'Riwayat Surat',
                'description' => 'Cek status permohonan',
                'icon'        => 'fa-solid fa-clock-rotate-left',
                'color'       => 'emerald',
                'route'       => route('warga.surat.riwayat'),
            ],
            [
                'title'       => 'Cek Bansos',
                'description' => 'Informasi bantuan sosial',
                'icon'        => 'fa-solid fa-hand-holding-heart',
                'color'       => 'amber',
                'route'       => route('warga.bansos'),
            ],
            [
                'title'       => 'Buat Pengaduan',
                'description' => 'Sampaikan aspirasi Anda',
                'icon'        => 'fa-solid fa-bullhorn',
                'color'       => 'red',
                'route'       => route('warga.pengaduan'),
            ],
        ];

        return view('pages.frontend.layanan-mandiri.dashboard', [
            'warga'           => $warga,
            'penduduk'        => $penduduk,
            'suratPermohonan' => $suratPermohonan,
            'notifications'   => $notifications,
            'berita'          => $berita,
            'quickActions'    => $quickActions,
        ]);
    }

    // ── Notification Helpers ──

    private function notifIcon(string $status): string
    {
        return match ($status) {
            'selesai'      => 'fa-solid fa-signature',
            'ditolak'      => 'fa-solid fa-triangle-exclamation',
            'menunggu_tte' => 'fa-solid fa-file-circle-check',
            default        => 'fa-solid fa-bell',
        };
    }

    private function notifIconBg(string $status): string
    {
        return match ($status) {
            'selesai'      => 'bg-green-100 text-green-600',
            'ditolak'      => 'bg-red-100 text-red-600',
            'menunggu_tte' => 'bg-purple-100 text-purple-600',
            default        => 'bg-blue-100 text-blue-600',
        };
    }

    private function notifMessage(SuratPermohonan $surat): string
    {
        return match ($surat->status) {
            'selesai'      => "Dokumen {$surat->jenisShort()} Anda telah selesai & siap cetak.",
            'ditolak'      => "Permohonan {$surat->jenisShort()} ditolak. " . ($surat->alasan_tolak ?? ''),
            'menunggu_tte' => "Dokumen {$surat->jenisShort()} menunggu tanda tangan Kepala Desa.",
            'verifikasi'   => "Berkas {$surat->jenisShort()} sedang diverifikasi petugas.",
            default        => "Status surat {$surat->jenisShort()} diperbarui.",
        };
    }
}
