<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Penduduk extends Model
{
    protected $table = 'penduduk';

    protected $fillable = [
        'nik',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'golongan_darah',
        'no_kk',
        'status_hubungan',
        'status_perkawinan',
        'nama_ayah',
        'nama_ibu',
        'pendidikan',
        'pekerjaan',
        'alamat',
        'dusun',
        'rt',
        'rw',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    // ─── Accessors ─────────────────────────────────────────────

    /**
     * Calculate current age in years.
     */
    public function umur(): int
    {
        return $this->tanggal_lahir->age;
    }

    /**
     * Full address line.
     */
    public function alamatLengkap(): string
    {
        $parts = array_filter([
            $this->dusun ? "Dusun {$this->dusun}" : null,
            ($this->rt && $this->rw) ? "RT {$this->rt}/RW {$this->rw}" : null,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Gender label in Indonesian.
     */
    public function jenisKelaminLabel(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    /**
     * Status badge color map.
     */
    public function statusColor(): array
    {
        return match ($this->status) {
            'hidup'     => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-200', 'label' => 'Hidup'],
            'pindah'    => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'label' => 'Pindah'],
            'meninggal' => ['bg' => 'bg-gray-100',  'text' => 'text-gray-600',  'border' => 'border-gray-200',  'label' => 'Meninggal'],
        };
    }

    // ─── Query Scopes ──────────────────────────────────────────

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) return $query;

        return $query->where(function ($q) use ($search) {
            $q->where('nik', 'like', "%{$search}%")
              ->orWhere('nama', 'like', "%{$search}%")
              ->orWhere('no_kk', 'like', "%{$search}%");
        });
    }

    public function scopeFilterDusun(Builder $query, ?string $dusun): Builder
    {
        return $dusun ? $query->where('dusun', $dusun) : $query;
    }

    public function scopeFilterGender(Builder $query, ?string $gender): Builder
    {
        return $gender ? $query->where('jenis_kelamin', $gender) : $query;
    }

    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function scopeFilterPerkawinan(Builder $query, ?string $perkawinan): Builder
    {
        return $perkawinan ? $query->where('status_perkawinan', $perkawinan) : $query;
    }

    public function scopeFilterUsia(Builder $query, ?string $group): Builder
    {
        if (!$group) return $query;

        $now = Carbon::now();

        return match ($group) {
            'balita'  => $query->whereBetween('tanggal_lahir', [$now->copy()->subYears(5), $now]),
            'anak'    => $query->whereBetween('tanggal_lahir', [$now->copy()->subYears(15), $now->copy()->subYears(5)]),
            'pemuda'  => $query->whereBetween('tanggal_lahir', [$now->copy()->subYears(25), $now->copy()->subYears(15)]),
            'dewasa'  => $query->whereBetween('tanggal_lahir', [$now->copy()->subYears(65), $now->copy()->subYears(25)]),
            'lansia'  => $query->where('tanggal_lahir', '<', $now->copy()->subYears(65)),
            default   => $query,
        };
    }

    // ─── Statistics ────────────────────────────────────────────

    /**
     * Get dashboard statistics.
     */
    public static function statistics(): array
    {
        $now = Carbon::now();
        $allActive = static::where('status', 'hidup');

        return [
            'total'       => static::count(),
            'laki'        => static::where('jenis_kelamin', 'L')->count(),
            'perempuan'   => static::where('jenis_kelamin', 'P')->count(),
            'produktif'   => (clone $allActive)
                                ->whereBetween('tanggal_lahir', [$now->copy()->subYears(64), $now->copy()->subYears(15)])
                                ->count(),
            'total_hidup' => (clone $allActive)->count(),
        ];
    }
}
