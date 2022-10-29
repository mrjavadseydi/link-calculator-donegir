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

        return ($this->message_type=="channel_post"&&$this->chat_id==config('telegram.sponsers')&&$this->text=="/detail");
    }

    public function handel()
    {

        $sponser = Sponser::query()->where('msg_id',$this->reply_to_message)->first();
        if (!$sponser){
            return false;
        }
        $links = SponserLink::query()->where('sponser_id',$sponser->id)->orderBy('usage','desc')->get();
        $str = "تبلیغ : $sponser->name\n";
        foreach ($links as $link){
            if (!Channel::find($link->channel_id)){
                continue;
            }
            $str .= "نام کانال : ".Channel::find($link->channel_id)->name."\n";
            $str .= "یوزر نیم کانال : ".Channel::find($link->channel_id)->username."\n";
            $str .= "تعداد  : $link->usage \n";
            $str .= "دستور جایزه  : ";
            $str .= "/reward_$link->id".'_mablagh'."\n";
            $str .= "\n ======== \n";
            if (strlen($str)>3000){
                sendMessage([
                    'chat_id'=>$this->chat_id,
                    'text'=>$str
                ]);
                $str = "";
            }
        }
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>$str
        ]);

    }
}
