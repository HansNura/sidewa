<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PresensiPegawai;
use App\Models\User;
use Carbon\Carbon;

class PresensiSeeder extends Seeder
{
    public function run()
    {
        // Don't run if we already have records
        if (PresensiPegawai::count() > 0) {
            return;
        }

        $users = User::whereNotNull('nip')->get();
        if ($users->isEmpty()) {
            return; // No employees to seed
        }

        $startDate = Carbon::create(2026, 4, 1);
        $endDate = Carbon::create(2026, 4, 30);
        
        $statuses = ['hadir', 'terlambat', 'izin', 'sakit', 'dinas', 'alpha'];
        $metode = ['kiosk', 'kiosk', 'manual'];

        while ($startDate <= $endDate) {
            // Skip weekends
            if ($startDate->isWeekend()) {
                $startDate->addDay();
                continue;
            }

            foreach ($users as $user) {
                // Determine a random status favoring 'hadir'
                $rand = rand(1, 100);
                if ($rand <= 80) {
                    $status = 'hadir';
                    $waktu_masuk = "07:" . str_pad(rand(30, 59), 2, '0', STR_PAD_LEFT) . ":00";
                } elseif ($rand <= 90) {
                    $status = 'terlambat';
                    $waktu_masuk = "08:" . str_pad(rand(1, 45), 2, '0', STR_PAD_LEFT) . ":00";
                } elseif ($rand <= 93) {
                    $status = 'izin';
                    $waktu_masuk = null;
                } elseif ($rand <= 96) {
                    $status = 'sakit';
                    $waktu_masuk = null;
                } elseif ($rand <= 98) {
                    $status = 'dinas';
                    $waktu_masuk = null; // Maybe checkin somewhere else
                } else {
                    $status = 'alpha';
                    $waktu_masuk = null;
                }

                $waktu_pulang = null;
                if (in_array($status, ['hadir', 'terlambat'])) {
                    $waktu_pulang = "16:" . str_pad(rand(0, 30), 2, '0', STR_PAD_LEFT) . ":00";
                }

                PresensiPegawai::create([
                    'user_id' => $user->id,
                    'tanggal' => $startDate->format('Y-m-d'),
                    'waktu_masuk' => $waktu_masuk,
                    'waktu_pulang' => $waktu_pulang,
                    'status' => $status,
                    'metode_masuk' => $waktu_masuk ? $metode[array_rand($metode)] : null,
                    'metode_pulang' => $waktu_pulang ? $metode[array_rand($metode)] : null,
                    'catatan' => in_array($status, ['izin', 'sakit']) ? 'Keterangan dilampirkan via WA' : null,
                ]);
            }
            $startDate->addDay();
        }
    }
}
