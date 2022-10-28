<?php

namespace App\Lib\Classes\Account;

use App\Lib\Interfaces\TelegramOprator;

class ManageChannels extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type == "message" && $this->text == '🔸مدیریت کانال🔸');
    }

    public function handel()
    {
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>config('robot.my_account'),
            'reply_markup'=>myChannels($this->user->id)
        ]);

    }
}
