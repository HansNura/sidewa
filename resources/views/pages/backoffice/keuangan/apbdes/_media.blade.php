<section x-show="activeTab === 'media'" x-transition.opacity class="max-w-4xl mx-auto space-y-6" x-cloak>
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 text-center border-b border-gray-100">
            <div class="w-16 h-16 bg-blue-50 text-blue-700 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl shadow-inner">
                <i class="fa-solid fa-images"></i>
            </div>
            <h3 class="text-xl font-extrabold text-gray-900">Publikasi Portal Web (TA {{ $tahun }})</h3>
            <p class="text-gray-500 max-w-md mx-auto mt-2 text-sm">Sesuaikan infografis rincian APBDes dan Dokumen resmi sebagai transparansi publik di Portal Desa.</p>
        </div>

        <form action="{{ route('admin.apbdes.store-poster') }}" method="POST" class="p-8 space-y-8">
            @csrf
            <input type="hidden" name="tahun" value="{{ $tahun }}">
            
            <!-- Baliho Infografis -->
            <div>
                <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fa-solid fa-panorama text-blue-600"></i> Gambar Baliho (Infografis APBDes)</h4>
                @if($poster && $poster->gambar_baliho_url)
                    <div class="mb-4 rounded-xl overflow-hidden border border-gray-200">
                        <img src="{{ $poster->gambar_baliho_url }}" alt="Baliho APBDes TA {{ $tahun }}" class="w-full h-auto object-cover max-h-64 shadow-sm">
                    </div>
                @else
                    <div class="mb-4 rounded-xl border border-gray-200 bg-gray-50 h-32 flex items-center justify-center text-gray-400">
                        Belum terpasang gambar Baliho.
                    </div>
                @endif
                <div class="space-y-1 mt-2">
                    <label class="text-xs font-bold text-gray-700">URL Gambar (JPG/PNG)</label>
                    <input type="url" name="gambar_baliho_url" value="{{ $poster->gambar_baliho_url ?? '' }}" placeholder="https://..." 
                        class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <p class="text-[10px] text-gray-500">Gunakan direct URL gambar public atau local path seperti `/images/baliho-2026.jpg`</p>
                </div>
            </div>

            <!-- Repository Dokumen -->
            <div class="pt-6 border-t border-gray-100">
                <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fa-solid fa-folder-open text-amber-500"></i> Repository Dokumen Resmi</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700">Perdes APBDes (URL Dokumen)</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm"><i class="fa-solid fa-file-pdf text-red-500"></i></span>
                            <input type="url" name="perdes_dokumen_url" value="{{ $poster->perdes_dokumen_url ?? '' }}" placeholder="https://..." class="flex-1 min-w-0 block w-full px-3 py-2.5 rounded-none rounded-r-xl text-sm border border-gray-300 focus:ring-blue-500 outline-none bg-white">
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700">RAB Rincian Pembangunan (URL Dokumen)</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm"><i class="fa-solid fa-file-excel text-green-600"></i></span>
                            <input type="url" name="rab_dokumen_url" value="{{ $poster->rab_dokumen_url ?? '' }}" placeholder="https://..." class="flex-1 min-w-0 block w-full px-3 py-2.5 rounded-none rounded-r-xl text-sm border border-gray-300 focus:ring-blue-500 outline-none bg-white">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action -->
            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white shadow-md rounded-xl px-8 py-3 text-sm font-bold transition-all flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Publikasi & Dokumen
                </button>
            </div>
        </form>
    </div>
</section>
