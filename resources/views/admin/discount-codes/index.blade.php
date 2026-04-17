@extends('layout')

@section('content')

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-5">🎁 Quản Lý Mã Giảm Giá</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.discount-codes.create') }}" class="btn btn-success btn-lg">
                ➕ Tạo Mã Mới
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Mã Giảm Giá</th>
                    <th>Loại</th>
                    <th>Giá Trị</th>
                    <th>Tối Thiểu</th>
                    <th>Lần Dùng</th>
                    <th>Từ Ngày</th>
                    <th>Đến Ngày</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($codes as $code)
                    <tr>
                        <td>
                            <strong>{{ $code->code }}</strong>
                            @if($code->description)
                                <br><small class="text-muted">{{ $code->description }}</small>
                            @endif
                        </td>
                        <td>
                            @if($code->type === 'percentage')
                                <span class="badge bg-info">Phần Trăm %</span>
                            @else
                                <span class="badge bg-warning">Số Cố Định</span>
                            @endif
                        </td>
                        <td>
                            @if($code->type === 'percentage')
                                {{ $code->value }}%
                            @else
                                {{ number_format($code->value, 0, ',', '.') }}₫
                            @endif
                        </td>
                        <td>
                            @if($code->minimum_order_amount)
                                {{ number_format($code->minimum_order_amount, 0, ',', '.') }}₫
                            @else
                                <span class="text-muted">Không</span>
                            @endif
                        </td>
                        <td>
                            {{ $code->usage_count }}
                            @if($code->usage_limit)
                                / {{ $code->usage_limit }}
                            @else
                                / ∞
                            @endif
                        </td>
                        <td>{{ $code->valid_from->format('d/m/Y') }}</td>
                        <td>
                            @if($code->valid_until)
                                {{ $code->valid_until->format('d/m/Y') }}
                            @else
                                <span class="text-muted">∞</span>
                            @endif
                        </td>
                        <td>
                            @if($code->is_active)
                                <span class="badge bg-success">✅ Hoạt Động</span>
                            @else
                                <span class="badge bg-danger">❌ Vô Hiệu</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.discount-codes.edit', $code) }}" class="btn btn-primary">
                                    ✏️ Sửa
                                </a>
                                <form action="{{ route('admin.discount-codes.toggle', $code) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-warning" title="Bật/Tắt">
                                        {{ $code->is_active ? '🔴 Tắt' : '🟢 Bật' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.discount-codes.destroy', $code) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Xác nhận xóa?')">
                                        🗑️ Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted p-4">
                            Chưa có mã giảm giá nào. <a href="{{ route('admin.discount-codes.create') }}">Tạo mới</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $codes->links() }}
    </div>
</div>

@endsection
