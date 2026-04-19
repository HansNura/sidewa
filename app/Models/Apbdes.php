<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apbdes extends Model
{
    use HasFactory;

    protected $table = 'apbdes';

    protected $fillable = [
        'tahun',
        'tipe_anggaran',
        'kode_rekening',
        'nama_kegiatan',
        'pagu_anggaran',
        'sumber_dana',
        'is_published',
    ];

    /**
     * Scope untuk filter tahun
     */
    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    /**
     * Helper formatting rupiah (contoh: Rp 1.500.000)
     */
    public function getFormatRupiahAttribute()
    {
        return 'Rp ' . number_format($this->pagu_anggaran, 0, ',', '.');
    }

    /**
     * Fungsi Static Helper untuk kalkulasi global satu tahun
     */
    public static function getSummary($tahun)
    {
        $baseQuery = self::where('tahun', $tahun)->where('is_published', true);
        
        $pendapatan = (clone $baseQuery)->where('tipe_anggaran', 'PENDAPATAN')->sum('pagu_anggaran');
        $belanja = (clone $baseQuery)->where('tipe_anggaran', 'BELANJA')->sum('pagu_anggaran');
        $pembiayaan = (clone $baseQuery)->where('tipe_anggaran', 'PEMBIAYAAN')->sum('pagu_anggaran');
        
        // Asumsi Surplus/Defisit murni dari (Pendapatan - Belanja)
        // Kadang di tata kelola (Pendapatan - Belanja + Pembiayaan Netto) tapi kita ambil basic.
        $surplus = $pendapatan - $belanja;

        return [
            'pendapatan' => $pendapatan,
            'belanja' => $belanja,
            'pembiayaan' => $pembiayaan,
            'surplus' => $surplus,
            'item_count' => (clone $baseQuery)->count()
        ];
    }
}
