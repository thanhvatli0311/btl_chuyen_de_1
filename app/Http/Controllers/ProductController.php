<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;

class ProductController extends Controller
{
    public function detail($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return redirect('/')->with('error', 'Sản phẩm không tồn tại');
        }

        // Lấy các đánh giá đã được phê duyệt (chỉ 3 đánh giá gần nhất)
        $reviews = Review::where('product_id', $id)
            ->where('is_approved', true)
            ->with('user')
            ->latest()
            ->limit(3)
            ->get();

        // Tính số đánh giá total
        $reviewCount = Review::where('product_id', $id)
            ->where('is_approved', true)
            ->count();

        // Tính điểm đánh giá trung bình
        $averageRating = Review::where('product_id', $id)
            ->where('is_approved', true)
            ->avg('rating') ?? 0;

        return view('product_detail', compact('product', 'reviews', 'averageRating', 'reviewCount'));
    }
}