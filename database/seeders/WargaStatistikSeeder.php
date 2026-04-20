<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warga;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class WargaStatistikSeeder extends Seeder
{
    public function run()
    {
        // Don't run if we already have thousands of wargas, keeping it simple
        if (Warga::count() > 100) {
            return;
        }

        $dusuns = ['Kaler', 'Kidul', 'Wetan'];
        $pekerjaans = ['Petani / Buruh Tani', 'Wiraswasta', 'Pegawai Swasta', 'PNS/TNI/Polri', 'Pekerja Lepas', 'Pelajar/Mahasiswa', 'Mengurus Rumah Tangga'];
        $pendidikans = ['Belum Sekolah', 'SD/Sederajat', 'SMP/Sederajat', 'SMA/SMK', 'Sarjana/Diploma'];

        for ($i = 0; $i < 500; $i++) {
            $tahunLahir = rand(1950, 2025);
            $bulanLahir = rand(1, 12);
            $hariLahir = rand(1, 28);

            $umur = date('Y') - $tahunLahir;
            $isStunting = false;
            $pendidikan = 'SMA/SMK'; // Default
            
            if ($umur < 5) {
                // Balita have chance of stunting
                $isStunting = rand(1, 100) <= 5; // 5% stunting
                $pendidikan = 'Belum Sekolah';
            } elseif ($umur >= 5 && $umur < 12) {
                $pendidikan = 'SD/Sederajat';
            } elseif ($umur >= 12 && $umur < 15) {
                $pendidikan = 'SMP/Sederajat';
            } elseif ($umur > 25) {
                $pendidikan = $pendidikans[array_rand($pendidikans)]; // random for adults
            }

            Warga::create([
                'nik' => '3201'.rand(100000000000, 999999999999), // 16 digit dummy
                'nama' => 'Warga ' . ($i + 1),
                'no_kk' => '3201'.rand(100000000000, 999999999999), // Group them somehow? We'll just randomize for now
                'tempat_lahir' => 'Sukakerta',
                'tanggal_lahir' => Carbon::createFromDate($tahunLahir, $bulanLahir, $hariLahir),
                'jenis_kelamin' => rand(0, 1) ? 'L' : 'P',
                'agama' => 'Islam',
                'pekerjaan' => $umur < 15 ? 'Pelajar/Mahasiswa' : $pekerjaans[array_rand($pekerjaans)],
                'status_perkawinan' => $umur < 20 ? 'Belum Kawin' : 'Kawin',
                'alamat' => 'Kampung Sindangmukti',
                'rt' => '00'.rand(1,5),
                'rw' => '00'.rand(1,3),
                'dusun' => $dusuns[array_rand($dusuns)],
                'pin' => Hash::make('123456'),
                'is_active' => true,
                'pendidikan_terakhir' => $pendidikan,
                'kesejahteraan' => (rand(1, 100) <= 15) ? 'pra-sejahtera' : 'sejahtera', // 15% pra sejahtera
                'is_stunting' => $isStunting,
                'created_at' => Carbon::today()->subDays(rand(1, 1500))
            ]);
        }
    }
}
