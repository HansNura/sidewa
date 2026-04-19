{{-- Step 3: Input Data Tambahan --}}
<section x-show="step === 3" x-transition.opacity.duration.300ms x-cloak>
    <div class="mb-4 border-b border-gray-200 pb-2 flex justify-between items-end">
        <div>
            <h3 class="text-lg font-bold text-gray-900">Langkah 3: Input Data Tambahan</h3>
            <p class="text-sm text-green-600 font-semibold mt-1" x-text="'Format: ' + templateName"></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm space-y-5">
        {{-- Keperluan --}}
        <div class="space-y-1">
            <label class="text-sm font-bold text-gray-700">Keperluan / Tujuan Surat <span class="text-red-500">*</span></label>
            <input type="text" x-model="formData.keperluan"
                placeholder="Misal: Persyaratan Pendaftaran Sekolah Anak"
                class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 outline-none transition-all">
        </div>

        {{-- Conditional: Nama Usaha (only for pengantar_usaha) --}}
        <div class="space-y-1" x-show="templateKey === 'pengantar_usaha'" x-collapse x-cloak>
            <label class="text-sm font-bold text-gray-700">Nama Usaha / Bidang <span class="text-red-500">*</span></label>
            <input type="text" x-model="formData.namaUsaha"
                placeholder="Misal: Warung Sembako / Kuliner"
                class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 outline-none transition-all">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            {{-- Masa Berlaku --}}
            <div class="space-y-1">
                <label class="text-sm font-bold text-gray-700">Masa Berlaku Surat</label>
                <select x-model="formData.berlakuHingga"
                    class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                    <option value="1 Bulan">1 Bulan sejak tanggal dikeluarkan</option>
                    <option value="3 Bulan">3 Bulan sejak tanggal dikeluarkan</option>
                    <option value="6 Bulan">6 Bulan sejak tanggal dikeluarkan</option>
                    <option value="Selamanya">Hingga ada perubahan data (Selamanya)</option>
                </select>
            </div>

            {{-- Keterangan Lain --}}
            <div class="space-y-1">
                <label class="text-sm font-bold text-gray-700">Lampiran Keterangan Lain (Opsional)</label>
                <input type="text" x-model="formData.keteranganLain"
                    placeholder="Catatan tambahan bila perlu..."
                    class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 outline-none transition-all">
            </div>
        </div>
    </div>
</section>
