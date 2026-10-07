<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketingSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketingSettingController extends Controller
{
    public function index(): View
    {
        return view('admin.marketing.settings', [
            'settings' => MarketingSetting::getSettings(),
        ]);
    }

    public function updateWhatsApp(Request $request): RedirectResponse
    {
        $settings = MarketingSetting::getSettings();

        $validated = $request->validate([
            'whatsapp_enabled' => ['sometimes', 'boolean'],
            'whatsapp_number' => ['required', 'string', 'max:50'],
            'whatsapp_agent_name' => ['required', 'string', 'max:100'],
            'whatsapp_default_message' => ['nullable', 'string', 'max:1000'],
            'chat_assistant_enabled' => ['sometimes', 'boolean'],
            'chat_assistant_title' => ['required', 'string', 'max:100'],
            'chat_greeting' => ['nullable', 'string', 'max:1000'],
            'chat_faqs' => ['nullable', 'array'],
            'chat_faqs.*.q' => ['nullable', 'string', 'max:255'],
            'chat_faqs.*.a' => ['nullable', 'string', 'max:1000'],
        ]);

        $faqs = [];
        if (isset($validated['chat_faqs']) && is_array($validated['chat_faqs'])) {
            foreach ($validated['chat_faqs'] as $faq) {
                if (! empty($faq['q']) && ! empty($faq['a'])) {
                    $faqs[] = [
                        'q' => trim($faq['q']),
                        'a' => trim($faq['a']),
                    ];
                }
            }
        }

        $settings->update([
            'whatsapp_enabled' => $request->boolean('whatsapp_enabled'),
            'whatsapp_number' => $validated['whatsapp_number'],
            'whatsapp_agent_name' => $validated['whatsapp_agent_name'],
            'whatsapp_default_message' => $validated['whatsapp_default_message'] ?? '',
            'chat_assistant_enabled' => $request->boolean('chat_assistant_enabled'),
            'chat_assistant_title' => $validated['chat_assistant_title'],
            'chat_greeting' => $validated['chat_greeting'] ?? '',
            'chat_faqs' => $faqs,
        ]);

        return redirect()->route('admin.marketing.index')->with('status', 'WhatsApp & AI Assistant settings saved successfully.');
    }

    public function updatePixels(Request $request): RedirectResponse
    {
        $settings = MarketingSetting::getSettings();

        $validated = $request->validate([
            'meta_pixel_enabled' => ['sometimes', 'boolean'],
            'meta_pixel_id' => ['nullable', 'string', 'max:100'],
            'tiktok_pixel_enabled' => ['sometimes', 'boolean'],
            'tiktok_pixel_id' => ['nullable', 'string', 'max:100'],
            'google_analytics_enabled' => ['sometimes', 'boolean'],
            'google_analytics_id' => ['nullable', 'string', 'max:100'],
            'google_ads_enabled' => ['sometimes', 'boolean'],
            'google_ads_id' => ['nullable', 'string', 'max:100'],
        ]);

        $settings->update([
            'meta_pixel_enabled' => $request->boolean('meta_pixel_enabled'),
            'meta_pixel_id' => $validated['meta_pixel_id'] ?? null,
            'tiktok_pixel_enabled' => $request->boolean('tiktok_pixel_enabled'),
            'tiktok_pixel_id' => $validated['tiktok_pixel_id'] ?? null,
            'google_analytics_enabled' => $request->boolean('google_analytics_enabled'),
            'google_analytics_id' => $validated['google_analytics_id'] ?? null,
            'google_ads_enabled' => $request->boolean('google_ads_enabled'),
            'google_ads_id' => $validated['google_ads_id'] ?? null,
        ]);

        return redirect()->route('admin.marketing.index')->with('status', 'Marketing pixels and tracking configuration saved successfully.');
    }

    public function updateSeo(Request $request): RedirectResponse
    {
        $settings = MarketingSetting::getSettings();

        $validated = $request->validate([
            'meta_title' => ['required', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'google_site_verification' => ['nullable', 'string', 'max:255'],
        ]);

        $settings->update([
            'meta_title' => $validated['meta_title'],
            'meta_description' => $validated['meta_description'] ?? '',
            'meta_keywords' => $validated['meta_keywords'] ?? '',
            'google_site_verification' => $validated['google_site_verification'] ?? null,
        ]);

        return redirect()->route('admin.marketing.index')->with('status', 'Global SEO and Google Verification meta tags updated successfully.');
    }
}
