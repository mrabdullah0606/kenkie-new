<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public const ALLOWED_STATUSES = [
        'pending',
        'processing',
        'shipped',
        'in_transit',
        'delivered',
        'completed',
        'returned',
        'cancelled',
    ];

    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $ordersQuery = Order::query()
            ->with(['items', 'user'])
            ->when($status && in_array($status, self::ALLOWED_STATUSES, true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('uuid', 'like', "%{$search}%")
                        ->orWhere('order_number', 'like', "%{$search}%")
                        ->orWhere('tracking_number', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest();

        $statusCounts = [
            'all' => Order::query()->count(),
            'pending' => Order::query()->where('status', 'pending')->count(),
            'processing' => Order::query()->where('status', 'processing')->count(),
            'shipped' => Order::query()->where('status', 'shipped')->count(),
            'in_transit' => Order::query()->where('status', 'in_transit')->count(),
            'delivered' => Order::query()->where('status', 'delivered')->count(),
            'completed' => Order::query()->where('status', 'completed')->count(),
            'returned' => Order::query()->where('status', 'returned')->count(),
            'cancelled' => Order::query()->where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', [
            'orders' => $ordersQuery->paginate(20)->withQueryString(),
            'currentStatus' => $status,
            'search' => $search,
            'statusCounts' => $statusCounts,
            'allowedStatuses' => self::ALLOWED_STATUSES,
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load(['items', 'user']),
            'allowedStatuses' => self::ALLOWED_STATUSES,
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        return $this->updateStatus($request, $order);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', self::ALLOWED_STATUSES)],
        ]);

        $newStatus = $validated['status'];
        $updates = ['status' => $newStatus];

        if ($newStatus === 'shipped' && empty($order->shipped_at)) {
            $updates['shipped_at'] = now();
        } elseif (in_array($newStatus, ['delivered', 'completed'], true) && empty($order->delivered_at)) {
            $updates['delivered_at'] = now();
        }

        $order->update($updates);

        return redirect()->route('admin.orders.show', $order)->with('status', 'Order status updated to '.str_replace('_', ' ', ucfirst($newStatus)).'.');
    }

    public function updateTracking(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'courier_name' => ['nullable', 'string', 'max:255'],
            'tracking_number' => ['nullable', 'string', 'max:255'],
            'tracking_url' => ['nullable', 'string', 'max:500'],
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order)->with('status', 'Courier tracking information updated successfully.');
    }

    public function updateNotes(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order)->with('status', 'Internal admin notes saved.');
    }

    public function updateShippingAddress(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address_line' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'region' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order)->with('status', 'Shipping and customer contact address updated.');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()->route('admin.orders.index')->with('status', 'Order deleted successfully.');
    }
}
