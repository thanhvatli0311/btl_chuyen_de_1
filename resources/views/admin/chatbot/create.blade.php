@extends('layout')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h4>➕ Thêm Câu Hỏi & Trả Lời Chatbot</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.chatbot.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">❓ Câu Hỏi</label>
                            <input type="text" class="form-control @error('question') is-invalid @enderror" 
                                   name="question" value="{{ old('question') }}" placeholder="VD: Giá ship bao nhiêu?" required>
                            @error('question')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">💬 Trả Lời</label>
                            <textarea class="form-control @error('answer') is-invalid @enderror" 
                                      name="answer" rows="4" placeholder="Viết câu trả lời..." required>{{ old('answer') }}</textarea>
                            @error('answer')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">📁 Danh Mục</label>
                            <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                   name="category" value="{{ old('category') }}" placeholder="VD: shipping, payment, product" required>
                            @error('category')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Dùng để phân loại câu hỏi</small>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="is_active" id="is_active" 
                                   value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                ✅ Kích Hoạt Ngay
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">✅ Thêm Câu Hỏi</button>
                            <a href="{{ route('admin.chatbot') }}" class="btn btn-outline-secondary">❌ Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
