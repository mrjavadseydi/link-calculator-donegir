<?php
namespace App\Lib\Classes\Signup;
use App\Lib\Interfaces\TelegramOprator;
use App\Models\Channel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SaveChannel extends TelegramOprator
{

    public function initCheck()
    {
//        devLog($this->update);
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
        }elseif(str_starts_with($this->text,'https://t.me/')||(isset($this->update['message']['entities'][0]['type'])&&$this->update['message']['entities'][0]['type']=="mention")){
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
        set_state($this->chat_id,"main");
        if (Channel::where('account_id',$this->user->id)->where('status',1)->first()){
            sendMessage([
                'chat_id' => $this->chat_id,
                'text'=>config('robot.new_channel_added'),
                'reply_markup'=>mainMenu()
            ]);
        }else{
            sendMessage([
                'chat_id' => $this->chat_id,
                'text'=>config('robot.after_add'),
                'reply_markup'=>signup()
            ]);
        }

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
