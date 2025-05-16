<?php
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\JavaEeApiService;
use Illuminate\Http\Request;

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
        // Retrieve the company ID (from the authenticated user or request headers)
        $companyId = auth()->user()->company_id ?? $request->header('X-Company-Id');

        // Add the company ID to the headers
        $headers = [
            'X-Company-Id' => $companyId,
        ];

        // Make the API call with the headers
        $dishes = $this->api->get('/dish-inventory-service/api/dishes', [], $headers);

        return view('seller.dishes', compact('dishes'));
    }



    // View sold dishes with customer & shipping info
    public function soldDishes(Request $request)
    {
        $companyId = auth()->user()->company_id ?? $request->header('X-Company-Id');

        // Add the company ID to the headers
        $headers = [
            'X-Company-Id' => $companyId,
        ];
        $soldDishes = $this->api->get('order-payment-service/api/orders', [], $headers);
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
            'companyId' => 'required|integer|exists:companies,id',
        ]);

        $data = [
            "name" => $data['name'],
            "description" => $data['description'],
            "calories" => $data['calories'],
            "price" => $data['price'],
            "companyId" => $data['companyId'], // companyId from the request
            "quantity" => $data['quantity'] // quantity in the inventory of the company
        ];

        $this->api->post('/dish-inventory-service/api/dishes', $data);

        return redirect()->route('seller.dishes.index')->with('status', 'Dish added successfully!');
    }

    // Show edit form for a dish
    public function edit($id)
    {
        $dish = $this->api->get("/dish-inventory-service/api/dishes/{$id}");
        return view('seller.dishes.edit', compact('dish'));
    }

    // Update a dish
    public function update(Request $request, $id)
    {
        $companyId = auth()->user()->company_id ?? $request->header('X-Company-Id');
        // Add the company ID to the headers
        $headers = [
            'X-Company-Id' => $companyId,
        ];
        $data = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'description' => 'required|string',
            'calories' => 'required|integer|min:0',
        ]);

        $this->api->put("/dishes/{$id}", $data, $headers);

        return redirect()->route('seller.dishes.index')->with('status', 'Dish updated successfully!');
    }
}
