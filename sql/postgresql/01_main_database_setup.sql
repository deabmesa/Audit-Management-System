-- Main PostgreSQL database setup for PAMS writable database
CREATE ROLE audit_app LOGIN PASSWORD 'change_me';
CREATE DATABASE audit_main OWNER audit_app;

\connect audit_main

-- Restrict default public privileges
REVOKE ALL ON SCHEMA public FROM PUBLIC;
GRANT USAGE, CREATE ON SCHEMA public TO audit_app;

-- Optional app role split
CREATE ROLE audit_readonly;
GRANT CONNECT ON DATABASE audit_main TO audit_readonly;
GRANT USAGE ON SCHEMA public TO audit_readonly;
ALTER DEFAULT PRIVILEGES IN SCHEMA public
GRANT SELECT ON TABLES TO audit_readonly;
