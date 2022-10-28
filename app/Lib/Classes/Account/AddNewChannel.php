<?php

namespace App\Lib\Classes\Account;

use App\Lib\Interfaces\TelegramOprator;

class AddNewChannel extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type == "message" && $this->text == '🔸افزودن کانال🔸');
    }

    public function handel()
    {
        set_state($this->chat_id,"save_channel");
        sendMessage([
            'chat_id' => $this->chat_id,
            'text'=>config('robot.forward_channel'),
            'reply_markup'=>backKey()
        ]);
    }
}
