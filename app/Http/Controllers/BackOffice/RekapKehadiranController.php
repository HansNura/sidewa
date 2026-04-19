<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\PresensiPegawai;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\SimpleExcel\SimpleExcelWriter;

class RekapKehadiranController extends Controller
{
    /**
     * Tampilkan halaman utama Rekap Kehadiran
     */
    public function index(Request $request)
    {
        // Parse active period
        $periodInput = $request->input('period', Carbon::now()->format('Y-m'));
        $activePeriod = Carbon::createFromFormat('Y-m', $periodInput);
        
        $startOfMonth = $activePeriod->copy()->startOfMonth();
        $endOfMonth = $activePeriod->copy()->endOfMonth();

        // Target Date Range: start to either the end of month OR today if it's the current month
        $workingDaysPassed = 0;
        $maxDate = $activePeriod->format('Y-m') === Carbon::now()->format('Y-m') 
                   ? Carbon::now() 
                   : $endOfMonth;

        // Count working days passed in this period (excluding weekends)
        $temp = $startOfMonth->copy();
        while ($temp <= $maxDate) {
            if (!$temp->isWeekend()) {
                $workingDaysPassed++;
            }
            $temp->addDay();
        }
        
        // Prevent division by zero if it's the 1st of the month and it's a weekend
        if ($workingDaysPassed === 0) {
            $workingDaysPassed = 1;
        }

        // Get All Presensi Data for this Period
        $periodPresensi = PresensiPegawai::whereBetween('tanggal', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->get();

        // 1. Calculate Global Summary
        $stats = [
            'hadir'     => $periodPresensi->where('status', 'hadir')->count(),
            'terlambat' => $periodPresensi->where('status', 'terlambat')->count(),
            'izin'      => $periodPresensi->whereIn('status', ['izin', 'sakit', 'dinas'])->count(),
            'alpha'     => $periodPresensi->where('status', 'alpha')->count(),
        ];

        // 2. Trend Chart Data (Group by date)
        $trendData = [];
        $temp = $startOfMonth->copy();
        while ($temp <= $maxDate) {
            if (!$temp->isWeekend()) {
                $dateStr = $temp->format('Y-m-d');
                $dailyData = $periodPresensi->where('tanggal', $dateStr);
                
                $trendData['dates'][] = $temp->format('d M');
                $trendData['hadir'][] = $dailyData->where('status', 'hadir')->count();
                $trendData['terlambat'][] = $dailyData->where('status', 'terlambat')->count();
            }
            $temp->addDay();
        }

        // 3. Employee Recap Data
        $pegawaiQuery = User::whereIn('role', [
            User::ROLE_KADES,
            User::ROLE_PERANGKAT,
            User::ROLE_OPERATOR,
            User::ROLE_RESEPSIONIS
        ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $pegawaiQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        // Eager load presensi for this month to avoid N+1
        $pegawais = $pegawaiQuery->with(['presensi' => function ($q) use ($startOfMonth, $endOfMonth) {
            $q->whereBetween('tanggal', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')]);
        }])->get();

        // Map statistics for Table
        $pegawais->transform(function ($user) use ($workingDaysPassed) {
            $recap = [
                'hadir'     => $user->presensi->where('status', 'hadir')->count(),
                'terlambat' => $user->presensi->where('status', 'terlambat')->count(),
                'izin'      => $user->presensi->whereIn('status', ['izin', 'sakit', 'dinas'])->count(),
                'alpha'     => $user->presensi->where('status', 'alpha')->count(),
            ];
            
            // Calculate Performance: (Hadir + Terlambat) / WorkingDays * 100
            $totalPresent = $recap['hadir'] + $recap['terlambat'];
            $performance = min(100, round(($totalPresent / $workingDaysPassed) * 100, 1));
            
            $user->recap = $recap;
            $user->performance = $performance;
            return $user;
        });

        // Generate options for month selector (last 6 months)
        $monthOptions = [];
        for ($i = 0; $i < 6; $i++) {
            $m = Carbon::now()->subMonths($i);
            $monthOptions[$m->format('Y-m')] = $m->translatedFormat('F Y');
        }

        return view('pages.backoffice.presensi.rekap.index', [
            'pageTitle'    => 'Rekap Kehadiran',
            'activePeriod' => $activePeriod->translatedFormat('F Y'),
            'periodInput'  => $periodInput,
            'monthOptions' => $monthOptions,
            'stats'        => $stats,
            'pegawais'     => $pegawais,
            'trendData'    => $trendData,
            'workingDays'  => $workingDaysPassed
        ]);
    }

    /**
     * Get Detailed Log per Employee for Drawer (JSON API)
     */
    public function showEmployee(Request $request, User $user): JsonResponse
    {
        $periodInput = $request->input('period', Carbon::now()->format('Y-m'));
        $activePeriod = Carbon::createFromFormat('Y-m', $periodInput);
        
        $startOfMonth = $activePeriod->copy()->startOfMonth();
        $endOfMonth = $activePeriod->copy()->endOfMonth();
        
        $presensiQuery = PresensiPegawai::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->orderBy('tanggal', 'desc')
            ->get();
            
        // Calculate daily working days passed
        $workingDaysPassed = 0;
        $maxDate = $activePeriod->format('Y-m') === Carbon::now()->format('Y-m') 
                   ? Carbon::now() 
                   : $endOfMonth;

        $temp = $startOfMonth->copy();
        while ($temp <= $maxDate) {
            if (!$temp->isWeekend()) {
                $workingDaysPassed++;
            }
            $temp->addDay();
        }
        if ($workingDaysPassed === 0) $workingDaysPassed = 1;

        $hadir = $presensiQuery->where('status', 'hadir')->count();
        $terlambat = $presensiQuery->where('status', 'terlambat')->count();
        
        $totalPresent = $hadir + $terlambat;
        $performance = min(100, round(($totalPresent / $workingDaysPassed) * 100, 1));

        // Create Chart Data (Weekly split M1, M2...)
        // Simplified for UI
        $chartData = [
            'categories' => ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4', 'Minggu 5'],
            'data' => [0, 0, 0, 0, 0]
        ];
        
        foreach ($presensiQuery as $p) {
            if (in_array($p->status, ['hadir', 'terlambat'])) {
                $dateObj = Carbon::parse($p->tanggal);
                $weekIndex = min(4, ceil($dateObj->day / 7) - 1);
                $chartData['data'][$weekIndex]++;
            }
        }

        // Map logs for table view
        $logs = $presensiQuery->map(function ($p) {
            return [
                'tanggal'      => Carbon::parse($p->tanggal)->translatedFormat('d M Y'),
                'waktu_masuk'  => $p->waktu_masuk ? substr($p->waktu_masuk, 0, 5) : '-',
                'waktu_pulang' => $p->waktu_pulang ? substr($p->waktu_pulang, 0, 5) : '-',
                'status'       => $p->status,
                'statusLabel'  => $p->statusLabel(),
                'catatan'      => $p->catatan ?? '-'
            ];
        })->values();

        return response()->json([
            'pegawai' => [
                'nama'    => $user->name,
                'nip'     => $user->nip ?? 'Belum ada NIP',
                'jabatan' => $user->jabatan ?? $user->roleName(),
                'avatar'  => $user->avatarUrl()
            ],
            'period' => $activePeriod->translatedFormat('F Y'),
            'stats' => [
                'performance'   => $performance,
                'poinTerlambat' => $terlambat * -1, // Simple metric: -1 point per late
                'avgCheckin'    => '07:45' // Idealnya dikalkulasi average timestamp
            ],
            'chart' => $chartData,
            'logs'  => $logs
        ]);
    }

    /**
     * Export Laporan
     */
    public function export(Request $request)
    {
        $type = $request->input('type'); // pdf or excel
        $periodInput = $request->input('period', Carbon::now()->format('Y-m'));
        $activePeriod = Carbon::createFromFormat('Y-m', $periodInput);
        
        $startOfMonth = $activePeriod->copy()->startOfMonth();
        $endOfMonth = $activePeriod->copy()->endOfMonth();

        // Get data
        $pegawais = User::whereIn('role', [User::ROLE_KADES, User::ROLE_PERANGKAT, User::ROLE_OPERATOR, User::ROLE_RESEPSIONIS])
            ->with(['presensi' => function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('tanggal', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')]);
            }])->get();

        $dataForExport = [];
        foreach ($pegawais as $index => $user) {
            $hadir = $user->presensi->where('status', 'hadir')->count();
            $terlambat = $user->presensi->where('status', 'terlambat')->count();
            $izin = $user->presensi->whereIn('status', ['izin', 'sakit', 'dinas'])->count();
            $alpha = $user->presensi->where('status', 'alpha')->count();
            
            $dataForExport[] = [
                'No' => $index + 1,
                'Nama Pegawai' => $user->name,
                'NIP' => $user->nip ?? '-',
                'Jabatan' => $user->jabatan ?? $user->roleName(),
                'Hadir' => $hadir,
                'Terlambat' => $terlambat,
                'Izin/Sakit' => $izin,
                'Alpha' => $alpha,
                'Total Kehadiran' => $hadir + $terlambat
            ];
        }

        $filename = 'Rekap_Kehadiran_' . $activePeriod->format('Y_m');

        if ($type === 'excel') {
            return SimpleExcelWriter::streamDownload("{$filename}.xlsx")
                ->addRows($dataForExport)
                ->toBrowser();
        }

        // For PDF
        // Create a simple compact view inline or use a real view
        $pdf = Pdf::loadView('pages.backoffice.presensi.rekap.pdf', [
            'data' => $dataForExport,
            'period' => $activePeriod->translatedFormat('F Y')
        ])->setPaper('a4', 'landscape');

        return $pdf->download("{$filename}.pdf");
    }
}
