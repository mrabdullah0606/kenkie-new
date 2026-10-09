<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $totalRevenue = (float) Order::query()->where('status', '!=', 'cancelled')->sum('total');
        $todayRevenue = (float) Order::query()->whereDate('created_at', today())->where('status', '!=', 'cancelled')->sum('total');
        $thisMonthRevenue = (float) Order::query()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        $orderCount = Order::query()->count();
        $completedOrdersCount = Order::query()->whereIn('status', ['completed', 'delivered'])->count();
        $pendingOrdersCount = Order::query()->whereIn('status', ['pending', 'processing'])->count();
        $cancelledOrdersCount = Order::query()->where('status', 'cancelled')->count();

        $averageOrderValue = $orderCount > 0 ? (float) ($totalRevenue / max(1, $orderCount - $cancelledOrdersCount)) : 0.0;
        $customerCount = User::query()->where('role', '!=', 'admin')->count();
        $guestCustomerCount = Order::query()->whereNull('user_id')->distinct('email')->count('email');
        $totalCustomers = $customerCount + $guestCustomerCount;

        $productCount = Product::query()->count();
        $categoryCount = Category::query()->count();
        $lowStockCount = Product::query()->where('stock', '<=', 5)->count();

        // 6-Month Sales & Orders Trend (Database-agnostic for MySQL & SQLite)
        $pastOrders = Order::query()
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->where('status', '!=', 'cancelled')
            ->get(['created_at', 'total']);

        $chartLabels = [];
        $chartRevenueData = [];
        $chartOrderData = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthKey = $monthDate->format('Y-m');
            $chartLabels[] = $monthDate->format('M Y');

            $matching = $pastOrders->filter(fn ($o) => $o->created_at?->format('Y-m') === $monthKey);
            $chartRevenueData[] = round((float) $matching->sum('total'), 2);
            $chartOrderData[] = $matching->count();
        }

        // Order Status Distribution
        $statusCounts = Order::query()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Top Selling Products
        $topSellingProducts = OrderItem::query()
            ->selectRaw('product_name, sku, SUM(quantity) as total_qty, SUM(line_total) as total_sales')
            ->groupBy('product_name', 'sku')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Low stock products alert
        $lowStockProducts = Product::query()
            ->with('category')
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'totalRevenue' => $totalRevenue,
            'todayRevenue' => $todayRevenue,
            'thisMonthRevenue' => $thisMonthRevenue,
            'orderCount' => $orderCount,
            'completedOrdersCount' => $completedOrdersCount,
            'pendingOrdersCount' => $pendingOrdersCount,
            'cancelledOrdersCount' => $cancelledOrdersCount,
            'averageOrderValue' => $averageOrderValue,
            'productCount' => $productCount,
            'categoryCount' => $categoryCount,
            'customerCount' => $totalCustomers,
            'registeredUserCount' => $customerCount,
            'lowStockCount' => $lowStockCount,
            'chartLabels' => $chartLabels,
            'chartRevenueData' => $chartRevenueData,
            'chartOrderData' => $chartOrderData,
            'statusCounts' => $statusCounts,
            'topSellingProducts' => $topSellingProducts,
            'lowStockProducts' => $lowStockProducts,
            'latestOrders' => Order::query()->with('items')->latest()->take(6)->get(),
            'featuredProducts' => Product::query()->with('category')->where('is_active', true)->orderByDesc('is_featured')->take(5)->get(),
        ]);
    }
}
