{{-- Permission Matrix Table --}}
<section class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">

    {{-- Header --}}
    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="font-bold text-gray-800">Matrix Hak Akses Global</h3>
            <p class="text-xs text-gray-500 mt-0.5">
                Ringkasan akses
                (<span class="font-bold text-gray-700">V</span>iew,
                 <span class="font-bold text-gray-700">C</span>reate,
                 <span class="font-bold text-gray-700">E</span>dit,
                 <span class="font-bold text-gray-700">D</span>elete)
                per modul fitur utama.
            </p>
        </div>

        {{-- Legend --}}
        <div class="flex gap-3 shrink-0">
            <span class="flex items-center gap-1.5 text-[10px] font-semibold text-gray-500">
                <div class="w-2.5 h-2.5 rounded bg-green-500"></div> Full Access
            </span>
            <span class="flex items-center gap-1.5 text-[10px] font-semibold text-gray-500">
                <div class="w-2.5 h-2.5 rounded bg-amber-400"></div> Partial Access
            </span>
            <span class="flex items-center gap-1.5 text-[10px] font-semibold text-gray-500">
                <div class="w-2.5 h-2.5 rounded border border-gray-300 bg-gray-50"></div> No Access
            </span>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-100 text-gray-600 text-[10px] uppercase tracking-wider border-b border-gray-200">
                    <th class="px-5 py-4 font-bold border-r border-gray-200 w-1/4">Modul / Fitur Sistem</th>
                    @foreach ($roles as $role)
                        <th class="px-5 py-4 font-bold text-center {{ !$loop->last ? 'border-r border-gray-200' : '' }}">
                            {{ $role->display_name }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                @foreach ($modules as $module)
                    <tr class="hover:bg-gray-50 transition-colors {{ $module->is_sensitive ? 'bg-red-50/20' : '' }}">
                        {{-- Module Name --}}
                        <td class="px-5 py-4 font-semibold text-gray-800 border-r border-gray-100 flex items-center gap-2">
                            @if ($module->is_sensitive)
                                <i class="{{ $module->icon }} text-red-400 w-4"></i>
                                <span class="text-red-800">{{ $module->name }}</span>
                            @else
                                <i class="{{ $module->icon }} text-gray-400 w-4"></i>
                                {{ $module->name }}
                            @endif
                        </td>

                        {{-- Permission badges per role --}}
                        @foreach ($roles as $role)
                            @php
                                $perm = $matrix[$role->id][$module->id] ?? null;
                                $hasView   = $perm && $perm->can_view;
                                $hasCreate = $perm && $perm->can_create;
                                $hasEdit   = $perm && $perm->can_edit;
                                $hasDelete = $perm && $perm->can_delete;
                                $hasAny    = $hasView || $hasCreate || $hasEdit || $hasDelete;
                            @endphp

                            <td class="px-5 py-4 text-center {{ !$loop->last ? 'border-r border-gray-100' : '' }}">
                                @if (!$hasAny)
                                    <span class="text-gray-400 font-medium text-xs">No Access</span>
                                @else
                                    <div class="flex justify-center gap-1">
                                        @foreach (['V' => $hasView, 'C' => $hasCreate, 'E' => $hasEdit, 'D' => $hasDelete] as $label => $active)
                                            @if ($active)
                                                @php
                                                    // Full access = green, partial = amber, sensitive module = red
                                                    $allFour = $hasView && $hasCreate && $hasEdit && $hasDelete;
                                                    if ($module->is_sensitive) {
                                                        $badgeBg   = 'bg-red-100';
                                                        $badgeText = 'text-red-700';
                                                    } elseif ($allFour) {
                                                        $badgeBg   = 'bg-green-100';
                                                        $badgeText = 'text-green-700';
                                                    } else {
                                                        $badgeBg   = 'bg-amber-100';
                                                        $badgeText = 'text-amber-700';
                                                    }
                                                @endphp
                                                <span class="w-5 h-5 flex items-center justify-center {{ $badgeBg }} {{ $badgeText }} rounded text-[10px] font-bold">{{ $label }}</span>
                                            @else
                                                <span class="w-5 h-5 flex items-center justify-center bg-gray-100 text-gray-400 rounded text-[10px] font-bold">-</span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
