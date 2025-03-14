@extends('layouts.master')
@section('title', 'Student Transcript')
@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Student Transcript</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Course</th>
                    <th>Course Code</th>
                    <th>Credit Hours</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transcripts as $transcript)
                    <tr>
                        <td>{{ $transcript['course'] }}</td>
                        <td>{{ $transcript['course_code'] }}</td>
                        <td>{{ $transcript['credit_hours'] }}</td>
                        <td>{{ $transcript['grade'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
