-- External PostgreSQL read-only role for Staff Information module
CREATE ROLE staff_reader LOGIN PASSWORD 'change_me';

-- Run in external staff DB
\connect staff_directory

GRANT CONNECT ON DATABASE staff_directory TO staff_reader;
GRANT USAGE ON SCHEMA public TO staff_reader;
GRANT SELECT ON ALL TABLES IN SCHEMA public TO staff_reader;
ALTER DEFAULT PRIVILEGES IN SCHEMA public
GRANT SELECT ON TABLES TO staff_reader;

-- Ensure no write privileges
REVOKE INSERT, UPDATE, DELETE, TRUNCATE, REFERENCES, TRIGGER
ON ALL TABLES IN SCHEMA public FROM staff_reader;
