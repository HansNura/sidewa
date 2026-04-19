<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class PengukuranBalita extends Model
{
    protected $table = 'pengukuran_balita';

    protected $fillable = [
        'penduduk_id',
        'tanggal_pengukuran',
        'umur_bulan',
        'tinggi_badan',
        'berat_badan',
        'status_gizi',
        'nama_ortu',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengukuran' => 'date',
            'tinggi_badan'      => 'decimal:1',
            'berat_badan'       => 'decimal:1',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

    // ─── Accessors ─────────────────────────────────────────────

    /**
     * Status gizi badge styling.
     */
    public function statusBadge(): array
    {
        return match ($this->status_gizi) {
            'normal'       => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-200', 'label' => 'Normal'],
            'pendek'       => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'label' => 'Pendek (Stunting)'],
            'sangat_pendek'=> ['bg' => 'bg-red-100',   'text' => 'text-red-700',   'border' => 'border-red-200',   'label' => 'Sangat Pendek'],
            'tinggi'       => ['bg' => 'bg-blue-100',  'text' => 'text-blue-700',  'border' => 'border-blue-200',  'label' => 'Tinggi'],
        };
    }

    /**
     * Check if stunting (pendek or sangat_pendek).
     */
    public function isStunting(): bool
    {
        return in_array($this->status_gizi, ['pendek', 'sangat_pendek']);
    }

    // ─── Scopes ────────────────────────────────────────────────

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) return $query;

        return $query->where(function ($q) use ($search) {
            $q->where('nama_ortu', 'like', "%{$search}%")
              ->orWhereHas('penduduk', fn ($p) =>
                  $p->where('nama', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
              );
        });
    }

    public function scopeStunting(Builder $query): Builder
    {
        return $query->whereIn('status_gizi', ['pendek', 'sangat_pendek']);
    }

    public function scopeLatestPerChild(Builder $query): Builder
    {
        return $query->whereIn('id', function ($sub) {
            $sub->selectRaw('MAX(id)')
                ->from('pengukuran_balita')
                ->groupBy('penduduk_id');
        });
    }
}
