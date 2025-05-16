<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::put('/orders/status', function(Request $request) {
    $orderId = $request->input('orderId');
    $status = $request->input('status');

    if (session('last_order_id') == $orderId) {
        session(['last_order_status' => $status]);
        return response()->json(['message' => 'Order status updated in session.']);
    } else {
        return response()->json(['error' => 'Order not found in session.'], 404);
    }
});