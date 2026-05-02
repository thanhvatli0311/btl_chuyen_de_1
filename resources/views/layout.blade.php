<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Watch Store - Bán đồng hồ chính hãng</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Google Fonts for elegant typography -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
<!-- Font Awesome for social icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    /* --- NEW ELEGANT STYLES --- */
    body {
        background-color: #f8f9fa; /* Softer white background */
        font-family: 'Lato', sans-serif;
        color: #495057;
    }

    /* Typography */
    h1, h2, h3, h4, h5, h6, .navbar-brand, .product-name {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
    }

    /* Navbar */
    .navbar {
        background-color: #1a1a1a !important; /* Solid, deep black for luxury feel */
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .navbar .nav-link {
        transition: color 0.3s ease;
    }

    .navbar .nav-link:hover {
        color: #ffffff !important;
    }

    /* --- NEW FOOTER --- */
    .site-footer {
        background-color: #1a1a1a;
        color: #a9a9a9;
        padding: 60px 0;
        font-size: 0.9rem;
        margin-top: 50px;
    }

    .site-footer h5 {
        color: #ffffff;
        font-size: 1rem;
        text-transform: uppercase;
        margin-bottom: 20px;
        letter-spacing: 1px;
    }

    .site-footer p, .site-footer a {
        color: #a9a9a9;
        text-decoration: none;
    }

    .site-footer a:hover {
        color: #ffffff;
    }

    .footer-links {
        list-style: none;
        padding-left: 0;
    }

    .footer-links li {
        margin-bottom: 10px;
    }

    .social-icons {
        list-style: none;
        padding-left: 0;
        display: flex;
        gap: 15px;
    }

    .social-icons a {
        font-size: 1.2rem;
        transition: color 0.3s ease;
    }

    .footer-bottom {
        border-top: 1px solid #333;
        padding-top: 20px;
        margin-top: 40px;
        text-align: center;
        font-size: 0.8rem;
    }
    
</style>
@yield('styles')

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark p-3 mb-4">
    <div class="container-fluid">
        <a class="navbar-brand text-white fw-bold" href="/">⌚ WATCH STORE</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            
            <!-- Search Form -->
            <form class="d-flex ms-auto me-3" action="{{ route('products.search') }}" method="GET" role="search">
                <input class="form-control me-2" type="search" name="query" placeholder="Tìm kiếm sản phẩm..." aria-label="Search" value="{{ request('query') ?? '' }}">
                <button class="btn btn-outline-light" type="submit">Tìm</button>
            </form>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white" href="/">🏠 Trang chủ</a>
                </li>

                @auth
                    @if(Auth::user()->isCustomer())
                        <li class="nav-item">
                            <a class="nav-link text-white position-relative" href="/cart">
                                🛒 Giỏ hàng
                                <!-- Biến $cartCount được cung cấp bởi ViewServiceProvider -->
                                <span id="cart-count" class="badge bg-danger position-absolute top-0 start-100 translate-middle" @if(empty($cartCount) || $cartCount === 0) style="display: none;" @endif>
                                    {{ $cartCount ?? 0 }}
                                </span>
                            </a>
                        </li>
                    @endif

                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-warning" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            👤 {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="userDropdown">
                            @if(Auth::user()->isCustomer())
                                <li><a class="dropdown-item" href="/orders">📋 Đơn hàng của tôi</a></li>
                            @endif
                            @if(Auth::user()->isAdmin())
                                <li><a class="dropdown-item" href="/admin/dashboard">⚙️ Admin Panel</a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('account.edit') }}">👤 Tài khoản của tôi</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="/logout" method="POST" class="d-inline w-100">
                                    @csrf
                                    <button class="dropdown-item" type="submit">🚪 Đăng xuất</button>
                                </form>
                            </li>
                        </ul>
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

<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5>Về [Tên Công Ty]</h5>
                <p>
                    Chuyên cung cấp các mẫu đồng hồ chính hãng từ những thương hiệu hàng đầu thế giới.
                    Chúng tôi cam kết chất lượng, dịch vụ hậu mãi và trải nghiệm mua sắm tốt nhất cho khách hàng.
                </p>
            </div>

            <div class="col-md-2 col-6 mb-4">
                <h5>Sản phẩm</h5>
                <ul class="footer-links">
                    <li><a href="#">Đồng Hồ Nam</a></li>
                    <li><a href="#">Đồng Hồ Nữ</a></li>
                    <li><a href="#">Phụ Kiện</a></li>
                    <li><a href="#">Thương Hiệu</a></li>
                </ul>
            </div>

            <div class="col-md-3 col-6 mb-4">
                <h5>Hỗ Trợ Khách Hàng</h5>
                <ul class="footer-links">
                    <li><a href="#">Câu Hỏi Thường Gặp (FAQ)</a></li>
                    <li><a href="#">Chính Sách Vận Chuyển</a></li>
                    <li><a href="#">Chính Sách Đổi Trả</a></li>
                    <li><a href="#">Chính Sách Bảo Hành</a></li>
                </ul>
            </div>

            <div class="col-md-3 mb-4">
                <h5>Liên Hệ</h5>
                <p><i class="fas fa-map-marker-alt me-2"></i> [Địa chỉ của bạn]</p>
                <p><i class="fas fa-phone me-2"></i> [Số điện thoại]</p>
                <p><i class="fas fa-envelope me-2"></i> [Email liên hệ]</p>
                <ul class="social-icons mt-3">
                    <li><a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                    <li><a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a></li>
                    <li><a href="#" aria-label="Youtube"><i class="fab fa-youtube"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} [Tên Công Ty]. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<!-- Chatbot Widget -->
@include('components.chatbot-widget')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')

</body>

</html>