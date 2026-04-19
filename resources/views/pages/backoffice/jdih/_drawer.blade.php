<!-- 1. DRAWER: DETAIL PRODUK HUKUM -->
<div x-show="detailDrawerOpen" class="fixed inset-0 z-[100] flex justify-end" x-cloak>
    <div x-show="detailDrawerOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="detailDrawerOpen = false"></div>

    <div x-show="detailDrawerOpen" x-transition:enter="transition ease-transform duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-transform duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="relative bg-white w-full max-w-2xl h-full shadow-2xl flex flex-col border-l border-gray-200">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <div class="flex items-center gap-2">
                <template x-if="previewData.status === 'berlaku'">
                    <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded border border-green-200 uppercase tracking-widest"><i class="fa-solid fa-check mr-1"></i> Status: Berlaku</span>
                </template>
                <template x-if="previewData.status === 'dicabut'">
                    <span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-0.5 rounded border border-red-200 uppercase tracking-widest"><i class="fa-solid fa-ban mr-1"></i> Status: Dicabut</span>
                </template>
                <template x-if="previewData.status === 'draft'">
                    <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-0.5 rounded border border-gray-200 uppercase tracking-widest"><i class="fa-solid fa-pen-ruler mr-1"></i> Status: Draft</span>
                </template>
            </div>
            <div class="flex gap-2">
                <button @click="openDocForm(previewData.id); detailDrawerOpen = false" class="text-gray-400 hover:text-amber-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-amber-50 transition-colors" title="Edit Data"><i class="fa-solid fa-pen"></i></button>
                <button @click="detailDrawerOpen = false" class="text-gray-400 hover:text-gray-800 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-200 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
        </div>

        <div class="overflow-y-auto custom-scrollbar flex-1 p-6 space-y-6">
            <!-- Document Header -->
            <div>
                <h4 class="text-[10px] font-bold text-primary-600 bg-primary-50 px-2 py-1 rounded inline-block uppercase tracking-wider mb-2" x-text="previewData.category ? previewData.category.name : ''"></h4>
                <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 leading-tight mb-2" x-text="previewData.title"></h2>
                <p class="text-sm font-mono text-gray-600">Nomor: <span x-text="previewData.document_number"></span></p>
            </div>

            <!-- Document Metadata -->
            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Tgl Ditetapkan</p>
                    <p class="text-sm font-semibold text-gray-800" x-text="formatDate(previewData.established_date)"></p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Diupload Oleh</p>
                    <p class="text-sm font-semibold text-gray-800" x-text="previewData.uploader ? previewData.uploader.name : 'Sistem'"></p>
                </div>
            </div>
            
            <div x-show="previewData.description">
                <h4 class="text-[10px] font-bold text-gray-400 uppercase mb-2">Deskripsi Kandungan Pokok</h4>
                <p class="text-sm text-gray-600 leading-relaxed" x-text="previewData.description"></p>
            </div>

            <!-- PDF Viewer Placeholder -->
            <div>
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Dokumen Digital</h4>
                
                <template x-if="previewData.file_path">
                    <div class="w-full h-[400px] bg-gray-200 border-2 border-gray-300 rounded-xl flex flex-col items-center justify-center text-gray-500 overflow-hidden relative group">
                        <div class="absolute inset-0 bg-white/50 backdrop-blur-sm z-10 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <a :href="'/storage/' + previewData.file_path" target="_blank" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg flex items-center gap-2 transition-transform transform hover:scale-105"><i class="fa-solid fa-download"></i> Unduh / Buka File PDF</a>
                            <p class="text-[10px] text-gray-600 mt-2 font-mono" x-text="'Ukuran: ' + formatBytes(previewData.file_size)"></p>
                        </div>
                        <i class="fa-regular fa-file-pdf text-6xl text-red-400 mb-4 opacity-50"></i>
                        <span class="text-sm font-bold text-gray-400">PDF Terlampir Tersedia</span>
                    </div>
                </template>
                
                <template x-if="!previewData.file_path">
                    <div class="w-full h-32 bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl flex flex-col items-center justify-center text-gray-400">
                        <i class="fa-solid fa-folder-open text-2xl mb-2 opacity-50"></i>
                        <span class="text-xs font-bold">PDF Tidak Terlampir</span>
                        <span class="text-[10px] text-gray-500 mt-1">Sistem hanya menyimpan data histori tanpa fisikal dokumen</span>
                    </div>
                </template>
            </div>

            <!-- Activity Log -->
            <div>
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Histori Pencatatan</h4>
                <ul class="relative border-l-2 border-gray-200 ml-2 pl-4 py-1 space-y-4">
                    <li class="relative z-10">
                        <div class="absolute -left-[23px] top-1 w-2.5 h-2.5 rounded-full bg-green-500 border-2 border-white"></div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase mb-0.5" x-text="formatDate(previewData.created_at)"></p>
                        <p class="text-xs font-semibold text-gray-800">Diregistrasikan ke JDIH oleh <span x-text="previewData.uploader ? previewData.uploader.name : 'Sistem'"></span>.</p>
                    </li>
                </ul>
            </div>

        </div>

        <div class="p-6 border-t border-gray-100 bg-white shrink-0 flex justify-end">
            <template x-if="previewData.file_path">
                <a :href="'/storage/' + previewData.file_path" target="_blank" class="w-full bg-red-50 text-red-600 hover:bg-red-100 px-4 py-3.5 rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center justify-center gap-2"><i class="fa-solid fa-download"></i> Download / Buka Murni Dokumen PDF (Publik Raw)</a>
            </template>
        </div>
    </div>
