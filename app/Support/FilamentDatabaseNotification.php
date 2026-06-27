<?php

namespace App\Support;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class FilamentDatabaseNotification
{
    public static function send(
        User $user,
        string $title,
        string $body,
        string $type = 'info',
        ?string $actionLabel = null,
        ?string $actionUrl = null,
    ): void {
        $notification = Notification::make()
            ->title($title)
            ->body($body);

        match ($type) {
            'success' => $notification->success(),
            'warning' => $notification->warning(),
            'danger' => $notification->danger(),
            default => $notification->info(),
        };

        if ($actionLabel && $actionUrl) {
            $notification->actions([
                Action::make('view')
                    ->label($actionLabel)
                    ->url($actionUrl),
            ]);
        }

        $user->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => \Filament\Notifications\DatabaseNotification::class,
            'data' => $notification->getDatabaseMessage(),
            'read_at' => null,
        ]);

        DatabaseNotificationsSent::dispatch($user);
    }
}
