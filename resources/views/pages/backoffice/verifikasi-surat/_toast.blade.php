{{-- Toast Notification --}}
<div x-show="showToast"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-2 sm:translate-x-4"
     x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0 translate-y-2 sm:translate-x-4"
     class="fixed bottom-4 right-4 z-[300] bg-gray-900 text-white px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-4"
     x-cloak>
    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 text-lg border-2 border-gray-800"
         :class="toastType === 'success' ? 'bg-green-500' : 'bg-red-500'">
        <i class="fa-solid text-white" :class="toastType === 'success' ? 'fa-check' : 'fa-xmark'"></i>
    </div>
    <div>
        <h4 class="font-bold text-sm" x-text="toastType === 'success' ? 'Berhasil!' : 'Gagal!'"></h4>
        <p class="text-xs text-gray-300 mt-0.5" x-text="toastMessage"></p>
    </div>
</div>
