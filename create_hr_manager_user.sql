-- SQL query to create HR Manager user
-- Email: hr@fortresslenders.com
-- Password: @Kenya1234
-- Role: hr_manager

-- EASIEST: Generate ready-to-run SQL with hash pre-filled:
--   php generate_admin_sql.php hr
-- Then pipe to MySQL or save and run.

-- ALTERNATIVE: Replace YOUR_PASSWORD_HASH_HERE with hash from: php artisan tinker → Hash::make('@Kenya1234')

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

-- Or use PHP script: php create_hr_manager.php  or  php update_admin_password.php (creates both)
