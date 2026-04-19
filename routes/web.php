<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\DataController;
use App\Http\Controllers\Frontend\PageController;

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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
