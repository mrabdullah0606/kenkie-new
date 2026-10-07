<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\MarketingSetting;
use App\Models\Product;
use App\Models\PromotionalPopup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketingAndPopupsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_admin_can_view_and_create_promotional_popups(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.popups.index'));
        $response->assertOk();

        $createResponse = $this->actingAs($this->admin)->post(route('admin.popups.store'), [
            'title' => 'Flash Sale 20% Off',
            'subtitle' => 'Weekend Only',
            'content' => 'Grab your favorites now with extra discount.',
            'discount_code' => 'WEEKEND20',
            'button_text' => 'Shop Deals',
            'button_url' => '/shop-category',
            'target_page' => 'all',
            'delay_seconds' => 2,
            'is_active' => true,
        ]);

        $createResponse->assertRedirect(route('admin.popups.index'));
        $this->assertDatabaseHas('promotional_popups', [
            'title' => 'Flash Sale 20% Off',
            'discount_code' => 'WEEKEND20',
        ]);
    }

    public function test_admin_can_toggle_popup_status(): void
    {
        $popup = PromotionalPopup::query()->create([
            'title' => 'Test Popup',
            'button_text' => 'Shop',
            'button_url' => '/shop-category',
            'target_page' => 'all',
            'delay_seconds' => 3,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.popups.toggle-status', $popup));
        $response->assertRedirect();
        $this->assertFalse($popup->fresh()->is_active);
    }

    public function test_admin_can_update_whatsapp_and_ai_assistant_settings(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.marketing.index'));
        $response->assertOk();

        $updateResponse = $this->actingAs($this->admin)->put(route('admin.marketing.whatsapp'), [
            'whatsapp_enabled' => true,
            'whatsapp_number' => '+447999888777',
            'whatsapp_agent_name' => 'Kenkie Support Desk',
            'whatsapp_default_message' => 'Need help with order',
            'chat_assistant_enabled' => true,
            'chat_assistant_title' => 'Kenkie AI Assistant',
            'chat_greeting' => 'Hello there!',
            'chat_faqs' => [
                ['q' => 'How to track?', 'a' => 'Go to /track-order'],
            ],
        ]);

        $updateResponse->assertRedirect(route('admin.marketing.index'));
        $settings = MarketingSetting::getSettings();
        $this->assertEquals('+447999888777', $settings->whatsapp_number);
        $this->assertEquals('Kenkie Support Desk', $settings->whatsapp_agent_name);
        $this->assertCount(1, $settings->chat_faqs);
    }

    public function test_admin_can_update_marketing_pixels(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.marketing.pixels'), [
            'meta_pixel_enabled' => true,
            'meta_pixel_id' => '1234567890',
            'tiktok_pixel_enabled' => true,
            'tiktok_pixel_id' => 'TIKTOK123',
            'google_analytics_enabled' => true,
            'google_analytics_id' => 'G-ABC123XYZ',
            'google_ads_enabled' => true,
            'google_ads_id' => 'AW-987654',
        ]);

        $response->assertRedirect(route('admin.marketing.index'));
        $settings = MarketingSetting::getSettings();
        $this->assertTrue($settings->meta_pixel_enabled);
        $this->assertEquals('1234567890', $settings->meta_pixel_id);
        $this->assertEquals('TIKTOK123', $settings->tiktok_pixel_id);
        $this->assertEquals('G-ABC123XYZ', $settings->google_analytics_id);
    }

    public function test_storefront_renders_whatsapp_widget_and_active_popup(): void
    {
        PromotionalPopup::query()->create([
            'title' => 'Storefront Welcome Popup',
            'subtitle' => 'Special promo',
            'content' => 'Use code HELLO10',
            'discount_code' => 'HELLO10',
            'button_text' => 'Shop Now',
            'button_url' => '/shop-category',
            'target_page' => 'all',
            'delay_seconds' => 1,
            'is_active' => true,
        ]);

        MarketingSetting::getSettings()->update([
            'whatsapp_enabled' => true,
            'whatsapp_number' => '+447000000000',
        ]);

        $response = $this->get(route('home'));
        $response->assertOk();
        $response->assertSee('Storefront Welcome Popup');
        $response->assertSee('HELLO10');
        $response->assertSee('kenkieSupportWidget');
        $response->assertSee('Start WhatsApp Chat');
    }

    public function test_product_page_renders_size_guide_modal_and_tab(): void
    {
        $category = Category::factory()->create([
            'size_chart' => 'assets/images/size-charts/category-guide.jpg',
        ]);

        $product = Product::factory()->create([
            'category_id' => $category->id,
            'size_chart' => 'assets/images/size-charts/product-guide.jpg',
        ]);

        $response = $this->get(route('products.show', $product->slug));
        $response->assertOk();
        $response->assertSee('Size Guide');
        $response->assertSee('sizeChartModal');
        $response->assertSee('sizeguide-tab');
        $response->assertSee('assets/images/size-charts/product-guide.jpg');
    }

    public function test_multi_buy_offer_allow_on_discounted_toggle_behavior(): void
    {
        $category = Category::factory()->create();

        // Product on sale ($100 regular, $80 current sale price)
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'regular_price' => 100.00,
            'price' => 80.00,
            'stock' => 10,
        ]);

        // Offer that is NOT allowed on already-discounted products
        $noStackOffer = $product->offers()->create([
            'title' => 'Buy 2 Get 10% Off',
            'min_quantity' => 2,
            'discount_percentage' => 10,
            'allow_on_discounted' => false,
            'is_active' => true,
        ]);

        // Product page should not show the non-stackable offer because product is already on sale
        $response = $this->get(route('products.show', $product->slug));
        $response->assertOk();
        $response->assertDontSee('Multi-Buy Savings');

        // Now enable allow_on_discounted
        $noStackOffer->update(['allow_on_discounted' => true]);

        $response2 = $this->get(route('products.show', $product->slug));
        $response2->assertOk();
        $response2->assertSee('Multi-Buy Savings');

        // Adding 2 to cart should calculate bundle discount off the $80 price (10% off $80 = $72 each -> $144 total)
        $this->withSession(['cart' => [$product->id => 2]])
            ->get(route('cart.index'))
            ->assertOk()
            ->assertSee('$144.00');
    }
}
