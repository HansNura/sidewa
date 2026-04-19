<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'title',
        'slug',
        'content_html',
        'meta_title',
        'meta_description',
        'type',
        'status',
        'order',
    ];

    public function parent()
    {
        return $this->belongsTo(PublicPage::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(PublicPage::class, 'parent_id')->orderBy('order', 'asc');
    }
}
