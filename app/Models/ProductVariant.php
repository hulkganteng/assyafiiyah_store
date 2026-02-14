<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'option1_name',
        'option1_value',
        'option2_name',
        'option2_value',
        'price',
        'stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getLabelAttribute()
    {
        $parts = [];
        if ($this->option1_value) {
            $parts[] = ($this->option1_name ?: 'Varian 1') . ': ' . $this->option1_value;
        }
        if ($this->option2_value) {
            $parts[] = ($this->option2_name ?: 'Varian 2') . ': ' . $this->option2_value;
        }

        return implode(' / ', $parts);
    }
}
