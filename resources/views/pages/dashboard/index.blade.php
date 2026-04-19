@extends('layouts.auth-custom')

@section('title', $pageTitle . ' - Desa Sindangmukti')

@section('content')

    {{-- Dashboard Card --}}
    <article class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <header class="bg-green-800 px-8 py-6 text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-20"
                 style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;40&quot; height=&quot;40&quot; viewBox=&quot;0 0 40 40&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.15&quot; fill-rule=&quot;evenodd&quot;%3E%3Cpath d=&quot;M0 20L20 0h20v20L20 40H0z&quot;/%3E%3C/g%3E%3C/svg%3E');">
            </div>
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg mb-3">
                    <i class="fa-solid fa-gauge-high text-2xl text-green-600"></i>
                </div>
                <h1 class="text-xl font-bold text-white tracking-tight">{{ $pageTitle }}</h1>
                <p class="text-green-100 text-sm mt-1">Selamat datang, {{ auth()->user()->name }}</p>
            </div>
        </header>

        {{-- Content --}}
        <section class="p-8">

            {{-- User Info Card --}}
            <div class="bg-green-50 border border-green-200 rounded-xl p-5 mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-green-700 rounded-full flex items-center justify-center text-white text-xl font-bold">
                        {{ auth()->user()->initials() }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 text-lg">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-gray-600">{{ auth()->user()->email }}</p>
                        <span class="inline-block mt-1 px-3 py-0.5 bg-green-700 text-white text-xs font-semibold rounded-full">
                            {{ auth()->user()->roleName() }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-50 rounded-xl p-4 text-center border border-gray-100">
                    <i class="fa-solid fa-users text-2xl text-green-600 mb-2"></i>
                    <p class="text-2xl font-bold text-gray-800">—</p>
                    <p class="text-xs text-gray-500 font-medium">Total Penduduk</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 text-center border border-gray-100">
                    <i class="fa-solid fa-file-lines text-2xl text-blue-600 mb-2"></i>
                    <p class="text-2xl font-bold text-gray-800">—</p>
                    <p class="text-xs text-gray-500 font-medium">Surat Diproses</p>
                </div>
            </div>

            {{-- Placeholder notice --}}
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-center">
                <i class="fa-solid fa-triangle-exclamation text-amber-500 text-lg mb-1"></i>
                <p class="text-sm text-amber-700 font-medium">
                    Dashboard ini masih dalam tahap pengembangan.
                </p>
                <p class="text-xs text-amber-600 mt-1">
                    Fitur-fitur lengkap akan segera tersedia.
                </p>
            </div>

            {{-- Logout Button --}}
            <form action="{{ route('logout') }}" method="POST" class="mt-6">
                @csrf
                <button type="submit"
                    class="w-full text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none
                           focus:ring-red-300 font-bold rounded-xl text-sm px-5 py-3.5 text-center shadow-lg
                           hover:shadow-xl transition-all flex items-center justify-center gap-2 group cursor-pointer">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar dari Sistem
                </button>
            </form>
        </section>
    </article>

    {{-- Footer --}}
    <footer class="mt-8 text-center">
        <a href="{{ route('home') }}"
           class="inline-flex items-center text-sm font-semibold text-gray-600
                  hover:text-green-700 transition-colors bg-white px-5 py-2.5
                  rounded-full shadow-sm border border-gray-200 hover:border-green-300">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Portal Publik
        </a>
    </footer>

@endsection
