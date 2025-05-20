<?php
namespace App\Http\Controllers\Customer;

use App\Events\OrderStatusUpdate;
use App\Http\Controllers\Controller;
use App\Http\Controllers\JavaEeApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CustomerOrderController extends Controller
{
    protected $api;

    public function __construct(JavaEeApiService $api)
    {
        $this->api = $api;
    }

    // View current and past orders
    public function index()
    {
        $mail = auth()->user()->email;
        $orders = $this->api->get("/order-payment-service/api/orders/{$mail}");

        // Handle API error
        if (isset($orders['error'])) {
            return redirect()->back()->withErrors(['orders' => $orders['error']]);
        }

        // Ensure $orders is always an array
        $orders = is_array($orders) ? $orders : [];
        foreach ($orders as &$order) {
            foreach ($order['items'] as &$item) {
                $item['dishName'] = $this->api->get("/dish-inventory-service/api/dishes/{$item['dishId']}")['name'];
            }
        }
        return view('customer.orders.index', compact('orders'));
    }

    public function showAll()
    {
        $dishes = $this->api->get("/dish-inventory-service/api/dishes/all");

        // Handle API error
        if (isset($dishes['error'])) {
            return redirect()->back()->withErrors(['dishes' => $dishes['error']]);
        }

        // Ensure $dishes is always an array
        $dishes = is_array($dishes) ? $dishes : [];

        return view('customer.dishes.show-all', compact('dishes'));
    }


    // Add dish to cart
    public function addToCart(Request $request)
    {
        $dishId = $request->input('dish_id');
        $quantity = $request->input('quantity', 1);

        $dish = $this->api->get("/dish-inventory-service/api/dishes/{$dishId}");

        // Handle API error
        if (isset($dish['error'])) {
            return redirect()->back()->withErrors(['cart' => $dish['error']]);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$dishId])) {
            $cart[$dishId]['quantity'] += $quantity;
        } else {
            $cart[$dishId] = [
                'id' => $dish['id'],
                'name' => $dish['name'],
                'price' => $dish['price'],
                'quantity' => $quantity,
            ];
        }

        session(['cart' => $cart]);

        return redirect()->back()->with('status', 'Dish added to cart!');
    }

    // View cart
    public function viewCart()
    {
        $cart = session('cart', []);
        $shippingCompanies = $this->api->get('/order-payment-service/api/shipping-companies');

        // Handle API error
        if (isset($shippingCompanies['error'])) {
            $shippingCompanies = [];
            return view('customer.orders.cart', compact('cart', 'shippingCompanies'))
                ->withErrors(['shipping' => $shippingCompanies['error']]);
        }

        $shippingCompanies = is_array($shippingCompanies) ? $shippingCompanies : [];

        return view('customer.orders.cart', compact('cart', 'shippingCompanies'));
    }

    // Remove item from cart
    public function removeFromCart($dishId)
    {
        $cart = session('cart', []);
        unset($cart[$dishId]);
        session(['cart' => $cart]);
        return redirect()->route('customer.cart')->with('status', 'Item removed from cart.');
    }

    // Place order (send cart to backend)
    public function placeOrder(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.cart')->withErrors(['cart' => 'Your cart is empty.']);
        }

        $shippingCompanyId = $request->input('shipping_company_id');

        $items = [];
        foreach ($cart as $item) {
            $items[] = [
                'dishId' => $item['id'],
                'quantity' => $item['quantity'],
                'priceAtPurchase' => $item['price'],
            ];
        }

        $payload = [
            'userEmail' => auth()->user()->email,
            'items' => $items,
            'shippingCompanyId' => $shippingCompanyId,
        ];

        $response = $this->api->post('/order-payment-service/api/orders', $payload);

        if (isset($response['error'])) {
            return redirect()->route('customer.cart')->withErrors(['order' => $response['error']]);
        }

        if (isset($response['id'])) {
            session(['last_order_id' => $response['id']]);
            session(['last_order_status' => strtolower($response['status'])]);
            session()->forget('cart');
        }

        session()->forget('cart');
        return redirect()->route('customer.orders.index')->with('status', 'Order sent, waiting for confirmation...');
    }

    public function statusUpdated(Request $request)
    {
        $orderId = $request->orderId;
        $status = $request->status;
        $msg = $request->msg;
        $userId = \App\Models\User::where('email', $request->userEmail)->first()->id;

        if($status) {
            Cache::put('order_update_' . $userId, ['order_id' => $orderId, 'message' => 'Order Placed successfully'], 10);
            $response = $this->payByOrderId($orderId);
            event(new OrderStatusUpdate($orderId, $status));
            Log::alert("Received response from the payment:". $response);
//            if($response != null && $response['error'])
//                Cache::put('order_update_' . $userId, ['order_id' => $orderId, 'message' => $response['error']], 10);
        } else
            Cache::put('order_update_' . $userId, ['order_id' => $orderId, 'message' => $msg], 10);

        \Illuminate\Support\Facades\Log::info("Received order status update for order: {$orderId} with status: {$status}, user: {$userId}, cache value: " . Cache::has('order_event_' . $userId));
        return response()->json("Status Updated Successfully");
    }

    public function payByOrderId($order_id)
    {
        return $this->api->get("/order-payment-service/api/pay/{$order_id}");
    }

    public function payment(Request $request)
    {
        $order_id = $request->order_id;
        $res = $this->payByOrderId($order_id);
        $response = $res['error'] ?? $res;
        return redirect()->route('customer.orders.index')->with('status', $response);
    }
}
