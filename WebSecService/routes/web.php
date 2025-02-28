<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome'); //welcome.blade.php
});
Route::get('/multable', function (Request $request) {
    $j = $request->number ??2;
    $msg = $request->msg ??'';
    return view('multable', compact('j',"msg")); //multable.blade.php
});
Route::get('/even', function () {
    return view('even'); //even.blade.php
});
Route::get('/prime', function () {
    return view('prime'); //prime.blade.php
});
Route::get('/test', function () {
    return view('test'); //test.blade.php
});
Route::get('/minitest', function () {
    $items = [
        ['item' => 'Apple', 'quantity' => 3, 'price' => 1.00],
        ['item' => 'Banana', 'quantity' => 2, 'price' => 0.50],
        ['item' => 'Orange', 'quantity' => 5, 'price' => 0.80],
    ];
    return view('minitest', ['items' => $items]);
});
Route::get('/transcript', function () {
    $transcripts = [
        ['course' => 'Mathematics', 'course_code' => 'MATH104', 'credit_hours' => 3, 'grade' => 'A'],
        ['course' => 'Chemistry', 'course_code' => 'CHEM302', 'credit_hours' => 3, 'grade' => 'A-'],
        ['course' => 'Physics', 'course_code' => 'PHYS203', 'credit_hours' => 4, 'grade' => 'B+'],

    ];
    return view('transcript', ['transcripts' => $transcripts]);
});
