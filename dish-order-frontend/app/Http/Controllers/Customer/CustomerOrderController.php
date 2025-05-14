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

    // Show form to make new order
    public function create()
    {
        $dishes = $this->api->get('/dishes/available');
        return view('customer.orders.create', compact('dishes'));
    }

    // Submit new order
    public function store(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.dish_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $response = $this->api->post('/orders', $data);

        if (isset($response['error'])) {
            return redirect()->back()->withErrors(['order' => $response['error']]);
        }

        return redirect()->route('customer.orders')->with('status', 'Order placed successfully!');
    }
}
