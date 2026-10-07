<?php

namespace App\Models;

use App\Services\CourierShippingService;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'uuid',
        'order_number',
        'user_id',
        'customer_name',
        'email',
        'phone',
        'address_line',
        'city',
        'region',
        'postal_code',
        'country',
        'status',
        'payment_method',
        'payment_status',
        'admin_notes',
        'courier_name',
        'courier_code',
        'tracking_number',
        'tracking_url',
        'tracking_status',
        'shipped_at',
        'delivered_at',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'subtotal',
        'shipping_fee',
        'total',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->order_number)) {
                $lastId = static::max('id') ?? 0;
                $order->order_number = 'KNK-'.str_pad((string) ($lastId + 1001), 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getFormattedOrderIdAttribute(): string
    {
        return $this->order_number ?? ('KNK-'.(1000 + $this->id));
    }

    public function getLiveTrackingUrlAttribute(): ?string
    {
        if (! empty($this->tracking_url)) {
            return $this->tracking_url;
        }

        return CourierShippingService::generateTrackingUrl(
            $this->courier_code ?: $this->courier_name,
            $this->tracking_number
        );
    }

    /**
     * @return array{code: string, name: string, badge_class: string, icon: string}
     */
    public function getCourierInfoAttribute(): array
    {
        $code = $this->courier_code ?: CourierShippingService::detectCourierCode($this->courier_name);
        $couriers = CourierShippingService::supportedCouriers();

        return $couriers[$code] ?? [
            'code' => 'other',
            'name' => $this->courier_name ?: 'Courier',
            'badge_class' => 'bg-secondary text-white',
            'icon' => 'fa-solid fa-truck',
        ];
    }

    /**
     * @return array{label: string, badge_class: string}
     */
    public function getTrackingStatusInfoAttribute(): array
    {
        $statuses = CourierShippingService::trackingStatuses();
        $key = $this->tracking_status ?: 'pending';

        return $statuses[$key] ?? [
            'label' => ucfirst(str_replace('_', ' ', $key)),
            'badge_class' => 'bg-secondary text-white',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'total' => 'decimal:2',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }
}
