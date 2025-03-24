<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;

class PermissionsController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasRole('Admin')) {
            return redirect()->route('welcome')->with('error', 'You do not have permission to access this page.');
        }

        $permissions = Permission::all();
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        if (!auth()->user()->hasRole('Admin')) {
            return redirect()->route('welcome')->with('error', 'You do not have permission to access this page.');
        }

        return view('permissions.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('Admin')) {
            return redirect()->route('welcome')->with('error', 'You do not have permission to access this page.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'display_name' => 'required|string|max:255',
        ]);

        Permission::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully');
    }

    public function edit($id)
    {
        if (!auth()->user()->hasRole('Admin')) {
            return redirect()->route('welcome')->with('error', 'You do not have permission to access this page.');
        }

        $permission = Permission::findOrFail($id);
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasRole('Admin')) {
            return redirect()->route('welcome')->with('error', 'You do not have permission to access this page.');
        }

        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
            'display_name' => 'required|string|max:255',
        ]);

        $permission->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasRole('Admin')) {
            return redirect()->route('welcome')->with('error', 'You do not have permission to access this page.');
        }

        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully');
    }
}
