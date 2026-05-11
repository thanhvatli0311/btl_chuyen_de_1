<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use Illuminate\Pagination\Paginator;

class HomeController extends Controller
{

public function index()
{
    // Lấy tất cả sản phẩm, eager load 'brand' để tối ưu, sắp xếp: featured trước, sau đó newest
    $products = Product::with('brand')
        ->orderByDesc('is_featured')
        ->latest()
        ->get();

    // Lấy tất cả các hãng để hiển thị trong bộ lọc
    $brands = Brand::orderBy('name')->get();

    // Thay vì phân trang, chúng ta sẽ hiển thị tất cả sản phẩm.
    // View sẽ tự xử lý việc nhóm các sản phẩm theo thương hiệu.
    // Điều này đáp ứng yêu cầu hiển thị tất cả sản phẩm trên một trang.
    return view('home', [
        'products' => $products,
        'brands' => $brands
    ]);
}

}