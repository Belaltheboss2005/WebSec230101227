@extends('layouts.master')
@section('title', 'User Profile')
@section('content')
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<div class="row">
    <div class="m-4 col-sm-6">
        <table class="table table-striped">
            <tr>
                <th>Name</th><td>{{$user->name}}</td>
            </tr>
            <tr>
                <th>Email</th><td>{{$user->email}}</td>
            </tr>
            <tr>
                <th>Credit</th><td>{{$user->credit}}</td>
            </tr>
            <tr>
                <th>Roles</th>
                <td>
                    @foreach($user->roles as $role)
                        <span class="badge bg-primary">{{$role->name}}</span>
                    @endforeach
                </td>
            </tr>
            <tr>
                <th>Permissions</th>
                <td>
                    @foreach($permissions as $permission)
                        <span class="badge bg-success">{{$permission->display_name}}</span>
                    @endforeach
                </td>
            </tr>
        </table>

        <div class="row">
            @if(!$user->hasVerifiedEmail())
                <div class="col col-5">
                    <form method="POST" action="{{ route('resend.verification') }}">
                        @csrf
                        <button type="submit" class="btn btn-warning">Resend Verification Email</button>
                    </form>
                </div>
            @endif
            @if(auth()->user()->hasPermissionTo('admin_users')||auth()->id()==$user->id)
                <div class="col col-4">
                    <a class="btn btn-primary" href='{{route('edit_password', $user->id)}}'>Change Password</a>
                </div>
            @endif
            @if(auth()->user()->hasPermissionTo('edit_users')||auth()->id()==$user->id)
                <div class="col col-3">
                    <a href="{{route('users_edit', $user->id)}}" class="btn btn-success form-control">Edit</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
