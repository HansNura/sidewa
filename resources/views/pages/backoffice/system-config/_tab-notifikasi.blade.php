{{-- Tab: Pengaturan Notifikasi --}}
<section x-show="activeTab === 'notifikasi'" x-transition.opacity.duration.300ms x-cloak>
    <div class="mb-6 border-b border-gray-100 pb-4">
        <h3 class="text-lg font-bold text-gray-900">Pengaturan Notifikasi</h3>
        <p class="text-sm text-gray-500">Pilih saluran komunikasi untuk memberikan alert kepada warga maupun admin.</p>
    </div>

    <div class="space-y-4 max-w-3xl">

        {{-- In-App Notification --}}
        <div class="flex items-start gap-4 p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition-colors">
            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-bell"></i>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-bold text-gray-800">In-App Notification (Notifikasi Internal)</h4>
                <p class="text-xs text-gray-500 mt-0.5">Notifikasi <em>real-time</em> yang muncul di ikon lonceng pada dashboard.</p>
            </div>
            <label class="flex items-center cursor-pointer shrink-0">
                <input type="hidden" name="notif_internal" value="0">
                <input type="checkbox" name="notif_internal" value="1" x-model="config.notifInternal" class="sr-only">
                <div class="relative">
                    <div class="block w-10 h-6 rounded-full transition-colors"
                         :class="config.notifInternal ? 'bg-green-500' : 'bg-gray-300'"></div>
                    <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform"
                         :class="config.notifInternal ? 'transform translate-x-4' : ''"></div>
                </div>
            </label>
        </div>

        {{-- Email Notification --}}
        <div class="flex items-start gap-4 p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition-colors">
            <div class="w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-envelope"></i>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-bold text-gray-800">Email Notification (SMTP)</h4>
                <p class="text-xs text-gray-500 mt-0.5">Kirim email ke warga saat surat selesai atau pendaftaran akun berhasil.</p>
                <div x-show="config.notifEmail" x-collapse class="mt-4 pt-4 border-t border-gray-200">
                    <button type="button"
                        class="text-xs font-semibold bg-white border border-gray-300 text-gray-700 px-3 py-1.5 rounded-lg hover:bg-gray-50 cursor-pointer">
                        Atur Kredensial SMTP
                    </button>
                </div>
            </div>
            <label class="flex items-center cursor-pointer shrink-0">
                <input type="hidden" name="notif_email" value="0">
                <input type="checkbox" name="notif_email" value="1" x-model="config.notifEmail" class="sr-only">
                <div class="relative">
                    <div class="block w-10 h-6 rounded-full transition-colors"
                         :class="config.notifEmail ? 'bg-green-500' : 'bg-gray-300'"></div>
                    <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform"
                         :class="config.notifEmail ? 'transform translate-x-4' : ''"></div>
                </div>
            </label>
        </div>

        {{-- WhatsApp Gateway --}}
        <div class="flex items-start gap-4 p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition-colors">
            <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                <i class="fa-brands fa-whatsapp text-lg"></i>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-bold text-gray-800">WhatsApp Gateway (Bot)</h4>
                <p class="text-xs text-gray-500 mt-0.5">Kirim notifikasi otomatis via WhatsApp kepada warga dan kepala desa.</p>
                <div x-show="config.notifWhatsapp" x-collapse class="mt-4 pt-4 border-t border-gray-200 grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Provider API</label>
                        <select name="wa_provider" x-model="config.waProvider"
                                class="w-full text-sm border-b border-gray-200 py-1 bg-transparent outline-none">
                            <option value="fonnte">Fonnte</option>
                            <option value="wablas">Wablas</option>
                            <option value="twilio">Twilio</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase">Status</label>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>
                            <span class="text-xs font-semibold text-gray-500">Belum Terhubung</span>
                        </div>
                    </div>
                </div>
            </div>
            <label class="flex items-center cursor-pointer shrink-0">
                <input type="hidden" name="notif_whatsapp" value="0">
                <input type="checkbox" name="notif_whatsapp" value="1" x-model="config.notifWhatsapp" class="sr-only">
                <div class="relative">
                    <div class="block w-10 h-6 rounded-full transition-colors"
                         :class="config.notifWhatsapp ? 'bg-green-500' : 'bg-gray-300'"></div>
                    <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform"
                         :class="config.notifWhatsapp ? 'transform translate-x-4' : ''"></div>
                </div>
            </label>
        </div>

    </div>
</section>
