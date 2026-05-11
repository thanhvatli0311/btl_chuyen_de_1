@extends('layout')

@section('content')

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="display-4">👥 Quản Lý Tài Khoản</h1>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Quay lại Dashboard</a>
            </div>
            <p class="text-muted">Quản lý và phân quyền tài khoản người dùng</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>❌ Lỗi!</strong>
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>✅ Thành công!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>⚠️ Cảnh báo!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">👥 Tổng Tài Khoản</h5>
                    <h2 class="card-text">{{ \App\Models\User::count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">🔑 Tài Khoản Admin</h5>
                    <h2 class="card-text">{{ \App\Models\User::where('role', 'admin')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">🛍️ Tài Khoản Khách</h5>
                    <h2 class="card-text">{{ \App\Models\User::where('role', 'customer')->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">📋 Danh Sách Tài Khoản</h6>
        </div>
        <div class="card-body">
            @if ($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" width="50">ID</th>
                                <th>Tên Tài Khoản</th>
                                <th>Email</th>
                                <th class="text-center">Vai Trò</th>
                                <th class="text-center">Ngày Tạo</th>
                                <th class="text-center" width="200">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="text-center">
                                        <strong>#{{ $user->id }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td class="text-center">
                                        @if ($user->role === 'admin')
                                            <span class="badge bg-danger">🔑 Admin</span>
                                        @else
                                            <span class="badge bg-success">🛍️ Customer</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="d-inline-block mb-2">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group input-group-sm">
                                                <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <option value="{{ $user->role }}" selected>{{ $user->role === 'admin' ? 'Admin' : 'Customer' }}</option>
                                                    <option value="{{ $user->role === 'admin' ? 'customer' : 'admin' }}">
                                                        {{ $user->role === 'admin' ? 'Customer' : 'Admin' }}
                                                    </option>
                                                </select>
                                            </div>
                                        </form>

                                        @if ($user->id !== Auth::id())
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn chắc chắn muốn xóa tài khoản này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-sm btn-secondary" disabled title="Không thể xóa tài khoản của chính bạn">
                                                <i class="fas fa-trash"></i> Xóa
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $users->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <strong>ℹ️ Thông tin:</strong> Chưa có tài khoản nào trong hệ thống.
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }

    .badge {
        font-size: 0.9rem;
        padding: 0.5rem 0.75rem;
    }

    .input-group-sm {
        max-width: 200px;
    }

    .btn-group-sm {
        gap: 5px;
    }

    .form-select-sm {
        cursor: pointer;
    }
</style>
@endsection
