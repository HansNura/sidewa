<!-- 1. MODAL: KELOLA PRODUK HUKUM (CREATE/EDIT) -->
<div x-show="docModalOpen" class="fixed inset-0 z-[150] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="docModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="docModalOpen = false"></div>

    <div x-show="docModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[95vh]">

        <form id="docForm" action="{{ route('admin.jdih.storeDocument') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col h-full overflow-hidden w-full m-0 p-0" x-data="{
                fileName: '',
                fileSize: '',
                dragOver: false,
                handleFileChage(event) {
                    const file = event.target.files[0];
                    if(file) {
                        this.fileName = file.name;
                        this.fileSize = formatBytes(file.size);
                    }
                }
            }">
            @csrf
            <input type="hidden" name="id" :value="docId">

            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
                <h3 class="font-extrabold text-lg text-gray-900" x-text="docId ? 'Edit Dokumen Hukum' : 'Tambah Dokumen Hukum Baru'"></h3>
                <button type="button" @click="docModalOpen = false" class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <div class="overflow-y-auto custom-scrollbar flex-1 flex flex-col md:flex-row">

                <!-- KIRI: Upload & Status -->
                <div class="w-full md:w-80 p-6 border-b md:border-b-0 md:border-r border-gray-100 space-y-6 bg-gray-50/30 text-center md:text-left">
                    <!-- File Upload -->
                    <div>
                        <label class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 block text-left">Upload File Dokumen <span class="text-red-500">*</span></label>
                        
                        <input type="file" id="dokumen_file" name="dokumen_file" accept=".pdf" class="hidden" @change="handleFileChage" x-ref="fileInput">

                        <!-- Drag & Drop Zone -->
                        <div @click="$refs.fileInput.click()" 
                            @dragover.prevent="dragOver = true" 
                            @dragleave.prevent="dragOver = false" 
                            @drop.prevent="dragOver = false; $refs.fileInput.files = $event.dataTransfer.files; handleFileChage({target: $refs.fileInput})"
                            class="w-full aspect-square md:aspect-auto md:h-64 bg-white rounded-2xl border-2 border-dashed flex flex-col items-center justify-center transition-colors cursor-pointer group relative overflow-hidden" 
                            :class="[
                                (fileName !== '' || (previewData.file_path && !fileName)) ? 'border-primary-300 bg-primary-50/30' : 'border-gray-300 hover:bg-primary-50 hover:border-primary-300 text-gray-400 hover:text-primary-600',
                                dragOver ? 'border-primary-500 bg-primary-100 text-primary-700 ring-4 ring-primary-500/20' : ''
                            ]">
                            
                            <template x-if="fileName !== ''">
                                <div class="flex flex-col items-center text-center p-4">
                                    <i class="fa-solid fa-file-pdf text-red-500 text-5xl mb-3 group-hover:scale-110 transition-transform"></i>
                                    <p class="text-xs font-bold text-gray-800 break-all" x-text="fileName"></p>
                                    <p class="text-[10px] text-gray-500 mt-1" x-text="fileSize"></p>
                                    <div class="absolute inset-0 bg-white/90 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-upload text-2xl text-primary-600 mb-2"></i>
                                        <span class="text-[10px] font-bold text-primary-800">Ganti File Dokumen</span>
                                    </div>
                                </div>
                            </template>

                            <template x-if="fileName === '' && previewData.file_path">
                                <div class="flex flex-col items-center text-center p-4">
                                    <i class="fa-solid fa-file-pdf text-red-500 text-5xl mb-3 group-hover:scale-110 transition-transform"></i>
                                    <p class="text-[10px] font-bold text-green-700 bg-green-100 px-2 py-1 rounded inline-block">File PDF Telah Tersedia</p>
                                    <p class="text-[10px] text-gray-500 mt-2">Biar kosongkan jika tidak ingin mengubah file PDF arsip ini.</p>
                                    <div class="absolute inset-0 bg-white/90 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-upload text-2xl text-primary-600 mb-2"></i>
                                        <span class="text-[10px] font-bold text-primary-800">Upload File / Timpa PDF Baru</span>
                                    </div>
                                </div>
                            </template>

                            <template x-if="fileName === '' && !previewData.file_path">
                                <div class="flex flex-col items-center text-center p-4 pointer-events-none">
                                    <i class="fa-solid fa-cloud-arrow-up text-4xl mb-3 group-hover:-translate-y-1 transition-transform" :class="dragOver && 'animate-bounce text-primary-600'"></i>
                                    <span class="text-xs font-bold text-gray-600 pointer-events-none">Tarik & Lepas File (PDF)</span>
                                    <span class="text-[10px] font-medium text-gray-400 mt-1 pointer-events-none">Maks. 10 MB. Harus berformat .pdf</span>
                                </div>
                            </template>

                        </div>
                    </div>

                    <!-- Publish Status -->
                    <div class="bg-white border border-gray-200 p-4 rounded-xl shadow-sm text-left">
                        <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-3">Status Dokumen</h4>
                        <select name="status" x-model="docStatus" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none font-semibold cursor-pointer">
                            <option value="berlaku">Berlaku (Publish)</option>
                            <option value="draft">Draft (Belum Ditetapkan)</option>
                            <option value="dicabut" class="text-red-600 font-bold">Dicabut / Tidak Berlaku</option>
                        </select>
                        <p x-show="docStatus === 'dicabut'" class="text-[10px] text-red-500 mt-2 font-medium">Dokumen dicabut akan secara otomatis ditandai batal/tidak berlaku lagi di portal publik.</p>
                    </div>
                </div>

                <!-- KANAN: Detail Form -->
                <div class="flex-1 p-6 space-y-5">
                    
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Judul Dokumen (Tentang) <span class="text-red-500">*</span></label>
                        <input type="text" name="title" x-model="docTitle" required placeholder="Misal: Anggaran Pendapatan dan Belanja Desa (APBDes) Tahun 2026" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-primary-500 outline-none transition-all placeholder:font-normal">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Kategori / Jenis <span class="text-red-500">*</span></label>
                            <select id="cat_select" name="category_id" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 outline-none cursor-pointer">
                                <option value="">Pilih Kategori...</option>
                                @foreach($categoriesList as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Nomor Dokumen <span class="text-red-500">*</span></label>
                            <input type="text" id="d_docnum" name="document_number" required placeholder="Misal: 04 Tahun 2026" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 outline-none transition-all font-mono">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Tanggal Ditetapkan / Disahkan <span class="text-red-500">*</span></label>
                        <input type="date" id="d_date" name="established_date" required class="w-full sm:w-1/2 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 outline-none transition-all cursor-pointer">
                    </div>

                    <div class="space-y-1 h-full flex flex-col">
                        <label class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Deskripsi / Ringkasan Isi</label>
                        <textarea id="d_desc" name="description" class="w-full flex-1 min-h-[120px] bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm text-gray-700 focus:ring-2 focus:ring-primary-500 outline-none resize-none transition-all" placeholder="Tuliskan ringkasan singkat isi peraturan atau keputusan ini agar mudah dipahami masyarakat umum..."></textarea>
                    </div>

                </div>

            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 shrink-0 flex justify-end gap-3 rounded-b-3xl z-10">
                <button type="button" @click="docModalOpen = false" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-white border border-gray-300 hover:bg-gray-100 transition-colors shadow-sm">Batal</button>
                <button type="submit" class="px-8 py-2.5 rounded-xl text-sm font-bold text-white bg-primary-700 hover:bg-primary-800 shadow-md transition-all flex items-center gap-2"><i class="fa-solid fa-save"></i> Simpan & Publikasi</button>
            </div>
        </form>
    </div>
</div>

<!-- 2. MODAL: MANAJEMEN KATEGORI JDIH -->
<div x-show="categoryModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="categoryModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="categoryModalOpen = false"></div>

    <div x-show="categoryModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col">
        
        <form id="categoryForm" action="{{ route('admin.jdih.storeCategory') }}" method="POST">
            @csrf
            <input type="hidden" name="id" :value="categoryId">

            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-extrabold text-lg text-gray-900" x-text="categoryId ? 'Edit Kategori JDIH' : 'Tambah Kategori JDIH'"></h3>
                <button type="button" @click="categoryModalOpen = false" class="text-gray-400 hover:text-red-500 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <div class="p-6 space-y-5">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" id="c_name" name="name" required placeholder="Misal: Peraturan Bupati" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase">Deskripsi / Keterangan (Opsional)</label>
                    <textarea id="c_desc" name="description" rows="3" placeholder="Jelaskan jenis peraturan ini..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 outline-none resize-none"></textarea>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3 rounded-b-3xl">
                <button type="button" @click="categoryModalOpen = false" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-white border border-gray-200 hover:bg-gray-100 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-primary-700 hover:bg-primary-800 shadow-md transition-all flex items-center gap-2"><i class="fa-solid fa-save"></i> Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>
