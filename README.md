# Enterprise Audit Management System (Laravel 10)

Enterprise-ready Audit Management System scaffold with modular PAMS and Staff Information subsystems.

## Key Capabilities

- Multi-database architecture:
  - Main PostgreSQL (read/write) for PAMS and platform data
  - External PostgreSQL (read-only)
  - External Oracle (read-only via `yajra/laravel-oci8`)
- Dashboard system selection UI with orange-themed cards
- Report Template Builder (SELECT-only SQL, Redis caching, pagination)
- Rules management (role-based + user-based report access)
- Check-In / Check-Out tracking with history
- Activity logging and evidence upload validation

## Environment Files

- `.env.example` for generic setup
- `.env.rhel8` for production-style RHEL8 deployment baseline

Create runtime `.env`:

```bash
cp .env.rhel8 .env
php artisan key:generate
```

## Vendor Dependencies

`vendor/` is intentionally not committed. Use:

```bash
composer install --no-dev --prefer-dist --optimize-autoloader
```

For air-gapped deployment, use scripts in `scripts/rhel8/`.

## Database Setup

- Main DB SQL: `sql/postgresql/01_main_database_setup.sql`
- External read-only DB SQL: `sql/postgresql/02_external_staff_readonly_setup.sql`
- Oracle read-only setup: `docs/operations/oracle-readonly-setup.md`

## Deployment (RHEL8)

See `docs/deployment-rhel8.md`.


## Runtime Preflight

Before running artisan in new environments:

```bash
./scripts/rhel8/php-preflight.sh
```

This detects PHP/OpenSSL runtime mismatch issues (including `OPENSSL_1_1_1` errors) and linked SSL libraries.
