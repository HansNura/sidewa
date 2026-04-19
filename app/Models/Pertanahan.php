<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pertanahan extends Model
{
    protected $table = 'pertanahan';

    protected $fillable = [
        'kode_lahan', 'penduduk_id', 'kepemilikan', 'nama_pemilik',
        'luas', 'lokasi_blok', 'dusun', 'rt', 'rw',
        'legalitas', 'nomor_sertifikat', 'geojson', 'catatan',
    ];

    protected function casts(): array
    {
        return [
            'geojson' => 'array',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

    // ─── Accessors ─────────────────────────────────────────────

    /**
     * Display name: penduduk name or custom nama_pemilik.
     */
    public function displayPemilik(): string
    {
        if ($this->kepemilikan === 'desa') return 'Pemerintah Desa';
        if ($this->kepemilikan === 'fasum') return 'Fasilitas Umum';
        return $this->penduduk?->nama ?? $this->nama_pemilik ?? '-';
    }

    /**
     * Kepemilikan badge styling.
     */
    public function kepemilikanBadge(): array
    {
        return match ($this->kepemilikan) {
            'desa'  => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-200', 'icon' => 'fa-building-flag', 'label' => 'Aset Desa (TKD)'],
            'warga' => ['bg' => 'bg-blue-100',  'text' => 'text-blue-700',  'border' => 'border-blue-200',  'icon' => 'fa-users',         'label' => 'Milik Pribadi'],
            'fasum' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'icon' => 'fa-road',          'label' => 'Fasilitas Umum'],
        };
    }

    /**
     * Legalitas badge styling.
     */
    public function legalitasBadge(): array
    {
        return match ($this->legalitas) {
            'shm'              => ['bg' => 'bg-blue-100',  'text' => 'text-blue-700',  'border' => 'border-blue-200',  'label' => 'SHM'],
            'shgb'             => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-200', 'label' => 'SHP (Hak Pakai)'],
            'girik'            => ['bg' => 'bg-gray-100',  'text' => 'text-gray-600',  'border' => 'border-gray-200',  'label' => 'Girik / Letter C'],
            'ajb'              => ['bg' => 'bg-purple-100','text' => 'text-purple-700','border' => 'border-purple-200','label' => 'AJB'],
            'belum_sertifikat' => ['bg' => 'bg-red-100',   'text' => 'text-red-700',   'border' => 'border-red-200',   'label' => 'Belum Bersertifikat'],
        };
    }

    /**
     * Map polygon color based on ownership.
     */
    public function mapColor(): string
    {
        return match ($this->kepemilikan) {
            'desa'  => '#22c55e',
            'warga' => '#3b82f6',
            'fasum' => '#f59e0b',
        };
    }

    public function mapBorderColor(): string
    {
        return match ($this->kepemilikan) {
            'desa'  => '#16a34a',
            'warga' => '#2563eb',
            'fasum' => '#d97706',
        };
    }

    // ─── Scopes ────────────────────────────────────────────────

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) return $query;

        return $query->where(function ($q) use ($search) {
            $q->where('kode_lahan', 'like', "%{$search}%")
              ->orWhere('lokasi_blok', 'like', "%{$search}%")
              ->orWhere('nama_pemilik', 'like', "%{$search}%")
              ->orWhereHas('penduduk', fn ($p) =>
                  $p->where('nama', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
              );
        });
    }

    public function scopeFilterKepemilikan(Builder $query, ?string $kepemilikan): Builder
    {
        if (!$kepemilikan) return $query;
        return $query->where('kepemilikan', $kepemilikan);
    }

    public function scopeFilterLegalitas(Builder $query, ?string $legalitas): Builder
    {
        if (!$legalitas) return $query;
        return $query->where('legalitas', $legalitas);
    }

    // ─── Auto Code ─────────────────────────────────────────────

    /**
     * Generate next kode_lahan based on kepemilikan.
     */
    public static function generateKode(string $kepemilikan): string
    {
        $suffix = match ($kepemilikan) {
            'desa'  => 'DS',
            'warga' => 'WG',
            'fasum' => 'FS',
        };

        $lastKode = static::where('kode_lahan', 'like', "LHN-%-{$suffix}")
            ->orderByDesc('id')
            ->value('kode_lahan');

        $nextNum = 1;
        if ($lastKode) {
            preg_match('/LHN-(\d+)-/', $lastKode, $matches);
            $nextNum = (int) ($matches[1] ?? 0) + 1;
        }

        return sprintf('LHN-%03d-%s', $nextNum, $suffix);
    }
}
