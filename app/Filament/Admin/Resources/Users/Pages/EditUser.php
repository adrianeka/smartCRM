<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Mail\RoleAssignedMail;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;

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
        $user = $this->record;
        $user->refresh();
        $currentRoles = $user->roles->pluck('name')->toArray();

        if (empty($this->previousRoles) && ! empty($currentRoles)) {
            $roleName = $currentRoles[0];
            Mail::to($user->email)->send(new RoleAssignedMail($user, $roleName));
        }
    }
}
