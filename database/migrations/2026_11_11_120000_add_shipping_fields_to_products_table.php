<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('shipping_type')->default('default')->after('cost_price'); // default, free, custom
            $table->decimal('shipping_fee', 10, 2)->nullable()->after('shipping_type');
            $table->string('courier_name')->nullable()->after('shipping_fee');
            $table->string('delivery_time_text')->nullable()->after('courier_name');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['shipping_type', 'shipping_fee', 'courier_name', 'delivery_time_text']);
        });
    }
};
