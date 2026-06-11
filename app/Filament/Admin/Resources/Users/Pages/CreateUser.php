<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Mail\RoleAssignedMail;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        $user = $this->record;
        $user->refresh();
        $currentRoles = $user->roles->pluck('name')->toArray();

        if (! empty($currentRoles)) {
            $roleName = $currentRoles[0];
            Mail::to($user->email)->send(new RoleAssignedMail($user, $roleName));
        }
    }
}
