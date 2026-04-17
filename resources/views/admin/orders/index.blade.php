@extends('layout')

@section('content')

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>📋 Quản Lý Đơn Hàng</h2>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#Mã</th>
                    <th>👤 Khách Hàng</th>
                    <th>📧 Email</th>
                    <th>💰 Tổng Tiền</th>
                    <th>📊 Trạng Thái</th>
                    <th>📅 Ngày Đặt</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $order->user->email }}</td>
                        <td class="price">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
                        <td>
                            @if($order->status === 'pending')
                                <span class="badge bg-warning">⏳ Chờ xác nhận</span>
                            @elseif($order->status === 'processing')
                                <span class="badge bg-info">🔄 Đang xử lý</span>
                            @elseif($order->status === 'completed')
                                <span class="badge bg-success">✅ Hoàn thành</span>
                            @else
                                <span class="badge bg-danger">❌ Bị hủy</span>
                            @endif
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.detail', $order) }}" class="btn btn-sm btn-primary">
                                👁️ Xem Chi Tiết
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection
