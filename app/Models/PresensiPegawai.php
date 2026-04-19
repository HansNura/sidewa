<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PresensiPegawai extends Model
{
    use HasFactory;

    protected $table = 'presensi_pegawai';

    protected $fillable = [
        'user_id',
        'tanggal',
        'waktu_masuk',
        'waktu_pulang',
        'status',
        'metode_masuk',
        'metode_pulang',
        'foto_masuk_url',
        'foto_pulang_url',
        'catatan',
        'dikoreksi_oleh',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    /**
     * Status UI labels mapping
     */
    public const STATUS_LABELS = [
        'hadir'     => 'Hadir',
        'terlambat' => 'Terlambat',
        'izin'      => 'Izin',
        'sakit'     => 'Sakit',
        'dinas'     => 'Dinas Luar',
        'alpha'     => 'Alpha / Belum Hadir',
    ];

    /**
     * Pegawai (User) yang melakukan presensi
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Admin/HR yang melakukan koreksi manual
     */
    public function korektor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dikoreksi_oleh');
    }

    /**
     * Format jam/waktu masuk (misal 08:00 WIB)
     */
    public function formatMasuk(): string
    {
        return $this->waktu_masuk ? Carbon::parse($this->waktu_masuk)->format('H:i') . ' WIB' : '-';
    }

    /**
     * Format jam/waktu pulang (misal 16:00 WIB)
     */
    public function formatPulang(): string
    {
        return $this->waktu_pulang ? Carbon::parse($this->waktu_pulang)->format('H:i') . ' WIB' : 'Belum Pulang';
    }

    /**
     * Label manusiawi untuk status
     */
    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }
}
