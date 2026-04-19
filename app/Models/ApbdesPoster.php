<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApbdesPoster extends Model
{
    use HasFactory;

    protected $table = 'apbdes_posters';

    protected $fillable = [
        'tahun',
        'gambar_baliho_url',
        'perdes_dokumen_url',
        'rab_dokumen_url',
    ];
}
