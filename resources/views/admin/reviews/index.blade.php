@extends('layout')

@section('content')

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="display-5">⭐ Quản Lý Đánh Giá Sản Phẩm</h1>
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
                    <th>👤 Khách Hàng</th>
                    <th>📦 Sản Phẩm</th>
                    <th>⭐ Xếp Hạng</th>
                    <th>💬 Bình Luận</th>
                    <th>📅 Ngày Đánh Giá</th>
                    <th>✅ Trạng Thái Duyệt</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td>
                            <strong>{{ $review->user->name }}</strong><br>
                            <small class="text-muted">{{ $review->user->email }}</small>
                        </td>
                        <td>
                            <strong>{{ $review->product->name }}</strong><br>
                            @if($review->order)
                                <small class="text-muted">Đơn #{{ $review->order->id }}</small>
                            @endif
                        </td>
                        <td>
                            @for($i = 0; $i < $review->rating; $i++)
                                ⭐
                            @endfor
                            @for($i = 0; $i < (5 - $review->rating); $i++)
                                ☆
                            @endfor
                            <br>
                            <strong>{{ $review->rating }}/5</strong>
                        </td>
                        <td>
                            @if($review->comment)
                                <span class="badge bg-info">Có bình luận</span><br>
                                <small>{{ Str::limit($review->comment, 50) }}</small>
                            @else
                                <span class="badge bg-secondary">Không có</span>
                            @endif
                        </td>
                        <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($review->is_approved)
                                <span class="badge bg-success">✅ Đã Phê Duyệt</span>
                            @else
                                <span class="badge bg-warning">⏳ Chờ Duyệt</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-info">
                                    👁️ Xem
                                </a>
                                @if(!$review->is_approved)
                                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success" title="Phê duyệt">
                                            ✅ Duyệt
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Chắc chắn xóa?')">
                                        🗑️ Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted p-4">
                            Chưa có đánh giá nào
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $reviews->links() }}
    </div>
</div>

@endsection
