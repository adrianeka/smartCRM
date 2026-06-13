<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->hasRole('super_admin');
    }

    public function view(AuthUser $authUser, Role $role): bool
    {
        return $authUser->hasRole('super_admin');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->hasRole('super_admin');
    }

    public function update(AuthUser $authUser, Role $role): bool
    {
        return $authUser->hasRole('super_admin');
    }

    public function delete(AuthUser $authUser, Role $role): bool
    {
        return $authUser->hasRole('super_admin');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->hasRole('super_admin');
    }

    public function restore(AuthUser $authUser, Role $role): bool
    {
        return $authUser->hasRole('super_admin');
    }

    public function forceDelete(AuthUser $authUser, Role $role): bool
    {
        return $authUser->hasRole('super_admin');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->hasRole('super_admin');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->hasRole('super_admin');
    }

    public function replicate(AuthUser $authUser, Role $role): bool
    {
        return $authUser->hasRole('super_admin');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->hasRole('super_admin');
    }
}
