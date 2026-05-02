@extends('layout')

@section('content')

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="display-4">📊 Admin Dashboard</h1>
            <p class="text-muted">Chào mừng {{ Auth::user()->name }}! 👋</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">📦 Tổng Sản Phẩm</h5>
                    <h2 class="card-text">{{ $totalProducts }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">📋 Tổng Đơn Hàng</h5>
                    <h2 class="card-text">{{ $totalOrders }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">💰 Tổng Doanh Thu</h5>
                    <h2 class="card-text">{{ number_format($totalRevenue, 0, ',', '.') }}₫</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">👥 Tổng Người Dùng</h5>
                    <h2 class="card-text">{{ $totalUsers }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Links -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h4 class="mb-3">⚙️ Quản Lý</h4>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-lg w-100 mb-2">
                📦 Quản lý Sản phẩm
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.orders') }}" class="btn btn-success btn-lg w-100 mb-2">
                📋 Quản lý Đơn hàng
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.discount-codes.index') }}" class="btn btn-warning btn-lg w-100 mb-2">
                🎁 Mã Giảm Giá
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-info btn-lg w-100 mb-2">
                ⭐ Đánh Giá Sản phẩm
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.chatbot') }}" class="btn btn-secondary btn-lg w-100 mb-2">
                🤖 Chatbot
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.messages') }}" class="btn btn-danger btn-lg w-100 mb-2">
                💬 Quản lý Khách hàng
            </a>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">📈 Biểu Đồ Doanh Thu (30 Ngày Gần Nhất)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 350px;">
                        {{-- Truyền dữ liệu từ PHP sang HTML bằng data attributes để JS sử dụng --}}
                        <canvas id="revenueChart"
                                data-labels='{!! json_encode($chartLabels) !!}'
                                data-revenue='{!! json_encode($chartData) !!}'>
                        </canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
{{-- Nhúng thư viện Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvas = document.getElementById('revenueChart');
        if (!canvas) return;

        // Đọc dữ liệu từ data attributes và parse thành đối tượng JavaScript
        const chartLabels = JSON.parse(canvas.dataset.labels || '[]');
        const chartData = JSON.parse(canvas.dataset.revenue || '[]');

        const ctx = canvas.getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Doanh thu',
                    data: chartData,
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    tension: 0.3,
                    fill: true,
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value)
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
