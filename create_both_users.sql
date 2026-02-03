-- SQL queries to create both Admin and HR Manager users
-- Both use password: @Kenya1234

-- EASIEST: Generate ready-to-run SQL with hash pre-filled (no copy/paste):
--   php generate_admin_sql.php
--   php generate_admin_sql.php both
-- Then: php generate_admin_sql.php | mysql -u root -p your_database
--   or: php generate_admin_sql.php > users.sql  then run users.sql

-- ============================================
-- Admin User (replace YOUR_PASSWORD_HASH_HERE if not using generator)
-- ============================================
INSERT INTO `users` (
    `name`, 
    `email`, 
    `password`, 
    `email_verified_at`, 
    `is_admin`, 
    `is_banned`, 
    `role`, 
    `created_at`, 
    `updated_at`
) VALUES (
    'Fortress Admin',
    'admin@fortresslenders.com',
    '$2y$12$YOUR_PASSWORD_HASH_HERE',
    NOW(),
    1,
    0,
    'admin',
    NOW(),
    NOW()
) ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `password` = VALUES(`password`),
    `is_admin` = VALUES(`is_admin`),
    `role` = VALUES(`role`),
    `updated_at` = NOW();

-- ============================================
-- HR Manager User (use same hash as above if manual)
-- ============================================
INSERT INTO `users` (
    `name`, 
    `email`, 
    `password`, 
    `email_verified_at`, 
    `is_admin`, 
    `is_banned`, 
    `role`, 
    `created_at`, 
    `updated_at`
) VALUES (
    'HR Manager',
    'hr@fortresslenders.com',
    '$2y$12$YOUR_PASSWORD_HASH_HERE',
    NOW(),
    0,
    0,
    'hr_manager',
    NOW(),
    NOW()
) ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `password` = VALUES(`password`),
    `is_admin` = VALUES(`is_admin`),
    `role` = VALUES(`role`),
    `updated_at` = NOW();

-- ============================================
-- EASIER: Use PHP to create/update users directly (no SQL)
-- ============================================
-- php update_admin_password.php

