@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Edit Student</h2>
    <form action="{{ route('student.update', $student->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" value="{{ $student->name }}" required>
        </div>
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" value="{{ $student->email }}" required>
        </div>
        <div class="mb-3">
            <label>Age:</label>
            <input type="number" name="age" class="form-control" value="{{ $student->age }}" required>
        </div>
        <div class="mb-3">
            <label>Major:</label>
            <input type="text" name="major" class="form-control" value="{{ $student->major }}" required>
        </div>
        <div class="mb-3">
            <label>New Password (optional):</label>
            <input type="password" name="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
