{{-- Revisi Modal --}}
<div x-show="revisiModalOpen" class="fixed inset-0 z-[200] flex items-center justify-center p-4" x-cloak>
    <div x-show="revisiModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/80 backdrop-blur-sm"
         @click="revisiModalOpen = false"></div>

    <div x-show="revisiModalOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">

        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg text-gray-900">Kembalikan untuk Revisi</h3>
                <p class="text-xs text-gray-500" x-text="currentSurat?.nomor_tiket || ''"></p>
            </div>
        </div>

        <div class="bg-amber-50 border border-amber-200 p-3 rounded-lg text-[10px] text-amber-800 leading-relaxed mb-4">
            Surat akan dikembalikan ke operator untuk diperbaiki. Status akan berubah menjadi "Menunggu Verifikasi".
        </div>

        <div class="mb-4">
            <label class="text-xs font-bold text-gray-700 block mb-1">Catatan Revisi <span class="text-red-500">*</span></label>
            <textarea x-model="catatanRevisi" rows="4"
                      placeholder="Jelaskan apa yang perlu diperbaiki oleh operator..."
                      class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-amber-400 outline-none resize-none"></textarea>
        </div>

        <div class="flex gap-3">
            <button @click="revisiModalOpen = false; catatanRevisi = ''"
                class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 cursor-pointer">Batal</button>
            <button @click="processRevisi()"
                class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-amber-600 hover:bg-amber-700 shadow-sm cursor-pointer">
                <i class="fa-solid fa-rotate-left mr-1"></i> Kembalikan
            </button>
        </div>
    </div>
</div>
