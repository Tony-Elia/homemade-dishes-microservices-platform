<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\JavaEeApiService;
use Illuminate\Http\Request;

class CustomerProfileController extends Controller
{
    protected $api;

    public function __construct(JavaEeApiService $api)
    {
        $this->api = $api;
    }

    public function index()
    {
        // Fetch the customer profile from the API
        // $customer = $this->api->get('/users/customers/' . auth()->user()->id);

        // // Pass the customer data to the view
        // return view('customer.profile', compact('customer'));
    }
    public function edit()
    {
        // Fetch the customer profile from the API
        // $customer = $this->api->get('/users/' . auth()->user()->id);

        // // Pass the customer data to the view
        // return view('customer.profile_edit', compact('customer'));
    }
    public function update(Request $request)
    {
        // Validate the request data
        // $data = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email|max:255',
        //     // 'phone' => 'required|string|max:15',
        //     // 'address' => 'required|string|max:255',
        // ]);

        // // Update the customer profile via API
        // $this->api->put('/users/' . auth()->user()->id, $data);

        // // Redirect back to the profile page with a success message
        // return redirect()->route('customer.profile')->with('status', 'Profile updated successfully!');
    }
    public function destroy()
    {
        // // Delete the customer profile via API
        // $this->api->delete('/users/' . auth()->user()->id);

        // // Log out the user
        // auth()->logout();

        // // Redirect to the home page with a success message
        // return redirect('/')->with('status', 'Profile deleted successfully!');
    }
}
