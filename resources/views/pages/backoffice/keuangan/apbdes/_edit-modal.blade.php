<div x-show="editBudgetModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
    <div x-show="editBudgetModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="editBudgetModalOpen = false"></div>

    <div x-show="editBudgetModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col z-10 max-h-[90vh]">

        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <div>
                <h3 class="font-extrabold text-xl text-gray-900 tracking-tight">Edit Anggaran</h3>
                <p class="text-xs text-gray-500 mt-0.5">Ubah rincian kegiatan dan pagu anggaran.</p>
            </div>
            <button @click="editBudgetModalOpen = false" class="text-gray-400 hover:text-red-500 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form :action="`{{ url('admin/apbdes') }}/${editData.id}`" method="POST" class="flex-1 overflow-y-auto p-6 space-y-5 custom-scrollbar">
            @csrf
            @method('PUT')
            <input type="hidden" name="tahun" :value="editData.tahun">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Tipe Anggaran <span class="text-red-500">*</span></label>
                    <select name="tipe_anggaran" x-model="editData.tipe_anggaran" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none cursor-pointer">
                        <option value="PENDAPATAN">Pendapatan</option>
                        <option value="BELANJA">Belanja</option>
                        <option value="PEMBIAYAAN">Pembiayaan</option>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Kode Rekening <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_rekening" x-model="editData.kode_rekening" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none font-mono">
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-700">Nama Kegiatan / Mata Anggaran <span class="text-red-500">*</span></label>
                <input type="text" name="nama_kegiatan" x-model="editData.nama_kegiatan" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Nominal Anggaran (Pagu) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">Rp</span>
                        <input type="number" name="pagu_anggaran" x-model="editData.pagu_anggaran" required min="0"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-12 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none font-bold">
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Sumber Dana</label>
                    <select name="sumber_dana" x-model="editData.sumber_dana"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500 outline-none cursor-pointer">
                        <option value="">-- Pilih Sumber Dana --</option>
                        <option value="DD">Dana Desa (DD)</option>
                        <option value="ADD">Alokasi Dana Desa (ADD)</option>
                        <option value="PADesa">PADesa</option>
                        <option value="PAD">Pendapatan Asli Daerah (PAD)</option>
                        <option value="BanProv">Bantuan Provinsi (BanProv)</option>
                        <option value="BKP">Bantuan Keuangan Provinsi (BKP)</option>
                        <option value="PBH">Pajak Bagi Hasil (PBH)</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
            </div>

            <label class="flex items-center gap-3 bg-amber-50/50 border border-amber-100 p-3 rounded-xl cursor-pointer mt-4">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" x-model="editData.is_published" class="w-4 h-4 text-amber-600 rounded focus:ring-amber-500">
                <div>
                    <p class="text-sm font-bold text-amber-900">Publikasikan</p>
                    <p class="text-[10px] text-amber-700/80">Tampilkan item anggaran ini di grafik transparansi publik.</p>
                </div>
            </label>

            <div class="pt-6 mt-4 border-t border-gray-100 grid grid-cols-2 gap-3 pb-2">
                <button type="button" @click="editBudgetModalOpen = false"
                    class="px-6 py-3 rounded-xl text-sm font-bold text-gray-500 bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer">Batal</button>
                <button type="submit"
                    class="px-6 py-3 rounded-xl text-sm font-bold text-white bg-amber-600 hover:bg-amber-700 shadow-lg cursor-pointer">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
