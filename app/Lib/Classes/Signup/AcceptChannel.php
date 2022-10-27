<?php
namespace App\Lib\Classes\Signup;
use App\Lib\Interfaces\TelegramOprator;
use App\Models\Channel;

class AcceptChannel extends TelegramOprator
{

    public function initCheck()
    {
        if ($this->message_type=="callback_query") {
           $ex = explode("_",$this->data);
           if ($ex[0]=="ac"||$ex[0]=="de"){
               return true;
           }
        }
        return false;
    }

    public function handel()
    {
        $ex = explode("_",$this->data);

        if ($ex[0]=="de"){
            Channel::where('id',$ex[1])->update([
                'status'=>2
            ]);
            sendMessage([
                'chat_id' =>Channel::where('id',$ex[1])->first()->account->chat_id,
                'text'=>config('robot.decline_channel'),
            ]);
            editMessageText([
                'chat_id' => config('telegram.channel_signup'),
                'message_id' => $this->message_id,
                'text'=>$this->text."\n رد شد ",
            ]);
        }else{
            Channel::where('id',$ex[1])->update([
                'status'=>1
            ]);
            sendMessage([
                'chat_id' => Channel::where('id',$ex[1])->first()->account->chat_id,
                'text'=>config('robot.accept_channel'),
                'reply_markup'=>mainMenu()
            ]);
            editMessageText([
                'chat_id' => config('telegram.channel_signup'),
                'message_id' => $this->message_id,
                'text'=>$this->text."\n تایید شد ",
            ]);
        }
    }
}
