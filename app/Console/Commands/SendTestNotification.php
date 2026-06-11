<?php

namespace App\Console\Commands;

use App\Models\Notification as AppNotification;
use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Console\Command;

class SendTestNotification extends Command
{
    protected $signature = 'smartcrm:send-notification';

    protected $description = 'Simulate highly realistic CRM notifications for demo purposes';

    private function getDemoNotifications(): array
    {
        return [
            'sales' => [
                [
                    'title' => 'New Lead Assigned',
                    'message' => 'New Lead "PT Sinar Abadi Tbk" has registered an inquiry. Source: Web Contact Form.',
                    'type' => 'info',
                    'source_module' => 'sales',
                    'priority' => 'high',
                    'action_url' => '/admin/customers',
                ],
                [
                    'title' => 'Opportunity Closed Won',
                    'message' => 'Deal with TechCorp Solutions closed successfully at $45,000 value.',
                    'type' => 'success',
                    'source_module' => 'sales',
                    'priority' => 'high',
                    'action_url' => '/admin/customers',
                ],
            ],
            'support' => [
                [
                    'title' => 'Ticket Assigned',
                    'message' => 'High priority ticket #TCK-4819 "Payment Gateway Timeout" assigned to your queue.',
                    'type' => 'warning',
                    'source_module' => 'support',
                    'priority' => 'high',
                    'action_url' => '#',
                ],
                [
                    'title' => 'SLA Warning: 15 Mins Left',
                    'message' => 'Ticket #TCK-3920 from PT Astra International has 15 minutes left before breach.',
                    'type' => 'danger',
                    'source_module' => 'support',
                    'priority' => 'high',
                    'action_url' => '#',
                ],
            ],
            'marketing' => [
                [
                    'title' => 'Campaign Completed',
                    'message' => 'Email campaign "Summer Promo 2026" sent to 12,500 leads. Click rate: 4.8%.',
                    'type' => 'success',
                    'source_module' => 'marketing',
                    'priority' => 'normal',
                    'action_url' => '#',
                ],
            ],
            'manager' => [
                [
                    'title' => 'Q2 Target Achieved',
                    'message' => 'Overall business revenue target achieved ($180,000 threshold reached).',
                    'type' => 'success',
                    'source_module' => 'analytics',
                    'priority' => 'high',
                    'action_url' => '#',
                ],
            ],
            'super_admin' => [
                [
                    'title' => 'API Rate Limit Alert',
                    'message' => 'Client API Key "Sales_API_Token" has consumed 95% of its hourly rate limit.',
                    'type' => 'warning',
                    'source_module' => 'integration',
                    'priority' => 'high',
                    'action_url' => '#',
                ],
                [
                    'title' => 'Webhook Delivery Failed',
                    'message' => 'Webhook endpoint "https://api.thirdparty.com/webhook" returned status 500.',
                    'type' => 'danger',
                    'source_module' => 'integration',
                    'priority' => 'high',
                    'action_url' => '#',
                ],
            ],
        ];
    }

    public function handle()
    {
        $this->info('=== SmartCRM79 Demo Notification Simulator ===');

        $choices = [
            'Send to All Users (Demo Setup)',
            'Send to Sales Role',
            'Send to Support Role',
            'Send to Marketing Role',
            'Send to Manager Role',
            'Send to Super Admin Role',
        ];

        $choice = $this->choice('Select target for demo notifications:', $choices, 0);

        $notifications = $this->getDemoNotifications();

        if ($choice === 'Send to All Users (Demo Setup)') {
            foreach ($notifications as $roleKey => $list) {
                $roleName = $this->mapRoleKey($roleKey);
                $users = User::whereHas('roles', function ($q) use ($roleName) {
                    $q->where('name', $roleName);
                })->get();

                foreach ($users as $user) {
                    foreach ($list as $data) {
                        $this->sendNotification($user, $data);
                    }
                }
            }
            $this->info('Successfully sent demo notifications to all users matching CRM roles.');

            return Command::SUCCESS;
        }

        $roleKey = $this->getRoleKeyFromChoice($choice);
        $roleName = $this->mapRoleKey($roleKey);

        $users = User::whereHas('roles', function ($q) use ($roleName) {
            $q->where('name', $roleName);
        })->get();

        if ($users->isEmpty()) {
            $this->error("No users found with role: {$roleName}");

            return Command::FAILURE;
        }

        foreach ($users as $user) {
            foreach ($notifications[$roleKey] as $data) {
                $this->sendNotification($user, $data);
            }
        }

        $this->info("Successfully sent demo notifications to users with role: {$roleName}");

        return Command::SUCCESS;
    }

    private function mapRoleKey(string $key): string
    {
        return match ($key) {
            'super_admin' => 'super_admin',
            'sales' => 'Sales',
            'marketing' => 'Marketing',
            'support' => 'Support',
            'manager' => 'Manager/Analyst',
            default => $key,
        };
    }

    private function getRoleKeyFromChoice(string $choice): string
    {
        return match ($choice) {
            'Send to Sales Role' => 'sales',
            'Send to Support Role' => 'support',
            'Send to Marketing Role' => 'marketing',
            'Send to Manager Role' => 'manager',
            'Send to Super Admin Role' => 'super_admin',
            default => 'sales',
        };
    }

    private function sendNotification(User $user, array $data): void
    {
        AppNotification::create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'],
            'source_module' => $data['source_module'],
            'priority' => $data['priority'],
            'is_read' => false,
            'action_url' => $data['action_url'],
        ]);

        $filamentNotification = FilamentNotification::make()
            ->title($data['title'])
            ->body($data['message']);

        if ($data['type'] === 'success') {
            $filamentNotification->success();
        } elseif ($data['type'] === 'warning') {
            $filamentNotification->warning();
        } elseif ($data['type'] === 'danger') {
            $filamentNotification->danger();
        } else {
            $filamentNotification->info();
        }

        $filamentNotification->sendToDatabase($user);
    }
}
