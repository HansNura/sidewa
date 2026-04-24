<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model untuk Permohonan Perubahan Data Profil Warga.
 *
 * Alur: Warga ajukan → Operator approve/reject → Jika approve, data di-sync ke tabel warga.
 */
class PermohonanPerubahanProfil extends Model
{
    protected $table = 'permohonan_perubahan_profil';

    protected $fillable = [
        'nik',
        'nama_warga',
        'status',
        'data_lama',
        'data_baru',
        'foto_ktp',
        'foto_kk',
        'keterangan',
        'catatan_operator',
        'operator_id',
        'diproses_at',
    ];

    protected function casts(): array
    {
        return [
            'data_lama'    => 'array',
            'data_baru'    => 'array',
            'diproses_at'  => 'datetime',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    // ─── Accessors ─────────────────────────────────────────────

    public function statusBadge(): array
    {
        return match ($this->status) {
            'menunggu'  => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Menunggu Review'],
            'disetujui' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Disetujui'],
            'ditolak'   => ['bg' => 'bg-red-100',   'text' => 'text-red-700',   'label' => 'Ditolak'],
            default     => ['bg' => 'bg-gray-100',  'text' => 'text-gray-600',  'label' => ucfirst($this->status)],
        };
    }

    /**
     * Generate user-friendly diff of changed fields.
     */
    public function diffLabel(string $field): ?string
    {
        $labels = [
            'nama'                => 'Nama Lengkap',
            'pendidikan_terakhir' => 'Pendidikan Terakhir',
            'pekerjaan'           => 'Pekerjaan',
            'status_perkawinan'   => 'Status Perkawinan',
            'agama'               => 'Agama',
            'alamat'              => 'Alamat',
            'dusun'               => 'Dusun',
            'rt'                  => 'RT',
            'rw'                  => 'RW',
        ];

        return $labels[$field] ?? $field;
    }
}
