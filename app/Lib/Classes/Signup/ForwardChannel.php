<?php
namespace App\Lib\Classes\Signup;
use App\Lib\Interfaces\TelegramOprator;

class ForwardChannel extends TelegramOprator
{

    public function initCheck()
    {
        return ($this->message_type=="message"&&$this->text=="👤 ثبت نام"&&get_state($this->chat_id)=="forward_channel");
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
