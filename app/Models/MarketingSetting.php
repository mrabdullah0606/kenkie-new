<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'whatsapp_enabled',
    'whatsapp_number',
    'whatsapp_default_message',
    'whatsapp_agent_name',
    'chat_assistant_enabled',
    'chat_assistant_title',
    'chat_greeting',
    'chat_faqs',
    'meta_pixel_enabled',
    'meta_pixel_id',
    'tiktok_pixel_enabled',
    'tiktok_pixel_id',
    'google_analytics_enabled',
    'google_analytics_id',
    'google_ads_enabled',
    'google_ads_id',
    'google_site_verification',
    'meta_title',
    'meta_description',
    'meta_keywords',
])]
class MarketingSetting extends Model
{
    public static function getSettings(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
                'whatsapp_enabled' => true,
                'whatsapp_number' => '+44 7123 456789',
                'whatsapp_default_message' => 'Hello Kenkie Support! I would like some assistance with my order / product.',
                'whatsapp_agent_name' => 'Kenkie Help Desk',
                'chat_assistant_enabled' => true,
                'chat_assistant_title' => 'Kenkie Quick Assistant',
                'chat_greeting' => 'Welcome to Kenkie! 👋 How can we assist you today? Click any quick option or chat directly with our team on WhatsApp.',
                'chat_faqs' => [
                    [
                        'q' => '📦 How do I track my order?',
                        'a' => 'You can track your order instantly anytime on our Track Order page using your KNK order number (e.g., KNK-1001) or UUID.',
                    ],
                    [
                        'q' => '🚚 What are delivery times and shipping costs?',
                        'a' => 'Standard UK delivery takes 2–3 business days via Evri / Royal Mail / DPD. Express delivery options are available at checkout.',
                    ],
                    [
                        'q' => '🔄 What is the return and refund policy?',
                        'a' => 'We offer a 14-day hassle-free return guarantee on all items in original packaging. Refunds are processed within 2–4 business days.',
                    ],
                    [
                        'q' => '💳 What payment methods are supported?',
                        'a' => 'We accept all major Credit & Debit cards, Visa, Mastercard, American Express, and secure Stripe checkout.',
                    ],
                ],
                'meta_pixel_enabled' => false,
                'meta_pixel_id' => null,
                'tiktok_pixel_enabled' => false,
                'tiktok_pixel_id' => null,
                'google_analytics_enabled' => false,
                'google_analytics_id' => null,
                'google_ads_enabled' => false,
                'google_ads_id' => null,
                'google_site_verification' => '-dGzkf4mqQ32aE6zvmSo35tzlFXXwWPjpE9YBF6jpxg',
                'meta_title' => 'KENKIE – Online Shopping Store | Home of the Future Gadgets.',
                'meta_description' => 'KENKIE - Delivers all over UK | Deals in electronic devices, mobile accessories, top-notch handy gadgets and many more.',
                'meta_keywords' => 'KENKIE, online shopping store, electronics, gadgets, UK delivery, mobile accessories',
            ]
        );

        if (empty($settings->meta_title)) {
            $settings->meta_title = 'KENKIE – Online Shopping Store | Home of the Future Gadgets.';
        }
        if (empty($settings->meta_description)) {
            $settings->meta_description = 'KENKIE - Delivers all over UK | Deals in electronic devices, mobile accessories, top-notch handy gadgets and many more.';
        }
        if (empty($settings->meta_keywords)) {
            $settings->meta_keywords = 'KENKIE, online shopping store, electronics, gadgets, UK delivery, mobile accessories';
        }
        if (empty($settings->google_site_verification)) {
            $settings->google_site_verification = '-dGzkf4mqQ32aE6zvmSo35tzlFXXwWPjpE9YBF6jpxg';
        }

        return $settings;
    }

    protected function casts(): array
    {
        return [
            'whatsapp_enabled' => 'boolean',
            'chat_assistant_enabled' => 'boolean',
            'chat_faqs' => 'array',
            'meta_pixel_enabled' => 'boolean',
            'tiktok_pixel_enabled' => 'boolean',
            'google_analytics_enabled' => 'boolean',
            'google_ads_enabled' => 'boolean',
        ];
    }
}
