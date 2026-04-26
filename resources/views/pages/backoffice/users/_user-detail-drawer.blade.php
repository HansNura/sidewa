{{-- User Detail Drawer (Slide in from right) --}}
<div x-show="drawerOpen" class="fixed inset-0 z-[100] flex justify-end !m-0" x-cloak>

    {{-- Backdrop --}}
    <div x-show="drawerOpen" x-transition.opacity
         class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm"
         @click="drawerOpen = false"></div>

    {{-- Drawer Panel --}}
    <div x-show="drawerOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="relative bg-white w-full max-w-md h-full shadow-2xl flex flex-col border-l border-gray-200">

        {{-- Drawer Header --}}
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start bg-gray-50/50 shrink-0">
            <div>
                <h3 class="font-extrabold text-lg text-gray-900">Detail Pengguna</h3>
                <p class="text-xs text-gray-500 mt-0.5">Informasi profil dan riwayat aktivitas sistem.</p>
            </div>
            <button @click="drawerOpen = false"
                class="text-gray-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 -mr-2 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Drawer Body --}}
        <div class="p-6 overflow-y-auto custom-scrollbar flex-1">

            {{-- Loading State --}}
            <template x-if="drawerLoading">
                <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                    <i class="fa-solid fa-spinner fa-spin text-3xl mb-3"></i>
                    <p class="text-sm font-medium">Memuat data...</p>
                </div>
            </template>

            {{-- Content (loaded) --}}
            <template x-if="!drawerLoading && drawerUser">
                <div>
                    {{-- Profile Card --}}
                    <div class="flex items-center gap-4 mb-8">
                        <div class="relative shrink-0">
                            <img :src="drawerUser.avatar_url"
                                 class="w-20 h-20 rounded-full shadow-md border-4 border-white"
                                 :alt="drawerUser.name">
                            <span class="absolute bottom-0 right-0 w-5 h-5 border-2 border-white rounded-full"
                                  :class="drawerUser.is_active ? 'bg-green-500' : 'bg-red-400'"
                                  :title="'Status: ' + drawerUser.status_label"></span>
                        </div>
                        <div>
                            <h4 class="text-xl font-extrabold text-gray-900 leading-none mb-1"
                                x-text="drawerUser.name"></h4>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wide"
                                  :class="drawerUser.role_badge"
                                  x-text="drawerUser.role_name"></span>
                        </div>
                    </div>

                    {{-- Info List --}}
                    <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Informasi Kontak</h5>
                    <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 space-y-4 mb-8">
                        <div class="flex items-start gap-3">
                            <i class="fa-regular fa-envelope text-gray-400 mt-0.5"></i>
                            <div>
                                <p class="text-[10px] text-gray-500 font-medium">Alamat Email</p>
                                <p class="text-sm font-semibold text-gray-900" x-text="drawerUser.email"></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fa-regular fa-id-card text-gray-400 mt-0.5"></i>
                            <div>
                                <p class="text-[10px] text-gray-500 font-medium">No. Identitas (NIK)</p>
                                <p class="text-sm font-semibold text-gray-900"
                                   x-text="drawerUser.nik || '—'"></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-clock-rotate-left text-gray-400 mt-0.5"></i>
                            <div>
                                <p class="text-[10px] text-gray-500 font-medium">Terakhir Login</p>
                                <p class="text-sm font-semibold text-gray-900">
                                    <span x-text="drawerUser.last_login_at || 'Belum pernah login'"></span>
                                    <template x-if="drawerUser.last_login_ip">
                                        <span class="text-xs text-gray-400 ml-1" x-text="'(IP: ' + drawerUser.last_login_ip + ')'"></span>
                                    </template>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fa-regular fa-calendar text-gray-400 mt-0.5"></i>
                            <div>
                                <p class="text-[10px] text-gray-500 font-medium">Terdaftar Sejak</p>
                                <p class="text-sm font-semibold text-gray-900" x-text="drawerUser.created_at"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Permissions --}}
                    <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Hak Akses (Permissions)</h5>
                    <div class="flex flex-wrap gap-2 mb-8">
                        <template x-for="perm in drawerUser.permissions" :key="perm">
                            <span class="bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded border border-blue-100"
                                  x-text="perm"></span>
                        </template>
                        <template x-if="drawerUser.permissions && drawerUser.permissions.length === 0">
                            <span class="text-xs text-gray-400 italic">Belum ada hak akses terdaftar.</span>
                        </template>
                    </div>

                    {{-- Activity History --}}
                    <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Riwayat Aktivitas</h5>
                    <template x-if="drawerUser.activity_logs && drawerUser.activity_logs.length > 0">
                        <div class="relative border-l-2 border-gray-200 ml-2 pl-4 py-1 space-y-5">
                            <template x-for="(log, index) in drawerUser.activity_logs" :key="index">
                                <div class="relative">
                                    <span class="absolute -left-[23px] top-1 w-2.5 h-2.5 rounded-full border-2 border-white"
                                          :class="index === 0 ? 'bg-green-500' : 'bg-gray-300'"></span>
                                    <p class="text-[10px] text-gray-400 font-medium mb-0.5" x-text="log.time"></p>
                                    <p class="text-sm font-semibold text-gray-800 leading-snug" x-text="log.description"></p>
                                </div>
                            </template>
                        </div>
                    </template>
                    <template x-if="!drawerUser.activity_logs || drawerUser.activity_logs.length === 0">
                        <p class="text-sm text-gray-400 italic">Belum ada riwayat aktivitas.</p>
                    </template>
                </div>
            </template>

        </div>

        {{-- Drawer Footer --}}
        <div class="p-6 border-t border-gray-100 bg-white shrink-0 flex gap-3">
            <button @click="drawerOpen = false; openEditModal({
                       id: drawerUser.id,
                       name: drawerUser.name,
                       email: drawerUser.email,
                       nik: drawerUser.nik,
                       role: drawerUser.role,
                       is_active: drawerUser.is_active
                   })"
                class="w-full flex justify-center items-center gap-2 bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-50 transition-colors cursor-pointer">
                <i class="fa-solid fa-pen text-xs"></i> Edit Profil
            </button>
        </div>
    </div>
</div>
