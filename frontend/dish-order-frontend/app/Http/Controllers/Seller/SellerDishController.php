<?php
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\JavaEeApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerDishController extends Controller
{
    protected $api;

    public function __construct(JavaEeApiService $api)
    {
        $this->api = $api;
    }

    // View offered dishes
    public function index(Request $request)
    {
        $headers = [
            'X-Company-Id' => $this->getSellerCompanyId(),
        ];

        $dishes = $this->api->get('/dish-inventory-service/api/dishes', [], $headers);

        // Handle API error
        if (isset($dishes['error'])) {
            return redirect()->back()->withErrors(['dishes' => $dishes['error']]);
        }

        // Ensure $dishes is always an array
        $dishes = is_array($dishes) ? $dishes : [];

        return view('seller.dishes.index', compact('dishes'));
    }

    // View sold dishes with customer & shipping info
    public function soldDishes(Request $request)
    {
        $headers = [
            'X-Company-Id' => $this->getSellerCompanyId(),
        ];
        $soldDishes = $this->api->get('/order-payment-service/api/orders', [], $headers);

        // Handle API error
        if (isset($soldDishes['error'])) {
            return redirect()->back()->withErrors(['soldDishes' => $soldDishes['error']]);
        }

        // Ensure $soldDishes is always an array
        $soldDishes = is_array($soldDishes) ? $soldDishes : [];

        return view('seller.dishes.sold', compact('soldDishes'));
    }

    // Show form to create a new dish
    public function create()
    {
        return view('seller.dishes.create');
    }

    // Save a new dish
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'description' => 'required|string',
            'calories' => 'required|integer|min:0',
        ]);

        $payload = [
            "name" => $data['name'],
            "description" => $data['description'],
            "calories" => $data['calories'],
            "price" => $data['price'],
            "companyId" => $this->getSellerCompanyId(),
            "quantity" => $data['quantity']
        ];

        $response = $this->api->post('/dish-inventory-service/api/dishes', $payload);

        if (isset($response['error'])) {
            return redirect()->back()->withErrors(['dish' => $response['error']])->withInput();
        }

        return redirect()->route('seller.dishes.index')->with('status', 'Dish added successfully!');
    }

    // Show edit form for a dish
    public function edit($id)
    {
        $dish = $this->api->get("/dish-inventory-service/api/dishes/{$id}");

        // Handle API error
        if (isset($dish['error'])) {
            return redirect()->back()->withErrors(['dish' => $dish['error']]);
        }

        return view('seller.dishes.edit', compact('dish'));
    }

    // Update a dish
    public function update(Request $request, $id)
    {
        $headers = [
            'X-Company-Id' => $this->getSellerCompanyId(),
        ];
        $data = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'description' => 'required|string',
            'calories' => 'required|integer|min:0',
        ]);

        $response = $this->api->put("/dish-inventory-service/api/dishes/{$id}", $data, $headers);

        if (isset($response['error'])) {
            return redirect()->back()->withErrors(['dish' => $response['error']])->withInput();
        }

        return redirect()->route('seller.dishes.index')->with('status', 'Dish updated successfully!');
    }

    private function getSellerCompanyId() {
        return $this->api->get("/user-management-service/api/users/" . Auth::user()->email)['company']['id'];
    }
}
