@extends('layout')

@section('content')

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>📦 Quản Lý Sản Phẩm</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.products.create') }}" class="btn btn-success">
                ➕ Thêm Sản Phẩm
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>📸 Ảnh</th>
                    <th>Tên Sản Phẩm</th>
                    <th>💰 Giá</th>
                    <th>📦 Số Lượng</th>
                    <th>📁 Danh Mục</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td><strong>#{{ $product->id }}</strong></td>
                        <td>
                            <img src="/images/{{ $product->image }}" width="50" class="rounded" alt="{{ $product->name }}">
                        </td>
                        <td>{{ $product->name }}</td>
                        <td class="price">{{ number_format($product->price, 0, ',', '.') }}₫</td>
                        <td>
                            @if($product->quantity > 0)
                                <span class="badge bg-success">{{ $product->quantity }}</span>
                            @else
                                <span class="badge bg-danger">Hết hàng</span>
                            @endif
                        </td>
                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-primary">
                                ✏️ Sửa
                            </a>
                            <form action="{{ route('admin.products.delete', $product) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn?')">
                                    🗑️ Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection
