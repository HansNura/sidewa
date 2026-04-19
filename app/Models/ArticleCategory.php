<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleCategory extends Model
{
    use HasFactory;

    protected $table = 'article_categories';

    protected $fillable = [
        'nama_kategori',
        'slug',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class, 'kategori_id');
    }
}
