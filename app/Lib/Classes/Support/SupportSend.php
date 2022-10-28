<?php

namespace App\Lib\Classes\Support;

use App\Lib\Interfaces\TelegramOprator;

class SupportSend extends TelegramOprator
{

    public function initCheck()
    {

        return (get_state($this->chat_id)=="support");
    }

    public function handel()
    {
        if ($this->message_type=="message"){

            $text = $this->chat_id . "^^__^^\n" . $this->text;
            sendMessage([
                'chat_id' => config('telegram.support'),
                'text' => $text,
            ]);
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"ارسال شد",
                'reply_markup'=>backKey()
            ]);
        }else{
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"لطفا فقط متن ارسال کنید"
            ]);
        }

    }
}
