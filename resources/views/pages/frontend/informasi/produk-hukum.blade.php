@extends('layouts.frontend')

@section('title', $pageTitle . ' - Desa Sindangmukti')

@push('styles')
<style>
    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.95) translateY(-10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-animate { animation: modalFadeIn 0.3s ease-out forwards; }
</style>
@endpush

@section('content')
<main class="flex-grow pt-16 bg-gray-50">

    <!-- SECTION 1: HEADER -->
    <section class="bg-gradient-to-br from-green-800 to-green-600 text-white py-16 md:py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <span
                class="bg-green-700/50 text-green-100 text-sm font-semibold px-3 py-1 rounded-full border border-green-500/30 mb-4 inline-block">Regulasi Desa</span>
            <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">{{ $pageTitle }}</h1>
            <p class="text-lg text-green-100 max-w-2xl mx-auto leading-relaxed">
                {{ $pageSubtitle }}
            </p>
        </div>
    </section>

    <div class="px-4 py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">

        <!-- SECTION 2: CATEGORY STATS -->
        <section class="grid grid-cols-2 gap-4 mb-10 md:grid-cols-{{ count($categories) <= 4 ? count($categories) : 4 }}">
            @php
                $catColors = [
                    ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-200', 'hoverBg' => 'group-hover:bg-blue-600', 'hoverBorder' => 'hover:border-blue-500'],
                    ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-200', 'hoverBg' => 'group-hover:bg-emerald-600', 'hoverBorder' => 'hover:border-emerald-500'],
                    ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-200', 'hoverBg' => 'group-hover:bg-amber-600', 'hoverBorder' => 'hover:border-amber-500'],
                    ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-200', 'hoverBg' => 'group-hover:bg-purple-600', 'hoverBorder' => 'hover:border-purple-500'],
                ];
                $catIcons = [
                    '<i class="fa-solid fa-book-section text-xl"></i>',
                    '<i class="fa-solid fa-file-signature text-xl"></i>',
                    '<i class="fa-solid fa-file-contract text-xl"></i>',
                    '<i class="fa-solid fa-gavel text-xl"></i>',
                ];
                $catShortNames = [
                    'peraturan-desa' => 'Perdes',
                    'sk-kepala-desa' => 'SK Kades',
                    'peraturan-kepala-desa' => 'Perkades',
                    'keputusan-bpd' => 'Kep. BPD',
                ];
            @endphp
            @foreach($categories as $idx => $cat)
            @php $c = $catColors[$idx % count($catColors)]; @endphp
            <a href="{{ route('informasi.hukum', ['kategori' => $cat->slug]) }}"
               class="flex items-center gap-4 p-5 transition-colors bg-white border border-gray-200 rounded-2xl shadow-sm {{ $c['hoverBorder'] }} group {{ $kategori == $cat->slug ? 'ring-2 ring-green-500 border-green-500' : '' }}">
                <div class="flex items-center justify-center w-12 h-12 transition-colors {{ $c['border'] }} rounded-full shrink-0 {{ $c['bg'] }} {{ $c['text'] }} {{ $c['hoverBg'] }} group-hover:text-white">
                    {!! $catIcons[$idx % count($catIcons)] !!}
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $cat->documents_count }}</p>
                    <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">{{ $catShortNames[$cat->slug] ?? $cat->name }}</p>
                </div>
            </a>
            @endforeach
        </section>

        <!-- SECTION 3: SEARCH & FILTER -->
        <section class="p-4 mb-8 bg-white border border-gray-200 shadow-sm md:p-6 rounded-2xl">
            <form action="{{ route('informasi.hukum') }}" method="GET" class="flex flex-col items-end gap-4 md:flex-row">
                <!-- Search -->
                <div class="w-full md:w-2/5">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Cari Dokumen</label>
                    <div class="relative">
                        <input type="text" name="q" value="{{ $search }}" placeholder="Masukkan judul, nomor, atau tentang..."
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-green-500 focus:border-green-500">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="w-full md:w-1/5">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Jenis Aturan</label>
                    <select name="kategori" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 p-2.5">
                        <option value="">Semua Jenis</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ $kategori == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Year Filter -->
                <div class="w-full md:w-1/5">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Tahun</label>
                    <select name="tahun" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 p-2.5">
                        <option value="">Semua Tahun</option>
                        @foreach($availableYears as $yr)
                            <option value="{{ $yr }}" {{ $tahun == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit -->
                <div class="w-full md:w-1/5">
                    <button type="submit" class="w-full text-white bg-green-600 hover:bg-green-700 font-semibold rounded-lg text-sm px-5 py-2.5 transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-filter"></i> Terapkan
                    </button>
                </div>
            </form>

            {{-- Active filter badges --}}
            @if($search || $kategori || $tahun)
            <div class="flex flex-wrap items-center gap-2 mt-4 pt-3 border-t border-gray-100">
                <span class="text-xs font-medium text-gray-500">Filter aktif:</span>
                @if($search)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">
                        "{{ $search }}"
                        <a href="{{ route('informasi.hukum', array_filter(['kategori' => $kategori, 'tahun' => $tahun])) }}" class="text-green-400 hover:text-green-700"><i class="fa-solid fa-times"></i></a>
                    </span>
                @endif
                @if($kategori)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">
                        {{ $categories->firstWhere('slug', $kategori)?->name }}
                        <a href="{{ route('informasi.hukum', array_filter(['q' => $search, 'tahun' => $tahun])) }}" class="text-blue-400 hover:text-blue-700"><i class="fa-solid fa-times"></i></a>
                    </span>
                @endif
                @if($tahun)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium bg-amber-100 text-amber-700 rounded-full">
                        {{ $tahun }}
                        <a href="{{ route('informasi.hukum', array_filter(['q' => $search, 'kategori' => $kategori])) }}" class="text-amber-400 hover:text-amber-700"><i class="fa-solid fa-times"></i></a>
                    </span>
                @endif
                <a href="{{ route('informasi.hukum') }}" class="text-xs text-red-500 hover:underline ml-2">Reset semua</a>
            </div>
            @endif
        </section>

        <!-- SECTION 4: DOCUMENT LIST -->
        <section class="mb-12">
            <div class="flex items-center justify-between pb-2 mb-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800">Daftar Produk Hukum</h2>
                <span class="text-sm text-gray-500">
                    Menampilkan {{ $daftarHukum->firstItem() ?? 0 }}-{{ $daftarHukum->lastItem() ?? 0 }} dari {{ $daftarHukum->total() }} dokumen
                </span>
            </div>

            @if($daftarHukum->count() > 0)
            <div class="flex flex-col gap-4">
                @foreach($daftarHukum as $doc)
                @php
                    $isBerlaku = $doc->is_berlaku;
                @endphp
                <article class="{{ $isBerlaku ? 'bg-white hover:border-green-400' : 'bg-gray-50' }} rounded-xl shadow-sm border border-gray-200 p-5 flex flex-col md:flex-row gap-5 items-start md:items-center hover:-translate-y-1 hover:shadow-md transition-all group"
                         x-data>
                    <!-- PDF Icon -->
                    <div class="{{ $isBerlaku ? 'bg-red-50 text-red-500 border-red-100' : 'bg-gray-200 text-gray-500 border-gray-300' }} w-14 h-14 rounded-lg flex items-center justify-center shrink-0 border">
                        <i class="fa-solid fa-file-pdf text-2xl"></i>
                    </div>

                    <!-- Metadata -->
                    <div class="flex-grow">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            {{-- Category badge --}}
                            @php
                                $catSlug = $doc->category?->slug ?? '';
                                $catBadgeColor = match($catSlug) {
                                    'peraturan-desa' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'sk-kepala-desa' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'peraturan-kepala-desa' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'keputusan-bpd' => 'bg-purple-100 text-purple-700 border-purple-200',
                                    default => 'bg-gray-100 text-gray-700 border-gray-200',
                                };
                            @endphp
                            <span class="{{ $catBadgeColor }} text-xs font-bold px-2.5 py-0.5 rounded border">
                                {{ $doc->category_name }}
                            </span>
                            {{-- Status badge --}}
                            @if($isBerlaku)
                                <span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-0.5 rounded border border-green-200 flex items-center">
                                    <i class="fa-solid fa-check-circle mr-1"></i> Berlaku
                                </span>
                            @else
                                <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-0.5 rounded border border-red-200 flex items-center">
                                    <i class="fa-solid fa-ban mr-1"></i> {{ $doc->status_label }}
                                </span>
                            @endif
                        </div>
                        <h3 class="font-bold text-lg {{ $isBerlaku ? 'text-gray-800 group-hover:text-green-700' : 'text-gray-600 line-through' }} mb-1 transition-colors">
                            {{ $doc->title }}
                        </h3>
                        @if($doc->description)
                        <p class="text-sm font-medium {{ $isBerlaku ? 'text-gray-600' : 'text-gray-500' }}">
                            Tentang: {{ $doc->description }}
                        </p>
                        @endif
                        <div class="flex gap-4 mt-3 text-xs {{ $isBerlaku ? 'text-gray-500' : 'text-gray-400' }} font-medium">
                            <span class="flex items-center">
                                <i class="fa-regular fa-calendar mr-1"></i> Ditetapkan: {{ $doc->formatted_date }}
                            </span>
                            @if($doc->download_count > 0)
                            <span class="flex items-center">
                                <i class="fa-solid fa-download mr-1"></i> {{ $doc->download_count }} Unduhan
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-row w-full gap-2 pt-4 border-t shrink-0 md:w-auto md:flex-col md:border-t-0 md:border-l border-gray-100 md:pt-0 md:pl-5">
                        <button x-on:click="$refs.modalDoc{{ $doc->id }}.showModal()"
                            class="flex items-center justify-center flex-1 gap-2 px-4 py-2 text-sm font-semibold {{ $isBerlaku ? 'text-green-700 bg-gray-50 hover:bg-green-50 border-gray-200 hover:border-green-300' : 'text-gray-700 bg-gray-100 hover:bg-gray-200 border-gray-300' }} transition-colors border rounded-lg md:flex-none">
                            <i class="fa-solid fa-eye"></i> Detail
                        </button>
                        @if($isBerlaku)
                            <a href="{{ route('informasi.hukum.download', $doc) }}"
                               class="flex items-center justify-center flex-1 gap-2 px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg shadow-sm hover:bg-green-700 transition-colors md:flex-none">
                                <i class="fa-solid fa-cloud-arrow-down"></i> Unduh
                            </a>
                        @else
                            <span class="flex items-center justify-center flex-1 gap-2 px-4 py-2 text-sm font-semibold text-white bg-gray-400 rounded-lg shadow-sm cursor-not-allowed md:flex-none">
                                <i class="fa-solid fa-lock"></i> Arsip
                            </span>
                        @endif
                    </div>
                </article>

                <!-- MODAL DETAIL for this document -->
                <dialog x-ref="modalDoc{{ $doc->id }}" class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full p-0 overflow-hidden backdrop:bg-black/60 backdrop:backdrop-blur-sm modal-animate">
                    <div class="flex flex-col h-full max-h-[90vh]">
                        <!-- Header -->
                        <div class="flex items-start justify-between px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-2xl shrink-0">
                            <div>
                                <h2 class="pr-8 text-xl font-bold text-gray-800">Detail Produk Hukum</h2>
                                <p class="mt-1 text-sm text-gray-500">Metadata Jaringan Dokumentasi dan Informasi Hukum (JDIH)</p>
                            </div>
                            <form method="dialog">
                                <button class="flex items-center justify-center w-8 h-8 text-gray-400 transition-colors rounded-full hover:text-red-500 hover:bg-gray-200">
                                    <i class="fa-solid fa-times text-xl"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Body -->
                        <div class="flex flex-col flex-grow p-6 overflow-y-auto md:flex-row gap-8">
                            <!-- Left: Metadata -->
                            <div class="w-full space-y-6 md:w-1/2">
                                <div>
                                    <h3 class="mb-1 font-bold text-gray-800">Judul Peraturan</h3>
                                    <p class="font-medium text-gray-700">{{ $doc->title }}{{ $doc->description ? ' tentang ' . $doc->description : '' }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-3 border border-gray-100 rounded-lg bg-gray-50">
                                        <p class="mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">Tipe / Jenis</p>
                                        <p class="text-sm font-bold text-gray-800">{{ $doc->category_name }}</p>
                                    </div>
                                    <div class="p-3 border border-gray-100 rounded-lg bg-gray-50">
                                        <p class="mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">Nomor</p>
                                        <p class="text-sm font-bold text-gray-800">{{ $doc->document_number }}</p>
                                    </div>
                                    <div class="p-3 border border-gray-100 rounded-lg bg-gray-50">
                                        <p class="mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">Tahun</p>
                                        <p class="text-sm font-bold text-gray-800">{{ $doc->year }}</p>
                                    </div>
                                    <div class="p-3 border border-gray-100 rounded-lg bg-gray-50">
                                        <p class="mb-1 text-xs font-semibold tracking-wide text-gray-500 uppercase">Status Keberlakuan</p>
                                        <p class="text-sm font-bold {{ $isBerlaku ? 'text-green-600' : 'text-red-600' }}">
                                            @if($isBerlaku)
                                                <i class="fa-solid fa-check-circle mr-1"></i>
                                            @else
                                                <i class="fa-solid fa-ban mr-1"></i>
                                            @endif
                                            {{ $doc->status_label }}
                                        </p>
                                    </div>
                                </div>

                                <div class="pt-2 border-t border-gray-100 space-y-3">
                                    <div class="flex justify-between pb-2 border-b border-gray-100">
                                        <span class="text-sm text-gray-500">Tanggal Ditetapkan</span>
                                        <span class="text-sm font-medium text-gray-800">{{ $doc->formatted_date_full }}</span>
                                    </div>
                                    <div class="flex justify-between pb-2 border-b border-gray-100">
                                        <span class="text-sm text-gray-500">Tanggal Diundangkan</span>
                                        <span class="text-sm font-medium text-gray-800">{{ $doc->promulgated_date_formatted }}</span>
                                    </div>
                                    <div class="flex justify-between pb-2 border-b border-gray-100">
                                        <span class="text-sm text-gray-500">Pemrakarsa</span>
                                        <span class="text-sm font-medium text-gray-800">{{ $doc->initiator_name }}</span>
                                    </div>
                                    <div class="flex justify-between pb-2">
                                        <span class="text-sm text-gray-500">Penandatangan</span>
                                        <span class="text-sm font-medium text-gray-800">{{ $doc->signer_name }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: File Preview -->
                            <div class="flex flex-col w-full md:w-1/2">
                                <h3 class="pb-2 mb-3 font-bold text-gray-800 border-b border-gray-200">Dokumen Elektronik</h3>

                                <div class="bg-gray-100 flex-grow rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center p-6 text-center mb-4 min-h-[250px]">
                                    <i class="fa-regular fa-file-pdf text-5xl text-red-400 mb-3"></i>
                                    <p class="font-bold text-gray-700">{{ $doc->file_name }}</p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Ukuran File: {{ $doc->file_size_formatted }} | Tipe: PDF
                                    </p>
                                    <span class="px-3 py-1 mt-4 text-xs text-gray-600 bg-white border border-gray-300 rounded-full shadow-sm">
                                        <i class="fa-solid fa-eye mr-1"></i> Pratinjau Dokumen
                                    </span>
                                </div>

                                @if($isBerlaku)
                                <a href="{{ route('informasi.hukum.download', $doc) }}"
                                   class="flex items-center justify-center w-full gap-2 px-4 py-3.5 font-bold text-white bg-green-600 shadow-md rounded-xl hover:bg-green-700 transition-all group/dl">
                                    <i class="fa-solid fa-cloud-arrow-down text-lg group-hover/dl:-translate-y-1 transition-transform"></i>
                                    Unduh Dokumen Resmi
                                </a>
                                @else
                                <span class="flex items-center justify-center w-full gap-2 px-4 py-3.5 font-bold text-white bg-gray-400 shadow-md rounded-xl cursor-not-allowed">
                                    <i class="fa-solid fa-lock text-lg"></i>
                                    Dokumen Tidak Tersedia (Dicabut)
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </dialog>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($daftarHukum->hasPages())
            <div class="mt-10 flex justify-center">
                {{ $daftarHukum->links() }}
            </div>
            @endif

            @else
            {{-- Empty State --}}
            <div class="text-center py-16 bg-white rounded-2xl border border-gray-200">
                <i class="fa-regular fa-file-lines text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-bold text-gray-700 mb-2">Tidak Ditemukan</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Tidak ada dokumen hukum yang cocok dengan filter Anda.
                </p>
                <a href="{{ route('informasi.hukum') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fa-solid fa-arrow-rotate-left"></i> Reset Filter
                </a>
            </div>
            @endif
        </section>

    </div>
</main>
@endsection
