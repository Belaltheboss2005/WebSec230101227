<?php

use App\Http\Controllers\Web\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users2Controller;

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

Route::get('products', [ProductsController::class, 'list'])->name('products_list');
Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');

Route::get('/calculator', function () {
    return view('calculator');
});

Route::get('/products2', function () {
    $products = [
        [
            'name' => 'Laptop',
            'image' => 'https://via.placeholder.com/150',
            'price' => 15000,
            'description' => 'A high-performance laptop for all your needs.'
        ],
        [
            'name' => 'Smartphone',
            'image' => 'https://via.placeholder.com/150',
            'price' => 8000,
            'description' => 'A sleek smartphone with the latest features.'
        ],
        [
            'name' => 'Headphones',
            'image' => 'https://via.placeholder.com/150',
            'price' => 2000,
            'description' => 'Noise-canceling headphones for a great experience.'
        ]
    ];

    return view('products2', compact('products'));
});

Route::prefix('users2')->group(function () {
    Route::get('/users2', [Users2Controller::class, 'index'])->name('users2.index');
    Route::get('/', [Users2Controller::class, 'index'])->name('users2.index');
    Route::get('/create', [Users2Controller::class, 'create'])->name('users2.create');
    Route::post('/store', [Users2Controller::class, 'store'])->name('users2.store');
    Route::get('/edit/{id}', [Users2Controller::class, 'edit'])->name('users2.edit');
    Route::post('/update/{id}', [Users2Controller::class, 'update'])->name('users2.update');
    Route::get('/delete/{id}', [Users2Controller::class, 'destroy'])->name('users2.delete');


});
