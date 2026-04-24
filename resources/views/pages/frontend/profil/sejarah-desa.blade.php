@extends('layouts.frontend')

@section('title', 'Sejarah Desa - Desa Sindangmukti')

@section('content')
<main class="bg-gray-50 pt-16">
    <!-- SECTION 1: HEADER SEJARAH -->
    <section class="bg-gradient-to-br from-green-800 to-green-600 text-white py-16 md:py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <span
                class="bg-green-700/50 text-green-100 text-sm font-semibold px-3 py-1 rounded-full border border-green-500/30 mb-4 inline-block">Napak Tilas Desa</span>
            <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">{{ $sejarahTitle }}</h1>
            <p class="text-lg text-green-100 max-w-2xl mx-auto leading-relaxed">
                {{ $sejarahSubtitle }}
            </p>
        </div>
    </section>

    <section class="px-6 py-20 mx-auto max-w-7xl">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-3 lg:gap-16">
            <div class="lg:col-span-2">
                <div class="group p-10 bg-white border border-slate-100 shadow-sm rounded-[2.5rem] hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-300">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-1 bg-green-600 rounded-full"></div>
                        <h2 class="text-3xl font-black text-slate-900 tracking-tight">{{ $asalUsulTitle }}</h2>
                    </div>
                    <div class="space-y-6">
                        @foreach($asalUsulParagraphs as $paragraf)
                            <p class="text-lg text-slate-600 leading-relaxed">{!! $paragraf !!}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-24 p-8 bg-green-950 text-white shadow-2xl rounded-[2.5rem] relative overflow-hidden">
                    <!-- Dekorasi -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-green-500 rounded-full mix-blend-screen filter blur-3xl opacity-20 transform translate-x-1/2 -translate-y-1/2"></div>
                    
                    <div class="relative z-10">
                        <h3 class="text-2xl font-black mb-8 tracking-tight flex items-center gap-3">
                            <svg class="w-7 h-7 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Tokoh & Wilayah
                        </h3>
                        <ul class="space-y-6">
                            @foreach($tokohKunci as $tokoh)
                                <li class="group flex items-start gap-4">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-green-300 group-hover:bg-green-600 group-hover:text-white transition-all duration-300">
                                        {!! $tokoh['icon'] !!}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-white mb-1 group-hover:text-green-400 transition-colors">{{ $tokoh['nama'] }}</h4>
                                        <p class="text-sm text-green-100/70 leading-relaxed">{{ $tokoh['deskripsi'] }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-white border-t border-slate-100">
        <div class="max-w-4xl px-6 mx-auto">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-4 text-center">Perjalanan Kepemimpinan Desa</h2>
                <div class="h-1.5 w-20 bg-green-600 rounded-full mx-auto"></div>
            </div>

            <div class="relative">
                <div class="absolute left-4 md:left-1/2 md:-ml-0.5 w-1 top-0 bottom-0 bg-green-100 rounded-full" aria-hidden="true">
                    <div class="absolute inset-0 bg-gradient-to-b from-green-600 to-green-500 rounded-full h-20"></div>
                </div>

                @foreach($kepalaDesa as $index => $item)
                    <div class="relative flex justify-between mb-16 md:items-center group">
                        <div class="{{ $index % 2 == 0 ? 'md:order-1' : 'md:order-3' }} order-3 md:w-[calc(50%-3rem)] w-[calc(100%-5rem)]">
                            <div class="p-8 bg-white border border-slate-100 rounded-[2rem] shadow-sm hover:shadow-[0_20px_50px_rgba(0,0,0,0.08)] hover:-translate-y-2 transition-all duration-500 group">
                                <div class="flex items-center gap-5 mb-6">
                                    <div class="relative">
                                        <div class="absolute inset-0 bg-green-100 rounded-full scale-0 group-hover:scale-110 transition-transform duration-500"></div>
                                        <img src="{{ $item['photo'] ?? asset('assets/img/logo.webp') }}" alt="{{ $item['nama'] }}" class="relative z-10 flex-shrink-0 object-cover w-16 h-16 rounded-full border-4 border-white shadow-md" />
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-black text-slate-900 mb-1 group-hover:text-green-700 transition-colors">{{ $item['nama'] }}</h3>
                                        <span class="inline-block px-3 py-1 bg-green-50 text-green-700 text-xs font-black rounded-full uppercase tracking-widest">{{ $item['masa'] }}</span>
                                    </div>
                                </div>
                                <p class="text-slate-500 leading-relaxed italic group-hover:text-slate-600 transition-colors">"{{ $item['deskripsi'] }}"</p>
                            </div>
                        </div>

                        <div class="relative z-10 flex items-center justify-center order-2 w-12 h-12">
                            <div class="w-6 h-6 rounded-full bg-white border-4 border-green-600 shadow-md group-hover:scale-125 transition-transform duration-300"></div>
                        </div>

                        <div class="{{ $index % 2 == 0 ? 'order-3' : 'order-1' }} hidden md:block md:w-[calc(50%-3rem)]"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</main>
@endsection
