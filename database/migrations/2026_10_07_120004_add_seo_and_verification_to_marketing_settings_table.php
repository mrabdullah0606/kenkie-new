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
        Schema::table('marketing_settings', function (Blueprint $table) {
            $table->string('google_site_verification')->default('-dGzkf4mqQ32aE6zvmSo35tzlFXXwWPjpE9YBF6jpxg')->after('google_ads_id');
            $table->string('meta_title')->nullable()->after('google_site_verification');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->text('meta_keywords')->nullable()->after('meta_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_settings', function (Blueprint $table) {
            $table->dropColumn([
                'google_site_verification',
                'meta_title',
                'meta_description',
                'meta_keywords',
            ]);
        });
    }
};
