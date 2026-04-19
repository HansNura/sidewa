<?php

namespace Database\Seeders;

use App\Models\Penduduk;
use App\Models\SuratPermohonan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LayananSuratSeeder extends Seeder
{
    public function run(): void
    {
        $pendudukList = Penduduk::where('status', 'hidup')->get();
        if ($pendudukList->count() < 6) return;

        $count = $pendudukList->count();
        $p = fn (int $i) => $pendudukList->get($i % $count);

        $operator = User::where('role', 'operator')->first();
        $operatorId = $operator?->id;

        // ─── Completed letters (various types, past dates) ───────
        $completedData = [
            ['idx' => 0, 'jenis' => 'sktm',            'prio' => 'normal', 'days_ago' => 15, 'hours' => 3],
            ['idx' => 1, 'jenis' => 'domisili',         'prio' => 'normal', 'days_ago' => 12, 'hours' => 5],
            ['idx' => 2, 'jenis' => 'pengantar_usaha',  'prio' => 'normal', 'days_ago' => 10, 'hours' => 2],
            ['idx' => 3, 'jenis' => 'sktm',            'prio' => 'normal', 'days_ago' => 8,  'hours' => 6],
            ['idx' => 4, 'jenis' => 'kematian',         'prio' => 'tinggi', 'days_ago' => 7,  'hours' => 1],
            ['idx' => 5, 'jenis' => 'domisili',         'prio' => 'normal', 'days_ago' => 5,  'hours' => 4],
            ['idx' => 0, 'jenis' => 'pengantar_nikah',  'prio' => 'normal', 'days_ago' => 4,  'hours' => 8],
            ['idx' => 1, 'jenis' => 'sktm',            'prio' => 'normal', 'days_ago' => 3,  'hours' => 3],
            ['idx' => 2, 'jenis' => 'pengantar_usaha',  'prio' => 'normal', 'days_ago' => 2,  'hours' => 5],
            ['idx' => 3, 'jenis' => 'pindah',           'prio' => 'normal', 'days_ago' => 1,  'hours' => 10],
        ];

        foreach ($completedData as $i => $d) {
            $pengajuan = Carbon::now()->subDays($d['days_ago'])->setTime(8, 0);
            $selesai = $pengajuan->copy()->addHours($d['hours']);

            SuratPermohonan::create([
                'nomor_tiket'       => sprintf('#TKT-%s-%03d', $pengajuan->format('ymd'), $i + 1),
                'penduduk_id'       => $p($d['idx'])->id,
                'operator_id'       => $operatorId,
                'jenis_surat'       => $d['jenis'],
                'prioritas'         => $d['prio'],
                'status'            => 'selesai',
                'tanggal_pengajuan' => $pengajuan,
                'tanggal_selesai'   => $selesai,
                'created_at'        => $pengajuan,
                'updated_at'        => $selesai,
            ]);
        }

        // ─── Active letters (in queue) ────────────────────────────
        $today = Carbon::today()->format('ymd');

        // 1. Urgent, overdue (verifikasi, > 24h old)
        SuratPermohonan::create([
            'nomor_tiket'       => "#TKT-{$today}-001",
            'penduduk_id'       => $p(4)->id,
            'jenis_surat'       => 'pengantar_usaha',
            'prioritas'         => 'tinggi',
            'status'            => 'verifikasi',
            'tanggal_pengajuan' => Carbon::now()->subHours(26),
            'created_at'        => Carbon::now()->subHours(26),
            'updated_at'        => Carbon::now()->subHours(2),
        ]);

        // 2. Menunggu TTE
        SuratPermohonan::create([
            'nomor_tiket'       => "#TKT-{$today}-002",
            'penduduk_id'       => $p(5)->id,
            'operator_id'       => $operatorId,
            'jenis_surat'       => 'sktm',
            'prioritas'         => 'normal',
            'status'            => 'menunggu_tte',
            'tanggal_pengajuan' => Carbon::now()->subHours(6),
            'created_at'        => Carbon::now()->subHours(6),
            'updated_at'        => Carbon::now()->subHours(1),
        ]);

        // 3. Pengajuan baru
        SuratPermohonan::create([
            'nomor_tiket'       => "#TKT-{$today}-003",
            'penduduk_id'       => $p(3)->id,
            'jenis_surat'       => 'domisili',
            'prioritas'         => 'normal',
            'status'            => 'pengajuan',
            'tanggal_pengajuan' => Carbon::now()->subHours(1),
            'created_at'        => Carbon::now()->subHours(1),
            'updated_at'        => Carbon::now()->subHours(1),
        ]);

        // 4. Menunggu TTE (urgent)
        SuratPermohonan::create([
            'nomor_tiket'       => "#TKT-{$today}-004",
            'penduduk_id'       => $p(2)->id,
            'operator_id'       => $operatorId,
            'jenis_surat'       => 'kematian',
            'prioritas'         => 'tinggi',
            'status'            => 'menunggu_tte',
            'tanggal_pengajuan' => Carbon::now()->subHours(20),
            'created_at'        => Carbon::now()->subHours(20),
            'updated_at'        => Carbon::now()->subHours(3),
        ]);

        // 5. Rejected
        SuratPermohonan::create([
            'nomor_tiket'       => "#TKT-{$today}-005",
            'penduduk_id'       => $p(1)->id,
            'jenis_surat'       => 'pindah',
            'prioritas'         => 'normal',
            'status'            => 'ditolak',
            'alasan_tolak'      => 'Berkas lampiran buram/tidak terbaca. Harap scan ulang.',
            'tanggal_pengajuan' => Carbon::now()->subDays(1),
            'created_at'        => Carbon::now()->subDays(1),
            'updated_at'        => Carbon::now()->subHours(8),
        ]);
    }
}
