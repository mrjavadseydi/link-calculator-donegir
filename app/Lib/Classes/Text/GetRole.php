<?php

namespace App\Lib\Classes\Text;

use App\Lib\Interfaces\TelegramOprator;

class GetRole extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type == "message" && $this->text == "🛂قوانین و مقررات");
    }

    public function handel()
    {
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>config('robot.role')
        ]);

    }
}
