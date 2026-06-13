<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WebhookLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class WebhookLogPolicy
{
    use HandlesAuthorization;

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
        return $user->hasRole('super_admin') || $user->can('ForceDelete:WebhookLog');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('ForceDeleteAny:WebhookLog');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('RestoreAny:WebhookLog');
    }

    public function replicate(User $user, WebhookLog $webhookLog): bool
    {
        return $user->can('Replicate:WebhookLog');
    }

    public function reorder(User $user): bool
    {
        return $user->can('Reorder:WebhookLog');
    }
}
