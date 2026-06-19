# SmartCRM Analytics Implementation Plan

## Objective

Build an Analytical CRM module that follows the existing SmartCRM user interface,
authorization model, and Laravel conventions while keeping metric calculations
accurate, testable, and easy to extend.

Required capabilities:

- Interactive charts: bar, line, pie, and funnel.
- KPI cards: total revenue, conversion rate, churn rate, and customer lifetime value.
- Filters: date range, sales person, product, and region.
- Custom report builder with drag-and-drop columns.
- Branded PDF, Excel, and CSV exports.
- Scheduled weekly and monthly email reports.
- Role-based dashboards for manager and sales users.

## Branch Assessment

| Branch | Assessment | Decision |
| --- | --- | --- |
| `origin/develop` | Latest integrated baseline. Contains Filament UI, dashboard widgets, customer module, RBAC, exports, queues, mail, Swagger, and tests. | Use as the implementation baseline. |
| `origin/feature/customer` | Fully contained in `develop`. | Do not merge separately. |
| `origin/feature/dashboard` | Has three commits not in `develop`, but its tree is significantly behind and misses many integrated files. | Review selected commits only; do not merge the branch wholesale. |
| `origin/feature/integration` | Contains early analytics summary and customer-growth endpoints, but is behind `develop`. | Reimplement the useful intent on top of `develop`; do not merge wholesale. |
| `origin/feature/auth` | Contains a useful waiting-assignment/auth-audit commit, but is behind `develop`. | Keep as reference; unrelated to analytics delivery. |
| `feature/analytics` | Currently points to the minimal `main` baseline. | Synchronize with `origin/develop` before analytics implementation. |

The current worktree also contains pre-existing local dependency changes in
`composer.json`, `composer.lock`, and `package-lock.json`. These must be preserved
when synchronizing the branch.

## Existing Project Conventions

Analytics should follow the dominant conventions in `develop`:

- Laravel 13 and PHP 8.3.
- Filament 5 for authenticated application pages, widgets, charts, tables, and actions.
- Tailwind CSS 4 and the existing Filament visual language.
- Filament `StatsOverviewWidget` for KPI cards.
- Filament `ChartWidget` for Chart.js visualizations.
- Six-column responsive dashboard grid.
- Spatie Permission and Filament Shield for authorization.
- Pest/PHPUnit feature tests with `RefreshDatabase`.
- OpenAPI attributes for API documentation.
- Service classes for reusable business calculations.
- Queue-backed jobs for long-running exports and scheduled email delivery.

Use the standard folders already used by integrated code:

```text
app/Filament/Pages/Analytics/
app/Filament/Widgets/Analytics/
app/Http/Controllers/Api/V1/
app/Http/Requests/Analytics/
app/Models/
app/Policies/
app/Services/Analytics/
app/Jobs/Analytics/
app/Mail/
```

Do not place production logic in `app/Modules/Analytics` yet. The module folders
exist only as placeholders and no existing feature uses them as an active
architecture.

## Reusable Foundation

The integrated project already provides:

- Customer dimensions: status, source, assigned sales person, industry, city,
  province, country, and timestamps.
- Customer activity logging.
- Filament role-aware dashboards and reusable chart/stat widget patterns.
- Customer Excel and CSV export infrastructure.
- Database queues and mail configuration.
- Sanctum API authentication.
- Swagger/OpenAPI support.
- Manager/Analyst, Sales, Marketing, Support, and super-admin roles.

The early analytics endpoints in `feature/integration` provide only customer
counts and monthly customer growth. Their intent is useful, but their controller
format, authorization, date grouping, and database portability should not be
copied directly.

## Data Gaps

The current schema cannot calculate the required business KPIs accurately.

There is no persisted source of truth for:

- Deal or transaction revenue.
- Deal stage history and conversion events.
- Products connected to deals.
- Lost or churned customer events.
- Subscription periods.
- Customer revenue history for lifetime value.

Some existing dashboard widgets estimate revenue by multiplying customer counts
by hard-coded values and provide demo fallbacks when data is absent. Those
widgets are useful UI references only and must not become analytics business
logic.

## Proposed Data Model

Minimum domain additions:

### `products`

- `id`
- `name`
- `sku`
- `category`
- `is_active`
- timestamps

### `deals`

- `id`
- `customer_id`
- `owner_id`
- `product_id`
- `stage`
- `status`
- `amount`
- `probability`
- `expected_close_at`
- `closed_at`
- `lost_at`
- timestamps

### `customer_status_histories`

- `id`
- `customer_id`
- `from_status`
- `to_status`
- `changed_by`
- `changed_at`

### `report_definitions`

- `id`
- `owner_id`
- `name`
- `columns` JSON
- `filters` JSON
- `chart_config` JSON
- `visibility`
- timestamps

### `report_schedules`

- `id`
- `report_definition_id`
- `frequency`
- `recipients` JSON
- `next_run_at`
- `last_run_at`
- `is_active`
- timestamps

