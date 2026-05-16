# Skill: BR-006 Main Dashboard & Notifications with Laravel + Filament

## Goal

Implement BR-006 using Laravel and Filament with native Filament widgets, database notifications, and role-based dashboard composition.[cite:51][cite:56][cite:57]

## Stack

- Laravel
- Filament Panel
- Livewire
- Laravel Notifications
- Filament Widgets
- Filament Database Notifications
- Optional: Laravel Broadcasting / Reverb / Pusher for real-time notifications.[cite:57][cite:65]

## Scope

- Personalized dashboard berdasarkan role.[cite:51]
- Quick links ke semua modul.[cite:51]
- Real-time notification center (bell icon). [cite:51][cite:57]
- Today’s task & upcoming deadline.[cite:51]
- Mini charts summary.[cite:51][cite:56]
- Welcome message & quick start guide.[cite:51]

## Implementation Principles

- Gunakan Filament sebagai shell utama panel admin karena dashboard dan notifications sudah didukung secara native melalui widgets dan database notifications.[cite:56][cite:57]
- Kerjakan BR-006 sebagai modul agregator, bukan source of truth utama.[cite:51]
- Ambil data dari modul lain melalui service layer atau query terpusat, jangan menaruh logika lintas modul langsung di widget.[cite:51]
- Pisahkan dashboard widgets per domain agar perubahan schema modul lain tidak merusak seluruh dashboard sekaligus.[cite:51]
- Dahulukan skeleton UI, widget contracts, dan fallback state; finalisasi query setelah schema modul lain stabil.[cite:51]

## Recommended Structure

```text
app/
├── Filament/
│   ├── Pages/
│   │   └── Dashboard.php
│   ├── Widgets/
│   │   ├── WelcomeWidget.php
│   │   ├── QuickLinksWidget.php
│   │   ├── TodayTasksWidget.php
│   │   ├── UpcomingDeadlinesWidget.php
│   │   ├── SalesSummaryWidget.php
│   │   ├── OpenTicketsWidget.php
│   │   └── NotificationSummaryWidget.php
│   └── Resources/
├── Models/
│   ├── User.php
│   ├── Task.php
│   ├── DeadlineReminder.php
│   └── QuickLink.php
├── Notifications/
│   ├── TaskAssignedNotification.php
│   ├── DeadlineReminderNotification.php
│   ├── TicketEscalatedNotification.php
│   └── MentionedInCommentNotification.php
├── Services/
│   └── Dashboard/
│       ├── DashboardDataService.php
│       ├── NotificationFeedService.php
│       ├── TaskOverviewService.php
│       ├── MetricsSummaryService.php
│       └── QuickLinksService.php
└── Support/
    └── Dashboard/
        └── RoleDashboardMap.php
```

## Filament Setup

### 1. Enable dashboard widgets

Gunakan halaman dashboard Filament dan daftar widget sesuai kebutuhan panel.[cite:56]

```php
// app/Providers/Filament/AdminPanelProvider.php
use App\Filament\Pages\Dashboard;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        ->default()
        ->id('admin')
        ->path('admin')
        ->login()
        ->pages([
            Dashboard::class,
        ])
        ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets');
}
```

### 2. Enable database notifications

Aktifkan database notifications di panel agar bell icon dan notification modal/slide-over milik Filament bisa dipakai langsung.[cite:57][cite:65]

```php
// app/Providers/Filament/AdminPanelProvider.php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        ->databaseNotifications()
        ->databaseNotificationsPolling('30s');
}
```

### 3. Install notifications table

Filament memakai notifikasi database Laravel. Tabel notifikasi harus dibuat terlebih dahulu.[cite:57]

```bash
php artisan make:notifications-table
php artisan migrate
```

## Dashboard Page Strategy

Buat satu dashboard page utama, lalu atur widget berdasarkan role user yang login.[cite:51][cite:56]

```php
// app/Filament/Pages/Dashboard.php
namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        $role = auth()->user()?->role;

        return match ($role) {
            'super_admin' => [
                \App\Filament\Widgets\WelcomeWidget::class,
                \App\Filament\Widgets\NotificationSummaryWidget::class,
                \App\Filament\Widgets\TodayTasksWidget::class,
                \App\Filament\Widgets\UpcomingDeadlinesWidget::class,
                \App\Filament\Widgets\SalesSummaryWidget::class,
                \App\Filament\Widgets\OpenTicketsWidget::class,
                \App\Filament\Widgets\QuickLinksWidget::class,
            ],
            'sales' => [
                \App\Filament\Widgets\WelcomeWidget::class,
                \App\Filament\Widgets\NotificationSummaryWidget::class,
                \App\Filament\Widgets\TodayTasksWidget::class,
                \App\Filament\Widgets\SalesSummaryWidget::class,
                \App\Filament\Widgets\QuickLinksWidget::class,
            ],
            'support' => [
                \App\Filament\Widgets\WelcomeWidget::class,
                \App\Filament\Widgets\NotificationSummaryWidget::class,
                \App\Filament\Widgets\TodayTasksWidget::class,
                \App\Filament\Widgets\OpenTicketsWidget::class,
                \App\Filament\Widgets\QuickLinksWidget::class,
            ],
            default => [
                \App\Filament\Widgets\WelcomeWidget::class,
                \App\Filament\Widgets\NotificationSummaryWidget::class,
                \App\Filament\Widgets\TodayTasksWidget::class,
                \App\Filament\Widgets\QuickLinksWidget::class,
            ],
        };
    }
}
```

