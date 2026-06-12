<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing notifications in app_notifications
        Notification::truncate();

        // 1. Sales User Notifications
        $salesUser = User::where('email', 'sales@smartcrm.com')->first();
        if ($salesUser) {
            Notification::create([
                'user_id' => $salesUser->id,
                'title' => 'New Hot Lead Assigned',
                'message' => 'Lead "Rian Dwi" from Toyota Astra has been assigned to you. Status is currently: Lead.',
                'type' => 'info',
                'source_module' => 'sales',
                'priority' => 'high',
                'is_read' => false,
                'action_url' => '/admin/customers',
            ]);

            Notification::create([
                'user_id' => $salesUser->id,
                'title' => 'Deal Closed Successfully!',
                'message' => 'Opportunity deal with "PT Maju Jaya" has been updated to Closed Won ($15,000 value).',
                'type' => 'success',
                'source_module' => 'sales',
                'priority' => 'normal',
                'is_read' => false,
            ]);

            Notification::create([
                'user_id' => $salesUser->id,
                'title' => 'Follow-up Reminder',
                'message' => 'You have a scheduled follow-up call with "Siti Aminah" in 2 hours.',
                'type' => 'warning',
                'source_module' => 'sales',
                'priority' => 'high',
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        // 2. Support User Notifications
        $supportUser = User::where('email', 'support@smartcrm.com')->first();
        if ($supportUser) {
            Notification::create([
                'user_id' => $supportUser->id,
                'title' => 'New High-Priority Ticket',
                'message' => 'Ticket #TCK-2045: "Billing issue on account" has been created and assigned to you.',
                'type' => 'warning',
                'source_module' => 'support',
                'priority' => 'high',
                'is_read' => false,
                'action_url' => '#',
            ]);

            Notification::create([
                'user_id' => $supportUser->id,
                'title' => 'SLA Alert: 30 Mins Left',
                'message' => 'Ticket #TCK-1982: "MFA Verification failing" is approaching its SLA response time limit.',
                'type' => 'danger',
                'source_module' => 'support',
                'priority' => 'high',
                'is_read' => false,
                'action_url' => '#',
            ]);

            Notification::create([
                'user_id' => $supportUser->id,
                'title' => 'New Feedback Received',
                'message' => 'Customer "Budi Santoso" left a 5-star rating on resolved ticket #TCK-1822.',
                'type' => 'success',
                'source_module' => 'support',
                'priority' => 'normal',
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        // 3. Marketing User Notifications
        $marketingUser = User::where('email', 'marketing@smartcrm.com')->first();
        if ($marketingUser) {
            Notification::create([
                'user_id' => $marketingUser->id,
                'title' => 'Campaign Limit Reached',
                'message' => 'The "Ramadhan Promo 2026" email campaign has successfully sent 5,000 emails.',
                'type' => 'success',
                'source_module' => 'marketing',
                'priority' => 'normal',
                'is_read' => false,
            ]);

            Notification::create([
                'user_id' => $marketingUser->id,
                'title' => 'A/B Test Winner Selected',
                'message' => 'A/B testing for "Newsletter #12" has concluded. Subject variant A (Open rate: 24%) won.',
                'type' => 'info',
                'source_module' => 'marketing',
                'priority' => 'normal',
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        // 4. Manager/Analyst User Notifications
        $managerUser = User::where('email', 'manager@smartcrm.com')->first();
        if ($managerUser) {
            Notification::create([
                'user_id' => $managerUser->id,
                'title' => 'Monthly Target Reached!',
                'message' => 'SmartCRM79 total revenue has exceeded $140,000 this month. Great job team!',
                'type' => 'success',
                'source_module' => 'analytics',
                'priority' => 'high',
                'is_read' => false,
            ]);

            Notification::create([
                'user_id' => $managerUser->id,
                'title' => 'Weekly Report Generated',
                'message' => 'The business performance report for week 23 is ready for download.',
                'type' => 'info',
                'source_module' => 'analytics',
                'priority' => 'normal',
                'is_read' => false,
                'action_url' => '/admin/activity-logs',
            ]);
        }

        // 5. Super Admin User Notifications
        $adminUser = User::where('email', 'admin@smartcrm.com')->first();
        if ($adminUser) {
            Notification::create([
                'user_id' => $adminUser->id,
                'title' => 'System Integration Health Check',
                'message' => 'All 12 webhook endpoints are responding successfully. System health: 100%.',
                'type' => 'success',
                'source_module' => 'integration',
                'priority' => 'normal',
                'is_read' => false,
            ]);

            Notification::create([
                'user_id' => $adminUser->id,
                'title' => 'API Rate Limit Warning',
                'message' => 'Integration endpoint /api/v1/customers reached 90% of rate limit threshold.',
                'type' => 'warning',
                'source_module' => 'integration',
                'priority' => 'high',
                'is_read' => false,
            ]);

            Notification::create([
                'user_id' => $adminUser->id,
                'title' => 'Failed Webhook Resent',
                'message' => 'Failed webhook transaction #LOG-9823 was auto-retried and successfully sent.',
                'type' => 'info',
                'source_module' => 'integration',
                'priority' => 'normal',
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }
}
