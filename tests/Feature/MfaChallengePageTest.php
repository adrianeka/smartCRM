<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects to login when accessing MFA challenge page unauthenticated', function () {
    $response = $this->get('/admin/mfa-challenge');
    $response->assertRedirect('/admin/login');
});

it('renders the MFA challenge page in setup mode for users without secret', function () {
    $user = User::factory()->create(['google2fa_secret' => null]);

    $response = $this->actingAs($user)
        ->withSession(['mfa_verified' => false])
        ->get('/admin/mfa-challenge');

    $response->assertStatus(200);
    $response->assertSee('Setup Keamanan Akun');
    $response->assertDontSee('Kirim Ulang Kode');
});
