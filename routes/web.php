<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\DataController;
use App\Http\Controllers\Frontend\PageController as FrontendPageController;
use App\Http\Controllers\BackOffice\UserController;
use App\Http\Controllers\BackOffice\RoleController;
use App\Http\Controllers\BackOffice\VillageSettingController;
use App\Http\Controllers\BackOffice\SystemConfigController;
use App\Http\Controllers\BackOffice\PendudukController;
use App\Http\Controllers\BackOffice\KartuKeluargaController;
use App\Http\Controllers\BackOffice\WilayahController;
use App\Http\Controllers\BackOffice\KesehatanController;
use App\Http\Controllers\BackOffice\BansosController;
use App\Http\Controllers\BackOffice\PertanahanController;
use App\Http\Controllers\BackOffice\LayananSuratController;
use App\Http\Controllers\BackOffice\TemplateSuratController;
use App\Http\Controllers\BackOffice\ArsipSuratController;
use App\Http\Controllers\BackOffice\VerifikasiSuratController;
use App\Http\Controllers\BackOffice\PresensiPegawaiController;
use App\Http\Controllers\BackOffice\RekapKehadiranController;
use App\Http\Controllers\BackOffice\LaporanBukuTamuController;
use App\Http\Controllers\BackOffice\ApbdesController;
use App\Http\Controllers\BackOffice\RealisasiAnggaranController;
use App\Http\Controllers\BackOffice\LaporanKeuanganController;
use App\Http\Controllers\BackOffice\PembangunanController;
use App\Http\Controllers\BackOffice\PerencanaanController;
use App\Http\Controllers\BackOffice\GaleriController;
use App\Http\Controllers\BackOffice\ArtikelController;
use App\Http\Controllers\BackOffice\PageController as BackOfficePageController;
use App\Http\Controllers\BackOffice\InformasiController;
use App\Http\Controllers\BackOffice\UmkmController;
use App\Http\Controllers\BackOffice\JdihController;
use App\Http\Controllers\BackOffice\StatistikController;
use App\Http\Controllers\BackOffice\PresensiController;
use App\Http\Controllers\BackOffice\DataExchangeController;
use App\Http\Controllers\BackOffice\ApiIntegrationController;



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
Route::get('/transparansi', [FrontendPageController::class, 'transparansi'])->name('transparansi');
Route::get('/layanan', [FrontendPageController::class, 'layanan'])->name('layanan');
Route::get('/layanan/pengaduan', [FrontendPageController::class, 'pengaduan'])->name('layanan.pengaduan');

// Halaman Lapak Desa
Route::get('/lapak', [FrontendPageController::class, 'lapak'])->name('lapak');
Route::get('/lapak/{slug}', [FrontendPageController::class, 'lapakDetail'])->name('lapak.detail');

