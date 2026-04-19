<div x-show="addModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="addModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="addModalOpen = false"></div>

    <div x-show="addModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[90vh]">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <h3 class="font-extrabold text-lg text-gray-900">Form Pembangunan Baru</h3>
            <button @click="addModalOpen = false" class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <form action="{{ route('admin.pembangunan.store') }}" method="POST" class="overflow-y-auto custom-scrollbar flex-1 flex flex-col md:flex-row">
            @csrf
            
            <!-- Kolom Kiri: Info Proyek -->
            <div class="w-full md:w-1/2 p-6 border-b md:border-b-0 md:border-r border-gray-100 space-y-5">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase">Nama Proyek <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_proyek" required placeholder="Misal: Pembangunan Sumur Bor..."
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase">Deskripsi & Volume</label>
                    <textarea name="deskripsi" rows="2" placeholder="Detail fisik (panjang, luas, unit)..."
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 outline-none resize-none"></textarea>
                </div>

                <!-- Budget Linking Panel -->
                <div class="p-4 bg-green-50 border border-green-100 rounded-xl">
                    <label class="text-[10px] font-bold text-green-800 uppercase tracking-widest mb-2 block"><i class="fa-solid fa-link"></i> Link ke APBDes</label>
                    <select name="apbdes_id" class="w-full bg-white border border-green-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none text-gray-700 font-semibold cursor-pointer">
                        <option value="">-- Pagu Mandiri / Tanpa Link --</option>
                        @foreach($apbdesOptions as $ap)
                            <option value="{{ $ap->id }}">{{ $ap->kode_rekening }} - {{ $ap->nama_kegiatan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none font-semibold">
                        <option value="Infrastruktur Jalan">Infrastruktur Jalan</option>
                        <option value="Fasilitas Umum">Fasilitas Umum</option>
                        <option value="Irigasi/Pertanian">Irigasi/Pertanian</option>
                        <option value="Sanitasi/Air Bersih">Sanitasi/Air Bersih</option>
                        <option value="Lainnya">Kategori Lainnya</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 uppercase">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 uppercase">Target Selesai</label>
                        <input type="date" name="target_selesai" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase">Status Proyek <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none font-semibold">
                        <option value="perencanaan" selected>Tahap Perencanaan</option>
                        <option value="berjalan">Sedang Berjalan</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
            </div>

            <!-- Kolom Kanan: Lokasi Spasial -->
            <div class="w-full md:w-1/2 p-6 bg-gray-50/50 flex flex-col space-y-4">
                <div>
                    <h4 class="text-sm font-bold text-gray-800 mb-1"><i class="fa-solid fa-map-location-dot text-green-700 mr-1"></i> Penentuan Titik Lokasi</h4>
                    <p class="text-[10px] text-gray-500">Pilih wilayah administratif dan sesuaikan koordinat peta.</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <input type="text" name="lokasi_dusun" placeholder="Nama Dusun" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                    <input type="text" name="rt_rw" placeholder="RT / RW" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[10px] font-bold text-gray-500">Latitude</label>
                        <input type="text" name="latitude" id="latInput" placeholder="-7.1726" class="w-full bg-gray-100 border border-gray-200 rounded-lg px-3 py-2 text-xs outline-none">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500">Longitude</label>
                        <input type="text" name="longitude" id="lngInput" placeholder="108.1963" class="w-full bg-gray-100 border border-gray-200 rounded-lg px-3 py-2 text-xs outline-none">
                    </div>
                </div>

                <!-- Simulated Map Input -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden flex-1 relative min-h-[250px] shadow-inner">
                    <div class="absolute inset-0 flex items-center justify-center p-6 text-center text-gray-400">
                        <div>
                            <i class="fa-solid fa-map text-4xl mb-3 opacity-30"></i>
                            <p class="text-xs">Titik Koordinat dapat langsung Anda ketik pada kotak isian di atas.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Absolute Submit Footer inside the form -->
            <div class="absolute bottom-0 left-0 right-0 px-6 py-4 border-t border-gray-100 bg-white shrink-0 flex justify-end gap-3 z-10">
                <button type="button" @click="addModalOpen = false" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 border border-gray-200 hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-8 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md transition-all flex items-center gap-2"><i class="fa-solid fa-save"></i> Simpan Proyek</button>
            </div>
            
            <!-- Spacer to push content above absolute footer -->
            <div class="h-20 w-full md:hidden"></div>
        </form>
    </div>
</div>
