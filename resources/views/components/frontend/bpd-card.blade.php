@props(['anggota', 'index'])

<!-- Kartu Individu -->
<div class="relative p-6 transition-all duration-300 bg-white border border-gray-100 shadow-sm cursor-pointer rounded-2xl hover:shadow-xl hover:-translate-y-1"
    @click="selected = selected === {{ $index }} ? null : {{ $index }}">
    <div class="flex flex-col items-center text-center">
        <img src="{{ asset('assets/img/people.png') }}" alt="{{ $anggota['nama'] }}"
            class="w-20 h-20 object-cover rounded-full border-4 border-[#2E7D32]/60 mb-4" />

        <h4 class="text-lg font-semibold text-gray-800">{{ $anggota['nama'] }}</h4>
        <p class="text-sm text-[#2E7D32]">{{ $anggota['jabatan'] }}</p>

        <!-- Detail Info (toggle) -->
        <div x-show="selected === {{ $index }}" x-transition x-cloak
            class="pt-3 mt-4 text-sm text-gray-600 border-t border-gray-200">
            <p>
                <strong>Kontak:</strong>
                <span>{{ $anggota['kontak'] }}</span>
            </p>
            <p>
                <strong>Alamat:</strong>
                <span>{{ $anggota['alamat'] }}</span>
            </p>
        </div>
    </div>
</div>
