<?php

namespace Database\Seeders;

use App\Models\Warga;
use Illuminate\Database\Seeder;

class WargaSeeder extends Seeder
{
    /**
     * Seed sample warga data for development/testing.
     *
     * FIX GAP-06: NIK harus SAMA dengan data di PendudukSeeder
     * agar relasi Warga → Penduduk via NIK berfungsi.
     */
    public function run(): void
    {
        $wargaData = [
            [
                'nik'              => '3209123456780001', // = BUDI SANTOSO di PendudukSeeder
                'nama'             => 'Budi Santoso',
                'no_kk'            => '3209123456780000',
                'tempat_lahir'     => 'Ciamis',
                'tanggal_lahir'    => '1982-08-15',
                'jenis_kelamin'    => 'L',
                'agama'            => 'Islam',
                'pekerjaan'        => 'Wiraswasta',
                'status_perkawinan' => 'Kawin',
                'alamat'           => 'Jl. Raya Panumbangan No 45',
                'rt'               => '01',
                'rw'               => '02',
                'dusun'            => 'Kaler',
                'pin'              => '123456',
                'is_active'        => true,
            ],
            [
                'nik'              => '3209123456780002', // = SITI AMINAH di PendudukSeeder
                'nama'             => 'Siti Aminah',
                'no_kk'            => '3209123456780000',
                'tempat_lahir'     => 'Ciamis',
                'tanggal_lahir'    => '1988-03-22',
                'jenis_kelamin'    => 'P',
                'agama'            => 'Islam',
                'pekerjaan'        => 'Ibu Rumah Tangga',
                'status_perkawinan' => 'Kawin',
                'alamat'           => 'Jl. Raya Panumbangan No 45',
                'rt'               => '01',
                'rw'               => '02',
                'dusun'            => 'Kaler',
                'pin'              => '654321',
                'is_active'        => true,
            ],
            [
                'nik'              => '3209123456780010', // = IWAN SETIAWAN di PendudukSeeder
                'nama'             => 'Iwan Setiawan',
                'no_kk'            => '3209123456780009',
                'tempat_lahir'     => 'Tasikmalaya',
                'tanggal_lahir'    => '1975-05-20',
                'jenis_kelamin'    => 'L',
                'agama'            => 'Islam',
                'pekerjaan'        => 'PNS',
                'status_perkawinan' => 'Kawin',
                'alamat'           => 'Kp. Cibulan RT 03/05',
                'rt'               => '03',
                'rw'               => '05',
                'dusun'            => 'Kidul',
                'pin'              => '111111',
                'is_active'        => true,
            ],
            [
                'nik'              => '3209123456780020', // = LINA MARLINA di PendudukSeeder
                'nama'             => 'Lina Marlina',
                'no_kk'            => '3209123456780019',
                'tempat_lahir'     => 'Bandung',
                'tanggal_lahir'    => '1995-09-14',
                'jenis_kelamin'    => 'P',
                'agama'            => 'Islam',
                'pekerjaan'        => 'Guru',
                'status_perkawinan' => 'Cerai Hidup',
                'alamat'           => 'Kp. Sindang RT 02/01',
                'rt'               => '02',
                'rw'               => '01',
                'dusun'            => 'Kaler',
                'pin'              => '222222',
                'is_active'        => true,
            ],
            [
                'nik'              => '3209123456780030', // = ASEP KURNIAWAN di PendudukSeeder
                'nama'             => 'Asep Kurniawan',
                'no_kk'            => '3209123456780029',
                'tempat_lahir'     => 'Ciamis',
                'tanggal_lahir'    => '2000-01-30',
                'jenis_kelamin'    => 'L',
                'agama'            => 'Islam',
                'pekerjaan'        => 'Freelancer',
                'status_perkawinan' => 'Belum Kawin',
                'alamat'           => 'Kp. Margaluyu RT 04/02',
                'rt'               => '04',
                'rw'               => '02',
                'dusun'            => 'Tengah',
                'pin'              => '333333',
                'is_active'        => false, // Non-active account for testing
            ],
        ];

        foreach ($wargaData as $data) {
            Warga::updateOrCreate(
                ['nik' => $data['nik']],
                $data
            );
        }
    }
}
