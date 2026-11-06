<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('standard_shipping_fee', 8, 2)->default(8.95);
            $table->boolean('free_shipping_enabled')->default(true);
            $table->decimal('free_shipping_threshold', 8, 2)->default(50.00);
            $table->string('estimated_delivery_text')->default('2-3 Business Days via Evri / Royal Mail');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_settings');
    }
};
