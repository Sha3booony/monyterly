<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\StatusPageController;
use App\Http\Controllers\AdminController;

// Landing page
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Language Switch
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Public Status Page
Route::get('/status/{userId}', [StatusPageController::class, 'show'])->name('status.page');
Route::get('/api/status/{userId}', [StatusPageController::class, 'api'])->name('status.api');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard Routes (authenticated)
Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

    // Monitors
    Route::resource('monitors', MonitorController::class);
    Route::post('monitors/{monitor}/toggle', [MonitorController::class, 'togglePause'])->name('monitors.toggle');
    Route::post('monitors/{monitor}/check-now', [MonitorController::class, 'checkNow'])->name('monitors.check-now');
    Route::get('monitors/{monitor}/export-issues', [MonitorController::class, 'exportIssues'])->name('monitors.export-issues');

    // Issues
    Route::get('issues', [IssueController::class, 'index'])->name('issues.index');
    Route::get('issues/{issue}', [IssueController::class, 'show'])->name('issues.show');
    Route::patch('issues/{issue}/status', [IssueController::class, 'updateStatus'])->name('issues.update-status');
});

// Admin Routes (admin only)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/{user}', [AdminController::class, 'userDetail'])->name('admin.user-detail');
    Route::post('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('admin.toggle-admin');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.delete-user');
    Route::get('/monitors', [AdminController::class, 'monitors'])->name('admin.monitors');
    Route::get('/issues', [AdminController::class, 'issues'])->name('admin.issues');
    Route::get('/logs', [AdminController::class, 'logs'])->name('admin.logs');
});
