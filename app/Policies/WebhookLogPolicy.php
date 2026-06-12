<?php

<<<<<<< HEAD
namespace App\Policies;

use App\Models\User;
=======
declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
>>>>>>> 92117236d2a356558d4a3ba2b67bdfc2a5dc0e2e
use App\Models\WebhookLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class WebhookLogPolicy
{
    use HandlesAuthorization;
<<<<<<< HEAD

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WebhookLog $webhookLog): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WebhookLog $webhookLog): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WebhookLog $webhookLog): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WebhookLog $webhookLog): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WebhookLog $webhookLog): bool
    {
        return $user->hasRole('super_admin');
    }
}
=======
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:WebhookLog');
    }

    public function view(AuthUser $authUser, WebhookLog $webhookLog): bool
    {
        return $authUser->can('View:WebhookLog');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:WebhookLog');
    }

    public function update(AuthUser $authUser, WebhookLog $webhookLog): bool
    {
        return $authUser->can('Update:WebhookLog');
    }

    public function delete(AuthUser $authUser, WebhookLog $webhookLog): bool
    {
        return $authUser->can('Delete:WebhookLog');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:WebhookLog');
    }

    public function restore(AuthUser $authUser, WebhookLog $webhookLog): bool
    {
        return $authUser->can('Restore:WebhookLog');
    }

    public function forceDelete(AuthUser $authUser, WebhookLog $webhookLog): bool
    {
        return $authUser->can('ForceDelete:WebhookLog');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:WebhookLog');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:WebhookLog');
    }

    public function replicate(AuthUser $authUser, WebhookLog $webhookLog): bool
    {
        return $authUser->can('Replicate:WebhookLog');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:WebhookLog');
    }

}
>>>>>>> 92117236d2a356558d4a3ba2b67bdfc2a5dc0e2e
