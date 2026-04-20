@extends('layouts.backoffice')

@section('title', 'Export & Import Data - Panel Administrasi')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        .custom-checkbox {
            appearance: none; background-color: #fff; margin: 0; font: inherit; color: currentColor; width: 1.15em; height: 1.15em;
            border: 2px solid #cbd5e1; border-radius: 0.25em; display: grid; place-content: center; cursor: pointer; transition: all 0.2s ease-in-out;
        }
        .custom-checkbox::before {
            content: ""; width: 0.65em; height: 0.65em; transform: scale(0); transition: 120ms transform ease-in-out;
            box-shadow: inset 1em 1em white; background-color: transform; transform-origin: center;
            clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        }
        .custom-checkbox:checked { background-color: #16a34a; border-color: #16a34a; }
        .custom-checkbox:checked::before { transform: scale(1); }
    </style>
@endpush

@section('content')
<div class="flex-1 flex flex-col h-full overflow-hidden w-full relative bg-[#F8FAFC]"
    x-data="{ 
        activeTab: '{{ session('activeTab', 'export') }}',
        exportFormat: 'excel',
        exportModule: 'penduduk',
        importStep: {{ session('importStatusMsg') ? 3 : 1 }},
        importStatus: '{{ session('importStatusMsg') ? 'success' : 'ready' }}',
        importModule: ''
    }">

    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 shrink-0 z-10 shadow-sm mt-0">
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex items-center gap-2">
                <span class="text-xs font-semibold px-2.5 py-1 bg-purple-100 text-purple-700 rounded-md uppercase tracking-wider">Super Admin</span>
                <h2 class="font-bold text-gray-800">Pusat Data & Integrasi</h2>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button class="flex items-center gap-2.5 focus:outline-none hover:bg-gray-50 p-1 rounded-full sm:rounded-xl sm:pr-3 transition-colors">
                <img src="{{ Auth::user()->avatarUrl() }}" alt="User" class="w-8 h-8 rounded-full shadow-sm">
            </button>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-6 lg:p-8">
        <div class="max-w-7xl mx-auto space-y-6">

            <section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Export & Import Data</h1>
                    <p class="text-sm text-gray-500 mt-1">Lakukan pertukaran data massal (backup, migrasi, sinkronisasi) dengan standar validasi keamanan.</p>
                </div>
            </section>

            <section class="flex gap-2 p-1 bg-white rounded-xl w-full md:w-auto overflow-x-auto shadow-sm border border-gray-100">
                <button @click="activeTab = 'export'" :class="activeTab === 'export' ? 'bg-primary-50 text-primary-700 font-bold border border-primary-200' : 'text-gray-500 hover:text-gray-700 border border-transparent hover:bg-gray-50'" class="flex-1 md:flex-none px-6 py-2.5 text-sm rounded-lg transition-all whitespace-nowrap"><i class="fa-solid fa-file-export mr-1.5"></i> Export Data</button>
                <button @click="activeTab = 'import'" :class="activeTab === 'import' ? 'bg-primary-50 text-primary-700 font-bold border border-primary-200' : 'text-gray-500 hover:text-gray-700 border border-transparent hover:bg-gray-50'" class="flex-1 md:flex-none px-6 py-2.5 text-sm rounded-lg transition-all whitespace-nowrap"><i class="fa-solid fa-file-import mr-1.5"></i> Import Data</button>
                <button @click="activeTab = 'riwayat'" :class="activeTab === 'riwayat' ? 'bg-primary-50 text-primary-700 font-bold border border-primary-200' : 'text-gray-500 hover:text-gray-700 border border-transparent hover:bg-gray-50'" class="flex-1 md:flex-none px-6 py-2.5 text-sm rounded-lg transition-all whitespace-nowrap"><i class="fa-solid fa-clock-rotate-left mr-1.5"></i> Riwayat Aktivitas</button>
            </section>

            <!-- TAB 1: EXPORT DATA -->
            <form action="{{ route('admin.data-exchange.export') }}" method="POST" target="_blank" x-show="activeTab === 'export'" x-transition.opacity class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-1 space-y-6 flex flex-col">
                        <section class="bg-white border border-gray-100 shadow-sm rounded-2xl p-5">
                            <h3 class="text-sm font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Filter Data Base</h3>
                            <div class="space-y-4">
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-gray-600 uppercase tracking-wider">Modul Data <span class="text-red-500">*</span></label>
                                    <select name="module" x-model="exportModule" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none cursor-pointer font-semibold text-gray-800">
                                        <option value="penduduk">Data Penduduk (Demografi)</option>
                                        <!-- Add others layer -->
                                    </select>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-gray-600 uppercase tracking-wider">Periode Update</label>
                                    <select class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 outline-none cursor-pointer">
                                        <option value="all">Semua Waktu (All Data)</option>
                                    </select>
                                </div>
                            </div>
                        </section>

                        <section class="bg-white border border-gray-100 shadow-sm rounded-2xl p-5 flex-1">
                            <h3 class="text-sm font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Format File Target</h3>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="format" value="excel" x-model="exportFormat" class="peer sr-only">
                                    <div class="p-3 border-2 border-gray-200 rounded-xl bg-white peer-checked:border-green-500 peer-checked:bg-green-50 transition-all flex flex-col items-center justify-center text-center gap-1.5 h-full">
                                        <i class="fa-solid fa-file-excel text-2xl text-green-600"></i>
                                        <span class="text-xs font-bold text-gray-700">Excel (.xlsx)</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="format" value="csv" x-model="exportFormat" class="peer sr-only">
                                    <div class="p-3 border-2 border-gray-200 rounded-xl bg-white peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all flex flex-col items-center justify-center text-center gap-1.5 h-full">
                                        <i class="fa-solid fa-file-csv text-2xl text-blue-500"></i>
                                        <span class="text-xs font-bold text-gray-700">CSV (.csv)</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="format" value="json" x-model="exportFormat" class="peer sr-only">
                                    <div class="p-3 border-2 border-gray-200 rounded-xl bg-white peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all flex flex-col items-center justify-center text-center gap-1.5 h-full">
                                        <i class="fa-solid fa-file-code text-2xl text-purple-600"></i>
                                        <span class="text-xs font-bold text-gray-700">JSON API</span>
                                    </div>
                                </label>
                            </div>
                        </section>
                    </div>

                    <div class="lg:col-span-2 space-y-6 flex flex-col">
                        <section class="bg-white border border-gray-100 shadow-sm rounded-2xl p-5">
                            <h3 class="text-sm font-bold text-gray-800 border-b border-gray-100 pb-2 mb-4">Pemilihan Kolom (Fields)</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <label class="flex items-center gap-2 p-2 rounded-lg opacity-60">
                                    <input type="checkbox" class="custom-checkbox" checked disabled>
                                    <span class="text-xs font-semibold text-gray-700">Kumpulan File Standar</span>
                                </label>
                            </div>
                        </section>
                        
                        <section class="bg-white border border-gray-100 shadow-sm rounded-2xl flex flex-col flex-1 overflow-hidden">
                            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                                <h3 class="text-sm font-bold text-gray-800">Informasi</h3>
                                <p class="text-[10px] text-gray-500">Data akan disiapkan dan diunduh seketika setelah tombol Export ditekan.</p>
                            </div>
                            
                            <div class="p-5 border-t border-gray-100 bg-gray-50/50 flex justify-between items-center mt-auto">
                                <div class="text-[10px] text-gray-500 flex items-center gap-2">
                                    <i class="fa-solid fa-circle-info text-blue-500"></i> Pastikan konfigurasi Anda benar.
                                </div>
                                <button type="submit" class="bg-primary-700 hover:bg-primary-800 text-white shadow-md rounded-xl px-6 py-3 text-sm font-bold transition-all flex items-center gap-2">
                                    <i class="fa-solid fa-download"></i> Generate & Download
                                </button>
                            </div>
                        </section>
                    </div>
                </div>
            </form>

            <!-- TAB 2: IMPORT DATA (WIZARD) -->
            <div x-show="activeTab === 'import'" x-transition.opacity class="space-y-6 max-w-4xl mx-auto" x-cloak>
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <div class="flex items-center justify-between relative max-w-2xl mx-auto">
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-200 z-0 rounded-full"></div>
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-primary-500 z-0 rounded-full transition-all duration-500" :style="'width: ' + ((importStep - 1) * 100) + '%'"></div>

                            <div class="relative z-10 flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all shadow-sm" :class="importStep >= 1 ? 'bg-primary-600 text-white border-2 border-primary-100' : 'bg-gray-100 text-gray-400'">1</div>
                                <span class="absolute top-12 text-[10px] font-bold uppercase tracking-wider text-center w-24 -ml-7" :class="importStep >= 1 ? 'text-primary-700' : 'text-gray-400'">Upload</span>
                            </div>
                            <!-- Removed intermediate check mapping step for direct static template execution -->
                            <div class="relative z-10 flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all shadow-sm" :class="importStep >= 2 ? 'bg-primary-600 text-white border-2 border-primary-100' : 'bg-gray-100 text-gray-400'">2</div>
                                <span class="absolute top-12 text-[10px] font-bold uppercase tracking-wider text-center w-24 -ml-7" :class="importStep >= 2 ? 'text-primary-700' : 'text-gray-400'">Hasil Import</span>
                            </div>
                        </div>
                        <div class="h-6"></div>
                    </div>

                    <!-- STEP 1: UPLOAD FILE -->
                    <div x-show="importStep === 1" x-transition.opacity class="p-8 sm:p-12">
                        <form action="{{ route('admin.data-exchange.import') }}" method="POST" enctype="multipart/form-data" @submit="importStatus = 'processing'">
                            @csrf
                            <div class="text-center mb-8">
                                <h2 class="text-2xl font-extrabold text-gray-900">Upload File Basis Data (Sesuai Template)</h2>
                            </div>

                            <div class="max-w-xl mx-auto space-y-6">
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Modul Tujuan Import <span class="text-red-500">*</span></label>
                                    <select name="module" x-model="importModule" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 outline-none cursor-pointer font-semibold" required>
                                        <option value="">Pilih Modul Database...</option>
                                        <option value="penduduk">Data Kependudukan Baru / Update</option>
                                    </select>
                                </div>

                                <div class="border-2 border-dashed border-gray-300 rounded-3xl p-10 text-center hover:bg-primary-50 hover:border-primary-400 transition-colors group relative">
                                    <input type="file" name="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".csv, .xlsx, .xls" required>
                                    <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                        <i class="fa-solid fa-cloud-arrow-up text-2xl"></i>
                                    </div>
                                    <h4 class="font-bold text-gray-800 text-lg mb-1">Pilih File Kesini</h4>
                                    <span class="bg-gray-100 text-gray-500 text-[10px] font-mono px-3 py-1 rounded-md uppercase tracking-widest border border-gray-200">Format: .CSV / .XLSX</span>
                                </div>

                                <div class="flex justify-between items-center text-xs">
                                    <a :href="'{{ route('admin.data-exchange.template') }}?module=' + importModule" x-show="importModule" class="font-bold text-primary-600 hover:underline flex items-center gap-1.5"><i class="fa-solid fa-download"></i> Download Template Resmi</a>
                                </div>
                            </div>

                            <div class="mt-10 flex justify-end">
                                <button type="submit" class="bg-gray-900 hover:bg-black text-white rounded-xl px-8 py-3 text-sm font-bold shadow-md transition-colors flex items-center gap-2">
                                    <span x-show="importStatus !== 'processing'">Eksekusi Import Data <i class="fa-solid fa-arrow-right"></i></span>
                                    <span x-show="importStatus === 'processing'"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Memproses...</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- STEP 2 / 3: SUCCESS STATE -->
                    <div x-show="importStep === 3" x-transition.opacity class="p-12 text-center" x-cloak>
                        @if(session('importStatusMsg'))
                            @php $msg = session('importStatusMsg') @endphp
                            <div class="w-24 h-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl shadow-sm border-4 border-white ring-4 ring-green-50"><i class="fa-solid fa-check"></i></div>
                            <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Import Selesai!</h3>
                            <p class="text-gray-500 mb-6">Berhasil mengimpor <span class="font-bold text-gray-800">{{ $msg['success'] }} baris data</span>. {{ $msg['failed'] }} baris dilewati.</p>

                            @if(count($msg['errors']) > 0)
                                <div class="bg-red-50 text-red-600 text-xs p-3 rounded-xl mb-6 mx-auto max-w-sm text-left">
                                    <span class="font-bold block mb-1">Catatan Peringatan:</span>
                                    <ul class="list-disc pl-5">
                                        @foreach($msg['errors'] as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="flex gap-4 justify-center">
                                <button @click="importStep = 1; importStatus = 'ready'" class="px-6 py-2.5 rounded-xl text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors">Import File Lain</button>
                                <button @click="activeTab = 'riwayat'" class="px-8 py-2.5 rounded-xl text-sm font-bold text-white bg-gray-900 hover:bg-black shadow-md transition-colors">Lihat Riwayat Data</button>
                            </div>
                        @else
                            <script>
                                document.addEventListener('alpine:initialized', () => {
                                    // if missing session data when state reaches 3, bounce back
                                    Alpine.data('importStep', () => 1)
                                })
                            </script>
                        @endif
                    </div>
                </div>
            </div>

            <!-- TAB 3: RIWAYAT -->
            <div x-show="activeTab === 'riwayat'" x-transition.opacity class="space-y-6" x-cloak>
                <section class="bg-white border border-gray-100 shadow-sm rounded-2xl flex flex-col overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-gray-800">Log Aktivitas Pertukaran Data</h3>
                            <p class="text-[10px] text-gray-500 mt-0.5">Catatan sistem untuk setiap aksi export dan import data massal.</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead>
                                <tr class="bg-white text-gray-500 text-[10px] uppercase tracking-wider border-b border-gray-200">
                                    <th class="p-4 font-bold">Waktu & Tanggal</th>
                                    <th class="p-4 font-bold text-center">Jenis Aksi</th>
                                    <th class="p-4 font-bold">Modul & File Info</th>
                                    <th class="p-4 font-bold text-center">Status / Ringkasan</th>
                                    <th class="p-4 font-bold">Oleh (User)</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100">
                                @forelse($logs as $log)
                                <tr class="hover:bg-gray-50 {{ $log->status == 'failed' ? 'bg-red-50/10' : '' }}">
                                    <td class="p-4 font-mono text-xs text-gray-500">{{ $log->created_at->format('d M Y, H:i') }}</td>
                                    <td class="p-4 text-center">
                                        @if($log->tipe == 'import')
                                            <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded font-bold text-[10px] uppercase border border-blue-200"><i class="fa-solid fa-file-import mr-1"></i> Import</span>
                                        @else
                                            <span class="bg-purple-50 text-purple-700 px-2 py-0.5 rounded font-bold text-[10px] uppercase border border-purple-200"><i class="fa-solid fa-file-export mr-1"></i> Export</span>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        <p class="font-bold text-gray-800">{{ ucfirst($log->modul_tujuan) }}</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $log->nama_file }}</p>
                                    </td>
                                    <td class="p-4 text-center">
                                        @if(in_array($log->status, ['success', 'partial']))
                                            <span class="text-xs font-bold text-green-600"><i class="fa-solid fa-check-circle"></i> Sukses</span>
                                            @if($log->jumlah_gagal > 0)
                                                <p class="text-[9px] text-red-500 mt-1">{{ $log->jumlah_gagal }} Data bermasalah</p>
                                            @endif
                                        @else
                                            <span class="text-xs font-bold text-red-600"><i class="fa-solid fa-xmark-circle"></i> Gagal</span>
                                            <p class="text-[9px] text-red-500 mt-1">{{ \Illuminate\Support\Str::limit($log->catatan_error, 30) }}</p>
                                        @endif
                                    </td>
                                    <td class="p-4 font-medium text-gray-700 text-xs">{{ optional($log->user)->name ?? 'System' }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="p-6 text-center text-gray-500 text-sm">Belum ada riwayat aktivitas pertukaran data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($logs->hasPages())
                        <div class="px-5 py-4 border-t border-gray-100 bg-gray-50">
                            {{ $logs->links() }}
                        </div>
                    @endif
                </section>
            </div>
            
            <footer class="text-center py-6">
                <p class="text-[10px] text-gray-400 font-medium">Modul Sistem Informasi Desa &copy; 2026. Pertukaran data dijaga enkripsi sistem.</p>
            </footer>
        </div>
    </main>
</div>
@endsection
