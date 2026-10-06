<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('orders')
            ->where('grand_total_amount', '>', 0)
            ->update(['total_amount' => DB::raw('grand_total_amount')]);
    }

    public function down(): void
    {
    }
};
