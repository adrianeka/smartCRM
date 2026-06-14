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
        // Notify Sales: new lead assigned
        $this->notifyRole(
            roles: ['sales', 'Sales'],
            customer: $customer,
            title: 'New Lead Assigned',
            message: "A new lead \"{$customer->full_name}\" from company \"{$customer->company_name}\" has been registered and is ready for follow-up.",
            type: 'info',
            sourceModule: 'sales',
            priority: 'high',
            actionLabel: 'View Lead'
        );

        // Notify Marketing: new lead for segmentation
        $this->notifyRole(
            roles: ['marketing', 'Marketing'],
            customer: $customer,
            title: 'New Lead Available for Segmentation',
            message: "Customer \"{$customer->full_name}\" ({$customer->company_name}) has been registered. Source: ".($customer->source ?? 'N/A').'.',
            type: 'info',
            sourceModule: 'marketing',
            priority: 'normal',
            actionLabel: 'View Customer'
        );

        // Notify Support: new customer record
        $this->notifyRole(
            roles: ['support', 'Support'],
            customer: $customer,
            title: 'New Customer Record',
            message: "Customer \"{$customer->full_name}\" ({$customer->company_name}) has been added to the system.",
            type: 'info',
            sourceModule: 'support',
            priority: 'normal',
            actionLabel: 'View Customer'
        );

        // Notify Super Admin: new customer registered
        $this->notifyRole(
            roles: ['super_admin'],
            customer: $customer,
            title: 'New Customer Registered',
            message: "Customer \"{$customer->full_name}\" ({$customer->company_name}) has been registered in the system.",
            type: 'info',
            sourceModule: 'customer',
            priority: 'normal',
            actionLabel: 'View Customer'
        );
    }

    public function updated(Customer $customer): void
    {
        if ($customer->isDirty('status') && $customer->status === 'Customer') {
            // Notify Manager/Analyst: deal closed
            $this->notifyRole(
                roles: ['Manager/Analyst', 'manager'],
                customer: $customer,
                title: 'Deal Closed Successfully!',
                message: "Opportunity \"{$customer->full_name}\" has been successfully closed won.",
                type: 'success',
                sourceModule: 'sales',
                priority: 'high',
                actionLabel: 'View Customer'
            );

            // Notify Sales: their lead converted
            $this->notifyRole(
                roles: ['sales', 'Sales'],
                customer: $customer,
                title: 'Lead Converted to Customer!',
                message: "Your lead \"{$customer->full_name}\" has been successfully converted to a customer.",
                type: 'success',
                sourceModule: 'sales',
                priority: 'high',
                actionLabel: 'View Customer'
            );

            // Notify Marketing: conversion for campaign tracking
            $this->notifyRole(
                roles: ['marketing', 'Marketing'],
                customer: $customer,
                title: 'Lead Converted to Customer',
                message: "Customer \"{$customer->full_name}\" has been converted. Source: ".($customer->source ?? 'N/A').'.',
                type: 'success',
                sourceModule: 'marketing',
                priority: 'normal',
                actionLabel: 'View Customer'
            );

            // Notify Super Admin: deal closed
            $this->notifyRole(
                roles: ['super_admin'],
                customer: $customer,
                title: 'Deal Closed Successfully!',
                message: "Opportunity \"{$customer->full_name}\" has been successfully closed won.",
                type: 'success',
                sourceModule: 'sales',
                priority: 'high',
                actionLabel: 'View Customer'
            );
        }
    }

    /**
     * Send notification to all users with the given roles.
     */
    private function notifyRole(
        array $roles,
        Customer $customer,
        string $title,
        string $message,
        string $type,
        string $sourceModule,
        string $priority,
        string $actionLabel,
    ): void {
        $users = User::whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        })->get();

        $actionUrl = "/admin/customers/{$customer->id}";

        foreach ($users as $user) {
            // Write to app_notifications table
            AppNotification::create([
                'user_id' => $user->id,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'source_module' => $sourceModule,
                'priority' => $priority,
                'is_read' => false,
                'action_url' => $actionUrl,
            ]);

            // Write to Filament notifications table (bell icon)
            $notification = FilamentNotification::make()
                ->title($title)
                ->body($message);

            match ($type) {
                'success' => $notification->success(),
                'warning' => $notification->warning(),
                'danger' => $notification->danger(),
                default => $notification->info(),
            };

            $notification
                ->actions([
                    Action::make('view')
                        ->label($actionLabel)
                        ->url($actionUrl),
                ])
                ->sendToDatabase($user);
        }
    }
}
