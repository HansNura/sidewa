<?php

namespace Database\Seeders;

use App\Models\Wilayah;
use Illuminate\Database\Seeder;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Dusun Kaler ─────────────────────────────────
        $kaler = Wilayah::create([
            'tipe' => 'dusun',
            'nama' => 'Kaler',
            'kepala_nama' => 'Suryana S.AP',
            'kepala_jabatan' => 'Kadus Kaler',
            'kepala_telepon' => '0812-3456-7890',
        ]);

        $rw01Kaler = Wilayah::create([
            'tipe' => 'rw', 'nama' => '01', 'parent_id' => $kaler->id,
            'kepala_nama' => 'Drs. Rahmat', 'kepala_jabatan' => 'Ketua RW',
        ]);

        Wilayah::create(['tipe' => 'rt', 'nama' => '01', 'parent_id' => $rw01Kaler->id,
            'kepala_nama' => 'Agus Mulyadi', 'kepala_jabatan' => 'Ketua RT']);
        Wilayah::create(['tipe' => 'rt', 'nama' => '02', 'parent_id' => $rw01Kaler->id,
            'kepala_nama' => 'Deden Suryadi', 'kepala_jabatan' => 'Ketua RT']);

        $rw02Kaler = Wilayah::create([
            'tipe' => 'rw', 'nama' => '02', 'parent_id' => $kaler->id,
            'kepala_nama' => 'H. Endang', 'kepala_jabatan' => 'Ketua RW',
        ]);

        // ─── Dusun Kidul ─────────────────────────────────
        $kidul = Wilayah::create([
            'tipe' => 'dusun',
            'nama' => 'Kidul',
            'kepala_nama' => 'Ahmad Hidayat',
            'kepala_jabatan' => 'Kadus Kidul',
            'kepala_telepon' => '0813-5678-1234',
        ]);

        $rw03Kidul = Wilayah::create([
            'tipe' => 'rw', 'nama' => '03', 'parent_id' => $kidul->id,
            'kepala_nama' => 'Taufik Ismail', 'kepala_jabatan' => 'Ketua RW',
        ]);

        Wilayah::create(['tipe' => 'rt', 'nama' => '03', 'parent_id' => $rw03Kidul->id]);
        Wilayah::create(['tipe' => 'rt', 'nama' => '05', 'parent_id' => $rw03Kidul->id,
            'kepala_nama' => 'Iwan Gunawan', 'kepala_jabatan' => 'Ketua RT']);

        $rw05Kidul = Wilayah::create([
            'tipe' => 'rw', 'nama' => '05', 'parent_id' => $kidul->id,
            'kepala_nama' => 'Neng Sukaesih', 'kepala_jabatan' => 'Ketua RW',
        ]);

        Wilayah::create(['tipe' => 'rt', 'nama' => '03', 'parent_id' => $rw05Kidul->id,
            'kepala_nama' => 'Cecep Hermawan', 'kepala_jabatan' => 'Ketua RT']);

        // ─── Dusun Tengah ────────────────────────────────
        $tengah = Wilayah::create([
            'tipe' => 'dusun',
            'nama' => 'Tengah',
            'kepala_nama' => 'Haji Saepudin',
            'kepala_jabatan' => 'Kadus Tengah',
            'kepala_telepon' => '0815-7890-4567',
        ]);

        $rw01Tengah = Wilayah::create([
            'tipe' => 'rw', 'nama' => '01', 'parent_id' => $tengah->id,
        ]);

        Wilayah::create(['tipe' => 'rt', 'nama' => '01', 'parent_id' => $rw01Tengah->id]);

        $rw02Tengah = Wilayah::create([
            'tipe' => 'rw', 'nama' => '02', 'parent_id' => $tengah->id,
        ]);

        Wilayah::create(['tipe' => 'rt', 'nama' => '04', 'parent_id' => $rw02Tengah->id]);
    }
}
