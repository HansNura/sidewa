{{-- User Table --}}
<section class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="p-4 w-12 text-center">
                        <input type="checkbox" class="custom-checkbox inline-block"
                               x-model="selectAll"
                               @change="selectedUsers = selectAll ? {{ $users->pluck('id')->toJson() }} : []">
                    </th>
                    <th class="p-4 font-semibold">Informasi Pengguna</th>
                    <th class="p-4 font-semibold">Role Sistem</th>
                    <th class="p-4 font-semibold">Status</th>
                    <th class="p-4 font-semibold">Login Terakhir</th>
                    <th class="p-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors {{ !$user->is_active ? 'opacity-75' : '' }}"
                        :class="selectedUsers.includes({{ $user->id }}) ? 'bg-green-50/30' : ''">

                        {{-- Checkbox --}}
                        <td class="p-4 text-center">
                            <input type="checkbox"
                                   class="custom-checkbox inline-block"
                                   value="{{ $user->id }}"
                                   x-model.number="selectedUsers">
                        </td>

                        {{-- User Info --}}
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                @if ($user->is_active)
                                    <img src="{{ $user->avatarUrl() }}"
                                         class="w-9 h-9 rounded-full shadow-sm" alt="{{ $user->name }}">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center shadow-sm">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-gray-900 leading-tight {{ !$user->is_active ? 'line-through' : '' }}">
                                        {{ $user->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Role Badge --}}
                        <td class="p-4">
                            <span class="{{ $user->roleBadgeClasses() }} text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wide">
                                {{ $user->roleName() }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="p-4">
                            @if ($user->is_active)
                                <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-green-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-red-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Nonaktif
                                </span>
                            @endif
                        </td>

                        {{-- Last Login --}}
                        <td class="p-4 text-gray-500 text-xs">
                            @if ($user->last_login_at)
                                {{ $user->last_login_at->diffForHumans() }}
                            @else
                                <span class="text-gray-400 italic">Belum pernah login</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                {{-- View Detail --}}
                                <button @click="openDrawer({{ $user->id }})"
                                    class="w-8 h-8 rounded-lg text-gray-400 hover:text-green-600 hover:bg-green-50 flex items-center justify-center transition-colors cursor-pointer"
                                    title="Detail Info">
                                    <i class="fa-solid fa-eye"></i>
                                </button>

                                {{-- Edit --}}
                                <button @click="openEditModal({{ json_encode([
                                    'id'        => $user->id,
                                    'name'      => $user->name,
                                    'email'     => $user->email,
                                    'nik'       => $user->nik,
                                    'role'      => $user->role,
                                    'is_active' => $user->is_active,
                                ]) }})"
                                    class="w-8 h-8 rounded-lg text-gray-400 hover:text-amber-500 hover:bg-amber-50 flex items-center justify-center transition-colors cursor-pointer"
                                    title="Edit User">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                {{-- Reset Password --}}
                                <form action="{{ route('admin.users.reset-password', $user) }}" method="POST"
                                      onsubmit="return confirm('Reset password user {{ $user->name }}?')">
                                    @csrf
                                    <button type="submit"
                                        class="w-8 h-8 rounded-lg text-gray-400 hover:text-blue-500 hover:bg-blue-50 flex items-center justify-center transition-colors cursor-pointer"
                                        title="Reset Password">
                                        <i class="fa-solid fa-key"></i>
                                    </button>
                                </form>

                                {{-- Delete --}}
                                @if ($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                          onsubmit="return confirm('Yakin hapus user {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-8 h-8 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition-colors cursor-pointer"
                                            title="Hapus User">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400">
                            <i class="fa-solid fa-users-slash text-3xl mb-3 block"></i>
                            <p class="font-semibold">Tidak ada user ditemukan.</p>
                            <p class="text-xs mt-1">Coba ubah filter atau kata kunci pencarian Anda.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Footer --}}
    <div class="p-4 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-sm text-gray-500 font-medium">
            Menampilkan
            <span class="font-bold text-gray-900">{{ $users->firstItem() ?? 0 }}</span>
            sampai
            <span class="font-bold text-gray-900">{{ $users->lastItem() ?? 0 }}</span>
            dari
            <span class="font-bold text-gray-900">{{ $users->total() }}</span> user
        </p>

        {{-- Pagination Links --}}
        <div class="flex items-center gap-1">
            {{-- Previous --}}
            @if ($users->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 bg-white opacity-50 cursor-not-allowed">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </span>
            @else
                <a href="{{ $users->previousPageUrl() }}"
                   class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 bg-white hover:bg-gray-50 transition-colors">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
                @if ($page == $users->currentPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-green-600 bg-green-50 text-green-700 font-bold text-sm">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                       class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 font-semibold text-sm transition-colors">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next --}}
            @if ($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}"
                   class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 bg-white hover:bg-gray-50 transition-colors">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>
            @else
                <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 bg-white opacity-50 cursor-not-allowed">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </span>
            @endif
        </div>
    </div>
</section>
