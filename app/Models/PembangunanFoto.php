<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PembangunanFoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'pembangunan_id',
        'foto_path',
        'keterangan',
        'progres_saat_foto',
    ];

    public function pembangunan()
    {
        return $this->belongsTo(Pembangunan::class);
    }

    public function getFotoUrlAttribute()
    {
        if ($this->foto_path) {
            return Storage::disk('public')->url($this->foto_path);
        }
        return null; // Akan digantikan dengan helper asset bawaan Tailwind / Placehold jk tidak ada
    }
}
