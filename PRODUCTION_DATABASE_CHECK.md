# Production Database Check for Bulk Operations

## Required Database Columns

### `team_members` table must have:
1. `id` (primary key)
2. `name`
3. `role` (nullable)
4. `email` (nullable)
5. `phone` (nullable)
6. `photo_path` (nullable)
7. `linkedin_url` (nullable)
8. `bio` (nullable)
9. `display_order` (default: 0)
10. `is_active` (default: true)
11. `user_id` (nullable, foreign key to users table) - **IMPORTANT: This might be missing in production!**
12. `created_at` (timestamp)
13. `updated_at` (timestamp)

## Check Production Database

Run this SQL query on your production database to verify the schema:

```sql
DESCRIBE team_members;
-- or
SHOW COLUMNS FROM team_members;
```

## Required Migrations

Make sure these migrations have been run in production:

1. `2025_11_26_120000_create_team_members_table.php` - Creates the table
2. `2026_02_02_000001_add_user_id_to_team_members_and_candidates.php` - Adds user_id column

## Run Migrations on Production

```bash
php artisan migrate --force
```

## Verify Migration Status

```bash
php artisan migrate:status
```

Look for:
- `2025_11_26_120000_create_team_members_table` - Should show "Ran"
- `2026_02_02_000001_add_user_id_to_team_members_and_candidates` - Should show "Ran"

## If user_id Column is Missing

If the `user_id` column is missing, run:

```bash
php artisan migrate --path=database/migrations/2026_02_02_000001_add_user_id_to_team_members_and_candidates.php --force
```

Or manually add it:

```sql
ALTER TABLE team_members 
ADD COLUMN user_id BIGINT UNSIGNED NULL AFTER id,
ADD CONSTRAINT team_members_user_id_foreign 
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL;
```

## Common Production Issues

1. **Missing user_id column** - The migration might not have run
2. **Foreign key constraint issues** - Check if users table exists
3. **Different database engine** - MyISAM vs InnoDB (foreign keys need InnoDB)
4. **Permission issues** - Database user might not have ALTER TABLE permissions
