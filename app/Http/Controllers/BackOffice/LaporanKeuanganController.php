<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Apbdes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LaporanKeuanganController extends Controller
{
    /**
     * Tampilkan Halaman Laporan Keuangan
     */
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        // Mendapatkan Rekap Global APBDes & Realisasi 
        $summary = Apbdes::getSummary($tahun);

        // Ambil data APBDes tipe PENDAPATAN
        $pendapatanData = Apbdes::where('tahun', $tahun)
            ->where('tipe_anggaran', 'PENDAPATAN')
            ->withSum('realisasis', 'nominal')
            ->orderBy('kode_rekening')
            ->get();

        // Ambil data APBDes tipe BELANJA
        $belanjaData = Apbdes::where('tahun', $tahun)
            ->where('tipe_anggaran', 'BELANJA')
            ->withSum('realisasis', 'nominal')
            ->orderBy('kode_rekening')
            ->get();

        // Ambil data desa
        $villagePath = storage_path('app/desa.json');
        $village = [];
        if (File::exists($villagePath)) {
            $village = json_decode(File::get($villagePath), true);
        }

        $yearOptions = range(date('Y') + 1, 2021);

        return view('pages.backoffice.keuangan.laporan.index', compact(
            'tahun',
            'summary',
            'pendapatanData',
            'belanjaData',
            'yearOptions',
            'village'
        ));
    }
}
