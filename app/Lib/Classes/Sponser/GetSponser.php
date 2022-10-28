<?php
namespace App\Lib\Classes\Sponser;
use App\Lib\Interfaces\TelegramOprator;
use Illuminate\Support\Facades\Cache;

class GetSponser extends TelegramOprator
{

    public function initCheck()
    {
        if ($this->message_type == "callback_query") {
            $ex = explode("_", $this->data);
            if ($ex[0] == "spselect") {
                return true;
            }
        }
        return false;
    }

    public function handel()
    {
        set_state($this->chat_id,"get_sponser");
        $ex = explode("_", $this->data);
        deleteMessage([
            'chat_id' => $this->chat_id,
            'message_id' => $this->message_id
        ]);
        Cache::forget('active_select' . $this->user->id);
        Cache::put('sponser_id'.$this->user->id,$ex[1],600);
        sendMessage([
            'chat_id' => $this->chat_id,
            'text'=>config('robot.sponser_list'),
            'reply_markup'=>choose_channel($this->user)
        ]);
    }
}
