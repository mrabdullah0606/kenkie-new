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
        Schema::table('users', function (Blueprint $table) {
            $table->string('email_otp_code', 10)->nullable()->after('remember_token');
            $table->timestamp('email_otp_expires_at')->nullable()->after('email_otp_code');
            $table->string('login_otp_code', 10)->nullable()->after('email_otp_expires_at');
            $table->timestamp('login_otp_expires_at')->nullable()->after('login_otp_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_otp_code',
                'email_otp_expires_at',
                'login_otp_code',
                'login_otp_expires_at',
            ]);
        });
    }
};
