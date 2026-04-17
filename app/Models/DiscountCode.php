<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'minimum_order_amount',
        'usage_limit',
        'usage_count',
        'valid_from',
        'valid_until',
        'is_active'
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean'
    ];

    /**
     * Kiểm tra xem mã giảm giá có còn hợp lệ không
     */
    public function isValid()
    {
        if (!$this->is_active) return false;
        
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        $now = now();
        if ($this->valid_from && $now < $this->valid_from) return false;
        if ($this->valid_until && $now > $this->valid_until) return false;

        return true;
    }

    /**
     * Kiểm tra xem tổng đơn có đạt tối thiểu không
     */
    public function meetsMinimumAmount($total): bool
    {
        if (!$this->minimum_order_amount) {
            return true;
        }

        return $total >= $this->minimum_order_amount;
    }

    /**
     * Tính toán số tiền giảm
     */
    public function calculateDiscount($total)
    {
        // Kiểm tra tối thiểu
        if (!$this->meetsMinimumAmount($total)) {
            return 0;
        }

        if ($this->type === 'percentage') {
            return (int)($total * $this->value / 100);
        } else {
            return min((int)$this->value, $total); // Không được âm
        }
    }

    /**
     * Tăng số lần sử dụng
     */
    public function incrementUsageCount()
    {
        $this->increment('usage_count');
    }
}
