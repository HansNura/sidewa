<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['name', 'email', 'password', 'role', 'nik', 'is_active', 'last_login_at', 'last_login_ip'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    // ─── Role Constants ─────────────────────────────────────────────
    public const ROLE_ADMINISTRATOR = 'administrator';
    public const ROLE_OPERATOR      = 'operator';
    public const ROLE_KADES         = 'kades';
    public const ROLE_PERANGKAT     = 'perangkat';
    public const ROLE_RESEPSIONIS   = 'resepsionis';

    /**
     * All available roles with their human-readable labels.
     */
    public const ROLES = [
        self::ROLE_ADMINISTRATOR => 'Administrator',
        self::ROLE_OPERATOR      => 'Operator Desa',
        self::ROLE_KADES         => 'Kepala Desa',
        self::ROLE_PERANGKAT     => 'Perangkat Desa',
        self::ROLE_RESEPSIONIS   => 'Resepsionis',
    ];

    /**
     * Role badge CSS classes for the back-office UI.
     */
    public const ROLE_BADGE_CLASSES = [
        self::ROLE_ADMINISTRATOR => 'bg-purple-100 text-purple-700',
        self::ROLE_OPERATOR      => 'bg-amber-100 text-amber-800',
        self::ROLE_KADES         => 'bg-blue-100 text-blue-700',
        self::ROLE_PERANGKAT     => 'bg-teal-100 text-teal-700',
        self::ROLE_RESEPSIONIS   => 'bg-pink-100 text-pink-700',
    ];

    /**
     * Hardcoded permission labels per role (decorative, for the detail drawer).
     */
    public const ROLE_PERMISSIONS = [
        self::ROLE_ADMINISTRATOR => [
            'Semua Modul (Full Access)',
            'Manajemen User',
            'Konfigurasi Sistem',
            'Manajemen Keuangan',
        ],
        self::ROLE_OPERATOR => [
            'Modul Penduduk (CRUD)',
            'Cetak Surat',
            'Kelola Buku Tamu',
        ],
        self::ROLE_KADES => [
            'Verifikasi & TTE',
            'Dashboard Eksekutif',
            'Laporan Statistik',
        ],
        self::ROLE_PERANGKAT => [
            'Modul Penduduk (Read)',
            'Cetak Surat',
            'Data Wilayah',
        ],
        self::ROLE_RESEPSIONIS => [
            'Buku Tamu Digital',
            'Monitoring Presensi',
        ],
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    // ─── Relationships ──────────────────────────────────────────────

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class)->latest();
    }

    // ─── Role Helpers ───────────────────────────────────────────────

    public function isAdministrator(): bool
    {
        return $this->role === self::ROLE_ADMINISTRATOR;
    }

    public function isOperator(): bool
    {
        return $this->role === self::ROLE_OPERATOR;
    }

    public function isKades(): bool
    {
        return $this->role === self::ROLE_KADES;
    }

    public function isPerangkat(): bool
    {
        return $this->role === self::ROLE_PERANGKAT;
    }

    public function isResepsionis(): bool
    {
        return $this->role === self::ROLE_RESEPSIONIS;
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    /**
     * Get the human-readable role label.
     */
    public function roleName(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

    /**
     * Get the Tailwind badge classes for this user's role.
     */
    public function roleBadgeClasses(): string
    {
        return self::ROLE_BADGE_CLASSES[$this->role] ?? 'bg-gray-100 text-gray-600';
    }

    /**
     * Get the decorative permission labels for this user's role.
     */
    public function rolePermissions(): array
    {
        return self::ROLE_PERMISSIONS[$this->role] ?? [];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get human-readable status label.
     */
    public function statusLabel(): string
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }

    /**
     * Get avatar URL via ui-avatars.com.
     */
    public function avatarUrl(): string
    {
        $bg = $this->is_active ? '15803d' : '9ca3af';
        $color = $this->is_active ? 'fff' : '6b7280';

        return 'https://ui-avatars.com/api/?' . http_build_query([
            'name'       => $this->name,
            'background' => $bg,
            'color'      => $color,
            'size'       => 128,
        ]);
    }

    // ─── Query Scopes ───────────────────────────────────────────────

    /**
     * Search by name, email, or NIK.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('nik', 'like', "%{$term}%");
        });
    }

    /**
     * Filter by role.
     */
    public function scopeFilterRole(Builder $query, ?string $role): Builder
    {
        if (blank($role)) {
            return $query;
        }

        return $query->where('role', $role);
    }

    /**
     * Filter by active status.
     */
    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {
        return match ($status) {
            'aktif'    => $query->where('is_active', true),
            'nonaktif' => $query->where('is_active', false),
            default    => $query,
        };
    }
}
