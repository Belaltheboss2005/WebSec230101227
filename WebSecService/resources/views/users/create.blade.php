@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Add User</h2>
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Role:</label>
            <select name="role" class="form-select" required>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}">
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Permissions:</label>
            <div class="form-check">
                @foreach($permissions as $permission)
                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-check-input" id="permission-{{ $permission->id }}">
                    <label class="form-check-label" for="permission-{{ $permission->id }}">
                        {{ ucfirst($permission->display_name) }}
                    </label><br>
                @endforeach
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Add User</button>
    </form>
</div>
@endsection
