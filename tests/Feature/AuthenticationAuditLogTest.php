<?php

use App\Models\User;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;

uses(RefreshDatabase::class);

it('logs successful login', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    event(new Login('web', $user, false));

    $this->assertDatabaseHas('activity_log', [
        'log_name' => 'authentication',
        'event' => 'login',
        'subject_type' => User::class,
        'subject_id' => $user->id,
        'causer_type' => User::class,
        'causer_id' => $user->id,
    ]);

    $log = Activity::where('event', 'login')->first();
    expect($log->description)->toContain('logged in successfully');
});

it('logs successful logout', function () {
    $user = User::factory()->create();

    event(new Logout('web', $user));

    $this->assertDatabaseHas('activity_log', [
        'log_name' => 'authentication',
        'event' => 'logout',
        'subject_type' => User::class,
        'subject_id' => $user->id,
        'causer_type' => User::class,
        'causer_id' => $user->id,
    ]);

    $log = Activity::where('event', 'logout')->first();
    expect($log->description)->toContain('logged out');
});

it('logs failed login attempt for existing user', function () {
    $user = User::factory()->create(['email' => 'test@example.com']);

    event(new Failed('web', $user, ['email' => 'test@example.com', 'password' => 'wrong']));

    $this->assertDatabaseHas('activity_log', [
        'log_name' => 'authentication',
        'event' => 'failed',
        'subject_type' => User::class,
        'subject_id' => $user->id,
    ]);

    $log = Activity::where('event', 'failed')->first();
    expect($log->description)->toContain('wrong password');
});

it('logs failed login attempt for unknown email', function () {
    event(new Failed('web', null, ['email' => 'unknown@example.com', 'password' => 'wrong']));

    $this->assertDatabaseHas('activity_log', [
        'log_name' => 'authentication',
        'event' => 'failed',
    ]);

    $log = Activity::where('event', 'failed')->first();
    expect($log->description)->toContain('unknown email: unknown@example.com');
});
