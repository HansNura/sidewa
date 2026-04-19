<?php

namespace Database\Seeders;

use App\Models\Penduduk;
use Illuminate\Database\Seeder;

class PendudukSeeder extends Seeder
{
    public function run(): void
    {
        $penduduk = [
            // KK 1 — Budi Santoso (Dusun Kaler)
            [
                'nik' => '3209123456780001', 'nama' => 'BUDI SANTOSO',
                'tempat_lahir' => 'Ciamis', 'tanggal_lahir' => '1982-08-15',
                'jenis_kelamin' => 'L', 'agama' => 'Islam', 'golongan_darah' => 'O',
                'no_kk' => '3209123456780000', 'status_hubungan' => 'Kepala Keluarga',
                'status_perkawinan' => 'Kawin', 'nama_ayah' => 'Suryana', 'nama_ibu' => 'Cicih',
                'pendidikan' => 'SMA/Sederajat', 'pekerjaan' => 'Wiraswasta',
                'alamat' => 'Jl. Raya Panumbangan No 45', 'dusun' => 'Kaler', 'rt' => '01', 'rw' => '02',
                'status' => 'hidup',
            ],
            [
                'nik' => '3209123456780002', 'nama' => 'SITI AMINAH',
                'tempat_lahir' => 'Ciamis', 'tanggal_lahir' => '1988-03-22',
                'jenis_kelamin' => 'P', 'agama' => 'Islam', 'golongan_darah' => 'B',
                'no_kk' => '3209123456780000', 'status_hubungan' => 'Istri',
                'status_perkawinan' => 'Kawin', 'nama_ayah' => 'Darto', 'nama_ibu' => 'Imas',
                'pendidikan' => 'SMA/Sederajat', 'pekerjaan' => 'Ibu Rumah Tangga',
                'alamat' => 'Jl. Raya Panumbangan No 45', 'dusun' => 'Kaler', 'rt' => '01', 'rw' => '02',
                'status' => 'hidup',
            ],
            [
                'nik' => '3209123456780003', 'nama' => 'DANI FIRMANSYAH',
                'tempat_lahir' => 'Ciamis', 'tanggal_lahir' => '2008-11-10',
                'jenis_kelamin' => 'L', 'agama' => 'Islam', 'golongan_darah' => 'O',
                'no_kk' => '3209123456780000', 'status_hubungan' => 'Anak',
                'status_perkawinan' => 'Belum Kawin', 'nama_ayah' => 'Budi Santoso', 'nama_ibu' => 'Siti Aminah',
                'pendidikan' => 'SMP/Sederajat', 'pekerjaan' => 'Pelajar/Mahasiswa',
                'alamat' => 'Jl. Raya Panumbangan No 45', 'dusun' => 'Kaler', 'rt' => '01', 'rw' => '02',
                'status' => 'hidup',
            ],

            // KK 2 — Keluarga Iwan (Dusun Kidul)
            [
                'nik' => '3209123456780010', 'nama' => 'IWAN SETIAWAN',
                'tempat_lahir' => 'Tasikmalaya', 'tanggal_lahir' => '1975-05-20',
                'jenis_kelamin' => 'L', 'agama' => 'Islam', 'golongan_darah' => 'A',
                'no_kk' => '3209123456780009', 'status_hubungan' => 'Kepala Keluarga',
                'status_perkawinan' => 'Kawin', 'nama_ayah' => 'Karna', 'nama_ibu' => 'Enok',
                'pendidikan' => 'S1/D4', 'pekerjaan' => 'PNS',
                'alamat' => 'Kp. Cibulan RT 03/05', 'dusun' => 'Kidul', 'rt' => '03', 'rw' => '05',
                'status' => 'hidup',
            ],
            [
                'nik' => '3209123456780011', 'nama' => 'NENG LESTARI',
                'tempat_lahir' => 'Ciamis', 'tanggal_lahir' => '1980-12-01',
                'jenis_kelamin' => 'P', 'agama' => 'Islam', 'golongan_darah' => 'AB',
                'no_kk' => '3209123456780009', 'status_hubungan' => 'Istri',
                'status_perkawinan' => 'Kawin', 'nama_ayah' => 'Ahmad', 'nama_ibu' => 'Iyah',
                'pendidikan' => 'D1-D3', 'pekerjaan' => 'Bidan',
                'alamat' => 'Kp. Cibulan RT 03/05', 'dusun' => 'Kidul', 'rt' => '03', 'rw' => '05',
                'status' => 'hidup',
            ],

            // KK 3 — Meninggal
            [
                'nik' => '3209123456780099', 'nama' => 'UJANG KARNA',
                'tempat_lahir' => 'Ciamis', 'tanggal_lahir' => '1960-06-12',
                'jenis_kelamin' => 'L', 'agama' => 'Islam', 'golongan_darah' => 'O',
                'no_kk' => '3209123456780055', 'status_hubungan' => 'Kepala Keluarga',
                'status_perkawinan' => 'Kawin', 'nama_ayah' => 'Taruna', 'nama_ibu' => 'Anih',
                'pendidikan' => 'SD/Sederajat', 'pekerjaan' => 'Petani',
                'alamat' => 'Kp. Pasir Panjang', 'dusun' => 'Kidul', 'rt' => '05', 'rw' => '03',
                'status' => 'meninggal',
            ],

            // More diverse data
            [
                'nik' => '3209123456780020', 'nama' => 'LINA MARLINA',
                'tempat_lahir' => 'Bandung', 'tanggal_lahir' => '1995-09-14',
                'jenis_kelamin' => 'P', 'agama' => 'Islam', 'golongan_darah' => 'A',
                'no_kk' => '3209123456780019', 'status_hubungan' => 'Kepala Keluarga',
                'status_perkawinan' => 'Cerai Hidup', 'nama_ayah' => 'Supardi', 'nama_ibu' => 'Yanti',
                'pendidikan' => 'S1/D4', 'pekerjaan' => 'Guru',
                'alamat' => 'Kp. Sindang RT 02/01', 'dusun' => 'Kaler', 'rt' => '02', 'rw' => '01',
                'status' => 'hidup',
            ],
            [
                'nik' => '3209123456780030', 'nama' => 'ASEP KURNIAWAN',
                'tempat_lahir' => 'Ciamis', 'tanggal_lahir' => '2000-01-30',
                'jenis_kelamin' => 'L', 'agama' => 'Islam', 'golongan_darah' => 'B',
                'no_kk' => '3209123456780029', 'status_hubungan' => 'Anak',
                'status_perkawinan' => 'Belum Kawin', 'nama_ayah' => 'Eman', 'nama_ibu' => 'Teti',
                'pendidikan' => 'S1/D4', 'pekerjaan' => 'Freelancer',
                'alamat' => 'Kp. Margaluyu RT 04/02', 'dusun' => 'Tengah', 'rt' => '04', 'rw' => '02',
                'status' => 'hidup',
            ],
            [
                'nik' => '3209123456780040', 'nama' => 'DEWI SARTIKA',
                'tempat_lahir' => 'Ciamis', 'tanggal_lahir' => '2020-07-05',
                'jenis_kelamin' => 'P', 'agama' => 'Islam', 'golongan_darah' => 'O',
                'no_kk' => '3209123456780000', 'status_hubungan' => 'Anak',
                'status_perkawinan' => 'Belum Kawin', 'nama_ayah' => 'Budi Santoso', 'nama_ibu' => 'Siti Aminah',
                'pendidikan' => 'Tidak/Belum Sekolah', 'pekerjaan' => null,
                'alamat' => 'Jl. Raya Panumbangan No 45', 'dusun' => 'Kaler', 'rt' => '01', 'rw' => '02',
                'status' => 'hidup',
            ],
            [
                'nik' => '3209123456780050', 'nama' => 'HAJI Ahmad SYARIF',
                'tempat_lahir' => 'Ciamis', 'tanggal_lahir' => '1955-02-28',
                'jenis_kelamin' => 'L', 'agama' => 'Islam', 'golongan_darah' => 'A',
                'no_kk' => '3209123456780049', 'status_hubungan' => 'Kepala Keluarga',
                'status_perkawinan' => 'Kawin', 'nama_ayah' => 'Muhammad', 'nama_ibu' => 'Halimah',
                'pendidikan' => 'SMP/Sederajat', 'pekerjaan' => 'Pensiunan',
                'alamat' => 'Kp. Sukarahayu RT 01/03', 'dusun' => 'Tengah', 'rt' => '01', 'rw' => '03',
                'status' => 'hidup',
            ],
        ];

        foreach ($penduduk as $data) {
            Penduduk::create($data);
        }
    }
}
