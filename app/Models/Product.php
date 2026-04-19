<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'price',
        'stock',
        'seller_name',
        'seller_phone',
        'description_html',
        'image_path',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    // Mutator for Whatsapp formatting
    // Ensure all saved phone numbers have 628 format
    public function setSellerPhoneAttribute($value)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $value);

        // Convert starting '0' to '62'
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        $this->attributes['seller_phone'] = $phone;
    }
}
