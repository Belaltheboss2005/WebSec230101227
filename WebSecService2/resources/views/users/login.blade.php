@extends('layouts.master')
@section('title', 'Login')
@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-6">
    <div class="card-body">
      <form action="{{route('do_login')}}" method="post">
      {{ csrf_field() }}
      <div class="form-group">
        @foreach($errors->all() as $error)
        <div class="alert alert-danger">
          <strong>Error!</strong> {{$error}}
        </div>
        @endforeach
      </div>
      <div class="form-group mb-2">
        <label for="model" class="form-label">Email:</label>
        <input type="email" class="form-control" placeholder="email" name="email" required>
      </div>
      <div class="form-group mb-2">
        <label for="model" class="form-label">Password:</label>
        <input type="password" class="form-control" placeholder="password" name="password" required>
      </div>
      <div class="form-group mb-2">
        <button type="submit" class="btn btn-primary">Login</button>
        <a href="{{route('login_with_google')}}" class="btn btn-success">Login with Google</a>
        <a href="{{ route('login_with_facebook') }}" class="btn btn-primary">Login with Facebook</a>
      </div>
    </form>
    <div class="mt-3">
        <a href="{{ route('password.request') }}" class="btn btn-link">Forget Password</a>
    </div>
    </div>
  </div>
</div>
@endsection
