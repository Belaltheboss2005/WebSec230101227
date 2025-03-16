@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Add Student</h2>
    <form action="{{ route('student.store') }}" method="POST">
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
            <label>Age:</label>
            <input type="number" name="age" class="form-control" min="16" required>
        </div>
        <div class="mb-3">
            <label>Major:</label>
            <input type="text" name="major" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </form>
</div>
@endsection
