{{--
    Login Page — Sistem Informasi Desa Sindangmukti
    Panel Administrasi & Layanan Terpadu
--}}

@extends('layouts.auth-custom')

@section('title', 'Login Sistem - Panel Administrasi Desa Sindangmukti')

@section('content')
<div class="w-full max-w-md bg-white rounded-[2rem] p-6 sm:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 relative z-10 my-8">
    <!-- Header Text -->
    <div class="text-center mb-6">
        <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-emerald-600 shadow-sm border border-emerald-100">
            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight mb-1">Sistem Informasi Desa</h2>
        <p class="text-sm text-slate-500 font-medium">Panel Administrasi & Layanan Terpadu</p>
    </div>

    <!-- Session Status / Error Messages -->
    @if (session('status'))
        <div class="mb-5 text-xs sm:text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 text-center font-bold">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 text-xs sm:text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
            <ul class="list-disc list-inside space-y-0.5 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Login Form -->
    <form action="{{ route('login') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Pilih Peran (Pill Radio Buttons) -->
        @php
            $roles = [
                'administrator' => 'Administrator',
                'operator' => 'Operator',
                'kades' => 'Kades',
                'perangkat' => 'Perangkat',
                'resepsionis' => 'Resepsionis',
            ];
            $selectedRole = old('role', 'administrator');
        @endphp
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-3">Pilih Peran Akses</label>
            <div class="flex flex-wrap gap-2">
                @foreach($roles as $key => $label)
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="{{ $key }}" class="peer sr-only" {{ $selectedRole === $key ? 'checked' : '' }}>
                    <span class="block px-3.5 py-2 text-xs font-bold rounded-xl border border-slate-200 text-slate-600 peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 hover:bg-slate-50 transition-all select-none">
                        {{ $label }}
                    </span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Input Username -->
        <div>
            <label for="login" class="block text-sm font-bold text-slate-700 mb-2">Username atau NIK</label>
            <input type="text" id="login" name="login" value="{{ old('login') }}" required autofocus
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 outline-none transition-all text-sm font-medium text-slate-800" 
                   placeholder="Masukkan username/NIK">
        </div>

        <!-- Input Password -->
        <div x-data="{ show: false }">
            <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Kata Sandi</label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" id="password" name="password" required
                       class="w-full px-4 py-3 pr-12 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 outline-none transition-all text-sm font-medium text-slate-800" 
                       placeholder="••••••••">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-4 flex items-center text-slate-400 hover:text-emerald-600 transition-colors focus:outline-none">
                    <i :class="show ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i>
                </button>
            </div>
        </div>

        <!-- Options: Remember & Forgot -->
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer group">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 transition-all cursor-pointer">
                <span class="text-sm font-medium text-slate-600 group-hover:text-slate-800 transition-colors">Ingat saya</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 transition-colors">Lupa sandi?</a>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-slate-900 text-white font-bold py-3.5 rounded-xl hover:bg-emerald-600 hover:shadow-lg hover:shadow-emerald-500/30 transition-all duration-300 transform active:scale-[0.98] flex items-center justify-center gap-2 group">
            Masuk Sistem
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 group-hover:translate-x-1 transition-transform">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </button>

    </form>

    <!-- Footer / Back Link -->
    <div class="mt-8 text-center border-t border-slate-100 pt-6">
        <p class="text-sm text-slate-500 font-medium mb-3">Bukan aparatur desa?</p>
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-700 hover:text-emerald-600 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Portal Publik
        </a>
    </div>
</div>
@endsection
