<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateSurat extends Model
{
    protected $table = 'template_surat';

    protected $fillable = [
        'nama',
        'kode',
        'kategori',
        'deskripsi',
        'body_template',
        'fields',
        'layout_settings',
        'is_active',
        'versi',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'fields'          => 'array',
            'layout_settings' => 'array',
            'is_active'       => 'boolean',
        ];
    }

    // ─── Constants ─────────────────────────────────────────────

    public const KATEGORI_LABELS = [
        'keterangan'  => 'Surat Keterangan',
        'pengantar'   => 'Surat Pengantar',
        'rekomendasi' => 'Surat Rekomendasi',
    ];

    public const KATEGORI_COLORS = [
        'keterangan'  => ['bg' => 'bg-blue-50',   'text' => 'text-blue-700',   'border' => 'border-blue-100'],
        'pengantar'   => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-100'],
        'rekomendasi' => ['bg' => 'bg-teal-50',   'text' => 'text-teal-700',   'border' => 'border-teal-100'],
    ];

    /**
     * Auto-fill fields from Penduduk data (inserted by system).
     */
    public const SYSTEM_FIELDS = [
        '{{nama_pemohon}}'      => 'Nama Lengkap Pemohon',
        '{{nik_pemohon}}'       => 'NIK Pemohon',
        '{{ttl_pemohon}}'       => 'Tempat, Tanggal Lahir',
        '{{pekerjaan_pemohon}}' => 'Pekerjaan Pemohon',
        '{{alamat_lengkap}}'    => 'Alamat Lengkap',
        '{{agama_pemohon}}'     => 'Agama Pemohon',
        '{{jenis_kelamin}}'     => 'Jenis Kelamin',
        '{{no_kk}}'             => 'Nomor Kartu Keluarga',
    ];

    /**
     * User-defined fields (operator fills manually).
     */
    public const MANUAL_FIELDS = [
        '{{keperluan}}'      => 'Keperluan / Tujuan Surat',
        '{{berlaku_hingga}}' => 'Masa Berlaku Surat',
        '{{nomor_surat}}'    => 'Nomor Surat',
        '{{tahun}}'          => 'Tahun Pembuatan',
        '{{nama_usaha}}'     => 'Nama Usaha (SKU)',
    ];

    // ─── Relationships ─────────────────────────────────────────

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ─── Accessors ─────────────────────────────────────────────

    public function kategoriLabel(): string
    {
        return self::KATEGORI_LABELS[$this->kategori] ?? $this->kategori;
    }

    public function kategoriColor(): array
    {
        return self::KATEGORI_COLORS[$this->kategori] ?? self::KATEGORI_COLORS['keterangan'];
    }

    /**
     * Count dynamic fields used in body_template.
     */
    public function fieldCount(): int
    {
        preg_match_all('/\{\{(\w+)\}\}/', $this->body_template ?? '', $matches);
        return count(array_unique($matches[1] ?? []));
    }

    /**
     * Extract field names used in body_template.
     */
    public function usedFields(): array
    {
        preg_match_all('/\{\{(\w+)\}\}/', $this->body_template ?? '', $matches);
        return array_unique($matches[0] ?? []);
    }

    /**
     * Default layout settings.
     */
    public function resolvedLayout(): array
    {
        return array_merge([
            'show_kop'  => true,
            'show_ttd'  => true,
            'show_qr'   => true,
            'margin_top'    => 3,
            'margin_bottom' => 3,
            'margin_left'   => 3,
            'margin_right'  => 2.5,
        ], $this->layout_settings ?? []);
    }

    // ─── Scopes ────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) return $query;
        return $query->where(fn ($q) =>
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('kode', 'like', "%{$search}%")
              ->orWhere('deskripsi', 'like', "%{$search}%")
        );
    }

    public function scopeFilterKategori(Builder $query, ?string $kategori): Builder
    {
        return $kategori ? $query->where('kategori', $kategori) : $query;
    }

    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {
        return match ($status) {
            'aktif'    => $query->where('is_active', true),
            'nonaktif' => $query->where('is_active', false),
            default    => $query,
        };
    }
}
