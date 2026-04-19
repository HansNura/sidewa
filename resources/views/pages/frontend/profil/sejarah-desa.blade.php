@extends('layouts.frontend')

@section('title', 'Sejarah Desa - Desa Sindangmukti')

@section('content')
<main class="pt-24 bg-gray-50">
    <section class="py-12 bg-white border-b border-gray-200">
        <div class="px-6 mx-auto max-w-7xl">
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 text-sm text-gray-500 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ url('/') }}" class="inline-flex items-center transition-colors hover:text-[#2e7d32]">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 md:ml-2">Profil Desa</span>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 font-medium text-gray-800 md:ml-2">Sejarah Desa</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-[#2e7d32] md:text-4xl">{{ $sejarahTitle }}</h1>
            <p class="mt-2 text-lg text-gray-600">{{ $sejarahSubtitle }}</p>
        </div>
    </section>

    <section class="px-6 py-16 mx-auto max-w-7xl">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-3 lg:gap-16">
            <div class="lg:col-span-2">
                <div class="p-8 space-y-4 leading-relaxed bg-white shadow-lg rounded-xl">
                    <h2 class="mb-4 text-2xl font-bold text-[#2e7d32]">{{ $asalUsulTitle }}</h2>
                    @foreach($asalUsulParagraphs as $paragraf)
                        <p class="text-gray-700">{!! $paragraf !!}</p>
                    @endforeach
                </div>
            </div>
            <div class="lg:col-span-1">
                <div class="sticky p-6 bg-white shadow-lg rounded-xl top-32">
                    <h3 class="mb-4 text-xl font-semibold text-[#2e7d32]">
                        Tokoh Kunci & Wilayah Awal
                    </h3>
                    <ul class="space-y-3">
                        @foreach($tokohKunci as $tokoh)
                            <li class="flex items-center gap-3">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-green-100/50 text-[#2e7d32]">
                                    {!! $tokoh['icon'] !!}
                                </span>
                                <div>
                                    <h4 class="font-semibold text-[#1f2937]">{{ $tokoh['nama'] }}</h4>
                                    <p class="text-sm text-gray-500">{{ $tokoh['deskripsi'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-gray-100 border-gray-200 border-y">
        <div class="max-w-4xl px-6 mx-auto">
            <h2 class="mb-12 text-3xl font-bold text-center text-[#2e7d32]">
                Perjalanan Kepemimpinan Desa
            </h2>

            <div class="relative">
                <div class="absolute left-4 md:left-1/2 -ml-0.5 w-1 top-0 bottom-0 bg-[#2e7d32] rounded-full" aria-hidden="true"></div>

                @foreach($kepalaDesa as $index => $item)
                    <div class="relative flex justify-between mb-12 md:items-center">
                        <div class="{{ $index % 2 == 0 ? 'md:order-1' : 'md:order-3' }} order-3 md:w-[calc(50%-2.5rem)] w-[calc(100%-4rem)]">
                            <div class="p-6 transition-all duration-300 bg-white rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-1">
                                <div class="flex items-center mb-3">
                                    <img src="{{ $item['photo'] ?? asset('assets/img/logo.webp') }}" alt="{{ $item['nama'] }}" class="flex-shrink-0 object-cover w-12 h-12 mr-3 rounded-full shadow-md" />
                                    <div>
                                        <h3 class="text-xl font-semibold text-[#1f2937]">{{ $item['nama'] }}</h3>
                                        <span class="text-sm font-semibold text-[#2e7d32]">{{ $item['masa'] }}</span>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600">{{ $item['deskripsi'] }}</p>
                            </div>
                        </div>

                        <div class="relative z-10 flex items-center justify-center order-2 w-10 h-10">
                            <div class="w-5 h-5 rounded-full bg-[#2e7d32] ring-8 ring-gray-100"></div>
                        </div>

                        <div class="{{ $index % 2 == 0 ? 'order-3' : 'order-1' }} hidden md:block md:w-[calc(50%-2.5rem)]"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</main>
@endsection
