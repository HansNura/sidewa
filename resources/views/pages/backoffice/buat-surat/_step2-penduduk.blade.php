{{-- Step 2: Pilih Data Pemohon (Penduduk) --}}
<section x-show="step === 2" x-transition.opacity.duration.300ms x-cloak>
    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">Langkah 2: Pilih Data Pemohon (Penduduk)</h3>

    {{-- Search Box --}}
    <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm mb-6">
        <label class="text-sm font-bold text-gray-700 block mb-2">Cari berdasarkan NIK atau Nama Pemohon</label>
        <div class="flex gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" x-model="searchNik"
                    @keyup.enter="findResident()"
                    @input.debounce.500ms="findResident()"
                    placeholder="Ketik NIK atau Nama..."
                    class="w-full bg-gray-50 border border-gray-300 rounded-xl pl-11 pr-4 py-3 text-sm focus:ring-2 focus:ring-green-500 outline-none font-mono">
            </div>
            <button @click="findResident()"
                class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-3 rounded-xl text-sm font-bold transition-colors flex items-center gap-2 shrink-0 shadow-sm cursor-pointer">
                <i class="fa-solid fa-search" x-show="!isSearching"></i>
                <i class="fa-solid fa-spinner fa-spin" x-show="isSearching" x-cloak></i>
                <span>Cari Data</span>
            </button>
        </div>
        <p class="text-xs text-gray-500 mt-2">
            <i class="fa-solid fa-circle-info mr-1"></i>
            Data akan ditarik otomatis dari database kependudukan.
        </p>

        {{-- Search Results Dropdown --}}
        <div x-show="searchResults.length > 0 && !selectedResident" x-cloak
             class="mt-3 border border-gray-200 rounded-xl overflow-hidden bg-white shadow-lg">
            <template x-for="r in searchResults" :key="r.id">
                <button @click="pickResident(r)"
                    class="w-full flex items-center gap-3 px-4 py-3 hover:bg-green-50 border-b border-gray-100 last:border-0 transition-colors text-left cursor-pointer">
                    <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-900 truncate" x-text="r.nama"></p>
                        <p class="text-xs text-gray-500 font-mono" x-text="'NIK: ' + r.nik"></p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-300 shrink-0"></i>
                </button>
            </template>
        </div>
    </div>

    {{-- Selected Resident Preview --}}
    <div x-show="selectedResident !== null" x-collapse x-cloak>
        <div class="bg-green-50 border border-green-200 rounded-2xl p-6 relative overflow-hidden shadow-sm">
            <div class="absolute right-0 top-0 text-green-100 opacity-50">
                <i class="fa-solid fa-circle-check text-9xl -mt-6 -mr-4"></i>
            </div>

            {{-- Reset Button --}}
            <button @click="selectedResident = null; searchNik = ''"
                class="absolute top-4 right-4 z-10 text-gray-400 hover:text-red-500 bg-white w-8 h-8 rounded-full flex items-center justify-center shadow-sm border border-gray-200 cursor-pointer"
                title="Ganti Pemohon">
                <i class="fa-solid fa-rotate-left text-sm"></i>
            </button>

            <div class="relative z-10 flex items-start gap-4">
                <div class="w-16 h-16 rounded-full bg-white border border-green-200 flex items-center justify-center text-green-600 text-2xl shrink-0 shadow-sm">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-[10px] font-bold text-green-700 uppercase tracking-wider mb-1">Data Pemohon Ditemukan</h4>
                    <h3 class="text-xl font-extrabold text-gray-900" x-text="selectedResident?.nama">-</h3>
                    <p class="text-sm font-mono text-gray-600 mt-1">NIK: <span x-text="selectedResident?.nik">-</span></p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 bg-white/60 p-4 rounded-xl border border-green-100">
                        <div>
                            <p class="text-[10px] text-gray-500 uppercase font-semibold">Tempat, Tgl Lahir</p>
                            <p class="text-sm font-medium text-gray-900" x-text="selectedResident?.ttl">-</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-500 uppercase font-semibold">Pekerjaan</p>
                            <p class="text-sm font-medium text-gray-900" x-text="selectedResident?.pekerjaan">-</p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-[10px] text-gray-500 uppercase font-semibold">Alamat Lengkap</p>
                            <p class="text-sm font-medium text-gray-900" x-text="selectedResident?.alamat">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
