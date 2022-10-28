<?php

namespace App\Lib\Classes\Text;

use App\Lib\Interfaces\TelegramOprator;

class GetHelp extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type == "message" && $this->text == '🤔 راهنما');
    }

    public function handel()
    {
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>config('robot.help')
        ]);

    }
}
