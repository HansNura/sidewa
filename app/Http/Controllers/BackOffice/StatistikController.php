<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warga;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatistikController extends Controller
{
    public function index(Request $request)
    {
        $yearFilter = $request->input('tahun', date('Y'));
        $dusunFilter = $request->input('wilayah', 'semua');

        // Base Query with filters
        $query = Warga::query();

        // If we want to simulate data growing by year, we filter by created_at.
        // Actually, demographics usually represent the current snapshot, but
        // to make the filter "Tahun" work realistically, we'll assume it means
        // "Data Penduduk yang Terdaftar Sampai Tahun Ini":
        if ($yearFilter) {
            $query->whereYear('created_at', '<=', $yearFilter);
        }

        if ($dusunFilter && $dusunFilter !== 'semua') {
            // Capitalize first letter to match Enum
            $query->where('dusun', ucfirst($dusunFilter));
        }

        // 1. KPI Summary Cards
        $totalPenduduk = (clone $query)->count();
        $totalKk = (clone $query)->distinct('no_kk')->count('no_kk');
        $totalPraSejahtera = (clone $query)->where('kesejahteraan', 'pra-sejahtera')->count();
        $totalBansosPercent = $totalKk > 0 ? round(($totalPraSejahtera / $totalKk) * 100, 1) : 0;

        $totalStunting = (clone $query)->where('is_stunting', true)->count();
        $totalBalita = (clone $query)->whereRaw("CAST(strftime('%Y', 'now') - strftime('%Y', tanggal_lahir) AS INTEGER) < 5")->count();
        $stuntingPercent = $totalBalita > 0 ? round(($totalStunting / $totalBalita) * 100, 1) : 0;

        // Gender breakdown
        $lakilaki = (clone $query)->where('jenis_kelamin', 'L')->count();
        $perempuan = (clone $query)->where('jenis_kelamin', 'P')->count();

        // 2. AI Insight Generation (Dummy realistic logic)
        $dusunBansosterbanyak = Warga::select('dusun', DB::raw('count(*) as total'))
            ->where('kesejahteraan', 'pra-sejahtera')
            ->groupBy('dusun')
            ->orderByDesc('total')->limit(1)->first();

        $bansosInsights = $dusunBansosterbanyak ? "Keluarga Pra-Sejahtera terbanyak saat ini berpusat di Dusun {$dusunBansosterbanyak->dusun} ({$dusunBansosterbanyak->total} Jiwa)." : '';
        $insightStunting = "Terdapat " . $totalStunting . " balita yang tercatat Stunting. $bansosInsights";

        // 3. Demografi Chart (Umur & JK)
        // Grouping: 0-4, 5-14, 15-24, 25-54, >55
        // Using SQLite logic since we have sqlite Connection in error earlier
        $demoAgesRaw = (clone $query)->selectRaw("
            jenis_kelamin,
            SUM(CASE WHEN CAST(strftime('%Y', 'now') - strftime('%Y', tanggal_lahir) AS INTEGER) BETWEEN 0 AND 4 THEN 1 ELSE 0 END) as age_0_4,
            SUM(CASE WHEN CAST(strftime('%Y', 'now') - strftime('%Y', tanggal_lahir) AS INTEGER) BETWEEN 5 AND 14 THEN 1 ELSE 0 END) as age_5_14,
            SUM(CASE WHEN CAST(strftime('%Y', 'now') - strftime('%Y', tanggal_lahir) AS INTEGER) BETWEEN 15 AND 24 THEN 1 ELSE 0 END) as age_15_24,
            SUM(CASE WHEN CAST(strftime('%Y', 'now') - strftime('%Y', tanggal_lahir) AS INTEGER) BETWEEN 25 AND 54 THEN 1 ELSE 0 END) as age_25_54,
            SUM(CASE WHEN CAST(strftime('%Y', 'now') - strftime('%Y', tanggal_lahir) AS INTEGER) >= 55 THEN 1 ELSE 0 END) as age_55_plus
        ")->groupBy('jenis_kelamin')->get();

        $chartDemografi = [
            'laki' => [0, 0, 0, 0, 0],
            'perempuan' => [0, 0, 0, 0, 0]
        ];

        foreach ($demoAgesRaw as $row) {
            $key = $row->jenis_kelamin == 'L' ? 'laki' : 'perempuan';
            $chartDemografi[$key] = [
                (int)$row->age_0_4,
                (int)$row->age_5_14,
                (int)$row->age_15_24,
                (int)$row->age_25_54,
                (int)$row->age_55_plus
            ];
        }

        // 4. Pendidikan Chart
        $eduRaw = (clone $query)->selectRaw("pendidikan_terakhir, count(*) as total")
            ->whereNotNull('pendidikan_terakhir')
            ->groupBy('pendidikan_terakhir')->get();
        $chartPendidikan = [];
        $eduColors = ['Belum Sekolah' => '#e2e8f0'];
        foreach ($eduRaw as $row) {
            $item = ['name' => $row->pendidikan_terakhir ?? 'Belum Sekolah', 'y' => (int)$row->total];
            if (isset($eduColors[$row->pendidikan_terakhir])) {
                $item['color'] = $eduColors[$row->pendidikan_terakhir];
            }
            $chartPendidikan[] = $item;
        }

        // 5. Pekerjaan Chart
        $kerjaRaw = (clone $query)->selectRaw("pekerjaan, count(*) as total")
            ->whereNotNull('pekerjaan')
            ->groupBy('pekerjaan')
            ->orderByDesc('total')
            ->limit(5)->get();
        $chartPekerjaan = [
            'categories' => $kerjaRaw->pluck('pekerjaan')->toArray(),
            'data' => $kerjaRaw->pluck('total')->toArray()
        ];

        // 6. Tren Pertumbuhan (Areaspline)
        // Dummy logic to generate 5 years back data from current filter
        $trendYears = [];
        $trendPop = [];
        $trendKK = [];
        for ($i = 4; $i >= 0; $i--) {
            $y = $yearFilter - $i;
            $trendYears[] = $y;
            $trendPop[] = Warga::whereYear('created_at', '<=', $y)->count();
            $trendKK[] = Warga::whereYear('created_at', '=', $y)->count(); // New residents that year roughly
        }
        $chartTren = [
            'categories' => $trendYears,
            'populasi' => $trendPop,
            'kk_baru' => $trendKK
        ];

        // 7. Tabel Rincian Kewilayahan + Leaflet Maps
        $dusunTable = Warga::select(
            'dusun',
            DB::raw('count(*) as populasi'),
            DB::raw('count(distinct no_kk) as jml_kk'),
            DB::raw('sum(case when jenis_kelamin = "L" then 1 else 0 end) as L'),
            DB::raw('sum(case when jenis_kelamin = "P" then 1 else 0 end) as P'),
            DB::raw('sum(case when kesejahteraan = "pra-sejahtera" then 1 else 0 end) as bansos'),
            DB::raw('sum(case when is_stunting = 1 then 1 else 0 end) as stunting')
        )->whereYear('created_at', '<=', $yearFilter)
            ->groupBy('dusun')->get();

        // Static geo-coordinates for the map
        $geoDusuns = [
            'Kaler' => ['lat' => -7.170, 'lng' => 108.190],
            'Kidul' => ['lat' => -7.175, 'lng' => 108.200],
            'Wetan' => ['lat' => -7.180, 'lng' => 108.190],
        ];

        $mapData = [];
        foreach ($dusunTable as $row) {
            $lat = $geoDusuns[$row->dusun]['lat'] ?? -7.172;
            $lng = $geoDusuns[$row->dusun]['lng'] ?? 108.196;

            // Kepadatan Radius Base (1 pop = ~2 radius points)
            $kepadatanRadius = min((int)$row->populasi * 10, 800);
            $kepadatanStatus = $row->populasi > 500 ? 'Kepadatan Tinggi' : 'Kepadatan Sedang';
            if ($row->populasi < 100) $kepadatanStatus = 'Kepadatan Rendah';

            $mapData[] = [
                'dusun' => 'Dusun ' . $row->dusun,
                'lat' => $lat,
                'lng' => $lng,
                'radiusKepadatan' => $kepadatanRadius,
                'statusKepadatan' => $kepadatanStatus,
                'stunting' => $row->stunting,
                'radiusStunting' => min((int)$row->stunting * 15, 100)
            ];
        }

        return view('pages.backoffice.statistik.index', compact(
            'yearFilter',
            'dusunFilter',
            'totalPenduduk',
            'totalKk',
            'totalPraSejahtera',
            'totalStunting',
            'totalBalita',
            'totalBansosPercent',
            'stuntingPercent',
            'lakilaki',
            'perempuan',
            'insightStunting',
            'chartDemografi',
            'chartPendidikan',
            'chartPekerjaan',
            'chartTren',
            'dusunTable',
            'mapData'
        ));
    }
}
