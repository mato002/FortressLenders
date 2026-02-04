<?php

/**
 * Admin Routes Verification Script
 * 
 * This script checks if all routes referenced in admin views actually exist.
 * Run: php verify-admin-routes.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$routeCollection = \Illuminate\Support\Facades\Route::getRoutes();
$allRoutes = [];

foreach ($routeCollection as $route) {
    $name = $route->getName();
    if ($name && strpos($name, 'admin.') === 0) {
        $allRoutes[$name] = true;
    }
}

// Routes that should exist based on views
$requiredRoutes = [
    'admin.dashboard',
    'admin.profile',
    'admin.products.index',
    'admin.products.create',
    'admin.products.store',
    'admin.products.show',
    'admin.products.edit',
    'admin.products.update',
    'admin.products.destroy',
    'admin.products.toggle-status',
    'admin.team-members.index',
    'admin.team-members.create',
    'admin.team-members.store',
    'admin.team-members.show',
    'admin.team-members.edit',
    'admin.team-members.update',
    'admin.team-members.destroy',
    'admin.team-members.toggle-status',
    'admin.branches.index',
    'admin.branches.create',
    'admin.branches.store',
    'admin.branches.edit',
    'admin.branches.update',
    'admin.branches.destroy',
    'admin.branches.toggle-status',
    'admin.jobs.index',
    'admin.jobs.create',
    'admin.jobs.store',
    'admin.jobs.show',
    'admin.jobs.edit',
    'admin.jobs.update',
    'admin.jobs.toggle-status',
    'admin.job-applications.index',
    'admin.job-applications.show',
    'admin.job-applications.destroy',
    'admin.job-applications.review',
    'admin.job-applications.resieve',
    'admin.job-applications.bulk-sieving',
    'admin.job-applications.bulk-send-confirmation',
    'admin.job-applications.bulk-update-status',
    'admin.job-applications.bulk-delete',
    'admin.job-applications.bulk-create-candidate-accounts',
    'admin.job-applications.export',
    'admin.job-applications.calendar',
    'admin.job-applications.view-cv',
    'admin.job-applications.download-cv',
    'admin.job-applications.send-message',
    'admin.job-applications.send-confirmation',
    'admin.job-applications.create-candidate-account',
    'admin.job-applications.resend-candidate-credentials',
    'admin.job-applications.view-candidate-dashboard',
    'admin.job-applications.preview-candidate-status',
    'admin.candidates.index',
    'admin.candidates.show',
    'admin.aptitude-test.index',
    'admin.aptitude-test.create',
    'admin.aptitude-test.store',
    'admin.aptitude-test.edit',
    'admin.aptitude-test.update',
    'admin.aptitude-test.destroy',
    'admin.aptitude-test.toggle-status',
    'admin.self-interview.index',
    'admin.self-interview.create',
    'admin.self-interview.store',
    'admin.self-interview.edit',
    'admin.self-interview.update',
    'admin.self-interview.destroy',
    'admin.self-interview.toggle-status',
    'admin.contact-messages.index',
    'admin.contact-messages.show',
    'admin.contact-messages.update',
    'admin.contact-messages.destroy',
    'admin.contact-messages.reply',
    'admin.contact-messages.bulk-update-status',
    'admin.contact-messages.bulk-delete',
    'admin.contact-messages.export',
    'admin.loan-applications.index',
    'admin.loan-applications.show',
    'admin.loan-applications.update',
    'admin.loan-applications.destroy',
    'admin.loan-applications.message',
    'admin.loan-applications.send-confirmation',
    'admin.loan-applications.bulk-send-confirmation',
    'admin.loan-applications.bulk-update-status',
    'admin.loan-applications.bulk-delete',
    'admin.loan-applications.export',
    'admin.users.index',
    'admin.users.create',
    'admin.users.store',
    'admin.users.edit',
    'admin.users.update',
    'admin.users.destroy',
    'admin.companies.index',
    'admin.companies.create',
    'admin.companies.store',
    'admin.companies.show',
    'admin.companies.edit',
    'admin.companies.update',
    'admin.companies.destroy',
    'admin.companies.toggle-status',
    'admin.activity-logs.index',
    'admin.activity-logs.show',
    'admin.activity-logs.block-ip',
    'admin.activity-logs.ban-user',
    'admin.activity-logs.revoke-sessions',
    'admin.tokens.index',
    'admin.tokens.usage',
    'admin.tokens.purchases',
    'admin.permissions.index',
    'admin.permissions.update',
    'admin.home.edit',
    'admin.home.update',
    'admin.about.edit',
    'admin.about.update',
    'admin.contact.edit',
    'admin.contact.update',
    'admin.logo.edit',
    'admin.logo.update',
    'admin.api.edit',
    'admin.api.update',
    'admin.general.edit',
    'admin.general.update',
    'admin.ai-prompts.index',
    'admin.ai-prompts.update',
    'admin.ai-prompts.reset',
    'admin.posts.index',
    'admin.posts.create',
    'admin.posts.store',
    'admin.posts.show',
    'admin.posts.edit',
    'admin.posts.update',
    'admin.posts.destroy',
    'admin.faqs.index',
    'admin.faqs.create',
    'admin.faqs.store',
    'admin.faqs.edit',
    'admin.faqs.update',
    'admin.faqs.destroy',
    'admin.ceo-messages.index',
    'admin.ceo-messages.create',
    'admin.ceo-messages.store',
    'admin.ceo-messages.edit',
    'admin.ceo-messages.update',
    'admin.ceo-messages.destroy',
];

echo "🔍 Verifying Admin Routes...\n\n";

$missing = [];
$found = [];

foreach ($requiredRoutes as $routeName) {
    if (isset($allRoutes[$routeName])) {
        $found[] = $routeName;
    } else {
        $missing[] = $routeName;
    }
}

if (empty($missing)) {
    echo "✅ All required routes are defined!\n";
    echo "Found: " . count($found) . " routes\n\n";
} else {
    echo "❌ Missing Routes (" . count($missing) . "):\n";
    foreach ($missing as $route) {
        echo "   - {$route}\n";
    }
    echo "\n✅ Found Routes (" . count($found) . "):\n";
    foreach ($found as $route) {
        echo "   - {$route}\n";
    }
}

echo "\n";