</div>

<!-- 2. MODAL: PREVIEW PRODUK HUKUM PUBLIK (JDIH SIMULATOR) -->
<div x-show="previewModalOpen" class="fixed inset-0 z-[200] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="previewModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/80 backdrop-blur-sm" @click="previewModalOpen = false"></div>

    <div x-show="previewModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative bg-gray-100 rounded-3xl shadow-2xl w-full max-w-5xl overflow-hidden flex flex-col h-[90vh]">

        <div class="px-4 py-3 bg-white border-b border-gray-200 flex justify-between items-center shrink-0 shadow-sm z-10">
            <div class="flex gap-1.5 w-16">
                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                <div class="w-3 h-3 rounded-full bg-green-400"></div>
            </div>

            <div class="flex-1 max-w-md mx-auto bg-gray-100 rounded-lg px-3 py-1.5 flex items-center gap-2 text-xs text-gray-500 font-mono text-center justify-center">
                <i class="fa-solid fa-lock text-gray-400"></i> jdih.sukakerta.desa.id
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

        <div class="flex-1 bg-gray-200 overflow-y-auto custom-scrollbar flex justify-center p-4 sm:p-8 transition-all duration-500">
            <div class="bg-white shadow-lg overflow-hidden transition-all duration-500 ease-in-out border border-gray-200 flex flex-col" :class="previewDevice === 'desktop' ? 'w-full max-w-4xl rounded-t-xl min-h-full' : 'w-[375px] h-[812px] rounded-3xl ring-8 ring-gray-800 shrink-0'">

                <!-- Web Header Fake -->
                <div class="bg-primary-800 text-white px-6 py-4 flex justify-between items-center shrink-0">
                    <div class="font-black text-lg flex items-center gap-2"><i class="fa-solid fa-scale-balanced text-primary-300"></i> JDIH Desa</div>
                    <div class="hidden sm:flex gap-6 text-xs font-bold text-primary-100">
                        <span>Beranda</span>
                        <span class="text-white border-b-2 border-white pb-1">Produk Hukum</span>
                        <span>Pencarian</span>
                    </div>
                    <div class="sm:hidden text-primary-200"><i class="fa-solid fa-bars text-xl"></i></div>
                </div>

                <!-- Web Content Fake (JDIH Portal) -->
                <div class="flex-1 overflow-y-auto custom-scrollbar bg-gray-50">
                    <!-- Hero Search -->
                    <div class="bg-primary-900 p-8 sm:p-12 text-center text-white">
                        <h1 class="text-2xl sm:text-3xl font-extrabold font-serif mb-3">Jaringan Dokumentasi dan Informasi Hukum</h1>
                        <p class="text-xs text-primary-200 mb-6 max-w-lg mx-auto">Akses transparansi regulasi dan keputusan strategis Pemerintah Desa Sukakerta.</p>

                        <div class="max-w-xl mx-auto flex bg-white rounded-xl overflow-hidden shadow-lg p-1">
                            <select class="bg-transparent text-gray-600 text-xs font-bold px-3 outline-none border-r border-gray-200 hidden sm:block">
                                <option>Semua Jenis</option>
                            </select>
                            <input type="text" placeholder="Cari nomor atau judul peraturan..." class="flex-1 px-4 py-3 text-sm text-gray-800 outline-none">
                            <button class="bg-primary-600 hover:bg-primary-700 text-white px-6 rounded-lg text-sm font-bold transition-colors"><i class="fa-solid fa-search"></i></button>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 max-w-4xl mx-auto">
                        <div class="space-y-4">
                            @if(isset($documents))
                                @foreach($documents->take(5) as $sim)
                                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row gap-5 items-start hover:border-primary-300 transition-colors {{ $sim->status != 'berlaku' ? 'opacity-70' : '' }}">
                                    <div class="w-12 h-14 bg-red-50 text-red-500 rounded-lg flex items-center justify-center border border-red-100 shrink-0 shadow-sm">
                                        <i class="fa-solid fa-file-pdf text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-[10px] font-bold text-primary-700 uppercase tracking-widest block mb-1">{{ $sim->category->name }}</span>
                                        <h3 class="text-lg font-bold text-gray-900 leading-tight mb-1 hover:text-primary-700 cursor-pointer">{{ $sim->title }}</h3>
                                        <p class="text-xs text-gray-500 font-mono mb-3">Nomor: {{ $sim->document_number }} &bull; Ditetapkan: {{ $sim->established_date->translatedFormat('d M Y') }}</p>
                                        @if($sim->status == 'berlaku')
                                            <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded border border-green-200 uppercase tracking-wider">Berlaku</span>
                                        @elseif($sim->status == 'dicabut')
                                            <span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-0.5 rounded border border-red-200 uppercase tracking-wider">Dicabut/Arsip</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-0.5 rounded border border-gray-200 uppercase tracking-wider">Draft</span>
                                        @endif
                                    </div>
                                    <div class="shrink-0 w-full sm:w-auto mt-2 sm:mt-0">
                                        @if($sim->file_path)
                                            <a href="/storage/{{ $sim->file_path }}" target="_blank" class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-xs font-bold transition-colors shadow-sm flex items-center justify-center gap-2"><i class="fa-solid fa-download"></i> Unduh File</a>
                                        @else
                                            <button disabled class="w-full sm:w-auto bg-gray-50 text-gray-400 px-4 py-2 rounded-xl text-xs font-bold shadow-sm flex items-center justify-center gap-2 cursor-not-allowed border border-gray-100"><i class="fa-solid fa-folder"></i> Belum Ada File</button>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="mt-8 text-center">
                            <button class="bg-white border border-gray-300 text-gray-600 px-6 py-2 rounded-full text-xs font-bold shadow-sm hover:bg-gray-50 transition-colors">Muat Lebih Banyak Dokumen</button>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-900 p-6 text-center shrink-0">
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest">&copy; {{ date('Y') }} JDIH Pemerintah Desa Sukakerta</p>
                </div>
            </div>

            <div class="p-4 border-t border-gray-200 bg-white shrink-0 flex justify-between items-center text-xs text-gray-500">
                <span>Simulator Pratinjau Tampilan Portal JDIH Publik</span>
            </div>
        </div>
    </div>
</div>
