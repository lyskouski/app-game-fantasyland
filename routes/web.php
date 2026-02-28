<?php

use App\Http\Controllers\ForumController;
use App\Http\Controllers\GenericController;
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

// Navigation: Citadel
Route::get('/citadel', function () {
    return view('citadel');
});

// Forum
Route::get('/cgi/forum_rooms.php', [ForumController::class, 'index']);
Route::get('/cgi/forum.php', [ForumController::class, 'room']);
Route::get('/cgi/f_show_thread.php', [ForumController::class, 'topic']);


// All other routes
Route::get('/{any}', [GenericController::class, 'index'])->where('any', '.*');
Route::post('/{any}', [GenericController::class, 'indexPost'])->where('any', '.*');