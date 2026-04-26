<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\CitadelController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\GenericController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PreyController;
use App\Http\Controllers\StoreController;
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
Route::get('/citadel', [CitadelController::class, 'index']);

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
Route::get('/ch/chout.php', [ChatController::class, 'messages']);
Route::get('/chat/clear', [ChatController::class, 'clear']);

// Mining
Route::get('/cgi/work_stop.php', [PreyController::class, 'stop']);
Route::get('/cgi/work_start.php', [PreyController::class, 'start']);
Route::post('/cgi/work_start.php', [PreyController::class, 'run']);
Route::get('/cgi/craft_favorite_ref.php', [PreyController::class, 'favorite']);

// Personal info
Route::get('/cgi/show_info.php', [InfoController::class, 'index']);
Route::post('/cgi/change_info.php', [InfoController::class, 'indexPost']);
Route::post('/cgi/add_um_effect.php', [InfoController::class, 'addUmEffect']);
// Personal info: Mail
Route::get('/cgi/msgs_read.php', [InfoController::class, 'mailIncome']);
Route::get('/cgi/letters_read.php', [InfoController::class, 'mailOutcome']);
Route::any('/cgi/send_letter.php', [InfoController::class, 'mail']);
Route::get('/cgi/msgs_del.php', [InfoController::class, 'deleteMessage']);
Route::post('/cgi/pl_notebook.php', [InfoController::class, 'notePost']);
// Personal info: History
//Route::get('/cgi/deal_info.php', [InfoController::class, 'dealInfo']);
//Route::get('/cgi/combats_info.php', [InfoController::class, 'combatsInfo']);
//Route::get('/cgi/last_visits.php', [InfoController::class, 'lastVisits']);
//Route::get('/cgi/last_punishments.php', [InfoController::class, 'lastPunishments']);
//Route::get('/cgi/deal_arend.php', [InfoController::class, 'dealArend']);
// Personal info: Army
Route::get('/cgi/army_needcombat_ref.php', [InfoController::class, 'armySelection']);
// Personal info: Stuff
Route::get('/cgi/inv_unwear.php', [InfoController::class, 'unwear']);
Route::get('/cgi/inv_wear.php', [InfoController::class, 'wear']);
Route::get('/cgi/inv_load_items.php', [InfoController::class, 'loadItems']);
Route::get('/cgi/set_wear.php', [InfoController::class, 'setWear']);
Route::get('/cgi/set_save.php', [InfoController::class, 'setSave']);
Route::get('/cgi/scrolls_set_save.php', [InfoController::class, 'scrollsSetSave']);
Route::get('/cgi/scrolls_set_wear.php', [InfoController::class, 'scrollsSetWear']);

// Labyrinth
Route::get('/labyrinth', [LabController::class, 'index']);
Route::post('/labyrinth/save', [LabController::class, 'save']);
Route::get('/labyrinth/config', [LabController::class, 'config']);
Route::get('/labyrinth/clear', [LabController::class, 'clear']);
Route::get('/labyrinth/sync', [LabController::class, 'sync']);
Route::post('/labyrinth/citadel/save', [LabController::class, 'saveToCitadel']);
Route::get('/labyrinth/citadel/init', [LabController::class, 'initToCitadel']);
Route::get('/cgi/inv_wear', [MainController::class, 'wear']);
Route::get('/cgi/maze_move.php', [LabController::class, 'move']);
//Route::get('/cgi/maze_ref.php', [LabController::class, 'ref']);
Route::get('/cgi/maze_qaction.php', [LabController::class, 'questAction']);
Route::get('/cgi/mc_main.php', [LabController::class, 'questMain']);
Route::get('/cgi/mc_hid.php', [LabController::class, 'questReply']);
Route::post('/cgi/mc_hid.php', [LabController::class, 'questReply']);
Route::get('/cgi/technical_lab_info.php', [LabController::class, 'technicalInfo']);
Route::get('/cgi/maze_pickup.php', [LabController::class, 'pickUp']);

// Marketplace (tents)
//Route::get('/cgi/v_trade_load_shop.php', [StoreController::class, 'buyList']);
//Route::get('/cgi/v_trade_show_goods_for_sale.php', [StoreController::class, 'saleList']);
Route::post('/cgi/buy.php', [StoreController::class, 'buyItem']);
Route::post('/cgi/sell_good_to_shop.php', [StoreController::class, 'sellItem']);
Route::get('/cgi/v_trade_load_shop.php', [StoreController::class, 'tent']);
Route::get('/cgi/v_trade_show_shops.php', [StoreController::class, 'showTents']);
Route::get('/store/price', [StoreController::class, 'priceJson']);
Route::get('/cgi/v_trade_search.php', [StoreController::class, 'search']);

// All other routes
Route::get('/{any}', [GenericController::class, 'index'])->where('any', '.*');
Route::post('/{any}', [GenericController::class, 'indexPost'])->where('any', '.*');
