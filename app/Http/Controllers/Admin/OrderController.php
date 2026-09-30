<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $ordersQuery = Order::query()
            ->with(['items', 'user'])
            ->when($status && in_array($status, ['pending', 'processing', 'completed', 'cancelled']), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('uuid', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest();

        $statusCounts = [
            'all' => Order::query()->count(),
            'pending' => Order::query()->where('status', 'pending')->count(),
            'processing' => Order::query()->where('status', 'processing')->count(),
            'completed' => Order::query()->where('status', 'completed')->count(),
            'cancelled' => Order::query()->where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', [
            'orders' => $ordersQuery->paginate(15)->withQueryString(),
            'currentStatus' => $status,
            'search' => $search,
            'statusCounts' => $statusCounts,
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load(['items', 'user']),
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,processing,completed,cancelled'],
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order)->with('status', 'Order status updated to '.ucfirst($validated['status']).'.');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()->route('admin.orders.index')->with('status', 'Order deleted successfully.');
    }
}
