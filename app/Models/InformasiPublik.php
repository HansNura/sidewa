<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InformasiPublik extends Model
{
    use HasFactory;

    protected $table = 'informasi_publik';

    protected $fillable = [
        'type',
        'title',
        'slug',
        'content_html',
        'start_date',
        'end_date',
        'location',
        'image_path',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    // Const
    public const TYPE_PENGUMUMAN = 'pengumuman';
    public const TYPE_AGENDA = 'agenda';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISH = 'publish';
    public const STATUS_ARCHIVED = 'archived';

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_PUBLISH);
    }

    public function scopeAgenda($query)
    {
        return $query->where('type', self::TYPE_AGENDA);
    }

    public function scopePengumuman($query)
    {
        return $query->where('type', self::TYPE_PENGUMUMAN);
    }

    // Accessors
    public function getIsExpiredAttribute()
    {
        if ($this->end_date) {
            return Carbon::now()->gt($this->end_date);
        }
        return false;
    }

    public function getStatusDisplayAttribute()
    {
        if ($this->status === self::STATUS_DRAFT) return 'Draft';
        if ($this->status === self::STATUS_ARCHIVED) return 'Arsip';
        
        if ($this->type === self::TYPE_PENGUMUMAN) {
            if ($this->start_date > Carbon::now()) return 'Dijadwalkan';
            if ($this->end_date && Carbon::now()->gt($this->end_date)) return 'Selesai/Expired';
            return 'Sedang Tayang';
        }

        if ($this->type === self::TYPE_AGENDA) {
            if ($this->start_date > Carbon::now()) return 'Akan Datang';
            return 'Selesai';
        }

        return 'Live';
    }
}
