<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\PaystackWebhookController;
use App\Http\Controllers\KorapayWebhookController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CandidateController as AdminCandidateController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', HomeController::class)->name('home');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/candidates/{candidate}', [CandidateController::class, 'show'])->name('candidates.show');

Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

// Voting routes (protected by VotingEnabled middleware)
Route::middleware('voting.enabled')->group(function () {
    Route::post('/vote', [VoteController::class, 'store'])->name('vote.store');
    Route::get('/vote/bank-transfer/{reference}', [VoteController::class, 'bankTransfer'])->name('vote.bank-transfer');
    Route::post('/vote/bank-transfer/{reference}/receipt', [VoteController::class, 'uploadReceipt'])->name('vote.upload-receipt');
});

// Payment callbacks (no voting check — must always be accessible)
Route::get('/vote/callback', [VoteController::class, 'callback'])->name('vote.callback');
Route::get('/vote/success/{reference}', [VoteController::class, 'success'])->name('vote.success');
Route::get('/vote/pending/{reference}', [VoteController::class, 'pending'])->name('vote.pending');
Route::get('/vote/failed', [VoteController::class, 'failed'])->name('vote.failed');

// Vote amount calculation API
Route::post('/api/calculate-amount', [VoteController::class, 'calculateAmount'])->name('api.calculate-amount');

// Leaderboard API (for real-time polling)
Route::get('/api/leaderboard', [LeaderboardController::class, 'apiGlobal'])->name('api.leaderboard');
Route::get('/api/leaderboard/{category}', [LeaderboardController::class, 'apiCategory'])->name('api.leaderboard.category');

// Share tracking API
Route::post('/api/candidates/{candidate}/share', [CandidateController::class, 'trackShare'])->name('api.candidate.share');

// Paystack webhook (excluded from CSRF)
Route::post('/webhook/paystack', [PaystackWebhookController::class, 'handle'])->name('webhook.paystack');

// Korapay webhook (excluded from CSRF)
Route::post('/webhook/korapay', [KorapayWebhookController::class, 'handle'])->name('webhook.korapay');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('categories', AdminCategoryController::class)->except(['show'])->parameters(['categories' => 'category:id']);
    Route::resource('candidates', AdminCandidateController::class)->except(['show'])->parameters(['candidates' => 'candidate:id']);

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
    Route::post('/transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (from Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
