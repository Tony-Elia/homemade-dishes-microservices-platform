<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\JavaEeApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AdminSellerRepController extends Controller
{
    protected $api;

    public function __construct(JavaEeApiService $api)
    {
        $this->api = $api;
    }

    public function index()
    {
        try {
            $reps = $this->api->get('/user-management-service/api/users/seller-representatives');
            // $reps=User::where('role', 'seller')->get();
            return view('admin.seller_reps', compact('reps'));
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error fetching seller representatives: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->withErrors(['error' => 'Failed to fetch seller representatives. Please try again later.']);
        }
    }

    public function assign($company_id){
        return view('admin.create_seller_rep',['company_id' => $company_id]);
    }

    public function create(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'email' => 'required|string|max:255|email',
            'name' => 'required|string|max:255',
            'company_id' => 'required|integer',
        ]);

        // Generate a random password
        $randomPassword = \Illuminate\Support\Str::random(10);

        // Create the user in the database
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => bcrypt($randomPassword), // Hash the password
            'role'     => 'seller',
        ]);

        try {
            // Make the API call to register the representative
            $this->api->post('/user-management-service/api/users/register/representative', [
                'email' => $validated['email'],
                'name' => $validated['name'],
                'company_id' => $validated['company_id'],
            ]);

            // Redirect to a success page or back with a success message
            return redirect()->route('admin.seller_representatives')->with('status', 'Seller representative created successfully. Password: ' . $randomPassword);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error creating seller representative: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->withErrors(['error' => 'Failed to create seller representative. Please try again later.']);
        }
    }
}