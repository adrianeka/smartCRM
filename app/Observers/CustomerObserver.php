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
        $companyText = $customer->company_name ? " ({$customer->company_name})" : '';
        $fromCompanyText = $customer->company_name ? " dari perusahaan \"{$customer->company_name}\"" : '';

        // Notify Sales: new lead assigned
        $this->notifyRole(
            roles: ['sales', 'Sales'],
            customer: $customer,
            title: 'Prospek Baru Ditugaskan',
            message: "Prospek baru \"{$customer->full_name}\"{$fromCompanyText} telah terdaftar dan siap untuk di-follow-up.",
            type: 'info',
            sourceModule: 'sales',
            priority: 'high',
            actionLabel: 'Lihat Prospek'
        );

        // Notify Marketing: new lead for segmentation
        $this->notifyRole(
            roles: ['marketing', 'Marketing'],
            customer: $customer,
            title: 'Prospek Baru Tersedia untuk Segmentasi',
            message: "Pelanggan \"{$customer->full_name}\"{$companyText} telah terdaftar. Sumber: " . ($customer->source ?? 'N/A') . '.',
            type: 'info',
            sourceModule: 'marketing',
            priority: 'normal',
            actionLabel: 'Lihat Pelanggan'
        );

        // Notify Support: new customer record
        $this->notifyRole(
            roles: ['support', 'Support'],
            customer: $customer,
            title: 'Data Pelanggan Baru',
            message: "Pelanggan \"{$customer->full_name}\"{$companyText} telah ditambahkan ke sistem.",
            type: 'info',
            sourceModule: 'support',
            priority: 'normal',
            actionLabel: 'Lihat Pelanggan'
        );

        // Notify Super Admin: new customer registered
        $this->notifyRole(
            roles: ['super_admin'],
            customer: $customer,
            title: 'Pelanggan Baru Terdaftar',
            message: "Pelanggan \"{$customer->full_name}\"{$companyText} telah didaftarkan di sistem.",
            type: 'info',
            sourceModule: 'customer',
            priority: 'normal',
            actionLabel: 'Lihat Pelanggan'
        );
    }

    public function updated(Customer $customer): void
    {
        if ($customer->isDirty('status') && $customer->status === 'Customer') {
            // Notify Manager/Analyst: deal closed
            $this->notifyRole(
                roles: ['Manager/Analyst', 'manager'],
                customer: $customer,
                title: 'Penjualan Berhasil Ditutup!',
                message: "Peluang \"{$customer->full_name}\" berhasil ditutup (Closed Won).",
                type: 'success',
                sourceModule: 'sales',
                priority: 'high',
                actionLabel: 'Lihat Pelanggan'
            );

            // Notify Sales: their lead converted
            $this->notifyRole(
                roles: ['sales', 'Sales'],
                customer: $customer,
                title: 'Prospek Dikonversi Menjadi Pelanggan!',
                message: "Prospek Anda \"{$customer->full_name}\" telah berhasil dikonversi menjadi pelanggan.",
                type: 'success',
                sourceModule: 'sales',
                priority: 'high',
                actionLabel: 'Lihat Pelanggan'
            );

            // Notify Marketing: conversion for campaign tracking
            $this->notifyRole(
                roles: ['marketing', 'Marketing'],
                customer: $customer,
                title: 'Prospek Dikonversi Menjadi Pelanggan',
                message: "Pelanggan \"{$customer->full_name}\" telah dikonversi. Sumber: " . ($customer->source ?? 'N/A') . '.',
                type: 'success',
                sourceModule: 'marketing',
                priority: 'normal',
                actionLabel: 'Lihat Pelanggan'
            );

            // Notify Super Admin: deal closed
            $this->notifyRole(
                roles: ['super_admin'],
                customer: $customer,
                title: 'Penjualan Berhasil Ditutup!',
                message: "Peluang \"{$customer->full_name}\" berhasil ditutup (Closed Won).",
                type: 'success',
                sourceModule: 'sales',
                priority: 'high',
                actionLabel: 'Lihat Pelanggan'
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
