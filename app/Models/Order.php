<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'shipping_address',
        'phone',
        'note',
        'discount_code',
        'discount_amount'
    ];

    protected $casts = [
        'total_price' => 'integer',
        'discount_amount' => 'integer',
    ];

    // Relationship với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship với OrderItem
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relationship với Review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
