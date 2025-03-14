<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Authenticate
{
    protected function redirectTo(Request $request)
    {
        if (!$request->expectsJson()) {
            return route('login'); // Redirect to login if not authenticated
        }
    }
}
