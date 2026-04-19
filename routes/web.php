<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\DataController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\BackOffice\UserController;
use App\Http\Controllers\BackOffice\RoleController;
use App\Http\Controllers\BackOffice\VillageSettingController;
use App\Http\Controllers\BackOffice\SystemConfigController;
use App\Http\Controllers\BackOffice\PendudukController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profil/identitas', [ProfileController::class, 'identitas'])->name('profil.identitas');
Route::get('/profil/visi-misi', [ProfileController::class, 'visiMisi'])->name('profil.visi-misi');
Route::get('/profil/struktur-bpd', [ProfileController::class, 'strukturBpd'])->name('profil.struktur-bpd');
Route::get('/profil/sejarah-desa', [ProfileController::class, 'sejarahDesa'])->name('profil.sejarah-desa');

// Data Desa
Route::get('/data/penduduk', [DataController::class, 'penduduk'])->name('data.penduduk');
Route::get('/data/wilayah', [DataController::class, 'wilayah'])->name('data.wilayah');
Route::get('/data/pendidikan-ditempuh', [DataController::class, 'pendidikanDitempuh'])->name('data.pendidikan-ditempuh');
Route::get('/data/pekerjaan', [DataController::class, 'pekerjaan'])->name('data.pekerjaan');
Route::get('/data/agama', [DataController::class, 'agama'])->name('data.agama');
Route::get('/data/jenis-kelamin', [DataController::class, 'jenisKelamin'])->name('data.jenis-kelamin');
Route::get('/data/kelompok-umur', [DataController::class, 'kelompokUmur'])->name('data.kelompok-umur');

// Halaman Informasi Umum (PageController)
Route::get('/transparansi', [PageController::class, 'transparansi'])->name('transparansi');
Route::get('/layanan', [PageController::class, 'layanan'])->name('layanan');
Route::get('/layanan/pengaduan', [PageController::class, 'pengaduan'])->name('layanan.pengaduan');

// Halaman Lapak Desa
Route::get('/lapak', [PageController::class, 'lapak'])->name('lapak');
Route::get('/lapak/{slug}', [PageController::class, 'lapakDetail'])->name('lapak.detail');

Route::get('/informasi/berita-artikel', [PageController::class, 'beritaArtikel'])->name('informasi.berita-artikel');
Route::get('/informasi/pengumuman-agenda', [PageController::class, 'pengumumanAgenda'])->name('informasi.pengumuman');
Route::get('/informasi/produk-hukum', [PageController::class, 'produkHukum'])->name('informasi.hukum');
Route::get('/informasi/informasi-publik', [PageController::class, 'informasiPublik'])->name('informasi.publik');
Route::get('/informasi/galeri', [PageController::class, 'galeri'])->name('informasi.galeri');

// ──────────────────────────────────────────────────────────
// AUTHENTICATED ROUTES
// ──────────────────────────────────────────────────────────

// Generic dashboard redirect → routes user to their role-specific dashboard
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = request()->user();
    $role = $user->role;

    return match ($role) {
        'administrator' => redirect()->route('admin.dashboard'),
        'operator'      => redirect()->route('operator.dashboard'),
        'kades'         => redirect()->route('kades.dashboard'),
        'perangkat'     => redirect()->route('perangkat.dashboard'),
        'resepsionis'   => redirect()->route('resepsionis.dashboard'),
        default         => redirect()->route('home'),
    };
})->name('dashboard');

// Administrator
Route::middleware(['auth', 'verified', 'role:administrator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn () => view('pages.dashboard.index', [
        'pageTitle' => 'Dashboard Administrator',
    ]))->name('dashboard');

    // ── Manajemen User ──────────────────────────────────────
    Route::resource('users', UserController::class)->except(['create', 'edit']);
    Route::post('users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

    // ── Manajemen Role & Hak Akses ──────────────────────────
    Route::resource('roles', RoleController::class)->except(['create', 'edit']);

    // ── Identitas Desa ─────────────────────────────────
    Route::get('village-settings', [VillageSettingController::class, 'edit'])->name('village-settings.edit');
    Route::put('village-settings', [VillageSettingController::class, 'update'])->name('village-settings.update');

    // ── Konfigurasi Sistem ─────────────────────────────
    Route::get('system-config', [SystemConfigController::class, 'edit'])->name('system-config.edit');
    Route::put('system-config', [SystemConfigController::class, 'update'])->name('system-config.update');

    // ── Data Penduduk ─────────────────────────────────
    Route::resource('penduduk', PendudukController::class)->except(['create', 'edit']);
});

// Operator Desa
Route::middleware(['auth', 'verified', 'role:operator'])->prefix('operator')->name('operator.')->group(function () {
    Route::get('/dashboard', fn () => view('pages.dashboard.index', [
        'pageTitle' => 'Dashboard Operator Desa',
    ]))->name('dashboard');
});

// Kepala Desa
Route::middleware(['auth', 'verified', 'role:kades'])->prefix('kades')->name('kades.')->group(function () {
    Route::get('/dashboard', fn () => view('pages.dashboard.index', [
        'pageTitle' => 'Dashboard Kepala Desa',
    ]))->name('dashboard');
});

// Perangkat Desa
Route::middleware(['auth', 'verified', 'role:perangkat'])->prefix('perangkat')->name('perangkat.')->group(function () {
    Route::get('/dashboard', fn () => view('pages.dashboard.index', [
        'pageTitle' => 'Dashboard Perangkat Desa',
    ]))->name('dashboard');
});

// Resepsionis
Route::middleware(['auth', 'verified', 'role:resepsionis'])->prefix('resepsionis')->name('resepsionis.')->group(function () {
    Route::get('/dashboard', fn () => view('pages.dashboard.index', [
        'pageTitle' => 'Dashboard Resepsionis',
    ]))->name('dashboard');
});
// ──────────────────────────────────────────────────────────
// LAYANAN MANDIRI WARGA (Separate auth guard: 'warga')
// ──────────────────────────────────────────────────────────

use App\Http\Controllers\Frontend\WargaAuthController;
use App\Http\Controllers\Frontend\WargaDashboardController;

// Login warga (public)
Route::get('/layanan/mandiri/login', [WargaAuthController::class, 'showLogin'])->name('layanan.mandiri.login');
Route::post('/layanan/mandiri/login', [WargaAuthController::class, 'authenticate'])->name('layanan.mandiri.authenticate');

// Protected warga area
Route::middleware(['auth:warga'])->prefix('layanan/mandiri')->name('warga.')->group(function () {
    Route::get('/dashboard', [WargaDashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [WargaAuthController::class, 'logout'])->name('logout');
});

require __DIR__.'/settings.php';
