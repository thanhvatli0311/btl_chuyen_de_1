<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Dữ liệu cho các thẻ thống kê ---
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');
        // The 'is_admin' column does not exist. The application likely uses a 'role' column.
        // We will count users whose role is not 'admin'.
        $totalUsers = User::where('role', '!=', 'admin')->count();

        // --- Dữ liệu cho biểu đồ doanh thu ---
        $days = 30;
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays($days - 1);

        // Lấy dữ liệu doanh thu thực tế từ DB cho các đơn hàng đã hoàn thành
        $revenueData = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy(function ($item) {
                // Key bằng ngày để dễ tra cứu
                return Carbon::parse($item->date)->format('Y-m-d');
            });

        // Tạo một mảng đầy đủ các ngày trong 30 ngày qua để đảm bảo biểu đồ liền mạch
        $period = CarbonPeriod::create($startDate, $endDate);
        $chartLabels = [];
        $chartData = [];

        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $chartLabels[] = $date->format('d/m'); // Định dạng ngày hiển thị trên biểu đồ
            // Nếu có doanh thu vào ngày này thì lấy, không thì mặc định là 0
            $chartData[] = $revenueData[$dateString]->revenue ?? 0;
        }

        return view('admin.dashboard', [
            // Dữ liệu cho các thẻ
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'totalUsers' => $totalUsers,

            // Dữ liệu cho biểu đồ
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }
}