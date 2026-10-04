<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $expectedColumns = ['email', 'resetpass', 'created_at', 'updated_at'];

        if (
            Schema::hasTable('password_reset_otps')
            && Schema::getColumnListing('password_reset_otps') === $expectedColumns
        ) {
            return;
        }

        Schema::dropIfExists('password_reset_otps');

        Schema::create('password_reset_otps', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->text('resetpass')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_otps');

        Schema::create('password_reset_otps', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('otp_hash')->nullable();
            $table->timestamp('expires_at');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('verified_at')->nullable();
            $table->string('reset_token_hash')->nullable();
            $table->timestamp('reset_token_expires_at')->nullable();
            $table->timestamps();
        });
    }
};
