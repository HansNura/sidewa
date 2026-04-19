<?php

namespace Database\Seeders;

use App\Models\IntervensiStunting;
use App\Models\Penduduk;
use App\Models\PengukuranBalita;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KesehatanSeeder extends Seeder
{
    public function run(): void
    {
        $pendudukList = Penduduk::where('status', 'hidup')->get();

        if ($pendudukList->isEmpty()) return;

        // Generate measurement history for each penduduk (treating as children for demo)
        $statusOptions = ['normal', 'normal', 'normal', 'normal', 'pendek', 'sangat_pendek', 'tinggi'];
        $now = Carbon::now();

        foreach ($pendudukList as $idx => $penduduk) {
            $birthDate = Carbon::parse($penduduk->tanggal_lahir);
            $ageMonths = max(6, $birthDate->diffInMonths($now));
            if ($ageMonths > 59) $ageMonths = rand(12, 48); // Cap for demo

            // Create 3 months of measurement history
            for ($m = 0; $m < 3; $m++) {
                $measureDate = $now->copy()->subMonths($m);
                $currentAge = max(1, $ageMonths - $m);

                // Simulate height/weight growth
                $baseHeight = 50 + ($currentAge * 1.5) + rand(-5, 5);
                $baseWeight = 3 + ($currentAge * 0.3) + (rand(-10, 10) / 10);

                $status = $statusOptions[array_rand($statusOptions)];

                // Make some children consistently stunting for realism
                if ($idx % 5 === 0) $status = 'pendek';
                if ($idx % 8 === 0) $status = 'sangat_pendek';

                PengukuranBalita::create([
                    'penduduk_id'        => $penduduk->id,
                    'tanggal_pengukuran' => $measureDate->format('Y-m-d'),
                    'umur_bulan'         => $currentAge,
                    'tinggi_badan'       => round($baseHeight, 1),
                    'berat_badan'        => round(max(2, $baseWeight), 1),
                    'status_gizi'        => $status,
                    'nama_ortu'          => 'Ortu ' . $penduduk->nama,
                ]);
            }
        }

        // Intervention programs
        IntervensiStunting::create([
            'nama'              => 'Pemberian Makanan Tambahan (PMT) Dusun Kaler',
            'deskripsi'         => 'Intervensi gizi untuk anak-anak stunting di wilayah Dusun Kaler.',
            'status'            => 'berjalan',
            'target_peserta'    => 12,
            'peserta_terdaftar' => 9,
            'progres'           => 75,
            'tanggal_mulai'     => $now->copy()->subWeeks(3)->format('Y-m-d'),
        ]);

        IntervensiStunting::create([
            'nama'              => 'Edukasi Gizi Ibu Hamil & Menyusui',
            'deskripsi'         => 'Kolaborasi dengan Puskesmas Kecamatan untuk edukasi nutrisi.',
            'status'            => 'terjadwal',
            'target_peserta'    => 50,
            'peserta_terdaftar' => 20,
            'progres'           => 40,
            'tanggal_mulai'     => $now->copy()->addWeeks(2)->format('Y-m-d'),
        ]);

        IntervensiStunting::create([
            'nama'              => 'Suplementasi Vitamin A & Zat Besi',
            'deskripsi'         => 'Program pemberian suplemen untuk balita usia 6-59 bulan.',
            'status'            => 'selesai',
            'target_peserta'    => 30,
            'peserta_terdaftar' => 30,
            'progres'           => 100,
            'tanggal_mulai'     => $now->copy()->subMonths(2)->format('Y-m-d'),
            'tanggal_selesai'   => $now->copy()->subWeeks(1)->format('Y-m-d'),
        ]);
    }
}
