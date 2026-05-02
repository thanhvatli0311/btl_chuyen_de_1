<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // ========== PUBLIC FACING ==========
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

    public function showByBrand(Brand $brand)
    {
        // Lấy sản phẩm thuộc về hãng, có phân trang
        $products = $brand->products()->paginate(12);

        return view('search-results', compact('products', 'brand'));
    }

    /**
     * Tìm kiếm sản phẩm dựa trên query.
     */
    public function search(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'query'       => 'nullable|string|max:100',
            'brand_id'    => 'nullable|integer|exists:brands,id',
            'price_range' => 'nullable|string',
            'sort'        => 'nullable|string|in:latest,price_asc,price_desc',
        ]);

        $queryInput     = $request->input('query');
        $selectedBrandId = $request->input('brand_id');
        $priceRange     = $request->input('price_range');
        $sort           = $request->input('sort');

        // Nếu không có bất kỳ query hay filter nào, chuyển hướng về trang chủ
        if (!$queryInput && !$selectedBrandId && !$priceRange && !$sort) {
            return redirect()->route('home');
        }

        $productQuery = Product::query(); // Bắt đầu xây dựng query

        // Tìm kiếm theo từ khóa
        if ($queryInput) {
            $productQuery->where(function ($q) use ($queryInput) {
                $q->where('name', 'LIKE', "%{$queryInput}%")
                  ->orWhere('description', 'LIKE', "%{$queryInput}%");
            });
        }

        // Lọc theo hãng
        if ($selectedBrandId) {
            $productQuery->where('brand_id', $selectedBrandId);
        }

        // Lọc theo khoảng giá
        if ($priceRange) {
            $prices = explode('-', $priceRange);
            if (count($prices) == 2) {
                $productQuery->whereBetween('price', [(float)$prices[0], (float)$prices[1]]);
            }
        }

        // Sắp xếp
        switch ($sort) {
            case 'price_asc':
                $productQuery->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $productQuery->orderBy('price', 'desc');
                break;
            default:
                $productQuery->latest(); // Mặc định là mới nhất
                break;
        }

        $products = $productQuery->paginate(12);

        // Giữ lại tất cả query string khi chuyển trang
        $products->appends($request->all());

        // Lấy tất cả các hãng để hiển thị form lọc
        $brands = Brand::orderBy('name')->get();

        // Trả về view hiển thị kết quả, truyền các biến để giữ trạng thái filter
        return view('search-results', compact('products', 'brands', 'queryInput', 'selectedBrandId', 'priceRange', 'sort'));
    }
}