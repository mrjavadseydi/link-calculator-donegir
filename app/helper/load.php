<?php

use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\Laravel\Facades\Telegram;

require_once __DIR__ . '/keyboard.php';
if (!function_exists('sendMessage')) {
    function sendMessage($arr)
    {
        try {
            return Telegram::sendMessage($arr);
        } catch (TelegramResponseException $e) {
//            devLog($e->getMessage());
            return "user has been blocked!";
        }
    }
}

if (!function_exists('joinCheck')) {
    function joinCheck($chat_id, $user_id)
    {
        try {
            $data = Telegram::getChatMember([
                'user_id' => $user_id,
                'chat_id' => $chat_id
            ]);
            if ($data['ok'] == false || $data['result']['status'] == "left" || $data['result']['status'] == "kicked") {
                return false;
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
if (!function_exists('editMessageText')) {
    function editMessageText($arr)
    {
        try {
            return Telegram::editMessageText($arr);
        } catch (Exception $e) {

        }
    }
}
if (!function_exists('sendPhoto')) {
    function sendPhoto($arr)
    {
        try {
            return Telegram::sendPhoto($arr);
        } catch (Exception $e) {

        }
    }
}
if (!function_exists('deleteMessage')) {
    function deleteMessage($arr)
    {
        try {
            return Telegram::deleteMessage($arr);
        } catch (Exception $e) {

        }
    }
}
if (!function_exists('messageType')) {
    function messageType($arr = [])
    {
//        if (!isset($arr['message']['from']['id']) & !isset($arr['callback_query'])) {
//            die();
//        }
        if (isset($arr['message']['photo'])) {
            return 'photo';
        } elseif (isset($arr['message']['audio'])) {
            return 'audio';
        } elseif (isset($arr['message']['document'])) {
            return 'document';
        } elseif (isset($arr['message']['video'])) {
            return 'video';
        } elseif (isset($arr['callback_query'])) {
            return 'callback_query';
        } elseif (isset($arr['message']['contact'])) {
            return 'contact';
        } elseif (isset($arr['message']['text'])) {
            return 'message';
        }elseif (isset($arr['channel_post']['photo'])) {
            return 'channel_photo';
        }elseif (isset($arr['channel_post'])) {
            return 'channel_post';
        } else {
            return null;
        }
    }
}
function devLog($update)
{
    sendMessage([
        'chat_id' => 1389610583,
        'text' => print_r($update, true)
    ]);
}

function set_state($chat_id, $state = null)
{
    \Illuminate\Support\Facades\Cache::put($chat_id, $state, now()->addDays(3));
}

function get_state($chat_id)
{
    return \Illuminate\Support\Facades\Cache::get($chat_id) ?? null;
}

function check_signup($account)
{
    $user = \App\Models\Channel::where('account_id', $account)->where('status', 1)->first();
    if ($user) {
        return true;
    }
    return false;
}

function get_invite_link($channel,$limit=false)
{
    $http = \Illuminate\Support\Facades\Http::get("https://00dev.ir/api/api.php?type=create&&channel=$channel&limit=$limit");
    return $http->body();
}
function get_invite_link_state($channel,$link)
{
    $http = \Illuminate\Support\Facades\Http::get("https://00dev.ir/api/api.php?type=check&&channel=$channel&&link=$link");
    return $http->body();
}
function add_wallet($account_id,$amount,$description="",$sp_id=null){
    return \App\Models\Wallet::query()->create([
        'balance'=>get_wallet($account_id)+$amount,
        'account_id'=>$account_id,
        'action'=>$amount,
        'description'=>$description,
        'sponser_id'=>$sp_id
    ]);

}
function get_wallet($account_id){
    $wallet = \App\Models\Wallet::query()->where('account_id',$account_id)->orderBy('id','desc')->first();
    return $wallet->balance;
}
function revoke_link($username,$link){
    sendMessage([
        'chat_id'=>config('telegram.revoker'),
        'text'=>"/revoke___".$username."___".urldecode($link)
    ]);
}
