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
        $orders = $this->api->get('/orders/my-orders');
        return view('customer.orders.index', compact('orders'));
    }

    public function showAll()
    {
        $dishes = $this->api->get("/dish-inventory-service/api/dishes/all");
        return view('customer.dishes.show-all', compact('dishes'));
    }

    
    // Add dish to cart
    public function addToCart(Request $request)
    {
        $dishId = $request->input('dish_id');
        $quantity = $request->input('quantity', 1);

        // Get dish details (optional, for validation/display)
        $dish = $this->api->get("/dish-inventory-service/api/dishes/{$dishId}");

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
        return view('customer.orders.cart', compact('cart'));
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

        // Optionally, get shipping company ID from request or user profile
        $shippingCompanyId = $request->input('shipping_company_id'); // or set a default

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

        $response = $this->api->post('order-payment-service/api/orders', $payload);

        if (isset($response['error'])) {
            return redirect()->route('customer.cart')->withErrors(['order' => $response['error']]);
        }

        // After successful order placement
        if (isset($response['orderId'])) {
            session(['last_order_id' => $response['orderId']]);
            session(['last_order_status' => 'pending']);
        }

        session()->forget('cart');
        return redirect()->route('customer.orders')->with('status', 'Order sent, waiting for confirmation...');
    }
}
