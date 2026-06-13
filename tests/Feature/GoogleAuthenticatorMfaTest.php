<?php

use App\Models\User;
use App\Services\Google2FAService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders setup screen with QR code for users who have not set up Google Auth', function () {
    $user = User::factory()->create(['google2fa_secret' => null]);

    $response = $this->actingAs($user)
        ->withSession(['mfa_verified' => false])
        ->get('/admin/mfa-challenge');

    $response->assertStatus(200);
    $response->assertSee('Setup Keamanan Akun');
    $response->assertSee('Pindai QR Code di bawah');
    $response->assertSee('api.qrserver.com');
});

it('fails to verify setup code if code is invalid', function () {
    $user = User::factory()->create(['google2fa_secret' => null]);
    $tempSecret = 'ABCDEFGHIJKLMNOP';

    $response = $this->actingAs($user)
        ->withSession([
            'mfa_verified' => false,
            'temp_google2fa_secret' => $tempSecret,
        ])
        ->post('/mfa/challenge/verify', [
            'code' => '000000',
        ]);

    $response->assertSessionHasErrors('code');
    $user->refresh();
    expect($user->google2fa_secret)->toBeNull();
});

it('successfully verifies setup code and saves secret to database on first login', function () {
    $user = User::factory()->create(['google2fa_secret' => null]);
    $tempSecret = 'ABCDEFGHIJKLMNOP';
    $validCode = Google2FAService::getCode($tempSecret, null);

    $response = $this->actingAs($user)
        ->withSession([
            'mfa_verified' => false,
            'temp_google2fa_secret' => $tempSecret,
        ])
        ->post('/mfa/challenge/verify', [
            'code' => $validCode,
        ]);

    $response->assertRedirect(route('filament.admin.pages.dashboard'));
    $user->refresh();
    expect($user->google2fa_secret)->toBe($tempSecret);
    expect(session('mfa_verified'))->toBeTrue();
});

it('renders verification screen (no QR code) on subsequent logins', function () {
    $user = User::factory()->create(['google2fa_secret' => 'ABCDEFGHIJKLMNOP']);

    $response = $this->actingAs($user)
        ->withSession(['mfa_verified' => false])
        ->get('/admin/mfa-challenge');

    $response->assertStatus(200);
    $response->assertSee('Verifikasi Dua Faktor');
    $response->assertSee('Masukkan 6 digit kode dari aplikasi Google Authenticator');
    $response->assertDontSee('Pindai QR Code di bawah');
    $response->assertDontSee('api.qrserver.com');
});

it('successfully verifies code and logs in on subsequent logins', function () {
    $secret = 'ABCDEFGHIJKLMNOP';
    $user = User::factory()->create(['google2fa_secret' => $secret]);
    $validCode = Google2FAService::getCode($secret, null);

    $response = $this->actingAs($user)
        ->withSession(['mfa_verified' => false])
        ->post('/mfa/challenge/verify', [
            'code' => $validCode,
        ]);

    $response->assertRedirect(route('filament.admin.pages.dashboard'));
    expect(session('mfa_verified'))->toBeTrue();
});
