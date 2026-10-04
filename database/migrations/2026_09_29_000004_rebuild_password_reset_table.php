<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const LEGACY_TABLE = 'password_reset_legacy';

    public function up(): void
    {
        if (! Schema::hasTable('password_reset')) {
            $this->createPasswordResetTable();

            return;
        }

        if (Schema::hasTable(self::LEGACY_TABLE)) {
            throw new RuntimeException('The temporary password reset table already exists.');
        }

        Schema::rename('password_reset', self::LEGACY_TABLE);
        $this->createPasswordResetTable();

        foreach (DB::table(self::LEGACY_TABLE)->orderBy('email')->get() as $legacyChallenge) {
            $resetData = json_decode($legacyChallenge->resetpass ?? '', true);

            if (! is_array($resetData)) {
                continue;
            }

            $token = $resetData['reset_token_hash'] ?? $resetData['otp_hash'] ?? null;
            $expiresAt = $resetData['reset_token_expires_at'] ?? $resetData['otp_expires_at'] ?? null;

            if (! is_string($token) || ! is_string($expiresAt)) {
                continue;
            }

            DB::table('password_reset')->insert([
                'email' => $legacyChallenge->email,
                'token' => $token,
                'created_at' => $legacyChallenge->created_at ?? now(),
                'expires_at' => $expiresAt,
            ]);
        }

        Schema::drop(self::LEGACY_TABLE);
    }

    public function down(): void
    {
        if (! Schema::hasTable('password_reset')) {
            return;
        }

        if (Schema::hasTable(self::LEGACY_TABLE)) {
            throw new RuntimeException('The temporary password reset table already exists.');
        }

        Schema::rename('password_reset', self::LEGACY_TABLE);

        Schema::create('password_reset', function (Blueprint $table): void {
            $table->string('email')->primary();
            $table->text('resetpass')->nullable();
            $table->timestamps();
        });

        foreach (DB::table(self::LEGACY_TABLE)->orderBy('id')->get() as $challenge) {
            DB::table('password_reset')->insert([
                'email' => $challenge->email,
                'resetpass' => json_encode([
                    'otp_hash' => $challenge->token,
                    'otp_expires_at' => $challenge->expires_at,
                    'attempts' => 0,
                    'verified_at' => null,
                    'reset_token_hash' => null,
                    'reset_token_expires_at' => null,
                ], JSON_THROW_ON_ERROR),
                'created_at' => $challenge->created_at,
                'updated_at' => $challenge->created_at,
            ]);
        }

        Schema::drop(self::LEGACY_TABLE);
    }

    private function createPasswordResetTable(): void
    {
        Schema::create('password_reset', function (Blueprint $table): void {
            $table->id();
            $table->string('email', 255)->index();
            $table->string('token', 255)->unique();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('expires_at')->useCurrent();
        });
    }
};
