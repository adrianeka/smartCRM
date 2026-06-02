<?php

use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the reset password page and contains the password criteria box', function () {
    $url = URL::temporarySignedRoute(
        'filament.admin.auth.password-reset.reset',
        now()->addMinutes(60),
        [
            'email' => 'cibayo@gmail.com',
            'token' => 'test-token',
        ]
    );

    $response = $this->get($url);
    $response->assertStatus(200);
    $html = $response->getContent();
    
    expect($html)->toContain('id="form.password"');
    expect($html)->toContain('id="form.passwordConfirmation"');
    expect($html)->toContain('Kriteria Kata Sandi');
});
