<?php

/**
 * Production Asset Fix Script
 * 
 * Run this script on your production server to fix asset issues:
 * php fix-production-assets.php
 */

echo "🔧 Fixing Production Assets...\n\n";

// 1. Remove hot file
$hotFile = __DIR__ . '/public/hot';
if (file_exists($hotFile)) {
    unlink($hotFile);
    echo "✅ Removed public/hot file\n";
} else {
    echo "ℹ️  public/hot file doesn't exist (good!)\n";
}

// 2. Check if build directory exists
$buildDir = __DIR__ . '/public/build';
if (!is_dir($buildDir)) {
    echo "❌ ERROR: public/build directory doesn't exist!\n";
    echo "   You need to run: npm run build\n";
    exit(1);
}

// 3. Check for manifest.json
$manifestFile = $buildDir . '/manifest.json';
if (!file_exists($manifestFile)) {
    echo "❌ ERROR: public/build/manifest.json doesn't exist!\n";
    echo "   You need to run: npm run build\n";
    exit(1);
}

echo "✅ Build directory exists\n";
echo "✅ Manifest file exists\n";

// 4. Check .env for APP_URL
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    if (strpos($envContent, 'APP_ENV=production') === false) {
        echo "⚠️  WARNING: APP_ENV might not be set to 'production' in .env\n";
    }
    if (strpos($envContent, 'APP_DEBUG=true') !== false) {
        echo "⚠️  WARNING: APP_DEBUG is set to true (should be false in production)\n";
    }
    if (strpos($envContent, 'APP_URL=http://localhost') !== false) {
        echo "⚠️  WARNING: APP_URL is set to localhost (should be your production domain)\n";
    }
}

echo "\n✅ Asset check complete!\n";
echo "\nNext steps:\n";
echo "1. Run: php artisan config:clear\n";
echo "2. Run: php artisan cache:clear\n";
echo "3. Run: php artisan view:clear\n";
echo "4. Run: php artisan optimize\n";
echo "\n";
