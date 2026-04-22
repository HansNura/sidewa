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
        'excerpt',
        'start_date',
        'end_date',
        'location',
        'contact_person',
        'time_text',
        'image_path',
        'status',
        'is_important',
    ];

    protected $casts = [
        'start_date'   => 'datetime',
        'end_date'     => 'datetime',
        'is_important' => 'boolean',
    ];

    // Const
    public const TYPE_PENGUMUMAN = 'pengumuman';
    public const TYPE_AGENDA = 'agenda';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISH = 'publish';
    public const STATUS_ARCHIVED = 'archived';

    // ── Scopes ──

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

    // ── Accessors ──

    public function getIsExpiredAttribute()
    {
        if ($this->end_date) {
            return Carbon::now()->gt($this->end_date);
        }
        return false;
    }

    public function getFormattedDateAttribute()
    {
        return $this->start_date
            ? $this->start_date->translatedFormat('d M Y')
            : $this->created_at->translatedFormat('d M Y');
    }

    public function getFormattedDateFullAttribute()
    {
        return $this->start_date
            ? $this->start_date->translatedFormat('l, d F Y')
            : $this->created_at->translatedFormat('l, d F Y');
    }

    public function getDayAttribute()
    {
        return $this->start_date ? $this->start_date->format('d') : $this->created_at->format('d');
    }

    public function getMonthShortAttribute()
    {
        return $this->start_date
            ? $this->start_date->translatedFormat('M')
            : $this->created_at->translatedFormat('M');
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path
            ? asset('storage/' . $this->image_path)
            : 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&q=80&w=800';
    }

    public function getExcerptTextAttribute()
    {
        if ($this->excerpt) return $this->excerpt;
        return \Str::limit(strip_tags($this->content_html), 200);
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

    /**
     * Get a color theme for the card based on index position.
     */
    public function getThemeColorAttribute()
    {
        $colors = ['green', 'amber', 'blue', 'purple', 'rose'];
        return $colors[$this->id % count($colors)];
    }
}
