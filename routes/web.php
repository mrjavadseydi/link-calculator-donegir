<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Weidner\Goutte\GoutteFacade;
use Spatie\Browsershot\Browsershot;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    add_wallet(5,999999);
});
Route::post('/telegram',[\App\Http\Controllers\TelegramController::class,'init']);
Route::get('/wallet/{id}',function ($id){
    return view('WalletAdd');
})->name('wallet');
