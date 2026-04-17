<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\DiscountCode;
use App\Models\Review;
use App\Models\ChatbotResponse;
use App\Models\ChatbotMessage;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        // Core Stats
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_price');
        $totalUsers = \App\Models\User::count();
        $recentOrders = Order::with('user')->latest()->limit(10)->get();

        // Customer Messages (Inquiries) Stats
        $pendingMessagesCount = ChatbotMessage::where('status', 'pending')->count();
        $totalMessagesCount = ChatbotMessage::count();
        $recentMessages = ChatbotMessage::with('user')->latest()->limit(8)->get();

        // Chatbot Knowledge Base (Auto-Responses) Stats
        $totalChatbotResponses = ChatbotResponse::count();
        $activeChatbotResponses = ChatbotResponse::where('is_active', true)->count();

        return view('admin.dashboard', [
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'totalUsers' => $totalUsers,
            'recentOrders' => $recentOrders,
            'pendingMessagesCount' => $pendingMessagesCount,
            'totalMessagesCount' => $totalMessagesCount,
            'recentMessages' => $recentMessages,
            'totalChatbotResponses' => $totalChatbotResponses,
            'activeChatbotResponses' => $activeChatbotResponses,
        ]);
    }

    // ========== PRODUCTS ==========
    public function products()
    {
        $products = Product::with('category')->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'quantity' => $validated['quantity'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'image' => $imageName
        ]);

        return redirect()->route('admin.products')->with('success', 'Sản phẩm được thêm thành công!');
    }

    public function editProduct(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $validated['image'] = $imageName;
        }

        $product->update($validated);

        return redirect()->route('admin.products')->with('success', 'Sản phẩm cập nhật thành công!');
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Sản phẩm xóa thành công!');
    }

    // ========== ORDERS ==========
    public function orders()
    {
        $orders = Order::with('user')->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        $orderItems = $order->items;
        return view('admin.orders.detail', compact('order', 'orderItems'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update($validated);
        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    // ========== DISCOUNT CODES ==========
    public function discountCodes()
    {
        $codes = DiscountCode::paginate(15);
        return view('admin.discounts.index', compact('codes'));
    }

    public function createDiscount()
    {
        return view('admin.discounts.create');
    }

    public function storeDiscount(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:discount_codes|max:50',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'is_active' => 'boolean'
        ]);

        DiscountCode::create($validated);

        return redirect()->route('admin.discounts')->with('success', 'Mã giảm giá được thêm thành công!');
    }

    public function editDiscount(DiscountCode $code)
    {
        return view('admin.discounts.edit', compact('code'));
    }

    public function updateDiscount(Request $request, DiscountCode $code)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:discount_codes,code,' . $code->id . '|max:50',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'is_active' => 'boolean'
        ]);

        $code->update($validated);

        return redirect()->route('admin.discounts')->with('success', 'Mã giảm giá cập nhật thành công!');
    }

    public function deleteDiscount(DiscountCode $code)
    {
        $code->delete();
        return redirect()->route('admin.discounts')->with('success', 'Mã giảm giá xóa thành công!');
    }

    // ========== REVIEWS ==========
    public function reviews()
    {
        $reviews = Review::with('user', 'product')->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approveReview(Review $review)
    {
        $review->update(['is_approved' => true]);
        return redirect()->back()->with('success', 'Phê duyệt đánh giá thành công!');
    }

    public function rejectReview(Review $review)
    {
        $review->delete();
        return redirect()->back()->with('success', 'Từ chối đánh giá!');
    }

    // ========== CHATBOT ==========
    public function chatbot()
    {
        $responses = ChatbotResponse::paginate(15);
        return view('admin.chatbot.index', compact('responses'));
    }

    public function createChatbot()
    {
        return view('admin.chatbot.create');
    }

    public function storeChatbot(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'category' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $validated['created_by'] = auth()->id();
        ChatbotResponse::create($validated);

        return redirect()->route('admin.chatbot')->with('success', 'Câu hỏi được thêm thành công!');
    }

    public function editChatbot(ChatbotResponse $response)
    {
        return view('admin.chatbot.edit', compact('response'));
    }

    public function updateChatbot(Request $request, ChatbotResponse $response)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'category' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $response->update($validated);

        return redirect()->route('admin.chatbot')->with('success', 'Câu hỏi cập nhật thành công!');
    }

    public function deleteChatbot(ChatbotResponse $response)
    {
        $response->delete();
        return redirect()->route('admin.chatbot')->with('success', 'Câu hỏi xóa thành công!');
    }

    // ========== CHATBOT MESSAGES (Customer Inquiries) ==========
    public function messages()
    {
        $messages = ChatbotMessage::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $pendingCount = ChatbotMessage::where('status', 'pending')->count();

        return view('admin.messages.index', [
            'messages' => $messages,
            'pendingCount' => $pendingCount
        ]);
    }

    public function messageDetail(ChatbotMessage $message)
    {
        $conversation = ChatbotMessage::with('user')
            ->conversationForMessage($message)
            ->get();

        return view('admin.messages.detail', [
            'message' => $message,
            'conversation' => $conversation,
            'conversationOwner' => $conversation->first(),
        ]);
    }

    public function replyMessage(Request $request, ChatbotMessage $message)
    {
        $validated = $request->validate([
            'response' => 'required|string|min:1|max:2000'
        ]);

        $adminName = auth()->user()->name ?? 'Admin';
        $replyText = trim($validated['response']);
        $replyPrefix = '[Admin: ' . $adminName . '] ';
        $finalResponse = str_starts_with($replyText, $replyPrefix) ? $replyText : $replyPrefix . $replyText;

        $message->update([
            'response' => $finalResponse,
            'status' => 'answered',
            'is_auto_reply' => false
        ]);

        return redirect()->route('admin.messages.detail', $message)->with('success', 'Tin nhắn đã được trả lời!');
    }

    public function deleteMessage(ChatbotMessage $message)
    {
        $message->delete();
        return redirect()->route('admin.messages')->with('success', 'Tin nhắn đã được xóa!');
    }

    // Legacy method - for backward compatibility
    public function addForm()
    {
        return $this->createProduct();
    }

    public function store(Request $request)
    {
        return $this->storeProduct($request);
    }
}
