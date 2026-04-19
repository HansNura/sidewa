<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembangunan extends Model
{
    use HasFactory;

    protected $fillable = [
        'apbdes_id',
        'nama_proyek',
        'deskripsi',
        'kategori',
        'lokasi_dusun',
        'rt_rw',
        'latitude',
        'longitude',
        'tanggal_mulai',
        'target_selesai',
        'progres_fisik',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'target_selesai' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function apbdes()
    {
        return $this->belongsTo(Apbdes::class, 'apbdes_id');
    }

    public function historis()
    {
        return $this->hasMany(PembangunanHistori::class, 'pembangunan_id')->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc');
    }

    public function fotos()
    {
        return $this->hasMany(PembangunanFoto::class, 'pembangunan_id')->orderBy('progres_saat_foto', 'asc');
    }
}
