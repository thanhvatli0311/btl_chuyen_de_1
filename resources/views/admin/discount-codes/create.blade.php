@extends('layout')

@section('content')

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="display-5 mb-4">➕ Tạo Mã Giảm Giá Mới</h1>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>❌ Có lỗi xảy ra:</strong>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('admin.discount-codes.store') }}" method="POST" class="card shadow-lg">
                @csrf
                <div class="card-body p-4">
                    <!-- Mã Giảm Giá -->
                    <div class="mb-3">
                        <label class="form-label">🎯 Mã Giảm Giá:</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               name="code" placeholder="VD: SAVE10" value="{{ old('code') }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Mô Tả -->
                    <div class="mb-3">
                        <label class="form-label">📝 Mô Tả:</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  name="description" rows="2" placeholder="Mô tả ngắn về mã giảm giá">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Loại Giảm Giá -->
                    <div class="mb-3">
                        <label class="form-label">💰 Loại Giảm Giá:</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="type" id="percentage" value="percentage" 
                                   {{ old('type') === 'percentage' ? 'checked' : '' }} required>
                            <label class="btn btn-outline-primary" for="percentage">
                                📊 Giảm Phần Trăm (%)
                            </label>

                            <input type="radio" class="btn-check" name="type" id="fixed_amount" value="fixed_amount"
                                   {{ old('type') === 'fixed_amount' ? 'checked' : '' }} required>
                            <label class="btn btn-outline-primary" for="fixed_amount">
                                💵 Giảm Số Cố Định (₫)
                            </label>
                        </div>
                        @error('type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Giá Trị -->
                    <div class="mb-3">
                        <label class="form-label">🔢 Giá Trị Giảm:</label>
                        <input type="number" class="form-control @error('value') is-invalid @enderror" 
                               name="value" placeholder="VD: 10 (cho 10%) hoặc 500000 (cho 500k)" 
                               step="0.01" value="{{ old('value') }}" required>
                        @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tối Thiểu Đơn Hàng -->
                    <div class="mb-3">
                        <label class="form-label">📦 Tối Thiểu Đơn Hàng (₫):</label>
                        <input type="number" class="form-control @error('minimum_order_amount') is-invalid @enderror" 
                               name="minimum_order_amount" placeholder="Để trống nếu không có yêu cầu tối thiểu" 
                               step="0.01" value="{{ old('minimum_order_amount') }}">
                        <small class="text-muted">Khách hàng chỉ được dùng mã nếu tổng đơn ≥ giá trị này</small>
                        @error('minimum_order_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Giới Hạn Sử Dụng -->
                    <div class="mb-3">
                        <label class="form-label">📊 Giới Hạn Sử Dụng:</label>
                        <input type="number" class="form-control @error('usage_limit') is-invalid @enderror" 
                               name="usage_limit" placeholder="Để trống nếu không giới hạn" 
                               min="1" value="{{ old('usage_limit') }}">
                        <small class="text-muted">Số lần tối đa mã này có thể được sử dụng</small>
                        @error('usage_limit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Ngày Bắt Đầu -->
                    <div class="mb-3">
                        <label class="form-label">📅 Ngày Bắt Đầu:</label>
                        <input type="date" class="form-control @error('valid_from') is-invalid @enderror" 
                               name="valid_from" value="{{ old('valid_from', now()->format('Y-m-d')) }}" required>
                        @error('valid_from')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Ngày Kết Thúc -->
                    <div class="mb-3">
                        <label class="form-label">📅 Ngày Kết Thúc (tuỳ chọn):</label>
                        <input type="date" class="form-control @error('valid_until') is-invalid @enderror" 
                               name="valid_until" value="{{ old('valid_until') }}">
                        <small class="text-muted">Để trống nếu không có ngày kết thúc</small>
                        @error('valid_until')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kích Hoạt -->
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                ✅ Kích Hoạt Mã Ngay Sau Khi Tạo
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex gap-2">
                    <button type="submit" class="btn btn-success btn-lg">
                        ✅ Tạo Mã
                    </button>
                    <a href="{{ route('admin.discount-codes.index') }}" class="btn btn-secondary btn-lg">
                        ❌ Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
