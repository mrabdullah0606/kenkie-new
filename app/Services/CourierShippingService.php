<?php

namespace App\Services;

class CourierShippingService
{
    public const string COURIER_EVRI = 'evri';

    public const string COURIER_ROYAL_MAIL = 'royal_mail';

    public const string COURIER_DPD = 'dpd';

    public const string COURIER_OTHER = 'other';

    /**
     * @return array<string, array{
     *     code: string,
     *     name: string,
     *     website: string,
     *     tracking_url_template: ?string,
     *     badge_class: string,
     *     icon: string,
     *     placeholder: string
     * }>
     */
    public static function supportedCouriers(): array
    {
        return [
            self::COURIER_EVRI => [
                'code' => self::COURIER_EVRI,
                'name' => 'Evri',
                'website' => 'https://www.evri.com',
                'tracking_url_template' => 'https://www.evri.com/track-a-parcel?trackingNumber={tracking}',
                'badge_class' => 'bg-info text-white',
                'icon' => 'fa-solid fa-box-archive',
                'placeholder' => 'e.g. 16-character tracking number (H00WWA0001234567)',
            ],
            self::COURIER_ROYAL_MAIL => [
                'code' => self::COURIER_ROYAL_MAIL,
                'name' => 'Royal Mail',
                'website' => 'https://www.royalmail.com',
                'tracking_url_template' => 'https://www.royalmail.com/track-your-item#/tracking-results/{tracking}',
                'badge_class' => 'bg-danger text-white',
                'icon' => 'fa-solid fa-envelope',
                'placeholder' => 'e.g. 13-character barcode (RM123456789GB or GB123456789GB)',
            ],
            self::COURIER_DPD => [
                'code' => self::COURIER_DPD,
                'name' => 'DPD',
                'website' => 'https://www.dpd.co.uk',
                'tracking_url_template' => 'https://www.dpd.co.uk/tracking/quicktrack.do?search.consignmentNumber={tracking}',
                'badge_class' => 'bg-dark text-white',
                'icon' => 'fa-solid fa-truck-fast',
                'placeholder' => 'e.g. 14-digit consignment or parcel number',
            ],
            self::COURIER_OTHER => [
                'code' => self::COURIER_OTHER,
                'name' => 'Other Carrier',
                'website' => '',
                'tracking_url_template' => null,
                'badge_class' => 'bg-secondary text-white',
                'icon' => 'fa-solid fa-truck',
                'placeholder' => 'Custom tracking number / reference',
            ],
        ];
    }

    /**
     * @return array<string, array{label: string, badge_class: string}>
     */
    public static function trackingStatuses(): array
    {
        return [
            'pending' => [
                'label' => 'Pending Dispatch',
                'badge_class' => 'bg-secondary text-white',
            ],
            'label_created' => [
                'label' => 'Label Created / Manifested',
                'badge_class' => 'bg-info text-dark',
            ],
            'in_transit' => [
                'label' => 'In Transit',
                'badge_class' => 'bg-primary text-white',
            ],
            'out_for_delivery' => [
                'label' => 'Out for Delivery',
                'badge_class' => 'bg-warning text-dark',
            ],
            'delivered' => [
                'label' => 'Delivered',
                'badge_class' => 'bg-success text-white',
            ],
            'delivery_attempted' => [
                'label' => 'Delivery Attempted',
                'badge_class' => 'bg-warning text-dark',
            ],
            'returned' => [
                'label' => 'Returned to Sender',
                'badge_class' => 'bg-danger text-white',
            ],
        ];
    }

    /**
     * Generate the official live tracking URL for a given carrier and tracking code.
     */
    public static function generateTrackingUrl(?string $courierCode, ?string $trackingNumber): ?string
    {
        if (empty($trackingNumber)) {
            return null;
        }

        $courierCode = strtolower(trim((string) $courierCode));
        $trackingNumber = trim($trackingNumber);

        // Normalize matching (e.g. "Royal Mail" string to "royal_mail")
        if (str_contains($courierCode, 'evri') || str_contains($courierCode, 'hermes')) {
            $courierCode = self::COURIER_EVRI;
        } elseif (str_contains($courierCode, 'royal')) {
            $courierCode = self::COURIER_ROYAL_MAIL;
        } elseif (str_contains($courierCode, 'dpd')) {
            $courierCode = self::COURIER_DPD;
        }

        $supported = self::supportedCouriers();
        if (isset($supported[$courierCode]['tracking_url_template']) && $supported[$courierCode]['tracking_url_template']) {
            return str_replace('{tracking}', urlencode($trackingNumber), $supported[$courierCode]['tracking_url_template']);
        }

        return null;
    }

    /**
     * Detect or normalize courier code from name or input.
     */
    public static function detectCourierCode(?string $name): string
    {
        $normalized = strtolower(trim((string) $name));
        if (str_contains($normalized, 'evri') || str_contains($normalized, 'hermes')) {
            return self::COURIER_EVRI;
        }
        if (str_contains($normalized, 'royal')) {
            return self::COURIER_ROYAL_MAIL;
        }
        if (str_contains($normalized, 'dpd')) {
            return self::COURIER_DPD;
        }

        return self::COURIER_OTHER;
    }
}
