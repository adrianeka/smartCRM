<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Mail\RoleAssignedMail;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected array $previousRoles = [];

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        $this->previousRoles = $this->record->roles->pluck('name')->toArray();
    }

    protected function afterSave(): void
    {
        /** @var User $user */
        $user = $this->record;

        // Filament calls afterSave() BEFORE it syncs BelongsToMany relationships like roles.
        // So we must get the new roles from the form state directly.
        $currentRoleIds = $this->data['roles'] ?? [];

        if (empty($this->previousRoles) && ! empty($currentRoleIds)) {
            $roleId = $currentRoleIds[0];
            $roleName = Role::findById($roleId)->name;
            Mail::to($user->email)->send(new RoleAssignedMail($user, $roleName));
        }
    }
}
