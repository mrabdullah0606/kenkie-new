<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use App\Services\PayPalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Stripe\Balance;
use Stripe\Exception\AuthenticationException;
use Stripe\Stripe;
use Throwable;

class PaymentSettingController extends Controller
{
    /**
     * Display the payment settings management view.
     */
    public function index(): View
    {
        $settings = PaymentSetting::getSettings();

        return view('admin.payments.index', [
            'settings' => $settings,
            'stripeConfigured' => PaymentSetting::isStripeConfigured(),
            'activeStripeKey' => PaymentSetting::getStripeKey(),
            'hasStripeSecret' => ! empty(PaymentSetting::getStripeSecret()),
            'paypalConfigured' => PaymentSetting::isPayPalConfigured(),
            'activePayPalClientId' => PaymentSetting::getPayPalClientId(),
            'hasPayPalSecret' => ! empty(PaymentSetting::getPayPalSecret()),
            'webhookUrl' => url('/stripe/webhook'),
        ]);
    }

    /**
     * Update Stripe gateway configuration.
     */
    public function updateStripe(Request $request): RedirectResponse
    {
        $settings = PaymentSetting::getSettings();

        $validated = $request->validate([
            'stripe_enabled' => ['sometimes', 'boolean'],
            'stripe_mode' => ['required', 'in:test,live'],
            'stripe_key' => ['nullable', 'string', 'max:255'],
            'stripe_secret' => ['nullable', 'string', 'max:255'],
            'stripe_webhook_secret' => ['nullable', 'string', 'max:255'],
        ]);

        $updateData = [
            'stripe_enabled' => $request->boolean('stripe_enabled'),
            'stripe_mode' => $validated['stripe_mode'],
            'stripe_key' => ! empty($validated['stripe_key']) ? trim($validated['stripe_key']) : null,
        ];

        // Only update secret if user typed a new non-empty value
        if ($request->filled('stripe_secret')) {
            $updateData['stripe_secret'] = trim($validated['stripe_secret']);
        } elseif ($request->has('clear_stripe_secret') && $request->boolean('clear_stripe_secret')) {
            $updateData['stripe_secret'] = null;
        }

        // Only update webhook secret if user typed a new non-empty value
        if ($request->filled('stripe_webhook_secret')) {
            $updateData['stripe_webhook_secret'] = trim($validated['stripe_webhook_secret']);
        } elseif ($request->has('clear_stripe_webhook_secret') && $request->boolean('clear_stripe_webhook_secret')) {
            $updateData['stripe_webhook_secret'] = null;
        }

        $settings->update($updateData);

        return redirect()->route('admin.payments.index')
            ->with('status', 'Stripe payment gateway settings have been updated successfully.');
    }

    /**
     * Update Cash On Delivery (COD) configuration.
     */
    public function updateCod(Request $request): RedirectResponse
    {
        $settings = PaymentSetting::getSettings();

        $validated = $request->validate([
            'cod_enabled' => ['sometimes', 'boolean'],
            'cod_instructions' => ['nullable', 'string', 'max:1000'],
        ]);

        $settings->update([
            'cod_enabled' => $request->boolean('cod_enabled'),
            'cod_instructions' => $validated['cod_instructions'] ?? null,
        ]);

        return redirect()->route('admin.payments.index')
            ->with('status', 'Cash On Delivery (COD) settings have been saved successfully.');
    }

    /**
     * Update PayPal gateway configuration.
     */
    public function updatePayPal(Request $request): RedirectResponse
    {
        $settings = PaymentSetting::getSettings();

        $validated = $request->validate([
            'paypal_enabled' => ['sometimes', 'boolean'],
            'paypal_mode' => ['required', 'in:sandbox,live'],
            'paypal_client_id' => ['nullable', 'string', 'max:255'],
            'paypal_secret' => ['nullable', 'string', 'max:255'],
        ]);

        $updateData = [
            'paypal_enabled' => $request->boolean('paypal_enabled'),
            'paypal_mode' => $validated['paypal_mode'],
            'paypal_client_id' => ! empty($validated['paypal_client_id']) ? trim($validated['paypal_client_id']) : null,
        ];

        if ($request->filled('paypal_secret')) {
            $updateData['paypal_secret'] = trim($validated['paypal_secret']);
        }

        $settings->update($updateData);

        return redirect()->route('admin.payments.index')
            ->with('status', 'PayPal gateway settings have been saved successfully.');
    }

    /**
     * Test live connectivity with PayPal using current credentials.
     */
    public function testPayPal(): RedirectResponse
    {
        $result = PayPalService::testConnection();

        if ($result['success']) {
            return redirect()->route('admin.payments.index')
                ->with('status', $result['message']);
        }

        return redirect()->route('admin.payments.index')
            ->with('error', 'PayPal Test Failed: '.$result['message']);
    }

    /**
     * Test live connectivity with Stripe using current credentials.
     */
    public function testStripe(): RedirectResponse
    {
        $secret = PaymentSetting::getStripeSecret();

        if (empty($secret)) {
            return redirect()->route('admin.payments.index')
                ->with('error', 'No Stripe Secret Key found. Please save a valid secret key (e.g. sk_test_... or sk_live_...) first.');
        }

        if (str_contains($secret, 'placeholder')) {
            return redirect()->route('admin.payments.index')
                ->with('info', 'Local simulation key (sk_test_placeholder) detected. Orders in test simulation mode will pass automatically. To test live Stripe API communication, enter actual Stripe keys.');
        }

        try {
            Stripe::setApiKey($secret);
            $balance = Balance::retrieve();
            $mode = $balance->livemode ? 'LIVE' : 'TEST';

            return redirect()->route('admin.payments.index')
                ->with('status', "Stripe connection successful! [Mode: {$mode}]. Your API keys are valid and successfully authenticating with Stripe servers.");
        } catch (AuthenticationException $e) {
            return redirect()->route('admin.payments.index')
                ->with('error', 'Stripe Authentication Failed: '.$e->getMessage().' Please check your Secret Key.');
        } catch (Throwable $e) {
            return redirect()->route('admin.payments.index')
                ->with('error', 'Stripe Connection Error: '.$e->getMessage());
        }
    }
}
