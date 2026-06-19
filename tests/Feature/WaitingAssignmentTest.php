<?php

use App\Filament\Admin\Resources\Users\Pages\EditUser;
use App\Mail\RoleAssignedMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('redirects unauthenticated user to login when accessing waiting assignment page', function () {
    $response = $this->get('/waiting-assignment');
    $response->assertRedirect('/admin/login');
});

it('redirects role-assigned user from waiting assignment page to dashboard', function () {
    $user = User::factory()->create();
    $role = Role::create(['name' => 'Sales']);
    $user->assignRole($role);

    $response = $this->actingAs($user)
        ->withSession(['mfa_verified' => true])
        ->get('/waiting-assignment');

    $response->assertRedirect(route('filament.admin.pages.dashboard'));
});

it('renders waiting assignment page for authenticated users without a role', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->withSession(['mfa_verified' => true])
        ->get('/waiting-assignment');

    $response->assertStatus(200);
    $response->assertSee('Pendaftaran Berhasil');
    $response->assertSee('Menunggu Persetujuan Admin');
    $response->assertSee('Keluar / Logout');
});

it('redirects authenticated users without a role to waiting assignment page when accessing admin routes', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->withSession(['mfa_verified' => true])
        ->get('/admin');

    $response->assertRedirect('/waiting-assignment');
});

it('sends email when role is assigned via EditUser page', function () {
    Mail::fake();

    $adminRole = Role::create(['name' => 'super_admin']);
    $admin = User::factory()->create();
    $admin->assignRole($adminRole);

    $role = Role::create(['name' => 'Sales']);
    $user = User::factory()->create(); // Has no roles

    // Acting as admin, test user role update via EditUser component
    Livewire::actingAs($admin)
        ->test(EditUser::class, ['record' => $user->getKey()])
        ->fillForm([
            'roles' => [$role->id],
        ])
        ->call('save')
        ->assertHasNoErrors();

    Mail::assertSent(RoleAssignedMail::class, function ($mail) use ($user, $role) {
        return $mail->hasTo($user->email) && $mail->roleName === $role->name;
    });
});
