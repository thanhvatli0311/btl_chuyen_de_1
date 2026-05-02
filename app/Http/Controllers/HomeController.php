<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;

class HomeController extends Controller
{

public function index()
{
    // Lấy sản phẩm mới nhất, phân trang 12 sản phẩm/trang
    $products = Product::latest()->paginate(12);
    // Lấy tất cả các hãng để hiển thị trong bộ lọc
    $brands = Brand::orderBy('name')->get();
    
    return view('home', compact('products', 'brands'));
}

}