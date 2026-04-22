<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kategori_id',
        'judul',
        'slug',
        'konten_html',
        'ringkasan',
        'cover_image',
        'status',
        'published_at',
        'view_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // ── Scopes ──

    public function scopePublished($query)
    {
        return $query->whereIn('status', ['published', 'publish'])
                     ->where('published_at', '<=', now());
    }

    // ── Relationships ──

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'kategori_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tag');
    }

    // ── Accessors ──

    public function getThumbnailUrlAttribute()
    {
        return $this->cover_image
            ? asset('storage/' . $this->cover_image)
            : asset('assets/img/berita/berita1.jpg');
    }

    public function getAuthorNameAttribute()
    {
        return $this->user?->name ?? 'Admin Desa';
    }

    public function getCategoryNameAttribute()
    {
        return $this->category?->nama_kategori ?? 'Umum';
    }

    public function getFormattedDateAttribute()
    {
        return $this->published_at
            ? $this->published_at->translatedFormat('d M Y')
            : $this->created_at->translatedFormat('d M Y');
    }

    public function getExcerptAttribute()
    {
        if ($this->ringkasan) return $this->ringkasan;
        return \Str::limit(strip_tags($this->konten_html), 160);
    }
}
