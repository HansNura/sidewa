<div x-show="inputModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="inputModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="inputModalOpen = false"></div>

    <div x-show="inputModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <h3 class="font-extrabold text-lg text-gray-900">Input Realisasi Anggaran Baru</h3>
            <button @click="inputModalOpen = false"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.realisasi.store') }}" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto custom-scrollbar flex flex-col">
            @csrf
            <div class="p-6 space-y-6 flex-1">
                <!-- Kegiatan Selector -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase">Pilih Kegiatan (T.A {{ $tahun }}) <span class="text-red-500">*</span></label>
                    <select name="apbdes_id" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer font-semibold text-gray-800">
                        <option value="">Pilih Pos Kegiatan Belanja...</option>
                        @foreach($kegiatans as $k)
                            <option value="{{ $k->id }}">
                                {{ $k->kode_rekening }} - {{ mb_strimwidth($k->nama_kegiatan, 0, 50, "...") }} 
                                (Pagu: Rp {{ number_format($k->pagu_anggaran, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Realization Input -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 uppercase">Nominal Realisasi <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                            <input type="number" name="nominal" placeholder="0" required min="1"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-12 pr-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-green-500 outline-none">
                        </div>
                    </div>
                    <!-- Date Input -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 uppercase">Tanggal Transaksi <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_transaksi" required value="{{ date('Y-m-d') }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none font-semibold cursor-pointer">
                    </div>
                </div>

                <!-- Document Upload -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase">Unggah Bukti Transaksi <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="file" name="bukti_file" accept=".pdf,.jpg,.jpeg,.png" id="bukti-file-upload" class="hidden">
                    <label for="bukti-file-upload" class="block border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center hover:bg-gray-50 transition-colors cursor-pointer group">
                        <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-300 group-hover:text-green-500 mb-2 transition-colors"></i>
                        <p class="text-xs font-bold text-gray-700">Klik untuk upload foto nota/kuitansi/SJB</p>
                        <p class="text-[9px] text-gray-400 mt-1 uppercase tracking-widest">PDF, JPG, PNG (Maks. 5MB)</p>
                    </label>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase">Catatan / Keterangan</label>
                    <textarea name="catatan" rows="3" placeholder="Misal: Pembayaran Termin 1 Material Semen..."
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 outline-none resize-none transition-all"></textarea>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 shrink-0 flex justify-end gap-3 rounded-b-3xl">
                <button type="button" @click="inputModalOpen = false"
                    class="px-6 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-white border border-gray-300 hover:bg-gray-100 transition-colors">Batal</button>
                <button type="submit"
                    class="px-8 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md">Simpan
                    Realisasi</button>
            </div>
        </form>
    </div>
</div>
