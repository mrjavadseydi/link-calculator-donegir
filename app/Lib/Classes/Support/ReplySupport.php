<?php

namespace App\Lib\Classes\Support;

use App\Lib\Interfaces\TelegramOprator;

class ReplySupport extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->chat_id==config('telegram.support'));
    }

    public function handel()
    {
        if ($this->message_type=="message"&&isset($this->update['message']['reply_to_message']['text'])){
            $reply_text =$this->update['message']['reply_to_message']['text'] ;
            $reply_id = explode("^^__^^",$reply_text)[0];
            sendMessage([
                'chat_id' => $reply_id,
                'text' => $this->text,
            ]);

        }

    }
}
