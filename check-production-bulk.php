<?php
/**
 * Production Diagnostic Script for Bulk Operations
 * 
 * Run this on your production server to check for issues:
 * php check-production-bulk.php
 */

echo "=== Production Bulk Operations Diagnostic ===\n\n";

// 1. Check database connection
echo "1. Checking database connection...\n";
try {
    $pdo = new PDO(
        "mysql:host=" . env('DB_HOST') . ";dbname=" . env('DB_DATABASE'),
        env('DB_USERNAME'),
        env('DB_PASSWORD')
    );
    echo "   ✓ Database connection successful\n\n";
} catch (PDOException $e) {
    echo "   ✗ Database connection failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// 2. Check team_members table structure
echo "2. Checking team_members table structure...\n";
try {
    $stmt = $pdo->query("DESCRIBE team_members");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $requiredColumns = ['id', 'name', 'role', 'email', 'phone', 'photo_path', 'linkedin_url', 'bio', 'display_order', 'is_active', 'user_id', 'created_at', 'updated_at'];
    $existingColumns = array_column($columns, 'Field');
    
    $missing = array_diff($requiredColumns, $existingColumns);
    
    if (empty($missing)) {
        echo "   ✓ All required columns exist\n";
    } else {
        echo "   ✗ Missing columns: " . implode(', ', $missing) . "\n";
        echo "   Run migration: php artisan migrate --force\n";
    }
    echo "\n";
} catch (PDOException $e) {
    echo "   ✗ Error checking table: " . $e->getMessage() . "\n\n";
}

// 3. Check if table has data
echo "3. Checking team_members data...\n";
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM team_members");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "   ✓ Found {$result['count']} team member(s)\n\n";
} catch (PDOException $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n\n";
}

// 4. Check storage permissions
echo "4. Checking storage permissions...\n";
$storagePath = __DIR__ . '/storage';
$cachePath = __DIR__ . '/bootstrap/cache';

if (is_writable($storagePath)) {
    echo "   ✓ Storage directory is writable\n";
} else {
    echo "   ✗ Storage directory is NOT writable\n";
    echo "   Run: chmod -R 775 storage\n";
}

if (is_writable($cachePath)) {
    echo "   ✓ Cache directory is writable\n";
} else {
    echo "   ✗ Cache directory is NOT writable\n";
    echo "   Run: chmod -R 775 bootstrap/cache\n";
}
echo "\n";

// 5. Check session configuration
echo "5. Checking session configuration...\n";
$sessionDriver = env('SESSION_DRIVER', 'file');
echo "   Session driver: {$sessionDriver}\n";

if ($sessionDriver === 'file') {
    $sessionPath = __DIR__ . '/storage/framework/sessions';
    if (is_dir($sessionPath) && is_writable($sessionPath)) {
        echo "   ✓ Session directory exists and is writable\n";
    } else {
        echo "   ✗ Session directory missing or not writable\n";
    }
}
echo "\n";

// 6. Check route exists
echo "6. Checking route configuration...\n";
echo "   Run: php artisan route:list --name=team-members.bulk-action\n";
echo "   Should show: POST admin/team-members/bulk-action\n\n";

// 7. Recommendations
echo "=== Recommendations ===\n";
echo "1. Clear all caches: php artisan optimize:clear\n";
echo "2. Run migrations: php artisan migrate --force\n";
echo "3. Check browser console (F12) for JavaScript errors\n";
echo "4. Check Laravel logs: tail -f storage/logs/laravel.log\n";
echo "5. Verify CSRF token is in form: Check page source for <input name=\"_token\">\n";
echo "6. Check if JavaScript files are loading (Network tab in browser)\n";
