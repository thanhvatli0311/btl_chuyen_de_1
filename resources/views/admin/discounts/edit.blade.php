@extends('layout')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h4>✏️ Sửa Mã Giảm Giá</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.discounts.update', $code) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">🔖 Mã Code</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   name="code" value="{{ old('code', $code->code) }}" required>
                            @error('code')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">📝 Mô Tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      name="description" rows="3">{{ old('description', $code->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">💰 Loại Giảm</label>
                                    <select class="form-control @error('type') is-invalid @enderror" name="type" required>
                                        <option value="percentage" {{ old('type', $code->type) === 'percentage' ? 'selected' : '' }}>Giảm %</option>
                                        <option value="fixed" {{ old('type', $code->type) === 'fixed' ? 'selected' : '' }}>Giảm Số Tiền</option>
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">📊 Giá Trị</label>
                                    <input type="number" class="form-control @error('value') is-invalid @enderror" 
                                           name="value" value="{{ old('value', $code->value) }}" step="0.01" required>
                                    @error('value')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">📊 Giới Hạn Lượt Sử Dụng</label>
                            <p class="text-muted small">Lượt dùng hiện tại: {{ $code->usage_count }}</p>
                            <input type="number" class="form-control @error('usage_limit') is-invalid @enderror" 
                                   name="usage_limit" value="{{ old('usage_limit', $code->usage_limit) }}">
                            @error('usage_limit')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">📅 Bắt Đầu</label>
                                    <input type="date" class="form-control @error('valid_from') is-invalid @enderror" 
                                           name="valid_from" value="{{ old('valid_from', $code->valid_from ? $code->valid_from->format('Y-m-d') : '') }}">
                                    @error('valid_from')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">📅 Kết Thúc</label>
                                    <input type="date" class="form-control @error('valid_until') is-invalid @enderror" 
                                           name="valid_until" value="{{ old('valid_until', $code->valid_until ? $code->valid_until->format('Y-m-d') : '') }}">
                                    @error('valid_until')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="is_active" id="is_active" 
                                   value="1" {{ old('is_active', $code->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                ✅ Kích Hoạt Mã Này
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">✅ Cập Nhật</button>
                            <a href="{{ route('admin.discounts') }}" class="btn btn-outline-secondary">❌ Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
