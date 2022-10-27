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
        foreach (Channel::where('status',0)->where('account_id',$this->user->id)->get() as $channel){
            $text = config('robot.new_channel');
            $text = str_replace('%username',$channel->username,$text);
            $text = str_replace('%name',$channel->name,$text);
            $text = str_replace('%category',$channel->category,$text);
            $text = str_replace('%id',$channel->chat_id,$text);
            $text = str_replace('%user',$this->chat_id,$text);

            sendMessage([
                'chat_id' => config('telegram.channel_signup'),
                'text'=>$text,
                'reply_markup'=>accept_channel($channel->id)
            ]);
        }
    }
}
