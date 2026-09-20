<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_bookings', function (Blueprint $table): void {
            $table->timestamp('archived_at')->nullable()->after('status');
            $table->timestamp('confirmation_email_sent_at')->nullable()->after('archived_at');
            $table->index(['status', 'archived_at']);
        });
    }

    public function down(): void
    {
        Schema::table('customer_bookings', function (Blueprint $table): void {
            $table->dropIndex(['status', 'archived_at']);
            $table->dropColumn(['archived_at', 'confirmation_email_sent_at']);
        });
    }
};
