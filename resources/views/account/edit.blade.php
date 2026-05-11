@extends('layout')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="display-5">👤 Tài khoản của tôi</h1>
        <p class="text-muted">Quản lý thông tin cá nhân và bảo mật tài khoản của bạn.</p>
        <hr>
    </div>
</div>

<div class="row g-4">
    {{-- Cột bên trái: Cập nhật thông tin --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-dark text-white">
                <h5>Thông tin cá nhân</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('account.profile.update') }}" method="POST">
                    @csrf
                    {{-- Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Địa chỉ Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Cột bên phải: Đổi mật khẩu --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-dark text-white">
                <h5>Đổi mật khẩu</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('account.password.update') }}" method="POST">
                    @csrf
                    {{-- Current Password --}}
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" id="current_password" name="current_password" required>
                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu mới</label>
                        <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" id="password" name="password" required>
                        @error('password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Confirm New Password --}}
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
