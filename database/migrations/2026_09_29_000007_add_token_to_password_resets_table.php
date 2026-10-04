<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('password_resets')) {
            Schema::create('password_resets', function (Blueprint $table): void {
                $table->id();
                $table->string('email', 255)->index();
                $table->string('token', 255)->unique();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('expires_at')->useCurrent();
                $table->boolean('is_used')->default(false);
            });

            return;
        }

        if (! Schema::hasColumn('password_resets', 'is_used')) {
            Schema::table('password_resets', function (Blueprint $table): void {
                $table->boolean('is_used')->default(false);
            });
        }

        if (! Schema::hasColumn('password_resets', 'token')) {
            Schema::table('password_resets', function (Blueprint $table): void {
                $table->string('token', 255)->nullable()->after('email');
            });

            foreach (DB::table('password_resets')->whereNull('token')->get(['id']) as $reset) {
                DB::table('password_resets')
                    ->where('id', $reset->id)
                    ->update([
                        'token' => Hash::make(Str::random(64)),
                        'is_used' => true,
                    ]);
            }

            Schema::table('password_resets', function (Blueprint $table): void {
                $table->string('token', 255)->nullable(false)->change();
                $table->unique('token');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('password_resets') && Schema::hasColumn('password_resets', 'token')) {
            Schema::table('password_resets', function (Blueprint $table): void {
                $table->dropUnique(['token']);
                $table->dropColumn('token');
            });
        }
    }
};
