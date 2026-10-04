<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendPasswordResetCodeRequest;
use App\Http\Requests\UpdatePasswordFromResetRequest;
use App\Http\Requests\VerifyPasswordResetCodeRequest;
use App\Mail\PasswordResetCode;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function sendCode(SendPasswordResetCodeRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->first();

        if ($user) {
            $code = (string) random_int(100000, 999999);
            $createdAt = now();

            DB::table('password_resets')
                ->where('email', $user->email)
                ->where('is_used', false)
                ->update(['is_used' => true]);

            $challengeId = DB::table('password_resets')->insertGetId([
                'email' => $user->email,
                'token' => Hash::make($code),
                'created_at' => $createdAt,
                'expires_at' => $createdAt->copy()->addMinute(),
                'is_used' => false,
            ]);

            Cache::forget($this->attemptCacheKey($challengeId));

            Mail::to($user->email)->send(new PasswordResetCode($code, $user->name));
        }

        return response()->json([
            'message' => 'If an account exists for that email, a 6-digit verification code has been sent.',
        ]);
    }

    public function verifyCode(VerifyPasswordResetCodeRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $result = DB::transaction(function () use ($validated): array {
            $challenge = DB::table('password_resets')
                ->where('email', $validated['email'])
                ->where('is_used', false)
                ->where('expires_at', '>', now())
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            if (! $challenge) {
                return ['status' => 'invalid'];
            }

            $attemptCacheKey = $this->attemptCacheKey($challenge->id);
            $attempts = (int) Cache::get($attemptCacheKey, 0);

            if ($attempts >= 5) {
                return ['status' => 'locked'];
            }

            if (! Hash::check($validated['code'], $challenge->token)) {
                Cache::put($attemptCacheKey, $attempts + 1, Carbon::parse($challenge->expires_at));

                return ['status' => 'invalid'];
            }

            $resetToken = Str::random(64);
            DB::table('password_resets')
                ->where('id', $challenge->id)
                ->update([
                    'token' => Hash::make($resetToken),
                    'expires_at' => now()->addMinutes(10),
                ]);
            Cache::forget($attemptCacheKey);

            return [
                'status' => 'verified',
                'challenge_id' => $challenge->id,
                'reset_token' => $resetToken,
            ];
        });

        if ($result['status'] === 'locked') {
            return response()->json([
                'message' => 'Too many incorrect codes. Request a new verification code to continue.',
            ], 422);
        }

        if ($result['status'] !== 'verified') {
            return $this->invalidCodeResponse();
        }

        return response()->json([
            'reset_token' => $result['reset_token'],
            'message' => 'Verification successful. You may now choose a new password.',
        ]);
    }

    public function resetPassword(UpdatePasswordFromResetRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $result = DB::transaction(function () use ($validated): ?int {
            $challenge = DB::table('password_resets')
                ->where('email', $validated['email'])
                ->where('is_used', false)
                ->where('expires_at', '>', now())
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            if (! $challenge || ! Hash::check($validated['reset_token'], $challenge->token)) {
                return null;
            }

            $user = User::where('email', $validated['email'])->first();

            if (! $user) {
                return null;
            }

            $user->password = $validated['password'];
            $user->remember_token = Str::random(60);
            $user->save();

            DB::table('password_resets')
                ->where('id', $challenge->id)
                ->update(['is_used' => true]);

            return $challenge->id;
        });

        if (! $result) {
            return $this->invalidResetResponse();
        }

        Cache::forget($this->attemptCacheKey($result));

        return response()->json([
            'message' => 'Your password has been reset successfully. You can now log in.',
        ]);
    }

    private function attemptCacheKey(int $challengeId): string
    {
        return 'password-reset-attempts:'.$challengeId;
    }

    private function invalidCodeResponse(): JsonResponse
    {
        return response()->json([
            'message' => 'The verification code is invalid or has expired.',
        ], 422);
    }

    private function invalidResetResponse(): JsonResponse
    {
        return response()->json([
            'message' => 'Your password reset session is invalid or has expired. Please request a new code.',
        ], 422);
    }
}
