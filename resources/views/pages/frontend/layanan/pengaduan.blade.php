@extends('layouts.frontend')

@section('title', 'Layanan Pengaduan Masyarakat - Desa Sindangmukti')

@push('styles')
<style>
    /* Styling native detail/summary accordion untuk FAQ */
    details > summary {
        list-style: none;
    }
    details > summary::-webkit-details-marker {
        display: none;
    }
</style>
@endpush

@section('content')
<main class="flex-grow pt-16 bg-gray-50">

    <!-- SECTION 1: HEADER PENGADUAN -->
    <section class="relative py-12 overflow-hidden text-white bg-gradient-to-br from-amber-600 to-amber-500 md:py-16">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="relative z-10 flex flex-col items-center justify-between gap-8 px-4 mx-auto max-w-7xl sm:px-6 lg:px-8 md:flex-row">
            <div class="w-full text-center md:w-2/3 md:text-left">
                <a href="{{ route('layanan') }}" class="inline-flex items-center mb-4 text-sm font-medium transition-colors text-amber-100 hover:text-white">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg> 
                    Kembali ke Layanan
                </a>
                <h1 class="mb-4 text-3xl font-bold leading-tight tracking-tight md:text-5xl">{{ $pageTitle }}</h1>
                <p class="max-w-xl mx-auto text-lg leading-relaxed text-amber-50 md:mx-0">
                    {{ $pageSubtitle }}
                </p>
            </div>
            <div class="hidden w-full justify-end md:w-1/3 md:flex">
                <svg class="transform -rotate-12 w-44 h-44 text-amber-300/50 drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
            </div>
        </div>
    </section>

    <!-- LAYOUT UTAMA (Grid 2 Kolom) -->
    <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="grid items-start grid-cols-1 gap-8 lg:grid-cols-3">
            
            <!-- KOLOM KIRI (Form & Riwayat) -->
            <div class="space-y-10 lg:col-span-2">
                
                <!-- SECTION 2 & 3: FORM PENGADUAN & UPLOAD BUKTI -->
                <section class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
                    <div class="flex items-center gap-3 px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full shrink-0 bg-amber-100 text-amber-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">Formulir Laporan Baru</h2>
                    </div>
                    
                    <form class="p-6 md:p-8">
                        <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
                            <!-- Input NIK -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Nomor Induk Kependudukan (NIK) <span class="text-red-500">*</span></label>
                                <input type="text" placeholder="16 Digit NIK Anda" class="block w-full p-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-amber-500 focus:border-amber-500" required>
                            </div>
                            <!-- Input Nama -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" placeholder="Sesuai KTP" class="block w-full p-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-amber-500 focus:border-amber-500" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
                            <!-- Input Kontak -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Nomor WhatsApp Aktif <span class="text-red-500">*</span></label>
                                <input type="tel" placeholder="Contoh: 08123456789" class="block w-full p-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-amber-500 focus:border-amber-500" required>
                            </div>
                            <!-- Input Kategori -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Kategori Laporan <span class="text-red-500">*</span></label>
                                <select class="block w-full p-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-amber-500 focus:border-amber-500" required>
                                    <option value="" disabled selected>Pilih Kategori...</option>
                                    <option value="infrastruktur">Infrastruktur (Jalan, Jembatan, Irigasi)</option>
                                    <option value="pelayanan">Pelayanan Aparatur Desa</option>
                                    <option value="kesehatan">Kesehatan & Lingkungan (Sampah, dsb)</option>
                                    <option value="keamanan">Ketertiban & Keamanan</option>
                                    <option value="lainnya">Lainnya / Aspirasi</option>
                                </select>
                            </div>
                        </div>

                        <!-- Input Isi Laporan -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Detail Laporan <span class="text-red-500">*</span></label>
                            <textarea rows="5" placeholder="Jelaskan secara detail kejadian, lokasi, atau aspirasi Anda..." class="block w-full p-3 text-sm text-gray-900 border border-gray-300 rounded-lg resize-none bg-gray-50 focus:ring-amber-500 focus:border-amber-500" required></textarea>
                            <p class="mt-2 text-xs text-gray-500">Gunakan bahasa yang sopan, jelas, dan hindari unsur SARA.</p>
                        </div>

                        <!-- SECTION 3: UPLOAD BUKTI (Visual Drag & Drop) -->
                        <div class="mb-8">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Lampiran Bukti (Foto/Dokumen)</label>
                            <div class="relative w-full p-6 text-center transition-colors border-2 border-gray-300 border-dashed cursor-pointer rounded-xl md:p-8 hover:bg-amber-50 hover:border-amber-400 group">
                                <input type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" multiple accept="image/*,.pdf">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <div class="flex items-center justify-center w-12 h-12 transition-colors bg-gray-100 rounded-full text-gray-400 group-hover:bg-amber-100 group-hover:text-amber-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700 group-hover:text-amber-600">Klik untuk unggah atau seret file ke sini</p>
                                        <p class="mt-1 text-xs text-gray-500">Maks. 5MB (Format: JPG, PNG, PDF)</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Privacy Toggle & Submit -->
                        <div class="flex flex-col items-start justify-between gap-4 pt-6 border-t border-gray-100 sm:flex-row sm:items-center">
                            <div class="flex items-center">
                                <input id="anonim" type="checkbox" class="w-4 h-4 border-gray-300 rounded cursor-pointer text-amber-600 bg-gray-100 focus:ring-amber-500 focus:ring-2">
                                <label for="anonim" class="ml-2 text-sm font-medium text-gray-700 cursor-pointer">Sembunyikan nama saya (Anonim)</label>
                            </div>
                            <button type="button" class="flex items-center justify-center w-full gap-2 px-8 py-3.5 text-sm font-bold text-center text-white transition-colors shadow-md bg-amber-500 hover:bg-amber-600 focus:ring-4 focus:outline-none focus:ring-amber-300 rounded-xl sm:w-auto">
                                Kirim Laporan <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </button>
                        </div>
                    </form>
                </section>

                <!-- SECTION 5: RIWAYAT PENGADUAN PUBLIK -->
                <section>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Laporan Publik Terbaru</h2>
                        <a href="#" class="text-sm font-semibold text-green-600 hover:text-green-800">Lihat Semua</a>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Card Riwayat 1 -->
                        <article class="p-5 transition-colors bg-white border border-gray-200 rounded-xl shadow-sm hover:border-gray-300">
                            <div class="flex flex-col items-start justify-between gap-2 mb-3 sm:flex-row sm:items-center">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name=Anonim&background=f3f4f6&color=6b7280" alt="Avatar" class="w-8 h-8 rounded-full">
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">Anonim</p>
                                        <p class="text-xs text-gray-500">22 Okt 2024 • Kategori: Infrastruktur</p>
                                    </div>
                                </div>
                                <span class="flex items-center px-3 py-1 text-xs font-bold border rounded-full bg-amber-100 text-amber-700 border-amber-200">
                                    <svg class="w-3 h-3 mr-1.5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> 
                                    Sedang Diproses
                                </span>
                            </div>
                            <h3 class="mb-2 font-bold text-gray-800">Jalan Berlubang di Area Dusun II</h3>
                            <p class="text-sm text-gray-600 line-clamp-2">Mohon perbaikan jalan berlubang di poros utama Dusun II depan masjid karena sangat membahayakan pengendara motor terutama saat hujan...</p>
                        </article>

                        <!-- Card Riwayat 2 -->
                        <article class="p-5 transition-colors bg-white border border-gray-200 rounded-xl shadow-sm hover:border-gray-300">
                            <div class="flex flex-col items-start justify-between gap-2 mb-3 sm:flex-row sm:items-center">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=d1fae5&color=065f46" alt="Avatar" class="w-8 h-8 rounded-full">
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">Budi Santoso</p>
                                        <p class="text-xs text-gray-500">18 Okt 2024 • Kategori: Pelayanan</p>
                                    </div>
                                </div>
                                <span class="flex items-center px-3 py-1 text-xs font-bold border rounded-full bg-green-100 text-green-700 border-green-200">
                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 
                                    Selesai
                                </span>
                            </div>
                            <h3 class="mb-2 font-bold text-gray-800">Pembuatan Kartu Keluarga Lama</h3>
                            <p class="text-sm text-gray-600 line-clamp-2">Pelayanan pembuatan KK di balai desa sangat responsif dan cepat selesai. Terima kasih perangkat desa Sindangmukti.</p>
                        </article>
                    </div>
                </section>
            </div>

            <!-- KOLOM KANAN (Sidebar: Tracking, FAQ, CTA) -->
            <aside class="space-y-8 lg:col-span-1">
                
                <!-- SECTION 4: STATUS PENGADUAN (Tracking) -->
                <section class="relative overflow-hidden p-6 bg-white border border-gray-200 shadow-sm rounded-2xl">
                    <!-- Dekorasi Background -->
                    <div class="absolute opacity-50 -right-6 -top-6 text-gray-50">
                        <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>

                    <div class="relative z-10">
                        <h2 class="mb-2 text-xl font-bold text-gray-800">Lacak Laporan</h2>
                        <p class="mb-5 text-sm text-gray-500">Masukkan ID Tiket yang Anda terima untuk mengetahui status laporan.</p>
                        
                        <form class="mb-6">
                            <div class="relative">
                                <input type="text" placeholder="Contoh: TKT-2410-001" class="block w-full p-3 pr-12 font-mono text-sm text-gray-900 uppercase border border-gray-300 rounded-lg bg-gray-50 focus:ring-green-500 focus:border-green-500">
                                <button type="button" class="absolute flex items-center justify-center w-8 h-8 text-sm text-white transition-colors transform -translate-y-1/2 bg-green-600 rounded right-2 top-1/2 hover:bg-green-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </button>
                            </div>
                        </form>

                        <!-- Hasil Tracking (Simulasi Data Muncul) -->
                        <div class="pt-5 mt-2 border-t border-gray-100">
                            <p class="mb-4 text-xs font-bold tracking-wider text-gray-400 uppercase">Hasil Pelacakan:</p>
                            <div class="p-4 border border-gray-200 bg-gray-50 rounded-xl">
                                <p class="mb-1 font-bold text-gray-800">TKT-2410-001</p>
                                <p class="mb-4 text-xs text-gray-500">Jalan Berlubang di Dusun II</p>
                                
                                <!-- Vertical Timeline Status -->
                                <div class="relative ml-2 pl-4 space-y-4 border-l-2 border-gray-200">
                                    <!-- Step 1 (Done) -->
                                    <div class="relative">
                                        <div class="absolute -left-[21px] bg-green-500 w-3 h-3 rounded-full border-2 border-white ring-2 ring-green-100 mt-1"></div>
                                        <p class="text-sm font-bold text-gray-800">Laporan Diterima</p>
                                        <p class="text-xs text-gray-500">22 Okt 2024, 09:15</p>
                                    </div>
                                    <!-- Step 2 (Active) -->
                                    <div class="relative">
                                        <div class="absolute -left-[21px] bg-amber-500 w-3 h-3 rounded-full border-2 border-white ring-2 ring-amber-100 mt-1 animate-pulse"></div>
                                        <p class="text-sm font-bold text-amber-600">Sedang Ditinjau</p>
                                        <p class="text-xs text-gray-500">Diserahkan ke Kaur Pembangunan</p>
                                    </div>
                                    <!-- Step 3 (Pending) -->
                                    <div class="relative">
                                        <div class="absolute -left-[21px] bg-gray-300 w-3 h-3 rounded-full border-2 border-white mt-1"></div>
                                        <p class="text-sm font-medium text-gray-400">Selesai / Ditindaklanjuti</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SECTION 6: FAQ PENGADUAN -->
                <section class="p-6 bg-white border border-gray-200 shadow-sm rounded-2xl">
                    <h2 class="pb-2 mb-5 text-xl font-bold border-b text-gray-800 border-gray-100">Pertanyaan Umum (FAQ)</h2>
                    
                    <div class="space-y-3">
                        <!-- FAQ Item 1 -->
                        <details class="group bg-gray-50 rounded-lg border border-gray-200 [&_summary::-webkit-details-marker]:hidden cursor-pointer">
                            <summary class="flex items-center justify-between p-4 text-sm font-semibold text-gray-800">
                                Apakah identitas pelapor aman?
                                <span class="transition group-open:rotate-180">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </span>
                            </summary>
                            <div class="px-4 pt-3 pb-4 mt-1 text-sm leading-relaxed text-gray-600 border-t border-gray-100">
                                Sangat aman. Sistem kami memisahkan identitas pelapor dari isi laporan yang diteruskan ke publik/petugas lapangan. Anda juga bisa memilih fitur "Anonim".
                            </div>
                        </details>

                        <!-- FAQ Item 2 -->
                        <details class="group bg-gray-50 rounded-lg border border-gray-200 [&_summary::-webkit-details-marker]:hidden cursor-pointer">
                            <summary class="flex items-center justify-between p-4 text-sm font-semibold text-gray-800">
                                Berapa lama laporan ditindaklanjuti?
                                <span class="transition group-open:rotate-180">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </span>
                            </summary>
                            <div class="px-4 pt-3 pb-4 mt-1 text-sm leading-relaxed text-gray-600 border-t border-gray-100">
                                Standar Operasional Prosedur (SOP) kami adalah memberikan respon awal maksimal 2x24 jam hari kerja.
                            </div>
                        </details>

                        <!-- FAQ Item 3 -->
                        <details class="group bg-gray-50 rounded-lg border border-gray-200 [&_summary::-webkit-details-marker]:hidden cursor-pointer">
                            <summary class="flex items-center justify-between p-4 text-sm font-semibold text-gray-800">
                                Di mana saya melihat ID Tiket?
                                <span class="transition group-open:rotate-180">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </span>
                            </summary>
                            <div class="px-4 pt-3 pb-4 mt-1 text-sm leading-relaxed text-gray-600 border-t border-gray-100">
                                ID Tiket akan muncul di layar setelah Anda berhasil mengirim laporan, dan juga akan dikirimkan ke nomor WhatsApp yang Anda daftarkan.
                            </div>
                        </details>
                    </div>
                </section>

                <!-- SECTION 7: CTA BANTUAN -->
                <section class="relative overflow-hidden p-6 text-center text-white shadow-lg bg-gray-900 rounded-2xl">
                    <div class="absolute w-24 h-24 bg-green-500 rounded-full opacity-20 -right-4 -bottom-4 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 border rounded-full bg-white/10 border-white/20">
                            <svg class="w-6 h-6 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h3 class="mb-2 text-lg font-bold">Kesulitan Lapor Online?</h3>
                        <p class="mb-5 text-sm leading-relaxed text-gray-400">
                            Petugas desa siap membantu Anda mencatat laporan secara manual melalui WhatsApp.
                        </p>
                        <a href="https://wa.me/6281234567890" target="_blank" class="flex items-center justify-center w-full gap-2 px-4 py-3 font-bold transition-colors bg-green-600 hover:bg-green-500 rounded-xl">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.0006 2.05203C6.46337 2.05203 1.95465 6.55018 1.95465 12.0838C1.95465 13.8427 2.40938 15.5451 3.25997 17.0673L2.09115 21.4338L6.61109 20.2526C8.0645 21.0503 9.7118 21.4883 11.3934 21.4883C11.3963 21.4883 11.3992 21.4883 12.0006 21.4883C17.535 21.4883 22.0466 16.99 22.0466 11.4565C22.0437 8.78441 21.0001 6.27364 19.1054 4.38289C17.2098 2.49021 14.6932 1.44855 12.0006 1.44855V2.05203ZM12.0006 19.8055C10.4907 19.8055 9.02055 19.4005 7.74737 18.6438L7.44754 18.4655L4.76727 19.1678L5.49129 16.536L5.2971 16.2238C4.47141 14.9224 4.03265 13.3857 4.03265 11.8211C4.03265 7.42418 7.61869 3.84196 12.0209 3.84196C14.1565 3.84486 16.1472 4.6766 17.6534 6.18522C19.1576 7.69383 19.9855 9.68832 19.9855 11.8288C19.9855 16.2248 16.4024 19.8055 12.0006 19.8055ZM16.3861 13.805C16.1457 13.6841 14.9657 13.102 14.7472 13.0217C14.5267 12.9424 14.3663 12.9018 14.2059 13.1436C14.0454 13.3854 13.5843 13.928 13.4442 14.1283C13.3031 14.3295 13.162 14.3508 12.9215 14.2299C12.6811 14.109 11.8986 13.8546 10.9703 13.0333C10.2458 12.394 9.76159 11.597 9.62047 11.3562C9.47936 11.1154 9.60505 10.9858 9.72688 10.8658C9.83516 10.7585 9.96665 10.5844 10.0875 10.4442C10.2074 10.304 10.247 10.2034 10.3273 10.043C10.4075 9.8825 10.3679 9.7423 10.3079 9.6214C10.2479 9.5005 9.76642 8.31575 9.56637 7.83415C9.36632 7.35255 9.16723 7.43282 9.02611 7.43282C8.885 7.43282 8.72559 7.41249 8.56617 7.41249C8.40675 7.41249 8.14629 7.47245 7.92583 7.71325C7.70537 7.95405 7.08343 8.53582 7.08343 9.72051C7.08343 10.9052 7.94614 12.0494 8.06604 12.2099C8.18594 12.3704 9.76545 14.8576 12.23 15.922C12.8166 16.1754 13.2721 16.3263 13.6294 16.4385C14.2202 16.6261 14.7578 16.598 15.1833 16.5275C15.6562 16.4491 16.6385 15.9269 16.8395 15.3655C17.0396 14.804 17.0396 14.3225 16.9796 14.2229C16.9196 14.1223 16.7592 14.0624 16.5187 13.9425L16.3861 13.805Z"></path></svg> 
                            Hubungi CS Desa
                        </a>
                    </div>
                </section>

            </aside>
        </div>
    </div>
</main>
@endsection
