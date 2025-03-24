@extends('layouts.master')

@section('title', 'Profile')

@section('content')
<div class="container mt-4">
    <h2>User Profile</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card p-3">
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Role:</strong> {{ $user->roles->pluck('name')->implode(', ') }}</p>
        <p><strong>Permissions:</strong> {{ $user->permissions->pluck('display_name')->implode(', ') }}</p>
    </div>

    <h3 class="mt-4">Edit Profile</h3>
    <form action="{{ route('profile.edit') }}" method="post">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">New Name:</label>
            <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
        </div>

        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password (required to change password):</label>
            <input type="password" class="form-control" name="current_password">
        </div>

        <div class="mb-3">
            <label for="new_password" class="form-label">New Password:</label>
            <input type="password" class="form-control" name="new_password">
        </div>

        <div class="mb-3">
            <label for="new_password_confirmation" class="form-label">Confirm New Password:</label>
            <input type="password" class="form-control" name="new_password_confirmation">
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
@endsection
