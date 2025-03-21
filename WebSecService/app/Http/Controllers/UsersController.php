<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;

class UsersController extends Controller
{
    use ValidatesRequests;

    public function index(Request $request)
    {
        $query = User::query();

        // Search filter
        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
        }

        // Sorting
        $sortField = $request->get('sort_by', 'id'); // Default: sort by ID
        $sortDirection = $request->get('sort_order', 'asc'); // Default: ascending

        // Prevent sorting by unauthorized fields
        $allowedSortFields = ['id', 'name', 'email', 'created_at', 'privilege'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'id';
        }

        $users = $query->orderBy($sortField, $sortDirection)->get();

        return view('users', compact('users', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        Log::info('Store method triggered');

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'privilege' => 'required|integer|between:-1,1',
        ]);

        Log::info('Validated Data:', $validatedData);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'privilege' => $request->privilege,
            ]);

            Log::info('User Created:', ['id' => $user->id]);

            return redirect()->route('users.index')->with('success', 'User added successfully');
        } catch (\Exception $e) {
            Log::error('Error Storing User: ' . $e->getMessage());
            return back()->with('error', 'Failed to store user');
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'privilege' => 'required|integer|between:-1,1',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'privilege' => $request->privilege,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    public function register()
    {
        return view('users.register');
    }

    public function doRegister(Request $request)
    {
        Log::info('Store method triggered');

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        Log::info('Validated Data:', $validatedData);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'privilege' => 0,
            ]);

            Log::info('User Created:', ['id' => $user->id]);

            return redirect()->route('login')->with('success', 'Registration successful.');
        } catch (\Exception $e) {
            Log::error('Error Storing User: ' . $e->getMessage());
            return back()->with('error', 'Failed to store user');
        }
    }

    public function login()
    {
        return view('users.login');
    }

    public function doLogin(Request $request)
    {
        Log::info('Login method triggered');
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            Log::info('Login successful for user:', ['email' => $request->email]);
            $request->session()->regenerate();
            return redirect('/')->with('success', 'Login successful.');
        }

        Log::warning('Invalid login credentials for user:', ['email' => $request->email]);
        return back()->withErrors('Invalid login credentials.')->withInput();
    }

    public function doLogout()
    {
        Log::info('Logout method triggered for user:', ['id' => Auth::id()]);
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }

    public function profile()
    {
        Log::info('Profile method triggered for user:', ['id' => Auth::id()]);
        return view('users.profile', ['user' => Auth::user()]);
    }

    public function changePassword(Request $request)
    {
        Log::info('Change password method triggered for user:', ['id' => Auth::id()]);
        $this->validate($request, [
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(6)->letters()->numbers()->mixedCase()->symbols()],
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            Log::warning('Current password is incorrect for user:', ['id' => Auth::id()]);
            return back()->withErrors('Current password is incorrect.');
        }

        Auth::user()->update(['password' => Hash::make($request->new_password)]);
        Log::info('Password updated successfully for user:', ['id' => Auth::id()]);

        return back()->with('success', 'Password updated successfully.');
    }
}
