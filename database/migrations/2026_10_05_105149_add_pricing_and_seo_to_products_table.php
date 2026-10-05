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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('regular_price', 10, 2)->nullable()->after('price');
            $table->decimal('cost_price', 10, 2)->nullable()->after('regular_price');
            $table->string('size_chart')->nullable()->after('images');
            $table->string('meta_title')->nullable()->after('is_active');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->text('meta_keywords')->nullable()->after('meta_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'regular_price',
                'cost_price',
                'size_chart',
                'meta_title',
                'meta_description',
                'meta_keywords',
            ]);
        });
    }
};
