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
        foreach ($temp_select as $key => $value) {
            $ch = Channel::find($key);

            if ($value) {
                if ($sp = SponserLink::where('sponser_id', $key)->where('channel_id', $value)->first()) {
                    return sendMessage([
                        'chat_id' => $this->chat_id,
                        'text' => "
                    کانال :
                    $ch->name \n
                    لینک تبلیغاتی شما  :\n
                     $sp->link",
                    ]);
                } else {
                    $sponser = Sponser::find(Cache::get('sponser_id' . $this->user->id));
                    $link = get_invite_link($sponser->username);
                    sendMessage([
                        'chat_id' => $this->chat_id,
                        'text' => "
                    کانال :
                    $ch->name \n
                    لینک تبلیغاتی شما  :\n
                     $sp->link",
                    ]);
                    SponserLink::query()->create([
                        'sponser_id' => $sponser->id,
                        'channel_id' => $key,
                        'link' => $link,
                        'usage' => 0
                    ]);
                }

            }


        }
    }
}
