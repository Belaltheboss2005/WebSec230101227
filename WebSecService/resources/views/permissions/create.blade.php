@extends('layouts.master')

@section('title', 'Create Permission')

@section('content')
<div class="container">
    <h2>Create Permission</h2>
    <form action="{{ route('permissions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="display_name" class="form-label">Display Name:</label>
            <input type="text" name="display_name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Permission</button>
    </form>
</div>
@endsection
