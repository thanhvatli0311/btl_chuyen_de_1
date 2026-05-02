@extends('layout')

@section('title', 'Tìm kiếm sản phẩm')

@section('styles')
<style>
    .no-results-container {
        background-color: #f8f9fa;
        border: 1px dashed #dee2e6;
        border-radius: 15px;
        padding: 4rem 2rem;
        margin-top: 2rem;
        text-align: center;
    }
    .no-results-container .icon {
        font-size: 4rem;
        color: #6c757d;
        margin-bottom: 1rem;
    }
    .no-results-container h2 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
    }

    /* --- Styles for Product Card - Copied from home.blade.php for consistency --- */
    .product-card {
        border: none;
        background-color: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        width: 100%; /* Đảm bảo card chiếm toàn bộ chiều rộng của cột */
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
        height: 250px; /* Chiều cao cố định cho tất cả ảnh */
        object-fit: cover; /* Giúp ảnh có cùng kích thước mà không bị méo */
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

    .out-of-stock-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background-color: rgba(33, 37, 41, 0.85);
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
</style>
@endsection

@section('content')
<div class="container px-0">
    {{-- Thanh lọc sản phẩm --}}
    @include('components.product-filter', [
        'brands' => $brands,
        'queryInput' => $queryInput ?? request('query'),
        'selectedBrandId' => $selectedBrandId ?? request('brand_id'),
        'priceRange' => $priceRange ?? request('price_range'),
        'sort' => $sort ?? request('sort')
    ])

    @php
        $pageTitle = 'Tất cả sản phẩm';
        if (isset($queryInput) && !empty($queryInput)) {
            $pageTitle = 'Kết quả cho: <span class="text-primary">"' . e($queryInput) . '"</span>';
        } elseif (isset($brand)) { // Dành cho route /brands/{slug}
            $pageTitle = 'Thương hiệu: <span class="text-primary">' . e($brand->name) . '</span>';
        } elseif (isset($selectedBrandId) && isset($brands)) {
            $selectedBrand = $brands->firstWhere('id', $selectedBrandId);
            if ($selectedBrand) {
                $pageTitle = 'Thương hiệu: <span class="text-primary">' . e($selectedBrand->name) . '</span>';
            }
        }
    @endphp

    <div class="d-flex justify-content-between align-items-center my-4 pt-3 border-top">
        <h2 class="mb-0" style="font-family: 'Montserrat', sans-serif; font-weight: 700;">
            {!! $pageTitle !!}
        </h2>
        <span class="text-muted">{{ $products->total() }} sản phẩm</span>
    </div>

    @if($products->isNotEmpty())
        <div class="row">
            @foreach($products as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4 d-flex">
                    {{-- Sử dụng Blade component cho product card để code sạch và dễ bảo trì --}}
                    @include('components.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{-- Hiển thị link phân trang, các tham số filter đã được controller thêm vào --}}
            {{ $products->links() }}
        </div>
    @else
        {{-- Giao diện chuyên nghiệp hơn khi không có kết quả --}}
        <div class="no-results-container">
            <div class="icon">🤷‍♂️</div>
            <h2>Không tìm thấy sản phẩm nào!</h2>
            <p class="lead text-muted">Rất tiếc, không có sản phẩm nào phù hợp với tiêu chí của bạn. Vui lòng thử lại với bộ lọc khác.</p>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">← Quay lại trang chủ</a>
        </div>
    @endif
</div>
@endsection

@section('scripts')
{{-- Thêm script để nút "Thêm vào giỏ" hoạt động (tương tự home.blade.php) --}}
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
            
            this.disabled = true;
            this.innerHTML = 'Đang thêm...';

            fetch(`/add-cart/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || 'Đã có lỗi xảy ra.');
                    });
                }
                return response.json();
            })
            .then(data => {
                toast.textContent = data.message;
                toast.style.backgroundColor = '#28a745';
                toast.classList.add('show');
                setTimeout(() => toast.classList.remove('show'), 3000);

                const cartCountElement = document.getElementById('cart-count');
                if (cartCountElement && data.cartCount > 0) {
                    cartCountElement.innerText = data.cartCount;
                    cartCountElement.style.display = 'inline-block';
                }
            })
            .catch(error => {
                toast.textContent = error.message;
                toast.style.backgroundColor = '#dc3545';
                toast.classList.add('show');
                setTimeout(() => toast.classList.remove('show'), 3000);
                console.error('Error:', error.message);
            })
            .finally(() => {
                this.disabled = false;
                this.innerHTML = '🛒 Thêm';
            });
        });
    });
});
</script>
@endsection