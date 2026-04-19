<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Apbdes;
use App\Models\ApbdesPoster;

class ApbdesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data APBDes untuk TA 2026
        // PENDAPATAN
        Apbdes::create([
            'tahun' => 2026,
            'tipe_anggaran' => 'PENDAPATAN',
            'kode_rekening' => '4',
            'nama_kegiatan' => 'PENDAPATAN',
            'pagu_anggaran' => 2450000000,
            'sumber_dana' => 'Semua',
        ]);
        Apbdes::create([
            'tahun' => 2026,
            'tipe_anggaran' => 'PENDAPATAN',
            'kode_rekening' => '4.1',
            'nama_kegiatan' => 'Pendapatan Asli Desa',
            'pagu_anggaran' => 250000000,
            'sumber_dana' => 'PADesa',
        ]);
        Apbdes::create([
            'tahun' => 2026,
            'tipe_anggaran' => 'PENDAPATAN',
            'kode_rekening' => '4.2',
            'nama_kegiatan' => 'Pendapatan Transfer',
            'pagu_anggaran' => 2200000000,
            'sumber_dana' => 'DD/ADD',
        ]);

        // BELANJA - BIDANG 1
        Apbdes::create([
            'tahun' => 2026,
            'tipe_anggaran' => 'BELANJA',
            'kode_rekening' => '1',
            'nama_kegiatan' => 'BIDANG PENYELENGGARAAN PEMERINTAHAN DESA',
            'pagu_anggaran' => 850000000,
            'sumber_dana' => 'ADD/PADesa',
        ]);
        Apbdes::create([
            'tahun' => 2026,
            'tipe_anggaran' => 'BELANJA',
            'kode_rekening' => '1.1',
            'nama_kegiatan' => 'Siltap dan Tunjangan Aparatur',
            'pagu_anggaran' => 500000000,
            'sumber_dana' => 'ADD',
        ]);
        Apbdes::create([
            'tahun' => 2026,
            'tipe_anggaran' => 'BELANJA',
            'kode_rekening' => '1.1.01',
            'nama_kegiatan' => 'Penghasilan Tetap Kepala Desa dan Perangkat',
            'pagu_anggaran' => 350000000,
            'sumber_dana' => 'ADD',
        ]);
        Apbdes::create([
            'tahun' => 2026,
            'tipe_anggaran' => 'BELANJA',
            'kode_rekening' => '1.1.02',
            'nama_kegiatan' => 'Tunjangan BPD dan Operasional',
            'pagu_anggaran' => 150000000,
            'sumber_dana' => 'ADD',
        ]);

        // BELANJA - BIDANG 2
        Apbdes::create([
            'tahun' => 2026,
            'tipe_anggaran' => 'BELANJA',
            'kode_rekening' => '2',
            'nama_kegiatan' => 'BIDANG PELAKSANAAN PEMBANGUNAN DESA',
            'pagu_anggaran' => 1250000000,
            'sumber_dana' => 'DD/BanProv',
        ]);
        Apbdes::create([
            'tahun' => 2026,
            'tipe_anggaran' => 'BELANJA',
            'kode_rekening' => '2.1',
            'nama_kegiatan' => 'Pembangunan Infrastruktur Jalan',
            'pagu_anggaran' => 800000000,
            'sumber_dana' => 'DD',
        ]);
        Apbdes::create([
            'tahun' => 2026,
            'tipe_anggaran' => 'BELANJA',
            'kode_rekening' => '2.1.01',
            'nama_kegiatan' => 'Pengaspalan Jalan Dusun Kaler',
            'pagu_anggaran' => 500000000,
            'sumber_dana' => 'DD',
        ]);
        Apbdes::create([
            'tahun' => 2026,
            'tipe_anggaran' => 'BELANJA',
            'kode_rekening' => '2.1.02',
            'nama_kegiatan' => 'Pembuatan Drainase Lingkungan',
            'pagu_anggaran' => 300000000,
            'sumber_dana' => 'DD',
        ]);

        // PEMBIAYAAN
        // We will assume Pembiayaan covers SILPA etc.
        Apbdes::create([
            'tahun' => 2026,
            'tipe_anggaran' => 'PEMBIAYAAN',
            'kode_rekening' => '6',
            'nama_kegiatan' => 'PEMBIAYAAN DESA',
            'pagu_anggaran' => 0,
            'sumber_dana' => 'SiLPA',
        ]);

        // DUMMY PAST YEARS FOR SILPA CHART
        $pastYears = [
            2025 => [
                'pendapatan' => 2300000000,
                'belanja' => 2200000000,
            ],
            2024 => [
                'pendapatan' => 1750000000,
                'belanja' => 1685000000,
            ],
            2023 => [
                'pendapatan' => 1620000000,
                'belanja' => 1590000000,
            ],
        ];

        foreach ($pastYears as $year => $data) {
            Apbdes::create([
                'tahun' => $year,
                'tipe_anggaran' => 'PENDAPATAN',
                'kode_rekening' => '4',
                'nama_kegiatan' => 'Total Pendapatan ' . $year,
                'pagu_anggaran' => $data['pendapatan'],
                'sumber_dana' => 'Campuran',
            ]);
            Apbdes::create([
                'tahun' => $year,
                'tipe_anggaran' => 'BELANJA',
                'kode_rekening' => '5',
                'nama_kegiatan' => 'Total Belanja ' . $year,
                'pagu_anggaran' => $data['belanja'],
                'sumber_dana' => 'Campuran',
            ]);
            ApbdesPoster::create([
                'tahun' => $year,
                'gambar_baliho_url' => 'https://placehold.co/1200x800/2E7D32/fff?text=Baliho+APBDes+' . $year,
                'perdes_dokumen_url' => '#',
                'rab_dokumen_url' => '#',
            ]);
        }

        // POSTER 2026
        ApbdesPoster::create([
            'tahun' => 2026,
            'gambar_baliho_url' => 'https://images.unsplash.com/photo-1541888048245-0d0bc42a0a2d?auto=format&fit=crop&q=80&w=1600',
            'perdes_dokumen_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
            'rab_dokumen_url' => 'https://freetestdata.com/wp-content/uploads/2021/09/Free_Test_Data_100KB_XLSX.xlsx',
        ]);
    }
}
