<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataExchangeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipe',
        'modul_tujuan',
        'nama_file',
        'status',
        'jumlah_berhasil',
        'jumlah_gagal',
        'catatan_error',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
