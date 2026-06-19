# SmartCRM Analytics

The analytics feature is built on the integrated Filament application and uses a
single service layer for dashboard widgets, APIs, custom reports, and scheduled
email reports.

## User Interface

- `/admin/analytics`: KPI cards, shared filters, charts, funnel, and sales performance.
- `/admin/analytics-reports`: drag-and-drop custom report builder and export actions.
- `/admin/analytics-report-schedules`: weekly/monthly scheduled report management.

The UI follows the existing Filament dashboard layout, Amber primary color, dark
mode, responsive six-column grid, and navigation grouping.

## Metric Definitions

- Total revenue: sum of `Won` deal amounts.
- Conversion rate: `Won` deals divided by all `Won` and `Lost` deals.
- Churn rate: inactive customers represented in the filtered deal set divided by
  unique customers represented in that set.
- Customer lifetime value: total won revenue divided by unique customers with a
  won deal.

The selected date range uses `closed_at` when available and falls back to
`created_at` for open deals.

## Authorization

- `super_admin` and `Manager/Analyst` can view all analytics data.
- `Sales` users only receive analytics for deals they own.
- Access requires the `View:Analytics` permission.
- Custom report update/delete access is limited to the owner, manager, or super admin.
- Team-visible reports may be viewed and scheduled by other authorized users.

Role scoping is applied in `AnalyticsQuery`, so the same rule protects the
Filament dashboard, APIs, exports, and scheduled reports.

## Main Components

```text
app/Services/Analytics/AnalyticsQuery.php
app/Services/Analytics/AnalyticsService.php
app/Services/Analytics/ReportBuilderService.php
app/Services/Analytics/ReportExportService.php
app/Filament/Pages/AnalyticsDashboard.php
app/Filament/Resources/AnalyticsReports/
app/Filament/Resources/AnalyticsReportSchedules/
app/Jobs/GenerateScheduledAnalyticsReport.php
```

## API

All API endpoints require Sanctum authentication and `View:Analytics`:

```text
GET /api/v1/analytics/dashboard
GET /api/v1/analytics/kpis
GET /api/v1/analytics/revenue-trend
GET /api/v1/analytics/sales-funnel
GET /api/v1/analytics/by-product
GET /api/v1/analytics/by-region
GET /api/v1/analytics/sales-performance
GET /api/v1/analytics/sales-performance/export/csv
```

Supported filter query parameters:

```text
from
until
owner_id
product_id
region
```

## Scheduled Reports

Laravel's scheduler checks due reports every hour:

```bash
php artisan analytics:send-scheduled-reports
```

Production must run Laravel's scheduler and queue worker. The dispatch command
atomically advances the due schedule before queueing it to prevent duplicate
dispatches.

Supported formats:

- CSV
- Excel (`.xlsx`)
- PDF

Each scheduled execution creates an `analytics_report_runs` record and stores the
generated file on the local disk before emailing it.

## Development

Create the schema and demo analytics data:

```bash
php artisan migrate
php artisan db:seed
```

Verification:

```bash
vendor/bin/pint --test
php artisan test
npm run build
php artisan route:list --path=analytics
php artisan schedule:list
```
