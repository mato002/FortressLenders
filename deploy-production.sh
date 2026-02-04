#!/bin/bash

# Production Deployment Script for Fortress Lenders
# Run this script on your production server after pulling latest code

set -e  # Exit on error

echo "🚀 Starting Production Deployment..."
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Step 1: Install/Update Dependencies
echo -e "${GREEN}Step 1: Installing dependencies...${NC}"
composer install --no-dev --optimize-autoloader
npm install

# Step 2: Build Assets
echo -e "${GREEN}Step 2: Building production assets...${NC}"
npm run build

# Step 3: Remove Hot File
echo -e "${GREEN}Step 3: Removing development files...${NC}"
if [ -f "public/hot" ]; then
    rm -f public/hot
    echo "✅ Removed public/hot"
else
    echo "ℹ️  public/hot doesn't exist (good!)"
fi

# Step 4: Run Migrations (if needed)
echo -e "${GREEN}Step 4: Running migrations...${NC}"
read -p "Run database migrations? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan migrate --force
fi

# Step 5: Clear Caches
echo -e "${GREEN}Step 5: Clearing caches...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Step 6: Optimize
echo -e "${GREEN}Step 6: Optimizing application...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 7: Link Storage
echo -e "${GREEN}Step 7: Linking storage...${NC}"
php artisan storage:link || echo "Storage already linked"

# Step 8: Set Permissions
echo -e "${GREEN}Step 8: Setting permissions...${NC}"
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || echo "Note: Run chown manually if needed"

echo ""
echo -e "${GREEN}✅ Deployment Complete!${NC}"
echo ""
echo "Verification checklist:"
echo "1. Check that public/build/manifest.json exists"
echo "2. Check that public/build/assets/ contains CSS and JS files"
echo "3. Visit your site and check browser console for errors"
echo "4. Test on multiple devices/browsers"
echo ""
