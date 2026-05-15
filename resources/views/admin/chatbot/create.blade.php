@extends('layout')

@section('title', 'Thêm Câu Trả Lời Tự Động')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-5">🤖 Thêm Câu Trả Lời Tự Động</h1>
        <a href="{{ route('admin.chatbot') }}" class="btn btn-secondary">← Quay lại</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.chatbot.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="question" class="form-label"><strong>Câu hỏi hoặc từ khóa của khách hàng</strong></label>
                    <input type="text" class="form-control @error('question') is-invalid @enderror" id="question" name="question" value="{{ old('question') }}" required>
                    <small class="form-text text-muted">Ví dụ: "chính sách bảo hành", "giao hàng mất bao lâu?", "đồng hồ rolex"</small>
                    @error('question')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="answer" class="form-label"><strong>Câu trả lời của Chatbot</strong></label>
                    <textarea class="form-control @error('answer') is-invalid @enderror" id="answer" name="answer" rows="4" required>{{ old('answer') }}</textarea>
                    @error('answer')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="product_ids" class="form-label"><strong>Đính kèm sản phẩm (tùy chọn)</strong></label>
                    <select class="form-control select2-products @error('product_ids') is-invalid @enderror" id="product_ids" name="product_ids[]" multiple="multiple">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Chọn các sản phẩm sẽ được hiển thị cùng với câu trả lời này.</small>
                    @error('product_ids')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label"><strong>Danh mục</strong></label>
                        <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category', 'general') }}" required>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><strong>Trạng thái</strong></label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Kích hoạt</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">💾 Lưu Câu Trả Lời</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        min-height: calc(1.5em + 0.75rem + 2px);
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-products').select2({
            placeholder: "Tìm và chọn sản phẩm...",
            allowClear: true
        });
    });
</script>
@endsection