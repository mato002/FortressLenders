# Clear Laravel Cache on Production Server

## The Problem
Laravel caches configuration files for performance. When you update your `.env` file, you must clear the cache for the changes to take effect.

## Solution - Run These Commands on Your Production Server

SSH into your production server and run:

```bash
# Navigate to your application directory
cd /home/fortress/FotressLenders  # or wherever your app is located

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optional: If you're using opcache, restart PHP-FPM
# sudo systemctl restart php8.2-fpm  # or php-fpm, depending on your setup
```

## After Clearing Cache

Your application should now use the correct database credentials from your `.env` file:
- Database: `fortress_FotressLenders`
- Username: `fortress_Fortress`
- Password: `@Mato22@`

## Verify It Worked

After running the commands, refresh your website. The error should be gone.

## Note About Database Name

I noticed your database name in `.env` is `fortress_FotressLenders` (with "Fotress" - note the typo). Make sure this matches exactly what your database is actually named on the server. If your database is actually named `fortress_FortressLenders` (with "Fortress"), update your `.env` file accordingly.
