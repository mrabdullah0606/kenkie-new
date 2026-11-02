<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketingSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('admin.profile.edit', [
            'user' => $request->user(),
            'marketingSettings' => MarketingSetting::getSettings(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('admin.profile.edit')->with('status', 'Profile details updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.profile.edit')->with('status', 'Password updated successfully.');
    }

    public function updateStoreContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'whatsapp_number' => ['required', 'string', 'max:50'],
            'whatsapp_enabled' => ['sometimes', 'boolean'],
            'whatsapp_agent_name' => ['nullable', 'string', 'max:100'],
            'whatsapp_default_message' => ['nullable', 'string', 'max:1000'],
        ]);

        $settings = MarketingSetting::getSettings();
        $settings->update([
            'whatsapp_number' => trim($validated['whatsapp_number']),
            'whatsapp_enabled' => $request->boolean('whatsapp_enabled'),
            'whatsapp_agent_name' => $validated['whatsapp_agent_name'] ?: 'Kenkie Support',
            'whatsapp_default_message' => $validated['whatsapp_default_message'] ?: 'Hello Kenkie Support! I would like some assistance with my order / product.',
        ]);

        return redirect()->route('admin.profile.edit')->with('status', 'Store WhatsApp & contact number updated successfully! The storefront widget and contact pages have been updated.');
    }
}
