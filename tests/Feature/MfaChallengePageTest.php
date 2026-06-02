<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects to login when accessing MFA challenge page unauthenticated', function () {
    $response = $this->get('/admin/mfa-challenge');
    $response->assertRedirect('/admin/login');
});

it('renders the MFA challenge page for authenticated users with unverified MFA status', function () {
    $user = User::factory()->create();
    
    // Log the user in
    $response = $this->actingAs($user)
        ->withSession(['mfa_verified' => false])
        ->get('/admin/mfa-challenge');

    $response->assertStatus(200);
    $response->assertSee('Verifikasi Dua Faktor');
    $response->assertSee('Kirim Ulang Kode');
});

it('successfully verifies OTP and redirects to the Filament dashboard', function () {
    $user = User::factory()->create();
    $code = '123456';
    
    \App\Models\MfaCode::create([
        'user_id' => $user->id,
        'code' => $code,
        'expires_at' => now()->addMinutes(5),
    ]);

    $response = $this->actingAs($user)
        ->withSession(['mfa_verified' => false])
        ->post('/mfa/challenge/verify', [
            'code' => $code,
        ]);

    $response->assertRedirect(route('filament.admin.pages.dashboard'));
    expect(session('mfa_verified'))->toBeTrue();
});
