<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'sort_order',
        'is_sensitive',
    ];

    protected function casts(): array
    {
        return [
            'is_sensitive' => 'boolean',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────

    public function permissions(): HasMany
    {
        return $this->hasMany(RoleModulePermission::class);
    }
}
