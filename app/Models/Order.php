<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'order_code',
        'status',
        'total_price',
        'shipping_method',
        'shipping_cost',
        'discount_type',
        'discount_value',
        'discount_amount',
        'shipping_address',
        'shipping_province_id',
        'shipping_province',
        'shipping_city_id',
        'shipping_city',
        'shipping_district_id',
        'shipping_district',
        'shipping_village_id',
        'shipping_village',
        'shipping_postal_code',
        'shipping_address_detail',
        'shipping_phone',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'shipping_cost' => 'decimal:2',
        'total_price' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function getHasDiscountAttribute()
    {
        return $this->discount_amount > 0;
    }

    public function getDiscountLabelAttribute()
    {
        if (!$this->has_discount) {
            return null;
        }

        if ($this->discount_type === 'percent') {
            $value = rtrim(rtrim(number_format((float) $this->discount_value, 2, ',', '.'), '0'), ',');
            return 'Diskon ' . $value . '%';
        }

        return 'Potongan Rp ' . number_format((float) $this->discount_value, 0, ',', '.');
    }
}
