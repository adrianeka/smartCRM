<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:seed-notifications')]
#[Description('Command description')]
class SeedNotifications extends Command
{
    public function handle()
    {
        $user = \App\Models\User::first();
        if (!$user) {
            $this->error('No users found.');
            return;
        }

        \Filament\Notifications\Notification::make()
            ->title('Saved successfully')
            ->body('Keep going! You\'re doing great')
            ->success()
            ->sendToDatabase($user);

        \Filament\Notifications\Notification::make()
            ->title('You\'re not allowed to edit')
            ->body('You weren\'t supposed to do that, naughty...')
            ->warning()
            ->sendToDatabase($user);

        \Filament\Notifications\Notification::make()
            ->title('Something went wrong')
            ->body('Uh oh! Let\'s try that again')
            ->danger()
            ->sendToDatabase($user);

        \Filament\Notifications\Notification::make()
            ->title('Here\'s some information')
            ->body('Filament is here to help you :)')
            ->info()
            ->sendToDatabase($user);

        $this->info('Notifications seeded!');
    }
}
