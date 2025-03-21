@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Users List</h2>
    <a href="{{ route('users.create') }}" class="btn btn-success mb-3">Add User</a>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Search Form -->
    <form method="GET" action="{{ route('users.index') }}" class="mb-3">
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
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Reset</a>
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
                <th>Privilege</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->privilege }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">Edit</a>
                        <a href="{{ route('users.delete', $user->id) }}" class="btn btn-danger"
                           onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
