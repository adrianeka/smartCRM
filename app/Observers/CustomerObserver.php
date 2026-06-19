<?php

namespace App\Observers;

use App\Models\Customer;
use App\Models\Notification as AppNotification;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;

class CustomerObserver
{
    public function created(Customer $customer): void
    {
        $salesUsers = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['sales', 'Sales']);
        })->get();

        foreach ($salesUsers as $user) {
            $title = 'New Lead Assigned';
            $message = "A new lead \"{$customer->full_name}\" from company \"{$customer->company_name}\" has been registered and is ready for follow-up.";

            AppNotification::create([
                'user_id' => $user->id,
                'title' => $title,
                'message' => $message,
                'type' => 'info',
                'source_module' => 'sales',
                'priority' => 'high',
                'is_read' => false,
                'action_url' => "/admin/customers/{$customer->id}",
            ]);

            FilamentNotification::make()
                ->title($title)
                ->body($message)
                ->info()
                ->actions([
                    Action::make('view')
                        ->label('View Lead')
                        ->url("/admin/customers/{$customer->id}"),
                ])
                ->sendToDatabase($user);
        }
    }

    public function updated(Customer $customer): void
    {
        if ($customer->isDirty('status') && $customer->status === 'Customer') {
            $managers = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['Manager/Analyst', 'manager']);
            })->get();

            foreach ($managers as $user) {
                $title = 'Deal Closed Successfully!';
                $message = "Opportunity \"{$customer->full_name}\" has been successfully closed won.";

                AppNotification::create([
                    'user_id' => $user->id,
                    'title' => $title,
                    'message' => $message,
                    'type' => 'success',
                    'source_module' => 'sales',
                    'priority' => 'high',
                    'is_read' => false,
                    'action_url' => "/admin/customers/{$customer->id}",
                ]);

                FilamentNotification::make()
                    ->title($title)
                    ->body($message)
                    ->success()
                    ->actions([
                        Action::make('view')
                            ->label('View Customer')
                            ->url("/admin/customers/{$customer->id}"),
                    ])
                    ->sendToDatabase($user);
            }
        }
    }
}
