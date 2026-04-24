<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LampiranSurat extends Model
{
    protected $table = 'lampiran_surat';

    protected $fillable = [
        'surat_permohonan_id',
        'nama_file',
        'path',
        'tipe',
        'ukuran',
    ];

    // ─── Relationships ─────────────────────────────────────────

    public function suratPermohonan(): BelongsTo
    {
        return $this->belongsTo(SuratPermohonan::class);
    }

    // ─── Accessors ─────────────────────────────────────────────

    /**
     * Human-readable file size.
     */
    public function ukuranFormatted(): string
    {
        $bytes = $this->ukuran;

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        }

        return number_format($bytes / 1024, 0) . ' KB';
    }

    /**
     * File type label in Indonesian.
     */
    public function tipeLabel(): string
    {
        return match ($this->tipe) {
            'ktp'       => 'Foto KTP',
            'kk'        => 'Foto Kartu Keluarga',
            'pendukung' => 'Dokumen Pendukung',
            default     => ucfirst($this->tipe),
        };
    }
}
