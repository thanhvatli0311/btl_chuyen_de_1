@extends('layout')

@section('content')

<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="display-4">⌚ Bộ sưu tập đồng hồ</h1>
        <p class="text-muted">Khám phá những chiếc đồng hồ cao cấp nhất</p>
    </div>
</div>

<div class="row">
    @forelse($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <img src="/images/{{ $product->image }}" class="card-img-top" height="200" alt="{{ $product->name }}">
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    
                    <p class="text-muted small">{{ Str::limit($product->description, 50) }}</p>
                    
                    <p class="price mt-auto">💰 {{ number_format($product->price, 0, ',', '.') }}₫</p>
                    
                    <a href="/product/{{ $product->id }}" class="btn btn-dark btn-sm">
                        👁️ Xem chi tiết
                    </a>

                    @auth
                        <!-- Nếu đã đăng nhập, cho phép thêm vào giỏ -->
                        <form action="/add-cart/{{ $product->id }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm w-100">
                                🛒 Thêm vào giỏ
                            </button>
                        </form>
                    @else
                        <!-- Nếu chưa đăng nhập, yêu cầu đăng nhập -->
                        <a href="{{ route('login') }}" class="btn btn-warning btn-sm w-100 mt-2">
                            🔓 Đăng nhập để mua
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    @empty
        <div class="col-md-12">
            <div class="alert alert-info">
                <h4>Không có sản phẩm nào!</h4>
                <p>Vui lòng quay lại sau.</p>
            </div>
        </div>
    @endforelse
</div>

@endsection