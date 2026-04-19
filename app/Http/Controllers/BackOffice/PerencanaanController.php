<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Perencanaan;
use App\Models\Pembangunan;
use App\Models\PembangunanHistori;
use Illuminate\Http\Request;

class PerencanaanController extends Controller
{
    /**
     * Menampilkan Dasbor Perencanaan
     */
    public function index(Request $request)
    {
        $rkpTahun = $request->input('rkp_tahun', date('Y') + 1);
        $rpjmPeriode = $request->input('rpjm_periode', '2024-2030'); // Disederhanakan untuk demo
        
        $tab = $request->input('tab', 'rkpdes');

        // Tarik data berdasarkan Tab aktif
        $query = Perencanaan::query();
        if ($tab == 'rkpdes') {
            $query->where('jenis_rencana', 'rkpdes')->where('tahun_pelaksanaan', $rkpTahun);
        } else {
            $query->where('jenis_rencana', 'rpjmdes')->where('tahun_pelaksanaan', $rpjmPeriode);
        }
        
        $rencanaList = $query->orderBy('created_at', 'desc')->get();

        // Kartu Ringkasan (Konteks RKP / Tab aktif)
        $totalProgram = $rencanaList->count();
        $estimasiAnggaran = $rencanaList->sum('estimasi_pagu');
        
        $telahRealisasi = $rencanaList->where('status', 'dikonversi')->count();
        $persenRealisasi = $totalProgram > 0 ? ($telahRealisasi / $totalProgram) * 100 : 0;
        
        $mendesak = $rencanaList->where('prioritas', 'tinggi')->where('status', 'draft')->count();

        return view('pages.backoffice.perencanaan.index', compact(
            'rencanaList', 'tab', 'rkpTahun', 'rpjmPeriode',
            'totalProgram', 'estimasiAnggaran', 'telahRealisasi', 'persenRealisasi', 'mendesak'
        ));
    }

    /**
     * Input Usulan Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_rencana' => 'required|in:rkpdes,rpjmdes',
            'tahun_pelaksanaan' => 'required|string',
            'prioritas' => 'required|in:tinggi,sedang,normal',
            'nama_program' => 'required|string|max:255',
            'estimasi_pagu' => 'required|numeric|min:0'
        ]);

        Perencanaan::create([
            'jenis_rencana' => $request->jenis_rencana,
            'tahun_pelaksanaan' => $request->tahun_pelaksanaan,
            'prioritas' => $request->prioritas,
            'nama_program' => $request->nama_program,
            'kategori' => $request->kategori ?? 'Infrastruktur Jalan',
            'tujuan_sasaran' => $request->tujuan_sasaran,
            'estimasi_pagu' => $request->estimasi_pagu,
            'sumber_dana' => $request->sumber_dana,
            'target_mulai' => $request->target_mulai, // misal 2026-05
            'target_selesai' => $request->target_selesai,
            'status' => 'draft'
        ]);

        return back()->with('success', 'Rencana/Usulan berhasil didaftarkan ke ' . strtoupper($request->jenis_rencana));
    }

    /**
     * Render Detail Drawer (AJAX)
     */
    public function detail($id)
    {
        $rencana = Perencanaan::findOrFail($id);
        return view('pages.backoffice.perencanaan._drawer_detail_content', compact('rencana'));
    }

    /**
     * Konversi Rencana ke Pembangunan (1-Click Sync)
     */
    public function konversiKeProyek(Request $request, $id)
    {
        $rencana = Perencanaan::findOrFail($id);

        if ($rencana->status == 'dikonversi' && $rencana->pembangunan_id) {
            return back()->with('error', 'Rencana ini sudah pernah dikonversi ke proyek!');
        }

        // 1. Buat Pembangunan (Draft)
        $proyek = Pembangunan::create([
            // apbdes_id dibiarkan null dulu kecuali dimapping scr eksplisit
            'nama_proyek' => "Pembangunan " . $rencana->nama_program, // imbuhan
            'deskripsi' => "Draft proyek hasil sinkronisasi dari RKPDes. Tujuan: " . $rencana->tujuan_sasaran,
            'kategori' => $rencana->kategori,
            'status' => 'perencanaan', // Sesuai arahan User, masuk ke status draft proyek
            'progres_fisik' => 0,
            
            // Konversi target bulan 'YYYY-MM' jadi date dummy
            'tanggal_mulai' => $rencana->target_mulai ? $rencana->target_mulai . '-01' : null,
            'target_selesai' => $rencana->target_selesai ? $rencana->target_selesai . '-28' : null,
        ]);

        // 2. Tulis Histori Pembuatan Proyek
        PembangunanHistori::create([
            'pembangunan_id' => $proyek->id,
            'judul_update' => 'Sistem Otomatis (Sync RKPDes)',
            'deskripsi' => 'Data awal dibentuk secara otomatis melalui konversi 1-Click dari modul Perencanaan.',
            'tanggal' => now(),
            'oleh_siapa' => auth()->user()->name ?? 'Administrator',
            'is_milestone' => true,
            'progres_dicapai' => 0
        ]);

        // 3. Update Status Rencana
        $rencana->status = 'dikonversi';
        $rencana->pembangunan_id = $proyek->id; // Traceability
        $rencana->save();

        // 4. Redirect ke Manajamen Data Pembangunan dengan Pesan
        return redirect()->route('admin.pembangunan.index')->with('success', 'Rencana "'.$rencana->nama_program.'" berhasil direalisasikan ke Tabel Proyek Fisik (Draft)!');
    }
}
