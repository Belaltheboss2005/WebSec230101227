@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Edit Permission</h2>
    <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" value="{{ $permission->name }}" required>
        </div>
        <div class="mb-3">
            <label>Display Name:</label>
            <input type="text" name="display_name" class="form-control" value="{{ $permission->display_name }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Permission</button>
    </form>
</div>
@endsection
