@extends('layouts.frontend')

@section('title', 'Visi & Misi - Desa Sindangmukti')

@section('content')
<div class="bg-gray-50 text-gray-800 antialiased pt-16">
    <!-- SECTION 1: HEADER VISI & MISI -->
    <section class="bg-gradient-to-br from-green-800 to-green-600 text-white py-16 md:py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <span
                class="bg-green-700/50 text-green-100 text-sm font-semibold px-3 py-1 rounded-full border border-green-500/30 mb-4 inline-block">Cita-cita & Tujuan</span>
            <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">Visi & Misi Desa Sindangmukti</h1>
            <p class="text-lg text-green-100 max-w-2xl mx-auto leading-relaxed">
                Arah kebijakan dan program unggulan untuk mewujudkan Desa Sindangmukti yang unggul, sejahtera, dan berkelanjutan.
            </p>
        </div>
    </section>

    <!-- SECTION VISI & MISI: SPLIT LAYOUT -->
    <section id="visi-misi" class="py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] p-8 sm:p-12 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
                    
                    <!-- Kolom Kiri: Judul & Visi (Sticky) -->
                    <div class="lg:col-span-5 space-y-8 lg:sticky lg:top-24">
                        <div>
                            <h2 class="text-3xl font-black text-slate-900 mb-4 tracking-tight">Visi & Misi Desa Sindangmukti</h2>
                            <p class="text-slate-500 leading-relaxed">Arah kebijakan dan program unggulan untuk mewujudkan Desa Sindangmukti yang lebih baik, sejahtera, dan mandiri.</p>
                        </div>

                        <!-- Card Visi -->
                        <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-[2rem] p-8 text-white shadow-xl shadow-green-600/20 relative overflow-hidden group">
                            <!-- Ornamen Background -->
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white opacity-5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                            <svg class="absolute top-6 left-6 w-10 h-10 text-white/20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                            </svg>
                            
                            <div class="relative z-10 pt-8">
                                <span class="inline-block px-3 py-1 bg-white/20 rounded-full text-xs font-semibold tracking-wide uppercase mb-4 backdrop-blur-sm">Visi Utama</span>
                                <p class="text-xl sm:text-2xl font-bold leading-snug italic">
                                    “{{ $visi }}”
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Misi List -->
                    <div class="lg:col-span-7">
                        <h3 class="text-lg font-black text-slate-900 mb-8 flex items-center gap-2">
                            <div class="w-8 h-1 bg-green-600 rounded-full"></div>
                            Misi Strategis Kami
                        </h3>
                        
                        <div class="space-y-4">
                            @foreach($misiList as $index => $misi)
                            <div class="group flex items-start gap-5 p-5 sm:p-6 rounded-[1.5rem] hover:bg-green-50 border border-transparent hover:border-green-100 transition-all duration-300">
                                <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center font-black text-lg group-hover:bg-green-600 group-hover:text-white transition-all duration-300 shadow-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div class="pt-1.5">
                                    <p class="text-slate-600 font-medium text-lg leading-relaxed group-hover:text-slate-900 transition-colors">
                                        {{ $misi }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
            </div>
        </div>
    </section>

    <!-- PROGRAM UNGGULAN -->
    <section class="py-24 bg-white border-t border-slate-100">
        <div class="px-6 mx-auto max-w-7xl lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-4">Program Unggulan Desa Sindangmukti</h2>
                <div class="h-1.5 w-20 bg-green-600 rounded-full mx-auto"></div>
            </div>
            
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach($programUnggulan as $program)
                <div class="group p-10 bg-white border border-slate-100 shadow-sm rounded-[2.5rem] hover:shadow-[0_20px_50px_rgba(0,0,0,0.08)] hover:-translate-y-2 transition-all duration-500">
                    <div class="flex items-center justify-center w-14 h-14 mb-8 rounded-2xl bg-green-50 text-green-600 group-hover:bg-green-600 group-hover:text-white transition-all duration-300 shadow-sm">
                        <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            {!! $program['icon'] !!}
                        </svg>
                    </div>
                    <h4 class="mb-4 text-2xl font-bold text-slate-900 group-hover:text-green-700 transition-colors">{{ $program['title'] }}</h4>
                    <p class="text-slate-500 leading-relaxed group-hover:text-slate-600 transition-colors">{{ $program['description'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
