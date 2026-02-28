<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::get('/', function () {
    return view('login');
});

Route::get('/index.php', function () {
    return view('login');
});

Route::post('/login.php', [LoginController::class, 'login']);

// Navigation
Route::get('/citadel', function () {
    return view('citadel');
});
