# 🏘️ SIDEWA — Sistem Informasi Desa Warga

> Platform digital terintegrasi untuk tata kelola administrasi, pelayanan publik, dan transparansi informasi Desa Sindangmukti, Kecamatan Mangunjaya, Kabupaten Pangandaran, Jawa Barat.

---

## 📌 Deskripsi

**SIDEWA** (Sistem Informasi Desa Warga) adalah aplikasi web full-stack berbasis Laravel yang dirancang untuk mendigitalisasi seluruh aspek pemerintahan desa. Sistem ini mencakup **portal publik** (website desa) untuk warga dan masyarakat umum, serta **back-office** (panel administrasi) untuk aparatur desa dengan sistem role-based access control (RBAC).

Aplikasi ini dibangun dari konversi desain HTML statis menjadi sistem full-stack yang production-ready, dengan arsitektur modular yang memisahkan frontend (portal publik) dan backend (panel administrasi) secara terstruktur.

---

## 🎯 Tujuan

1. **Digitalisasi Administrasi Desa** — Menggantikan proses manual (pencatatan penduduk, surat-menyurat, keuangan) menjadi sistem digital terintegrasi.
2. **Transparansi Publik** — Menyediakan akses informasi desa yang terbuka (APBDes, produk hukum, pembangunan) bagi masyarakat.
3. **Efisiensi Pelayanan** — Mempercepat proses pelayanan surat, verifikasi, dan administrasi kependudukan.
4. **Pengambilan Keputusan Berbasis Data** — Dashboard eksekutif dengan visualisasi data demografis, keuangan, dan pembangunan untuk Kepala Desa.
5. **Portal Layanan Mandiri** — Warga dapat mengakses layanan desa secara mandiri melalui portal khusus dengan autentikasi terpisah.

---

## 🧩 Fitur Utama

### 🌐 Portal Publik (Frontend)
- **Halaman Beranda** — Informasi umum desa dengan statistik real-time, berita terkini, dan peta interaktif
- **Profil Desa** — Identitas desa, visi-misi, struktur BPD, dan sejarah desa
- **Data Kependudukan** — Statistik penduduk berdasarkan wilayah, pendidikan, pekerjaan, agama, jenis kelamin, dan kelompok umur (dilengkapi Chart.js)
- **Transparansi Keuangan** — Informasi APBDes dan realisasi anggaran
- **Informasi Publik** — Berita & artikel, pengumuman & agenda, produk hukum (JDIH), informasi publik, dan galeri foto/video
- **Lapak Desa (UMKM)** — Katalog produk UMKM desa dengan halaman detail
- **Layanan Desa** — Informasi layanan dan pengaduan masyarakat
- **Layanan Mandiri Warga** — Portal khusus warga dengan login NIK + PIN (guard terpisah)

### 🏢 Panel Administrasi (Back-Office)
- **Dashboard Eksekutif** — KPI cards, chart demografi (Highcharts), distribusi pendidikan, serapan APBDes, peta proyek (Leaflet.js), dan insight cerdas
- **Dashboard Operasional** — Monitoring operasional harian
- **Manajemen Kependudukan** — CRUD data penduduk, kartu keluarga (dengan relasi anggota), dan wilayah administratif (Dusun → RW → RT)
- **Kesehatan & Stunting** — Pencatatan pengukuran balita, deteksi stunting, dan intervensi
- **Bantuan Sosial** — Manajemen program bansos dan penerima
- **Pertanahan Desa** — Data lahan dengan integrasi peta GeoJSON (Leaflet.js)
- **Layanan Surat** — Wizard pembuatan surat multi-step, manajemen template surat (WYSIWYG), arsip surat, dan verifikasi/TTE (Tanda Tangan Elektronik)
- **Presensi & Buku Tamu** — Monitoring presensi pegawai, rekap kehadiran (dengan export), dan pencatatan tamu
- **Keuangan** — Manajemen APBDes, realisasi anggaran, dan laporan keuangan
- **Pembangunan** — Data proyek pembangunan (dengan tracking progress & foto) dan perencanaan pembangunan (konversi ke proyek)
- **Konten Publikasi** — Galeri (album + media), berita/artikel (dengan kategori & tag), halaman statis, informasi publik, produk UMKM, dan JDIH
- **Laporan & Statistik** — Dashboard statistik komprehensif dan laporan presensi
- **Manajemen User & Role** — CRUD user, role-based access control (RBAC) dengan permission per modul
- **Konfigurasi Sistem** — Identitas desa dan konfigurasi sistem
- **Pertukaran Data** — Export/import data (Excel) dan manajemen API key untuk integrasi eksternal

