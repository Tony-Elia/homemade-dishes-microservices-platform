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
        $customers = $this->api->get('/users/customers');

        // Pass the customers data to the view
        return view('admin.customers', compact('customers'));
    }

}

