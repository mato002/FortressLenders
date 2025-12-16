<?php

use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ContactSettingsController as AdminContactSettingsController;
use App\Http\Controllers\Admin\HomeSettingsController as AdminHomeSettingsController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductImageController as AdminProductImageController;
use App\Http\Controllers\Admin\TeamMemberController as AdminTeamMemberController;
use App\Http\Controllers\Admin\BranchController as AdminBranchController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\CeoMessageController as AdminCeoMessageController;
use App\Http\Controllers\AboutPageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LoanApplicationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AboutSettingsController;
use App\Http\Controllers\Admin\LogoSettingsController;
use App\Http\Controllers\Admin\ApiSettingsController;
use App\Http\Controllers\Admin\GeneralSettingsController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CeoMessageController;
use App\Http\Controllers\Admin\LoanApplicationController as AdminLoanApplicationController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\Admin\JobPostController;
use App\Http\Controllers\Admin\JobApplicationController as AdminJobApplicationController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CookieConsentController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;

// Public Website Routes
Route::get('/', HomeController::class)->name('home');
Route::get('/about', AboutPageController::class)->name('about');

Route::get('/products', [ProductController::class, 'index'])->name('products');

Route::get('/apply-loan', [LoanApplicationController::class, 'create'])->name('loan.apply');
Route::post('/apply-loan', [LoanApplicationController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('loan.apply.submit');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/news', [PostController::class, 'index'])->name('posts.index');
Route::get('/news/{post:slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('/ceo-message', [CeoMessageController::class, 'index'])->name('ceo-message');

// Company Profile (PDF)
Route::get('/company-profile', function () {
    $path = base_path('Fortress company profile.pdf');

    if (! file_exists($path)) {
        abort(404);
    }

    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->name('company.profile');

// Cookie Consent Routes
Route::post('/cookie-consent/accept', [CookieConsentController::class, 'accept'])->name('cookie.consent.accept');
Route::post('/cookie-consent/reject', [CookieConsentController::class, 'reject'])->name('cookie.consent.reject');
Route::get('/cookie-consent/check', [CookieConsentController::class, 'check'])->name('cookie.consent.check');

// Newsletter Routes
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->middleware('throttle:5,1')
    ->name('newsletter.subscribe');
Route::post('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe'])
    ->middleware('throttle:5,1')
    ->name('newsletter.unsubscribe');

// Career Routes
Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
Route::get('/careers/{jobPost:slug}', [CareerController::class, 'show'])->name('careers.show');
Route::get('/careers/{jobPost:slug}/apply', [JobApplicationController::class, 'create'])->name('careers.apply');
Route::post('/careers/{jobPost:slug}/apply', [JobApplicationController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('careers.apply.store');

// Dashboard Routes (Protected)
Route::get('/dashboard', function () {
    $user = auth()->user();

    // All authenticated users with roles can access admin dashboard
    // Role-based permissions are enforced at the route level
    if ($user && in_array($user->role, ['admin', 'hr_manager', 'loan_manager', 'editor'])) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('profile.edit');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/sessions/{sessionId}/revoke', [ProfileController::class, 'revokeSession'])->name('profile.sessions.revoke');
    Route::post('/profile/sessions/revoke-others', [ProfileController::class, 'revokeOtherSessions'])->name('profile.sessions.revoke-others');
});

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/search', [\App\Http\Controllers\Admin\SearchController::class, 'search'])->name('search');
        Route::get('/profile', [ProfileController::class, 'editAdmin'])->name('profile');
        // Admin-only routes: Settings, Team, Branches
        Route::middleware('role:admin')->group(function () {
            // Settings
            Route::get('/home', [AdminHomeSettingsController::class, 'edit'])->name('home.edit');
            Route::post('/home', [AdminHomeSettingsController::class, 'update'])->name('home.update');
            Route::get('/about', [AboutSettingsController::class, 'edit'])->name('about.edit');
            Route::post('/about', [AboutSettingsController::class, 'update'])->name('about.update');
            Route::get('/contact-page', [AdminContactSettingsController::class, 'edit'])->name('contact.edit');
            Route::post('/contact-page', [AdminContactSettingsController::class, 'update'])->name('contact.update');
            Route::get('/logo', [LogoSettingsController::class, 'edit'])->name('logo.edit');
            Route::post('/logo', [LogoSettingsController::class, 'update'])->name('logo.update');
            Route::get('/api', [ApiSettingsController::class, 'edit'])->name('api.edit');
            Route::post('/api', [ApiSettingsController::class, 'update'])->name('api.update');
            Route::get('/general', [GeneralSettingsController::class, 'edit'])->name('general.edit');
            Route::post('/general', [GeneralSettingsController::class, 'update'])->name('general.update');
            
            // Team Members
            Route::resource('team-members', AdminTeamMemberController::class);
            
            // Branches
            Route::resource('branches', AdminBranchController::class)->except(['show']);
            
            // Activity Logs
            Route::resource('activity-logs', AdminActivityLogController::class)->only(['index', 'show']);
            Route::post('activity-logs/{activityLog}/block-ip', [AdminActivityLogController::class, 'blockIp'])->name('activity-logs.block-ip');
            Route::post('activity-logs/{activityLog}/ban-user', [AdminActivityLogController::class, 'banUser'])->name('activity-logs.ban-user');
            Route::post('activity-logs/{activityLog}/revoke-sessions', [AdminActivityLogController::class, 'revokeUserSessions'])->name('activity-logs.revoke-sessions');
            Route::post('blocked-ips/unblock', [AdminActivityLogController::class, 'unblockIp'])->name('blocked-ips.unblock');
            Route::post('users/{user}/unban', [AdminActivityLogController::class, 'unbanUser'])->name('users.unban');
        });
        
        Route::resource('products', AdminProductController::class);
        Route::post('contact-messages/bulk-update-status', [AdminContactMessageController::class, 'bulkUpdateStatus'])->name('contact-messages.bulk-update-status');
        Route::post('contact-messages/bulk-delete', [AdminContactMessageController::class, 'bulkDelete'])->name('contact-messages.bulk-delete');
        Route::get('contact-messages/export', [AdminContactMessageController::class, 'export'])->name('contact-messages.export');
        Route::resource('contact-messages', AdminContactMessageController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::post('contact-messages/{contactMessage}/reply', [AdminContactMessageController::class, 'sendReply'])->name('contact-messages.reply');
        Route::resource('faqs', AdminFaqController::class)->except(['show']);
        Route::resource('posts', AdminPostController::class);
        Route::resource('ceo-messages', AdminCeoMessageController::class)->except(['show']);
        
        // User Management - Only accessible by admins
        Route::middleware('role:admin')->group(function () {
            Route::resource('users', AdminUserController::class);
            Route::get('permissions', [\App\Http\Controllers\Admin\PermissionsController::class, 'index'])->name('permissions.index');
            Route::put('permissions', [\App\Http\Controllers\Admin\PermissionsController::class, 'update'])->name('permissions.update');
        });
        
        // Loan Applications Routes - Accessible by Admin and Loan Manager
        Route::middleware('role:admin,loan_manager')->group(function () {
            Route::prefix('loan-applications')->name('loan-applications.')->group(function () {
                // Bulk actions must come before resource routes to avoid route conflicts
                Route::post('bulk-send-confirmation', [AdminLoanApplicationController::class, 'sendBulkConfirmationEmails'])->name('bulk-send-confirmation');
                Route::post('bulk-update-status', [AdminLoanApplicationController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
                Route::post('bulk-delete', [AdminLoanApplicationController::class, 'bulkDelete'])->name('bulk-delete');
                Route::get('export', [AdminLoanApplicationController::class, 'export'])->name('export');
            });
            Route::resource('loan-applications', AdminLoanApplicationController::class)->only(['index', 'show', 'update', 'destroy']);
            Route::post('loan-applications/{loanApplication}/message', [AdminLoanApplicationController::class, 'sendMessage'])->name('loan-applications.message');
            Route::post('loan-applications/{loanApplication}/send-confirmation', [AdminLoanApplicationController::class, 'sendConfirmationEmail'])->name('loan-applications.send-confirmation');
        });
        
        // Careers Routes - Accessible by Admin and HR Manager
        Route::middleware('role:admin,hr_manager')->group(function () {
            Route::resource('jobs', JobPostController::class)->except(['destroy']);
            Route::post('jobs/{job}/toggle-status', [JobPostController::class, 'toggleStatus'])->name('jobs.toggle-status');
            
            // Job Applications Routes
            Route::prefix('job-applications')->name('job-applications.')->group(function () {
                // Bulk actions must come before resource routes to avoid route conflicts
                Route::post('bulk-send-confirmation', [AdminJobApplicationController::class, 'sendBulkConfirmationEmails'])->name('bulk-send-confirmation');
                Route::post('bulk-update-status', [AdminJobApplicationController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
                Route::post('bulk-delete', [AdminJobApplicationController::class, 'bulkDelete'])->name('bulk-delete');
                Route::get('export', [AdminJobApplicationController::class, 'export'])->name('export');
                Route::get('calendar', [AdminJobApplicationController::class, 'interviewCalendar'])->name('calendar');
            });
            Route::get('job-applications/{application}/view-cv', [AdminJobApplicationController::class, 'viewCv'])->name('job-applications.view-cv');
            Route::get('job-applications/{application}/download-cv', [AdminJobApplicationController::class, 'downloadCv'])->name('job-applications.download-cv');
            Route::resource('job-applications', AdminJobApplicationController::class)->only(['index', 'show', 'destroy']);
            Route::post('job-applications/{application}/review', [AdminJobApplicationController::class, 'review'])->name('job-applications.review');
            Route::post('job-applications/{application}/schedule-interview', [AdminJobApplicationController::class, 'scheduleInterview'])->name('job-applications.schedule-interview');
            Route::post('job-applications/{application}/update-status', [AdminJobApplicationController::class, 'updateStatus'])->name('job-applications.update-status');
            Route::post('job-applications/{application}/send-message', [AdminJobApplicationController::class, 'sendMessage'])->name('job-applications.send-message');
            Route::post('job-applications/{application}/send-confirmation', [AdminJobApplicationController::class, 'sendConfirmationEmail'])->name('job-applications.send-confirmation');
            Route::post('interviews/{interview}/update-result', [AdminJobApplicationController::class, 'updateInterviewResult'])->name('interviews.update-result');
        });
        Route::prefix('products/{product}/images')
            ->name('products.images.')
            ->group(function () {
                Route::post('reorder', [AdminProductImageController::class, 'reorder'])->name('reorder');
                Route::post('{image}/primary', [AdminProductImageController::class, 'makePrimary'])->name('primary');
                Route::delete('{image}', [AdminProductImageController::class, 'destroy'])->name('destroy');
            });
    });

require __DIR__.'/auth.php';
