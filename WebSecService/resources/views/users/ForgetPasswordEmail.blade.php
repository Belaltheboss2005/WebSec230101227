@extends('layouts.master')

@section('title', 'Reset Password')

@section('content')
<div class="container mt-4">
    <h2>Reset Password</h2>

    @if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif

    <form action="{{ route('password.email') }}" method="post">
        {{ csrf_field() }}

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Send Password Reset Link</button>
    </form>
</div>
@endsection
