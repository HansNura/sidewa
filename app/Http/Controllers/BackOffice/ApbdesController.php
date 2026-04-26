<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\Apbdes;
use App\Models\ApbdesPoster;
use Illuminate\Http\Request;

class ApbdesController extends Controller
{
    /**
     * Tampilkan Halaman Manajemen APBDes
     */
    public function index(Request $request)
    {
        // Parameter Filter Tahun (Default 2026)
        $tahun = $request->input('tahun', '2026');

        // Mendapatkan Rekap Global APBDes pada tahun terpilih
        $summary = Apbdes::getSummary($tahun);

        // Mendapatkan Data Anggaran (Khusus Tipe Belanja biasanya yang di-hierarkikan di UI)
        // Akan dikelompokkan flat atau di-tree di Blade
        $anggarans = Apbdes::where('tahun', $tahun)
            ->where('tipe_anggaran', 'BELANJA')
            ->orderBy('kode_rekening')
            ->get();

        // Mengelompokkan berdasarkan Bidang (Kepala 1 digit, cth: "1", "2")
        // Dan SubBidang/Kegiatan di dalamnya
        $strukturs = [];
        foreach ($anggarans as $item) {
            $parts = explode('.', $item->kode_rekening);
            // Bidang (ex: 1)
            $bidangKey = $parts[0];

            if (!isset($strukturs[$bidangKey])) {
                $strukturs[$bidangKey] = [
                    'bidang_item' => null,
                    'subs' => []
                ];
            }

            if (count($parts) == 1) { // Ini adalah Bidangnya itu sendiri
                $strukturs[$bidangKey]['bidang_item'] = $item;
            } else if (count($parts) == 2) { // Ini Sub Bidang (1.1)
                $subKey = $parts[0] . '.' . $parts[1];
                $strukturs[$bidangKey]['subs'][$subKey]['sub_item'] = $item;
                if (!isset($strukturs[$bidangKey]['subs'][$subKey]['kegiatans'])) {
                    $strukturs[$bidangKey]['subs'][$subKey]['kegiatans'] = [];
                }
            } else { // Ini Kegiatan (1.1.01 atau 1.1.1.1 dsb)
                $subKey = $parts[0] . '.' . $parts[1];
                // Pastikan parent array ada walaupun data parent-nya blm ketarif
                if (!isset($strukturs[$bidangKey]['subs'][$subKey])) {
                    $strukturs[$bidangKey]['subs'][$subKey] = [
                        'sub_item' => null,
                        'kegiatans' => []
                    ];
                }
                $strukturs[$bidangKey]['subs'][$subKey]['kegiatans'][] = $item;
            }
        }

        // Ambil Data Poster/Dokumen
        $poster = ApbdesPoster::where('tahun', $tahun)->first();

        // Option List Tahun (2020 s/d Tahun Depan)
        $yearOptions = range(date('Y') + 1, 2021);

        return view('pages.backoffice.keuangan.apbdes.index', compact('tahun', 'summary', 'strukturs', 'poster', 'yearOptions'));
    }

    /**
     * Store item anggaran baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun'         => 'required|numeric',
            'kode_rekening' => 'required|string|max:50',
            'tipe_anggaran' => 'required|in:PENDAPATAN,BELANJA,PEMBIAYAAN',
            'nama_kegiatan' => 'required|string|max:255',
            'pagu_anggaran' => 'required|numeric|min:0',
            'sumber_dana'   => 'nullable|string|max:50',
        ]);

        // Cek duplikasi kode rekening pada tahun yang sama
        $exists = Apbdes::where('tahun', $request->tahun)
            ->where('kode_rekening', $request->kode_rekening)
            ->first();

        if ($exists) {
            return back()->with('error', 'Kode Rekening ' . $request->kode_rekening . ' sudah digunakan pada tahun ' . $request->tahun)->withInput();
        }

        Apbdes::create($request->all());

        return back()->with('success', 'Berhasil mempublikasikan alokasi anggaran baru!');
    }

    /**
     * Update item anggaran
     */
    public function update(Request $request, Apbdes $apbdes)
    {
        $request->validate([
            'kode_rekening' => 'required|string|max:50',
            'tipe_anggaran' => 'required|in:PENDAPATAN,BELANJA,PEMBIAYAAN',
            'nama_kegiatan' => 'required|string|max:255',
            'pagu_anggaran' => 'required|numeric|min:0',
            'sumber_dana'   => 'nullable|string|max:50',
        ]);

        // Cek duplikasi kode rekening pada tahun yang sama
        $exists = Apbdes::where('tahun', $apbdes->tahun)
            ->where('kode_rekening', $request->kode_rekening)
            ->where('id', '!=', $apbdes->id)
            ->first();

        if ($exists) {
            return back()->with('error', 'Kode Rekening ' . $request->kode_rekening . ' sudah digunakan pada tahun ' . $apbdes->tahun)->withInput();
        }

        $data = $request->all();
        $data['is_published'] = $request->has('is_published') && $request->is_published;

        $apbdes->update($data);

        return back()->with('success', 'Data Anggaran berhasil diperbarui!');
    }

    /**
     * Store konfigurasi poster/baliho & dokumen untuk public page
     */
    public function storePoster(Request $request)
    {
        $request->validate([
            'tahun' => 'required|numeric',
            'gambar_baliho' => 'nullable|image|max:2048', // max 2MB
            'perdes_dokumen' => 'nullable|mimes:pdf|max:5120', // max 5MB
            'rab_dokumen' => 'nullable|mimes:pdf,xls,xlsx|max:5120', // max 5MB
            'gambar_baliho_url' => 'nullable|string',
            'perdes_dokumen_url' => 'nullable|string',
            'rab_dokumen_url' => 'nullable|string',
        ]);

        $poster = ApbdesPoster::where('tahun', $request->tahun)->first();
        $data = ['tahun' => $request->tahun];

        // Gambar Baliho
        if ($request->hasFile('gambar_baliho')) {
            $path = $request->file('gambar_baliho')->store('apbdes/baliho', 'public');
            $data['gambar_baliho_url'] = \Illuminate\Support\Facades\Storage::url($path);
        } elseif ($request->filled('gambar_baliho_url')) {
            $data['gambar_baliho_url'] = $request->input('gambar_baliho_url');
        }

        // Perdes Dokumen
        if ($request->hasFile('perdes_dokumen')) {
            $path = $request->file('perdes_dokumen')->store('apbdes/dokumen', 'public');
            $data['perdes_dokumen_url'] = \Illuminate\Support\Facades\Storage::url($path);
        } elseif ($request->filled('perdes_dokumen_url')) {
            $data['perdes_dokumen_url'] = $request->input('perdes_dokumen_url');
        }

        // RAB Dokumen
        if ($request->hasFile('rab_dokumen')) {
            $path = $request->file('rab_dokumen')->store('apbdes/dokumen', 'public');
            $data['rab_dokumen_url'] = \Illuminate\Support\Facades\Storage::url($path);
        } elseif ($request->filled('rab_dokumen_url')) {
            $data['rab_dokumen_url'] = $request->input('rab_dokumen_url');
        }

        ApbdesPoster::updateOrCreate(
            ['tahun' => $request->tahun],
            $data
        );

        return back()->with('success', 'Media & Dokumen Publikasi APBDes ' . $request->tahun . ' berhasil diperbarui!');
    }
}