### `report_runs`

- `id`
- `report_definition_id`
- `requested_by`
- `format`
- `status`
- `file_disk`
- `file_path`
- `completed_at`
- `error_message`
- timestamps

Metric definitions must be documented before implementation:

- Total revenue: sum of won deal amounts in the selected period.
- Conversion rate: won deals divided by qualified opportunities in the selected period.
- Churn rate: customers moved to churned/inactive divided by active customers at period start.
- Customer lifetime value: total won revenue per customer, with an optional average view.

## Proposed Application Design

### Filament UI

Create a dedicated `AnalyticsDashboard` page rather than adding every analytics
widget to the operational dashboard.

Recommended layout:

1. Shared filter bar at the top.
2. Four full-width KPI cards.
3. Revenue trend line/bar chart.
4. Sales funnel chart.
5. Revenue by product pie chart.
6. Revenue by region bar chart.
7. Sales-person performance table.
8. Saved reports and schedule management.

Use one filter state object for the entire page and pass it to widgets through
Filament dashboard filters. Avoid duplicating filter parsing in each widget.

### Services

Keep controllers, pages, and widgets thin:

```text
AnalyticsFilterData
AnalyticsQuery
KpiService
RevenueAnalyticsService
ConversionAnalyticsService
ChurnAnalyticsService
CustomerLifetimeValueService
ReportBuilderService
ReportExportService
ReportScheduleService
```

All metric services should accept the same validated filter object. Shared query
scopes must enforce role-based visibility:

- `super_admin` and `Manager/Analyst`: all permitted analytics data.
- `Sales`: only deals/customers assigned to the authenticated user.
- Other roles: no analytics access until explicitly granted.

### API

Place analytics APIs behind `auth:sanctum` and authorization middleware.

Suggested endpoints:

```text
GET    /api/v1/analytics/kpis
GET    /api/v1/analytics/revenue-trend
GET    /api/v1/analytics/sales-funnel
GET    /api/v1/analytics/by-product
GET    /api/v1/analytics/by-region
GET    /api/v1/analytics/sales-performance
GET    /api/v1/analytics/reports
POST   /api/v1/analytics/reports
PUT    /api/v1/analytics/reports/{report}
DELETE /api/v1/analytics/reports/{report}
POST   /api/v1/analytics/reports/{report}/export
POST   /api/v1/analytics/reports/{report}/schedules
```

Use form request classes for validation and a single API response convention.
Do not repeat the current mix of `status: success` and `success: true` formats.

### Export And Scheduling

- Reuse Filament/Laravel Excel infrastructure for Excel and CSV.
- Add a maintained PDF renderer only when PDF report templates are ready.
- Generate large reports in queued jobs.
- Store report run status and generated file metadata.
- Use Laravel's scheduler to dispatch due weekly/monthly report jobs.
- Email links or attachments according to file size and retention policy.

## Security Requirements

- Protect all analytics endpoints with Sanctum.
- Add an `AnalyticsPolicy` and explicit permissions.
- Apply sales-person data scoping in the query layer, not only in the UI.
- Validate saved report ownership and visibility.
- Prevent arbitrary column names in the report builder by using an allowlist.
- Escape labels and branding fields used in generated files.
- Record export and schedule changes in the activity log.

## Testing Strategy

Required automated coverage:

- KPI calculations for empty data, normal data, and period boundaries.
- Date, sales-person, product, and region filters.
- Manager versus sales data visibility.
- Unauthorized API and Filament page access.
- Saved report column allowlist and ownership.
- Excel, CSV, and PDF export generation.
- Weekly/monthly schedule dispatch.
- Database portability for SQLite tests and the production database.

Avoid MySQL-only expressions such as `MONTH(created_at)` in shared analytics
queries. Prefer date ranges and database-portable aggregation, or isolate
database-specific expressions behind tested query implementations.

## Delivery Sequence

1. Synchronize `feature/analytics` with `origin/develop` while preserving local dependency changes.
2. Add analytics permissions, policy, shared filters, and a Filament analytics page shell.
3. Add the minimum deal/product/status-history schema and realistic seed data.
4. Implement tested KPI services and role-based query scoping.
5. Add interactive charts and performance tables.
6. Implement saved custom reports and drag-and-drop column ordering.
7. Implement queued Excel/CSV/PDF exports with branding.
8. Implement scheduled email reports and audit logging.
9. Complete OpenAPI documentation, feature tests, Pint formatting, and UI verification.

## Definition Of Done

- Required analytics features work with real persisted data, not demo fallbacks.
- Analytics UI visually matches the existing Filament application.
- Manager and sales users see only their authorized data.
- Metric definitions are documented and covered by tests.
- Export and schedule jobs are queue-safe and observable.
- APIs are authenticated, documented, and consistently formatted.
- `composer test`, Pint, and frontend build pass.
