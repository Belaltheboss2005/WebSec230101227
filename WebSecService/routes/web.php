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
