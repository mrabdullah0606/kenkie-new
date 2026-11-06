<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promotional_popups', function (Blueprint $table) {
            $table->string('discount_type')->default('percentage')->after('discount_code'); // percentage or fixed
            $table->decimal('discount_value', 10, 2)->default(10.00)->after('discount_type');
            $table->decimal('min_order_amount', 10, 2)->default(0.00)->after('discount_value');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('coupon_code')->nullable()->after('postal_code');
            $table->decimal('discount_amount', 10, 2)->default(0.00)->after('subtotal');
        });
    }

    public function down(): void
    {
        Schema::table('promotional_popups', function (Blueprint $table) {
            $table->dropColumn(['discount_type', 'discount_value', 'min_order_amount']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['coupon_code', 'discount_amount']);
        });
    }
};
