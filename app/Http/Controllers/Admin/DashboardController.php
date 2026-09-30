<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $totalRevenue = Order::query()->sum('total');

        return view('admin.dashboard', [
            'productCount' => Product::query()->count(),
            'categoryCount' => Category::query()->count(),
            'orderCount' => Order::query()->count(),
            'userCount' => User::query()->count(),
            'totalRevenue' => (float) $totalRevenue,
            'latestOrders' => Order::query()->with('items')->latest()->take(6)->get(),
            'featuredProducts' => Product::query()->with('category')->where('is_active', true)->orderByDesc('is_featured')->take(5)->get(),
        ]);
    }
}
