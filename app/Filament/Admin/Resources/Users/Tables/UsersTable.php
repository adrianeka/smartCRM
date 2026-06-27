<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar_url')
                    ->label('Avatar')
                    ->circular()
                    ->disk('public')
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&color=7F9CF5&background=EBF4FF'),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Peran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'Manager/Analyst' => 'warning',
                        'Sales' => 'success',
                        'Marketing' => 'info',
                        'Support' => 'gray',
                        default => 'primary',
                    })
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->label('Verified')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->placeholder('Belum Verifikasi'),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->label('Filter Peran')
                    ->preload()
                    ->multiple(),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('forceLogout')
                    ->label('Paksa Logout')
                    ->icon('heroicon-o-arrow-right-start-on-rectangle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Paksa Logout Users')
                    ->modalDescription(fn ($record) => "Semua sesi aktif milik \"{$record->name}\" akan dikeluarkan. Users harus login ulang untuk mengakses sistem.")
                    ->modalSubmitActionLabel('Ya, Paksa Logout')
                    ->action(function ($record) {
                        $deleted = DB::table('sessions')
                            ->where('user_id', $record->id)
                            ->delete();

                        Notification::make()
                            ->title('Success')
                            ->body("All sessions for \"{$record->name}\" have been signed out ({$deleted} sessions).")
                            ->success()
                            ->send();
                    })
                    ->visible(fn () => auth()->user()?->hasRole('super_admin'))
                    ->hidden(fn ($record) => $record->id === auth()->id()),
                Action::make('verifyEmail')
                    ->label('Verify Email')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Verify User Email')
                    ->modalDescription(fn ($record) => "Are you sure you want to verify the email for \"{$record->name}\"?")
                    ->modalSubmitActionLabel('Yes, Verify')
                    ->action(function ($record) {
                        $record->update([
                            'email_verified_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Success')
                            ->body("Email for \"{$record->name}\" has been verified.")
                            ->success()
                            ->send();
                    })
                    ->visible(fn ($record) => empty($record->email_verified_at)),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
