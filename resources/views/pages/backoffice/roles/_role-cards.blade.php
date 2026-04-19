{{-- Role Cards Grid --}}
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    @foreach ($roles as $role)
        <article class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-md transition-shadow group relative overflow-hidden">

            {{-- System / Default Badge --}}
            @if ($role->is_system)
                <div class="absolute top-0 right-0 bg-blue-100 text-blue-700 text-[9px] font-bold px-3 py-1 rounded-bl-xl uppercase">
                    System
                </div>
            @endif

            {{-- Role Info --}}
            <div class="mb-4">
                <div class="w-12 h-12 {{ $role->iconBgClass() }} {{ $role->iconTextClass() }} rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i class="{{ $role->icon }} text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 leading-tight">{{ $role->display_name }}</h3>
                <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $role->description ?? 'Tidak ada deskripsi.' }}</p>
            </div>

            {{-- Footer: User Count + Actions --}}
            <div class="flex justify-between items-end border-t border-gray-50 pt-4 mt-auto">
                <div>
                    <span class="text-xs font-semibold text-gray-400 block mb-0.5">Total User</span>
                    <span class="text-base font-extrabold text-gray-800">{{ $role->userCount() }} Akun</span>
                </div>
                <div class="flex gap-2">
                    {{-- Edit --}}
                    <button @click="openEditModal({{ $role->id }})"
                        class="w-8 h-8 rounded-lg text-gray-400 hover:text-amber-500 hover:bg-amber-50 flex items-center justify-center transition-colors shadow-sm border border-gray-100 cursor-pointer"
                        title="Edit Role">
                        <i class="fa-solid fa-pen text-xs"></i>
                    </button>

                    {{-- Delete --}}
                    @if ($role->is_system)
                        <button class="w-8 h-8 rounded-lg text-gray-300 cursor-not-allowed flex items-center justify-center border border-gray-100"
                                title="System Role tidak dapat dihapus" disabled>
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    @elseif ($role->userCount() > 0)
                        <button class="w-8 h-8 rounded-lg text-gray-300 cursor-not-allowed flex items-center justify-center border border-gray-100"
                                title="Role masih memiliki user aktif" disabled>
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    @else
                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST"
                              onsubmit="return confirm('Yakin hapus role {{ $role->display_name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-8 h-8 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition-colors shadow-sm border border-gray-100 cursor-pointer"
                                title="Hapus Role">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </article>
    @endforeach
</section>
