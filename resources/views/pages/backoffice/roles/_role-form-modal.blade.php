{{-- Role Form Modal (Tambah / Edit with Permission Matrix) --}}
<div x-show="modalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 !m-0" x-cloak>

    {{-- Backdrop --}}
    <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="modalOpen = false"></div>

    {{-- Modal Panel --}}
    <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[90vh]">

        {{-- Header --}}
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-extrabold text-lg text-gray-900"
                x-text="modalMode === 'edit' ? 'Edit Role: ' + formData.display_name : 'Tambah Role Baru'"></h3>
            <button @click="modalOpen = false"
                class="text-gray-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Body --}}
        <form
            :action="modalMode === 'edit'
                ?
                '{{ url('admin/roles') }}/' + editRoleId :
                '{{ route('admin.roles.store') }}'"
            method="POST" id="roleForm" class="flex flex-col flex-1 min-h-0">
            @csrf
            <template x-if="modalMode === 'edit'">
                <input type="hidden" name="_method" value="PUT">
            </template>

            <div class="overflow-y-auto custom-scrollbar flex-1 flex flex-col md:flex-row">

                {{-- Left: Role Details --}}
                <div class="w-full md:w-1/3 p-6 border-b md:border-b-0 md:border-r border-gray-100 bg-white">
                    <h4 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Informasi Dasar Role
                    </h4>

                    <div class="space-y-4">
                        {{-- Display Name --}}
                        <div class="space-y-1">
                            <label for="display_name" class="text-sm font-bold text-gray-700">
                                Nama Role <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="display_name" name="display_name" x-model="formData.display_name"
                                placeholder="Misal: Operator Pelayanan"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all"
                                required>
                            @error('display_name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Slug (only on create) --}}
                        <div class="space-y-1" x-show="modalMode === 'create'">
                            <label for="slug" class="text-sm font-bold text-gray-700">
                                Slug (ID Teknis) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="slug" name="slug" x-model="formData.slug"
                                placeholder="contoh: operator-pelayanan"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all font-mono text-xs">
                            <p class="text-[10px] text-gray-400">Huruf kecil, angka, dan tanda hubung saja.</p>
                            @error('slug')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="space-y-1">
                            <label for="description" class="text-sm font-bold text-gray-700">Deskripsi Singkat</label>
                            <textarea id="description" name="description" rows="3" x-model="formData.description"
                                placeholder="Jelaskan fungsionalitas role ini..."
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all"></textarea>
                        </div>

                        {{-- Icon --}}
                        <div class="space-y-1">
                            <label for="icon" class="text-sm font-bold text-gray-700">Ikon (Font Awesome)</label>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500">
                                    <i :class="formData.icon || 'fa-solid fa-shield-halved'"></i>
                                </div>
                                <input type="text" id="icon" name="icon" x-model="formData.icon"
                                    placeholder="fa-solid fa-shield-halved"
                                    class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all font-mono text-xs">
                            </div>
                        </div>

                        {{-- Color --}}
                        <div class="space-y-1">
                            <label class="text-sm font-bold text-gray-700">Warna Tema</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach (['blue', 'emerald', 'amber', 'purple', 'teal', 'pink', 'gray', 'red'] as $color)
                                    @php
                                        $colorMap = [
                                            'blue' => 'bg-blue-500',
                                            'emerald' => 'bg-emerald-500',
                                            'amber' => 'bg-amber-500',
                                            'purple' => 'bg-purple-500',
                                            'teal' => 'bg-teal-500',
                                            'pink' => 'bg-pink-500',
                                            'gray' => 'bg-gray-500',
                                            'red' => 'bg-red-500',
                                        ];
                                    @endphp
                                    <button type="button" @click="formData.color = '{{ $color }}'"
                                        :class="formData.color === '{{ $color }}' ?
                                            'ring-2 ring-offset-2 ring-gray-400 scale-110' : ''"
                                        class="w-7 h-7 rounded-full {{ $colorMap[$color] }} cursor-pointer transition-all hover:scale-110">
                                    </button>
                                @endforeach
                                <input type="hidden" name="color" :value="formData.color">
                            </div>
                        </div>

                        {{-- Warning --}}
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 flex gap-3 mt-4">
                            <i class="fa-solid fa-circle-info text-amber-500 mt-0.5"></i>
                            <p class="text-[10px] text-amber-700 leading-relaxed">
                                Berikan akses secara bijak. Akses 'Delete' disarankan hanya untuk tingkat
                                Manajerial/Admin.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Right: Permission Matrix --}}
                <div class="w-full md:w-2/3 bg-gray-50/50 flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-100 bg-white flex justify-between items-center shrink-0">
                        <h4 class="text-sm font-bold text-gray-800">Assign Permissions (Hak Akses)</h4>
                        <button type="button" @click="toggleAllPermissions()"
                            class="text-[10px] font-bold text-green-600 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition-colors border border-green-200 cursor-pointer">
                            Check All V/C/E/D
                        </button>
                    </div>

                    <div class="overflow-x-auto p-4 custom-scrollbar flex-1">
                        <table
                            class="w-full text-left border-collapse bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                            <thead>
                                <tr
                                    class="bg-gray-100 text-gray-600 text-xs uppercase tracking-wider border-b border-gray-200">
                                    <th class="p-3 font-bold w-1/2">Modul / Menu Sistem</th>
                                    <th class="p-3 font-bold text-center" title="Melihat Data">View</th>
                                    <th class="p-3 font-bold text-center" title="Menambah Data">Create</th>
                                    <th class="p-3 font-bold text-center" title="Mengubah Data">Edit</th>
                                    <th class="p-3 font-bold text-center" title="Menghapus Data">Delete</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100">
                                @foreach ($modules as $module)
                                    <tr
                                        class="hover:bg-gray-50 transition-colors {{ $module->is_sensitive ? 'bg-red-50/10' : '' }}">
                                        <td class="p-3 font-medium text-gray-800 flex items-center gap-2">
                                            @if ($module->is_sensitive)
                                                <i class="fa-solid fa-shield text-red-500"></i>
                                                <span class="text-red-800">{{ $module->name }}</span>
                                            @else
                                                {{ $module->name }}
                                            @endif
                                        </td>
                                        @foreach (['can_view', 'can_create', 'can_edit', 'can_delete'] as $ability)
                                            <td class="p-3 text-center">
                                                <input type="hidden"
                                                    name="permissions[{{ $module->id }}][{{ $ability }}]"
                                                    value="0">
                                                <input type="checkbox" class="matrix-checkbox mx-auto"
                                                    name="permissions[{{ $module->id }}][{{ $ability }}]"
                                                    value="1"
                                                    x-model="permissionData[{{ $module->id }}] && permissionData[{{ $module->id }}]['{{ $ability }}']"
                                                    :checked="permissionData[{{ $module->id }}] && permissionData[
                                                        {{ $module->id }}]['{{ $ability }}']"
                                                    @if ($module->is_sensitive && $ability !== 'can_view') {{-- Sensitive modules: only view is freely assignable, others need deliberate choice --}} @endif>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3 rounded-b-2xl shrink-0">
                <button @click="modalOpen = false" type="button"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-200 transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-md transition-all cursor-pointer">
                    <i class="fa-solid fa-save mr-2"></i>
                    <span x-text="modalMode === 'edit' ? 'Simpan Perubahan' : 'Simpan Role & Hak Akses'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
