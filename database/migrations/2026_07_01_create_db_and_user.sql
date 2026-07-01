-- Run this as a privileged user (root):
--   mysql -u root -p < database/migrations/2026_07_01_create_db_and_user.sql
--
-- Creates the database and the application user with least-privilege access.
-- Credentials must match .env (DB_NAME / DB_USER / DB_PASSWORD).

CREATE DATABASE IF NOT EXISTS cms_bro
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- The app connects via TCP (DB_HOST=127.0.0.1), but grant both hosts so it
-- works whether the driver resolves the connection as 127.0.0.1 or localhost.
CREATE USER IF NOT EXISTS 'cms_user'@'localhost' IDENTIFIED BY 'cms_password';
CREATE USER IF NOT EXISTS 'cms_user'@'127.0.0.1' IDENTIFIED BY 'cms_password';

GRANT SELECT, INSERT, UPDATE, DELETE ON cms_bro.* TO 'cms_user'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON cms_bro.* TO 'cms_user'@'127.0.0.1';

FLUSH PRIVILEGES;
