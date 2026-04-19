@extends('layouts.backoffice')

@section('title', ($template ? 'Edit ' . $template->nama : 'Buat Template Baru') . ' - Panel Administrasi')

@section('content')

{{-- Full-Screen Editor Overlay (rendered as full-page, extends backoffice but takes over viewport) --}}
<div x-data="{
        editorTab: 'metadata',
        isSaving: false,
        saveMessage: '',

        // ── Form State ──────────────────────────────────
        formData: {
            nama: '{{ $template->nama ?? '' }}',
            kode: '{{ $template->kode ?? '' }}',
            kategori: '{{ $template->kategori ?? 'keterangan' }}',
            deskripsi: '{{ $template->deskripsi ?? '' }}',
            body_template: {{ Js::from($template->body_template ?? 'Yang bertanda tangan di bawah ini ' . ($village->jabatan_kades ?? 'Kepala Desa') . ' ' . ($village->nama_desa ?? '') . ', Kecamatan ' . ($village->kecamatan ?? '') . ', Kabupaten ' . ($village->kabupaten ?? '') . ', menerangkan dengan sebenarnya bahwa:\n\nNama: {{nama_pemohon}}\nNIK: {{nik_pemohon}}\n\nAdalah benar warga kami yang berdomisili di alamat tersebut.') }},
            is_active: {{ ($template->is_active ?? true) ? 'true' : 'false' }},
            layout_settings: {{ Js::from($template ? $template->resolvedLayout() : ['show_kop' => true, 'show_ttd' => true, 'show_qr' => true, 'margin_top' => 3, 'margin_bottom' => 3, 'margin_left' => 3, 'margin_right' => 2.5]) }},
        },

        version: '{{ $template->versi ?? 'v1.0' }}',
        isNew: {{ $template ? 'false' : 'true' }},

        // ── Insert field placeholder into editor ────────
        insertField(placeholder) {
            this.formData.body_template += ' ' + placeholder;
            // Focus the editor textarea
            this.$nextTick(() => {
                const el = document.getElementById('editor-textarea');
                if (el) { el.focus(); el.scrollTop = el.scrollHeight; }
            });
        },

        // ── Generate kode from nama ─────────────────────
        generateKode() {
            if (this.isNew) {
                this.formData.kode = this.formData.nama
                    .toLowerCase()
                    .replace(/\s+/g, '_')
                    .replace(/[^a-z0-9_]/g, '')
                    .substring(0, 50);
            }
        },

        // ── Highlighted body for preview ────────────────
        get highlightedBody() {
            return (this.formData.body_template || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/\{\{(\w+)\}\}/g, '<span class=\'bg-yellow-200 px-1 border border-yellow-400 border-dashed rounded font-mono text-xs\'>{{$1}}</span>')
                .replace(/\n/g, '<br>');
        },

        // ── Save Template ───────────────────────────────
        async saveTemplate() {
            this.isSaving = true;
            this.saveMessage = '';

            const url = this.isNew
                ? '{{ route('admin.template-surat.store') }}'
                : '{{ $template ? route('admin.template-surat.update', $template) : '' }}';

            const method = this.isNew ? 'POST' : 'PUT';

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(this.formData)
                });

                if (res.status === 422) {
                    const err = await res.json();
                    const msgs = Object.values(err.errors || {}).flat();
                    this.saveMessage = '❌ ' + msgs.join(', ');
                    this.isSaving = false;
                    return;
                }

                const data = await res.json();
                if (data.success) {
                    if (data.version) this.version = data.version;
                    this.saveMessage = '✅ ' + data.message;
                    if (this.isNew && data.id) {
                        // Redirect to edit mode after first save
                        setTimeout(() => {
                            window.location.href = '{{ url('admin/template-surat') }}/' + data.id + '/edit';
                        }, 1000);
                    }
                }
            } catch (e) {
                this.saveMessage = '❌ Terjadi kesalahan jaringan.';
            }
            this.isSaving = false;
        }
     }"
     class="-m-4 sm:-m-6 lg:-m-8 flex flex-col bg-gray-100"
     style="min-height: calc(100vh - 4rem);">

    <!-- Editor Header -->
    <header class="h-16 bg-white border-b border-gray-200 px-4 sm:px-6 flex items-center justify-between shrink-0 shadow-sm z-20">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.template-surat.index') }}"
                class="text-gray-500 hover:text-red-600 transition-colors flex items-center gap-2 font-semibold text-sm bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                <i class="fa-solid fa-arrow-left"></i> Tutup
            </a>
            <div class="h-6 w-px bg-gray-300 hidden sm:block"></div>
            <div class="hidden sm:block">
                <div class="flex items-center gap-2">
                    <h2 class="font-extrabold text-gray-900 text-lg leading-none">Editor Template</h2>
                    <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded border border-blue-200"
                          x-text="version">{{ $template->versi ?? 'v1.0' }}</span>
                </div>
                <p class="text-[10px] text-gray-500 mt-1" x-show="saveMessage">
                    <span x-text="saveMessage"></span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button @click="saveTemplate()" :disabled="isSaving"
                class="bg-green-700 hover:bg-green-800 text-white shadow-md rounded-xl px-5 py-2 text-sm font-bold transition-all flex items-center gap-2 cursor-pointer disabled:opacity-60">
                <i class="fa-solid fa-spinner fa-spin" x-show="isSaving" x-cloak></i>
                <i class="fa-solid fa-save" x-show="!isSaving"></i>
                <span x-text="isSaving ? 'Menyimpan...' : 'Simpan & Publikasi'"></span>
            </button>
        </div>
    </header>

    <!-- Editor Workspace Layout (3 Columns) -->
    <div class="flex-1 flex overflow-hidden">

        <!-- LEFT: SETTINGS PANEL -->
        <div class="w-80 bg-white border-r border-gray-200 flex flex-col shrink-0 z-10 shadow-[4px_0_10px_-5px_rgba(0,0,0,0.05)]">
            <!-- Tabs -->
            <div class="flex border-b border-gray-200 bg-gray-50 p-2 gap-1 shrink-0">
                <button @click="editorTab = 'metadata'"
                    :class="editorTab === 'metadata' ? 'bg-white shadow-sm text-green-600 font-bold' : 'text-gray-500 hover:bg-gray-200'"
                    class="flex-1 py-1.5 text-xs rounded-lg transition-all cursor-pointer">Metadata</button>
                <button @click="editorTab = 'fields'"
                    :class="editorTab === 'fields' ? 'bg-white shadow-sm text-green-600 font-bold' : 'text-gray-500 hover:bg-gray-200'"
                    class="flex-1 py-1.5 text-xs rounded-lg transition-all cursor-pointer">Field Data</button>
                <button @click="editorTab = 'layout'"
                    :class="editorTab === 'layout' ? 'bg-white shadow-sm text-green-600 font-bold' : 'text-gray-500 hover:bg-gray-200'"
                    class="flex-1 py-1.5 text-xs rounded-lg transition-all cursor-pointer">Layout</button>
            </div>

            {{-- Tab: Metadata --}}
            <div x-show="editorTab === 'metadata'" class="p-5 overflow-y-auto custom-scrollbar flex-1 space-y-4">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Nama Template <span class="text-red-500">*</span></label>
                    <input type="text" x-model="formData.nama" @input="generateKode()"
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Kode Unik <span class="text-red-500">*</span></label>
                    <input type="text" x-model="formData.kode" :disabled="!isNew"
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:ring-2 focus:ring-green-500 outline-none disabled:opacity-50"
                        placeholder="auto_generate">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Kategori Surat</label>
                    <select x-model="formData.kategori"
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer">
                        @foreach ($kategoris as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Deskripsi Singkat</label>
                    <textarea rows="3" x-model="formData.deskripsi"
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none resize-none"
                        placeholder="Keterangan singkat tentang template ini..."></textarea>
                </div>
                <div class="space-y-1">
                    <label class="flex items-center justify-between cursor-pointer">
                        <span class="text-xs font-bold text-gray-700">Status Aktif</span>
                        <div class="relative">
                            <input type="checkbox" class="sr-only peer" x-model="formData.is_active">
                            <div class="w-10 h-6 bg-gray-300 peer-checked:bg-green-600 rounded-full transition-colors after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:w-4 after:h-4 after:rounded-full after:transition-transform peer-checked:after:translate-x-4"></div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Tab: Dynamic Fields --}}
            <div x-show="editorTab === 'fields'" class="p-5 overflow-y-auto custom-scrollbar flex-1 space-y-4" x-cloak>
                <div class="bg-blue-50 border border-blue-200 p-3 rounded-lg text-[10px] text-blue-800 leading-relaxed">
                    Klik pada blok field di bawah untuk menyisipkannya ke dalam editor dokumen (sebagai placeholder).
                </div>

                {{-- System Fields --}}
                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Field Penduduk (Auto-fill)</h4>
                    <div class="space-y-2">
                        @foreach ($systemFields as $placeholder => $label)
                            <button @click="insertField('{{ $placeholder }}')"
                                class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs font-mono font-bold text-gray-700 hover:border-green-500 hover:text-green-600 cursor-pointer shadow-sm flex items-center justify-between group text-left transition-colors">
                                <div>
                                    <span>{{ $placeholder }}</span>
                                    <span class="font-sans font-normal text-gray-400 text-[10px] block">{{ $label }}</span>
                                </div>
                                <i class="fa-solid fa-plus opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Manual Fields --}}
                <div class="pt-2 border-t border-gray-100">
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Field Tambahan (Manual)</h4>
                    <div class="space-y-2">
                        @foreach ($manualFields as $placeholder => $label)
                            <button @click="insertField('{{ $placeholder }}')"
                                class="w-full bg-white border border-amber-200 rounded-lg p-2 text-xs font-mono font-bold text-amber-700 hover:border-green-500 hover:text-green-600 cursor-pointer shadow-sm flex items-center justify-between group text-left transition-colors">
                                <div>
                                    <span>{{ $placeholder }}</span>
                                    <span class="font-sans font-normal text-gray-400 text-[10px] block">{{ $label }}</span>
                                </div>
                                <i class="fa-solid fa-plus opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Tab: Layout --}}
            <div x-show="editorTab === 'layout'" class="p-5 overflow-y-auto custom-scrollbar flex-1 space-y-5" x-cloak>
                <div>
                    <h4 class="text-xs font-bold text-gray-800 mb-3">Tampilkan Elemen Kertas</h4>
                    <div class="space-y-3">
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-gray-600 font-medium">Kop Surat Resmi (Header)</span>
                            <div class="relative">
                                <input type="checkbox" class="sr-only peer" x-model="formData.layout_settings.show_kop">
                                <div class="w-8 h-5 bg-gray-300 peer-checked:bg-green-600 rounded-full transition-colors after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:w-3 after:h-3 after:rounded-full after:transition-transform peer-checked:after:translate-x-3"></div>
                            </div>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-gray-600 font-medium">Blok Tanda Tangan (TTE)</span>
                            <div class="relative">
                                <input type="checkbox" class="sr-only peer" x-model="formData.layout_settings.show_ttd">
                                <div class="w-8 h-5 bg-gray-300 peer-checked:bg-green-600 rounded-full transition-colors after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:w-3 after:h-3 after:rounded-full after:transition-transform peer-checked:after:translate-x-3"></div>
                            </div>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-gray-600 font-medium">QR Code Validasi Footer</span>
                            <div class="relative">
                                <input type="checkbox" class="sr-only peer" x-model="formData.layout_settings.show_qr">
                                <div class="w-8 h-5 bg-gray-300 peer-checked:bg-green-600 rounded-full transition-colors after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:w-3 after:h-3 after:rounded-full after:transition-transform peer-checked:after:translate-x-3"></div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <h4 class="text-xs font-bold text-gray-800 mb-3">Margin Kertas (cm)</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center border border-gray-300 rounded px-2 bg-white">
                            <span class="text-[10px] text-gray-400 w-8 shrink-0">Top</span>
                            <input type="number" step="0.5" x-model.number="formData.layout_settings.margin_top"
                                class="w-full text-sm text-center py-1.5 outline-none">
                        </div>
                        <div class="flex items-center border border-gray-300 rounded px-2 bg-white">
                            <span class="text-[10px] text-gray-400 w-8 shrink-0">Btm</span>
                            <input type="number" step="0.5" x-model.number="formData.layout_settings.margin_bottom"
                                class="w-full text-sm text-center py-1.5 outline-none">
                        </div>
                        <div class="flex items-center border border-gray-300 rounded px-2 bg-white">
                            <span class="text-[10px] text-gray-400 w-8 shrink-0">Lft</span>
                            <input type="number" step="0.5" x-model.number="formData.layout_settings.margin_left"
                                class="w-full text-sm text-center py-1.5 outline-none">
                        </div>
                        <div class="flex items-center border border-gray-300 rounded px-2 bg-white">
                            <span class="text-[10px] text-gray-400 w-8 shrink-0">Rgt</span>
                            <input type="number" step="0.5" x-model.number="formData.layout_settings.margin_right"
                                class="w-full text-sm text-center py-1.5 outline-none">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CENTER: TEXT EDITOR -->
        <div class="flex-1 flex flex-col bg-gray-50 border-r border-gray-300 z-0">
            {{-- Toolbar --}}
            <div class="h-12 bg-white border-b border-gray-200 px-4 flex items-center gap-1 shrink-0 shadow-sm overflow-x-auto">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mr-2">Body Template</span>
                <div class="w-px h-6 bg-gray-300 mx-1"></div>
                <span class="text-[10px] text-gray-500">
                    <i class="fa-solid fa-info-circle mr-1"></i>
                    Gunakan <code class="bg-gray-100 px-1 rounded">@{{placeholder}}</code> untuk field dinamis
                </span>
            </div>

            {{-- Textarea Editor --}}
            <div class="flex-1 p-6 overflow-y-auto custom-scrollbar flex justify-center">
                <textarea id="editor-textarea" x-model="formData.body_template"
                    class="w-full max-w-2xl bg-white border border-gray-300 shadow-sm p-8 font-serif text-[15px] leading-relaxed outline-none resize-none rounded-sm focus:border-green-400 focus:ring-1 focus:ring-green-200"
                    style="min-height: 600px;"
                    placeholder="Ketik isi template surat di sini...

