<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class KartuKeluarga extends Model
{
    protected $table = 'kartu_keluarga';

    protected $fillable = [
        'no_kk',
        'alamat',
        'dusun',
        'rt',
        'rw',
        'tanggal_dikeluarkan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_dikeluarkan' => 'date',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────

    /**
     * All family members.
     */
    public function anggota(): HasMany
    {
        return $this->hasMany(Penduduk::class);
    }

    /**
     * The head of family (Kepala Keluarga).
     */
    public function kepalaKeluarga(): HasOne
    {
        return $this->hasOne(Penduduk::class)->where('status_hubungan', 'Kepala Keluarga');
    }

    // ─── Accessors ─────────────────────────────────────────────

    /**
     * Formatted wilayah string.
     */
    public function wilayah(): string
    {
        $parts = [];
        if ($this->rt && $this->rw) {
            $parts[] = "RT {$this->rt} / RW {$this->rw}";
        }
        if ($this->dusun) {
            $parts[] = "Dusun {$this->dusun}";
        }
        return implode(', ', $parts) ?: '-';
    }

    /**
     * Number of family members.
     */
    public function jumlahAnggota(): int
    {
        return $this->anggota()->count();
    }

    // ─── Query Scopes ──────────────────────────────────────────

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) return $query;

        return $query->where(function ($q) use ($search) {
            $q->where('no_kk', 'like', "%{$search}%")
              ->orWhereHas('kepalaKeluarga', function ($sub) use ($search) {
                  $sub->where('nama', 'like', "%{$search}%");
              });
        });
    }

    public function scopeFilterDusun(Builder $query, ?string $dusun): Builder
    {
        return $dusun ? $query->where('dusun', $dusun) : $query;
    }

    public function scopeOrderByAnggota(Builder $query, ?string $dir): Builder
    {
        if (!$dir || !in_array($dir, ['asc', 'desc'])) return $query->latest();

        return $query->withCount('anggota')->orderBy('anggota_count', $dir);
    }
}
