<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'api_key',
        'plain_token_suffix',
        'scopes',
        'status',
        'last_used_at'
    ];

    protected $casts = [
        'scopes' => 'array',
        'last_used_at' => 'datetime'
    ];

    public static function generateToken()
    {
        return 'sk_live_' . Str::random(40);
    }
}
