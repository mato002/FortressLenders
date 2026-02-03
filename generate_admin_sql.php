<?php

/**
 * Generates ready-to-run SQL for creating/updating Admin and/or HR Manager users
 * with the password hash pre-filled. No manual hash replacement needed.
 *
 * Usage:
 *   php generate_admin_sql.php           → SQL for both admin + hr_manager
 *   php generate_admin_sql.php admin    → SQL for admin only
 *   php generate_admin_sql.php hr        → SQL for hr_manager only
 *   php generate_admin_sql.php both      → SQL for both (same as no arg)
 *
 * Then run the output against your database, e.g.:
 *   php generate_admin_sql.php | mysql -u root -p fortresslenders
 *   php generate_admin_sql.php both > users.sql
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Hash;

$password = '@Kenya1234';
$hash = Hash::make($password);

$which = strtolower($argv[1] ?? 'both');
if (!in_array($which, ['admin', 'hr', 'both'], true)) {
    $which = 'both';
}

$adminSql = <<<SQL
-- Admin user (generated with hash)
INSERT INTO `users` (
    `name`, `email`, `password`, `email_verified_at`, `is_admin`, `is_banned`, `role`, `created_at`, `updated_at`
) VALUES (
    'Fortress Admin',
    'admin@fortresslenders.com',
    '{$hash}',
    NOW(), 1, 0, 'admin', NOW(), NOW()
) ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `password` = VALUES(`password`),
    `is_admin` = VALUES(`is_admin`),
    `role` = VALUES(`role`),
    `updated_at` = NOW();

SQL;

$hrSql = <<<SQL
-- HR Manager user (generated with hash)
INSERT INTO `users` (
    `name`, `email`, `password`, `email_verified_at`, `is_admin`, `is_banned`, `role`, `created_at`, `updated_at`
) VALUES (
    'HR Manager',
    'hr@fortresslenders.com',
    '{$hash}',
    NOW(), 0, 0, 'hr_manager', NOW(), NOW()
) ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `password` = VALUES(`password`),
    `is_admin` = VALUES(`is_admin`),
    `role` = VALUES(`role`),
    `updated_at` = NOW();

SQL;

if ($which === 'admin') {
    echo $adminSql;
} elseif ($which === 'hr') {
    echo $hrSql;
} else {
    echo $adminSql . $hrSql;
}
