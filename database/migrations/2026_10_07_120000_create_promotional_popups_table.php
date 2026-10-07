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
        Schema::create('promotional_popups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('content')->nullable();
            $table->string('discount_code')->nullable();
            $table->string('button_text')->default('Claim Offer');
            $table->string('button_url')->default('/shop-category');
            $table->string('image')->nullable();
            $table->string('target_page')->default('all'); // all, home, product, category
            $table->unsignedInteger('delay_seconds')->default(3);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotional_popups');
    }
};
