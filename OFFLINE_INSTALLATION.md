# Offline Linux Deployment Guide

## 1) Prerequisites on target server
- Linux server
- PHP 8.1+
- Extensions: `pdo_pgsql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`
- PostgreSQL 13+

## 2) Prepare package on an online build machine
Because this runtime environment may block Packagist access, prepare the package once on a connected machine:

```bash
composer install --no-dev --optimize-autoloader
```

Then copy the **entire** project folder to the offline server, including:
- `vendor/`
- `public/build/`
- `database/migrations/`
- `database/seeders/`
- `.env.example`

## 3) Configure database
Create PostgreSQL DB and user, then configure `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=audit_management
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

## 4) Run deployment script
```bash
chmod +x deploy.sh
./deploy.sh
```

## 5) What deploy.sh does
1. Creates `.env` from `.env.example` when missing
2. Generates APP_KEY
3. Sets `storage` and `bootstrap/cache` permissions
4. Clears caches
5. Optimizes app and caches config/routes
6. Runs migrations
7. Seeds demo data
8. Starts Laravel server on `0.0.0.0:8000`

## 6) Demo credentials
- Admin: `admin@audit.local` / `password123`
- Auditor: `auditor@audit.local` / `password123`
- Reviewer: `reviewer@audit.local` / `password123`

## Notes
- Replace placeholder Bootstrap files in `public/css/bootstrap.min.css` and `public/js/bootstrap.bundle.min.js` with the official Bootstrap 5 artifacts in production packaging.

## Troubleshooting
- If you see an error about incomplete Composer dependencies, the full `vendor/` directory was not packaged (`vendor/autoload.php` and `vendor/composer/autoload_real.php` are required).
- On a connected Linux build machine, run:
  ```bash
  composer install --no-dev --optimize-autoloader
  ```
- If `vendor.tar.gz`, `vendor.tgz`, or `vendor.zip` is present, `./deploy.sh` will extract it automatically before bootstrapping.
- If `composer` is available, `./deploy.sh` will otherwise try to rebuild dependencies locally before failing.
- Re-copy the generated `vendor/` directory or a packaged vendor archive to the offline server and rerun `./deploy.sh` if local rebuild is unavailable or fails.
