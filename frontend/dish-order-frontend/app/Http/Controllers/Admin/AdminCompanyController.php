<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\JavaEeApiService;

class AdminCompanyController extends Controller
{
    protected $api;

    public function __construct(JavaEeApiService $api)
    {
        $this->api = $api;
    }

    public function index()
    {
        return view('admin.create_companies');
    }

    public function store()
    {
        $data = request()->validate([
            'company_names' => 'required|string'
        ]);

        // Prepare the payload for the API
        $payload = [
            'name' => $data['company_names'],
            'region' => 'Africa/Cairo' // Hardcoded region
        ];

        // Use the provided API URL
        $response = $this->api->post('/companies', $payload);

        // Check if the API returned an error
        if (isset($response['error']) && $response['error'] === 'Company Name is already in use') {
            return redirect()->back()->withErrors(['company_names' => 'The company name is already in use.']);
        }

        // Handle success
        return redirect()->back()->with('status', 'Companies created successfully.');
    }
}
