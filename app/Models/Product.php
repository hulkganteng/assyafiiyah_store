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
        'description',
        'price',
        'stock',
        'is_active',
        'is_preorder',
        'discount_type',
        'discount_value',
        'discount_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_preorder' => 'boolean',
        'discount_active' => 'boolean',
        'price' => 'decimal:2',
        'discount_value' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getHasActiveDiscountAttribute()
    {
        return $this->discount_active && $this->discount_type && $this->discount_value > 0;
    }

    public function getDiscountedPriceAttribute()
    {
        $price = (float) $this->price;
        if (!$this->has_active_discount) {
            return $price;
        }

        return $this->discountPrice($price);
    }

    public function getDiscountLabelAttribute()
    {
        if (!$this->has_active_discount) {
            return null;
        }

        if ($this->discount_type === 'percent') {
            $value = rtrim(rtrim(number_format((float) $this->discount_value, 2, ',', '.'), '0'), ',');
            return 'Diskon ' . $value . '%';
        }

        return 'Potongan Rp ' . number_format((float) $this->discount_value, 0, ',', '.');
    }

    public function discountPrice(float $basePrice): float
    {
        $price = $basePrice;
        $value = (float) $this->discount_value;

        if ($this->discount_type === 'percent') {
            $price -= ($price * ($value / 100));
        } elseif ($this->discount_type === 'fixed') {
            $price -= $value;
        }

        return max(0, round($price, 2));
    }
}
