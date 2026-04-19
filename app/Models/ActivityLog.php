<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Helpers ────────────────────────────────────────────────

    /**
     * Log an activity for the given user.
     */
    public static function record(User $user, string $action, string $description, ?array $metadata = null): static
    {
        return static::create([
            'user_id'    => $user->id,
            'action'     => $action,
            'description'=> $description,
            'ip_address' => request()->ip(),
            'metadata'   => $metadata,
        ]);
    }
}
