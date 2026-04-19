@extends('layouts.backoffice')

@section('title', 'Manajemen APBDes - Panel Administrasi')

@section('content')
<div class="flex-1 flex flex-col h-full bg-[#F8FAFC]">

    <!-- TOP NAVBAR & HEADERS -->
    <header class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4 shrink-0 transition-opacity">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-semibold px-2.5 py-1 bg-amber-100 text-amber-700 rounded-md uppercase tracking-wider">Akses Keuangan</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen APBDes</h1>
            <p class="text-sm text-gray-500 mt-1">Pengelolaan Anggaran Pendapatan dan Belanja Desa (Murni).</p>
        </div>

        <div class="flex items-center gap-3">
            <form action="{{ route('admin.apbdes.index') }}" method="GET" class="relative shrink-0 hidden md:block">
                <select name="tahun" onchange="this.form.submit()"
                    class="bg-white border border-gray-200 shadow-sm rounded-xl px-4 py-2 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-green-500 outline-none cursor-pointer appearance-none pr-10">
                    @foreach($yearOptions as $yr)
                        <option value="{{ $yr }}" {{ $tahun == $yr ? 'selected' : '' }}>T.A {{ $yr }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-calendar-check absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
            </form>
        </div>
    </header>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-bold shadow-sm">
            <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-bold shadow-sm">
            <i class="fa-solid fa-circle-exclamation mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <div class="space-y-6" x-data="{ 
            activeTab: 'overview',
            addBudgetModal: false,
            
            formatIDR(val) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);
            }
        }">

        <!-- CARDS -->
        @include('pages.backoffice.keuangan.apbdes._cards')

        <!-- TABS NAV -->
        <section class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex flex-wrap gap-2 p-1 bg-gray-100 rounded-xl">
                <button @click="activeTab = 'overview'"
                    :class="activeTab === 'overview' ? 'bg-white shadow-sm text-green-700 font-bold' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap">Struktur APBDes</button>
                    
                <button @click="activeTab = 'kelola'"
                    :class="activeTab === 'kelola' ? 'bg-white shadow-sm text-green-700 font-bold' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap">Input Anggaran</button>
                    
                <button @click="activeTab = 'media'"
                    :class="activeTab === 'media' ? 'bg-white shadow-sm text-green-700 font-bold' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-2 text-sm rounded-lg transition-all whitespace-nowrap">Publikasi & Dokumen</button>
            </div>

            <div class="flex items-center gap-3">
                <button @click="addBudgetModal = true"
                    class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2.5 text-sm font-bold transition-all flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah Anggaran
                </button>
            </div>
        </section>

        <!-- VIEW 1: STRUKTUR APBDes (HIERARCHY TREE) -->
        @include('pages.backoffice.keuangan.apbdes._struktur')

        <!-- VIEW 2: INPUT ANGGARAN (KELOLA) -->
        @include('pages.backoffice.keuangan.apbdes._form')

        <!-- VIEW 3: MEDIA PUBIKASI & DOKUMEN -->
        @include('pages.backoffice.keuangan.apbdes._media')

        <!-- MODAL ADD (Sama dengan format Tab Kelola sebenarnya, tapi dibuat floating) -->
        @include('pages.backoffice.keuangan.apbdes._modal')

    </div>
</div>
@endsection
