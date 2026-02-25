<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('/index.php', function () {
    return view('login');
});

Route::get('/login.php', function () {
    return view('main');
});