@extends('layout')

@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-dark text-white text-center">
                <h4>🔓 Đăng nhập</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="/login">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">📧 Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">🔐 Mật khẩu</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Ghi nhớ tôi
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-dark btn-lg">
                            ✅ Đăng nhập
                        </button>
                    </div>
                </form>

                <hr>

                <p class="text-center mb-0">
                    Chưa có tài khoản? 
                    <a href="{{ route('register') }}">Đăng ký ngay</a>
                </p>
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="/" class="btn btn-outline-dark">← Quay lại trang chủ</a>
        </div>
    </div>
</div>

@endsection
