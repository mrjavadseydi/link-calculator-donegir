<?php

namespace App\Lib\Classes\Sponser;

use App\Lib\Interfaces\TelegramOprator;
use App\Models\Channel;
use App\Models\Sponser;
use App\Models\SponserLink;
use Illuminate\Support\Facades\Cache;

class GetLink extends TelegramOprator
{

    public function initCheck()
    {
        if ($this->message_type == "callback_query") {
            $ex = explode("_", $this->data);
            if ($ex[0] == "sponser") {
                return true;
            }
        }
        return false;
    }

    public function handel()
    {
        $temp_select = \Illuminate\Support\Facades\Cache::get('active_select' . $this->user->id);
        deleteMessage([
            'chat_id' => $this->chat_id,
            'message_id' => $this->message_id
        ]);
        if (!is_array($temp_select)){
            $temp_select = [];
        }
        $new = false;
        $links= [];
        foreach ($temp_select as $key => $value) {
            $ch = Channel::find($key);

            if ($value) {
                if ($sp = SponserLink::where('channel_id', $key)->where('sponser_id', Cache::get('sponser_id'.$this->user->id))->first()) {
                    $links[] = $sp->link;
                     sendMessage([
                        'chat_id' => $this->chat_id,
                        'text' => "
                    کانال :
$ch->name \n
                    لینک تبلیغاتی شما  :\n
$sp->link",
                    ]);
                     continue;
                } else {
                    $new = true;
                    $sponser = Sponser::find(Cache::get('sponser_id' . $this->user->id));
                    $link = get_invite_link($sponser->username);
                    if(empty($link)||$link==" " || $link == ''){
                        return sendMessage([
                            'chat_id' => $this->chat_id,
                            'text'=>'مشکلی در دریافت لینک بوجود آمده است لطفا دوباره تلاش کنید',
                        ]);
                    }
                    sendMessage([
                        'chat_id' => $this->chat_id,
                        'text' => "
                    کانال :
$ch->name \n
                    لینک تبلیغاتی شما  :\n
$link",
                    ]);
                    $links[] = $link;
                    SponserLink::query()->create([
                        'sponser_id' => $sponser->id,
                        'channel_id' => $key,
                        'link' => $link,
                        'usage' => 0
                    ]);


                }

            }
        }
        try {
            if (count($links) == 0) {
                return 0 ;
            }
            $user_link = '<a href="tg://user?id=' . $this->chat_id . '">' . $this->chat_id . '</a>';
            $text = "لینک دریافتی  :   \n";
            foreach ($links as $link) {
                $text .= $link . "\n";
            }
            $text .= "کاربر : $user_link \n";
            $text.=" اسم حساب بانکی کاربر : {$this->user->name} \n";
            $text.=" اسم کاربر : {$this->user->account_name} \n";
            $us = '@'.$this->user->username;
            $text.=" یوزرنیم  کاربر : $us \n";
            $text .= "کانال های کاربر  :  \n";
            foreach ($this->user->channels as $channel) {
                if ($channel->status == 1) {
                    $text .= $channel->username . "\n";
                }
            }
            if (isset($sponser)){
                $text .= "تبلیغ  : $sponser->name \n";

            }elseif(isset($sp)){
                $text .= "تبلیغ  : {$sp->sponser->name} \n";
            }
            $sm = sendMessage([
                'chat_id' => config('telegram.done'),
                'text' => $text,
                'parse_mode' => 'HTML'
            ]);
        }catch (\Exception $e){}

    }
}
