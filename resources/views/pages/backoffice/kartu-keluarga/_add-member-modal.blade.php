{{-- Add Member to KK Modal --}}
<div x-show="addMemberModalOpen" class="fixed inset-0 z-[130] flex items-center justify-center p-4" x-cloak>
    <div x-show="addMemberModalOpen" x-transition.opacity class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
         @click="addMemberModalOpen = false"></div>

    <div x-show="addMemberModalOpen" x-transition
         class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col">

        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-extrabold text-lg text-gray-900">Tambahkan Anggota (Assign Relasi)</h3>
        </div>

        <div class="p-6">
            <form :action="`{{ url('admin/kartu-keluarga') }}/${detail?.id}/add-member`"
                  method="POST" class="space-y-4" id="addMemberForm">
                @csrf

                {{-- Search unlinked penduduk --}}
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Pilih Penduduk (Tanpa KK)</label>
                    <select name="penduduk_id" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer" required>
                        <option value="">-- Pilih Penduduk --</option>
                        @php
                            $unlinkedAll = \App\Models\Penduduk::whereNull('kartu_keluarga_id')
                                ->where('status', 'hidup')
                                ->orderBy('nama')
                                ->get(['id', 'nik', 'nama']);
                        @endphp
                        @foreach ($unlinkedAll as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->nik }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Relation --}}
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700">Pilih Status Relasi</label>
                    <select name="status_hubungan" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none cursor-pointer" required>
                        @foreach (['Istri', 'Anak', 'Menantu', 'Cucu', 'Orang Tua', 'Mertua', 'Famili Lain'] as $rel)
                            <option value="{{ $rel }}">{{ $rel }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-2">
            <button @click="addMemberModalOpen = false"
                class="px-4 py-2 rounded-lg text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors cursor-pointer">Batal</button>
            <button type="submit" form="addMemberForm"
                class="px-4 py-2 rounded-lg text-sm font-bold text-white bg-green-600 hover:bg-green-700 transition-colors cursor-pointer">Tambahkan</button>
        </div>
    </div>
</div>
