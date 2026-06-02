<?php

namespace App\Filament\Admin\Pages;

use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                FileUpload::make('avatar_url')
                    ->label('Foto Profil (Avatar)')
                    ->image()
                    ->avatar()
                    ->directory('avatars')
                    ->maxSize(2048)
                    ->nullable(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function getCancelFormAction(): \Filament\Actions\Action
    {
        return parent::getCancelFormAction()
            ->label('Kembali')
            ->icon('heroicon-m-arrow-left')
            ->color('gray');
    }
}
