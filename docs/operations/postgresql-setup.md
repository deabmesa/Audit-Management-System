# PostgreSQL Setup (Main + External Read-Only)

## Main database (writable)

```bash
psql -U postgres -f sql/postgresql/01_main_database_setup.sql
```

## External staff database read-only role

```bash
psql -U postgres -f sql/postgresql/02_external_staff_readonly_setup.sql
```

## Privilege verification

```sql
-- Run in psql
\du
\dp public.*
```

Ensure `staff_reader` has `SELECT` only and no DML privileges.
