<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the registration page and contains the password fields', function () {
    $response = $this->get('/admin/register');
    $response->assertStatus(200);
    $html = $response->getContent();
    
    expect($html)->toContain('id="form.password"');
    expect($html)->toContain('id="form.passwordConfirmation"');
    expect($html)->toContain('Kriteria Kata Sandi');
});
