<?php

namespace Database\Seeders;

use App\Models\Penduduk;
use App\Models\PenerimaBansos;
use App\Models\ProgramBansos;
use Illuminate\Database\Seeder;

class BansosSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Programs ────────────────────────────────────────────
        $blt = ProgramBansos::create([
            'nama'       => 'BLT Dana Desa',
            'deskripsi'  => 'Bantuan Tunai Langsung (Tahap 2)',
            'icon'       => 'fa-hand-holding-dollar',
            'icon_bg'    => 'bg-blue-50',
            'icon_color' => 'text-blue-600',
            'periode'    => 'Q2 2026',
            'status'     => 'aktif',
        ]);

        $bpnt = ProgramBansos::create([
            'nama'       => 'BPNT (Sembako)',
            'deskripsi'  => 'Bantuan Pangan Non Tunai Kemensos',
            'icon'       => 'fa-box-open',
            'icon_bg'    => 'bg-amber-50',
            'icon_color' => 'text-amber-600',
            'periode'    => 'Tahunan',
            'status'     => 'aktif',
        ]);

        $rutilahu = ProgramBansos::create([
            'nama'       => 'Rutilahu',
            'deskripsi'  => 'Renovasi Rumah Tidak Layak Huni',
            'icon'       => 'fa-house-chimney-crack',
            'icon_bg'    => 'bg-purple-50',
            'icon_color' => 'text-purple-600',
            'periode'    => '2025',
            'status'     => 'selesai',
        ]);

        // ─── Recipients ──────────────────────────────────────────
        $pendudukList = Penduduk::where('status', 'hidup')->limit(9)->get();

        if ($pendudukList->count() < 3) return;

        // BLT recipients
        foreach ($pendudukList->take(5) as $idx => $penduduk) {
            PenerimaBansos::create([
                'penduduk_id'       => $penduduk->id,
                'program_bansos_id' => $blt->id,
                'tahap'             => 'Tahap 2 (April)',
                'desil'             => $idx < 2 ? 1 : 2,
                'status_distribusi' => $idx === 0 ? 'diterima' : ($idx === 1 ? 'siap_diambil' : 'pending'),
                'tanggal_distribusi'=> $idx === 0 ? now()->subDays(1) : null,
            ]);
        }

        // BPNT recipients
        foreach ($pendudukList->slice(2, 4) as $idx => $penduduk) {
            PenerimaBansos::create([
                'penduduk_id'       => $penduduk->id,
                'program_bansos_id' => $bpnt->id,
                'tahap'             => 'Tahap 1',
                'desil'             => 2,
                'status_distribusi' => $idx === 0 ? 'diterima' : 'pending',
                'tanggal_distribusi'=> $idx === 0 ? now()->subDays(5) : null,
            ]);
        }

        // One flagged duplicate
        $dupPenduduk = $pendudukList->get(2); // Same person in BLT + another BLT entry
        if ($dupPenduduk) {
            PenerimaBansos::create([
                'penduduk_id'        => $dupPenduduk->id,
                'program_bansos_id'  => $blt->id,
                'tahap'              => 'Tahap 1 (Maret)',
                'desil'              => 1,
                'status_distribusi'  => 'tertahan',
                'is_duplikat'        => true,
                'catatan_audit'      => 'Auto-flagged: Penerima sudah terdaftar di BLT DD Tahap 2. Kemungkinan duplikasi data.',
            ]);
        }

        // Rutilahu (completed)
        foreach ($pendudukList->slice(6, 2) as $penduduk) {
            PenerimaBansos::create([
                'penduduk_id'       => $penduduk->id,
                'program_bansos_id' => $rutilahu->id,
                'tahap'             => null,
                'desil'             => 1,
                'status_distribusi' => 'diterima',
                'tanggal_distribusi'=> now()->subMonths(3),
            ]);
        }
    }
}
