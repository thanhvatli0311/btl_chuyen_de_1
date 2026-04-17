@extends('layout')

@section('content')

<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="display-5">📋 Đơn hàng của tôi</h1>
    </div>
</div>

@if($orders->isEmpty())
    <div class="alert alert-info">
        <h4>Bạn chưa có đơn hàng nào!</h4>
        <p>Hãy bắt đầu mua sắm ngay bây giờ.</p>
        <a href="/" class="btn btn-primary">← Quay lại mua sắm</a>
    </div>
@else
    <div class="orders-list">
        @foreach($orders as $order)
            <div class="card order-card mb-3 shadow-sm border-0 rounded-lg" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="row align-items-center g-3">
                        <!-- Thông tin chính: Mã đơn + Ngày + Sản phẩm + Giá -->
                        <div class="col-md-8">
                            <div class="d-flex flex-wrap gap-3 align-items-center">
                                <div>
                                    <h5 class="mb-0 text-dark">
                                        <strong>#{{ $order->id }}</strong>
                                    </h5>
                                </div>
                                <div class="text-muted small">
                                    🗓️ {{ $order->created_at->format('d/m/Y') }}
                                    <span class="ms-2">⏱️ {{ $order->created_at->format('H:i') }}</span>
                                </div>
                                <div class="badge bg-light text-dark border">
                                    📦 {{ $order->items->count() }} sản phẩm
                                </div>
                                <div class="fw-bold text-success fs-5">
                                    {{ number_format($order->total_price, 0, ',', '.') }}₫
                                </div>
                            </div>
                        </div>

                        <!-- Trạng thái + Hành động -->
                        <div class="col-md-4 d-flex justify-content-end gap-2 flex-wrap">
                            <div>
                                @if($order->status === 'pending')
                                    <span class="badge bg-warning" style="font-size: 0.85rem;">⏳ Chờ xác nhận</span>
                                @elseif($order->status === 'processing')
                                    <span class="badge bg-info" style="font-size: 0.85rem;">🔄 Đang xử lý</span>
                                @elseif($order->status === 'completed')
                                    <span class="badge bg-success" style="font-size: 0.85rem;">✅ Hoàn thành</span>
                                @elseif($order->status === 'cancelled')
                                    <span class="badge bg-danger" style="font-size: 0.85rem;">❌ Bị hủy</span>
                                @endif
                            </div>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#orderDetail{{ $order->id }}">
                                👁️ Chi tiết
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal chi tiết đơn hàng -->
            <div class="modal fade" id="orderDetail{{ $order->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-dark text-white">
                            <h5 class="modal-title">Chi tiết đơn hàng #{{ $order->id }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                    <p><strong>Trạng thái:</strong> 
                                        @if($order->status === 'pending')
                                            <span class="badge bg-warning">⏳ Chờ xác nhận</span>
                                        @elseif($order->status === 'processing')
                                            <span class="badge bg-info">🔄 Đang xử lý</span>
                                        @elseif($order->status === 'completed')
                                            <span class="badge bg-success">✅ Hoàn thành</span>
                                        @elseif($order->status === 'cancelled')
                                            <span class="badge bg-danger">❌ Bị hủy</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Địa chỉ giao:</strong></p>
                                    <p class="small">{{ $order->shipping_address }}</p>
                                </div>
                            </div>

                            <hr>

                            <h6>📦 Sản phẩm:</h6>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Tên sản phẩm</th>
                                        <th>Số lượng</th>
                                        <th>Giá</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                                            <td class="price">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="text-end">
                                <strong>Tổng cộng: <span class="price fs-5">{{ number_format($order->total_price, 0, ',', '.') }}₫</span></strong>
                            </div>

                            @if($order->note)
                                <hr>
                                <p><strong>Ghi chú:</strong></p>
                                <p class="small">{{ $order->note }}</p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            @if($order->status === 'completed')
                                <a href="{{ route('review.create', $order->id) }}" class="btn btn-warning">
                                    ⭐ Đánh Giá Sản Phẩm
                                </a>
                            @endif
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
@endif

<div class="mt-4">
    <a href="/" class="btn btn-outline-dark">← Quay lại</a>
</div>

<style>
    .order-card {
        border-left: 4px solid #007bff !important;
        transition: all 0.3s ease;
    }

    .order-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-2px);
        border-left-color: #0056b3 !important;
    }

    .order-card .card-body {
        border-radius: 0.5rem;
    }

    @media (max-width: 768px) {
        .order-card {
            margin-bottom: 1.5rem;
        }
        
        .d-flex.flex-wrap.gap-3 {
            flex-direction: column;
        }

        .col-md-4 {
            margin-top: 1rem;
        }
    }
</style>

@endsection
