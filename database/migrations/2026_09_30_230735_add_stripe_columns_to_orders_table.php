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
            if (! Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('payment_method');
            }
            if (! Schema::hasColumn('orders', 'stripe_session_id')) {
                $table->string('stripe_session_id')->nullable()->after('payment_status');
            }
            if (! Schema::hasColumn('orders', 'stripe_payment_intent_id')) {
                $table->string('stripe_payment_intent_id')->nullable()->after('stripe_session_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('orders', 'payment_status')) {
                $columns[] = 'payment_status';
            }
            if (Schema::hasColumn('orders', 'stripe_session_id')) {
                $columns[] = 'stripe_session_id';
            }
            if (Schema::hasColumn('orders', 'stripe_payment_intent_id')) {
                $columns[] = 'stripe_payment_intent_id';
            }
            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
