<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MediaGaleri extends Model
{
    use HasFactory;

    protected $table = 'media_galeris';

    protected $fillable = [
        'album_id',
        'file_path',
        'file_name',
        'file_type',
        'mime_type',
        'file_size',
        'deskripsi',
        'tags',
        'uploader_name',
    ];

    public function album()
    {
        return $this->belongsTo(AlbumGaleri::class, 'album_id');
    }

    // Accessor untuk public url
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    // Format size user friendly
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
