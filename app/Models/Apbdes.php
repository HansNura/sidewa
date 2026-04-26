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
     * Relasi Histori Realisasi
     */
    public function realisasis()
    {
        return $this->hasMany(ApbdesRealisasi::class, 'apbdes_id');
    }

    /**
     * Relasi Data Pembangunan Fisik
     */
    public function pembangunan()
    {
        return $this->hasOne(Pembangunan::class, 'apbdes_id');
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

        $pendapatan_realisasi = ApbdesRealisasi::whereHas('apbdes', function ($q) use ($tahun) {
            $q->where('tahun', $tahun)->where('is_published', true)->where('tipe_anggaran', 'PENDAPATAN');
        })->sum('nominal');

        $belanja_realisasi = ApbdesRealisasi::whereHas('apbdes', function ($q) use ($tahun) {
            $q->where('tahun', $tahun)->where('is_published', true)->where('tipe_anggaran', 'BELANJA');
        })->sum('nominal');

        $pembiayaan_realisasi = ApbdesRealisasi::whereHas('apbdes', function ($q) use ($tahun) {
            $q->where('tahun', $tahun)->where('is_published', true)->where('tipe_anggaran', 'PEMBIAYAAN');
        })->sum('nominal');

        // Asumsi Surplus/Defisit murni dari (Pendapatan target - Belanja target)
        // Kadang di tata kelola (Pendapatan - Belanja + Pembiayaan Netto) tapi kita ambil basic.
        $surplus = $pendapatan - $belanja;
        $surplus_realisasi = $pendapatan_realisasi - $belanja_realisasi;

        return [
            'pendapatan' => $pendapatan,
            'pendapatan_realisasi' => $pendapatan_realisasi,
            'belanja' => $belanja,
            'belanja_realisasi' => $belanja_realisasi,
            'pembiayaan' => $pembiayaan,
            'pembiayaan_realisasi' => $pembiayaan_realisasi,
            'surplus' => $surplus,
            'surplus_realisasi' => $surplus_realisasi,
            'item_count' => (clone $baseQuery)->count()
        ];
    }
}
