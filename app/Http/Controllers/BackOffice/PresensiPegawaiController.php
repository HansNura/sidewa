<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\PresensiPegawai;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PresensiPegawaiController extends Controller
{
    /**
     * Tampilkan tabel monitoring presensi harian dengan statistik.
     */
    public function index(Request $request): View
    {
        $todayStr = $request->input('tanggal', Carbon::now()->format('Y-m-d'));
        $search   = $request->input('search');
        $status   = $request->input('status');

        $tanggal = Carbon::parse($todayStr);

        // Ambill semua pegawai
        $pegawaiQuery = User::whereIn('role', [
            User::ROLE_KADES,
            User::ROLE_PERANGKAT,
            User::ROLE_OPERATOR,
            User::ROLE_RESEPSIONIS
        ]);

        if ($search) {
            $pegawaiQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        // Ambil data user & presensi hari tsb
        $pegawaiList = $pegawaiQuery->with(['presensi' => function ($q) use ($tanggal) {
            $q->whereDate('tanggal', $tanggal->format('Y-m-d'));
        }])->get();

        // Map data agar tiap presensi 'alpha' dibuat secara in-memory jk belum ada record.
        // Juga handle filter logic status
        $data = [];
        $stats = [
            'hadir'     => 0,
            'terlambat' => 0,
            'izin'      => 0, // Gabung izin/sakit/dinas
            'alpha'     => 0,
        ];

        foreach ($pegawaiList as $user) {
            $presensi = $user->presensi->first();

            if (!$presensi) {
                // Mock presensi kosong (Belum Hadir / Alpha)
                $presensi = new PresensiPegawai([
                    'user_id' => $user->id,
                    'tanggal' => $tanggal->format('Y-m-d'),
                    'status'  => 'alpha',
                ]);
            }

            // Update stats
            if ($presensi->status === 'hadir') {
                $stats['hadir']++;
            } elseif ($presensi->status === 'terlambat') {
                $stats['terlambat']++;
            } elseif (in_array($presensi->status, ['izin', 'sakit', 'dinas'])) {
                $stats['izin']++;
            } else {
                $stats['alpha']++;
            }

            // Status Filter Validation
            if ($status && !in_array($presensi->status, (array)$status, true)) {
                // If filtering by "izin", matching izin, sakit, dinas
                if ($status === 'izin' && in_array($presensi->status, ['izin', 'sakit', 'dinas'])) {
                    // pass
                } else {
                    continue;
                }
            }

            $user->presensiHariIni = $presensi;
            $data[] = $user;
        }

        return view('pages.backoffice.presensi.monitoring', [
            'pageTitle' => 'Monitoring Presensi',
            'pegawai'   => collect($data),
            'stats'     => $stats,
            'tanggal'   => $todayStr,
            'search'    => $search,
            'selStatus' => $status,
        ]);
    }

    /**
     * Handle submit form Koreksi Presensi Manual dari Modal.
     */
    public function storeManual(Request $request)
    {
        $request->validate([
            'user_id'      => 'required|exists:users,id',
            'tanggal'      => 'required|date',
            'status'       => 'required|in:hadir,terlambat,izin,sakit,dinas,alpha',
            'waktu_masuk'  => 'nullable|date_format:H:i',
            'waktu_pulang' => 'nullable|date_format:H:i',
            'catatan'      => 'required|string|max:1000',
        ]);

        $status = $request->status;
        $isAbsen = in_array($status, ['izin', 'sakit', 'dinas', 'alpha']);

        $masuk = $isAbsen ? null : $request->waktu_masuk;
        $pulang = $isAbsen ? null : $request->waktu_pulang;

        $presensi = PresensiPegawai::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'tanggal' => $request->tanggal,
            ],
            [
                'status'         => $status,
                'waktu_masuk'    => $masuk,
                'waktu_pulang'   => $pulang,
                'metode_masuk'   => $masuk ? 'manual' : null,
                'metode_pulang'  => $pulang ? 'manual' : null,
                'catatan'        => $request->catatan,
                'dikoreksi_oleh' => $request->user()->id,
            ]
        );

        return back()->with('success', "Presensi manual untuk tanggal {$request->tanggal} berhasil disimpan.");
    }

    /**
     * Ambil data JSON untuk Drawer Detail Presensi Pegawai
     */
    public function showInfo(Request $request, User $user): JsonResponse
    {
        $tanggalStr = $request->input('tanggal', Carbon::now()->format('Y-m-d'));
        $tanggal = Carbon::parse($tanggalStr);

        // Presensi hari ini
        $presensiDaily = PresensiPegawai::where('user_id', $user->id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        // Setup mock jika belum absen
        if (!$presensiDaily) {
            $presensiDaily = new PresensiPegawai([
                'user_id' => $user->id,
                'tanggal' => $tanggal,
                'status'  => 'alpha',
            ]);
        }

        // Hitung ringkasan bulan ini
        $startOfMonth = $tanggal->copy()->startOfMonth();
        $endOfMonth = $tanggal->copy()->endOfMonth();

        $monthlyData = collect(
            PresensiPegawai::where('user_id', $user->id)
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->get()
        );

        $monthlyStats = [
            'hadir'     => $monthlyData->where('status', 'hadir')->count(),
            'terlambat' => $monthlyData->where('status', 'terlambat')->count(),
            'alphaIzin' => $monthlyData->whereIn('status', ['izin', 'sakit', 'dinas', 'alpha'])->count(),
        ];

        return response()->json([
            'pegawai' => [
                'id'       => $user->id,
                'nama'     => $user->name,
                'nip'      => $user->nip ?? '-',
                'jabatan'  => $user->jabatan ?? $user->roleName(),
                'avatar'   => $user->avatarUrl(),
                'isLive'   => ($presensiDaily->waktu_masuk && !$presensiDaily->waktu_pulang),
            ],
            'daily' => [
                'tanggal'       => $tanggal->translatedFormat('l, d F Y'),
                'status'        => $presensiDaily->status,
                'statusLabel'   => $presensiDaily->statusLabel(),
                'waktu_masuk'   => $presensiDaily->waktu_masuk ? Carbon::parse($presensiDaily->waktu_masuk)->format('H:i') . ' WIB' : null,
                'waktu_pulang'  => $presensiDaily->waktu_pulang ? Carbon::parse($presensiDaily->waktu_pulang)->format('H:i') . ' WIB' : null,
                'metode_masuk'  => $presensiDaily->metode_masuk,
                'metode_pulang' => $presensiDaily->metode_pulang,
                'catatan'       => $presensiDaily->catatan,
                'foto_masuk'    => $presensiDaily->foto_masuk_url,
            ],
            'monthly' => $monthlyStats
        ]);
    }
}
