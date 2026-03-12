# Audit Management System (Laravel 10 + PostgreSQL)

Offline-ready Audit Management System with RBAC (`Admin`, `Auditor`, `Reviewer`) and modules for:

- User Management
- Audit Planning
- Audit Fieldwork
- Audit Findings
- Follow-up Tracking
- Dashboard analytics

## Project structure delivered
- Laravel-style app bootstrapping (`artisan`, `bootstrap/app.php`, `public/index.php`)
- Models, controllers, middleware, routes
- Blade views with Bootstrap-styled UI
- PostgreSQL migrations with FKs and indexes
- Seeders + demo data
- Offline deployment script (`deploy.sh`)
- Offline installation guide (`OFFLINE_INSTALLATION.md`)

## Quick start
1. Copy `.env.example` to `.env` and configure PostgreSQL.
2. Ensure `vendor/` (Composer dependencies) exists.
3. Run:
   ```bash
   ./deploy.sh
   ```

## Demo users
- `admin@audit.local` / `password123`
- `auditor@audit.local` / `password123`
- `reviewer@audit.local` / `password123`


## Troubleshooting
- Error `vendor/autoload.php` missing means Composer dependencies were not included in the deployment package.
- Build dependencies on a connected Linux machine with:
  ```bash
  composer install --no-dev --optimize-autoloader
  ```
- Copy the resulting `vendor/` folder into this project before running `./deploy.sh`.
