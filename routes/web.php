<?php

use App\Http\Controllers\AlertController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BirdController;
use App\Http\Controllers\BirdImmunizationController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\BreedController;
use App\Http\Controllers\ChickPurchaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\EggCollectionController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseLimitController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\FeedTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FeedingLogController;
use App\Http\Controllers\HealthCheckController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolePermissionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/authenticate', [LoginController::class, 'login'])->name('authenticate');

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Profile page
    Route::get('/profile', [UserController::class, 'index'])->name('profile');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');    
    Route::resource('roles', RolePermissionController::class);
    Route::get('/role-permissions', [RolePermissionController::class, 'splitView'])->name('roles.split');
    Route::post('/roles/{role}/assign-permissions', [RolePermissionController::class, 'assignPermissions'])->name('roles.assignPermissions');
});

Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/manager/dashboard', [DashboardController::class, 'manager'])->name('manager.dashboard');
});

Route::middleware(['auth', 'role:salesmanager'])->group(function () {
    Route::get('/salesmanager/dashboard', [DashboardController::class, 'salesManager'])->name('salesmanager.dashboard');
});

Route::middleware(['auth', 'role:worker'])->group(function () {
    Route::get('/worker/dashboard', [DashboardController::class, 'worker'])->name('worker.dashboard');
});

Route::middleware(['auth', 'permission:manage transfer'])->group(function () {
    Route::resource('transfers', TransferController::class);
});

Route::middleware(['auth', 'permission:manage expense'])->group(function () {
    Route::resource('expenses', ExpenseController::class);
});

Route::middleware(['auth', 'permission:manage sale'])->group(function () {
    Route::resource('sales', SaleController::class);
});

Route::middleware(['auth', 'permission:manage egg-collection'])->group(function () {
    Route::resource('egg-collections', EggCollectionController::class);
});

Route::middleware(['auth', 'permission:manage chick-purchase'])->group(function () {
    Route::resource('chick-purchases', ChickPurchaseController::class);
});

Route::middleware(['auth', 'permission:manage bird-immunization'])->group(function () {
    Route::resource('bird-immunizations', BirdImmunizationController::class);
});

Route::middleware(['auth', 'permission:manage feeding-log'])->group(function () {
    Route::resource('feeding-logs', FeedingLogController::class);
});

Route::middleware(['auth', 'permission:manage feed'])->group(function () {
    Route::resource('feeds', FeedController::class);
});

Route::middleware(['auth', 'permission:manage health-check'])->group(function () {
    Route::resource('health-checks', HealthCheckController::class);
});

Route::middleware(['auth', 'permission:manage supplier'])->group(function () {
    Route::resource('suppliers', SupplierController::class);
});

Route::middleware(['auth', 'permission:manage buyer'])->group(function () {
    Route::resource('buyers', BuyerController::class);
});

Route::middleware(['auth', 'permission:manage alert'])->group(function () {
    Route::resource('alerts', AlertController::class);
});

Route::middleware(['auth', 'permission:manage breed'])->group(function () {
    Route::resource('breeds', BreedController::class);
});

Route::middleware(['auth', 'permission:manage feed-type'])->group(function () {
    Route::resource('feedtypes', FeedTypeController::class);
});

Route::middleware(['auth', 'permission:manage branch'])->group(function () {
    Route::resource('branches', BranchController::class);
    Route::post('/assign-branch', [BranchController::class, 'assignBranch'])->name('branches.assign');
});

Route::middleware(['auth', 'permission:manage user'])->group(function () {
    Route::resource('users', UserController::class);
    Route::post('/assign-roles', [UserController::class, 'assignRoles'])->name('roles.assign');
    Route::post('/revoke-roles/{user_id}', [UserController::class, 'revokeRoles'])->name('roles.revoke');
});

Route::middleware(['auth', 'permission:manage bird'])->group(function () {
    Route::resource('birds', BirdController::class);
});

Route::middleware(['auth', 'permission:manage expense-limit'])->group(function () {
    Route::resource('expense-limits', ExpenseLimitController::class);
});

Route::middleware(['auth', 'permission:manage medicine'])->group(function () {
    Route::resource('medicine', MedicineController::class);
});

Route::middleware(['auth', 'permission:manage equipment'])->group(function () {
    Route::resource('equipments', EquipmentController::class);
});

Route::middleware(['auth', 'permission:manage product'])->group(function () {
    Route::resource('products', ProductController::class);
});

Route::middleware(['auth', 'permission:manage role'])->group(function () {
    Route::resource('roles', RolePermissionController::class);
    Route::get('/role-permissions', [RolePermissionController::class, 'splitView'])->name('roles.split');
    Route::post('/roles/{role}/assign-permissions', [RolePermissionController::class, 'assignPermissions'])->name('roles.assignPermissions');
});
