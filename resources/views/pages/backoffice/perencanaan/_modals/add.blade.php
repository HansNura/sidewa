<div x-show="addModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="addModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="addModalOpen = false"></div>

    <div x-show="addModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white rounded-3xl shadow-2xl w-full max-w-3xl overflow-hidden flex flex-col max-h-[90vh]">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <h3 class="font-extrabold text-lg text-gray-900">Input Data Perencanaan Baru</h3>
            <button @click="addModalOpen = false" class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6">
            <form action="{{ route('admin.perencanaan.store') }}" method="POST" class="space-y-6" id="addRencanaForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 border-b border-gray-100 pb-5">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 uppercase">Jenis Rencana <span class="text-red-500">*</span></label>
                        <select name="jenis_rencana" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                            <option value="rkpdes">RKPDes (Tahunan)</option>
                            <option value="rpjmdes">RPJMDes (Jangka Menengah)</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 uppercase">Periode / Tahun Pelaksanaan <span class="text-red-500">*</span></label>
                        <input type="text" name="tahun_pelaksanaan" required value="{{ date('Y') + 1 }}" placeholder="Contoh: 2026 atau 2024-2030"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 uppercase">Tingkat Prioritas <span class="text-red-500">*</span></label>
                        <select name="prioritas" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                            <option value="tinggi">Sangat Mendesak (Wajib)</option>
                            <option value="sedang" selected>Mendesak (Prioritas 2)</option>
                            <option value="normal">Normal / Tambahan</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 uppercase">Kategori / Bidang <span class="text-red-500">*</span></label>
                        <select name="kategori" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                            <option value="Pelaksanaan Pembangunan">Pembangunan Fisik</option>
                            <option value="Pemberdayaan Masyarakat">Pemberdayaan Masyarakat</option>
                            <option value="Penyelenggaraan Pemdes">Penyelenggaraan Pemdes</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase">Nama Program / Kegiatan Rencana <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_program" required placeholder="Misal: Pembangunan Saluran Irigasi Tersier..."
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase">Tujuan & Sasaran (Objective)</label>
                    <textarea name="tujuan_sasaran" rows="3" placeholder="Jelaskan secara singkat manfaat dari program ini bagi masyarakat..."
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 outline-none resize-none"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 bg-gray-50 border border-gray-100 p-4 rounded-xl shadow-sm">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-600 uppercase tracking-widest"><i class="fa-solid fa-coins mr-1"></i> Estimasi Anggaran Pagu <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">Rp</span>
                            <input type="number" name="estimasi_pagu" required placeholder="0" min="0" value="0"
                                class="w-full bg-white border border-gray-200 rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none font-bold">
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-600 uppercase tracking-widest"><i class="fa-solid fa-piggy-bank mr-1"></i> Rencana Sumber Dana</label>
                        <select name="sumber_dana" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                            <option value="Dana Desa (DD)">Dana Desa (DD)</option>
                            <option value="Alokasi Dana Desa (ADD)">Alokasi Dana Desa (ADD)</option>
                            <option value="Pendapatan Asli Desa (PADesa)">Pendapatan Asli Desa (PADesa)</option>
                            <option value="Bantuan Provinsi / Kab">Bantuan Provinsi / Kab</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 uppercase">Target Waktu Mulai</label>
                        <input type="month" name="target_mulai" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 uppercase">Target Selesai (Estimasi)</label>
                        <input type="month" name="target_selesai" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                    </div>
                </div>
            </form>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-white shrink-0 flex justify-end gap-3 rounded-b-3xl">
            <button @click="addModalOpen = false" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 border border-gray-200 hover:bg-gray-50 transition-colors">Batal</button>
            <button type="submit" form="addRencanaForm" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md transition-all flex items-center gap-2"><i class="fa-solid fa-check"></i> Daftarkan Rencana</button>
        </div>
    </div>
</div>
