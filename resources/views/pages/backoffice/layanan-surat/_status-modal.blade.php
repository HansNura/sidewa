{{-- Status Update Modal --}}
<div x-show="statusModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4" x-cloak>
    <div x-show="statusModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
         @click="statusModalOpen = false"></div>

    <div x-show="statusModalOpen" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col">

        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h3 class="font-extrabold text-lg text-gray-900">Update Status Surat</h3>
                <p class="text-xs text-gray-500 font-mono" x-text="statusTarget?.tiket"></p>
            </div>
            <button @click="statusModalOpen = false"
                class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form method="POST"
              :action="`{{ url('admin/layanan-surat') }}/${statusTarget?.id}/status`"
              class="p-6 space-y-5">
            @csrf
            @method('PATCH')

            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-700">Ubah Status Ke <span class="text-red-500">*</span></label>
                <select name="status" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none font-semibold cursor-pointer">
                    <option value="pengajuan">Pengajuan Baru</option>
                    <option value="verifikasi">Verifikasi Operator</option>
                    <option value="menunggu_tte">Menunggu TTE Kades</option>
                    <option value="selesai">Selesai</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-700">Alasan Penolakan <span class="text-gray-400 font-normal">(Jika ditolak)</span></label>
                <textarea name="alasan_tolak" rows="2" placeholder="Wajib diisi jika status Ditolak..."
                          class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none resize-none"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" @click="statusModalOpen = false"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 border border-gray-200 hover:bg-gray-100 cursor-pointer">Batal</button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md cursor-pointer">
                    <i class="fa-solid fa-check mr-2"></i> Perbarui Status
                </button>
            </div>
        </form>
    </div>
</div>
