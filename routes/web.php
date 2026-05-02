<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\DiscountCodeController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;

// ==================== PUBLIC ROUTES ====================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'detail'])->name('product.detail');
Route::get('/product/{product}/reviews', [ReviewController::class, 'showByProduct'])->name('review.show');
Route::get('/brands/{brand:slug}', [ProductController::class, 'showByBrand'])->name('products.by_brand');

// Route cho chức năng tìm kiếm sản phẩm
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

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

    // Account Management
    Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
    Route::post('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::post('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');
});

// ==================== ADMIN ROUTES ====================
Route::middleware(['admin'])->prefix('admin')->group(function () { // Giữ nguyên middleware và prefix
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Products
    Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');

    // Brands Management
    Route::get('/brands', [BrandController::class, 'index'])->name('admin.brands.index');
    Route::get('/brands/create', [BrandController::class, 'create'])->name('admin.brands.create');
    Route::post('/brands', [BrandController::class, 'store'])->name('admin.brands.store');
    Route::post('/brands/store-ajax', [BrandController::class, 'storeAjax'])->name('admin.brands.store.ajax');
    Route::get('/brands/{brand}/edit', [BrandController::class, 'edit'])->name('admin.brands.edit');
    Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('admin.brands.update');
    Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('admin.brands.destroy');
    
    // Categories Management
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::post('/categories/store-ajax', [CategoryController::class, 'storeAjax'])->name('admin.categories.store.ajax');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

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