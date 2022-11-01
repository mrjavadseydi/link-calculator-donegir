<?php
namespace App\Lib\Classes;
use App\Lib\Interfaces\TelegramOprator;

class Start extends TelegramOprator
{

    public function initCheck()
    {
        return ($this->message_type=="message"&&($this->text=="/start"||$this->text=='بازگشت ↪️'));
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
                'text'=>'با لمس کردن دکمه چهارخانه، کیبورد داخلی باز خواهد شد و شما به راحتی به قسمت های مختلف ربات دسترسی خواهید داشت.',
                'reply_markup'=>mainMenu()
            ]);
        }
    }
}
