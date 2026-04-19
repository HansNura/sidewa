{{--
    Login Page — Sistem Informasi Desa Sindangmukti
    Panel Administrasi & Layanan Terpadu
--}}

@extends('layouts.auth-custom')

@section('title', 'Login Sistem - Panel Administrasi Desa Sindangmukti')

@section('content')

    {{-- LOGIN CARD --}}
    <article class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">

        {{-- Card Header --}}
        @include('components.auth.login-header')

        {{-- Form Section --}}
        <section class="p-8">

            {{-- Role Selector --}}
            @include('components.auth.role-selector')

            {{-- Session Status / Error Messages --}}
            @if (session('status'))
                <div class="mb-4 text-sm text-green-600 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-center">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Login Form --}}
            <form action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf

                {{-- Input Username / NIK --}}
                <div class="mb-5">
                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                        Username atau NIK
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-user text-gray-400"></i>
                        </div>
                        <input
                            type="text"
                            id="username"
                            name="email"
                            value="{{ old('email') }}"
                            class="input-focus-ring w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm
                                   rounded-xl focus:border-green-500 block p-3.5 pl-11 transition-all outline-none"
                            placeholder="Masukkan username/NIK"
                            required
                            autofocus
                        />
                    </div>
                </div>

                {{-- Input Password --}}
                <div class="mb-5" x-data="{ showPassword: false }">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400"></i>
                        </div>
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            id="password"
                            name="password"
                            class="input-focus-ring w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm
                                   rounded-xl focus:border-green-500 block p-3.5 pl-11 pr-11 transition-all outline-none"
                            placeholder="••••••••"
                            required
                        />
                        {{-- Toggle Show/Hide Password --}}
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400
                                   hover:text-green-600 transition-colors focus:outline-none cursor-pointer"
                            :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                        >
                            <i :class="showPassword ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"></i>
                        </button>
                    </div>
                </div>

                {{-- Remember Me & Forgot Password --}}
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input
                                id="remember"
                                name="remember"
                                type="checkbox"
                                class="w-4 h-4 border border-gray-300 rounded bg-gray-50
                                       focus:ring-3 focus:ring-green-300 text-green-600 cursor-pointer accent-green-600"
                                {{ old('remember') ? 'checked' : '' }}
                            />
                        </div>
                        <label for="remember" class="ml-2 text-sm font-medium text-gray-600 cursor-pointer">
                            Ingat saya
                        </label>
                    </div>
                    <a href="#"
                       class="text-sm font-semibold text-green-600 hover:text-green-800 hover:underline transition-colors">
                        Lupa sandi?
                    </a>
                </div>

                {{-- CTA Login Button --}}
                <button
                    type="submit"
                    id="loginSubmitBtn"
                    class="w-full text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none
                           focus:ring-green-300 font-bold rounded-xl text-sm px-5 py-4 text-center shadow-lg
                           hover:shadow-xl transition-all flex items-center justify-center gap-2 group cursor-pointer"
                >
                    Masuk Sistem
                    <i class="fa-solid fa-arrow-right-to-bracket group-hover:translate-x-1 transition-transform"></i>
                </button>
            </form>
        </section>
    </article>

    {{-- Footer / Back Navigation --}}
    @include('components.auth.login-footer')

@endsection
