@extends('layout')

@section('content')

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="display-4">📊 Admin Dashboard</h1>
            <p class="text-muted">Chào mừng {{ Auth::user()->name }}! 👋</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">📦 Tổng Sản Phẩm</h5>
                    <h2 class="card-text">{{ $totalProducts }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">📋 Tổng Đơn Hàng</h5>
                    <h2 class="card-text">{{ $totalOrders }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">💰 Tổng Doanh Thu</h5>
                    <h2 class="card-text">{{ number_format($totalRevenue, 0, ',', '.') }}₫</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">👥 Tổng Người Dùng</h5>
                    <h2 class="card-text">{{ $totalUsers }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Links -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h4 class="mb-3">⚙️ Quản Lý</h4>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.products') }}" class="btn btn-primary btn-lg w-100 mb-2">
                📦 Quản lý Sản phẩm
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.orders') }}" class="btn btn-success btn-lg w-100 mb-2">
                📋 Quản lý Đơn hàng
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.discount-codes.index') }}" class="btn btn-warning btn-lg w-100 mb-2">
                🎁 Mã Giảm Giá
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-info btn-lg w-100 mb-2">
                ⭐ Đánh Giá Sản phẩm
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.chatbot') }}" class="btn btn-secondary btn-lg w-100 mb-2">
                🤖 Chatbot
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.messages') }}" class="btn btn-danger btn-lg w-100 mb-2">
                💬 Quản lý Khách hàng
            </a>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="row">
        <div class="col-md-12">
            <h4 class="mb-3">📈 Đơn Hàng Gần Đây</h4>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#Mã</th>
                            <th>👤 Khách Hàng</th>
                            <th>💰 Tổng Tiền</th>
                            <th>📍 Địa Chỉ</th>
                            <th>📊 Trạng Thái</th>
                            <th>📅 Ngày Đặt</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td class="price">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
                                <td>{{ Str::limit($order->shipping_address, 30) }}</td>
                                <td>
                                    @if($order->status === 'pending')
                                        <span class="badge bg-warning">⏳ Chờ</span>
                                    @elseif($order->status === 'processing')
                                        <span class="badge bg-info">🔄 Xử Lý</span>
                                    @elseif($order->status === 'completed')
                                        <span class="badge bg-success">✅ Hoàn Thành</span>
                                    @else
                                        <span class="badge bg-danger">❌ Hủy</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.detail', $order) }}" class="btn btn-sm btn-primary">
                                        👁️
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Customer Inquiries Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h4 class="mb-3">💬 Tin Nhắn Khách Hàng</h4>
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Tổng quan Tin nhắn</h6>
                    <a href="{{ route('admin.messages') }}" class="btn btn-sm btn-primary">Xem tất cả</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light text-dark shadow-sm">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                        Tin nhắn chờ xử lý
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-dark">{{ $pendingMessagesCount }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light text-dark shadow-sm">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                        Tổng số tin nhắn
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-dark">{{ $totalMessagesCount }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h6 class="mt-3 mb-2 text-gray-800">Tin nhắn gần đây:</h6>
                    @if($recentMessages->isEmpty())
                        <p class="text-center text-muted">Không có tin nhắn gần đây.</p>
                    @else
                        <div class="list-group">
                            @foreach($recentMessages as $message)
                                <a href="{{ route('admin.messages.detail', $message->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="font-weight-bold">
                                            {{ $message->user->name ?? $message->visitor_name ?? 'Khách vãng lai' }}
                                            @if($message->user)
                                                <small class="text-muted">({{ $message->user->email }})</small>
                                            @elseif($message->visitor_email)
                                                <small class="text-muted">({{ $message->visitor_email }})</small>
                                            @endif
                                        </div>
                                        <small class="text-muted">{{ Str::limit($message->message, 50) }}</small>
                                    </div>
                                    <span class="badge {{ $message->status == 'pending' ? 'bg-warning' : 'bg-success' }} text-white">{{ $message->status == 'pending' ? 'Chờ' : 'Đã trả lời' }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
