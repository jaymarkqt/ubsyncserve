<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('password_reset_otps')) {
            return;
        }

        if (Schema::hasTable('password_reset')) {
            throw new RuntimeException(
                'Both password_reset and password_reset_otps tables exist; resolve the duplicate before migrating.',
            );
        }

        Schema::rename('password_reset_otps', 'password_reset');
    }

    public function down(): void
    {
        if (! Schema::hasTable('password_reset')) {
            return;
        }

        if (Schema::hasTable('password_reset_otps')) {
            throw new RuntimeException(
                'Both password_reset and password_reset_otps tables exist; resolve the duplicate before rolling back.',
            );
        }

        Schema::rename('password_reset', 'password_reset_otps');
    }
};
