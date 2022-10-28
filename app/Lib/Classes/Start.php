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
        set_state($this->chat_id,"main");
        sendMessage([
            'chat_id' => $this->chat_id,
            'text'=>'start!',
            'reply_markup'=>mainMenu()
        ]);
    }
}
