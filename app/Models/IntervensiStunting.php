<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntervensiStunting extends Model
{
    protected $table = 'intervensi_stunting';

    protected $fillable = [
        'nama',
        'deskripsi',
        'status',
        'target_peserta',
        'peserta_terdaftar',
        'progres',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai'   => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    /**
     * Status badge styling.
     */
    public function statusBadge(): array
    {
        return match ($this->status) {
            'berjalan'  => ['bg' => 'bg-blue-50',  'text' => 'text-blue-600',  'border' => 'border-blue-100'],
            'terjadwal' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-100'],
            'selesai'   => ['bg' => 'bg-green-50', 'text' => 'text-green-600', 'border' => 'border-green-100'],
        };
    }

    /**
     * Progress bar color.
     */
    public function progressColor(): string
    {
        return match ($this->status) {
            'berjalan'  => 'bg-green-500',
            'terjadwal' => 'bg-amber-400',
            'selesai'   => 'bg-blue-500',
        };
    }
}
