<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenerimaBansos extends Model
{
    protected $table = 'penerima_bansos';

    protected $fillable = [
        'penduduk_id', 'program_bansos_id', 'tahap', 'desil',
        'status_distribusi', 'tanggal_distribusi',
        'catatan_audit', 'is_duplikat',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_distribusi' => 'date',
            'is_duplikat'        => 'boolean',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(ProgramBansos::class, 'program_bansos_id');
    }

    // ─── Accessors ─────────────────────────────────────────────

    public function statusBadge(): array
    {
        return match ($this->status_distribusi) {
            'pending'       => ['bg' => 'bg-gray-50',  'text' => 'text-gray-600',  'border' => 'border-gray-200', 'icon' => 'fa-clock',             'label' => 'Pending'],
            'siap_diambil'  => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200','icon' => 'fa-clock',             'label' => 'Menunggu Diambil'],
            'diterima'      => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200','icon' => 'fa-check',             'label' => 'Diterima'],
            'tertahan'      => ['bg' => 'bg-red-50',   'text' => 'text-red-700',   'border' => 'border-red-200',  'icon' => 'fa-ban',               'label' => 'Tertahan (Audit)'],
        };
    }

    // ─── Scopes ────────────────────────────────────────────────

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) return $query;

        return $query->where(function ($q) use ($search) {
            $q->whereHas('penduduk', fn ($p) =>
                $p->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_kk', 'like', "%{$search}%")
            );
        });
    }

    public function scopeFilterProgram(Builder $query, ?string $programId): Builder
    {
        if (!$programId) return $query;
        return $query->where('program_bansos_id', $programId);
    }

    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {
        if (!$status) return $query;
        return $query->where('status_distribusi', $status);
    }

    public function scopeDuplikat(Builder $query): Builder
    {
        return $query->where('is_duplikat', true);
    }
}
