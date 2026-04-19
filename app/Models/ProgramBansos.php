<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramBansos extends Model
{
    protected $table = 'program_bansos';

    protected $fillable = [
        'nama', 'deskripsi', 'icon', 'icon_bg', 'icon_color',
        'periode', 'status',
    ];

    // ─── Relationships ─────────────────────────────────────────

    public function penerima(): HasMany
    {
        return $this->hasMany(PenerimaBansos::class);
    }

    // ─── Accessors ─────────────────────────────────────────────

    public function jumlahPenerima(): int
    {
        return $this->penerima()->count();
    }

    public function statusBadge(): array
    {
        return match ($this->status) {
            'aktif'  => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Aktif'],
            'selesai' => ['bg' => 'bg-gray-100',  'text' => 'text-gray-500',  'label' => 'Selesai'],
        };
    }
}