## Recommended Widgets

### 1. WelcomeWidget

Isi:
- nama user
- role user
- quick start guide singkat
- CTA ke modul utama sesuai role.[cite:51]

Cocok dibuat sebagai custom widget view sederhana.

### 2. QuickLinksWidget

Isi:
- daftar menu cepat sesuai role
- icon Filament/Heroicons
- URL ke resource/page Filament.[cite:51]

Simpan konfigurasi quick links di service atau config agar mudah diganti.

### 3. TodayTasksWidget

Isi:
- task hari ini
- status task
- prioritas
- assignment source module.[cite:51]

Paling cocok dibuat dengan TableWidget jika datanya tabular.[cite:60]

### 4. UpcomingDeadlinesWidget

Isi:
- deadline terdekat
- tipe sumber deadline, misalnya deal, campaign, atau ticket.[cite:51]

Bisa dibuat sebagai TableWidget atau custom widget card list.

### 5. SalesSummaryWidget

Isi:
- sales this month
- target vs actual
- opportunities open.[cite:51]

Paling cocok pakai StatsOverviewWidget untuk angka ringkas.[cite:56]

### 6. OpenTicketsWidget

Isi:
- jumlah open ticket
- SLA warning
- escalated tickets.[cite:51]

Paling cocok pakai StatsOverviewWidget atau ChartWidget tergantung sumber data.[cite:56]

### 7. NotificationSummaryWidget

Isi:
- unread notifications count
- shortcut buka modal notifikasi
- ringkasan 5 notifikasi terbaru.[cite:57]

Bell icon utama tetap gunakan database notifications bawaan Filament agar konsisten dengan panel.[cite:57]

## Example Widget: Stats Overview

```php
namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesSummaryWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $data = app(\App\Services\Dashboard\MetricsSummaryService::class)
            ->salesSummary(auth()->user());

        return [
            Stat::make('Sales This Month', $data['sales_this_month'])
                ->description('Total transaksi bulan ini'),
            Stat::make('Open Opportunities', $data['open_opportunities'])
                ->description('Peluang aktif'),
            Stat::make('Conversion Rate', $data['conversion_rate'] . '%')
                ->description('Rasio konversi'),
        ];
    }
}
```

## Notifications Strategy

### Database notifications

Gunakan notifikasi database Laravel + Filament untuk kebutuhan utama bell icon dan notification list.[cite:57][cite:65]

```php
use Filament\Notifications\Notification;

Notification::make()
    ->title('Task baru ditugaskan')
    ->body('Anda mendapat task follow-up customer hari ini.')
    ->sendToDatabase($user);
```

### Broadcast / real-time notifications

Jika ingin real-time tanpa polling, Filament mendukung broadcast notifications, tetapi perlu setup broadcasting Laravel dan websocket service seperti Pusher atau Echo-compatible stack.[cite:62][cite:65]

```php
use Filament\Notifications\Notification;

Notification::make()
    ->title('Ticket melewati SLA')
    ->body('Segera tindak lanjuti ticket dengan prioritas tinggi.')
    ->broadcast($user);
```

### Trigger locations

Notifikasi sebaiknya dikirim dari:
- Observer model
- Action Filament setelah create/update
- Domain event listener
- Scheduled job untuk reminder deadline.[cite:61][cite:63]

## Service Layer Contract

Jangan query semua data langsung di widget. Buat service layer seperti berikut:

```php
namespace App\Services\Dashboard;

use App\Models\User;

class DashboardDataService
{
    public function forUser(User $user): array
    {
        return [
            'tasks_today' => app(TaskOverviewService::class)->today($user),
            'deadlines' => app(TaskOverviewService::class)->upcomingDeadlines($user),
            'quick_links' => app(QuickLinksService::class)->forRole($user->role),
            'metrics' => app(MetricsSummaryService::class)->summary($user),
            'notifications' => app(NotificationFeedService::class)->latest($user),
        ];
    }
}
```

