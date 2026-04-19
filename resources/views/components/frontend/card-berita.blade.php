@props(['item'])

<div class="flex flex-col overflow-hidden transition bg-white shadow-md group rounded-2xl hover:shadow-lg">
    <div class="relative">
        <a href="{{ url('berita/' . $item['id']) }}">
            <img src="{{ asset($item['imgSrc']) }}" alt="{{ $item['judul'] }}"
                class="object-cover w-full transition duration-500 aspect-video group-hover:scale-105" />
        </a>
        <div class="absolute top-0 left-0 bg-[#2E7D32]/80 text-white text-sm px-3 py-1 rounded-br-lg">
            {{ $item['tanggal'] }}
        </div>
    </div>
    <div class="flex flex-col flex-grow p-6">
        <h4 class="font-semibold text-xl text-gray-800 mb-3 group-hover:text-[#2E7D32] transition">
            <a href="{{ url('berita/' . $item['id']) }}">{{ $item['judul'] }}</a>
        </h4>

        <div class="flex items-center justify-between mb-4 text-sm text-gray-600">
            <span class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <span>{{ $item['admin'] }}</span>
            </span>
            <span class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                <span>{{ $item['views'] }} dibuka</span>
            </span>
        </div>

        <a href="{{ url('berita/' . $item['id']) }}" class="inline-block mt-auto text-[#2E7D32] font-medium hover:underline">
            Buka Halaman &rarr;
        </a>
    </div>
</div>
