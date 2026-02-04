@echo off
REM Production Deployment Script for Fortress Lenders (Windows)
REM Run this script on your production server after pulling latest code

echo 🚀 Starting Production Deployment...
echo.

REM Step 1: Install/Update Dependencies
echo Step 1: Installing dependencies...
call composer install --no-dev --optimize-autoloader
call npm install

REM Step 2: Build Assets
echo Step 2: Building production assets...
call npm run build

REM Step 3: Remove Hot File
echo Step 3: Removing development files...
if exist "public\hot" (
    del /f "public\hot"
    echo ✅ Removed public/hot
) else (
    echo ℹ️  public/hot doesn't exist (good!)
)

REM Step 4: Clear Caches
echo Step 4: Clearing caches...
call php artisan config:clear
call php artisan cache:clear
call php artisan view:clear
call php artisan route:clear

REM Step 5: Optimize
echo Step 5: Optimizing application...
call php artisan config:cache
call php artisan route:cache
call php artisan view:cache

REM Step 6: Link Storage
echo Step 6: Linking storage...
call php artisan storage:link

echo.
echo ✅ Deployment Complete!
echo.
echo Verification checklist:
echo 1. Check that public\build\manifest.json exists
echo 2. Check that public\build\assets\ contains CSS and JS files
echo 3. Visit your site and check browser console for errors
echo 4. Test on multiple devices/browsers
echo.
pause
