<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Watch Store - Bán đồng hồ chính hãng</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f5f6fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.navbar{
    background: linear-gradient(135deg, #000 0%, #333 100%);
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

.navbar a, .navbar span {
    transition: 0.3s;
}

.navbar a:hover {
    text-shadow: 0 0 10px rgba(255,255,255,0.5);
}

.card {
    transition: 0.3s;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
}

.price {
    color: #dc3545;
    font-weight: bold;
    font-size: 1.2rem;
}

.footer {
    background: linear-gradient(135deg, #000 0%, #333 100%);
    color: white;
    text-align: center;
    padding: 20px;
    margin-top: 50px;
}

.alert {
    margin-top: 20px;
}

</style>

</head>

<body>

<nav class="navbar navbar-expand-lg p-3 mb-4">
    <div class="container-fluid">
        <a class="navbar-brand text-white fw-bold" href="/">⌚ WATCH STORE</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="/">🏠 Trang chủ</a>
                </li>
                
                @auth
                    @if(Auth::user()->isCustomer())
                        <li class="nav-item">
                            <a class="nav-link text-white position-relative" href="/cart">
                                🛒 Giỏ hàng
                                @if(session()->has('cart') && count(session('cart')) > 0)
                                    <span class="badge bg-danger position-absolute start-100 translate-middle">{{ count(session('cart')) }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/orders">📋 Đơn hàng của tôi</a>
                        </li>
                    @endif
                    @if(Auth::user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link text-warning fw-bold" href="/admin/dashboard">⚙️ Admin Panel</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <span class="nav-link text-warning">👤 {{ Auth::user()->name }}</span>
                    </li>
                    <li class="nav-item">
                        <form action="/logout" method="POST" class="d-inline">
                            @csrf
                            <button class="nav-link btn btn-link text-white" type="submit">🚪 Đăng xuất</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/login">🔓 Đăng nhập</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/register">✍️ Đăng ký</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">

<!-- Hiển thị thông báo thành công -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>✅ Thành công!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Hiển thị thông báo lỗi -->
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>❌ Lỗi!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@yield('content')

</div>

<div class="footer">
    Watch Store - Bán đồng hồ chính hãng ⌚
</div>

<!-- Chatbot Widget -->
@include('components.chatbot-widget')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>