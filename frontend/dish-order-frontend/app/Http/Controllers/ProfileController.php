<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    protected $api;

    public function __construct(JavaEeApiService $api)
    {
        $this->api = $api;
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        try {
            // First attempt to update Java EE server
            $this->api->put('/user-management-service/api/users/' . $user->email, [
                'name' => $request->validated()['name'],
                'email' => $request->validated()['email'],
            ]);

            // If Java EE succeeded, then update local database
            $user->fill($request->validated());

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

        } catch (\Exception $e) {
            // Log and return back with error message
            \Log::error('Failed to update user on Java EE server: ' . $e->getMessage());

            return Redirect::back()->withErrors(['update' => 'Failed to update your profile on the server. Try again later.']);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        try {
            // First delete from Java EE
            $this->api->delete('/user-management-service/api/users/' . $user->email);
        } catch (\Exception $e) {
            \Log::error('Failed to delete user on Java EE server: ' . $e->getMessage());

            return Redirect::back()->withErrors(['delete' => 'Failed to delete your profile on the server. Try again later.']);
        }

        // Only proceed locally if remote delete succeeded
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

}
