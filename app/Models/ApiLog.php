<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'endpoint',
        'method',
        'status_code',
        'latency_ms',
        'ip_address',
        'api_client_id'
    ];

    public function client()
    {
        return $this->belongsTo(ApiClient::class, 'api_client_id');
    }
}
