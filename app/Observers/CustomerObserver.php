<?php

namespace App\Observers;

use App\Models\Customer;
use App\Models\Notification as AppNotification;
use App\Models\User;
use App\Support\FilamentDatabaseNotification;

class CustomerObserver
{
    public function created(Customer $customer): void
    {
        $companyText = $customer->company_name ? " ({$customer->company_name})" : '';
        $fromCompanyText = $customer->company_name ? " from \"{$customer->company_name}\"" : '';

        // Notify Sales: new lead assigned
        $this->notifyRole(
            roles: ['sales', 'Sales'],
            customer: $customer,
            title: 'New Lead Assigned',
            message: "New lead \"{$customer->full_name}\"{$fromCompanyText} has been registered and is ready for follow-up.",
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
            message: "Customer \"{$customer->full_name}\"{$companyText} has been registered. Source: " . ($customer->source ?? 'N/A') . '.',
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
            message: "Customer \"{$customer->full_name}\"{$companyText} has been added to the system.",
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
            message: "Customer \"{$customer->full_name}\"{$companyText} has been registered in the system.",
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
                message: "Opportunity \"{$customer->full_name}\" has been closed won.",
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
                message: "Your lead \"{$customer->full_name}\" has been converted to a customer.",
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
                message: "Customer \"{$customer->full_name}\" has been converted. Source: " . ($customer->source ?? 'N/A') . '.',
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
                message: "Opportunity \"{$customer->full_name}\" has been closed won.",
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

            FilamentDatabaseNotification::send(
                user: $user,
                title: $title,
                body: $message,
                type: $type,
                actionLabel: $actionLabel,
                actionUrl: $actionUrl,
            );
        }
    }
}
