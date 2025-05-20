<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\JavaEeApiService;

// You may need to resolve the service from the container
Route::put('/orders/status', function(Request $request) {
    $orderId = $request->input('orderId');
    $status = $request->input('status');

    if (session('last_order_id') == $orderId) {
        session(['last_order_status' => $status]);

        // If status is UNPAID, trigger the payment API
        if (strtoupper($status) === 'UNPAID') {
            // Resolve your API service
            $api = app(JavaEeApiService::class);
            $paymentResponse = $api->get("/order-payment-service/api/pay/{$orderId}");

            if (isset($paymentResponse['error'])) {
                return response()->json(['error' => $paymentResponse['error']], 500);
            }

            return response()->json([
                'message' => 'Order status updated and payment triggered.',
                'payment' => $paymentResponse
            ]);
        }

        return response()->json(['message' => 'Order status updated in session.']);
    } else {
        return response()->json(['error' => 'Order not found in session.'], 404);
    }
});
