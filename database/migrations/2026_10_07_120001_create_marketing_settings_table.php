<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('marketing_settings', function (Blueprint $table) {
            $table->id();
            // WhatsApp
            $table->boolean('whatsapp_enabled')->default(true);
            $table->string('whatsapp_number')->default('+447123456789');
            $table->text('whatsapp_default_message')->nullable();
            $table->string('whatsapp_agent_name')->default('Kenkie Support');

            // AI / Instant Assistant Chatbot
            $table->boolean('chat_assistant_enabled')->default(true);
            $table->string('chat_assistant_title')->default('Kenkie Assistant');
            $table->text('chat_greeting')->nullable();
            $table->json('chat_faqs')->nullable();

            // Tracking & Pixels
            $table->boolean('meta_pixel_enabled')->default(false);
            $table->string('meta_pixel_id')->nullable();

            $table->boolean('tiktok_pixel_enabled')->default(false);
            $table->string('tiktok_pixel_id')->nullable();

            $table->boolean('google_analytics_enabled')->default(false);
            $table->string('google_analytics_id')->nullable();

            $table->boolean('google_ads_enabled')->default(false);
            $table->string('google_ads_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_settings');
    }
};
