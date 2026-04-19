<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Apbdes;
use App\Models\ApbdesRealisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RealisasiAnggaranController extends Controller
{
    /**
     * Tampilkan Halaman Monitoring Realisasi
     */
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        // Mendapatkan Rekap Global APBDes & Realisasi 
        $summary = Apbdes::getSummary($tahun);

        // Ambil data APBDes tipe BELANJA untuk tabel & perhitungan progress
        $kegiatans = Apbdes::where('tahun', $tahun)
            ->where('tipe_anggaran', 'BELANJA')
            ->withSum('realisasis', 'nominal') // Ambil total realisasi per item
            ->orderBy('kode_rekening')
            ->get();

        // Statistik Trend per Bidang (Kepala rekening 1.x, 2.x dst)
        $trenPerBidang = [];
        foreach ($kegiatans as $k) {
            $kodeGroup = explode('.', $k->kode_rekening)[0]; // Misal 1 dari 1.2.03
            if (!isset($trenPerBidang[$kodeGroup])) {
                $trenPerBidang[$kodeGroup] = [
                    'nama_bidang' => "Bidang $kodeGroup",
                    'total_pagu' => 0,
                    'total_realisasi' => 0,
                ];
            }
            $trenPerBidang[$kodeGroup]['total_pagu'] += $k->pagu_anggaran;
            $trenPerBidang[$kodeGroup]['total_realisasi'] += $k->realisasis_sum_nominal ?? 0;
            
            // Nama Bidang yang lebih representatif
            if (strlen($k->kode_rekening) == 1) {
                $trenPerBidang[$kodeGroup]['nama_bidang'] = $k->nama_kegiatan;
            }
        }

        // Peringatan Anomali (Over-budget atau Pagu Menipis >= 95%)
        $anomali = [];
        foreach ($kegiatans as $k) {
            $realisasi = $k->realisasis_sum_nominal ?? 0;
            if ($k->pagu_anggaran > 0) {
                $pct = ($realisasi / $k->pagu_anggaran) * 100;
                if ($pct > 100) {
                    $anomali[] = [
                        'jenis' => 'OVER_BUDGET',
                        'kegiatan' => $k->nama_kegiatan,
                        'keterangan' => "Realisasi melampaui pagu sebesar " . number_format($pct, 1) . "%",
                    ];
                } elseif ($pct >= 95) {
                    $anomali[] = [
                        'jenis' => 'WARNING',
                        'kegiatan' => $k->nama_kegiatan,
                        'keterangan' => "Pagu anggaran menipis (" . number_format($pct, 1) . "%)",
                    ];
                }
            }
        }

        $yearOptions = range(date('Y') + 1, 2021);

        return view('pages.backoffice.keuangan.realisasi.index', compact('tahun', 'summary', 'kegiatans', 'trenPerBidang', 'anomali', 'yearOptions'));
    }

    /**
     * Store realisasi penggunaan dana (Input Realisasi)
     */
    public function store(Request $request)
    {
        $request->validate([
            'apbdes_id'         => 'required|exists:apbdes,id',
            'tanggal_transaksi' => 'required|date',
            'nominal'           => 'required|numeric|min:1',
            'catatan'           => 'nullable|string',
            'bukti_file'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Maks 5MB
        ]);

        $filePath = null;
        if ($request->hasFile('bukti_file')) {
            $file = $request->file('bukti_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            // Disimpan di storage/app/public/realisasi_apbdes agar dapat diakses jika mendapatkan link url
            $filePath = $file->storeAs('realisasi_apbdes', $fileName, 'public');
        }

        ApbdesRealisasi::create([
            'apbdes_id' => $request->apbdes_id,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'nominal' => $request->nominal,
            'catatan' => $request->catatan,
            'bukti_file_path' => $filePath,
        ]);

        return back()->with('success', 'Data realisasi berhasil ditambahkan.');
    }

    /**
     * Get histori realisasi untuk Side Drawer (AJAX Request / View)
     */
    public function detailActivity(Request $request, $apbdes_id)
    {
        $kegiatan = Apbdes::with(['realisasis' => function($q) {
            $q->orderBy('tanggal_transaksi', 'desc');
        }])->findOrFail($apbdes_id);

        return view('pages.backoffice.keuangan.realisasi._drawer_detail_content', compact('kegiatan'));
    }
}
