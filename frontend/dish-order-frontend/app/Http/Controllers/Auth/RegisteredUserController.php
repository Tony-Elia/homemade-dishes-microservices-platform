<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Http\Controllers\JavaEeApiService;

class RegisteredUserController extends Controller
{
    protected $api;

    public function __construct(JavaEeApiService $api)
    {
        $this->api = $api;
    }
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            DB::beginTransaction();
            // Create Laravel user locally
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'customer', // Default role
            ]);

            Auth::login($user);
            // Send user to Java EE API
            $response = $this->api->post('/user-management-service/api/users/register/customer', [
                'name'  => $user->name,
                'email' => $user->email,
            ]);

            if(isset($response['status']) && $response['status'] > 399) throw new \Exception($response['error']);

            event(new Registered($user));

            DB::commit();

            return redirect(route('dashboard', absolute: false));

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to register user in Java EE system: ' . $e->getMessage());
            return redirect()->back()->withErrors([
                'registration' => 'Error: ' . $e->getMessage(),
            ])->withInput();
        }
    }
}
