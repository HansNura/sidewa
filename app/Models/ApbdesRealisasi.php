<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ApbdesRealisasi extends Model
{
    use HasFactory;

    protected $table = 'apbdes_realisasi';

    protected $fillable = [
        'apbdes_id',
        'tanggal_transaksi',
        'nominal',
        'catatan',
        'bukti_file_path',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
    ];

    /**
     * Relasi ke Apbdes Induk
     */
    public function apbdes()
    {
        return $this->belongsTo(Apbdes::class, 'apbdes_id');
    }

    /**
     * Get URL for file storage
     */
    public function getBuktiUrlAttribute()
    {
        if ($this->bukti_file_path) {
            return Storage::disk('public')->url($this->bukti_file_path);
        }
        return null;
    }
}
