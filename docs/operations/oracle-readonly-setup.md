# Oracle Read-Only User Setup (External DB)

Use a DBA account and create a dedicated read-only user for reports.

```sql
CREATE USER oracle_reader IDENTIFIED BY "change_me";
GRANT CREATE SESSION TO oracle_reader;
GRANT SELECT ANY TABLE TO oracle_reader;
```

If tighter controls are required, grant SELECT on specific schemas/tables only:

```sql
GRANT SELECT ON HR.STAFF_PROFILES TO oracle_reader;
GRANT SELECT ON HR.BRANCHES TO oracle_reader;
```

Do **not** grant any INSERT/UPDATE/DELETE/CREATE/ALTER privileges.
