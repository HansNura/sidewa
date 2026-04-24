<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Penduduk;
use App\Models\PenerimaBansos;
use App\Models\ProgramBansos;
use App\Models\SuratPermohonan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk semua halaman Layanan Mandiri Warga
 * selain dashboard dan auth (profil, bansos, pengaduan, notifikasi).
 */
class WargaPageController extends Controller
{
    // ─── Helper: Penduduk & Notifications ───────────────────────

    private function getWarga()
    {
        return Auth::guard('warga')->user();
    }

    private function getPenduduk(): ?Penduduk
    {
        return Penduduk::where('nik', $this->getWarga()->nik)->first();
    }

    private function getNotifications(): \Illuminate\Support\Collection
    {
        $penduduk = $this->getPenduduk();

        if (! $penduduk) {
            return collect();
        }

        return SuratPermohonan::where('penduduk_id', $penduduk->id)
            ->whereIn('status', ['selesai', 'ditolak', 'menunggu_tte', 'verifikasi'])
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get()
            ->map(fn ($surat) => [
                'icon'    => $this->notifIcon($surat->status),
                'iconBg'  => $this->notifIconBg($surat->status),
                'message' => $this->notifMessage($surat),
                'time'    => $surat->updated_at->diffForHumans(),
                'isNew'   => $surat->updated_at->isToday() || $surat->updated_at->isYesterday(),
            ]);
    }

    // ─── Pages ──────────────────────────────────────────────────

    /**
     * GET /layanan/mandiri/profil
     */
    public function profil()
    {
        return view('pages.frontend.layanan-mandiri.profil', [
            'warga'         => $this->getWarga(),
            'notifications' => $this->getNotifications(),
        ]);
    }

    /**
     * GET /layanan/mandiri/bansos
     */
    public function bansos()
    {
        $penduduk = $this->getPenduduk();

        $penerimaBansos = collect();
        $semuaProgram   = collect();

        if ($penduduk) {
            $penerimaBansos = PenerimaBansos::with('program')
                ->where('penduduk_id', $penduduk->id)
                ->orderByDesc('created_at')
                ->get();
        }

        $semuaProgram = ProgramBansos::withCount('penerima')
            ->orderBy('nama')
            ->get();

        return view('pages.frontend.layanan-mandiri.bansos', [
            'warga'          => $this->getWarga(),
            'penerimaBansos' => $penerimaBansos,
            'semuaProgram'   => $semuaProgram,
            'notifications'  => $this->getNotifications(),
        ]);
    }

    /**
     * GET /layanan/mandiri/pengaduan
     */
    public function pengaduan()
    {
        return view('pages.frontend.layanan-mandiri.pengaduan', [
            'warga'         => $this->getWarga(),
            'notifications' => $this->getNotifications(),
        ]);
    }

    /**
     * POST /layanan/mandiri/pengaduan
     */
    public function storePengaduan(Request $request)
    {
        $validated = $request->validate([
            'kategori'  => ['required', 'string', 'in:infrastruktur,kebersihan,keamanan,administrasi,sosial,lainnya'],
            'prioritas' => ['required', 'in:rendah,sedang,tinggi'],
            'judul'     => ['required', 'string', 'max:200', 'min:5'],
            'lokasi'    => ['nullable', 'string', 'max:200'],
            'isi'       => ['required', 'string', 'min:20', 'max:2000'],
        ], [
            'judul.required' => 'Judul pengaduan wajib diisi.',
            'judul.min'      => 'Judul minimal 5 karakter.',
            'isi.required'   => 'Isi pengaduan wajib diisi.',
            'isi.min'        => 'Isi pengaduan minimal 20 karakter.',
            'kategori.required' => 'Kategori wajib dipilih.',
        ]);

        $warga = $this->getWarga();

        // Simpan pengaduan ke tabel buku_tamu (tujuan = Pengaduan Warga)
        \App\Models\BukuTamu::create([
            'nama_tamu'       => $warga->nama,
            'instansi'        => 'Warga - NIK: ' . $warga->nik,
            'tujuan_kategori' => 'Pengaduan Warga',
            'keperluan'       => "[" . strtoupper($validated['kategori']) . "][" . strtoupper($validated['prioritas']) . "] " .
                                 "{$validated['judul']}\n{$validated['isi']}" .
                                 ($validated['lokasi'] ? "\nLokasi: {$validated['lokasi']}" : ''),
            'metode_input'    => 'portal_warga',
            'status'          => 'menunggu',
            'waktu_masuk'     => now(),
        ]);

        return redirect()->route('warga.pengaduan')
            ->with('success', 'Pengaduan Anda telah berhasil dikirim. Perangkat desa akan menindaklanjuti dalam waktu dekat.');
    }

    /**
     * GET /layanan/mandiri/notifikasi
     */
    public function notifikasi()
    {
        return view('pages.frontend.layanan-mandiri.notifikasi', [
            'warga'         => $this->getWarga(),
            'notifications' => $this->getNotifications(),
        ]);
    }

    // ─── Notification Helpers ────────────────────────────────────

    private function notifIcon(string $status): string
    {
        return match ($status) {
            'selesai'      => 'fa-solid fa-check-double',
            'ditolak'      => 'fa-solid fa-triangle-exclamation',
            'menunggu_tte' => 'fa-solid fa-signature',
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
