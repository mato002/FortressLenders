-- SQL query to create/update admin user
-- Email: admin@fortresslenders.com
-- Password: @Kenya1234
-- Role: admin

-- EASIEST: Generate ready-to-run SQL with hash pre-filled (no manual steps):
--   php generate_admin_sql.php admin
-- Then pipe to MySQL or save to a file and run.

-- ALTERNATIVE (manual hash): Generate hash with: php artisan tinker → Hash::make('@Kenya1234')
-- Then replace YOUR_PASSWORD_HASH_HERE below with that hash.

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

-- Or create/update user directly in the database via PHP (no SQL needed):
-- php update_admin_password.php

