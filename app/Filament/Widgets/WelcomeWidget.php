<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WelcomeWidget extends Widget
{
    protected static ?string $pollingInterval = null;

    protected string $view = 'filament.widgets.welcome-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public function getRoleName(): string
    {
        $user = auth()->user();

        return match (true) {
            $user?->hasRole('super_admin') => 'Super Admin',
            $user?->hasRole('Sales') => 'Sales',
            $user?->hasRole('Marketing') => 'Marketing',
            $user?->hasRole('Support') => 'Support',
            $user?->hasRole('Manager/Analyst') => 'Manager/Analyst',
            default => 'User',
        };
    }

    public function getRoleDescription(): string
    {
        return match ($this->getRoleName()) {
            'Super Admin' => 'Mengelola user, customer, audit log, dan integrasi teknis.',
            'Sales' => 'Mengelola customer, follow-up, deteksi duplikat, dan smart merge.',
            'Marketing' => 'Mengelola data customer untuk segmentasi, import, dan export.',
            'Support' => 'Melihat data customer untuk kebutuhan layanan dan histori pelanggan.',
            'Manager/Analyst' => 'Memantau customer, laporan, audit log, dan indikator performa.',
            default => 'Mengakses fitur SmartCRM sesuai peran yang diberikan.',
        };
    }

    public function getDemoTips(): array
    {
        return match ($this->getRoleName()) {
            'Super Admin' => ['Buka Users untuk mengelola akun demo', 'Buka Customer Management untuk melihat semua data', 'Buka Audit Log untuk melihat aktivitas sistem'],
            'Sales' => ['Tambah atau edit customer', 'Klik Cek Duplikat untuk mencari data ganda', 'Gunakan Merge Duplikat pada baris customer utama'],
            'Marketing' => ['Import customer dari CSV/Excel', 'Download Excel atau CSV untuk kebutuhan campaign', 'Gunakan custom fields seperti Segment atau Kategori'],
            'Support' => ['Buka Customer Management untuk melihat identitas pelanggan', 'Gunakan pencarian untuk menemukan customer', 'Cek detail customer sebelum menangani kasus'],
            'Manager/Analyst' => ['Lihat dashboard performa', 'Buka Customer Management untuk review data', 'Buka Audit Log untuk memantau aktivitas'],
            default => ['Buka Customer Management', 'Gunakan pencarian data', 'Ikuti akses sesuai role'],
        };
    }
}
