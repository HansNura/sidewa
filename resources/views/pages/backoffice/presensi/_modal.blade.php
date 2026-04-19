{{-- Modal Koreksi Presensi Manual --}}
<div x-show="koreksiModalOpen" class="fixed inset-0 z-[150] flex items-center justify-center p-4 sm:p-6" x-cloak>
    <div x-show="koreksiModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="koreksiModalOpen = false"></div>

    <div x-show="koreksiModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">

        <form action="{{ route('admin.presensi.store-manual') }}" method="POST" x-data="{ stateStatus: 'hadir' }">
            @csrf
            <!-- Header Modal -->
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
                <h3 class="font-extrabold text-lg text-gray-900">Input / Koreksi Presensi Manual</h3>
                <button type="button" @click="koreksiModalOpen = false"
                    class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 transition-colors cursor-pointer"><i
                        class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <!-- Body Modal (Form) -->
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-5">
                <div class="bg-blue-50 border border-blue-100 p-3 rounded-xl text-[10px] text-blue-800 leading-relaxed mb-2">
                    <i class="fa-solid fa-circle-info mr-1"></i> Form ini digunakan apabila mesin Kiosk *error*, atau pegawai melakukan perjalanan dinas/izin sehingga tidak dapat melakukan presensi secara mandiri.
                </div>

                <!-- Pegawai Selector -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Nama Pegawai <span class="text-red-500">*</span></label>
                    <select name="user_id" id="koreksi_user_id" required
                        class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none font-semibold text-gray-800 cursor-pointer">
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach(App\Models\User::whereIn('role', ['kades', 'perangkat', 'operator', 'resepsionis'])->get() as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->jabatan ?? $u->roleName() }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date & Status -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700">Tanggal Presensi <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" x-model="today" required
                            class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none font-semibold text-gray-700 cursor-pointer">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700">Status Kehadiran <span class="text-red-500">*</span></label>
                        <select name="status" required x-model="stateStatus"
                            class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-green-500 outline-none font-semibold cursor-pointer"
                            :class="{
                                'text-green-600': stateStatus === 'hadir',
                                'text-amber-600': stateStatus === 'terlambat',
                                'text-blue-600': ['sakit','izin','dinas'].includes(stateStatus),
                                'text-red-600': stateStatus === 'alpha'
                            }">
                            <option value="hadir" class="text-green-600">Hadir (Manual)</option>
                            <option value="terlambat" class="text-amber-600">Terlambat</option>
                            <option value="sakit" class="text-blue-600">Sakit</option>
                            <option value="izin" class="text-blue-600">Izin / Cuti</option>
                            <option value="dinas" class="text-blue-600">Dinas Luar</option>
                            <option value="alpha" class="text-red-600">Alpha / Tanpa Keterangan</option>
                        </select>
                    </div>
                </div>

                <!-- Jam (Disabled jika Izin/Sakit/Alpha/Dinas) -->
                <div class="grid grid-cols-2 gap-4 border-t border-gray-100 pt-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700">Jam Masuk (In)</label>
                        <input type="time" name="waktu_masuk" value="08:00"
                            class="w-full border rounded-xl px-3 py-2 text-sm outline-none"
                            :class="['hadir', 'terlambat'].includes(stateStatus) ? 'bg-white border-gray-300 focus:ring-2 focus:ring-green-500 text-gray-800' : 'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed'"
                            x-bind:disabled="!['hadir', 'terlambat'].includes(stateStatus)">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700">Jam Pulang (Out)</label>
                        <input type="time" name="waktu_pulang" value="16:00"
                            class="w-full border rounded-xl px-3 py-2 text-sm outline-none"
                            :class="['hadir', 'terlambat'].includes(stateStatus) ? 'bg-white border-gray-300 focus:ring-2 focus:ring-green-500 text-gray-800' : 'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed'"
                            x-bind:disabled="!['hadir', 'terlambat'].includes(stateStatus)">
                    </div>
                </div>
                <p class="text-[10px] text-gray-500 -mt-2">Jam hanya diisi untuk status Hadir / Terlambat.</p>

                <!-- Reason Input -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Keterangan / Alasan <span class="text-red-500">*</span></label>
                    <textarea name="catatan" rows="3" required placeholder="Tuliskan keterangan (mis. Lupa absen, Sakit lampiran surat dokter, dsb)..."
                        class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-green-500 outline-none resize-none transition-all"></textarea>
                </div>

            </div>

            <!-- Footer Modal -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 shrink-0 flex justify-end gap-3">
                <button type="button" @click="koreksiModalOpen = false"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-white border border-gray-300 hover:bg-gray-100 transition-colors shadow-sm cursor-pointer">Batal</button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md transition-all flex items-center gap-2 cursor-pointer"><i
                        class="fa-solid fa-save"></i> Simpan Presensi</button>
            </div>
        </form>
    </div>
</div>
