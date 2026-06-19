<?php

namespace App\Filament\Admin\Pages;

use Filament\Actions\Action;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;

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
            ]);
    }

    public function getFormContentComponent(): Component
    {
        return Form::make([EmbeddedSchema::make('form')])
            ->id('form')
            ->livewireSubmitHandler('save');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFormContentComponent(),
                ...Arr::wrap($this->getMultiFactorAuthenticationContentComponent()),
                $this->getSessionManagementComponent(),
                Actions::make($this->getFormActions())
                    ->alignment($this->getFormActionsAlignment())
                    ->fullWidth($this->hasFullWidthFormActions())
                    ->sticky((! static::isSimple()) && $this->areFormActionsSticky())
                    ->key('form-actions'),
            ]);
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->extraAttributes(['form' => 'form']);
    }

    protected function getSessionManagementComponent(): Component
    {
        return Section::make('Manajemen Sesi')
            ->description('Kelola sesi aktif dan perangkat yang terhubung ke akun Anda.')
            ->icon('heroicon-o-computer-desktop')
            ->schema([
                ViewField::make('active_sessions')
                    ->hiddenLabel()
                    ->view('filament.components.active-sessions'),
            ])
            ->collapsible();
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Kembali')
            ->icon('heroicon-m-arrow-left')
            ->color('gray');
    }

    public function logoutOtherDevicesAction(): Action
    {
        return Action::make('logoutOtherDevices')
            ->label('Keluarkan Perangkat Lain')
            ->color('danger')
            ->icon('heroicon-o-arrow-right-on-rectangle')
            ->requiresConfirmation()
            ->modalHeading('Konfirmasi Keluarkan Perangkat')
            ->modalDescription('Semua sesi aktif di perangkat lain akan dikeluarkan. Masukkan kata sandi Anda untuk melanjutkan.')
            ->form([
                \Filament\Forms\Components\TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password()
                    ->required()
                    ->currentPassword()
            ])
            ->action(function (array $data) {
                $user = auth()->user();
                \Illuminate\Support\Facades\Auth::logoutOtherDevices($data['password']);

                $passwordHash = $user->getAuthPassword();
                $guard = \Illuminate\Support\Facades\Auth::guard('web');
                if (method_exists($guard, 'hashPasswordForCookie')) {
                    $passwordHash = $guard->hashPasswordForCookie($passwordHash);
                }

                request()->session()->put([
                    'password_hash_web' => $passwordHash,
                ]);

                \Illuminate\Support\Facades\DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->where('id', '!=', session()->getId())
                    ->delete();

                \Filament\Notifications\Notification::make()
                    ->title('Berhasil mengeluarkan semua perangkat lain.')
                    ->success()
                    ->send();
            });
    }
}