Gunakan {{nama_pemohon}}, {{nik_pemohon}}, dll. untuk placeholder yang akan diisi otomatis."></textarea>
            </div>
        </div>

        <!-- RIGHT: LIVE PREVIEW -->
        <div class="w-96 bg-gray-800 flex flex-col shrink-0 z-0 hidden xl:flex">
            <div class="h-10 bg-gray-900 border-b border-gray-700 px-4 flex items-center justify-between shrink-0">
                <span class="text-xs font-bold text-gray-300"><i class="fa-solid fa-eye text-green-500 mr-1"></i> Live Preview (A4)</span>
            </div>

            {{-- Scaled Preview --}}
            <div class="flex-1 overflow-auto p-4 flex justify-center custom-scrollbar">
                <div class="transform scale-[0.4] origin-top">
                    <div class="print-paper bg-white w-[794px] min-h-[1123px] mx-auto font-serif text-black relative"
                         :style="'padding: ' + formData.layout_settings.margin_top + 'cm ' + formData.layout_settings.margin_right + 'cm ' + formData.layout_settings.margin_bottom + 'cm ' + formData.layout_settings.margin_left + 'cm'">

                        {{-- KOP --}}
                        <div x-show="formData.layout_settings.show_kop" class="flex items-start gap-4 border-b-4 border-double border-black pb-4 mb-6">
                            <div class="w-20 h-24 shrink-0 flex items-center justify-center">
                                <img src="{{ $village->logoUrl() }}" class="max-h-full opacity-80" alt="Logo">
                            </div>
                            <div class="flex-1 text-center pr-10">
                                <h2 class="text-lg font-bold uppercase tracking-wide leading-tight">Pemerintah Kabupaten {{ $village->kabupaten ?? '' }}</h2>
                                <h2 class="text-lg font-bold uppercase tracking-wide leading-tight">Kecamatan {{ $village->kecamatan ?? '' }}</h2>
                                <h1 class="text-2xl font-extrabold uppercase tracking-wider leading-tight mt-1">{{ $village->fullName() }}</h1>
                                <p class="text-sm mt-2">{{ $village->alamat ?? '' }}, Kode Pos {{ $village->kode_pos ?? '' }}</p>
                            </div>
                        </div>

                        {{-- Judul --}}
                        <div class="text-center mb-6">
                            <h3 class="text-xl font-bold uppercase underline" x-text="formData.nama || 'JUDUL SURAT'"></h3>
                        </div>

                        {{-- Body --}}
                        <div class="whitespace-pre-wrap leading-relaxed text-[15px]"
                             x-html="highlightedBody"></div>

                        {{-- TTD --}}
                        <div x-show="formData.layout_settings.show_ttd" class="mt-20 flex justify-end">
                            <div class="w-64 text-center">
                                <p class="mb-1">{{ $village->nama_desa ?? '' }}, ........................ 20...</p>
                                <p class="font-bold mb-20">{{ $village->jabatan_kades ?? 'Kepala Desa' }} {{ $village->nama_desa ?? '' }}</p>
                                <p class="font-bold underline uppercase">{{ $village->nama_kades ?? '...........................' }}</p>
                            </div>
                        </div>

                        {{-- QR --}}
                        <div x-show="formData.layout_settings.show_qr" class="absolute bottom-8 left-8 flex items-center gap-2 opacity-30">
                            <i class="fa-solid fa-qrcode text-3xl text-gray-400"></i>
                            <span class="text-[8px] text-gray-400 font-mono leading-tight">QR Validasi<br>Digital</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('styles')
<style>
    .print-paper {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush
