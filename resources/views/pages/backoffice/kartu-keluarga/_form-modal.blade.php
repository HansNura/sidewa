{{-- Add KK Form Modal --}}
<div x-show="addModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="addModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
         @click="addModalOpen = false"></div>

    <div x-show="addModalOpen" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <h3 class="font-extrabold text-lg text-gray-900">Tambah Kartu Keluarga (KK) Baru</h3>
            <button @click="addModalOpen = false"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            <form id="kkForm" method="POST" action="{{ route('admin.kartu-keluarga.store') }}" class="space-y-6">
                @csrf

                {{-- No KK --}}
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Nomor Kartu Keluarga (16 Digit) <span class="text-red-500">*</span></label>
                    <input type="text" name="no_kk" value="{{ old('no_kk') }}" maxlength="16" placeholder="Contoh: 320912..."
                           class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none font-mono" required>
                </div>

                {{-- Kepala Keluarga --}}
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Pilih Kepala Keluarga <span class="text-red-500">*</span></label>
                    <select name="kepala_keluarga_id" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer" required>
                        <option value="">-- Pilih Penduduk --</option>
                        @php
                            $unlinked = \App\Models\Penduduk::whereNull('kartu_keluarga_id')
                                ->where('status', 'hidup')
                                ->orderBy('nama')
                                ->get(['id', 'nik', 'nama']);
                        @endphp
                        @foreach ($unlinked as $p)
                            <option value="{{ $p->id }}" {{ old('kepala_keluarga_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }} ({{ $p->nik }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-gray-500 mt-1">Hanya penduduk yang belum terdaftar di KK mana pun yang ditampilkan.</p>
                </div>

                {{-- Address --}}
                <div class="border-t border-gray-100 pt-5 mt-2">
                    <h4 class="text-sm font-bold text-gray-800 mb-3">Wilayah Administratif & Alamat</h4>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-600 uppercase">Dusun <span class="text-red-500">*</span></label>
                            <input type="text" name="dusun" value="{{ old('dusun') }}" placeholder="Kaler"
                                   class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-600 uppercase">RT <span class="text-red-500">*</span></label>
                            <input type="text" name="rt" value="{{ old('rt') }}" placeholder="01" maxlength="3"
                                   class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-600 uppercase">RW <span class="text-red-500">*</span></label>
                            <input type="text" name="rw" value="{{ old('rw') }}" placeholder="02" maxlength="3"
                                   class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none" required>
                        </div>
                        <div class="col-span-2 md:col-span-3 space-y-1">
                            <label class="text-[10px] font-bold text-gray-600 uppercase">Detail Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="alamat" rows="2" placeholder="Jl. Raya, Blok, atau patokan rumah..."
                                      class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none resize-none" required>{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Issue date --}}
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Tanggal Dikeluarkan</label>
                    <input type="date" name="tanggal_dikeluarkan" value="{{ old('tanggal_dikeluarkan', date('Y-m-d')) }}"
                           class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                </div>
            </form>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 shrink-0 flex justify-end gap-3">
            <button type="button" @click="addModalOpen = false"
                class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 border border-gray-200 hover:bg-gray-100 transition-colors bg-white cursor-pointer">Batal</button>
            <button type="submit" form="kkForm"
                class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md transition-all cursor-pointer">
                <i class="fa-solid fa-save mr-2"></i> Simpan Data KK
            </button>
        </div>
    </div>
</div>
