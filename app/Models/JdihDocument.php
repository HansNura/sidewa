<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JdihDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'document_number',
        'established_date',
        'status',
        'description',
        'file_path',
        'file_size',
        'uploader_id',
    ];

    protected $casts = [
        'established_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(JdihCategory::class, 'category_id', 'id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'berlaku');
    }
}
