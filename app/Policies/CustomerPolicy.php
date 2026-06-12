<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->hasAnyRole(['super_admin', 'Sales', 'Marketing', 'Support', 'Manager/Analyst']);
    }

    public function view(AuthUser $authUser, Customer $customer): bool
    {
        return $authUser->hasAnyRole(['super_admin', 'Sales', 'Marketing', 'Support', 'Manager/Analyst']);
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->hasAnyRole(['super_admin', 'Sales', 'Marketing']);
    }

    public function update(AuthUser $authUser, Customer $customer): bool
    {
        return $authUser->hasAnyRole(['super_admin', 'Sales', 'Marketing']);
    }

    public function delete(AuthUser $authUser, Customer $customer): bool
    {
        return $authUser->hasAnyRole(['super_admin', 'Sales']);
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->hasAnyRole(['super_admin']);
    }

    public function restore(AuthUser $authUser, Customer $customer): bool
    {
        return $authUser->hasRole('super_admin');
    }

    public function forceDelete(AuthUser $authUser, Customer $customer): bool
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

    public function replicate(AuthUser $authUser, Customer $customer): bool
    {
        return $authUser->hasAnyRole(['super_admin', 'Sales']);
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->hasRole('super_admin');
    }

}
