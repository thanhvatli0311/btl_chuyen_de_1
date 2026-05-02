{{-- 
    Reusable Product Card Component
    Accepts: $product
--}}
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