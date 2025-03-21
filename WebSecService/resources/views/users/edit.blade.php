@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Edit User</h2>
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
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
        <div class="mb-3">
            <label>New Privilege (optional):</label>
            <input type="number" name="privilege" class="form-control" min="-1" max="1" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
