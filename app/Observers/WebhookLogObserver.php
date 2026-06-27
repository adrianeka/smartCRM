<?php

namespace App\Observers;

use App\Models\Notification as AppNotification;
use App\Models\User;
use App\Models\WebhookLog;
use App\Support\FilamentDatabaseNotification;

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

                FilamentDatabaseNotification::send(
                    user: $user,
                    title: $title,
                    body: $message,
                    type: 'danger',
                    actionUrl: '/admin/webhook-logs',
                );
            }
        }
    }
}
