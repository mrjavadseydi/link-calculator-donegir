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
            $chat_id = Channel::where('id',$ex[1])->first()->account->chat_id;
            sendMessage([
                'chat_id' =>$chat_id,
                'text'=>config('robot.decline_channel'),
            ]);
            $this->text = str_replace($chat_id, '<a href="tg://user?id=' .$chat_id . '">' . $chat_id . '</a>',$this->text);
            editMessageText([
                'chat_id' => config('telegram.channel_signup'),
                'message_id' => $this->message_id,
                'text'=>$this->text."\n رد شد ",
                'parse_mode' => 'HTML'
            ]);
        }else{
            Channel::where('id',$ex[1])->update([
                'status'=>1
            ]);
            $chat_id = Channel::where('id',$ex[1])->first()->account->chat_id;

            $this->text = str_replace($chat_id, '<a href="tg://user?id=' .$chat_id . '">' . $chat_id . '</a>',$this->text);

            sendMessage([
                'chat_id' => $chat_id,
                'text'=>config('robot.accept_channel'),
                'reply_markup'=>mainMenu()
            ]);
            editMessageText([
                'chat_id' => config('telegram.channel_signup'),
                'message_id' => $this->message_id,
                'text'=>$this->text."\n تایید شد ",
                'parse_mode' => 'HTML'
            ]);
        }
    }
}
