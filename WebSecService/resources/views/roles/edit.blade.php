@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Edit Role</h2>
    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
        </div>
        <div class="mb-3">
            <label>Permissions:</label>
            <div class="form-check">
                @foreach($permissions as $permission)
                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-check-input" id="permission-{{ $permission->id }}" {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                    <label class="form-check-label" for="permission-{{ $permission->id }}">
                        {{ ucfirst($permission->name) }}
                    </label><br>
                @endforeach
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Role</button>
    </form>
</div>
@endsection
