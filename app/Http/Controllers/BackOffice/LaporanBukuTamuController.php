<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\BukuTamu;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelWriter;

class LaporanBukuTamuController extends Controller
{
    /**
     * Tampilkan halaman analisis dan pelaporan Buku Tamu
     */
    public function index(Request $request)
    {
        $periodInput = $request->input('period', Carbon::now()->format('Y-m'));
        $activePeriod = Carbon::createFromFormat('Y-m', $periodInput);
        
        $startOfMonth = $activePeriod->copy()->startOfMonth();
        $endOfMonth = $activePeriod->copy()->endOfMonth();

        // Target Date Range (Days elapsed this month to calculate accurate averages)
        $daysPassed = 0;
        $maxDate = $activePeriod->format('Y-m') === Carbon::now()->format('Y-m') 
                   ? Carbon::now() 
                   : $endOfMonth;

        $temp = $startOfMonth->copy();
        while ($temp <= $maxDate) {
            $daysPassed++;
            $temp->addDay();
        }
        if ($daysPassed === 0) $daysPassed = 1;

        // Base Query Modifiers
        $periodQuery = BukuTamu::whereBetween('waktu_masuk', [
            $startOfMonth->format('Y-m-d 00:00:00'), 
            $endOfMonth->format('Y-m-d 23:59:59')
        ]);
        
        // 1. CARDS / GLOBAL STATS
        // - Total Kunjungan
        $totalKunjungan = (clone $periodQuery)->count();
        
        // - Kunjungan Bulan Lalu (Untuk trend % pertumbuhan)
        $lastMonthStart = $startOfMonth->copy()->subMonth();
        $lastMonthEnd = $lastMonthStart->copy()->endOfMonth();
        $totalLastMonth = BukuTamu::whereBetween('waktu_masuk', [
            $lastMonthStart->format('Y-m-d 00:00:00'), 
            $lastMonthEnd->format('Y-m-d 23:59:59')
        ])->count();
        
        $pertumbuhan = 0;
        if ($totalLastMonth > 0) {
            $pertumbuhan = round((($totalKunjungan - $totalLastMonth) / $totalLastMonth) * 100, 1);
        } else if ($totalLastMonth == 0 && $totalKunjungan > 0) {
            $pertumbuhan = 100; // 100% up if previous was 0 and now has data
        }

        // - Instansi Terbanyak
        $instansiTerbanyak = (clone $periodQuery)
            ->whereNotNull('instansi')
            ->select('instansi', \DB::raw('count(*) as total'))
            ->groupBy('instansi')
            ->orderByDesc('total')
            ->first();

        // - Persentase Kiosk Mandiri
        $totalKiosk = (clone $periodQuery)->where('metode_input', 'kiosk')->count();
        $persentaseKiosk = $totalKunjungan > 0 ? round(($totalKiosk / $totalKunjungan) * 100, 1) : 0;

        $stats = [
            'total'             => $totalKunjungan,
            'pertumbuhan'       => $pertumbuhan,
            'rata_rata_harian'  => round($totalKunjungan / $daysPassed, 1),
            'instansi_teratas'  => $instansiTerbanyak ? $instansiTerbanyak->instansi : 'N/A',
            'instansi_count'    => $instansiTerbanyak ? $instansiTerbanyak->total : 0,
            'persentase_kiosk'  => $persentaseKiosk
        ];

        // 2. TREND CHART Harian (Areaspline)
        $trendData = [
            'categories' => [],
            'data' => []
        ];
        
        $temp = $startOfMonth->copy();
        while ($temp <= $endOfMonth) { // show full month in chart
            $dayStr = $temp->format('Y-m-d');
            $count = (clone $periodQuery)
                ->whereDate('waktu_masuk', $dayStr)
                ->count();
            
            $trendData['categories'][] = $temp->format('d M');
            $trendData['data'][] = $count;
            $temp->addDay();
        }

        // 3. TABLE LOG & FILTERING
        $tableQuery = clone $periodQuery;
        if ($request->filled('search')) {
            $search = $request->search;
            $tableQuery->where(function($q) use ($search) {
                $q->where('nama_tamu', 'like', "%{$search}%")
                  ->orWhere('instansi', 'like', "%{$search}%");
            });
        }
        if ($request->filled('filter_tujuan') && $request->filter_tujuan !== 'Semua Tujuan') {
            $tableQuery->where('tujuan_kategori', $request->filter_tujuan);
        }
        $bukuTamus = $tableQuery->orderBy('waktu_masuk', 'desc')->paginate(15)->withQueryString();

        // 4. PREPARE GLOBAL ANALYTICS FOR DRAWER (Pie & Bar)
        // Pie Chart: Tujuan
        $tujuanGroups = (clone $periodQuery)
            ->select('tujuan_kategori', \DB::raw('count(*) as total'))
            ->groupBy('tujuan_kategori')
            ->get();
            
        $pieData = [];
        $colors = ['Layanan Surat' => '#16a34a', 'Koordinasi' => '#3b82f6', 'Lain-lain' => '#cbd5e1'];
        foreach($tujuanGroups as $group) {
            $percentage = $totalKunjungan > 0 ? round(($group->total / $totalKunjungan) * 100, 1) : 0;
            $pieData[] = [
                'name'  => $group->tujuan_kategori,
                'y'     => $percentage,
                'color' => $colors[$group->tujuan_kategori] ?? '#94a3b8'
            ];
        }

        // Bar Chart: Jam Ramai
        // Simplified grouping manually via PHP to support generic timestamps across DBs
        $allTimes = (clone $periodQuery)->select('waktu_masuk')->get();
        $timeBlocks = ['08-10' => 0, '10-12' => 0, '12-14' => 0, '14-16' => 0, 'Lainnya' => 0];
        
        foreach($allTimes as $time) {
            $hour = Carbon::parse($time->waktu_masuk)->hour;
            if ($hour >= 8 && $hour < 10) $timeBlocks['08-10']++;
            else if ($hour >= 10 && $hour < 12) $timeBlocks['10-12']++;
            else if ($hour >= 12 && $hour < 14) $timeBlocks['12-14']++;
            else if ($hour >= 14 && $hour < 16) $timeBlocks['14-16']++;
            else $timeBlocks['Lainnya']++;
        }

        $barData = [
            'categories' => array_keys($timeBlocks),
            'data' => array_values($timeBlocks)
        ];

        // Top Institutions List
        $topInstansiList = (clone $periodQuery)
            ->whereNotNull('instansi')
            ->select('instansi', 'tujuan_kategori', \DB::raw('count(*) as total'))
            ->groupBy('instansi', 'tujuan_kategori')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $analytics = [
            'pie' => $pieData,
            'bar' => $barData,
            'top_instansi' => $topInstansiList
        ];

        // Period Dropdown Generator (last 6 months)
        $monthOptions = [];
        for ($i = 0; $i < 6; $i++) {
            $m = Carbon::now()->subMonths($i);
            $monthOptions[$m->format('Y-m')] = $m->translatedFormat('F Y');
        }

        return view('pages.backoffice.buku-tamu.index', compact(
            'activePeriod', 'periodInput', 'monthOptions', 
            'stats', 'trendData', 'bukuTamus', 'analytics'
        ));
    }

