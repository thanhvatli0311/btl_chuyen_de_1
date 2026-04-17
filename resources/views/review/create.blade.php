@extends('layout')

@section('content')

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h1 class="display-5 mb-4">⭐ Đánh Giá Sản Phẩm - Đơn Hàng #{{ $order->id }}</h1>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Order Info -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5>📦 Thông Tin Đơn Hàng</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Mã Đơn Hàng:</strong> #{{ $order->id }}</p>
                            <p><strong>Tổng Tiền:</strong> {{ number_format($order->total_price, 0, ',', '.') }}₫</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Trạng Thái:</strong> 
                                <span class="badge bg-success">✅ {{ ucfirst($order->status) }}</span>
                            </p>
                            <p><strong>Ngày Đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products to Review -->
            <h5 class="mb-3">🛍️ Sản Phẩm Trong Đơn Hàng</h5>
            <div class="row">
                @foreach($products as $item)
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="/images/{{ $item->product->image }}" class="img-fluid rounded-start" alt="{{ $item->product->name }}">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $item->product->name }}</h5>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                Giá: {{ number_format($item->price, 0, ',', '.') }}₫<br>
                                                Số Lượng: {{ $item->quantity }}
                                            </small>
                                        </p>

                                        @if(in_array($item->product_id, $reviewedProductIds))
                                            <div class="alert alert-info mb-0">
                                                ✅ Đã đánh giá
                                            </div>
                                        @else
                                            <button type="button" class="btn btn-primary btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#reviewModal{{ $item->product_id }}">
                                                ⭐ Đánh Giá
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Review Modal -->
                        <div class="modal fade" id="reviewModal{{ $item->product_id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">⭐ Đánh Giá: {{ $item->product->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('review.store', $order->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <input type="hidden" name="product_id" value="{{ $item->product_id }}">

                                            <!-- Rating -->
                                            <div class="mb-3">
                                                <label class="form-label">⭐ Xếp Hạng:</label>
                                                <div class="rating-input">
                                                    <button type="button" class="btn btn-outline-warning me-2 rating-btn" data-rating="1">
                                                        ⭐ 1 Sao
                                                    </button>
                                                    <button type="button" class="btn btn-outline-warning me-2 rating-btn" data-rating="2">
                                                        ⭐⭐ 2 Sao
                                                    </button>
                                                    <button type="button" class="btn btn-outline-warning me-2 rating-btn" data-rating="3">
                                                        ⭐⭐⭐ 3 Sao
                                                    </button>
                                                    <button type="button" class="btn btn-outline-warning me-2 rating-btn" data-rating="4">
                                                        ⭐⭐⭐⭐ 4 Sao
                                                    </button>
                                                    <button type="button" class="btn btn-outline-warning rating-btn" data-rating="5">
                                                        ⭐⭐⭐⭐⭐ 5 Sao
                                                    </button>
                                                </div>
                                                <input type="hidden" name="rating" id="rating{{ $item->product_id }}" value="">
                                                <div id="ratingError{{ $item->product_id }}" class="invalid-feedback d-block" style="display: none;">
                                                    Vui lòng chọn số sao
                                                </div>
                                            </div>

                                            <!-- Comment -->
                                            <div class="mb-3">
                                                <label class="form-label">💬 Bình Luận (Tuỳ Chọn):</label>
                                                <textarea class="form-control" name="comment" rows="4" 
                                                          placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Hủy</button>
                                            <button type="submit" class="btn btn-primary">✅ Gửi Đánh Giá</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                <a href="{{ route('orders.list') }}" class="btn btn-secondary btn-lg">
                    ← Quay Lại Đơn Hàng
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.rating-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const rating = this.dataset.rating;
            const modal = this.closest('.modal-dialog');
            const productId = modal.querySelector('input[name="product_id"]').value;
            
            // Cập nhật hidden input
            document.getElementById('rating' + productId).value = rating;
            
            // Cập nhật style button
            const buttons = modal.querySelectorAll('.rating-btn');
            buttons.forEach((b, idx) => {
                if (idx < rating) {
                    b.classList.remove('btn-outline-warning');
                    b.classList.add('btn-warning');
                } else {
                    b.classList.add('btn-outline-warning');
                    b.classList.remove('btn-warning');
                }
            });

            // Hide error message
            document.getElementById('ratingError' + productId).style.display = 'none';
        });
    });

    // Validation khi submit
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const productId = this.querySelector('input[name="product_id"]').value;
            const rating = this.querySelector('input[name="rating"]').value;

            if (!rating) {
                e.preventDefault();
                document.getElementById('ratingError' + productId).style.display = 'block';
            }
        });
    });
</script>

@endsection
