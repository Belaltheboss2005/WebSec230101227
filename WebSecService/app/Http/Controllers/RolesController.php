<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;

class RolesController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasRole('Admin')) {
            return redirect()->route('welcome')->with('error', 'You do not have permission to access this page.');
        }

        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        if (!auth()->user()->hasRole('Admin')) {
            return redirect()->route('welcome')->with('error', 'You do not have permission to access this page.');
        }

        return view('roles.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('Admin')) {
            return redirect()->route('welcome')->with('error', 'You do not have permission to access this page.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        Role::create(['name' => $request->name]);

        return redirect()->route('roles.index')->with('success', 'Role created successfully');
    }

    public function edit($id)
    {
        if (!auth()->user()->hasRole('Admin')) {
            return redirect()->route('welcome')->with('error', 'You do not have permission to access this page.');
        }

        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasRole('Admin')) {
            return redirect()->route('welcome')->with('error', 'You do not have permission to access this page.');
        }

        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();
            Log::info('Permissions to sync:', ['permissions' => $permissions]);
            $role->syncPermissions($permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role updated successfully');
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasRole('Admin')) {
            return redirect()->route('welcome')->with('error', 'You do not have permission to access this page.');
        }

        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
    }
}
