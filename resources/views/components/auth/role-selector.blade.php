{{-- Role Selector Pill Tabs --}}
@props([
    'roles' => [
        ['key' => 'admin', 'label' => 'Admin', 'icon' => 'fa-solid fa-user-shield'],
        ['key' => 'staff', 'label' => 'Staf', 'icon' => 'fa-solid fa-user-tie'],
        ['key' => 'kades', 'label' => 'Kades', 'icon' => 'fa-solid fa-user-check'],
    ],
    'default' => 'admin',
])

<div class="mb-8" x-data="{ activeRole: '{{ $default }}' }">
    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3 text-center">
        Pilih Peran Akses
    </p>

    <div class="flex p-1 bg-gray-100 rounded-xl" role="tablist" aria-label="Pilih peran akses">
        @foreach ($roles as $role)
            <button
                type="button"
                role="tab"
                :aria-selected="activeRole === '{{ $role['key'] }}'"
                @click="activeRole = '{{ $role['key'] }}'"
                class="flex-1 py-2 px-3 rounded-lg text-sm transition-all duration-200 cursor-pointer"
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
