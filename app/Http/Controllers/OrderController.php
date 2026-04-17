<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\DiscountCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Xử lý checkout
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect('/cart')->with('error', 'Giỏ hàng trống!');
        }

        // Tính tổng tiền
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Kiểm tra số lượng kho trước khi tạo đơn hàng
        foreach ($cart as $item) {
            $product = Product::find($item['id']);
            if (!$product || $product->quantity < $item['quantity']) {
                $available = $product ? $product->quantity : 0;
                return redirect('/cart')->with('error', 
                    "❌ Sản phẩm '{$item['name']}' không đủ số lượng! Chỉ còn {$available} chiếc trong kho.");
            }
        }

        // Xử lý mã giảm giá
        $discountCode = null;
        $discountAmount = 0;
        $finalTotal = $total;

        if ($request->has('discount_code') && !empty($request->input('discount_code'))) {
            $codeInput = $request->input('discount_code');
            $discountCodeObj = DiscountCode::where('code', $codeInput)->first();

            if ($discountCodeObj && $discountCodeObj->isValid()) {
                // Verify again on server side for security
                $discountAmount = $discountCodeObj->calculateDiscount($total);
                $finalTotal = $total - $discountAmount;
                $discountCode = $codeInput;

                // Tăng số lần sử dụng
                $discountCodeObj->incrementUsageCount();
            }
        }

        // Tạo đơn hàng
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $finalTotal,
            'status' => 'pending',
            'shipping_address' => $request->input('shipping_address'),
            'phone' => $request->input('phone'),
            'note' => $request->input('note'),
            'discount_code' => $discountCode,
            'discount_amount' => $discountAmount
        ]);

        // Lưu chi tiết đơn hàng
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);

            // Giảm số lượng sản phẩm trong kho
            $product = Product::find($item['id']);
            $product->quantity -= $item['quantity'];
            $product->save();
        }

        // Xóa giỏ hàng
        session()->forget('cart');
        session()->forget('discount_code');

        return redirect()->route('order.success', ['order' => $order->id]);
    }

    // Hiển thị trang thành công
    public function success(Order $order)
    {
        // Kiểm tra xem đơn hàng có thuộc về người dùng hiện tại không
        if ($order->user_id !== Auth::id()) {
            return redirect('/')->with('error', 'Đơn hàng không tồn tại');
        }

        $orderItems = $order->items;

        return view('order_success', [
            'order' => $order,
            'orderItems' => $orderItems
        ]);
    }

    // Danh sách đơn hàng của người dùng
    public function myOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('my_orders', compact('orders'));
    }
}
