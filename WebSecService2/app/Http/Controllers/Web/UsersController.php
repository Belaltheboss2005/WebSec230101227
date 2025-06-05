<?php
namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use Artisan;
use Laravel\Socialite\Facades\Socialite;


use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller {

	use ValidatesRequests;

    public function redirectToGoogle()
    {
    return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback()
    {
        try {
            // Retrieve the Google user data
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Log the Google user data for debugging
            Log::info('Google User:', [
                'id' => $googleUser->id,
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'token' => $googleUser->token,
                'refresh_token' => $googleUser->refreshToken,
            ]);

            // Check if the user already exists by email
            $user = User::where('email', $googleUser->email)->first();

            // If the user exists, update their Google data
            if ($user) {
                $user->google_id = $googleUser->id;
                $user->google_token = $googleUser->token;
                $user->google_refresh_token = $googleUser->refreshToken;
                $user->save();
            } else {
                // If the user doesn't exist, create a new one
                $user = new User();
                $user->name = $googleUser->name;
                $user->email = $googleUser->email;
                $user->google_id = $googleUser->id;
                $user->google_token = $googleUser->token;
                $user->google_refresh_token = $googleUser->refreshToken;
                $user->password = bcrypt('default_password'); // Set a default password
                $user->credit = 80000; // Assign 80000 credit to the user
                $user->assignRole('customer');
                $user->save();

                // Send the verification email as in your doRegister method
                $title = "Verification Link";
                $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
                $link = route("verify", ['token' => $token]);
                Mail::to($user->email)->send(new VerificationEmail($link, $user->name));
            }

            // Log the user in
            Auth::login($user);

            // Redirect to the home page or dashboard
            return redirect('/')->with('success', 'Logged in successfully with Google!');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Google Login Error:', ['message' => $e->getMessage()]);

            // Redirect back to the login page with an error message
            return redirect('/login')->with('error', 'Google login failed. Please try again.');
        }
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {

        $facebookUser = Socialite::driver('facebook')->stateless()->user();

        $user = User::firstOrCreate(
            ['facebook_id' => $facebookUser->getId()],
            [
                'facebook_name' => $facebookUser->getName(),
                'facebook_email' => $facebookUser->getEmail()
            ]
        );

        Auth::login($user);

        return redirect('/')->with('success', 'Logged in successfully with Facebook!');
        // try {
        //     // Retrieve the Facebook user
        //     $facebookUser = Socialite::driver('facebook')->stateless()->user();

        //     // Log the Facebook user data for debugging
        //     Log::info('Facebook User:', [
        //         'id' => $facebookUser->id,
        //         'name' => $facebookUser->name,
        //         'email' => $facebookUser->email,
        //     ]);

        //     // Find the user by email or create a new one
        //     $user = User::where('email', $facebookUser->email)->first();

        //     if ($user) {
        //         // Update the user's Facebook ID and token if they already exist
        //         $user->update([
        //             'facebook_id' => $facebookUser->id,
        //             'facebook_token' => $facebookUser->token,
        //         ]);
        //     } else {
        //         // Create a new user if they don't exist
        //         $user = User::create([
        //             'name' => $facebookUser->name,
        //             'email' => $facebookUser->email,
        //             'facebook_id' => $facebookUser->id,
        //             'facebook_token' => $facebookUser->token,
        //             'password' => bcrypt('default_password'), // Set a default password
        //         ]);
        //     }
        //     $user->assignRole('customer');

        //     // Log the user in
        //     Auth::login($user);

            // Redirect to the home page or dashboard
        //     return redirect('/')->with('success', 'Logged in successfully with Facebook!');
        // } catch (\Exception $e) {
        //     // Log the error for debugging
        //     Log::error('Facebook Login Error:', ['message' => $e->getMessage()]);

        //     // Redirect back to the login page with an error message
        //     return redirect('/login')->with('error', 'Facebook login failed. Please try again.');
        // }
    }

    public function list()
    {
        $user = auth()->user();
        if (!auth()->user()->hasPermissionTo('show_users')) {
            abort(401); // Unauthorized
        }

        if ($user->hasRole('Employee')) {
            // Employees can only see customers
            $users = User::whereHas('roles', function ($query) {
                $query->where('name', 'Customer');
            })->get();
        } else {
            // Admins or other roles can see all users
            $users = User::all();
        }

        return view('users.list', compact('users'));
    }

	public function register(Request $request) {
        return view('users.register');
    }

    public function doRegister(Request $request) {

        try {
            $this->validate($request, [
                'name' => ['required', 'string', 'min:5'],
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required', 'confirmed', Password::min(5)->numbers()->letters()->mixedCase()->symbols()],
                'email_verification' => ['required', 'in:now,later'],
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->input())->withErrors('Invalid registration information.');
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password); // Secure
        $user->credit = 80000; // Assign 80000 credit to the user
        $user->save();

        // Assign the "customer" role to the user
        $user->assignRole('customer');

        // Check email verification preference
        if ($request->email_verification === 'now') {
            $title = "Verification Link";
            $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
            $link = route("verify", ['token' => $token]);
            try {
                Mail::to($user->email)->send(new VerificationEmail($link, $user->name));
                Log::info('Verification email sent successfully to ' . $user->email);
            } catch (\Exception $e) {
                Log::error('Failed to send verification email: ' . $e->getMessage());
            }
        }

        return redirect()->route('users')->with('success', 'Registration successful!');
    }

    public function verify(Request $request) {
        try {
            // Decrypt the token
            $decryptedData = json_decode(Crypt::decryptString($request->token), true);

            // Find the user by ID
            $user = User::find($decryptedData['id']);

            // If the user is not found, return a 404 error
            if (!$user) {
                return abort(404, 'User not found.');
            }

            // Mark the user's email as verified
            $user->email_verified_at = Carbon::now();
            $user->save();

            return view('users.verified', compact('user'));
        } catch (\Exception $e) {
            // Handle decryption or other errors
            return abort(401, 'Invalid or expired verification link.');
        }
    }

    public function addUser()
{
    if (!auth()->user()->hasPermissionTo('add_users')) {
        abort(401); // Unauthorized
    }

    $roles = Role::all(); // Fetch all roles for the dropdown
    return view('users.add', compact('roles'));
}




    public function storeUser(Request $request)
    {

        if(!auth()->user()->hasPermissionTo('add_users')) abort(401);

        Log::info('storeUser method called', ['request' => $request->all()]);

        if (!auth()->user()->hasPermissionTo('add_users')) {
            Log::warning('Unauthorized access attempt to storeUser by user ID: ' . auth()->id());
            abort(401); // Unauthorized
        }

        $this->validate($request, [
            'name' => ['required', 'string', 'min:3'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(6)->numbers()->letters()->mixedCase()->symbols()],
            'credit' => ['required', 'integer', 'min:0'],
            'role' => ['required', 'exists:roles,name'],
        ]);

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->credit = $request->credit;
            $user->save();

            // Assign the selected role to the user
            $user->assignRole($request->role);

            Log::info('New user created successfully', [
                'admin_id' => auth()->id(),
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'role' => $request->role,
            ]);

            return redirect()->route('users')->with('success', 'User added successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating user', [
                'admin_id' => auth()->id(),
                'error_message' => $e->getMessage(),
            ]);

            return redirect()->back()->withErrors('An error occurred while adding the user.');
        }
    }
    public function login(Request $request) {
        return view('users.login');
    }

    public function doLogin(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user->hasRole('Banned')) {
            return redirect()->route('banned_page');
        }

        // if (!$user || !$user->email_verified_at) {
        //     return redirect()->back()->withInput($request->input())
        //         ->withErrors('Your email is not verified.');
        // }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->back()->withInput($request->input())
                ->withErrors('Invalid login information.');
        }

        Auth::setUser($user);

        return redirect('/');
    }

    public function doLogout(Request $request) {

    	Auth::logout();

        return redirect('/');
    }

    public function profile(Request $request, User $user = null) {

        $user = $user??auth()->user();
        if(auth()->id()!=$user->id) {
            if(!auth()->user()->hasPermissionTo('show_users')) abort(401);
        }

        $permissions = [];
        foreach($user->permissions as $permission) {
            $permissions[] = $permission;
        }
        foreach($user->roles as $role) {
            foreach($role->permissions as $permission) {
                $permissions[] = $permission;
            }
        }

        return view('users.profile', compact('user', 'permissions'));
    }

    public function edit(Request $request, User $user = null) {

        $user = $user??auth()->user();
        if(auth()->id()!=$user?->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);
        }

        $roles = [];
        foreach(Role::all() as $role) {
            $role->taken = ($user->hasRole($role->name));
            $roles[] = $role;
        }

        $permissions = [];
        $directPermissionsIds = $user->permissions()->pluck('id')->toArray();
        foreach(Permission::all() as $permission) {
            $permission->taken = in_array($permission->id, $directPermissionsIds);
            $permissions[] = $permission;
        }

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function save(Request $request, User $user) {

        if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);

        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'credit' => ['required', 'numeric', 'min:0'], // Validation for credit
        ]);

        // Ensure the credit can only be increased
        if ($request->credit < $user->credit) {
            return redirect()->back()->withErrors(['credit' => 'You cannot decrease the user\'s credit.'])->withInput();
        }

        $user->name = $request->name;
        $user->credit = $request->credit; // Save the credit value
        $user->save();

        // Only sync roles and permissions if the user has the appropriate permission
        if (auth()->user()->hasPermissionTo('admin_users')) {
            // Sync roles only if roles are provided in the request
            if ($request->has('roles')) {
                $user->syncRoles($request->roles);
            }

            // Sync permissions only if permissions are provided in the request
            if ($request->has('permissions')) {
                $user->syncPermissions($request->permissions);
            }
            // Clear cache to reflect changes
            Artisan::call('cache:clear');
        }

        return redirect(route('profile', ['user' => $user->id]));
    }

    public function delete(Request $request, User $user) {

        if(!auth()->user()->hasPermissionTo('delete_users')) abort(401);

        $user->delete();

        return redirect()->route('users');
    }

    public function editPassword(Request $request, User $user = null) {

        $user = $user??auth()->user();
        if(auth()->id()!=$user?->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);
        }

        return view('users.edit_password', compact('user'));
    }

    public function savePassword(Request $request, User $user) {

        if(auth()->id()==$user?->id) {

            $this->validate($request, [
                'password' => ['required', 'confirmed', Password::min(6)->numbers()->letters()->mixedCase()->symbols()],
            ]);

            if(!Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {

                Auth::logout();
                return redirect('/');
            }
        }
        else if(!auth()->user()->hasPermissionTo('edit_users')) {

            abort(401);
        }

        $user->password = bcrypt($request->password); //Secure
        $user->save();

        return redirect(route('profile', ['user'=>$user->id]));
    }

    public function resendVerificationEmail(Request $request)
    {
        $user = auth()->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->back()->with('info', 'Your email is already verified.');
        }

        $title = "Verification Link";
        $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
        $link = route("verify", ['token' => $token]);
        Mail::to($user->email)->send(new VerificationEmail($link, $user->name));

        return redirect(route('profile'))->with('success', 'Verification email has been resent.');
    }

    public function ban(User $user)
    {
        if (!auth()->user()->hasPermissionTo('ban_users')) {
            abort(401); // Unauthorized
        }

        // Assign the 'Banned' role to the user
        $user->syncRoles(['Banned']);

        // Log the user out if they are currently logged in
        if (Auth::id() === $user->id) {
            Auth::logout();
        }

        return redirect()->route('users')->with('success', 'User has been banned successfully.');
    }
}
