<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Hiển thị form đánh giá sản phẩm từ đơn hàng
     */
    public function create(Order $order)
    {
        // Kiểm tra xem đơn hàng có thuộc về user hiện tại không
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại');
        }

        // Kiểm tra xem đơn hàng đã hoàn thành chưa
        if ($order->status !== 'completed') {
            return redirect()->back()->with('error', 'Chỉ có thể đánh giá khi đơn hàng hoàn thành');
        }

        // Lấy danh sách sản phẩm trong đơn hàng
        $products = $order->items()->with('product')->get();

        // Kiểm tra xem đã review hết chưa
        $reviewedProductIds = Review::where('order_id', $order->id)
            ->pluck('product_id')
            ->toArray();

        return view('review.create', [
            'order' => $order,
            'products' => $products,
            'reviewedProductIds' => $reviewedProductIds
        ]);
    }

    /**
     * Lưu đánh giá
     */
    public function store(Request $request, Order $order)
    {
        // Kiểm tra quyền
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền truy cập');
        }

        // Kiểm tra trạng thái đơn hàng
        if ($order->status !== 'completed') {
            return redirect()->back()->with('error', 'Không thể đánh giá đơn hàng này');
        }

        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000'
        ]);

        // Kiểm tra sản phẩm có trong đơn hàng không
        $orderItem = $order->items()->where('product_id', $validated['product_id'])->first();
        if (!$orderItem) {
            return redirect()->back()->with('error', 'Sản phẩm không có trong đơn hàng này');
        }

        // Kiểm tra xem đã review sản phẩm này từ đơn hàng này chưa
        $existingReview = Review::where('order_id', $order->id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi');
        }

        // Tạo review
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'],
            'order_id' => $order->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'is_approved' => false
        ]);

        return redirect()->route('review.create', $order->id)
            ->with('success', '✅ Đánh giá của bạn đã được gửi. Cảm ơn bạn!');
    }

    /**
     * Hiển thị đánh giá sản phẩm (cho trang product detail)
     */
    public function showByProduct($productId)
    {
        $product = Product::findOrFail($productId);

        // Lấy những review đã được approve
        $reviews = Review::where('product_id', $productId)
            ->where('is_approved', true)
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(5);

        // Tính toán rating trung bình
        $averageRating = Review::where('product_id', $productId)
            ->where('is_approved', true)
            ->avg('rating');

        return view('review.show', [
            'product' => $product,
            'reviews' => $reviews,
            'averageRating' => $averageRating ?? 0
        ]);
    }
}
