<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('/index.php', function () {
    return view('login');
});

Route::post('/login.php', [LoginController::class, 'login']);
