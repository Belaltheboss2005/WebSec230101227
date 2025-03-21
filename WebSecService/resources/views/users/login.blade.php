@extends('layouts.master')

@section('title', 'Login')

@section('content')
<div class="container mt-4">
    <h2>Login</h2>

    @foreach($errors->all() as $error)
    <div class="alert alert-danger">
        <strong>Error!</strong> {{$error}}
    </div>
    @endforeach

    @if(session('error'))
    <div class="alert alert-danger">
        <strong>Error!</strong> {{ session('error') }}
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success">
        <strong>Success!</strong> {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('do_login') }}" method="post">
        {{ csrf_field() }}

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>

    <div class="mt-3">
        <a href="{{ route('password.request') }}" class="btn btn-link">Forget Password</a>
    </div>
</div>
@endsection
