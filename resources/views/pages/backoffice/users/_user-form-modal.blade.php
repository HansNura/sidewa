{{-- User Form Modal (Tambah / Edit) --}}
<div x-show="modalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" x-cloak>

    {{-- Backdrop --}}
    <div x-show="modalOpen" x-transition.opacity
         class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
         @click="modalOpen = false"></div>

    {{-- Modal Panel --}}
    <div x-show="modalOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">

        {{-- Modal Header --}}
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-extrabold text-lg text-gray-900"
                x-text="modalMode === 'edit' ? 'Edit Akun User' : 'Tambah Akun User'"></h3>
            <button @click="modalOpen = false"
                class="text-gray-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Modal Body (Form) --}}
        <form :action="modalMode === 'edit'
                ? '{{ url('admin/users') }}/' + editUserData.id
                : '{{ route('admin.users.store') }}'"
              method="POST"
              id="userForm">
            @csrf
            <template x-if="modalMode === 'edit'">
                <input type="hidden" name="_method" value="PUT">
            </template>

            <div class="px-6 py-5 overflow-y-auto custom-scrollbar flex-1 space-y-5">

                {{-- Nama Lengkap --}}
                <div class="space-y-1">
                    <label for="name" class="text-sm font-bold text-gray-700">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                           x-model="formData.name"
                           placeholder="Masukkan nama..."
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all"
                           required>
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="space-y-1">
                    <label for="email" class="text-sm font-bold text-gray-700">
                        Email Utama <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email"
                           x-model="formData.email"
                           placeholder="email@contoh.com"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all"
                           required>
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NIK --}}
                <div class="space-y-1">
                    <label for="nik" class="text-sm font-bold text-gray-700">No. Identitas (NIK)</label>
                    <input type="text" id="nik" name="nik"
                           x-model="formData.nik"
                           placeholder="16 digit NIK"
                           maxlength="16"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                    @error('nik')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role & Status --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="space-y-1">
                        <label for="role" class="text-sm font-bold text-gray-700">
                            Role Sistem <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="role" name="role"
                                    x-model="formData.role"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm appearance-none focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none cursor-pointer"
                                    required>
                                <option value="">Pilih Role...</option>
                                @foreach ($roles as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                        @error('role')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="is_active" class="text-sm font-bold text-gray-700">Status Akun</label>
                        <div class="relative">
                            <select id="is_active" name="is_active"
                                    x-model="formData.is_active"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm appearance-none focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none cursor-pointer">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif / Suspend</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                {{-- Password --}}
                <div class="border-t border-gray-100 my-2 pt-4">
                    <div class="space-y-1" x-data="{ showPassword: false }">
                        <label for="password" class="text-sm font-bold text-gray-700">
                            Kata Sandi (Password)
                            <span class="text-red-500" x-show="modalMode === 'create'">*</span>
                        </label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'"
                                   id="password" name="password"
                                   placeholder="Minimal 8 karakter"
                                   :required="modalMode === 'create'"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all pr-10">
                            <button type="button" @click="showPassword = !showPassword"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer hover:text-gray-600">
                                <i :class="showPassword ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i>
                            </button>
                        </div>
                        <p class="text-[10px] text-gray-500 mt-1">
                            <template x-if="modalMode === 'edit'">
                                <span>Kosongkan jika tidak ingin mengubah password.</span>
                            </template>
                            <template x-if="modalMode === 'create'">
                                <span>Gunakan kombinasi huruf dan angka untuk keamanan.</span>
                            </template>
                        </p>
                        @error('password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3 rounded-b-2xl">
                <button @click="modalOpen = false" type="button"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-200 transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md transition-all cursor-pointer">
                    <span x-text="modalMode === 'edit' ? 'Simpan Perubahan' : 'Simpan User'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
