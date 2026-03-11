# Audit Management System (Laravel 10)

Audit Management System scaffold with modular PAMS and Staff Information subsystems.

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


## GitHub + Codespaces + PostgreSQL

This repository includes out-of-the-box support for GitHub Codespaces and PostgreSQL:

- Codespaces devcontainer: `.devcontainer/devcontainer.json`
- Post-create bootstrap script: `scripts/codespaces/post-create.sh`
- PostgreSQL service definition: `docker-compose.postgres.yml`
- GitHub Actions CI: `.github/workflows/ci.yml`

### Run locally with PostgreSQL

```bash
docker compose -f docker-compose.postgres.yml up -d
cp .env.example .env
composer install
```

### Codespaces behavior

When Codespace is created, it will:

1. Copy `.env.example` to `.env` (if missing)
2. Run `composer install` (best effort)
3. Start PostgreSQL with Docker Compose
4. Wait until PostgreSQL is healthy


## Local Runtime Quick Fix (`php: command not found`)

If running `php artisan serve` fails with `php: command not found`:

```bash
./scripts/dev/install-php.sh
./scripts/dev/artisan.sh key:generate
./scripts/dev/run-serve.sh
```

What these scripts do:

- `install-php.sh`: installs PHP 8.2+ for Ubuntu (`apt`) or RHEL8 (`dnf`)
- `run-serve.sh`: validates PHP + `artisan` before starting local server

You can still run directly (once PHP exists):

```bash
php artisan key:generate
php artisan serve --host=0.0.0.0 --port=8000
```

