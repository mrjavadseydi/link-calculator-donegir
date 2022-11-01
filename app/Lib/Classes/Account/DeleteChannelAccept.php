<?php

namespace App\Lib\Classes\Account;

use App\Lib\Interfaces\TelegramOprator;
use App\Models\Channel;

class DeleteChannelAccept extends TelegramOprator
{

    public function initCheck()
    {

        if ($this->message_type == "callback_query") {
            $ex = explode("_", $this->data);
            if ($ex[0] == "dlac") {
                return true;
            }
        }
        return false;
    }

    public function handel()
    {
        $ex = explode("_", $this->data);
        $channel =  Channel::where('id',$ex[1])->delete();
        deleteMessage([
            'chat_id' => $this->chat_id,
            'message_id' => $this->message_id
        ]);
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"حذف شد !",
        ]);

    }
}
