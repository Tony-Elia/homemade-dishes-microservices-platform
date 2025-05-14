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
    public function index()
    {
        $dishes = $this->api->get('/dishes/my-dishes');
        return view('seller.dishes.index', compact('dishes'));
    }

    // View sold dishes with customer & shipping info
    public function soldDishes()
    {
        $soldDishes = $this->api->get('/orders/my-sold-dishes');
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
            'amount' => 'required|integer|min:1',
        ]);

        $this->api->post('/dishes', $data);

        return redirect()->route('seller.dishes')->with('status', 'Dish added successfully!');
    }

    // Show edit form for a dish
    public function edit($id)
    {
        $dish = $this->api->get("/dishes/{$id}");
        return view('seller.dishes.edit', compact('dish'));
    }

    // Update a dish
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'amount' => 'required|integer|min:1',
        ]);

        $this->api->put("/dishes/{$id}", $data);

        return redirect()->route('seller.dishes')->with('status', 'Dish updated successfully!');
    }
}
