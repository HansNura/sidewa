{{-- Template Preview Modal --}}
<div x-show="previewModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="previewModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="previewModalOpen = false"></div>

    <div x-show="previewModalOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative bg-gray-200 rounded-2xl shadow-2xl w-full max-w-3xl overflow-hidden flex flex-col max-h-[90vh]">

        {{-- Modal Header --}}
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-white shrink-0">
            <div>
                <h3 class="font-extrabold text-lg text-gray-900">Preview Template</h3>
                <p class="text-xs text-gray-500 font-mono mt-0.5" x-text="previewData?.nama || 'Memuat...'"></p>
            </div>
            <button @click="previewModalOpen = false"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="p-6 overflow-y-auto custom-scrollbar flex-1 flex justify-center">
            {{-- Loading --}}
            <div x-show="isLoadingPreview" class="flex flex-col items-center justify-center py-12" x-cloak>
                <i class="fa-solid fa-spinner fa-spin text-3xl text-gray-300 mb-3"></i>
                <p class="text-sm text-gray-500">Memuat preview...</p>
            </div>

            {{-- A4 Paper --}}
            <div x-show="!isLoadingPreview && previewData" class="print-paper bg-white w-full max-w-[600px] p-8 font-serif text-black text-sm" x-cloak>
                {{-- KOP --}}
                <div class="border-b-2 border-black pb-3 mb-6 text-center">
                    <h2 class="font-bold uppercase leading-tight">Pemerintah Kabupaten {{ $village->kabupaten ?? '' }}</h2>
                    <h1 class="text-xl font-extrabold uppercase leading-tight">{{ $village->fullName() }}</h1>
                </div>

                {{-- Title --}}
                <div class="text-center mb-6">
                    <h3 class="font-bold uppercase underline" x-text="previewData?.nama || ''"></h3>
                    <p class="text-xs">Nomor : 470 / <span class="bg-yellow-100 px-1 border border-yellow-300 border-dashed rounded text-xs font-mono">@{{nomor_surat}}</span> / Desa / <span class="bg-yellow-100 px-1 border border-yellow-300 border-dashed rounded text-xs font-mono">@{{tahun}}</span></p>
                </div>

                {{-- Body with highlighted placeholders --}}
                <div class="text-justify leading-relaxed whitespace-pre-wrap"
                     x-html="(previewData?.body_template || '').replace(/\{\{(\w+)\}\}/g, '<span class=\'bg-yellow-100 px-1 border border-yellow-300 border-dashed rounded text-xs font-mono\'>{{$1}}</span>')">
                </div>

                {{-- Info Bar --}}
                <div class="mt-8 pt-4 border-t border-gray-200 flex justify-between text-[10px] text-gray-400">
                    <span x-text="'Versi: ' + (previewData?.versi || '-')"></span>
                    <span x-text="'Field: ' + (previewData?.field_count || 0) + ' placeholder'"></span>
                    <span x-text="'Terakhir: ' + (previewData?.updated_at || '-')"></span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .print-paper {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush
