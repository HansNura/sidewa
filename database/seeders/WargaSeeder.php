<?php

namespace Database\Seeders;

use App\Models\Warga;
use Illuminate\Database\Seeder;

class WargaSeeder extends Seeder
{
    /**
     * Seed sample warga data for development/testing.
     */
    public function run(): void
    {
        $wargaData = [
            [
                'nik'              => '3207261234560001',
                'nama'             => 'Ahmad Supriyadi',
                'no_kk'            => '3207261234560000',
                'tempat_lahir'     => 'Ciamis',
                'tanggal_lahir'    => '1985-03-15',
                'jenis_kelamin'    => 'L',
                'agama'            => 'Islam',
                'pekerjaan'        => 'Petani',
                'status_perkawinan' => 'Kawin',
                'alamat'           => 'Jl. Raya Sindangmukti No. 10',
                'rt'               => '01',
                'rw'               => '03',
                'dusun'            => 'Cibunar',
                'pin'              => '123456',
                'is_active'        => true,
            ],
            [
                'nik'              => '3207261234560002',
                'nama'             => 'Siti Nurhaliza',
                'no_kk'            => '3207261234560000',
                'tempat_lahir'     => 'Ciamis',
                'tanggal_lahir'    => '1990-07-22',
                'jenis_kelamin'    => 'P',
                'agama'            => 'Islam',
                'pekerjaan'        => 'Ibu Rumah Tangga',
                'status_perkawinan' => 'Kawin',
                'alamat'           => 'Jl. Raya Sindangmukti No. 10',
                'rt'               => '01',
                'rw'               => '03',
                'dusun'            => 'Cibunar',
                'pin'              => '654321',
                'is_active'        => true,
            ],
            [
                'nik'              => '3207261234560003',
                'nama'             => 'Budi Santoso',
                'no_kk'            => '3207261234560010',
                'tempat_lahir'     => 'Bandung',
                'tanggal_lahir'    => '1978-11-05',
                'jenis_kelamin'    => 'L',
                'agama'            => 'Islam',
                'pekerjaan'        => 'Pedagang',
                'status_perkawinan' => 'Kawin',
                'alamat'           => 'Kp. Babakan No. 5',
                'rt'               => '02',
                'rw'               => '01',
                'dusun'            => 'Sukamanah',
                'pin'              => '111111',
                'is_active'        => true,
            ],
            [
                'nik'              => '3207261234560004',
                'nama'             => 'Dewi Ratnasari',
                'no_kk'            => '3207261234560020',
                'tempat_lahir'     => 'Tasikmalaya',
                'tanggal_lahir'    => '1995-01-30',
                'jenis_kelamin'    => 'P',
                'agama'            => 'Islam',
                'pekerjaan'        => 'Guru',
                'status_perkawinan' => 'Belum Kawin',
                'alamat'           => 'Kp. Pasirkuda No. 12',
                'rt'               => '04',
                'rw'               => '02',
                'dusun'            => 'Sukamanah',
                'pin'              => '222222',
                'is_active'        => true,
            ],
            [
                'nik'              => '3207261234560005',
                'nama'             => 'Ujang Heryanto',
                'no_kk'            => '3207261234560030',
                'tempat_lahir'     => 'Ciamis',
                'tanggal_lahir'    => '1965-06-18',
                'jenis_kelamin'    => 'L',
                'agama'            => 'Islam',
                'pekerjaan'        => 'Wiraswasta',
                'status_perkawinan' => 'Kawin',
                'alamat'           => 'Kp. Neglasari No. 3',
                'rt'               => '03',
                'rw'               => '04',
                'dusun'            => 'Cibunar',
                'pin'              => '333333',
                'is_active'        => false, // Non-active account for testing
            ],
        ];

        foreach ($wargaData as $data) {
            Warga::create($data);
        }
    }
}
