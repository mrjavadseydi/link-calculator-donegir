<?php
namespace App\Lib\Classes\Signup;
use App\Lib\Interfaces\TelegramOprator;
use App\Models\Channel;
use Illuminate\Support\Facades\Log;

class SaveChannel extends TelegramOprator
{

    public function initCheck()
    {
        return (get_state($this->chat_id)=="save_channel");
    }

    public function handel()
    {
        if (isset($this->update['message']['forward_from_chat']['id'])) {
            $channel_id = $this->update['message']['forward_from_chat']['id'];
            $channel_username = $this->update['message']['forward_from_chat']['username']??"private";
            $channel_title = $this->update['message']['forward_from_chat']['title'];
            if (Channel::where('chat_id',$channel_id)->exists()){
                return sendMessage([
                    'chat_id'=>$this->chat_id,
                    'text'=>config('robot.duplicate_channel')
                ]);
            }
            Channel::query()->create([
                'account_id'=>$this->user->id,
                'username'=>$channel_username,
                'status'=>0,
                'chat_id'=>$channel_id,
                'name'=>$channel_title,
            ]);
        }elseif(isset($this->update['message']['entities'][0]['type'])&&$this->update['message']['entities'][0]['type']=="mention"){
            if (Channel::where('username',$this->text)->exists()){
                return sendMessage([
                    'chat_id'=>$this->chat_id,
                    'text'=>config('robot.duplicate_channel')
                ]);
            }
            Channel::query()->create([
                'name'=>$this->text,
                'account_id'=>$this->user->id,
                'username'=>$this->text,
                'status'=>0
            ]);
        }else{
            return sendMessage([
                'chat_id' => $this->chat_id,
                'text'=>config('robot.not_fount_channel'),
                'reply_markup'=>backKey()
            ]);
        }
        set_state($this->chat_id,"choose_category");
        return sendMessage([
            'chat_id' => $this->chat_id,
            'text'=>config('robot.chose_category'),
            'reply_markup'=>categoryMenu()
        ]);

    }
}