    /**
     * Detail 1 Tamu (Async JSON) untuk Drawer
     */
    public function show(BukuTamu $bukuTamu): JsonResponse
    {
        return response()->json([
            'id' => $bukuTamu->id,
            'nama_tamu' => $bukuTamu->nama_tamu,
            'instansi' => $bukuTamu->instansi ?? 'Tidak Ada',
            'tujuan_kategori' => $bukuTamu->tujuan_kategori,
            'keperluan' => $bukuTamu->keperluan ?? '-',
            'metode_input' => strtoupper($bukuTamu->metode_input),
            'status' => ucfirst($bukuTamu->status),
            'status_color' => $bukuTamu->statusColor(),
            'waktu_masuk' => $bukuTamu->waktu_masuk->translatedFormat('d M Y - H:i WIB'),
            'waktu_keluar' => $bukuTamu->waktu_keluar ? $bukuTamu->waktu_keluar->translatedFormat('d M Y - H:i WIB') : 'Belum selesai',
            'durasi' => $bukuTamu->waktu_keluar ? $bukuTamu->waktu_masuk->diffInMinutes($bukuTamu->waktu_keluar) . ' Menit' : '-'
        ]);
    }

    /**
     * Export Dokumen (Mirip Rekap Presensi)
     */
    public function export(Request $request)
    {
        $type = $request->input('format', 'pdf');
        
        $periodInput = $request->input('period', Carbon::now()->format('Y-m'));
        $activePeriod = Carbon::createFromFormat('Y-m', $periodInput);
        $startOfMonth = $activePeriod->copy()->startOfMonth();
        $endOfMonth = $activePeriod->copy()->endOfMonth();

        $dataTamu = BukuTamu::whereBetween('waktu_masuk', [
                $startOfMonth->format('Y-m-d 00:00:00'), 
                $endOfMonth->format('Y-m-d 23:59:59')
            ])->orderBy('waktu_masuk', 'asc')->get();

        $dataForExport = [];
        foreach ($dataTamu as $index => $tamu) {
            $dataForExport[] = [
                'No' => $index + 1,
                'Tanggal' => $tamu->waktu_masuk->format('d/m/Y'),
                'Waktu' => $tamu->waktu_masuk->format('H:i') . ' - ' . ($tamu->waktu_keluar ? $tamu->waktu_keluar->format('H:i') : '..:..'),
                'Nama Tamu' => $tamu->nama_tamu,
                'Asal/Instansi' => $tamu->instansi ?? '-',
                'Kategori Tujuan' => $tamu->tujuan_kategori,
                'Keperluan Detil' => $tamu->keperluan,
                'Status' => $tamu->status
            ];
        }

        $filename = 'Laporan_Buku_Tamu_' . $activePeriod->format('Y_m');

        if ($type === 'excel') {
            return SimpleExcelWriter::streamDownload("{$filename}.xlsx")
                ->addRows($dataForExport)
                ->toBrowser();
        }

        // Output to standard PDF format previously styled (Or create similar one)
        $pdf = Pdf::loadView('pages.backoffice.buku-tamu.pdf', [
            'data' => $dataForExport,
            'period' => $activePeriod->translatedFormat('F Y')
        ])->setPaper('a4', 'landscape');

        return $pdf->download("{$filename}.pdf");
    }
}
