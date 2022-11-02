<?php

namespace App\Lib\Classes\Support;

use App\Lib\Interfaces\TelegramOprator;

class ReplySignup extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->chat_id==config('telegram.channel_signup'));
    }

    public function handel()
    {

        if ($this->message_type=="channel_post"&&isset($this->update['channel_post']['reply_to_message']['text'])){
            $reply_text =$this->update['channel_post']['reply_to_message']['text'] ;
            $reply_id = explode("\n",$reply_text)[0];
            sendMessage([
                'chat_id' => $reply_id,
                'text' => $this->text,
            ]);

        }

    }
}
