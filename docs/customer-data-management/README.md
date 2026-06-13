# Customer Data Management - SmartCRM79

Dokumentasi ini dibuat untuk modul **Customer Data Management** pada branch `feature/customer`.
Modul ini menjadi single source of truth data pelanggan SmartCRM dan disiapkan agar mudah
diintegrasikan dengan modul lain seperti Sales, Marketing, Support, Dashboard, Analytics, Auth,
dan Integration.

## Artefak Standar

| Dokumen | Tujuan |
| --- | --- |
| [RE-DOCUMENT.md](RE-DOCUMENT.md) | Requirement, scope, business rules, dan acceptance criteria modul pelanggan. |
| [UI-UX-SPEC.md](UI-UX-SPEC.md) | Spesifikasi layar, role access, alur demo, dan state tampilan Filament. |
| [API-CONTRACT.md](API-CONTRACT.md) | Kontrak endpoint REST untuk CRUD, import/export, duplicate, merge, tag, favorite, attachment, dan activity. |
| [IMPLEMENTATION-REPORT.md](IMPLEMENTATION-REPORT.md) | Laporan implementasi fitur, file penting, dan status compliance terhadap scope. |
| [TEST-NOTES.md](TEST-NOTES.md) | Catatan pengujian happy path, edge cases, command validasi, dan known issue. |
| [customer-import-template.csv](customer-import-template.csv) | Template import CSV resmi untuk data pelanggan 20+ field. |

## Stack Aktual Project

- Laravel sebagai backend utama.
- Filament Admin Panel untuk dashboard dan manajemen data.
- MySQL/MariaDB melalui Laragon sebagai database lokal.
- Spatie Permission untuk role dan akses.
- Spatie Activitylog untuk audit trail.
- Laravel Excel untuk import/export CSV/Excel.
- Laravel Sanctum untuk API auth.
- Vite/Tailwind untuk asset frontend bawaan project.

## Catatan Integrasi

Dokumen kickoff memberi contoh stack React + Spring Boot + PostgreSQL. Repository kita saat ini
menggunakan Laravel + Filament + MySQL, sehingga standar yang dipenuhi di folder ini berfokus pada
artefak, alur, kontrak API, testing, dan integrasi modul tanpa mengganti konsep atau stack project.
