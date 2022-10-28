<?php

namespace App\Lib\Classes\Account;

use App\Lib\Interfaces\TelegramOprator;

class MyAccount extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type == "message" && $this->text == '👤 حساب کاربری من');
    }

    public function handel()
    {
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>config('robot.my_account')
        ]);

    }
}
