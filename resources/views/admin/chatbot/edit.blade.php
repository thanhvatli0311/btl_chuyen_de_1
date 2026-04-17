@extends('layout')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h4>✏️ Sửa Câu Hỏi Chatbot</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.chatbot.update', $response) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">❓ Câu Hỏi</label>
                            <input type="text" class="form-control @error('question') is-invalid @enderror" 
                                   name="question" value="{{ old('question', $response->question) }}" required>
                            @error('question')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">💬 Trả Lời</label>
                            <textarea class="form-control @error('answer') is-invalid @enderror" 
                                      name="answer" rows="4" required>{{ old('answer', $response->answer) }}</textarea>
                            @error('answer')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">📁 Danh Mục</label>
                            <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                   name="category" value="{{ old('category', $response->category) }}" required>
                            @error('category')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="is_active" id="is_active" 
                                   value="1" {{ old('is_active', $response->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                ✅ Kích Hoạt
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">✅ Cập Nhật</button>
                            <a href="{{ route('admin.chatbot') }}" class="btn btn-outline-secondary">❌ Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
