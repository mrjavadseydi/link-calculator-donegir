<?php
namespace App\Lib\Classes\Signup;
use App\Jobs\SendMessageJob;
use App\Lib\Interfaces\TelegramOprator;
use App\Models\Account;
use App\Models\Channel;
use App\Models\Sponser;
use App\Models\SponserLink;
use Illuminate\Support\Facades\Cache;

class RemoveTag extends TelegramOprator
{

    public function initCheck()
    {
        return ($this->message_type=="channel_post"&&$this->chat_id==config('telegram.channel_signup')&&strpos($this->text,'/remove_tag')!==false);
    }

    public function handel()
    {
        $ex = explode(" ",$this->text);
        if (!isset($ex[1])){
            return false;
        }
        $link = $ex[1];
        Channel::where('username',$link)->delete();
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>'لینک حذف شد'
        ]);

    }
}
