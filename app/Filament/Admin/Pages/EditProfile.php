<?php

namespace App\Filament\Admin\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Illuminate\Support\HtmlString;

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
                    ->disk('public')
                    ->directory('avatars')
                    ->maxSize(2048)
                    ->nullable(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),

                Section::make('Manajemen Sesi')
                    ->description('Kelola sesi aktif dan perangkat yang terhubung ke akun Anda.')
                    ->icon('heroicon-o-computer-desktop')
                    ->schema([
                        \Filament\Forms\Components\ViewField::make('active_sessions')
                            ->hiddenLabel()
                            ->view('filament.components.active-sessions'),
                    ])
                    ->collapsible(),
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
