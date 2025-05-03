@extends('layouts.master')
@section('title', 'Register')
@section('content')
<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-6">
    <div class="card-body">
      @if(session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
      @endif
      @if(session('verification_prompt'))
      <div class="alert alert-info">
        <p>Would you like to verify your email now?</p>
        <a href="{{ route('resend_verification_email') }}" class="btn btn-success">Verify Now</a>
        <a href="{{ route('users') }}" class="btn btn-secondary">Later</a>
      </div>
      @endif
      <form action="{{route('do_register')}}" method="post">
      {{ csrf_field() }}
      <div class="form-group">
      @foreach($errors->all() as $error)
        <div class="alert alert-danger">
          <strong>Error!</strong> {{$error}}
        </div>
      @endforeach
      <div class="form-group mb-2">
        <label for="code" class="form-label">Name:</label>
        <input type="text" class="form-control" placeholder="name" name="name" required>
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
        <label for="model" class="form-label">Password Confirmation:</label>
        <input type="password" class="form-control" placeholder="Confirmation" name="password_confirmation" required>
      </div>
      <div class="form-group mb-2">
        <label for="email_verification" class="form-label">Email Verification:</label>
        <div>
          <input type="radio" id="verify_now" name="email_verification" value="now" required>
          <label for="verify_now">Now</label>
          <input type="radio" id="verify_later" name="email_verification" value="later" required>
          <label for="verify_later">Later</label>
        </div>
      </div>
      <div class="form-group mb-2">
        <button type="submit" class="btn btn-primary">Register</button>
      </div>
    </form>
    </div>
  </div>
</div>
@endsection
