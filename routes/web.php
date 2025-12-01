<?php

use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ContactSettingsController as AdminContactSettingsController;
use App\Http\Controllers\Admin\HomeSettingsController as AdminHomeSettingsController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductImageController as AdminProductImageController;
use App\Http\Controllers\Admin\TeamMemberController as AdminTeamMemberController;
use App\Http\Controllers\Admin\BranchController as AdminBranchController;
use App\Http\Controllers\AboutPageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AboutSettingsController;
use Illuminate\Support\Facades\Route;

// Public Website Routes
Route::get('/', HomeController::class)->name('home');
Route::get('/about', AboutPageController::class)->name('about');

Route::get('/products', [ProductController::class, 'index'])->name('products');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

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
        Route::resource('products', AdminProductController::class);
        Route::resource('team-members', AdminTeamMemberController::class)->except(['show']);
        Route::resource('branches', AdminBranchController::class)->except(['show']);
        Route::resource('contact-messages', AdminContactMessageController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::prefix('products/{product}/images')
            ->name('products.images.')
            ->group(function () {
                Route::post('reorder', [AdminProductImageController::class, 'reorder'])->name('reorder');
                Route::post('{image}/primary', [AdminProductImageController::class, 'makePrimary'])->name('primary');
                Route::delete('{image}', [AdminProductImageController::class, 'destroy'])->name('destroy');
            });
    });

require __DIR__.'/auth.php';
