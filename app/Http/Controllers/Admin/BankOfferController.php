<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankOffer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BankOfferController extends Controller
{
    public function index(): View
    {
        return view('admin.offers.index', [
            'offers' => BankOffer::query()->orderBy('position')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.offers.form', [
            'offer' => new BankOffer,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        BankOffer::query()->create($this->validatedData($request));

        return redirect()->route('admin.offers.index')->with('status', 'Bank & Wallet offer created successfully.');
    }

    public function edit(BankOffer $offer): View
    {
        return view('admin.offers.form', [
            'offer' => $offer,
        ]);
    }

    public function update(Request $request, BankOffer $offer): RedirectResponse
    {
        $offer->update($this->validatedData($request, $offer));

        return redirect()->route('admin.offers.index')->with('status', 'Bank & Wallet offer updated successfully.');
    }

    public function destroy(BankOffer $offer): RedirectResponse
    {
        $offer->delete();

        return redirect()->route('admin.offers.index')->with('status', 'Bank & Wallet offer deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?BankOffer $offer = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'validity' => ['nullable', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50'],
            'color_theme' => ['required', 'string', 'in:theme-1,theme-2,theme-3,theme-4'],
            'bank_image_file' => ['nullable', 'image', 'max:5120'],
            'position' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($request->hasFile('bank_image_file')) {
            $path = $request->file('bank_image_file')->store('offers', 'public');
            $validated['bank_image'] = 'storage/'.$path;
        }

        unset($validated['bank_image_file']);

        $validated['position'] = (int) ($validated['position'] ?? 0);
        $validated['is_active'] = $request->boolean('is_active', $offer?->is_active ?? true);

        return $validated;
    }
}
