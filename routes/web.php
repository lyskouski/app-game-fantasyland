<?php

use App\Http\Controllers\ForumController;
use App\Http\Controllers\GenericController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::get('/', [LoginController::class, 'index']);
Route::get('/index.php', [LoginController::class, 'index']);
Route::post('/login.php', [LoginController::class, 'login']);
Route::get('/guestlogin.php', [LoginController::class, 'guestLogin']);
Route::get('/registration', function () {
    return view('registry');
});
Route::post('/cgi/register.php', [LoginController::class, 'register']);

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