# Audit Management System (Laravel 10)

Production-focused starter architecture for an Audit Management System with:

- PostgreSQL primary transactional storage
- Oracle read-only reporting (PAMS)
- Authentication + RBAC + check-in/check-out enforcement

## Key Modules

1. **Staff Info** (PostgreSQL): CRUD for audit engagements, activities, notes, evidence.
2. **PAMS** (Oracle): Read-only reports with filtering, pagination, PDF/Excel export.

## Security & Session Control

- Session regeneration on login
- Required check-in before dashboard/module access
- Required check-out before logout
- Single active user session constraint in `checkin_logs`
- Role middleware for route-level authorization

## Oracle Read-only Enforcement

`App\Models\OracleReportModel` hard-blocks `save()` and `delete()` calls.

## Setup (when dependencies are available)

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
```
