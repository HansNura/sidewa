<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JdihDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'document_number',
        'established_date',
        'promulgated_date',
        'status',
        'description',
        'signer',
        'initiator',
        'file_path',
        'file_size',
        'download_count',
        'uploader_id',
    ];

    protected $casts = [
        'established_date'  => 'date',
        'promulgated_date'  => 'date',
        'download_count'    => 'integer',
        'file_size'         => 'integer',
    ];

    // ── Constants ──
    public const STATUS_BERLAKU = 'berlaku';
    public const STATUS_DICABUT = 'dicabut';
    public const STATUS_DRAFT   = 'draft';

    // ── Relationships ──

    public function category()
    {
        return $this->belongsTo(JdihCategory::class, 'category_id', 'id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id', 'id');
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_BERLAKU);
    }

    public function scopeByCategory($query, $slug)
    {
        return $query->whereHas('category', fn($q) => $q->where('slug', $slug));
    }

    public function scopeByYear($query, $year)
    {
        return $query->whereYear('established_date', $year);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('title', 'LIKE', "%{$keyword}%")
              ->orWhere('document_number', 'LIKE', "%{$keyword}%")
              ->orWhere('description', 'LIKE', "%{$keyword}%");
        });
    }

    // ── Accessors ──

    public function getIsBerlakuAttribute()
    {
        return $this->status === self::STATUS_BERLAKU;
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            self::STATUS_BERLAKU => 'Berlaku',
            self::STATUS_DICABUT => 'Tidak Berlaku / Dicabut',
            self::STATUS_DRAFT   => 'Draft',
            default              => ucfirst($this->status),
        };
    }

    public function getCategoryNameAttribute()
    {
        return $this->category?->name ?? 'Lainnya';
    }

    public function getFormattedDateAttribute()
    {
        return $this->established_date
            ? $this->established_date->translatedFormat('d M Y')
            : '-';
    }

    public function getFormattedDateFullAttribute()
    {
        return $this->established_date
            ? $this->established_date->translatedFormat('d F Y')
            : '-';
    }

    public function getPromulgatedDateFormattedAttribute()
    {
        return $this->promulgated_date
            ? $this->promulgated_date->translatedFormat('d F Y')
            : '-';
    }

    public function getYearAttribute()
    {
        return $this->established_date
            ? $this->established_date->year
            : null;
    }

    public function getFileUrlAttribute()
    {
        return $this->file_path
            ? asset('storage/' . $this->file_path)
            : null;
    }

    public function getFileNameAttribute()
    {
        if ($this->file_path) {
            return basename($this->file_path);
        }
        return \Str::slug($this->title, '_') . '.pdf';
    }

    public function getFileSizeFormattedAttribute()
    {
        if ($this->file_size <= 0) return '-';
        $kb = $this->file_size / 1024;
        if ($kb >= 1024) return round($kb / 1024, 1) . ' MB';
        return round($kb, 0) . ' KB';
    }

    public function getSignerNameAttribute()
    {
        return $this->signer ?? 'Kepala Desa Sindangmukti';
    }

    public function getInitiatorNameAttribute()
    {
        return $this->initiator ?? 'Pemerintah Desa';
    }
}
