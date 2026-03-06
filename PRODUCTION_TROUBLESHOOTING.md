# Production Bulk Operations Troubleshooting Guide

## Common Issues When Bulk Operations Work Locally But Not in Production

### 1. **Clear All Caches**
Run these commands on your production server:
```bash
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
```

### 2. **Check JavaScript Console**
Open browser DevTools (F12) and check:
- Are there any JavaScript errors?
- Is the form submitting?
- Are the selected IDs being set correctly?
- Check Network tab - is the POST request being sent?

### 3. **CSRF Token Issues**
Check if CSRF token is being sent:
- Verify `@csrf` is in the form
- Check if meta tag `<meta name="csrf-token">` exists in layout
- Verify session cookies are working (check browser DevTools > Application > Cookies)

### 4. **Session Configuration**
Check `.env` file for session settings:
```env
SESSION_DRIVER=file  # or database, redis
SESSION_SECURE_COOKIE=true  # Should be true for HTTPS
SESSION_SAME_SITE=lax  # or none for cross-site
```

### 5. **Route Caching**
If routes are cached, clear and rebuild:
```bash
php artisan route:clear
php artisan route:cache
```

### 6. **File Permissions**
Ensure storage directories are writable:
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 7. **Check Production Logs**
```bash
tail -f storage/logs/laravel.log
```
Look for:
- CSRF token mismatches
- Validation errors
- Missing route errors
- Session errors

### 8. **JavaScript Asset Loading**
If using Vite/build tools:
- Ensure assets are compiled: `npm run build` or `npm run production`
- Check if JavaScript files are loading (Network tab)
- Verify no 404 errors for JS files

### 9. **PHP Version Differences**
Check PHP version matches:
```bash
php -v
```
Ensure production PHP version is compatible with Laravel 12.

### 10. **Database Connection**
Verify database connection is working:
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

### 11. **Check Browser Console for Errors**
Common JavaScript errors in production:
- CORS issues
- Mixed content (HTTP/HTTPS)
- CSP (Content Security Policy) blocking scripts

### 12. **Verify Form Submission**
Add temporary logging to see if request reaches server:
- Check Laravel logs after attempting bulk action
- Verify `bulkAction` method is being called
- Check if validation is passing

### 13. **Session Storage**
If using file sessions, check:
- `storage/framework/sessions` directory exists
- Directory is writable
- No disk space issues

### 14. **Environment Variables**
Verify `.env` has correct values:
- `APP_URL` matches your production domain
- `APP_ENV=production`
- `APP_DEBUG=false` (for security)
