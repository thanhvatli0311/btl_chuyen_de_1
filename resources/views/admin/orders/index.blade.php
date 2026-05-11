@extends('layout')

@section('content')

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>📋 Quản Lý Đơn Hàng</h2>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0"><strong>🔍 Tìm Kiếm & Lọc Đơn Hàng</strong></h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.orders') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Tìm kiếm (Mã/Tên/Email)</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Nhập mã đơn hàng, tên hoặc email...">
                </div>

                <div class="col-md-2">
                    <label for="status" class="form-label">Trạng Thái</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">-- Tất Cả --</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Chờ xác nhận</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>🔄 Đang xử lý</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>✅ Hoàn thành</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>❌ Bị hủy</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="date_from" class="form-label">Từ Ngày</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="{{ request('date_from') }}">
                </div>

                <div class="col-md-2">
                    <label for="date_to" class="form-label">Đến Ngày</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="{{ request('date_to') }}">
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">🔍 Tìm Kiếm</button>
                    <a href="{{ route('admin.orders') }}" class="btn btn-secondary">🔄 Làm Mới</a>
                </div>
            </form>
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
                @forelse($orders as $order)
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
                                👁️ Xem
                            </a>
                            <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" 
                                  style="display: inline;" 
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này? Hành động này không thể hoàn tác.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    🗑️ Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <p class="mb-0">Không có đơn hàng nào</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection
