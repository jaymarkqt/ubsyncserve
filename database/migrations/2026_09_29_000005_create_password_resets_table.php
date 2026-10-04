<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const OLD_TABLES = [
        'password_reset',
        'password_reset_otps',
        'password_reset_legacy',
    ];

    public function up(): void
    {
        foreach (self::OLD_TABLES as $table) {
            Schema::dropIfExists($table);
        }

        Schema::dropIfExists('password_resets');

        Schema::create('password_resets', function (Blueprint $table): void {
            $table->id();
            $table->string('email', 255)->index();
            $table->string('token', 255)->unique();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('expires_at')->useCurrent();
            $table->boolean('is_used')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_resets');

        Schema::create('password_reset', function (Blueprint $table): void {
            $table->id();
            $table->string('email', 255)->index();
            $table->string('token', 255)->unique();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('expires_at')->useCurrent();
        });
    }
};
