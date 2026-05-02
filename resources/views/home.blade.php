@extends('layout')

@section('styles')
<style>
    /* --- Custom Styles for a more elegant look --- */
    /* --- NEW Carousel Banner Styles --- */
    #heroCarousel {
        border-radius: 15px;
        overflow: hidden; /* Important for border-radius on images */
        margin-bottom: 40px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .carousel-item {
        height: 450px; /* Define a fixed height for the banner */
    }

    .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Ensures the image covers the area without distortion */
    }

    .carousel-caption {
        background: rgba(0, 0, 0, 0.5); /* Semi-transparent background for readability */
        border-radius: 10px;
        padding: 20px;
        bottom: 3rem; /* Position it a bit higher */
    }

    .carousel-caption h1 {
        font-weight: 700;
        font-size: 3.5rem;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
    }

    .carousel-caption p {
        font-size: 1.2rem;
        text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
    }

    .out-of-stock-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background-color: rgba(33, 37, 41, 0.85); /* Dark background */
        color: white;
        padding: 5px 12px;
        border-radius: 5px;
        font-size: 0.8rem;
        font-weight: bold;
        z-index: 10;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .product-image-container.out-of-stock img {
        filter: grayscale(100%);
    }

    .product-card {
        border: none;
        background-color: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .product-image-container {
        position: relative;
        overflow: hidden;
    }

    .product-card img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .product-card:hover img {
        transform: scale(1.05);
    }

    .product-actions {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.6);
        padding: 10px;
        transform: translateY(100%);
        transition: transform 0.3s ease;
        display: flex;
        gap: 10px;
    }

    .product-card:hover .product-actions {
        transform: translateY(0);
    }

    .product-actions .btn {
        flex-grow: 1;
        font-size: 0.9rem;
    }

    .product-info {
        padding: 20px;
        text-align: center;
    }

    .product-name {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
    }

    .product-price {
        font-size: 1.2rem;
        font-weight: 700;
        color: #007bff;
    }

    /* Toast Notification */
    .toast-notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #28a745;
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        z-index: 1050;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s, visibility 0.3s, transform 0.3s;
        transform: translateY(20px);
    }
    .toast-notification.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    
    
</style>
@endsection

@section('content')

<!-- NEW Carousel Banner -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="https://images.unsplash.com/photo-1523170335258-f5ed11844a49?q=80&w=2080&auto=format&fit=crop" class="d-block w-100" alt="Luxury Watch Collection">
            <div class="carousel-caption d-none d-md-block">
                <h1>⌚ The Watch Collection</h1>
                <p>Khám phá những kiệt tác vượt thời gian.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="./../images/banner4.jpg" class="d-block w-100" alt="Modern Timepiece">
            <div class="carousel-caption d-none d-md-block">
                <h1>Đẳng Cấp & Tinh Tế</h1>
                <p>Mỗi chiếc đồng hồ là một câu chuyện về phong cách của bạn.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="./../images/banner1.jpg" class="d-block w-100" alt="Elegant Watch on Wrist">
            <div class="carousel-caption d-none d-md-block">
                <h1>Ưu Đãi Đặc Biệt</h1>
                <p>Sở hữu ngay những mẫu đồng hồ độc quyền với giá tốt nhất.</p>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
 
{{-- Thanh lọc sản phẩm --}}
<div class="container px-0 mt-4">
    @include('components.product-filter', [
        'brands' => $brands,
        'queryInput' => request('query'),
        'selectedBrandId' => request('brand_id'),
        'priceRange' => request('price_range'),
        'sort' => request('sort')
    ])
</div>
 
<h2 class="text-center mb-4" style="font-family: 'Montserrat', sans-serif; font-weight: 700;">Sản Phẩm Nổi Bật</h2>
 
<div class="row">
    @forelse($products as $product)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 d-flex">
            <div class="product-card h-100">
                <div class="product-image-container @if($product->quantity <= 0) out-of-stock @endif">
                    @if($product->quantity <= 0)
                        <span class="out-of-stock-badge">Hết Hàng</span>
                    @endif
                    <a href="{{ route('product.detail', $product->id) }}">
                        <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}">
                    </a>
                    <div class="product-actions">
                        <a href="{{ route('product.detail', $product->id) }}" class="btn btn-outline-light btn-sm">👁️ Xem</a>
                        @auth
                            @if($product->quantity > 0)
                                <button class="btn btn-light btn-sm btn-add-to-cart" data-id="{{ $product->id }}">🛒 Thêm</button>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>Hết hàng</button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-warning btn-sm">🔓 Đăng nhập</a>
                        @endauth
                    </div>
                </div>
                <div class="product-info">
                    <h5 class="product-name">
                        <a href="{{ route('product.detail', $product->id) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
                    </h5>
                    <p class="product-price">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <h4>Không có sản phẩm nào!</h4>
                <p>Vui lòng quay lại sau.</p>
            </div>
        </div>
    @endforelse
</div>
 
{{-- Hiển thị link phân trang --}}
<div class="d-flex justify-content-center mt-4">
    {{ $products->links() }}
</div>

<!-- Toast Notification Element -->
<div id="toast-notification" class="toast-notification"></div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const addToCartButtons = document.querySelectorAll('.btn-add-to-cart');
    const toast = document.getElementById('toast-notification');

    // Lấy CSRF token từ thẻ meta trong layout
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const productId = this.getAttribute('data-id');
            
            // Vô hiệu hóa nút để tránh click nhiều lần
            this.disabled = true;
            this.innerHTML = 'Đang thêm...';

            fetch(`/add-cart/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json' // Báo cho server biết chúng ta muốn nhận JSON
                },
            })
            .then(response => {
                // Kiểm tra xem phản hồi có thành công không (status 200-299)
                if (!response.ok) {
                    // Nếu không, phân tích JSON lỗi từ server và ném ra một lỗi
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || 'Đã có lỗi xảy ra.');
                    });
                }
                return response.json();
            })
            .then(data => {
                // Chỉ chạy khi phản hồi thành công
                toast.textContent = data.message;
                toast.style.backgroundColor = '#28a745'; // Màu xanh cho thành công
                toast.classList.add('show');
                setTimeout(() => toast.classList.remove('show'), 3000);

                // Cập nhật số lượng giỏ hàng trên navbar (nếu có)
                const cartCountElement = document.getElementById('cart-count');
                if (cartCountElement) {
                    if (data.cartCount > 0) {
                        cartCountElement.innerText = data.cartCount;
                        cartCountElement.style.display = 'inline-block';
                    } else {
                        cartCountElement.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                // Bắt lỗi từ mạng hoặc lỗi từ server đã được ném ra ở trên
                toast.textContent = error.message;
                toast.style.backgroundColor = '#dc3545'; // Màu đỏ cho lỗi
                toast.classList.add('show');
                setTimeout(() => toast.classList.remove('show'), 3000);
                console.error('Error:', error.message);
            })
            .finally(() => {
                // Kích hoạt lại nút
                this.disabled = false;
                this.innerHTML = '🛒 Thêm';
            });
        });
    });
});
</script>
@endsection