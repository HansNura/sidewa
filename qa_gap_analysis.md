# 🔴 CRITICAL GAP ANALYSIS — Layanan Mandiri SID
> Sistem Informasi Desa Sindangmukti | Modul: Citizen Self-Service

---

## 1. RBAC Architecture Gap

### GAP-01: Dual Auth Guard Tanpa Integrasi RBAC Terpusat

| Aspek | Detail |
|---|---|
| **Gap** | Sistem menggunakan 2 auth guard terpisah (`web` untuk User/Admin, `warga` untuk Warga) tetapi RBAC (`Role`, `RoleModulePermission`) **hanya terhubung ke `User` model**, bukan `Warga` |
| **Dampak Teknis** | `EnsureUserHasRole` middleware hanya cek `$request->user()->role` (guard `web`). Warga guard tidak memiliki role check sama sekali — hanya cek `auth:warga` |
| **Dampak Bisnis** | Semua warga yang login memiliki akses identik. Tidak bisa membedakan warga aktif/terblokir pada level fitur |
| **Risiko** | Warga bisa mengakses semua endpoint layanan mandiri tanpa granular permission. Jika ada warga bermasalah, satu-satunya cara blokir = set `is_active = false` |

### GAP-02: Role Operator/Kades Hanya Placeholder

| Aspek | Detail |
|---|---|
| **Gap** | Route group `operator.*` dan `kades.*` hanya berisi 1 route masing-masing (dashboard). Semua fitur layanan surat (buat, verifikasi, TTE) ada di route group `admin.*` |
| **Dampak Teknis** | `VerifikasiSuratController::approve()` (TTE Kades) dijalankan oleh Admin. `$request->user()` yang tercatat di `ActivityLog` = Admin, bukan Kades |
| **Dampak Bisnis** | Audit trail tidak valid — setiap TTE tercatat atas nama Admin, bukan Kepala Desa. Surat yang "ditandatangani" tidak memiliki legitimasi digital |
| **Risiko** | **KRITIS** — dokumen desa yang dikeluarkan tidak memiliki chain of authority yang valid |

### GAP-03: Verifikasi PIN TTE Fiktif

| Aspek | Detail |
|---|---|
| **Gap** | `VerifikasiSuratController::approve()` menerima PIN 6 digit tapi **tidak memverifikasi terhadap hash apapun** (komentar: "we accept any 6-digit PIN as valid") |
| **Dampak Teknis** | Siapapun yang punya akses admin bisa TTE surat dengan PIN acak |
| **Dampak Bisnis** | Tanda Tangan Elektronik tidak memiliki validitas hukum |
| **Risiko** | Pemalsuan dokumen resmi desa |

---

## 2. Database Schema Gap

### GAP-04: Missing Columns di Migration `surat_permohonan`

| Aspek | Detail |
|---|---|
| **Gap** | Model `SuratPermohonan` memiliki `$fillable` dengan `keperluan`, `berlaku_hingga`, `nama_usaha` — tetapi kolom-kolom ini **TIDAK ADA** di migration `2026_04_19_190000` |
| **Dampak Teknis** | `WargaLayananSuratController::store()` dan `LayananSuratController::storeWizard()` akan **GAGAL** saat insert karena kolom tidak exist di DB |
| **Dampak Bisnis** | **Fitur pengajuan surat TIDAK BISA DIGUNAKAN** — baik dari sisi warga maupun admin |
| **Risiko** | **SHOWSTOPPER** — core feature broken |

### GAP-05: Status `draft` Tidak Ada di Enum Migration

| Aspek | Detail |
|---|---|
| **Gap** | Migration enum `status`: `['pengajuan', 'verifikasi', 'menunggu_tte', 'selesai', 'ditolak']` — tetapi model dan controller menggunakan status `'draft'` (`scopeDraft()`, `storeWizard()`) |
| **Dampak Teknis** | Insert surat dengan `status = 'draft'` akan throw DB error pada MySQL strict mode |
| **Dampak Bisnis** | Fitur "Simpan sebagai Draft" di wizard buat surat GAGAL |
| **Risiko** | **SHOWSTOPPER** pada fitur wizard |

### GAP-06: NIK Mismatch Warga ↔ Penduduk

| Aspek | Detail |
|---|---|
| **Gap** | `WargaSeeder` NIK: `3207261234560001-0005`. `PendudukSeeder` NIK: `3209123456780001-0050`. **Tidak ada NIK yang sama** |
| **Dampak Teknis** | `WargaDashboardController::index()` → `Penduduk::where('nik', $warga->nik)->first()` selalu return `null` |
| **Dampak Bisnis** | Dashboard warga **kosong** — tidak bisa lihat surat, tidak bisa ajukan surat (akan ditolak di line 77-81 `WargaLayananSuratController`) |
| **Risiko** | **SHOWSTOPPER** — seluruh flow layanan mandiri patah total |

---

## 3. Business Logic Gap

### GAP-07: Alur Workflow Surat Tidak Lengkap

```
Expected Flow (ADPL):
Warga Ajukan → Operator Verifikasi → Kades TTE → Selesai/Cetak

Current Implementation:
Admin Buat (wizard) → Admin Update Status → Admin TTE → Arsip
                                          ↗
Warga Ajukan (BROKEN — GAP-04/06) ─────╯
```

| Aspek | Detail |
|---|---|
| **Gap** | Tidak ada mekanisme bridge: surat yang diajukan warga (`WargaLayananSuratController::store()`) tidak otomatis muncul di antrian operator. Hanya tergabung via `penduduk_id` lookup |
| **Dampak** | Operator harus manually check database. Tidak ada notifikasi ke operator saat ada pengajuan baru |

### GAP-08: Upload File / Lampiran Tidak Diimplementasi

| Aspek | Detail |
|---|---|
| **Gap** | Migration `surat_permohonan` tidak memiliki kolom untuk lampiran file. Controller tidak handle file upload. View `ajukan-surat.blade.php` mungkin memiliki UI upload tapi tidak ada backend |
| **Dampak** | Warga tidak bisa melampirkan dokumen pendukung (KTP, KK, dll) |

### GAP-09: Generate PDF / Cetak Surat Belum Ada

| Aspek | Detail |
|---|---|
| **Gap** | Tidak ada controller/service untuk generate PDF surat. `TemplateSurat` model ada dengan `body_template` dan field placeholders, tapi tidak ada kode yang merender template ke PDF |
| **Dampak** | Alur selesai hanya update status, tidak menghasilkan dokumen cetak |

---

## 4. Security Gap

### GAP-10: Session Conflict Dual Guard

| Aspek | Detail |
|---|---|
| **Gap** | Guard `web` dan `warga` keduanya menggunakan driver `session`. Jika user login sebagai Admin DAN Warga di browser yang sama, session bisa konflik |
| **Dampak** | Logout dari satu guard bisa menginvalidasi session guard lain (lihat `WargaAuthController::logout()` yang call `session()->invalidate()`) |

### GAP-11: Tidak Ada Rate Limiting di BackOffice

| Aspek | Detail |
|---|---|
| **Gap** | `WargaAuthController` memiliki rate limiting (5 attempts/minute) tapi BackOffice login (Fortify) tidak di-custom |
| **Dampak** | Brute force risk pada admin panel |
