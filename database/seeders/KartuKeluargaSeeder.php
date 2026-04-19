<?php

namespace Database\Seeders;

use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use Illuminate\Database\Seeder;

class KartuKeluargaSeeder extends Seeder
{
    public function run(): void
    {
        $families = [
            [
                'no_kk' => '3209123456780000',
                'alamat' => 'Jl. Raya Panumbangan No 45',
                'dusun' => 'Kaler',
                'rt' => '01',
                'rw' => '02',
                'tanggal_dikeluarkan' => '2024-01-12',
            ],
            [
                'no_kk' => '3209123456780009',
                'alamat' => 'Kp. Cibulan RT 03/05',
                'dusun' => 'Kidul',
                'rt' => '03',
                'rw' => '05',
                'tanggal_dikeluarkan' => '2025-03-05',
            ],
            [
                'no_kk' => '3209123456780019',
                'alamat' => 'Kp. Sindang RT 02/01',
                'dusun' => 'Kaler',
                'rt' => '02',
                'rw' => '01',
                'tanggal_dikeluarkan' => '2024-06-20',
            ],
            [
                'no_kk' => '3209123456780029',
                'alamat' => 'Kp. Margaluyu RT 04/02',
                'dusun' => 'Tengah',
                'rt' => '04',
                'rw' => '02',
                'tanggal_dikeluarkan' => '2025-08-10',
            ],
            [
                'no_kk' => '3209123456780049',
                'alamat' => 'Kp. Sukarahayu RT 01/03',
                'dusun' => 'Tengah',
                'rt' => '01',
                'rw' => '03',
                'tanggal_dikeluarkan' => '2023-11-15',
            ],
            [
                'no_kk' => '3209123456780055',
                'alamat' => 'Kp. Pasir Panjang',
                'dusun' => 'Kidul',
                'rt' => '05',
                'rw' => '03',
                'tanggal_dikeluarkan' => '2022-04-01',
            ],
        ];

        foreach ($families as $data) {
            $kk = KartuKeluarga::create($data);

            // Link all penduduk with matching no_kk
            Penduduk::where('no_kk', $kk->no_kk)
                ->update(['kartu_keluarga_id' => $kk->id]);
        }
    }
}
