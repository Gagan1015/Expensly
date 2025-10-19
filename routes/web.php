<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;

// Landing page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Guest routes (accessible only when not authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.post')
        ->middleware('throttle:6,1'); // Rate limiting: 6 attempts per minute
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard (updated to use DashboardController)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User settings
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Expense Management
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    Route::get('/expenses/export/csv', [ExpenseController::class, 'exportCsv'])->name('expenses.export.csv');
    Route::get('/expenses/reports', [ExpenseController::class, 'reports'])->name('expenses.reports');
    
// Category Management
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Budget Management
    Route::post('/budget/update', [DashboardController::class, 'updateBudget'])->name('budget.update');
});

// API routes for AJAX requests (outside web middleware to avoid CSRF on GET requests)
Route::middleware('auth')->prefix('api')->name('api.')->group(function () {
    Route::get('/expenses/chart-data', [ExpenseController::class, 'getChartData'])->name('expenses.chart-data');
    Route::get('/expenses/category-breakdown', [ExpenseController::class, 'getCategoryBreakdown'])->name('expenses.category-breakdown');
    Route::get('/expenses/{expense}', [ExpenseController::class, 'getExpenseData'])->name('expenses.data');
    Route::get('/categories/{category}', [CategoryController::class, 'getCategoryData'])->name('categories.data');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
});// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard (updated to use DashboardController)
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
    
    // User Management
    Route::resource('users', UserController::class);
    Route::post('users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
    
    // Admin Expense Management (view all users' expenses)
    Route::get('/expenses', [ExpenseController::class, 'adminIndex'])->name('expenses.index');
    Route::get('/expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    Route::get('/expenses/export/csv', [ExpenseController::class, 'adminExportCsv'])->name('expenses.export.csv');
    
    // Admin Category Management (manage global categories)
    Route::get('/categories', [CategoryController::class, 'adminIndex'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    
    // Admin Reports
    Route::get('/reports', [DashboardController::class, 'adminReports'])->name('reports');
    Route::get('/reports/export/csv', [DashboardController::class, 'exportReportsCsv'])->name('reports.export.csv');
    
    // Admin Settings/System Management
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');
});