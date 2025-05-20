<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::put('orders/status', [\App\Http\Controllers\Customer\CustomerOrderController::class, 'statusUpdated']);
