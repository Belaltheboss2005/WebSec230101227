@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Edit User</h2>
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>
        <div class="mb-3">
            <label>New Password (optional):</label>
            <input type="password" name="password" class="form-control">
        </div>
        @if(!auth()->user()->hasRole('Employee'))
            <div class="mb-3">
                <label>Role:</label>
                <select name="role" class="form-select" required>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Permissions:</label>
                <div class="form-check">
                    @foreach($permissions as $permission)
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-check-input" id="permission-{{ $permission->id }}" {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                        <label class="form-check-label" for="permission-{{ $permission->id }}">
                            {{ ucfirst($permission->display_name) }}
                        </label><br>
                    @endforeach
                </div>
            </div>
        @endif
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
