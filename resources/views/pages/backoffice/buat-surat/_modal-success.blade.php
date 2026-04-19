{{-- Success Modal Overlay --}}
<div x-show="showSuccessModal" class="fixed inset-0 z-[150] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="showSuccessModal" x-transition.opacity class="absolute inset-0 bg-gray-900/80 backdrop-blur-sm"></div>

    <div x-show="showSuccessModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden flex flex-col p-8 text-center">

        <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl">
            <i class="fa-solid fa-check"></i>
        </div>
        <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Sukses!</h2>
        <p class="text-gray-500 mb-6">
            Surat <span class="font-bold" x-text="templateName"></span> berhasil
            <span x-text="successData.action === 'proses'
                ? 'dikirim ke antrian TTE.'
                : (successData.action === 'draft'
                    ? 'disimpan ke Draft.'
                    : 'diteruskan ke petugas lain.')"></span>
        </p>

        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-8">
            <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Nomor Tiket Antrian</p>
            <p class="text-lg font-mono font-bold text-green-700" x-text="successData.nomor_tiket || '-'"></p>
        </div>

        <div class="flex flex-col gap-3">
            <a href="{{ route('admin.layanan-surat.index') }}"
               class="w-full px-5 py-3 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 transition-colors text-center">
                Kembali ke Dashboard
            </a>
            <button @click="window.location.reload()"
                class="w-full px-5 py-3 rounded-xl text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors cursor-pointer">
                Buat Surat Lainnya
            </button>
        </div>
    </div>
</div>
