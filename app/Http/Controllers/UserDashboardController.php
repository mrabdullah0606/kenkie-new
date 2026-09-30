<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\StorefrontCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    public function index(Request $request, StorefrontCart $cart): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $orders = Order::query()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('email', $user->email);
            })
            ->with(['items.product'])
            ->latest()
            ->get();

        $cartContents = $cart->contents($request);

        $stats = [
            'total_orders' => $orders->count(),
            'pending_orders' => $orders->where('status', 'pending')->count(),
            'completed_orders' => $orders->where('status', 'completed')->count(),
            'processing_orders' => $orders->where('status', 'processing')->count(),
        ];

        return view('website.content.user-dashboard', [
            'user' => $user,
            'orders' => $orders,
            'stats' => $stats,
            'cartCount' => $cartContents['headerCartCount'] ?? 0,
            'wishlistCount' => $cartContents['headerWishlistCount'] ?? 0,
        ]);
    }

    public function showOrder(Request $request, string $uuid): View|RedirectResponse
    {
        $user = $request->user();

        $order = Order::query()
            ->where('uuid', $uuid)
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('email', $user->email);
            })
            ->with(['items.product'])
            ->firstOrFail();

        return view('website.content.order-details', [
            'order' => $order,
            'user' => $user,
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update($validated);

        return back()->with('status', 'Profile information updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'Password changed successfully.');
    }
}
