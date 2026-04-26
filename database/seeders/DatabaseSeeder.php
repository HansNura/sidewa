<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Module;
use App\Models\Role;
use App\Models\RoleModulePermission;
use App\Models\SystemConfig;
use App\Models\User;
use App\Models\VillageSetting;
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

        // ─── Roles Metadata ────────────────────────────────────────
        $roleAdmin = Role::create([
            'slug'         => 'administrator',
            'display_name' => 'Administrator',
            'description'  => 'Memiliki akses penuh ke seluruh fitur dan pengaturan sistem (Super User).',
            'icon'         => 'fa-solid fa-user-shield',
            'color'        => 'blue',
            'is_system'    => true,
        ]);

        $roleKades = Role::create([
            'slug'         => 'kades',
            'display_name' => 'Kepala Desa',
            'description'  => 'Hak akses eksekutif untuk melihat laporan, dashboard, dan TTE Surat.',
            'icon'         => 'fa-solid fa-user-tie',
            'color'        => 'emerald',
            'is_system'    => false,
        ]);

        $roleOperator = Role::create([
            'slug'         => 'operator',
            'display_name' => 'Operator / Kasi',
            'description'  => 'Akses operasional harian seperti cetak surat, input penduduk, dan buku tamu.',
            'icon'         => 'fa-solid fa-desktop',
            'color'        => 'amber',
            'is_system'    => false,
        ]);

        $rolePerangkat = Role::create([
            'slug'         => 'perangkat',
            'display_name' => 'Perangkat Desa',
            'description'  => 'Akses terbatas untuk perangkat desa sesuai tugas pokok dan fungsi.',
            'icon'         => 'fa-solid fa-id-badge',
            'color'        => 'teal',
            'is_system'    => false,
        ]);

        $roleResepsionis = Role::create([
            'slug'         => 'resepsionis',
            'display_name' => 'Resepsionis',
            'description'  => 'Akses untuk pencatatan tamu dan presensi.',
            'icon'         => 'fa-solid fa-bell-concierge',
            'color'        => 'pink',
            'is_system'    => false,
        ]);

        // ─── System Modules ────────────────────────────────────────
        $modPenduduk = Module::create([
            'name' => 'Data Kependudukan', 'slug' => 'data-kependudukan',
            'icon' => 'fa-solid fa-users', 'sort_order' => 1,
        ]);

        $modSurat = Module::create([
            'name' => 'Layanan Surat', 'slug' => 'layanan-surat',
            'icon' => 'fa-solid fa-envelope-open-text', 'sort_order' => 2,
        ]);

        $modPresensi = Module::create([
            'name' => 'Presensi & Buku Tamu', 'slug' => 'presensi-buku-tamu',
            'icon' => 'fa-solid fa-fingerprint', 'sort_order' => 3,
        ]);

        $modKeuangan = Module::create([
            'name' => 'Keuangan APBDes', 'slug' => 'keuangan-apbdes',
            'icon' => 'fa-solid fa-wallet', 'sort_order' => 4,
        ]);

        $modKonten = Module::create([
            'name' => 'Konten Publik (Web)', 'slug' => 'konten-publik',
            'icon' => 'fa-solid fa-globe', 'sort_order' => 5,
        ]);

        $modSystem = Module::create([
            'name' => 'Konfigurasi Sistem', 'slug' => 'konfigurasi-sistem',
            'icon' => 'fa-solid fa-server', 'sort_order' => 6, 'is_sensitive' => true,
        ]);

        // ─── Default Permissions ────────────────────────────────────
        // Admin: full access everywhere
        foreach ([$modPenduduk, $modSurat, $modPresensi, $modKeuangan, $modKonten, $modSystem] as $mod) {
            RoleModulePermission::create([
                'role_id' => $roleAdmin->id, 'module_id' => $mod->id,
                'can_view' => true, 'can_create' => true, 'can_edit' => true, 'can_delete' => true,
            ]);
        }

        // Operator: VCED on penduduk & surat (no delete), VCE on presensi, V on konten
        RoleModulePermission::create(['role_id' => $roleOperator->id, 'module_id' => $modPenduduk->id, 'can_view' => true, 'can_create' => true, 'can_edit' => true, 'can_delete' => false]);
        RoleModulePermission::create(['role_id' => $roleOperator->id, 'module_id' => $modSurat->id, 'can_view' => true, 'can_create' => true, 'can_edit' => true, 'can_delete' => false]);
        RoleModulePermission::create(['role_id' => $roleOperator->id, 'module_id' => $modPresensi->id, 'can_view' => true, 'can_create' => true, 'can_edit' => false, 'can_delete' => false]);
        RoleModulePermission::create(['role_id' => $roleOperator->id, 'module_id' => $modKonten->id, 'can_view' => true, 'can_create' => false, 'can_edit' => false, 'can_delete' => false]);

        // Kades: V on penduduk & keuangan, V+E(TTE) on surat
        RoleModulePermission::create(['role_id' => $roleKades->id, 'module_id' => $modPenduduk->id, 'can_view' => true, 'can_create' => false, 'can_edit' => false, 'can_delete' => false]);
        RoleModulePermission::create(['role_id' => $roleKades->id, 'module_id' => $modSurat->id, 'can_view' => true, 'can_create' => false, 'can_edit' => true, 'can_delete' => false]);
        RoleModulePermission::create(['role_id' => $roleKades->id, 'module_id' => $modKeuangan->id, 'can_view' => true, 'can_create' => false, 'can_edit' => false, 'can_delete' => false]);

        // Perangkat: V on penduduk
        RoleModulePermission::create(['role_id' => $rolePerangkat->id, 'module_id' => $modPenduduk->id, 'can_view' => true, 'can_create' => false, 'can_edit' => false, 'can_delete' => false]);

        // Resepsionis: VC on presensi
        RoleModulePermission::create(['role_id' => $roleResepsionis->id, 'module_id' => $modPresensi->id, 'can_view' => true, 'can_create' => true, 'can_edit' => false, 'can_delete' => false]);

        // ─── Village Settings ──────────────────────────────────────
        VillageSetting::create([
            'nama_desa'     => 'Sindangmukti',
            'kecamatan'     => 'Mangunjaya',
            'kabupaten'     => 'Pangandaran',
            'provinsi'      => 'Jawa Barat',
            'kode_pos'      => '46353',
            'alamat'        => 'Jl. Desa Sindangmukti',
            'email'         => 'pemdes@sindangmukti.desa.id',
            'telepon'       => '0265-654321',
            'website'       => 'www.sindangmukti.desa.id',
            'nama_kades'    => 'Bpk. Tateng',
            'nip_kades'     => '19700101 200012 1 001',
            'jabatan_kades' => 'Kepala Desa',
        ]);

        // ─── System Configuration ──────────────────────────────────
        foreach (SystemConfig::DEFAULTS as $key => $meta) {
            SystemConfig::create([
                'group' => $meta['group'],
                'key'   => $key,
                'value' => $meta['value'],
                'type'  => $meta['type'],
            ]);
        }

        // ─── Penduduk (Sample Data) ────────────────────────────────
        $this->call(PendudukSeeder::class);

        // ─── Kartu Keluarga (Links penduduk → KK) ─────────────────
        $this->call(KartuKeluargaSeeder::class);

        // ─── Wilayah Administratif (Dusun → RW → RT) ─────────────
        $this->call(WilayahSeeder::class);

        // ─── Kesehatan & Stunting (Pengukuran + Intervensi) ──────
        $this->call(KesehatanSeeder::class);

        // ─── Bantuan Sosial (Program + Penerima) ─────────────────
        $this->call(BansosSeeder::class);

        // ─── Pertanahan Desa (Lahan + GeoJSON) ───────────────────
        $this->call(PertanahanSeeder::class);

        // ─── Layanan Surat (Permohonan + Status) ─────────────────
        $this->call(LayananSuratSeeder::class);

        // ─── APBDes (Keuangan Desa) ──────────────────────────────
        $this->call(ApbdesSeeder::class);
    }
}
