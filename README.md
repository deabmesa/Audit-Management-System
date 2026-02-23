# Audit Management System (Laravel 10+)

Production-ready Laravel starter for an Audit Management System with:

- **PostgreSQL** as the primary transactional database
- **Oracle** as a **read-only** reporting source for PAMS
- Authentication + RBAC + check-in/check-out enforcement

---

## 1) Prerequisites

Before you start, install:

1. **PHP 8.1+**
2. **Composer 2+**
3. **PostgreSQL 13+**
4. **Oracle client access** (for read-only reporting DB)
5. (Optional) Node.js 18+ if you want to build frontend assets

---

## 2) Clone and enter project

```bash
git clone <your-repo-url> Audit-Management-System
cd Audit-Management-System
```

---

## 3) Install dependencies

```bash
composer install
```

> If Oracle package install fails, ensure OCI/Oracle client libraries are correctly configured for your environment.

---

## 4) Create application environment file

```bash
cp .env.example .env
```

Open `.env` and set:

- `APP_ENV`, `APP_URL`, `APP_DEBUG`
- PostgreSQL values (`DB_*`)
- Oracle values (`DB_ORACLE_*`)

You can use the provided `.env.example` keys as reference.

---

## 5) Generate application key

```bash
php artisan key:generate
```

---

## 6) Configure database connections

The app is configured for two databases in `config/database.php`:

1. **`pgsql`** (default)
   - Stores users, check-ins, audit engagements, and transactional data.
2. **`oracle`** (secondary)
   - Used by PAMS reporting.
   - Intended read-only access.

Confirm your credentials in `.env` before migration.

---

## 7) Run migrations (PostgreSQL)

```bash
php artisan migrate
```

This creates primary system tables such as:

- `users`
- `checkin_logs`
- `audit_engagements`

---

## 8) Prepare storage for evidence uploads

```bash
php artisan storage:link
```

Uploaded Staff Info evidence files are stored under the public disk.

---

## 9) Create an initial user/admin account

If no seeder is present yet, create a user through Tinker:

```bash
php artisan tinker
```

Then run:

```php
\App\Models\User::create([
    'name' => 'System Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('ChangeMeNow!'),
    'role' => 'admin',
]);
```

Supported route roles include `admin`, `auditor`, `manager`, `viewer`.

---

## 10) Start the application

```bash
php artisan serve
```

Open the app in your browser:

- `http://127.0.0.1:8000`

---

## 11) Login and required check-in flow

After login:

1. User is redirected to **Check-In** page.
2. User must check-in before accessing dashboard/modules.
3. System logs check-in metadata (IP, machine name when resolvable, session info).
4. User must check-out before logout.

---

## 12) Module usage

### A. Staff Info (PostgreSQL)

Use the Staff Info menu to:

1. Create audit engagements
2. Record audit activities
3. Store working notes
4. Upload evidence
5. Update and close engagements

### B. PAMS (Oracle read-only)

Use PAMS menu to:

1. View Oracle report rows
2. Filter by keyword/status
3. Paginate results
4. Export PDF / Excel

> The Oracle model intentionally blocks write operations (`save/delete`) to enforce read-only behavior in application code.

---

## 13) SQL reference files

For DBA collaboration and validation:

- PostgreSQL sample schema: `database/postgresql_schema.sql`
- Oracle report examples: `database/oracle_query_examples.sql`

---

## 14) Quick validation checklist

Run these checks after setup:

```bash
php artisan route:list
php artisan config:clear
php artisan cache:clear
```

If PHP extensions and DB connectivity are correct, the app should be ready for use.

---

## 15) Security and production notes

1. Keep `APP_DEBUG=false` in production.
2. Use secure cookies and HTTPS at reverse proxy/load balancer.
3. Rotate credentials and avoid committing real secrets.
4. Grant **read-only** Oracle DB privileges to the reporting user.
5. Use queue/workers and centralized logging for high-volume deployments.

---

## 16) Troubleshooting

### Composer dependency errors
- Verify PHP version and required extensions.
- Re-run with verbose output:
  ```bash
  composer install -vvv
  ```

### Oracle connection errors
- Verify `DB_ORACLE_*` values.
- Validate Oracle service/SID and network accessibility.
- Ensure Oracle client/OCI8 prerequisites are installed.

### Migration failures
- Confirm PostgreSQL DB/user privileges.
- Check `DB_CONNECTION=pgsql` is active in `.env`.

---

## 17) Core architecture summary

- **Controllers**: `app/Http/Controllers`
- **Middleware**: `app/Http/Middleware`
- **Models**: `app/Models`
- **Services**: `app/Services`
- **Views**: `resources/views`
- **Routes**: `routes/web.php`
- **Migrations**: `database/migrations`

This keeps MVC separation clean and production maintainability high.
