<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembangunanHistori extends Model
{
    use HasFactory;

    protected $fillable = [
        'pembangunan_id',
        'judul_update',
        'deskripsi',
        'tanggal',
        'oleh_siapa',
        'is_milestone',
        'progres_dicapai',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_milestone' => 'boolean',
    ];

    public function pembangunan()
    {
        return $this->belongsTo(Pembangunan::class);
    }
}