Route::get('/informasi/berita-artikel', [FrontendPageController::class, 'beritaArtikel'])->name('informasi.berita-artikel');
Route::get('/informasi/pengumuman-agenda', [FrontendPageController::class, 'pengumumanAgenda'])->name('informasi.pengumuman');
Route::get('/informasi/produk-hukum', [FrontendPageController::class, 'produkHukum'])->name('informasi.hukum');
Route::get('/informasi/informasi-publik', [FrontendPageController::class, 'informasiPublik'])->name('informasi.publik');
Route::get('/informasi/galeri', [FrontendPageController::class, 'galeri'])->name('informasi.galeri');

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

    // ── Kartu Keluarga ───────────────────────────────
    Route::resource('kartu-keluarga', KartuKeluargaController::class)->only(['index', 'show', 'store', 'destroy']);
    Route::post('kartu-keluarga/{kartu_keluarga}/add-member', [KartuKeluargaController::class, 'addMember'])->name('kartu-keluarga.add-member');
    Route::delete('kartu-keluarga/{kartu_keluarga}/remove-member/{penduduk}', [KartuKeluargaController::class, 'removeMember'])->name('kartu-keluarga.remove-member');
    Route::get('kartu-keluarga-search-penduduk', [KartuKeluargaController::class, 'searchPenduduk'])->name('kartu-keluarga.search-penduduk');

    // ── Wilayah Administratif ─────────────────────
    Route::resource('wilayah', WilayahController::class)->except(['create', 'edit']);

    // ── Kesehatan & Stunting ──────────────────────
    Route::get('kesehatan', [KesehatanController::class, 'index'])->name('kesehatan.index');
    Route::post('kesehatan', [KesehatanController::class, 'store'])->name('kesehatan.store');
    Route::get('kesehatan/search-balita', [KesehatanController::class, 'searchBalita'])->name('kesehatan.search-balita');
    Route::get('kesehatan/{penduduk}', [KesehatanController::class, 'show'])->name('kesehatan.show');
    Route::delete('kesehatan/pengukuran/{pengukuran}', [KesehatanController::class, 'destroy'])->name('kesehatan.destroy');

    // ── Bantuan Sosial ──────────────────────────
    Route::get('bansos', [BansosController::class, 'index'])->name('bansos.index');
    Route::post('bansos', [BansosController::class, 'store'])->name('bansos.store');
    Route::get('bansos/search-penduduk', [BansosController::class, 'searchPenduduk'])->name('bansos.search-penduduk');
    Route::get('bansos/{banso}', [BansosController::class, 'show'])->name('bansos.show');
    Route::patch('bansos/{banso}/status', [BansosController::class, 'updateStatus'])->name('bansos.update-status');
    Route::delete('bansos/{banso}', [BansosController::class, 'destroy'])->name('bansos.destroy');

    // ── Pertanahan Desa ───────────────────────
    Route::get('pertanahan', [PertanahanController::class, 'index'])->name('pertanahan.index');
    Route::post('pertanahan', [PertanahanController::class, 'store'])->name('pertanahan.store');
    Route::get('pertanahan/search-penduduk', [PertanahanController::class, 'searchPenduduk'])->name('pertanahan.search-penduduk');
    Route::get('pertanahan/{pertanahan}', [PertanahanController::class, 'show'])->name('pertanahan.show');
    Route::delete('pertanahan/{pertanahan}', [PertanahanController::class, 'destroy'])->name('pertanahan.destroy');

    // ── Layanan Surat ────────────────────────
    Route::get('layanan-surat', [LayananSuratController::class, 'index'])->name('layanan-surat.index');
    Route::post('layanan-surat', [LayananSuratController::class, 'store'])->name('layanan-surat.store');
    Route::get('layanan-surat/search-penduduk', [LayananSuratController::class, 'searchPenduduk'])->name('layanan-surat.search-penduduk');

    // Wizard Buat Surat Baru (sebelum route parametric agar tidak konflik)
    Route::get('layanan-surat/buat', [LayananSuratController::class, 'create'])->name('layanan-surat.create');
    Route::post('layanan-surat/buat', [LayananSuratController::class, 'storeWizard'])->name('layanan-surat.store-wizard');
    Route::get('layanan-surat/drafts', [LayananSuratController::class, 'drafts'])->name('layanan-surat.drafts');
    Route::get('layanan-surat/{surat}/edit-wizard', [LayananSuratController::class, 'editWizard'])->name('layanan-surat.edit-wizard');

    Route::patch('layanan-surat/{surat}/status', [LayananSuratController::class, 'updateStatus'])->name('layanan-surat.update-status');
    Route::delete('layanan-surat/{surat}', [LayananSuratController::class, 'destroy'])->name('layanan-surat.destroy');

    // ── Template Surat ──────────────────────
    Route::get('template-surat', [TemplateSuratController::class, 'index'])->name('template-surat.index');
    Route::get('template-surat/create', [TemplateSuratController::class, 'create'])->name('template-surat.create');
    Route::post('template-surat', [TemplateSuratController::class, 'store'])->name('template-surat.store');
    Route::get('template-surat/{template}', [TemplateSuratController::class, 'show'])->name('template-surat.show');
    Route::get('template-surat/{template}/edit', [TemplateSuratController::class, 'edit'])->name('template-surat.edit');
    Route::put('template-surat/{template}', [TemplateSuratController::class, 'update'])->name('template-surat.update');
    Route::patch('template-surat/{template}/toggle', [TemplateSuratController::class, 'toggleStatus'])->name('template-surat.toggle');
    Route::delete('template-surat/{template}', [TemplateSuratController::class, 'destroy'])->name('template-surat.destroy');

    // ── Arsip Surat ────────────────────────
    Route::get('arsip-surat', [ArsipSuratController::class, 'index'])->name('arsip-surat.index');
    Route::get('arsip-surat/{surat}', [ArsipSuratController::class, 'show'])->name('arsip-surat.show');
    Route::post('arsip-surat/bulk-destroy', [ArsipSuratController::class, 'bulkDestroy'])->name('arsip-surat.bulk-destroy');

    // ── Verifikasi & TTE Surat ─────────────
    Route::get('verifikasi-surat', [VerifikasiSuratController::class, 'index'])->name('verifikasi-surat.index');
    Route::get('verifikasi-surat/{surat}', [VerifikasiSuratController::class, 'show'])->name('verifikasi-surat.show');
    Route::post('verifikasi-surat/{surat}/approve', [VerifikasiSuratController::class, 'approve'])->name('verifikasi-surat.approve');
    Route::post('verifikasi-surat/{surat}/reject', [VerifikasiSuratController::class, 'reject'])->name('verifikasi-surat.reject');
    Route::post('verifikasi-surat/{surat}/revisi', [VerifikasiSuratController::class, 'revisi'])->name('verifikasi-surat.revisi');
    Route::post('verifikasi-surat/{surat}/verify', [VerifikasiSuratController::class, 'verify'])->name('verifikasi-surat.verify');

    // ── Presensi Pegawai ───────────────────
    Route::get('presensi/monitoring', [PresensiPegawaiController::class, 'index'])->name('presensi.monitoring');
    Route::post('presensi/koreksi-manual', [PresensiPegawaiController::class, 'storeManual'])->name('presensi.store-manual');
    Route::get('presensi/{user}/info', [PresensiPegawaiController::class, 'showInfo'])->name('presensi.show-info');
    
    // ── Rekap Kehadiran Pegawei ────────────
    Route::get('presensi/rekap', [RekapKehadiranController::class, 'index'])->name('presensi.rekap');
    Route::get('presensi/rekap/{user}/info', [RekapKehadiranController::class, 'showEmployee'])->name('presensi.rekap.show-info');
    Route::post('presensi/rekap/export', [RekapKehadiranController::class, 'export'])->name('presensi.rekap.export');

    // ── Laporan Buku Tamu ────────────
    Route::get('buku-tamu', [LaporanBukuTamuController::class, 'index'])->name('buku-tamu.index');
    Route::get('buku-tamu/{bukuTamu}/detail', [LaporanBukuTamuController::class, 'show'])->name('buku-tamu.show');
    Route::post('buku-tamu/export', [LaporanBukuTamuController::class, 'export'])->name('buku-tamu.export');

    // ── Manajemen Keuangan (APBDes) ───
    Route::get('keuangan/apbdes', [ApbdesController::class, 'index'])->name('apbdes.index');
    Route::post('keuangan/apbdes', [ApbdesController::class, 'store'])->name('apbdes.store');
    Route::post('keuangan/apbdes/poster', [ApbdesController::class, 'storePoster'])->name('apbdes.store-poster');

    // ── Realisasi Anggaran ───
    Route::get('keuangan/realisasi', [RealisasiAnggaranController::class, 'index'])->name('realisasi.index');
    Route::post('keuangan/realisasi', [RealisasiAnggaranController::class, 'store'])->name('realisasi.store');
    Route::get('keuangan/realisasi/drawer/{apbdes_id}', [RealisasiAnggaranController::class, 'detailActivity'])->name('realisasi.detail');

    // ── Laporan Keuangan ───
    Route::get('keuangan/laporan', [LaporanKeuanganController::class, 'index'])->name('laporan.index');

    // ── Manajemen Pembangunan ──
    Route::get('pembangunan/data', [PembangunanController::class, 'index'])->name('pembangunan.index');
    Route::post('pembangunan/data', [PembangunanController::class, 'store'])->name('pembangunan.store');
    Route::get('pembangunan/data/drawer/{id}', [PembangunanController::class, 'detail'])->name('pembangunan.detail');
    Route::post('pembangunan/data/{id}/progress', [PembangunanController::class, 'updateProgress'])->name('pembangunan.update-progress');

    // ── Perencanaan Pembangunan ──
    Route::get('pembangunan/perencanaan', [PerencanaanController::class, 'index'])->name('perencanaan.index');
    Route::post('pembangunan/perencanaan', [PerencanaanController::class, 'store'])->name('perencanaan.store');
    Route::get('pembangunan/perencanaan/drawer/{id}', [PerencanaanController::class, 'detail'])->name('perencanaan.detail');
    Route::post('pembangunan/perencanaan/{id}/sync', [PerencanaanController::class, 'konversiKeProyek'])->name('perencanaan.konversi');

    // ── Konten Publikasi / Galeri & Artikel ──
    Route::get('konten/galeri', [GaleriController::class, 'index'])->name('galeri.index');
    Route::post('konten/galeri/album', [GaleriController::class, 'storeAlbum'])->name('galeri.album.store');
    Route::delete('konten/galeri/album/{id}', [GaleriController::class, 'destroyAlbum'])->name('galeri.album.destroy');
    Route::post('konten/galeri/media', [GaleriController::class, 'storeMedia'])->name('galeri.media.store');
    Route::get('konten/galeri/media/drawer/{id}', [GaleriController::class, 'detailMedia'])->name('galeri.media.detail');
    Route::delete('konten/galeri/media/{id}', [GaleriController::class, 'destroyMedia'])->name('galeri.media.destroy');

    Route::get('konten/berita', [ArtikelController::class, 'index'])->name('artikel.index');
    Route::post('konten/berita/kategori', [ArtikelController::class, 'storeCategory'])->name('artikel.kategori.store');
    Route::post('konten/berita', [ArtikelController::class, 'store'])->name('artikel.store');
    Route::post('konten/berita/bulk', [ArtikelController::class, 'bulkAction'])->name('artikel.bulk');
    Route::get('konten/berita/api/{id}', [ArtikelController::class, 'apiDetail'])->name('artikel.api.detail');

    Route::get('konten/halaman', [BackOfficePageController::class, 'index'])->name('halaman.index');
    Route::post('konten/halaman', [BackOfficePageController::class, 'store'])->name('halaman.store');
    Route::post('konten/halaman/bulk', [BackOfficePageController::class, 'bulkAction'])->name('halaman.bulk');
    Route::get('konten/halaman/move/{id}/{direction}', [BackOfficePageController::class, 'moveOrder'])->name('halaman.move');
    Route::get('konten/halaman/api/{id}', [BackOfficePageController::class, 'apiDetail'])->name('halaman.api.detail');

    Route::get('konten/informasi', [InformasiController::class, 'index'])->name('informasi.index');
    Route::post('konten/informasi', [InformasiController::class, 'store'])->name('informasi.store');
    Route::post('konten/informasi/bulk', [InformasiController::class, 'bulkAction'])->name('informasi.bulk');
    Route::get('konten/informasi/api/{id}', [InformasiController::class, 'apiDetail'])->name('informasi.api.detail');

    // ── Manajemen Produk UMKM ──
    Route::get('konten/umkm', [UmkmController::class, 'index'])->name('umkm.index');
    Route::post('konten/umkm/produk', [UmkmController::class, 'storeProduct'])->name('umkm.storeProduct');
    Route::post('konten/umkm/produk/destroy', [UmkmController::class, 'destroyProduct'])->name('umkm.destroyProduct');
    Route::get('konten/umkm/api/{id}', [UmkmController::class, 'apiDetailProduct'])->name('umkm.api.detail');
    Route::post('konten/umkm/kategori', [UmkmController::class, 'storeCategory'])->name('umkm.storeCategory');
    Route::post('konten/umkm/kategori/{id}/destroy', [UmkmController::class, 'destroyCategory'])->name('umkm.destroyCategory');

    // ── Manajemen JDIH (Produk Hukum) ──
    Route::get('konten/jdih', [JdihController::class, 'index'])->name('jdih.index');
    Route::post('konten/jdih/dokumen', [JdihController::class, 'storeDocument'])->name('jdih.storeDocument');
    Route::post('konten/jdih/dokumen/destroy', [JdihController::class, 'destroyDocument'])->name('jdih.destroyDocument');
    Route::get('konten/jdih/api/{id}', [JdihController::class, 'apiDetailDocument'])->name('jdih.api.detail');
    Route::post('konten/jdih/kategori', [JdihController::class, 'storeCategory'])->name('jdih.storeCategory');
    Route::post('konten/jdih/kategori/{id}/destroy', [JdihController::class, 'destroyCategory'])->name('jdih.destroyCategory');

    // ── Laporan & Integrasi ──
    Route::get('laporan/statistik', [StatistikController::class, 'index'])->name('statistik.index');
    Route::get('laporan/presensi', [PresensiController::class, 'index'])->name('presensi.index');

    // ── Pertukaran Data (Export/Import) ──
    Route::prefix('data-exchange')->name('data-exchange.')->group(function () {
        Route::get('/', [DataExchangeController::class, 'index'])->name('index');
        Route::post('/export', [DataExchangeController::class, 'export'])->name('export');
        Route::post('/import', [DataExchangeController::class, 'importExecute'])->name('import');
        Route::get('/template', [DataExchangeController::class, 'downloadTemplate'])->name('template');
    });

    // ── Integrasi API ──
    Route::prefix('api-integration')->name('api.')->group(function () {
        Route::get('/', [ApiIntegrationController::class, 'index'])->name('index');
        Route::post('/generate', [ApiIntegrationController::class, 'generateKey'])->name('generate');
        Route::post('/revoke/{id}', [ApiIntegrationController::class, 'revokeKey'])->name('revoke');
        Route::post('/sync', [ApiIntegrationController::class, 'syncData'])->name('sync');
    });
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
