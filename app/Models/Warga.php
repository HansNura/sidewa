<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class Warga extends Authenticatable
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'warga';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nik',
        'nama',
        'no_kk',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'pekerjaan',
        'status_perkawinan',
        'alamat',
        'rt',
        'rw',
        'dusun',
        'pendidikan_terakhir',
        'kesejahteraan',
        'is_stunting',
        'pin',
        'is_active',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'pin',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'pin'           => 'hashed',
            'is_active'     => 'boolean',
            'is_stunting'   => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    // ─── Auth Overrides ─────────────────────────────────────────

    /**
     * Get the password column name for the Authenticatable contract.
     * Warga uses 'pin' instead of 'password'.
     */
    public function getAuthPasswordName(): string
    {
        return 'pin';
    }

    // ─── Accessors & Helpers ────────────────────────────────────

    /**
     * Get the warga's initials for avatar display.
     */
    public function initials(): string
    {
        return Str::of($this->nama)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get formatted NIK with dashes for display: 3201-2345-6789-0001
     */
    public function formattedNik(): string
    {
        return implode('-', str_split($this->nik, 4));
    }

    /**
     * Get age in years from tanggal_lahir.
     */
    public function umur(): ?int
    {
        return $this->tanggal_lahir?->age;
    }

    /**
     * Get full address string.
     */
    public function alamatLengkap(): string
    {
        $parts = array_filter([
            $this->alamat,
            $this->rt ? 'RT ' . $this->rt : null,
            $this->rw ? 'RW ' . $this->rw : null,
            $this->dusun ? 'Dusun ' . $this->dusun : null,
        ]);

        return implode(', ', $parts) ?: '-';
    }
}
