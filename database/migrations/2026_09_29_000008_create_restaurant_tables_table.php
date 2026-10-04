<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_tables', function (Blueprint $table): void {
            $table->id();
            $table->unsignedTinyInteger('table_number')->unique();
            $table->string('status')->default('available');
            $table->boolean('is_paid')->default(false);
            $table->unsignedInteger('adults')->default(0);
            $table->unsignedInteger('children')->default(0);
            $table->unsignedInteger('guests')->default(0);
            $table->decimal('bill', 10, 2)->default(0);
            $table->json('orders');
            $table->timestamp('start_time')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_tables');
    }
};
