{{-- Active Queue Table --}}
<section class="bg-white border border-gray-100 shadow-sm rounded-2xl flex flex-col overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
        <div>
            <h3 class="font-bold text-gray-800">Antrian Surat Aktif</h3>
            <p class="text-xs text-gray-500 mt-0.5">Daftar surat yang sedang diproses saat ini berdasarkan prioritas SLA.</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="p-4 font-semibold">Tiket & Pemohon</th>
                    <th class="p-4 font-semibold">Jenis Layanan</th>
                    <th class="p-4 font-semibold text-center">Prioritas</th>
                    <th class="p-4 font-semibold">Status / Posisi</th>
                    <th class="p-4 font-semibold text-center">Estimasi SLA</th>
                    <th class="p-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                @forelse ($antrian as $s)
                    @php
                        $sBadge = $s->statusBadge();
                        $pBadge = $s->prioritasBadge();
                        $sla    = $s->slaInfo();
                    @endphp
                    <tr class="transition-colors {{ $sla['overdue'] ? 'hover:bg-red-50/30 bg-red-50/10' : 'hover:bg-gray-50' }}">
                        <td class="p-4">
                            <div class="font-bold text-green-700 font-mono text-xs mb-1">{{ $s->nomor_tiket }}</div>
                            <div class="font-semibold text-gray-900">{{ $s->penduduk?->nama ?? '-' }}</div>
                        </td>
                        <td class="p-4 text-gray-600">{{ $s->jenisShort() }}</td>
                        <td class="p-4 text-center">
                            <span class="{{ $pBadge['bg'] }} {{ $pBadge['text'] }} text-[10px] font-bold px-2 py-0.5 rounded border {{ $pBadge['border'] }} uppercase">
                                {{ $pBadge['label'] }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="text-xs font-semibold {{ $sBadge['text'] }} flex items-center gap-1.5">
                                <i class="fa-solid {{ $sBadge['icon'] }}"></i> {{ $sBadge['label'] }}
                            </div>
                        </td>
                        <td class="p-4 text-center font-semibold text-xs {{ $sla['overdue'] ? 'text-red-600' : 'text-gray-500' }}">
                            {{ $sla['label'] }}
                        </td>
                        <td class="p-4 text-center">
                            <button type="button"
                                @click="openStatusModal({{ $s->id }}, '{{ $s->nomor_tiket }}', '{{ $s->status }}')"
                                class="bg-white border border-gray-200 text-gray-600 hover:text-green-600 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors shadow-sm cursor-pointer">
                                Proses
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400">
                            <i class="fa-solid fa-inbox text-3xl mb-2 block"></i>
                            <p class="font-semibold">Tidak ada surat dalam antrian.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
