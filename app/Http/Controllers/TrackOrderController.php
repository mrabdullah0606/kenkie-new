<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrackOrderController extends Controller
{
    public function show(): View
    {
        return view('website.content.track-order');
    }

    public function lookup(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'order_number' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $input = trim($validated['order_number']);

        $query = Order::query()
            ->where('email', $validated['email'])
            ->where(function ($q) use ($input) {
                // Exact match on stored order_number field
                $q->where('order_number', $input)
                  // Full UUID match
                    ->orWhere('uuid', $input)
                  // Partial UUID (first 8 chars shown in UI)
                    ->orWhere('uuid', 'like', $input.'%');

                // Support computed KNK-XXXX format (KNK-{1000+id})
                if (preg_match('/^KNK[-\s]?(\d+)$/i', $input, $m)) {
                    $resolvedId = (int) $m[1] - 1000;
                    if ($resolvedId > 0) {
                        $q->orWhere('id', $resolvedId);
                    }
                    // Also try the number itself as an id
                    $q->orWhere('id', (int) $m[1]);
                }
            });

        $order = $query->with(['items.product'])->first();

        if (! $order) {
            return back()->withInput()->withErrors([
                'order_number' => 'No order found with those details. Please check your order number and email address.',
            ]);
        }

        return view('website.content.track-order', compact('order'));
    }
}
