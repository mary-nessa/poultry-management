<?php

use App\Http\Controllers\AlertController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BirdController;
use App\Http\Controllers\BirdBatchController;
use App\Http\Controllers\BirdImmunizationController;
use App\Http\Controllers\BirdTransferController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\ChickPurchaseController;
use App\Http\Controllers\DailyActivityController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\EggTrayController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseLimitController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
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
    // Dashboard home page
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    // Profile page
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');



    // Resource routes for your controllers
    Route::resource('users', UserController::class);
    Route::resource('branches', BranchController::class);
    Route::post('/assign-branch', [BranchController::class, 'assignBranch'])->name('branches.assign');
    Route::resource('birds', BirdController::class);
    Route::resource('chick-purchases', ChickPurchaseController::class);
    Route::resource('bird-immunizations', BirdImmunizationController::class);
    Route::resource('feeds', FeedController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('equipments', EquipmentController::class);
    Route::resource('egg-trays', EggTrayController::class);
    Route::resource('products', ProductController::class);
    Route::resource('sales', SaleController::class);
    Route::resource('buyers', BuyerController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('transfers', TransferController::class);
    Route::post('/assign-roles', [UserController::class, 'assignRoles'])->name('roles.assign');
    Route::resource('daily-activities', DailyActivityController::class);
    Route::resource('expense-limits', ExpenseLimitController::class);
    Route::resource('alerts', AlertController::class);
    Route::resource('medicine', MedicineController::class);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
