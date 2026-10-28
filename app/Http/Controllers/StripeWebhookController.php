<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Event;
use Stripe\Webhook;
use Throwable;

class StripeWebhookController extends Controller
{
    /**
     * Handle incoming Stripe webhook notifications.
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = PaymentSetting::getStripeWebhookSecret();

        $event = null;

        if (! empty($webhookSecret) && ! empty($sigHeader)) {
            try {
                $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
            } catch (Throwable $e) {
                Log::warning('Stripe webhook signature verification failed: '.$e->getMessage());

                return response()->json(['error' => 'Invalid signature'], 400);
            }
        } else {
            $data = json_decode($payload, true);
            if (! is_array($data) || empty($data['type'])) {
                return response()->json(['error' => 'Invalid payload'], 400);
            }
            try {
                $event = Event::constructFrom($data);
            } catch (Throwable $e) {
                Log::warning('Stripe webhook event construction failed: '.$e->getMessage());

                return response()->json(['error' => 'Invalid event structure'], 400);
            }
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->handleCheckoutSessionCompleted($session);
                break;

            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentIntentSucceeded($paymentIntent);
                break;

            default:
                Log::info('Unhandled Stripe webhook event: '.$event->type);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle completed checkout session.
     */
    protected function handleCheckoutSessionCompleted(object $session): void
    {
        $orderUuid = $session->metadata->order_uuid ?? null;
        $orderId = $session->metadata->order_id ?? null;
        $sessionId = $session->id ?? null;

        $order = null;
        if ($orderUuid) {
            $order = Order::query()->where('uuid', $orderUuid)->first();
        }
        if (! $order && $sessionId) {
            $order = Order::query()->where('stripe_session_id', $sessionId)->first();
        }
        if (! $order && $orderId) {
            $order = Order::query()->find($orderId);
        }

        if ($order && $order->payment_status !== 'paid') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'stripe_payment_intent_id' => is_string($session->payment_intent ?? null) ? $session->payment_intent : null,
            ]);

            CheckoutController::sendOrderEmails($order);
            Log::info("Order #{$order->id} ({$order->uuid}) marked as paid via Stripe webhook.");
        }
    }

    /**
     * Handle payment intent succeeded.
     */
    protected function handlePaymentIntentSucceeded(object $paymentIntent): void
    {
        $orderId = $paymentIntent->metadata->order_id ?? null;
        $intentId = $paymentIntent->id ?? null;

        $order = null;
        if ($orderId) {
            $order = Order::query()->find($orderId);
        }
        if (! $order && $intentId) {
            $order = Order::query()->where('stripe_payment_intent_id', $intentId)->first();
        }

        if ($order && $order->payment_status !== 'paid') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
            ]);

            CheckoutController::sendOrderEmails($order);
            Log::info("Order #{$order->id} marked as paid via payment_intent.succeeded.");
        }
    }
}
