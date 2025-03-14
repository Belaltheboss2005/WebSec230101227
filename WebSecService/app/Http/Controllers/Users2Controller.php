<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users2;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class Users2Controller extends Controller
{
    public function index(Request $request)
    {
        $query = Users2::query();

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

        return view('users2', compact('users', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        return view('users2.create'); // This will load resources/views/users2/create.blade.php
    }

    public function store(Request $request)
    {
        Log::info('Store method triggered'); // Debugging

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users2,email',
            'password' => 'required|min:6',
            'privilege' => 'required|integer|between:-1,1',
        ]);

        Log::info('Validated Data:', $validatedData);

        try {
            $user = Users2::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'privilege' => $request->privilege,
            ]);

            Log::info('User Created:', ['id' => $user->id]);

            return redirect()->route('users2.index')->with('success', 'User added successfully');
        } catch (\Exception $e) {
            Log::error('Error Storing User: ' . $e->getMessage());
            return back()->with('error', 'Failed to store user');
        }
    }

    public function edit($id)
    {
        $user = Users2::findOrFail($id);
        return view('users2.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = Users2::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users2,email,' . $id,
            'password' => 'nullable|min:6',
            'privilege' => 'required|integer|between:-1,1',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'privilege' => $request->privilege,
        ]);

        return redirect()->route('users2.index')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        Users2::findOrFail($id)->delete();
        return redirect()->route('users2.index')->with('success', 'User deleted successfully');
    }
}
