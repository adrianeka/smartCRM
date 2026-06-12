<?php

namespace App\Observers;

use App\Models\Notification as AppNotification;
use App\Models\User;
use App\Models\WebhookLog;
use Filament\Notifications\Notification as FilamentNotification;

class WebhookLogObserver
{
    public function created(WebhookLog $log): void
    {
        if ($log->status === 'failed') {
            $adminUsers = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['super_admin', 'admin', 'Admin']);
            })->get();

            foreach ($adminUsers as $user) {
                $title = 'Webhook Delivery Failed';
                $message = "Webhook event \"{$log->event_type}\" to endpoint \"{$log->target_url}\" failed with status code {$log->status_code}.";

                AppNotification::create([
                    'user_id' => $user->id,
                    'title' => $title,
                    'message' => $message,
                    'type' => 'danger',
                    'source_module' => 'integration',
                    'priority' => 'high',
                    'is_read' => false,
                    'action_url' => '/admin/webhook-logs',
                ]);

                FilamentNotification::make()
                    ->title($title)
                    ->body($message)
                    ->danger()
                    ->sendToDatabase($user);
            }
        }
    }
}
