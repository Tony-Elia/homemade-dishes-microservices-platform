<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\JavaEeApiService;
use Illuminate\Http\Request;

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
            $paymentDetails = $this->api->get("/order-payment-service/api/pay/{$response['id']}");
            if (isset($paymentDetails['error'])) {
                return redirect()->route('customer.cart')->withErrors(['payment' => $paymentDetails['error']]);
            }
        }

        session()->forget('cart');
        return redirect()->route('customer.orders.index')->with('status', 'Order sent, waiting for confirmation...');
    }

    public function payment(Request $request)
    {
        $response = $this->api->get("/order-payment-service/api/pay/{$request->query('order_id')}");
        $response = $response['error'] ?? $response;
        return redirect()->route('customer.orders.index')->with('status', $response);
    }
}
