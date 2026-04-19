{{-- Real-Time Attendance Table --}}
<section class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden flex flex-col">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50/80 text-gray-500 text-[11px] uppercase tracking-wider border-b border-gray-200">
                    <th class="p-4 w-12 text-center">
                        <input type="checkbox" class="custom-checkbox inline-block" x-model="selectAll"
                            @change="selectedRows = selectAll ? [{{ $pegawai->pluck('id')->join(',') }}] : []">
                    </th>
                    <th class="p-4 font-semibold">Pegawai & Jabatan</th>
                    <th class="p-4 font-semibold text-center">Status Hari Ini</th>
                    <th class="p-4 font-semibold text-center">Jam Masuk (In)</th>
                    <th class="p-4 font-semibold text-center">Jam Pulang (Out)</th>
                    <th class="p-4 font-semibold text-center">Metode Log</th>
                    <th class="p-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                @forelse ($pegawai as $user)
                    @php
                        $presensi = $user->presensiHariIni;
                        $isLive = ($presensi->status === 'hadir' || $presensi->status === 'terlambat') && !$presensi->waktu_pulang;
                        
                        // Set colors
                        $rowColor = match($presensi->status) {
                            'terlambat' => 'bg-amber-50/10 hover:bg-amber-50/20',
                            'alpha'     => 'bg-red-50/5 hover:bg-red-50/10',
                            default     => 'hover:bg-gray-50',
                        };
                        
                        $badgeColor = match($presensi->status) {
                            'hadir'     => 'bg-green-50 text-green-700 border-green-200',
                            'terlambat' => 'bg-amber-100 text-amber-800 border-amber-200',
                            'sakit', 'izin', 'dinas' => 'bg-blue-50 text-blue-700 border-blue-200',
                            default     => 'bg-red-50 text-red-700 border-red-200',
                        };
                        
                        $iconClass = match($presensi->status) {
                            'hadir'     => 'fa-check',
                            'terlambat' => 'fa-clock',
                            'sakit'     => 'fa-bed',
                            'izin'      => 'fa-calendar-minus',
                            'dinas'     => 'fa-car-side',
                            default     => 'fa-user-xmark',
                        };
                    @endphp
                    
                    <tr class="transition-colors {{ $rowColor }} {{ $presensi->status == 'alpha' ? 'opacity-80' : '' }}" :class="selectedRows.includes({{ $user->id }}) ? 'bg-green-50/30' : ''">
                        <td class="p-4 text-center">
                            <input type="checkbox" class="custom-checkbox inline-block" value="{{ $user->id }}" x-model="selectedRows">
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="relative {{ $presensi->status == 'alpha' ? 'opacity-60' : '' }}">
                                    @if($user->avatarUrl())
                                        <img src="{{ $user->avatarUrl() }}" class="w-10 h-10 rounded-full shadow-sm" alt="Avatar">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-lg">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                    @endif
                                    
                                    @if($isLive)
                                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full animate-pulse" title="Masih di Kantor (Active)"></span>
                                    @elseif($presensi->waktu_pulang)
                                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-gray-400 border-2 border-white rounded-full" title="Sudah Pulang"></span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 leading-tight">{{ $user->name }}</p>
                                    <p class="text-[10px] text-gray-500 mt-0.5 font-mono">NIP: {{ $user->nip ?? '-' }}</p>
                                    <p class="text-[10px] font-semibold text-green-700 mt-0.5">{{ $user->jabatan ?? $user->roleName() }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center gap-1.5 {{ $badgeColor }} text-[10px] font-bold px-2.5 py-1 rounded border uppercase tracking-wide">
                                <i class="fa-solid {{ $iconClass }}"></i> {{ $presensi->statusLabel() }}
                            </span>
                        </td>
                        <td class="p-4 text-center {{ $presensi->status == 'terlambat' ? 'text-red-600' : 'text-gray-800' }}">
                            <span class="text-xs font-bold">{{ $presensi->formatMasuk() }}</span>
                        </td>
                        <td class="p-4 text-center text-gray-800">
                            @if($presensi->status === 'hadir' || $presensi->status === 'terlambat')
                                @if($presensi->waktu_pulang)
                                    <span class="text-xs font-bold">{{ $presensi->formatPulang() }}</span>
                                @else
                                    <span class="text-xs font-semibold text-gray-400 italic">Belum Pulang</span>
                                @endif
                            @else
                                <span class="text-xs font-bold text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            @if($presensi->metode_masuk || $presensi->metode_pulang)
                                @php
                                    // Sederhanakan untuk UI
                                    $metode = $presensi->metode_masuk ?? $presensi->metode_pulang;
                                @endphp
                                @if($metode === 'manual')
                                    <div class="inline-flex flex-col items-center justify-center text-blue-500" title="Diinput Manual">
                                        <i class="fa-solid fa-keyboard text-sm"></i>
                                        <span class="text-[8px] font-bold mt-1 uppercase">Manual</span>
                                    </div>
                                @elseif($metode === 'kiosk')
                                    <div class="inline-flex flex-col items-center justify-center text-gray-500">
                                        <i class="fa-solid fa-desktop text-sm"></i>
                                        <span class="text-[8px] font-bold mt-1 uppercase">Kiosk NFC</span>
                                    </div>
                                @elseif($metode === 'face_capture')
                                    <div class="inline-flex flex-col items-center justify-center text-gray-500">
                                        <i class="fa-solid fa-camera text-sm"></i>
                                        <span class="text-[8px] font-bold mt-1 uppercase">Face Capture</span>
                                    </div>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="openDrawer({{ $user->id }})"
                                    class="w-8 h-8 rounded-lg bg-white text-gray-500 hover:text-green-600 hover:bg-green-50 flex items-center justify-center transition-colors border border-gray-200 shadow-sm cursor-pointer"
                                    title="Log & Detail"><i class="fa-solid fa-eye text-xs"></i></button>
                                
                                <button @click="openKoreksi({{ $user->id }})"
                                    class="w-8 h-8 rounded-lg bg-white text-gray-500 hover:text-amber-600 hover:bg-amber-50 flex items-center justify-center transition-colors border border-gray-200 shadow-sm cursor-pointer"
                                    title="Koreksi Manual"><i class="fa-solid fa-pen text-xs"></i></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-gray-500">
                            Tidak ada data aparatur.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer Summary --}}
    <div class="p-4 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-sm text-gray-500 font-medium">Menampilkan <span class="font-bold text-gray-900">{{ $pegawai->count() }}</span> Pegawai</p>
    </div>
</section>
