{{-- Reject Modal --}}
<div x-show="rejectModalOpen" class="fixed inset-0 z-[200] flex items-center justify-center p-4" x-cloak>
    <div x-show="rejectModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/80 backdrop-blur-sm"
         @click="rejectModalOpen = false"></div>

    <div x-show="rejectModalOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">

        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-red-100 text-red-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-ban"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg text-gray-900">Tolak Surat</h3>
                <p class="text-xs text-gray-500" x-text="currentSurat?.nomor_tiket || ''"></p>
            </div>
        </div>

        <div class="mb-4">
            <label class="text-xs font-bold text-gray-700 block mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
            <textarea x-model="alasanTolak" rows="4"
                      placeholder="Jelaskan alasan penolakan surat ini..."
                      class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-400 outline-none resize-none"></textarea>
        </div>

        <div class="flex gap-3">
            <button @click="rejectModalOpen = false; alasanTolak = ''"
                class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 cursor-pointer">Batal</button>
            <button @click="processReject()"
                class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-red-600 hover:bg-red-700 shadow-sm cursor-pointer">
                <i class="fa-solid fa-ban mr-1"></i> Tolak Surat
            </button>
        </div>
    </div>
</div>
