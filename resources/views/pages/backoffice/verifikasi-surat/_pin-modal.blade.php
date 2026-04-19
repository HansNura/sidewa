{{-- PIN Authorization Modal --}}
<div x-show="pinModalOpen" class="fixed inset-0 z-[200] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="pinModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/80 backdrop-blur-sm"
         @click="!isSigning && (pinModalOpen = false)"></div>

    <div x-show="pinModalOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
         class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden flex flex-col p-8 text-center">

        <div class="w-16 h-16 bg-green-50 text-green-600 rounded-full flex items-center justify-center mx-auto mb-5 text-2xl border-4 border-white shadow-sm">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <h3 class="font-extrabold text-xl text-gray-900 mb-2">Otorisasi TTE</h3>
        <p class="text-xs text-gray-500 mb-6 px-4">Masukkan 6 digit PIN keamanan (Passphrase) Anda untuk menandatangani dokumen ini secara sah.</p>

        <div class="mb-6">
            <input type="password" maxlength="6" x-model="pinInput"
                   placeholder="••••••"
                   class="w-full text-center text-3xl tracking-[1em] font-mono bg-gray-50 border border-gray-300 rounded-xl py-4 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all"
                   :disabled="isSigning"
                   @keydown.enter="processTTE()">
        </div>

        <div class="flex gap-3">
            <button @click="pinModalOpen = false; pinInput = ''" :disabled="isSigning"
                class="flex-1 px-4 py-3 rounded-xl text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors disabled:opacity-50 cursor-pointer">
                Batal
            </button>
            <button @click="processTTE()" :disabled="isSigning"
                class="flex-1 px-4 py-3 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60">
                <i class="fa-solid fa-spinner fa-spin" x-show="isSigning" x-cloak></i>
                <span x-text="isSigning ? 'Memvalidasi...' : 'Verifikasi PIN'"></span>
            </button>
        </div>
    </div>
</div>
