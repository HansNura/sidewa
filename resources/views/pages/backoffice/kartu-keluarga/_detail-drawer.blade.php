{{-- Detail KK Drawer --}}
<div x-show="detailDrawerOpen" class="fixed inset-0 z-[100] flex justify-end !m-0" x-cloak>
    <div x-show="detailDrawerOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="detailDrawerOpen = false"></div>

    <div x-show="detailDrawerOpen" x-transition:enter="transition ease-transform duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-transform duration-300" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="relative bg-white w-full max-w-4xl h-full shadow-2xl flex flex-col">

        {{-- Premium Header --}}
        <div
            class="px-4 py-5 sm:px-6 sm:py-8 bg-gradient-to-br from-green-800 to-emerald-950 flex justify-between items-start shrink-0 relative overflow-hidden">
            <!-- Decorative Pattern -->
            <div
                class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white/10 blur-3xl pointer-events-none">
            </div>
            <div
                class="absolute bottom-0 left-0 ml-10 -mb-10 w-32 h-32 rounded-full bg-green-500/20 blur-2xl pointer-events-none">
            </div>

            <div class="flex gap-3 sm:gap-5 items-start sm:items-center relative z-10 flex-1 pr-4">
                <div
                    class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl bg-white/10 border border-white/20 text-white flex items-center justify-center text-xl sm:text-3xl shadow-inner backdrop-blur-md shrink-0 mt-0.5 sm:mt-0">
                    <i class="fa-solid fa-users-rectangle"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-extrabold text-xl sm:text-3xl text-white tracking-tight mb-2 sm:mb-1 leading-tight">
                        Detail Kartu Keluarga</h3>
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                        <span
                            class="bg-green-700 text-green-50 text-[10px] sm:text-xs font-mono font-bold px-2 sm:px-2.5 py-1 rounded-lg border border-green-600/50 shadow-sm flex items-center gap-1.5 sm:gap-2">
                            <i class="fa-solid fa-hashtag text-green-400"></i>
                            <span x-text="detail?.no_kk ?? ''"></span>
                        </span>
                        <span class="text-green-200 text-[10px] sm:text-xs font-medium flex items-center gap-1.5"
                            x-show="detail?.tanggal_dikeluarkan">
                            <i class="fa-regular fa-calendar-check shrink-0"></i> <span
                                x-text="'Sejak ' + detail?.tanggal_dikeluarkan"></span>
                        </span>
                    </div>
                </div>
            </div>
            <button @click="detailDrawerOpen = false"
                class="relative z-10 text-green-200 hover:text-white w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center rounded-lg sm:rounded-xl hover:bg-white/10 transition-colors cursor-pointer backdrop-blur-sm shrink-0 bg-white/5 sm:bg-transparent">
                <i class="fa-solid fa-xmark text-xl sm:text-2xl"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-8 bg-gray-50" x-show="detail">

            {{-- Premium Info Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Info Utama Card -->
                <div
                    class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-green-50/50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110 pointer-events-none">
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-5">
                            <div
                                class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-address-card"></i>
                            </div>
                            <h5 class="text-sm font-extrabold text-gray-800 tracking-wide">Informasi Utama</h5>
                        </div>
                        <div class="space-y-5 text-sm">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Kepala
                                    Keluarga</p>
                                <p class="font-black text-gray-900 text-lg flex items-center gap-2">
                                    <span x-text="detail?.kepala ?? '-'"></span>
                                    <i class="fa-solid fa-circle-check text-green-500 text-sm" title="Terverifikasi"
                                        x-show="detail?.kepala"></i>
                                </p>
                            </div>
                            <div class="grid grid-cols-2 gap-4 pt-1">
                                <div class="bg-gray-50 border border-gray-100 rounded-xl p-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total
                                        Anggota</p>
                                    <p class="font-extrabold text-gray-800 text-base flex items-center gap-2">
                                        <span x-text="(detail?.jumlah_anggota ?? 0)"></span> Jiwa
                                    </p>
                                </div>
                                <div class="bg-gray-50 border border-gray-100 rounded-xl p-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Status
                                        KK</p>
                                    <p class="font-bold text-green-600 text-base flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Aktif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alamat Card -->
                <div
                    class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-blue-50/50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110 pointer-events-none">
                    </div>
                    <div class="relative z-10 flex flex-col h-full">
                        <div class="flex items-center gap-3 mb-5">
                            <div
                                class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-map-location-dot"></i>
                            </div>
                            <h5 class="text-sm font-extrabold text-gray-800 tracking-wide">Alamat Domisili</h5>
                        </div>
                        <div class="space-y-4 flex-1 flex flex-col">
                            <div class="flex-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Detail
                                    Alamat Lengkap</p>
                                <p class="text-sm font-bold text-gray-800 leading-relaxed"
                                    x-text="detail?.alamat ?? '-'"></p>
                            </div>
                            <div class="flex gap-2 pt-2 mt-auto">
                                <div class="bg-blue-50 border border-blue-100 px-3 py-2 rounded-xl text-center flex-1">
                                    <p class="text-[9px] text-blue-400 font-bold uppercase">RT / RW</p>
                                    <p class="text-xs font-black text-blue-800"
                                        x-text="(detail?.rt ?? '-') + ' / ' + (detail?.rw ?? '-')"></p>
                                </div>
                                <div class="bg-blue-50 border border-blue-100 px-3 py-2 rounded-xl text-center flex-1">
                                    <p class="text-[9px] text-blue-400 font-bold uppercase">Dusun</p>
                                    <p class="text-xs font-black text-blue-800" x-text="detail?.dusun ?? '-'"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Members Table Card --}}
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden flex flex-col">
                <div
                    class="px-6 py-5 border-b border-gray-100 bg-white flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-50 to-green-100 border border-green-200 text-green-600 flex items-center justify-center">
                            <i class="fa-solid fa-sitemap text-sm"></i>
                        </div>
                        <div>
                            <h5 class="text-base font-extrabold text-gray-800">Daftar Anggota Keluarga</h5>
                            <p class="text-xs text-gray-500 font-medium mt-0.5">Struktur dan relasi antar anggota</p>
                        </div>
                    </div>
                    <button @click="addMemberModalOpen = true"
                        class="bg-green-700 text-white hover:bg-green-800 shadow-md shadow-green-700/20 px-5 py-2.5 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center justify-center gap-2 hover:shadow-lg">
                        <i class="fa-solid fa-user-plus"></i> Tambah Anggota
                    </button>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left min-w-[750px]">
                        <thead>
                            <tr
                                class="bg-gray-50/80 border-b border-gray-100 text-[10px] uppercase text-gray-500 font-bold tracking-wider">
                                <th class="py-4 px-6 text-center w-14">No</th>
                                <th class="py-4 px-6">Identitas Penduduk</th>
                                <th class="py-4 px-6 text-center">Status Relasi</th>
                                <th class="py-4 px-6">Pendidikan & Pekerjaan</th>
                                <th class="py-4 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-sm">
                            <template x-for="(member, index) in (detail?.anggota ?? [])" :key="member.id">
                                <tr class="hover:bg-green-50/30 transition-colors group">
                                    <td class="py-4 px-6 text-center text-gray-400 font-bold text-xs"
                                        x-text="index + 1"></td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-green-100 to-emerald-50 border border-green-200 flex items-center justify-center text-green-700 font-extrabold text-lg shrink-0"
                                                x-text="member.nama.charAt(0).toUpperCase()"></div>
                                            <div>
                                                <div class="font-extrabold text-gray-900 group-hover:text-green-700 transition-colors"
                                                    x-text="member.nama"></div>
                                                <div class="font-mono text-[11px] text-gray-500 mt-0.5"
                                                    x-text="member.nik"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg text-[10px] font-bold border shadow-sm"
                                            :class="relationColor(member.status_hubungan)"
                                            x-text="member.status_hubungan"></span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex flex-col gap-1.5">
                                            <span
                                                class="text-xs font-semibold text-gray-700 flex items-center gap-2"><i
                                                    class="fa-solid fa-graduation-cap text-green-600/70 w-3"></i> <span
                                                    x-text="member.pendidikan ?? '-'"></span></span>
                                            <span class="text-xs text-gray-500 flex items-center gap-2"><i
                                                    class="fa-solid fa-briefcase text-blue-600/70 w-3"></i> <span
                                                    x-text="member.pekerjaan ?? '-'"></span></span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <template x-if="member.status_hubungan !== 'Kepala Keluarga'">
                                            <form
                                                :action="`{{ url('admin/kartu-keluarga') }}/${detail.id}/remove-member/${member.id}`"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Keluarkan anggota ini dari KK?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-gray-400 hover:text-red-600 bg-white hover:bg-red-50 border border-gray-200 hover:border-red-200 w-9 h-9 rounded-xl flex items-center justify-center transition-all cursor-pointer shadow-sm ml-auto"
                                                    title="Keluarkan dari KK">
                                                    <i class="fa-solid fa-user-minus text-xs"></i>
                                                </button>
                                            </form>
                                        </template>
                                        <template x-if="member.status_hubungan === 'Kepala Keluarga'">
                                            <div class="w-9 h-9 flex items-center justify-center ml-auto">
                                                <i class="fa-solid fa-crown text-amber-400"
                                                    title="Kepala Keluarga"></i>
                                            </div>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="(detail?.anggota ?? []).length === 0">
                                <tr>
                                    <td colspan="5" class="py-12 text-center">
                                        <div
                                            class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fa-solid fa-users-slash text-2xl text-gray-300"></i>
                                        </div>
                                        <p class="text-gray-500 text-sm font-semibold">Tidak ada data anggota keluarga.
                                        </p>
                                        <p class="text-gray-400 text-xs mt-1">Silakan tambahkan anggota baru.</p>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-4 sm:px-6 py-4 border-t border-gray-100 bg-white shrink-0 flex justify-end">
            <button @click="detailDrawerOpen = false"
                class="bg-white border border-gray-200 text-gray-700 hover:text-gray-900 hover:bg-gray-50 hover:border-gray-300 px-6 py-2.5 rounded-xl font-bold text-sm transition-all cursor-pointer shadow-sm w-full sm:w-auto">
                Tutup Drawer
            </button>
        </div>
    </div>
</div>
