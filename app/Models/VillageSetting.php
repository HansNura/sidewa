<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VillageSetting extends Model
{
    protected $fillable = [
        'nama_desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'alamat',
        'email',
        'telepon',
        'website',
        'nama_kades',
        'nip_kades',
        'jabatan_kades',
        'logo_path',
        'banner_path',
    ];

    // ─── Singleton Access ──────────────────────────────────────

    /**
     * Get the single settings row, creating it with defaults if missing.
     */
    public static function instance(): static
    {
        return static::firstOrCreate([], [
            'nama_desa'     => 'Sindangmukti',
            'kecamatan'     => 'Mangunjaya',
            'kabupaten'     => 'Pangandaran',
            'provinsi'      => 'Jawa Barat',
            'kode_pos'      => '46353',
            'alamat'        => 'Jl. Desa Sindangmukti',
            'jabatan_kades' => 'Kepala Desa',
        ]);
    }

    // ─── Accessors ─────────────────────────────────────────────

    /**
     * Get the full public URL for the logo.
     */
    public function logoUrl(): string
    {
        if ($this->logo_path && Storage::disk('public')->exists($this->logo_path)) {
            return Storage::disk('public')->url($this->logo_path);
        }

        // Default fallback: Garuda Pancasila
        return 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b2/Pancasila_Coat_of_Arms_of_Indonesia.svg/150px-Pancasila_Coat_of_Arms_of_Indonesia.svg.png';
    }

    /**
     * Get the full public URL for the banner.
     */
    public function bannerUrl(): ?string
    {
        if ($this->banner_path && Storage::disk('public')->exists($this->banner_path)) {
            return Storage::disk('public')->url($this->banner_path);
        }

        return null;
    }

    /**
     * Full village name with prefix.
     */
    public function fullName(): string
    {
        return "Desa {$this->nama_desa}";
    }

    /**
     * Full hierarchical address.
     */
    public function fullAddress(): string
    {
        return "{$this->alamat}, Kec. {$this->kecamatan}, Kab. {$this->kabupaten}, Prov. {$this->provinsi}, {$this->kode_pos}";
    }
}
