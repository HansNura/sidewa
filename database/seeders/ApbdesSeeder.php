<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Apbdes;
use App\Models\ApbdesRealisasi;
use Carbon\Carbon;

class ApbdesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $years = [date('Y') - 1, date('Y')];

        foreach ($years as $tahun) {
            $this->seedTahun($tahun);
        }
    }

    private function seedTahun($tahun)
    {
        // PENDAPATAN
        $pendapatanData = [
            ['kode' => '4.1.1.01', 'nama' => 'Hasil Usaha Desa', 'pagu' => 50000000, 'sumber' => 'PAD'],
            ['kode' => '4.2.1.01', 'nama' => 'Dana Desa', 'pagu' => 850000000, 'sumber' => 'DD'],
            ['kode' => '4.2.2.01', 'nama' => 'Bagi Hasil Pajak dan Retribusi', 'pagu' => 45000000, 'sumber' => 'PBH'],
            ['kode' => '4.2.3.01', 'nama' => 'Alokasi Dana Desa', 'pagu' => 420000000, 'sumber' => 'ADD'],
            ['kode' => '4.2.4.01', 'nama' => 'Bantuan Keuangan Provinsi', 'pagu' => 130000000, 'sumber' => 'BKP'],
        ];

        foreach ($pendapatanData as $data) {
            $apbdes = Apbdes::create([
                'tahun' => $tahun,
                'tipe_anggaran' => 'PENDAPATAN',
                'kode_rekening' => $data['kode'],
                'nama_kegiatan' => $data['nama'],
                'pagu_anggaran' => $data['pagu'],
                'sumber_dana' => $data['sumber'],
                'is_published' => true,
            ]);

            // Add realisasi (random 70% to 100%)
            $this->createRealisasi($apbdes, $tahun, rand(70, 100) / 100);
        }

        // BELANJA
        $belanjaData = [
            ['kode' => '5.1.1.01', 'nama' => 'Penghasilan Tetap Kepala Desa', 'pagu' => 48000000, 'sumber' => 'ADD'],
            ['kode' => '5.1.1.02', 'nama' => 'Penghasilan Tetap Perangkat Desa', 'pagu' => 180000000, 'sumber' => 'ADD'],
            ['kode' => '5.1.2.01', 'nama' => 'Penyediaan Operasional Pemerintah Desa', 'pagu' => 65000000, 'sumber' => 'ADD'],
            ['kode' => '5.2.1.01', 'nama' => 'Pembangunan Jalan Lingkungan Desa', 'pagu' => 250000000, 'sumber' => 'DD'],
            ['kode' => '5.2.1.05', 'nama' => 'Pembangunan Saluran Irigasi Tersier', 'pagu' => 120000000, 'sumber' => 'DD'],
            ['kode' => '5.2.3.01', 'nama' => 'Pembangunan Posyandu', 'pagu' => 85000000, 'sumber' => 'DD'],
            ['kode' => '5.3.1.01', 'nama' => 'Pembinaan Karang Taruna', 'pagu' => 25000000, 'sumber' => 'DD'],
            ['kode' => '5.3.2.01', 'nama' => 'Pembinaan PKK', 'pagu' => 15000000, 'sumber' => 'DD'],
            ['kode' => '5.4.1.01', 'nama' => 'Pelatihan Kepala Desa dan Perangkat', 'pagu' => 20000000, 'sumber' => 'DD'],
            ['kode' => '5.4.2.01', 'nama' => 'Pelatihan Kader Kesehatan', 'pagu' => 10000000, 'sumber' => 'DD'],
            ['kode' => '5.5.1.01', 'nama' => 'Bantuan Langsung Tunai (BLT) Desa', 'pagu' => 216000000, 'sumber' => 'DD'],
        ];

        foreach ($belanjaData as $data) {
            $apbdes = Apbdes::create([
                'tahun' => $tahun,
                'tipe_anggaran' => 'BELANJA',
                'kode_rekening' => $data['kode'],
                'nama_kegiatan' => $data['nama'],
                'pagu_anggaran' => $data['pagu'],
                'sumber_dana' => $data['sumber'],
                'is_published' => true,
            ]);

            // Add realisasi (random 40% to 95%)
            $this->createRealisasi($apbdes, $tahun, rand(40, 95) / 100);
        }

        // PEMBIAYAAN
        $pembiayaanData = [
            ['kode' => '6.1.1.01', 'nama' => 'Sisa Lebih Perhitungan Anggaran (SiLPA)', 'pagu' => 45000000, 'sumber' => 'PAD'],
            ['kode' => '6.2.1.01', 'nama' => 'Penyertaan Modal BUMDes', 'pagu' => 50000000, 'sumber' => 'DD'],
        ];

        foreach ($pembiayaanData as $data) {
            $apbdes = Apbdes::create([
                'tahun' => $tahun,
                'tipe_anggaran' => 'PEMBIAYAAN',
                'kode_rekening' => $data['kode'],
                'nama_kegiatan' => $data['nama'],
                'pagu_anggaran' => $data['pagu'],
                'sumber_dana' => $data['sumber'],
                'is_published' => true,
            ]);

            $this->createRealisasi($apbdes, $tahun, 1.0);
        }
    }

    private function createRealisasi($apbdes, $tahun, $ratio)
    {
        $totalRealisasi = $apbdes->pagu_anggaran * $ratio;

        // Split into 1 to 4 transactions
        $numTransactions = rand(1, 4);

        $currentDate = Carbon::create($tahun, 1, 15);
        $remaining = $totalRealisasi;

        for ($i = 0; $i < $numTransactions; $i++) {
            $currentDate->addDays(rand(15, 60));
            if ($currentDate->year > $tahun) {
                $currentDate = Carbon::create($tahun, 12, 28);
            }

            if ($i === $numTransactions - 1) {
                $nominal = $remaining;
            } else {
                $nominal = $remaining / ($numTransactions - $i) * (rand(80, 120) / 100);
            }

            $remaining -= $nominal;

            ApbdesRealisasi::create([
                'apbdes_id' => $apbdes->id,
                'tanggal_transaksi' => clone $currentDate,
                'nominal' => $nominal,
                'catatan' => 'Realisasi ' . $apbdes->nama_kegiatan . ' Tahap ' . ($i + 1),
            ]);
        }
    }
}
