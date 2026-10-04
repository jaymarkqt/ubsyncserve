<?php

use App\Mail\PasswordResetCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

test('it sends a six digit code to a registered email without exposing account existence', function () {
    $user = User::factory()->create(['email' => 'person@example.com']);
    Mail::fake();

    $response = $this->postJson(route('password-reset.send-code'), [
        'email' => $user->email,
    ]);

    $response->assertSuccessful()
        ->assertJsonPath('message', 'If an account exists for that email, a 6-digit verification code has been sent.');

    $code = null;
    Mail::assertSent(PasswordResetCode::class, function (PasswordResetCode $mail) use ($user, &$code): bool {
        $code = $mail->code;

        return $mail->hasTo($user->email)
            && $mail->recipientName === $user->name
            && preg_match('/^\d{6}$/', $mail->code) === 1;
    });

    $challenge = DB::table('password_resets')->where('email', $user->email)->first();

    expect($challenge)->not->toBeNull();

    expect(Schema::getColumnListing('password_resets'))->toBe([
        'id',
        'email',
        'token',
        'created_at',
        'expires_at',
        'is_used',
    ])
        ->and(Hash::check($code, $challenge->token))->toBeTrue()
        ->and($challenge->is_used)->toBe(0)
        ->and(Carbon::parse($challenge->expires_at)->diffInSeconds(Carbon::parse($challenge->created_at)))->toEqual(60);
});

test('it verifies the emailed code and resets the password once', function () {
    $user = User::factory()->create(['email' => 'person@example.com']);
    Mail::fake();

    $this->postJson(route('password-reset.send-code'), ['email' => $user->email])->assertSuccessful();

    $code = null;
    Mail::assertSent(PasswordResetCode::class, function (PasswordResetCode $mail) use (&$code): bool {
        $code = $mail->code;

        return true;
    });

    $verification = $this->postJson(route('password-reset.verify-code'), [
        'email' => $user->email,
        'code' => $code,
    ])->assertSuccessful();

    $resetToken = $verification->json('reset_token');

    $this->postJson(route('password-reset.verify-code'), [
        'email' => $user->email,
        'code' => $code,
    ])->assertUnprocessable();

    $this->postJson(route('password-reset.reset'), [
        'email' => $user->email,
        'reset_token' => $resetToken,
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ])->assertSuccessful();

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue()
        ->and(DB::table('password_resets')->where('email', $user->email)->value('is_used'))->toBe(1);

    $this->postJson(route('password-reset.reset'), [
        'email' => $user->email,
        'reset_token' => $resetToken,
        'password' => 'another-password',
        'password_confirmation' => 'another-password',
    ])->assertUnprocessable();

});

test('it rejects an OTP after one minute', function () {
    $user = User::factory()->create(['email' => 'person@example.com']);
    Mail::fake();

    $this->postJson(route('password-reset.send-code'), ['email' => $user->email])->assertSuccessful();

    $code = null;
    Mail::assertSent(PasswordResetCode::class, function (PasswordResetCode $mail) use (&$code): bool {
        $code = $mail->code;

        return true;
    });

    $this->travel(61)->seconds();

    $this->postJson(route('password-reset.verify-code'), [
        'email' => $user->email,
        'code' => $code,
    ])->assertUnprocessable();
});

test('it does not reset a password without a verified and valid reset token', function () {
    $user = User::factory()->create(['email' => 'person@example.com']);

    $this->postJson(route('password-reset.reset'), [
        'email' => $user->email,
        'reset_token' => str_repeat('a', 64),
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ])->assertUnprocessable();

    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
});

test('it returns the same send-code response for an unknown email without sending mail', function () {
    Mail::fake();

    $this->postJson(route('password-reset.send-code'), [
        'email' => 'unknown@example.com',
    ])->assertSuccessful()
        ->assertJsonPath('message', 'If an account exists for that email, a 6-digit verification code has been sent.');

    Mail::assertNothingSent();
});
