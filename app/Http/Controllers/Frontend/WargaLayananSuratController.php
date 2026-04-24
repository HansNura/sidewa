<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\SuratPermohonan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WargaLayananSuratController extends Controller
{
    // ─── Konstanta ──────────────────────────────────────────────

    /**
     * Opsi jenis surat yang tersedia untuk warga.
     */
    private function getJenisSuratOptions(): array
    {
        return [
            'sktm'            => 'Surat Keterangan Tidak Mampu (SKTM)',
            'domisili'        => 'Surat Keterangan Domisili',
            'pengantar_usaha' => 'Surat Pengantar Usaha',
            'kematian'        => 'Surat Keterangan Kematian',
            'pengantar_nikah' => 'Surat Pengantar Nikah',
            'pindah'          => 'Surat Keterangan Pindah',
        ];
    }

    /**
     * Cari data penduduk berdasarkan NIK warga yang login.
     */
    private function getPenduduk(): ?Penduduk
    {
        $warga = Auth::guard('warga')->user();
        return Penduduk::where('nik', $warga->nik)->first();
    }

    // ─── Pages ──────────────────────────────────────────────────

    /**
     * Form ajukan permohonan surat baru.
     */
    public function ajukan()
    {
        return view('pages.frontend.layanan-mandiri.ajukan-surat', [
            'jenisSuratOptions' => $this->getJenisSuratOptions(),
            'notifications'     => $this->getNotifications(),
        ]);
    }

    /**
     * Proses pengajuan surat dari form.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'jenis_surat' => ['required', 'string', 'in:' . implode(',', array_keys($this->getJenisSuratOptions()))],
            'keperluan'   => ['required', 'string', 'min:10', 'max:500'],
            'catatan'     => ['nullable', 'string', 'max:300'],
            'prioritas'   => ['required', 'in:normal,tinggi'],
            'nama_usaha'  => ['nullable', 'string', 'max:150', 'required_if:jenis_surat,pengantar_usaha'],
            'berlaku_hingga' => ['nullable', 'date', 'after:today'],
            // GAP-08: File upload validation
            'lampiran'    => ['nullable', 'array', 'max:5'],
            'lampiran.*'  => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ], [
            'jenis_surat.required'       => 'Jenis surat wajib dipilih.',
            'jenis_surat.in'             => 'Jenis surat tidak valid.',
            'keperluan.required'         => 'Keperluan wajib diisi.',
            'keperluan.min'              => 'Keperluan minimal 10 karakter.',
            'nama_usaha.required_if'     => 'Nama usaha wajib diisi untuk Surat Pengantar Usaha.',
            'berlaku_hingga.after'       => 'Tanggal berlaku harus setelah hari ini.',
            'lampiran.max'               => 'Maksimal 5 file lampiran.',
            'lampiran.*.max'             => 'Ukuran file maksimal 2MB.',
            'lampiran.*.mimes'           => 'Format file harus PDF, JPG, atau PNG.',
        ]);

        // Cek data penduduk
        $penduduk = $this->getPenduduk();

        if (! $penduduk) {
            return back()->withErrors([
                'jenis_surat' => 'Data kependudukan Anda tidak ditemukan. Silakan hubungi petugas desa.',
            ])->withInput();
        }

        // Buat nomor tiket otomatis
        $nomorTiket = SuratPermohonan::generateTiket();

        // Simpan permohonan
        $surat = SuratPermohonan::create([
            'nomor_tiket'       => $nomorTiket,
            'penduduk_id'       => $penduduk->id,
            'jenis_surat'       => $validated['jenis_surat'],
            'keperluan'         => $validated['keperluan'],
            'catatan'           => $validated['catatan'] ?? null,
            'prioritas'         => $validated['prioritas'],
            'nama_usaha'        => $validated['nama_usaha'] ?? null,
            'berlaku_hingga'    => $validated['berlaku_hingga'] ?? null,
            'status'            => 'pengajuan',
            'tanggal_pengajuan' => now(),
        ]);

        // GAP-08: Handle file uploads
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $path = $file->store('lampiran-surat/' . $surat->id, 'public');

                \App\Models\LampiranSurat::create([
                    'surat_permohonan_id' => $surat->id,
                    'nama_file'           => $file->getClientOriginalName(),
                    'path'                => $path,
                    'tipe'                => 'pendukung',
                    'ukuran'              => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('warga.surat.riwayat')
            ->with('success', "Permohonan surat berhasil dikirim dengan nomor tiket {$nomorTiket}. Petugas desa akan memproses dalam 1-2 hari kerja.");
    }

    /**
     * Riwayat permohonan surat milik warga ini.
     */
    public function riwayat(Request $request)
    {
        $penduduk = $this->getPenduduk();

        $query = SuratPermohonan::query()
            ->where('penduduk_id', $penduduk?->id ?? 0)
            ->orderByDesc('created_at');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter jenis
        if ($request->filled('jenis')) {
            $query->where('jenis_surat', $request->jenis);
        }

        $suratPermohonan = $query->paginate(10)->withQueryString();

        // Hitung stats
        $statsCount = [
            'aktif'   => SuratPermohonan::where('penduduk_id', $penduduk?->id ?? 0)->aktif()->count(),
            'selesai' => SuratPermohonan::where('penduduk_id', $penduduk?->id ?? 0)->selesai()->count(),
        ];

        return view('pages.frontend.layanan-mandiri.riwayat-surat', [
            'suratPermohonan'   => $suratPermohonan,
            'statsCount'        => $statsCount,
            'jenisSuratOptions' => $this->getJenisSuratOptions(),
            'notifications'     => $this->getNotifications(),
        ]);
    }

    /**
     * Detail permohonan surat (timeline + dokumen).
     */
    public function detail(SuratPermohonan $surat)
    {
        $penduduk = $this->getPenduduk();

        // Authorization: warga hanya bisa lihat surat milik sendiri
        if (! $penduduk || $surat->penduduk_id !== $penduduk->id) {
            abort(403, 'Anda tidak memiliki akses ke permohonan ini.');
        }

        return view('pages.frontend.layanan-mandiri.detail-surat', [
            'surat'         => $surat->load('penduduk'),
            'notifications' => $this->getNotifications(),
        ]);
    }

    /**
     * Download dokumen PDF hasil verifikasi & TTE.
     */
    public function download(SuratPermohonan $surat)
    {
        $penduduk = $this->getPenduduk();

        // Authorization
        if (! $penduduk || $surat->penduduk_id !== $penduduk->id) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        // Cek status dan keberadaan path
        if ($surat->status !== 'selesai' || ! $surat->pdf_path) {
            return back()->with('error', 'Dokumen belum tersedia atau belum ditandatangani.');
        }

        // Tentukan path lengkap (asumsi disimpan di disk 'public')
        $filePath = storage_path('app/public/' . $surat->pdf_path);

        if (! file_exists($filePath)) {
            // Coba di disk local (private) jika tidak ada di public
            $filePath = storage_path('app/' . $surat->pdf_path);
        }

        if (! file_exists($filePath)) {
            return back()->with('error', 'File fisik dokumen tidak ditemukan di server. Silakan hubungi admin.');
        }

        $fileName = str_replace('#', '', $surat->nomor_tiket) . '_' . str_replace(' ', '_', $surat->jenisShort()) . '.pdf';

        return response()->download($filePath, $fileName);
    }

    // ─── Helper ─────────────────────────────────────────────────

    /**
     * Ambil notifikasi warga (digunakan oleh sidebar/topbar).
     */
    private function getNotifications(): \Illuminate\Support\Collection
    {
        $penduduk = $this->getPenduduk();

        if (! $penduduk) {
            return collect();
        }

        $recentUpdates = SuratPermohonan::where('penduduk_id', $penduduk->id)
            ->whereIn('status', ['selesai', 'ditolak', 'menunggu_tte', 'verifikasi'])
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get();

        return $recentUpdates->map(function ($surat) {
            return [
                'icon'    => $this->notifIcon($surat->status),
                'iconBg'  => $this->notifIconBg($surat->status),
                'message' => $this->notifMessage($surat),
                'time'    => $surat->updated_at->diffForHumans(),
                'isNew'   => $surat->updated_at->isToday() || $surat->updated_at->isYesterday(),
            ];
        });
    }

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
