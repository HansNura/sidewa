<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PresensiPegawai;
use App\Models\User;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        // 1. Process Filters
        $filterBulan = $request->input('bulan', date('Y-m')); // '2026-04'
        $search = $request->input('search');
        $jabatan = $request->input('jabatan'); // kasi, kadus, staf

        $year = substr($filterBulan, 0, 4);
        $month = substr($filterBulan, 5, 2);

        // Calculate active days in the month excluding weekends (simplistic calculation)
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $totalHariKerja = 0;
        $curr = $startOfMonth->copy();
        while($curr <= $endOfMonth) {
            if (!$curr->isWeekend()) {
                $totalHariKerja++;
            }
            $curr->addDay();
        }

        // Base Query User
        $usersQuery = User::whereNotNull('nip');
        if ($search) {
            $usersQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }
        if ($jabatan) {
            $usersQuery->where('jabatan', 'like', "%{$jabatan}%");
        }
        $pegawais = $usersQuery->get()->keyBy('id');

        $userIds = $pegawais->pluck('id');

        // Presensi Query Set
        $presensiQuery = PresensiPegawai::whereIn('user_id', $userIds)
                                        ->whereYear('tanggal', $year)
                                        ->whereMonth('tanggal', $month);

        // 2. KPI Cards Aggregation
        $kpiHadir = (clone $presensiQuery)->where('status', 'hadir')->count();
        $kpiTerlambat = (clone $presensiQuery)->where('status', 'terlambat')->count();
        $kpiIzinSakit = (clone $presensiQuery)->whereIn('status', ['izin', 'sakit', 'cuti', 'dinas'])->count();
        $kpiAlfa = (clone $presensiQuery)->where('status', 'alpha')->count();

        // 3. Tab 1: Rekapitulasi Presensi Data
        $rekapData = [];
        $presensiGrouped = (clone $presensiQuery)->get()->groupBy('user_id');

        foreach ($pegawais as $user) {
            $userLogs = $presensiGrouped->get($user->id, collect());
            
            $hadir = $userLogs->where('status', 'hadir')->count();
            $terlambat = $userLogs->where('status', 'terlambat')->count();
            $izinSakit = $userLogs->whereIn('status', ['izin', 'sakit', 'cuti', 'dinas'])->count();
            $alfa = $userLogs->where('status', 'alpha')->count();
            
            $totalHadir = $hadir + $terlambat; // Hadir is both on time or late
            $performa = $totalHariKerja > 0 ? round(($totalHadir / $totalHariKerja) * 100) : 0;
            // Cap performa at 100 just in case
            $performa = min($performa, 100);

            $rekapData[] = (object) [
                'pegawai' => $user,
                'hadir_tepat' => $hadir,
                'terlambat' => $terlambat,
                'izin_sakit' => $izinSakit,
                'alfa' => $alfa,
                'performa' => $performa,
                'total_hadir' => $totalHadir, // for sorting top/bottom
            ];
        }

        // Sort for Rekap Data Table
        $rekapData = collect($rekapData)->sortByDesc('performa')->values();

        // Top and Bottom Highlight
        $topPegawai = $rekapData->take(2);
        // Find bottom (worst) performer, typically someone with high alfa or telat
        $bottomPegawai = collect($rekapData)->sortBy(function($item) {
            // Formula penalty for lateness and alfa
            return ($item->terlambat * 0.5) + ($item->alfa * 2);
        })->last();

        // 4. Tab 2: Log Aktivitas Harian (Using Pagination)
        $logHarian = (clone $presensiQuery)->with('pegawai')
                                           ->orderBy('tanggal', 'desc')
                                           ->orderBy('waktu_masuk', 'asc')
                                           ->paginate(15);

        // 5. Chart Trend Kehadiran
        // We will build arrays for Highcharts categories and series
        $trendDates = [];
        $trendHadir = [];
        $trendTelat = [];
        
        $dailyGroup = (clone $presensiQuery)->selectRaw("tanggal, status, count(*) as total")
                             ->whereIn('status', ['hadir', 'terlambat'])
                             ->groupBy('tanggal', 'status')
                             ->orderBy('tanggal')
                             ->get()
                             ->groupBy('tanggal');
        
        // Loop from start of month to end (or today, but we just cover the whole month logic)
        $curr = $startOfMonth->copy();
        while($curr <= $endOfMonth) {
            if (!$curr->isWeekend()) {
                $dateStr = $curr->format('Y-m-d');
                $shortDate = $curr->format('j M');
                $trendDates[] = $shortDate;
                
                $dayStats = $dailyGroup->get($dateStr, collect());
                $trendHadir[] = $dayStats->where('status', 'hadir')->first()->total ?? 0;
                $trendTelat[] = $dayStats->where('status', 'terlambat')->first()->total ?? 0;
            }
            $curr->addDay();
        }

        $chartTren = [
            'categories' => $trendDates,
            'hadir' => $trendHadir,
            'terlambat' => $trendTelat
        ];

        return view('pages.backoffice.presensi.index', compact(
            'filterBulan', 'search', 'jabatan', 'totalHariKerja',
            'kpiHadir', 'kpiTerlambat', 'kpiIzinSakit', 'kpiAlfa',
            'rekapData', 'topPegawai', 'bottomPegawai',
            'logHarian', 'chartTren', 'startOfMonth'
        ));
    }
}
