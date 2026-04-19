@extends('layouts.frontend')

@section('title', 'Pusat Layanan Digital - Desa Sindangmukti')

@section('content')
<main class="flex-grow pt-16 bg-gray-50">

    <!-- SECTION 1: HEADER LAYANAN -->
    <section class="relative py-16 overflow-hidden text-white bg-gradient-to-br from-[#2e7d32] to-[#1b5e20] md:py-20">
        <!-- Pattern Overlay -->
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
        </div>

        <div class="relative z-10 flex flex-col items-center gap-10 px-4 mx-auto max-w-7xl sm:px-6 lg:px-8 md:flex-row">
            <div class="w-full text-center md:w-3/5 md:text-left">
                <span class="bg-green-700/50 text-green-100 text-sm font-semibold px-4 py-1.5 rounded-full border border-green-500/30 mb-5 inline-block">
                    <svg class="inline-block w-4 h-4 mr-1 pb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg> 
                    E-Layanan Desa
                </span>
                <h1 class="mb-5 text-4xl font-bold leading-tight tracking-tight md:text-5xl">Pusat Layanan Digital<br>Masyarakat Sindangmukti</h1>
                <p class="max-w-lg mx-auto mb-8 text-lg leading-relaxed text-green-100 md:mx-0">
                    Akses berbagai layanan administrasi dan pelaporan dengan mudah, cepat, dan transparan langsung dari genggaman Anda.
                </p>
            </div>

            <div class="hidden w-full md:w-2/5 md:block">
                <!-- Ilustrasi Sederhana menggunakan Icon -->
                <div class="flex items-center justify-center p-8 transition-transform duration-500 transform rotate-2 shadow-2xl bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl hover:rotate-0">
                    <svg class="text-green-200/80 drop-shadow-lg w-44 h-44" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
        </div>
    </section>

    <div class="px-4 py-12 mx-auto max-w-7xl sm:px-6 lg:px-8">

        <!-- SECTION 2: LAYANAN UTAMA (Grid Cards) -->
        <section class="relative z-20 mb-16 -mt-20">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

                <!-- Card Layanan 1: Pengaduan (Aktif) -->
                <article class="flex flex-col h-full p-8 transition-all duration-300 bg-white border-t-4 border-amber-500 shadow-lg rounded-2xl hover:-translate-y-2 hover:shadow-xl group">
                    <div class="flex items-center justify-center w-16 h-16 mb-6 transition-transform shadow-inner bg-amber-100 text-amber-600 rounded-2xl group-hover:scale-110">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    </div>
                    <h2 class="mb-3 text-2xl font-bold text-gray-800">Layanan Pengaduan</h2>
                    <p class="flex-grow mb-6 leading-relaxed text-gray-600">
                        Sampaikan laporan, keluhan, atau aspirasi Anda terkait fasilitas desa, pelayanan aparat, maupun infrastruktur. Laporan Anda akan diproses secara rahasia.
                    </p>
                    <a href="{{ route('layanan.pengaduan') }}" class="flex items-center justify-center w-full gap-2 px-4 py-3 font-bold text-center text-white transition-colors bg-amber-500 shadow-sm hover:bg-amber-600 rounded-xl">
                        Buat Laporan <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </article>

                <!-- Card Layanan 2: Administrasi Surat (Placeholder/Coming Soon) -->
                <article class="relative flex flex-col h-full p-8 overflow-hidden transition-all bg-white border border-gray-200 shadow-lg rounded-2xl opacity-75 hover:opacity-100">
                    <!-- Label Coming Soon -->
                    <div class="absolute top-6 right-0 bg-blue-600 text-white text-xs font-bold px-4 py-1 rounded-l-full shadow-md z-10">
                        Segera Hadir
                    </div>
                    <div class="flex items-center justify-center w-16 h-16 mb-6 shadow-inner grayscale bg-blue-50 text-blue-500 rounded-2xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"></path></svg>
                    </div>
                    <h2 class="mb-3 text-2xl font-bold text-gray-800">Surat Online</h2>
                    <p class="flex-grow mb-6 leading-relaxed text-gray-600">
                        Pengajuan pembuatan surat pengantar, keterangan domisili, SKTM, dan administrasi lainnya secara mandiri dari rumah tanpa harus antre.
                    </p>
                    <button disabled class="flex items-center justify-center w-full gap-2 px-4 py-3 font-bold text-center text-gray-400 border border-gray-200 cursor-not-allowed bg-gray-100 rounded-xl">
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg> 
                        Belum Tersedia
                    </button>
                </article>

                <!-- Card Layanan 3: Cek Bansos (Placeholder/Coming Soon) -->
                <article class="relative flex flex-col h-full p-8 overflow-hidden transition-all bg-white border border-gray-200 shadow-lg rounded-2xl opacity-75 hover:opacity-100">
                    <div class="absolute top-6 right-0 bg-blue-600 text-white text-xs font-bold px-4 py-1 rounded-l-full shadow-md z-10">
                        Segera Hadir
                    </div>
                    <div class="flex items-center justify-center w-16 h-16 mb-6 shadow-inner grayscale bg-emerald-50 text-emerald-500 rounded-2xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h2 class="mb-3 text-2xl font-bold text-gray-800">Cek Bantuan Sosial</h2>
                    <p class="flex-grow mb-6 leading-relaxed text-gray-600">
                        Cek status kepesertaan Anda atau keluarga dalam program bantuan sosial desa (BLT Dana Desa, PKH, BPNT) secara mandiri hanya dengan NIK.
                    </p>
                    <button disabled class="flex items-center justify-center w-full gap-2 px-4 py-3 font-bold text-center text-gray-400 border border-gray-200 cursor-not-allowed bg-gray-100 rounded-xl">
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg> 
                        Belum Tersedia
                    </button>
                </article>

            </div>
        </section>

        <!-- SECTION 3: INFORMASI LAYANAN (Panduan/Edukasi) -->
        <section class="p-8 mb-16 bg-white border border-gray-100 shadow-sm rounded-3xl md:p-12">
            <div class="mb-10 text-center">
                <span class="text-sm font-bold tracking-wider uppercase text-green-600">Panduan Masyarakat</span>
                <h2 class="mt-2 text-3xl font-bold text-gray-800">Bagaimana Cara Kerja Layanan Digital?</h2>
            </div>

            <div class="relative grid grid-cols-1 gap-8 md:grid-cols-3">
                <!-- Garis Penghubung (Hanya terlihat di Desktop) -->
                <div class="hidden md:block absolute top-12 left-[16.66%] right-[16.66%] h-0.5 bg-gray-200 z-0"></div>

                <!-- Step 1 -->
                <div class="relative z-10 flex flex-col items-center text-center group">
                    <div class="flex items-center justify-center w-24 h-24 mb-4 transition-colors bg-white border-4 border-green-100 rounded-full shadow-sm group-hover:border-green-500">
                        <svg class="w-10 h-10 text-gray-400 transition-colors group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                    </div>
                    <h3 class="mb-2 text-xl font-bold text-gray-800">1. Pilih Layanan</h3>
                    <p class="text-sm text-gray-500">Pilih jenis layanan yang Anda butuhkan melalui menu di atas (Misal: Pengaduan).</p>
                </div>

                <!-- Step 2 -->
                <div class="relative z-10 flex flex-col items-center text-center group">
                    <div class="flex items-center justify-center w-24 h-24 mb-4 transition-colors bg-white border-4 border-green-100 rounded-full shadow-sm group-hover:border-green-500">
                        <svg class="w-10 h-10 text-gray-400 transition-colors group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="mb-2 text-xl font-bold text-gray-800">2. Isi Formulir</h3>
                    <p class="text-sm text-gray-500">Lengkapi data diri Anda sesuai KTP dan berikan rincian permintaan yang jelas.</p>
                </div>

                <!-- Step 3 -->
                <div class="relative z-10 flex flex-col items-center text-center group">
                    <div class="flex items-center justify-center w-24 h-24 mb-4 transition-colors bg-white border-4 border-green-100 rounded-full shadow-sm group-hover:border-green-500">
                        <svg class="w-10 h-10 text-gray-400 transition-colors group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="mb-2 text-xl font-bold text-gray-800">3. Diproses & Selesai</h3>
                    <p class="text-sm text-gray-500">Notifikasi akan dikirimkan dan layanan Anda ditindaklanjuti secara online.</p>
                </div>
            </div>
        </section>

        <!-- SECTION 4: CTA SECTION (Bantuan Langsung) -->
        <section class="relative overflow-hidden shadow-xl bg-gray-900 rounded-3xl">
            <!-- Elemen background abstrak -->
            <div class="absolute w-64 h-64 bg-green-600 rounded-full opacity-20 -right-10 -top-10 blur-3xl"></div>

            <div class="relative z-10 flex flex-col items-center justify-between gap-8 px-8 py-12 md:p-14 md:flex-row">
                <div class="text-center md:w-2/3 md:text-left">
                    <h2 class="mb-4 text-3xl font-bold text-white">Butuh Bantuan Mendesak?</h2>
                    <p class="mb-0 text-lg leading-relaxed text-gray-400">
                        Jika Anda mengalami kendala darurat, butuh pelayanan offline segera, atau bingung menggunakan layanan ini, petugas kami siap membantu Anda melalui WhatsApp.
                    </p>
                </div>

                <div class="flex justify-center shrink-0 md:w-1/3 md:justify-end">
                    <a href="https://wa.me/6281234567890" target="_blank" class="flex items-center gap-3 px-8 py-4 font-bold text-white transition-all transform border border-green-500 shadow-lg bg-[#2e7d32] hover:bg-[#1b5e20] rounded-2xl hover:scale-105">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.0006 2.05203C6.46337 2.05203 1.95465 6.55018 1.95465 12.0838C1.95465 13.8427 2.40938 15.5451 3.25997 17.0673L2.09115 21.4338L6.61109 20.2526C8.0645 21.0503 9.7118 21.4883 11.3934 21.4883C11.3963 21.4883 11.3992 21.4883 12.0006 21.4883C17.535 21.4883 22.0466 16.99 22.0466 11.4565C22.0437 8.78441 21.0001 6.27364 19.1054 4.38289C17.2098 2.49021 14.6932 1.44855 12.0006 1.44855V2.05203ZM12.0006 19.8055C10.4907 19.8055 9.02055 19.4005 7.74737 18.6438L7.44754 18.4655L4.76727 19.1678L5.49129 16.536L5.2971 16.2238C4.47141 14.9224 4.03265 13.3857 4.03265 11.8211C4.03265 7.42418 7.61869 3.84196 12.0209 3.84196C14.1565 3.84486 16.1472 4.6766 17.6534 6.18522C19.1576 7.69383 19.9855 9.68832 19.9855 11.8288C19.9855 16.2248 16.4024 19.8055 12.0006 19.8055ZM16.3861 13.805C16.1457 13.6841 14.9657 13.102 14.7472 13.0217C14.5267 12.9424 14.3663 12.9018 14.2059 13.1436C14.0454 13.3854 13.5843 13.928 13.4442 14.1283C13.3031 14.3295 13.162 14.3508 12.9215 14.2299C12.6811 14.109 11.8986 13.8546 10.9703 13.0333C10.2458 12.394 9.76159 11.597 9.62047 11.3562C9.47936 11.1154 9.60505 10.9858 9.72688 10.8658C9.83516 10.7585 9.96665 10.5844 10.0875 10.4442C10.2074 10.304 10.247 10.2034 10.3273 10.043C10.4075 9.8825 10.3679 9.7423 10.3079 9.6214C10.2479 9.5005 9.76642 8.31575 9.56637 7.83415C9.36632 7.35255 9.16723 7.43282 9.02611 7.43282C8.885 7.43282 8.72559 7.41249 8.56617 7.41249C8.40675 7.41249 8.14629 7.47245 7.92583 7.71325C7.70537 7.95405 7.08343 8.53582 7.08343 9.72051C7.08343 10.9052 7.94614 12.0494 8.06604 12.2099C8.18594 12.3704 9.76545 14.8576 12.23 15.922C12.8166 16.1754 13.2721 16.3263 13.6294 16.4385C14.2202 16.6261 14.7578 16.598 15.1833 16.5275C15.6562 16.4491 16.6385 15.9269 16.8395 15.3655C17.0396 14.804 17.0396 14.3225 16.9796 14.2229C16.9196 14.1223 16.7592 14.0624 16.5187 13.9425L16.3861 13.805Z"></path></svg>
                        <div class="text-left">
                            <span class="block text-xs font-normal text-green-100">Hubungi Admin</span>
                            <span class="block text-lg">0812-3456-7890</span>
                        </div>
                    </a>
                </div>
            </div>
        </section>

    </div>
</main>
@endsection
