<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\GenericController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PreyController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::get('/', [LoginController::class, 'index']);
Route::get('/index.php', [LoginController::class, 'index']);
Route::post('/login.php', [LoginController::class, 'login']);
Route::get('/guestlogin.php', [LoginController::class, 'guestLogin']);
Route::get('/registration', [LoginController::class, 'indexRegister']);
Route::post('/cgi/register.php', [LoginController::class, 'register']);
Route::get('/rules.php', [LoginController::class, 'rules']);

// Navigation: Citadel
Route::get('/citadel', function () {
    return view('citadel');
});

// Forum
Route::get('/cgi/forum_rooms.php', [ForumController::class, 'index']);
Route::get('/cgi/forum.php', [ForumController::class, 'room']);
Route::post('/cgi/forum.php', [ForumController::class, 'room']);
Route::get('/cgi/f_show_thread.php', [ForumController::class, 'topic']);
Route::post('/cgi/f_show_thread.php', [ForumController::class, 'topicPost']);

// Main page
Route::get('/cgi/no_combat.php', [MainController::class, 'index']);
Route::post('/cgi/no_combat.php', [MainController::class, 'index']);
Route::get('/cgi/no_travel.php', [MainController::class, 'index']);
Route::get('/cgi/map.php', [MainController::class, 'map']);
Route::post('/cgi/travel_start.php', [MainController::class, 'map']);
Route::get('/cgi/travel_stop.php', [MainController::class, 'mapStop']);

// Chat
Route::get('/cgi/ch_ref.php', [ChatController::class, 'index']);

// Mining
Route::get('/cgi/work_stop.php', [PreyController::class, 'stop']);
Route::get('/cgi/work_start.php', [PreyController::class, 'start']);
Route::post('/cgi/work_start.php', [PreyController::class, 'run']);
Route::get('/cgi/craft_favorite_ref.php', [PreyController::class, 'favorite']);

// Personal info
Route::get('/cgi/show_info.php', [InfoController::class, 'index']);
Route::post('/cgi/change_info.php', [InfoController::class, 'indexPost']);

// All other routes
Route::get('/{any}', [GenericController::class, 'index'])->where('any', '.*');
Route::post('/{any}', [GenericController::class, 'indexPost'])->where('any', '.*');
