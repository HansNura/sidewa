<?php

namespace Database\Seeders;

use App\Models\BukuTamu;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BukuTamuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instansis = [null, null, 'Warga Dusun I', 'Warga Dusun II', 'Dinas Sosial Ciamis', 'Kecamatan', 'Puskesmas', 'Polsek', 'Warga Luar Desa'];
        $kategoris = ['Layanan Surat', 'Layanan Surat', 'Koordinasi', 'Lain-lain'];
        
        // Generate visitors for the last 3 months
        for ($i = 0; $i < 200; $i++) {
            $daysAgo = rand(0, 90);
            $date = Carbon::now()->subDays($daysAgo);
            
            // Random time between 08:00 and 15:00
            $date->setHour(rand(8, 15))->setMinute(rand(0, 59));
            
            $kategori = $kategoris[array_rand($kategoris)];
            $isLayanan = $kategori === 'Layanan Surat';
            
            BukuTamu::create([
                'nama_tamu'       => 'Tamu Dummy ' . ($i + 1),
                'instansi'        => $instansis[array_rand($instansis)],
                'tujuan_kategori' => $kategori,
                'keperluan'       => $isLayanan ? 'Mengurus Pembuatan SKTM/Pengantar' : 'Koordinasi kelembagaan',
                'metode_input'    => rand(1, 100) > 30 ? 'kiosk' : 'manual', // 70% kiosk
                'status'          => rand(1, 100) > 5 ? 'selesai' : 'menunggu',
                'waktu_masuk'     => $date,
                'waktu_keluar'    => (clone $date)->addMinutes(rand(15, 120)),
                'created_at'      => $date,
                'updated_at'      => $date,
            ]);
        }
    }
}
