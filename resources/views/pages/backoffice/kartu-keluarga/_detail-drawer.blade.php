{{-- Detail KK Drawer --}}
<div x-show="detailDrawerOpen" class="fixed inset-0 z-[100] flex justify-end" x-cloak>
    <div x-show="detailDrawerOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
         @click="detailDrawerOpen = false"></div>

    <div x-show="detailDrawerOpen"
         x-transition:enter="transition ease-transform duration-300"
         x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-transform duration-300"
         x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
         class="relative bg-white w-full max-w-4xl h-full shadow-2xl flex flex-col border-l border-gray-200">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start bg-gray-50/50 shrink-0">
            <div class="flex gap-4 items-center">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-700 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-users-rectangle"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-xl text-gray-900">Detail Kartu Keluarga</h3>
                    <p class="text-sm font-mono text-green-600 font-bold mt-0.5" x-text="'No. ' + (detail?.no_kk ?? '')"></p>
                </div>
            </div>
            <button @click="detailDrawerOpen = false"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 cursor-pointer">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6" x-show="detail">

            {{-- Info Utama --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Informasi Utama</h5>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between border-b border-gray-200 pb-1">
                            <span class="text-gray-500">Kepala Keluarga</span>
                            <span class="font-bold text-gray-900" x-text="detail?.kepala ?? '-'"></span>
                        </div>
                        <div class="flex justify-between border-b border-gray-200 pb-1">
                            <span class="text-gray-500">Jumlah Anggota</span>
                            <span class="font-bold text-gray-900" x-text="(detail?.jumlah_anggota ?? 0) + ' Jiwa'"></span>
                        </div>
                        <div class="flex justify-between border-b border-gray-200 pb-1">
                            <span class="text-gray-500">Tgl Dikeluarkan</span>
                            <span class="font-semibold text-gray-800" x-text="detail?.tanggal_dikeluarkan ?? '-'"></span>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Alamat Domisili</h5>
                    <p class="text-sm font-semibold text-gray-800 leading-relaxed mb-2" x-text="detail?.alamat ?? '-'"></p>
                    <div class="flex gap-2">
                        <span class="bg-white border border-gray-200 text-gray-700 text-[10px] font-bold px-2 py-1 rounded" x-text="'RT ' + (detail?.rt ?? '-')"></span>
                        <span class="bg-white border border-gray-200 text-gray-700 text-[10px] font-bold px-2 py-1 rounded" x-text="'RW ' + (detail?.rw ?? '-')"></span>
                        <span class="bg-white border border-gray-200 text-gray-700 text-[10px] font-bold px-2 py-1 rounded" x-text="'Dusun ' + (detail?.dusun ?? '-')"></span>
                    </div>
                </div>
            </div>

            {{-- Family Members Table --}}
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h5 class="text-sm font-bold text-gray-800">
                        <i class="fa-solid fa-sitemap text-green-600 mr-2"></i>Struktur & Anggota Keluarga
                    </h5>
                    <button @click="addMemberModalOpen = true"
                        class="bg-green-50 text-green-700 border border-green-200 hover:bg-green-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors cursor-pointer">
                        <i class="fa-solid fa-user-plus mr-1"></i> Tambah Anggota
                    </button>
                </div>

                <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <table class="w-full text-left bg-white">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-[10px] uppercase text-gray-500 font-bold">
                                <th class="p-3 w-10 text-center">No</th>
                                <th class="p-3">Identitas (NIK & Nama)</th>
                                <th class="p-3">Status Relasi</th>
                                <th class="p-3 text-center">Pendidikan / Pekerjaan</th>
                                <th class="p-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <template x-for="(member, index) in (detail?.anggota ?? [])" :key="member.id">
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 text-center text-gray-500 font-semibold" x-text="index + 1"></td>
                                    <td class="p-3">
                                        <div class="font-bold text-gray-900" x-text="member.nama"></div>
                                        <div class="font-mono text-xs text-gray-500" x-text="member.nik"></div>
                                    </td>
                                    <td class="p-3">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded border"
                                              :class="relationColor(member.status_hubungan)"
                                              x-text="member.status_hubungan"></span>
                                    </td>
                                    <td class="p-3 text-center text-xs text-gray-600">
                                        <span x-text="member.pendidikan ?? '-'"></span><br>
                                        <span class="text-gray-400" x-text="member.pekerjaan ?? '-'"></span>
                                    </td>
                                    <td class="p-3 text-right">
                                        <template x-if="member.status_hubungan !== 'Kepala Keluarga'">
                                            <form :action="`{{ url('admin/kartu-keluarga') }}/${detail.id}/remove-member/${member.id}`"
                                                  method="POST" class="inline"
                                                  onsubmit="return confirm('Keluarkan anggota ini dari KK?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-gray-400 hover:text-red-500 w-6 h-6 rounded hover:bg-gray-100 cursor-pointer"
                                                    title="Keluarkan dari KK">
                                                    <i class="fa-solid fa-user-minus"></i>
                                                </button>
                                            </form>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="p-6 border-t border-gray-100 bg-white shrink-0">
            <button @click="detailDrawerOpen = false"
                class="w-full bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-100 cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>
