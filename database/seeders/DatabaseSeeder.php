<?php

namespace Database\Seeders;

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
        User::factory()->create([
            'name'     => 'Admin Desa',
            'email'    => 'admin@desa.go.id',
            'nik'      => '3201234567890001',
            'role'     => 'administrator',
            'password' => 'password',
        ]);

        User::factory()->create([
            'name'     => 'Operator Desa',
            'email'    => 'operator@desa.go.id',
            'nik'      => '3201234567890002',
            'role'     => 'operator',
            'password' => 'password',
        ]);

        User::factory()->create([
            'name'     => 'Kepala Desa',
            'email'    => 'kades@desa.go.id',
            'nik'      => '3201234567890003',
            'role'     => 'kades',
            'password' => 'password',
        ]);

        User::factory()->create([
            'name'     => 'Perangkat Desa',
            'email'    => 'perangkat@desa.go.id',
            'nik'      => '3201234567890004',
            'role'     => 'perangkat',
            'password' => 'password',
        ]);

        User::factory()->create([
            'name'     => 'Resepsionis Desa',
            'email'    => 'resepsionis@desa.go.id',
            'nik'      => '3201234567890005',
            'role'     => 'resepsionis',
            'password' => 'password',
        ]);

        // ─── Test Warga (citizens) ─────────────────────────────────
        $this->call(WargaSeeder::class);
    }
}
