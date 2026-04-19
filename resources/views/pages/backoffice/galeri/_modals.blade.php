<!-- 1. MODAL: UPLOAD & KELOLA MEDIA -->
<div x-show="uploadModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="uploadModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="uploadModalOpen = false"></div>

    <div x-show="uploadModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[90vh]">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <h3 class="font-extrabold text-lg text-gray-900">Upload Media Baru</h3>
            <button @click="uploadModalOpen = false" class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <form action="{{ route('admin.galeri.media.store') }}" method="POST" enctype="multipart/form-data" class="overflow-y-auto custom-scrollbar flex-1 flex flex-col md:flex-row">
            @csrf
            
            <!-- Kolom Kiri: Input Files -->
            <div class="w-full md:w-1/2 p-6 border-b md:border-b-0 md:border-r border-gray-100 flex flex-col gap-4">
                <div class="space-y-1 h-full">
                    <label class="text-xs font-bold text-gray-700 uppercase mb-2 block">Pilih File (Bisa multi-file)</label>
                    <div class="h-full border-2 border-dashed border-green-300 bg-green-50/50 rounded-2xl flex flex-col items-center justify-center text-center cursor-pointer hover:bg-green-50 transition-colors min-h-[250px] relative">
                        <input type="file" name="files[]" multiple accept="image/jpeg,image/png,video/mp4" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-green-500 text-2xl shadow-sm mb-4">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                        </div>
                        <h4 class="font-bold text-gray-800">Klik / Tarik File di Sini</h4>
                        <p class="text-[10px] font-mono text-gray-400 mt-4 uppercase">JPG, PNG (Max 5MB) | MP4 (Max 20MB)</p>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Meta Data Input -->
            <div class="w-full md:w-1/2 p-6 bg-gray-50/50 space-y-5">
                <div class="bg-blue-50 border border-blue-100 p-3 rounded-xl flex items-start gap-2">
                    <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 text-xs"></i>
                    <p class="text-[10px] text-blue-800 leading-relaxed">Pengaturan di bawah ini akan diterapkan ke <b>semua</b> file dalam antrean upload saat ini (Bulk Update).</p>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase">Simpan ke Album</label>
                    <select name="album_id" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                        <option value="">Tanpa Album</option>
                        @foreach($albums as $ab)
                            <option value="{{ $ab->id }}">{{ $ab->nama_album }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase">Deskripsi Umum</label>
                    <textarea name="deskripsi" rows="3" placeholder="Tuliskan keterangan mengenai foto/video ini..." class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 outline-none resize-none"></textarea>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase">Tags / Label</label>
                    <input type="text" name="tags" placeholder="Misal: Jalan, Pembangunan, Gotong Royong..." class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                    <p class="text-[10px] text-gray-400 mt-1">Pisahkan dengan koma (,).</p>
                </div>
            </div>
            
            <div class="absolute bottom-0 left-0 w-full px-6 py-4 border-t border-gray-100 bg-white shrink-0 flex justify-end gap-3 z-10">
                <button type="button" @click="uploadModalOpen = false" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 border border-gray-200 hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-8 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md transition-all flex items-center gap-2"><i class="fa-solid fa-cloud-arrow-up"></i> Terapkan & Upload</button>
            </div>
            <div class="h-20 w-full md:hidden"></div>
        </form>
    </div>
</div>

<!-- 2. DRAWER: DETAIL MEDIA -->
<div x-show="detailDrawerOpen" class="fixed inset-0 z-[100] flex justify-end" x-cloak>
    <div x-show="detailDrawerOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="detailDrawerOpen = false"></div>

    <div x-show="detailDrawerOpen" x-transition:enter="transition ease-transform duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-transform duration-300" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="relative bg-white w-full max-w-md h-full shadow-2xl flex flex-col border-l border-gray-200">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <h3 class="font-extrabold text-lg text-gray-900">Detail File Media</h3>
            <div class="flex gap-2">
                <button @click="detailDrawerOpen = false" class="text-gray-400 hover:text-gray-800 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-200 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
        </div>

        <div class="overflow-y-auto custom-scrollbar flex-1">
            <!-- Media Viewer -->
            <div class="w-full bg-gray-900 flex items-center justify-center p-4 min-h-[250px]">
                <template x-if="selectedMedia.type === 'video/mp4'">
                    <video :src="selectedMedia.url" controls class="max-w-full max-h-64 object-contain rounded shadow-lg border border-gray-700"></video>
                </template>
                <template x-if="selectedMedia.type !== 'video/mp4'">
                    <img :src="selectedMedia.url" class="max-w-full max-h-64 object-contain rounded shadow-lg border border-gray-700" alt="Preview">
                </template>
            </div>

            <div class="p-6 space-y-6">
                <!-- Media Info -->
                <div>
                    <h4 class="text-xl font-extrabold text-gray-900 leading-tight mb-2" x-text="selectedMedia.title"></h4>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded flex items-center gap-1"><i class="fa-regular fa-folder"></i> <span x-text="selectedMedia.album"></span></span>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 grid grid-cols-2 gap-2 text-xs">
                        <div><span class="text-gray-400 block text-[9px] uppercase">Format</span><span class="font-semibold text-gray-800 font-mono" x-text="selectedMedia.type"></span></div>
                        <div><span class="text-gray-400 block text-[9px] uppercase">Ukuran File</span><span class="font-semibold text-gray-800" x-text="selectedMedia.size"></span></div>
                        <div><span class="text-gray-400 block text-[9px] uppercase">Tanggal Upload</span><span class="font-semibold text-gray-800" x-text="selectedMedia.date"></span></div>
                        <div><span class="text-gray-400 block text-[9px] uppercase">Diunggah Oleh</span><span class="font-semibold text-gray-800" x-text="selectedMedia.uploader"></span></div>
                    </div>
                </div>

                <!-- Direct Link -->
                <div>
                    <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">URL Publik Langsung</h5>
                    <div class="flex gap-2">
                        <input type="text" :value="selectedMedia.url" readonly class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-[10px] font-mono text-gray-500 focus:outline-none">
                        <a :href="selectedMedia.url" target="_blank" class="bg-white border border-gray-200 text-gray-600 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors" title="Buka URL"><i class="fa-solid fa-arrow-up-right-from-square text-xs"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3. MODAL: MANAJEMEN ALBUM -->
<div x-show="albumModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="albumModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="albumModalOpen = false"></div>

    <div x-show="albumModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-extrabold text-lg text-gray-900">Form Data Album Baru</h3>
            <button @click="albumModalOpen = false" class="text-gray-400 hover:text-red-500 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <form action="{{ route('admin.galeri.album.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-700 uppercase">Nama Album <span class="text-red-500">*</span></label>
                <input type="text" name="nama_album" required placeholder="Misal: Perayaan HUT RI 79..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-700 uppercase">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" placeholder="Tuliskan keterangan album..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 outline-none resize-none"></textarea>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3 mt-4">
                <button type="button" @click="albumModalOpen = false" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-white border border-gray-200 hover:bg-gray-100 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md transition-all flex items-center gap-2"><i class="fa-solid fa-save"></i> Simpan Album</button>
            </div>
        </form>
    </div>
</div>

<!-- 4. MODAL: PREVIEW WEB PUBLIK (SIMULATOR) -->
<div x-show="previewModalOpen" class="fixed inset-0 z-[120] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="previewModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/80 backdrop-blur-sm"
        @click="previewModalOpen = false"></div>

    <div x-show="previewModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        class="relative bg-gray-100 rounded-3xl shadow-2xl w-full max-w-5xl overflow-hidden flex flex-col h-[90vh]">

        <div class="px-4 py-3 bg-white border-b border-gray-200 flex justify-between items-center shrink-0 shadow-sm z-10">
            <div class="flex gap-1.5 w-16">
                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                <div class="w-3 h-3 rounded-full bg-green-400"></div>
            </div>

            <div class="flex-1 max-w-md mx-auto bg-gray-100 rounded-lg px-3 py-1.5 flex items-center gap-2 text-xs text-gray-500 font-mono text-center justify-center">
                <i class="fa-solid fa-lock text-gray-400"></i> sindangmukti.desa.id/galeri
            </div>

            <div class="flex items-center gap-2 justify-end w-32">
                <div class="bg-gray-100 rounded-lg flex p-1">
                    <button @click="previewDevice = 'desktop'" :class="previewDevice === 'desktop' ? 'bg-white shadow-sm text-gray-800' : 'text-gray-400'" class="w-7 h-7 rounded flex items-center justify-center transition-all"><i class="fa-solid fa-desktop text-xs"></i></button>
                    <button @click="previewDevice = 'mobile'" :class="previewDevice === 'mobile' ? 'bg-white shadow-sm text-gray-800' : 'text-gray-400'" class="w-7 h-7 rounded flex items-center justify-center transition-all"><i class="fa-solid fa-mobile-screen text-xs"></i></button>
                </div>
                <div class="w-px h-5 bg-gray-300 mx-1"></div>
                <button @click="previewModalOpen = false" class="text-gray-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto custom-scrollbar flex justify-center bg-gray-200 p-4 sm:p-8 transition-all duration-500">
            <div class="bg-white shadow-lg overflow-hidden transition-all duration-500 ease-in-out border border-gray-200"
                :class="previewDevice === 'desktop' ? 'w-full max-w-4xl rounded-t-xl min-h-full' : 'w-[375px] h-[812px] rounded-3xl ring-8 ring-gray-800 relative isolate'">

                <div class="bg-green-800 text-white p-4 sm:p-6 text-center">
                    <h1 class="text-xl sm:text-2xl font-black">Galeri Desa Sindangmukti</h1>
                    <p class="text-xs text-green-200 mt-1">Dokumentasi kegiatan dan pesona alam desa.</p>
                </div>

                <div class="p-4 sm:p-8">
                    <div class="flex gap-2 overflow-x-auto pb-4 mb-4 custom-scrollbar">
                        <span class="bg-green-600 text-white px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap">Semua</span>
                        @foreach($albums->take(3) as $al)
                            <span class="bg-gray-100 text-gray-600 px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap">{{ $al->nama_album }}</span>
                        @endforeach
                    </div>

                    <div class="grid gap-4" :class="previewDevice === 'desktop' ? 'grid-cols-3' : 'grid-cols-2'">
                        @foreach($medias->take(6) as $m)
                            <div class="aspect-square bg-gray-200 rounded-xl overflow-hidden relative">
                                @if($m->file_type == 'image')
                                    <img src="{{ $m->url }}" class="w-full h-full object-cover">
                                @else
                                    <video src="{{ $m->url }}" class="w-full h-full object-cover"></video>
                                    <div class="absolute inset-0 flex items-center justify-center"><i class="fa-solid fa-play text-white drop-shadow-md text-xl"></i></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 border-t border-gray-200 bg-white shrink-0 flex justify-between items-center text-xs text-gray-500">
            <span>Mode Pratinjau Tampilan Publik</span>
            <span class="font-bold text-green-600"><i class="fa-solid fa-globe mr-1"></i> Modul Galeri Aktif</span>
        </div>
    </div>
</div>
