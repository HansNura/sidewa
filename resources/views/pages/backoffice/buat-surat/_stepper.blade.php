{{-- Stepper Header — Buat Surat Baru Wizard --}}
<div class="bg-white border-b border-gray-200 px-4 sm:px-6 lg:px-8 py-5 shrink-0 z-10 sticky top-0 shadow-sm">
    <div class="max-w-5xl mx-auto flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Buat Surat Baru</h1>
            <p class="text-sm text-gray-500 mt-1">Ikuti panduan langkah demi langkah untuk menerbitkan surat.</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="draftModalOpen = true; loadDrafts()"
                class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-xl text-sm font-semibold transition-colors flex items-center gap-2 shadow-sm cursor-pointer">
                <i class="fa-solid fa-folder-open text-amber-500"></i> Buka Draft
            </button>
            <button @click="saveDraftQuick()"
                class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-xl text-sm font-semibold transition-colors flex items-center gap-2 shadow-sm cursor-pointer">
                <i class="fa-solid fa-bookmark text-blue-500"></i> Simpan Draft
            </button>
        </div>
    </div>

    {{-- Stepper Indicator --}}
    <div class="max-w-5xl mx-auto mt-6">
        <div class="flex items-center justify-between relative">
            {{-- Line Background --}}
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-200 z-0 rounded-full"></div>
            {{-- Active Line (Progress) --}}
            <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-green-500 z-0 rounded-full transition-all duration-500"
                :style="'width: ' + ((step - 1) / (maxStep - 1) * 100) + '%'"></div>

            {{-- Step Dots --}}
            @php
                $stepLabels = ['Pilih Template', 'Data Penduduk', 'Data Tambahan', 'Pratinjau Surat', 'Selesai & Proses'];
            @endphp
            @foreach ($stepLabels as $i => $label)
                @php $n = $i + 1; @endphp
                <div class="relative z-10 flex flex-col items-center group"
                     :class="step > {{ $n }} ? 'cursor-pointer' : ''"
                     @click="({{ $n }} < step) ? step = {{ $n }} : null">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300"
                        :class="{
                            'bg-green-600 text-white border-4 border-green-100 shadow-md scale-110': step === {{ $n }},
                            'bg-green-500 text-white': step > {{ $n }},
                            'bg-white border-2 border-gray-300 text-gray-400': step < {{ $n }}
                        }">
                        <i x-show="step > {{ $n }}" class="fa-solid fa-check" x-cloak></i>
                        <span x-show="step <= {{ $n }}">{{ $n }}</span>
                    </div>
                    <span class="hidden sm:block absolute top-12 text-[11px] font-semibold uppercase tracking-wider text-center w-24"
                        :class="step === {{ $n }} ? 'text-green-700' : 'text-gray-400'">
                        {{ $label }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>
