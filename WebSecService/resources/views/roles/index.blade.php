@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Roles</h2>
    <a href="{{ route('roles.create') }}" class="btn btn-success mb-3">Add Role</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Permissions</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->permissions->pluck('name')->implode(', ') }}</td>
                    <td>
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
