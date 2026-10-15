<?php

use App\Http\Controllers\Admin\CuisineController as AdminCuisineController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MerchantController as AdminMerchantController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Customer\CateringController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\InvoiceController as CustomerInvoiceController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Merchant\DashboardController as MerchantDashboardController;
use App\Http\Controllers\Merchant\InvoiceController as MerchantInvoiceController;
use App\Http\Controllers\Merchant\MenuController;
use App\Http\Controllers\Merchant\OrderController as MerchantOrderController;
use App\Http\Controllers\Merchant\ProfileController as MerchantProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Route Web — Marketplace Katering
|--------------------------------------------------------------------------
| Tiga kelompok: publik (guest), portal merchant (role:merchant),
| dan portal customer/kantor (role:customer). Middleware "role" adalah
| alias dari App\Http\Middleware\EnsureUserHasRole (lihat bootstrap/app.php).
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// ── Publik / guest ────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ── Portal Merchant (Katering) ────────────────────────────────────────────
Route::middleware(['auth', 'role:merchant'])
    ->prefix('merchant')
    ->name('merchant.')
    ->group(function () {
        Route::get('/dashboard', [MerchantDashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [MerchantProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [MerchantProfileController::class, 'update'])->name('profile.update');

        // Route non-resource dideklarasikan sebelum resource.
        Route::patch('/menus/{menu}/toggle', [MenuController::class, 'toggleAvailable'])->name('menus.toggle');
        Route::resource('menus', MenuController::class)->except(['show']);

        Route::get('/orders', [MerchantOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [MerchantOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [MerchantOrderController::class, 'updateStatus'])->name('orders.updateStatus');

        Route::get('/invoices/{invoice}', [MerchantInvoiceController::class, 'show'])->name('invoices.show');
        Route::patch('/invoices/{invoice}/paid', [MerchantInvoiceController::class, 'markPaid'])->name('invoices.markPaid');
    });

// ── Portal Admin (akun dibuat via seeder, tidak bisa self-register) ──────
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/merchants', [AdminMerchantController::class, 'index'])->name('merchants.index');
        Route::get('/merchants/{merchant}', [AdminMerchantController::class, 'show'])->name('merchants.show');
        Route::patch('/merchants/{merchant}/toggle', [AdminMerchantController::class, 'toggleActive'])->name('merchants.toggle');

        Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');

        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');

        Route::get('/cuisines', [AdminCuisineController::class, 'index'])->name('cuisines.index');
        Route::post('/cuisines', [AdminCuisineController::class, 'store'])->name('cuisines.store');
        Route::put('/cuisines/{cuisine}', [AdminCuisineController::class, 'update'])->name('cuisines.update');
        Route::patch('/cuisines/{cuisine}/toggle', [AdminCuisineController::class, 'toggle'])->name('cuisines.toggle');
        Route::delete('/cuisines/{cuisine}', [AdminCuisineController::class, 'destroy'])->name('cuisines.destroy');
    });

// ── Portal Customer (Kantor) ──────────────────────────────────────────────
Route::middleware(['auth', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [CustomerProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');

        Route::get('/caterings', [CateringController::class, 'index'])->name('caterings.index');
        Route::get('/caterings/{catering}', [CateringController::class, 'show'])->name('caterings.show');
        Route::get('/caterings/{catering}/order', [CustomerOrderController::class, 'create'])->name('orders.create');
        Route::post('/caterings/{catering}/order', [CustomerOrderController::class, 'store'])->name('orders.store');

        Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');

        Route::get('/invoices/{invoice}', [CustomerInvoiceController::class, 'show'])->name('invoices.show');
    });