---

## 🏗️ Arsitektur Sistem

### Frontend (Portal Publik)
- **Blade Templating** dengan layout `frontend.blade.php`
- **Tailwind CSS v4** untuk styling responsif
- **Alpine.js** untuk interaktivitas UI (dropdown, modal, tab, mobile menu)
- **Chart.js** untuk visualisasi statistik kependudukan
- **Leaflet.js** untuk peta interaktif wilayah desa
- Komponen reusable: `navbar`, `footer`, `card-berita`, `bpd-card`, `lapak-item`

### Backend (Panel Administrasi)
- **Blade Templating** dengan layout `backoffice.blade.php` (sidebar + topbar)
- **Highcharts** untuk visualisasi data dashboard (demografi, keuangan, surat)
- **Leaflet.js** untuk peta proyek pembangunan dan pertanahan
- **Quill.js** sebagai rich-text editor (template surat)
- **Font Awesome 6** untuk ikonografi
- **Google Fonts (Inter)** untuk tipografi

### Backend Logic
- **Laravel 13** sebagai framework utama
- **Laravel Fortify** untuk autentikasi (login, register, 2FA, password reset)
- **Livewire** tersedia namun mayoritas UI menggunakan Blade + Alpine.js
- **Dual Auth Guard** — guard `web` untuk aparatur, guard `warga` untuk warga/citizen
- **Role Middleware** (`EnsureUserHasRole`) untuk proteksi akses berbasis role
- **DomPDF** untuk generate dokumen PDF (surat, laporan)
- **Spatie Simple Excel** untuk export/import data

### Struktur Modular
```
Controller (34 BackOffice + 6 Frontend)
    ↓
Model (41 Eloquent Models)
    ↓
Migration (49 migration files)
    ↓
Seeder (18 seeder files)
```

---

## 📂 Struktur Folder

```
desa-sindangmukti/
├── app/
│   ├── Actions/                    # Action classes
│   ├── Concerns/                   # Trait/concern reusable
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── BackOffice/         # 34 controller panel admin
│   │   │   └── Frontend/           # 6 controller portal publik
│   │   ├── Middleware/
│   │   │   └── EnsureUserHasRole.php   # Role-based middleware
│   │   ├── Requests/               # Form request validation
│   │   └── Responses/              # Custom response classes
│   ├── Livewire/                   # Livewire components
│   ├── Models/                     # 41 Eloquent models
│   └── Providers/                  # Service providers
│
├── config/
│   ├── auth.php                    # Dual guard (web + warga)
│   ├── fortify.php                 # Fortify auth config
│   └── ...                         # Laravel default configs
│
├── database/
│   ├── migrations/                 # 49 migration files
│   ├── seeders/                    # 18 seeder files (data sampel)
│   └── factories/                  # Model factories
│
├── resources/
│   ├── css/app.css                 # Tailwind CSS entry point
│   ├── js/app.js                   # JavaScript entry point
│   └── views/
│       ├── layouts/
│       │   ├── frontend.blade.php      # Layout portal publik
│       │   ├── backoffice.blade.php    # Layout panel admin
│       │   ├── auth-custom.blade.php   # Layout auth warga
│       │   └── app.blade.php           # Layout Livewire
│       ├── components/
│       │   ├── frontend/           # Navbar, footer, cards
│       │   └── backoffice/         # Sidebar, topbar
│       └── pages/
│           ├── frontend/           # 20+ halaman publik
│           ├── backoffice/         # 31 modul admin
│           ├── auth/               # Login, register, 2FA
│           ├── dashboard/          # Dashboard generik
│           └── settings/           # Profil & keamanan user
│
├── routes/
│   ├── web.php                     # 330+ route definitions
│   └── settings.php                # Route pengaturan user
│
├── public/
│   └── assets/
│       ├── img/                    # Gambar statis
│       └── js/                     # Script statis
│
└── vite.config.js                  # Vite + Tailwind CSS plugin
```

