@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Add Permission</h2>
    <form action="{{ route('permissions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Display Name:</label>
            <input type="text" name="display_name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Permission</button>
    </form>
</div>
@endsection
