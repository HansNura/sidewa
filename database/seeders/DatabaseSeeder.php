<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ─── Test Users (one per role) ─────────────────────────────
        $admin = User::factory()->create([
            'name'          => 'Admin Desa',
            'email'         => 'admin@desa.go.id',
            'nik'           => '3201234567890001',
            'role'          => 'administrator',
            'is_active'     => true,
            'last_login_at' => now()->subMinutes(30),
            'last_login_ip' => '192.168.1.1',
            'password'      => 'password',
        ]);

        $operator = User::factory()->create([
            'name'          => 'Asep Kasi Pelayanan',
            'email'         => 'operator@desa.go.id',
            'nik'           => '3201234567890002',
            'role'          => 'operator',
            'is_active'     => true,
            'last_login_at' => now()->subHours(2),
            'last_login_ip' => '192.168.1.12',
            'password'      => 'password',
        ]);

        $kades = User::factory()->create([
            'name'          => 'Bpk. Tateng',
            'email'         => 'kades@desa.go.id',
            'nik'           => '3201234567890003',
            'role'          => 'kades',
            'is_active'     => true,
            'last_login_at' => now()->subHours(1),
            'last_login_ip' => '192.168.1.5',
            'password'      => 'password',
        ]);

        $perangkat = User::factory()->create([
            'name'          => 'Dedi Perangkat',
            'email'         => 'perangkat@desa.go.id',
            'nik'           => '3201234567890004',
            'role'          => 'perangkat',
            'is_active'     => true,
            'last_login_at' => now()->subDays(1),
            'last_login_ip' => '192.168.1.8',
            'password'      => 'password',
        ]);

        $resepsionis = User::factory()->create([
            'name'          => 'Siti Resepsionis',
            'email'         => 'resepsionis@desa.go.id',
            'nik'           => '3201234567890005',
            'role'          => 'resepsionis',
            'is_active'     => true,
            'last_login_at' => now()->subHours(3),
            'last_login_ip' => '192.168.1.20',
            'password'      => 'password',
        ]);

        // ─── Additional Users for realistic table ──────────────────
        $nani = User::factory()->create([
            'name'          => 'Nani Staff',
            'email'         => 'nani@desa.go.id',
            'nik'           => '3201234567890006',
            'role'          => 'operator',
            'is_active'     => false,
            'last_login_at' => now()->subMonths(3),
            'last_login_ip' => '192.168.1.15',
            'password'      => 'password',
        ]);

        User::factory()->create([
            'name'          => 'Rina Bendahara',
            'email'         => 'rina@desa.go.id',
            'nik'           => '3201234567890007',
            'role'          => 'perangkat',
            'is_active'     => true,
            'last_login_at' => now()->subDays(2),
            'last_login_ip' => '192.168.1.22',
            'password'      => 'password',
        ]);

        User::factory()->create([
            'name'          => 'Ujang Staf Umum',
            'email'         => 'ujang@desa.go.id',
            'nik'           => '3201234567890008',
            'role'          => 'operator',
            'is_active'     => true,
            'last_login_at' => now()->subDays(5),
            'last_login_ip' => '192.168.1.30',
            'password'      => 'password',
        ]);

        User::factory()->create([
            'name'          => 'Yuli Arsiparis',
            'email'         => 'yuli@desa.go.id',
            'nik'           => '3201234567890009',
            'role'          => 'operator',
            'is_active'     => false,
            'last_login_at' => null,
            'password'      => 'password',
        ]);

        User::factory()->create([
            'name'          => 'Bambang IT Support',
            'email'         => 'bambang@desa.go.id',
            'nik'           => '3201234567890010',
            'role'          => 'administrator',
            'is_active'     => true,
            'last_login_at' => now()->subMinutes(10),
            'last_login_ip' => '192.168.1.2',
            'password'      => 'password',
        ]);

        // ─── Activity Logs ─────────────────────────────────────────
        // Admin activities
        ActivityLog::create([
            'user_id'     => $admin->id,
            'action'      => 'login',
            'description' => 'Login ke sistem',
            'ip_address'  => '192.168.1.1',
            'created_at'  => now()->subMinutes(30),
        ]);
        ActivityLog::create([
            'user_id'     => $admin->id,
            'action'      => 'create_user',
            'description' => 'Menambahkan user baru: Nani Staff',
            'ip_address'  => '192.168.1.1',
            'metadata'    => ['target_user_id' => $nani->id],
            'created_at'  => now()->subDays(90),
        ]);

        // Operator activities
        ActivityLog::create([
            'user_id'     => $operator->id,
            'action'      => 'login',
            'description' => 'Login ke sistem',
            'ip_address'  => '192.168.1.12',
            'created_at'  => now()->subHours(2),
        ]);
        ActivityLog::create([
            'user_id'     => $operator->id,
            'action'      => 'create_kk',
            'description' => 'Menambah Data Keluarga: KK baru atas nama Budi Santoso',
            'ip_address'  => '192.168.1.12',
            'created_at'  => now()->subHours(1),
        ]);
        ActivityLog::create([
            'user_id'     => $operator->id,
            'action'      => 'print_surat',
            'description' => 'Mencetak Surat SKTM dengan Nomor #470/12/2026',
            'ip_address'  => '192.168.1.12',
            'created_at'  => now()->subMinutes(45),
        ]);

        // Kades activities
        ActivityLog::create([
            'user_id'     => $kades->id,
            'action'      => 'login',
            'description' => 'Login ke sistem',
            'ip_address'  => '192.168.1.5',
            'created_at'  => now()->subHours(1),
        ]);
        ActivityLog::create([
            'user_id'     => $kades->id,
            'action'      => 'verify_surat',
            'description' => 'Memverifikasi & TTE Surat Keterangan Domisili',
            'ip_address'  => '192.168.1.5',
            'created_at'  => now()->subMinutes(50),
        ]);

        // ─── Test Warga (citizens) ─────────────────────────────────
        $this->call(WargaSeeder::class);
    }
}
