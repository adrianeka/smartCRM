<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Spatie\Activitylog\Models\Activity;

class ActivityPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->hasAnyRole(['super_admin', 'Manager/Analyst']);
    }

    public function view(AuthUser $authUser, Activity $activity): bool
    {
        return $authUser->hasAnyRole(['super_admin', 'Manager/Analyst']);
    }

    public function create(AuthUser $authUser): bool
    {
        return false;
    }

    public function update(AuthUser $authUser, Activity $activity): bool
    {
        return false;
    }

    public function delete(AuthUser $authUser, Activity $activity): bool
    {
        return $authUser->hasRole('super_admin');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->hasRole('super_admin');
    }

    public function restore(AuthUser $authUser, Activity $activity): bool
    {
        return $authUser->can('Restore:Activity');
    }

    public function forceDelete(AuthUser $authUser, Activity $activity): bool
    {
        return $authUser->can('ForceDelete:Activity');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Activity');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Activity');
    }

    public function replicate(AuthUser $authUser, Activity $activity): bool
    {
        return $authUser->can('Replicate:Activity');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Activity');
    }
}
