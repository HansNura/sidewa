@extends('layouts.frontend')

@section('title', 'Visi & Misi - Desa Sukakerta')

@section('content')
<div class="pt-24 bg-gray-50 text-gray-800 antialiased">
    <section class="py-12 bg-white border-b border-gray-200">
        <div class="px-6 mx-auto max-w-7xl">
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 text-sm text-gray-500 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ url('/') }}" class="inline-flex items-center transition-colors hover:text-primary">
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
                            <span class="ml-1 font-medium text-gray-800 md:ml-2">Visi & Misi</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold md:text-4xl" style="color: #2e7d32;">
                Visi & Misi Desa Sukakerta
            </h1>
            <p class="mt-2 text-lg text-gray-600">
                Arah kebijakan dan program unggulan untuk mewujudkan
                Desa Sukakerta yang lebih baik.
            </p>
        </div>
    </section>

    <!-- SECTION VISI & MISI -->
    <section class="py-16">
        <div class="px-6 mx-auto max-w-7xl lg:px-8">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-3 lg:gap-16">
                <!-- Kolom VISI (sticky) -->
                <div class="lg:sticky lg:top-32 h-fit">
                    <div class="p-8 bg-white border shadow-lg rounded-2xl" style="border-color: #2e7d32;">
                        <h2 class="flex items-center gap-3 mb-4 text-3xl font-bold" style="color: #2e7d32;">
                            <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Visi
                        </h2>
                        <p class="text-xl italic font-medium text-gray-700">{{ $visi }}</p>
                    </div>
                </div>

                <!-- Kolom MISI (scrollable) -->
                <div class="lg:col-span-2">
                    <h2 class="flex items-center gap-3 mb-6 text-3xl font-bold" style="color: #1f2937;">
                        <svg class="w-8 h-8" style="color: #2e7d32;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3.75 3 20.25l18-16.5L3 20.25v-3.853L14.25 12 3 7.603V3.75Z" />
                        </svg>
                        Misi
                    </h2>
                    <div class="space-y-5">
                        @foreach($misiList as $index => $misi)
                        <div class="flex items-start gap-4 p-5 bg-white border border-gray-100 shadow-md rounded-xl">
                            <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 text-lg font-bold rounded-full" style="background-color: #2e7d32; color: #fbc02d;">
                                <span>{{ $index + 1 }}</span>
                            </div>
                            <p class="leading-relaxed text-gray-700">{{ $misi }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PROGRAM UNGGULAN -->
    <section class="py-20 bg-white border-t border-gray-100">
        <div class="px-6 mx-auto text-center max-w-7xl lg:px-8">
            <h2 class="mb-12 text-3xl font-bold" style="color: #2e7d32;">
                Program Unggulan Desa Sukakerta
            </h2>
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach($programUnggulan as $program)
                <div class="p-8 text-left transition-shadow duration-300 bg-white border border-gray-100 shadow-lg rounded-2xl hover:shadow-xl">
                    <div class="flex items-center justify-center w-12 h-12 mb-5 rounded-full" style="background-color: #2e7d32; color: #fbc02d;">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            {!! $program['icon'] !!}
                        </svg>
                    </div>
                    <h4 class="mb-2 text-xl font-semibold" style="color: #2e7d32;">{{ $program['title'] }}</h4>
                    <p class="text-sm text-gray-700">{{ $program['description'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
