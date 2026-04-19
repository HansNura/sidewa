<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlbumGaleri extends Model
{
    use HasFactory;

    protected $table = 'album_galeris';

    protected $fillable = [
        'nama_album',
        'deskripsi',
        'cover_image'
    ];

    public function medias()
    {
        return $this->hasMany(MediaGaleri::class, 'album_id');
    }
}
