<?php
namespace App\Lib\Classes;
use App\Lib\Interfaces\TelegramOprator;

class Back extends TelegramOprator
{

    public function initCheck()
    {
        return ($this->message_type=="message"&&($this->text=='بازگشت ↪️'));
    }

    public function handel()
    {
        if (!check_signup($this->user->id)){
            set_state($this->chat_id,"forward_channel");
            sendMessage([
                'chat_id' => $this->chat_id,
                'text'=>config('robot.signup'),
                'reply_markup'=>signup()
            ]);
        }else{
            set_state($this->chat_id,"main");
            sendMessage([
                'chat_id' => $this->chat_id,
                'text'=>'start!',
                'reply_markup'=>mainMenu()
            ]);
        }

    }
}
