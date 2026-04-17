@extends('layout')

@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card border-success">
            <div class="card-body text-center py-5">
                <h1 class="mb-3" style="font-size: 3rem;">✅</h1>
                <h2 class="mb-3">Đặt hàng thành công!</h2>
                <p class="fs-5 text-muted mb-4">Cảm ơn bạn đã mua sắm tại Watch Store</p>

                <div class="card bg-light mb-4">
                    <div class="card-body text-start">
                        <h5 class="mb-3">📦 Thông tin đơn hàng</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Mã đơn hàng:</strong></p>
                                <p class="fs-4 text-primary">#{{ $order->id }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Ngày đặt:</strong></p>
                                <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <hr>

                        <h6 class="mb-3">Chi tiết sản phẩm:</h6>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orderItems as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                                        <td class="price">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>📍 Địa chỉ giao hàng:</strong></p>
                                <p>{{ $order->shipping_address }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>📱 Số điện thoại:</strong></p>
                                <p>{{ $order->phone }}</p>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <strong>⏳ Trạng thái:</strong> {{ ucfirst($order->status) }}
                            <br>
                            <small>Shop sẽ liên hệ bạn trong 24 giờ để xác nhận đơn hàng</small>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <span><strong>Tổng cộng:</strong></span>
                            <span class="price fs-4">{{ number_format($order->total_price, 0, ',', '.') }}₫</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="/" class="btn btn-primary btn-lg me-2">🏠 Tiếp tục mua sắm</a>
                    <a href="/orders" class="btn btn-outline-primary btn-lg">📋 Xem đơn hàng của tôi</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
