<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FraudDetectionService
{
    /**
     * Common disposable and temporary email domains.
     *
     * @var array<string>
     */
    protected static array $disposableDomains = [
        'mailinator.com',
        '10minutemail.com',
        'tempmail.com',
        'temp-mail.org',
        'guerrillamail.com',
        'sharklasers.com',
        'throwawaymail.com',
        'yopmail.com',
        'yopmail.fr',
        'trashmail.com',
        'dispostable.com',
        'getnada.com',
        'fakemailgenerator.com',
        'maildrop.cc',
        'inboxkitten.com',
        'mohmal.com',
        'crazymailing.com',
        'generator.email',
    ];

    /**
     * Analyze order data and request context for potential fraud or high risk.
     *
     * @param  array<string, mixed>  $validatedData
     * @param  array<int, array<string, mixed>>  $lineItems
     * @return array{score: string, numeric_score: int, flags: array<string>}
     */
    public static function evaluate(array $validatedData, array $lineItems, ?Request $request = null, float $totalAmount = 0.0): array
    {
        $flags = [];
        $numericScore = 0;

        $email = strtolower(trim((string) ($validatedData['email'] ?? '')));
        $phone = preg_replace('/[^\d]/', '', (string) ($validatedData['phone'] ?? ''));
        $name = strtolower(trim((string) ($validatedData['customer_name'] ?? '')));
        $ip = $request?->ip();

        // 1. Check disposable email domain
        $emailDomain = substr(strrchr($email, '@') ?: '', 1);
        if ($emailDomain && in_array($emailDomain, self::$disposableDomains, true)) {
            $flags[] = "Disposable / temporary email domain detected (@{$emailDomain}).";
            $numericScore += 40;
        }

        // 2. Check rapid sequential orders from same IP or email (within 15 minutes)
        if ($ip || $email) {
            try {
                $hasIpColumn = Schema::hasColumn('orders', 'ip_address');
                $recentOrdersCount = Order::query()
                    ->where('created_at', '>=', now()->subMinutes(15))
                    ->where(function ($query) use ($ip, $email, $hasIpColumn) {
                        if ($email) {
                            $query->where('email', $email);
                        }
                        if ($ip && $hasIpColumn) {
                            $query->orWhere('ip_address', $ip);
                        }
                    })
                    ->count();

                if ($recentOrdersCount >= 2) {
                    $flags[] = "Multiple rapid orders ({$recentOrdersCount} in last 15 mins) from same IP/email.";
                    $numericScore += 30;
                }
            } catch (\Throwable $e) {
                // Graceful fallback if database schema is undergoing migration or connection glitch
            }
        }

        // 3. High monetary order value
        if ($totalAmount >= 500.00) {
            $flags[] = 'Unusually high order value (£'.number_format($totalAmount, 2).' exceeds standard threshold).';
            $numericScore += 20;
        }

        // 4. Large single product quantities
        foreach ($lineItems as $item) {
            $qty = (int) ($item['quantity'] ?? 1);
            if ($qty >= 10) {
                $displayName = $item['displayName'] ?? 'Product';
                $flags[] = "Bulk quantity detected ({$qty} units of {$displayName}).";
                $numericScore += 15;
                break;
            }
        }

        // 5. Suspicious / dummy names
        if (preg_match('/^(test|asdf|qwerty|fake|demo|anon|user)\b/i', $name) || strlen($name) < 3) {
            $flags[] = "Suspicious or dummy customer name ('{$name}').";
            $numericScore += 15;
        }

        // 6. Suspicious phone number
        if ($phone && (strlen($phone) < 7 || preg_match('/^(\d)\1+$/', $phone))) {
            $flags[] = 'Suspicious or invalid phone number.';
            $numericScore += 15;
        }

        // Determine category: low, medium, high
        $riskScore = match (true) {
            $numericScore >= 45 => 'high',
            $numericScore >= 20 => 'medium',
            default => 'low',
        };

        return [
            'score' => $riskScore,
            'numeric_score' => $numericScore,
            'flags' => $flags,
        ];
    }
}
