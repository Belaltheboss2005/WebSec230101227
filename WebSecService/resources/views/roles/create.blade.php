@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Add Role</h2>
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Role</button>
    </form>
</div>
@endsection
