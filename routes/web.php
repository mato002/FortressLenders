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
use App\Http\Controllers\FaqController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CeoMessageController;
use App\Http\Controllers\Admin\LoanApplicationController as AdminLoanApplicationController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\Admin\JobPostController;
use App\Http\Controllers\Admin\JobApplicationController as AdminJobApplicationController;
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

// Career Routes
Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
Route::get('/careers/{slug}', [CareerController::class, 'show'])->name('careers.show');
Route::get('/careers/{slug}/apply', [JobApplicationController::class, 'create'])->name('careers.apply');
Route::post('/careers/{slug}/apply', [JobApplicationController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('careers.apply.store');

// Dashboard Routes (Protected)
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user?->is_admin) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('profile.edit');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'editAdmin'])->name('profile');
        Route::get('/home', [AdminHomeSettingsController::class, 'edit'])->name('home.edit');
        Route::post('/home', [AdminHomeSettingsController::class, 'update'])->name('home.update');
        Route::get('/about', [AboutSettingsController::class, 'edit'])->name('about.edit');
        Route::post('/about', [AboutSettingsController::class, 'update'])->name('about.update');
        Route::get('/contact-page', [AdminContactSettingsController::class, 'edit'])->name('contact.edit');
        Route::post('/contact-page', [AdminContactSettingsController::class, 'update'])->name('contact.update');
        Route::get('/logo', [LogoSettingsController::class, 'edit'])->name('logo.edit');
        Route::post('/logo', [LogoSettingsController::class, 'update'])->name('logo.update');
        Route::resource('products', AdminProductController::class);
        Route::resource('team-members', AdminTeamMemberController::class)->except(['show']);
        Route::resource('branches', AdminBranchController::class)->except(['show']);
        Route::resource('contact-messages', AdminContactMessageController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::post('contact-messages/{contactMessage}/reply', [AdminContactMessageController::class, 'sendReply'])->name('contact-messages.reply');
        Route::resource('loan-applications', AdminLoanApplicationController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::resource('faqs', AdminFaqController::class)->except(['show']);
        Route::resource('posts', AdminPostController::class);
        Route::resource('ceo-messages', AdminCeoMessageController::class)->except(['show']);
        Route::resource('jobs', JobPostController::class);
        Route::resource('job-applications', AdminJobApplicationController::class)->only(['index', 'show']);
        Route::post('job-applications/{application}/review', [AdminJobApplicationController::class, 'review'])->name('job-applications.review');
        Route::post('job-applications/{application}/schedule-interview', [AdminJobApplicationController::class, 'scheduleInterview'])->name('job-applications.schedule-interview');
        Route::post('job-applications/{application}/update-status', [AdminJobApplicationController::class, 'updateStatus'])->name('job-applications.update-status');
        Route::post('interviews/{interview}/update-result', [AdminJobApplicationController::class, 'updateInterviewResult'])->name('interviews.update-result');
        Route::prefix('products/{product}/images')
            ->name('products.images.')
            ->group(function () {
                Route::post('reorder', [AdminProductImageController::class, 'reorder'])->name('reorder');
                Route::post('{image}/primary', [AdminProductImageController::class, 'makePrimary'])->name('primary');
                Route::delete('{image}', [AdminProductImageController::class, 'destroy'])->name('destroy');
            });
    });

require __DIR__.'/auth.php';