---

## ⚙️ Teknologi yang Digunakan

| Kategori | Teknologi | Versi |
|----------|-----------|-------|
| **Framework** | Laravel | ^13.0 |
| **PHP** | PHP | ^8.3 |
| **CSS Framework** | Tailwind CSS | ^4.0.7 |
| **Build Tool** | Vite | ^8.0.0 |
| **Auth** | Laravel Fortify | ^1.34 |
| **Realtime UI** | Livewire | ^4.1 |
| **UI Components** | Livewire Flux | ^2.13.1 |
| **JS Framework** | Alpine.js | 3.x (CDN) |
| **Charts (Frontend)** | Chart.js | Latest (CDN) |
| **Charts (BackOffice)** | Highcharts | Latest (CDN) |
| **Maps** | Leaflet.js | 1.9.4 (CDN) |
| **Rich Text Editor** | Quill.js | ^2.0.0-rc.5 |
| **PDF Generator** | barryvdh/laravel-dompdf | ^3.1 |
| **Excel Export/Import** | spatie/simple-excel | ^3.9 |
| **Icons** | Font Awesome | 6.4.0 (CDN) |
| **Typography** | Google Fonts (Inter) | — |
| **Database** | SQLite (dev) / MySQL (prod) | — |
| **Testing** | Pest PHP | ^4.6 |
| **Linting** | Laravel Pint | ^1.27 |

---

## 🚀 Instalasi & Setup

### Prasyarat
- PHP >= 8.3
- Composer
- Node.js >= 18 & NPM
- SQLite (development) atau MySQL (production)

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/HansNura/sidewa.git
cd desa-sindangmukti

# 2. Install dependensi PHP
composer install

# 3. Salin file environment
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Buat database SQLite (untuk development)
# File akan dibuat otomatis di database/database.sqlite
touch database/database.sqlite

# 6. Jalankan migrasi database
php artisan migrate

# 7. (Opsional) Seed data sampel
php artisan db:seed

# 8. Install dependensi frontend
npm install

# 9. Jalankan development server
npm run dev
```

> **Catatan:** Perintah `npm run dev` akan menjalankan **Laravel server** dan **Vite dev server** secara bersamaan menggunakan `concurrently`.

### Quick Setup (Alternatif)
```bash
composer setup
```
Script ini akan menjalankan `composer install`, setup `.env`, `key:generate`, `migrate`, `npm install`, dan `npm run build` secara otomatis.

---

## 🔑 Konfigurasi

### File `.env` — Variabel Penting

```env
# ── Aplikasi ──────────────────────────────
APP_NAME="SIDEWA"
APP_URL=http://localhost:8000
APP_LOCALE=id

# ── Database ──────────────────────────────
# Development (SQLite - default)
DB_CONNECTION=sqlite

# Production (MySQL)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=sidewa
# DB_USERNAME=root
# DB_PASSWORD=

# ── Session & Cache ───────────────────────
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# ── Mail (untuk notifikasi) ──────────────
MAIL_MAILER=log              # Ganti ke smtp untuk production
```

### Akun Default (Setelah Seed)

| Role | Email | Password |
|------|-------|----------|
| Administrator | `admin@desa.go.id` | `password` |
| Operator | `operator@desa.go.id` | `password` |
| Kepala Desa | `kades@desa.go.id` | `password` |
| Perangkat Desa | `perangkat@desa.go.id` | `password` |
| Resepsionis | `resepsionis@desa.go.id` | `password` |

> ⚠️ **Penting:** Segera ubah password default setelah deployment ke production!

---

## 📊 Alur Sistem

### Flow Portal Publik
```
Pengunjung → Route (web.php)
              ↓
         Frontend Controller
         (Home / Profile / Data / Page)
              ↓
         Query Model (Penduduk, Article, dll.)
              ↓
         Blade View (layouts/frontend.blade.php)
              ↓
         Halaman Publik (+ Chart.js / Leaflet.js)
