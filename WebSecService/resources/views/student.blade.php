@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Student List</h2>
    <a href="{{ route('student.create') }}" class="btn btn-success mb-3">Add Student</a>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Search Form -->
    <form method="GET" action="{{ route('student.index') }}" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control"
                       placeholder="Search by name or email" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="sort_by" class="form-select">
                    <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Sort by Name</option>
                    <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Sort by Email</option>
                    <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>Sort by ID</option>
                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Sort by Created At</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="sort_order" class="form-select">
                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('student.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    <!-- Users Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Age</th>
                <th>Major</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
                <tr>
                    <td>{{ $student->id }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->age }}</td>
                    <td>{{ $student->major }}</td>
                    <td>
                        <a href="{{ route('student.edit', $student->id) }}" class="btn btn-primary">Edit</a>
                        <a href="{{ route('student.delete', $student->id) }}" class="btn btn-danger"
                           onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
