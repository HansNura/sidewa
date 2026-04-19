{{-- Template List Table --}}
<section class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50/80 text-gray-500 text-[11px] uppercase tracking-wider border-b border-gray-200">
                    <th class="p-4 font-semibold">Nama Template Surat</th>
                    <th class="p-4 font-semibold text-center">Kategori</th>
                    <th class="p-4 font-semibold text-center">Field Dinamis</th>
                    <th class="p-4 font-semibold text-center">Terakhir Diperbarui</th>
                    <th class="p-4 font-semibold text-center">Status</th>
                    <th class="p-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                @forelse ($templates as $tpl)
                    @php
                        $katColor = $tpl->kategoriColor();
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors {{ !$tpl->is_active ? 'opacity-60' : '' }}">
                        {{-- Nama --}}
                        <td class="p-4">
                            <div class="font-bold text-gray-900 text-[13px]">{{ $tpl->nama }}</div>
                            <div class="text-[11px] text-gray-500 mt-0.5 max-w-xs truncate">{{ $tpl->deskripsi ?: 'Tidak ada deskripsi.' }}</div>
                        </td>

                        {{-- Kategori --}}
                        <td class="p-4 text-center">
                            <span class="{{ $katColor['bg'] }} {{ $katColor['text'] }} text-[10px] font-bold px-2 py-0.5 rounded border {{ $katColor['border'] }}">
                                {{ $tpl->kategoriLabel() }}
                            </span>
                        </td>

                        {{-- Field Count --}}
                        <td class="p-4 text-center">
                            <span class="text-xs font-semibold text-gray-600 border border-gray-200 bg-white px-2 py-1 rounded-lg">
                                {{ $tpl->fieldCount() }} Field
                            </span>
                        </td>

                        {{-- Updated --}}
                        <td class="p-4 text-center">
                            <span class="text-xs font-semibold text-gray-700">{{ $tpl->updated_at->translatedFormat('d M Y') }}</span><br>
                            <span class="text-[10px] text-gray-400">{{ $tpl->versi }} oleh {{ $tpl->editor?->name ?? 'Sistem' }}</span>
                        </td>

                        {{-- Status Toggle --}}
                        <td class="p-4 text-center">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox"
                                       class="sr-only peer"
                                       {{ $tpl->is_active ? 'checked' : '' }}
                                       @change="toggleStatus({{ $tpl->id }}, $el)">
                                <div class="relative w-10 h-6 bg-gray-300 peer-checked:bg-green-600 rounded-full transition-colors after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:w-4 after:h-4 after:rounded-full after:transition-transform peer-checked:after:translate-x-4"></div>
                            </label>
                        </td>

                        {{-- Actions --}}
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="openPreview({{ $tpl->id }})"
                                    class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:text-green-600 hover:bg-green-50 flex items-center justify-center transition-colors border border-gray-200 cursor-pointer"
                                    title="Preview">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </button>
                                <a href="{{ route('admin.template-surat.edit', $tpl) }}"
                                    class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:text-amber-600 hover:bg-amber-50 flex items-center justify-center transition-colors border border-gray-200"
                                    title="Buka Editor">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                                <form action="{{ route('admin.template-surat.destroy', $tpl) }}" method="POST"
                                      onsubmit="return confirm('Hapus template {{ $tpl->nama }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:text-red-600 hover:bg-red-50 flex items-center justify-center transition-colors border border-gray-200 cursor-pointer"
                                        title="Hapus">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <i class="fa-regular fa-file-lines text-4xl text-gray-200"></i>
                                <p class="text-sm text-gray-500 font-medium">Belum ada template surat.</p>
                                <a href="{{ route('admin.template-surat.create') }}"
                                   class="text-sm font-bold text-green-600 hover:text-green-700">
                                    <i class="fa-solid fa-plus mr-1"></i> Buat Template Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($templates->hasPages())
        <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">
            {{ $templates->links() }}
        </div>
    @endif
</section>
