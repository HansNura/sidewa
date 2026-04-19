{{-- Add/Edit Penduduk Modal --}}
<div x-show="addModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="addModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
         @click="addModalOpen = false"></div>

    <div x-show="addModalOpen" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="relative bg-white rounded-2xl shadow-2xl w-full max-w-5xl overflow-hidden flex flex-col h-full max-h-[90vh]">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
            <h3 class="font-extrabold text-lg text-gray-900">Tambah Data Penduduk Baru</h3>
            <button @click="addModalOpen = false"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="px-6 py-5 overflow-y-auto custom-scrollbar flex-1 bg-gray-50/30">
            <form id="pendudukForm" method="POST" action="{{ route('admin.penduduk.store') }}" class="space-y-8">
                @csrf
                {{-- Identitas Utama --}}
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <h4 class="font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 text-sm">
                        <i class="fa-regular fa-id-card mr-2 text-green-600"></i>Identitas Utama
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">NIK <span class="text-red-500">*</span></label>
                            <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none font-mono" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama') }}"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none uppercase" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Tempat Lahir <span class="text-red-500">*</span></label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="jenis_kelamin" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer" required>
                                <option value="">Pilih...</option>
                                <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Agama <span class="text-red-500">*</span></label>
                            <select name="agama" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer" required>
                                @foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $a)
                                    <option value="{{ $a }}" {{ old('agama', 'Islam') === $a ? 'selected' : '' }}>{{ $a }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Golongan Darah</label>
                            <select name="golongan_darah" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                                <option value="">-</option>
                                @foreach (['A', 'B', 'AB', 'O'] as $gd)
                                    <option value="{{ $gd }}" {{ old('golongan_darah') === $gd ? 'selected' : '' }}>{{ $gd }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Data Keluarga --}}
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <h4 class="font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 text-sm">
                        <i class="fa-solid fa-users mr-2 text-green-600"></i>Data Keluarga
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1 md:col-span-2">
                            <label class="text-xs font-bold text-gray-700">No. Kartu Keluarga (KK) <span class="text-red-500">*</span></label>
                            <input type="text" name="no_kk" value="{{ old('no_kk') }}" maxlength="16"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none font-mono" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Status Hubungan dlm Keluarga <span class="text-red-500">*</span></label>
                            <select name="status_hubungan" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer" required>
                                @foreach (['Kepala Keluarga', 'Istri', 'Anak', 'Famili Lain'] as $sh)
                                    <option value="{{ $sh }}" {{ old('status_hubungan') === $sh ? 'selected' : '' }}>{{ $sh }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Status Perkawinan <span class="text-red-500">*</span></label>
                            <select name="status_perkawinan" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer" required>
                                @foreach (['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'] as $sp)
                                    <option value="{{ $sp }}" {{ old('status_perkawinan') === $sp ? 'selected' : '' }}>{{ $sp }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Nama Ayah</label>
                            <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Nama Ibu</label>
                            <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                        </div>
                    </div>
                </div>

                {{-- Pendidikan, Pekerjaan, Alamat --}}
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <h4 class="font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 text-sm">
                        <i class="fa-solid fa-graduation-cap mr-2 text-green-600"></i>Pendidikan, Pekerjaan & Alamat
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Pendidikan Terakhir</label>
                            <select name="pendidikan" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                                <option value="">-</option>
                                @foreach (['Tidak/Belum Sekolah', 'SD/Sederajat', 'SMP/Sederajat', 'SMA/Sederajat', 'D1-D3', 'S1/D4', 'S2/S3'] as $pend)
                                    <option value="{{ $pend }}" {{ old('pendidikan') === $pend ? 'selected' : '' }}>{{ $pend }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Pekerjaan</label>
                            <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none"
                                   placeholder="Contoh: Wiraswasta">
                        </div>
                        <div class="space-y-1 md:col-span-2">
                            <label class="text-xs font-bold text-gray-700">Alamat</label>
                            <input type="text" name="alamat" value="{{ old('alamat') }}"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none"
                                   placeholder="Jl. / Kp. / Gang ...">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-700">Dusun</label>
                            <input type="text" name="dusun" value="{{ old('dusun') }}"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none"
                                   placeholder="Dusun Kaler">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-700">RT</label>
                                <input type="text" name="rt" value="{{ old('rt') }}" maxlength="3"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none text-center"
                                       placeholder="01">
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-700">RW</label>
                                <input type="text" name="rw" value="{{ old('rw') }}" maxlength="3"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none text-center"
                                       placeholder="02">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-white shrink-0 flex justify-end gap-3">
            <button type="button" @click="addModalOpen = false"
                class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 border border-gray-200 hover:bg-gray-50 transition-colors cursor-pointer">Batal</button>
            <button type="submit" form="pendudukForm"
                class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md transition-all cursor-pointer">
                <i class="fa-solid fa-save mr-2"></i> Simpan Data
            </button>
        </div>
    </div>
</div>
