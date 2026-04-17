<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Admin\DiscountCodeController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;

// ==================== PUBLIC ROUTES ====================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'detail'])->name('product.detail');
Route::get('/product/{product}/reviews', [ReviewController::class, 'showByProduct'])->name('review.show');

// Chatbot API
Route::middleware(['auth'])->group(function () {
    Route::post('/api/chatbot/send', [ChatbotController::class, 'sendMessage'])->name('chatbot.send');
    Route::get('/api/chatbot/history', [ChatbotController::class, 'getChatHistory'])->name('chatbot.history');
});

// ==================== AUTHENTICATION ROUTES ====================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==================== AUTHENTICATED CUSTOMER ROUTES ====================
Route::middleware(['auth'])->group(function () {
    // Cart
    Route::post('/add-cart/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    
    // Orders
    Route::post('/order/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
    Route::get('/order/success/{order}', [OrderController::class, 'success'])->name('order.success');
    Route::get('/orders', [OrderController::class, 'myOrders'])->name('orders.list');
    
    // Reviews
    Route::get('/order/{order}/review', [ReviewController::class, 'create'])->name('review.create');
    Route::post('/order/{order}/review', [ReviewController::class, 'store'])->name('review.store');
});

// ==================== ADMIN ROUTES ====================
Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Products
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('admin.products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');
    
    // Orders
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/orders/{order}', [AdminController::class, 'orderDetail'])->name('admin.orders.detail');
    Route::put('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');
    
    // Discount Codes
    Route::get('/discount-codes', [DiscountCodeController::class, 'index'])->name('admin.discount-codes.index');
    Route::get('/discount-codes/create', [DiscountCodeController::class, 'create'])->name('admin.discount-codes.create');
    Route::post('/discount-codes', [DiscountCodeController::class, 'store'])->name('admin.discount-codes.store');
    Route::get('/discount-codes/{code}/edit', [DiscountCodeController::class, 'edit'])->name('admin.discount-codes.edit');
    Route::put('/discount-codes/{code}', [DiscountCodeController::class, 'update'])->name('admin.discount-codes.update');
    Route::delete('/discount-codes/{code}', [DiscountCodeController::class, 'destroy'])->name('admin.discount-codes.destroy');
    Route::patch('/discount-codes/{code}/toggle', [DiscountCodeController::class, 'toggle'])->name('admin.discount-codes.toggle');
    
    // Reviews
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('admin.reviews.index');
    Route::get('/reviews/{review}', [AdminReviewController::class, 'show'])->name('admin.reviews.show');
    Route::post('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('admin.reviews.approve');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'reject'])->name('admin.reviews.destroy');
    
    // Chatbot Management (Q&A Training)
    Route::get('/chatbot', [AdminController::class, 'chatbot'])->name('admin.chatbot');
    Route::get('/chatbot/create', [AdminController::class, 'createChatbot'])->name('admin.chatbot.create');
    Route::post('/chatbot', [AdminController::class, 'storeChatbot'])->name('admin.chatbot.store');
    Route::get('/chatbot/{response}/edit', [AdminController::class, 'editChatbot'])->name('admin.chatbot.edit');
    Route::put('/chatbot/{response}', [AdminController::class, 'updateChatbot'])->name('admin.chatbot.update');
    Route::delete('/chatbot/{response}', [AdminController::class, 'deleteChatbot'])->name('admin.chatbot.delete');
    
    // Chat Messages (Customer inquiries)
    Route::get('/messages', [AdminController::class, 'messages'])->name('admin.messages');
    Route::get('/messages/{message}', [AdminController::class, 'messageDetail'])->name('admin.messages.detail');
    Route::put('/messages/{message}/reply', [AdminController::class, 'replyMessage'])->name('admin.messages.reply');
    Route::delete('/messages/{message}', [AdminController::class, 'deleteMessage'])->name('admin.messages.delete');
});