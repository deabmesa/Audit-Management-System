# Audit Management System (Laravel + PostgreSQL)

This repository contains a Laravel-based, Blade-driven web Audit Management System with role-based access control for **Admin**, **Auditor**, and **Manager**.

## Features

- Authentication flow (login using email/username, remember me, logout, change password)
- Role-aware sidebar with collapsible submenus and active-state highlighting
- Modules for:
  - Develop Task
  - Pre-Audit Work
  - Audit Fieldwork
  - Audit Report
  - Management Report
  - User and Administrator management pages
- Audit findings/issues tracking (status, responsible person, due date)
- Attachments schema for audits/issues
- Issue export endpoint (`/exports/issues/csv` and `/exports/issues/pdf`)
- Dashboard summary cards and simple chart visualization
- Activity logging table and helper model method
- PostgreSQL-first configuration with UUID primary keys in all domain tables

## Technology Stack

- Laravel 12 (latest stable target)
- Blade templates + Tailwind CSS (CDN)
- PostgreSQL

## Setup

1. Install dependencies:
   ```bash
   composer install
   ```
2. Copy environment file and update PostgreSQL credentials:
   ```bash
   cp .env.example .env
   ```
3. Ensure these values in `.env`:
   ```dotenv
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=audit_management
   DB_USERNAME=postgres
   DB_PASSWORD=password
   ```
4. Generate app key and run migrations + seeders:
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```
5. Start local server:
   ```bash
   php artisan serve
   ```

## Sample Seeded Users

| Role | Login | Password |
|---|---|---|
| Admin | `admin` or `admin@example.com` | `password123` |
| Auditor | `auditor` or `auditor@example.com` | `password123` |
| Manager | `manager` or `manager@example.com` | `password123` |

## Notes

- Role middleware alias: `role`
- Admin-only routes: User management + Administrator pages
- CSRF + validation are enforced on authentication/password flows
