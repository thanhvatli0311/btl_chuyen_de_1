<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\DiscountCode;

class DiscountApiTest extends TestCase
{
    // Sử dụng RefreshDatabase để Laravel tự động reset dữ liệu test sau khi chạy xong,
    // tránh làm rác database thật của bạn
    use RefreshDatabase;

    /**
     * Test case: Kiểm tra áp dụng mã giảm giá hợp lệ thành công
     */
    public function test_valid_discount_code_returns_success(): void
    {
        // 1. Chuẩn bị dữ liệu: Tạo 1 mã giảm giá ảo trong database test
        DiscountCode::create([
            'code' => 'TEST10',
            'description' => 'Giảm 10% test',
            'type' => 'percentage',
            'value' => 10,
            'minimum_order_amount' => 500000,
            'usage_limit' => 100,
            'usage_count' => 0,
            'valid_from' => now()->subDay(),
            'valid_until' => now()->addDays(30),
            'is_active' => true,
        ]);

        // 2. Thực thi: Giả lập Frontend gọi API lên Backend
        $response = $this->postJson('/api/discount/verify', [
            'code' => 'TEST10',
            'total' => 1000000 // Đơn hàng 1 triệu
        ]);

        // 3. Khẳng định (Assert): Backend phải trả về HTTP 200 và chuỗi JSON báo thành công
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'discount_amount' => 100000, // 10% của 1 củ = 100k
                     'final_total' => 900000
                 ]);
    }

    /**
     * Test case: Kiểm tra mã giảm giá sai
     */
    public function test_invalid_discount_code_returns_error(): void
    {
        // Thực thi gọi API với mã không tồn tại
        $response = $this->postJson('/api/discount/verify', [
            'code' => 'WRONG_CODE',
            'total' => 500000
        ]);

        // Khẳng định: Trả về HTTP 404 và báo lỗi
        $response->assertStatus(404)
                 ->assertJson([
                     'success' => false
                 ]);
    }
}