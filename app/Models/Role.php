<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'slug',
        'display_name',
        'description',
        'icon',
        'color',
        'is_system',
    ];

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
        ];
    }

    // ─── Color Config ──────────────────────────────────────────

    /**
     * Tailwind color map for role card UI.
     */
    public const COLOR_MAP = [
        'blue'    => ['bg' => 'bg-blue-50',    'text' => 'text-blue-600',    'badge' => 'bg-blue-100 text-blue-700'],
        'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'badge' => 'bg-emerald-100 text-emerald-700'],
        'amber'   => ['bg' => 'bg-amber-50',   'text' => 'text-amber-600',   'badge' => 'bg-amber-100 text-amber-700'],
        'gray'    => ['bg' => 'bg-gray-100',    'text' => 'text-gray-600',    'badge' => 'bg-gray-100 text-gray-600'],
        'purple'  => ['bg' => 'bg-purple-50',   'text' => 'text-purple-600',  'badge' => 'bg-purple-100 text-purple-700'],
        'teal'    => ['bg' => 'bg-teal-50',     'text' => 'text-teal-600',    'badge' => 'bg-teal-100 text-teal-700'],
        'pink'    => ['bg' => 'bg-pink-50',     'text' => 'text-pink-600',    'badge' => 'bg-pink-100 text-pink-700'],
        'red'     => ['bg' => 'bg-red-50',      'text' => 'text-red-600',     'badge' => 'bg-red-100 text-red-700'],
    ];

    // ─── Relationships ─────────────────────────────────────────

    public function permissions(): HasMany
    {
        return $this->hasMany(RoleModulePermission::class);
    }

    // ─── Helpers ────────────────────────────────────────────────

    /**
     * Get the number of users assigned to this role.
     */
    public function userCount(): int
    {
        return User::where('role', $this->slug)->count();
    }

    /**
     * Get Tailwind icon classes.
     */
    public function iconBgClass(): string
    {
        return self::COLOR_MAP[$this->color]['bg'] ?? 'bg-gray-100';
    }

    public function iconTextClass(): string
    {
        return self::COLOR_MAP[$this->color]['text'] ?? 'text-gray-600';
    }

    /**
     * Check if this role can be deleted.
     */
    public function isDeletable(): bool
    {
        return !$this->is_system && $this->userCount() === 0;
    }

    /**
     * Get permissions keyed by module_id for easy lookup.
     */
    public function permissionsMatrix(): array
    {
        return $this->permissions->keyBy('module_id')->toArray();
    }
}
