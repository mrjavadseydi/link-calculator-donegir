<?php
namespace App\Lib\Classes\Signup;
use App\Lib\Interfaces\TelegramOprator;
use App\Models\Channel;

class ChooseCategory extends TelegramOprator
{

    public function initCheck()
    {
        return ($this->message_type=="message"&&get_state($this->chat_id)=="choose_category");
    }

    public function handel()
    {
        Channel::where('account_id',$this->user->id)->update([
            'category'=>$this->text
        ]);
        set_state($this->chat_id,"main");
        sendMessage([
            'chat_id' => $this->chat_id,
            'text'=>config('robot.after_add'),
            'reply_markup'=>signup()
        ]);
    }
}
