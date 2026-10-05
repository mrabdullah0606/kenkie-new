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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_number')->nullable()->unique()->after('uuid');
            $table->text('admin_notes')->nullable()->after('payment_status');
            $table->string('courier_name')->nullable()->after('admin_notes');
            $table->string('tracking_number')->nullable()->after('courier_name');
            $table->string('tracking_url')->nullable()->after('tracking_number');
            $table->timestamp('shipped_at')->nullable()->after('tracking_url');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_number',
                'admin_notes',
                'courier_name',
                'tracking_number',
                'tracking_url',
                'shipped_at',
                'delivered_at',
            ]);
        });
    }
};
