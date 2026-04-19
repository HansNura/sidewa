<div x-show="addBudgetModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
    <div x-show="addBudgetModal" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="addBudgetModal = false"></div>

    <div x-show="addBudgetModal" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col p-8 z-10">

        <div class="flex justify-between items-center mb-6">
            <h3 class="font-extrabold text-xl text-gray-900 tracking-tight">Entri Anggaran Cepat TA {{ $tahun }}</h3>
            <button @click="addBudgetModal = false" class="text-gray-400 hover:text-red-500 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.apbdes.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="tahun" value="{{ $tahun }}">
            <input type="hidden" name="tipe_anggaran" value="BELANJA">
            
            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-600 uppercase">Pilih Bidang Induk</label>
                <select name="parent_bidang"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                    @foreach($strukturs as $bidangKey => $bidangData)
                        <option value="{{ $bidangKey }}">Bidang {{ $bidangKey }}: {{ $bidangData['bidang_item'] ? $bidangData['bidang_item']->nama_kegiatan : "Bidang $bidangKey" }}</option>
                    @endforeach
                </select>
                <p class="text-[10px] text-gray-400 mt-1">Perhatian: Gunakan penambahan manual Kode Rekening di Menu Input Anggaran untuk kontrol penuh. Ini adalah jalan pintas.</p>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-600 uppercase">Kode Rekening Lengkap</label>
                <input type="text" name="kode_rekening" placeholder="Contoh: 1.1.04" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none font-mono tracking-wider">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-600 uppercase">Uraian / Kegiatan</label>
                <input type="text" name="nama_kegiatan" required autocomplete="off"
                    class="w-full bg-white border border-gray-200 shadow-sm rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-600 uppercase">Anggaran (Pagu)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                    <input type="number" name="pagu_anggaran" min="0" required
                        class="w-full bg-white border border-gray-200 shadow-sm rounded-xl pl-12 pr-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-green-500 outline-none">
                </div>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-3">
                <button type="button" @click="addBudgetModal = false"
                    class="px-6 py-3 rounded-xl text-sm font-bold text-gray-500 bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer">Batal</button>
                <button type="submit"
                    class="px-6 py-3 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-lg cursor-pointer">Simpan Item</button>
            </div>
        </form>
    </div>
</div>
