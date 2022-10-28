<?php
namespace App\Lib\Classes\Admin;
use App\Jobs\SendMessageJob;
use App\Lib\Interfaces\TelegramOprator;
use App\Models\Account;
use App\Models\Channel;
use App\Models\Sponser;
use App\Models\SponserLink;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class StatusSponser extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type=="channel_post"&&$this->chat_id==config('telegram.sponsers')&&$this->text=="/status");
    }

    public function handel()
    {

        $sponser = Sponser::query()->where('msg_id',$this->reply_to_message)->first();
        $links = SponserLink::query()->where('sponser_id',$sponser->id)->get();
        $str = "تبلیغ : $sponser->name\n";
        foreach ($links as $link){
            $str .= "کانال : ".Channel::find($link->channel_id)->username."\n";
            $str .= "تعداد  : $link->usage \n";
            $str .= "\n ======== \n";
        }
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>$str
        ]);

    }
}
