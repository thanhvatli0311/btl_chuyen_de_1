<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Kiểm tra mã giảm giá (API endpoint)
     */
    public function verify(Request $request)
    {
        $code = $request->input('code');
        $total = $request->input('total');

        if (!$code || !$total) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá hoặc tổng tiền không hợp lệ'
            ], 400);
        }

        // Tìm mã giảm giá
        $discountCode = DiscountCode::where('code', $code)->first();

        if (!$discountCode) {
            return response()->json([
                'success' => false,
                'message' => '❌ Mã giảm giá không tồn tại'
            ], 404);
        }

        // Kiểm tra xem mã có được kích hoạt không
        if (!$discountCode->is_active) {
            return response()->json([
                'success' => false,
                'message' => '❌ Mã giảm giá đã bị vô hiệu hóa'
            ], 422);
        }

        // Kiểm tra xem mã có còn hợp lệ không
        if (!$discountCode->isValid()) {
            return response()->json([
                'success' => false,
                'message' => '❌ Mã giảm giá đã hết hiệu lực hoặc đạt giới hạn sử dụng'
            ], 422);
        }

        // Kiểm tra tối thiểu
        if (!$discountCode->meetsMinimumAmount($total)) {
            $minAmount = number_format($discountCode->minimum_order_amount, 0, ',', '.');
            return response()->json([
                'success' => false,
                'message' => "❌ Mã giảm giá cần tối thiểu {$minAmount}₫"
            ], 422);
        }

        // Tính tiền giảm
        $discountAmount = $discountCode->calculateDiscount($total);
        $finalTotal = $total - $discountAmount;

        return response()->json([
            'success' => true,
            'message' => '✅ Áp dụng mã giảm giá thành công!',
            'discount_amount' => $discountAmount,
            'final_total' => $finalTotal,
            'discount_type' => $discountCode->type,
            'discount_value' => $discountCode->value
        ]);
    }
}
