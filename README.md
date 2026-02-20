# Audit Management System (Laravel + PostgreSQL)

A complete starter implementation for an Audit Management System with:

- Laravel (latest stable target in `composer.json`)
- PostgreSQL + `pgcrypto`
- UUID primary keys
- Authentication via Laravel Breeze (install command included)
- RBAC via Spatie Permission
- Audits + Issues modules
- Attachment uploads/downloads
- Dashboard metrics + Chart.js
- Email notifications
- Sanctum-protected REST API
- PHPUnit feature tests

## 1) Installation

```bash
cp .env.example .env
composer install
php artisan key:generate
```

### Configure PostgreSQL

Ensure `.env` contains:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=audit_management
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

### Install Breeze authentication

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
```

Breeze provides login, registration, password reset, email verification, and logout flows.

### Install RBAC + API auth

```bash
composer require spatie/laravel-permission
composer require laravel/sanctum
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

## 2) Database & Seed

```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

- `pgcrypto` extension is enabled by migration.
- UUID primary keys are used for domain tables.
- Roles seeded: `Admin`, `Auditor`, `Manager`.

## 3) Run Application

```bash
php artisan serve
```

Default seeded admin:
- Email: `admin@audit.local`
- Password: `password`

## 4) Modules Included

- **Audit module**: CRUD with status (`Planned`, `Ongoing`, `Completed`)
- **Issue module**: linked to audits with status (`Open`, `In Progress`, `Closed`)
- **Attachments**: upload for audits/issues, validated and stored in `public` disk
- **Dashboard**: counts + Chart.js visualization
- **Notifications**: new audit, issue assignment, overdue issue alerts

## 5) API (Sanctum)

- `POST /api/token` → create API token
- `GET|POST|PUT|DELETE /api/audits`
- `GET|POST|PUT|DELETE /api/issues`

Use Bearer token from `/api/token` for protected endpoints.

## 6) Testing

```bash
php artisan test
```

Feature tests are included for core audit/issue flows.
