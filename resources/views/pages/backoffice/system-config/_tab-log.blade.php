{{-- Tab: Audit & System Log --}}
<section x-show="activeTab === 'log'" x-transition.opacity.duration.300ms x-cloak class="flex flex-col h-full">
    <div class="mb-4 flex flex-col md:flex-row md:items-end justify-between gap-4 shrink-0">
        <div>
            <h3 class="text-lg font-bold text-gray-900">Audit & System Log</h3>
            <p class="text-sm text-gray-500">Rekaman jejak seluruh aktivitas user dan perubahan sistem.</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="flex-1 overflow-x-auto border border-gray-200 rounded-xl rounded-b-none">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                    <th class="p-3 font-semibold">Waktu</th>
                    <th class="p-3 font-semibold">User / Aktor</th>
                    <th class="p-3 font-semibold">Aktivitas</th>
                    <th class="p-3 font-semibold">Deskripsi</th>
                    <th class="p-3 font-semibold text-center">IP</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                @forelse ($logs as $log)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-3 text-xs text-gray-500 font-mono">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="p-3">
                            <span class="font-medium text-gray-800">
                                {{ $log->user ? $log->user->name : 'Sistem' }}
                            </span>
                        </td>
                        <td class="p-3">
                            @php
                                $badgeColor = match(true) {
                                    str_contains($log->action, 'delete') => 'bg-red-100 text-red-700',
                                    str_contains($log->action, 'create') => 'bg-blue-100 text-blue-700',
                                    str_contains($log->action, 'update') => 'bg-amber-100 text-amber-700',
                                    str_contains($log->action, 'login')  => 'bg-green-100 text-green-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="{{ $badgeColor }} text-[10px] font-bold px-2 py-0.5 rounded uppercase">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="p-3 text-xs text-gray-700 max-w-xs truncate">{{ $log->description }}</td>
                        <td class="p-3 text-center text-xs text-gray-400 font-mono">{{ $log->ip_address ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-400">
                            <i class="fa-solid fa-inbox text-2xl mb-2 block"></i>
                            Belum ada log aktivitas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($logs->hasPages())
        <div class="bg-gray-50 p-3 border border-t-0 border-gray-200 rounded-b-xl flex justify-between items-center shrink-0">
            <span class="text-xs text-gray-500">
                Menampilkan {{ $logs->firstItem() }}-{{ $logs->lastItem() }} dari {{ $logs->total() }} logs
            </span>
            <div class="flex gap-1">
                @if ($logs->onFirstPage())
                    <span class="px-2 py-1 bg-white border border-gray-200 rounded text-gray-400 cursor-not-allowed text-xs">&laquo; Prev</span>
                @else
                    <a href="{{ $logs->previousPageUrl() }}&tab=log" class="px-2 py-1 bg-white border border-gray-200 rounded text-gray-700 hover:bg-gray-50 text-xs">&laquo; Prev</a>
                @endif

                @if ($logs->hasMorePages())
                    <a href="{{ $logs->nextPageUrl() }}&tab=log" class="px-2 py-1 bg-white border border-gray-200 rounded text-gray-700 hover:bg-gray-50 text-xs">Next &raquo;</a>
                @else
                    <span class="px-2 py-1 bg-white border border-gray-200 rounded text-gray-400 cursor-not-allowed text-xs">Next &raquo;</span>
                @endif
            </div>
        </div>
    @endif
</section>
