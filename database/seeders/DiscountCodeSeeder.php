<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DiscountCode;

class DiscountCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $codes = [
            [
                'code' => 'SAVE10',
                'description' => 'Giảm 10% cho tất cả sản phẩm',
                'type' => 'percentage',
                'value' => 10,
                'minimum_order_amount' => 0,
                'usage_limit' => 100,
                'usage_count' => 0,
                'valid_from' => now(),
                'valid_until' => now()->addMonth(),
                'is_active' => true,
            ],
            [
                'code' => 'SAVE20',
                'description' => 'Giảm 20% cho đơn hàng từ 1 triệu đồng',
                'type' => 'percentage',
                'value' => 20,
                'minimum_order_amount' => 1000000,
                'usage_limit' => 50,
                'usage_count' => 0,
                'valid_from' => now(),
                'valid_until' => now()->addMonth(),
                'is_active' => true,
            ],
            [
                'code' => 'NEWYEAR500K',
                'description' => 'Giảm 500k cho đơn hàng từ 2 triệu đồng',
                'type' => 'fixed_amount',
                'value' => 500000,
                'minimum_order_amount' => 2000000,
                'usage_limit' => 30,
                'usage_count' => 0,
                'valid_from' => now(),
                'valid_until' => now()->addMonth(),
                'is_active' => true,
            ],
            [
                'code' => 'SUMMER15',
                'description' => 'Giảm 15% nhân dịp hè',
                'type' => 'percentage',
                'value' => 15,
                'minimum_order_amount' => 500000,
                'usage_limit' => 200,
                'usage_count' => 0,
                'valid_from' => now(),
                'valid_until' => now()->addMonth(),
                'is_active' => true,
            ],
        ];

        foreach ($codes as $code) {
            DiscountCode::firstOrCreate(
                ['code' => $code['code']],
                $code
            );
        }
    }
}
