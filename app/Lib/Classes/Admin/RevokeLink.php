<?php
namespace App\Lib\Classes\Admin;
use App\Jobs\SendMessageJob;
use App\Lib\Interfaces\TelegramOprator;
use App\Models\Account;
use App\Models\Channel;
use App\Models\Sponser;
use App\Models\SponserLink;
use Illuminate\Support\Facades\Cache;

class RevokeLink extends TelegramOprator
{

    public function initCheck()
    {
        return ($this->message_type=="channel_post"&&$this->chat_id==config('telegram.sponsers')&&strpos($this->text,'/revoke')!==false);
    }

    public function handel()
    {
        $ex = explode(" ",$this->text);
        $link = $ex[1];
        $sponser = SponserLink::query()->where('link',$link)->first();
        if (!$sponser){
            return false;
        }
        $channel = $sponser->sponser->username;
        \Illuminate\Support\Facades\Http::get("https://00dev.ir/api/api.php?type=check&&channel=$channel&&link=$link");
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>'لینک غیرفعال شد'
        ]);

    }
}
