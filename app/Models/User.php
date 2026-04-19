<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['name', 'email', 'password', 'role', 'nik'])]
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
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
}
