<?php

namespace App\Lib\Classes\Support;

use App\Lib\Interfaces\TelegramOprator;

class GetSupport extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type == "message" && $this->text == '🚸پشتیبانی');
    }

    public function handel()
    {
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>config('robot.support'),
            'reply_markup'=>backKey()
        ]);
        set_state($this->chat_id,"support");
    }
}
