<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('cart', [
            'cart' => $cart,
            'total' => $total
        ]);
    }

    // Thêm sản phẩm vào giỏ (yêu cầu đăng nhập)
    public function add($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return redirect('/')->with('error', 'Sản phẩm không tồn tại');
        }

        // Kiểm tra số lượng kho
        if ($product->quantity <= 0) {
            return redirect("/product/{$id}")->with('error', '❌ Sản phẩm này đã hết hàng!');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            // Nếu sản phẩm đã có trong giỏ, kiểm tra số lượng mới
            $newQuantity = $cart[$id]['quantity'] + 1;
            if ($newQuantity > $product->quantity) {
                return redirect("/product/{$id}")->with('error', 
                    "❌ Số lượng vượt quá kho! Chỉ còn {$product->quantity} chiếc trong kho.");
            }
            $cart[$id]['quantity'] = $newQuantity;
        } else {
            // Thêm sản phẩm mới
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => 1
            ];
        }

        session()->put('cart', $cart);

        return redirect('/cart')->with('success', 'Thêm vào giỏ hàng thành công!');
    }

    // Cập nhật số lượng sản phẩm
    public function update(Request $request, $id)
    {
        $quantity = $request->input('quantity');
        $product = Product::find($id);
        
        if (!$product) {
            return redirect('/cart')->with('error', 'Sản phẩm không tồn tại');
        }

        // Kiểm tra số lượng yêu cầu không vượt quá kho
        if ($quantity > $product->quantity) {
            return redirect('/cart')->with('error', 
                "❌ Số lượng vượt quá kho! Chỉ còn {$product->quantity} chiếc trong kho.");
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($quantity > 0) {
                $cart[$id]['quantity'] = $quantity;
            } else {
                unset($cart[$id]);
            }
        }

        session()->put('cart', $cart);
        
        return redirect('/cart')->with('success', 'Cập nhật giỏ hàng thành công!');
    }

    // Xóa sản phẩm khỏi giỏ
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        session()->put('cart', $cart);

        return redirect('/cart')->with('success', 'Xóa sản phẩm khỏi giỏ hàng!');
    }
}