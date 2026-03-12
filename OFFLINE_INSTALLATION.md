# Offline Linux Deployment Guide

## 1) Prerequisites on target server
- Linux server
- PHP 8.1+
- Extensions: `pdo_pgsql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`
- PostgreSQL 13+
- Web server optional (Nginx/Apache) if not using `php artisan serve`

## 2) Copy project package
Copy this full project folder to the offline server, including:
- `vendor/` (if available from online build machine)
- `public/build/`
- `database/migrations/`
- `database/seeders/`
- `.env.example`

## 3) Configure database
Create PostgreSQL DB and user, then update `.env`:
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

## 5) Demo credentials
- Admin: `admin@audit.local` / `password123`
- Auditor: `auditor@audit.local` / `password123`
- Reviewer: `reviewer@audit.local` / `password123`

## Notes
- If `vendor/` is missing, run `composer install --no-dev --optimize-autoloader` on an online build machine first, then copy the folder offline.
- Replace placeholder Bootstrap files in `public/css/bootstrap.min.css` and `public/js/bootstrap.bundle.min.js` with official Bootstrap 5 distribution for production.
