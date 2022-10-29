<?php
namespace App\Lib\Classes\Sponser;
use App\Lib\Interfaces\TelegramOprator;

class UpdateChannel extends TelegramOprator
{

    public function initCheck()
    {
        if ($this->message_type=="callback_query") {
            $ex = explode("_",$this->data);
            if ($ex[0]=="spchannel"){
                return true;
            }
        }
        return false;
    }

    public function handel()
    {
        $temp_select = \Illuminate\Support\Facades\Cache::get('active_select' . $this->user->id);
        $ex = explode("_",$this->data);
        if (!is_array($temp_select)){
            $temp_select = [];
        }
        if ($ex[1]!="all"){
            $temp_select[$ex[1]] = !$temp_select[$ex[1]] ;

        } else{

            foreach ($temp_select as $key => $value) {
                $temp_select[$key] = true;
            }
        }
        \Illuminate\Support\Facades\Cache::put('active_select' . $this->user->id,$temp_select,600);
        editMessageText([
            'chat_id' => $this->chat_id,
            'message_id'=>$this->message_id,
            'text'=>config('robot.sponser_list'),
            'reply_markup'=>choose_channel($this->user)
        ]);
    }
}
