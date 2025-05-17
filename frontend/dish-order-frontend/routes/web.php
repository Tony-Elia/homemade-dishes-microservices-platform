<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

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
    Route::get('/admin/companies/create', [\App\Http\Controllers\Admin\AdminCompanyController::class, 'create'])->name('admin.companies.create');
    Route::get('/admin/seller/create', [\App\Http\Controllers\Admin\AdminSellerRepController::class, 'create'])->name('admin.seller.create');
    Route::get('/admin/seller/assign/{company_id}', [\App\Http\Controllers\Admin\AdminSellerRepController::class, 'assign'])->name('admin.seller.assign');
});

Route::middleware(['auth', 'role:seller'])->group(function () {
    // Seller dashboard
    Route::get('/seller/dashboard', function () {
        return view('seller.dashboard'); // Create a Blade file for the seller dashboard
    })->name('seller.dashboard');

    // Routes for SellerDishController
    Route::get('/seller/dishes', [\App\Http\Controllers\Seller\SellerDishController::class, 'index'])->name('seller.dishes.index');
    Route::get('/seller/dishes/sold', [\App\Http\Controllers\Seller\SellerDishController::class, 'soldDishes'])->name('seller.dishes.sold');
    Route::get('/seller/dishes/create', [\App\Http\Controllers\Seller\SellerDishController::class, 'create'])->name('seller.dishes.create');
    Route::post('/seller/dishes', [\App\Http\Controllers\Seller\SellerDishController::class, 'store'])->name('seller.dishes.store');
    Route::get('/seller/dishes/{id}/edit', [\App\Http\Controllers\Seller\SellerDishController::class, 'edit'])->name('seller.dishes.edit');
    Route::put('/seller/dishes/{id}', [\App\Http\Controllers\Seller\SellerDishController::class, 'update'])->name('seller.dishes.update');
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/orders', [\App\Http\Controllers\Customer\CustomerOrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/customer/dishes', [\App\Http\Controllers\Customer\CustomerOrderController::class, 'showAll'])->name('customer.dishes.show_all');
    Route::post('/customer/cart/add', [\App\Http\Controllers\Customer\CustomerOrderController::class, 'addToCart'])->name('customer.cart.add');
    Route::get('/customer/cart', [\App\Http\Controllers\Customer\CustomerOrderController::class, 'viewCart'])->name('customer.cart');
    Route::post('/customer/cart/remove/{dishId}', [\App\Http\Controllers\Customer\CustomerOrderController::class, 'removeFromCart'])->name('customer.cart.remove');
    Route::post('/customer/cart/place-order', [\App\Http\Controllers\Customer\CustomerOrderController::class, 'placeOrder'])->name('customer.cart.place_order');
    Route::get('/customer/payment', [\App\Http\Controllers\Customer\CustomerOrderController::class, 'payment'])->name('customer.payment');
});


require __DIR__ . '/auth.php';
