<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromotionalPopup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromotionalPopupController extends Controller
{
    public function index(): View
    {
        return view('admin.popups.index', [
            'popups' => PromotionalPopup::query()->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.popups.form', [
            'popup' => new PromotionalPopup([
                'delay_seconds' => 3,
                'target_page' => 'all',
                'button_text' => 'Claim Offer Now',
                'button_url' => '/shop-category',
                'is_active' => true,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        PromotionalPopup::query()->create($data);

        return redirect()->route('admin.popups.index')->with('status', 'Promotional Pop-up created successfully.');
    }

    public function edit(PromotionalPopup $popup): View
    {
        return view('admin.popups.form', [
            'popup' => $popup,
        ]);
    }

    public function update(Request $request, PromotionalPopup $popup): RedirectResponse
    {
        $data = $this->validatedData($request, $popup);
        $popup->update($data);

        return redirect()->route('admin.popups.index')->with('status', 'Promotional Pop-up updated successfully.');
    }

    public function toggleStatus(PromotionalPopup $popup): RedirectResponse
    {
        $popup->update([
            'is_active' => ! $popup->is_active,
        ]);

        return redirect()->back()->with('status', 'Pop-up status updated.');
    }

    public function destroy(PromotionalPopup $popup): RedirectResponse
    {
        $popup->delete();

        return redirect()->route('admin.popups.index')->with('status', 'Promotional Pop-up deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?PromotionalPopup $popup = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'max:2000'],
            'discount_code' => ['nullable', 'string', 'max:50'],
            'button_text' => ['required', 'string', 'max:100'],
            'button_url' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'max:10240'],
            'remove_image' => ['sometimes', 'boolean'],
            'target_page' => ['required', 'string', 'in:all,home,shop,product'],
            'delay_seconds' => ['required', 'integer', 'min:0', 'max:120'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('popups', 'public');
            $validated['image'] = 'storage/'.$path;
        } elseif ($request->boolean('remove_image')) {
            $validated['image'] = null;
        }

        unset($validated['image_file'], $validated['remove_image']);

        $validated['is_active'] = $request->boolean('is_active', $popup?->is_active ?? true);

        return $validated;
    }
}
