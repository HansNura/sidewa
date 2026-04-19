{{-- Add/Edit Wilayah Modal --}}
<div x-show="addModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="addModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
         @click="addModalOpen = false"></div>

    <div x-show="addModalOpen" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <h3 class="font-extrabold text-lg text-gray-900">Form Wilayah Administratif</h3>
            <button @click="addModalOpen = false"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-5">
            <form id="wilayahForm" method="POST" action="{{ route('admin.wilayah.store') }}" class="space-y-5">
                @csrf

                {{-- Tipe --}}
                <div class="space-y-1">
                    <label class="text-sm font-bold text-gray-700">Tipe Tingkat Wilayah <span class="text-red-500">*</span></label>
                    <select name="tipe" x-model="formType"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                        <option value="dusun">Dusun (Tingkat 1)</option>
                        <option value="rw">RW (Tingkat 2)</option>
                        <option value="rt">RT (Tingkat 3)</option>
                    </select>
                </div>

                {{-- Nama --}}
                <div class="space-y-1">
                    <label class="text-sm font-bold text-gray-700">Nama / Nomor Wilayah <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Misal: Kaler atau 01"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none" required>
                </div>

                {{-- Parent (dynamic) --}}
                <div class="space-y-1" x-show="formType !== 'dusun'" x-collapse>
                    <label class="text-sm font-bold text-gray-700">Induk Wilayah (Parent) <span class="text-red-500">*</span></label>
                    <select name="parent_id"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                        <option value="">Pilih Induk Wilayah...</option>
                        {{-- Dusun options (for RW type) --}}
                        <template x-if="formType === 'rw'">
                            <optgroup label="Dusun">
                                @foreach ($dusunList as $d)
                                    <option value="{{ $d->id }}" {{ old('parent_id') == $d->id ? 'selected' : '' }}>
                                        Dusun {{ $d->nama }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </template>
                        {{-- RW options (for RT type) --}}
                        <template x-if="formType === 'rt'">
                            <optgroup label="RW">
                                @foreach ($rwList as $rw)
                                    <option value="{{ $rw->id }}" {{ old('parent_id') == $rw->id ? 'selected' : '' }}>
                                        RW {{ $rw->nama }} (Dusun {{ $rw->parent?->nama }})
                                    </option>
                                @endforeach
                            </optgroup>
                        </template>
                    </select>
                    <p class="text-[10px] text-gray-500 mt-1">Pilih wilayah yang berada satu tingkat di atasnya.</p>
                </div>

                {{-- Kepala --}}
                <div class="border-t border-gray-100 pt-4 space-y-4">
                    <h4 class="text-sm font-bold text-gray-700">Data Kepala Wilayah</h4>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-600 uppercase">Nama Lengkap</label>
                        <input type="text" name="kepala_nama" value="{{ old('kepala_nama') }}" placeholder="Nama kepala / ketua"
                               class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-600 uppercase">Jabatan</label>
                            <input type="text" name="kepala_jabatan" value="{{ old('kepala_jabatan') }}" placeholder="Kadus / Ketua RW"
                                   class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-600 uppercase">No. Telepon</label>
                            <input type="text" name="kepala_telepon" value="{{ old('kepala_telepon') }}" placeholder="0812-..."
                                   class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                        </div>
                    </div>
                </div>

                {{-- GeoJSON --}}
                <div class="space-y-1 pt-2 border-t border-gray-100">
                    <label class="text-sm font-bold text-gray-700 flex items-center gap-2">
                        Data Geospasial (Peta)
                        <span class="text-[10px] bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded font-medium">Opsional</span>
                    </label>
                    <textarea name="geojson" rows="3"
                              placeholder='Paste data format GeoJSON di sini...'
                              class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-xs font-mono focus:ring-2 focus:ring-green-500 outline-none resize-none">{{ old('geojson') }}</textarea>
                </div>
            </form>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 shrink-0 flex justify-end gap-3 rounded-b-2xl">
            <button type="button" @click="addModalOpen = false"
                class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 border border-gray-200 hover:bg-gray-100 transition-colors bg-white cursor-pointer">Batal</button>
            <button type="submit" form="wilayahForm"
                class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md transition-all cursor-pointer">
                <i class="fa-solid fa-save mr-2"></i> Simpan Data
            </button>
        </div>
    </div>
</div>
