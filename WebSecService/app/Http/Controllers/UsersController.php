<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
        $allowedSortFields = ['id', 'name', 'email', 'created_at'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'id';
        }

        // Restrict Employee role to only see users with User role
        if (auth()->user()->hasRole('Employee')) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', 'User');
            });
        }

        $users = $query->orderBy($sortField, $sortDirection)->get();

        return view('users', compact('users', 'sortField', 'sortDirection'));
    }

    public function create()
    {

        $roles = Role::all();
        $permissions = Permission::all();
        return view('users.create', compact('roles', 'permissions'));

    }
    public function store(Request $request)
    {
        Log::info('Store method triggered');

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|string|exists:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        Log::info('Validated Data:', $validatedData);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            Log::info('User Created:', ['id' => $user->id]);

            // Assign role to the user
            $user->assignRole($request->role);
            Log::info('Role assigned:', ['role' => $request->role]);

            // Assign permissions to the user
            if ($request->has('permissions')) {
                $user->syncPermissions($request->permissions);
                Log::info('Permissions assigned:', ['permissions' => $request->permissions]);
            }

            return redirect()->route('users.index')->with('success', 'User added successfully');
        } catch (\Exception $e) {
            Log::error('Error Storing User: ' . $e->getMessage());
            return back()->with('error', 'Failed to store user');
        }
    }


    public function edit($id)
{
    Log::info('Edit method triggered for user:', ['id' => $id]);

    $user = User::findOrFail($id);

    // Restrict Employee role to only edit users with User role
    if (auth()->user()->hasRole('Employee') && !$user->hasRole('User')) {
        return redirect()->route('users.index')->with('error', 'You do not have permission to edit this user.');
    }

    $roles = Role::all(); // Fetch all roles
    $permissions = Permission::all(); // Fetch all permissions

    Log::info('User data fetched for edit:', ['user' => $user, 'roles' => $roles, 'permissions' => $permissions]);

    return view('users.edit', compact('user', 'roles', 'permissions'));
}

public function update(Request $request, $id)
{
    Log::info('Update method triggered for user:', ['id' => $id]);

    $user = User::findOrFail($id);

    // Restrict Employee role to only update users with User role
    if (auth()->user()->hasRole('Employee') && !$user->hasRole('User')) {
        return redirect()->route('users.index')->with('error', 'You do not have permission to update this user.');
    }

    Log::info('User found:', ['user' => $user]);

    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'password' => 'nullable|min:6',
    ]);

    Log::info('Validated Data:', $validatedData);

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password ? Hash::make($request->password) : $user->password,
    ]);

    Log::info('User updated:', ['user' => $user]);

    if (!auth()->user()->hasRole('Employee')) {
        // Convert permission IDs to permission names
        $permissions = Permission::whereIn('id', $request->permissions ?? [])->pluck('name')->toArray();
        Log::info('Permissions to sync:', ['permissions' => $permissions]);

        // Sync roles and permissions
        $user->syncRoles([$request->role]);
        Log::info('Role synced:', ['role' => $request->role]);

        $user->syncPermissions($permissions);
        Log::info('Permissions synced:', ['permissions' => $permissions]);

        // Handle unchecked permissions
        $currentPermissions = $user->permissions->pluck('id')->toArray();
        $newPermissions = $request->permissions ?? [];
        $permissionsToDelete = array_diff($currentPermissions, $newPermissions);

        if (!empty($permissionsToDelete)) {
            $user->permissions()->detach($permissionsToDelete);
            Log::info('Permissions detached:', ['permissions_to_delete' => $permissionsToDelete]);
        }
    }

    Log::info('User updated successfully:', ['id' => $user->id]);

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
        ]);

        // Assign the "user" role to the newly registered user
        $user->assignRole('user');

        Log::info('User Created and role assigned:', ['id' => $user->id]);

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

    public function editProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'current_password' => 'required_with:new_password|string',
            'new_password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;

        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = bcrypt($request->new_password);
            } else {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully');
    }
}
