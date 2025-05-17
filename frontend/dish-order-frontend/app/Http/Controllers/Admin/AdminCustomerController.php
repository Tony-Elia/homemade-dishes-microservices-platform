<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\JavaEeApiService;

class AdminCustomerController extends Controller
{
    protected $api;

    public function __construct(JavaEeApiService $api)
    {
        $this->api = $api;
    }

    public function index()
    {
        // Fetch all customers from the API
        $customers = $this->api->get('/user-management-service/api/users/customers');

        // Handle API error
        if (isset($customers['error'])) {
            return redirect()->back()->withErrors(['customers' => $customers['error']]);
        }

        // If API returns ['data' => [...]], extract the array
        if (isset($customers['data'])) {
            $customers = $customers['data'];
        }

        // Always pass an array
        $customers = is_array($customers) ? $customers : [];

        // Pass the customers data to the view
        return view('admin.customers', compact('customers'));
    }

}

