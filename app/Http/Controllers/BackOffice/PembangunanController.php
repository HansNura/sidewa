<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Apbdes;
use App\Models\Pembangunan;
use App\Models\PembangunanHistori;
use App\Models\PembangunanFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembangunanController extends Controller
{
    /**
     * Tampilkan Dashboard Pembangunan
     */
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        // Query Utama
        $query = Pembangunan::with('apbdes')->whereYear('tanggal_mulai', $tahun)->orWhereYear('target_selesai', $tahun)->orWhereNull('tanggal_mulai');
        
        $proyeks = $query->orderBy('created_at', 'desc')->get();

        // Kartu Ringkasan
        $totalProyek = $proyeks->count();
        $berjalan = $proyeks->where('status', 'berjalan')->count();
        $selesai = $proyeks->where('status', 'selesai')->count();
        $terlambat = $proyeks->where('status', 'terlambat')->count();

        // Chart Kategori
        $kategoriDist = $proyeks->groupBy('kategori')->map(function($row) {
            return $row->count();
        });
        
        // Pilihan APBDes (Belanja Pembangunan) untuk di-link ke Modal Tambah
        $apbdesOptions = Apbdes::where('tahun', $tahun)
            ->where('tipe_anggaran', 'BELANJA')
            ->where('kode_rekening', 'LIKE', '2.%') // Bidang 2: Pelaksanaan Pembangunan Desa
            ->get();

        return view('pages.backoffice.pembangunan.index', compact(
            'tahun', 'proyeks', 
            'totalProyek', 'berjalan', 'selesai', 'terlambat',
            'kategoriDist', 'apbdesOptions'
        ));
    }

    /**
     * Input Data Pembangunan Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'apbdes_id' => 'nullable|exists:apbdes,id',
            'kategori' => 'required|string',
            'status' => 'required|in:perencanaan,berjalan,selesai,terlambat',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $project = Pembangunan::create([
            'nama_proyek' => $request->nama_proyek,
            'deskripsi' => $request->deskripsi,
            'apbdes_id' => $request->apbdes_id,
            'kategori' => $request->kategori,
            'lokasi_dusun' => $request->lokasi_dusun,
            'rt_rw' => $request->rt_rw,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'tanggal_mulai' => $request->tanggal_mulai,
            'target_selesai' => $request->target_selesai,
            'status' => $request->status,
            'progres_fisik' => ($request->status == 'selesai') ? 100 : 0
        ]);

        // Catat Historis Pertama
        PembangunanHistori::create([
            'pembangunan_id' => $project->id,
            'judul_update' => 'Proyek Didaftarkan',
            'deskripsi' => 'Data awal proyek dimasukkan ke dalam sistem oleh admin.',
            'tanggal' => now(),
            'oleh_siapa' => auth()->user()->name ?? 'Administrator',
            'is_milestone' => true,
            'progres_dicapai' => $project->progres_fisik
        ]);

        return back()->with('success', 'Data proyek berhasil ditambahkan!');
    }

    /**
     * Render Drawer Detail (Ajax)
     */
    public function detail(Request $request, $id)
    {
        $proyek = Pembangunan::with(['apbdes.realisasis', 'historis', 'fotos'])->findOrFail($id);
        
        $paguAnggaran = $proyek->apbdes->pagu_anggaran ?? 0;
        $totalRealisasi = isset($proyek->apbdes) ? current($proyek->apbdes->realisasis)->sum('nominal') : 0;
        
        // Catatan: Model Apbdes kita punya logic relasi realisasis() yg merupakan query hasMany.
        if(isset($proyek->apbdes)){
             $totalRealisasi = $proyek->apbdes->realisasis()->sum('nominal');
        }

        return view('pages.backoffice.pembangunan._drawer_detail_content', compact('proyek', 'paguAnggaran', 'totalRealisasi'));
    }

    /**
     * Update Progres Fisik Lapangan & Upload Gambar
     */
    public function updateProgress(Request $request, $id)
    {
        $proyek = Pembangunan::findOrFail($id);

        $request->validate([
            'progres_fisik' => 'required|integer|min:0|max:100',
            'tanggal' => 'required|date',
            'judul_update' => 'required|string|max:255',
            'deskripsi_update' => 'nullable|string',
            'foto_lapangan' => 'nullable|image|max:5120',
        ]);

        // Simpan Foto
        $fotoPath = null;
        if ($request->hasFile('foto_lapangan')) {
            $fotoPath = $request->file('foto_lapangan')->store('pembangunan_fotos', 'public');
            
            PembangunanFoto::create([
                'pembangunan_id' => $proyek->id,
                'foto_path' => $fotoPath,
                'keterangan' => $request->progres_fisik . '% (' . $request->judul_update . ')',
                'progres_saat_foto' => $request->progres_fisik
            ]);
        }

        // Catat di timeline
        PembangunanHistori::create([
            'pembangunan_id' => $proyek->id,
            'judul_update' => $request->judul_update,
            'deskripsi' => $request->deskripsi_update,
            'tanggal' => $request->tanggal,
            'oleh_siapa' => auth()->user()->name ?? 'Administrator',
            'is_milestone' => ($request->progres_fisik >= 100),
            'progres_dicapai' => $request->progres_fisik
        ]);

        // Update Progres Proyek & Status
        $proyek->progres_fisik = $request->progres_fisik;
        if ($request->progres_fisik >= 100) {
            $proyek->status = 'selesai';
        } elseif ($proyek->status == 'perencanaan' && $request->progres_fisik > 0) {
            $proyek->status = 'berjalan';
        }
        $proyek->save();

        return back()->with('success', 'Update progres berhasil disimpan!');
    }
}
