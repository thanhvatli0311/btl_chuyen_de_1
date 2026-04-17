@extends('layout')

@section('content')

<div class="row mt-5">
    <div class="col-md-6">
        <img src="/images/{{ $product->image }}" class="img-fluid rounded" alt="{{ $product->name }}">
    </div>

    <div class="col-md-6">
        <h2 class="display-5">{{ $product->name }}</h2>
        
        <div class="mb-3">
            <span class="badge bg-info">Danh mục: {{ $product->category->name ?? 'N/A' }}</span>
        </div>

        <p class="price fs-3">💰 {{ number_format($product->price, 0, ',', '.') }}₫</p>

        <div class="mb-3">
            <p class="text-muted">
                <strong>Số lượng còn:</strong> 
                @if($product->quantity > 0)
                    <span class="badge bg-success">{{ $product->quantity }} chiếc</span>
                @else
                    <span class="badge bg-danger">Hết hàng</span>
                @endif
            </p>
        </div>

        <h5>Mô tả sản phẩm:</h5>
        <p>{{ $product->description }}</p>

        @auth
            <!-- Nếu đã đăng nhập -->
            @if($product->quantity > 0)
                <form action="/add-cart/{{ $product->id }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg w-100">
                        🛒 Thêm vào giỏ hàng
                    </button>
                </form>
            @else
                <button class="btn btn-danger btn-lg w-100 mt-4" disabled>
                    ❌ Sản phẩm hết hàng
                </button>
            @endif
        @else
            <!-- Nếu chưa đăng nhập -->
            <div class="alert alert-warning mt-4">
                <strong>⚠️ Bạn chưa đăng nhập!</strong>
                <p>Vui lòng đăng nhập để có thể mua hàng.</p>
                <a href="{{ route('login') }}" class="btn btn-primary">🔓 Đăng nhập</a>
                <a href="{{ route('register') }}" class="btn btn-secondary">✍️ Đăng ký</a>
            </div>
        @endauth

        <a href="/" class="btn btn-outline-dark mt-3">← Quay lại</a>
    </div>
</div>

<!-- Reviews Section -->
<div class="row mt-5">
    <div class="col-md-12">
        <h3 class="mb-4">⭐ Đánh giá sản phẩm ({{ $reviewCount > 0 ? $reviewCount : 0 }})</h3>
        
        @if($reviewCount > 0)
            <div class="alert alert-info">
                <strong>📊 Điểm đánh giá trung bình:</strong>
                <span class="fs-5">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($averageRating))
                            <span style="color: #ffc107;">★</span>
                        @else
                            <span style="color: #ccc;">★</span>
                        @endif
                    @endfor
                    {{ number_format($averageRating, 1) }}/5.0
                </span>
            </div>
        @endif

        @if($reviews->count() > 0)
            <div class="reviews-list">
                @foreach($reviews as $review)
                    <div class="card mb-3 border-light">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-title">
                                        👤 <strong>{{ $review->user->name }}</strong>
                                        <span class="text-muted" style="font-size: 0.9rem;">{{ $review->created_at->format('d/m/Y') }}</span>
                                    </h6>
                                    <div class="mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <span style="color: #ffc107;">★</span>
                                            @else
                                                <span style="color: #ccc;">★</span>
                                            @endif
                                        @endfor
                                        <span class="badge bg-success">{{ $review->rating }}/5</span>
                                    </div>
                                </div>
                            </div>
                            <p class="card-text">{{ $review->comment }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-light text-center">
                <p class="mb-0">Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá sản phẩm!</p>
            </div>
        @endif

        @if($reviewCount > 0)
            <div class="text-center mt-3">
                <a href="{{ route('review.show', $product->id) }}" class="btn btn-info">
                    👁️ Xem tất cả {{ $reviewCount }} đánh giá
                </a>
            </div>
        @endif
    </div>
</div>

@endsection