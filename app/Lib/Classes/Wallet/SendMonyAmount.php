<?php

namespace App\Lib\Classes\Wallet;

use App\Lib\Interfaces\TelegramOprator;
use App\Models\Channel;
use App\Models\Sponser;
use App\Models\SponserLink;
use Illuminate\Support\Facades\Cache;

class SendMonyAmount extends TelegramOprator
{

    public function initCheck()
    {
        if ($this->message_type == "callback_query") {
            $ex = explode("_", $this->data);
            if ($ex[0] == "getwallet") {
                return true;
            }
        }
        return false;
    }

    public function handel()
    {
        deleteMessage([
            'chat_id' => $this->chat_id,
            'message_id' => $this->message_id
        ]);
       sendMessage([
           'chat_id'=>$this->chat_id,
           'text'=>config('robot.receive_wallet'),
           'reply_markup'=>backKey()
       ]);
       set_state($this->chat_id,'receive_wallet');
    }
}
