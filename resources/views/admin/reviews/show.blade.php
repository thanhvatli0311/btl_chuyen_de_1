@extends('layout')

@section('content')

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="display-5 mb-4">⭐ Chi Tiết Đánh Giá</h1>

            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5>👤 Thông Tin Khách Hàng</h5>
                </div>
                <div class="card-body">
                    <p><strong>Tên:</strong> {{ $review->user->name }}</p>
                    <p><strong>Email:</strong> {{ $review->user->email }}</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5>📦 Sản Phẩm Được Đánh Giá</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="/images/{{ $review->product->image }}" class="img-fluid rounded" alt="{{ $review->product->name }}">
                        </div>
                        <div class="col-md-8">
                            <p><strong>Tên Sản Phẩm:</strong> {{ $review->product->name }}</p>
                            <p><strong>Giá:</strong> {{ number_format($review->product->price, 0, ',', '.') }}₫</p>
                            <p><strong>Danh Mục:</strong> {{ $review->product->category->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5>⭐ Đánh Giá & Bình Luận</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-3">
                        @for($i = 0; $i < $review->rating; $i++)
                            ⭐
                        @endfor
                        @for($i = 0; $i < (5 - $review->rating); $i++)
                            ☆
                        @endfor
                        <span class="ms-2">{{ $review->rating }}/5 sao</span>
                    </h6>

                    @if($review->comment)
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="card-text">{{ $review->comment }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-muted"><em>Khách hàng không có bình luận</em></p>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5>🛍️ Thông Tin Đơn Hàng</h5>
                </div>
                <div class="card-body">
                    @if($review->order)
                        <p><strong>Mã Đơn Hàng:</strong> #{{ $review->order->id }}</p>
                        <p><strong>Ngày Đặt:</strong> {{ $review->order->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Tổng Tiền:</strong> {{ number_format($review->order->total_price, 0, ',', '.') }}₫</p>
                        <p><strong>Trạng Thái:</strong> 
                            <span class="badge bg-success">{{ ucfirst($review->order->status) }}</span>
                        </p>
                    @else
                        <p class="text-muted">Không có thông tin đơn hàng</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5>✅ Trạng Thái Phê Duyệt</h5>
                </div>
                <div class="card-body">
                    <p><strong>Ngày Đánh Giá:</strong> {{ $review->created_at->format('d/m/Y H:i') }}</p>
                    <p>
                        <strong>Trạng Thái:</strong>
                        @if($review->is_approved)
                            <span class="badge bg-success">✅ Đã Phê Duyệt</span>
                        @else
                            <span class="badge bg-warning">⏳ Chờ Duyệt</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                @if(!$review->is_approved)
                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg">
                            ✅ Phê Duyệt Đánh Giá
                        </button>
                    </form>
                @endif

                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-lg" onclick="return confirm('Chắc chắn xóa?')">
                        🗑️ Xóa Đánh Giá
                    </button>
                </form>

                <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary btn-lg">
                    ← Quay Lại
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
