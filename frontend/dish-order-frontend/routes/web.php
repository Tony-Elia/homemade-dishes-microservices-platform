<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin routes
    Route::get('/admin/customers', [\App\Http\Controllers\Admin\AdminCustomerController::class, 'index'])->name('admin.customers');
    Route::get('/admin/seller-representatives', [\App\Http\Controllers\Admin\AdminSellerRepController::class, 'index'])->name('admin.seller_representatives');
    
    // Routes for AdminCompanyController
    Route::get('/admin/companies', [\App\Http\Controllers\Admin\AdminCompanyController::class, 'index'])->name('admin.companies');
    Route::post('/admin/companies', [\App\Http\Controllers\Admin\AdminCompanyController::class, 'store'])->name('admin.companies.store');
    Route::get('/admin/seller/create', [\App\Http\Controllers\Admin\AdminSellerRepController::class, 'create'])->name('admin.seller.create');
});

Route::middleware(['auth', 'role:seller'])->group(function () {
    // seller-only routes
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    // customer-only routes
    // Route::get('/customer/profile', [\App\Http\Controllers\Customer\CustomerProfileController::class, 'index'])->name('customer.profile');
    // Route::get('/customer/profile/edit', [\App\Http\Controllers\Customer\CustomerProfileController::class, 'edit'])->name('customer.profile.edit');
    // Route::patch('/customer/profile', [\App\Http\Controllers\Customer\CustomerProfileController::class, 'update'])->name('customer.profile.update');
    // Route::delete('/customer/profile', [\App\Http\Controllers\Customer\CustomerProfileController::class, 'destroy'])->name('customer.profile.destroy');
});


require __DIR__.'/auth.php';