```

### Flow Panel Administrasi
```
Aparatur Desa → Login (Fortify Auth)
                  ↓
             Middleware: auth + verified + role:{role}
                  ↓
             BackOffice Controller (34 controllers)
                  ↓
             CRUD + Business Logic (Eloquent Models)
                  ↓
             Blade View (layouts/backoffice.blade.php)
                  ↓
             Dashboard / Modul Admin (+ Highcharts / Leaflet.js / Alpine.js)
```

### Flow Layanan Mandiri Warga
```
Warga → Login NIK + PIN (Guard: 'warga')
          ↓
     WargaDashboardController
          ↓
     Dashboard Warga (Riwayat Surat, Data Pribadi)
```

### Flow Pembuatan Surat
```
Operator → Wizard Buat Surat (Multi-step)
             ↓
         Pilih Template → Isi Data Penduduk → Preview
             ↓
         Submit (status: draft/menunggu_verifikasi)
             ↓
         Kepala Desa → Verifikasi & TTE
             ↓
         Surat Selesai (status: selesai) → Cetak PDF (DomPDF)
```

---

## 📄 Halaman yang Tersedia

### Portal Publik (Frontend)
| Halaman | Route | Deskripsi |
|---------|-------|-----------|
| Beranda | `/` | Landing page dengan statistik & berita |
| Identitas Desa | `/profil/identitas` | Informasi identitas resmi desa |
| Visi & Misi | `/profil/visi-misi` | Visi dan misi pemerintah desa |
| Struktur BPD | `/profil/struktur-bpd` | Struktur Badan Permusyawaratan Desa |
| Sejarah Desa | `/profil/sejarah-desa` | Sejarah dan asal-usul desa |
| Data Penduduk | `/data/penduduk` | Statistik kependudukan |
| Data Wilayah | `/data/wilayah` | Peta dan data wilayah administratif |
| Data Pendidikan | `/data/pendidikan-ditempuh` | Statistik pendidikan |
| Data Pekerjaan | `/data/pekerjaan` | Statistik pekerjaan |
| Data Agama | `/data/agama` | Statistik agama |
| Data Jenis Kelamin | `/data/jenis-kelamin` | Statistik gender |
| Data Kelompok Umur | `/data/kelompok-umur` | Piramida penduduk |
| Transparansi Keuangan | `/transparansi` | APBDes & realisasi |
| Layanan Desa | `/layanan` | Informasi layanan |
| Pengaduan | `/layanan/pengaduan` | Form pengaduan masyarakat |
| Lapak Desa | `/lapak` | Katalog produk UMKM |
| Detail Produk | `/lapak/{slug}` | Detail produk UMKM |
| Berita & Artikel | `/informasi/berita-artikel` | Daftar berita |
| Detail Berita | `/informasi/berita-artikel/{slug}` | Baca berita |
| Pengumuman & Agenda | `/informasi/pengumuman-agenda` | Pengumuman resmi |
| Produk Hukum (JDIH) | `/informasi/produk-hukum` | Dokumen hukum desa |
| Informasi Publik | `/informasi/informasi-publik` | Dokumen informasi publik |
| Galeri | `/informasi/galeri` | Album foto & video |
| Login Warga | `/layanan/mandiri/login` | Login layanan mandiri |
| Dashboard Warga | `/layanan/mandiri/dashboard` | Portal warga |

### Panel Administrasi (31 Modul)
| Modul | Prefix Route | Fitur |
|-------|-------------|-------|
| Dashboard Eksekutif | `/admin/dashboard` | KPI, charts, insight |
| Dashboard Operasional | `/admin/dashboard-operasional` | Monitoring harian |
| Manajemen User | `/admin/users` | CRUD user + bulk action |
| Manajemen Role | `/admin/roles` | RBAC per modul |
| Identitas Desa | `/admin/village-settings` | Setting profil desa |
| Konfigurasi Sistem | `/admin/system-config` | Parameter sistem |
| Data Penduduk | `/admin/penduduk` | CRUD penduduk |
| Kartu Keluarga | `/admin/kartu-keluarga` | Manajemen KK |
| Wilayah | `/admin/wilayah` | Dusun/RW/RT |
| Kesehatan & Stunting | `/admin/kesehatan` | Pengukuran balita |
| Bantuan Sosial | `/admin/bansos` | Program & penerima |
| Pertanahan | `/admin/pertanahan` | Data lahan + peta |
| Layanan Surat | `/admin/layanan-surat` | Permohonan surat |
| Wizard Buat Surat | `/admin/layanan-surat/buat` | Multi-step wizard |
| Template Surat | `/admin/template-surat` | Editor template |
| Arsip Surat | `/admin/arsip-surat` | Arsip digital |
| Verifikasi & TTE | `/admin/verifikasi-surat` | Approve/reject/TTE |
| Presensi Pegawai | `/admin/presensi/monitoring` | Monitoring presensi |
| Rekap Kehadiran | `/admin/presensi/rekap` | Rekap + export |
| Buku Tamu | `/admin/buku-tamu` | Pencatatan tamu |
| APBDes | `/admin/keuangan/apbdes` | Anggaran desa |
| Realisasi Anggaran | `/admin/keuangan/realisasi` | Realisasi belanja |
| Laporan Keuangan | `/admin/keuangan/laporan` | Laporan keuangan |
| Data Pembangunan | `/admin/pembangunan/data` | Proyek + progress |
| Perencanaan | `/admin/pembangunan/perencanaan` | Musrenbang |
| Galeri | `/admin/konten/galeri` | Album & media |
| Berita/Artikel | `/admin/konten/berita` | Manajemen berita |
| Halaman | `/admin/konten/halaman` | Halaman statis |
| Informasi Publik | `/admin/konten/informasi` | Kelola informasi |
| UMKM | `/admin/konten/umkm` | Produk & kategori |
| JDIH | `/admin/konten/jdih` | Dokumen hukum |
| Statistik | `/admin/laporan/statistik` | Dashboard statistik |
| Pertukaran Data | `/admin/data-exchange` | Export/import Excel |
| Integrasi API | `/admin/api-integration` | API key management |

---

## 🧪 Catatan Pengembangan

### Status Saat Ini
- ✅ Seluruh modul back-office telah diimplementasi (34 controller)
- ✅ Portal publik lengkap dengan 20+ halaman dinamis
- ✅ Dual authentication system (Aparatur + Warga)
- ✅ Role-based access control (5 role: Administrator, Operator, Kades, Perangkat, Resepsionis)
- ✅ Dashboard eksekutif dengan visualisasi data real-time
- ✅ Seeder lengkap untuk data sampel (18 seeder)
- ✅ Export/Import data via Excel
- ✅ Generate PDF surat (DomPDF)

### Potensi Improvement
- 🔲 Notifikasi real-time (WebSocket/Pusher)
- 🔲 Mobile app / PWA support
- 🔲 Integrasi Dukcapil API untuk validasi NIK
- 🔲 Tanda Tangan Digital (Digital Signature) yang terverifikasi
- 🔲 Unit & feature testing yang lebih komprehensif
- 🔲 CI/CD pipeline untuk automated deployment
- 🔲 Multi-tenancy untuk mendukung beberapa desa
- 🔲 Fitur chat/konsultasi warga dengan aparatur desa
- 🔲 Dashboard role Operator, Perangkat, dan Resepsionis yang lebih spesifik

---

## 👨‍💻 Author

**Tim Pengembang SIDEWA**
Desa Sindangmukti, Kecamatan Mangunjaya, Kabupaten Pangandaran, Jawa Barat

---

## 📝 Lisensi

Project ini dikembangkan menggunakan [Laravel Framework](https://laravel.com/) yang berlisensi [MIT License](https://opensource.org/licenses/MIT).

---

<p align="center">
  <em>Dibangun dengan ❤️ untuk kemajuan Desa Sindangmukti</em>
</p>
