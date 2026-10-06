<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->decimal('subtotal_amount', 10, 2)->default(0);
            $table->string('discount_type')->nullable();
            $table->text('discount_id_number')->nullable();
            $table->string('discount_id_image_path')->nullable();
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('vat_amount', 10, 2)->default(0);
            $table->decimal('grand_total_amount', 10, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropColumn([
                'subtotal_amount',
                'discount_type',
                'discount_id_number',
                'discount_id_image_path',
                'discount_amount',
                'vat_amount',
                'grand_total_amount',
            ]);
        });
    }
};
