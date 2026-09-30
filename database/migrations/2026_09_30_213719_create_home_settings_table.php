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
        Schema::create('home_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hero_badge')->nullable()->default('Weekend Special Offer');
            $table->string('hero_title')->nullable()->default('Premium Quality Home & Garden Collection');
            $table->string('hero_subtitle')->nullable()->default('Online Shopping Made Easy & Fast');
            $table->text('hero_description')->nullable();
            $table->string('hero_button_text')->nullable()->default('Shop Collection');
            $table->string('hero_button_url')->nullable()->default('/shop-category');
            $table->string('hero_image')->nullable()->default('assets/images/banner/kenkie-hero-banner.jpg');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_settings');
    }
};
