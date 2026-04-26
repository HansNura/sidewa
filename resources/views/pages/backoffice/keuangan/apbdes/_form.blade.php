<section x-show="activeTab === 'kelola'" x-transition.opacity class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-cloak>
    <!-- Kiri: Form Input -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="font-bold text-gray-800 mb-6 pb-2 border-b border-gray-100">Alokasi Anggaran Manual</h4>
            <form action="{{ route('admin.apbdes.store') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="tahun" value="{{ $tahun }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700">Kode Rekening <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="kode_rekening" placeholder="Contoh: 1.1.01" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none transition-all font-mono">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700">Tipe Anggaran</label>
                        <select name="tipe_anggaran" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                            <option value="PENDAPATAN">Pendapatan</option>
                            <option value="BELANJA" selected>Belanja</option>
                            <option value="PEMBIAYAAN">Pembiayaan</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Nama Kegiatan / Mata Anggaran <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="nama_kegiatan" placeholder="Masukkan deskripsi anggaran..." required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none transition-all">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700">Pagu Anggaran (Nominal)</label>
                        <div class="relative">
                            <span
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">Rp</span>
                            <input type="number" name="pagu_anggaran" placeholder="0" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-12 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none transition-all font-bold">
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700">Sumber Dana</label>
                        <select name="sumber_dana"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
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
                <!-- Checkbox Publikasi -->
                <label
                    class="flex items-center gap-3 bg-green-50/50 border border-green-100 p-3 rounded-xl cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" checked
                        class="w-4 h-4 text-green-600 rounded">
                    <div>
                        <p class="text-sm font-bold text-green-800">Tampilkan di Publik</p>
                        <p class="text-[10px] text-green-700/80">Anggaran akan muncul pada diagram transparansi portal
                            desa.</p>
                    </div>
                </label>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="submit"
                        class="px-8 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md">
                        Simpan Anggaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Kanan: Validation Rules -->
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><i
                    class="fa-solid fa-shield-check text-green-600"></i> Aturan Validasi</h4>
            <ul class="space-y-3">
                <li class="flex items-start gap-3">
                    <i class="fa-solid fa-circle-check text-green-500 mt-1 text-xs"></i>
                    <p class="text-xs text-gray-600">Kode rekening harus unik per Tahun (cth: 1.1.01 atau 2.1).</p>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fa-solid fa-circle-check text-green-500 mt-1 text-xs"></i>
                    <p class="text-xs text-gray-600">Pendapatan dan Belanja idealnya _balance_ selaras pada tahun
                        berjalan.</p>
                </li>
            </ul>
        </div>

        <!-- Live Draft Summary based on year -->
        <div class="bg-gray-900 rounded-2xl p-5 text-white shadow-xl">
            <h4 class="text-xs font-bold text-green-400 uppercase tracking-widest mb-4">Ringkasan TA {{ $tahun }}
            </h4>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Total Psn.</span>
                    <span class="font-bold" x-text="formatIDR({{ $summary['pendapatan'] }})">Rp
                        {{ number_format($summary['pendapatan']) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Total Bln.</span>
                    <span class="font-bold" x-text="formatIDR({{ $summary['belanja'] }})">Rp
                        {{ number_format($summary['belanja']) }}</span>
                </div>
                <hr class="border-gray-800">
                <div class="flex justify-between text-base">
                    <span class="text-gray-400">Stat</span>
                    <span class="font-bold {{ $summary['surplus'] >= 0 ? 'text-green-400' : 'text-red-400' }}">
                        {{ $summary['surplus'] >= 0 ? 'Surplus' : 'Defisit' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>
