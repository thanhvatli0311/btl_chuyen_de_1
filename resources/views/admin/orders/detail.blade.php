@extends('layout')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h4>📋 Chi Tiết Đơn Hàng #{{ $order->id }}</h4>
                </div>
                <div class="card-body">
                    <!-- Order Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="mb-3"><strong>👤 Thông Tin Khách Hàng</strong></h6>
                            <p><strong>Tên:</strong> {{ $order->user->name }}</p>
                            <p><strong>Email:</strong> {{ $order->user->email }}</p>
                            <p><strong>Số ĐT:</strong> {{ $order->phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3"><strong>📍 Địa Chỉ Giao Hàng</strong></h6>
                            <p>{{ $order->shipping_address }}</p>
                        </div>
                    </div>

                    <hr>

                    <!-- Products in Order -->
                    <h6 class="mb-3"><strong>📦 Sản Phẩm Đơn Hàng</strong></h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản Phẩm</th>
                                    <th>Số Lượng</th>
                                    <th>Giá</th>
                                    <th>Thành Tiền</th>
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
                    </div>

                    <hr>

                    <!-- Order Status -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><strong>📊 Trạng Thái Đơn Hàng</strong></h6>
                            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-select form-select-sm">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>⏳ Chờ xác nhận</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>🔄 Đang xử lý</option>
                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>✅ Hoàn thành</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>❌ Bị hủy</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-success">💾 Cập Nhật</button>
                            </form>
                        </div>
                        <div class="col-md-6 text-end">
                            <h6><strong>💰 Tổng Tiền</strong></h6>
                            <p class="price fs-4">{{ number_format($order->total_price, 0, ',', '.') }}₫</p>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($order->note)
                        <hr>
                        <h6><strong>📝 Ghi Chú</strong></h6>
                        <p>{{ $order->note }}</p>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('admin.orders') }}" class="btn btn-outline-dark">← Quay Lại</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
