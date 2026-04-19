{{-- Role Selector Pill Tabs --}}
@props([
    'roles' => [
        ['key' => 'administrator', 'label' => 'Administrator', 'icon' => 'fa-solid fa-user-shield'],
        ['key' => 'operator',      'label' => 'Operator',      'icon' => 'fa-solid fa-user-gear'],
        ['key' => 'kades',         'label' => 'Kades',         'icon' => 'fa-solid fa-user-check'],
        ['key' => 'perangkat',     'label' => 'Perangkat',     'icon' => 'fa-solid fa-user-tie'],
        ['key' => 'resepsionis',   'label' => 'Resepsionis',   'icon' => 'fa-solid fa-user-clock'],
    ],
    'default' => 'administrator',
])

<div class="mb-8" x-data="{ activeRole: '{{ old('role', $default) }}' }">
    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3 text-center">
        Pilih Peran Akses
    </p>

    <div class="flex flex-wrap p-1 bg-gray-100 rounded-xl gap-1" role="tablist" aria-label="Pilih peran akses">
        @foreach ($roles as $role)
            <button
                type="button"
                role="tab"
                :aria-selected="activeRole === '{{ $role['key'] }}'"
                @click="activeRole = '{{ $role['key'] }}'"
                class="flex-1 min-w-[calc(33%-0.25rem)] py-2 px-2 rounded-lg text-xs sm:text-sm transition-all duration-200 cursor-pointer"
                :class="activeRole === '{{ $role['key'] }}'
                    ? 'bg-white shadow text-green-700 font-bold border border-gray-200'
                    : 'text-gray-500 font-medium hover:text-gray-700'"
            >
                <i class="{{ $role['icon'] }} mr-1"></i> {{ $role['label'] }}
            </button>
        @endforeach
    </div>

    {{-- Hidden input to submit the selected role --}}
    <input type="hidden" name="role" :value="activeRole" />
</div>
