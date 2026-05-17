<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:seed-notifications {role? : The role to send notifications to (e.g. sales, support)}')]
#[Description('Seed dummy notifications for specific roles or all users')]
class SeedNotifications extends Command
{
    public function handle()
    {
        $role = $this->argument('role');

        $users = $role 
            ? \App\Models\User::where('role', $role)->get() 
            : \App\Models\User::all();

        if ($users->isEmpty()) {
            $this->error("No users found for role: {$role}");
            return;
        }

        foreach ($users as $user) {
            if ($user->role === 'sales') {
                \Filament\Notifications\Notification::make()
                    ->title('New Deal Closed!')
                    ->body('PT Jaya Abadi just signed the contract worth $15,000.')
                    ->success()
                    ->sendToDatabase($user);
            } 
            elseif ($user->role === 'support') {
                \Filament\Notifications\Notification::make()
                    ->title('Urgent Ticket SLA')
                    ->body('Ticket T-1042 has only 15 mins left before SLA breach!')
                    ->danger()
                    ->sendToDatabase($user);
            }
            elseif ($user->role === 'marketing') {
                \Filament\Notifications\Notification::make()
                    ->title('Campaign Finished')
                    ->body('The Q3 Email Blast campaign has finished sending.')
                    ->info()
                    ->sendToDatabase($user);
            }
            elseif ($user->role === 'manager' || $user->role === 'admin') {
                \Filament\Notifications\Notification::make()
                    ->title('Weekly Report Ready')
                    ->body('Your weekly company performance report is ready to download.')
                    ->info()
                    ->sendToDatabase($user);
            }
            
            \Filament\Notifications\Notification::make()
                ->title('System Update')
                ->body('SmartCRM79 will undergo maintenance tonight at 12:00 AM.')
                ->warning()
                ->sendToDatabase($user);
        }

        $this->info("Successfully sent notifications to " . $users->count() . " user(s).");
    }
}
