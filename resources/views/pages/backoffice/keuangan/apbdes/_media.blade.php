<section x-show="activeTab === 'media'" x-transition.opacity class="max-w-4xl mx-auto space-y-6" x-cloak>
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 text-center border-b border-gray-100">
            <div class="w-16 h-16 bg-blue-50 text-blue-700 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl shadow-inner">
                <i class="fa-solid fa-images"></i>
            </div>
            <h3 class="text-xl font-extrabold text-gray-900">Publikasi Portal Web (TA {{ $tahun }})</h3>
            <p class="text-gray-500 max-w-md mx-auto mt-2 text-sm">Sesuaikan infografis rincian APBDes dan Dokumen resmi sebagai transparansi publik di Portal Desa.</p>
        </div>

        <form action="{{ route('admin.apbdes.store-poster') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8" x-data="{
            balihoPreview: '{{ $poster->gambar_baliho_url ?? '' }}',
            perdesFile: '{{ $poster->perdes_dokumen_url ? basename($poster->perdes_dokumen_url) : '' }}',
            rabFile: '{{ $poster->rab_dokumen_url ? basename($poster->rab_dokumen_url) : '' }}',
            
            previewBaliho(event) {
                const file = event.target.files[0];
                if(file) {
                    this.balihoPreview = URL.createObjectURL(file);
                }
            },
            updatePerdesName(event) {
                const file = event.target.files[0];
                if(file) this.perdesFile = file.name;
            },
            updateRabName(event) {
                const file = event.target.files[0];
                if(file) this.rabFile = file.name;
            }
        }">
            @csrf
            <input type="hidden" name="tahun" value="{{ $tahun }}">
            
            <!-- Baliho Infografis -->
            <div>
                <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fa-solid fa-panorama text-blue-600"></i> Gambar Baliho (Infografis APBDes)</h4>
                
                <div x-show="balihoPreview" class="mb-4 rounded-xl overflow-hidden border border-gray-200" style="display: none;">
                    <img :src="balihoPreview" alt="Baliho APBDes TA {{ $tahun }}" class="w-full h-auto object-cover max-h-64 shadow-sm">
                </div>
                <div x-show="!balihoPreview" class="mb-4 rounded-xl border border-dashed border-gray-300 bg-gray-50 h-32 flex flex-col items-center justify-center text-gray-400">
                    <i class="fa-solid fa-image text-2xl mb-2"></i>
                    <span class="text-xs">Belum ada gambar (Pilih file untuk preview)</span>
                </div>
                
                <div class="space-y-1 mt-2">
                    <label class="text-xs font-bold text-gray-700">Upload Gambar (JPG/PNG)</label>
                    <input type="file" name="gambar_baliho" accept="image/jpeg,image/png,image/jpg" @change="previewBaliho"
                        class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    <p class="text-[10px] text-gray-500">Maksimal ukuran file: 2MB.</p>
                </div>

                <div class="space-y-1 mt-4">
                    <label class="text-xs font-bold text-gray-700">Atau Gunakan Link URL Eksternal</label>
                    <input type="url" name="gambar_baliho_url" x-model="balihoPreview" placeholder="https://..." 
                        class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <p class="text-[10px] text-gray-500">Isi ini jika gambar disimpan di luar (mengabaikan upload di atas jika terisi).</p>
                </div>
            </div>

            <!-- Repository Dokumen -->
            <div class="pt-6 border-t border-gray-100">
                <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fa-solid fa-folder-open text-amber-500"></i> Repository Dokumen Resmi</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Perdes Dokumen -->
                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Perdes APBDes (Upload File)</label>
                            <div class="relative">
                                <input type="file" name="perdes_dokumen" id="perdes_dokumen" accept=".pdf" @change="updatePerdesName" class="hidden">
                                <label for="perdes_dokumen" class="flex items-center w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-blue-500 transition-all">
                                    <i class="fa-solid fa-file-pdf text-red-500 mr-3"></i>
                                    <span class="flex-1 truncate" x-text="perdesFile || 'Pilih File PDF...'" :class="perdesFile ? 'text-gray-800 font-medium' : 'text-gray-400'"></span>
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-lg text-xs font-bold border border-gray-200">Browse</span>
                                </label>
                            </div>
                            <p class="text-[10px] text-gray-500 mt-1">Hanya menerima format .pdf (Maks 5MB)</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Atau Gunakan Link Dokumen</label>
                            <input type="url" name="perdes_dokumen_url" value="{{ $poster->perdes_dokumen_url ?? '' }}" placeholder="https://drive.google.com/..." 
                                class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    </div>

                    <!-- RAB Dokumen -->
                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">RAB Pembangunan (Upload Excel/PDF)</label>
                            <div class="relative">
                                <input type="file" name="rab_dokumen" id="rab_dokumen" accept=".pdf,.xls,.xlsx" @change="updateRabName" class="hidden">
                                <label for="rab_dokumen" class="flex items-center w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-blue-500 transition-all">
                                    <i class="fa-solid fa-file-excel text-green-600 mr-3"></i>
                                    <span class="flex-1 truncate" x-text="rabFile || 'Pilih File Excel/PDF...'" :class="rabFile ? 'text-gray-800 font-medium' : 'text-gray-400'"></span>
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-lg text-xs font-bold border border-gray-200">Browse</span>
                                </label>
                            </div>
                            <p class="text-[10px] text-gray-500 mt-1">Menerima format .pdf, .xls, .xlsx (Maks 5MB)</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Atau Gunakan Link Dokumen</label>
                            <input type="url" name="rab_dokumen_url" value="{{ $poster->rab_dokumen_url ?? '' }}" placeholder="https://drive.google.com/..." 
                                class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action -->
            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white shadow-md rounded-xl px-8 py-3 text-sm font-bold transition-all flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Publikasi & Dokumen
                </button>
            </div>
        </form>
    </div>
</section>
