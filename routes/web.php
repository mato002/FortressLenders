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
use App\Http\Controllers\Admin\TokenController as AdminTokenController;
use App\Http\Controllers\CookieConsentController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// Public Website Routes
Route::get('/', HomeController::class)->name('home');
Route::get('/about', AboutPageController::class)->name('about');

Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
Route::get('/careers/{jobPost:slug}', [CareerController::class, 'show'])->name('careers.show');

Route::get('/apply-loan', [LoanApplicationController::class, 'create'])->name('loan.apply');
Route::post('/apply-loan', [LoanApplicationController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('loan.apply.submit');
Route::post('/apply-loan/whatsapp-lead', [LoanApplicationController::class, 'storeCalculatorLead'])
    ->middleware('throttle:10,1')
    ->name('loan.apply.whatsapp-lead');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/news', [PostController::class, 'index'])->name('posts.index');
Route::get('/news/{post:slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('/ceo-message', [CeoMessageController::class, 'index'])->name('ceo-message');


// Team Onboarding (public form for company members to self-register)
Route::get('/team-onboarding', [\App\Http\Controllers\TeamOnboardingController::class, 'create'])->name('team.onboarding');
Route::post('/team-onboarding', [\App\Http\Controllers\TeamOnboardingController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('team.onboarding.store');
Route::get('/team-onboarding/success', [\App\Http\Controllers\TeamOnboardingController::class, 'success'])->name('team.onboarding.success');
// Legal / Terms
Route::view('/terms', 'terms')->name('terms');

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

// Two-Factor Authentication Routes
Route::middleware(['auth:web,candidate'])->group(function () {
    Route::get('/two-factor-auth', [\App\Http\Controllers\TwoFactorAuthController::class, 'index'])->name('two-factor-auth.index');
    Route::get('/two-factor-auth/setup', [\App\Http\Controllers\TwoFactorAuthController::class, 'setup'])->name('two-factor-auth.setup');
    Route::post('/two-factor-auth/confirm', [\App\Http\Controllers\TwoFactorAuthController::class, 'confirm'])->name('two-factor-auth.confirm');
    Route::get('/two-factor-auth/backup-codes', [\App\Http\Controllers\TwoFactorAuthController::class, 'backupCodes'])->name('two-factor-auth.backup-codes');
    Route::post('/two-factor-auth/disable', [\App\Http\Controllers\TwoFactorAuthController::class, 'disable'])->name('two-factor-auth.disable');
    Route::post('/two-factor-auth/verify', [\App\Http\Controllers\TwoFactorAuthController::class, 'verify'])->name('two-factor-auth.verify');
});

// Loan Application Timeline
Route::middleware(['auth', 'verified', 'admin', 'not.candidate'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/loan-applications/{loanApplication}/timeline', [\App\Http\Controllers\Admin\LoanApplicationController::class, 'timeline'])->name('loan-applications.timeline');
});
Route::get('/careers/{jobPost:slug}/apply', [JobApplicationController::class, 'create'])->name('careers.apply');
Route::post('/careers/{jobPost:slug}/apply', [JobApplicationController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('careers.apply.store');

// Application Status Routes (Public)
Route::get('/application/status/lookup', function() {
    return view('careers.application-status-lookup');
})->name('application.status.lookup');
Route::post('/application/lookup', [JobApplicationController::class, 'lookup'])->name('application.lookup');
Route::get('/application/{application}/status', [JobApplicationController::class, 'status'])->name('application.status');

// Aptitude Test Routes (block employees; candidates and guests allowed)
Route::middleware(['only.candidates'])->group(function () {
    Route::get('/aptitude-test/{application}', [\App\Http\Controllers\AptitudeTestController::class, 'show'])->name('aptitude-test.show');
    Route::post('/aptitude-test/{application}/submit', [\App\Http\Controllers\AptitudeTestController::class, 'submit'])->name('aptitude-test.submit');
    Route::get('/aptitude-test/{application}/results', [\App\Http\Controllers\AptitudeTestController::class, 'results'])->name('aptitude-test.results');
    Route::get('/aptitude-test/{application}/verify', [\App\Http\Controllers\AptitudeTestController::class, 'verify'])->name('aptitude-test.verify');
});

// Self Interview Routes (block employees; candidates and guests allowed)
Route::middleware(['only.candidates'])->group(function () {
    Route::get('/self-interview/{application}', [\App\Http\Controllers\SelfInterviewController::class, 'show'])->name('self-interview.show');
    Route::post('/self-interview/{application}/submit', [\App\Http\Controllers\SelfInterviewController::class, 'submit'])->name('self-interview.submit');
    Route::get('/self-interview/{application}/results', [\App\Http\Controllers\SelfInterviewController::class, 'results'])->name('self-interview.results');
});

// Dashboard Routes (Protected)
Route::get('/dashboard', function () {
    // Check if candidate is logged in
    if (auth()->guard('candidate')->check()) {
        return redirect()->route('candidate.dashboard');
    }
    
    // Check if portal employee is logged in (web guard, role employee)
    $user = auth()->user();
    if ($user && $user->role === 'employee') {
        return redirect()->route('candidate.dashboard');
    }
    
    // Check if staff (admin, hr, loan, editor, client) is logged in
    if ($user && (in_array($user->role, ['admin', 'hr_manager', 'loan_manager', 'editor']) || $user->isClient())) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('profile.edit');
})->middleware(['auth:web,candidate', 'verified'])->name('dashboard');

// Candidate Routes (candidate guard OR employee with linked candidate)
Route::middleware(['auth:web,candidate', 'verified', 'portal.candidate.or.employee'])->prefix('candidate')->name('candidate.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\CandidateDashboardController::class, 'index'])->name('dashboard');
    Route::get('/application/{application}', [\App\Http\Controllers\CandidateDashboardController::class, 'show'])->name('application.show');
    Route::get('/applications', [\App\Http\Controllers\CandidateDashboardController::class, 'applications'])->name('applications');
    Route::get('/notifications', [\App\Http\Controllers\Candidate\NotificationController::class, 'index'])->name('notifications');
    Route::get('/help', [\App\Http\Controllers\Candidate\HelpController::class, 'index'])->name('help');
    
    // Saved Jobs Routes
    Route::get('/saved-jobs', [\App\Http\Controllers\Candidate\SavedJobController::class, 'index'])->name('saved-jobs.index');
    Route::post('/jobs/{jobPost}/save', [\App\Http\Controllers\Candidate\SavedJobController::class, 'save'])->name('jobs.save');
    Route::delete('/saved-jobs/{jobPost}', [\App\Http\Controllers\Candidate\SavedJobController::class, 'unsave'])->name('saved-jobs.destroy');
    
    // Bio Data Routes
    Route::get('/bio-data', [\App\Http\Controllers\Candidate\BioDataController::class, 'index'])->name('bio-data.index');
    Route::post('/bio-data', [\App\Http\Controllers\Candidate\BioDataController::class, 'update'])->name('bio-data.update');

    // Candidate profile alias (separate named routes to avoid ambiguity)
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('candidate.profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('candidate.profile.update');
    
    // Documents Routes
    Route::resource('documents', \App\Http\Controllers\Candidate\DocumentController::class)->only(['index', 'destroy']);
    Route::post('/documents/upload', [\App\Http\Controllers\Candidate\DocumentController::class, 'upload'])->name('documents.upload');
    Route::get('/documents/{document}/download', [\App\Http\Controllers\Candidate\DocumentController::class, 'download'])->name('documents.download');
    
    // Appraisals Routes
    Route::resource('appraisals', \App\Http\Controllers\Candidate\AppraisalController::class)->only(['index', 'show']);
    Route::post('/appraisals/{appraisal}/acknowledge', [\App\Http\Controllers\Candidate\AppraisalController::class, 'acknowledge'])->name('appraisals.acknowledge');
});

// Profile routes (accessible by both candidates and employees)
Route::middleware(['auth:web,candidate'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Employee-only routes (web guard only)
Route::middleware(['auth:web'])->group(function () {
    Route::post('/profile/sessions/{sessionId}/revoke', [ProfileController::class, 'revokeSession'])->name('profile.sessions.revoke');
    Route::post('/profile/sessions/revoke-others', [ProfileController::class, 'revokeOtherSessions'])->name('profile.sessions.revoke-others');
});

Route::middleware(['auth', 'verified', 'admin', 'not.candidate'])
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
            
            // AI Prompts
            Route::get('/ai-prompts', [\App\Http\Controllers\Admin\AIPromptSettingsController::class, 'index'])->name('ai-prompts.index');
            Route::post('/ai-prompts', [\App\Http\Controllers\Admin\AIPromptSettingsController::class, 'update'])->name('ai-prompts.update');
            Route::post('/ai-prompts/reset', [\App\Http\Controllers\Admin\AIPromptSettingsController::class, 'reset'])->name('ai-prompts.reset');
            
            // Team Members
            Route::post('team-members/bulk-action', [AdminTeamMemberController::class, 'bulkAction'])->name('team-members.bulk-action');
            Route::post('team-members/{teamMember}/generate-login', [AdminTeamMemberController::class, 'generateLogin'])->name('team-members.generate-login');
            Route::post('team-members/{teamMember}/regenerate-login', [AdminTeamMemberController::class, 'regenerateLogin'])->name('team-members.regenerate-login');
            Route::post('team-members/{teamMember}/toggle-status', [AdminTeamMemberController::class, 'toggleStatus'])->name('team-members.toggle-status');
            Route::resource('team-members', AdminTeamMemberController::class)->parameters(['team-members' => 'teamMember']);
            
            // Branches
            Route::resource('branches', AdminBranchController::class)->except(['show']);
            Route::post('branches/{branch}/toggle-status', [AdminBranchController::class, 'toggleStatus'])->name('branches.toggle-status');
            
            // Activity Logs
            Route::resource('activity-logs', AdminActivityLogController::class)->only(['index', 'show']);
            Route::post('activity-logs/{activityLog}/block-ip', [AdminActivityLogController::class, 'blockIp'])->name('activity-logs.block-ip');
            Route::post('activity-logs/{activityLog}/ban-user', [AdminActivityLogController::class, 'banUser'])->name('activity-logs.ban-user');
            Route::post('activity-logs/{activityLog}/revoke-sessions', [AdminActivityLogController::class, 'revokeUserSessions'])->name('activity-logs.revoke-sessions');
            Route::post('blocked-ips/unblock', [AdminActivityLogController::class, 'unblockIp'])->name('blocked-ips.unblock');
            Route::post('users/{user}/unban', [AdminActivityLogController::class, 'unbanUser'])->name('users.unban');
        });
        
        Route::resource('products', AdminProductController::class);
        Route::post('products/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('products.toggle-status');
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
            
            // Company Management
            Route::resource('companies', \App\Http\Controllers\Admin\CompanyController::class);
            Route::post('companies/{company}/regenerate-api-key', [\App\Http\Controllers\Admin\CompanyController::class, 'regenerateApiKey'])->name('companies.regenerate-api-key');
            Route::post('companies/{company}/toggle-status', [\App\Http\Controllers\Admin\CompanyController::class, 'toggleStatus'])->name('companies.toggle-status');
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
        
        // Analytics Routes
        Route::get('analytics/dashboard', [\App\Http\Controllers\Admin\AnalyticsController::class, 'dashboard'])->name('analytics.dashboard');
        
        // Reports Routes
        Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.dashboard');
        Route::get('reports/job-applications', [\App\Http\Controllers\Admin\ReportController::class, 'jobApplicationsReport'])->name('reports.job-applications');
        Route::get('reports/loan-applications', [\App\Http\Controllers\Admin\ReportController::class, 'loanApplicationsReport'])->name('reports.loan-applications');
        Route::get('reports/export/job-applications', [\App\Http\Controllers\Admin\ReportController::class, 'exportJobApplications'])->name('reports.export-job-applications');
        Route::get('reports/export/loan-applications', [\App\Http\Controllers\Admin\ReportController::class, 'exportLoanApplications'])->name('reports.export-loan-applications');
        
        // Email Templates
        Route::resource('email-templates', \App\Http\Controllers\Admin\EmailTemplateController::class);
        Route::get('email-templates/{emailTemplate}/preview', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'preview'])->name('email-templates.preview');

        // Careers Routes - Accessible by Admin, HR Manager, and Clients
        Route::middleware('role:admin,hr_manager,client')->group(function () {
            // Candidates Management with Filtering
            Route::get('candidates', [\App\Http\Controllers\Admin\CandidateController::class, 'index'])->name('candidates.index');
            Route::get('candidates/filter', [\App\Http\Controllers\Admin\CandidateFilterController::class, 'index'])->name('candidates.filter');
            Route::get('candidates/{candidate}', [\App\Http\Controllers\Admin\CandidateController::class, 'show'])->name('candidates.show');
            
            Route::resource('jobs', JobPostController::class)->except(['destroy']);
            Route::post('jobs/{job}/toggle-status', [JobPostController::class, 'toggleStatus'])->name('jobs.toggle-status');
            Route::get('jobs/{job}/configure-sieving', [JobPostController::class, 'configureSieving'])->name('jobs.configure-sieving');
            Route::post('jobs/{job}/configure-sieving', [JobPostController::class, 'storeSieving'])->name('jobs.store-sieving');
            
            // Aptitude Test Management
            Route::resource('aptitude-test', \App\Http\Controllers\Admin\AptitudeTestController::class)->except(['show']);
            Route::post('aptitude-test/{question}/toggle-status', [\App\Http\Controllers\Admin\AptitudeTestController::class, 'toggleStatus'])->name('aptitude-test.toggle-status');
            Route::post('aptitude-test/bulk-activate', [\App\Http\Controllers\Admin\AptitudeTestController::class, 'bulkActivate'])->name('aptitude-test.bulk-activate');
            Route::post('aptitude-test/bulk-deactivate', [\App\Http\Controllers\Admin\AptitudeTestController::class, 'bulkDeactivate'])->name('aptitude-test.bulk-deactivate');
            Route::delete('aptitude-test/bulk-delete', [\App\Http\Controllers\Admin\AptitudeTestController::class, 'bulkDelete'])->name('aptitude-test.bulk-delete');

            // Self Interview Question Management
            Route::resource('self-interview', \App\Http\Controllers\Admin\SelfInterviewQuestionController::class)->except(['show']);
            Route::post('self-interview/{selfInterview}/toggle-status', [\App\Http\Controllers\Admin\SelfInterviewQuestionController::class, 'toggleStatus'])->name('self-interview.toggle-status');
            Route::post('self-interview/bulk-activate', [\App\Http\Controllers\Admin\SelfInterviewQuestionController::class, 'bulkActivate'])->name('self-interview.bulk-activate');
            Route::post('self-interview/bulk-deactivate', [\App\Http\Controllers\Admin\SelfInterviewQuestionController::class, 'bulkDeactivate'])->name('self-interview.bulk-deactivate');
            Route::delete('self-interview/bulk-delete', [\App\Http\Controllers\Admin\SelfInterviewQuestionController::class, 'bulkDelete'])->name('self-interview.bulk-delete');
            
            // Job Applications Routes
            Route::prefix('job-applications')->name('job-applications.')->group(function () {
                // Bulk actions must come before resource routes to avoid route conflicts
                Route::post('bulk-send-confirmation', [AdminJobApplicationController::class, 'sendBulkConfirmationEmails'])->name('bulk-send-confirmation');
                Route::post('bulk-update-status', [AdminJobApplicationController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
                Route::post('bulk-delete', [AdminJobApplicationController::class, 'bulkDelete'])->name('bulk-delete');
                Route::post('bulk-sieving', [AdminJobApplicationController::class, 'bulkSieving'])->name('bulk-sieving');
                Route::get('export', [AdminJobApplicationController::class, 'export'])->name('export');
                Route::get('calendar', [AdminJobApplicationController::class, 'interviewCalendar'])->name('calendar');
            });
            Route::get('job-applications/{application}/view-cv', [AdminJobApplicationController::class, 'viewCv'])->name('job-applications.view-cv');
            Route::get('job-applications/{application}/download-cv', [AdminJobApplicationController::class, 'downloadCv'])->name('job-applications.download-cv');
            Route::resource('job-applications', AdminJobApplicationController::class)->only(['index', 'show', 'destroy']);
            Route::post('job-applications/{application}/review', [AdminJobApplicationController::class, 'review'])->name('job-applications.review');
            Route::post('job-applications/{application}/resieve', [AdminJobApplicationController::class, 'resieve'])->name('job-applications.resieve');
            Route::post('job-applications/{application}/schedule-interview', [AdminJobApplicationController::class, 'scheduleInterview'])->name('job-applications.schedule-interview');
            Route::post('job-applications/{application}/update-status', [AdminJobApplicationController::class, 'updateStatus'])->name('job-applications.update-status');
            Route::post('job-applications/{application}/send-message', [AdminJobApplicationController::class, 'sendMessage'])->middleware('throttle:10,1')->name('job-applications.send-message');
            Route::post('job-applications/{application}/send-confirmation', [AdminJobApplicationController::class, 'sendConfirmationEmail'])->name('job-applications.send-confirmation');
            Route::post('job-applications/{application}/create-candidate-account', [AdminJobApplicationController::class, 'createCandidateAccount'])->name('job-applications.create-candidate-account');
            Route::post('job-applications/{application}/resend-candidate-credentials', [AdminJobApplicationController::class, 'resendCandidateCredentials'])->name('job-applications.resend-candidate-credentials');
            Route::get('job-applications/{application}/view-candidate-dashboard', [AdminJobApplicationController::class, 'viewCandidateDashboard'])->name('job-applications.view-candidate-dashboard');
            Route::get('job-applications/{application}/preview-aptitude-test', [AdminJobApplicationController::class, 'previewAptitudeTest'])->name('job-applications.preview-aptitude-test');
            Route::get('job-applications/{application}/preview-candidate-status', [AdminJobApplicationController::class, 'previewCandidateStatus'])->name('job-applications.preview-candidate-status');
            Route::post('job-applications/bulk-create-candidate-accounts', [AdminJobApplicationController::class, 'bulkCreateCandidateAccounts'])->name('job-applications.bulk-create-candidate-accounts');
            Route::post('interviews/{interview}/update-result', [AdminJobApplicationController::class, 'updateInterviewResult'])->name('interviews.update-result');
            Route::post('job-applications/{application}/parse-cv', [AdminJobApplicationController::class, 'parseCv'])->name('job-applications.parse-cv');
            Route::post('job-applications/{application}/analyze-with-ai', [AdminJobApplicationController::class, 'analyzeWithAI'])->name('job-applications.analyze-with-ai');
            Route::post('job-applications/{application}/process-cv-and-ai', [AdminJobApplicationController::class, 'processCvAndAI'])->name('job-applications.process-cv-and-ai');
        });
        
        // Token Management Routes - Accessible by Admin
        Route::middleware('role:admin')->group(function () {
            Route::get('tokens', [AdminTokenController::class, 'index'])->name('tokens.index');
            Route::get('tokens/usage', [AdminTokenController::class, 'usage'])->name('tokens.usage');
            Route::get('tokens/purchases', [AdminTokenController::class, 'purchases'])->name('tokens.purchases');
            Route::post('tokens/purchases', [AdminTokenController::class, 'createPurchase'])->name('tokens.purchases.create');
            Route::post('tokens/allocate', [AdminTokenController::class, 'allocate'])->name('tokens.allocate');
            Route::get('tokens/balance', [AdminTokenController::class, 'balance'])->name('tokens.balance');
            Route::get('tokens/stats', [AdminTokenController::class, 'stats'])->name('tokens.stats');
        });
        Route::prefix('products/{product}/images')
            ->name('products.images.')
            ->group(function () {
                Route::post('reorder', [AdminProductImageController::class, 'reorder'])->name('reorder');
                Route::post('{image}/primary', [AdminProductImageController::class, 'makePrimary'])->name('primary');
                Route::delete('{image}', [AdminProductImageController::class, 'destroy'])->name('destroy');
            });

        // Temporary maintenance route to clear caches from browser (admin only).
        // Visit /admin/maintenance/clear-caches once in production, then remove this route.
        Route::get('/maintenance/clear-caches', function () {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');

            return response()->json([
                'status' => 'ok',
                'message' => 'Application caches cleared successfully.',
            ]);
        })->middleware('role:admin')->name('maintenance.clear-caches');
    });

require __DIR__.'/auth.php';
