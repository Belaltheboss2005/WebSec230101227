<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

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

        $students = $query->orderBy($sortField, $sortDirection)->get();

        return view('student', compact('students', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        return view('student.create'); // This will load resources/views/users2/create.blade.php
    }

    public function store(Request $request)
    {
        Log::info('Store method triggered'); // Debugging

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:student,email',
            'password' => 'required|min:6',
            'age'=> 'required|int|max:255',
            'major'=> 'required|string|max:255',

        ]);

        Log::info('Validated Data:', $validatedData);

        try {
            $student = Student::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'age' => $request->age,
                'major' => $request->major,

            ]);

            Log::info('Student Created:', ['id' => $student->id]);

            return redirect()->route('student.index')->with('success', 'Student added successfully');
        } catch (\Exception $e) {
            Log::error('Error Storing Student: ' . $e->getMessage());
            return back()->with('error', 'Failed to store Student');
        }
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('student.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:student,email,' . $id,
            'password' => 'nullable|min:6',
            'age'=> 'required|int|max:255',
            'major'=> 'required|string|max:255',

        ]);

        $student->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $student->password,
            'age' => $request->age,
            'major' => $request->major,
        ]);

        return redirect()->route('student.index')->with('success', 'Student updated successfully');
    }

    public function destroy($id)
    {
        Student::findOrFail($id)->delete();
        return redirect()->route('student.index')->with('success', 'Student deleted successfully');
    }
}
