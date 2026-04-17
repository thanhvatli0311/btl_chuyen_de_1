<?php

namespace App\Http\Controllers\Admin;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    /**
     * Hiển thị danh sách reviews cần duyệt
     */
    public function index()
    {
        $reviews = Review::with('user', 'product', 'order')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.reviews.index', ['reviews' => $reviews]);
    }

    /**
     * Duyệt review
     */
    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);

        return redirect()->route('admin.reviews.index')
            ->with('success', '✅ Đánh giá đã được phê duyệt!');
    }

    /**
     * Từ chối/xóa review
     */
    public function reject(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', '✅ Đánh giá đã bị xóa!');
    }

    /**
     * Xem chi tiết review
     */
    public function show(Review $review)
    {
        return view('admin.reviews.show', ['review' => $review]);
    }
}
