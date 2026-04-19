{{-- Step 5: Submit & Proses --}}
<section x-show="step === 5" x-transition.opacity.duration.300ms x-cloak>
    <div class="text-center max-w-2xl mx-auto mb-8 mt-4">
        <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-sm text-3xl">
            <i class="fa-solid fa-file-circle-check"></i>
        </div>
        <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Dokumen Siap Diterbitkan</h3>
        <p class="text-gray-500">
            Silakan pilih tindak lanjut untuk dokumen
            <span class="font-bold text-gray-700" x-text="templateName"></span>
            atas nama
            <span class="font-bold text-gray-700" x-text="selectedResident?.nama"></span>.
        </p>
    </div>

    {{-- Action Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-4xl mx-auto mb-10">
        {{-- Option 1: Proses Langsung --}}
        <label class="cursor-pointer">
            <input type="radio" name="action" value="proses" x-model="submitAction" class="peer sr-only">
            <div class="p-5 border-2 rounded-2xl transition-all h-full bg-white text-center hover:bg-gray-50
                        peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:shadow-md relative group">
                <div class="absolute top-4 right-4 text-gray-200 peer-checked:text-green-500 transition-colors">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                </div>
                <i class="fa-solid fa-paper-plane text-3xl text-green-600 mb-3 group-hover:scale-110 transition-transform"></i>
                <h4 class="font-bold text-gray-900 mb-1">Langsung Proses</h4>
                <p class="text-xs text-gray-500">Kirim ke antrian TTE Kepala Desa untuk segera disahkan.</p>
            </div>
        </label>

        {{-- Option 2: Draft --}}
        <label class="cursor-pointer">
            <input type="radio" name="action" value="draft" x-model="submitAction" class="peer sr-only">
            <div class="p-5 border-2 rounded-2xl transition-all h-full bg-white text-center hover:bg-gray-50
                        peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:shadow-md relative group">
                <div class="absolute top-4 right-4 text-gray-200 peer-checked:text-amber-500 transition-colors">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                </div>
                <i class="fa-solid fa-folder-closed text-3xl text-amber-500 mb-3 group-hover:scale-110 transition-transform"></i>
                <h4 class="font-bold text-gray-900 mb-1">Simpan ke Draft</h4>
                <p class="text-xs text-gray-500">Simpan sementara. Anda bisa melanjutkannya nanti.</p>
            </div>
        </label>

        {{-- Option 3: Assign --}}
        <label class="cursor-pointer">
            <input type="radio" name="action" value="assign" x-model="submitAction" class="peer sr-only">
            <div class="p-5 border-2 rounded-2xl transition-all h-full bg-white text-center hover:bg-gray-50
                        peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-md relative group">
                <div class="absolute top-4 right-4 text-gray-200 peer-checked:text-blue-500 transition-colors">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                </div>
                <i class="fa-solid fa-user-clock text-3xl text-blue-500 mb-3 group-hover:scale-110 transition-transform"></i>
                <h4 class="font-bold text-gray-900 mb-1">Teruskan ke Petugas</h4>
                <p class="text-xs text-gray-500">Berikan ke operator lain untuk melengkapi syarat lanjutan.</p>
            </div>
        </label>
    </div>
</section>
