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

    // ─── Relations ────────────────────────────────────────────

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    // ─── Scopes ───────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    // ─── Accessors ────────────────────────────────────────────

    /**
     * Get the product image URL with local fallback.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }

        return asset('assets/img/galeri/galeri1.jpg');
    }

    /**
     * Get formatted price in Rupiah.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get WhatsApp order link.
     */
    public function getWhatsappLinkAttribute(): string
    {
        $phone = $this->seller_phone ?: '6281234567890';
        $text  = urlencode("Halo, saya tertarik dengan produk \"{$this->name}\" di Lapak Desa Sindangmukti. Apakah masih tersedia?");

        return "https://wa.me/{$phone}?text={$text}";
    }

    /**
     * Get stock availability label.
     */
    public function getStockLabelAttribute(): string
    {
        if ($this->stock === null || $this->stock <= 0) {
            return 'Stok Habis';
        }

        if ($this->stock > 50) {
            return "Tersedia (50+ pcs)";
        }

        return "Tersedia ({$this->stock} pcs)";
    }

    // ─── Mutators ─────────────────────────────────────────────

    /**
     * Ensure all saved phone numbers have 628 format.
     */
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