## Role-Based Composition

Gunakan role map terpusat, jangan hardcode di terlalu banyak tempat.

```php
return [
    'super_admin' => [
        'widgets' => ['welcome', 'notifications', 'tasks', 'deadlines', 'sales', 'tickets', 'quick_links'],
        'links' => ['users', 'customers', 'sales', 'campaigns', 'tickets', 'reports'],
    ],
    'sales' => [
        'widgets' => ['welcome', 'notifications', 'tasks', 'sales', 'quick_links'],
        'links' => ['customers', 'leads', 'opportunities', 'calendar'],
    ],
    'support' => [
        'widgets' => ['welcome', 'notifications', 'tasks', 'tickets', 'quick_links'],
        'links' => ['tickets', 'knowledge-base', 'customers'],
    ],
];
```

## Suggested Data Sources by Widget

| Widget | Sumber utama | Catatan |
|---|---|---|
| WelcomeWidget | users, roles | dari auth user |
| QuickLinksWidget | role config / permissions | sebaiknya config-driven |
| TodayTasksWidget | tasks, assignments | gabungan dari modul sales/support/marketing |
| UpcomingDeadlinesWidget | tasks, deals, tickets, campaigns | normalisasi tanggal penting |
| SalesSummaryWidget | deals, sales metrics | tunggu schema sales stabil |
| OpenTicketsWidget | tickets, sla status | tunggu schema support stabil |
| NotificationSummaryWidget | notifications table | pakai Laravel notifications |

## Database Suggestions

Minimal tabel internal BR-006 yang aman dibuat dari awal:
- `notifications` dari Laravel notifications table.[cite:57]
- `quick_links` jika ingin konfigurasi lewat database.
- `dashboard_preferences` untuk simpan layout atau preferensi widget user.

Sisanya, seperti task, deals, tickets, campaign metrics, sebaiknya konsumsi dari modul sumber masing-masing setelah schema stabil.[cite:51]

## Implementation Order

1. Setup panel Filament, dashboard page, dan database notifications.[cite:57][cite:65]
2. Buat WelcomeWidget, QuickLinksWidget, dan NotificationSummaryWidget lebih dulu.[cite:51]
3. Buat TodayTasksWidget dan UpcomingDeadlinesWidget dengan fallback state.[cite:51]
4. Tambahkan StatsOverviewWidget untuk sales summary dan open tickets setelah schema modul sumber jelas.[cite:51]
5. Tambahkan broadcast / websocket hanya jika kebutuhan real-time penuh benar-benar diperlukan.[cite:62][cite:65]
6. Refactor seluruh query lintas modul ke service layer.[cite:51]

## Safe Fallback Rules

- Jika data sales belum siap, tampilkan widget dengan state `Data belum tersedia`.
- Jika modul tickets belum stabil, sembunyikan widget support untuk role tertentu.
- Jika notifications queue belum aktif, fallback ke polling database notifications.[cite:57][cite:65]
- Jangan membuat satu query dashboard besar yang gagal total saat satu domain bermasalah.

## Coding Notes

- Gunakan `StatsOverviewWidget` untuk KPI ringkas.[cite:56]
- Gunakan `TableWidget` untuk task/deadline list.[cite:60]
- Gunakan database notifications bawaan Filament untuk bell icon.[cite:57]
- Gunakan broadcast notifications hanya jika stack websocket siap.[cite:62][cite:65]
- Simpan business logic dashboard di service, bukan di widget.
- Gunakan policy / permission Laravel atau Filament Shield jika butuh kontrol akses granular.

## Minimal Definition of Done

- Dashboard tampil sesuai role.
- Quick links tampil sesuai role.
- Bell icon notifikasi aktif.
- Notifikasi database bisa terkirim ke user.
- Task hari ini dan deadline terdekat tampil.
- Minimal 2 mini summary cards tampil.
- Setiap widget punya empty state dan error state.
- Widget tidak saling membuat dashboard gagal total saat satu sumber data error.

## Recommended Prompt for Coding Agent

```text
Implement BR-006 Main Dashboard & Notifications in Laravel + Filament.
Use Filament dashboard page, role-based widgets, database notifications, and Filament components.
Create these widgets: WelcomeWidget, QuickLinksWidget, NotificationSummaryWidget, TodayTasksWidget, UpcomingDeadlinesWidget, SalesSummaryWidget, OpenTicketsWidget.
Use service classes for cross-module data aggregation.
Use native Filament database notifications for the bell icon.
Use StatsOverviewWidget for mini summaries and TableWidget for task/deadline lists.
Add fallback empty states if dependent module data is unavailable.
Keep widget visibility role-based.
Avoid hardcoding cross-module queries directly inside widget classes.
```
