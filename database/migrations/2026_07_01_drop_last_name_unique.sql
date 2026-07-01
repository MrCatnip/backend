USE cms_bro;

-- Drop the UNIQUE constraint on users.last_name.
-- MySQL/MariaDB name the auto-created unique index after the column ("last_name").
ALTER TABLE users DROP INDEX last_name;
