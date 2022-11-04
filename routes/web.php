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
//    sendMessage([
//        'chat_id'=>config('telegram.payout'),
//        'text'=>"@AliEmami",
//        'reply_to_message_id'=>42
//    ]);
//    \Telegram\Bot\Laravel\Facades\Telegram::forwardMessage([
//        'from_chat_id'=>config('telegram.payout'),
//        'chat_id'=>1389610583,
//        'message_id'=>42
//    ]);
//    add_wallet(8,100000);
    $accounts = \App\Models\Account::all();
    foreach ($accounts as $account){
        $wallet_now = get_wallet($account->id);
        $real_wallet = \App\Models\Wallet::where('account_id',$account->id)->sum('action');
        if ($real_wallet!=$wallet_now){
            dump([$account->id,$wallet_now,$real_wallet,$real_wallet-$wallet_now]);

            \App\Models\Wallet::where('account_id',$account->id)->orderBy('id','desc')->first()->update([
                'balance'=>$real_wallet
            ]);
        }

    }

});
Route::post('/telegram',[\App\Http\Controllers\TelegramController::class,'init']);
Route::get('/wallet/{id}',function ($id){
    return view('WalletAdd');
})->name('wallet');
