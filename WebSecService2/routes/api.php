<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;

Route::post('/login', [UsersController::class, 'login']); // Deprecated: Use /oauth/token for authentication

// For authentication, use Laravel Passport's built-in /oauth/token endpoint.
// Example (handled by Passport, no need to define manually):
// Route::post('/oauth/token', [\Laravel\Passport\Http\Controllers\AccessTokenController::class, 'issueToken']);

Route::get('/users', [UsersController::class, 'users'])->middleware('auth:api');
Route::get('/logout', [UsersController::class, 'logout'])->middleware('auth:api');
