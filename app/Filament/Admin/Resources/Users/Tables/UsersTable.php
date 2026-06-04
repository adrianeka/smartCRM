<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
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
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=7F9CF5&background=EBF4FF'),
                TextColumn::make('name')
                    ->label('Nama')
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
                    ->label('Terverifikasi')
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
                    ->modalHeading('Paksa Logout Pengguna')
                    ->modalDescription(fn ($record) => "Semua sesi aktif milik \"{$record->name}\" akan dikeluarkan. Pengguna harus login ulang untuk mengakses sistem.")
                    ->modalSubmitActionLabel('Ya, Paksa Logout')
                    ->action(function ($record) {
                        $deleted = DB::table('sessions')
                            ->where('user_id', $record->id)
                            ->delete();

                        Notification::make()
                            ->title('Berhasil')
                            ->body("Semua sesi milik \"{$record->name}\" telah dikeluarkan ({$deleted} sesi).")
                            ->success()
                            ->send();
                    })
                    ->visible(fn () => auth()->user()?->hasRole('super_admin'))
                    ->hidden(fn ($record) => $record->id === auth()->id()),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
