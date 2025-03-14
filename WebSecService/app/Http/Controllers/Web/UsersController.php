<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    use ValidatesRequests;

    /**
     * Show the registration form
     */
    public function register()
    {
        return view('users.register');
    }

    /**
     * Handle user registration
     */
    public function doRegister(Request $request)
    {
        // Validate user input
        $this->validate($request, [
            'name' => ['required', 'string', 'min:5'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => [
                'required',
                'confirmed',
                Password::min(6)->letters()->numbers()->mixedCase()->symbols()
            ],
        ]);

        // Create new user with privilege set to 0
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->privilege = 0; // Force privilege to 0

        $user->save();

        Auth::login($user);

        return redirect("/")->with('success', 'Registration successful.');
    }


    /**
     * Show the login form
     */
    public function login()
    {
        return view('users.login');
    }

    /**
     * Handle user login
     */
    public function doLogin(Request $request)
    {
        // Validate login input
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Attempt login
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate(); // Regenerate session for security
            return redirect('/')->with('success', 'Login successful.');
        }

        return back()->withErrors('Invalid login credentials.')->withInput();
    }


    /**
     * Handle user logout
     */
    public function doLogout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }


    /**
     * Show user profile page
     */
    public function profile()
    {
        return view('users.profile', ['user' => Auth::user()]);
    }

    /**
     * Handle password change
     */
    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()->mixedCase()->symbols()],
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors('Current password is incorrect.');
        }

        Auth::user()->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', 'Password updated successfully.');
    }
}
