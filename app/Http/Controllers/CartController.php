<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())
            ->with('product') // Tải trước thông tin sản phẩm để tối ưu
            ->get();

        $total = 0;
        foreach ($cartItems as $item) {
            if ($item->product) {
                $total += $item->product->price * $item->quantity;
            }
        }
        
        return view('cart', [
            'cart' => $cartItems, // Truyền collection CartItem
            'total' => $total
        ]);
    }

    // Thêm sản phẩm vào giỏ (yêu cầu đăng nhập)
    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $user = Auth::user();

        // Kiểm tra số lượng kho
        if ($product->quantity <= 0) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => '❌ Sản phẩm này đã hết hàng!'], 400);
            }
            return back()->with('error', '❌ Sản phẩm này đã hết hàng!');
        }

        // Tìm sản phẩm trong giỏ hàng của user
        $cartItem = CartItem::where('user_id', $user->id)
                            ->where('product_id', $product->id)
                            ->first();

        if ($cartItem) {
            // Nếu sản phẩm đã có trong giỏ, kiểm tra số lượng mới
            $newQuantity = $cartItem->quantity + 1;
            if ($newQuantity > $product->quantity) {
                $message = "❌ Số lượng vượt quá kho! Chỉ còn {$product->quantity} chiếc.";
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 400);
                }
                return back()->with('error', $message);
            }
            // Tăng số lượng
            $cartItem->increment('quantity');
        } else {
            // Thêm sản phẩm mới vào giỏ hàng trong DB
            CartItem::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => 1
            ]);
        }

        // Lấy tổng số loại sản phẩm trong giỏ hàng từ DB
        $cartCount = CartItem::where('user_id', $user->id)->count();

        if ($request->wantsJson()) {
            return response()->json([
                'success'   => true,
                'message'   => '✅ Đã thêm vào giỏ hàng!',
                'cartCount' => $cartCount
            ]);
        }

        return redirect('/cart')->with('success', 'Thêm vào giỏ hàng thành công!');
    }

    // Cập nhật số lượng sản phẩm
    public function update(Request $request, $productId)
    {
        $quantity = $request->input('quantity');

        // Tìm cart item dựa trên product_id và user_id
        $cartItem = CartItem::where('product_id', $productId)->where('user_id', Auth::id())->firstOrFail();
        $product = $cartItem->product;

        // Kiểm tra số lượng yêu cầu không vượt quá kho
        if ($quantity > $product->quantity) {
            return redirect('/cart')->with('error', 
                "❌ Số lượng vượt quá kho! Chỉ còn {$product->quantity} chiếc trong kho.");
        }

        if ($quantity > 0) {
            $cartItem->update(['quantity' => $quantity]);
        } else {
            $cartItem->delete(); // Xóa nếu số lượng <= 0
        }
        
        return redirect('/cart')->with('success', 'Cập nhật giỏ hàng thành công!');
    }

    // Xóa sản phẩm khỏi giỏ
    public function remove($productId)
    {
        // Tìm và xóa cart item dựa trên product_id và user_id
        CartItem::where('product_id', $productId)->where('user_id', Auth::id())->delete();

        return redirect('/cart')->with('success', 'Xóa sản phẩm khỏi giỏ hàng!');
    }
}