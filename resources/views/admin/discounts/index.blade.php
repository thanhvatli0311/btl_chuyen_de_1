@extends('layout')

@section('content')

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>🎁 Quản Lý Mã Giảm Giá</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.discounts.create') }}" class="btn btn-success">
                ➕ Thêm Mã Mới
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>🔖 Mã Code</th>
                    <th>📝 Mô Tả</th>
                    <th>💰 Loại & Giá Trị</th>
                    <th>📊 Lượt Dùng</th>
                    <th>📅 Hạn Sử Dụng</th>
                    <th>✅ Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($codes as $code)
                    <tr>
                        <td><strong>#{{ $code->id }}</strong></td>
                        <td><strong class="badge bg-primary">{{ $code->code }}</strong></td>
                        <td>{{ Str::limit($code->description, 30) }}</td>
                        <td>
                            @if($code->type === 'percentage')
                                <span class="badge bg-info">{{ $code->value }}%</span>
                            @else
                                <span class="badge bg-success">{{ number_format($code->value, 0, ',', '.') }}₫</span>
                            @endif
                        </td>
                        <td>
                            {{ $code->usage_count }}
                            @if($code->usage_limit)
                                / {{ $code->usage_limit }}
                            @else
                                / Không giới hạn
                            @endif
                        </td>
                        <td>
                            @if($code->valid_until)
                                {{ $code->valid_until->format('d/m/Y') }}
                            @else
                                Không có
                            @endif
                        </td>
                        <td>
                            @if($code->is_active)
                                <span class="badge bg-success">✅ Hoạt Động</span>
                            @else
                                <span class="badge bg-danger">❌ Tắt</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.discounts.edit', $code) }}" class="btn btn-sm btn-primary">✏️</a>
                            <form action="{{ route('admin.discounts.delete', $code) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn?')">🗑️</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $codes->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection
