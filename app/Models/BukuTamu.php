<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuTamu extends Model
{
    use HasFactory;

    protected $table = 'buku_tamu';

    protected $fillable = [
        'nama_tamu',
        'instansi',
        'tujuan_kategori',
        'keperluan',
        'foto_ktp_url',
        'metode_input',
        'status',
        'waktu_masuk',
        'waktu_keluar',
    ];

    protected $casts = [
        'waktu_masuk'  => 'datetime',
        'waktu_keluar' => 'datetime',
    ];

    // Helper for badge color
    public function statusColor()
    {
        return match ($this->status) {
            'selesai'   => 'green',
            'dilayani'  => 'blue',
            'menunggu'  => 'amber',
            default     => 'gray',
        };
    }

    public function tujuanColor()
    {
        return match ($this->tujuan_kategori) {
            'Layanan Surat' => 'primary',
            'Koordinasi'    => 'blue',
            'Lain-lain'     => 'gray',
            default         => 'gray',
        };
    }
}
