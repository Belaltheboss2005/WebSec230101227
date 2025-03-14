@extends('layouts.master')
@section('title', 'Test Page')
@section('content')
    <script>
        function testsomething() {
            alert("Testing from Java Script");
        }
    </script>
    <div class="card m-4">
        <div class="card-body">
            <button type="button" class="btn btn-primary" onclick="testsomething()">Test Me</button>
        </div>
    </div>
@endsection
