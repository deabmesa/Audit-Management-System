# Enterprise Audit Management System (Laravel 10)

This repository provides an enterprise-ready Audit Management System architecture with:

- Multi-database setup: main PostgreSQL, external read-only PostgreSQL, and external read-only Oracle.
- Dashboard system selector (Staff Information + PAMS).
- Report template builder with SQL-based read-only report execution.
- Rule management (role/user/report permission mapping).
- Check-In / Check-Out module with audit history.
- Security controls (RBAC, activity logging, upload validation).
- Performance controls (Redis report caching, pagination).

## Modules

1. **Staff Information** (external PostgreSQL read-only)
2. **PAMS** (Audit Program, Findings, Recommendation Tracking, Follow-up Monitoring, Audit Reports, Evidence Upload)
3. **Report System**
4. **Rule Management**
5. **Check-In / Check-Out**

## Quick Start

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

## Example Reports

- Staff Listing Report
- Branch Staff Report
- Transaction Monitoring Report
- Audit Finding Report

All report SQL queries are enforced as `SELECT` only.
