<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perencanaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_rencana',
        'tahun_pelaksanaan',
        'prioritas',
        'nama_program',
        'tujuan_sasaran',
        'estimasi_pagu',
        'sumber_dana',
        'kategori',
        'target_mulai',
        'target_selesai',
        'status',
        'pembangunan_id'
    ];

    public function pembangunan()
    {
        return $this->belongsTo(Pembangunan::class, 'pembangunan_id');
    }
}
