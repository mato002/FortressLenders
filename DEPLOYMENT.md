# Production Deployment Guide

## Critical Steps for Production Deployment

### 1. Build Assets for Production

**IMPORTANT:** You MUST build assets for production before deploying:

```bash
npm install
npm run build
```

This creates optimized CSS and JS files in `public/build/` directory.

### 2. Remove Development Files

After building, ensure these files are NOT in production:

```bash
# Remove the hot file if it exists (this tells Vite to use dev server)
rm -f public/hot

# Or on Windows:
del public\hot
```

### 3. Environment Configuration

Ensure your `.env` file has these settings for production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Make sure APP_URL matches your actual domain
```

### 4. Clear All Caches

After deployment, run these commands:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize
```

### 5. Verify Asset Files

Check that these files exist in `public/build/`:
- `manifest.json`
- `assets/app-*.css`
- `assets/app-*.js`

### 6. Set Correct Permissions

```bash
# Ensure storage and cache directories are writable
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 7. Complete Deployment Checklist

```bash
# 1. Pull latest code
git pull origin main

# 2. Install/update dependencies
composer install --no-dev --optimize-autoloader
npm install

# 3. Build production assets
npm run build

# 4. Remove hot file
rm -f public/hot

# 5. Run migrations (if needed)
php artisan migrate --force

# 6. Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 7. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Link storage (if not already linked)
php artisan storage:link
```

## Troubleshooting Styling Issues

### Issue: Styles work on one device but not others

**Common Causes:**

1. **Assets not built for production**
   - Solution: Run `npm run build` and deploy the `public/build/` directory

2. **Hot file exists in production**
   - Solution: Delete `public/hot` file

3. **APP_URL incorrect**
   - Solution: Set `APP_URL=https://yourdomain.com` in `.env`

4. **Browser cache**
   - Solution: Hard refresh (Ctrl+Shift+R or Cmd+Shift+R)

5. **CDN or proxy caching old assets**
   - Solution: Clear CDN cache or wait for cache to expire

6. **Missing manifest.json**
   - Solution: Ensure `public/build/manifest.json` exists after running `npm run build`

### Quick Fix Script

Create a file `deploy.sh`:

```bash
#!/bin/bash
echo "Building assets..."
npm run build

echo "Removing hot file..."
rm -f public/hot

echo "Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Deployment complete!"
```

Make it executable: `chmod +x deploy.sh`

## Verification

After deployment, verify:

1. Visit your site and check browser console for 404 errors on CSS/JS files
2. Check Network tab to ensure assets are loading from `/build/assets/`
3. Verify `public/build/manifest.json` exists and is readable
4. Test on multiple devices/browsers

## Notes

- Never commit `public/hot` or `public/build/` to git (they're in .gitignore)
- Always run `npm run build` on the server or include built assets in deployment
- The `@vite()` directive automatically uses built assets in production
- In development, Vite dev server is used (when `public/hot` exists)
- In production, built assets from `public/build/` are used
