<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleModulePermission extends Model
{
    protected $fillable = [
        'role_id',
        'module_id',
        'can_view',
        'can_create',
        'can_edit',
        'can_delete',
    ];

    protected function casts(): array
    {
        return [
            'can_view'   => 'boolean',
            'can_create' => 'boolean',
            'can_edit'   => 'boolean',
            'can_delete' => 'boolean',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Check if this permission entry has any access at all.
     */
    public function hasAnyAccess(): bool
    {
        return $this->can_view || $this->can_create || $this->can_edit || $this->can_delete;
    }

    /**
     * Check if this permission entry has full CRUD access.
     */
    public function hasFullAccess(): bool
    {
        return $this->can_view && $this->can_create && $this->can_edit && $this->can_delete;
    }
}
