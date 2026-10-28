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
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();

            // Stripe
            $table->boolean('stripe_enabled')->default(true);
            $table->string('stripe_mode')->default('test'); // 'test' or 'live'
            $table->text('stripe_key')->nullable();
            $table->text('stripe_secret')->nullable();
            $table->text('stripe_webhook_secret')->nullable();

            // Cash on Delivery (COD)
            $table->boolean('cod_enabled')->default(true);
            $table->text('cod_instructions')->nullable();

            // PayPal (Extensible)
            $table->boolean('paypal_enabled')->default(false);
            $table->string('paypal_mode')->default('sandbox'); // 'sandbox' or 'live'
            $table->text('paypal_client_id')->nullable();
            $table->text('paypal_secret')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
